<?php

namespace App\Http\Controllers;

use App\Filters\POFilter\POIndexFilter;
use App\Models\Inbound;
use App\Models\MaterialGroup;
use App\Models\PODetail;
use App\Models\POHeader;
use App\Models\RawMaterial;
use App\Models\ShippingUnit;
use App\Models\Supplier;
use App\Models\User;
use App\Models\IncoTerm;
use App\Models\Country;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

class POController extends Controller
{
    // Stub show method since it's not used
    /**
     * Redirect any show route to index (stub to satisfy resource route)
     *
     * @param mixed $id
     */
    public function show($id)
    {
        return redirect()->route('purchase-orders.index');
    }

    public function index(){
        $this->authorize(__FUNCTION__,POHeader::class);
        $suppliers  = Supplier::orderBy('name')->where('is_active',1)->get();
        $incoTerms = IncoTerm::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();
        $user = Auth::user();
        if(is_null($user->is_super_admin)){
            $persons = [];
            $items = POHeader::filter(new POIndexFilter(request()))->where('person_in_charge_id',$user->id)->paginate(30);
        }else{
            $persons = User::where('is_active',1)->whereNull('is_super_admin')->orderBy('name')->get();
            $items = POHeader::filter(new POIndexFilter(request()))->paginate(30);
        }
        
        return view('po-header.index',[
            'items'=>$items,
            'suppliers'=>$suppliers,
            'persons'=>$persons,
            'incoTerms'=>$incoTerms,
            'countries'=>$countries,
        ]);
    }

    public function create(){
        $this->authorize(__FUNCTION__,POHeader::class);
        $suppliers  = Supplier::orderBy('name')->where('is_active',1)->get();
        $incoTerms = IncoTerm::all();
        $countries = Country::orderBy('name')->get();
        $persons = User::where('is_active',1)->whereNull('is_super_admin')->orderBy('name')->get();
        $shippingUnits = ShippingUnit::get();
        
        // Load all materials initially - person selection will be based on material selection
        $rawMaterials = RawMaterial::orderBy('name')->get();
        return view('po-header.create',[
            'suppliers'=>$suppliers,
            'persons'=>$persons,
            'units'=>$shippingUnits,
            'rawMaterials'=>$rawMaterials,
            'incoTerms'=>$incoTerms,
            'countries'=>$countries
        ]);
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,POHeader::class);
        $detail = collect($request->input('detail'))->filter(function($item){
           return !is_null($item['raw_material_id']) || !is_null($item['qty']) || !is_null($item['shipping_unit_id']);
        });
        $request->merge([
            'detail'=>$detail->all()
        ]);
        
        // Custom validation: if there are details, person_in_charge_id is required
        if (!empty($detail->all()) && empty($request->input('basic.person_in_charge_id'))) {
            return redirect()->back()
                             ->withErrors(['basic.person_in_charge_id' => 'Person in charge is required when materials are selected.'])
                             ->withInput();
        }
        
