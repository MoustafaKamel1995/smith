<?php

namespace App\Http\Controllers\V1\Common\Admin\Resource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Common\News;
use App\Helpers\Helper;


class NewsController extends Controller
{
    public function get(Request $request, $id=null){
        $data = News::orderBy('created_at', 'desc');

        if($id != null){
          $data = $data->where('id', $id)->first();
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
          'title' => 'required',
          'content' => 'required',
          'image' => 'required',
          // 'url' => 'required',
      ]);

      // if(!$this->startsWith($request->url, 'https://')){
      //   return Helper::getResponse(['status' => 404, 'message' => 'Url must be start with https://']);
      // }

      try{
          if(!empty($request->id)){
            $data = News::findOrFail($request->id);
          }else{
            $data = new News();
          }
          if($request->hasFile('image')) {
              $image_url = Helper::upload_file($request->file('image'), 'news');
              $data->image = $image_url;
          }
          $data->user_type = $request->user_type;
          $data->title = $request->title;
          $data->content = $request->content;  
          // $data->url = $request->url;
          $data->save();
          return Helper::getResponse(['status' => 200, 'message' => trans('admin.create')]);
      } 
      catch (ModelNotFoundException $e) {
          return Helper::getResponse(['status' => 404, 'message' => trans('admin.something_wrong'), 'error' => $e->getMessage()]);
      }
    }


    public function delete(Request $request, $id){
        News::where('id', $id)->delete();
        return Helper::getResponse(['status' => 200, 'message' => 'success']);
    }

    public function startsWith ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
}
