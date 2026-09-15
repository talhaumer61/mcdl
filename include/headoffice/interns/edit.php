<?php
// INTERNS
$condition = [
    'select'        =>  'id, full_name, father_name, status, id_role, email, phone, cnic, highest_qualification, photo, gender, applied_date, selection_date, joining_date, leaving_date',
    'where' 	    =>  [
                            'is_deleted'  => 0,
                            'id'    => cleanvars(LMS_EDIT_ID),
                        ],
    'return_type'  =>  'single'
];
$INTERNS = $dblms->getRows(INTERNS, $condition);

if($INTERNS['gender'] == '2'){
    $photo = SITE_URL.'uploads/images/default_female.jpg';
} else {            
    $photo = SITE_URL.'uploads/images/default_male.jpg';
}
if(!empty($INTERNS['photo']) && file_exists('uploads/images/interns/'.$INTERNS['photo'])){
    $photo = SITE_URL.'uploads/images/interns/'.$INTERNS['photo'];
}
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
<form action="'.moduleName().'.php" autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <div class="card">      
        <ul class="nav nav-tabs nav-justified nav-border-top nav-border-top-primary mb-3" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#basic" role="tab" aria="false">
                    <i class="ri-information-line align-middle me-1"></i> Basic
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#internship" role="tab" aria="false">
                    <i class="ri-book-open-line me-1 align-middle"></i> Internship
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#contact" role="tab" aria="false">
                    <i class="ri-user-location-line align-middle me-1"></i> Contact
                </a>
            </li>
        </ul>
        <div class="card-body">
            <div class="tab-content">
                <input type="hidden" name="edit_id" id="edit_id" value="'.cleanvars(LMS_EDIT_ID).'">
                
                <div class="tab-pane active" id="basic" role="tabpanel">
                    <div class="row">                        
                        <div class="profile-user position-relative d-inline-block mx-auto mb-2">
                            <img src="'.$photo.'" class="avatar-lg img-thumbnail user-profile-image" alt="user-profile-image">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Photo</label>
                            <input type="file" name="photo" class="form-control" accept="image/png, image/jpeg, image/jpg">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" value="'.$INTERNS['full_name'].'" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Father Name <span class="text-danger">*</span></label>
                            <input type="text" name="father_name" class="form-control" value="'.$INTERNS['father_name'].'" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-control" data-choices required>
                                <option value="">Select Gender</option>';
                                foreach(get_gendertypes() as $k => $v){
                                    echo'<option value="'.$k.'" '.($INTERNS['gender'] == $k ? 'selected' : '').'>'.$v.'</option>';
                                }
                                echo'
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Highest Qualification <span class="text-danger">*</span></label>
                            <select name="highest_qualification" class="form-control" data-choices required>
                                <option value="">Select Qualification</option>';
                                foreach(get_educationtypes() as $k => $v){
                                    echo'<option value="'.$k.'" '.($INTERNS['highest_qualification'] == $k ? 'selected' : '').'>'.$v.'</option>';
                                }
                                echo'
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Remarks</label>
                            <textarea name="remarks" class="form-control">'.$INTERNS['remarks'].'</textarea>
                        </div>
                    </div>
                    <div class="hstack gap-2 justify-content-end pt-3">
                        <a href="'.moduleName().'.php" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Cancel</a>
                    </div>
                </div>
                <div class="tab-pane" id="internship" role="tabpanel">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" data-choices required>
                                <option value="">Select Status</option>';
                                foreach(getInternStatus() as $k => $v){
                                    echo'<option value="'.$k.'" '.($INTERNS['status'] == $k ? 'selected' : '').'>'.$v.'</option>';
                                }
                                echo'
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Role <span class="text-danger">*</span></label>
                            <select name="id_role" class="form-control" data-choices required>
                                <option value="">Select Role</option>';
                                foreach(getInternRoles() as $k => $v){
                                    echo'<option value="'.$k.'" '.($INTERNS['id_role'] == $k ? 'selected' : '').'>'.$v.'</option>';
                                }
                                echo'
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Applied Date <span class="text-danger">*</span></label>
                            <input type="text" name="applied_date"
                                class="form-control"
                                data-provider="flatpickr"
                                data-date-format="Y-m-d"
                                value="'.($INTERNS['applied_date'] != '0000-00-00' ? $INTERNS['applied_date'] : '').'"
                                required>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Selection Date</label>
                            <input type="text" name="selection_date"
                                class="form-control"
                                data-provider="flatpickr"
                                value="'.($INTERNS['selection_date'] != '0000-00-00' ? $INTERNS['selection_date'] : '').'"
                                data-date-format="Y-m-d">
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Joining Date</label>
                            <input type="text" name="joining_date"
                                class="form-control"
                                data-provider="flatpickr"
                                value="'.($INTERNS['joining_date'] != '0000-00-00' ? $INTERNS['joining_date'] : '').'"
                                data-date-format="Y-m-d">
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Leaving Date</label>
                            <input type="text" name="leaving_date"
                                class="form-control"
                                data-provider="flatpickr"
                                value="'.($INTERNS['leaving_date'] != '0000-00-00' ? $INTERNS['leaving_date'] : '').'"
                                data-date-format="Y-m-d">
                        </div>
                    </div>
                    <div class="hstack gap-2 justify-content-end pt-3">
                        <a href="'.moduleName().'.php" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Cancel</a>
                    </div>
                </div>
                <div class="tab-pane" id="contact" role="tabpanel">
                    <div class="row">                    
                        <div class="col-md-4 mb-2">
                            <label>CNIC <span class="text-danger">*</span></label>
                            <input type="text" name="cnic" id="cleave-cnic" placeholder="xxxxx-xxxxxxx-x" class="form-control" value="'.$INTERNS['cnic'].'" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label>Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="cleave-pk-phone" placeholder="+92 xxx xxxxxxx" class="form-control" value="'.$INTERNS['phone'].'" required>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="form-label">Email <span class="text-danger" id="emailError">*</span></label>
                            <input type="email" name="emply_email" class="form-control" value="'.$INTERNS['email'].'" required>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Address <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" required>'.$INTERNS['address'].'</textarea>
                        </div>
                    </div>
                    <div class="hstack gap-2 justify-content-end pt-3">
                        <a href="'.moduleName().'.php" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Cancel</a>
                        '.((!empty($INTERNS['email']))? '<button type="submit" class="btn btn-primary btn-sm" name="submit_edit" id="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Intern</button>': '').'
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>';
?>