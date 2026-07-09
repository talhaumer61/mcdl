<?php
include_once ('query.php');
$condition = array ( 
                         'select' 	    =>  'a.adm_username, a.adm_fullname, a.adm_email, a.adm_phone, a.adm_photo, e.emply_regno, e.emply_name, e.emply_fathername, e.emply_dob, e.emply_cnic, e.emply_phone, e.emply_email, e.emply_officialemail, e.emply_mobile, e.emply_postal_address, e.emply_permanent_address, e.emply_joining_date, dp.dept_name, dg.designation_name, ecu.country_name, eci.city_name, e.emply_gender'
                        ,'join'         =>  '
                                                INNER JOIN '.EMPLOYEES.' e ON e.emply_loginid = a.adm_id AND e.is_deleted = 0 AND e.emply_status = 1
                                                LEFT JOIN '.DEPARTMENTS.' dp ON dp.dept_id = e.id_dept
                                                LEFT JOIN '.DESIGNATIONS.' dg ON dg.designation_id = e.id_designation
                                                LEFT JOIN '.COUNTRIES.' ecu ON ecu.country_id = e.id_country
                                                LEFT JOIN '.CITIES.' eci ON eci.city_id = e.id_city
                                            ' 
                        ,'where' 	    =>  array(  
                                                     'a.is_deleted'        => 0
                                                    ,'a.adm_id '           => cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$ADMINS = $dblms->getRows(ADMINS.' a', $condition, $sql);
echo'
<div class="row">
    <div class="col-md-8">
        <label class="form-label">Employee Profile</label>
        <table class="table table-bordered table-nowrap align-middle">
            <thead>
                <tr>
                    <th width="10">Employee No</th>
                    <td><span class="badge badge-soft-primary">'.$ADMINS['emply_regno'].'</span></td>
                    <th width="10">Department</th>
                    <td>'.$ADMINS['dept_name'].'</td>
                </tr>
                <tr>
                    <th width="10">Designation</th>
                    <td>'.$ADMINS['designation_name'].'</td>
                    <th width="10">Joining Date</th>
                    <td>'.date("d M, Y", strtotime($ADMINS['emply_joining_date'])).'</td>
                </tr>
            </thead>
        </table>
        <label class="form-label">Personal Information</label>
        <table class="table table-bordered table-nowrap align-middle">
            <thead>
                <tr>
                    <th width="10">Name</th>
                    <td>'.$ADMINS['emply_name'].'</td>
                    <th width="10">Father Name</th>
                    <td>'.$ADMINS['emply_fathername'].'</td>
                </tr>
                <tr>
                    <th width="10">Phone</th>
                    <td>'.$ADMINS['emply_phone'].'</td>
                    <th width="10">Mobile</th>
                    <td>'.$ADMINS['emply_mobile'].'</td>
                </tr>
                <tr>
                    <th width="10">Gender</th>
                    <td>'.get_gendertypes($ADMINS['emply_gender']).'</td>
                    <th width="10">Date of Birth</th>
                    <td>'.date("d M, Y", strtotime($ADMINS['emply_dob'])).'</td>
                </tr>
            </thead>
        </table>
    </div>
    <div class="col-md-4">
        <div class="text-center pt-5">
            <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                <img src="'.$_SESSION['userlogininfo']['LOGINPHOTO'].'" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
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
                    <th width="100">Permanent</th>
                    <td colspan="3">'.$ADMINS['emply_permanent_address'].'</td>
                </tr>
                <tr>
                    <th width="100">Postal</th>
                    <td colspan="3">'.$ADMINS['emply_postal_address'].'</td>
                </tr>
                <tr>
                    <th width="100">Country</th>
                    <td>'.$ADMINS['country_name'].'</td>
                    <th width="100">City</th>
                    <td>'.$ADMINS['city_name'].'</td>
                </tr>
                <tr>
                    <th width="100">Email</th>
                    <td>'.$ADMINS['emply_email'].'</td>
                    <th width="100">Offical Email</th>
                    <td>'.$ADMINS['emply_officialemail'].'</td>
                </tr>
            </thead>
        </table>
    </div>
</div>';
?>