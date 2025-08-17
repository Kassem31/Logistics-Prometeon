@extends('main')
@section('container')
<div class="row">
    <div class="col-xl-12">
        <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="flaticon2-analytics text-primary"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        Logistics Dashboard
                        {{-- <small class="text-muted">Real-time overview of your logistics operations</small> --}}
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <label class="kt-font-bold kt-font-sm text-uppercase">From:</label>
                                    <input type="date" class="form-control form-control-sm" id="start_date" value="{{ $startDate }}">
                                </div>
                                <div class="col-auto">
                                    <label class="kt-font-bold kt-font-sm text-uppercase">To:</label>
                                    <input type="date" class="form-control form-control-sm" id="end_date" value="{{ $endDate }}">
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-primary btn-sm btn-elevate" onclick="refreshDashboard()">
                                        <i class="la la-refresh"></i> Refresh
                                    </button>
                                </div>
                                {{-- <div class="col-auto">
                                    <button class="btn btn-outline-secondary btn-sm" onclick="testRefresh()">
                                        <i class="la la-cog"></i> Debug
                                    </button>
                                </div> --}}
                            </div>
                            <div class="kt-portlet__head-actions mt-2">
                                <small class="text-muted">
                                    <i class="la la-clock-o"></i> Last updated: 
                                    <span id="last-updated" class="kt-font-bold">{{ now()->format('H:i:s') }}</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-3 col-lg-6">
        <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-widget24">
                <div class="kt-widget24__details">
                    <div class="kt-widget24__info">
                        <h4 class="kt-widget24__title">Total Inbounds</h4>
                        <span class="kt-widget24__desc">All inbound shipments</span>
                    </div>
                    <span class="kt-widget24__stats kt-font-brand" data-stat="total_inbounds">{{ $statistics['total_inbounds'] }}</span>
                </div>
                <div class="progress progress--sm">
                    <div class="progress-bar kt-bg-brand" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="kt-widget24__action">
                    <span class="kt-widget24__change">All Time</span>
                    <span class="kt-widget24__number">100%</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6">
        <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-widget24">
                <div class="kt-widget24__details">
                    <div class="kt-widget24__info">
                        <h4 class="kt-widget24__title">Completed</h4>
                        <span class="kt-widget24__desc">Successfully delivered</span>
                    </div>
                    <span class="kt-widget24__stats kt-font-success" data-stat="completed_inbounds">{{ $statistics['completed_inbounds'] }}</span>
                </div>
                <div class="progress progress--sm">
                    <div class="progress-bar kt-bg-success" role="progressbar" style="width: {{ $statistics['completion_rate'] }}%;" aria-valuenow="{{ $statistics['completion_rate'] }}" aria-valuemin="0" aria-valuemax="100" data-stat-progress="completion_rate"></div>
                </div>
                <div class="kt-widget24__action">
                    <span class="kt-widget24__change">Completion Rate</span>
                    <span class="kt-widget24__number" data-stat-percent="completion_rate">{{ $statistics['completion_rate'] }}%</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6">
        <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-widget24">
                <div class="kt-widget24__details">
                    <div class="kt-widget24__info">
                        <h4 class="kt-widget24__title">In Transit</h4>
                        <span class="kt-widget24__desc">Currently shipping</span>
                    </div>
                    <span class="kt-widget24__stats kt-font-warning" data-stat="in_transit">{{ $statistics['in_transit'] }}</span>
                </div>
                <div class="progress progress--sm">
                    <div class="progress-bar kt-bg-warning" role="progressbar" style="width: {{ $statistics['total_inbounds'] > 0 ? round(($statistics['in_transit'] / $statistics['total_inbounds']) * 100) : 0 }}%;" aria-valuenow="{{ $statistics['in_transit'] }}" aria-valuemin="0" aria-valuemax="100" data-stat-progress="in_transit_percent"></div>
                </div>
                <div class="kt-widget24__action">
                    <span class="kt-widget24__change">Active Shipments</span>
                    <span class="kt-widget24__number" data-stat-percent="in_transit_percent">{{ $statistics['total_inbounds'] > 0 ? round(($statistics['in_transit'] / $statistics['total_inbounds']) * 100) : 0 }}%</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6">
        <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-widget24">
                <div class="kt-widget24__details">
                    <div class="kt-widget24__info">
                        <h4 class="kt-widget24__title">Delayed</h4>
                        <span class="kt-widget24__desc">Requires attention</span>
                    </div>
                    <span class="kt-widget24__stats kt-font-danger" data-stat="delayed">{{ $statistics['delayed'] }}</span>
                </div>
                <div class="progress progress--sm">
                    <div class="progress-bar kt-bg-danger" role="progressbar" style="width: {{ $statistics['total_inbounds'] > 0 ? round(($statistics['delayed'] / $statistics['total_inbounds']) * 100) : 0 }}%;" aria-valuenow="{{ $statistics['delayed'] }}" aria-valuemin="0" aria-valuemax="100" data-stat-progress="delayed_percent"></div>
                </div>
                <div class="kt-widget24__action">
                    <span class="kt-widget24__change">Attention Needed</span>
                    <span class="kt-widget24__number" data-stat-percent="delayed_percent">{{ $statistics['total_inbounds'] > 0 ? round(($statistics['delayed'] / $statistics['total_inbounds']) * 100) : 0 }}%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Orphan POs Widget -->
    <div class="col-xl-6">
        <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="flaticon2-warning text-danger"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        Orphan Purchase Orders
                        <small class="text-muted">POs not linked to any inbound</small>
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <span class="kt-badge kt-badge--danger kt-badge--pill">{{ $orphanPOs->count() }}</span>
                        <a href="{{ route('inbound.create') }}" class="btn btn-primary btn-sm btn-elevate ml-2">
                            <i class="la la-plus"></i> Create Inbound
                        </a>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                @if($orphanPOs->count() > 0)
                    <div class="kt-scroll" data-scroll="true" data-height="400">
                        @foreach($orphanPOs as $po)
                            <div class="kt-widget4__item orphan-po-item">
                                <div class="kt-widget4__pic">
                                    <span class="kt-badge kt-badge--danger kt-badge--dot"></span>
                                </div>
                                <div class="kt-widget4__info">
                                    <a href="#" class="kt-widget4__username kt-font-bold">{{ $po->po_number }}</a>
                                    <p class="kt-widget4__text">
                                        <span class="kt-badge kt-badge--secondary kt-badge--inline">{{ $po->supplier->name ?? 'N/A' }}</span><br>
                                        <small class="text-muted">
                                            <i class="la la-user"></i> PIC: {{ $po->pic->name ?? 'N/A' }} |
                                            <i class="la la-calendar"></i> {{ $po->created_at->format('d/m/Y') }}
                                        </small>
                                    </p>
                                </div>
                                {{-- <div class="kt-widget4__ext">
                                    <a href="{{ route('inbound.create') }}?po_id={{ $po->id }}" class="btn btn-outline-primary btn-sm btn-elevate">
                                        <i class="la la-link"></i> Link
                                    </a>
                                </div> --}}
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="kt-widget4__item text-center py-5">
                        <div class="kt-widget4__info">
                            <div class="empty-state">
                                <i class="flaticon2-check-mark text-success" style="font-size: 3rem;"></i>
                                <h4 class="text-muted mt-3">All Clear!</h4>
                                <p class="text-muted">No orphan POs – all purchase orders are properly assigned to inbounds.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Operational Stages Chart -->
    <div class="col-xl-6">
        <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="flaticon2-pie-chart-3 text-info"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        Operational Stages
                        <small class="text-muted">Inbound distribution across workflow stages</small>
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="dropdown dropdown-inline">
                        <button type="button" class="btn btn-clean btn-icon btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="la la-cog"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="refreshDashboard()">
                                <i class="la la-refresh"></i> Refresh Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                @php
                    $stageLabels = ['Inbound Details', 'Booking', 'Shipping', 'Documents', 'Clearance', 'Delivery', 'Bank', 'Complete'];
                    $stageData = $operationalData['data'];
                    $stageColors = ['#6c757d', '#007bff', '#28a745', '#ffc107', '#fd7e14', '#dc3545', '#6f42c1', '#20c997'];
                    $totalInbounds = array_sum($stageData);
                @endphp
                
                @if($totalInbounds > 0)
                    <div id="operational-stages">
                        @foreach($stageLabels as $index => $label)
                            @php
                                $count = $stageData[$index];
                                $percentage = $totalInbounds > 0 ? round(($count / $totalInbounds) * 100, 1) : 0;
                                $color = $stageColors[$index];
                            @endphp
                            
                            <div class="stage-item mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="kt-font-bold">{{ $label }}</span>
                                    <span class="kt-badge kt-badge--inline kt-badge--pill" style="background-color: {{ $color }}; color: white;">
                                        {{ $count }} ({{ $percentage }}%)
                                    </span>
                                </div>
                                <div class="progress" style="height: 10px; border-radius: 5px;">
                                    <div class="progress-bar" 
                                         style="width: {{ $percentage }}%; background-color: {{ $color }}; border-radius: 5px;" 
                                         role="progressbar" 
                                         aria-valuenow="{{ $percentage }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="mt-4 pt-3 border-top">
                            <div class="text-center">
                                <h5 class="kt-font-bold text-primary">Total Inbounds: {{ $totalInbounds }}</h5>
                                <small class="text-muted">
                                    <i class="la la-calendar"></i> 
                                    {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                @else
                    <div style="height: 300px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                        <i class="flaticon2-pie-chart-3 text-muted" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mt-3">No Data Available</h4>
                        <p class="text-muted">No inbound data found for the selected date range.</p>
                        <small class="text-muted">Try adjusting the date range or check if there are any inbounds in the system.</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Progressive Inbound List -->
<div class="row">
    <div class="col-xl-12">
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="flaticon2-list-3 text-success"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        Progressive Inbound List
                        <small class="text-muted">Current inbounds grouped by status</small>
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <a href="{{ route('inbound.index') }}" class="btn btn-outline-primary btn-sm btn-elevate">
                            <i class="la la-list"></i> View All
                        </a>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="status-grid" id="progressive-inbound-list">
                    <div class="row">
                        @foreach($inboundsByStatus as $status => $inbounds)
                            @if($inbounds->count() > 0)
                                <div class="col-xl-3 col-lg-6 col-md-6">
                                    <div class="status-card kt-bg-light-{{ $statusColors[$status] ?? 'secondary' }}" data-status="{{ $status }}">
                                        <div class="status-card__header">
                                            <div class="status-card__icon">
                                                <span class="kt-badge kt-badge--{{ $statusColors[$status] ?? 'secondary' }} kt-badge--lg kt-badge--rounded">
                                                    {{ $inbounds->count() }}
                                                </span>
                                            </div>
                                            <div class="status-card__info">
                                                <h4 class="status-card__title">{{ ucfirst($status) }}</h4>
                                                <p class="status-card__desc">
                                                    {{ $inbounds->count() }} inbound{{ $inbounds->count() > 1 ? 's' : '' }}
                                                </p>
                                            </div>
                                            <div class="status-card__toggle">
                                                <button class="btn btn-clean btn-icon btn-sm" onclick="toggleStatusDetails('{{ $status }}')">
                                                    <i class="la la-angle-down" id="toggle-icon-{{ $status }}"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="status-card__details" id="status-details-{{ $status }}" style="display: none;">
                                            <div class="status-card__content">
                                                @foreach($inbounds->take(5) as $inbound)
                                                    <div class="inbound-item">
                                                        <div class="inbound-item__info">
                                                            <a href="{{ route('inbound.show', $inbound) }}" class="inbound-item__title">
                                                                {{ $inbound->inbound_no ?? 'INB-' . $inbound->id }}
                                                            </a>
                                                            <div class="inbound-item__meta">
                                                                <span class="kt-badge kt-badge--secondary kt-badge--inline">
                                                                    {{ $inbound->po_header->po_number ?? 'N/A' }}
                                                                </span>
                                                                <small class="text-muted ml-2">
                                                                    <i class="la la-calendar"></i> {{ $inbound->created_at->format('d/m/Y') }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                        <div class="inbound-item__actions">
                                                            <a href="{{ route('inbound.edit', $inbound) }}" class="btn btn-clean btn-sm btn-icon" title="Edit">
                                                                <i class="la la-edit"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @if($inbounds->count() > 5)
                                                    <div class="text-center pt-3">
                                                        <small class="text-muted">
                                                            <i class="la la-plus-circle"></i>
                                                            {{ $inbounds->count() - 5 }} more items...
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
.kt-widget24__stats {
    font-size: 2.5rem;
    font-weight: 600;
    line-height: 1;
}

.kt-widget24__title {
    font-weight: 600;
    color: #595d6e;
}

.kt-widget24__desc {
    color: #74788d;
    font-size: 0.9rem;
}

.kt-widget24__change {
    font-size: 0.8rem;
    color: #74788d;
}

.kt-widget24__number {
    font-size: 0.9rem;
    font-weight: 600;
}

.empty-state {
    padding: 2rem;
}

.orphan-po-item {
    border-left: 3px solid #fd397a;
    margin-bottom: 1rem;
    padding: 1rem;
    border-radius: 0 0.25rem 0.25rem 0;
    transition: all 0.3s ease;
}

.orphan-po-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.status-grid {
    margin: -0.5rem;
}

.status-card {
    margin: 0.5rem;
    border-radius: 0.5rem;
    border: 1px solid rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    cursor: pointer;
}

.status-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.status-card__header {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.status-card__icon {
    margin-right: 1rem;
}

.status-card__info {
    flex: 1;
}

.status-card__title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #595d6e;
}

.status-card__desc {
    margin: 0;
    color: #74788d;
    font-size: 0.9rem;
}

.status-card__toggle {
    transition: transform 0.3s ease;
}

.status-card__details {
    overflow: hidden;
    transition: all 0.3s ease;
}

.status-card__content {
    padding: 1rem 1.5rem 1.5rem;
}

.inbound-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    transition: all 0.2s ease;
}

.inbound-item:hover {
    background-color: rgba(0,0,0,0.02);
    padding-left: 0.5rem;
    margin-left: -0.5rem;
    margin-right: -0.5rem;
    padding-right: 0.5rem;
    border-radius: 0.25rem;
}

.inbound-item:last-child {
    border-bottom: none;
}

.inbound-item__title {
    font-weight: 600;
    color: #5867dd;
    text-decoration: none;
    display: block;
    margin-bottom: 0.25rem;
}

.inbound-item__title:hover {
    color: #384bd6;
    text-decoration: none;
}

.inbound-item__meta {
    display: flex;
    align-items: center;
}

.inbound-item__actions {
    opacity: 0;
    transition: opacity 0.2s ease;
}

.inbound-item:hover .inbound-item__actions {
    opacity: 1;
}

.kt-portlet__head-icon {
    margin-right: 0.5rem;
    font-size: 1.2rem;
}

.btn-elevate {
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.btn-elevate:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transform: translateY(-1px);
}

.progress--sm {
    height: 4px;
}

.kt-badge--rounded {
    border-radius: 50%;
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    font-weight: 600;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.status-card__details[style*="block"] .inbound-item {
    animation: fadeIn 0.3s ease forwards;
}

.status-card__details[style*="block"] .inbound-item:nth-child(1) { animation-delay: 0.1s; }
.status-card__details[style*="block"] .inbound-item:nth-child(2) { animation-delay: 0.2s; }
.status-card__details[style*="block"] .inbound-item:nth-child(3) { animation-delay: 0.3s; }
.status-card__details[style*="block"] .inbound-item:nth-child(4) { animation-delay: 0.4s; }
.status-card__details[style*="block"] .inbound-item:nth-child(5) { animation-delay: 0.5s; }
</style>
@endpush

@push('scripts')
<script>
// Auto-refresh every 10 minutes
setInterval(function() {
    refreshDashboard(false);
}, 600000);

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - initializing dashboard');
    
    // Add event listeners to date inputs
    document.getElementById('start_date').addEventListener('change', function() {
        refreshDashboard(true);
    });
    
    document.getElementById('end_date').addEventListener('change', function() {
        refreshDashboard(true);
    });
    
    setTimeout(() => {
        initCounterAnimations();
        initTooltips();
        console.log('Dashboard initialized successfully');
    }, 100);
});

