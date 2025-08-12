<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboundDetails extends Model
{
    public $has_permission=false;
    protected $guarded = [];

    public function poDetail(){
        return $this->belongsTo(PODetail::class,'po_detail_id','id');
    }

}
