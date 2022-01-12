<?php

namespace App\Models\Transport;

use Illuminate\Database\Eloquent\Model;

class RideRequestPaymentRefund extends Model
{
	protected $connection = 'transport';
	protected $table = 'ride_request_payment_refund';
    protected $fillable = [
        'ride_request_id','response_status','message', 'reference', 'amount', 'currency', 'deducted_amount','refund_status', 'refunded_by', 'domain', 'fully_deducted', 'data_json'
  	];

  	public function ride()
    {
        return $this->belongsTo('App\Models\Transport\RideRequest');
    }
}
