<body>
<!-- START LOADER  -->
<div id="load_screen">
    <div class="loader">
        <div class="loader-content">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 792 723"
                 style="enable-background:new 0 0 792 723;" xml:space="preserve">
                <g>
                    <g>
                        <path class="st0"
                              d="M213.9,584.4c-47.4-25.5-84.7-60.8-111.8-106.1C75,433.1,61.4,382,61.4,324.9c0-57,13.6-108.1,40.7-153.3 S166.5,91,213.9,65.5s100.7-38.2,159.9-38.2c49.9,0,95,8.8,135.3,26.3s74.1,42.8,101.5,75.7l-85.5,78.9 c-38.9-44.9-87.2-67.4-144.7-67.4c-35.6,0-67.4,7.8-95.4,23.4s-49.7,37.4-65.4,65.4c-15.6,28-23.4,59.8-23.4,95.4 s7.8,67.4,23.4,95.4s37.4,49.7,65.4,65.4c28,15.6,59.7,23.4,95.4,23.4c57.6,0,105.8-22.7,144.7-68.2l85.5,78.9 c-27.4,33.4-61.4,58.9-102,76.5c-40.6,17.5-85.8,26.3-135.7,26.3C314.3,622.7,261.3,809.9,213.9,584.4z"/>
                    </g>
                    <circle class="st1" cx="375.4" cy="322.9" r="100"/>
                </g>
                <g>
                    <circle class="st2" cx="275.4" cy="910" r="65"></circle>
                    <circle class="st4" cx="475.4" cy="910" r="65"></circle>
                </g> </svg>
        </div>
    </div>
</div>
<!--  END LOADER -->

<!--  BEGIN NAVBAR  -->
<div class="header-container fixed-top">
    <header class="header navbar navbar-expand-sm">
        <ul class="navbar-item theme-brand flex-row  text-center">
            <li class="nav-item theme-logo">
                <a href="/admin-panel">
                    <img
                        src="https://res.cloudinary.com/carsads/image/upload/w_100,q_100/v1581928924/<?=Auth::user()->custom['setting']['logo']?>"
                        class="navbar-logo" alt="logo">
                </a>
            </li>
            <li class="nav-item theme-text">
                @if(App::isLocale('en'))
                    <a href="/admin-panel" class="nav-link"> <?=Auth::user()->custom['setting']['app_name_en']?></a>
                @else
                    <a href="/admin-panel" class="nav-link"> <?=Auth::user()->custom['setting']['app_name_ar']?></a>
                @endif
            </li>
        </ul>
        <ul class="navbar-item flex-row ml-md-auto">
            <li class="nav-item dropdown language-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="language-dropdown"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(App::isLocale('en'))
                        <img src="/admin/assets/img/ca.png" class="flag-width" alt="flag">
                    @else
                        <img src="/admin/assets/img/ar.png" class="flag-width" alt="flag">
                    @endif
                </a>
                <div class="dropdown-menu position-absolute" aria-labelledby="language-dropdown">
                    <a class="dropdown-item d-flex" href="/setlocale/en"><img src="/admin/assets/img/ca.png" class="flag-width" alt="flag">
                        <span class="align-self-center">&nbsp;{{ __('messages.english') }}</span></a>
                    <a class="dropdown-item d-flex" href="/setlocale/ar"><img src="/admin/assets/img/ar.png" class="flag-width" alt="flag">
                        <span class="align-self-center">&nbsp;{{ __('messages.arabic') }}</span></a>
                </div>
            </li>

            <li class="nav-item dropdown user-profile-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <img src="/admin/assets/img/man.png" alt="avatar">
                </a>
                <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                    <div class="">
                        <div class="dropdown-item">
                            <a class="" href="/admin-panel/profile">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-user">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg> {{ __('messages.myprofile') }}
                            </a>
                        </div>
                        <div class="dropdown-item">
                            <a class="" href="/admin-panel/logout">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-log-out">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg> {{ __('messages.signout') }}
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </header>
</div>
<!--  END NAVBAR  -->
<!--  BEGIN NAVBAR  -->
<div class="sub-header-container">
    <header class="header navbar navbar-expand-sm">
        <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="feather feather-menu">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </a>
        <ul class="navbar-nav flex-row">
            <li>
                <div class="page-header">
                    <nav class="breadcrumb-one" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin-panel">{{ __('messages.dashboard') }}</a></li>
                            <!-- <li class="breadcrumb-item active" aria-current="page"><span>Sales</span></li> -->
                        </ol>
                    </nav>
                </div>
            </li>
        </ul>
    </header>
</div>
<!--  END NAVBAR  -->
