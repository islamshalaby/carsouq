@extends('admin.app')
@section('title' , __('messages.add_new_product'))
@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.add_new_product') }}</h4>
                    </div>
                </div>
                @if (session('status'))
                    <div class="alert alert-danger mb-4" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
                        <strong>Error!</strong> {{ session('status') }} </button>
                    </div>
                @endif
                <form method="post" enctype="multipart/form-data" action="">
                    @csrf
                    <div class="form-group">
                        <label for="sel1">{{ __('messages.user') }}</label>
                        <select class="form-control" name="user_id" id="sel1">
                            <option selected disabled>{{ __('messages.select') }}</option>
                            @foreach ($data['users'] as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
{{--                    // 0--}}
                    <div class="form-group">
                        @php $cats = \App\Category::where('deleted',0)->get(); @endphp
                        <label for="sel1">{{ __('messages.category') }}</label>
                        <select required class="form-control" name="category_id" id="cmb_cat">
                            <option selected disabled>{{ __('messages.choose_category') }}</option>
                            @foreach ($cats as $row)
                                @if( app()->getLocale() == 'en')
                                    <option value="{{ $row->id }}">{{ $row->title_en }}</option>
                                @else
                                    <option value="{{ $row->id }}">{{ $row->title_ar }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
{{--                    // 1--}}
                    <div class="form-group" id="sub_cat_cont" style="display:none;">
                        @php $sub_cats = \App\SubCategory::where('deleted',0)->get(); @endphp
                        <label for="sel1">{{ __('messages.sub_category_first') }}</label>
                        <select required class="form-control" name="sub_category_id" id="cmb_sub_cat">
                            <option selected disabled>{{ __('messages.choose_sub_category') }}</option>
                            @foreach ($sub_cats as $row)
                                @if( app()->getLocale() == 'en')
                                    <option value="{{ $row->id }}">{{ $row->title_en }}</option>
                                @else
                                    <option value="{{ $row->id }}">{{ $row->title_ar }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
{{--                    // 2--}}
                    <div class="form-group" id="sub_two_cat_cont" style="display:none;">
                        @php $sub_two_cats = \App\SubTwoCategory::where('deleted',0)->get(); @endphp
                        <label for="sel1">{{ __('messages.sub_category_second') }}</label>
                        <select class="form-control" name="sub_category_two_id" id="cmb_sub_two_cat">
                            <option selected>{{ __('messages.choose_sub_two_category') }}</option>
                            @foreach ($sub_two_cats as $row)
                                @if( app()->getLocale() == 'en')
                                    <option value="{{ $row->id }}">{{ $row->title_en }}</option>
                                @else
                                    <option value="{{ $row->id }}">{{ $row->title_ar }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
{{--                    // 3--}}
                    <div class="form-group" id="sub_three_cat_cont" style="display:none;">
                        @php $sub_three_cats = \App\SubThreeCategory::where('deleted',0)->get(); @endphp
                        <label for="sel1">{{ __('messages.sub_category_third') }}</label>
                        <select class="form-control" name="sub_category_three_id" id="cmb_sub_three_cat">
                            <option selected>{{ __('messages.choose_sub_three_category') }}</option>
                            @foreach ($sub_three_cats as $row)
                                @if( app()->getLocale() == 'en')
                                    <option value="{{ $row->id }}">{{ $row->title_en }}</option>
                                @else
                                    <option value="{{ $row->id }}">{{ $row->title_ar }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
{{--                    // 4--}}
                    <div class="form-group" id="sub_four_cat_cont" style="display:none;" >
                        @php $sub_four_cats = \App\SubFourCategory::where('deleted',0)->get(); @endphp
                        <label for="sel1">{{ __('messages.sub_category_fourth') }}</label>
                        <select class="form-control" name="sub_category_four_id" id="cmb_sub_four_cat">
                            <option selected>{{ __('messages.choose_sub_four_category') }}</option>
                            @foreach ($sub_four_cats as $row)
                                @if( app()->getLocale() == 'en')
                                    <option value="{{ $row->id }}">{{ $row->title_en }}</option>
                                @else
                                    <option value="{{ $row->id }}">{{ $row->title_ar }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="sub_five_cat_cont" style="display:none;" >
                        @php $sub_five_cats = \App\SubFiveCategory::where('deleted','0')->get(); @endphp
                        <label for="sel1">{{ __('messages.sub_category_fifth') }}</label>
                        <select class="form-control" name="sub_category_five_id" id="cmb_sub_five_cat">
                            <option selected>{{ __('messages.choose_sub_five_category') }}</option>
                            @foreach ($sub_five_cats as $row)
                                @if( app()->getLocale() == 'en')
                                    <option value="{{ $row->id }}">{{ $row->title_en }}</option>
                                @else
                                    <option value="{{ $row->id }}">{{ $row->title_ar }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label for="title">{{ __('messages.product_name') }}</label>
                        <input required type="text" name="title" class="form-control" id="title"
                               placeholder="{{ __('messages.product_name') }}" value="">
                    </div>
                    <div class="form-group mb-4">
                        <label for="price">{{ __('messages.product_price') }}</label>
                        <input required type="number" class="form-control" step="any" min="0" id="price" name="price"
                               placeholder="{{ __('messages.product_price') }}" value="">
                    </div>
                    <h4>{{ __('messages.properties') }}</h4>
                    <div class="form-group" id="brand_cont" style="display:none;" >
                        @php $brands = \App\Category_option_value::where('deleted','0')->get(); @endphp
                        <label for="sel1">{{ __('messages.brand') }}</label>
                        <select required class="form-control" name="options[]" id="cmb_brand_id">
                            <option selected>{{ __('messages.choose_brand') }}</option>
                            @foreach ($brands as $row)
                                @if( app()->getLocale() == 'en')
                                    <option value="{{ $row->id }}">{{ $row->value_en }}</option>
                                @else
                                    <option value="{{ $row->id }}">{{ $row->value_ar }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="brand_types_cont" style="display:none;" >
                        <label for="sel1">{{ __('messages.brand_type') }}</label>
                        <select required class="form-control" name="options[]" id="cmb_brand_types_id">
                            <option selected>{{ __('messages.choose_brand_type') }}</option>
                            @foreach ($brands as $row)
                                @if( app()->getLocale() == 'en')
                                    <option value="{{ $row->id }}">{{ $row->value_en }}</option>
                                @else
                                    <option value="{{ $row->id }}">{{ $row->value_ar }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="model_year_cont" style="display:none;" >
                        <label for="sel1">{{ __('messages.model_year') }}</label>
                        <select required class="form-control" name="options[]" id="cmb_model_year_id">
                            <option selected>{{ __('messages.choose_model_year') }}</option>
                            @foreach ($brands as $row)
                                @if( app()->getLocale() == 'en')
                                    <option value="{{ $row->id }}">{{ $row->value_en }}</option>
                                @else
                                    <option value="{{ $row->id }}">{{ $row->value_ar }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="counter_cont" style="display:none;" >
                        <label for="sel1">{{ __('messages.counter') }}</label>
                        <select required class="form-control" name="options[]" id="cmb_counter_id">
                            <option selected>{{ __('messages.choose_counter') }}</option>
                            @foreach ($brands as $row)
                                @if( app()->getLocale() == 'en')
                                    <option value="{{ $row->id }}">{{ $row->value_en }}</option>
                                @else
                                    <option value="{{ $row->id }}">{{ $row->value_ar }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-4 arabic-direction">
                        <label for="description">{{ __('messages.product_description') }}</label>
                        <textarea required name="description" placeholder="{{ __('messages.product_description') }}"
                                  class="form-control" id="description" rows="5"></textarea>
                    </div>
                    <h4>{{ __('messages.plan') }}</h4>
                    <div class="form-group" id="plan_cont" style="display:none;" >
                        @php $brands = \App\Plan::where('status','show')->get(); @endphp
                        <select required class="form-control" name="plan_id" id="cmb_plan_id">
                            <option selected>{{ __('messages.choose_plan') }}</option>
                        </select>
                    </div>
                    <div class="form-group mb-4 mt-3">
                        <label for="exampleFormControlFile1">{{ __('messages.main_image') }}</label>

                        <div class="custom-file-container" data-upload-id="mySecondImage">
                            <label>{{ __('messages.upload') }} ({{ __('messages.single_image') }}) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                            <label class="custom-file-container__custom-file" >
                                <input type="file" required name="main_image" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                                <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                                <span class="custom-file-container__custom-file__custom-file-control"></span>
                            </label>
                            <div class="custom-file-container__image-preview">

                            </div>
                        </div>
                    </div>
                    <h4>{{ __('messages.ad_images') }}</h4>
                    <div class="custom-file-container" data-upload-id="myFirstImage">
                        <label>{{ __('messages.upload') }} ({{ __('messages.multiple_images') }}) <a
                                href="javascript:void(0)" class="custom-file-container__image-clear"
                                title="Clear Image">x</a></label>
                        <label class="custom-file-container__custom-file">
                            <input type="file" required name="images[]" multiple
                                   class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                            <input type="hidden" name="MAX_FILE_SIZE" value="10485760"/>
                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                        </label>
                        <div class="custom-file-container__image-preview"></div>
                    </div>
                    <input type="submit" value="{{ __('messages.add') }}" class="btn btn-primary">
                </form>
            </div>

@endsection
@section('scripts')
   <script src="/admin/assets/js/generate_categories.js"></script>
@endsection
