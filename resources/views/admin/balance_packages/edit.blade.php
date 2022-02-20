@extends('admin.app')

@section('title' , __('messages.category_edit'))

@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">

                        <h4>{{ __('messages.category_edit') }}</h4>
                 </div>
        </div>
        <form action="{{route('balance_p.update',$data->id)}}" method="post" enctype="multipart/form-data" >
            @csrf
            <div class="form-group mb-4">
                <label for="title_ar">{{ __('messages.name_ar') }}</label>
                <input required type="text" name="name_ar" value="{{$data->name_ar}}" class="form-control" id="name_ar">
            </div>
            <div class="form-group mb-4">
                <label for="title_ar">{{ __('messages.name_en') }}</label>
                <input required type="text" name="name_en" value="{{$data->name_en}}" class="form-control" id="name_en">
            </div>
            <div class="form-group mb-4">
                <label for="title_ar">{{ __('messages.desc_ar') }}</label>
                <input required type="text" name="desc_ar" value="{{$data->desc_ar}}" class="form-control" id="desc_ar">
            </div>
            <div class="form-group mb-4">
                <label for="title_ar">{{ __('messages.desc_en') }}</label>
                <input required type="text" name="desc_en" value="{{$data->desc_en}}" class="form-control" id="desc_en" >
            </div>
            <div class="form-group mb-4">
                <label for="title_ar">{{ __('messages.price') }}</label>
                <input required type="number" name="price" value="{{$data->price}}" step="any" class="form-control" id="price">
            </div>
            <div class="form-group mb-4">
                <label for="title_ar">{{ __('messages.amount') }}</label>
                <input required type="number" name="amount" value="{{$data->amount}}" step="any" class="form-control" id="amount">
            </div>
            <input type="submit" value="{{ __('messages.edit') }}" class="btn btn-primary">
        </form>
    </div>
@endsection
