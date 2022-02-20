<?php
namespace App\Http\Controllers\Admin;
use App\Plan_details;
use Illuminate\Http\Request;
use App\Plan;
class PlanController extends AdminController{
    // show
    public function index() {
        $data['plans'] = Plan::where('deleted','0')->OrderBy('id','asc')->get();
        return view('admin.plans.index', ["data" => $data]);
    }

    public function create() {
        return view('admin.plans.create');
    }
    public function store(Request $request) {
        $data = $this->validate(\request(),
            [
                'title_ar' => 'required',
                'title_en' => 'required',
                'cat_id' => 'required',
                'price' => 'required|numeric',
                'day_num' => 'required|numeric',
            ]);
        if($request->re_post == 're_post'){
             $this->validate(\request(),
                [
                    're_post_title_ar' => 'required',
                    're_post_title_en' => 'required',
                    're_post_days' => 'required'
                ]);
        }
        if($request->pin == 'pin'){
            $this->validate(\request(),
                [
                    'pin_it_title_ar' => 'required',
                    'pin_it_title_en' => 'required',
                    'pin_it_hours' => 'required'
                ]);
        }
        if($request->special == 'special'){
            $this->validate(\request(),
                [
                    'special_title_ar' => 'required',
                    'special_title_en' => 'required',
                    'special_hours' => 'required'
                ]);
        }

        $plan = Plan::create($data);
        $feature_data['title_ar'] = 'يعرض ل '.$request->day_num.' يوم';
        $feature_data['title_en'] = 'display for '.$request->day_num.' day';
        $feature_data['plan_id'] = $plan->id;
        $feature_data['type'] = 'expier_num';
        $feature_data['expire_days'] = $request->day_num;
        Plan_details::create($feature_data);
        if($request->re_post == 're_post'){
            $repost_data['title_ar'] = $request->re_post_title_ar;
            $repost_data['title_en'] = $request->re_post_title_en;
            $repost_data['plan_id'] = $plan->id;
            $repost_data['type'] = 're_post';
            $repost_data['expire_days'] = $request->re_post_days;
            Plan_details::create($repost_data);
        }
        if($request->pin == 'pin'){
            $pin_data['title_ar'] = $request->pin_it_title_ar;
            $pin_data['title_en'] = $request->pin_it_title_en;
            $pin_data['plan_id'] = $plan->id;
            $pin_data['type'] = 'pin';
            $pin_data['expire_days'] = $request->pin_it_hours;
            Plan_details::create($pin_data);
        }
        if($request->special == 'special'){
            $pin_data['title_ar'] = $request->special_title_ar;
            $pin_data['title_en'] = $request->special_title_en;
            $pin_data['plan_id'] = $plan->id;
            $pin_data['type'] = 'special';
            $pin_data['expire_days'] = $request->special_hours;
            Plan_details::create($pin_data);
        }
        session()->flash('success', trans('messages.added_s'));
        return redirect( route('plans.index'));
    }
    public function edit($id) {

        $plan = Plan::findOrFail($id);
        return view('admin.plans.edit', compact('plan'));
    }
    public function update_plan_status(Request $request) {
        $data['status'] = $request->status ;
        Plan::where('id', $request->id)->update($data);
        return 1;
    }
    public function update(Request $request, $id) {
        $data = $this->validate(\request(),
            [
                'title_ar' => 'required',
                'title_en' => 'required',
                'cat_id' => 'required',
                'price' => 'required|numeric',
                'day_num' => 'required|numeric',
            ]);
        if($request->re_post == 're_post'){
            $this->validate(\request(),
                [
                    're_post_title_ar' => 'required',
                    're_post_title_en' => 'required',
                    're_post_days' => 'required'
                ]);
        }
        if($request->pin == 'pin'){
            $this->validate(\request(),
                [
                    'pin_it_title_ar' => 'required',
                    'pin_it_title_en' => 'required',
                    'pin_it_hours' => 'required'
                ]);
        }
        if($request->special == 'special'){
            $this->validate(\request(),
                [
                    'special_title_ar' => 'required',
                    'special_title_en' => 'required',
                    'special_hours' => 'required'
                ]);
        }

        $plan = Plan::find($id);

        $user = Plan::where('id',$id)->update($data);

        $plan_detail_expire = Plan_details::where('plan_id',$id)->where('type','expier_num')->first();
        $plan_detail_expire->title_en ='display for '.$request->day_num.' day';
        $plan_detail_expire->title_ar ='يعرض ل '.$request->day_num.' يوم';
        $plan_detail_expire->expire_days =$request->day_num;
        $plan_detail_expire->save();

        if($request->re_post == 're_post'){
            $plan_detail_re_post = Plan_details::where('plan_id',$id)->where('type','re_post')->first();
            if($plan_detail_re_post == null){
                $repost_data['title_ar'] = $request->re_post_title_ar;
                $repost_data['title_en'] = $request->re_post_title_en;
                $repost_data['plan_id'] = $plan->id;
                $repost_data['type'] = 're_post';
                $repost_data['expire_days'] = $request->re_post_days;
                Plan_details::create($repost_data);
            }else{
                $plan_detail_re_post->title_ar =$request->re_post_title_ar;
                $plan_detail_re_post->title_en =$request->re_post_title_en;
                $plan_detail_re_post->expire_days =$request->re_post_days;
                $plan_detail_re_post->save();
            }
        }else{
            $plan_detail_re_post = Plan_details::where('plan_id',$id)->where('type','re_post')->first();
            if($plan_detail_re_post != null){
                $plan_detail_re_post->delete();
            }
        }
        if($request->pin == 'pin'){
            $plan_detail_pin = Plan_details::where('plan_id',$id)->where('type','pin')->first();
            if($plan_detail_pin == null){
                $pin_data['title_ar'] = $request->pin_it_title_ar;
                $pin_data['title_en'] = $request->pin_it_title_en;
                $pin_data['plan_id'] = $plan->id;
                $pin_data['type'] = 'pin';
                $pin_data['expire_days'] = $request->pin_it_hours;
                Plan_details::create($pin_data);
            }else{
                $plan_detail_pin->title_ar =$request->pin_it_title_ar;
                $plan_detail_pin->title_en =$request->pin_it_title_en;
                $plan_detail_pin->expire_days =$request->pin_it_hours;
                $plan_detail_pin->save();
            }
        }else{
            $plan_detail_pin = Plan_details::where('plan_id',$id)->where('type','pin')->first();
            if($plan_detail_pin != null){
                $plan_detail_pin->delete();
            }
        }

        if($request->special == 'special'){
            $plan_detail_special = Plan_details::where('plan_id',$id)->where('type','special')->first();
            if($plan_detail_special == null){
                $pin_data['title_ar'] = $request->special_title_ar;
                $pin_data['title_en'] = $request->special_title_en;
                $pin_data['plan_id'] = $plan->id;
                $pin_data['type'] = 'special';
                $pin_data['expire_days'] = $request->special_hours;
                Plan_details::create($pin_data);
            }else{
                $plan_detail_special->title_ar = $request->special_title_ar;
                $plan_detail_special->title_en = $request->special_title_en;
                $plan_detail_special->expire_days = $request->special_hours;
                $plan_detail_special->save();
            }
        }else{
            $plan_detail_special = Plan_details::where('plan_id',$id)->where('type','special')->first();
            if($plan_detail_special != null){
                $plan_detail_special->delete();
            }
        }
        session()->flash('success', trans('messages.updated_s'));
        return redirect( route('plans.index'));
    }
    public function show(){
        dd('show');
    }
    public function destroy($id) {
        $data['deleted'] = '1';
        Plan::where('id',$id)->update($data);
        session()->flash('success', trans('messages.deleted_s'));
        return back();
    }

