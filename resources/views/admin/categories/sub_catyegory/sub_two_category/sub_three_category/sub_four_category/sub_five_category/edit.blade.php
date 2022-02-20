@extends('admin.app')
@section('title' , __('messages.edit'))
@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.edit') }}</h4>
                    </div>
                </div>
            </div>
            <form action="{{route('sub_five_cat.update.new',$data->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-4">
                    <label for="">{{ __('messages.current_image') }}</label><br>
                    <img
                        src="https://res.cloudinary.com/carsads/image/upload/w_100,q_100/v1581928924/{{ $data->image }}"/>
                </div>
                <div class="custom-file-container" data-upload-id="myFirstImage">
                    <label>{{ __('messages.change_image') }} ({{ __('messages.single_image') }}) <a
                            href="javascript:void(0)" class="custom-file-container__image-clear"
                            title="Clear Image">x</a></label>
                    <label class="custom-file-container__custom-file">
                        <input type="file" name="image" class="custom-file-container__custom-file__custom-file-input"
                               accept="image/*">
                        <input type="hidden" name="MAX_FILE_SIZE" value="10485760"/>
                        <span class="custom-file-container__custom-file__custom-file-control"></span>
                    </label>
                    <div class="custom-file-container__image-preview"></div>
                </div>
                <div class="form-group mb-4">
                    <label for="plan_price">{{ __('messages.name_ar') }}</label>
                    <input required type="text" value="{{$data->title_ar}}" name="title_ar" class="form-control">
                </div>
                <div class="form-group mb-4">
                    <label for="plan_price">{{ __('messages.name_en') }}</label>
                    <input required type="text" value="{{$data->title_en}}" name="title_en" class="form-control">
                </div>
                <input type="submit" value="{{ __('messages.edit') }}" class="btn btn-primary">
            </form>
        </div>
    </div>
@endsection
