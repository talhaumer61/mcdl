<?php
include_once (LMS_VIEW.'/query.php');
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="'.$profileIcon.' align-bottom me-1"></i>'.(LMS_EDIT_ID ? 'Edit ': '').moduleName(false).' '.moduleName(LMS_VIEW).'</h5>
            <div class="flex-shrink-0">';
                if (empty(LMS_EDIT_ID)) {
                    echo'<a href="'.moduleName().'.php?edit_id='.cleanvars($_SESSION['userlogininfo']['LOGINIDA']).'&'.$redirection.'" class="btn btn-info btn-xs"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(false).'</a>';
                }
                echo'
                <a class="btn btn-info btn-xs" onclick="showAjaxModalZoom(\'include/modals/'.moduleName().'/'.LMS_VIEW.'/password_change.php?'.$redirection.'\');"><i class="ri-edit-circle-line align-bottom me-1"></i>Change Password</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12" >';
                if (LMS_EDIT_ID) {
                    include(LMS_VIEW.'/edit.php');
                } else {
                    include(LMS_VIEW.'/list.php');
                }
                echo'
            </div>
        </div>
    </div>
</div>';
?>