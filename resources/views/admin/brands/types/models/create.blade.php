@extends('admin.app')
@section('title' , __('messages.add_new_model'))
@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.add_new_model') }}</h4>
                 </div>
            </div>
        </div>
        <form action="{{route('models.store')}}" method="post" enctype="multipart/form-data" >
            @csrf
            <input required type="hidden" name="marka_type_id" value="{{$type_id}}" class="form-control" >
            <div class="form-group mb-4">
                <label for="plan_price">{{ __('messages.year') }}</label>
                <input required type="number" name="year" min="0"  class="form-control" >
            </div>
            <input type="submit" value="{{ __('messages.add') }}" class="btn btn-primary">
        </form>
    </div>
@endsection
