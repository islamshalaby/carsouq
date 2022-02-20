@extends('admin.app')
@section('title' , __('messages.edit_feature'))
@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.edit_feature') }}</h4>
                 </div>
            </div>
        </div>
        <form action="{{route('plans.details.update',$detail->id)}}" method="put">
            @csrf
            <input required type="hidden" name="type" value="{{$detail->type}}" class="form-control" >
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.feature_name_ar') }}</label>
                <input required type="text" value="{{$detail->title_ar}}" name="title_ar" class="form-control" >
            </div>
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.feature_name_en') }}</label>
                <input required type="text" value="{{$detail->title_en}}"  name="title_en" class="form-control" >
            </div>
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.feature_type') }}</label>
                <h4>
                    @if($detail->type == 'expier_num')
                        {{ __('messages.expier_num') }}
                    @elseif($detail->type == 're_post')
                        {{ __('messages.re_post') }}
                    @elseif($detail->type == 'cars_only')
                        {{ __('messages.cars_only') }}
                    @elseif($detail->type == 'pin')
                        {{ __('messages.pin') }}
                    @endif
                </h4>
            </div>
            @if($detail->type != 'cars_only')
            <div class="form-group mb-4">
                @if($detail->type == 'pin')
                <label for="plan_price">{{ __('messages.feature_hours') }}</label>
                @else
                    <label for="plan_price">{{ __('messages.feature_days') }}</label>
                @endif
                <input required type="number" value="{{$detail->expire_days}}" name="expire_days"  min="1" class="form-control" >
            </div>
            @else
                <input required type="hidden" value="{{$detail->expire_days}}" name="expire_days"  min="1" class="form-control" >
            @endif
            <input type="submit" value="{{ __('messages.edit') }}" class="btn btn-primary">
        </form>
    </div>
@endsection
