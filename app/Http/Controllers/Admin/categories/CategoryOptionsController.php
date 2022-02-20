<?php
namespace App\Http\Controllers\Admin\categories;
use App\Category_option;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use App\Category;

class CategoryOptionsController extends AdminController{
    public function index()
    {
    }

    public function show($id){
        $data = Category_option::where('deleted','0')->where('cat_id',$id)->get();
        return view('admin.categories.category_options.index',compact('data','id'));
    }
    public function destroy($id){
        $data['deleted'] = '1';
        Category_option::where('id',$id)->update($data);
        session()->flash('success', trans('messages.deleted_s'));
        return back();
    }

    public function store(Request $request){
        $data = $this->validate(\request(),
            [
                'cat_id' => 'required',
                'image' => 'required',
                'title_ar' => 'required',
                'title_en' => 'required',
                'is_required' => 'required'
            ]);

        $image_name = $request->file('image')->getRealPath();
        Cloudder::upload($image_name, null);
        $imagereturned = Cloudder::getResult();
        $image_id = $imagereturned['public_id'];
        $image_format = $imagereturned['format'];
        $image_new_name = $image_id.'.'.$image_format;
        $data['image'] = $image_new_name ;
        Category_option::create($data);
        session()->flash('success', trans('messages.added_s'));
        return back();
    }
}
