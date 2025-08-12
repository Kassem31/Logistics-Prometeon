<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Permission;
use App\Role;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $testUser = User::where('name','test')->first();
        if(is_null($testUser)){
            User::create([
                'name'=>'test',
                'full_name'=>'Test User',
                'email'=>'mohamed.talaat@sanatechnology.com',
                'password'=>Hash::make(123456),
                'is_active'=>1,
                'is_admin'=>1,
                'is_super_admin'=>1
            ]);
        }
        $roleTest = Role::where('name','test')->first();
        if(is_null($roleTest)){
          $roleTest = Role::create([
              'name' => 'test',
              'display_name' => NULL,
              'description' => NULL,
          ]);
        }
        $testUser = User::where('name','test')->pluck('id');
        $roleTest->users()->sync($testUser->all());
    }
}
