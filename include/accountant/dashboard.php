<?php
include 'dashboard/query.php';
echo'
<title>'.moduleName(false).' - '.TITLE_HEADER.'</title>
<div class="page-content">
    <div class="container-fluid mb-5">
        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Dashboard</h4>
                        <p class="text-muted mb-0">'.welcome(date('H')).', '.$_SESSION['userlogininfo']['LOGINNAME'].'!</p>
                    </div>
                    <div class="mt-3 mt-lg-0">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                <li class="breadcrumb-item"><a href="'.moduleName().'.php" class="text-primary">'.moduleName(false).'</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-9">';
                include 'dashboard/counters.php';
                include 'dashboard/course_enrollment_metrics.php';
                include 'dashboard/revenue.php';
                include 'dashboard/refferrals.php';
                echo'
            </div>
            <div class="col-lg-3">';
                include 'dashboard/right_bar.php';
                echo'
            </div>
        </div>
    </div>
</div>';
?>