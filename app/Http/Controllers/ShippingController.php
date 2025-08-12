<?php

namespace App\Http\Controllers;

use App\Filters\Inbound\InboundIndexFilter;
use App\Models\Bank;
use App\Models\User;
use App\Models\Port;
use App\Models\Broker;
use App\Models\ShippingBasic;
use App\Models\Country;
use App\Models\Inbound;
use App\Models\IncoTerm;
use App\Models\POHeader;
use App\Models\Supplier;
use App\Models\RawMaterial;
use Illuminate\Support\Arr;
use App\Models\CustomSystem;
use App\Models\ShippingLine;
use App\Models\ShippingUnit;
use Illuminate\Http\Request;
use App\Models\ContainerSize;
use App\Models\IncoForwarder;
use App\Models\ContainerLoadType;
use App\Models\InsuranceCompany;
use App\Models\ShippingBasicInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShippingController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,ShippingBasic::class);
        $user = Auth::user();
        if(is_null($user->is_super_admin)){
            $inbounds = Inbound::filter(new InboundIndexFilter(request()))->whereHas('po_header',function($q) use($user){
                return $q->where('person_in_charge_id',$user->id);
            })->with('po_header.pic','booking')->paginate(30);
            $persons = [];
        }else{
            $inbounds = Inbound::filter(new InboundIndexFilter(request()))->with('po_header.pic','booking')->paginate(30);
            $persons = User::where('is_active',1)->whereNull('is_super_admin')->orderBy('name')->get();
        }

        $steps = (new Inbound())->steps;
        return view('shipping.index',[
            'inbounds'=>$inbounds,
            'persons'=>$persons,
            'steps'=>$steps
        ]);
    }
    public function create(){
        $this->authorize(__FUNCTION__,ShippingBasic::class);
       $user = Auth::user();
       if(is_null($user->is_super_admin)){
        $pos = POHeader::where('status','Open')->where('person_in_charge_id',$user->id)->get();
       }else{
        $pos = POHeader::where('status','Open')->get();
       }
       
       $po = POHeader::find(old('inbound.po_header_id'));
       $counter = 1;
        if(is_null($po)){
            $rawMaterials = collect([]);
            $oldRawMaterials = collect([]);
        }else{
            $detail = collect(old('detail',[]))->filter(function($item){
                return !is_null($item['po_detail_id']);
            });
           $rawMaterials = $po->load('details.rawMaterial','details.shippingUnit')->details;
           $oldRawMaterials =  $rawMaterials->whereNotIn('id',$detail->pluck('po_detail_id')->all());
            $counter = count($rawMaterials)+1;
        }
        $oldDetails = collect(old('detail',[]))->filter(function($item){
            return !is_null($item['po_detail_id']);
        });
        return view('shipping.wizard.create',[
            'pos'=>$pos,
            'inbound'=>new Inbound(),
            'rawMaterials'=>$rawMaterials,
            'oldRawMaterials'=>$oldRawMaterials,
            'counter'=>$counter,
            'oldDetails'=>$oldDetails
        ]);
    }

    public function store(Request $request){
        // dd($request->input());
        $this->authorize(__FUNCTION__,ShippingBasic::class);
        $detail = collect($request->input('detail'))->filter(function($item){
            return !is_null($item['po_detail_id']);
        });
        $request->merge([
            'detail'=>$detail->all()
        ]);
        $this->validate($request,$this->rules());
        $inbound = null;
        DB::transaction(function () use($request,&$inbound){
            $details =  collect($request->input('detail'))->map(function($item){
                return [
                    'po_detail_id'=>$item['po_detail_id'],
                    'qty'=>$item['qty']
                ];
            });
            $inbound = Inbound::create($request->input('inbound'));
            if(count($details)>0){
                $inbound->details()->createMany($details->all());
            }
        });
       return redirect()->route('inbound.edit',['inbound'=>$inbound])->with('success','Inbound Created Successfully');
    }

    public function edit(Inbound $inbound){
        $this->authorize(__FUNCTION__,ShippingBasic::class);
        $inbound->load('details.poDetail.rawMaterial','details.poDetail.shippingUnit');
        $user = Auth::user();
        if(is_null($user->is_super_admin)){
            $pos = POHeader::where('status','Open')->where('person_in_charge_id',$user->id)->orWhere('id',$inbound->po_header_id)->get();
        }else{
            $pos = POHeader::where('status','Open')->orWhere('id',$inbound->po_header_id)->get();

        }
        $po = POHeader::find(old('inbound.po_header_id',$inbound->po_header_id));
        $counter = 1;
        $rawMaterials = $po->load('details.rawMaterial','details.shippingUnit')->details;
        $oldRawMaterials =  $rawMaterials->whereNotIn('id',$inbound->details->pluck('po_detail_id')->all());
        
        // Get PO incoterm and origin countries from PO details
        $poIncoterm = $po->load('incoterm')->incoterm;
        $poOriginCountries = $inbound->details()->with('poDetail.originCountry')->get()
            ->pluck('poDetail.originCountry')->filter()->unique('id');
        
        $units = ShippingUnit::get();
        $sizes = ContainerSize::get();
        $loadTypes = ContainerLoadType::get();
        $countries = Country::orderBy('name')->get();
        $incoTerms = IncoTerm::where('is_active',1)->get();
        $incoForwarder = IncoForwarder::where('is_active',1)->get();
        $currency = Country::where('currency_is_active',1)->get(['id','currency'])->unique('currency');
        $lines = ShippingLine::where('is_active',1)->get();
        $ports = Port::where('country_id', $poOriginCountries->count() == 1 ? $poOriginCountries->first()->id : old('shipping.origin_country_id',$inbound->shipping->origin_country_id))->orderBy('name')->get(['id','name']);
        $customs = CustomSystem::orderBy('name')->get();
        $brokers = Broker::orderBy('name')->get();
        $currentPercent = $inbound->getPercent();
        $insuranceCompanies = InsuranceCompany::get();
        $banks = Bank::where('is_active',1)->get();
        $booking = $inbound->booking;
        
        $incoTerm = optional($inbound->shipping)->incoTerm ?? $poIncoterm;
        $incoPrefix = optional($incoTerm)->prefix ?? '';
        $hideForworder = in_array(strtolower($incoPrefix),['cif','cfr','ddu']);
        $hideInsurance = strtolower($incoPrefix) == 'cif';
        // dd(old());
        return view('shipping.wizard.edit',[
            'pos'=>$pos,
            'inbound'=>$inbound,
            'rawMaterials'=>$rawMaterials,
            'oldRawMaterials'=>$oldRawMaterials,
            'counter'=>$counter,
            'rawMaterials'=>$rawMaterials,
            'sizes'=>$sizes,
            'loadTypes'=>$loadTypes,
            'coutries'=>$countries,
            'units'=>$units,
            'incoTerms'=>$incoTerms,
            'incoForwarder'=>$incoForwarder,
            'currency'=>$currency,
            'lines'=>$lines,
            'customs'=>$customs,
            'ports'=>$ports,
            'brokers'=>$brokers,
            'insuranceCompanies'=>$insuranceCompanies,
            'currentPercent'=>$currentPercent,
            'banks'=>$banks,
            'book'=>$booking,
            'shipping'=>$inbound->shipping,
            'clear'=>$inbound->clearance,
            'document'=>$inbound->document,
            'deliver'=>$inbound->delivery,
            'hideForworder'=>$hideForworder,
            'hideInsurance'=>$hideInsurance,
            'poIncoterm'=>$poIncoterm,
            'poOriginCountries'=>$poOriginCountries
        ]);
    }

    public function update(Request $request,Inbound $inbound){
        $this->authorize(__FUNCTION__,ShippingBasic::class);
        $detail = collect($request->input('detail'))->filter(function($item){
            return !is_null($item['po_detail_id']);
        });
        // $containers = collect($request->input('container'))->filter(function($item){
        //     return !is_null($item['container_no']) && !is_null($item['container_size_id']) && !is_null($item['load_type_id']);
        // });
        // $container_edit = collect($request->input('container_edit'))->filter(function($item){
        //     return !is_null($item['container_no']) && !is_null($item['container_size_id']) && !is_null($item['load_type_id']);
        // });
        $request->merge([
            'detail'=>$detail->all(),
        ]);
        $this->validate($request,$this->rules(true,$inbound),[
            'shipping.other_shipping_line.required_if'=>'Other Shipping Line is Required when Other is selected'
        ]);
        $inbound->updateDetails($request);
        $inbound->updateDocument($request);
        $inbound->updateBooking($request);
        $inbound->updateShipping($request);
        $inbound->updateClearance($request);
        $inbound->updateBank($request);
        $inbound->updateDelivery($request);

        return redirect()->route('inbound.edit',['inbound'=>$inbound])->with('success','Inbound Updated Successfully');
    }

    public function show(Inbound $inbound){
        $this->authorize(__FUNCTION__,ShippingBasic::class);
        $inbound->load('details.poDetail.rawMaterial','details.poDetail.shippingUnit');
        $pos = POHeader::where('status','Open')->get();
        $po = POHeader::find(old('inbound.po_header_id',$inbound->po_header_id));
        $counter = 1;
        $rawMaterials = $po->load('details.rawMaterial','details.shippingUnit')->details;
        $oldRawMaterials =  $rawMaterials->whereNotIn('id',$inbound->details->pluck('po_detail_id')->all());
        
        // Get PO incoterm and origin countries from PO details
        $poIncoterm = $po->load('incoterm')->incoterm;
        $poOriginCountries = $inbound->details()->with('poDetail.originCountry')->get()
            ->pluck('poDetail.originCountry')->filter()->unique('id');
        
        $units = ShippingUnit::get();
        $sizes = ContainerSize::get();
        $loadTypes = ContainerLoadType::get();
        $countries = Country::orderBy('name')->get();
        $incoTerms = IncoTerm::where('is_active',1)->get();
        $incoForwarder = IncoForwarder::where('is_active',1)->get();
        $currency = Country::where('currency_is_active',1)->get(['id','currency'])->unique('currency');
        $lines = ShippingLine::where('is_active',1)->get();
        $ports = Port::where('country_id', $poOriginCountries->count() == 1 ? $poOriginCountries->first()->id : old('shipping.origin_country_id',$inbound->shipping->origin_country_id))->orderBy('name')->get(['id','name']);
        $customs = CustomSystem::orderBy('name')->get();
        $brokers = Broker::orderBy('name')->get();
        $currentPercent = $inbound->getPercent();
        $insuranceCompanies = InsuranceCompany::get();
        $banks = Bank::where('is_active',1)->get();
        $booking = $inbound->booking;
        
        $incoTerm = optional($inbound->shipping)->incoTerm ?? $poIncoterm;
        $incoPrefix = optional($incoTerm)->prefix ?? '';
        $hideForworder = in_array(strtolower($incoPrefix),['cif','cfr','ddu']);
        $hideInsurance = strtolower($incoPrefix) == 'cif';
        // dd(old());
        return view('shipping.wizard.show',[
            'pos'=>$pos,
            'inbound'=>$inbound,
            'rawMaterials'=>$rawMaterials,
            'oldRawMaterials'=>$oldRawMaterials,
            'counter'=>$counter,
            'rawMaterials'=>$rawMaterials,
            'sizes'=>$sizes,
            'loadTypes'=>$loadTypes,
            'coutries'=>$countries,
            'units'=>$units,
            'incoTerms'=>$incoTerms,
            'incoForwarder'=>$incoForwarder,
            'currency'=>$currency,
            'lines'=>$lines,
            'customs'=>$customs,
            'ports'=>$ports,
            'brokers'=>$brokers,
            'insuranceCompanies'=>$insuranceCompanies,
            'currentPercent'=>$currentPercent,
            'banks'=>$banks,
            'book'=>$booking,
            'shipping'=>$inbound->shipping,
            'clear'=>$inbound->clearance,
            'document'=>$inbound->document,
            'deliver'=>$inbound->delivery,
            'hideForworder'=>$hideForworder,
            'hideInsurance'=>$hideInsurance,
            'poIncoterm'=>$poIncoterm,
            'poOriginCountries'=>$poOriginCountries
        ]);
    }
    protected function rules($is_update = false,$model = null){
        $rules =  [
            'inbound.po_header_id'=>'required',
            'inbound.inbound_no'=>'nullable|unique:inbounds,inbound_no',
            'inbound.acid_number'=>'required|string|max:50|regex:/^[A-Za-z0-9]+$/',
            'detail.*.po_detail_id'=>'required',
            'detail.*.qty'=>['required','numeric',function($attr,$value,$fail){
                if($value <= 0){
                    $fail('Qty Must be greater than Zero.');
                }
            }],
            // 'container.*.number'=>'required',
            // 'container.*.size'=>'required',
            // 'container.*.load'=>'required',
            // 'basic.order_date'=>'required',
            // 'basic.due_date'=>'required',
            // 'basic.person_in_charge_id'=>'required',
            'shipping.other_shipping_line'=>'required_if:shipping.shipping_line_id,0',
             'shipping.rate'=>'nullable|numeric',
             'bank.lg_amount'=>'nullable|numeric',
             'bank.amount'=>'nullable|numeric',
             'clear.received_accounting_date'=>'nullable|date_format:d/m/Y',
             'clear.invoicing_date'=>'nullable|date_format:d/m/Y',

            // 'basic.qty'=>'nullable|numeric',
            // 'basic.container_count'=>'nullable|numeric',
        ];
        if($is_update){
            $rules = array_merge($rules, [
                'inbound.inbound_no'=>'nullable|unique:inbounds,inbound_no,'.$model->id
            ]);
        }
        return $rules;
    }
}
