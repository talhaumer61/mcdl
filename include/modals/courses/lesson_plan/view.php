<?php
include "../../../dbsetting/lms_vars_config.php";
include "../../../dbsetting/classdbconection.php";
include "../../../functions/functions.php";
$dblms = new dblms();
include "../../../functions/login_func.php";
checkCpanelLMSALogin();
$condition = array ( 
                         'select' 	    =>  'cl.lesson_topic, cl.lesson_detail, cl.lesson_video_code, cl.lesson_reading_detail'
                        ,'where' 	    =>  array(  
                                                     'cl.is_deleted'           => 0
                                                    ,'cl.id_session'           => cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    ,'cl.id_campus'            => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                    ,'cl.id_teacher'           => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                    ,'cl.lesson_id'            => cleanvars($_GET['view_id'])
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$row = $dblms->getRows(COURSES_LESSONS.' cl', $condition);

// COURSES DOWNLOADS
$condition = array ( 
                         'select' 	    =>  'id, file_name, url, file'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           =>  0
                                                    ,'id_lesson'			=>  cleanvars($_GET['view_id'])
                                                )
                        ,'return_type'  =>  'all' 
); 
$COURSES_DOWNLOADS = $dblms->getRows(COURSES_DOWNLOADS, $condition);
echo '
<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">Lesson Plan Detail</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="overflow-hidden">
    <div data-simplebar style="height: calc(100vh - 100px);">
        <div class="offcanvas-body">
            <h6 class="text-muted text-uppercase fw-semibold"><b>Topic:</b></h6>
            <p>'.$row['lesson_topic'].'</p>
            <hr>';
            if(!empty($COURSES_DOWNLOADS)){
                echo'<h6 class="text-muted text-uppercase fw-semibold"><b>Lesson Resource:</b></h6>';
                foreach ($COURSES_DOWNLOADS as $key => $value) {
                    $file_info  = pathinfo($value['file']);
                    $extension  = $file_info['extension'];
                    echo'
                    <div class="mb-2">
                        <a title="Click to Download" href="'.SITE_URL.'uploads/files/lesson_plan/'.$value['file'].'" target="_blank" download><i class="ri-download-2-fill me-1"></i>'.$value['file_name'].'</a>
                        <span class="badge bg-success">'.$extension.'</span>
                    </div>';
                }
                echo'<hr>';
            }
            echo'
            <h6 class="text-muted text-uppercase fw-semibold"><b>Lesson Detail:</b></h6>
            <div>'.(!empty($row['lesson_detail']) ? html_entity_decode(html_entity_decode($row['lesson_detail'])) : 'Detail not added').'</div>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Reading Detail:</b></h6>
            <div>'.(!empty($row['lesson_reading_detail']) ? html_entity_decode(html_entity_decode($row['lesson_reading_detail'])) : 'Detail not added').'</div>
        </div>
    </div>
</div>';
?>