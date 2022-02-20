<?php

namespace App\Http\Controllers\Admin;

use App\Balance_package;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BalanceBackagesController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Balance_package::orderBy('id','desc')->get();
        return view('admin.balance_packages.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.balance_packages.create');
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
                'name_ar' => 'required',
                'name_en' => 'required',
                'desc_ar' => 'required',
                'desc_en' => 'required',
                'price' => 'required',
                'amount' => 'required'
            ]);
        $exist_detail = Balance_package::create($data);
        session()->flash('success', trans('messages.added_s'));
        return redirect( route('balance_packages.index'));
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
        $data = Balance_package::where('id',$id)->first();
        return view('admin.balance_packages.edit',compact('data'));
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

        $data = $this->validate(\request(),
            [
                'name_ar' => 'required',
                'name_en' => 'required',
                'desc_ar' => 'required',
                'desc_en' => 'required',
                'price' => 'required',
                'amount' => 'required'
            ]);
        Balance_package::where('id',$id)->update($data);
        session()->flash('success', trans('messages.updated_s'));
        return redirect( route('balance_packages.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Balance_package::where('id',$id)->delete();
        session()->flash('success', trans('messages.deleted_s'));
        return back();
    }
}
