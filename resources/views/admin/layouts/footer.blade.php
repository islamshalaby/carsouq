</div>
</div>
</div>
<div class="footer-wrapper">
    <div class="footer-section f-section-1">
        <p class="">{{ __('messages.copyright') }} © 2020
            <a target="_blank" class="website-link" href="https://u-smart.co/">{{ __('messages.usmart') }}</a>
            , {{ __('messages.all_rights_reserved') }}
        </p>
    </div>
</div>
</div>


<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="/admin/assets/js/libs/jquery-3.1.1.min.js"></script>
<script src="/admin/bootstrap/js/popper.min.js"></script>
<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
<script src="/admin/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="/admin/assets/js/app.js"></script>
<script>
    $(document).ready(function () {
        App.init();
    });
</script>
<script src="/admin/assets/js/custom.js"></script>
<!-- END GLOBAL MANDATORY SCRIPTS -->
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
<script src="/admin/plugins/editors/markdown/simplemde.min.js"></script>
<script src="/admin/plugins/editors/markdown/custom-markdown.js"></script>
<script src="/admin/plugins/apex/apexcharts.min.js"></script>
<script src="/admin/assets/js/dashboard/dash_1.js"></script>
<script src="/admin/assets/js/dashboard/dash_2.js"></script>
<script src="/admin/plugins/file-upload/file-upload-with-preview.min.js"></script>
<script src="/admin/plugins/table/datatable/datatables.js"></script>
<!-- NOTE TO Use Copy CSV Excel PDF Print Options You Must Include These Files  -->
<script src="/admin/plugins/table/datatable/button-ext/dataTables.buttons.min.js"></script>
<script src="/admin/plugins/table/datatable/button-ext/jszip.min.js"></script>
<script src="/admin/plugins/table/datatable/button-ext/buttons.html5.min.js"></script>
<script src="/admin/plugins/table/datatable/button-ext/buttons.print.min.js"></script>
<script src="https://cdn.ckeditor.com/4.14.0/full/ckeditor.js"></script>
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

{{--to generat toaster--}}
<script src="/admin/plugins/toast-master/js/jquery.toast.js"></script>
<script src="/admin/plugins/toastr/toastr.js"></script>
<script src="/admin/assets/js/scrollspyNav.js"></script>
<script>
    CKEDITOR.replace('editor-ck-en');
    CKEDITOR.replace('editor-ck-ar');
</script>
<script>
    $('#html5-extension').DataTable({
        buttons: {
            buttons: [
                {extend: 'csv', className: 'btn'},
                {extend: 'excel', className: 'btn'},
                {extend: 'print', className: 'btn'}
            ]
        },
        "oLanguage": {
            "oPaginate": {
                "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
            },
            "sInfo": "Showing page _PAGE_ of _PAGES_",
            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            "sSearchPlaceholder": "Search...",
            "sLengthMenu": "Results :  _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [20, 30, 40, 50],
        "pageLength": 20
    });
</script>
<script>
    $(function () {
        $("#map_url").on("change paste keyup", function () {
            var url = $(this).val();
            var regex = new RegExp('@(.*),(.*),');
            var lon_lat_match = url.match(regex);
            var lon = lon_lat_match[2];
            var lat = lon_lat_match[1];
            $('input[name=longitude]').val(lon);
            $('input[name=latitude]').val(lat);
        });
    });
</script>
<script>
    var firstUpload = new FileUploadWithPreview('myFirstImage')
    var secondUpload = new FileUploadWithPreview('mySecondImage')
</script>
<script>
    $(".menu a").removeAttr("data-active");
    $(".menu a").attr("aria-expanded", "false");
    $(".menu ul").removeClass("show");
    $(".menu ul li").removeClass("active");
    var pathname = window.location.pathname;
    var pathnameArray = pathname.split("/");
    var currentSection = pathnameArray[2];
    $("." + currentSection + " .first-link").attr("data-active", "true");
    $("." + currentSection + " .first-link").attr("aria-expanded", "true");
    $("." + currentSection + " ul").addClass("show");
    $("." + currentSection + " ." + pathnameArray[3]).addClass("active")
</script>
{{--------------------------- accordation in settings page -------------------------}}
<script src="/admin/assets/js/scrollspyNav.js"></script>
<script src="/admin/assets/js/components/ui-accordions.js"></script>
{{----------------------------------------end -------------------------------------------}}
@stack('scripts')
@yield('scripts')

</body>
</html>
