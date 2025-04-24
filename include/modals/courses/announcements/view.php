<?php
include "../../../dbsetting/lms_vars_config.php";
include "../../../dbsetting/classdbconection.php";
include "../../../functions/functions.php";
$dblms = new dblms();
include "../../../functions/login_func.php";
checkCpanelLMSALogin();

$condition = array ( 
                         'select' 	    =>  'announcement_detail, announcement_topic, id_lecture'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           => 0
                                                    ,'id_session'           => cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    ,'id_campus'            => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                    // ,'id_teacher'           => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                    ,'announcement_id'      => cleanvars($_GET['view_id'])
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$row = $dblms->getRows(COURSES_ANNOUNCEMENTS, $condition);
echo '
<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">'.moduleName(LMS_VIEW).' Detail</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="overflow-hidden">
    <div data-simplebar style="height: calc(100vh - 100px);">
        <div class="offcanvas-body">
            <h6 class="text-muted text-uppercase fw-semibold"><b>Topic:</b></h6>
            <p>'.$row['announcement_topic'].'</p>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Lectures:</b></h6>
            <div>';
                $array = explode(',',$row['id_lecture']);
                foreach ($array as $key => $value) {
                    echo'<span class="badge bg-secondary rounded-pill me-2 mb-2">'.get_LessonLectures($value).'</span>';
                }
                echo'
            </div>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Lesson Detail:</b></h6>
            <div>'.(!empty($row['announcement_detail']) ? html_entity_decode(html_entity_decode($row['announcement_detail'])) : 'Detail not added').'</div>
        </div>
    </div>
</div>';
?>