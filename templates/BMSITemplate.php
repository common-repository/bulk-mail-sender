<?php include "BMSIHeader.php"; ?>


<div class="col-xl-10 col-lg-11 col-md-12 col-sm-12 col-12 col-bg-img">

    <?php
         

        if(is_bulk_mail_pro_activated()){
            require_once (BMSI_PRO_DIR_PATH . 'templates/BMSIProTemplate.php');
        }else{
            ?>
                <div class="template-bg-image d-flex justify-content-center align-items-center">
                    <div class="dashboard-wrapper">
                        <div class="bg-color p-3 rounded-4 mx-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="55" height="55" viewBox="0 0 66 75" fill="none">
                                <path
                                    d="M58.8933 37.3734H22.9333V22.4884C22.9333 16.7711 27.5298 12.0212 33.2718 11.9635C39.0718 11.9057 43.8133 16.5979 43.8133 22.3584V24.6684C43.8133 26.5886 45.3648 28.1334 47.2933 28.1334H51.9333C53.8618 28.1334 55.4133 26.5886 55.4133 24.6684V22.3584C55.4133 10.231 45.4808 0.37016 33.3008 0.413472C21.1208 0.456785 11.3333 10.4475 11.3333 22.575V37.3734H7.85331C4.01081 37.3734 0.893311 40.4775 0.893311 44.3034V67.4033C0.893311 71.2293 4.01081 74.3333 7.85331 74.3333H58.8933C62.7358 74.3333 65.8533 71.2293 65.8533 67.4033V44.3034C65.8533 40.4775 62.7358 37.3734 58.8933 37.3734ZM39.1733 59.3184C39.1733 62.509 36.5778 65.0933 33.3733 65.0933C30.1688 65.0933 27.5733 62.509 27.5733 59.3184V52.3884C27.5733 49.1977 30.1688 46.6134 33.3733 46.6134C36.5778 46.6134 39.1733 49.1977 39.1733 52.3884V59.3184Z"
                                    fill="white" />
                            </svg>
                        </div>
                        <div class="text-center">
                            <p>Purchase Pro Version</p>
                        </div>
                    </div>
                </div>
            <?php
        }
    ?>


</div>

<?php include "BMSIFooter.php"; ?>
