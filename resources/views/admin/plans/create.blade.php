@extends('admin.app')

@section('title' , __('messages.add_plan'))

@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.add_plan') }}</h4>
                 </div>
            </div>
        </div>
        <form action="{{route('plans.store')}}" method="post" enctype="multipart/form-data" >
            @csrf
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.plan_name_ar') }}</label>
                <input type="text" name="title_ar" class="form-control" >
            </div>
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.plan_name_en') }}</label>
                <input required type="text" name="title_en" class="form-control" >
            </div>
            <div class="form-group mr-1">
                @php $cats = \App\Category::where('deleted','0')->get(); @endphp
                <label for="plan_price">{{ __('messages.category') }}</label>
                <select name="cat_id" class="form-control" id="exampleFormControlSelect1">
                    <option value="all"> {{ __('messages.all') }}</option>
                    @foreach($cats as $cat)
                        <option value="{{$cat->id}}">
                            {{ app()->getLocale() == 'en' ? $cat->title_en : $cat->title_ar }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.plan_price') }}</label>
                <input required type="number" name="price" class="form-control" id="plan_price"  step="any" min="0">
            </div>
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.publish_day_num') }}</label>
                <input type="number" name="day_num" class="form-control" id="plan_price" min="0">
            </div>
            <div class="form-group row" >
                <div class="col-md-3">
                    <label for="plan_price"> &nbsp; </label>
                    <div class="form-check pl-0">
                        <div class="custom-control custom-checkbox checkbox-info">
                            <input type="checkbox" class="custom-control-input" name="re_post" value="re_post" id="re_post_id">
                            <label class="custom-control-label" for="re_post_id">{{ __('messages.re_post') }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" id="re_post1_cont" style="display:none;">
                    <label for="plan_price">{{ __('messages.name_ar') }}</label>
                    <input  type="text" name="re_post_title_ar"  class="form-control" value="اعادة نشرة فى المكان الاول كل 8 أيام">
                </div>
                <div class="col-md-3" id="re_post2_cont" style="display:none;">
                    <label for="plan_price">{{ __('messages.name_en') }}</label>
                    <input  type="text" name="re_post_title_en"  class="form-control" value="Republish every 8 days">
                </div>
                <div class="col-md-3" id="re_post3_cont" style="display:none;">
                    <label for="plan_price">{{ __('messages.days_num') }}</label>
                    <input  type="number" name="re_post_days" class="form-control" value="8" min="0">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3">
                    <label for="plan_price"> &nbsp; </label>
                    <div class="form-check pl-0">
                        <div class="custom-control custom-checkbox checkbox-info">
                            <input type="checkbox" class="custom-control-input" name="pin" value="pin" id="pin_id">
                            <label class="custom-control-label" for="pin_id">{{ __('messages.pin_it') }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" id="pin_it1_cont" style="display:none;">
                    <label for="plan_price">{{ __('messages.name_ar') }}</label>
                    <input  type="text" name="pin_it_title_ar"  class="form-control" value="يتم تثبيته فى الاعلى لمدة 72 ساعة">
                </div>
                <div class="col-md-3" id="pin_it2_cont" style="display:none;">
                    <label for="plan_price">{{ __('messages.name_en') }}</label>
                    <input  type="text" name="pin_it_title_en"  class="form-control" value="It is pined on top for 72 hours">
                </div>
                <div class="col-md-3" id="pin_it3_cont" style="display:none;">
                    <label for="plan_price">{{ __('messages.days_num') }}</label>
                    <input  type="number" name="pin_it_hours" class="form-control" value="72" min="0">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3">
                    <label for="plan_price"> &nbsp; </label>
                    <div class="form-check pl-0">
                        <div class="custom-control custom-checkbox checkbox-info">
                            <input type="checkbox" class="custom-control-input" name="special" value="special" id="special_id">
                            <label class="custom-control-label" for="special_id">{{ __('messages.add_to_spicial') }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" id="special1_cont" style="display:none;">
                    <label for="plan_price">{{ __('messages.name_ar') }}</label>
                    <input  type="text" name="special_title_ar"  class="form-control" value="يتم أظهار الاعلان فى القسم المميز ل 10 ايام">
                </div>
                <div class="col-md-3" id="special2_cont" style="display:none;">
                    <label for="plan_price">{{ __('messages.name_en') }}</label>
                    <input  type="text" name="special_title_en"  class="form-control" value="The ad will appear in the special section fot 10 days">
                </div>
                <div class="col-md-3" id="special3_cont" style="display:none;">
                    <label for="plan_price">{{ __('messages.days_num') }}</label>
                    <input  type="number" name="special_hours" class="form-control" value="10" min="0">
                </div>
            </div>
            <input type="submit" value="{{ __('messages.add') }}" class="btn btn-primary">
        </form>
    </div>
@endsection
@section('scripts')
    <script src="/admin/assets/js/plans.js"></script>
@endsection
