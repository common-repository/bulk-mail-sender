<?php
namespace BMSIplugin;

use BMSIplugin\BMSIEmailQueue;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class BMSIAdmin
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'bmsi_admin_enqueue_scripts']);
        add_action('admin_menu', [$this, 'bmsi_admin_page']);
        add_action('wp_ajax_BMSI_display_users', [$this, "BMSI_get_users_callback"]);
        add_action('wp_ajax_nopriv_BMSI_display_users', [$this, "BMSI_get_users_callback"]);
        add_action('wp_ajax_BMSI_display_contact_tags', [$this, "BMSI_display_contact_tags_callback"]);
        add_action('wp_ajax_nopriv_BMSI_display_contact_tags', [$this, "BMSI_display_contact_tags_callback"]);
        add_action('wp_ajax_BMSI_User_send_mail_template', [$this, "BMSI_User_send_mail_template_callback"]);
        add_action('wp_ajax_nopriv_BMSI_User_send_mail_template', [$this, "BMSI_User_send_mail_template_callback"]);
        add_action('wp_ajax_BMSI_manage_setting', [$this, "BMSI_manage_setting_callback"]);
        add_action('wp_ajax_nopriv_BMSI_manage_setting', [$this, "BMSI_manage_setting_callback"]);
        add_action('wp_ajax_BMSI_create_new_user', [$this, "BMSI_create_new_user_callback"]);
        add_action('wp_ajax_nopriv_BMSI_create_new_user', [$this, "BMSI_create_new_user_callback"]);
        add_action('wp_ajax_BMSI_create_contact_tag', [$this, "BMSI_create_contact_tag_callback"]);
        add_action('wp_ajax_nopriv_BMSI_create_contact_tag', [$this, "BMSI_create_contact_tag_callback"]);
        add_action('wp_ajax_BMSI_User_send_mail', [$this, "BMSI_User_send_mail_callback"]);
        add_action('wp_ajax_nopriv_BMSI_User_send_mail', [$this, "BMSI_User_send_mail_callback"]);
        add_action('wp_ajax_BMSI_send_test_mail', [$this, "BMSI_send_test_mail_callback"]);
        add_action('wp_ajax_nopriv_BMSI_send_test_mail', [$this, "BMSI_send_test_mail_callback"]);
        add_action('wp_ajax_BMSI_view_user', [$this, "BMSI_view_user_callback"]);
        add_action('wp_ajax_nopriv_BMSI_view_user', [$this, "BMSI_view_user_callback"]);
        add_action('wp_ajax_BMSI_delete_user', [$this, "BMSI_delete_users_callback"]);
        add_action('wp_ajax_nopriv_BMSI_delete_user', [$this, "BMSI_delete_users_callback"]);
        add_action('wp_ajax_BMSI_delete_contact_tag', [$this, "BMSI_delete_contact_tag_callback"]);
        add_action('wp_ajax_nopriv_BMSI_delete_contact_tag', [$this, "BMSI_delete_contact_tag_callback"]);
        add_action('wp_ajax_BMSI_pro_User_send_mail', [$this, "BMSI_pro_User_send_mail_callback"]);
        add_action('wp_ajax_nopriv_BMSI_pro_User_send_mail', [$this, "BMSI_pro_User_send_mail_callback"]);
        add_action('admin_init', [$this, 'BMSI_register_settings']);
        add_action('wp_ajax_BMSI_SMTP_Connect', [$this, "BMSI_SMTP_Connect_callback"]);
        add_action('wp_ajax_nopriv_BMSI_SMTP_Connect', [$this, "BMSI_SMTP_Connect_callback"]);
        add_action('phpmailer_init', [$this, 'configure_phpmailer_callback']);
        add_action('wp_ajax_BMSI_add_contact_category', [$this, 'BMSI_add_contact_category_callback']);
        add_action('wp_ajax_nopriv_BMSI_add_contact_category', [$this, 'BMSI_add_contact_category_callback']);
        add_action('wp_ajax_BMSI_disply_category', [$this, 'BMSI_display_category_callback']);
        add_action('wp_ajax_nopriv_BMSI_disply_category', [$this, 'BMSI_display_category_callback']);
        add_action('wp_ajax_BMSI_delete_category', [$this, 'BMSI_delete_category_callback']);
        add_action('wp_ajax_nopriv_BMSI_delete_category', [$this, 'BMSI_delete_category_callback']);
        add_action('wp_ajax_BMSI_edit_cataegory', [$this, 'BMSI_edit_cataegory_callback']);
        add_action('wp_ajax_nopriv_BMSI_edit_cataegory', [$this, 'BMSI_edit_cataegory_callback']);
        add_action('wp_ajax_BMSI_file_cataegory', [$this, 'BMSI_file_category_callback']);
        add_action('wp_ajax_nopriv_BMSI_file_cataegory', [$this, 'BMSI_file_category_callback']);

        add_action('wp_ajax_BMSI_import_contacts', [$this, 'BMSI_import_contacts_callback']);
        add_action('wp_ajax_nopriv_BMSI_import_contacts', [$this, 'BMSI_import_contacts_callback']);


        add_action('admin_head', function () {
            remove_submenu_page('bulk_mail_sender', 'bulk_mail_sender');
            remove_submenu_page('bulk_mail_sender', 'users');
            remove_submenu_page('bulk_mail_sender', 'create_user');
            remove_submenu_page('bulk_mail_sender', 'contact_tag');
            remove_submenu_page('bulk_mail_sender', 'add_contact_tag');
            remove_submenu_page('bulk_mail_sender', 'send_mail');
            remove_submenu_page('bulk_mail_sender', 'templates');
            remove_submenu_page('bulk_mail_sender', 'settings');
            remove_submenu_page('bulk_mail_sender', 'get_help');
            remove_submenu_page('bulk_mail_sender', 'contact_category');
            remove_submenu_page('bulk_mail_sender', 'file_contact_category');

        });

    }

    public function bmsi_admin_enqueue_scripts()
    {
        global $pagenow;
        if ($pagenow == "admin.php") {
            if (in_array($_GET['page'], ["users", "create_user", "contact_tag", "add_contact_tag", "send_mail", "get_help", "bulk_mail_sender", "settings", "templates", "create_template", "contact_category", "file_contact_category"])) {
                wp_enqueue_script('blockui-js', BMSI_DIR_URI . 'assets/js/jquery.blockUI.min.js');

                wp_enqueue_style('bms-admin-css', BMSI_DIR_URI . 'assets/css/admin_style.css', '1.1.3');
                wp_enqueue_style('datatables-style', BMSI_DIR_URI . 'assets/css/jquery.dataTables.min.css', '1.1.3');
                wp_enqueue_style('btn-css', BMSI_DIR_URI . 'assets/css/buttons.dataTables.min.css', '1.1.3');
                wp_enqueue_style('filter_toggle', BMSI_DIR_URI . 'assets/css/bootstrap.min.css', '1.1.3');
                wp_enqueue_style('font_awosme_icon', BMSI_DIR_URI . 'assets/css/all.min.css', '1.1.3');
                wp_enqueue_style('chart_style', BMSI_DIR_URI . 'assets/css/apexcharts.min.css', '1.1.3');
                wp_enqueue_style('jquery-confirm', BMSI_DIR_URI . 'assets/css/apexcharts.min.css', '1.1.3');
                wp_enqueue_style('alert-css', BMSI_DIR_URI . 'assets/css/alert_style.css', '1.1.3');
                wp_enqueue_style('jquery-te-1.4.0-css', BMSI_DIR_URI . 'assets/css/jquery-te-1.4.0.css', '1.4.0');
                wp_enqueue_script('chart_script', BMSI_DIR_URI . 'assets/js/apexcharts.min.js');
                wp_enqueue_script('sortable-js', BMSI_DIR_URI . 'assets/js/sortable.js');
                wp_enqueue_script('alert-js', BMSI_DIR_URI . 'assets/js/alert_script.js');
                wp_enqueue_script('Tq-js', BMSI_DIR_URI . 'assets/js/jquery-te-1.4.0.min.js');
                wp_enqueue_script('chart-js', BMSI_DIR_URI . 'assets/js/chart.js', '4.4.2');
                wp_enqueue_script('angular-js', BMSI_DIR_URI . 'assets/js/angular.min.js', '1.8.3');
                wp_enqueue_script('ajax-model-script', BMSI_DIR_URI . 'assets/js/jquery.modal.min.js');
                wp_enqueue_script('filter_toggle_script', BMSI_DIR_URI . 'assets/js/popper.min.js');
                wp_enqueue_script('filter_toggle_script_', BMSI_DIR_URI . 'assets/js/bootstrap.min.js');
                wp_enqueue_script('filter_toggle_script_', BMSI_DIR_URI . 'assets/js/bootstrap.min.js');
                wp_enqueue_script('validate_data', BMSI_DIR_URI . 'assets/js/jquery.validate.min.js');
                wp_enqueue_script('datatables-script', BMSI_DIR_URI . 'assets/js/jquery.dataTables.min.js', array('jquery'), '1.10.25', true);
                wp_enqueue_script('btn-js', BMSI_DIR_URI . 'assets/js/dataTables.buttons.min.js', array('datatables'), '1.7.1', true);
                wp_enqueue_script('bmsi_admin_js', BMSI_DIR_URI . 'assets/js/admin_script.js', ['jquery'], '1.1.3', true);
                wp_enqueue_style('select2-css', BMSI_DIR_URI . 'assets/css/select2.min.css');
                wp_enqueue_script('select2-js', BMSI_DIR_URI . 'assets/js/select2.min.js');
                wp_enqueue_script('xlsx-full-js', BMSI_DIR_URI . 'assets/js/xlsx.full.min.js');


                wp_localize_script(
                    'bmsi_admin_js',
                    'BMSI_object',
                    array(
                        'ajax_url' => esc_url(admin_url('admin-ajax.php')),
                        'plugin_url' => BMSI_DIR_URI,
                        'admin_url' => esc_url(get_admin_url()),
                        'nonce' => wp_create_nonce('ajax-nonce'),
                        'BMSI_Option' => get_option("BMSI_User_Options"),
                    )

                );

            }
            if (in_array($_GET['page'], ["send_mail"])) {
                wp_enqueue_script('radio-js', BMSI_DIR_URI . 'assets/js/radio.js');
            }
            if (in_array($_GET['page'], ["history"])) {
                wp_enqueue_style('history-css', BMSI_DIR_URI . 'assets/css/history.css');
            }
            if (in_array($_GET['page'], ["settings"])) {
                wp_enqueue_script('settings-js', BMSI_DIR_URI . 'assets/js/setting.js');
            }
        }
    }
    public function bmsi_admin_page()
    {
        add_menu_page(
            __('Bulk Mail Sender', 'bulk-mail'),
            __('Bulk Mail Sender', 'bulk-mail'),
            'manage_options',
            'users',
            [$this, 'bmsi_admin_users_submenu_page'],
            BMSI_DIR_URI . 'assets/images/icon.svg',
            6
        );

        // add_submenu_page(
        //     'bulk_mail_sender',
        //     __('Bulk Mail Sender Users', 'bulk-mail'),
        //     __('Dashboard', 'bulk-mail'),
        //     'manage_options',
        //     'bulk_mail_sender',
        //     [$this, 'bmsi_main_admin_page'],
        // );

        add_submenu_page(
            'bulk_mail_sender',
            __('Bulk Mail Sender Users', 'bulk-mail'),
            __('Users', 'bulk-mail'),
            'manage_options',
            'users',
            [$this, 'bmsi_admin_users_submenu_page'],
        );


        add_submenu_page(
            'bulk_mail_sender',
            __('Bulk Mail Sender Add Users', 'bulk-mail'),
            __('Create User', 'bulk-mail'),
            'manage_options',
            'create_user',
            [$this, 'bmsi_admin_create_user_submenu_page'],
        );
        add_submenu_page(
            'bulk_mail_sender',
            __('Bulk Mail Sender Send Mail', 'bulk-mail'),
            __('Send Mail', 'bulk-mail'),
            'manage_options',
            'send_mail',
            [$this, 'BMSI_send_mail_callback'],
        );
        add_submenu_page(
            'bulk_mail_sender',
            __('Bulk Mail Sender Send Mail', 'bulk-mail'),
            __('Templates', 'bulk-mail'),
            'manage_options',
            'templates',
            [$this, 'BMSI_templates_callback'],
        );

        add_submenu_page(
            'bulk_mail_sender',
            __('Bulk Mail Sender Setting', 'bulk-mail'),
            __('Setting', 'bulk-mail'),
            'manage_options',
            'settings',
            [$this, 'BMSI_setting_callback'],
        );

        add_submenu_page(
            'bulk_mail_sender',
            __('Bulk Mail Sender Get Help', 'bulk-mail'),
            __('Get Help', 'bulk-mail'),
            'manage_options',
            'get_help',
            [$this, 'BMSI_get_help_callback'],
        );

        add_submenu_page(
            'bulk_mail_sender',
            __('Bulk Mail Sender contact_category', 'bulk-mail'),
            __('contact_category', 'bulk-mail'),
            'manage_options',
            'contact_category',
            [$this, 'BMSI_contact_category_callback'],
        );

        add_submenu_page(
            'bulk_mail_sender',
            __('Bulk Mail Sender contact_category', 'bulk-mail'),
            __('file_contact_category', 'bulk-mail'),
            'manage_options',
            'file_contact_category',
            [$this, 'BMSI_file_category_callback'],
        );

        add_submenu_page(
            'bulk_mail_sender',
            __('Bulk Mail Sender add_contact_tag', 'bulk-mail'),
            __('add_contact_tag', 'bulk-mail'),
            'manage_options',
            'add_contact_tag',
            [$this, 'BMSI_add_contact_tag_callback'],
        );

        add_submenu_page(
            'bulk_mail_sender',
            __('Bulk Mail Sender contact_tag', 'bulk-mail'),
            __('contact_tag', 'bulk-mail'),
            'manage_options',
            'contact_tag',
            [$this, 'BMSI_contact_tag_callback'],
        );

    }
    public function bmsi_main_admin_page()
    {
        require_once(BMSI_DIR_PATH . 'templates/BMSIDashboard.php');
    }

    public function BMSI_templates_callback()
    {
        require_once(BMSI_DIR_PATH . 'templates/BMSITemplate.php');
    }

    public function bmsi_admin_users_submenu_page()
    {
        require_once(BMSI_DIR_PATH . 'templates/BMSIUser.php');
    }

    public function BMSI_setting_callback()
    {
        require_once(BMSI_DIR_PATH . 'templates/BMSISetting.php');
    }

    public function bmsi_admin_create_user_submenu_page()
    {
        require_once(BMSI_DIR_PATH . 'templates/BMSIAddUser.php');
    }


    public function BMSI_send_mail_callback()
    {
        require_once(BMSI_DIR_PATH . 'templates/BMSISendMail.php');
    }

    public function BMSI_get_help_callback()
    {
        require_once(BMSI_DIR_PATH . 'templates/BMSIGetHelp.php');
    }

    public function BMSI_contact_category_callback()
    {
        require_once(BMSI_DIR_PATH . 'templates/BMSIcontactcategory.php');
    }
    public function BMSI_file_category_callback()
    {
        require_once(BMSI_DIR_PATH . 'templates/BMSIaddcontactcategory.php');
    }
    public function BMSI_add_contact_tag_callback()
    {
        require_once(BMSI_DIR_PATH . 'templates/BMSIAddcontacttag.php');
    }
    public function BMSI_contact_tag_callback()
    {
        require_once(BMSI_DIR_PATH . 'templates/BMSIContacttag.php');
    }

    public function BMSI_get_users_callback()
    {
        global $wpdb;
        $tbl_contact = $wpdb->prefix . "bmsi_contact";
        $tbl_contact_category = $wpdb->prefix . "bmsi_contact_category";
        $tbl_contact_tag = $wpdb->prefix . "bmsi_contact_tag";

        if (!isset($_POST['contact_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['contact_nonce'])), 'ajax-nonce')) {
            die('Nonce verification failed');
        }

        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $search = isset($_POST["search"]["value"]) ? sanitize_text_field($_POST["search"]["value"]) : "";


        $sql = $wpdb->prepare("SELECT * FROM %1s", $tbl_contact);
        $results = $wpdb->get_results($sql, ARRAY_A);

        if (!empty($search)) {
            $sql .= " WHERE `fname` LIKE '%$search%' OR `lname` LIKE '%$search%'";
        }
        $sql .= " ORDER BY `id` DESC LIMIT $start, $length";

        $results = $wpdb->get_results($sql, ARRAY_A);

        foreach ($results as $result) {
            $name = $result['fname'] . " " . $result['lname'];
            $get_tagnames = [];
            $tag_names = [];

            $category_id = $result['contact_category'];
            $sqlforcategory = $wpdb->prepare("SELECT `category_name` FROM %1s WHERE id=%d ", $tbl_contact_category, $category_id);
            $category_name = $wpdb->get_results($sqlforcategory, ARRAY_A);

            $tag_id_array = $result['contact_tag'];
            $tag_id_decode = stripslashes(html_entity_decode($tag_id_array));
            $tag_ids = json_decode($tag_id_decode, true);
            foreach ($tag_ids as $tag_id) {
                $sqlfortag = $wpdb->prepare("SELECT `contact_tag` FROM %1s WHERE id=%d", $tbl_contact_tag, $tag_id);
                $get_tagnames[] = $wpdb->get_results($sqlfortag);
                foreach ($get_tagnames as $get_tagname) {
                    $tagnames = $get_tagname;
                    foreach ($tagnames as $tagname) {
                    }
                }
                $tag_names[] = $tagname->{'contact_tag'};
            }
            $tagname_is = implode(", ", $tag_names);



            $response['id'] = $result['id'];
            $response['name'] = $name;
            $response['email'] = $result['email'];
            $response['category'] = $category_name[0]['category_name'] ?? '-';
            $response['tag'] = !empty($tagname_is) ? $tagname_is : '-';
            $response['status'] = ($result['status'] == "1") ? "Active" : "Inactive";
            $data[] = $response;
        }
        $query_table2 = $wpdb->prepare("SELECT COUNT(*) FROM %1s", $tbl_contact);
        $total_filtered_records = $wpdb->get_var($query_table2);
        $response = [
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $total_filtered_records,
            'recordsFiltered' => $total_filtered_records,
            'data' => $data
        ];
        header("Content-Type: application/json");
        echo json_encode($response);
        die();
    }

    // version 1.2.0  delete user callback
    public function BMSI_delete_users_callback()
    {
        global $wpdb;
        if (!isset($_POST['delete_contact_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['delete_contact_nonce'])), 'ajax-nonce')) {
            die('Nonce verification failed');
        }
        $tbl_contact = $wpdb->prefix . "bmsi_contact";

        $cid = !empty($_POST["id"]) ? sanitize_text_field($_POST["id"]) : "";

        $wpdb->delete(
            $tbl_contact,
            array('id' => $cid, )
        );
        if ($wpdb) {
            echo 1;
        } else {
            echo 0;
        }
        die();
    }


    // version 1.2.0 display contact tags
    public function BMSI_display_contact_tags_callback()
    {
        global $wpdb;
        if (!isset($_POST['tag_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tag_nonce'])), 'ajax-nonce')) {
            die('Nonce verification failed');
        }
        $tbl_contact_tag = $wpdb->prefix . "bmsi_contact_tag";

        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $search = isset($_POST["search"]["value"]) ? sanitize_text_field($_POST["search"]["value"]) : "";

        $sql = $wpdb->prepare("SELECT * FROM %1s", $tbl_contact_tag);
        $results = $wpdb->get_results($sql, ARRAY_A);

        $sql = "SELECT * FROM $tbl_contact_tag";
        $results = $wpdb->get_results($sql, ARRAY_A);

        if (!empty($search)) {
            $sql .= " WHERE contact_tag LIKE '%$search%'";
        }
        $sql .= " ORDER BY `id` DESC LIMIT $length OFFSET $start";
        $results = $wpdb->get_results($sql, ARRAY_A);

        foreach ($results as $result) {
            $response['id'] = $result['id'];
            $response['contact_tag'] = $result['contact_tag'];
            $data[] = $response;
        }
        $query_table2 = $wpdb->prepare("SELECT COUNT(*) FROM %1s", $tbl_contact_tag);
        $total_filtered_records = $wpdb->get_var($query_table2);
        $response = [
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $total_filtered_records,
            'recordsFiltered' => $total_filtered_records,
            'data' => $data
        ];
        header("Content-Type: application/json");
        echo json_encode($response);
        die();
    }

    // version 1.2.0  delete tag callback
    public function BMSI_delete_contact_tag_callback()
    {
        global $wpdb;
        if (!isset($_POST['delete_contact_tag_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['delete_contact_tag_nonce'])), 'ajax-nonce')) {
            die('Nonce verification failed');
        }
        $tbl_contact_tag = $wpdb->prefix . "bmsi_contact_tag";
        $tid = !empty($_POST["id"]) ? sanitize_text_field($_POST["id"]) : "";
        $wpdb->delete(
            $tbl_contact_tag,
            array('id' => $tid, )
        );
        if ($wpdb) {
            echo 1;
        } else {
            echo 0;
        }
        die();
    }


    public function BMSI_User_send_mail_callback()
    {

        if (!isset($_POST['send_mail_user_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['send_mail_user_nonce'])), 'ajax-nonce')) {
            die('Nonce verification failed');
        }

        $option = get_option("BMSI_User_Options");
        $smtp_option = get_option("BMSI_SMTP_Options");

        global $wpdb;
        $tbl_contact = $wpdb->prefix . "bmsi_contact";
        $tbl_mail_details = $wpdb->prefix . 'bmsi_mail_details';
        $tbl_send_mail_details = $wpdb->prefix . 'bmsi_send_mail_details';
        $table_total_mail = $wpdb->prefix . 'bmsi_total_mail';
        $table_history = $wpdb->prefix . 'bmsi_history';

        $total_mail_result_query = $wpdb->prepare("SELECT * FROM %1s", $table_total_mail);
        $total_mail_result = $wpdb->get_results($total_mail_result_query);
        $today_mail_result_query = $wpdb->prepare("SELECT * FROM %1s ORDER BY createdate DESC LIMIT 1", $table_total_mail);
        $today_mail_result = $wpdb->get_results($today_mail_result_query);
        $mail_counter = 0;
        $count = 0;

        $category = !empty($_POST['category']) ? sanitize_text_field($_POST['category']) : "";
        $subject = !empty($_POST['subject']) ? sanitize_text_field($_POST['subject']) : "";
        $sender = !empty($_POST['sender']) ? sanitize_text_field($_POST['sender']) : "";
        $sender_mail = !empty($_POST['sender_id']) ? sanitize_text_field($_POST['sender_id']) : "";

        if (!empty($category)) {
            $total_contact_query = $wpdb->prepare("SELECT * FROM %1s WHERE `contact_category`=%1s AND `status`=%d", $tbl_contact, $category, 1);
        } else {
            $total_contact_query = $wpdb->prepare("SELECT * FROM %1s WHERE `status`=%d", $tbl_contact, 1);
        }
        $all_contacts = $wpdb->get_results($total_contact_query);

        $user_emails = array();
        $today_date = date('Y-m-d');

        foreach ($all_contacts as $contact) {
            $user_emails[] = $contact->email;
            $user_id = $contact->id;
        }
                
        require __DIR__ . '/../vendor/PHPMailer/Exception.php';
        require __DIR__ . '/../vendor/PHPMailer/PHPMailer.php';
        require __DIR__ . '/../vendor/PHPMailer/SMTP.php';

        $phpmail = new PHPMailer(true);

        $phpmail->isSMTP();                                            
        $phpmail->Host = $smtp_option['smtpH'];                     
        $phpmail->SMTPAuth = true;
        $phpmail->Username = $smtp_option['smtpU'];                     
        $phpmail->Password = $smtp_option['smtpPass'];                               
        $phpmail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $phpmail->Port = $smtp_option['smtpPort'];    
        if (!empty($smtp_option["Secure"]) && strtolower($smtp_option["Secure"]) === "ssl") {
            $phpmail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $phpmail->Port = 465;
            $phpmail->SMTPAutoTLS = false;
        } else {
            $phpmail->SMTPAutoTLS = true;
        }     

        $message = !empty($_POST['body']) ? wp_kses_post($_POST['body']) : " ";
        if (!empty($option['smtp_connect']) && $option['smtp_connect'] === "true") {

            try {  
                foreach ($user_emails as $email) {                      
                    //Recipients
                    $phpmail->setFrom($sender_mail, $sender);
                    $phpmail->addAddress($email, 'receiver');     
                    //Content
                    $phpmail->isHTML(true);                                  
                    $phpmail->Subject = $subject;
                    $phpmail->Body = $message;

                    $phpmail->send();
                }
                $message = "Mail Send Successfully";
                $title = "Done!";
                $response = [
                    'status' => true,
                    'message' => esc_html($message, 'bulk-mail'),
                    'title' => esc_html($title, 'bulk-mail'),
                ];
            } catch (Exception $e) {
                $message = "Mails Send Failed";
                $title = "Alert!";
                $response = [
                    'status' => false,
                    'message' => esc_html($message, 'bulk-mail'),
                    'title' => esc_html($title, 'bulk-mail'),
                ];
            }
        } else {
            foreach ($user_emails as $email) {
                $details_data = [
                    'from_mail' => $sender_mail,
                    'from_name' => $sender,
                    'subject' => $subject,
                    'body' => $message,
                    'category' => $category,
                ];

                $history_details_data = [
                    'from_name' => $sender,
                    'subject' => $subject,
                    'body' => $message,
                    'type' => 'manual',
                    'category' => $category,
                    'createdate' => $today_date
                ];

                if ($option["permission"] == "true") {
                    $insert_detail_data = $wpdb->insert(
                        $tbl_mail_details,
                        $details_data,
                    );
                    $sender_id = $wpdb->insert_id;
                }

                $headers = array(
                    'From: ' . $sender . ', <' . $sender_mail . '>',
                    'Cont-ent-Type: text/html; charset=UTF-8'
                );


                $send_details_data = [
                    'mail_detail_id' => !empty($sender_id) ? $sender_id : "",
                    'user_id' => $user_id,
                ];

                if ($option["permission"] == "true") {
                    $insert_sql = $wpdb->prepare("
                INSERT INTO $tbl_send_mail_details (`mail_detail_id`, `user_id`) VALUES (%s, %s)", $send_details_data['mail_detail_id'], $send_details_data['user_id']);

                    $wpdb->query($insert_sql);
                }

                if ($option["permission"] == "true") {
                    $insert_detail = $wpdb->insert(
                        $table_history,
                        $history_details_data
                    );
                    $email_id = $wpdb->insert_id;

                    if ($email_id) {
                        $tracking_pixel_url = plugins_url('track.php', __FILE__) . '?id=' . $email_id;
                        $tracking_pixel = '<img src="' . esc_url($tracking_pixel_url) . '" alt="track" width="1" height="1" border="0" />';
                        $message .= $tracking_pixel;
                    }
                }

                if (isset($option["cron"]) && $option["cron"] == "true") {
                    $email_queue = new BMSIEmailQueue;

                    $result = $email_queue->add_to_email_queue($email, $subject, $message, $headers, $category);
                    $count++;
                } else {
                    $result = wp_mail($email, $subject, $message, $headers, $category);
                    $count++;
                }

                if ($insert_detail !== false) {
                    if ($result === false) {
                        $wpdb->delete(
                            $tbl_mail_details,
                            array('id' => $email_id)
                        );
                    }
                }

                if ($insert_detail_data !== false && !empty($result)) {
                    $mail_counter++;
                }

            }

            if (!empty($total_mail_result)) {
                if ($today_mail_result[0]->createdate === $today_date) {
                    $total_news_mail = $today_mail_result[0]->newsleeter;
                    $total_user_mail = $today_mail_result[0]->user + $mail_counter;
                    $total_mail = $today_mail_result[0]->total + $mail_counter;
                    $total_mail_details_data = array(
                        'total' => $total_mail,
                        'user' => $total_user_mail,
                        'newsleeter' => $total_news_mail
                    );
                    $where = array(
                        'createdate' => $today_date
                    );
                    $wpdb->update($table_total_mail, $total_mail_details_data, $where);
                } else {
                    $total_mail_details_data = array(
                        'total' => $mail_counter,
                        'user' => $mail_counter,
                        'newsleeter' => 0,
                        'createdate' => $today_date
                    );
                    $wpdb->insert(
                        $table_total_mail,
                        $total_mail_details_data,
                    );
                }
            } else {
                $total_mail_details_data = array(
                    'total' => $mail_counter,
                    'user' => $mail_counter,
                    'newsleeter' => 0,
                    'createdate' => $today_date
                );
                $wpdb->insert(
                    $table_total_mail,
                    $total_mail_details_data,
                );
            }

            if ($count > 0) {
                if ($result) {
                    $message = "Mail Send Successfully";
                    $title = "Well Done!";
                    $response = [
                        'status' => true,
                        'message' => esc_html($message, 'bulk-mail'),
                        'title' => esc_html($title, 'bulk-mail'),
                    ];
                } else {
                    $message = "Mails Send Failed";
                    $title = "Alert!";
                    $response = [
                        'status' => false,
                        'message' => esc_html($message, 'bulk-mail'),
                        'title' => esc_html($title, 'bulk-mail'),
                    ];
                }
            } else {
                $message = "No users are available";
                $title = "Alert!";

                $response = [
                    'status' => false,
                    'title' => esc_html($title, 'bulk-mail'),
                    'message' => esc_html($message, 'bulk-mail'),
                ];
            }
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        wp_die();
    }

    public function BMSI_send_test_mail_callback(){

        if (!isset($_POST['teat_send_mail_user_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['teat_send_mail_user_nonce'])), 'ajax-nonce')) {
            die('Nonce verification failed');
        }
        
        $option = get_option("BMSI_User_Options");
        $smtp_option = get_option("BMSI_SMTP_Options");
        
        $mailfrom = !empty($_POST['mailfrom']) ? sanitize_text_field($_POST['mailfrom']) : "";
        $mailto = !empty($_POST['mailto']) ? sanitize_text_field($_POST['mailto']) : "";
        
        require __DIR__ . '/../vendor/PHPMailer/Exception.php';
        require __DIR__ . '/../vendor/PHPMailer/PHPMailer.php';
        require __DIR__ . '/../vendor/PHPMailer/SMTP.php';
        
        $phpmail = new PHPMailer(true);
        
        $phpmail->isSMTP();                                            
        $phpmail->Host = $smtp_option['smtpH'];                     
        $phpmail->SMTPAuth = true;
        $phpmail->Username = $smtp_option['smtpU'];                     
        $phpmail->Password = $smtp_option['smtpPass'];                               
        $phpmail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $phpmail->Port = $smtp_option['smtpPort'];    
        if (!empty($smtp_option["Secure"]) && strtolower($smtp_option["Secure"]) === "ssl") {
            $phpmail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $phpmail->Port = 465;
            $phpmail->SMTPAutoTLS = false;
        } else {
            $phpmail->SMTPAutoTLS = true;
        } 
        
        
        try {                      
            //Recipients
            $phpmail->setFrom($mailfrom, 'sender');
            $phpmail->addAddress($mailto, 'receiver');     
            //Content
            $phpmail->isHTML(true);                                  
            $phpmail->Subject = 'test mail';
            $phpmail->Body = 'Test mail send successfully';
            
            $phpmail->send();
            $message = "Mail Send Successfully";
            $title = "Done!";
            $response = [
                'status' => true,
                'message' => esc_html($message, 'bulk-mail'),
                'title' => esc_html($title, 'bulk-mail'),
            ];
        } catch (Exception $e) {
            $message = "Mails Send Failed";
            $title = "Alert!";
            $response = [
                'status' => false,
                'message' => esc_html($message, 'bulk-mail'),
                'title' => esc_html($title, 'bulk-mail'),
            ];
        }
        
        echo json_encode($response);
        wp_die();
    }

    public function BMSI_pro_User_send_mail_callback()
    {
        if (!isset($_POST['send_mail_user_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['send_mail_user_nonce'])), 'ajax-nonce')) {
            die('Nonce verification failed');
        }

        $today_date = date('Y-m-d');
        $option = get_option("BMSI_User_Options");

        global $wpdb;
        $tbl_mail_details = $wpdb->prefix . 'bmsi_mail_details';
        $tbl_send_mail_details = $wpdb->prefix . 'bmsi_send_mail_details';
        $table_mail_details = $wpdb->prefix . 'bmsi_pro_send_mail_details';
        $table_total_mail = $wpdb->prefix . 'bmsi_total_mail';
        $table_history = $wpdb->prefix . 'bmsi_history';
        $total_mail_result_query = $wpdb->prepare("SELECT * FROM %1s", $table_total_mail);
        $total_mail_result = $wpdb->get_results($total_mail_result_query);
        $today_mail_result_query = $wpdb->prepare("SELECT * FROM %1s ORDER BY createdate DESC LIMIT 1", $table_total_mail);
        $today_mail_result = $wpdb->get_results($today_mail_result_query);
        $mail_counter = 0;
        $count = 0;

        $subject = !empty($_POST['subject']) ? sanitize_text_field($_POST['subject']) : "";
        $sender = !empty($_POST['sender']) ? sanitize_text_field($_POST['sender']) : "";
        $sender_mail = !empty($_POST['sender_mail']) ? sanitize_text_field($_POST['sender_mail']) : "";


        $all_users = get_users();
        $user_emails = array();

        foreach ($all_users as $user) {
            $user_emails[] = $user->user_email;
            $user_id = $user->ID;
        }

        $result = false;

        foreach ($user_emails as $email) {


            $message = !empty($_POST['body']) ? wp_kses_post($_POST['body']) : " ";

            $details_data = [
                'from_mail' => $sender_mail,
                'from_name' => $sender,
                'subject' => $subject,
                'body' => $message,
            ];

            if ($option["permission"] == "true") {
                $insert_detail_data = $wpdb->insert(
                    $tbl_mail_details,
                    $details_data
                );

                $sender_id = $wpdb->insert_id;
            }


            $from_header = 'From: ' . $sender . ' <' . $sender_mail . '>';
            $headers = array(
                $from_header,
                'Content-Type: text/html; charset=UTF-8'
            );

            $pro_details_data = [
                'from_name' => $sender,
                'subject' => $subject,
                'body' => $message,
                'type' => 'manual',
                'createdate' => $today_date,
            ];

            $insert_detail_data = $wpdb->insert(
                $table_mail_details,
                $pro_details_data
            );

            if ($option["permission"] == "true") {
                $insert_detail = $wpdb->insert(
                    $table_history,
                    $pro_details_data
                );
                $email_id = $wpdb->insert_id;

                if ($email_id) {
                    $tracking_pixel_url = plugins_url('track.php', __FILE__) . '?id=' . $email_id;
                    $tracking_pixel = '<img src="' . esc_url($tracking_pixel_url) . '" alt="track" width="1" height="1" border="0" />';
                    $message .= $tracking_pixel;
                }
            }

            if (isset($option["cron"]) && $option["cron"] == "true") {
                $email_queue = new BMSIEmailQueue;
                $result = $email_queue->add_to_email_queue($email, $subject, $message, $headers);
                $count++;
            } else {
                $result = wp_mail($email, $subject, $message, $headers);
                $count++;
            }

            if ($insert_detail !== false) {
                if ($result === false) {
                    $wpdb->delete(
                        $table_history,
                        array('id' => $email_id)
                    );
                }
            }

            if ($insert_detail_data !== false && !empty($result)) {
                $mail_counter++;
            }
        }

        if (!empty($total_mail_result)) {
            if ($today_mail_result[0]->createdate === $today_date) {
                $total_news_mail = $today_mail_result[0]->newsleeter;
                $total_user_mail = $today_mail_result[0]->user + $mail_counter;
                $total_mail = $today_mail_result[0]->total + $mail_counter;
                $total_mail_details_data = array(
                    'total' => $total_mail,
                    'user' => $total_user_mail,
                    'newsleeter' => $total_news_mail
                );
                $where = array(
                    'createdate' => $today_date
                );
                $wpdb->update($table_total_mail, $total_mail_details_data, $where);
            } else {
                $total_mail_details_data = array(
                    'total' => $mail_counter,
                    'user' => $mail_counter,
                    'newsleeter' => 0,
                    'createdate' => $today_date
                );
                $wpdb->insert(
                    $table_total_mail,
                    $total_mail_details_data
                );
            }
        } else {
            $total_mail_details_data = array(
                'total' => $mail_counter,
                'user' => $mail_counter,
                'newsleeter' => 0,
                'createdate' => $today_date
            );
            $wpdb->insert(
                $table_total_mail,
                $total_mail_details_data
            );
        }

        if ($count > 0) {
            if ($result) {
                $message = "Mail Send Successfully";
                $title = "Well Done!";
                $response = [
                    'status' => true,
                    'message' => esc_html($message, 'bulk-mail'),
                    'title' => esc_html($title, 'bulk-mail'),
                ];
            } else {
                $message = "Mails Send Failed";
                $title = "Alert!";
                $response = [
                    'status' => false,
                    'title' => esc_html($title, 'bulk-mail'),
                    'message' => esc_html($message, 'bulk-mail'),
                ];
            }
        } else {
            $message = "No Users are available";
            $title = "Alert!";
            $response = [
                'status' => false,
                'message' => esc_html($message, 'bulk-mail'),
                'title' => esc_html($title, 'bulk-mail'),
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        wp_die();
    }

    public function BMSI_manage_setting_callback()
    {

        if (is_bulk_mail_pro_activated() == "true") {
            if (get_option('BMIS_Pro_activated')) {
                $connect_smtp = !empty($_POST["connect_smtp"]) ? sanitize_text_field($_POST["connect_smtp"]) : "";
                $theme_color = !empty($_POST["theme_color"]) ? sanitize_text_field($_POST["theme_color"]) : "";

                $user_options['smtp_connect'] = $connect_smtp;
                $user_options['color'] = $theme_color;
                update_option("BMSI_User_Options", $user_options);
                $option = get_option("BMSI_User_Options");

                $message = "Changes Saved.";
                $title = "Well Done!";
                $response = [
                    'status' => true,
                    'message' => esc_html($message, 'bulk-mail'),
                    'title' => esc_html($title, 'bulk-mail'),
                ];
            } else {
                $message = "Activation key is requied.";
                $title = "Alert!";
                $response = [
                    'status' => false,
                    'message' => esc_html($message, 'bulk-mail'),
                    'title' => esc_html($title, 'bulk-mail'),
                ];
            }

        } else {
            $connect_smtp = !empty($_POST["connect_smtp"]) ? sanitize_text_field($_POST["connect_smtp"]) : "";
            $user_options['smtp_connect'] = $connect_smtp;
            update_option("BMSI_User_Options", $user_options);
            $option = get_option("BMSI_User_Options");

            $message = "Changes Saved.";
            $title = "Well Done!";
            $response = [
                'status' => true,
                'message' => esc_html($message, 'bulk-mail'),
                'title' => esc_html($title, 'bulk-mail'),
            ];
        }



        header('Content-Type: application/json');
        echo wp_json_encode($response);
        wp_die();

    }

    public function BMSI_create_new_user_callback()
    {
        global $wpdb;
        if (!isset($_POST['create_contact_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['create_contact_nonce'])), 'ajax-nonce')) {
            die('Nonce verification failed');
        }

        $tbl_contact = $wpdb->prefix . "bmsi_contact";

        $option = get_option("BMSI_User_Options");


        //version 1.2.0
        $id = !empty($_POST['cid']) ? sanitize_text_field($_POST['cid']) : "";
        $email = !empty($_POST['email']) ? sanitize_text_field($_POST['email']) : "";
        $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : " ";
        $tag = !empty($_POST['tag']) ? sanitize_text_field($_POST['tag']) : "";
        $status = !empty($_POST['status']) ? sanitize_text_field($_POST['status']) : "";
        $fname = !empty($_POST['fname']) ? sanitize_text_field($_POST['fname']) : "";
        $lname = !empty($_POST['lname']) ? sanitize_text_field($_POST['lname']) : "";
        $job = !empty($_POST['job']) ? sanitize_text_field($_POST['job']) : null;
        $company = !empty($_POST['company']) ? sanitize_text_field($_POST['company']) : "";
        $bdate = !empty($_POST['bdate']) ? sanitize_text_field($_POST['bdate']) : "";
        $adate = !empty($_POST['adate']) ? sanitize_text_field($_POST['adate']) : "";
        //version 1.2.0
        if (!empty($id)) {
            $user_update = $wpdb->update(
                $tbl_contact,
                array(
                    'email' => $email,
                    'contact_category' => $category,
                    'contact_tag' => $tag,
                    'status' => $status,
                    'fname' => $fname,
                    'lname' => $lname,
                    'job_title' => $job,
                    'company' => $company,
                    'birth_date' => $bdate,
                    'anniversary' => $adate
                ),
                array(
                    'id' => $id
                )
            );
        } else {
            $user_details = $wpdb->insert(
                $tbl_contact,
                array(
                    'email' => $email,
                    'contact_category' => $category,
                    'contact_tag' => $tag,
                    'status' => $status,
                    'fname' => $fname,
                    'lname' => $lname,
                    'job_title' => $job,
                    'company' => $company,
                    'birth_date' => $bdate,
                    'anniversary' => $adate
                )
            );
        }

        if ($user_details > 0) {

            $message = 'User Created Successfully.';
            $title = "Congratulations!";
            $response = [
                'status' => true,
                'message' => esc_html($message, 'bulk-mail'),
                'title' => esc_html($title, 'bulk-mail'),
            ];
        } else {
            $message = 'Invalid User.';
            $title = "Alert!";
            $response = [
                'status' => false,
                'message' => esc_html($message, 'bulk-mail'),
                'title' => esc_html($title, 'bulk-mail'),
            ];

        }

        if ($user_update > 0) {

            $message = 'User updated Successfully.';
            $title = "Congratulations!";
            $response = [
                'status' => true,
                'message' => esc_html($message, 'bulk-mail'),
                'title' => esc_html($title, 'bulk-mail'),
            ];

        }
        header('Content-Type: application/json');
        echo json_encode($response);
        wp_die();


    }

    //version 1.2.0  create and update tag
    public function BMSI_create_contact_tag_callback()
    {
        global $wpdb;
        if (!isset($_POST['create_contact_tag_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['create_contact_tag_nonce'])), 'ajax-nonce')) {
            die('Nonce verification failed');
        }
        $tbl_contact_tag = $wpdb->prefix . "bmsi_contact_tag";
        $contactTag = !empty($_POST['contactTag']) ? sanitize_text_field($_POST['contactTag']) : "";

        $tid = !empty($_POST['tid']) ? sanitize_text_field($_POST['tid']) : "";
        if (!empty($tid)) {
            $tag_update = $wpdb->update(
                $tbl_contact_tag,
                array(
                    'contact_tag' => $contactTag,
                ),
                array(
                    'id' => $tid
                )
            );
        } else {
            $tag_details = $wpdb->insert(
                $tbl_contact_tag,
                array(
                    'contact_tag' => $contactTag,
                )
            );
        }

        if ($tag_details > 0) {

            $message = 'Contact Tag Created Successfully.';
            $title = "Congratulations!";
            $response = [
                'status' => true,
                'message' => esc_html($message, 'bulk-mail'),
                'title' => esc_html($title, 'bulk-mail'),
            ];
        } else {
            $message = 'Invalid Contact Tag.';
            $title = "Alert!";
            $response = [
                'status' => false,
                'message' => esc_html($message, 'bulk-mail'),
                'title' => esc_html($title, 'bulk-mail'),
            ];

        }

        if ($tag_update > 0) {

            $message = 'Contact Tag Updated Successfully.';
            $title = "Congratulations!";
            $response = [
                'status' => true,
                'message' => esc_html($message, 'bulk-mail'),
                'title' => esc_html($title, 'bulk-mail'),
            ];

        }

        header('Content-Type: application/json');
        echo json_encode($response);
        wp_die();


    }



    public function BMSI_User_send_mail_template_callback()
    {
        if (!isset($_POST['send_mail_user_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['send_mail_user_nonce'])), 'ajax-nonce')) {
            die('Nonce verification failed');
        }

        $today_date = date('Y-m-d');
        $option = get_option("BMSI_User_Options");

        global $wpdb;
        $table_mail_details = $wpdb->prefix . 'bmsi_pro_send_mail_details';
        $table_total_mail = $wpdb->prefix . 'bmsi_total_mail';
        $tbl_mail_details = $wpdb->prefix . 'bmsi_mail_details';
        $tbl_send_mail_details = $wpdb->prefix . 'bmsi_send_mail_details';
        $table_history = $wpdb->prefix . 'bmsi_history';
        $mail_counter = 0;
        $total_mail_result_query = $wpdb->prepare("SELECT * FROM %1s", $table_total_mail);
        $total_mail_result = $wpdb->get_results($total_mail_result_query);
        $today_mail_result_query = $wpdb->prepare("SELECT * FROM %1s ORDER BY createdate DESC LIMIT 1", $table_total_mail);
        $today_mail_result = $wpdb->get_results($today_mail_result_query);
        $template_name = isset($_POST['template_name']) ? sanitize_text_field($_POST['template_name']) : "";

        $count = 0;
        $all_users = get_users();
        $user_emails = array();

        foreach ($all_users as $user) {
            $user_emails[] = $user->user_email;
            $user_id = $user->ID;
        }

        if (!empty($template_name)) {

            $posts = get_posts(
                array(
                    'post_type' => 'BMSI_Pro_Templates',
                    'post_status' => 'publish',
                    'name' => $template_name,
                    'posts_per_page' => -1,
                )
            );

            if (!empty($posts)) {
                foreach ($posts as $post) {
                    $post_id = $post->ID;
                    $subject = get_post_meta($post_id, 'template_subject', true);
                    $from_name = get_post_meta($post_id, 'mail_from_name', true);
                    $from_email = get_post_meta($post_id, 'mail_from_email', true);



                    foreach ($user_emails as $email) {

                        if (isset($email)) {
                            $user_email = $email;
                            $message = $post->post_content;
                            $from_header = 'From: ' . $from_name . ' <' . $from_email . '>';
                            $headers = array(
                                $from_header,
                                'Content-Type: text/html; charset=UTF-8'
                            );

                            $details_data = [
                                'from_mail' => $from_email,
                                'from_name' => $from_name,
                                'subject' => $subject,
                                'body' => $message,
                            ];

                            if ($option["permission"] == "true") {
                                $insert_detail_data = $wpdb->insert(
                                    $tbl_mail_details,
                                    $details_data,
                                );

                                $sender_id = $wpdb->insert_id;
                            }


                            $pro_details_data = array(
                                'from_name' => $from_name,
                                'subject' => $subject,
                                'body' => $message,
                                'type' => 'template',
                                'createdate' => $today_date,
                            );

                            $pro_insert_detail_data = $wpdb->insert(
                                $table_mail_details,
                                $pro_details_data,
                            );

                            if ($option["permission"] == "true") {
                                $insert_detail = $wpdb->insert(
                                    $table_history,
                                    $pro_details_data
                                );
                                $email_id = $wpdb->insert_id;

                                if ($email_id) {
                                    $tracking_pixel_url = plugins_url('track.php', __FILE__) . '?id=' . $email_id;
                                    $tracking_pixel = '<img src="' . esc_url($tracking_pixel_url) . '" alt="track" width="1" height="1" border="0" />';
                                    $message .= $tracking_pixel;
                                }
                            }

                            if (isset($option["cron"]) && $option["cron"] == "true") {
                                $email_queue = new BMSIEmailQueue;

                                $result_mail = $email_queue->add_to_email_queue($email, $subject, $message, $headers);

                                $count++;
                            } else {
                                $result_mail = wp_mail($email, $subject, $message, $headers);

                                $count++;
                            }


                            if ($insert_detail !== false) {
                                if ($result_mail === false) {
                                    $wpdb->delete(
                                        $table_history,
                                        array('id' => $email_id)
                                    );
                                }
                            }


                            if ($result_mail) {
                                $mail_counter++;
                            }


                        }
                    }
                }
            }

            if (!empty($total_mail_result)) {
                if ($today_mail_result[0]->createdate === $today_date) {
                    $total_news_mail = $today_mail_result[0]->newsleeter;
                    $total_user_mail = $today_mail_result[0]->user + $mail_counter;
                    $total_mail = $today_mail_result[0]->total + $mail_counter;
                    $total_mail_details_data = array(
                        'total' => $total_mail,
                        'user' => $total_user_mail,
                        'newsleeter' => $total_news_mail
                    );
                    $where = array(
                        'createdate' => $today_date
                    );
                    $wpdb->update($table_total_mail, $total_mail_details_data, $where);
                } else {
                    $total_mail_details_data = array(
                        'total' => $mail_counter,
                        'user' => $mail_counter,
                        'newsleeter' => 0,
                        'createdate' => $today_date
                    );
                    $wpdb->insert(
                        $table_total_mail,
                        $total_mail_details_data,
                    );
                }
            } else {
                $total_mail_details_data = array(
                    'total' => $mail_counter,
                    'user' => $mail_counter,
                    'newsleeter' => 0,
                    'createdate' => $today_date
                );
                $wpdb->insert(
                    $table_total_mail,
                    $total_mail_details_data,
                );
            }

            if ($count > 0) {
                if ($result_mail > 0) {
                    $message = "Mail Send Successfully.";
                    $title = "Well Done!";
                    $response = [
                        'status' => true,
                        'message' => esc_html($message, 'bulk-mail'),
                        'title' => esc_html($title, 'bulk-mail'),
                    ];
                } else {
                    $message = "Mail Send Failed.";
                    $title = "Alert!";
                    $response = [
                        'status' => false,
                        'title' => esc_html($title, 'bulk-mail'),
                        'message' => esc_html($message, 'bulk-mail'),
                    ];
                }
            } else {
                $message = "No users are available.";
                $title = "Alert!";

                $response = [
                    'status' => false,
                    'title' => esc_html($title, 'bulk-mail'),
                    'message' => esc_html($message, 'bulk-mail'),
                ];
            }
        } else {
            $message = "No Template Found.";
            $title = "Alert!";

            $response = [
                'status' => false,
                'title' => esc_html($title, 'bulk-mail'),
                'message' => esc_html($message, 'bulk-mail'),
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        wp_die();

    }


    public function BMSI_SMTP_Connect_callback()
    {
        if (!isset($_POST['smtp_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['smtp_nonce'])), 'ajax-nonce')) {
            die('Nonce verification failed');
        }

        $smtpH = !empty($_POST['smtpH']) ? sanitize_text_field($_POST['smtpH']) : "";
        $smtpPort = !empty($_POST['smtpPort']) ? sanitize_text_field($_POST['smtpPort']) : "";
        $smtpU = !empty($_POST['smtpU']) ? sanitize_text_field($_POST['smtpU']) : "";
        $smtpPass = !empty($_POST['smtpPass']) ? sanitize_text_field($_POST['smtpPass']) : "";
        $Secure = !empty($_POST['Secure']) ? sanitize_text_field($_POST['Secure']) : "";
        $Authentication = !empty($_POST['Authentication']) ? sanitize_text_field($_POST['Authentication']) : "";
        $connect_smtp = !empty($_POST['connect_smtp']) ? sanitize_text_field($_POST['connect_smtp']) : "";

        $smtp_option['smtpH'] = $smtpH;
        $smtp_option['smtpPort'] = $smtpPort;
        $smtp_option['smtpU'] = $smtpU;
        $smtp_option['smtpPass'] = $smtpPass;
        $smtp_option['Secure'] = $Secure;
        $smtp_option['Authentication'] = $Authentication;
        update_option("BMSI_SMTP_Options", $smtp_option);
        $option = get_option("BMSI_SMTP_Options");

        $user_option = get_option("BMSI_User_Options");

        if ($connect_smtp == true) {

            global $phpmailer;

            if (!($phpmailer instanceof PHPMailer\PHPMailer\PHPMailer)) {
                require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
                require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
                require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
                $phpmailer = new PHPMailer(true);
            }

            $smtp_option = get_option('BMSI_SMTP_Options');

            try {
                $phpmailer->isSMTP();
                $phpmailer->Host = $smtp_option["smtpH"] ?? '';
                $phpmailer->SMTPAuth = true;
                $phpmailer->Port = $smtp_option["smtpPort"] ?? 587;
                $phpmailer->Username = $smtp_option["smtpU"] ?? '';
                $phpmailer->Password = $smtp_option["smtpPass"] ?? '';
                $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                if (!empty($smtp_option["Secure"]) && strtolower($smtp_option["Secure"]) === "ssl") {
                    $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $phpmailer->Port = 465;
                    $phpmailer->SMTPAutoTLS = false;
                } else {
                    $phpmailer->SMTPAutoTLS = true;
                }
                // print_r($phpmailer->SMTPSecure);
                // die;
                if ($phpmailer->smtpConnect()) {
                    $message = "SMTP connection Successfully.";
                    $title = "Success!";
                    $response = [
                        'status' => true,
                        'title' => esc_html($title, 'bulk-mail'),
                        'message' => esc_html($message, 'bulk-mail'),
                    ];
                } else {
                    $message = "SMTP connection failed.";
                    $title = "Alert!";
                    $response = [
                        'status' => false,
                        'title' => esc_html($title, 'bulk-mail'),
                        'message' => esc_html($message, 'bulk-mail'),
                    ];
                }
            } catch (Exception $e) {
                $message = $e->getMessage();
                $title = "Alert!";
                $response = [
                    'status' => false,
                    'title' => esc_html($title, 'bulk-mail'),
                    'message' => esc_html($message, 'bulk-mail'),
                ];
            }
        } else {
            $message = "Plz check smtp checkbox.";
            $title = "Alert!";
            $response = [
                'status' => false,
                'title' => esc_html($title, 'bulk-mail'),
                'message' => esc_html($message, 'bulk-mail'),
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        wp_die();
    }

    public function configure_phpmailer_callback(PHPMailer $phpmailer): void
    {
        $option = get_option("BMSI_User_Options");

        if (!empty($option['smtp_connect']) && $option['smtp_connect'] === "true") {
            $this->BMSI_PHPMailer_Connect_callback($phpmailer);
        } else {
            $this->BMSI_PHPMailer_callback($phpmailer);
        }
    }

    public function BMSI_PHPMailer_Connect_callback(PHPMailer $phpmailer)
    {
        $smtp_option = get_option('BMSI_SMTP_Options');

        $phpmailer->isSMTP();
        $phpmailer->Host = $smtp_option["smtpH"] ?? '';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = $smtp_option["smtpPort"] ?? 587;
        $phpmailer->Username = $smtp_option["smtpU"] ?? '';
        $phpmailer->Password = $smtp_option["smtpPass"] ?? '';

        $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        if (!empty($smtp_option["Secure"]) && strtolower($smtp_option["Secure"]) === "ssl") {
            $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $phpmailer->Port = 465;
            $phpmailer->SMTPAutoTLS = false;
        } else {
            $phpmailer->SMTPAutoTLS = true;
        }

    }

    public function BMSI_PHPMailer_callback(PHPMailer $phpmailer)
    {
        $phpmailer->IsMail();
    }


    public function BMSI_register_settings()
    {
        $default_options = [];
        if (get_option('BMSI_User_Options') == false) {
            add_option('BMSI_User_Options');
            update_option($default_options, 'BMSI_User_Options');
        }
        register_setting('BMSI_register_settings', 'BMSI_User_Options');

        if (get_option('BMSI_SMTP_Options') == false) {
            add_option('BMSI_SMTP_Options');
            update_option($default_options, 'BMSI_SMTP_Options');
        }
        register_setting('BMSI_register_settings', 'BMSI_SMTP_Options');
    }
    public function BMSI_view_user_callback()
    {
        global $wpdb;
        $tbl_contact = $wpdb->prefix . "bmsi_contact";
        $tbl_contact_category = $wpdb->prefix . "bmsi_contact_category";
        $tbl_contact_tag = $wpdb->prefix . "bmsi_contact_tag";

        if (!isset($_POST['view_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['view_nonce'])), 'ajax-nonce')) {
            die('Nonce verification failed');
        }

        $cid = !empty($_POST["id"]) ? sanitize_text_field($_POST["id"]) : "";
        $sql = $wpdb->prepare("SELECT * FROM %1s WHERE id=%d", $tbl_contact, $cid);
        $results = $wpdb->get_results($sql, ARRAY_A);


        foreach ($results as $result) {
            $name = $result['fname'] . " " . $result['lname'];
            $get_tagnames = [];
            $tag_names = [];

            $category_id = $result['contact_category'];
            $sqlforcategory = $wpdb->prepare("SELECT `category_name` FROM $tbl_contact_category WHERE id=$category_id ");
            $category_name = $wpdb->get_results($sqlforcategory, ARRAY_A);
            $tag_id_array = $result['contact_tag'];
            $tag_id_decode = stripslashes(html_entity_decode($tag_id_array));
            $tag_ids = json_decode($tag_id_decode, true);
            foreach ($tag_ids as $tag_id) {
                $sqlfortag = $wpdb->prepare("SELECT `contact_tag` FROM %1s WHERE id=%d", $tbl_contact_tag, $tag_id);
                $get_tagnames[] = $wpdb->get_results($sqlfortag);
                foreach ($get_tagnames as $get_tagname) {
                    $tagnames = $get_tagname;
                    foreach ($tagnames as $tagname) {
                    }
                }
                $tag_names[] = $tagname->{'contact_tag'};
            }
            $tagname_is = implode(", ", $tag_names);

            $response['id'] = $result['id'];
            $response['name'] = $name;
            $response['email'] = $result['email'];
            $response['category'] = $category_name[0]['category_name'] ?? '-';
            $response['tag'] = !empty($tagname_is) ? $tagname_is : '-';
            $response['status'] = ($result['status'] == "1") ? "Active" : "Inactive";
            $data[] = $response;
        }

        header("Content-Type: application/json");
        echo json_encode($data);
        wp_die();
    }

    public function BMSI_add_contact_category_callback()
    {
        global $wpdb;
        if (!isset($_POST['create_contact_category_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['create_contact_category_nonce'])), 'ajax-nonce')) {
            die('Nonce verification failed');
        }
        $category_details = !empty($eid);
        $tbl_contact_category = $wpdb->prefix . "bmsi_contact_category";

        $eid = !empty($_POST['category_id']) ? sanitize_text_field($_POST['category_id']) : "";
        $parent_id = !empty($_POST['parent_category']) ? sanitize_text_field($_POST['parent_category']) : "";
        $category_name = !empty($_POST['category_name']) ? sanitize_text_field($_POST['category_name']) : "";


        if (!empty($eid)) {
            $category_update = $wpdb->update(
                $tbl_contact_category,
                array(
                    'parent_id' => $parent_id,
                    'category_name' => $category_name
                ),
                array('id' => $eid)
            );
        } else {

            $category_details = $wpdb->insert(
                $tbl_contact_category,
                array(

                    'parent_id' => $parent_id,
                    'category_name' => $category_name
                )
            );
        }

        if ($category_details > 0) {
            $message = 'category Created Successfully.';
            $title = "Congratulations!";
            $response = [
                'status' => true,
                'message' => esc_html($message, 'bulk-mail'),
                'title' => esc_html($title, 'bulk-mail'),
            ];
        }
        if ($category_update > 0) {
            $message = 'updated Successfully.';
            $title = "Congratulations!";
            $response = [
                'status' => true,
                'message' => esc_html($message, 'bulk-mail'),
                'title' => esc_html($title, 'bulk-mail'),
            ];
        }

        // echo json_encode($response);
        wp_die();
    }
    public function BMSI_display_category_callback()
    {
        global $wpdb;
        if (!isset($_POST['display_contact_category_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['display_contact_category_nonce'])), 'ajax-nonce')) {
            die('Nonce verification failed');
        }

        $tbl_contact_category = $wpdb->prefix . "bmsi_contact_category";

        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $search = isset($_POST["search"]["value"]) ? sanitize_text_field($_POST["search"]["value"]) : "";

        $sql = $wpdb->prepare("SELECT * FROM %1s ", $tbl_contact_category);

        if (!empty($search)) {
            $sql .= " WHERE category_name LIKE '%$search%'";
        }

        $sql .= " ORDER BY `id` DESC LIMIT $start, $length";

        $results = $wpdb->get_results($sql);

        foreach ($results as $result) {
            $forparent = $result->parent_id;
            $forcontactcategory = $wpdb->prepare("SELECT `category_name`  FROM %1s WHERE id=%d", $tbl_contact_category, $forparent);

            $categotyid = $wpdb->get_results($forcontactcategory, ARRAY_A);

            $response['id'] = $result->id;

            $response['category_name'] = $result->category_name;
            $response['parent_id'] = $categotyid[0]['category_name'] ?? '-';

            $data[] = $response;

        }

        $query_table2 = $wpdb->prepare("SELECT COUNT(*) FROM %1s", $tbl_contact_category);

        $total_filtered_records = $wpdb->get_var($query_table2);

        $response = [
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $total_filtered_records,
            'recordsFiltered' => $total_filtered_records,
            'data' => $data
        ];

        header("Content-Type:application/json");
        echo json_encode($response);
        die();
    }

    public function BMSI_delete_category_callback()
    {
        global $wpdb;

        if (!isset($_POST['delete_contact_category_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['delete_contact_category_nonce'])), 'ajax-nonce')) {
            die('Nonce verification failed');
        }


        $tbl_contact_category = $wpdb->prefix . "bmsi_contact_category";


        $eid = $_POST["id"] ? sanitize_text_field($_POST['id']) : "";

        $wpdb->delete(
            $tbl_contact_category,
            array('id' => $eid, )
        );
        if ($wpdb) {
            echo 1;
        } else {
            echo 0;
        }
        header("Content-Type:application/json");
        die();
    }

    public function BMSI_import_contacts_callback()
    {
        global $wpdb;
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        // Check if a file was uploaded
        if (empty($_FILES['importSheet']['name'])) {
            echo json_encode(["status" => false, "message" => 'No file uploaded']);
            die;

        }

        $fileTmpPath = $_FILES['importSheet']['tmp_name'];
        $fileName = $_FILES['importSheet']['name'];
        $fileType = $_FILES['importSheet']['type'];

        // Decode the column mappings and rows from the POST request
        $columnMappings = json_decode(stripslashes($_POST['columnMappings']), true);

        // Your database table names
        $contacts_table = $wpdb->prefix . 'bmsi_contact'; // Contacts table
        $category_table = $wpdb->prefix . 'bmsi_contact_category'; // Categories table

        // Load the file based on its type
        if ($fileType == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $fileType == 'application/vnd.ms-excel') {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        } elseif ($fileType == 'text/csv') {
            $sheetData = array_map('str_getcsv', file($fileTmpPath));
        } else {
            echo json_encode(["status" => false, "message" => 'Invalid file type. Only Excel and CSV files are allowed.']);
            die;
        }
        // unset($sheetData[1]);    
        // Get the header row
        $headers = array_shift($sheetData);

        // Create a new array to hold the transformed sheet data
        $transformedData = [];

        foreach ($sheetData as $row) {
            $transformedRow = [];

            foreach ($headers as $key => $header) {
                // Use the header value as the key
                $transformedRow[$header] = $row[$key];
            }

            $transformedData[] = $transformedRow;
        }


        foreach ($transformedData as $row) {
            // Prepare data for insertion using the mappings
            $contact_category = !empty($row[$columnMappings['category']]) ? sanitize_text_field($row[$columnMappings['category']]) : null;

            // Get the category ID
            $category_id = null;
            if ($contact_category) {
                $category_id = $wpdb->get_var($wpdb->prepare(
                    "SELECT id FROM $category_table WHERE LOWER(category_name) = LOWER(%s)",
                    $contact_category
                ));

                // If category does not exist, insert it and get the new ID
                if (!$category_id) {
                    $wpdb->insert($category_table, ['category_name' => $contact_category]);
                    $category_id = $wpdb->insert_id; // Get the ID of the newly inserted category
                }
            }

            // Prepare data for the contacts table
            $email = !empty($row[$columnMappings['email']]) ? sanitize_email($row[$columnMappings['email']]) : null;

            // Check if the email already exists in the contacts table
            if (!empty($email)) {
                $email_exists = $wpdb->get_var($wpdb->prepare(
                    "SELECT COUNT(*) FROM $contacts_table WHERE LOWER(email) = LOWER(%s)",
                    $email
                ));

                // Only insert if the email does not exist
                if ($email_exists == 0) {
                    $data = array(
                        'email' => $email,
                        'fname' => !empty($row[$columnMappings['fname']]) ? sanitize_text_field($row[$columnMappings['fname']]) : null,
                        'lname' => !empty($row[$columnMappings['lname']]) ? sanitize_text_field($row[$columnMappings['lname']]) : null,
                        'contact_category' => $category_id, // Store the category ID instead of the name
                        'contact_tag' => "[]",
                        'status' => 1,
                    );

                    // Insert into the contacts table
                    $wpdb->insert($contacts_table, $data);
                }
            }
        }
        echo json_encode(["status" => true, "message" => 'Contacts imported successfully!']);
        die;
    }




}



?>