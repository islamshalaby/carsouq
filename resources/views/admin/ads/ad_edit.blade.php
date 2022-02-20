@extends('admin.app')

@section('title' , __('messages.ad_edit'))



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
                    $('select#products').prop("disabled", false)
                    data.forEach(function (product) {
                        $('select#products').append(
                            "<option value='" + product.id + "'>" + product.title + "</option>"
                        )
                    })
                }
            })
        })

        var user = $("select#users option:selected").val()

        @if ($data['ad']['type'] == 'id')
        $.ajax({
            url : "/admin-panel/ads/fetchproducts/" + user,
            type : 'GET',
            success : function (data) {
                $('.productsParent').show()
                $('select#products').prop("disabled", false)
                var productId = "{{ $data['ad']['content'] }}"
                data.forEach(function (product) {
                    var selected = ""
                    if (productId == product.id) {
                        selected = "selected"
                    }
                    $('select#products').append(
                        "<option " + selected + " value='" + product.id + "'>" + product.title + "</option>"
                    )
                })
            }
        })
        @endif

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
                        <h4>{{ __('messages.ad_edit') }}</h4>
                 </div>
        </div>
        <form action="" method="post" enctype="multipart/form-data" >
            @csrf
            <div class="form-group mb-4">
                <label for="">{{ __('messages.current_image') }}</label><br>
                <img src="https://res.cloudinary.com/carsads/image/upload/w_100,q_100/v1581928924/{{ $data['ad']['image'] }}"  />
            </div>
            <div class="custom-file-container" data-upload-id="myFirstImage">
                <label>{{ __('messages.change_image') }} ({{ __('messages.single_image') }}) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                <label class="custom-file-container__custom-file" >
                    <input type="file" name="image" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                    <span class="custom-file-container__custom-file__custom-file-control"></span>
                </label>
                <div class="custom-file-container__image-preview"></div>
            </div>
            <div class="form-group">
                <label for="sel1">{{ __('messages.ad_place') }}</label>
                <select class="form-control" name="place" id="sel1">
                    <option selected>{{ __('messages.select') }}</option>
                    <option {{ $data['ad']['place'] == 1 ? 'selected' : '' }} value="1">{{ __('messages.on_the_top') }}</option>
                    <option {{ $data['ad']['place'] == 2 ? 'selected' : '' }} value="2">{{ __('messages.on_the_middle') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="sel1">{{ __('messages.ad_type') }}</label>
                <select id="ad_type" name="type" class="form-control">
                    <option selected>{{ __('messages.select') }}</option>
                    <option {{ $data['ad']['type'] == 'link' ? 'selected' : '' }} value="1">{{ __('messages.outside_the_app') }}</option>
                    <option {{ $data['ad']['type'] == 'id' ? 'selected' : '' }} value="2">{{ __('messages.inside_the_app') }}</option>
                </select>
            </div>
                   
            <div style="display: {{ $data['ad']['type'] == 'id' ? 'none' : '' }}" class="form-group mb-4 outside">
                <label for="link">{{ __('messages.link') }}</label>
                <input required type="text" name="content" class="form-control" id="link" placeholder="{{ __('messages.link') }}" value="{{ $data['ad']['content'] }}" >
            </div>
            

            <div style="display: {{ $data['ad']['type'] == 'link' ? 'none' : '' }}" class="form-group inside">
                <label for="users">{{ __('messages.user') }}</label>
                <select id="users" class="form-control">
                    <option selected disabled>{{ __('messages.select') }}</option>
                    @foreach ($data['users'] as $user)
                    <option {{ isset($data['product']['user']) && $data['product']['user']['id'] == $user->id ? 'selected' : ''  }} value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display: none" class="form-group productsParent">
                <label for="products">{{ __('messages.product') }}</label>
                <select id="products" class="form-control" name="content">
                </select>
            </div>
            
            <input type="submit" value="{{ __('messages.edit') }}" class="btn btn-primary">
        </form>
    </div>
@endsection