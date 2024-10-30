<?php

if (!defined('ABSPATH'))
    exit;
    global $wpdb;
    $tbl_contact = $wpdb->prefix . "bmsi_contact";
    
        if(!empty($_GET['cid'])){
            $cid=!empty($_GET['cid']) ? sanitize_text_field($_GET['cid']) : "";
            $sql = $wpdb->prepare("SELECT * FROM %1s WHERE id=%d", $tbl_contact, $cid);
            $results = $wpdb->get_results($sql);
            foreach( $results as $result ) {
                $data['email'] = $result->email;
                $data['contact_category'] = $result->contact_category;
                $data['contact_tag'] = $result->contact_tag;
                $data['status'] = $result->status;
                $data['fname'] = $result->fname;
                $data['lname'] = $result->lname;
                $data['job_title'] = $result->job_title;
                $data['company'] = $result->company;
                $data['birth_date'] = $result->birth_date;
                $data['anniversary'] = $result->anniversary;
                $tag_id_array = $data['contact_tag'];
                $tag_id_decode = stripslashes(html_entity_decode($tag_id_array));
                $tag_ids=json_decode($tag_id_decode,true);
            }
        }
?>
<?php
//for display category in contact category
global $wpdb;
$tbl_contact_category = $wpdb->prefix . "bmsi_contact_category";
$sql = $wpdb->prepare("SELECT * FROM %1s", $tbl_contact_category);
$results = $wpdb->get_results($sql, ARRAY_A);
?>

<?php 
// for display tag in category tag
global $wpdb;
$tbl_contact_tag = $wpdb->prefix . "bmsi_contact_tag";
$sql = $wpdb->prepare("SELECT * FROM %1s", $tbl_contact_tag);
$responce = $wpdb->get_results($sql, ARRAY_A);
?>

<?php

include "BMSIHeader.php";

?>


