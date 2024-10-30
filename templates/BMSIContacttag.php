
<?php

if (!defined('ABSPATH'))
    exit;
$admin_url = get_admin_url();
?>

<?php include "BMSIHeader.php"; ?>


<html>
<div class="col-xl-10 col-lg-11 col-md-11">
    <div class="pt-4">
        <div class="ps-70 column-space">
            <div class="d-flex align-items-center gap-3 title">
                <h1 class="fw-bold" style="font-size: 40px;">Contact Tag</h1>
                <a href="<?php printf(esc_html('%sadmin.php?page=add_contact_tag', 'bulk-mail'), esc_html($admin_url)); ?>"
                    class="add-button border-0 text-decoration-none text-black">Add Contact Tag</a>
            </div>
            <div class="wrap">
                <input type="hidden" name="all_tags" value="all_tag_nonce" />
                <div class="table-wrapper">
                    <table id="get_all_contact_tags" class="display">
                        <thead>
                            <tr>
                                <th scope="col" style="text-align:left;">
                                    <?php echo esc_html__("Id", 'bulk-mail'); ?>
                                </th>
                                <th scope="col" style="text-align:left;">
                                    <?php echo esc_html__("Contact Tag Name", 'bulk-mail'); ?>
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

<?php include "BMSIFooter.php"; ?>
</html>