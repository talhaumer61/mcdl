<?php 
require_once ("../../../dbsetting/lms_vars_config.php");
require_once ("../../../dbsetting/classdbconection.php");
require_once ("../../../functions/functions.php");
$dblms = new dblms();

$condition = array(
                     'select'       =>  'id, embedcode'
                    ,'where'        =>  array(
                                                 'is_deleted'   => 0
                                                ,'id'           => cleanvars($_GET['view_id'])
                                            )
                    ,'return_type'  =>  'single'
);
$row = $dblms->getRows(COURSES_DOWNLOADS, $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-danger p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-youtube-fill align-bottom me-1"></i>View Video</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="resource_id" value="'.$row['id'].'"/>
            <input type="hidden" name="id" value="'.$_GET['id'].'"/>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.youtube.com/embed/'.$row['embedcode'].'" title="YouTube video" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>