<?php
// DEPARTMENTS
$condition = array ( 
                        'select'        =>  "dept_id, dept_name"
                        ,'where' 	    =>  array( 
                                                     'is_deleted'       => '0'
                                                    ,'dept_status'      => '1'
                                                    ,'id_campus'        => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                )
                        ,'order_by'     =>  'dept_id ASC'
                        ,'return_type'  =>  'all' 
                    ); 
$DEPARTMENTS = $dblms->getRows(DEPARTMENTS, $condition);

// DESIGNATIONS
$condition = array ( 
                        'select'        =>  "designation_id, designation_name"
                        ,'where' 	    =>  array( 
                                                     'is_deleted'           => '0'
                                                    ,'designation_status'   => '1'
                                                    ,'id_campus'            => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                )
                        ,'order_by'     =>  'designation_id ASC'
                        ,'return_type'  =>  'all' 
                    ); 
$DESIGNATIONS = $dblms->getRows(DESIGNATIONS, $condition);

// CITIES
$condition = array ( 
                        'select'        =>  "c.city_id, c.city_name, cc.country_id"
                        ,'join'         =>  "INNER JOIN ".COUNTRIES." cc ON c.id_country = cc.country_id"
                        ,'where' 	    =>  array( 
                                                     'c.is_deleted'           => '0'
                                                    ,'c.city_status'          => '1'
                                                )
                        ,'group_by'     =>  'c.city_id'
                        ,'order_by'     =>  'c.city_id ASC'
                        ,'return_type'  =>  'all' 
                    ); 
$CITIES = $dblms->getRows(CITIES.' c', $condition);

// EMPLOYEE
$condition = array ( 
                        'select'        =>  "   
                                                emply_id, emply_status, emply_ordering, emply_name, emply_fathername, emply_dob, emply_cnic, emply_religion, 
                                                emply_phone, emply_email, emply_mobile, emply_postal_address, emply_permanent_address, emply_experince, 
                                                emply_degreecountry, emply_passingyear, emply_qualification, emply_university, emply_specialsubject, id_dept, 
                                                id_designation, id_type, emply_permanentvisiting, emply_joining_date, emply_religion, emply_blood, emply_gender, 
                                                emply_marital, id_city, emply_specialization, emply_introduction
                                            "
                        ,'where' 	    =>    array( 
                                                         'is_deleted'  => 0
                                                        ,'emply_id'    => cleanvars($_GET['id'])
                                                    )
                        ,'return_type'  =>  'single' 
                    ); 
$EMPLOYEES = $dblms->getRows(EMPLOYEES, $condition);
echo '
<style>
    @keyframes blink-red {
        0%, 100% {
          border-color: transparent;
        }
        50% {
          border-color: red;
        }
    }
    .error-border {
        border: 1px solid transparent;
        animation: blink-red 1s infinite;
    }
    @keyframes blink-green {
        0%, 100% {
          border-color: transparent;
        }
        50% {
          border-color: green;
        }
    }
    .success-border {
        border: 1px solid transparent;
        animation: blink-green 1s infinite;
    }
