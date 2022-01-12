<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;

class UserPaystackCard extends Model
{
	protected $connection = 'common';
	protected $table = 'user_paystack_cards';
    protected $fillable = [
        'user_id', 'company_id', 'authorization_code','last4', 'exp_month', 'exp_year', 'card_type', 'signature','card_json', 'customer_json'
  	];
}
