<?php
include_once (LMS_VIEW.'/query.php');
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="'.$iconPg.' align-bottom me-1"></i>'.moduleName(LMS_VIEW).'</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12" >';
                include_once (LMS_VIEW.'/list.php');
                echo'
            </div>
        </div>
    </div>
</div>';
?>