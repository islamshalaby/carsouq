@extends('admin.app')
@section('title' , __('messages.add_new_feature'))
@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.add_new_feature') }}</h4>
                 </div>
            </div>
        </div>
        <form action="{{route('plans.details.store')}}" method="post" enctype="multipart/form-data" >
            @csrf
            <input required type="hidden" name="plan_id" value="{{$plan_id}}" class="form-control" >
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.feature_name_ar') }}</label>
                <input required type="text" name="title_ar" class="form-control" >
            </div>
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.feature_name_en') }}</label>
                <input required type="text" name="title_en" class="form-control" >
            </div>
            <div class="form-group mr-1">
                <label for="plan_price">{{ __('messages.feature_type') }}</label>
                <select name="type" class="form-control" id="exampleFormControlSelect1">
                    <option value="re_post">{{ __('messages.re_post') }}</option>
                    <option value="pin">{{ __('messages.pin_it') }}</option>
                    <option value="special">{{ __('messages.special') }}</option>
                </select>
            </div>
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.days_num') }}</label>
                <input required type="text" name="expire_days" class="form-control" >
            </div>
            <input type="submit" value="{{ __('messages.add') }}" class="btn btn-primary">
        </form>
    </div>
@endsection
