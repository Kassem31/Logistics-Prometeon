<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class  ModelSaving{
    public function handle(Model $event)
    {
        echo('Testing Save');
    }
}
