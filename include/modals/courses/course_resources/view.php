<?php
require_once("../../../dbsetting/lms_vars_config.php");
require_once("../../../dbsetting/classdbconection.php");
require_once("../../../functions/functions.php");
$dblms = new dblms();

include "../../../db.classes/courses.php";

$coursecls = new courses();
$result    = $coursecls->get_lessondownload($_GET['view_id']);

echo'

<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">'.moduleName(LMS_VIEW).' Detail </h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="overflow-hidden">
    <div data-simplebar style="height: calc(100vh - 100px);">
        <div class="offcanvas-body"> 
            <div class="table-responsive">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="fw-medium" scope="row">Status</td>
                            <td>'.get_status($result['status']).'</td>
                        </tr>
                        <tr>
                            <td class="fw-medium" scope="row">Type</td>
                            <td>'.get_CourseResources($result['id_type']).'</td>
                        </tr>';
                        if($result['open_with']){
                            echo'                                
                            <tr>
                                <td class="fw-medium" scope="row">Open With</td>
                                <td>'.$result['open_with'].'</td>
                            </tr>';
                        }
                        echo'
                    </tbody>
                </table>
            </div>
            <hr>       
            <h6 class="text-muted text-uppercase fw-semibold"><b>File Name:</b></h6>
            <div>'.$result['file_name'].'</div>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Detail:</b></h6>
            <div>'.$result['detail'].'</div>
        </div>
    </div>
</div>';