jQuery(document).ready(function() {
    jQuery('.use_template_form').hide();

    jQuery('input[type=radio][name=template_radio]').change(function (e) {
        e.preventDefault();
        var selected_option = jQuery('input[type=radio][name=template_radio]:checked').val();


        if (selected_option == "user_template") {
            jQuery('.manual_form').hide();
            jQuery('.use_template_form').show();
        } else {
            jQuery('.manual_form').show();
            jQuery('.use_template_form').hide();
        }

    });

});