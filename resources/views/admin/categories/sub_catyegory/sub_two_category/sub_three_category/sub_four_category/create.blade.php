@extends('admin.app')
@section('title' , __('messages.add'))
@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.add') }}</h4>
                    </div>
                </div>
            </div>
            <form action="{{route('sub_four_cat.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <input required type="hidden" name="sub_category_id" value="{{$id}}" class="form-control">
                <div class="custom-file-container" data-upload-id="myFirstImage">
                    <label>{{ __('messages.upload') }} ({{ __('messages.single_image') }}) <a href="javascript:void(0)"
                                                                                              class="custom-file-container__image-clear"
                                                                                              title="Clear Image">x</a></label>
                    <label class="custom-file-container__custom-file">
                        <input type="file" required name="image"
                               class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                        <input type="hidden" name="MAX_FILE_SIZE" value="10485760"/>
                        <span class="custom-file-container__custom-file__custom-file-control"></span>
                    </label>
                    <div class="custom-file-container__image-preview"></div>
                </div>
                <div class="form-group mb-4">
                    <label for="plan_price">{{ __('messages.name_ar') }}</label>
                    <input required type="text" name="title_ar" class="form-control">
                </div>
                <div class="form-group mb-4">
                    <label for="plan_price">{{ __('messages.name_en') }}</label>
                    <input required type="text" name="title_en" class="form-control">
                </div>
                <input type="submit" value="{{ __('messages.add') }}" class="btn btn-primary">
            </form>
        </div>
    </div>
@endsection
