@extends('admin.app')

@section('title' , __('messages.plans'))

@section('content')
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.plans') }}</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <a class="btn btn-primary" href="{{route('plans.create')}}">{{ __('messages.add_plan') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center blue-color">Id</th>
                            <th class="text-center blue-color">{{ __('messages.plane_name') }}</th>
                            <th class="text-center blue-color">{{ __('messages.category') }}</th>
                            <th class="text-center blue-color">{{ __('messages.plan_price') }}</th>
                            <th class="text-center blue-color">{{ __('messages.view') }}</th>
                            <th class="text-center blue-color">{{ __('messages.features') }}</th>
                        @if(Auth::user()->update_data)
                                <th class="text-center">{{ __('messages.edit') }}</th>
                            @endif
                            @if(Auth::user()->delete_data)
                                <th class="text-center" >{{ __('messages.delete') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($data['plans'] as $plan)
                            <tr >
                                <td class="text-center blue-color"><?=$i;?></td>
                                <td class="text-center blue-color">{{ app()->getLocale() == 'en' ? $plan->title_en : $plan->title_ar }}</td>
                                <td class="text-center blue-color">
                                    @if($plan->cat_id == 'all')
                                        {{ __('messages.all') }}
                                    @else
                                        {{ app()->getLocale() == 'en' ? $plan->Cat->title_en : $plan->Cat->title_ar }}
                                    @endif
                                </td>
                                <td class="text-center blue-color">{{ $plan->price }} {{ __('messages.dinar') }}</td>
                                <td class="text-center">
                                    <div class="switch">
                                        <label>
                                            <input onchange="update_status(this)" value="{{ $plan->id }}" type="checkbox" <?php if($plan->status == 'show') echo "checked";?> >
                                            <span class="lever switch-col-indigo"></span>
                                        </label>
                                    </div>
                                </td >
                                <td class="text-center blue-color"><a href="{{ route('plans.details', $plan->id) }}" ><i class="far fa-eye"></i></a></td>
                                @if(Auth::user()->update_data)
                                    <td class="text-center blue-color" ><a href="{{ route('plans.edit', $plan->id) }}" ><i class="far fa-edit"></i></a></td>
                                @endif
                                @if(Auth::user()->delete_data)
                                    <td class="text-center blue-color" ><a onclick="return confirm('{{ __('messages.are_you_sure') }}');" href="{{ route('delete.plan', $plan->id) }}" ><i class="far fa-trash-alt"></i></a></td>
                                @endif
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
            $.post('{{ route('plans.showed') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    toastr.success("{{ __('messages.status_changed') }}");
                }else{
                    toastr.error("{{trans('admin.status_not_changed')}}");
                }
            });
        }
    </script>
@endsection

