<?php

namespace App\Http\Controllers\Admin\categories;
use App\Http\Controllers\Admin\AdminController;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Http\Request;
use App\SubThreeCategory;

class SubThreeCategoryController extends AdminController
{

    public function index()
    {

    }
    public function create($id)
    {
        return view('admin.categories.sub_catyegory.sub_two_category.sub_three_category.create',compact('id'));
    }
    public function store(Request $request)
    {
        $data = $this->validate(\request(),
            [
                'sub_category_id' => 'required',
                'title_ar' => 'required',
                'title_en' => 'required',
                'image' => 'required'
            ]);
        $image_name = $request->file('image')->getRealPath();
        Cloudder::upload($image_name, null);
        $imagereturned = Cloudder::getResult();
        $image_id = $imagereturned['public_id'];
        $image_format = $imagereturned['format'];
        $image_new_name = $image_id.'.'.$image_format;
        $data['image'] = $image_new_name;
        SubThreeCategory::create($data);
        session()->flash('success', trans('messages.added_s'));
        return redirect( route('sub_three_cat.show',$request->sub_category_id));
    }
    public function show($id)
    {
        $cat_id = $id;
        $data = SubThreeCategory::where('sub_category_id',$id)->where('deleted','0')->orderBy('sort' , 'asc')->get();
        $prevent_next_level = false;
        if ($data && count($data) > 0) {
            foreach($data as $row) {
                $row->next_level = false;
            
                if (count($row->ViewSubCategories) > 0) {
                    $hasProducts = false;
                    for ($i = 0; $i < count($row->ViewSubCategories); $i ++) {
                        if (count($row->ViewSubCategories[$i]->products) > 0) {
                            $hasProducts = true;
                        }
                    }

                    if ($hasProducts) {
                        $row->next_level = true;
                    }
                    
                }

                if (count($row->products) > 0 && $row->next_level == false) {
                    $prevent_next_level = true;
                    break;
                }
            }
        }
        
        return view('admin.categories.sub_catyegory.sub_two_category.sub_three_category.index',compact('data','cat_id', 'prevent_next_level'));
    }

    public function edit($id) {
        $data = SubThreeCategory::where('id',$id)->first();
        return view('admin.categories.sub_catyegory.sub_two_category.sub_three_category.edit', compact('data'));
    }
    public function update(Request $request, $id) {
        $model = SubThreeCategory::where('id',$id)->first();
        $data = $this->validate(\request(),
            [
                'title_ar' => 'required',
                'title_en' => 'required'
            ]);
        if($request->file('image')){
            $image = $model->image;
            $publicId = substr($image, 0 ,strrpos($image, "."));
            if($publicId != null ){
                Cloudder::delete($publicId);
            }
            $image_name = $request->file('image')->getRealPath();
            Cloudder::upload($image_name, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];
            $image_new_name = $image_id.'.'.$image_format;
            $data['image'] = $image_new_name;
        }
        SubThreeCategory::where('id',$id)->update($data);
        session()->flash('success', trans('messages.updated_s'));
        return redirect( route('sub_three_cat.show',$model->sub_category_id));
    }
    public function destroy($id)
    {
        $data['deleted'] = "1";
        SubThreeCategory::where('id',$id)->update($data);
        session()->flash('success', trans('messages.deleted_s'));
        return back();
    }
    // sorting
    public function sort(Request $request) {
        $post = $request->all();
        $count = 0;
        for ($i = 0; $i < count($post['id']); $i ++) :
            $index = $post['id'][$i];
            $home_section = SubThreeCategory::findOrFail($index);
            $count ++;
            $newPosition = $count;
            $data['sort'] = $newPosition;
            if($home_section->update($data)) {
                echo "success";
            }else {
                echo "failed";
            }
        endfor;
        exit('success');
    }
}
