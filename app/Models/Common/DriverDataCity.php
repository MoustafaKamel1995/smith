<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;

class DriverDataCity extends Model
{
    protected $connection = 'common';
    protected $fillable = [
        'name',
    ];
}
