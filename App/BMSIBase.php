<?php
namespace BMSIplugin;

class BMSIBase
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'bmsi_base_enqueue_scripts']);
        add_action('init', [$this, "bmsi_quey_alter"]);
    }
    public function bmsi_quey_alter()
    {
        global $wpdb;

        $tbl_history = $wpdb->prefix . "bmsi_history";

        if ($wpdb->get_var("SHOW COLUMNS FROM $tbl_history LIKE 'open_count'") !== 'open_count') {
           
            $alter_tbl_history = "ALTER TABLE $tbl_history ADD COLUMN `open_count` varchar(50) NOT NULL DEFAULT '0'";
            $wpdb->query($alter_tbl_history);
        }
    }
    public function bmsi_base_enqueue_scripts()
    {
        wp_enqueue_style('bms-base-css', BMSI_DIR_URI . 'assets/css/base_style.css', '1.2.0');
        wp_enqueue_script('bms-base-js', BMSI_DIR_URI . 'assets/js/base_script.js', ['jquery'], '1.2.0', true);
    }
}
?>