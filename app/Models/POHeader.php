<?php

namespace App\Models;

use App\Traits\HasFilter;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;

class POHeader extends Model
{
    use HasFilter;
    public $display_name = "Purchase Order";
    protected $guarded = [];
    public const STATUS = [
        'Open','Closed'
    ];
    public function details(){
        return $this->hasMany(PODetail::class,'po_header_id','id')->whereNull('deleted_at')->orderBy('row_no');
    }
    public function pic(){
        return $this->belongsTo(User::class,'person_in_charge_id','id');
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }
    // Incoterm relation
    public function incoterm(){
        return $this->belongsTo(IncoTerm::class,'incoterm_id','id');
    }
    public function getOrderDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(Exception $ex){
            return $value;
        }
    }
    public function setOrderDateAttribute($value){
        try{
            $this->attributes['order_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
        }catch(Exception $ex){
            return $value;
        }
    }
    public function getDueDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(Exception $ex){
            return $value;
        }
    }
    public function setDueDateAttribute($value){
        try{

            $this->attributes['due_date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
        }catch(Exception $ex){
            return $value;
        }
    }

}
