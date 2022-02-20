@extends('admin.app')
@section('title' , __('messages.balance_packages'))
@section('content')
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">

        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.balance_packages') }}</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <a class="btn btn-primary" href="{{route('balance_packages.create')}}">{{ __('messages.add') }}</a>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                    <thead>
                    <tr>
                        <th class="text-center blue-color">Id</th>
                        <th class="text-center blue-color">{{ __('messages.name') }}</th>
                        <th class="text-center blue-color">{{ __('messages.desc') }}</th>
                        <th class="text-center blue-color">{{ __('messages.amount') }}</th>
                        <th class="text-center blue-color">{{ __('messages.price') }}</th>
                        <th class="text-center blue-color">{{ __('messages.date') }}</th>
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
                    @foreach ($data as $row)
                        <tr >
                            <td class="text-center blue-color"><?=$i;?></td>
                            <td class="text-center blue-color">{{ app()->getLocale() == 'en' ? $row->name_en : $row->name_ar }}</td>
                            <td class="text-center blue-color">{{ app()->getLocale() == 'en' ? $row->desc_en : $row->desc_ar }}</td>
                            <td class="text-center">{{ $row->amount }}</td>
                            <td class="text-center">{{ $row->price }}</td>
                            <td class="text-center">{{ $row->created_at->format('Y-m-d') }}</td>
                            @if(Auth::user()->update_data)
                                <td class="text-center blue-color" ><a href="{{ route('balance_packages.edit', $row->id) }}" ><i class="far fa-edit"></i></a></td>
                            @endif
                            @if(Auth::user()->delete_data)
                                <td class="text-center blue-color" ><a onclick="return confirm('{{ __('messages.are_you_sure') }}');" href="{{ route('balance_packages.delete', $row->id) }}" ><i class="far fa-trash-alt"></i></a></td>
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
