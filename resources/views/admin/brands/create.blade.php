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
                <input required type="text" name="title_ar" class="form-control" >
            </div>
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.plan_name_en') }}</label>
                <input required type="text" name="title_en" class="form-control" >
            </div>
            <div class="form-group mb-4">
                <label for="ads_count">{{ __('messages.ads_count') }}</label>
                <input required type="number" name="ads_count" class="form-control" id="ads_count" placeholder="{{ __('messages.ads_count') }}" value="" >
            </div>
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.plan_price') }}</label>
                <input required type="number" name="price" class="form-control" id="plan_price"  step="any" min="0" placeholder="{{ __('messages.plan_price') }}" value="" >
            </div>
            <input type="submit" value="{{ __('messages.add') }}" class="btn btn-primary">
        </form>
    </div>
@endsection
