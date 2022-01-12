<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;

class DriverData extends Model
{
    protected $connection = 'common';
    protected $fillable = [
        'name', 'email', 'phone', 'city_id', 'type_of_car'        
    ];

    public function city()
    {
        return $this->belongsTo('App\Models\Common\DriverDataCity', 'city_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\Common\DriverDataTypeOfCar', 'type_of_car');
    }
}
