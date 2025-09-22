<?php

namespace App\Http\Controllers;

use App\Models\POHeader;
use App\Models\Inbound;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get date range for filtering (default to current month)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        // Get orphan POs (POs not linked to any inbound)
        $orphanPOs = $this->getOrphanPOs($user);
        
        // Get progressive inbound list grouped by status
        $inboundsByStatus = $this->getInboundsByStatus($user, $startDate, $endDate);
        
        // Get graph data for operational stages
        $operationalData = $this->getOperationalGraphData($user, $startDate, $endDate);
        
        // Get summary statistics
        $statistics = $this->getDashboardStatistics($user, $startDate, $endDate);
        
        // Debug logging
        Log::info('Dashboard Data Debug', [
            'user_id' => $user->id,
            'date_range' => [$startDate, $endDate],
            'operational_data_sum' => array_sum($operationalData['data']),
            'inbounds_count' => count($inboundsByStatus)
        ]);
        
        // Prepare status colors
        $statusColors = [
            'inbound' => 'secondary',
            'booking' => 'primary', 
            'shipping' => 'info',
            'document' => 'warning',
            'clearance' => 'danger',
            'delivery' => 'success',
            'bank' => 'dark',
            'complete' => 'success'
        ];
        
        return view('dashboard.index', [
            'orphanPOs' => $orphanPOs,
            'inboundsByStatus' => $inboundsByStatus,
            'operationalData' => $operationalData,
            'statistics' => $statistics,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'user' => $user,
            'statusColors' => $statusColors
        ]);
    }
    
    private function getOrphanPOs($user)
    {
        $query = POHeader::where('status', 'Open')
            ->whereDoesntHave('inbounds');
            
        // Filter by user group if not super admin
        if (is_null($user->is_super_admin)) {
            $query->where('person_in_charge_id', $user->id);
        }
        
        return $query->with(['pic', 'supplier'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    private function getInboundsByStatus($user, $startDate, $endDate)
    {
        // Convert dates to Carbon instances with proper time ranges
        $startDateTime = Carbon::parse($startDate)->startOfDay();
        $endDateTime = Carbon::parse($endDate)->endOfDay();
        
        $query = Inbound::with(['po_header.pic', 'booking', 'shipping', 'clearance', 'delivery'])
            ->whereBetween('created_at', [$startDateTime, $endDateTime]);
            
        // Filter by user group if not super admin
        if (is_null($user->is_super_admin)) {
            $query->whereHas('po_header', function($q) use($user) {
                $q->where('person_in_charge_id', $user->id);
            });
        }
        
        $inbounds = $query->get();
        
        // Group inbounds by their current status
        $groupedInbounds = [
            'inbound' => [],
            'booking' => [],
            'shipping' => [],
            'document' => [],
            'clearance' => [],
            'delivery' => [],
            'bank' => [],
            'complete' => []
        ];
        
        foreach ($inbounds as $inbound) {
            $inbound->calcCurrentStep();
            $status = $inbound->getCurrentStatus();
            
            if (isset($groupedInbounds[$status])) {
                $groupedInbounds[$status][] = $inbound;
            }
        }
        
        // Convert arrays to collections for easier manipulation in views
        foreach ($groupedInbounds as $status => $inboundArray) {
            $groupedInbounds[$status] = collect($inboundArray);
        }
        
        return $groupedInbounds;
    }
    
    private function getOperationalGraphData($user, $startDate, $endDate)
    {
        // Convert dates to Carbon instances with proper time ranges
        $startDateTime = Carbon::parse($startDate)->startOfDay();
        $endDateTime = Carbon::parse($endDate)->endOfDay();
        
        $query = Inbound::with(['booking', 'shipping', 'clearance', 'delivery'])
            ->whereBetween('created_at', [$startDateTime, $endDateTime]);
        
        // Filter by user group if not super admin
        if (is_null($user->is_super_admin)) {
            $query->whereHas('po_header', function($q) use($user) {
                $q->where('person_in_charge_id', $user->id);
            });
        }
        
        $inbounds = $query->get();
        
        $operationalData = [
            'labels' => ['Inbound Details', 'Booking', 'Shipping', 'Documents', 'Clearance', 'Delivery', 'Bank', 'Complete'],
            'data' => [0, 0, 0, 0, 0, 0, 0, 0],
            'colors' => ['#6c757d', '#007bff', '#28a745', '#ffc107', '#fd7e14', '#dc3545', '#6f42c1', '#20c997']
        ];
        
        foreach ($inbounds as $inbound) {
            $inbound->calcCurrentStep();
            $stepIndex = $inbound->currentStep - 1; // Convert to 0-based index
            
            // Ensure the step index is valid
            if ($stepIndex >= 0 && $stepIndex < 8) {
                $operationalData['data'][$stepIndex]++;
            }
        }
        
        return $operationalData;
    }
    
    private function getDashboardStatistics($user, $startDate, $endDate)
    {
        // Convert dates to Carbon instances with proper time ranges
        $startDateTime = Carbon::parse($startDate)->startOfDay();
        $endDateTime = Carbon::parse($endDate)->endOfDay();
        
        $baseQuery = Inbound::whereBetween('created_at', [$startDateTime, $endDateTime]);
        
        // Filter by user group if not super admin
        if (is_null($user->is_super_admin)) {
            $baseQuery->whereHas('po_header', function($q) use($user) {
                $q->where('person_in_charge_id', $user->id);
            });
        }
        
        $totalInbounds = $baseQuery->count();
        $completedInbounds = (clone $baseQuery)->whereHas('delivery', function($q) {
            $q->whereNotNull('atco_date');
        })->count();
        
        $inTransit = (clone $baseQuery)->whereHas('booking', function($q) {
            $q->whereNotNull('ats')->whereNull('ata');
        })->count();
        
        $delayed = (clone $baseQuery)->whereHas('booking', function($q) {
            $q->where('eta', '<', Carbon::now())->whereNull('ata');
        })->count();
        
        return [
            'total_inbounds' => $totalInbounds,
            'completed_inbounds' => $completedInbounds,
            'in_transit' => $inTransit,
            'delayed' => $delayed,
            'completion_rate' => $totalInbounds > 0 ? round(($completedInbounds / $totalInbounds) * 100, 1) : 0,
            'in_transit_percent' => $totalInbounds > 0 ? round(($inTransit / $totalInbounds) * 100, 1) : 0,
            'delayed_percent' => $totalInbounds > 0 ? round(($delayed / $totalInbounds) * 100, 1) : 0
        ];
    }
    
    public function refreshData(Request $request)
    {
        try {
            // AJAX endpoint for auto-refresh functionality
            $user = Auth::user();
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
            
            // Validate date format and ensure end date is not before start date
            try {
                $startDateTime = Carbon::parse($startDate);
                $endDateTime = Carbon::parse($endDate);
                
                if ($endDateTime->lt($startDateTime)) {
                    return response()->json([
                        'error' => 'End date cannot be before start date',
                        'success' => false
                    ], 400);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Invalid date format',
                    'success' => false
                ], 400);
            }
            
            // Log for debugging
            Log::info('Dashboard refresh requested', [
                'user_id' => $user->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'parsed_start' => $startDateTime->toDateTimeString(),
                'parsed_end' => $endDateTime->toDateTimeString()
            ]);
            
            $statistics = $this->getDashboardStatistics($user, $startDate, $endDate);
            $operationalData = $this->getOperationalGraphData($user, $startDate, $endDate);
            $inboundsByStatus = $this->getInboundsByStatus($user, $startDate, $endDate);
            
            // Prepare status colors
            $statusColors = [
                'inbound' => 'secondary',
                'booking' => 'primary', 
                'shipping' => 'info',
                'document' => 'warning',
                'clearance' => 'danger',
                'delivery' => 'success',
                'bank' => 'dark',
                'complete' => 'success'
            ];
            
            return response()->json([
                'statistics' => $statistics,
                'operationalData' => $operationalData,
                'inboundsByStatus' => $inboundsByStatus,
                'statusColors' => $statusColors,
                'lastUpdated' => Carbon::now()->format('H:i:s'),
                'dateRange' => [
                    'start' => $startDate,
                    'end' => $endDate,
                    'parsedStart' => $startDateTime->toDateTimeString(),
                    'parsedEnd' => $endDateTime->toDateTimeString()
                ],
                'debug' => [
                    'totalInbounds' => $statistics['total_inbounds'],
                    'operationalDataSum' => array_sum($operationalData['data'])
                ],
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard refresh error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to refresh dashboard',
                'message' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
    
    /**
     * Get color class for status badges
     */
    public function getStatusColor($status)
    {
        $colors = [
            'inbound' => 'secondary',
            'booking' => 'primary', 
            'shipping' => 'info',
            'document' => 'warning',
            'clearance' => 'danger',
            'delivery' => 'success',
            'bank' => 'dark',
            'complete' => 'success'
        ];
        return $colors[$status] ?? 'secondary';
    }
}
