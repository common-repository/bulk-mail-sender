<?php

if (!defined('ABSPATH'))
    exit;
?>

<?php

include "BMSIHeader.php";

?>
<div class="col-xl-10 col-lg-11 col-md-11">
    <div class="pt-4"></div>
    <div class="ps-70 column-space">
        <div class="title">

            <div class="d-flex align-items-center gap-3">
                <h1 class="fw-bold" style="font-size: 40px;">Contact Category</h1>
                <a href="<?php printf(esc_html('%sadmin.php?page=file_contact_category', 'bulk-mail-sender'),esc_html($admin_url)); ?>"
                    class="add-button border-0 text-decoration-none text-black">Add Category</a>
            </div>

            <div class="wrap">
                <input type="hidden" name="all_users" value="all_user_nonce">
                <div class="table-wrapper">
                    <div class="dt-layout-cell ">
                        <table id="cont_category_form" class="display">

                            <thead>

                                <tr scope="row">
                                    <th scope="col">
                                        <?php echo esc_html__("ID", 'bulk-mail'); ?>
                                    </th>
                                    <th scope="col">
                                        <?php echo esc_html__("Category  Name", 'bulk-mail'); ?>
                                    </th>
                                    <th scope="col">
                                        <?php echo esc_html__("Parent Category", 'bulk-mail'); ?>
                                    </th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php include "BMSIFooter.php"; ?>