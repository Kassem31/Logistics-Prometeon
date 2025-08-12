<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Carbon\Carbon;
use App\Permission;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Faker\Generator as Faker;


/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('user:add_permission {permission}', function ($permission) {
    $permission = Permission::where('name', $permission)->first();
    if (!is_null($permission)) {
        $role = \App\Role::where('name', 'test')->first();
        optional($role)->detachPermission($permission->id);
        $role = \App\Role::where('name', 'test')->first();
        optional($role)->attachPermissions([$permission->id]);
    }
})->describe('Add permission to test role');

Artisan::command('user:remove_permission {permission}', function ($permission) {
    $permission = Permission::where('name', $permission)->first();
    if (!is_null($permission)) {
        $role = \App\Role::where('name', 'test')->first();
        optional($role)->detachPermission($permission->id);
    }
})->describe('remove permission to test role');

Artisan::command('test:create_user', function () {
    DB::table('users')->insert([
        'full_name' => 'testing user',
        'name' => 'testing',
        'email' => 'test@testing.com',
        'password' => Hash::make(123456),
        'is_active' => 1
    ]);
})->describe('create user');

Artisan::command('test:fake_users', function () {
    $users = factory(App\Models\User::class, 'fake_users', 100)->create();
})->describe('Generate fake users');
Artisan::command('test:create_user2', function () {
    DB::table('users')->insert([
        'full_name' => 'test 2 user',
        'name' => 'testing2',
        'email' => 'test2@testing.com',
        'password' => Hash::make(123456),
        'is_active' => 1
    ]);
})->describe('create user 2');

Artisan::command('test:delete_user2', function () {
    optional(App\Models\User::where('name', 'testing2'))->delete();
})->describe('delete user');

Artisan::command('test:edit_user', function () {
    DB::table('users')->where('name','test')->update([

        'is_active' => 1
    ]);
})->describe('update user');

Artisan::command('test:create_role_testing', function () {
    DB::table('roles')->insert([
        'id' => '9999',
        'name' => 'testing',
        'display_name' => NULL,
        'description' => NULL,

    ]);
})->describe('creates role');

Artisan::command('test:delete_role', function () {
    optional(App\Role::find(9999))->delete();
})->describe('delete role');

Artisan::command('test:create_user_with_role', function () {

    $role = App\Role::where('name', 'testing')->first();
    DB::table('role_user')->insert([
        'user_id' => 9999,
        'role_id' => 9999 ,
        'user_type' => 'App/User'
    ]);
})->describe('add role to user');

Artisan::command('test:create_user1', function () {
    DB::table('users')->insert([

        'id' => 9999,
        'name' => 'test1',
        'full_name' => 'testing1',
        'password' => Hash::make(9999),
        'is_active' => 1
    ]);
})->describe('create user 1');

Artisan::command('test:delete_user1', function () {
    optional(User::find(9999))->delete();
})->describe('deletes user 1');

Artisan::command('test:fake_roles', function () {
    $roles = factory(App\Role::class, 'fake_roles', 300)->create();
})->describe('Generate fake roles');

Artisan::command('test:fake_brokers', function () {
    $roles = factory(App\Models\Broker::class, 'fake_brokers', 300)->create();
})->describe('Generate fake brokers');

Artisan::command('test:edit_broker', function () {
    DB::table('brokers')->insert([
        'id' => 1000,
        'name' => 'testing',
    ]);
})->describe('creates broker');
Artisan::command('test:fake_ports', function () {
    $ports = factory(App\Models\Port::class, 'fake_ports', 300)->create();
})->describe('Generate fake ports');

Artisan::command('test:edit_port', function () {
    DB::table('ports')->insert([
        'id' => 1000,
        'name' => 'new_port',
        'country_id' => 2
    ]);
})->describe('create fake port for edit');


artisan::command('test:create_country',function (){
  DB::table('countries')->insert([
      'id'=> 9999,
      'name'=>'test',
      'prefix'=>'test',
        'currency'=> 'USD',

  ]);

});


artisan::command('test:create_custom_system',function (){
    DB::table('countries')->insert([
        'id'=> 9999,
        'name'=>'test',

    ]);

});


