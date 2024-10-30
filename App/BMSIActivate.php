<?php
namespace BMSIplugin;

class BMSIActivate
{
    public function __construct()
    {
    }
    public function bmsi_activate()
    {
        $this->add_bmsi_users_table();
        $this->activate_details();
    }
    public function add_bmsi_users_table()
    {
        global $wpdb;
        $tbl_mail_details = $wpdb->prefix . "bmsi_mail_details";
        $tbl_send_mail_details = $wpdb->prefix . "bmsi_send_mail_details";
        $tbl_total_mail = $wpdb->prefix . "bmsi_total_mail";
        $tbl_email_queue = $wpdb->prefix . "bmsi_email_queue";
        $tbl_contact_category = $wpdb->prefix . "bmsi_contact_category";
        $tbl_contact = $wpdb->prefix . "bmsi_contact";  //version 1.2.0
        $tbl_contact_tag = $wpdb->prefix . "bmsi_contact_tag";  //version 1.2.0

        $create_tbl_mail_details = "CREATE TABLE $tbl_mail_details (
            `id` int(25) not null auto_increment,
            `from_mail` varchar(255) not null,
            `from_name` varchar(255) not null,
            `subject` varchar(255) not null,
            `body` varchar(65535) not null,
            `category` int(25),
            `createdate` timestamp not null DEFAULT CURRENT_TIMESTAMP,
            primary key(`id`) 
        )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $create_tbl_total_mail = "CREATE TABLE $tbl_total_mail (
            `id` int(25) not null auto_increment,
            `total` int(25) not null,
            `newsleeter` int(25) not null,
            `user` int(25) not null,
            `createdate` date not null,
            primary key(`id`) 
        )";

        $create_tbl_send_mail_details = "CREATE TABLE $tbl_send_mail_details (
            `id` int(25) not null auto_increment,
            `mail_detail_id` varchar(255) not null,
            `user_id` varchar(255) not null,
            `createdate` timestamp not null DEFAULT CURRENT_TIMESTAMP,
            primary key(`id`) 
        )";


        $create_tbl_email_queue = "CREATE TABLE $tbl_email_queue (
            `id` int(25) not null auto_increment,
            to_email VARCHAR(100) NOT NULL,
            subject VARCHAR(255) NOT NULL,
            message varchar(65535) NOT NULL ,
            headers TEXT NOT NULL,
            primary key(`id`) 
        )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        //version 1.2.0
        $create_tbl_contact = "CREATE TABLE $tbl_contact (
            `id` int(25) not null auto_increment,
            `email` VARCHAR(100) NOT NULL,
            `contact_category` VARCHAR(100) NOT NULL,
            `contact_tag` VARCHAR(100) DEFAULT NULL,
            `status` VARCHAR(100) DEFAULT 1,
            `fname` VARCHAR(100) NOT NULL,
            `lname` VARCHAR(100) NOT NULL,
            `job_title` VARCHAR(100) DEFAULT NULL,
            `company` VARCHAR(100) DEFAULT NULL,
            `birth_date` DATE DEFAULT NULL,
            `anniversary` DATE DEFAULT NULL,
            primary key(`id`) 
        )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $create_tbl_contact_category = "CREATE TABLE $tbl_contact_category (
            `id` int(25) not null auto_increment,
            category_name VARCHAR(255) NOT NULL,
            parent_id  int(50) NOT NULL,
            primary key(`id`) 
        )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $create_tbl_contact_tag = "CREATE TABLE $tbl_contact_tag (
            `id` int(25) not null auto_increment,
            `contact_tag` VARCHAR(255) NOT NULL,
            primary key(`id`) 
        )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        require_once (ABSPATH . '/wp-admin/includes/upgrade.php');
        maybe_create_table($tbl_mail_details, $create_tbl_mail_details);
        maybe_create_table($tbl_send_mail_details, $create_tbl_send_mail_details);
        maybe_create_table($tbl_total_mail, $create_tbl_total_mail);
        maybe_create_table($tbl_email_queue, $create_tbl_email_queue);
        maybe_create_table($tbl_contact_category, $create_tbl_contact_category);
        maybe_create_table($tbl_contact, $create_tbl_contact );  //version 1.2.0
        maybe_create_table($tbl_contact_tag, $create_tbl_contact_tag );
       
    }

    public function activate_details(){
        $api_url = 'https://bulkmail.insixus.com/wp-json/insixus/v1/submission-activate-form';
        $api_data = array(
            'site' => get_site_url(),
            'reason' => "Activate",
            'message' => "Activate",
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
    }

}