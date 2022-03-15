<!DOCTYPE html>
<html lang="en">
<head>
    @if(App::isLocale('ar'))
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <title>@yield('title')</title>
        <link rel="icon" type="image/x-icon" href="/images/favicon.png"/>
        <link href="/admin/rtl/assets/css/loader.css" rel="stylesheet" type="text/css" />
        <script src="/admin/rtl/assets/js/loader.js"></script>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
        <link href="/admin/rtl/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/admin/rtl/assets/css/plugins.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
        <link href="/admin/rtl/plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
        <link href="/admin/rtl/assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css">
        <link href="/admin/rtl/assets/css/scrollspyNav.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="/admin/rtl/assets/css/forms/theme-checkbox-radio.css">
        <link href="/admin/rtl/assets/css/tables/table-basic.css" rel="stylesheet" type="text/css" />
        <link href="/admin/rtl/plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
        <link href="/admin/rtl/assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
        <link href="/admin/rtl/assets/css/elements/custom-pagination.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="/admin/rtl/plugins/font-icons/fontawesome/css/regular.css">
        <link rel="stylesheet" href="/admin/rtl/plugins/font-icons/fontawesome/css/fontawesome.css">
        <link rel="stylesheet" type="text/css" href="/admin/rtl/assets/css/elements/alert.css">
        <link href="/admin/rtl/plugins/file-upload/file-upload-with-preview.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="/admin/rtl/assets/css/forms/theme-checkbox-radio.css">
        <link rel="stylesheet" type="text/css" href="/admin/rtl/plugins/table/datatable/datatables.css">
        <link rel="stylesheet" type="text/css" href="/admin/rtl/plugins/table/datatable/custom_dt_html5.css">
        <link rel="stylesheet" type="text/css" href="/admin/rtl/plugins/table/datatable/dt-global_style.css">
        <link rel="stylesheet" href="/admin/rtl/plugins/editors/markdown/simplemde.min.css">
        <link rel="stylesheet" href="/admin/assets/css/custom.css">
        <link rel="stylesheet" type="text/css" href="https://www.fontstatic.com/f=dubai-medium" />
        <link rel="stylesheet" href="/admin/assets/css/ar.css">

        <!-- BEGIN animate modal PLUGINS -->
        <link href="/admin/rtl/plugins/animate/animate.css" rel="stylesheet" type="text/css" />
        <link href="/admin/rtl/assets/css/components/custom-modal.css" rel="stylesheet" type="text/css" />
        <!-- END animate modal PLUGINS/CUSTOM STYLES -->
        <!--  BEGIN CUSTOM STYLE FILE  -->
        <link href="/admin/rtl/assets/css/scrollspyNav.css" rel="stylesheet" type="text/css" />
        <link href="/admin/rtl/assets/css/components/tabs-accordian/custom-accordions.css" rel="stylesheet" type="text/css" />
        <!--  END CUSTOM STYLE FILE  -->
    @endif
    @if (App::isLocale('en'))
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <title>@yield('title')</title>
        <link rel="icon" type="image/x-icon" href="/images/favicon.png"/>
        <link href="/admin/assets/css/loader.css" rel="stylesheet" type="text/css" />
        <script src="/admin/assets/js/loader.js"></script>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
        <link href="/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/admin/assets/css/plugins.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
        <link href="/admin/plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
        <link href="/admin/assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css">
        <link href="/admin/assets/css/scrollspyNav.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="/admin/assets/css/forms/theme-checkbox-radio.css">
        <link href="/admin/assets/css/tables/table-basic.css" rel="stylesheet" type="text/css" />
        <link href="/admin/plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
        <link href="/admin/assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
        <link href="/admin/assets/css/elements/custom-pagination.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="/admin/plugins/font-icons/fontawesome/css/regular.css">
        <link rel="stylesheet" href="/admin/plugins/font-icons/fontawesome/css/fontawesome.css">
        <link rel="stylesheet" type="text/css" href="/admin/assets/css/elements/alert.css">
        <link href="/admin/plugins/file-upload/file-upload-with-preview.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="/admin/assets/css/forms/theme-checkbox-radio.css">
        <link rel="stylesheet" type="text/css" href="/admin/plugins/table/datatable/datatables.css">
        <link rel="stylesheet" type="text/css" href="/admin/plugins/table/datatable/custom_dt_html5.css">
        <link rel="stylesheet" type="text/css" href="/admin/plugins/table/datatable/dt-global_style.css">
        <link rel="stylesheet" href="/admin/plugins/editors/markdown/simplemde.min.css">
        <link rel="stylesheet" href="/admin/assets/css/custom.css">
        <link rel="stylesheet" href="/admin/assets/css/en.css">
        <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
        <!-- BEGIN animate modal PLUGINS -->
        <link href="/admin/plugins/animate/animate.css" rel="stylesheet" type="text/css" />
        <link href="/admin/assets/css/components/custom-modal.css" rel="stylesheet" type="text/css" />
        <!-- END animate modal PLUGINS/CUSTOM STYLES -->
        <!--  BEGIN setting page  -->
        <link href="/admin/assets/css/scrollspyNav.css" rel="stylesheet" type="text/css" />
        <link href="/admin/assets/css/components/tabs-accordian/custom-accordions.css" rel="stylesheet" type="text/css" />
        <!--  END setting page  -->
    @endif

        <link href="/admin/plugins/toast-master/css/jquery.toast.css" rel="stylesheet">
        <link href="/admin/plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css">
        <link href="/css/new_styles.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="/admin/assets/css/forms/switches.css">



</head>
