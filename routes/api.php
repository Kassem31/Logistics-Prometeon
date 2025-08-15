<?php

use App\Models\Inbound;
use App\Models\MaterialGroup;
use App\Models\POHeader;
use App\Models\RawMaterial;
use Faker\Provider\en_UG\Person;
use Illuminate\Http\Request;
use App\Models\ShippingBasicInfo;
use Illuminate\Support\Facades\View;
use SebastianBergmann\Environment\Console;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('user/{username}',function($username){
  return App\Models\User::where('name',$username)->with('roles')->first();
});

Route::get('role/{role}',function($role){
  return App\Role::where('name',$role)->with('permissions')->first();
});

Route::get('broker/{name}',function($name){
  return App\Models\Broker::where('name',$name)->first();
});

Route::get('inco-forarders/{name}',function($name){
  return App\Models\IncoForwarder::where('name',$name)->first();
});
Route::get('port/{name}',function($name){
  return App\Models\Port::where('name',$name)->first();
});

Route::get('load-types/{name}',function($name){
  return App\Models\ContainerLoadType::where('name',$name)->first();
});

Route::get('inco-terms/{name}',function($name){
  return App\Models\IncoTerm::where('name',$name)->first();
});

Route::get('raw-materials/{sap_code}',function($sap_code){
  return App\Models\RawMaterial::where('sap_code',$sap_code)->first();
});

Route::get('shipping-line/{name}',function($name){
  return App\Models\ShippingLine::where('name',$name)->first();
});

Route::get('suppliers/{sap_code}',function($sap_code){
  return App\Models\Supplier::where('sap_code',$sap_code)->first();
});

Route::get('material-group/{id}',function($id){
  return App\Models\MaterialGroup::with('users')->find($id);
});

Route::get('material-groups/{iname}',function($name){
  return App\Models\MaterialGroup::with('users')->where('name',$name)->first();
});

Route::get('country/ports/',function(Request $request){
    return App\Models\Port::where('country_id',$request->input('country'))->orderBy('name')->get(['id','name']);
  })->name('api.country.ports');

Route::get('group/users',function(Request $request){
      $groups = optional(RawMaterial::find($request->input('material')))->materialGroup;
      if(is_null($groups))return [];
      return $groups->load('users')->users;
  })->name('api.gruop.users');
Route::post('log',function(Request $request){
    $shipping = Inbound::find($request->input('shipping'));
    $log = $shipping->getLogFor($request->input('field'));
    $view = View::make('_log-table', ['log' => $log]);
    return $view->render();
})->name('api.log');

Route::get('shipping/{phase_name}/{sap_inbound}',function($phase_name,$sap_inbound){
  switch ($phase_name){
    case 'booking_data':
      return (string) App\Models\ShippingBasicInfo::where('sap_inbound',$sap_inbound)->first()->canGoBooking();
    break;
    case 'document_cycle':
      return (string) App\Models\ShippingBasicInfo::where('sap_inbound',$sap_inbound)->first()->canGoDocumentCycle();
    break;
    case 'delivery':
    return (string) App\Models\ShippingBasicInfo::where('sap_inbound',$sap_inbound)->first()->canGoDelivery();
  }
});

Route::get('shipping/getInfo/{phase}/{sap_inbound}',function($phase,$sap_inbound){
  switch($phase){
    case 'basic_info':
      return App\Models\ShippingBasicInfo::where('sap_inbound',$sap_inbound)->first();
    break;
    case 'booking_data':
      return App\Models\ShippingBasicInfo::where('sap_inbound',$sap_inbound)->first()->booking;
  }
});

Route::get('pic/materials',function(Request $request){
    $pic = App\Models\User::find($request->input('user'));
    if(is_null($pic)){
        $rawMaterials = [];
    }else{
        $groups = $pic->load('materialGroups')->materialGroups->pluck('id');
        $rawMaterials = RawMaterial::whereIn('material_group_id',$groups)->get();
    }
    return $rawMaterials;
});

Route::get('po/materials',function(Request $request){
    $po = POHeader::find($request->input('po'));
    if(is_null($po)){
        $rawMaterials = [];
    }else{
        $rawMaterials = $po->load('details.rawMaterial','details.shippingUnit')->details;
    }
   return $rawMaterials;
});

Route::get('po/materials-by-origin',function(Request $request){
    $po = POHeader::find($request->input('po'));
    $originCountryId = $request->input('origin');
    
    if(is_null($po)){
        $rawMaterials = [];
    }else{
        // Load PO details and filter by origin country if provided
        $query = $po->details()->with(['rawMaterial','shippingUnit']);
        
        if($originCountryId) {
            $query->where('origin_country_id', $originCountryId);
        }
        
        $rawMaterials = $query->get();
    }
   return $rawMaterials;
});

Route::get('banks/{id}',function($id){
    return App\Models\Bank::find($id);
});


Route::get('bank/{name}',function($name){
    return App\Models\Bank::where('name',$name)->first();
});

Route::get('insurance_companies/{id}',function($id){
    return App\Models\InsuranceCompany::find($id);
});

Route::get('purchase-orders/{po_number}',function($po_number){
    return App\Models\POHeader::where('po_number',$po_number)->with('details')->first();
});

Route::get('inbound/{inbound_no}',function($inbound_no){
    return App\Models\Inbound::where('inbound_no',$inbound_no)->with('details')->first();
});
