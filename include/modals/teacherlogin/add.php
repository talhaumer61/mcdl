<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
include "../../functions/functions.php";
$dblms = new dblms();

// DEPARTMENTS
$condition = array(
                     'select'       =>  'dept_id, dept_name, dept_code'
                    ,'where'        =>  array(
                                                'dept_status'  => 1
                                            )
                    ,'order_by'     =>  'dept_name DESC'
                    ,'return_type'  =>  'all'
);
$departments = $dblms->getRows(DEPARTMENTS, $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-primary p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-add-circle-line align-bottom me-1"></i>Create Login</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="teacherlogin.php" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices required name="id_dept" id="id_dept">
                            <option value="">Choose one</option>';
                            foreach($departments as $dept) {
                                echo '<option value="'.$dept['dept_id'].'">'.$dept['dept_name'].' - '.$dept['dept_code'].'</option>';
                            }
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Employee <span class="text-danger">*</span></label>
                        <div id="get_deptemployee">
                            <select class="form-control" data-choices required name="id_emply" id="id_emply">
                                <option value="">Choose department first</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="get_employeedetail">
                    <div class="row">
                        <div class="col mb-2">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="adm_fullname" required/>
                        </div>
                        <div class="col mb-2">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="adm_email" required/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-2">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="adm_phone"/>
                        </div>
                        <div class="col mb-2">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="adm_username" required/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">                        
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="adm_userpass" required/>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices required name="adm_status" onchange="get_deptemployee(this.value)">
                            <option value="">Choose one</option>';
                            foreach(get_status() as $key => $val) {
                                echo '<option value="'.$key.'">'.$val.'</option>';
                            }
                            echo'
                        </select>                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Create Login</button>
                </div>
            </div>
        </form>
    </div>
</div>';
include_once ('../../headoffice/teacherlogin/script.php');
?>