artisan::command('test:create_material_group',function (){
    DB::table('material_groups')->insert([
        'id'=> 9999,
        'name'=>'test',


    ]);

}
);

Artisan::command('test:fake_inco_forwarders', function () {
    $roles = factory(App\Models\IncoForwarder::class, 'fake_inco_forwarders', 300)->create();
})->describe('Generate fake inco forwarders');

Artisan::command('test:edit_inco_forwarder', function () {
    DB::table('inco_forwarders')->insert([
        'id' => 1000,
        'name' => 'testing',
    ]);
})->describe('creates inco forwarder for edit');

Artisan::command('test:activate_inco_forwarder', function () {
    App\Models\IncoForwarder::find(1000)->update([
      'is_active' => 1,
    ]);
})->describe('updates inco forwarder for edit');

Artisan::command('test:fake_container_load_types', function () {
    $container_load_types = factory(App\Models\ContainerLoadType::class, 'fake_container_load_types', 300)->create();
})->describe('Generate fake container_load_types');

Artisan::command('test:edit_container_load_type', function () {
    DB::table('container_load_types')->insert([
        'id' => 1000,
        'name' => 'new_port',
        'prefix' => 'new_port',
    ]);
})->describe('create fake container laod type for edit');


Artisan::command('test:fake_inco_terms', function () {
    $inco_terms = factory(App\Models\IncoTerm::class, 'fake_inco_terms', 300)->create();
})->describe('Generate fake inco terms');

Artisan::command('test:edit_inco_term', function () {
    DB::table('inco_terms')->insert([
        'id' => 1000,
        'name' => 'new_port',
        'prefix' => 'new_port',
        'is_active' => 1
    ]);
})->describe('create fake inco term for edit');

Artisan::command('test:fake_raw_materials', function () {
    $roles = factory(App\Models\RawMaterial::class, 'fake_raw_materials', 300)->create();
})->describe('Generate fake raw materials');

Artisan::command('test:edit_raw_material', function () {
    DB::table('raw_materials')->insert([
        'id' => 1000,
        'name' => 'testraw',
        'sap_code' => 'testraw',
        'material_group_id' => 1,
    ]);
})->describe('creates raw material for edit');

artisan::command('test:create_country',function (){
    DB::table('shipping_units')->insert([
        'id'=> 9999,
        'name'=>'test',
        'prefix'=>'test',

    ]);

});




Artisan::command('test:fake_shipping_lines', function () {
    $shipping_lines = factory(App\Models\ShippingLine::class, 'fake_shipping_lines', 300)->create();
})->describe('Generate fake shipping lines');

Artisan::command('test:edit_shipping_line', function () {
    DB::table('shipping_lines')->insert([
        'id' => 1000,
        'name' => 'testing',
    ]);
})->describe('creates shipping line for edit');


Artisan::command('test:fake_suppliers', function () {
    $suppliers = factory(App\Models\Supplier::class, 'fake_suppliers', 300)->create();
})->describe('Generate fake suppliers');

Artisan::command('test:edit_supplier', function () {
    DB::table('suppliers')->insert([
        'id' => 1000,
        'name' => 'testraw',
        'sap_code' => 'testraw',
    ]);
})->describe('creates supplier for edit');

Artisan::command('test:create_supplier', function () {
    DB::table('suppliers')->insert([
        'name' => 'testing',
        'sap_code' => 'testing',
    ]);
})->describe('creates supplier for edit');

Artisan::command('test:create_user_material', function () {
    DB::table('users')->insert([
        'id' => 2000,
        'name' => 'tester',
        'full_name' => 'testing user',
        'password' => Hash::make(9999),
        'is_active' => 1
    ]);
})->describe('create user for material group');

Artisan::command('test:create_raw_material', function () {
    DB::table('raw_materials')->insert([
        'id' => 99999,
        'sap_code' => '9999',
        'name' => 'testrawmaterial',
        'material_group_id' => 1,
        'hs_code' => 'test'
    ]);
})->describe('create user for material group');

