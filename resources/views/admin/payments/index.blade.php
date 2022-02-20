@extends('admin.app')
@section('title' , __('messages.payments'))
@push('scripts')
    <script>
        var language = "{{ Config::get('app.locale') }}"
        $("#area_select").on("change", function() {
            $("#areaForm").submit()
        })
        $("#toDate").on("change", function() {
            console.log("test")
            $("#dateForm").submit()
        })
        $("#payment_select").on("change", function() {
            $("#paymentForm").submit()
        })
    </script>



    <script>
        var
            sumTotal = "{{ $data->sum('price') }}",
            totalString = "{{ __('messages.total_with_delivery') }}",
            dinar = "{{ __('messages.dinar') }}"
        var dTbls = $('#order-tbl').DataTable( {
            dom: 'Blfrtip',
            buttons: {
                buttons: [
                    { extend: 'excel', className: 'btn', footer: true, exportOptions: {
                            columns: ':visible',
                            rows: ':visible'
                        } },
                    { extend: 'print', className: 'btn', footer: true,
                        exportOptions: {
                            columns: ':visible',
                            rows: ':visible'
                        },customize: function(win) {
                            $(win.document.body).prepend(`<br /><h4 style="border-bottom: 1px solid; padding : 10px">${priceString} : ${sumPrice} ${dinar} | ${deliveryString} : ${sumDelivery} ${dinar} | ${totalString} : ${sumTotal} ${dinar}</h4>`); //before the table
                        }
                    }
                ]
            },
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
                "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [50, 100, 1000, 10000, 100000, 1000000, 2000000, 3000000, 4000000, 5000000],
            "pageLength": 50
        } );
    </script>
    <script>
        var price = dTbls.column(6).data(),
            delivery = dTbls.column(7).data(),
            total = dTbls.column(8).data(),
            dinar = "{{ __('messages.dinar') }}"
        var totalPrice = parseFloat(price.reduce(function (a, b) { return parseFloat(a) + parseFloat(b); }, 0)).toFixed(2),
            totalDelivery = parseFloat(delivery.reduce(function (a, b) { return parseFloat(a) + parseFloat(b); }, 0)).toFixed(2),
            allTotal = parseFloat(total.reduce(function (a, b) { return parseFloat(a) + parseFloat(b); }, 0)).toFixed(2)

        $("#order-tbl tfoot").find('th').eq(6).text(`${totalPrice} ${dinar}`);
        $("#order-tbl tfoot").find('th').eq(7).text(`${totalDelivery} ${dinar}`);
        $("#order-tbl tfoot").find('th').eq(8).text(`${allTotal} ${dinar}`);
    </script>

    <script>
        $("#payment_select").on("change", function() {
            $("#paymentForm").submit()
        })
    </script>

@endpush
@section('content')
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.payments') }}</h4>
                </div>
            </div>
                <div class="row">{{ __('messages.total_payments') }} &nbsp; <code>{{$data->sum('price')}}</code> </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="form-group col-md-3">
                <form id="paymentForm" method="post" action="{{ route('payment.fetch_by_package') }}">
                    @csrf
                    <label for="payment_select">{{ __('messages.search_with_package') }}</label>
                    @php $balance_backagess =  \App\Balance_package::where('status','show')->get(); @endphp
                    <select required id="payment_select" name="backage_id" class="form-control">
                        <option disabled selected>{{ __('messages.select') }}</option>
                            @foreach($balance_backagess as $row)
                                <option value="{{$row->id}}">
                                    @if(app()->getLocale() == 'ar' )
                                        {{$row->name_ar}}
                                    @else
                                        {{$row->name_en}}
                                    @endif
                                </option>
                            @endforeach
                    </select>

                </form>
            </div>
            <div class="table-responsive">
                <table id="order-tbl" class="table table-hover non-hover" style="width:100%">
                    <thead>
                    <tr>
                        <th class="text-center blue-color">Id</th>
                        <th class="text-center blue-color">{{ __('messages.user_name') }}</th>
                        <th class="text-center blue-color">{{ __('messages.amount') }}</th>
                        <th class="text-center blue-color">{{ __('messages.price') }}</th>
                        <th class="text-center blue-color">{{ __('messages.plan_name') }}</th>
                        <th class="text-center blue-color">{{ __('messages.date') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1; ?>
                    @foreach ($data as $row)
                        <tr >
                            <td class="text-center blue-color"><?=$i;?></td>
                            <td class="text-center blue-color">{{ $row->User ? $row->User->name : '' }}</td>
                            <td class="text-center blue-color">{{ $row->value }}</td>
                            <td class="text-center blue-color">{{ $row->price }}</td>
                            <td class="text-center blue-color">@if(app()->getLocale() == 'ar' ) {{ $row->Package ? $row->Package->name_ar : "" }} @else {{ $row->Package ? $row->Package->name_en : "" }} @endif</td>
                            <td class="text-center">{{ $row->created_at->format('Y-m-d') }}</td>
                            <?php $i++; ?>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
