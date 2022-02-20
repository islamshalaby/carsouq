@extends('admin.app')

@section('title' , __('messages.terms_conditions'))

@section('content')

<div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.terms_conditions') }}</h4>
             </div>
        </div>
        @if (session('status'))
            <div class="alert alert-danger mb-4" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
                <strong>Error!</strong> {{ session('status') }} </button>
            </div>
        @endif

        <form action="{{route('save.terms')}}" method="post" >
            @csrf
            <h5>{{ __('messages.arabic') }}</h5>
            <div class="form-group mb-4 arabic-direction">
                <textarea id="editor-ck-ar" required name="termsandconditions_ar" class="form-control" id="termsandconditions_ar" rows="5">{{ $data['setting']['termsandconditions_ar'] }}</textarea>
            </div>
            <h5>{{ __('messages.english') }}</h5>
            <div class="form-group mb-4 arabic-direction">
                <textarea id="editor-ck-en" required name="termsandconditions_en" class="form-control" id="termsandconditions_en" rows="5">{{ $data['setting']['termsandconditions_en'] }}</textarea>
            </div>
            <input type="submit" value="{{ __('messages.edit') }}" class="btn btn-primary">
        </form>
</div>

@endsection
