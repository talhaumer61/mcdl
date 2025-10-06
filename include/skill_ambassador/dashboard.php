<?php
if($_SESSION['userlogininfo']['LOGINTYPE'] == 1 && LMS_VIEW == 'dashboard' && !empty(LMS_EDIT_ID)){
    $_SESSION['userlogininfo']['LOGINORGANIZATIONID'] = get_dataHashingOnlyExp(LMS_EDIT_ID, false);
    $redirect = moduleName().'.php?view='.LMS_VIEW.'&edit_id='.LMS_EDIT_ID;
} else {
    $redirect = moduleName().'.php';
}
include 'dashboard/query.php';
echo'
<title>'.moduleName(false).' - '.TITLE_HEADER.'</title>
<div class="page-content">
    <div class="container-fluid mb-5">
        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Dashboard '.(isset($_GET['name']) ? '<span class="text-primary">('.$_GET['name'].')</span>' : '').'</h4>
                        <p class="text-muted mb-0">'.welcome(date('H')).', '.$_SESSION['userlogininfo']['LOGINNAME'].'!</p>
                    </div>
                    <div class="mt-3 mt-lg-0">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>';
                                if(!empty(LMS_EDIT_ID)){
                                    echo'<li class="breadcrumb-item"><a href="skill_ambassador.php" class="text-primary">Skill Ambassador</a></li>';
                                }
                                echo'
                                <li class="breadcrumb-item"><a href="'.$redirect.'" class="text-primary">'.moduleName(false).'</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-12">';            
                include 'dashboard/counters.php';
                include 'dashboard/course_enrollment_metrics.php';
                echo'
            </div>
        </div>
    </div>
</div>';
?>