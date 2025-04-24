<?php
$condition = array ( 
                         'select' 	    =>  'a.adm_username, a.adm_fullname, a.adm_email, a.adm_phone, a.adm_photo, e.emply_photo, e.emply_loginid, e.emply_id, e.emply_regno, e.emply_extension, e.emply_name, e.emply_fathername, e.emply_dob, e.emply_cnic, e.emply_phone, e.emply_email, e.emply_officialemail, e.emply_mobile, e.emply_postal_address, e.emply_permanent_address, e.emply_joining_date, dp.dept_name, dg.designation_name, ecu.country_name, eci.city_name, e.emply_gender'
                        ,'join'         =>  '
                                                INNER JOIN '.EMPLOYEES.' e ON e.emply_loginid = a.adm_id AND e.is_deleted = 0 AND e.emply_status = 1
                                                LEFT JOIN '.DEPARTMENTS.' dp ON dp.dept_id = e.id_dept
                                                LEFT JOIN '.DESIGNATIONS.' dg ON dg.designation_id = e.id_designation
                                                LEFT JOIN '.COUNTRIES.' ecu ON ecu.country_id = e.id_country
                                                LEFT JOIN '.CITIES.' eci ON eci.city_id = e.id_city
                                            ' 
                        ,'where' 	    =>  array(  
                                                     'a.is_deleted'        =>   0
                                                    ,'a.adm_id '           =>   cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$ADMINS = $dblms->getRows(ADMINS.' a', $condition, $sql);
echo'
<script src="assets/js/app.js"></script>
<form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <div class="row">
        <div class="col-12" draggable="true">
            <div class="row">
                <div class="col-md-8">
                    <label class="form-label">Employee Profile</label>
                    <table class="table table-bordered table-nowrap align-middle">
                        <thead>
                            <tr>
                                <th width="10">Name <span class="text-danger">*</span></th>
                                <td class="text-center"><input type="text" name="emply_name" id="emply_name" value="'.$ADMINS['emply_name'].'" class="form-control" required></td>
                                <th width="10">Father Name</th>
                                <td><input type="text" name="emply_fathername" id="emply_fathername" value="'.$ADMINS['emply_fathername'].'" class="form-control"></td>
                            </tr>
                            <tr>
                                <th width="100">CNIC <span class="text-danger">*</span></th>
                                <td><input type="text" name="emply_cnic" id="emply_cnic" value="'.$ADMINS['emply_cnic'].'" class="form-control" required></td>
                                <th width="100">Office Extension</th>
                                <td><input type="text" name="emply_extension" id="emply_extension" value="'.$ADMINS['emply_extension'].'" class="form-control"></td>
                            </tr>
                        </thead>
                    </table>
                    <label class="form-label">Personal Information</label>
                    <table class="table table-bordered table-nowrap align-middle">
                        <thead>
                            <tr>
                                <th width="10">Phone</th>
                                <td><input type="text" name="emply_phone" id="emply_phone" value="'.$ADMINS['emply_phone'].'" class="form-control" required></td>
                                <th width="10">Mobile</th>
                                <td><input type="text" name="emply_mobile" id="emply_mobile" value="'.$ADMINS['emply_mobile'].'" class="form-control" required></td>
                            </tr>
                            <tr>
                                <th width="10">Gender</th>
                                <td>
                                    <select class="form-control" data-choices name="emply_gender" required>
                                        <option value=""> Choose one</option>';
                                        foreach(get_gendertypes() as $key => $status):
                                            echo'<option value="'.$key.'" '.(($key == $ADMINS['emply_gender'])? 'selected': '').'>'.$status.'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </td>
                                <th width="10">Date of Birth <span class="text-danger">*</span></th>
                                <td><input type="text" name="emply_dob" id="emply_dob" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" value="'.$ADMINS['emply_dob'].'" required></td>
                            </tr>
                            <tr>
                                <th width="100">Email</th>
                                <td><input type="text" name="emply_email" id="emply_email" value="'.$ADMINS['emply_email'].'" class="form-control" required></td>
                                <th width="100">Offical Email</th>
                                <td><input type="text" name="emply_officialemail" id="emply_officialemail" value="'.$ADMINS['emply_officialemail'].'" class="form-control"></td>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="col-md-4">
                    <div class="text-center pt-5">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <img src="'.$_SESSION['userlogininfo']['LOGINPHOTO'].'" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                <input id="profile-img-file-input" name="emply_photo" id="emply_photo" type="file" accept="image/png, image/jpg, image/jpeg" class="profile-img-file-input">
                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <h5 class="fs-16 mb-1">'.$_SESSION['userlogininfo']['LOGINNAME'].'</h5>
                        <p class="text-muted mb-0">'.get_admtypes($_SESSION['userlogininfo']['LOGINTYPE']).' / '.$ADMINS['dept_name'].'</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="form-label">Address</label>
                    <table class="table table-bordered table-nowrap align-middle">
                        <thead>
                            <tr>
                                <th width="100">Permanent</th=>
                                <td><textarea name="emply_permanent_address" id="emply_permanent_address" class="form-control">'.$ADMINS['emply_permanent_address'].'</textarea></td>
                            </tr>
                            <tr>
                                <th width="100">Postal</th>
                                <td><textarea name="emply_postal_address" id="emply_postal_address" class="form-control">'.$ADMINS['emply_postal_address'].'</textarea></td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="hstack gap-2 justify-content-end">
        <a href="'.moduleName().'.php?id='.cleanvars($_GET['id']).'&view='.cleanvars($_GET['view']).'" class="btn btn-danger btn-sm""><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
        <button type="submit" class="btn btn-info btn-sm" name="submit_edit_profile"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Profile</button>
    </div>
</form>';
?>