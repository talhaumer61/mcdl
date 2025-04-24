<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms = new dblms();

// RECORD TO EDIT
$condition = array(
                     'select'       =>  'a.adm_id, a.adm_status, a.adm_fullname, a.adm_username, a.adm_email, a.adm_phone, a.id_dept, e.emply_id'
                    ,'join'         =>  'INNER JOIN '.EMPLOYEES.' e ON e.emply_loginid = a.adm_id'
                    ,'where'        =>  array(
                                                 'a.is_deleted'     => 0
                                                ,'a.adm_id'         => cleanvars($_GET['adm_id'])
                                            )
                    ,'return_type'  =>  'single'
);
$row = $dblms->getRows(ADMINS.' a', $condition);

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

// EMPLOYEES
$condition = array(
                     'select'       =>  'emply_id, emply_name'
                    ,'where'        =>  array(
                                                    'id_dept'          => cleanvars($row['id_dept'])
                                                ,'emply_status'     => 1
                                                ,'id_type'          => 1
                                                ,'is_deleted'       => 0
                                            )
                    ,'order_by'     =>  'emply_name ASC'
                    ,'return_type'  =>  'all'
);
$employees = $dblms->getRows(EMPLOYEES, $condition);

print_r($employees);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Login</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="teacherlogin.php" autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="adm_id" value="'.$row['adm_id'].'"/>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices required name="id_dept" id="id_dept" disabled>
                            <option value="">Choose one</option>';
                            foreach($departments as $dept) {
                                echo '<option value="'.$dept['dept_id'].'" '.($dept['dept_id'] == $row['id_dept'] ? 'selected' : '').'>'.$dept['dept_name'].' - '.$dept['dept_code'].'</option>';
                            }
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Employee <span class="text-danger">*</span></label>
                        <div id="get_deptemployee">
                            <select class="form-control" data-choices required name="id_emply" id="id_emply" disabled>
                                <option value="">Choose one</option>';
                                foreach($employees as $emply) {
                                    echo'<option value="'.$emply['emply_id'].'" '.($emply['emply_id'] == $row['emply_id'] ? 'selected' : '').'>'.$emply['emply_name'].'</option>';
                                }
                                echo'
                            </select>
                        </div>
                    </div>
                </div>
                <div id="get_employeedetail">
                    <div class="row">
                        <div class="col mb-2">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="adm_fullname" value="'.$row['adm_fullname'].'" readonly required/>
                        </div>
                        <div class="col mb-2">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="adm_email" value="'.$row['adm_email'].'" readonly required/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-2">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="adm_phone" value="'.$row['adm_phone'].'" readonly/>
                        </div>
                        <div class="col mb-2">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="adm_username" value="'.$row['adm_username'].'" readonly required/>
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
                                echo '<option value="'.$key.'" '.($row['adm_status'] == $key ? 'selected' : '').'>'.$val.'</option>';
                            }
                            echo'
                        </select>                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Login</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>