function initCounterAnimations() {
    const counters = document.querySelectorAll('.kt-widget24__stats');
    
    counters.forEach(counter => {
        const target = parseInt(counter.textContent);
        let current = 0;
        const increment = target / 60;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            counter.textContent = Math.floor(current);
        }, 25);
    });
}

function initTooltips() {
    // Add tooltips for interactive elements
    if (typeof $ !== 'undefined') {
        $('[data-toggle="tooltip"]').tooltip();
    }
}

function refreshDashboard(showLoading = true) {
    if (showLoading) {
        document.getElementById('last-updated').innerHTML = '<i class="la la-spinner la-spin"></i>';
    }
    
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    console.log('Refreshing dashboard with dates:', startDate, endDate);
    
    fetch('{{ route("dashboard.refresh") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            start_date: startDate,
            end_date: endDate
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Received data:', data);
        
        // Update statistics with animation
        updateStatistics(data.statistics);
        
        // Update operational stages chart
        updateOperationalStages(data.operationalData);
        
        // Update progressive inbound list
        updateProgressiveInboundList(data.inboundsByStatus, data.statusColors);
        
        // Update last updated time
        document.getElementById('last-updated').innerHTML = 
            '<i class="la la-check text-success"></i> ' + data.lastUpdated;
        
        if (showLoading) {
            setTimeout(() => {
                document.getElementById('last-updated').innerHTML = 
                    '<i class="la la-check text-success"></i> Updated at ' + data.lastUpdated;
            }, 1000);
        }
    })
    .catch(error => {
        console.error('Error refreshing dashboard:', error);
        document.getElementById('last-updated').innerHTML = 
            '<i class="la la-exclamation-triangle text-danger"></i> Error updating: ' + error.message;
    });
}

