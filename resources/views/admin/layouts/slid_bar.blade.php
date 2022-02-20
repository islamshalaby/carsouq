<!--  BEGIN MAIN CONTAINER  -->
<div class="main-container" id="container">
    <div class="overlay"></div>
    <div class="search-overlay"></div>
    <!--  BEGIN SIDEBAR  -->
    <div class="sidebar-wrapper sidebar-theme">
        <nav id="sidebar">
            <div class="shadow-bottom"></div>
            <ul class="list-unstyled menu-categories" id="accordionExample">
                @if(in_array(1 , Auth::user()->custom['admin_permission']))
                    <li class="menu users">
                        <a href="#users" data-active="true" data-toggle="collapse" aria-expanded="true"
                           class="dropdown-toggle first-link">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-users">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span>{{ __('messages.users') }}
                                    @if( Auth::user()->custom['user_count'] > 0 )
                                        <span class="unreadcount">
                                        <span class="insidecount">
                                            <?=Auth::user()->custom['user_count']?>
                                        </span>
                                    </span>
                                    @endif
                                </span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled show" id="users" data-parent="#accordionExample">
                            @if(Auth::user()->add_data)
                                <li class="active add">
                                    <a href="/admin-panel/users/add"> {{ __('messages.add') }} </a>
                                </li>
                            @endif
                            <li class="show">
                                <a href="/admin-panel/users/show"> {{ __('messages.show') }} </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if(in_array(13 , Auth::user()->custom['admin_permission']))
                    <li class="menu products">
                        <a href="#products" data-toggle="collapse" aria-expanded="false"
                           class="dropdown-toggle first-link">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-lock">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                <span>{{ __('messages.products') }}</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="products" data-parent="#accordionExample">
                            <li class="show">
                                <a href="/admin-panel/products/show">{{ __('messages.show') }}</a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(in_array(4 , Auth::user()->custom['admin_permission']))
                    <li class="menu categories">
                        <a href="/admin-panel/categories/show" class="dropdown-toggle first-link">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-layers">
                                    <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                    <polyline points="2 17 12 22 22 17"></polyline>
                                    <polyline points="2 12 12 17 22 12"></polyline>
                                </svg>
                                <span>{{ __('messages.categories') }}</span>
                            </div>
                        </a>
                    </li>
                @endif
                @if(in_array(3 , Auth::user()->custom['admin_permission']))
                    <li class="menu ads main_ads categories_ads">
                        <a href="#ads" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle first-link">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-airplay">
                                    <path
                                        d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path>
                                    <polygon points="12 15 17 21 7 21 12 15"></polygon>
                                </svg>
                                <span>{{ __('messages.ads') }}</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="ads" data-parent="#accordionExample">
                            <li class="show">
                                <a href="/admin-panel/ads/show">{{ __('messages.main_ads_second') }}</a>
                            </li>
                            <li class="">
                                <a href="{{route('main_ads.index')}}" @if(Route::current()->getName() == 'main_ads.index') style="color: #1b55e2; font-weight: 600;"  @endif >{{ __('messages.main_ads') }}</a>
                            </li>
                            <li class="">
                                <a href="{{route('categories_ads.index')}}" @if(Route::current()->getName() == 'categories_ads.index') style="color: #1b55e2; font-weight: 600;"  @endif >{{ __('messages.categories_ads') }}</a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(in_array(14 , Auth::user()->custom['admin_permission']))
                    <li class="menu plans">
                        <a href="{{route('plans.index')}}" class="dropdown-toggle first-link">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-map">
                                    <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                                    <line x1="8" y1="2" x2="8" y2="18"></line>
                                    <line x1="16" y1="6" x2="16" y2="22"></line>
                                </svg>
                                <span>{{ __('messages.plans') }}</span>
                            </div>
                        </a>
                    </li>
                @endif
                @if(in_array(16 , Auth::user()->custom['admin_permission']))
                    <li class="menu balance_packages">
                        <a href="{{route('balance_packages.index')}}" class="dropdown-toggle first-link">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zap"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                                <span>{{ __('messages.balance_packages') }}</span>
                            </div>
                        </a>
                    </li>
                @endif
                @if(in_array(17 , Auth::user()->custom['admin_permission']))
                    <li class="menu payments">
                        <a href="{{route('payments.index')}}" class="dropdown-toggle first-link">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                                <span>{{ __('messages.payments') }}</span>
                            </div>
                        </a>
                    </li>
                @endif

                @if(in_array(18 , Auth::user()->custom['admin_permission']))
                    <li class="menu mndob">
                        <a href="#mndobeen" data-active="true" data-toggle="collapse" aria-expanded="true"
                           class="dropdown-toggle first-link">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-user-check">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <polyline points="17 11 19 13 23 9"></polyline>
                                </svg>
                                <span>{{ __('messages.mndobeen') }}</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled show" id="mndobeen" data-parent="#accordionExample">
                            @if(Auth::user()->add_data)
                                <li class="create">
                                    <a href="{{route('mndob.create')}}"> {{ __('messages.add') }} </a>
                                </li>
                            @endif
                            <li class="" >
                                <a href="{{route('mndob.index')}}" @if(Route::current()->getName() == 'mndob.index') style="color: #1b55e2; font-weight: 600;"  @endif> {{ __('messages.show') }} </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(in_array(5 , Auth::user()->custom['admin_permission']))
                    <li class="menu contact_us">
                        <a href="/admin-panel/contact_us" class="dropdown-toggle first-link">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-inbox">
                                    <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                                    <path
                                        d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                                </svg>
                                <span>{{ __('messages.contact_us') }}
                                    @if( Auth::user()->custom['contact_us_count'] > 0 )
                                        <span class="unreadcount">
                                            <span class="insidecount">
                                                <?=Auth::user()->custom['contact_us_count']?>
                                            </span>
                                      </span>
                                    @endif
                                 </span>
                            </div>
                        </a>
                    </li>
                @endif

                @if(in_array(6 , Auth::user()->custom['admin_permission']))
                    <li class="menu notifications">
                        <a href="#notifications" data-active="true" data-toggle="collapse" aria-expanded="true"
                           class="dropdown-toggle first-link">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-bell">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                </svg>
                                <span>{{ __('messages.notifications') }}</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled show" id="notifications"
                            data-parent="#accordionExample">
                            @if(Auth::user()->add_data)
                                <li class="send">
                                    <a href="/admin-panel/notifications/send"> {{ __('messages.send') }} </a>
                                </li>
                            @endif
                            <li class="show">
                                <a href="/admin-panel/notifications/show"> {{ __('messages.show') }} </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(in_array(2 , Auth::user()->custom['admin_permission']))
                    <li class="menu app_pages">
                        <a href="#app_pages" data-toggle="collapse" aria-expanded="false"
                           class="dropdown-toggle first-link">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-cpu">
                                    <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
                                    <rect x="9" y="9" width="6" height="6"></rect>
                                    <line x1="9" y1="1" x2="9" y2="4"></line>
                                    <line x1="15" y1="1" x2="15" y2="4"></line>
                                    <line x1="9" y1="20" x2="9" y2="23"></line>
                                    <line x1="15" y1="20" x2="15" y2="23"></line>
                                    <line x1="20" y1="9" x2="23" y2="9"></line>
                                    <line x1="20" y1="14" x2="23" y2="14"></line>
                                    <line x1="1" y1="9" x2="4" y2="9"></line>
                                    <line x1="1" y1="14" x2="4" y2="14"></line>
                                </svg>
                                <span>{{ __('messages.app_pages') }}</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="app_pages" data-parent="#accordionExample">
                            <li class="aboutapp">
                                <a href="/admin-panel/app_pages/aboutapp">{{ __('messages.about_app') }}</a>
                            </li>
                            <li class="termsandconditions">
                                <a href="/admin-panel/app_pages/termsandconditions">{{ __('messages.terms_conditions') }}</a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(in_array(7 , Auth::user()->custom['admin_permission']))
                    <li class="menu settings">
                        <a href="/admin-panel/settings" class="dropdown-toggle first-link">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-settings">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path
                                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                </svg>
                                <span>{{ __('messages.settings') }}</span>
                            </div>
                        </a>
                    </li>
                @endif