    // plan details actions
    public function plans_details($plan_id) {
        $plan_details = Plan_details::where('plan_id' , $plan_id)->get();
        return view('admin.plans.plan_details.index', compact('plan_details','plan_id'));
    }
    public function create_details($plan_id) {
        return view('admin.plans.plan_details.create',compact('plan_id'));
    }
    public function update_status(Request $request) {
        $data['status'] = $request->status ;
        Plan_details::where('id', $request->id)->update($data);
        return 1;
    }


    public function store_details(Request $request)
    {
        $data = $this->validate(\request(),
            [
                'plan_id' => 'required|exists:plans,id',
                'title_ar' => 'required',
                'title_en' => 'required',
                'type' => 'required'
            ]);
        $exist_detail = Plan_details::where('plan_id',$request->plan_id)->where('type',$request->type)->first();
        if($exist_detail != null){
            session()->flash('danger', trans('messages.you_have_enter_this_befor'));
            return back();
        }else{
            Plan_details::create($data);
            session()->flash('success', trans('messages.added_s'));
            return redirect( route('plans.details',$request->plan_id));
        }
    }
    public function edit_details($detail_id) {
        $detail = Plan_details::findOrFail($detail_id);
        return view('admin.plans.plan_details.edit',compact('detail'));
    }
    public function update_details(Request $request , $detail_id) {
        $detail = Plan_details::findOrFail($detail_id);
        $data = $this->validate(\request(),
            [
                'title_ar' => 'required',
                'title_en' => 'required',
                'expire_days' => 'required',
            ]);
        $user = Plan_details::where('id',$detail_id)->update($data);
        session()->flash('success', trans('messages.updated_s'));
        return redirect( route('plans.details',$detail->plan_id));
    }

    public function delete_details($detail_id) {
        Plan_details::where('id',$detail_id)->delete();
        session()->flash('success', trans('messages.deleted_s'));
        return back();
    }
    public function show_div($type) {
        return 1;
    }
}