function updateOperationalStages(operationalData) {
    const container = document.getElementById('operational-stages');
    if (!container || !operationalData) return;
    
    const stageLabels = ['Inbound Details', 'Booking', 'Shipping', 'Documents', 'Clearance', 'Delivery', 'Bank', 'Complete'];
    const stageColors = ['#6c757d', '#007bff', '#28a745', '#ffc107', '#fd7e14', '#dc3545', '#6f42c1', '#20c997'];
    const totalInbounds = operationalData.data.reduce((a, b) => a + b, 0);
    
    if (totalInbounds === 0) {
        container.innerHTML = `
            <div style="height: 300px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                <i class="flaticon2-pie-chart-3 text-muted" style="font-size: 4rem;"></i>
                <h4 class="text-muted mt-3">No Data Available</h4>
                <p class="text-muted">No inbound data found for the selected date range.</p>
            </div>
        `;
        return;
    }
    
    let html = '';
    stageLabels.forEach((label, index) => {
        const count = operationalData.data[index];
        const percentage = totalInbounds > 0 ? Math.round((count / totalInbounds) * 100 * 10) / 10 : 0;
        const color = stageColors[index];
        
        html += `
            <div class="stage-item mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="kt-font-bold">${label}</span>
                    <span class="kt-badge kt-badge--inline kt-badge--pill" style="background-color: ${color}; color: white;">
                        ${count} (${percentage}%)
                    </span>
                </div>
                <div class="progress" style="height: 10px; border-radius: 5px;">
                    <div class="progress-bar" 
                         style="width: ${percentage}%; background-color: ${color}; border-radius: 5px; transition: width 0.5s ease;" 
                         role="progressbar" 
                         aria-valuenow="${percentage}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                    </div>
                </div>
            </div>
        `;
    });
    
    html += `
        <div class="mt-4 pt-3 border-top">
            <div class="text-center">
                <h5 class="kt-font-bold text-primary">Total Inbounds: ${totalInbounds}</h5>
            </div>
        </div>
    `;
    
    container.innerHTML = html;
}

