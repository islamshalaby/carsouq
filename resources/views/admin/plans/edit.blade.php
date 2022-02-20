@extends('admin.app')

@section('title' , __('messages.plan_edit'))

@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.plan_edit') }}</h4>
                 </div>
            </div>
        </div>
        <form action="{{route('plans.update.new',$plan->id)}}" method="post" >
            @csrf
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.plan_name_ar') }}</label>
                <input type="text" name="title_ar" value="{{$plan->title_ar}}" class="form-control" >
            </div>
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.plan_name_en') }}</label>
                <input required type="text" name="title_en"  value="{{$plan->title_en}}" class="form-control" >
            </div>
            <div class="form-group mr-1">
                @php $cats = \App\Category::where('deleted','0')->get(); @endphp
                <label for="plan_price">{{ __('messages.category') }}</label>
                <select name="cat_id" class="form-control" id="exampleFormControlSelect1">

                    @foreach($cats as $cat)
                        @if($plan->cat_id == 'all')
                            <option value="all" selected> {{ __('messages.all') }}</option>
                        @else
                            <option value="all"> {{ __('messages.all') }}</option>
                        @endif
                        @if($plan->cat_id == $cat->id)
                            <option value="{{$cat->id}}" selected>
                                {{ app()->getLocale() == 'en' ? $cat->title_en : $cat->title_ar }}
                            </option>
                        @else
                            <option value="{{$cat->id}}">
                                {{ app()->getLocale() == 'en' ? $cat->title_en : $cat->title_ar }}
                            </option>
                        @endif

                    @endforeach
                </select>
            </div>
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.plan_price') }}</label>
                <input required type="number" name="price" class="form-control" value="{{$plan->price}}" id="plan_price"  step="any" min="0">
            </div>
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.publish_day_num') }}</label>
                <input type="number" name="day_num" class="form-control" value="{{$plan->day_num}}" id="plan_price" min="0">
            </div>
            @php $plan_repost = \App\Plan_details::where('type','re_post')->where('plan_id',$plan->id)->first(); @endphp
            <div class="form-group row" >
                <div class="col-md-3">
                    <label for="plan_price"> &nbsp; </label>
                    <div class="form-check pl-0">
                        <div class="custom-control custom-checkbox checkbox-info">
                            <input type="checkbox" @if($plan_repost !=null) checked @endif class="custom-control-input" name="re_post" value="re_post" id="re_post_id">
                            <label class="custom-control-label" for="re_post_id">{{ __('messages.re_post') }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" id="re_post1_cont"  @if($plan_repost ==null) style="display:none;"@endif >
                    <label for="plan_price">{{ __('messages.name_ar') }}</label>
                    <input  type="text" name="re_post_title_ar"  class="form-control"  @if($plan_repost !=null) value="{{$plan_repost->title_ar}}"@endif >
                </div>
                <div class="col-md-3" id="re_post2_cont" @if($plan_repost ==null) style="display:none;"@endif  >
                    <label for="plan_price">{{ __('messages.name_en') }}</label>
                    <input  type="text" name="re_post_title_en"  class="form-control"  @if($plan_repost !=null) value="{{$plan_repost->title_en}}"@endif >
                </div>
                <div class="col-md-3" id="re_post3_cont" @if($plan_repost ==null) style="display:none;"@endif  >
                    <label for="plan_price">{{ __('messages.days_num') }}</label>
                    <input  type="number" name="re_post_days" class="form-control"  @if($plan_repost !=null) value="{{$plan_repost->expire_days}}"@endif  min="0">
                </div>
            </div>
            @php $plan_pin = \App\Plan_details::where('type','pin')->where('plan_id',$plan->id)->first(); @endphp
            <div class="form-group row">
                <div class="col-md-3">
                    <label for="plan_price"> &nbsp; </label>
                    <div class="form-check pl-0">
                        <div class="custom-control custom-checkbox checkbox-info">
                            <input type="checkbox" @if($plan_pin !=null) checked @endif  class="custom-control-input" name="pin" value="pin" id="pin_id">
                            <label class="custom-control-label" for="pin_id">{{ __('messages.pin_it') }}</label>
                        </div>
                    </div>
                </div>
                    <div class="col-md-3" id="pin_it1_cont" @if($plan_pin ==null) style="display:none;"@endif >
                        <label for="plan_price">{{ __('messages.name_ar') }}</label>
                        <input  type="text" name="pin_it_title_ar"  class="form-control"  @if($plan_pin !=null) value="{{$plan_pin->title_ar}}"@endif >
                    </div>
                    <div class="col-md-3" id="pin_it2_cont" @if($plan_pin ==null) style="display:none;"@endif >
                        <label for="plan_price">{{ __('messages.name_en') }}</label>
                        <input  type="text" name="pin_it_title_en"  class="form-control"  @if($plan_pin !=null) value="{{$plan_pin->title_en}}"@endif >
                    </div>
                    <div class="col-md-3" id="pin_it3_cont" @if($plan_pin ==null) style="display:none;"@endif >
                        <label for="plan_price">{{ __('messages.days_num') }}</label>
                        <input  type="number" name="pin_it_hours" class="form-control"  @if($plan_pin !=null) value="{{$plan_pin->expire_days}}"@endif  min="0">
                    </div>
            </div>
            @php $plan_special = \App\Plan_details::where('type','special')->where('plan_id',$plan->id)->first(); @endphp
            <div class="form-group row">
                <div class="col-md-3">
                    <label for="plan_price"> &nbsp; </label>
                    <div class="form-check pl-0">
                        <div class="custom-control custom-checkbox checkbox-info">
                            <input type="checkbox" @if($plan_special !=null) checked @endif class="custom-control-input" name="special" value="special" id="special_id">
                            <label class="custom-control-label" for="special_id">{{ __('messages.add_to_spicial') }}</label>
                        </div>
                    </div>
                </div>
                    <div class="col-md-3" id="special1_cont" @if($plan_special ==null) style="display:none;"@endif >
                        <label for="plan_price">{{ __('messages.name_ar') }}</label>
                        <input  type="text" name="special_title_ar"  class="form-control"  @if($plan_special !=null) value="{{$plan_special->title_ar}}" @endif >
                    </div>
                    <div class="col-md-3" id="special2_cont" @if($plan_special ==null) style="display:none;"@endif >
                        <label for="plan_price">{{ __('messages.name_en') }}</label>
                        <input  type="text" name="special_title_en"  class="form-control"  @if($plan_special !=null) value="{{$plan_special->title_ar}}" @endif >
                    </div>
                    <div class="col-md-3" id="special3_cont" @if($plan_special ==null) style="display:none;"@endif >
                        <label for="plan_price">{{ __('messages.days_num') }}</label>
                        <input  type="number" name="special_hours" class="form-control"  @if($plan_special !=null) value="{{$plan_special->expire_days}}" @endif  min="0">
                    </div>
            </div>
            <input type="submit" value="{{ __('messages.edit') }}" class="btn btn-primary">
        </form>
    </div>
@endsection
@section('scripts')
    <script src="/admin/assets/js/plans.js"></script>
@endsection
