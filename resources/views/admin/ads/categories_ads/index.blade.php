@extends('admin.app')
@section('title' , __('messages.categories_ads'))
@section('content')
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-12">
                        <h4>{{ __('messages.categories_ads') }}</h4>
                    </div>
                    <div class="col-md-6 pl-0 col-sm-6 col-12 text-right">
                     <a href="{{route('categories_ads.create_all')}}" class="btn btn-success mb-2"  >
                         {{ __('messages.add_ad_to_all') }}
                     </a>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="table-responsive">
                    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>{{ __('messages.image') }}</th>
                                <th>{{ __('messages.category_title') }}</th>
                                <th class="text-center">{{ __('messages.sub_category_first') }}</th>
                                <th class="text-center">{{ __('messages.ads') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($data as $category)
                                <tr>
                                    <td><?=$i;?></td>
                                    <td class="text-center"><img src="https://res.cloudinary.com/carsads/image/upload/w_100,q_100/v1581928924/{{ $category->image }}"  /></td>
                                    <td>{{ app()->getLocale() == 'en' ? $category->title_en : $category->title_ar }}</td>
                                    <td class="text-center blue-color">
                                        <a href="{{route('sub_categories_ads.index',$category->id)}}">
                                            <div class="">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                     stroke-linejoin="round" class="feather feather-layers">
                                                    <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                                    <polyline points="2 17 12 22 22 17"></polyline>
                                                    <polyline points="2 12 12 17 22 12"></polyline>
                                                </svg>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="text-center blue-color"><a href="{{ route('categories_ads.show', $category->id) }}" ><i class="far fa-eye"></i></a></td>
                                    <?php $i++; ?>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection
