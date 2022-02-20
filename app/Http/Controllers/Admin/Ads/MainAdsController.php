<?php

namespace App\Http\Controllers\Admin\Ads;

use App\Ad;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Controller;
use App\Main_ad;
use App\Product;
use App\User;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Http\Request;

class MainAdsController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Main_ad::where('deleted','0')->get();
        return view('admin.ads.main_ads.index',compact('data'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.ads.main_ads.create',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate(\request(),
            [
                'image' => 'required',
            ]);
        $image_name = $request->file('image')->getRealPath();
        Cloudder::upload($image_name, null);
        $imagereturned = Cloudder::getResult();
        $image_id = $imagereturned['public_id'];
        $image_format = $imagereturned['format'];
        $image_new_name = $image_id.'.'.$image_format;
        $data['image'] = $image_new_name ;
        if ($request->type == 1) {
            $data['type'] = "link";
        }else {
            $data['type'] = "id";
        }
        $data['content'] = $request->content ;
        Main_ad::create($data);
        session()->flash('success', trans('messages.added_s'));
        return redirect( route('main_ads.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//        $users = User::orderBy('created_at', 'desc')->get();
//        $data = Main_ad::find($id);

        $data['ad'] = Main_ad::find($id);
        $data['users'] = User::orderBy('created_at', 'desc')->get();

        if ($data['ad']['type'] == 'id') {
            $data['product'] = Product::find($data['ad']['content']);
        }else {
            $data['product'] = [];
        }
        return view('admin.ads.main_ads.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data['deleted'] = "1";
        Main_ad::where('id',$id)->update($data);
        session()->flash('success', trans('messages.deleted_s'));
        return back();
    }
}
