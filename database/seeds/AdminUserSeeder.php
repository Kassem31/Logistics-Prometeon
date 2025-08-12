<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('is_super_admin',1)->first();
        if(is_null($user)){
            User::create([
                'name'=>'admin',
                'full_name'=>'System Admin',
                'password'=>Hash::make(123456),
                'is_active'=>1,
                'is_admin'=>1,
                'is_super_admin'=>1
            ]);
        }
    }
}
