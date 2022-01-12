<?php

namespace App\Http\Controllers\V1\Common\Admin\Resource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Hash;
use App\Traits\Actions;
use App\Models\Common\Promocode;
use App\Models\Common\DriverPromotion;
use App\Models\Order\Store;
use DB;
use Auth;
use Carbon\Carbon;

class PromocodeController extends Controller
{
    use Actions;

    private $model;
    private $request;
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PromoCode $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
      $datum = Promocode::whereHas('service' ,function($query){  
            $query->where('status',1);  
        })->where('company_id', Auth::user()->company_id);
        
        if($request->has('search_text') && $request->search_text != null) {
            $datum->Search($request->search_text);
        }

        if($request->has('order_by')) {
            $datum->orderby($request->order_by, $request->order_direction);
        }

        
        if($request->has('page') && $request->page == 'all') {
            $data = $datum->get();
        } else {
            $data = $datum->paginate(10);
        }

        return Helper::getResponse(['data' => $data]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.promocode.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
          'service' => 'required',
          'user_type' => 'required',
          'apply_type' => 'required',
          'no_of_rides' => 'required',
          'start' => 'required',
          'end' => 'required',
          'percentage' => 'required|numeric',
          'max_amount' => 'required|numeric',
          'picture' => 'required|mimes:jpeg,jpg,bmp,png|max:5242880', 
      ]);

      try{
          $p_code = '';
          if($request->apply_type == 'PROMOCODE'){
            if(!isset($request->promo_code) || empty($request->promo_code)){
              return Helper::getResponse(['status' => 401, 'message' => 'Promocode Required.']);
            }
            else{
              $p_code = $request->promo_code;
            }
          }

          $promo_code = new Promocode();
          $promo_code->company_id = Auth::user()->company_id;  
          $promo_code->service = $request->service;

          if($request->hasFile('picture')) {
              // $imagedetails = getimagesize($_FILES['picture']['tmp_name']);
              // $height = $imagedetails[1];
              // if($height < 190 || $height > 200){
              //     return Helper::getResponse(['status' => 404,'message' => 'image Height must be 200px. this image height is '.$height.' px', 'error' => '']);
              // }
              $promo_code->picture = Helper::upload_file($request->file('picture'), 'promocode');
          }
          $promo_code->promo_code = $p_code;
          $promo_code->apply_type = $request->apply_type;
          $promo_code->percentage = $request->percentage;  
          $promo_code->max_amount = $request->max_amount;  
          $promo_code->user_type = $request->user_type;   
          $promo_code->no_of_rides = $request->no_of_rides;                                
          $promo_code->start = Carbon::parse($request->start)->format('Y-m-d');
          $promo_code->end = Carbon::parse($request->end)->format('Y-m-d');
          $promo_code->promo_description = $request->promo_description;               
          $promo_code->save();
          return Helper::getResponse(['status' => 200, 'message' => trans('admin.create')]);
      } 
      catch (ModelNotFoundException $e) {
          return Helper::getResponse(['status' => 404, 'message' => trans('admin.something_wrong'), 'error' => $e->getMessage()]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $promocode = Promocode::findOrFail($id);
            $start = $promocode['start'];
            $promocode['start']=date('d/m/Y',strtotime($promocode['start']));
            $promocode['start_date'] = date('m/d/Y',strtotime($start));
            $end = $promocode['end'];
            $promocode['end']=date('d/m/Y',strtotime($promocode['end']));
            $promocode['end_date'] = date('m/d/Y',strtotime($end));
            return Helper::getResponse(['data' => $promocode]); 
        } catch (\Throwable $e) {
            return Helper::getResponse(['status' => 404,'message' => trans('admin.something_wrong'), 'error' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $this->validate($request, [
        'id' => 'required',
        'service' => 'required',
        'user_type' => 'required',
        'apply_type' => 'required',
        'no_of_rides' => 'required',
        'start' => 'required',
        'end' => 'required',
        'percentage' => 'required|numeric',
        'max_amount' => 'required|numeric',
      ]);

      try{
          $p_code = '';
          if($request->apply_type == 'PROMOCODE'){
            if(!isset($request->promo_code) || empty($request->promo_code)){
              return Helper::getResponse(['status' => 401, 'message' => 'Promocode Required.']);
            }
            else{
              $p_code = $request->promo_code;
            }
          }

          $promo_code = Promocode::find($request->id);
          $promo_code->company_id = Auth::user()->company_id;  
          $promo_code->service = $request->service;

          if($request->hasFile('picture')) {
              // $imagedetails = getimagesize($_FILES['picture']['tmp_name']);
              // $height = $imagedetails[1];
              // if($height < 190 || $height > 200){
              //     return Helper::getResponse(['status' => 404,'message' => 'image Height must be 200px. this image height is '.$height.' px', 'error' => '']);
              // }
              $promo_code->picture = Helper::upload_file($request->file('picture'), 'promocode');
          }
          $promo_code->promo_code = $p_code;
          $promo_code->apply_type = $request->apply_type;
          $promo_code->percentage = $request->percentage;  
          $promo_code->max_amount = $request->max_amount;  
          $promo_code->user_type = $request->user_type;   
          $promo_code->no_of_rides = $request->no_of_rides;                                
          $promo_code->start = Carbon::parse($request->start)->format('Y-m-d');
          $promo_code->end = Carbon::parse($request->end)->format('Y-m-d');
          $promo_code->promo_description = $request->promo_description;               
          $promo_code->save();
          return Helper::getResponse(['status' => 200, 'message' => trans('admin.create')]);
      } 
      catch (ModelNotFoundException $e) {
          return Helper::getResponse(['status' => 404, 'message' => trans('admin.something_wrong'), 'error' => $e->getMessage()]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->removeModel($id);
    }


    public function driver_index(Request $request)
    {
        
      $datum = DriverPromotion::where('company_id', Auth::user()->company_id);
        
        if($request->has('search_text') && $request->search_text != null) {
            $datum->Search($request->search_text);
        }

        if($request->has('order_by')) {
            $datum->orderby($request->order_by, $request->order_direction);
        }

        
        if($request->has('page') && $request->page == 'all') {
            $data = $datum->get();
        } else {
            $data = $datum->paginate(10);
        }

        return Helper::getResponse(['data' => $data]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function driver_create()
    {
        return view('admin.promocode.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function driver_store(Request $request)
    {
      $this->validate($request, [
        'service' => 'required',
        'bonus' => 'required',
        'description' => 'required',
        'start' => 'required',
        'end' => 'required',
        'no_of_rides' => 'required',
        'picture' => 'required|mimes:jpeg,jpg,bmp,png|max:5242880', 
      ]);

      try{
          $promo_code = new DriverPromotion();
          $promo_code->company_id = Auth::user()->company_id;  
          $promo_code->service = $request->service;

          if($request->hasFile('picture')) {
              $promo_code->picture = Helper::upload_file($request->file('picture'), 'driver-promotions');
          }
          $promo_code->bonus = $request->bonus;
          $promo_code->description = $request->description;
          $promo_code->no_of_rides = $request->no_of_rides;                                
          $promo_code->start = Carbon::parse($request->start)->format('Y-m-d');
          $promo_code->end = Carbon::parse($request->end)->format('Y-m-d');
          $promo_code->save();
          return Helper::getResponse(['status' => 200, 'message' => trans('admin.create')]);
      } 
      catch (ModelNotFoundException $e) {
          return Helper::getResponse(['status' => 404, 'message' => trans('admin.something_wrong'), 'error' => $e->getMessage()]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function driver_show($id)
    {
        try {
            $promocode = DriverPromotion::findOrFail($id);
            $start = $promocode['start'];
            $promocode['start']=date('d/m/Y',strtotime($promocode['start']));
            $promocode['start_date'] = date('m/d/Y',strtotime($start));
            $end = $promocode['end'];
            $promocode['end']=date('d/m/Y',strtotime($promocode['end']));
            $promocode['end_date'] = date('m/d/Y',strtotime($end));
            return Helper::getResponse(['data' => $promocode]); 
        } catch (\Throwable $e) {
            return Helper::getResponse(['status' => 404,'message' => trans('admin.something_wrong'), 'error' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function driver_update(Request $request, $id)
    {
      $this->validate($request, [
        'id' => 'required',
        'service' => 'required',
        'bonus' => 'required',
        'description' => 'required',
        'start' => 'required',
        'end' => 'required',
        'no_of_rides' => 'required',
      ]);

      try{
          $promo_code = DriverPromotion::find($request->id);
          $promo_code->company_id = Auth::user()->company_id;  
          $promo_code->service = $request->service;

          if($request->hasFile('picture')) {
              $promo_code->picture = Helper::upload_file($request->file('picture'), 'driver-promotions');
          }
          $promo_code->bonus = $request->bonus;
          $promo_code->description = $request->description;
          $promo_code->no_of_rides = $request->no_of_rides;                                
          $promo_code->start = Carbon::parse($request->start)->format('Y-m-d');
          $promo_code->end = Carbon::parse($request->end)->format('Y-m-d');
          $promo_code->save();
          return Helper::getResponse(['status' => 200, 'message' => trans('admin.create')]);
      } 
      catch (ModelNotFoundException $e) {
          return Helper::getResponse(['status' => 404, 'message' => trans('admin.something_wrong'), 'error' => $e->getMessage()]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function driver_destroy($id)
    {
        $promo = DriverPromotion::find($id);
        $promo->delete();

        return Helper::getResponse(['status' => 200, 'message' => 'Promotion Deleted Successfully.']);
    }

}
