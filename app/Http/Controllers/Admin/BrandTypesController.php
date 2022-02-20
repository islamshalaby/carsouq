<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\MarkaType;
class BrandTypesController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($plan_id)
    {
            return view('admin.brands.types.create',compact('plan_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $this->validate(\request(),
            [
                'title_ar' => 'required',
                'title_en' => 'required',
                'marka_id' => 'required',
            ]);
        MarkaType::create($data);
        session()->flash('success', trans('messages.added_s'));
        return redirect( route('brand_types.show',$request->marka_id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand_id = $id;
        $brands = MarkaType::where('marka_id',$id)->where('deleted','0')->get();
        return view('admin.brands.types.index',compact('brands','brand_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $data = MarkaType::where('id',$id)->first();
        return view('admin.brands.types.edit', compact('data'));
    }
    public function update(Request $request, $id) {
        $type = MarkaType::where('id',$id)->first();
        $data = $this->validate(\request(),
            [
                'title_ar' => 'required',
                'title_en' => 'required',
            ]);
        MarkaType::where('id',$id)->update($data);
        session()->flash('success', trans('messages.updated_s'));
        return redirect( route('brand_types.show',$type->marka_id));
    }
    public function destroy($id)
    {
        $data['deleted'] = "1";
        MarkaType::where('id',$id)->update($data);
        session()->flash('success', trans('messages.deleted_s'));
        return back();
    }
}