<div class="col-lg-10">
    <div class="main">
        <?php
        // Output the nonce field in your form
        $nonce = wp_create_nonce('create_user_nonce');
        ?>

        <?php if (is_bulk_mail_pro_activated()) {
            if (get_option('BMIS_Pro_activated')) { ?>

                <form action="#" method="POST" class="form_data">
                    <div id="form_data">
                        <input type="hidden" id="create_user_nonce" name="create_user_nonce"
                            value="<?php echo esc_attr($nonce); ?>" />
                        <div class="pt-4"></div>
                        <div class="form-contant d-flex flex-column">
                            <div
                                class="add-new-users d-lg-flex d-md-flex d-sm-flex d-block justify-content-between flex-row align-items-center">
                                <div class="title">
                                    <h1 class="fw-bold" style="font-size: 40px;">Add New Contact</h1>
                                </div>
                                <div
                                    class="d-flex justify-content-lg-between justify-content-md-between justify-content-sm-between justify-content-start flex-row">
                                    <a class="add-button border-0 btn-back"
                                        href="<?php printf(esc_html('%sadmin.php?page=users', 'bulk-mail'), esc_html($admin_url)); ?>">Back</a>
                                    <button type="submit" name="submit-sub" id="submit"
                                        class="add-button fs-16menu border-0 btn_add_user btn-save"><span
                                            class="btn-txt">Save</span><i class="fa-solid fa-spinner spinn"></i></button>
                                </div>
                            </div>
                            <div class="firstAndLastName">
                                <div>
                                    <label for="fname">First Name</label>
                                    <input type="text" class="input-groups" name="fname" id="fname"
                                        placeholder="Enter User First Name" value="<?php if (!empty($user_names[0])) {
                                            printf(esc_html__('%s.', 'bulk-mail'), esc_html($edit_data[0]->email));
                                        } ?>" required>
                                </div>
                                <div>
                                    <label for="lname" class="lname">Last Name</label>
                                    <input type="text" class="input-groups" name="lname" id="lname"
                                        placeholder="Enter User Last Name" value="<?php if (!empty($user_names[1])) {
                                            printf(esc_html__('%s.', 'bulk-mail'), esc_html($user_names[1]));
                                        } ?>" required>
                                </div>
                            </div>
                            <div class="firstAndLastName">
                                <div>
                                    <label for="email">Email</label>
                                    <input type="email" class="input-groups" name="email" id="email"
                                        placeholder="Enter User Email" value="<?php if (!empty($edit_data[0]->email)) {
                                            printf(esc_html__('%s.', 'bulk-mail'), esc_html($edit_data[0]->email));
                                        } ?>" required>
                                </div>
                                <div>
                                    <label for="mob">Mobile No</label>
                                    <input type="number" class="input-groups" name="mob" id="mob"
                                        placeholder="Enter User Mobile Number" value="<?php if (!empty($edit_data[0]->mobile)) {
                                            printf(esc_html__('%s.', 'bulk-mail'), esc_html($edit_data[0]->mobile));
                                        } ?>" required>
                                </div>
                            </div>

                            <div class="firstAndLastName">
                                <?php
                                $option = get_option("BMSI_User_Options");
                                if (!empty($option["pass"]) && $option["pass"] == "false") {
                                    ?>
                                    <div>

                                        <label for="pass">Password</label>
                                        <input type="text" class="input-groups" name="pass" id="pass"
                                            placeholder="Enter User Password" value="<?php if (!empty($edit_data[0]->pass)) {
                                                printf(esc_html__('%s.', 'bulk-mail'), esc_html($edit_data[0]->pass));
                                            } ?>" required>
                                    </div>

                                <?php } ?>
                                <div>
                                    <label for="role">User Role</label>
                                    <?php
                                    global $wp_roles;
                                    $roles = $wp_roles->get_names();
                                    ?>
                                    <select name="role" id="role" class="input-groups">
                                        <?php
                                        foreach ($roles as $key => $role) { ?>
                                            <option name="roles" value="<?php echo esc_attr($key); ?>" >
                                                <?php echo esc_attr($role); ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                </div>

                            </div>
                            <div class="align-items-end save-button">
                                <button type="submit" name="submit-sub" id="submit"
                                    class="add-button fs-16menu border-0 btn_add_user btn-save"><span
                                        class="btn-txt">Save</span><i class="fa-solid fa-spinner spinn"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } else { ?>
                <div class="creatuser-bg-image d-flex justify-content-center align-items-center">
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
                            <a
                                href="<?php printf(esc_html('%sadmin.php?page=settings', 'bulk-mail-sender-pro'), esc_html($admin_url)); ?>">click
                                here</a>
                        </div>
                    </div>
                </div>
            <?php }
        } else { ?>

            <form action="#" method="POST" class="form_data">
                <div id="form_data">
                    <input type="hidden" id="create_user_nonce" name="create_user_nonce"
                        value="<?php echo esc_attr($nonce);?>" />
                    <div class="pt-4"></div>
                    <div class="form-contant d-flex flex-column">        
                        <!-- version 1.2.0-->
                        <div
                            class="add-new-users d-lg-flex d-md-flex d-sm-flex d-block justify-content-between flex-row align-items-center">
                                    <div class="title">
                                        <h1 class="fw-bold" style="font-size: 40px;"><?php !empty($cid) ? printf("Update Contact") : printf("Add New Contact")?></h1>
                                    </div>
                            <div
                                class="d-flex justify-content-lg-between justify-content-md-between justify-content-sm-between justify-content-start flex-row">
                                <a class="add-button border-0 btn-back"
                                    href="<?php printf(esc_html('%sadmin.php?page=users', 'bulk-mail'), esc_html($admin_url)); ?>">Back</a>
                                    <div class="align-items-end save-button">
                                        <button type="submit" name="submit-sub" id="submit"
                                            class="add-button fs-16menu border-0 btn_add_user btn-save"><span
                                            class="btn-txt"><?php !empty($cid) ? printf("Update") : printf("Save")?></span><i class="fa-solid fa-spinner spinn"></i></button>
                                    </div>
                            </div>
                        </div>
                        <input type="text" id="cid" hidden value="<?php if (!empty($cid)) {
                                    printf(esc_html__('%d', 'bulk-mail'),esc_html($cid));
                                } ?>">
                        <div>
                            <label for="email">Add Email</label>   
                            <input type="email" class="input-groups" name="email" id="email"
                                placeholder="Enter User Email" value="<?php if (!empty($data['email'])) {
                                    printf(esc_html__('%s', 'bulk-mail'), esc_html($data['email']));
                                } ?>" required>
                        </div>
                        <div class="contactinfo">   
                            <div>
                                <label for="Category">Contact Category</label>    
                                <select name="category" id="category" class="input-groups">
                                    <option value="">Select Contact Category</option>
                                    <?php
                                    foreach ($results as $result) { ?>
                                        <option name="" value="<?php echo esc_html__($result['id']); ?>"
                                        <?php if (!empty( $data['contact_category']) && $data['contact_category']==$result['id']) {
                                                printf('selected');
                                            } ?>><?php echo esc_html__($result['category_name']); ?>
                                        </option>
                                    <?php } ?> 
                                </select>
                            </div>
                            <div>
                                <label for="tag">Contact Tag</label>
                                <select class="input-groups" id="contact_category_tag" multiple="multiple" name="tag_name[]">
                                  <option></option>
                                  <?php
                                    foreach ($responce as $tag) { ?>
                                        <option name="" value="<?php echo esc_html__($tag['id']); ?>" 
                                            <?php if(!empty( $tag_ids) && in_array(esc_html__($tag['id']),$tag_ids)){
                                                printf('selected');}?>><?php echo esc_html__($tag['contact_tag']); ?>
                                        </option>
                                    <?php } ?> 
                                </select> 
                            </div>
                            <div>
                                <label for="status">Status</label>  
                                <select name="status" id="status" class="input-groups">
                                    <option value="1" <?php if (!empty($data['status'])==1) {
                                    printf('selected');
                                } ?>>Active</option>
                                    <option value="0" <?php if (!empty($data['status'])==0) {
                                    printf('selected');
                                } ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="firstAndLastName">
                            <div>
                                <label for="fname">First Name</label>
                                <input type="text" class="input-groups" name="fname" id="fname"
                                    placeholder="Enter User First Name" value="<?php if (!empty($data['fname'])) {
                                        printf(esc_html__('%s', 'bulk-mail'), esc_html($data['fname']));
                                    } ?>" required>
                            </div>
                            <div>
                                <label for="lname" class="lname">Last Name</label>
                                <input type="text" class="input-groups" name="lname" id="lname"
                                    placeholder="Enter User Last Name" value="<?php if (!empty($data['lname'])) {
                                        printf(esc_html__('%s', 'bulk-mail'), esc_html($data['lname']));
                                    } ?>" required>
                            </div>
                        </div>
                        <div>
                            <label for="mob">Job Title</label>
                            <input type="text" class="input-groups" name="job" id="job"
                                placeholder="Enter Job Title" value="<?php if (!empty($data['job_title'])) {
                                    printf(esc_html__('%s', 'bulk-mail'), esc_html($data['job_title']));
                                } ?>" required>
                        </div>
                        <div>
                            <label for="mob">Company</label>
                            <input type="text" class="input-groups" name="company" id="company"
                                placeholder="Enter Company Name" value="<?php if (!empty($data['company'])) {
                                    printf(esc_html__('%s', 'bulk-mail'), esc_html($data['company']));
                                } ?>" required>
                        </div>
                        <div class="firstAndLastName">
                            <div>
                                <label for="mob">Birth Date</label>
                                <input type="date" class="input-groups" name="bdate" id="bdate"
                                        value="<?php if (!empty($data['birth_date'])) {
                                        printf(esc_html__('%s', 'bulk-mail'), esc_html($data['birth_date']));
                                        } ?>" required>
                            </div>
                            <div>
                                <label for="mob">Anniversary Date</label>
                                <input type="date" class="input-groups" name="adate" id="adate"
                                        value="<?php if (!empty($data['anniversary'])) {;
                                        printf(esc_html__('%s', 'bulk-mail'), esc_html($data['anniversary']));
                                        } ?>" required>
                            </div>
                        </div>
                            <div class="align-items-end save-button">
                            <button type="submit" name="submit-sub" id="submit"
                                class="add-button fs-16menu border-0 btn_add_user btn-save"><span
                                    class="btn-txt"><?php !empty($cid) ? printf("Update Contact") : printf("Add New Contact")?></span><i class="fa-solid fa-spinner spinn"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        <?php } ?>
    </div>
</div>

<?php include "BMSIFooter.php"; ?>

</body>

</html>



