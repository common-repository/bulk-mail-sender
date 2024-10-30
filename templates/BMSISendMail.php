<?php

if (!defined('ABSPATH'))
    exit;
$type = '';
$disable = false;
if (is_bulk_mail_pro_activated() == "true") {
    if (isset($_GET['type']) && $_GET['type'] === 'newslettter') {
        $type = 'btn-' . $_GET['type'];
    } else {
        $type = 'btn-pro-send-mail';
    }
}

$args = array(
    'role' => 'administrator',
);

$users = get_users($args);
?>

<?php

global $wpdb;
$tbl_contact = $wpdb->prefix . "bmsi_contact";
$tatal_contact_query = $wpdb->prepare("SELECT * FROM %1s", $tbl_contact);
$all_contacts = $wpdb->get_results($tatal_contact_query,ARRAY_A);
?>
<?php

global $wpdb;
$tbl_contact_category = $wpdb->prefix . "bmsi_contact_category";
$tatal_contact_category_query = $wpdb->prepare("SELECT * FROM %1s", $tbl_contact_category);
$all_contact_categories = $wpdb->get_results($tatal_contact_category_query,ARRAY_A);
$category_query = $wpdb->prepare("SELECT * FROM %1s", $tbl_contact_category);
$parent_categories = $wpdb->get_results($category_query);
$subcategory_query = $wpdb->prepare("SELECT * FROM %1s", $tbl_contact_category);
$subcategories = $wpdb->get_results("$subcategory_query");
            
?>

<?php include "BMSIHeader.php" ?>

