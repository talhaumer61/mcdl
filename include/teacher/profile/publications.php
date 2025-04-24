<?php
include_once (LMS_VIEW.'/query.php');
echo'
<div class="card mb-5">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="'.$profileIcon.' align-bottom me-1"></i>'.moduleName(LMS_VIEW).'</h5>';
            if(!isset($_GET['add']) && !LMS_EDIT_ID){
                echo '
                <div class="flex-shrink-0">
                    <a class="btn btn-primary btn-xs" href="profile.php?view='.cleanvars($_GET['view']).'&add"><i class="ri-add-circle-line align-bottom me-1"></i>'.moduleName(LMS_VIEW).'</a>
                </div>';
            }
            echo '
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12" draggable="true">';
                if (isset($_GET['add'])) {
                    include_once (LMS_VIEW.'/add.php');
                } else if (LMS_EDIT_ID) {
                    include_once (LMS_VIEW.'/edit.php');
                } else {
                    include_once (LMS_VIEW.'/list.php');
                }
                echo'
            </div>
        </div>
    </div>
</div>';
include_once (LMS_VIEW.'/script.php');
?>