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
use Illuminate\Support\Facades\Log;

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
            
            // For newly created inbound, explicitly set current step to 2
            // Since inbound details are already saved, move to booking step
            $inbound->currentStep = 2;
            $inbound->save();
        });
       return redirect()->route('inbound.edit',['inbound'=>$inbound])->with('success','Inbound Created Successfully')->with('redirect_to_step', 2);
    }

    public function edit(Request $request, Inbound $inbound){
        $this->authorize(__FUNCTION__,ShippingBasic::class);
        
        // Check if this is a newly created inbound (created within last 5 minutes and no booking data with ID)
        $isNewlyCreated = $inbound->created_at->diffInMinutes(now()) < 5 && !$inbound->booking->id;
        
        if (!$isNewlyCreated) {
            // Only recalculate current step for existing inbounds
            $inbound->calcCurrentStep();
        }
        
        // If step is provided in the request (e.g., after creating new inbound), override the calculated step
        if($request->has('step')) {
            $inbound->currentStep = (int) $request->input('step');
        }
        
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
        
        // Get PO incoterm and origin countries from PO details
        $poIncoterm = $po->load('incoterm')->incoterm;
        $poOriginCountries = $inbound->details()->with('poDetail.originCountry')->get()
            ->pluck('poDetail.originCountry')->filter()->unique('id');
        
        // Filter available materials by origin if existing materials have a specific origin
        if($poOriginCountries->isNotEmpty()) {
            // Get the first origin country (they should all be the same)
            $existingOriginId = $poOriginCountries->first()->id;
            
            // Filter materials to only include those from the same origin and not already selected
            $oldRawMaterials = $rawMaterials
                ->where('origin_country_id', $existingOriginId)
                ->whereNotIn('id', $inbound->details->pluck('po_detail_id')->all());
        } else {
            // No existing materials or no origin info, show all available materials
            $oldRawMaterials = $rawMaterials->whereNotIn('id', $inbound->details->pluck('po_detail_id')->all());
        }
        
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

        // Determine which step was being saved and redirect to the next step BEFORE recalculating
        $nextStep = $this->getNextStepAfterSaving($request, $inbound);
        
        // Only recalculate current step for non-newly-created inbounds
        $isNewlyCreated = $inbound->created_at->diffInMinutes(now()) < 5 && !$inbound->booking->id;
        if (!$isNewlyCreated) {
            // Calculate current step after updates
            $inbound->calcCurrentStep();
        }
        $inbound->updateStatus();
        
        // Debug logging - remove after testing
        Log::info('Current step after saving: ' . $inbound->currentStep);
        Log::info('Redirecting to step: ' . $nextStep);
        
        // Redirect to edit with specific step navigation
        return redirect()->route('inbound.edit',['inbound'=>$inbound])
            ->with('success','Inbound Updated Successfully')
            ->with('redirect_to_step', $nextStep);
    }

    public function show(Inbound $inbound){
        $this->authorize(__FUNCTION__,ShippingBasic::class);
        
        // Calculate current step to ensure it's up to date
        $inbound->calcCurrentStep();
        
        $inbound->load('details.poDetail.rawMaterial','details.poDetail.shippingUnit');
        $pos = POHeader::where('status','Open')->get();
        $po = POHeader::find(old('inbound.po_header_id',$inbound->po_header_id));
        $counter = 1;
        $rawMaterials = $po->load('details.rawMaterial','details.shippingUnit')->details;
        
        // Get PO incoterm and origin countries from PO details
        $poIncoterm = $po->load('incoterm')->incoterm;
        $poOriginCountries = $inbound->details()->with('poDetail.originCountry')->get()
            ->pluck('poDetail.originCountry')->filter()->unique('id');
        
        // Filter available materials by origin if existing materials have a specific origin
        if($poOriginCountries->isNotEmpty()) {
            // Get the first origin country (they should all be the same)
            $existingOriginId = $poOriginCountries->first()->id;
            
            // Filter materials to only include those from the same origin and not already selected
            $oldRawMaterials = $rawMaterials
                ->where('origin_country_id', $existingOriginId)
                ->whereNotIn('id', $inbound->details->pluck('po_detail_id')->all());
        } else {
            // No existing materials or no origin info, show all available materials
            $oldRawMaterials = $rawMaterials->whereNotIn('id', $inbound->details->pluck('po_detail_id')->all());
        }
        
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

    private function getNextStepAfterSaving(Request $request, Inbound $inbound) {
        // Debug logging to understand what's happening
        Log::info('=== getNextStepAfterSaving Debug ===');
        Log::info('Inbound ID: ' . $inbound->id);
        Log::info('Inbound created_at: ' . $inbound->created_at);
        Log::info('Minutes since creation: ' . $inbound->created_at->diffInMinutes(now()));
        Log::info('Booking ID: ' . ($inbound->booking->id ?? 'null'));
        Log::info('Request has current_wizard_step: ' . ($request->has('current_wizard_step') ? 'yes' : 'no'));
        if($request->has('current_wizard_step')) {
            Log::info('current_wizard_step value: ' . $request->input('current_wizard_step'));
        }
        Log::info('All request data: ', $request->all());
        
        // First, check if we have a current step indicator from the form
        if($request->has('current_wizard_step')) {
            $currentStep = (int) $request->input('current_wizard_step');
            Log::info('Current wizard step from form: ' . $currentStep);
            
            // Return the next step based on current step
            switch($currentStep) {
                case 1: // Inbound Details
                    Log::info('On Inbound Details step - redirecting to Booking Data');
                    return 2;
                case 2: // Booking Data
                    Log::info('On Booking Data step - redirecting to Shipping Details');
                    return 3;
                case 3: // Shipping Details
                    Log::info('On Shipping Details step - redirecting to Documents Cycle');
                    return 4;
                case 4: // Documents Cycle
                    Log::info('On Documents Cycle step - redirecting to Clearance Details');
                    return 5;
                case 5: // Clearance Details
                    Log::info('On Clearance Details step - redirecting to Delivery Details');
                    return 6;
                case 6: // Delivery Details
                    Log::info('On Delivery Details step - redirecting to Bank');
                    return 7;
                case 7: // Bank
                    Log::info('On Bank step - staying on Bank');
                    return 7;
                default:
                    Log::info('Unknown step: ' . $currentStep);
                    break;
            }
        }
        
        // Check for inbound details (materials) - this should be checked FIRST for newly created inbounds
        if($request->has('detail') && is_array($request->input('detail')) && !empty(array_filter($request->input('detail'), function($item) {
            return !empty($item['po_detail_id']);
        }))) {
            // Check if this is a newly created inbound
            $isNewlyCreated = $inbound->created_at->diffInMinutes(now()) < 5 && !$inbound->booking->id;
            if($isNewlyCreated) {
                Log::info('Detected newly created inbound with details being saved - forcing redirect to Booking Data');
                return 2; // Force redirect to Booking Data for newly created inbounds
            }
            Log::info('Detected inbound details being saved - redirecting to Booking Data');
            return 2; // Booking Data
        }
        
        // Fallback: Determine step based on form data submitted
        Log::info('No current_wizard_step found, using form data detection');
        
        // Check for shipping-related data (containers, shipping details)
        if($request->has('container') || $request->has('container_edit') || $request->has('container_delete') ||
           ($request->has('shipping') && is_array($request->input('shipping')) && !empty(array_filter($request->input('shipping'))))) {
            Log::info('Detected shipping step being saved - redirecting to Documents Cycle');
            return 4; // Documents Cycle
        }
        
        // Check for booking data
        if($request->has('book') && is_array($request->input('book')) && !empty(array_filter($request->input('book')))) {
            Log::info('Detected booking step being saved - redirecting to Shipping Details');
            return 3; // Shipping Details
        }
        
        // Check for document data
        if($request->has('document') && is_array($request->input('document')) && !empty(array_filter($request->input('document')))) {
            Log::info('Detected document step being saved - redirecting to Clearance Details');
            return 5; // Clearance Details
        }
        
        // Check for clearance data
        if($request->has('clear') && is_array($request->input('clear')) && !empty(array_filter($request->input('clear')))) {
            Log::info('Detected clearance step being saved - redirecting to Delivery Details');
            return 6; // Delivery Details
        }
        
        // Check for delivery data
        if($request->has('deliver') && is_array($request->input('deliver')) && !empty(array_filter($request->input('deliver')))) {
            Log::info('Detected delivery step being saved - redirecting to Bank');
            return 7; // Bank
        }
        
        // Check for bank data
        if($request->has('bank') && is_array($request->input('bank')) && !empty(array_filter($request->input('bank')))) {
            Log::info('Detected bank step being saved - staying on Bank');
            return 7; // Stay on Bank (complete)
        }
        
        // Default: redirect to the next step based on current step calculation
        Log::info('No specific step detected - using calculated current step: ' . $inbound->currentStep);
        return $inbound->currentStep;
    }
}
