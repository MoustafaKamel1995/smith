<?php

namespace App\Http\Controllers\V1\Common\Admin\Resource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Common\Reward;
use App\Helpers\Helper;


class RewardController extends Controller
{

  public function randome_string($n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
  
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
  
    return $randomString;
  }

  public function create_code(){
    $status = true;
    while ($status) {
      $code = $this->randome_string(8);
      $check = Reward::where('code', $code)->first();
      if(empty($check)){
        $status = false;
      }
    }

    return $code;
  }

  public function get(Request $request, $id=null){
      $data = Reward::with(['city', 'country'])->orderBy('created_at', 'desc');

      if($id){

      }
      else{
          $data = $data->get();
      }
      return Helper::getResponse(['data' => $data]);
  }

  public function post(Request $request)
  {
    $this->validate($request, [
        'user_type' => 'required',
        'country_id' => 'required',
        'city_id' => 'required',
        'restaurant_name' => 'required',
        'image' => 'required',
        'item_name' => 'required',
    ]);

    try{
          $data = new Reward();
        if($request->hasFile('image')) {
            $image_url = Helper::upload_file($request->file('image'), 'reward');
            $data->image = $image_url;
        }
        $data->user_type = $request->user_type;
        $data->country_id = $request->country_id;
        $data->city_id = $request->city_id;  
        $data->restaurant_name = $request->restaurant_name;
        $data->code = strtoupper($this->create_code());
        if(!empty($request->free)){
          $data->free = true;
        }          
        else{
          $data->free = false;
        }
        if(!empty($request->amount)){
          $data->amount = $request->amount;
        }
        $data->item_name = $request->item_name;               
        $data->save();
        return Helper::getResponse(['status' => 200, 'message' => trans('admin.create')]);
    } 
    catch (ModelNotFoundException $e) {
        return Helper::getResponse(['status' => 404, 'message' => trans('admin.something_wrong'), 'error' => $e->getMessage()]);
    }
  }


  public function delete(Request $request, $id){
      Reward::where('id', $id)->delete();
      return Helper::getResponse(['status' => 200, 'message' => 'success']);
  }
}
