jQuery(document).ready(function ($) {
    // Append feedback form HTML to the body
    $('body').append(`
    <div id="bms-feedback-popup" style="display:none;">
    <div id="bms-feedback-overlay"></div>
    <div id="bms-feedback-content">
    <button type="button" class="button-secondary" id="bms-close-popup">×</button>
        <h2>We Value Your Feedback</h2>
        <form id="bms-feedback-form">
            <div bis_skin_checked="1">
                <h4>Please share why you are deactivating Bulk Mail Sender:</h4>
                <div class="form-group mb-2" bis_skin_checked="1">
                    <input id="bulk-mail-sender-no_longer_needed" type="radio" name="reason_key" value="no_longer_needed">
                    <label for="bulk-mail-sender-no_longer_needed" class="form-label">I no longer need the plugin</label>
                </div>
                <div class="form-group mb-2" bis_skin_checked="1">
                    <input id="bulk-mail-sender-found_a_better_plugin" type="radio" name="reason_key" value="found_a_better_plugin">
                    <label for="bulk-mail-sender-found_a_better_plugin" class="form-label">I found a better plugin</label>
                    <input type="text" class="reason-text" style="width: 100%" name="reason_found_a_better_plugin" placeholder="Please share which plugin" readonly>
                </div>
                <div class="form-group mb-2" bis_skin_checked="1">
                    <input id="bulk-mail-sender-couldnt_get_the_plugin_to_work" type="radio" name="reason_key" value="couldnt_get_the_plugin_to_work">
                    <label for="bulk-mail-sender-couldnt_get_the_plugin_to_work" class="form-label">I couldn't get the plugin to work</label>
                </div>
                <div class="form-group mb-2" bis_skin_checked="1">
                    <input id="bulk-mail-sender-temporary_deactivation" type="radio" name="reason_key" value="temporary_deactivation">
                    <label for="bulk-mail-sender-temporary_deactivation" class="form-label">It's a temporary deactivation</label>
                </div>
                <div class="form-group mb-2" bis_skin_checked="1">
                    <input id="bulk-mail-sender-pro_plugin" type="radio" name="reason_key" value="pro_plugin">
                    <label for="bulk-mail-sender-pro_plugin" class="form-label">I have bulk main sender Pro</label>
                    <div bis_skin_checked="1">Note: bulk main sender is a Mandatory plugin for PRO version to work</div>
                </div>
                <div class="form-group mb-2" bis_skin_checked="1">
                    <input id="bulk-mail-sender-customization_issue" type="radio" name="reason_key" value="customization_issue">
                    <label for="bulk-mail-sender-customization_issue" class="form-label">Not able To customize</label>
                    <input type="text" class="reason-text" style="width: 100%" name="reason_customization_issue" placeholder="Please share where you need customization" readonly>
                </div>
                <div class="form-group mb-2" bis_skin_checked="1">
                    <input id="bulk-mail-sender-other" type="radio" name="reason_key" value="other">
                    <label for="bulk-mail-sender-other" class="form-label">Other</label>
                    <input type="text" class="reason-text" style="width: 100%" name="reason_other" placeholder="Please share the reason" readonly>
                </div>
            </div>
            
            <p id="bms-error-message" style="color:red; display:none;">*Please select a reason for deactivation.</p>
            <div class="bms-buttons">
                <button type="submit" class="button-primary" id="deactivtion_button"><span id="deactivtion_button_text">Submit & Deactivate</span><span id="Submitting" style="display:none;">Submitting..</span></button>
                
            </div>
        </form>
    </div>
</div>
    `);

    // Enable corresponding text input when radio button is selected
    $('input[type=radio][name=reason_key]').change(function () {
        $('input.reason-text').prop('readonly', true).val(''); // Disable all text inputs and clear them
        $(this).closest('.form-group').find('input.reason-text').prop('readonly', false); // Enable the related text input
    });

    // Show feedback form popup on plugin deactivate
    $(document).on('click', '#deactivate-bulk-mail-sender', function (e) {
        e.preventDefault();
        $('#bms-feedback-popup').fadeIn();
    });

    // Handle feedback form submission
    $('#bms-feedback-form').on('submit', function (e) {
        e.preventDefault();

        var reason_key = $('input[name=reason_key]:checked').val();
        if (!reason_key) {
            $('#bms-error-message').show();
            return;
        } else {
            $('#bms-error-message').hide();
            var reason_text = $('input[name=reason_key]:checked').closest('.form-group').find('input.reason-text').val();

            jQuery("#Submitting").show();
            jQuery("#deactivtion_button_text").hide();

            var button = $('#deactivtion_button');
            button.prop('disabled', true);

            $.ajax({
                url: bms_ajax.ajax_url,
                method: 'POST',
                data: {
                    action: 'bms_handle_feedback',
                    security: bms_ajax.nonce,
                    reason_key: reason_key,
                    reason_text: reason_text || '',
                    plugin: bms_ajax.plugin
                },
                success: function (response) {
                    if (response.success) {
                        $('#bms-feedback-popup').fadeOut(function () {
                            window.location.reload();
                        });
                    } else {
                        $button.prop('disabled', false).removeClass('loading');
                    }
                },
                error: function () {
                    $button.prop('disabled', false).removeClass('loading');
                }
            });
        }
    });

    $('#bms-feedback-overlay, #bms-close-popup').on('click', function () {
        $('#bms-feedback-popup').fadeOut();
    });
});
