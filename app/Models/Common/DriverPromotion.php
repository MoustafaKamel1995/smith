<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;

class DriverPromotion extends Model
{
    public function usage()
	{
		return $this->hasMany('App\Models\Common\DriverPromotionUsage', 'promocode_id');
	}
}
