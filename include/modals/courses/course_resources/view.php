<?php
require_once("../../../dbsetting/lms_vars_config.php");
require_once("../../../dbsetting/classdbconection.php");
require_once("../../../functions/functions.php");
$dblms = new dblms();

$condition = array(
                     'select'       =>  '*'
                    ,'where'        =>  array(
                                                 'is_deleted'   => 0
                                                ,'id'           => cleanvars($_GET['view_id'])
                                            )
                    ,'return_type'  =>  'single'
);
$row = $dblms->getRows(COURSES_DOWNLOADS, $condition);
echo'
<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">'.moduleName(LMS_VIEW).' Detail</h5>
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
                            <td>'.get_status($row['status']).'</td>
                        </tr>
                        <tr>
                            <td class="fw-medium" scope="row">Type</td>
                            <td>'.get_CourseResources($row['id_type']).'</td>
                        </tr>';
                        if($row['open_with']){
                            echo'                                
                            <tr>
                                <td class="fw-medium" scope="row">Open With</td>
                                <td>'.$row['open_with'].'</td>
                            </tr>';
                        }
                        echo'
                    </tbody>
                </table>
            </div>
            <hr>       
            <h6 class="text-muted text-uppercase fw-semibold"><b>File Name:</b></h6>
            <div>'.$row['file_name'].'</div>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Detail:</b></h6>
            <div>'.$row['detail'].'</div>
        </div>
    </div>
</div>';
?>