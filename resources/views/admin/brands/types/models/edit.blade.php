@extends('admin.app')
@section('title' , __('messages.edit_model'))
@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.edit_model') }}</h4>
                 </div>
            </div>
        </div>
        <form action="{{route('model.update.new',$data->id)}}" method="put">
            @csrf
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.year') }}</label>
                <input required type="number" min="0" value="{{$data->year}}" name="year" class="form-control" >
            </div>
            <input type="submit" value="{{ __('messages.edit') }}" class="btn btn-primary">
        </form>
    </div>
@endsection
