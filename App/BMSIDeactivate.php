<?php

namespace BMSIplugin;

class BMSIDeactivate
{

    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'bmsi_deactivate_enqueue_scripts']);

        add_action('wp_ajax_bms_handle_feedback', [$this, 'bms_handle_feedback']);
    }

    public function bmsi_deactivate(){
        
    }

    public function bmsi_deactivate_enqueue_scripts()
    {
        wp_enqueue_style('bms-feedback-style', BMSI_DIR_URI . 'assets/css/feedback.css', array(), '1.2.0');
        wp_enqueue_script('bms-feedback-script', BMSI_DIR_URI . 'assets/js/feedback.js', array('jquery'), '1.2.0', true);
        wp_localize_script(
            'bms-feedback-script',
            'bms_ajax',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('bms_feedback_nonce'),
                'plugin' => BMSI_PLUGIN_BASENAME
            )
        );
    }


    public function bms_handle_feedback()
    {

        check_ajax_referer('bms_feedback_nonce', 'security');

        $plugin = sanitize_text_field($_POST['plugin']);

        $reason_key = !empty($_POST['reason_key']) ? sanitize_text_field($_POST['reason_key']) : "";

        $message = !empty($_POST['reason_text']) ? sanitize_text_field($_POST['reason_text']) : "";

        $api_url = 'https://bulkmail.insixus.com/wp-json/insixus/v1/submission-deactivate-form';
        $api_data = array(
            'site' => get_site_url(),
            'reason' => $reason_key,
            'message' => $message,
            'extra' => BMSI_PLUGIN_VERSION
        );

        $response = wp_remote_post(
            $api_url,
            array(
                'method' => 'POST',
                'body' => wp_json_encode($api_data),
                'headers' => array(
                    'Content-Type' => 'application/json',
                ),
            )
        );

        deactivate_plugins($plugin, true);

        wp_send_json_success('Plugin deactivated successfully.');
    }

}

new BMSIDeactivate();
?>