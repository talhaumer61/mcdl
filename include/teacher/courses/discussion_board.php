<?php
include_once (LMS_VIEW.'/query.php');
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="'.$iconPg.' align-bottom me-1"></i>'.moduleName(LMS_VIEW).'</h5>';
            if(!LMS_EDIT_ID && !isset($_GET['add'])){
                echo'
                <div class="flex-shrink-0">
                    <a class="btn btn-primary btn-xs" href="?add&'.$redirection.'"><i class="ri-add-circle-line align-bottom me-1"></i>'.moduleName(LMS_VIEW).'</a>
                </div>';
            }
            echo'
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12" >';
                if (isset($_GET['add'])) {
                    include_once (LMS_VIEW.'/add.php');
                } else if (!empty(LMS_EDIT_ID)) {
                    include_once (LMS_VIEW.'/edit.php');
                } else {
                    include_once (LMS_VIEW.'/list.php');
                }
                echo'
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">    
    CKEDITOR.replace(\'ckeditor1\');
</script>';
?>