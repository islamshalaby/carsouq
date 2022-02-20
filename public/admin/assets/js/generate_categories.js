$(document).ready(function () {
    $('#cmb_cat').change(function(){
        var cat_id = $(this).val();
        $.ajax({
            url: "/admin-panel/get_sub_cat/" + cat_id ,
            dataType: 'html',
            type: 'get',
            success: function (data) {
                $('#sub_cat_cont').show();
                $('#cmb_sub_cat').html(data);
            }
        });
        $.ajax({
            url: "/admin-panel/get_brands/" + cat_id ,
            dataType: 'html',
            type: 'get',
            success: function (data) {
                $('#brand_cont').show();
                $('#cmb_brand_id').html(data);
            }
        });
        $.ajax({
            url: "/admin-panel/get_brand_types/" + cat_id ,
            dataType: 'html',
            type: 'get',
            success: function (data) {
                $('#brand_types_cont').show();
                $('#cmb_brand_types_id').html(data);
            }
        });
        $.ajax({
            url: "/admin-panel/get_model_year/" + cat_id ,
            dataType: 'html',
            type: 'get',
            success: function (data) {
                $('#model_year_cont').show();
                $('#cmb_model_year_id').html(data);
            }
        });
        $.ajax({
            url: "/admin-panel/get_counter/" + cat_id ,
            dataType: 'html',
            type: 'get',
            success: function (data) {
                $('#counter_cont').show();
                $('#cmb_counter_id').html(data);
            }
        });
        $.ajax({
            url: "/admin-panel/get_plan/" + cat_id ,
            dataType: 'html',
            type: 'get',
            success: function (data) {
                $('#plan_cont').show();
                $('#cmb_plan_id').html(data);
            }
        });
    });
    $('#cmb_cat').change(function(){
        var cat_id = $(this).val();
        $.ajax({
            url: "/admin-panel/get_brands/" + cat_id ,
            dataType: 'html',
            type: 'get',
            success: function (data) {
                $('#brands_cont').show();
                $('#cmb_brand_id').html(data);
            }
        });
    });
    $('#cmb_sub_cat').change(function(){
        var one_id = $(this).val();
        $.ajax({
            url: "/admin-panel/get_sub_two_cat/" + one_id ,
            dataType: 'html',
            type: 'get',
            success: function (data) {
                $('#sub_two_cat_cont').show(data);
                $('#cmb_sub_two_cat').html(data);
            }
        });
    });
    $('#cmb_sub_two_cat').change(function(){
        var two_id = $(this).val();
        $.ajax({
            url: "/admin-panel/get_sub_three_cat/" + two_id ,
            dataType: 'html',
            type: 'get',
            success: function (data) {
                $('#sub_three_cat_cont').show(data);
                $('#cmb_sub_three_cat').html(data);
            }
        });
    });
    $('#cmb_sub_three_cat').change(function(){
        var three_id = $(this).val();
        $.ajax({
            url: "/admin-panel/get_sub_four_cat/" + three_id ,
            dataType: 'html',
            type: 'get',
            success: function (data) {
                $('#sub_four_cat_cont').show(data);
                $('#cmb_sub_four_cat').html(data);
            }
        });
    });
    $('#cmb_sub_four_cat').change(function(){
        var three_id = $(this).val();
        $.ajax({
            url: "/admin-panel/get_sub_five_cat/" + three_id ,
            dataType: 'html',
            type: 'get',
            success: function (data) {
                $('#sub_five_cat_cont').show(data);
                $('#cmb_sub_five_cat').html(data);
            }
        });
    });

});