Artisan::command('test:create_supplier1', function () {
    DB::table('suppliers')->insert([
        'id' => 99999,
        'country_id' => 1,
        'sap_code' => '9999',
        'name' => 'testsupplier',
        'contact_person' =>'test',
        'is_active' => 1,
        'is_group' => 1
    ]);
})->describe('create user for material group');

Artisan::command('test:create_user_materialgroup', function () {
    DB::table('material_group_user')->insert([
        'user_id' => 100,
        'material_group_id' => 1,

    ]);
})->describe('');

Artisan::command('test:create_user_shipping', function () {
    DB::table('users')->insert([

        'id' => 100,
        'name' => 'test shipping',
        'full_name' => 'shipping test',
        'password' => Hash::make(9999),
        'is_active' => 1,
        'is_admin' => Null ,
        'is_super_admin' => Null
    ]);
})->describe('create user 1');



Artisan::command('test:assign_user_material', function () {
    $material_group_user = App\Models\MaterialGroup::find(1)->users()->attach(9999);
})->describe('assign user for material group');

Artisan::command('test:fake_shipping_basics', function () {
    $shipping_basic_info = factory(App\Models\ShippingBasicInfo::class, 'fake_shipping_basics', 50)->create();
})->describe('Generate fake shipping_basic_info');

Artisan::command('test:create_shipping', function () {
    DB::table('shipping_basic_infos')->insert([

        'id' => 100,
        'raw_material_id' => 1,
        'person_in_charge_id' => 100,
        'supplier_id' => 99999,
        'sap_inbound' => 123,

    ]);
})->describe('create user 1');



Artisan::command('test:create_shipping_test_docs', function () {
    DB::table('shipping_basic_infos')->insert([

        'id' => 9999,
        'raw_material_id' => 99999,
        'person_in_charge_id' => 100,
        'supplier_id' => 1,
        'sap_inbound' => 1,
        'shipping_unit_id' => 1,
        'container_size_id' => 1,
        'load_type_id' => 1,
        'origin_country_id' => 1,
        'loading_port_id' => 1,
        'inco_term_id' => 1,
        'inco_forwarder_id' => 1,
        'currency_id' => 1,
        'shipping_line_id' => 1,
        'container_count' => 1,
        'rate' => '100',
        'qty' => '100',
        'po_number' => '123',
        'order_date' => '2019-11-07',
        'due_date' => '2019-11-17',
        'vessel_name' => 'test',
        'bl_number' => '100'
    ]);
})->describe('create ');

artisan::command('test:create_shipping_test_docs_2', function () {
    DB::table('shipping_bookings')->insert([

        'id' => 9999,
        'shipping_id' => 9999,
        'ets' => '2019-11-07',
        'eta' => '2019-11-17',
        'ats' => '2019-11-07',
        'ata' => '2019-11-19'
    ]);
})->describe('create ');


Artisan::command('test:create_container_size', function () {
    DB::table('container_sizes')->insert([
        'id' => 1000,
        'size' => '20x30',
    ]);
})->describe('create_container_size');

Artisan::command('test:create_shipping_basic',function(){
  App\Models\ShippingBasicInfo::create([
    'id' => 1000,
    'raw_material_id'=>99999,
    'person_in_charge_id'=> 100,
    'sap_inbound'=> '456123',
    'po_number'=> 'test',
    'qty'=> 52,
    'order_date'=> '12/12/2019',
    'supplier_id'=> 99999,
    'shipping_unit_id'=> 1,
    'container_size_id'=> 1000,
    'load_type_id'=> 1000,
    'origin_country_id'=> 6,
    'loading_port_id'=> 15,
    'inco_term_id'=> 1000,
    'inco_forwarder_id'=> 1000,
    'currency_id'=> 1,
    'shipping_line_id'=> 0,
    'container_count'=> 5,
    'rate'=> '3.32',
    'due_date'=> '12/12/2019',
    'vessel_name'=> 'reujhe',
    'bl_number'=> 'reujhe',
    'other_shipping_line'=> 'new person',
  ]);
})->describe('create shipping basic');

