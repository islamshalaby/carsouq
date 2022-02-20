@extends('admin.app')
@section('title' , __('messages.add'))
@push('scripts')
    <script>
        $("select#users").on("change", function () {
            $('select#products').html("")
            var userId = $(this).find("option:selected").val();
            console.log(userId)
            $.ajax({
                url : "/admin-panel/ads/fetchproducts/" + userId,
                type : 'GET',
                success : function (data) {
                    $('.productsParent').show()
                    $('#products_cont').show();
                    $('select#products').prop("disabled", false)
                    data.forEach(function (product) {
                        $('select#products').append(
                            "<option value='" + product.id + "'>" + product.title + "</option>"
                        )
                    })
                }
            })
        })
        $("#ad_type").on("change", function() {
            if(this.value == 1) {
                $(".outside").show()
                $('.productsParent').hide()
                $('select#products').prop("disabled", true)
                $(".outside input").prop("disabled", false)
                $(".inside").hide()
            }else {
                $(".outside").hide()
                $(".outside input").prop("disabled", true)
                $(".inside").show()
            }
        })
    </script>
@endpush
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
            @if(Route::current()->getName() == 'sub_two_cat_ads.create')
                <form action="{{route('sub_two_cat_ads.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input required type="hidden" name="id" value="{{$id}}" class="form-control">
            @else
                <form action="{{route('sub_two_cat_ads.store_all_categories',$id)}}" method="post" enctype="multipart/form-data">
                @csrf
            @endif
                <div class="custom-file-container" data-upload-id="myFirstImage">
                    <label>{{ __('messages.upload') }} ({{ __('messages.single_image') }}) <a
                            href="javascript:void(0)" class="custom-file-container__image-clear"
                            title="Clear Image">x</a></label>
                    <label class="custom-file-container__custom-file">
                        <input type="file" required name="image"
                               class="custom-file-container__custom-file__custom-file-input"
                               accept="image/*">
                        <input type="hidden" name="MAX_FILE_SIZE" value="10485760"/>
                        <span class="custom-file-container__custom-file__custom-file-control"></span>
                    </label>
                    <div class="custom-file-container__image-preview"></div>
                </div>
                    <div class="form-group">
                        <label for="sel1">{{ __('messages.ad_type') }}</label>
                        <select id="ad_type" required name="ad_type" class="form-control">
                            <option selected>{{ __('messages.select') }}</option>
                            <option value="out">{{ __('messages.outside_the_app') }}</option>
                            <option value="in">{{ __('messages.inside_the_app') }}</option>
                        </select>
                    </div>
                    <div style="display: none" class="form-group mb-4 outside">
                        <label for="link">{{ __('messages.link') }}</label>
                        <input required type="text" name="content" class="form-control" id="link" placeholder="{{ __('messages.link') }}" value="" >
                    </div>
                    <div style="display: none" class="form-group inside">
                        <label for="users">{{ __('messages.user') }}</label>
                        <select id="users" class="form-control">
                            <option selected disabled>{{ __('messages.select') }}</option>
                            @php $users = \App\User::orderBy('created_at', 'desc')->get(); @endphp
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: none" id="products_cont" class="form-group productsParent">
                        <label for="products">{{ __('messages.product') }}</label>
                        <select id="products" class="form-control" name="content">
                        </select>
                    </div>
                <input type="submit" value="{{ __('messages.add') }}" class="btn btn-primary">
            </form>
        </div>
@endsection
