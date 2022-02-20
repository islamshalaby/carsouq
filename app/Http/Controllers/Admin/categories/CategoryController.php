<?php
namespace App\Http\Controllers\Admin\categories;
use App\Category_option;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use App\Category;

class CategoryController extends AdminController{
    // type : get -> to add new
    public function AddGet(){
        return view('admin.categories.create');
    }
    // type : post -> add new category
    public function AddPost(Request $request){
        $image_name = $request->file('image')->getRealPath();
        Cloudder::upload($image_name, null);
        $imagereturned = Cloudder::getResult();
        $image_id = $imagereturned['public_id'];
        $image_format = $imagereturned['format'];
        $image_new_name = $image_id.'.'.$image_format;

        $category = new Category();
        $category->title_en = $request->title_en;
        $category->title_ar = $request->title_ar;
        $category->image = $image_new_name;
        $category->save();

        session()->flash('success', trans('messages.added_s'));
        return redirect('admin-panel/categories/show');
    }
    // get all categories
    public function show(){
        $data['categories'] = Category::where('deleted' , 0)->orderBy('sort' , 'asc')->get();
        $data['prevent_next_level'] = false;
        if ($data['categories'] && count($data['categories']) > 0) {
            foreach($data['categories'] as $row) {
                $row->next_level = false;
                if ($row->ViewSubCategories && count($row->ViewSubCategories) > 0) {
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
                    $data['prevent_next_level'] = true;
                    break;
                }
            }
        }
        return view('admin.categories.index' , ['data' => $data]);
    }
    // get edit page
    public function EditGet(Request $request){
        $data['category'] = Category::find($request->id);
        return view('admin.categories.edit' , ['data' => $data ]);
    }
    // edit category
    public function EditPost(Request $request){
        $category = Category::find($request->id);
        if($request->file('image')){
            $image = $category->image;
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
            $category->image = $image_new_name;
        }
        $category->title_en = $request->title_en;
        $category->title_ar = $request->title_ar;
        $category->save();
        return redirect('admin-panel/categories/show');
    }
    // delete category
    public function delete(Request $request){
        $category = Category::find($request->id);
        $category->deleted = 1;
        $category->save();
        return redirect()->back();
    }
    // get category products
    public function category_products(Category $category) {
        $data['products'] = $category->products;
        if (app()->getLocale() == 'en') {
            $data['category'] = $category->title_en;
        }else {
            $data['category'] = $category->title_ar;
        }
        return view('admin.products.products', ['data' => $data]);
    }

    // sorting
    public function sort(Request $request) {
        $post = $request->all();
        $count = 0;
        for ($i = 0; $i < count($post['id']); $i ++) :
            $index = $post['id'][$i];
            $home_section = Category::findOrFail($index);
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