function updateProgressiveInboundList(inboundsByStatus, statusColors) {
    const container = document.getElementById('progressive-inbound-list');
    if (!container || !inboundsByStatus) return;
    
    // Count total inbounds for the "No data" check
    let totalInbounds = 0;
    Object.keys(inboundsByStatus).forEach(status => {
        if (inboundsByStatus[status] && inboundsByStatus[status].length) {
            totalInbounds += inboundsByStatus[status].length;
        }
    });
    
    if (totalInbounds === 0) {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="flaticon2-list-3 text-muted" style="font-size: 4rem;"></i>
                <h4 class="text-muted mt-3">No Inbounds Found</h4>
                <p class="text-muted">No inbound data found for the selected date range.</p>
            </div>
        `;
        return;
    }
    
    let html = '<div class="row">';
    
    Object.keys(inboundsByStatus).forEach(status => {
        const inbounds = inboundsByStatus[status];
        if (inbounds && inbounds.length > 0) {
            const statusColor = statusColors[status] || 'secondary';
            const inboundCount = inbounds.length;
            
            html += `
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="status-card kt-bg-light-${statusColor}" data-status="${status}">
                        <div class="status-card__header">
                            <div class="status-card__icon">
                                <span class="kt-badge kt-badge--${statusColor} kt-badge--lg kt-badge--rounded">
                                    ${inboundCount}
                                </span>
                            </div>
                            <div class="status-card__info">
                                <h4 class="status-card__title">${status.charAt(0).toUpperCase() + status.slice(1)}</h4>
                                <p class="status-card__desc">
                                    ${inboundCount} inbound${inboundCount > 1 ? 's' : ''}
                                </p>
                            </div>
                            <div class="status-card__toggle">
                                <button class="btn btn-clean btn-icon btn-sm" onclick="toggleStatusDetails('${status}')">
                                    <i class="la la-angle-down" id="toggle-icon-${status}"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="status-card__details" id="status-details-${status}" style="display: none;">
                            <div class="status-card__content">
            `;
            
            // Add first 5 inbounds
            const displayInbounds = inbounds.slice(0, 5);
            displayInbounds.forEach(inbound => {
                const inboundNo = inbound.inbound_no || `INB-${inbound.id}`;
                const poNumber = inbound.po_header ? inbound.po_header.po_number : 'N/A';
                const createdDate = new Date(inbound.created_at).toLocaleDateString('en-GB');
                
                html += `
                    <div class="inbound-item">
                        <div class="inbound-item__info">
                            <a href="/inbound/${inbound.id}" class="inbound-item__title">
                                ${inboundNo}
                            </a>
                            <div class="inbound-item__meta">
                                <span class="kt-badge kt-badge--secondary kt-badge--inline">
                                    ${poNumber}
                                </span>
                                <small class="text-muted ml-2">
                                    <i class="la la-calendar"></i> ${createdDate}
                                </small>
                            </div>
                        </div>
                        <div class="inbound-item__actions">
                            <a href="/inbound/${inbound.id}/edit" class="btn btn-clean btn-sm btn-icon" title="Edit">
                                <i class="la la-edit"></i>
                            </a>
                        </div>
                    </div>
                `;
            });
            
            // Add "more items" indicator if needed
            if (inbounds.length > 5) {
                html += `
                    <div class="text-center pt-3">
                        <small class="text-muted">
                            <i class="la la-plus-circle"></i>
                            ${inbounds.length - 5} more items...
                        </small>
                    </div>
                `;
            }
            
            html += `
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
    });
    
    html += '</div>';
    container.innerHTML = html;
}

