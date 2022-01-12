<?php

namespace App\Http\Controllers\V1\Common\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Common\Reward;
use App\Models\Common\RewardRedeem;
use App\Helpers\Helper;
use Auth;

class RewardController extends Controller
{
    public function get(Request $request, $id=null){
        $data = Reward::with(['city', 'country', 'redeem' => function($q){
                            $q->where('user_id', Auth::guard('user')->user()->id)
                            ->where('user_type', 'USER');
                          }])
                          ->where(function($query){
                            $query->where('user_type', 'USER');
                            $query->orWhere('user_type', 'BOTH');
                          })
                          ->orderBy('created_at', 'desc')
                          ->get();

        return Helper::getResponse(['data' => $data]);
    }

    public function redeem(Request $request, $id){
      $reward = Reward::where(function($query){
                            $query->where('user_type', 'USER');
                            $query->orWhere('user_type', 'BOTH');
                          })
                        ->where('id', $id)
                        ->firstOrFail();
      $redeem = RewardRedeem::where([
                            'user_id' => Auth::guard('user')->user()->id,
                            'user_type' => 'USER',
                            'reward_id' => $reward->id,
                          ])->first();
      if(!empty($redeem)){
        return Helper::getResponse(['message' => 'user already redeem this reward.', 'status', 401]);
      }
      $redeem = new RewardRedeem();
      $redeem->user_id = Auth::guard('user')->user()->id;
      $redeem->user_type = 'USER';
      $redeem->reward_id = $reward->id;
      $redeem->save();

      return Helper::getResponse(['message' => 'success']);
    }

}