<div class="col-xl-10 col-lg-11 col-md-12 col-sm-12 col-12">
    <div class="main mail-data">
        <?php
        $nonce = wp_create_nonce('send_mail_user_nonce');
        ?>

        <?php if(is_bulk_mail_pro_activated()){
            if(get_option('BMIS_Pro_activated')){ ?>
        <div class="send_mail_data">
            <input type="hidden" name="send_mail_user_nonce" id="send_mail_user_nonce"
                value="<?php echo esc_attr($nonce); ?>" />
            <div class="pt-2"></div>
            <div class="send_mail_form_contant">
                <div class="d-lg-flex d-md-flex d-sm-flex d-block justify-content-between flex-row align-items-center">
                    <div class="title">
                        <h1 class="fw-bold" style="font-size: 40px;">Send Mails</h1>
                    </div>
                    <div
                        class="mb-lg-0 mb-md-0 mb-sm-0 mb-4 d-flex justify-content-lg-between justify-content-md-between justify-content-sm-between justify-content-start flex-row">
                        <button class="add-button border-0"><a class="btn-back"
                                href="<?php printf(esc_html('%sadmin.php?page=bulk_mail_sender', 'bulk-mail'), esc_html($admin_url)); ?>">Back</a></button>
                        <button class="add-button border-0 <?php echo !empty($type) ? esc_html($type) : 'btn-send-mail' ?>"><span
                                class="btn-txt">Send</span><i class="fa-solid fa-spinner spinn"></i></button>
                    </div>
                </div>

                    <!-- <div class="btn_radio d-lg-flex d-md-flex d-sm-flex d-block gap-3 align-items-center flex-row mb-4 mt-3 <?php echo is_bulk_mail_pro_activated() == "true" ? "" : "btn_active" ?>">
                        <label class="template_radio cursor-pointer <?php echo is_bulk_mail_pro_activated() == "true" ? "" : "o-5" ?>">
                            <input type="radio" name="template_radio" id="user_template" value="user_template" <?php echo (is_bulk_mail_pro_activated() == "true") ? "" : "disabled" ?>>Use
                            Template
                        </label>
                        <label class="manual_option cursor-pointer <?php echo is_bulk_mail_pro_activated() == "true" ? "" : "o-5" ?>">
                            <input type="radio" name="template_radio" checked id="manually" value="manually" <?php echo is_bulk_mail_pro_activated() == "true" ? "" : "disabled" ?>>Manually</label>
                        <?php if(is_bulk_mail_pro_activated() != "true") {?><a href="https://bulkmail.insixus.com/" target="_blank" class="link text-decoration-none"><p class="upgrade">Upgrade to pro</p></a><?php } ?>
                    </div> -->

                <form action="#" method="POST" class="manual_form send_mail_form">

                    <?php if(is_bulk_mail_pro_activated() == "true") { 

                        if (isset($_GET['type']) && $_GET['type'] === 'newslettter') {

                            global $wpdb;
                            $table_category = $wpdb->prefix . 'bmsi_news_letter_category';
                            $table_sub_category = $wpdb->prefix . 'bmsi_news_letter_sub_category';
                            
                            $parent_categories = $wpdb->get_results("SELECT * FROM $table_category");
                            
                            $subcategories = $wpdb->get_results("SELECT * FROM $table_sub_category");
                            
                            $grouped_subcategories = [];
                            if (!empty($subcategories)) {
                                foreach ($subcategories as $sub) {
                                    $grouped_subcategories[$sub->parent_category_id][] = $sub;
                                }
                            }
                            
                            if (!empty($parent_categories)) {
                                foreach ($parent_categories as $category) {
                                    $category->subcategories = isset($grouped_subcategories[$category->id]) ? $grouped_subcategories[$category->id] : [];
                                }
                            }

                        ?>

                        <div class="firstAndLastName d-flex flex-row mb-4">

                        <div class=" d-flex flex-column w-100">
                            <label>NewsLetter Category</label>
                            <select name="category" id="category" class="input-groups">
                            <option class="level-0" value="0">All
                                </option>
                                <?php
                                foreach ($parent_categories as $category) {
                                        if (!empty($category->subcategories)) {
                                            echo '<option class="'. esc_attr($category->level) .'" value="' . esc_html($category->id) . '">'. esc_html($category->category_name) .'</option>';
                                            foreach ($category->subcategories as $sub_category) {
                                                echo '<option class="'. esc_attr($sub_category->level) .'" value="' . esc_attr($sub_category->id) . '">&nbsp;&nbsp;&nbsp;- ' . esc_html($sub_category->sub_category_name) . '</option>';
                                            }
                                        } else {
                                            echo '<option class="'. esc_attr($category->level) .'" value="' . esc_attr($category->id) . '">' . esc_html($category->category_name) . '</option>';
                                        }
                                } ?>
                                
                            </select>
                        </div>

                        <div class=" d-flex flex-column w-100">
                            <label>NewsLetter Status</label>
                            <select name="status" id="status" class="input-groups">
                                <option value="2">All
                                </option>
                                <option value="1">active
                                </option>
                                <option value="0">Inactive
                                </option>
                            </select>
                        </div>

                        </div>

                        <?php } 

                    }?>

                    <div class="subjectAndemailfrom manual mb-4">
                        <div class="d-flex">
                            <label for="mail_sub">Subject</label>
                            <input type="text" name="subject" id="mail_sub" class="mail_sub input-groups"
                                placeholder="Enter User Subject" required>
                        </div>
                        <div class="d-flex">
                            <label for="mail_from">Email From Name</label>
                            <input type="text" name="mail_from" id="mail_from" class="mail_from input-groups"
                                placeholder="Enter Email Form Name" required>

                        </div>
                    </div>
                    <div class="subjectAndemailfrom firstAndLastName manual mb-4">
                        <div class="emailfrom d-flex">
                            <label for="admin_mail">Email From</label>
                            <select name="admin_mail" id="admin_mail" class="input-groups">
                                        <?php
                                        foreach ($users as $user) { ?>
                                           
                                                <option value="<?php echo esc_attr($user->user_email); ?>">
                                                <?php echo esc_attr($user->user_email); ?>
                                            </option>
                                             <?php 
                                        }
                                            ?>
                                            
                                    </select>
                                    </div>
                    </div>
                    <div class="manual">
                        <label for="mail_contant">Email Body</label><br>
                        <?php
                        $content = "add some text";
                        $editor_id = 'bmsi_mail_box';
                        $editor_name = 'bmsi_mail_box_content';
                        $settings = array(
                            'textarea_rows' => 8,
                            'media_buttons' => true,
                            'quicktags' => array('buttons' => 'h1,h2,h3,h4,h5,h6,strong,em,link,block,del,ins,img,ul,ol,li,code'),
                            'tinymce' => true,
                            'editor_name' => $editor_name,
                        );
                        $headers = array('Content-Type: text/html; charset=UTF-8');
                        wp_editor($content, $editor_id, $settings, $headers);
                        ?>
                        <span class="bmsi_mail_box_error error"></span>
                    </div>
                    <div class="text-end">
                    <button type="submit" name="submit"
                        class="add-button fs-16 border-0 <?php echo !empty($type) ? esc_html($type) : 'btn-send-mail' ?>"
                        id="submit"><span class="btn-txt">Send</span><i class="fa-solid fa-spinner spinn"></i></button>
                </div>
                </form>
                <form action="#" method="POST" class="use_template_form send_mail_form" style="display: none;">
                    <div class="subjectAndemailfrom">
                        <div class="firstAndLastName">
                            <div class="d-flex">
                                <label for="Select_template">Select Template</label>
                                <?php
                                $args = array(
                                    'post_type' => 'BMSI_Pro_Templates',
                                    'post_status' => 'publish',
                                    'posts_per_page' => -1,
                                );

                                $query = new \WP_Query($args);

                                if ($query->have_posts()) {
                                    $disable = true;
                                    ?>
                                    <select name="template_name" id="template_name" class="input-groups">
                                        <?php
                                        while ($query->have_posts()) {
                                            $query->the_post();
                                            $template_name = get_the_title();
                                            ?>
                                            <option value="<?php echo esc_attr($template_name); ?>">
                                                <?php echo esc_attr($template_name); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <?php
                                    wp_reset_postdata();
                                } else { ?>
                                    <p class="mb-0 error">*No Template Found.</p>
                                    <a href="<?php printf(esc_html('%sadmin.php?page=create_template&edit=false'), esc_html($admin_url)); ?>" class="fz-14 border-0 text-decoration-none theme-color w-30">Add New Template</a>
                                <?php } ?>
                            </div>
                        </div>

                        <?php if(is_bulk_mail_pro_activated() == "true") { 
                            if (isset($_GET['type']) && $_GET['type'] === 'newslettter') {
                                global $wpdb;
                                $table_category = $wpdb->prefix . 'bmsi_news_letter_category';
                                $table_sub_category = $wpdb->prefix . 'bmsi_news_letter_sub_category';

                                $parent_categories = $wpdb->get_results("SELECT * FROM $table_category");
                                $subcategories = $wpdb->get_results("SELECT * FROM $table_sub_category");

                                $grouped_subcategories = [];
                                if (!empty($subcategories)) {
                                    foreach ($subcategories as $sub) {
                                        $grouped_subcategories[$sub->parent_category_id][] = $sub;
                                    }
                                }

                                if (!empty($parent_categories)) {
                                    foreach ($parent_categories as $category) {
                                        $category->subcategories = isset($grouped_subcategories[$category->id]) ? $grouped_subcategories[$category->id] : [];
                                    }
                                }
                            ?>

                            <div class="firstAndLastName">
                                <div class=" d-flex flex-column">
                                    <label>NewsLetter Category</label>
                                    <select name="category" id="Newslettercategory" class="input-groups">
                                        <option class="level-0" value="0">All</option>
                                        <?php
                                        foreach ($parent_categories as $category) {
                                            if (!empty($category->subcategories)) {
                                                echo '<option class="'. esc_attr($category->level) .'" value="' . esc_html($category->id) . '">'. esc_html($category->category_name) .'</option>';
                                                foreach ($category->subcategories as $sub_category) {
                                                    echo '<option class="'. esc_attr($sub_category->level) .'" value="' . esc_attr($sub_category->id) . '">&nbsp;&nbsp;&nbsp;- ' . esc_html($sub_category->sub_category_name) . '</option>';
                                                }
                                            } else {
                                                echo '<option class="'. esc_attr($category->level) .'" value="' . esc_attr($category->id) . '">' . esc_html($category->category_name) . '</option>';
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="firstAndLastName mb-4">
                                <div class="d-flex flex-column w-100">
                                    <label>NewsLetter Status</label>
                                    <select name="Ustatus" id="Ustatus" class="input-groups">
                                        <option value="2">All</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <?php } 
                        } ?>
                    </div>
                    <div class="text-end">
                        <button type="submit" name="submit" <?php echo ($disable == false) ? "disabled" : "" ?>
                            class="add-button mt-4 fs-16 border-0 <?php echo !empty($type) ? esc_html($type) : 'btn-send-mail' ?>"
                            id="submit"><span class="btn-txt">Send</span><i class="fa-solid fa-spinner spinn"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
           <?php }else{ ?>
            <div class="send-bg-image d-flex justify-content-center align-items-center">
                <div class="dashboard-wrapper">
                    <div class="bg-color p-3 rounded-4 mx-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="55" height="55" viewBox="0 0 66 75" fill="none">
                            <path
                                d="M58.8933 37.3734H22.9333V22.4884C22.9333 16.7711 27.5298 12.0212 33.2718 11.9635C39.0718 11.9057 43.8133 16.5979 43.8133 22.3584V24.6684C43.8133 26.5886 45.3648 28.1334 47.2933 28.1334H51.9333C53.8618 28.1334 55.4133 26.5886 55.4133 24.6684V22.3584C55.4133 10.231 45.4808 0.37016 33.3008 0.413472C21.1208 0.456785 11.3333 10.4475 11.3333 22.575V37.3734H7.85331C4.01081 37.3734 0.893311 40.4775 0.893311 44.3034V67.4033C0.893311 71.2293 4.01081 74.3333 7.85331 74.3333H58.8933C62.7358 74.3333 65.8533 71.2293 65.8533 67.4033V44.3034C65.8533 40.4775 62.7358 37.3734 58.8933 37.3734ZM39.1733 59.3184C39.1733 62.509 36.5778 65.0933 33.3733 65.0933C30.1688 65.0933 27.5733 62.509 27.5733 59.3184V52.3884C27.5733 49.1977 30.1688 46.6134 33.3733 46.6134C36.5778 46.6134 39.1733 49.1977 39.1733 52.3884V59.3184Z"
                                fill="white" />
                        </svg>
                    </div>
                    <div class="text-center mt-2">
                        <p style="margin-bottom:0;">Activate Pro version</p>
                        <a href="<?php printf(esc_html('%sadmin.php?page=settings', 'bulk-mail-sender-pro'), esc_html($admin_url)); ?>">click
                            here</a>
                    </div>
                </div>
            </div>
           <?php }
        }else{ ?>
<div class="send_mail_data">
            <input type="hidden" name="send_mail_user_nonce" id="send_mail_user_nonce"
                value="<?php echo esc_attr($nonce); ?>" />
            <div class="pt-2"></div>
            <div class="send_mail_form_contant">
                <div class="d-lg-flex d-md-flex d-sm-flex d-block justify-content-between flex-row align-items-center">
                    <div class="title">
                        <h1 class="fw-bold" style="font-size: 40px;">Send Mails</h1>
                    </div>
                    <div
                        class="mb-lg-0 mb-md-0 mb-sm-0 mb-4 d-flex justify-content-lg-between justify-content-md-between justify-content-sm-between justify-content-start flex-row">
                        <button class="add-button border-0"><a class="btn-back"
                                href="<?php printf(esc_html('%sadmin.php?page=bulk_mail_sender', 'bulk-mail'), esc_html($admin_url)); ?>">Back</a></button>
                        <button class="add-button border-0 <?php echo !empty($type) ? esc_html($type) : 'btn-send-mail' ?>"><span
                                class="btn-txt">Send</span><i class="fa-solid fa-spinner spinn"></i></button>
                    </div>
                </div>
                    <!-- <div class="btn_radio d-lg-flex d-md-flex d-sm-flex d-block gap-3 align-items-center flex-row mb-4 mt-3 <?php echo is_bulk_mail_pro_activated() == "true" ? "" : "btn_active" ?>">
                        <label class="template_radio cursor-pointer <?php echo is_bulk_mail_pro_activated() == "true" ? "" : "o-5" ?>">
                            <input type="radio" name="template_radio" id="user_template" value="user_template" <?php echo (is_bulk_mail_pro_activated() == "true") ? "" : "disabled" ?>>Use
                            Template
                        </label>
                        <label class="manual_option cursor-pointer <?php echo is_bulk_mail_pro_activated() == "true" ? "" : "o-5" ?>">
                            <input type="radio" name="template_radio" checked id="manually" value="manually" <?php echo is_bulk_mail_pro_activated() == "true" ? "" : "disabled" ?>>Manually</label>
                        <?php if(is_bulk_mail_pro_activated() != "true") {?>
                            <a href="https://bulkmail.insixus.com/" target="_blank" class="link text-decoration-none"><p class="upgrade">Upgrade to pro</p></a>
                        <?php } ?>
                    </div> -->

                   <?php 
                    $grouped_subcategories = [];
                    if (!empty($subcategories)) {
                        foreach ($subcategories as $sub) {
                            $grouped_subcategories[$sub->parent_id][$sub->id] = $sub;
                        }
                    }
                    if (!empty($parent_categories)) {
                        foreach ($parent_categories as $category) {
                            $category->subcategories = isset($grouped_subcategories[$category->id]) ? $grouped_subcategories[$category->id] : [];
                        }
                    }
                    ?>
                <form action="#" method="POST" class="manual_form send_mail_form">
                    <div class="subjectAndemailfrom firstAndLastName manual mb-4">
                        <div class="emailfrom d-flex">
                            <label for="category">Category</label>
                            <select id="category" class="input-groups">
                                <option value="">Select category</option>
                                <?php
                                foreach ($parent_categories as $category) {
                                    if (!empty($category->subcategories)) {
                                        echo '<option value="' . esc_html($category->id) . '">'. esc_html($category->category_name) .'</option>';
                                        foreach ($category->subcategories as $sub_category) {
                                            echo '<option value="' . esc_attr($sub_category->id) . '">&nbsp;&nbsp;&nbsp;- ' . esc_html($sub_category->category_name) . '</option>';
                                        }
                                    }
                                    else {
                                        if($category->parent_id == 0){
                                            ?><option value="<?php echo esc_attr($category->id); ?>"><?php echo esc_attr($category->category_name); ?><?php
                                        }
                                    }
                                } ?>
                            </select>
                        </div>
                        <div class="d-flex">
                            <label for="mail_sub">Subject <b style="color:red;">*</b></label>
                            <input type="text" name="subject" id="mail_sub" class="mail_sub input-groups"
                                placeholder="Enter User Subject" required>
                        </div>
                    </div>
                    <div class="subjectAndemailfrom firstAndLastName manual mb-4">
                        <div class="d-flex">
                            <label for="mail_from">Email From Name <b style="color:red;">*</b></label>
                            <input type="text" name="mail_from" id="mail_from" class="mail_from input-groups"
                                placeholder="Enter Email Form Name" required>
                        </div>
                        <div class="emailfrom d-flex">
                            <label for="admin_mail">Email From <b style="color:red;">*</b></label>
                            <select name="admin_mail" id="admin_mail" class="input-groups">
                                <?php
                                    foreach ($users as $user) { ?>
                                        <option value="<?php echo esc_attr($user->user_email); ?>">
                                            <?php echo esc_attr($user->user_email); ?>
                                        </option>
                                        <?php 
                                    }
                                        ?>
                            </select>
                        </div>
                    </div>
                    <div class="manual">
                        <label for="mail_contant">Email Body</label><br>
                        <?php
                        $content = "add some text";
                        $editor_id = 'bmsi_mail_box';
                        $editor_name = 'bmsi_mail_box_content';
                        $settings = array(
                            'textarea_rows' => 8,
                            'media_buttons' => true,
                            'quicktags' => array('buttons' => 'h1,h2,h3,h4,h5,h6,strong,em,link,block,del,ins,img,ul,ol,li,code'),
                            'tinymce' => true,
                            'editor_name' => $editor_name,
                        );
                        $headers = array('Content-Type: text/html; charset=UTF-8');
                        wp_editor($content, $editor_id, $settings, $headers);
                        ?>
                        <span class="bmsi_mail_box_error error"></span>
                    </div>
                    <div class="text-end">
                    <button type="submit" name="submit"
                        class="add-button fs-16 border-0 <?php echo !empty($type) ? esc_html($type) : 'btn-send-mail' ?>"
                        id="submit"><span class="btn-txt">Send</span><i class="fa-solid fa-spinner spinn"></i></button>

                </div>

                </form>
                <form action="#" method="POST" class="use_template_form send_mail_form" style="display: none;">
                    <div class="subjectAndemailfrom">
                        <div class="firstAndLastName">
                            <div class="d-flex">
                            <label for="Select_template">Select Template</label>
                            <?php
                            $args = array(
                                'post_type' => 'BMSI_Pro_Templates',
                                'post_status' => 'publish',
                                'posts_per_page' => -1,
                            );

                            $query = new \WP_Query($args);


                            if ($query->have_posts()) {
                                $disable = true;
                                ?>

                                <select name="template_name" id="template_name" class="input-groups">
                                    <?php
                                    while ($query->have_posts()) {
                                        $query->the_post();
                                        $template_name = get_the_title();
                                        ?>
                                        <option name="template_name" value="<?php echo esc_attr($template_name); ?>">
                                            <?php echo esc_attr($template_name); ?>
                                        </option>
                                    <?php } ?>
                                    </select>
                                    <?php 
                                    wp_reset_postdata();
                                    }else{ ?>
                                        <p class="mb-0 error">*No Template Found.</p>
                                        <a href="<?php printf(esc_html('%sadmin.php?page=create_template&edit=false'), esc_html($admin_url)); ?>" class="fz-14 border-0 text-decoration-none theme-color w-30">Add New Template</a>

                                <?php } ?>
                            </div>

                        </div>             
                    </div>
                    <div class="text-end">
                    <button type="submit" name="submit" <?php echo ($disable == false) ? "disabled" : "" ?>
                        class="add-button mt-4 fs-16 border-0 <?php echo !empty($type) ? esc_html($type) : 'btn-send-mail' ?>"
                        id="submit"><span class="btn-txt">Send</span><i class="fa-solid fa-spinner spinn"></i></button>

                </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
        <?php }
        ?>

<?php include "BMSIFooter.php" ?>

</body>

</html>