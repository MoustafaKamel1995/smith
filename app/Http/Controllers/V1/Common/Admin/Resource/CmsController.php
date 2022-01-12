<?php

namespace App\Http\Controllers\V1\Common\Admin\Resource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Traits\Actions;
use App\Models\Common\CmsPage;
use App\Models\Common\CmsFaq;
use App\Models\Common\CmsHowItWork;
use App\Models\Common\CmsHomeNews;
use DB;
use Auth;

class CmsController extends Controller
{
        use Actions;

        private $model;
        private $request;
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CmsPage $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cms_page = CmsPage::where('company_id',Auth::user()->company_id)->get();
        return Helper::getResponse(['data' => $cms_page]);
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
            'page_name' => 'required',
            'content' => 'required',  
            'status' => 'required',          
        ]);

        try{
            $cms_page = new CmsPage;
            $cms_page->company_id = Auth::user()->company_id;  
            $cms_page->page_name = $request->page_name;
            $cms_page->content = $request->content;  
            $cms_page->status = $request->status;                   
            $cms_page->save();
            return Helper::getResponse(['status' => 200, 'message' => trans('admin.create')]);
        } 
        catch (\Throwable $e) {
            return Helper::getResponse(['status' => 404, 'message' => trans('admin.something_wrong'), 'error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reason  $id
     * @return \Illuminate\Http\Response
     */
    public function show($page)
    {
        try {
            $cms_page = CmsPage::where('company_id',Auth::user()->company_id)->where('page_name',$page)->get();

            return Helper::getResponse(['data' => $cms_page]);
        } catch (\Throwable $e) {
            return Helper::getResponse(['status' => 404,'message' => trans('admin.something_wrong'), 'error' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reason  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'page_name' => 'required',
            'content' => 'required',
            'status' => 'required',       
        ]);

        try {

            $cms_page = CmsPage::findOrFail($id);
            $cms_page->page_name = $request->page_name;
            $cms_page->content = $request->content;   
            $cms_page->status = $request->status;                     
            $cms_page->save();

            return Helper::getResponse(['status' => 200, 'message' => trans('admin.update')]);   
        } 
        catch (\Throwable $e) {
            return Helper::getResponse(['status' => 404,'message' => trans('admin.something_wrong'), 'error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reason  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->removeModel($id);
    }


    ///////////////////////////////////////////////////////////////
    /////// CMS FAQ

    public function faqIndex()
    {
        $faqs = CmsFaq::where('company_id',Auth::user()->company_id)->get();
        return Helper::getResponse(['data' => $faqs]);
    }

    public function faqStore(Request $request)
    {
        $this->validate($request, [
            'question' => 'required',
            'answer' => 'required',         
        ]);

        try{

            if(isset($request->id) && !empty($request->id))  {
                $faq = CmsFaq::where('company_id',Auth::user()->company_id)->where('id', $request->id)->first();
            }
            else{
                $faq = new CmsFaq;
                $faq->company_id = Auth::user()->company_id;
            }
            $faq->question = $request->question;
            $faq->answer = $request->answer;  
            if(isset($request->priority)){
                $faq->priority = $request->priority; 
            }                  
            $faq->save();
            return Helper::getResponse(['status' => 200, 'message' => trans('admin.create')]);
        } 
        catch (\Throwable $e) {
            return Helper::getResponse(['status' => 404, 'message' => trans('admin.something_wrong'), 'error' => $e->getMessage()]);
        }
    }

    public function faqDestroy($id)
    {
        CmsFaq::where('company_id',Auth::user()->company_id)->where('id', $id)->delete();
        return Helper::getResponse(['message' => trans('admin.user_msgs.user_delete')]);
    }


    public function faqShow($id)
    {
        try {
            $faq = CmsFaq::where('company_id',Auth::user()->company_id)->where('id', $id)->first();

            return Helper::getResponse(['data' => $faq]);
        } catch (\Throwable $e) {
            return Helper::getResponse(['status' => 404,'message' => trans('admin.something_wrong'), 'error' => $e->getMessage()]);
        }
    }


    ///////////////////////////////////////////////////////////////
    /////// CMS How it works

    public function cmshowitworksIndex()
    {
        $how = CmsHowItWork::where('company_id',Auth::user()->company_id)->orderBy('step', 'asc')->get();
        return Helper::getResponse(['data' => $how]);
    }

    public function cmshowitworksStore(Request $request)
    {
        $this->validate($request, [
            'step' => 'required',
            'heading' => 'required',
            'content' => 'required',
        ]);

        try{

            if(isset($request->id) && !empty($request->id))  {
                $how = CmsHowItWork::where('company_id',Auth::user()->company_id)->where('id', $request->id)->first();
            }
            else{
                $how = CmsHowItWork::where('company_id',Auth::user()->company_id)->where('step', $request->step)->first();
                if(!empty($how)){
                    return Helper::getResponse(['status' => 404, 'message' => 'Step' . $request->step . ' already exist.']);
                }
                $how = new CmsHowItWork;
                $how->company_id = Auth::user()->company_id;
            }
            $how->step = $request->step;
            $how->heading = $request->heading;  
            $how->content = $request->content;  
            $how->save();
            return Helper::getResponse(['status' => 200, 'message' => trans('admin.create')]);
        } 
        catch (\Throwable $e) {
            return Helper::getResponse(['status' => 404, 'message' => trans('admin.something_wrong'), 'error' => $e->getMessage()]);
        }
    }

    public function cmshowitworksDestroy($id)
    {
        CmsHowItWork::where('company_id',Auth::user()->company_id)->where('id', $id)->delete();
        return Helper::getResponse(['message' => trans('admin.user_msgs.user_delete')]);
    }


    public function cmshowitworksShow($id)
    {
        try {
            $how = CmsHowItWork::where('company_id',Auth::user()->company_id)->where('id', $id)->first();

            return Helper::getResponse(['data' => $how]);
        } catch (\Throwable $e) {
            return Helper::getResponse(['status' => 404,'message' => trans('admin.something_wrong'), 'error' => $e->getMessage()]);
        }
    }


    ///////////////////////////////////////////////////////////////
    /////// CMS Home News

    public function cmshomenewsIndex()
    {
        $news = CmsHomeNews::where('company_id',Auth::user()->company_id)->get();
        return Helper::getResponse(['data' => $news]);
    }

    public function cmshomenewsStore(Request $request)
    {
        $this->validate($request, [
            'heading' => 'required',
            'content' => 'required',
        ]);

        try{

            if(isset($request->id) && !empty($request->id))  {
                $news = CmsHomeNews::where('company_id',Auth::user()->company_id)->where('id', $request->id)->first();
            }
            else{
                $this->validate($request, [
                    'photo' => 'required',
                ]);
                $news = new CmsHomeNews;
                $news->company_id = Auth::user()->company_id;
            }
            $news->heading = $request->heading;  
            $news->content = $request->content;
            if($request->file('photo')){
                $photo = Helper::upload_photo($request->file('photo'), 'cms_home_news', null, Auth::user()->company_id);
                $news->photo = $photo;
            }

            $news->save();
            return Helper::getResponse(['status' => 200, 'message' => trans('admin.create')]);
        } 
        catch (\Throwable $e) {
            return Helper::getResponse(['status' => 404, 'message' => trans('admin.something_wrong'), 'error' => $e->getMessage()]);
        }
    }

    public function cmshomenewsDestroy($id)
    {
        CmsHomeNews::where('company_id',Auth::user()->company_id)->where('id', $id)->delete();
        return Helper::getResponse(['message' => trans('admin.user_msgs.user_delete')]);
    }


    public function cmshomenewsShow($id)
    {
        try {
            $news = CmsHomeNews::where('company_id',Auth::user()->company_id)->where('id', $id)->first();

            return Helper::getResponse(['data' => $news]);
        } catch (\Throwable $e) {
            return Helper::getResponse(['status' => 404,'message' => trans('admin.something_wrong'), 'error' => $e->getMessage()]);
        }
    }

}

