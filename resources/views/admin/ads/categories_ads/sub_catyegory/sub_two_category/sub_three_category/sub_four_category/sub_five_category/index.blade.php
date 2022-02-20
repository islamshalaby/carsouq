@extends('admin.app')

@section('title' , __('messages.sub_category_fourth'))

@section('content')
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-12">
                        <h4>{{ __('messages.sub_category_fifth') }}</h4>
                    </div>
                    <div class="col-md-6 pl-0 col-sm-6 col-12 text-right">
                     <a href="{{route('sub_five_cat_ads.create_all',$cat_id)}}" class="btn btn-success mb-2"  >
                         {{ __('messages.add_ad_to_all') }}
                     </a>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div clSass="table-responsive">
                    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">{{ __('messages.image') }}</th>
                            <th class="text-center">{{ __('messages.name') }}</th>
                            <th class="text-center">{{ __('messages.ads') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; ?>
                        @foreach ($data as $row)
                            <tr >
                                <td class="text-center"><?=$i;?></td>
                                <td class="text-center"><img src="https://res.cloudinary.com/carsads/image/upload/w_100,q_100/v1581928924/{{ $row->image }}"  /></td>
                                <td class="text-center blue-color">{{ app()->getLocale() == 'en' ? $row->title_en : $row->title_ar }}</td>
                                <td class="text-center blue-color">
                                    <a href="{{ route('sub_five_cat_ads.show', $row->id) }}" >
                                        <i class="far fa-eye"></i>
                                    </a>
                                </td>
                                <?php $i++; ?>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection
