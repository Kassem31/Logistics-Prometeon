<?php
namespace App\Filters\POFilter;

use App\Filters\AbstractBasicFilter;
use Carbon\Carbon;
use Exception;

class POOrderDateToFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        try{
            $date = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            return $this->builder->whereDate('order_date','<=',$date);

        }catch(Exception $ex){
            return $this->builder;
        }
    }
}
