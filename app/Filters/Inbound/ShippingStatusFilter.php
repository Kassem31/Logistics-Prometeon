<?php
namespace App\Filters\Inbound;

use App\Filters\AbstractBasicFilter;

class ShippingStatusFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        $this->builder->where(function($query) use ($value) {
            if(in_array('Arrived',$value)){
                $query->orWhereHas('booking', function($booking) {
                    $booking->whereNotNull('ata');
                });
            }
            if(in_array('In-Transit',$value)){
                $query->orWhereHas('booking', function($booking) {
                    $booking->whereNotNull('ats')->whereNull('ata');
                });
            }
            if(in_array('Unbooked',$value)){
                // Unbooked means either no booking record OR booking with null ats and ata
                $query->orWhereDoesntHave('booking')
                      ->orWhereHas('booking', function($booking) {
                          $booking->whereNull('ats')->whereNull('ata');
                      });
            }
        });
        
        return $this->builder;
    }
}
