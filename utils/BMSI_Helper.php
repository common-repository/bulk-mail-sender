<?php
function get_role_from_slug($role_slug)
{
    global $wp_roles;

    if (!isset($wp_roles)) {
        $wp_roles = new WP_Roles();
    }

    if (isset($wp_roles->roles[$role_slug])) {
        return $wp_roles->roles[$role_slug]['name'];
    }

    return false;
}

function is_bulk_mail_pro_activated()
{
    if (is_plugin_active('bulk-mailer-pro/bulk-mailer-pro.php')) {
        return true;
    }
    return false;
}

?>