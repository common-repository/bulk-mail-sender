<?php
if (!defined(constant_name: 'ABSPATH'))
    exit;
$admin_url = get_admin_url();

global $wpdb;
$tbl_contact_category = $wpdb->prefix . "bmsi_contact_category";

if (!empty($_GET['eid'])) {
    $eid = $_GET['eid'];
    $sql = $wpdb->prepare("SELECT * FROM %1s WHERE id =%d ", $tbl_contact_category, $eid);
    $results = $wpdb->get_results($sql);

    foreach ($results as $result) {
        $data['id'] = $result->id;
        $data['parent_id'] = $result->parent_id;
        $data['category_name'] = $result->category_name;
    }
}
?>
<?php
global $wpdb;
$tbl_contact_category = $wpdb->prefix . "bmsi_contact_category";
$sql = $wpdb->prepare("SELECT * FROM %1s  WHERE parent_id = %d", $tbl_contact_category, 0);
$results = $wpdb->get_results($sql, ARRAY_A);
?>

<?php

include "BMSIHeader.php";

?>
<div class="col-lg-10">
    <div class="main">
        <form class="category_form">
            <div id="form_data" for="cont_category">
                <div class="form-contant">
                    <div>
                        <h1 class="fw-bold"><?php echo !empty($_GET['eid']) ? "Update Contact Category" : "Add Contact Category"?></h1>
                    </div>
                    <div
                            class="d-flex justify-content-end flex-row">
                            <a class="add-button border-0 btn-back"
                                href="<?php printf(esc_html('%sadmin.php?page=contact_category', 'bulk-mail'), esc_html($admin_url)); ?>">Back</a>
                    </div>
                    <div id="h1_cont_cateagory">

                    </div>
                    <input type="hidden" name="all_users" id="category_id" value="
                    <?php  if (!empty($eid)) {
                        printf(esc_html__('%s', 'bulk-mail'), esc_html($eid));
                    } 
                    ?>">

                    <div class="firstAndLastName">
                        <div id="parent_category_wrapper">

                            <lable>Parent Category</lable>
                            <select name="parent_category" id="parent_category" class="input-groups">
                                <option value="">---Parent Category---</option>

                                <?php

                                foreach ($results as $result) { ?>
                                    <option name="" value="<?php echo esc_html__($result['id']); ?>">
                                        <?php echo esc_html__($result['category_name']); ?>
                                    </option>
                                <?php } ?>


                            </select>
                            <?php

                            ?>
                        </div>
                        <div>
                            <lable>Category Name</lable>

                            <input type="text" class="input-groups" placeholder="Enter category name"
                                name="category_name" id="category_name" name="category_name" value="<?php if (!empty($data['category_name'])) {
                                    printf( esc_html__('%s', 'bulk-mail'), esc_html($data['category_name']));
                                } ?>" required>
                            <div class="align-items-end save-button">
                                <button type="submit" name="update-sub" id="update_category_form"
                                    class="add-button fs-16menu border-0 btn_add_user btn-save"><p class="btn-txt m-0"><?php echo !empty($_GET['eid']) ? "Update" : "Add"?></p><i class="fa-solid fa-spinner spinn"></i></button>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </form>
    </div>
</div>
<?php include "BMSIFooter.php"; ?>