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
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'order_date' => 'date',
        'due_date' => 'date',
    ];
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
    public function getDueDateAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(Exception $ex){
            return $value;
        }
    }

    /**
     * Set the order_date attribute, parsing various formats
     */
    public function setOrderDateAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['order_date'] = null;
            return;
        }
        try {
            // Try dd/mm/YYYY
            $date = Carbon::createFromFormat('d/m/Y', $value);
        } catch (Exception $e) {
            try {
                // Try Y-m-d or other formats
                $date = Carbon::parse($value);
            } catch (Exception $e) {
                $date = null;
            }
        }
        $this->attributes['order_date'] = $date ? $date->format('Y-m-d') : null;
    }
    /**
     * Set the due_date attribute, parsing various formats
     */
    public function setDueDateAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['due_date'] = null;
            return;
        }
        try {
            // Try dd/mm/YYYY
            $date = Carbon::createFromFormat('d/m/Y', $value);
        } catch (Exception $e) {
            try {
                // Try Y-m-d or other formats
                $date = Carbon::parse($value);
            } catch (Exception $e) {
                $date = null;
            }
        }
        $this->attributes['due_date'] = $date ? $date->format('Y-m-d') : null;
    }
    /**
     * Get the inbounds associated with this PO
     */
    public function inbounds()
    {
        return $this->hasMany(Inbound::class, 'po_header_id', 'id');
    }

}