{{--                @if(in_array(8 , Auth::user()->custom['admin_permission']))--}}
{{--                    <li class="menu meta_tags">--}}
{{--                        <a href="/admin-panel/meta_tags" class="dropdown-toggle first-link">--}}
{{--                            <div class="">--}}
{{--                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"--}}
{{--                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"--}}
{{--                                     stroke-linejoin="round" class="feather feather-search">--}}
{{--                                    <circle cx="11" cy="11" r="8"></circle>--}}
{{--                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>--}}
{{--                                </svg>--}}
{{--                                <span>{{ __('messages.meta_tags') }}</span>--}}
{{--                            </div>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                @endif--}}

                @if(in_array(9 , Auth::user()->custom['admin_permission']))
                    <li class="menu managers">
                        <a href="#managers" data-active="true" data-toggle="collapse" aria-expanded="true"
                           class="dropdown-toggle first-link">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-user-check">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <polyline points="17 11 19 13 23 9"></polyline>
                                </svg>
                                <span>{{ __('messages.managers') }}</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled show" id="managers" data-parent="#accordionExample">
                            @if(Auth::user()->add_data)
                                <li class="active add">
                                    <a href="/admin-panel/managers/add"> {{ __('messages.add') }} </a>
                                </li>
                            @endif
                            <li class="show">
                                <a href="/admin-panel/managers/show"> {{ __('messages.show') }} </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if(in_array(10 , Auth::user()->custom['admin_permission']))
                    <li class="menu databasebackup">
                        <a href="/admin-panel/databasebackup" class="dropdown-toggle first-link">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-database">
                                    <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                    <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                                </svg>
                                <span>{{ __('messages.databasebackup') }}</span>
                            </div>
                        </a>
                    </li>
                @endif
            </ul>
            <!-- <div class="shadow-bottom"></div> -->

        </nav>

    </div>
    <!--  END SIDEBAR  -->

    <!--  BEGIN CONTENT AREA  -->
    <div id="content" class="main-content">
        <div class="layout-px-spacing">
            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
