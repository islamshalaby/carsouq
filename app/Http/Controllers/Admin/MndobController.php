<?php

namespace App\Http\Controllers\Admin;
use JD\Cloudder\Facades\Cloudder;
use App\Mndob;
use Illuminate\Http\Request;

class MndobController extends AdminController
{

    public function index()
    {
        $data = Mndob::where('deleted','0')->OrderBy('id','desc')->get();
        return view('admin.mndob.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.mndob.create');
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
                'name_ar' => 'required',
                'name_en' => 'required',
                'phone' => 'required|numeric',
                'watsapp' => 'required',
            ]);
        $image_name = $request->file('image')->getRealPath();
        Cloudder::upload($image_name, null);
        $imagereturned = Cloudder::getResult();
        $image_id = $imagereturned['public_id'];
        $image_format = $imagereturned['format'];
        $image_new_name = $image_id.'.'.$image_format;
        $data['image'] = $image_new_name ;
        Mndob::create($data);
        session()->flash('success', trans('messages.added_s'));
        return redirect( route('mndob.index'));
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
        $data = Mndob::findOrFail($id);
        return view('admin.mndob.edit',compact('data'));
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
        $mndob = Mndob::find($id);
        if($request->file('image')){

            $image = $mndob->image;
            $publicId = substr($image, 0 ,strrpos($image, "."));
            // Cloudder::delete($publicId);
            $image_name = $request->file('image')->getRealPath();
            Cloudder::upload($image_name, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];
            $image_new_name = $image_id.'.'.$image_format;
            $mndob->image = $image_new_name;
        }
//        dd($image_new_name);
        $mndob->name_ar = $request->name_ar;
        $mndob->name_en = $request->name_en;
        $mndob->phone = $request->phone;
        $mndob->watsapp = $request->watsapp;
        $mndob->save();
        return redirect(route('mndob.index'));
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
        Mndob::where('id',$id)->update($data);
        session()->flash('success', trans('messages.deleted_s'));
        return back();
    }
}
