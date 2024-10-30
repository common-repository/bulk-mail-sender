
<!-- version 1.2.0 -->

<?php

if (!defined('ABSPATH'))
    exit;
$admin_url = get_admin_url();
    global $wpdb;
    $tbl_contact_tag = $wpdb->prefix . "bmsi_contact_tag";
    
        if(!empty($_GET['tid'])){
            $tid=!empty($_GET['tid']) ? sanitize_text_field($_GET['tid']) : "";
            $sql = $wpdb->prepare("SELECT * FROM %1s WHERE id=%d",$tbl_contact_tag ,$tid);
            $results = $wpdb->get_results($sql);
            foreach( $results as $result ) {
                $data['contact_tag'] = $result->contact_tag;
            }
        }
?>

<?php include "BMSIHeader.php"; ?>

<html>
    <head></head>
    <body>
    <div class="col-lg-10">
        <div class="main">
            <form action="#" method="POST" class="form_data">
                <div id="form_data">
                    <input type="hidden" id="create_tag_nonce" name="create_tag_nonce"
                        value="<?php echo esc_attr($nonce);?>">
                    <div class="pt-4"></div>
                    <div class="form-contant d-flex flex-column">        
                        <div
                            class="add-new-users d-lg-flex d-md-flex d-sm-flex d-block justify-content-between flex-row align-items-center">
                                <div class="title">
                                    <h1 class="fw-bold" style="font-size: 40px;"><?php !empty($tid) ? printf("Update Contact Tag") : printf("Add Contact Tag")?></h1>
                                </div>
                            <div
                                class="d-flex justify-content-lg-between justify-content-md-between justify-content-sm-between justify-content-start flex-row">
                                <a class="add-button border-0 btn-back"
                                    href="<?php printf(esc_html('%sadmin.php?page=contact_tag', 'bulk-mail'), esc_html($admin_url)); ?>">Back</a>
                                    <button type="submit" name="submit-sub" id="submit"
                                        class="add-tag-button fs-16menu border-0 btn_add_tag btn-save"><span
                                            class="btn-txt"><?php !empty($tid) ? printf("Update") : printf("Save")?></span><i class="fa-solid fa-spinner spinn"></i></button>
                            </div>
                        </div>
                        <div class="firstAndLastName">
                            <div>
                                <label for="contactTag">Contact Tag</label>
                                <input type="text" class="input-groups" name="contactTag" id="contactTag"
                                    placeholder="Enter Contact Tag" value="<?php if (!empty($data['contact_tag'])) {
                                        printf(esc_html__('%s', 'bulk-mail'), esc_html($data['contact_tag']));
                                    } ?>" required>
                            </div>
                            <input type="text" id="tid" hidden value="<?php if (!empty($tid)) {
                                        printf(esc_html__('%d', 'bulk-mail'),esc_html($tid));
                                    } ?>">
                        </div>
                        <div class="align-items-end save-button">
                            <button type="submit" name="submit-sub" id="submit"
                                class="add-tag-button fs-16menu border-0 btn_add_tag btn-save" style="margin-top: 80px;"><span
                                    class="btn-txt"><?php !empty($tid) ? printf("Update Contact Tag") : printf("Add Contact Tag")?></span><i class="fa-solid fa-spinner spinn"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php include "BMSIFooter.php"; ?>
    </body>
</html>



