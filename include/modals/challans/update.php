<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
include "../../functions/functions.php";
$dblms = new dblms();

$condition = array(
                     'select'       =>  'ch.*, s.std_name'
                    ,'join'         =>  'INNER JOIN '.STUDENTS.' s ON s.std_id = ch.id_std'
                    ,'where'        =>  array(
                                                 'ch.is_deleted'    => 0
                                                ,'ch.challan_id'    => cleanvars($_GET['challan_id'])
                                            )
                    ,'order_by'     =>  'ch.challan_id ASC'
                    ,'return_type'  =>  'single'
);
$row = $dblms->getRows(CHALLANS.' ch', $condition);

$title  = ($_GET['status'] == '1' ? 'Paid' : 'Unpaid');
$modal  = ($_GET['status'] == '1' ? 'success' : 'danger');
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-'.$modal.' p-3">
            <h5 class="modal-title"><i class="ri-send-plane-fill align-bottom me-1"></i>Update as '.$title.'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="challan_id" value="'.$row['challan_id'].'" />
            <input type="hidden" name="challan_no" value="'.$row['challan_no'].'" />
            <input type="hidden" name="id_std" value="'.$row['id_std'].'" />
            <input type="hidden" name="std_name" value="'.$row['std_name'].'" />
            <input type="hidden" name="status" value="'.$_GET['status'].'" />
            <input type="hidden" name="total_amount" value="'.$row['total_amount'].'" />
            <input type="hidden" name="id_enroll" value="'.$row['id_enroll'].'" />
            <div class="modal-body">
                <h5 class="fs-15 pt-3 pb-3">Do you realy want to Update the Challan <span class="text-'.$modal.'">'.$row['challan_no'].'</span> of student <span class="text-'.$modal.'">'.$row['std_name'].'</span> to '.$title.'.</h5>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-'.$modal.' btn-sm" name="update_challan"><i class="ri-send-plane-fill align-bottom me-1"></i>Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>