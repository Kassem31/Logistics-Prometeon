<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('test',function(){
    dd(bcrypt('123456'));
    //App\Menu::generate();
});
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'DashboardController@index')->name('home');
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
    Route::post('/dashboard/refresh', 'DashboardController@refreshData')->name('dashboard.refresh');
    
    Route::get('user/reset-password','Auth\ResetPasswordController@showResetForm')->name('user.resetpassword');
    Route::post('user/reset-password','Auth\ResetPasswordController@resetPassword');

    Route::resource('users', 'UserController');
    Route::resource('roles', 'RolesController');
    Route::resource('suppliers', 'SupplierController');
    Route::resource('inco-forwarders', 'IncoForwardersController');
    Route::resource('shipping-line', 'ShippingLineController');
    Route::resource('brokers', 'BrokerController');
    Route::resource('container-sizes', 'ContainerSizeController');
    Route::resource('custom-systems', 'CustomSystemController');
    Route::resource('shipping-unit', 'ShippingUnitController');
    Route::resource('load-types', 'LoadTypesController');
    Route::resource('inco-terms', 'IncoTermsController');
    Route::resource('raw-materials', 'RawMaterialController');
    Route::resource('material-groups', 'MaterialGroupController');
    Route::resource('ports', 'PortController');
    Route::resource('countries', 'CountryController');
    Route::resource('inbound', 'ShippingController');
    Route::resource('banks', 'BanksController');
    Route::resource('insurance-companies', 'InsuranceCompanyController');
    
    // Purchase Orders routes
    // Purchase Orders import routes
    Route::get('purchase-orders/import', 'POController@importForm')->name('purchase-orders.import');
    Route::post('purchase-orders/import', 'POController@import')->name('purchase-orders.import.process');
    Route::get('purchase-orders/template', 'POController@downloadTemplate')->name('purchase-orders.template');
    Route::get('purchase-orders/materials-by-person', 'POController@getMaterialsByPerson')->name('purchase-orders.materials-by-person');
    Route::get('purchase-orders/persons-by-material', 'POController@getPersonsByMaterial')->name('purchase-orders.persons-by-material');
    Route::get('purchase-orders/persons-by-materials', 'POController@getPersonsByMaterials')->name('purchase-orders.persons-by-materials');
    Route::resource('purchase-orders', 'POController')->except(['show']);
    
    Route::resource('inbound-banks', 'InboundBankController');

});
Auth::routes(['register' => false]);

