<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Reward extends Model
{
    public function city()
    {
        return $this->belongsTo('App\Models\Common\City', 'city_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Common\Country', 'country_id');
    }

    public function redeem()
    {
        return $this->hasOne('App\Models\Common\RewardRedeem', 'reward_id');
    }
}