    // Convert date formats for detail items
        $details = $request->input('detail', []);
        foreach ($details as $key => $detailItem) {
            if (!empty($detailItem['item_due_date'])) {
                try {
                    $details[$key]['item_due_date'] = Carbon::createFromFormat('d/m/Y', $detailItem['item_due_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    // Keep original value if conversion fails
                }
            }
            if (!empty($detailItem['amendment_date'])) {
                try {
                    $details[$key]['amendment_date'] = Carbon::createFromFormat('d/m/Y', $detailItem['amendment_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    // Keep original value if conversion fails
                }
            }
        }
        $request->merge(['detail' => $details]);
        // Convert header order_date and due_date from dd/mm/YYYY to Y-m-d
        $basic = $request->input('basic', []);
        if (!empty($basic['order_date'])) {
            try {
                $basic['order_date'] = Carbon::createFromFormat('d/m/Y', $basic['order_date'])->format('Y-m-d');
            } catch (\Exception $e) {
                // leave unmodified if conversion fails
            }
        }
        if (!empty($basic['due_date'])) {
            try {
                $basic['due_date'] = Carbon::createFromFormat('d/m/Y', $basic['due_date'])->format('Y-m-d');
            } catch (\Exception $e) {
                // leave unmodified if conversion fails
            }
        }
        $request->merge(['basic' => $basic]);
        
        $this->validate($request,$this->rules());
        DB::transaction(function () use($request) {
            $data = $request->input('basic');
            $header = POHeader::create($data);
            // Explicitly assign and save date fields to ensure proper saving
            if (!empty($data['order_date'])) {
                $header->order_date = $data['order_date'];
            }
            if (!empty($data['due_date'])) {
                $header->due_date = $data['due_date'];
            }
            $header->save();
            $details = collect(array_values($request->input('detail')))->map(function($item,$key){
                return array_merge($item,['row_no'=> $key+1]);
            })->all();
            $header->details()->createMany($details);
        });
        //Details
        return redirect()->route('purchase-orders.index')->with('success','Purchase Order Created Successfully');

    }

    public function edit(POHeader $purchase_order){
        $this->authorize(__FUNCTION__,POHeader::class);
        $purchase_order->load('details.inboundDetails');
        $suppliers  = Supplier::orderBy('name')->where('is_active',1)->get();
        $incoTerms = IncoTerm::all();
        $countries = Country::orderBy('name')->get();
        $persons = User::where('is_active',1)->whereNull('is_super_admin')->orderBy('name')->get();
        $shippingUnits = ShippingUnit::get();
        $pic = User::find(old('basic.person_in_charge_id',$purchase_order->person_in_charge_id));

        if(is_null($pic)){
            // If no person in charge is assigned (e.g., imported POs), load all materials
            $rawMaterials = RawMaterial::orderBy('name')->get();
        }else{
            $groups = $pic->load('materialGroups')->materialGroups->pluck('id');
            $rawMaterials = RawMaterial::whereIn('material_group_id',$groups)->get();
        }
        return view('po-header.edit',[
            'suppliers'=>$suppliers,
            'persons'=>$persons,
            'units'=>$shippingUnits,
            'rawMaterials'=>$rawMaterials,
            'incoTerms'=>$incoTerms,
            'countries'=>$countries,
            'po'=>$purchase_order
        ]);
    }

    public function update(Request $request,POHeader $purchase_order){
        $this->authorize(__FUNCTION__,POHeader::class);
        $detail = collect($request->input('detail'))->filter(function($item){
            return !is_null($item['raw_material_id']) || !is_null($item['qty']) || !is_null($item['shipping_unit_id']);
         });
         $edit = collect($request->input('edit'))->filter(function($item){
            //  dd($item);
            return !is_null($item['raw_material_id']) || !is_null($item['qty']) || !is_null($item['shipping_unit_id']);
         });
         $request->merge([
             'edit'=>$edit->all(),
             'detail'=>$detail->all()
         ]);
         
        // Custom validation: if there are details, person_in_charge_id is required
        $hasDetails = !empty($detail->all()) || !empty($edit->all());
        if ($hasDetails && empty($request->input('basic.person_in_charge_id'))) {
            return redirect()->back()
                             ->withErrors(['basic.person_in_charge_id' => 'Person in charge is required when materials are selected.'])
                             ->withInput();
        }
         
        // Convert date formats for detail items (new items)
        $details = $request->input('detail', []);
        foreach ($details as $key => $detailItem) {
            if (!empty($detailItem['item_due_date'])) {
                try {
                    $details[$key]['item_due_date'] = Carbon::createFromFormat('d/m/Y', $detailItem['item_due_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    // Keep original value if conversion fails
                }
            }
            if (!empty($detailItem['amendment_date'])) {
                try {
                    $details[$key]['amendment_date'] = Carbon::createFromFormat('d/m/Y', $detailItem['amendment_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    // Keep original value if conversion fails
                }
            }
        }
        
        // Convert date formats for edit items (existing items)
        $editItems = $request->input('edit', []);
        foreach ($editItems as $key => $editItem) {
            if (!empty($editItem['item_due_date'])) {
                try {
                    $editItems[$key]['item_due_date'] = Carbon::createFromFormat('d/m/Y', $editItem['item_due_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    // Keep original value if conversion fails
                }
            }
            if (!empty($editItem['amendment_date'])) {
                try {
                    $editItems[$key]['amendment_date'] = Carbon::createFromFormat('d/m/Y', $editItem['amendment_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    // Keep original value if conversion fails
                }
            }
        }
        
        $request->merge([
            'edit' => $editItems,
            'detail' => $details
        ]);
        // Convert header order_date and due_date from dd/mm/YYYY to Y-m-d
        $basic = $request->input('basic', []);
        if (!empty($basic['order_date'])) {
            try {
                $basic['order_date'] = Carbon::createFromFormat('d/m/Y', $basic['order_date'])->format('Y-m-d');
            } catch (\Exception $e) {
                // leave unmodified if conversion fails
            }
        }
        if (!empty($basic['due_date'])) {
            try {
                $basic['due_date'] = Carbon::createFromFormat('d/m/Y', $basic['due_date'])->format('Y-m-d');
            } catch (\Exception $e) {
                // leave unmodified if conversion fails
            }
        }
        $request->merge(['basic' => $basic]);
         
        $this->validate($request,$this->rules(true,$purchase_order));
        $data = $request->input('basic');
        $purchase_order->update($data);
        collect($editItems)->each(function($item,$key){
            PODetail::where('id',$key)->update($item);
        });
        $startCounter = $purchase_order->details->count();
        $detailsToCreate = collect(array_values($details))->map(function($item)use($startCounter){
            return array_merge($item,['row_no'=> $startCounter+1]);
        })->all();
        collect($request->input('delete',[]))->each(function($item){
            PODetail::where('id',$item)->update(['deleted_at'=>Carbon::now(),'deleted_by'=>Auth::id()]);
        });
        $purchase_order->details()->createMany($detailsToCreate);

        return redirect()->route('purchase-orders.index')->with('success','Purchase Order Updated Successfully');

    }

    protected function rules($is_update = false,$model = null){
        $rules =  [
            'basic.po_number'=>['required','unique:p_o_headers,po_number'],
            'basic.supplier_id'=>'required',
            'basic.order_date'=>'required',
            'basic.due_date'=>'required',
            'basic.person_in_charge_id'=>'nullable', // Made nullable - will be validated via JS
            'detail.*.raw_material_id'=>'required',
            'detail.*.qty'=>['required','numeric',function($attr,$value,$fail){
                if($value <= 0){
                    $fail('Qty Must be greater than Zero.');
                }
            }],
            'detail.*.shipping_unit_id'=>'required',
            'basic.incoterm_id'=>'required|exists:inco_terms,id',
            'detail.*.origin_country_id'=>'required|exists:countries,id',
            'detail.*.item_due_date'=>'required|date',
            'detail.*.amendment_date'=>'nullable|date',
            // 'detail'=>[function($attr,$value,$fail){
            //     if(empty($value)){
            //         $fail('Can not create Purchase Order with no details.');
            //     }
            // }]
        ];
        if($is_update){
            $rules = array_merge($rules, [
                'basic.po_number'=>'required|unique:p_o_headers,po_number,'.$model->id,
                'basic.person_in_charge_id'=>'nullable', // Made nullable for update too
                'basic.incoterm_id'=>'required|exists:inco_terms,id',
                'detail.*.origin_country_id'=>'required|exists:countries,id',
                'detail.*.item_due_date'=>'required|date',
                'detail.*.amendment_date'=>'nullable|date',
                'edit.*.raw_material_id'=>'required',
                'edit.*.qty'=>['required','numeric',function($attr,$value,$fail){
                if($value <= 0){
                    $fail('Qty Must be greater than Zero.');
                }
                }],
                'edit.*.shipping_unit_id'=>'required',
                'edit.*.origin_country_id'=>'required|exists:countries,id',
                'edit.*.item_due_date'=>'required|date',
                'edit.*.amendment_date'=>'nullable|date',
            ]);
        }
        return $rules;
    }

    /**
     * Show the Excel import form
     */
    public function importForm()
    {
        $this->authorize('create', POHeader::class);
        
        return view('po-header.import');
    }

    /**
     * Process the Excel file upload and import purchase orders
     */
    public function import(Request $request)
    {
        $this->authorize('create', POHeader::class);
        
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            // Get raw cell values without formatting to handle Excel date serial numbers correctly
            // toArray parameters: (nullValue, calculateFormulas, formatData)
            $rows = $worksheet->toArray(null, true, false);

            // Skip header row (first row)
            $dataRows = array_slice($rows, 1);
            
            $poCount = 0;
            $detailCount = 0;
            $materialCount = 0;
            $materialGroupCount = 0;
            $skippedPOs = 0;
            $updatedPOs = 0;
            $errors = [];

            DB::transaction(function () use ($dataRows, &$poCount, &$detailCount, &$materialCount, &$materialGroupCount, &$skippedPOs, &$updatedPOs, &$errors) {
                $processedPOs = [];
                $skippedPONumbers = []; // Track POs that are linked to inbounds
                
                foreach ($dataRows as $index => $row) {
                    $rowNumber = $index + 2; // +2 because we start from row 2 (after header)
                    
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }
                    
                    // Expected columns based on your example:
                    // Order date, Sap Code, Material Group, Material, PO Number, Line Number, Due Date, Amendment Date, Qty, Shipping Unit, Supplier, Incoterm, Origin
                    $orderDate = trim($row[0] ?? '');
                    $sapCode = trim($row[1] ?? '');
                    $materialGroup = trim($row[2] ?? '');
                    $materialName = trim($row[3] ?? '');
                    $poNumber = trim($row[4] ?? '');
                    $lineNumber = trim($row[5] ?? ''); // Line number for the PO detail
                    $dueDate = trim($row[6] ?? '');
                    $amendmentDate = trim($row[7] ?? '');
                    $quantity = floatval($row[8] ?? 0);
                    $shippingUnit = trim($row[9] ?? '');
                    $supplierName = trim($row[10] ?? '');
                    $incotermName = trim($row[11] ?? '');
                    $originCountry = trim($row[12] ?? '');
                    
                    // Skip if this PO is already marked as linked to inbound
                    if (in_array($poNumber, $skippedPONumbers)) {
                        continue;
                    }
                    
                    // Validate required fields
                    if (empty($poNumber) || empty($materialName) || $quantity <= 0) {
                        $errors[] = "Row {$rowNumber}: Missing required fields (PO Number, Material Name, or invalid Quantity)";
                        continue;
                    }
                    
                    // Find supplier by name
                    $supplier = Supplier::where('name', 'LIKE', "%{$supplierName}%")->first();
                    if (!$supplier) {
                        $errors[] = "Row {$rowNumber}: Supplier '{$supplierName}' not found";
                        continue;
                    }
                    
                    // Find incoterm by prefix
                    $incoterm = IncoTerm::where('prefix', $incotermName)->first();
                    if (!$incoterm) {
                        $errors[] = "Row {$rowNumber}: Incoterm '{$incotermName}' not found";
                        continue;
                    }
                    
                    // Parse order date
                    $parsedOrderDate = null;
                    try {
                        if (is_numeric($orderDate)) {
                            // Excel date serial number
                            $parsedOrderDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($orderDate)->format('Y-m-d');
                        } else {
                            // Try parsing as d/m/Y first
                            try {
                                $parsedOrderDate = Carbon::createFromFormat('d/m/Y', $orderDate)->format('Y-m-d');
                            } catch (\Exception $e) {
                                // Fallback to generic parse
                                $parsedOrderDate = Carbon::parse($orderDate)->format('Y-m-d');
                            }
                        }
                    } catch (\Exception $e) {
                        $errors[] = "Row {$rowNumber}: Invalid order date format";
                        continue;
                    }
                    
                    // Parse due date
                    $parsedDueDate = null;
                    try {
                        if (is_numeric($dueDate)) {
                            // Excel date serial number
                            $parsedDueDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dueDate)->format('Y-m-d');
                        } else {
                            // Try parsing as d/m/Y first
                            try {
                                $parsedDueDate = Carbon::createFromFormat('d/m/Y', $dueDate)->format('Y-m-d');
                            } catch (\Exception $e) {
                                // Fallback to generic parse
                                $parsedDueDate = Carbon::parse($dueDate)->format('Y-m-d');
                            }
                        }
                    } catch (\Exception $e) {
                        $errors[] = "Row {$rowNumber}: Invalid due date format";
                        continue;
                    }
                    
                    // Find or create Purchase Order
                    if (!isset($processedPOs[$poNumber])) {
                        $existingPO = POHeader::where('po_number', $poNumber)->first();
                        
                        if (!$existingPO) {
                            // Create new PO (without person_in_charge_id - will be assigned manually later)
                            $poHeader = POHeader::create([
                                'po_number' => $poNumber,
                                'supplier_id' => $supplier->id,
                                'incoterm_id' => $incoterm->id,
                                'order_date' => $parsedOrderDate,
                                'due_date' => $parsedDueDate,
                                'status' => 'Open'
                            ]);
                            
                            $processedPOs[$poNumber] = $poHeader;
                            $poCount++;
                        } else {
                            // Check if existing PO is linked to any inbound
                            if ($existingPO->inbounds()->exists()) {
                                $skippedPONumbers[] = $poNumber;
                                $skippedPOs++;
                                $errors[] = "Row {$rowNumber}: PO '{$poNumber}' is linked to an inbound and cannot be modified";
                                Log::info("PO Import: Skipped PO '{$poNumber}' - linked to inbound");
                                continue;
                            }
                            
                            // PO exists but not linked to inbound - delete all existing details and update header
                            PODetail::where('po_header_id', $existingPO->id)->delete();
                            
                            // Update PO header with new data
                            $existingPO->update([
                                'supplier_id' => $supplier->id,
                                'incoterm_id' => $incoterm->id,
                                'order_date' => $parsedOrderDate,
                                'due_date' => $parsedDueDate,
                            ]);
                            
                            $processedPOs[$poNumber] = $existingPO;
                            $updatedPOs++;
                            Log::info("PO Import: Updated existing PO '{$poNumber}' - deleted old details for re-import");
                        }
                    }
                    
                    $poHeader = $processedPOs[$poNumber];
                    
                    // Find or create raw material by name
                    $rawMaterial = RawMaterial::where('name', 'LIKE', "%{$materialName}%")->first();
                    if (!$rawMaterial) {
                        // Also check by SAP code if provided
                        if (!empty($sapCode)) {
                            $rawMaterial = RawMaterial::where('sap_code', $sapCode)->first();
                        }
                    }
                    
                    if (!$rawMaterial) {
                        // Try to find or create material group
                        $materialGroupRecord = null;
                        if (!empty($materialGroup)) {
                            $materialGroupRecord = MaterialGroup::where('name', 'LIKE', "%{$materialGroup}%")->first();
                            if (!$materialGroupRecord) {
                                // Create new material group
                                $materialGroupRecord = MaterialGroup::create([
                                    'name' => $materialGroup
                                ]);
                                $materialGroupCount++;
                                Log::info("PO Import: Created new material group '{$materialGroup}' for row {$rowNumber}");
                            }
                        }
                        
                        // Create new raw material
                        if ($materialGroupRecord) {
                            // Check if SAP code already exists to avoid duplicates
                            if (!empty($sapCode) && RawMaterial::where('sap_code', $sapCode)->exists()) {
                                $errors[] = "Row {$rowNumber}: SAP code '{$sapCode}' already exists for another material";
                                continue;
                            }
                            
                            $rawMaterial = RawMaterial::create([
                                'name' => $materialName,
                                'sap_code' => $sapCode ?: 'AUTO-' . time() . '-' . $rowNumber, // Generate unique SAP code if empty
                                'material_group_id' => $materialGroupRecord->id,
                                'hs_code' => null // Will be set later if needed
                            ]);
                            $materialCount++;
                            Log::info("PO Import: Created new raw material '{$materialName}' with SAP code '{$sapCode}' for row {$rowNumber}");
                        } else {
                            $errors[] = "Row {$rowNumber}: Could not create raw material '{$materialName}' - material group '{$materialGroup}' is required";
                            continue;
                        }
                    }
                    
                    // Find origin country
                    $country = null;
                    if (!empty($originCountry)) {
                        $country = Country::where('name', 'LIKE', "%{$originCountry}%")
                                         ->orWhere('prefix', $originCountry)
                                         ->first();
                    }
                    
                    // Find shipping unit
                    $shippingUnitRecord = null;
                    if (!empty($shippingUnit)) {
                        $shippingUnitRecord = ShippingUnit::where('name', 'LIKE', "%{$shippingUnit}%")->first();
                    }
                    
                    // Parse amendment date
                    $parsedAmendmentDate = null;
                    if (!empty($amendmentDate)) {
                        try {
                            if (is_numeric($amendmentDate)) {
                                // Excel date serial number
                                $parsedAmendmentDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($amendmentDate)->format('Y-m-d');
                            } else {
                                // Try parsing as d/m/Y first
                                try {
                                    $parsedAmendmentDate = Carbon::createFromFormat('d/m/Y', $amendmentDate)->format('Y-m-d');
                                } catch (\Exception $e) {
                                    // Fallback to generic parse
                                    $parsedAmendmentDate = Carbon::parse($amendmentDate)->format('Y-m-d');
                                }
                            }
                        } catch (\Exception $e) {
                            // Leave as null if parsing fails
                            $parsedAmendmentDate = null;
                        }
                    }
                    
                    // Create PO Detail
                    $rowNo = $poHeader->details()->count() + 1;
                    
                    PODetail::create([
                        'po_header_id' => $poHeader->id,
                        'raw_material_id' => $rawMaterial->id,
                        'qty' => $quantity,
                        'shipping_unit_id' => $shippingUnitRecord ? $shippingUnitRecord->id : null,
                        'origin_country_id' => $country ? $country->id : null,
                        'item_due_date' => $parsedDueDate, // Use due date from sheet
                        'amendment_date' => $parsedAmendmentDate,
                        'row_no' => $rowNo,
                        'line_number' => $lineNumber ?: null
                    ]);
                    
                    $detailCount++;
                }
            });

            // Log any import errors
            if (!empty($errors)) {
                foreach ($errors as $err) {
                    Log::warning('PO Import: ' . $err);
                }
            }
            // Prepare response message
            $message = "Import completed successfully! Created {$poCount} purchase orders, {$detailCount} details";
            if ($updatedPOs > 0) {
                $message .= ", updated {$updatedPOs} existing POs";
            }
            if ($skippedPOs > 0) {
                $message .= ", skipped {$skippedPOs} POs (linked to inbounds)";
            }
            if ($materialCount > 0) {
                $message .= ", {$materialCount} raw materials";
            }
            if ($materialGroupCount > 0) {
                $message .= ", {$materialGroupCount} material groups";
            }
            $message .= ".";
            
            // Check if this is an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'errors' => $errors,
                    'stats' => [
                        'po_count' => $poCount,
                        'updated_po_count' => $updatedPOs,
                        'skipped_po_count' => $skippedPOs,
                        'detail_count' => $detailCount,
                        'material_count' => $materialCount,
                        'material_group_count' => $materialGroupCount
                    ]
                ]);
            }
            
            // Traditional redirect for non-AJAX requests
            if (!empty($errors)) {
                return redirect()->route('purchase-orders.index')
                                 ->with('success', $message)
                                 ->with('import_errors', $errors);
            }
            return redirect()->route('purchase-orders.index')
                             ->with('success', $message);

        } catch (\Exception $e) {
            // Log exception
            Log::error('PO import failed: ' . $e->getMessage());
            
            // Check if this is an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Import failed: ' . $e->getMessage(),
                    'errors' => ['Exception: ' . $e->getMessage()]
                ], 500);
            }
            
            // Traditional redirect for non-AJAX requests
            return redirect()->back()
                             ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Download Excel template for PO import
     */
    public function downloadTemplate()
    {
        $this->authorize('create', POHeader::class);
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers based on the new format
        $headers = [
            'Order Date',
            'Sap Code',
            'Material Group',
            'Material',
            'PO Number',
            'Line Number',
            'Due Date',
            'Amendment Date',
            'Qty',
            'Shipping Unit',
            'Supplier',
            'Incoterm',
            'Origin'
        ];
        
        $sheet->fromArray($headers, null, 'A1');
        
        // Add sample data based on your example
        $sampleData = [
            ['Jun-25', 'RA0766', 'CH', 'COBALT BORON COMPLEX SALT', '4600076032', '20', '20-Aug', '20-Sep', '6.4', 'Tons', 'SHEPHERD', 'CIF', 'France'],
            ['Jul-25', 'RA0767', 'PL', 'ZINC OXIDE', '4600076033', '30', '25-Aug', '', '10.2', 'MT', 'ACME CORP', 'FOB', 'Germany'],
            ['Aug-25', 'RA0768', 'AD', 'POLYMER BASE (NEW)', '4600076034', '15', '15-Sep', '30-Sep', '25.0', 'KG', 'POLYMER LTD', 'EXW', 'Italy']
        ];
        
        $sheet->fromArray($sampleData, null, 'A2');
        
        // Add a note about auto-creation
        $sheet->setCellValue('A6', 'Note: If a Material or Material Group does not exist in the system, it will be automatically created.');
        $sheet->getStyle('A6')->getFont()->setBold(true)->setItalic(true);
        $sheet->getStyle('A6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A6')->getFill()->getStartColor()->setARGB('FFFFE599');
        
        // Auto-size columns
        foreach (range('A', 'M') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        $filename = 'po_import_template.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);
        
        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Get raw materials for a specific person in charge
     */
    public function getMaterialsByPerson(Request $request)
    {
        $personId = $request->get('person_id');
        
        if (empty($personId)) {
            return response()->json(['materials' => []]);
        }

        $person = User::find($personId);
        if (!$person) {
            return response()->json(['materials' => []]);
        }

        $materialGroupIds = $person->materialGroups()->pluck('material_groups.id');
        $materials = RawMaterial::whereIn('material_group_id', $materialGroupIds)
                                ->orderBy('name')
                                ->get(['id', 'name', 'sap_code']);

        return response()->json(['materials' => $materials]);
    }

    /**
     * Get persons in charge for multiple selected raw materials
     */
    public function getPersonsByMaterials(Request $request)
    {
        $materialIds = $request->get('material_ids', []);
        
        if (empty($materialIds)) {
            return response()->json(['persons' => []]);
        }

        // Get all material group IDs for the selected materials
        $materialGroupIds = RawMaterial::whereIn('id', $materialIds)
                                      ->pluck('material_group_id')
                                      ->unique();

        // Get persons who are associated with any of these material groups
        $persons = User::whereHas('materialGroups', function ($query) use ($materialGroupIds) {
                        $query->whereIn('material_groups.id', $materialGroupIds);
                    })
                    ->where('is_active', 1)
                    ->whereNull('is_super_admin')
                    ->orderBy('name')
                    ->get(['id', 'name']);

        return response()->json(['persons' => $persons]);
    }

    /**
     * Get persons in charge for a specific raw material
     */
    public function getPersonsByMaterial(Request $request)
    {
        $materialId = $request->get('material_id');
        
        if (empty($materialId)) {
            return response()->json(['persons' => []]);
        }

        $material = RawMaterial::find($materialId);
        if (!$material) {
            return response()->json(['persons' => []]);
        }

        $persons = User::whereHas('materialGroups', function ($query) use ($material) {
                        $query->where('material_groups.id', $material->material_group_id);
                    })
                    ->where('is_active', 1)
                    ->whereNull('is_super_admin')
                    ->orderBy('name')
                    ->get(['id', 'name']);

        return response()->json(['persons' => $persons]);
    }
}
