@extends('admin.app')
@section('title' , __('messages.sub_category_fourth'))
@section('scripts')
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js" type="text/javascript"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("tbody#sortable").sortable({
            items: "tr",
            placeholder: "ui-state-hightlight",
            update: function () {
                var ids = $('tbody#sortable').sortable("serialize");
                var url = "{{ route('sub_four_cat.sort') }}";
                $.post(url, ids + "&_token={{ csrf_token() }}");
            }
        });
    </script>
@endsection
@section('content')
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.sub_category_fourth') }}</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <a class="btn btn-primary"
                           href="{{route('sub_four_cat.create.new',$cat_id)}}">{{ __('messages.add') }}</a>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="table-responsive">
                    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">{{ __('messages.image') }}</th>
                            <th class="text-center">{{ __('messages.name') }}</th>
                            <th class="text-center">{{ __('messages.sub_category_fiveth') }}</th>
                            <th class="text-center">{{ __('messages.hidden_show') }}</th>
                            @if(Auth::user()->update_data)
                                <th class="text-center">{{ __('messages.edit') }}</th>@endif
                            @if(Auth::user()->delete_data)
                                <th class="text-center">{{ __('messages.delete') }}</th>@endif
                        </tr>
                        </thead>
                        <tbody id="sortable">
                        <?php $i = 1; ?>
                        @foreach ($data as $row)
                            <tr id="id_{{ $row->id }}">
                                <td class="text-center"><?=$i;?></td>
                                <td class="text-center"><img
                                        src="https://res.cloudinary.com/carsads/image/upload/w_100,q_100/v1581928924/{{ $row->image }}"/>
                                </td>
                                <td class="text-center blue-color">{{ app()->getLocale() == 'en' ? $row->title_en : $row->title_ar }}</td>
                                <td class="text-center blue-color">
                                    @if ($prevent_next_level)
                                        {{ __('messages.category_has_products_add') }}
                                    @else
                                    <a href="{{route('sub_five_cat.show',$row->id)}}">
                                        <div class="">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round"
                                                 stroke-linejoin="round" class="feather feather-layers">
                                                <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                                <polyline points="2 17 12 22 22 17"></polyline>
                                                <polyline points="2 12 12 17 22 12"></polyline>
                                            </svg>
                                        </div>
                                    </a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                        <input type="checkbox" onchange="update_active(this)"
                                               value="{{ $row->id }}" @if($row->is_show == 1) checked  @endif >
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                @if(Auth::user()->update_data)
                                    <td class="text-center blue-color"><a
                                            href="{{ route( 'sub_four_cat.edit', $row->id ) }}"><i
                                                class="far fa-edit"></i></a></td>
                                @endif
                                @if(Auth::user()->delete_data)
                                    <td class="text-center blue-color">
                                        @if (count($row->ViewSubCategories) > 0 || count($row->products) > 0)
                                        {{ __('messages.categories_has_plans') }}
                                        @else
                                        <a onclick="return confirm('{{ __('messages.are_you_sure') }}');"
                                           href="{{ route('sub_four_cat.delete', $row->id) }}">
                                            <i class="far fa-trash-alt"></i>
                                        </a>
                                        @endif
                                    </td>
                                @endif
                                <?php $i++; ?>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        function update_status(el) {
            if (el.checked) {
                var status = 'show';
            } else {
                var status = 'hide';
            }
            $.post('{{ route('plans.details.showed') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function (data) {
                if (data == 1) {
                    toastr.success("{{ __('messages.status_changed') }}");
                } else {
                    toastr.error("{{trans('admin.status_not_changed')}}");
                }
            });
        }

        function update_active(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('sub_four_cat.change_is_show') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function (data) {
                if (data == 1) {
                    toastr.success("{{trans('messages.statuschanged')}}");
                } else {
                    toastr.error("{{trans('messages.statuschanged')}}");
                }
            });
        }
    </script>
@endpush