</style>
<form action="employees.php?id='.cleanvars($_GET['id']).'" autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <div class="card">      
        <ul class="nav nav-tabs nav-justified nav-border-top nav-border-top-primary mb-3" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#nav-border-justified-home" role="tab" aria-selected="false">
                    <i class="ri-information-line align-middle me-1"></i> Basic Detail
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#nav-border-justified-type" role="tab" aria-selected="false">
                    <i class=" ri-git-merge-line me-1 align-middle"></i> Employee Type
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#nav-border-justified-profile" role="tab" aria-selected="false">
                    <i class="ri-book-open-line me-1 align-middle"></i> Education Detail
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#nav-border-justified-messages" role="tab" aria-selected="false">
                    <i class="ri-user-location-line align-middle me-1"></i> Address Detail
                </a>
            </li>
        </ul>
        <div class="card-body">
            <div class="tab-content">
                <input type="hidden" name="edit_id" id="edit_id" value="'.cleanvars($_GET['id']).'">
                <div class="tab-pane active" id="nav-border-justified-home" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Picture</label>
                            <input type="file" class="form-control" name="emply_photo" accept="image/png, image/jpeg, image/jpg" />
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Ordering</label>
                            <input type="text" value="'.$EMPLOYEES['emply_ordering'].'" class="form-control" required readonly="">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Employee Name <span class="text-danger">*</span></label>
                            <input type="text" name="emply_name" id="emply_name" value="'.$EMPLOYEES['emply_name'].'" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Father Name <span class="text-danger">*</span></label>
                            <input type="text" name="emply_fathername" id="emply_fathername" value="'.$EMPLOYEES['emply_fathername'].'" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="text" name="emply_dob" id="emply_dob" class="form-control" value="'.$EMPLOYEES['emply_dob'].'" data-provider="flatpickr" data-date-format="Y-m-d" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">CNIC <span class="text-danger">*</span></label>
                            <input type="text" name="emply_cnic" id="emply_cnic" value="'.$EMPLOYEES['emply_cnic'].'" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Marital Status <span class="text-danger">*</span></label>
                            <select class="form-control" data-choices name="emply_marital" required>
                                <option value=""> Choose one</option>';
                                foreach(get_maritalstatustypes() as $key => $status):
                                    echo'<option value="'.$key.'" '.(($key == $EMPLOYEES['emply_marital'])? 'selected': '').'>'.$status.'</option>';
                                endforeach;
                                echo'
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select class="form-control" data-choices name="emply_gender" required>
                                <option value=""> Choose one</option>';
                                foreach(get_gendertypes() as $key => $status):
                                    echo'<option value="'.$key.'" '.(($key == $EMPLOYEES['emply_gender'])? 'selected': '').'>'.$status.'</option>';
                                endforeach;
                                echo'
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Blood Group <span class="text-danger">*</span></label>
                            <select class="form-control" data-choices name="emply_blood" required>
                                <option value=""> Choose one</option>';
                                foreach(get_bloodgroup() as $key => $status):
                                    echo'<option value="'.$key.'" '.(($key == $EMPLOYEES['emply_blood'])? 'selected': '').'>'.$status.'</option>';
                                endforeach;
                                echo'
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Religion <span class="text-danger">*</span></label>
                            <select class="form-control" data-choices name="emply_religion" required>
                                <option value=""> Choose one</option>';
                                foreach(get_religion() as $key => $status):
                                    echo'<option value="'.$key.'" '.(($key == $EMPLOYEES['emply_religion'])? 'selected': '').'>'.$status.'</option>';
                                endforeach;
                                echo'
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Teacher Bio</label>
                            <textarea class="form-control" id="ckeditor1" name="emply_introduction">'.html_entity_decode($EMPLOYEES['emply_introduction']).'</textarea>
                        </div>
                    </div>
                    <div class="hstack gap-2 justify-content-end pt-3">
                        <a href="'.moduleName().'.php" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Cancel</a>
                        '.((!empty($EMPLOYEES['emply_email']))? '<button type="submit" class="btn btn-primary btn-sm" name="submit_edit" id="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Employee</button>': '').'
                    </div>
                </div>
                <div class="tab-pane" id="nav-border-justified-type" role="tabpanel">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Joining Date <span class="text-danger">*</span></label>
                            <input type="text" name="emply_joining_date" id="emply_joining_date" class="form-control" value="'.$EMPLOYEES['emply_joining_date'].'" data-provider="flatpickr" data-date-format="Y-m-d" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Employee Type <span class="text-danger">*</span></label>
                            <select class="form-control" data-choices name="id_type" required>
                                <option value=""> Choose one</option>';
                                foreach(get_emplytypes() as $key => $status):
                                    echo'<option value="'.$key.'" '.(($key == $EMPLOYEES['id_type'])? 'selected': '').'>'.$status.'</option>';
                                endforeach;
                                echo'
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Visiting <span class="text-danger">*</span></label>
                            <select class="form-control" data-choices name="emply_permanentvisiting" required>
                                <option value=""> Choose one</option>';
                                foreach(get_visiting() as $key => $status):
                                    echo'<option value="'.$key.'" '.(($key == $EMPLOYEES['emply_permanentvisiting'])? 'selected': '').'>'.$status.'</option>';
                                endforeach;
                                echo'
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Department <span class="text-danger">*</span></label>
                            <select class="form-control" data-choices name="id_dept" required>
                                <option value=""> Choose one</option>';
                                foreach($DEPARTMENTS as $key => $val):
                                    echo'<option value="'.$val['dept_id'].'" '.(($val['dept_id'] == $EMPLOYEES['id_dept'])? 'selected': '').'>'.$val['dept_name'].'</option>';
                                endforeach;
                                echo'
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Designation <span class="text-danger">*</span></label>
                            <select class="form-control" data-choices name="id_designation" required>
                                <option value=""> Choose one</option>';
                                foreach($DESIGNATIONS as $key => $val):
                                    echo'<option value="'.$val['designation_id'].'" '.(($val['designation_id'] == $EMPLOYEES['id_designation'])? 'selected': '').'>'.$val['designation_name'].'</option>';
                                endforeach;
                                echo'
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Staus <span class="text-danger">*</span></label>
                            <select class="form-control" data-choices name="emply_status" required>
                                <option value=""> Choose one</option>';
                                foreach(get_status() as $key => $status):
                                    echo'<option value="'.$key.'" '.(($key == $EMPLOYEES['emply_status'])? 'selected': '').'>'.$status.'</option>';
                                endforeach;
                                echo'
                            </select>
                        </div>
                    </div>
                    <div class="hstack gap-2 justify-content-end pt-3">
                        <a href="'.moduleName().'.php" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Cancel</a>
                        '.((!empty($EMPLOYEES['emply_email']))? '<button type="submit" class="btn btn-primary btn-sm" name="submit_edit" id="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Employee</button>': '').'
                    </div>
                </div>
                <div class="tab-pane" id="nav-border-justified-profile" role="tabpanel">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Qualification <span class="text-danger">*</span></label>
                            <input type="text" name="emply_qualification" id="emply_qualification" value="'.$EMPLOYEES['emply_qualification'].'" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">University <span class="text-danger">*</span></label>
                            <input type="text" name="emply_university" id="emply_university" value="'.$EMPLOYEES['emply_university'].'" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Special Subject <span class="text-danger">*</span></label>
                            <input type="text" name="emply_specialsubject" id="emply_specialsubject" value="'.$EMPLOYEES['emply_specialsubject'].'" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Passing Year <span class="text-danger">*</span></label>
                            <input type="text" name="emply_passingyear" id="emply_passingyear" value="'.$EMPLOYEES['emply_passingyear'].'" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Degree Country <span class="text-danger">*</span></label>
                            <input type="text" name="emply_degreecountry" id="emply_degreecountry" value="'.$EMPLOYEES['emply_degreecountry'].'" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Experince <span class="text-danger">*</span></label>
                            <input type="text" name="emply_experince" id="emply_experince" value="'.$EMPLOYEES['emply_experince'].'" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-2">
                            <label class="form-label">Specialization Title</label>
                            <input type="text" name="emply_specialization" id="emply_specialization" value="'.$EMPLOYEES['emply_specialization'].'" class="form-control">
                        </div>
                    </div>
                    <div class="hstack gap-2 justify-content-end pt-3">
                        <a href="'.moduleName().'.php" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Cancel</a>
                        '.((!empty($EMPLOYEES['emply_email']))? '<button type="submit" class="btn btn-primary btn-sm" name="submit_edit" id="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Employee</button>': '').'
                    </div>
                </div>
                <div class="tab-pane" id="nav-border-justified-messages" role="tabpanel">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="emply_phone" id="emply_phone" class="form-control" required value="'.$EMPLOYEES['emply_phone'].'">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Mobile <span class="text-danger">*</span></label>
                            <input type="text" name="emply_mobile" id="emply_mobile" class="form-control" required value="'.$EMPLOYEES['emply_mobile'].'">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Email <span class="text-danger" id="emailError">*</span></label>
                            <input type="email" name="emply_email" id="emply_email" onkeyup="get_EmailValidation(this.value,\'1\')" value="'.$EMPLOYEES['emply_email'].'" '.((!empty($EMPLOYEES['emply_email']))? 'readonly': '').' class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">City <span class="text-danger">*</span></label>
                            <select class="form-control" data-choices name="id_city" required>
                                <option value=""> Choose one</option>';
                                foreach($CITIES as $key => $val):
                                    echo'<option value="'.$val['city_id'].'|'.$val['country_id'].'" '.(($val['city_id'] == $EMPLOYEES['id_city'])? 'selected': '').'>'.$val['city_name'].'</option>';
                                endforeach;
                                echo'
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Postal Address <span class="text-danger">*</span></label>
                            <textarea type="text" name="emply_postal_address" id="emply_postal_address" class="form-control" required>'.$EMPLOYEES['emply_postal_address'].'</textarea>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Permanent Address</label>
                            <textarea type="number" name="emply_permanent_address" id="emply_permanent_address" class="form-control">'.$EMPLOYEES['emply_permanent_address'].'</textarea>
                        </div>
                    </div>
                    <div class="hstack gap-2 justify-content-end pt-3">
                        <a href="employees.php" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Cancel</a>
                        <span id="add_submit_btn"></span>
                        '.((!empty($EMPLOYEES['emply_email']))? '<button type="submit" class="btn btn-primary btn-sm" name="submit_edit" id="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Employee</button>': '').'
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>';
?>