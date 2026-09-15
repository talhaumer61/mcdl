<?php
include "../../../dbsetting/lms_vars_config.php";
include "../../../dbsetting/classdbconection.php";
include "../../../functions/functions.php";
$dblms = new dblms();
include "../../../functions/login_func.php";
checkCpanelLMSALogin();

require_once("../../../db.classes/courses.php");
$coursecls = new courses();

// lesson information
$result     = $coursecls->get_courselessondetail($_GET['view_id']);
// COURSES lesson DOWNLOADS
$downloads  = $coursecls->get_lessondownloads($_GET['view_id']);



echo '
<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">Lesson Plan Detail</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="overflow-hidden">
    <div data-simplebar style="height: calc(100vh - 100px);">
        <div class="offcanvas-body">
            <h6 class="text-muted text-uppercase fw-semibold"><b>Topic:</b></h6>
            <p>'.$result['lesson_topic'].'</p>
            <hr>';
            if(!empty($downloads)){
                echo'<h6 class="text-muted text-uppercase fw-semibold"><b>Lesson Resource:</b></h6>';
                foreach ($downloads as $key => $value) {
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
            <div>'.(!empty($result['lesson_detail']) ? html_entity_decode(html_entity_decode($result['lesson_detail'])) : 'Detail not added').'</div>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Reading Detail:</b></h6>
            <div>'.(!empty($result['lesson_reading_detail']) ? html_entity_decode(html_entity_decode($result['lesson_reading_detail'])) : 'Detail not added').'</div>
        </div>
    </div>
</div>';
