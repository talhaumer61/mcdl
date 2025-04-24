<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms = new dblms();

$condition = array(
                     'select'       =>  'currency_id, currency_name,currency_code, currency_ordering, currency_symbol, currency_position, currency_fractionalunits, currency_status'
                    ,'where'        =>  array(
                                                 'is_deleted'   => 0
                                                ,'currency_id'      => cleanvars($_GET['currency_id'])
                                            )
                    ,'return_type'  =>  'single'
);
$row = $dblms->getRows(CURRENCIES , $condition);

echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Currency</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="currencies.php" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="currency_id" value="'.$row['currency_id'].'"/>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="currency_name" value="'.$row['currency_name'].'" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Ordering <span class="text-danger">*</span></label>
                        <input type="number" value="'.$row['currency_ordering'].'" name="currency_ordering" class="form-control" required="" readonly="">
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Code <span class="text-danger">*</span></label>
                        <input class="form-control" name="currency_code" value="'.$row['currency_code'].'" type="text" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Symbol <span class="text-danger">*</span></label>
                        <input class="form-control" name="currency_symbol" value="'.$row['currency_symbol'].'" type="text" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Fractional Unit <span class="text-danger">*</span></label>
                        <input class="form-control" name="currency_fractionalunits" value="'.$row['currency_fractionalunits'].'" type="text" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Position <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="currency_position" required>
                            <option value=""> Choose one</option>';
                            foreach (get_currency_postition() as $key => $cp):
                                echo'<option value="'.$key.'" '.($row['currency_position'] == $key ? 'selected' : '').'>'.$cp.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2" id="state">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="currency_status" required>
                            <option value=""> Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo'<option value="'.$key.'" '.($row['currency_status'] == $key ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Currency</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>