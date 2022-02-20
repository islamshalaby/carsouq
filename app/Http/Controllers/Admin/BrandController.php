<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Marka;
class BrandController extends AdminController
{
    public function index() {
        $data = Marka::where('deleted','0')->OrderBy('id','desc')->get();
        return view('admin.brands.index',compact('data'));
    }
    public function create() {
        return view('admin.brands.create');
    }
    public function store(Request $request) {
        $data = $this->validate(\request(),
            [
                'title_ar' => 'required',
                'title_en' => 'required',
            ]);
        Marka::create($data);
        session()->flash('success', trans('messages.added_s'));
        return redirect( route('brands.index'));
    }
    public function edit($id) {
        $data = Marka::findOrFail($id)->first();
        return view('admin.brands.edit', compact('data'));
    }
    public function update(Request $request, $id) {
        $data = $this->validate(\request(),
            [
                'title_ar' => 'required',
                'title_en' => 'required',
            ]);
        Marka::findOrFail($id)->update($data);
        session()->flash('success', trans('messages.updated_s'));
        return redirect( route('brands.index'));
    }
    public function show(){
    }
    public function destroy($id) {
        $data['deleted'] = "1";
        Marka::where('id',$id)->update($data);
        session()->flash('success', trans('messages.deleted_s'));
        return back();
    }
}
