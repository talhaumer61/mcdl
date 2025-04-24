<?php 
require_once("../../../dbsetting/lms_vars_config.php");
require_once("../../../dbsetting/classdbconection.php");
require_once("../../../functions/functions.php");
$dblms  = new dblms();
require_once("../../../functions/login_func.php");
checkCpanelLMSALogin();
$condition = array ( 
                         'select' 	    =>  'bank_status,bank_account_name,bank_account_no,bank_account_iban_no,bank_name,bank_branch_name,bank_branch_code'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'       => 0
                                                    ,'id_emply'         => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                    ,'bank_id'          => cleanvars($_GET['bank_id'])
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$row = $dblms->getRows(BANK_INFORMATION, $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="bank_id" value="'.cleanvars($_GET['bank_id']).'"> 
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col">
                        <label class="form-label">Account Title <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="bank_account_name" value="'.$row['bank_account_name'].'" required="">
                    </div>
                    <div class="col">
                        <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="bank_name" required=""="">
                            <option value=""> Choose one</option>';
                            foreach(get_bank() as $key => $val):
                                echo'<option value="'.$key.'" '.(($key == $row['bank_name'])? 'selected': '').'>'.$val.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label class="form-label">Account Number <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" name="bank_account_no" value="'.$row['bank_account_no'].'" required="">
                    </div>
                    <div class="col">
                        <label class="form-label">IBAN Number <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="bank_account_iban_no" value="'.$row['bank_account_iban_no'].'" required="">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="bank_branch_name" value="'.$row['bank_branch_name'].'" required="">
                    </div>
                    <div class="col">
                        <label class="form-label">Branch Code <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="bank_branch_code" value="'.$row['bank_branch_code'].'" required="">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label class="form-label" for="card-name">Status <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="bank_status" required="">
                            <option value=""> Choose one</option>';
                            foreach(get_status() as $key => $val):
                                echo'<option value="'.$key.'" '.(($key == $row['bank_status'])? 'selected': '').'>'.$val.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label class="form-label" for="card-name">Detail</label>
                        <textarea class="form-control" name="bank_account_detail">'.$row['bank_account_detail'].'</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>