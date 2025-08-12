<?php

namespace Tests\Feature;

use App\Contracts\Loggable;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class BasicShippingInfoTest extends TestCase
{
    use RefreshDatabase,DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed',['--class' => 'AdminUserSeeder']);
    }
    /**
     * A basic test example.
     *
     * @test
     */
    public function model_is_instance_of_loggable_interface()
    {
        $model = $this->getModelInstance();
        $model->sap_inbound = 'x';
        $this->assertInstanceOf(Loggable::class,$model);
    }
    /**
     * @test
     */
    public function change_1_field_get_1_log(){
        $model = $this->getModelInstance();
        $model->save();
        $model->sap_inbound = 'x';
        $model->save();
        $log = $model->getLog();
        $this->assertCount(1,$log);
    }

    /**
     * @test
     */
    public function change_2_fields_get_2_log(){
        Auth::loginUsingId(1);
        $model = $this->getModelInstance();
        $model->save();
        $model->sap_inbound = 'x';
        $model->due_date = Carbon::now();
        $model->save();
        $log = $model->getLog();
        $this->assertCount(2,$log);

    }

    /**
     * @test
     */
    public function change_field_log(){
        $model = $this->getModelInstance();
        $model->save();
        $model->sap_inbound = 'x';
        $model->save();
        $model->sap_inbound = 'y';
        $model->save();
        $model->sap_inbound = 'z';
        $model->save();
        $log = $model->getLogFor('sap_inbound');
        $this->assertCount(3,$log);

    }

    protected function getModelInstance(){
        return new \App\Models\ShippingBasicInfo();

    }
}
