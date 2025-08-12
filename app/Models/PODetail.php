<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PODetail extends Model
{
    public $has_permission=false;
    protected $guarded = [];
    protected $appends = ['remaining'];

    public function header(){
        return $this->belongsTo(POHeader::class,'po_header_id','id');
    }
    public function rawMaterial(){
        return $this->belongsTo(RawMaterial::class,'raw_material_id','id');
    }

    public function shippingUnit(){
        return $this->belongsTo(ShippingUnit::class,'shipping_unit_id','id');
    }

    public function originCountry(){
        return $this->belongsTo(Country::class,'origin_country_id','id');
    }

    public function inboundDetails(){
        return $this->hasMany(InboundDetails::class,'po_detail_id','id')->whereNull('deleted_at');
    }

    public function getRemainingAttribute(){
        return $this->calcRemaining();
    }
    public function calcRemaining(){
        $detailQty = $this->inboundDetails()->sum('qty');
        return $this->qty - $detailQty;
    }

}
