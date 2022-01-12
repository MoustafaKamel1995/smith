<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;

class DriverDataTypeOfCar extends Model
{
    protected $connection = 'common';
    protected $fillable = [
        'name',
    ];
}