function updateStatistics(stats) {
    Object.keys(stats).forEach(key => {
        // Update main statistics numbers
        const element = document.querySelector(`[data-stat="${key}"]`);
        if (element) {
            animateNumber(element, parseInt(element.textContent), stats[key]);
        }
        
        // Update progress bars
        const progressElement = document.querySelector(`[data-stat-progress="${key}"]`);
        if (progressElement) {
            const percentage = stats[key];
            progressElement.style.width = percentage + '%';
            progressElement.setAttribute('aria-valuenow', percentage);
        }
        
        // Update percentage displays
        const percentElement = document.querySelector(`[data-stat-percent="${key}"]`);
        if (percentElement) {
            percentElement.textContent = stats[key] + '%';
        }
    });
}

function animateNumber(element, from, to) {
    const duration = 1000;
    const start = Date.now();
    
    const timer = setInterval(() => {
        const progress = (Date.now() - start) / duration;
        if (progress >= 1) {
            element.textContent = to;
            clearInterval(timer);
            return;
        }
        
        const current = Math.floor(from + (to - from) * easeInOutQuart(progress));
        element.textContent = current;
    }, 16);
}

function easeInOutQuart(t) {
    return t < 0.5 ? 8 * t * t * t * t : 1 - 8 * (--t) * t * t * t;
}

function toggleStatusDetails(status) {
    const details = document.getElementById('status-details-' + status);
    const icon = document.getElementById('toggle-icon-' + status);
    
    if (details) {
        if (details.style.display === 'none') {
            details.style.display = 'block';
            icon.classList.remove('la-angle-down');
            icon.classList.add('la-angle-up');
            
            details.style.height = '0px';
            details.style.overflow = 'hidden';
            const height = details.scrollHeight + 'px';
            details.style.height = height;
            
            setTimeout(() => {
                details.style.height = 'auto';
                details.style.overflow = 'visible';
            }, 300);
        } else {
            details.style.height = details.scrollHeight + 'px';
            details.style.overflow = 'hidden';
            
            setTimeout(() => {
                details.style.height = '0px';
            }, 10);
            
            setTimeout(() => {
                details.style.display = 'none';
                icon.classList.remove('la-angle-up');
                icon.classList.add('la-angle-down');
            }, 300);
        }
    }
}

function testRefresh() {
    console.log('Testing refresh functionality...');
    console.log('CSRF Token:', '{{ csrf_token() }}');
    console.log('Route URL:', '{{ route("dashboard.refresh") }}');
    
    refreshDashboard(true);
}
</script>
@endpush
