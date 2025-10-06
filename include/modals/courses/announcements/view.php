<?php
include "../../../dbsetting/lms_vars_config.php";
include "../../../dbsetting/classdbconection.php";
include "../../../functions/functions.php";
$dblms = new dblms();
include "../../../functions/login_func.php";
checkCpanelLMSALogin();

include "../../../db.classes/courses.php";
$coursecls = new courses();
$result    = $coursecls->get_courseannouncementdetail($_GET['view_id']);

echo '
<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">'.moduleName(LMS_VIEW).' Detail</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="overflow-hidden">
    <div data-simplebar style="height: calc(100vh - 100px);">
        <div class="offcanvas-body">
            <h6 class="text-muted text-uppercase fw-semibold"><b>Topic:</b></h6>
            <p>'.$result['announcement_topic'].'</p>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Lectures:</b></h6>
            <div>';
                $array = explode(',',$result['id_lecture']);
                foreach ($array as $key => $value) {
                    echo'<span class="badge bg-secondary rounded-pill me-2 mb-2">'.get_LessonLectures($value).'</span>';
                }
                echo'
            </div>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Lesson Detail:</b></h6>
            <div>'.(!empty($result['announcement_detail']) ? html_entity_decode(html_entity_decode($result['announcement_detail'])) : 'Detail not added').'</div>
        </div>
    </div>
</div>';
