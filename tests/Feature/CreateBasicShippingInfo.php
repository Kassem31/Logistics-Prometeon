<?php

namespace Tests\Feature;

use App\Models\ShippingBasicInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateBasicShippingInfo extends TestCase
{
    use RefreshDatabase,DatabaseMigrations;
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed',['--class' => 'AdminUserSeeder']);
    }
    /**
     *
     * @return void
     * @test
     */
    public function CreateShippingWithMandatoryFields()
    {
        // $shipping = ShippingBasicInfo::create([
        //     ''
        // ]);


    }
}
