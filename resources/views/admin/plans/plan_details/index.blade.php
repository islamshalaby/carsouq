@extends('admin.app')

@section('title' , __('messages.show_plans_details'))

@section('content')
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.show_plans_details') }}</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
{{--                        <a class="btn btn-primary" href="{{route('plans.details.create',$plan_id)}}">{{ __('messages.add_new_feature') }}</a>--}}
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="table-responsive">
                    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">{{ __('messages.feature_name') }}</th>
                            <th class="text-center">{{ __('messages.feature_type') }}</th>
                            <th class="text-center">{{ __('messages.feature_days') }}</th>
                            <th class="text-center">{{ __('messages.view') }}</th>
{{--                            @if(Auth::user()->update_data)--}}
{{--                                <th class="text-center">{{ __('messages.edit') }}</th>--}}
{{--                            @endif--}}
{{--                            @if(Auth::user()->delete_data)--}}
{{--                                <th class="text-center" >{{ __('messages.delete') }}</th>--}}
{{--                            @endif--}}
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; ?>
                        @foreach ($plan_details as $detail)
                            <tr >
                                <td class="text-center"><?=$i;?></td>
                                <td class="text-center">{{ app()->getLocale() == 'en' ? $detail->title_en : $detail->title_ar }}</td>
                                <td class="text-center">
                                    @if($detail->type == 'expier_num')
                                        {{ __('messages.expier_num') }}
                                    @elseif($detail->type == 're_post')
                                        {{ __('messages.re_post') }}
                                    @elseif($detail->type == 'special')
                                        {{ __('messages.special') }}
                                    @elseif($detail->type == 'pin')
                                        {{ __('messages.pin') }}
                                    @endif
                                </td>
                                <td class="text-center">{{ $detail->expire_days }}</td>
                                <td class="text-center">
                                    <div class="switch">
                                        <label>
                                            <input onchange="update_status(this)" value="{{ $detail->id }}" type="checkbox" <?php if($detail->status == 'show') echo "checked";?> >
                                            <span class="lever switch-col-indigo"></span>
                                        </label>
                                    </div>
                                </td >
{{--                                @if(Auth::user()->update_data)--}}
{{--                                    <td class="text-center blue-color" ><a href="{{ route( 'plans.details.edit', $detail->id ) }}" ><i class="far fa-edit"></i></a></td>--}}
{{--                                @endif--}}
{{--                                @if(Auth::user()->delete_data)--}}
{{--                                    @if($detail->type != 'expier_num')--}}
{{--                                        <td class="text-center blue-color" ><a onclick="return confirm('{{ __('messages.are_you_sure') }}');" href="{{ route('plans.details.delete', $detail->id) }}" ><i class="far fa-trash-alt"></i></a></td>--}}
{{--                                    @endif--}}
{{--                                @endif--}}
                                <?php $i++; ?>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        function update_status(el){
            if(el.checked){
                var status = 'show';
            }else{
                var status = 'hide';
            }
            $.post('{{ route('plans.details.showed') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    toastr.success("{{ __('messages.status_changed') }}");
                }else{
                    toastr.error("{{trans('admin.status_not_changed')}}");
                }
            });
        }
    </script>
@endsection


