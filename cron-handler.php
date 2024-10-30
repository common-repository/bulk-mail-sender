<?php
$script_path = dirname(__FILE__);
$path_segments = explode('wp-content', $script_path);
if (isset($path_segments[0])) {
    $wordpress_path = rtrim($path_segments[0], '/') . '/';
} else {
    die('Unable to determine WordPress path.');
}

$wp_load_path = $wordpress_path . 'wp-load.php';
if (!file_exists($wp_load_path)) {
    die('WordPress wp-load.php not found.');
}

require_once($wp_load_path);

$logfile = $wordpress_path . 'wp-content/plugins/bulk-mail-sender/App/cron.log';

error_log('Log file path: ' . $logfile);

function logMessage($message) {
    global $logfile;
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "$timestamp - $message" . PHP_EOL;
    file_put_contents($logfile, $logMessage, FILE_APPEND);
}

logMessage("Cron job work!");

$email_queue_handler = new BMSIplugin\BMSIEmailQueue;
$email_queue_handler->process_email_queue();

?>
