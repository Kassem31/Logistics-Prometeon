<?php
namespace App\Filters\POFilter;

use App\Filters\AbstractBasicFilter;
use Carbon\Carbon;
use Exception;

class PODueDateFromFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        try{
            $date = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');
            return $this->builder->whereDate('due_date','>=',$date);

        }catch(Exception $ex){
            return $this->builder;
        }
    }
}
