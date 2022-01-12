<?php

namespace App\Http\Controllers\V1\Common\Admin\Resource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Traits\Actions;
use App\Helpers\Helper;
use App\Models\Common\DriverData;
use App\Models\Common\DriverDataCity;
use App\Models\Common\DriverDataTypeOfCar;
use App\Models\Common\CompanyCity;
use App\Models\Common\CompanyCountry;
use App\Models\Common\Setting;
use App\Models\Common\AuthLog;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use App\Services\SendPushNotification;
use Auth;
use DB;
use App\Traits\Encryptable;
use Illuminate\Validation\Rule;


class DriverDataController extends Controller
{

    public function index(Request $request){

        $datum = DriverData::with(['city', 'type'])->orderBy('created_at');

        if($request->has('search_text') && $request->search_text != null) {
            $datum->Search($request->search_text);
        }

        if($request->has('order_by')) {
            $datum->orderby($request->order_by, $request->order_direction);
        }

        if($request->has('page') && $request->page == 'all') {
            $datum = $datum->get();
        } else {
            $datum = $datum->paginate(10);
        }

        return Helper::getResponse(['data' => $datum]);
    }


    public function cities(Request $request){
        switch ($request->method()) {
            case 'POST':
                $this->validate($request, [
                    'name' => 'required',
                ]);

                if(isset($request->id) && !empty($request->id)){
                    DriverDataCity::where('id', $request->id)->update([
                        'name' => $request->name,
                    ]);
                }
                else{
                    DriverDataCity::create([
                        'name' => $request->name,
                    ]);
                }

                return Helper::getResponse(['message' => 'success']);
                
                break;

            case 'GET':
                $data = DriverDataCity::all();
                return Helper::getResponse(['data' => $data]);
                break;

            default:
                break;
        }
    }

    public function cities_delete(Request $request, $id){
        DriverDataCity::find($id)->delete();
        return Helper::getResponse(['message' => 'success']);
    }

    public function city_get(Request $request, $id){
        $data = DriverDataCity::find($id);
        return Helper::getResponse(['data' => $data]);
    }


    public function type_of_cars(Request $request){
        switch ($request->method()) {
            case 'POST':
                $this->validate($request, [
                    'name' => 'required',
                ]);

                if(isset($request->id) && !empty($request->id)){
                    DriverDataTypeOfCar::where('id', $request->id)->update([
                        'name' => $request->name,
                    ]);
                }
                else{
                    DriverDataTypeOfCar::create([
                        'name' => $request->name,
                    ]);
                }

                return Helper::getResponse(['message' => 'success']);
                
                break;

            case 'GET':
                $data = DriverDataTypeOfCar::all();
                return Helper::getResponse(['data' => $data]);
                break;

            default:
                break;
        }
    }

    public function type_of_cars_delete(Request $request, $id){
        DriverDataTypeOfCar::find($id)->delete();
        return Helper::getResponse(['message' => 'success']);
    }

    public function type_of_cars_get(Request $request, $id){
        $data = DriverDataTypeOfCar::find($id);
        return Helper::getResponse(['data' => $data]);
    }

}
