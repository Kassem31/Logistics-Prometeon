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

class POController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,POHeader::class);
        $suppliers  = Supplier::orderBy('name')->where('is_active',1)->get();
        $incoTerms = IncoTerm::orderBy('name')->get();
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
        ]);
    }

    public function create(){
        $this->authorize(__FUNCTION__,POHeader::class);
        $suppliers  = Supplier::orderBy('name')->where('is_active',1)->get();
        $incoTerms = IncoTerm::all();
        $countries = Country::orderBy('name')->get();
        $persons = User::where('is_active',1)->whereNull('is_super_admin')->orderBy('name')->get();
        $shippingUnits = ShippingUnit::get();
        $pic = User::find(old('basic.person_in_charge_id'));

        if(is_null($pic)){
            $rawMaterials = [];
        }else{
            $groups = $pic->load('materialGroups')->materialGroups->pluck('id');
            $rawMaterials = RawMaterial::whereIn('material_group_id',$groups)->get();
        }
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
        // Convert date formats for detail items
        $details = $request->input('detail', []);
        foreach ($details as $key => $detail) {
            if (!empty($detail['item_due_date'])) {
                try {
                    $details[$key]['item_due_date'] = Carbon::createFromFormat('d/m/Y', $detail['item_due_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    // Keep original value if conversion fails
                }
            }
            if (!empty($detail['amendment_date'])) {
                try {
                    $details[$key]['amendment_date'] = Carbon::createFromFormat('d/m/Y', $detail['amendment_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    // Keep original value if conversion fails
                }
            }
        }
        $request->merge(['detail' => $details]);
        
        $this->validate($request,$this->rules());
        DB::transaction(function () use($request) {
            $data = $request->input('basic');
            $header = POHeader::create($data);
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
            $rawMaterials = [];
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
            'basic.person_in_charge_id'=>'required',
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
}
