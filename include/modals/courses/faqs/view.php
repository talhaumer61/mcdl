<?php
include "../../../dbsetting/lms_vars_config.php";
include "../../../dbsetting/classdbconection.php";
include "../../../functions/functions.php";
$dblms = new dblms();
include "../../../functions/login_func.php";
checkCpanelLMSALogin();

$condition = array ( 
                        'select' 	    =>  'question, answer, id_lesson'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           => 0
                                                    ,'id_session'           => cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    ,'id_campus'            => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                    // ,'id_teacher'           => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                    ,'id'                   => cleanvars($_GET['view_id'])
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$row = $dblms->getRows(COURSES_FAQS, $condition);


$condition = array ( 
                        'select' 	    =>  'lesson_id, lesson_topic'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           => 0
                                                )
                        ,'search_by'    =>  ' AND lesson_id IN ('.$row['id_lesson'].')'
                        ,'return_type'  =>  'all' 
                    ); 
$COURSES_LESSONS = $dblms->getRows(COURSES_LESSONS, $condition);
echo '
<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">'.moduleName(LMS_VIEW).' Detail</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="overflow-hidden">
    <div data-simplebar style="height: calc(100vh - 100px);">
        <div class="offcanvas-body">
            <h6 class="text-muted text-uppercase fw-semibold"><b>Lessons:</b></h6>
            <div>';
                $array = explode(',',$row['id_lesson']);
                foreach ($COURSES_LESSONS as $key => $value) {
                    echo'<span class="badge bg-secondary rounded-pill me-2 mb-2">'.$value['lesson_topic'].'</span>';
                }
                echo'
            </div>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Question:</b></h6>
            <div>'.(!empty($row['question']) ? html_entity_decode(html_entity_decode($row['question'])) : 'Detail not added').'</div>
            <hr>            
            <h6 class="text-muted text-uppercase fw-semibold"><b>Answer:</b></h6>
            <div>'.(!empty($row['answer']) ? html_entity_decode(html_entity_decode($row['answer'])) : 'Detail not added').'</div>
        </div>
    </div>
</div>';
?>