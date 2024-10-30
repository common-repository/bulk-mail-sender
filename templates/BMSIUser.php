<?php

if (!defined('ABSPATH'))
    exit;
$admin_url = get_admin_url();
?>

<?php
global $wpdb;
$tbl_contact_category = $wpdb->prefix . "bmsi_contact_category";
$sql = $wpdb->prepare("SELECT * FROM %1s", $tbl_contact_category);
$results = $wpdb->get_results($sql, ARRAY_A);
?>

<?php include "BMSIHeader.php"; ?>

<div class="col-xl-10 col-lg-11 col-md-11">
    <div class="pt-4"></div>

    <?php if (is_bulk_mail_pro_activated()) {
        if (get_option('BMIS_Pro_activated')) { ?>
            <div class="ps-70 column-space">

                <div class="d-flex align-items-center gap-3 title">
                    <h1 class="fw-bold" style="font-size: 40px;">Contacts</h1>
                    <a href="<?php printf(esc_html('%sadmin.php?page=create_user', 'bulk-mail'), esc_html($admin_url)); ?>"
                        class="add-button border-0 text-decoration-none text-black">Add New Contact</a>
                </div>

                <div class="wrap">
                    <input type="hidden" name="all_users" value="all_user_nonce" />
                    <div class="table-wrapper">
                        <?php global $wp_roles;
                        $roles = $wp_roles->get_names();
                        ?>
                        <select name="role_filter" id="role_filter">
                            <option name="role_filter" value="All">All roles
                            </option>
                            <?php
                            foreach ($roles as $key => $role) { ?>
                                <option name="role_filter" value="<?php echo esc_attr($key); ?>">
                                    <?php echo esc_attr($role); ?>
                                </option>
                            <?php } ?>
                        </select>
                        <table id="get_all_users" class="display">
                            <thead>
                                <tr>
                                    <th scope="col" style="text-align:center;">
                                        <?php echo esc_html__("Id", 'bulk-mail'); ?>
                                    </th>
                                    <th scope="col" style="text-align:left;">
                                        <?php echo esc_html__("Name", 'bulk-mail'); ?>
                                    </th>
                                    <th scope="col" style="text-align:left;">
                                        <?php echo esc_html__("Email", 'bulk-mail'); ?>
                                    </th>
                                    <th scope="col" style="text-align:left;">
                                        <?php echo esc_html__("Mobile", 'bulk-mail'); ?>
                                    </th>
                                    <th scope="col" style="text-align:left;">
                                        <?php echo esc_html__("Role", 'bulk-mail'); ?>
                                    </th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog mt-5">
                        <div class="modal-content fs-14">
                            <div class="modal-header">
                                <h5 class="modal-title fs-14 py-2 px-1" id="exampleModalLabel">User Information</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="id">
                                    Id :- <span class="user_id"></span>
                                </div><br>
                                <div class="name">
                                    Name :- <span class="user_name"></span>
                                </div><br>
                                <div class="email">
                                    Email :- <span class="user_email"></span>
                                </div><br>
                                <div class="mobile">
                                    Mobile :- <span class="user_mobile"></span>
                                </div><br>
                                <div class="role">
                                    Role :- <span class="user_role"></span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="fs-14 add-button text-black border-0"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <?php } else { ?>
            <div class="user-bg-image d-flex justify-content-center align-items-center">
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
        <div class="ps-70 column-space">
            <div class="d-flex align-items-center gap-3 title">
                <h1 class="fw-bold" style="font-size: 40px;">Contacts</h1>
                <a href="<?php printf(esc_html('%sadmin.php?page=create_user', 'bulk-mail'), esc_html($admin_url)); ?>"
                    class="add-button border-0 text-decoration-none text-black">Add New Contact</a>
                <a href="#" class="add-button border-0 text-decoration-none text-black" data-bs-toggle="modal" data-bs-target="#importModal">Import Contacts</a>
            </div>
            <div class="wrap">
                <input type="hidden" name="all_users" value="all_user_nonce" />
                <div class="table-wrapper">
                    <table id="get_all_users" class="display">
                        <thead>
                            <tr>
                                <th scope="col" style="text-align:center;">
                                    <?php echo esc_html__("Id", 'bulk-mail'); ?>
                                </th>
                                <th scope="col" style="text-align:left;">
                                    <?php echo esc_html__("Name", 'bulk-mail'); ?>
                                </th>
                                <th scope="col" style="text-align:left;">
                                    <?php echo esc_html__("Email", 'bulk-mail'); ?>
                                </th>
                                <th scope="col" style="text-align:left;">
                                    <?php echo esc_html__("Contact Category", 'bulk-mail'); ?>
                                </th>
                                <th scope="col" style="text-align:left;">
                                    <?php echo esc_html__("Contact Tag", 'bulk-mail'); ?>
                                </th>
                                <th scope="col" style="text-align:left;">
                                    <?php echo esc_html__("Status", 'bulk-mail'); ?>
                                </th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog mt-5">
                    <div class="modal-content fs-14">
                        <div class="modal-header">
                            <h5 class="modal-title fs-14 py-2 px-1" id="exampleModalLabel">User Information</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="id">
                                Id :- <span class="Contact_id"></span>
                            </div><br>
                            <div class="name">
                                Name :- <span class="Contact_name"></span>
                            </div><br>
                            <div class="email">
                                Email :- <span class="Contact_email"></span>
                            </div><br>
                            <div class="Category">
                                Contact Category :- <span class="Contact_Category"></span>
                            </div><br>
                            <div class="tag">
                                Contact Tag :- <span class="Contact_tag"></span>
                            </div><br>
                            <div class="Status">
                                Status :- <span class="Contact_Status"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="fs-14 add-button text-black border-0"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                <div class="modal-dialog mt-5">
                    <div class="modal-content fs-14">
                        <div class="modal-header">
                            <h5 class="modal-title fs-14 py-2 px-1" id="importModalLabel">Import Contacts</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="importForm" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="importSheet" class="form-label">Upload Excel/CSV file</label>
                                    <input type="file" class="form-control" id="importSheet" name="importSheet" accept=".csv, .xls, .xlsx" required>
                                </div>
                                <div id="columnMapping" class="d-none">
                                    <h6>Map Columns to Fields</h6>
                                    <div class="mb-2">
                                        <label for="emailField" class="form-label">Email (required)</label>
                                        <select id="emailField" class="form-select" name="email_field" required>
                                            <option value="">Select Column</option>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label for="fnameField" class="form-label">First Name (required)</label>
                                        <select id="fnameField" class="form-select" name="fname_field" required>
                                            <option value="">Select Column</option>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label for="lnameField" class="form-label">Last Name (required)</label>
                                        <select id="lnameField" class="form-select" name="lname_field" required>
                                            <option value="">Select Column</option>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label for="categoryField" class="form-label">Contact Category (required)</label>
                                        <select id="categoryField" class="form-select" name="category_field" required>
                                            <option value="">Select Column</option>
                                        </select>
                                    </div>                                    
                                    <!-- Add more fields as needed -->
                                </div>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="fs-14 add-button text-black border-0" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    <?php } ?>

    <?php include "BMSIFooter.php"; ?>


    </html>