<?php

namespace App\Models;

use App\Models\ShippingBasic;
use Illuminate\Support\Carbon;

class ShippingBooking extends ShippingBasic
{
    protected $guarded = [];
    public $has_permission=false;


    public function getEtsAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setEtsAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['ets'] = null;
            }else{
                $this->attributes['ets'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['ets'] = null;
        }
    }

    public function getEtaAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setEtaAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['eta'] = null;
            }else{
                $this->attributes['eta'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['eta'] = null;
        }
    }

    public function getAtsAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setAtsAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['ats'] = null;
            }else{
                $this->attributes['ats'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['ats'] = null;
        }
    }

    public function getAtaAttribute($value){
        if(is_null($value)) return null;
        try{
            return Carbon::parse($value)->format('d/m/Y');
        }catch(\Exception $ex){
            return $value;
        }
    }
    public function setAtaAttribute($value){
        try{
            if(is_null($value))  {
                $this->attributes['ata'] = null;
            }else{
                $this->attributes['ata'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            }
        }catch(\Exception $ex){
            $this->attributes['ata'] = null;
        }
    }

    public function getEttAttribute(){
        if(is_null($this->ets) || is_null($this->eta)){
            return null;
        }
        try{
            $ets = Carbon::parse($this->attributes['ets'])->startOfDay();
            $eta = Carbon::parse($this->attributes['eta'])->startOfDay();
            return (int) $eta->diffInDays($ets);
        }catch(\Exception $ex){
            return null;
        }
    }
    public function getAttAttribute(){
        if(is_null($this->ats) && is_null($this->ata)){
            return null;
        }
        try{
            $ats = Carbon::parse($this->attributes['ats'])->startOfDay();
            $ata = Carbon::parse($this->attributes['ata'])->startOfDay();
            return (int) $ats->diffInDays($ata);
        }catch(\Exception $ex){
            return null;
        }
    }

    public function getDeviationAttribute(){
        if(!is_null($this->ats) && !is_null($this->ets)){
            $ats = Carbon::parse($this->attributes['ats'])->startOfDay();
            $ets = Carbon::parse($this->attributes['ets'])->startOfDay();
            return (int) $ats->diffInDays($ets);
        }
        return null;
    }

    // Arrival Deviation: ATA - ETA
    public function getSailingDaysAttribute(){
        if(!is_null($this->ata) && !is_null($this->eta)){
            $eta = Carbon::parse($this->attributes['eta'])->startOfDay();
            $ata = Carbon::parse($this->attributes['ata'])->startOfDay();
            return (int) $ata->diffInDays($eta);
        }
        return null;
    }
}
