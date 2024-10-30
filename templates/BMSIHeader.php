<?php $option = get_option("BMSI_User_Options"); ?>

<div class="mb-4">
    <a href="#" class="text-decoration-none fs-4 fw-medium text-black">Bulk Mail Sender </a>
</div>
<style>
    :root {
        --theme-color : <?php echo (!empty($option['color'])) ? $option['color'] : "#F8D57E" ?>
    }
</style>
<div class="row">
    <div class="col-xl-2 col-lg-1 col-md-1">
        <?php include "BMSISidebar.php"; ?>
    </div>

