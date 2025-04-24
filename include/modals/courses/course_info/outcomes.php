<?php
include "../../../dbsetting/lms_vars_config.php";
include "../../../dbsetting/classdbconection.php";
include "../../../functions/functions.php";
$dblms = new dblms();

if(isset($_GET['info_id']) && !empty($_GET['info_id'])){
    $condition = array(
                         'select'       =>  'id, outcomes'
                        ,'where'        =>  array(
                                                     'status'   => 1
                                                    ,'id_curs'  => cleanvars($_GET['id'])
                                                    ,'id'       => cleanvars($_GET['info_id'])
                                                )
                        ,'return_type'  =>  'single'
    );
    $row = $dblms->getRows(COURSES_INFO, $condition);
}

$btn = (empty($row['outcomes']) ? 'add' : 'edit');
$modal = (empty($row['outcomes']) ? 'primary' : 'info');
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-'.$modal.' p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-'.$btn.'-circle-line align-bottom me-1"></i>'.ucfirst($btn).' Outcomes</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="id" value="'.$_GET['id'].'"/>
            <input type="hidden" name="info_id" value="'.(isset($row) ? $row['id'] : '').'"/>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Learning Outcomes <span class="text-danger">*</span></label>
                        <textarea id="ckeditor1" name="outcomes" class="form-control" required>'.(isset($row) ? html_entity_decode($row['outcomes']) : '').'</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-'.$modal.' btn-sm" name="submit_outcomes"><i class="ri-'.$btn.'-circle-line align-bottom me-1"></i>'.ucfirst($btn).' Outcomes</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    CKEDITOR.replace(\'ckeditor1\');
</script>';
?>
