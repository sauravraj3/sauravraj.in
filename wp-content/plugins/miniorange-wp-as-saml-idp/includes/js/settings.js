jQuery(document).ready(function() {
    $idp = jQuery;
    $idp("select[name='service_provider']").change(function() {
        $idp("input[name='service_provider']").val($idp(this).val());
        $idp("#change_sp").submit()
    });
    $idp(".mo_idp_help_title").click(function(a) {
        a.preventDefault();
        $idp(this).next(".mo_idp_help_desc").slideToggle(400)
    });
    $idp(".mo_idp_checkbox").click(function() {
        $idp(this).next(".mo_idp_help_desc").slideToggle(400)
    });
    $idp("#lk_check1").change(function() {
        if ($idp("#lk_check2").is(":checked") && $idp("#lk_check1").is(":checked")) {
            $idp("#activate_plugin").removeAttr("disabled")
        }
    });
    $idp("#lk_check2").change(function() {
        if ($idp("#lk_check2").is(":checked") && $idp("#lk_check1").is(":checked")) {
            $idp("#activate_plugin").removeAttr("disabled")
        }
    });
    $idp("div[class^='protocol_choice_'").click(function() {
        if (!$idp(this).hasClass("selected")) {
            $idp(this).parent().parent().next("form").fadeOut();
            $idp('#add_sp input[name="action"]').val($idp(this).data("toggle"));
            $idp(".loader").fadeIn();
            $idp("#add_sp").submit()
        }
    });
    $idp(".copyClip").click(function() {
        $idp(this).next(".copyBody").select();
        document.execCommand("copy")
    });
    $idp('a[aria-label="Deactivate Login using WordPress Users"]').click(function(a) {
        $idp("#mo_idp_feedback_modal").show();
        a.preventDefault()
    });
    $idp("#remove_accnt").click(function(a) {
        $idp("#remove_accnt_form").submit()
    });
    $idp("#goToLoginPage").click(function(a) {
        $idp("#goToLoginPageForm").submit()
    });
    $idp("#mo_idp_users_dd").val('100');
    $idp("#mo_idp_users_dd").change(function(){
        switch($idp("#mo_idp_users_dd").val())
        {
            case '100':
                $idp(".mo_idp_price_slab_100").css("display","table-cell");
                $idp(".mo_idp_price_slab_200").css("display","none");
                $idp(".mo_idp_price_slab_300").css("display","none");
                $idp(".mo_idp_price_slab_400").css("display","none");
                $idp(".mo_idp_price_slab_500").css("display","none");
                $idp(".mo_idp_price_slab_1000").css("display","none");
                $idp(".mo_idp_price_slab_2000").css("display","none");
                $idp(".mo_idp_price_slab_3000").css("display","none");
                $idp(".mo_idp_price_slab_4000").css("display","none");
                $idp(".mo_idp_price_slab_5000").css("display","none");
                $idp(".mo_idp_price_slab_5000p").css("display","none");
                $idp(".mo_idp_price_slab_ul").css("display","none");
                break;
            case '200':
                $idp(".mo_idp_price_slab_100").css("display","none");
                $idp(".mo_idp_price_slab_200").css("display","table-cell");
                $idp(".mo_idp_price_slab_300").css("display","none");
                $idp(".mo_idp_price_slab_400").css("display","none");
                $idp(".mo_idp_price_slab_500").css("display","none");
                $idp(".mo_idp_price_slab_1000").css("display","none");
                $idp(".mo_idp_price_slab_2000").css("display","none");
                $idp(".mo_idp_price_slab_3000").css("display","none");
                $idp(".mo_idp_price_slab_4000").css("display","none");
                $idp(".mo_idp_price_slab_5000").css("display","none");
                $idp(".mo_idp_price_slab_5000p").css("display","none");
                $idp(".mo_idp_price_slab_ul").css("display","none");
                break;    
            case '300':
                $idp(".mo_idp_price_slab_100").css("display","none");
                $idp(".mo_idp_price_slab_200").css("display","none");
                $idp(".mo_idp_price_slab_300").css("display","table-cell");
                $idp(".mo_idp_price_slab_400").css("display","none");
                $idp(".mo_idp_price_slab_500").css("display","none");
                $idp(".mo_idp_price_slab_1000").css("display","none");
                $idp(".mo_idp_price_slab_2000").css("display","none");
                $idp(".mo_idp_price_slab_3000").css("display","none");
                $idp(".mo_idp_price_slab_4000").css("display","none");
                $idp(".mo_idp_price_slab_5000").css("display","none");
                $idp(".mo_idp_price_slab_5000p").css("display","none");
                $idp(".mo_idp_price_slab_ul").css("display","none");
                break;
            case '400':
                $idp(".mo_idp_price_slab_100").css("display","none");
                $idp(".mo_idp_price_slab_200").css("display","none");
                $idp(".mo_idp_price_slab_300").css("display","none");
                $idp(".mo_idp_price_slab_400").css("display","table-cell");
                $idp(".mo_idp_price_slab_500").css("display","none");
                $idp(".mo_idp_price_slab_1000").css("display","none");
                $idp(".mo_idp_price_slab_2000").css("display","none");
                $idp(".mo_idp_price_slab_3000").css("display","none");
                $idp(".mo_idp_price_slab_4000").css("display","none");
                $idp(".mo_idp_price_slab_5000").css("display","none");
                $idp(".mo_idp_price_slab_5000p").css("display","none");
                $idp(".mo_idp_price_slab_ul").css("display","none");
                break;
            case '500':
                $idp(".mo_idp_price_slab_100").css("display","none");
                $idp(".mo_idp_price_slab_200").css("display","none");
                $idp(".mo_idp_price_slab_300").css("display","none");
                $idp(".mo_idp_price_slab_400").css("display","none");
                $idp(".mo_idp_price_slab_500").css("display","table-cell");
                $idp(".mo_idp_price_slab_1000").css("display","none");
                $idp(".mo_idp_price_slab_2000").css("display","none");
                $idp(".mo_idp_price_slab_3000").css("display","none");
                $idp(".mo_idp_price_slab_4000").css("display","none");
                $idp(".mo_idp_price_slab_5000").css("display","none");
                $idp(".mo_idp_price_slab_5000p").css("display","none");
                $idp(".mo_idp_price_slab_ul").css("display","none");
                break;
            case '1000':
                $idp(".mo_idp_price_slab_100").css("display","none");
                $idp(".mo_idp_price_slab_200").css("display","none");
                $idp(".mo_idp_price_slab_300").css("display","none");
                $idp(".mo_idp_price_slab_400").css("display","none");
                $idp(".mo_idp_price_slab_500").css("display","none");
                $idp(".mo_idp_price_slab_1000").css("display","table-cell");
                $idp(".mo_idp_price_slab_2000").css("display","none");
                $idp(".mo_idp_price_slab_3000").css("display","none");
                $idp(".mo_idp_price_slab_4000").css("display","none");
                $idp(".mo_idp_price_slab_5000").css("display","none");
                $idp(".mo_idp_price_slab_5000p").css("display","none");
                $idp(".mo_idp_price_slab_ul").css("display","none");
                break;
            case '2000':
                $idp(".mo_idp_price_slab_100").css("display","none");
                $idp(".mo_idp_price_slab_200").css("display","none");
                $idp(".mo_idp_price_slab_300").css("display","none");
                $idp(".mo_idp_price_slab_400").css("display","none");
                $idp(".mo_idp_price_slab_500").css("display","none");
                $idp(".mo_idp_price_slab_1000").css("display","none");
                $idp(".mo_idp_price_slab_2000").css("display","table-cell");
                $idp(".mo_idp_price_slab_3000").css("display","none");
                $idp(".mo_idp_price_slab_4000").css("display","none");
                $idp(".mo_idp_price_slab_5000").css("display","none");
                $idp(".mo_idp_price_slab_5000p").css("display","none");
                $idp(".mo_idp_price_slab_ul").css("display","none");
                break;
            case '3000':
                $idp(".mo_idp_price_slab_100").css("display","none");
                $idp(".mo_idp_price_slab_200").css("display","none");
                $idp(".mo_idp_price_slab_300").css("display","none");
                $idp(".mo_idp_price_slab_400").css("display","none");
                $idp(".mo_idp_price_slab_500").css("display","none");
                $idp(".mo_idp_price_slab_1000").css("display","none");
                $idp(".mo_idp_price_slab_2000").css("display","none");
                $idp(".mo_idp_price_slab_3000").css("display","table-cell");
                $idp(".mo_idp_price_slab_4000").css("display","none");
                $idp(".mo_idp_price_slab_5000").css("display","none");
                $idp(".mo_idp_price_slab_5000p").css("display","none");
                $idp(".mo_idp_price_slab_ul").css("display","none");
                break;
            case '4000':
                $idp(".mo_idp_price_slab_100").css("display","none");
                $idp(".mo_idp_price_slab_200").css("display","none");
                $idp(".mo_idp_price_slab_300").css("display","none");
                $idp(".mo_idp_price_slab_400").css("display","none");
                $idp(".mo_idp_price_slab_500").css("display","none");
                $idp(".mo_idp_price_slab_1000").css("display","none");
                $idp(".mo_idp_price_slab_2000").css("display","none");
                $idp(".mo_idp_price_slab_3000").css("display","none");
                $idp(".mo_idp_price_slab_4000").css("display","table-cell");
                $idp(".mo_idp_price_slab_5000").css("display","none");
                $idp(".mo_idp_price_slab_5000p").css("display","none");
                $idp(".mo_idp_price_slab_ul").css("display","none");
                break;                    
            case '5000':
                $idp(".mo_idp_price_slab_100").css("display","none");
                $idp(".mo_idp_price_slab_200").css("display","none");
                $idp(".mo_idp_price_slab_300").css("display","none");
                $idp(".mo_idp_price_slab_400").css("display","none");
                $idp(".mo_idp_price_slab_500").css("display","none");
                $idp(".mo_idp_price_slab_1000").css("display","none");
                $idp(".mo_idp_price_slab_2000").css("display","none");
                $idp(".mo_idp_price_slab_3000").css("display","none");
                $idp(".mo_idp_price_slab_4000").css("display","none");
                $idp(".mo_idp_price_slab_5000").css("display","table-cell");
                $idp(".mo_idp_price_slab_5000p").css("display","none");
                $idp(".mo_idp_price_slab_ul").css("display","none");
                break;
            case '5000+':
                $idp(".mo_idp_price_slab_100").css("display","none");
                $idp(".mo_idp_price_slab_200").css("display","none");
                $idp(".mo_idp_price_slab_300").css("display","none");
                $idp(".mo_idp_price_slab_400").css("display","none");
                $idp(".mo_idp_price_slab_500").css("display","none");
                $idp(".mo_idp_price_slab_1000").css("display","none");
                $idp(".mo_idp_price_slab_2000").css("display","none");
                $idp(".mo_idp_price_slab_3000").css("display","none");
                $idp(".mo_idp_price_slab_4000").css("display","none");
                $idp(".mo_idp_price_slab_5000").css("display","none");
                $idp(".mo_idp_price_slab_5000p").css("display","table-cell");
                $idp(".mo_idp_price_slab_ul").css("display","none");
                break;
            case 'UL':
                $idp(".mo_idp_price_slab_100").css("display","none");
                $idp(".mo_idp_price_slab_200").css("display","none");
                $idp(".mo_idp_price_slab_300").css("display","none");
                $idp(".mo_idp_price_slab_400").css("display","none");
                $idp(".mo_idp_price_slab_500").css("display","none");
                $idp(".mo_idp_price_slab_1000").css("display","none");
                $idp(".mo_idp_price_slab_2000").css("display","none");
                $idp(".mo_idp_price_slab_3000").css("display","none");
                $idp(".mo_idp_price_slab_4000").css("display","none");
                $idp(".mo_idp_price_slab_5000").css("display","none");
                $idp(".mo_idp_price_slab_5000p").css("display","none");
                $idp(".mo_idp_price_slab_ul").css("display","table-cell");
                break;
        }
      }); 
});

function showTestWindow(a) {
    var b = window.open(a, "TEST SAML IDP", "scrollbars=1 width=800, height=600")
}

function deleteSpSettings() {
    jQuery("#mo_idp_delete_sp_settings_form").submit()
}

function mo_valid_query(a) {
    !(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(a.value) ? a.value = a.value.replace(/[^a-zA-Z?,.\(\)\/@ 0-9]/, "") : null
}

function mo2f_upgradeform(a) {
    jQuery("#requestOrigin").val(a);
    jQuery("#mocf_loginform").submit()
}

function mo_idp_feedback_goback() {
    $idp("#mo_idp_feedback_modal").hide()
}

function copyToClipboard(copyButton, element, copyelement) {
    var temp = jQuery("<input>");
    jQuery("body").append(temp);
    temp.val(jQuery(element).text()).select();
    document.execCommand("copy");
    temp.remove();
    jQuery(copyelement).text("Copied");

    jQuery(copyButton).mouseout(function(){
        jQuery(copyelement).text("Copy to Clipboard");
    });
}

function gatherplaninfo(name,users){
    document.getElementById("plan-name").value=name;
    document.getElementById("plan-users").value=users;
    document.getElementById("mo_idp_request_quote_form").submit();
}

