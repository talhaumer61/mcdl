<?php 
require_once("../../dbsetting/vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms = new dblms();
$condition = array ( 
                    'select'        =>  'm.mail_status, m.is_sent, m.mail_subject, m.mail_body, m.date_added, md.mail_sender, md.mail_reciver'
                    ,'join'         =>  'INNER JOIN '.MAIL_SEND_DETAIL.' AS md ON m.mail_id = md.id_mail'
                    ,'where' 	    =>  array( 
                                                'm.is_deleted'    => 0
                                            )
                    ,'return_type'  =>  'single' 
);
$MAIL_SEND = $dblms->getRows(MAIL_SEND .' AS m', $condition);
echo '<pre>';
print_r($MAIL_SEND);
echo '</pre>';
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i> Mail Detail</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col mb-2">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="rev_name" id="rev_name" class="form-control" required>
                </div>
            </div>
            <div class="row"> 
                <div class="col mb-2">
                    <label class="form-label">Photo</label>
                    <input type="file" name="rev_photo" id="rev_photo" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="row"> 
                <div class="col mb-2">
                    <label class="form-label">Review <span class="text-danger">*</span></label>
                    <textarea class="form-control" rows="5" name="rev_detail" required></textarea>
                </div> 
            </div>
        </div>
        <div class="modal-footer">
            <div class="hstack gap-2 justify-content-end">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
            </div>
        </div>
    </div>
</div>';
?>