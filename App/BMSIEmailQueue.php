<?php
namespace BMSIplugin;

class BMSIEmailQueue
{
    public function __construct()
    {
        
    }

    public function add_to_email_queue($to, $subject, $message, $headers)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'bmsi_email_queue';

        $data = array(
            'to_email' => $to,
            'subject' => $subject,
            'message' => $message,
            'headers' => serialize($headers),
        );

        $result_data = $wpdb->insert($table_name, $data);

        if($result_data > 0){
            return $result_data;
        }
    }

    public function process_email_queue()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'bmsi_email_queue';

        $emails = $wpdb->get_results($wpdb->prepare("SELECT * FROM %1s",$table_name));

        foreach ($emails as $email) {
            $to = $email->to_email;
            $subject = $email->subject;
            $message = $email->message;
            $headers = unserialize($email->headers);

            $result_data = wp_mail($to, $subject, $message, $headers);
            
            if($result_data){

                $wpdb->delete($table_name, array('id' => $email->id));
            }
        }
    }
}

new BMSIEmailQueue();
?>