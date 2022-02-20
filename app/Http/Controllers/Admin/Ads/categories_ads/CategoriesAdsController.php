<?php

namespace App\Http\Controllers\Admin\Ads\categories_ads;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Controller;
use App\User;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Http\Request;
use App\Categories_ad;
use App\Category;

class CategoriesAdsController extends AdminController
{

    public function index(){
        $data = Category::where('deleted' , 0)->orderBy('sort' , 'asc')->get();
        return view('admin.ads.categories_ads.index' , compact('data'));
    }

    public function create($id)
    {
        return view('admin.ads.categories_ads.create' , compact('id'));
    }
    public function create_all()
    {
        return view('admin.ads.categories_ads.create' );
    }

    public function store(Request $request)
    {

        $image_name = $request->file('image')->getRealPath();
        Cloudder::upload($image_name, null);
        $imagereturned = Cloudder::getResult();
        $image_id = $imagereturned['public_id'];
        $image_format = $imagereturned['format'];
        $image_new_name = $image_id.'.'.$image_format;

        $data['image'] = $image_new_name;
        $data['cat_id'] = $request->id;
        $data['type'] = 'category';
        if($request->type == 1){
            $data['ad_type'] = 'link';
        }else if($request->type == 2){
            $data['ad_type'] = 'id';
        }
        $data['content'] = $request->content;
        Categories_ad::create($data);
        session()->flash('success', trans('messages.added_s'));
        return redirect(route('categories_ads.show',$request->id));
    }
    public function store_all_categories(Request $request)
    {
        $cats = Category::where('deleted' , 0)->orderBy('id' , 'desc')->get();
        $image_name = $request->file('image')->getRealPath();
        Cloudder::upload($image_name, null);
        $imagereturned = Cloudder::getResult();
        $image_id = $imagereturned['public_id'];
        $image_format = $imagereturned['format'];
        $image_new_name = $image_id.'.'.$image_format;

        foreach ($cats as $key => $row) {
            $data['image'] = $image_new_name;
            $data['cat_id'] = $row->id;
            $data['type'] = 'category';
            if($request->type == 1){
                $data['ad_type'] = 'link';
            }else if($request->type == 2){
                $data['ad_type'] = 'id';
            }
            $data['content'] = $request->content;
            Categories_ad::create($data);
        }

        session()->flash('success', trans('messages.added_s'));
        return redirect(route('categories_ads.index',$request->id));
    }

    public function show($id)
    {
        $data = Categories_ad::where('cat_id',$id)->where('type','category')->where('deleted' , '0')->orderBy('id' , 'desc')->get();
        return view('admin.ads.categories_ads.ads' , compact('data','id'));
    }

    public function destroy($id)
    {
        $data['deleted'] = "1";
        Categories_ad::where('id',$id)->update($data);
        session()->flash('success', trans('messages.deleted_s'));
        return back();
    }
}