Artisan::command('test:create_booking',function(){
  App\Models\ShippingBasicInfo::find(1000)->booking()->create([
    'ats' => '12/12/2020',
    'created_at' => Carbon::now(),
    'updated_at' => Carbon::now()
  ]);
})->describe('create shipping booking');

Artisan::command('test:update_ata_date',function(){
  App\Models\ShippingBasicInfo::find(1000)->booking()->update([
    'ata' => '23/01/2020',
  ]);
})->describe('update ATA shipping');

Artisan::command('test:create_document_cycle',function(){
  App\Models\ShippingBasicInfo::find(1000)->document()->create([
    'invoice_copy' => '12/12/2020',
    'purchase_confirmation' => '15/12/2020',
    'original_invoice' => '17/12/2020',
    'stamped_invoice' => '18/12/2020',
    'copy_docs' => '19/12/2020',
    'original_docs' => '21/12/2020',
    'copy_docs_broker' => '22/12/2020',
    'original_docs_broker' => '24/12/2020',
    'stamped_invoice_broker' => '25/12/2020',
    'created_at' => Carbon::now(),
    'updated_at' => Carbon::now()
  ]);
})->describe('create shipping document cycle');


Artisan::command('test:fake_pos', function () {
    $pos = factory(App\Models\POHeader::class, 'fake_pos', 100)->create();
})->describe('Generate fake pos');

Artisan::command('test:fake_banks', function () {
    $pos = factory(App\Models\Bank::class, 'fake_banks', 100)->create();
})->describe('Generate fake banks');

Artisan::command('test:edit_bank', function () {
    DB::table('banks')->insert([
        'id' => 1000,
        'name' => 'testing',
        'is_active' => 0
    ]);
})->describe('creates banks');

Artisan::command('test:edit_insurance_company', function () {
    DB::table('insurance_companies')->insert([
        'id' => 1000,
        'name' => 'testing',
        'is_active' => 1
    ]);
})->describe('creates insurance company');


Artisan::command('test:create_fake_po', function () {
  DB::table('p_o_headers')->insert([
      'id' => 1000,
      'supplier_id' => 1000,
      'person_in_charge_id' => 1000,
      'status' => 'Open',
      'po_number' => 'po_number',
      'order_date' =>  \Carbon\Carbon::now(),
      'due_date' =>  \Carbon\Carbon::now(),
      'created_at' => \Carbon\Carbon::now(),
      'updated_at' => \Carbon\Carbon::now(),
  ]);
  DB::table('p_o_details')->insert([
        'id' => 100,
        'row_no' => 1,
        'po_header_id' => 1000,
        'raw_material_id' => 99999,
        'qty' =>100,
        'shipping_unit_id' =>  1,
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ]);
})->describe('Generate fake po');


Artisan::command('test:create_user_materialgroup1', function () {
    DB::table('material_group_user')->insert([
        'user_id' => 2000,
        'material_group_id' => 1,

    ]);
})->describe('');

Artisan::command('test:create_forwarder', function () {
    DB::table('inco_forwarders')->insert([
        'id' => 2000,
        'name'=>'testing',
        'country_id' => 61,
        'is_active'=> 1,

    ]);
})->describe('');

Artisan::command('test:create_insurance', function () {
    DB::table('insurance_companies')->insert([
        'id' => 2000,
        'name'=>'testing',
        'is_active'=> 1,

    ]);
})->describe('');

Artisan::command('test:create_container_size', function () {
    DB::table('container_sizes')->insert([
        'id' => 2000,
        'size'=>'300',
    ]);
})->describe('');

Artisan::command('test:create_fake_inbound', function () {
    DB::table('inbounds')->insert([
        'id' => 1000,
        'po_header_id' => 1000,

    ]);
    DB::table('inbound_details')->insert([
        'id' => 100,
        'inbound_id' => 1000,
        'po_detail_id' => 100,
        'qty' => 100,
    ]);

    DB::table('shipping_bookings')->insert([
        'id' => 100,
       'shipping_id' => 1000,
        'ats' => \Carbon\Carbon::now(),
    ]);


})->describe('Generate fake inbound');

