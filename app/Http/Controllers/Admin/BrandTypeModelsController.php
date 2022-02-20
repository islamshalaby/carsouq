<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\TypeModel;

class BrandTypeModelsController extends AdminController
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
    public function create($type_id)
    {

        return view('admin.brands.types.models.create',compact('type_id'));
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
                'marka_type_id' => 'required',
                'year' => 'required|numeric',
            ]);
        TypeModel::create($data);
        session()->flash('success', trans('messages.added_s'));
        return redirect( route('models.show',$request->marka_type_id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $type_id = $id;
        $models = TypeModel::where('marka_type_id',$id)->where('deleted','0')->get();
        return view('admin.brands.types.models.index',compact('models','type_id'));
    }

    public function edit($id) {
        $data = TypeModel::where('id',$id)->first();
        return view('admin.brands.types.models.edit', compact('data'));
    }
    public function update(Request $request, $id) {
        $model = TypeModel::where('id',$id)->first();
        $data = $this->validate(\request(),
            [
                'year' => 'required|numeric',
            ]);
        TypeModel::where('id',$id)->update($data);
        session()->flash('success', trans('messages.updated_s'));
        return redirect( route('models.show',$model->marka_type_id));
    }
    public function destroy($id)
    {
        $data['deleted'] = "1";
        TypeModel::where('id',$id)->update($data);
        session()->flash('success', trans('messages.deleted_s'));
        return back();
    }
}
