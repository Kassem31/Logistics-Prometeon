<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\Loggable;

class ShippingBasic extends Model  implements Loggable{
    public $display_name = "Inbound";

    public $totalSteps = 7;
    public $currentStep = 1;

    public function getLog(){
        return ShippingLogger::where('class_name',$this->display_name)
        ->where('instance_id',$this->id)
        ->get();
    }

    public function getLogFor($fieldName){
        return ShippingLogger::where('class_name',$this->display_name)
        ->where('instance_id',$this->id)
        ->where('field_name',$fieldName)
        ->with('user')
        ->get();
    }

}
