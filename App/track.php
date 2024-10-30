<?php
require_once('../../../../wp-load.php'); 

global $wpdb;
$table_name = $wpdb->prefix . 'bmsi_history';

$email_id = intval($_GET['id']);

if ($email_id > 0) {
    $wpdb->query(
        $wpdb->prepare(
            "UPDATE $table_name SET open_count = open_count + 1 WHERE id = %d",
            $email_id
        )
    );
}

header('Content-Type: image/gif');
echo base64_decode('R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==');
exit;
?>
