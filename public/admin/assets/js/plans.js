$(document).ready(function () {
    $('input[id="re_post_id"]').click(function(){
        if($(this).prop("checked") == true){
            $('#re_post1_cont').show();
            $('#re_post2_cont').show();
            $('#re_post3_cont').show();
        }else if($(this).prop("checked") == false){
            $('#re_post1_cont').hide();
            $('#re_post2_cont').hide();
            $('#re_post3_cont').hide();
        }
    });

    $('input[id="pin_id"]').click(function(){
        if($(this).prop("checked") == true){
            $('#pin_it1_cont').show();
            $('#pin_it2_cont').show();
            $('#pin_it3_cont').show();
        }else if($(this).prop("checked") == false){
            $('#pin_it1_cont').hide();
            $('#pin_it2_cont').hide();
            $('#pin_it3_cont').hide();
        }
    });

    $('input[id="special_id"]').click(function(){
        if($(this).prop("checked") == true){
            $('#special1_cont').show();
            $('#special2_cont').show();
            $('#special3_cont').show();
        }else if($(this).prop("checked") == false){
            $('#special1_cont').hide();
            $('#special2_cont').hide();
            $('#special3_cont').hide();
        }
    });

});
