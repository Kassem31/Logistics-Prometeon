<?php
namespace App\Filters\Inbound;

use App\Filters\AbstractBasicFilter;
use Carbon\Carbon;
use Exception;

class AtaDateFromFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        try{
            $date = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            return $this->builder->whereHas('booking',function($q)use ($date){
                $q->whereDate('ata','>=',$date);
            });


        }catch(Exception $ex){
            return $this->builder;
        }
    }
}
