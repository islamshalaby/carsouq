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
        <form action="{{route('balance_packages.store')}}" method="post" enctype="multipart/form-data" >
            @csrf
            <div class="form-group mb-4">
                <label for="title_ar">{{ __('messages.name_ar') }}</label>
                <input required type="text" name="name_ar" class="form-control" id="name_ar">
            </div>
            <div class="form-group mb-4">
                <label for="title_ar">{{ __('messages.name_en') }}</label>
                <input required type="text" name="name_en" class="form-control" id="name_en">
            </div>
            <div class="form-group mb-4">
                <label for="title_ar">{{ __('messages.desc_ar') }}</label>
                <input required type="text" name="desc_ar" class="form-control" id="desc_ar">
            </div>
            <div class="form-group mb-4">
                <label for="title_ar">{{ __('messages.desc_en') }}</label>
                <input required type="text" name="desc_en" class="form-control" id="desc_en" >
            </div>
            <div class="form-group mb-4">
                <label for="title_ar">{{ __('messages.price') }}</label>
                <input required type="number" name="price" step="any" class="form-control" id="price">
            </div>
            <div class="form-group mb-4">
                <label for="title_ar">{{ __('messages.amount') }}</label>
                <input required type="number" name="amount" step="any" class="form-control" id="amount">
            </div>
            <input type="submit" value="{{ __('messages.add') }}" class="btn btn-primary">
        </form>
    </div>
@endsection
