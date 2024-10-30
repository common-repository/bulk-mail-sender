<?php
/**
 * Plugin Name: Bulk Mail Sender
 * Plugin URI: https://insixus.com/
 * Description: Bulk Mail Sender
 * Version: 1.2.0
 * Author: InSixUs InfoTech
 * Text Domain: Bulk Mail Sender
 * Domain Path: /languages
 * Text Domain: bulk-mail
 * Author URI: https://insixus.com
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('ABSPATH') or die('Something went wrong');

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
} else {
    die('Something went wrong');
}

if (!defined('BMSI_DIR_PATH')) {
    define('BMSI_DIR_PATH', plugin_dir_path(__FILE__));
}
if (!defined('BMSI_PLUGIN_BASENAME')) {
    define('BMSI_PLUGIN_BASENAME', plugin_basename(__FILE__));
}
if (!defined('BMSI_DIR_URI')) {
    define('BMSI_DIR_URI', plugin_dir_url(__FILE__));
}
if (!defined('BMSI_PLUGIN_VERSION')) {
    define('BMSI_PLUGIN_VERSION', '1.2.0');
}


// add_filter('plugin_action_links', 'add_pro_version_button', 10, 2);

// function add_pro_version_button($actions, $plugin_file) {
//     if (strpos($plugin_file, 'bulk-mailer/bulk_mail_sender.php') !== false) {
//         if (is_bulk_mail_pro_activated() == "true") {
//             return $actions;
//         } else {
//             $pro_version_button = '<a href="https://bulkmail.insixus.com/" class="pro_version_button" style="color: #00a32a; font-weight: 700;" target="_blank">Get Bulk Mail Sender Pro</a>';
//             $actions = array_merge(array('pro_version' => $pro_version_button), $actions);
//         }
//     }
//     return $actions;
// }

function bmsi_add_custom_post_type()
{

    $labels = array(
        'name' => 'INX_bulk_sender',
        'singular_name' => 'INX_bulk_sender',
        'add_new' => esc_html__('Add New', 'INX_bulk_sender'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'INX_bulk_sender'),
        'supports' => array('title', 'editor', 'thumbnail', 'revisions'),
        'show_ui' => false,
    );

    register_post_type('INX_bulk_sender', $args);

    add_post_type_support('INX_bulk_sender', 'thumbnail');

    add_theme_support('post-thumbnails');
    add_theme_support('post-thumbnails', array('post', 'page', 'popup'));
    add_post_type_support('INX_bulk_sender', 'thumbnail');
}

add_action('init', 'bmsi_add_custom_post_type', 0);


use BMSIplugin\BMSIActivate;
use BMSIplugin\BMSIDeactivate;
use BMSIplugin\BMSIAdmin;
use BMSIplugin\BMSIBase;

register_activation_hook(__FILE__, [(new BMSIActivate()), 'bmsi_activate']);
register_deactivation_hook(__FILE__, [(new BMSIDeactivate()), 'bmsi_deactivate']);

(new BMSIAdmin());
(new BMSIBase());
?>