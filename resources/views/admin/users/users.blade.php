@extends('admin.app')

@section('title' , __('messages.show_users'))

@section('content')
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-12">
                        <h4>{{ __('messages.show_users') }}</h4>
                    </div>
                    <div class="col-md-6 pl-0 col-sm-6 col-12 text-right">
                     <a class="btn btn-success mb-2" id="btn_send" data-toggle="modal" data-target="#zoomup_group_Modal" >
                         {{ __('messages.send_free_balance_for_all_users') }}
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
                            <th>{{ __('messages.user_name') }}</th>
                            <th>{{ __('messages.user_phone') }}</th>
                            <th class="text-center">{{ __('messages.block_active') }}</th>
                            <th class="text-center">{{ __('messages.send_balance') }}</th>
                            <th class="text-center">{{ __('messages.details') }}</th>
                            <th class="text-center">{{ __('messages.current_ads') }}</th>
                            <th class="text-center">{{ __('messages.ended_ads') }}</th>
                            @if(Auth::user()->update_data)
                                <th class="text-center">{{ __('messages.edit') }}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; ?>
                        @foreach ($data['users'] as $user)
                            <tr class="{{$user->seen == 0 ? 'unread' : '' }}">
                                <td><?=$i;?></td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->phone }}</td>
                                <td class="text-center">
                                    @if($user->active)
                                        <a href="/admin-panel/users/block/{{$user->id}}">
                                            <span class="badge badge-danger">{{ __('messages.block') }}</span>
                                        </a>
                                    @else
                                        <a href="/admin-panel/users/active/{{$user->id}}">
                                            <span class="badge badge-success">{{ __('messages.active') }}</span>
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-warning mb-2 mr-2 btn-rounded" data-user="{{$user->id}}"
                                       id="btn_send" data-toggle="modal"
                                       data-target="#zoomupModal">{{ __('messages.send_balance') }}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-briefcase">
                                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                        </svg>
                                    </a>
                                </td>
                                <td class="text-center blue-color"><a href="/admin-panel/users/details/{{ $user->id }}"><i
                                            class="far fa-eye"></i></a></td>
                                <td class="text-center blue-color">
                                    @if(count($user->products) > 0 )
                                        <a href="{{ route('user.products', $user->id) }}">
                                            <i class="far fa-eye"></i>
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center blue-color">
                                    @if(count($user->Ended_products ) > 0)
                                        <a href="{{ route('user.ended_products', $user->id) }}">
                                            <i class="far fa-eye"></i>
                                        </a>
                                    @endif
                                </td>
                                @if(Auth::user()->update_data)
                                    <td class="text-center blue-color"><a
                                            href="/admin-panel/users/edit/{{ $user->id }}"><i
                                                class="far fa-edit"></i></a></td>
                                @endif
                                <?php $i++; ?>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{--model--}}
        {{--send free balance for single user--}}
        <div id="zoomupModal" class="modal animated zoomInUp custo-zoomInUp" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('messages.send_free_balance') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <form action="{{route('users.send_balance')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input required type="hidden" min="0" name="user_id" id="txt_user_id">
                            <div class="form-group mb-4">
                                <label for="plan_price">{{ __('messages.balance_value') }}</label>
                                <input required type="number" min="0" name="ammount" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal"><i
                                    class="flaticon-cancel-12"></i> {{ __('messages.cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('messages.send') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- send free balance for group of users--}}
        <div id="zoomup_group_Modal" class="modal animated zoomInUp custo-zoomInUp" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('messages.send_free_balance') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <form action="{{route('users_group.send_group_balance')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group mb-4">
                                <label for="plan_price">{{ __('messages.balance_value') }}</label>
                                <input required type="number" min="0" name="ammount" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal">
                                <i class="flaticon-cancel-12"></i> {{ __('messages.cancel') }}
                            </button>
                            <button type="submit" class="btn btn-primary">{{ __('messages.send') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endsection
        @section('scripts')
            <script>
                $(document).ready(function () {
                    $(document).on('click', '#btn_send', function () {
                        user_id = $(this).data('user');
                        $("#txt_user_id").val(user_id);
                    });
                });
            </script>
@endsection

