<?php
include "BMSIHeader.php";

$option = get_option("BMSI_User_Options");

if (empty($option)) {
    update_option("BMSI_User_Options", array("permission" => "true", "pass" => "true", "title" => "Form", "sub_text" => "Submit", "smtp_connect" => "false", "cron" => "false", "color" => "#F8D57E"));
    $option = get_option("BMSI_User_Options");
}

$smtp_option = get_option("BMSI_SMTP_Options");
if (empty($smtp_option)) {
    update_option("BMSI_SMTP_Options", array("smtpH" => "", "smtpPort" => "", "smtpU" => "", "smtpPass" => "", "Secure" => "none", "Authentication" => "true"));
    $smtp_option = get_option("BMSI_SMTP_Options");
}

$phpPath = exec("which php");
?>

<div class="col-lg-10">
    <div class="pt-4"></div>
    <div class="main setting-design">
        <div class="d-lg-flex d-md-flex d-sm-flex d-block justify-content-between flex-row align-items-center"
            id="sub_main">
            <div class="title">
                <h1 class="fw-bold" style="font-size: 40px;">Settings</h1>
            </div>
            <div>
                <button type="button" class="btn-change add-button text-black border-0" id="btn-change"
                    name="btn-change">
                    <span class="btn-txt">Save Changes</span><i class="fa-solid fa-spinner spinn"></i>
                </button>
            </div>
        </div>

        <div class="tab-titles mb-0">
            <p class="tab-links active-link" data-tab="tab1">General</p>

            <?php
            if (is_bulk_mail_pro_activated() == "true") {
                echo apply_filters('additional_tab_links', '');
            }
            ?>
        </div>

        <hr>

        <div class="tab-contents active-tab" id="tab1">

            <h3 class="text-left my-3">SMTP</h3>
            
            <p style="color:green;"><?php echo (!empty($option['smtp_connect']) && esc_html($option['smtp_connect']) == "true") ? "SMTP Already Connected" : "" ?></p>
            <input type="checkbox" class="" name="connect_smtp" id="connect_smtp" <?php echo (!empty($option['smtp_connect']) && esc_html($option['smtp_connect']) == "true") ? "checked" : "" ?>>
                <label for="connect_smtp" class="text-break">Do you want to set up SMTP?</label>
                <?php $nonce = wp_create_nonce('smtp_nonce'); ?>
                <form action="#" method="POST" class="smtp_form_data mt-4" style="display:none">
                    <div id="form_data">
                        <input type="hidden" id="create_user_nonce" name="create_user_nonce"
                            value="<?php echo esc_attr($nonce); ?>" />
                        <div class="form-contant-smtp d-flex flex-column">
                            <div class="firstAndLastName">
                                <div class="pb-sm-3">
                                    <label for="smtpH">SMTP Host</label>
                                    <input type="text" class="input-groups" name="smtpH" id="smtpH"
                                        placeholder="Enter SMTP Host"
                                        value="<?php echo (!empty($smtp_option['smtpH']) ? esc_html($smtp_option['smtpH']) : '') ?>"
                                        required>
                                </div>
                                <div>
                                    <label for="smtpPort" class="smtpPort">SMTP Port</label>
                                    <input type="number" class="input-groups" name="smtpPort" id="smtpPort"
                                        placeholder="Enter SMTP Port"
                                        value="<?php echo (!empty($smtp_option['smtpPort']) ? esc_html($smtp_option['smtpPort']) : '') ?>"
                                        required>
                                </div>
                            </div>
                            <div class="firstAndLastName">
                                <div class="pb-sm-3">
                                    <label for="smtpU">SMTP Username</label>
                                    <input type="text" class="input-groups" name="smtpU" id="smtpU"
                                        placeholder="Enter SMTP Username"
                                        value="<?php echo (!empty($smtp_option['smtpU']) ? esc_html($smtp_option['smtpU']) : '') ?>"
                                        required>
                                </div>
                                <div>
                                    <label for="smtpPass">SMTP Password</label>
                                    <input type="text" class="input-groups" name="smtpPass" id="smtpPass"
                                        placeholder="Enter SMTP Password"
                                        value="<?php echo (!empty($smtp_option['smtpPass']) ? esc_html($smtp_option['smtpPass']) : '') ?>"
                                        required>
                                </div>
                            </div>
                            <div class="firstAndLastName">
                                <div class="flex-row align-items-center pb-sm-3">
                                    <label for="smtpSecure" style="padding-right:10px;">SMTP Secure</label>
                                    <input type="radio" class="setting_radio" id="none" name="Secure" value="none" <?php echo (!empty($smtp_option['Secure']) && $smtp_option['Secure'] === "none") ? "checked" : '' ?> />
                                    <label for="none">none</label>
                                    <input type="radio" class="setting_radio" id="SSL" name="Secure" value="SSL" <?php echo (!empty($smtp_option['Secure']) && $smtp_option['Secure'] === "SSL") ? "checked" : '' ?> />
                                    <label for="SSL">SSL</label>
                                    <input type="radio" class="setting_radio" id="TLS" name="Secure" value="TLS" <?php echo (!empty($smtp_option['Secure']) && $smtp_option['Secure'] === "TLS") ? "checked" : '' ?> />
                                    <label for="TLS">TLS</label>
                                </div>
                            </div>
                            <div class="firstAndLastName">
                                <div class="flex-row align-items-center">
                                    <div>
                                        <label for="smtpSecure" style="padding-right:10px;">SMTP Authentication</label>
                                    </div>
                                    <div class="d-block">
                                        <input type="radio" class="setting_radio" id="yes" name="Authentication"
                                            value="true" <?php echo (!empty($smtp_option['Authentication']) && $smtp_option['Authentication'] === "true") ? "checked" : '' ?> />
                                        <label for="yes" style="padding-right:10px;">yes</label>
                                        <input type="radio" class="setting_radio" id="No" name="Authentication"
                                            value="false" <?php echo (!empty($smtp_option['Authentication']) && $smtp_option['Authentication'] === "false") ? "checked" : '' ?> />
                                        <label for="No">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex w-100 justify-content-end save-button gap-3">
                                <?php 
                                    if(!empty($option['smtp_connect']) && esc_html($option['smtp_connect']) == "true"){
                                        ?><a href="#" class="add-button border-0 text-decoration-none text-black d-flex align-items-center" data-bs-toggle="modal" id="test-mail" data-bs-target="#testmailModal"><span class="btn-txt">Send Test Email</span><i class="fa-solid fa-spinner spinn"></i></a>
                                <?php } ?>

                                <button type="submit" name="submit-sub" id="submit"
                                    class="add-button fs-16menu border-0 btn-Connect">
                                    <span class="btn-txt">Connect</span><i class="fa-solid fa-spinner spinn"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="modal fade" id="testmailModal" tabindex="-1" aria-labelledby="testmailModalLabel" aria-hidden="true">
                    <div class="modal-dialog mt-5">
                        <div class="modal-content fs-14 rounded-4">
                            <div class="modal-header border-0">
                                <h5 class="modal-title py-2 px-1" id="testmailModalLabel">Enter Detail Of Email</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="sendmailForm" method="post">
                                <div class="firstAndLastName">
                                    <div class="pb-sm-3 d-flex flex-column gap-2">
                                        <label for="mailfrom">From</label>
                                        <input type="text" class="input-groups" name="mailfrom" id="mailfrom"
                                            placeholder="Enter your Email"
                                            required>
                                    </div>
                                    <div class="d-flex flex-column gap-2">
                                        <label for="mailto">TO</label>
                                        <input type="text" class="input-groups" name="mailto" id="mailto"
                                            placeholder="Enter Receiver Email"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="fs-14 add-button text-black border-0 m-0 send-test-mail" data-bs-dismiss="modal">Send Mail</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if (is_bulk_mail_pro_activated() == "true") { ?>

                <h3 class="text-left my-3">Styling</h3>


                <div class="d-flex align-items-center justify-content-start gap-2">
                    <label for="theme_color" class="text-break">Theme Color :- </label>
                    <input type="color" name="theme_color" id="theme_color"
                        value="<?php echo (!empty($option['color'])) ? $option['color'] : "#F8D57E" ?>">
                </div>

            <?php } ?>
        </div>

        <?php
        if (is_bulk_mail_pro_activated() == "true") {
            echo apply_filters('additional_tab_contents', '');
        }
        ?>
    </div>
</div>

<style>
    :root {
        --theme-color:
            <?php echo (!empty($option['color'])) ? $option['color'] : "#F8D57E" ?>
    }
</style>

<?php include "BMSIFooter.php"; ?>