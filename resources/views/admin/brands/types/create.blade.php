@extends('admin.app')
@section('title' , __('messages.add_new_type'))
@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.add_new_type') }}</h4>
                 </div>
            </div>
        </div>
        <form action="{{route('brand_types.store')}}" method="post" enctype="multipart/form-data" >
            @csrf
            <input required type="hidden" name="marka_id" value="{{$plan_id}}" class="form-control" >
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.name_ar') }}</label>
                <input required type="text" name="title_ar" class="form-control" >
            </div>
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.name_en') }}</label>
                <input required type="text" name="title_en" class="form-control" >
            </div>
            <input type="submit" value="{{ __('messages.add') }}" class="btn btn-primary">
        </form>
    </div>
@endsection
