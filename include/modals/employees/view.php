<?php
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms = new dblms();

$condition = array ( 
                    'select'        =>  "
                                                e.emply_id, e.emply_status, e.emply_ordering, e.emply_name, e.emply_fathername, e.emply_dob, e.emply_cnic, 
                                                e.emply_religion, e.emply_phone, 
                                                e.emply_email, e.emply_mobile, e.emply_postal_address, e.emply_permanent_address, e.emply_experince, 
                                                e.emply_degreecountry, e.emply_passingyear, e.emply_qualification, e.emply_university, e.emply_specialsubject, e.id_dept, 
                                                e.id_designation, e.id_type, e.emply_permanentvisiting, e.emply_joining_date, e.emply_religion, e.emply_blood, e.emply_gender, 
                                                e.emply_marital, e.id_city, e.emply_photo,
                                                dp.dept_name,
                                                de.designation_name
                                        "
                    ,'join'         =>  '
                                            LEFT JOIN '.DEPARTMENTS.' dp    ON dp.dept_id           = e.id_dept
                                            LEFT JOIN '.DESIGNATIONS.' de   ON de.designation_id    = e.id_designation
                                        ' 
                    ,'where' 	    =>    array( 
                                                     'e.is_deleted'   => 0
                                                    ,'e.emply_id'     => cleanvars($_GET['emply_id'])
                                                )
                    ,'return_type'  =>  'single' 
                   ); 
$EMPLOYEES = $dblms->getRows(EMPLOYEES.' e', $condition);
echo '
<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">Employee Detail</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body p-0 overflow-hidden">
    <div data-simplebar style="height: calc(100vh - 112px);">
        <div class="acitivity-timeline p-4">
            <div class="card-body text-center">
                <div class="position-relative d-inline-block">
                    <img src="uploads/images/employees/'.$EMPLOYEES['emply_photo'].'" alt="" class="avatar-lg rounded-circle img-thumbnail">
                    <span class="contact-active position-absolute rounded-circle bg-success"><span class="visually-hidden"></span>
                </div>
                <h5 class="mt-4 mb-1">'.$EMPLOYEES['emply_name'].'</h5>
                <p class="text-muted">Nesta Technologies</p>

                <ul class="list-inline mb-0">
                    <li class="list-inline-item avatar-xs">
                        <a href="tel:'.$EMPLOYEES['emply_phone'].'" class="avatar-title bg-soft-success text-success fs-15 rounded">
                            <i class="ri-phone-line"></i>
                        </a>
                    </li>
                    <li class="list-inline-item avatar-xs">
                        <a href="mailto:'.$EMPLOYEES['emply_email'].'" class="avatar-title bg-soft-danger text-danger fs-15 rounded">
                            <i class="ri-mail-line"></i>
                        </a>
                    </li>
                    <!--
                    <li class="list-inline-item avatar-xs">
                        <a href="javascript:void(0);" class="avatar-title bg-soft-warning text-warning fs-15 rounded">
                            <i class="ri-question-answer-line"></i>
                        </a>
                    </li>
                    -->
                </ul>
            </div>
            <div class="card-body">
                <h6 class="text-muted text-uppercase fw-semibold mb-3">Employee Type</h6>
                <div class="table-responsive table-card">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="fw-medium" scope="row">Joining Date</td>
                                <td>'.$EMPLOYEES['emply_joining_date'].'</td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Joining Employee Type</td>
                                <td>'.get_emplytypes($EMPLOYEES['id_type']).'</td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Visiting</td>
                                <td>'.get_visiting($EMPLOYEES['emply_permanentvisiting']).'</td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Department</td>
                                <td>'.$EMPLOYEES['dept_name'].'</td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Designtion</td>
                                <td>'.$EMPLOYEES['designation_name'].'</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <h6 class="text-muted text-uppercase fw-semibold mb-3">Education Detail</h6>
                <div class="table-responsive table-card">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="fw-medium" scope="row">Qualification</td>
                                <td>'.$EMPLOYEES['emply_qualification'].'</td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">University</td>
                                <td>'.$EMPLOYEES['emply_university'].'</td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Special Subject</td>
                                <td>'.$EMPLOYEES['emply_specialsubject'].'</td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Passing Year</td>
                                <td>'.$EMPLOYEES['emply_passingyear'].'</td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Degree Country</td>
                                <td>'.$EMPLOYEES['emply_degreecountry'].'</td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Experince</td>
                                <td>'.$EMPLOYEES['emply_experince'].'</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <h6 class="text-muted text-uppercase fw-semibold mb-3">Address Detail</h6>
                <p class="text-muted mb-4">'.$EMPLOYEES['emply_postal_address'].'</p>
                <p class="text-muted mb-4">'.$EMPLOYEES['emply_permanent_address'].'</p>
            </div>
        </div>
    </div>
</div>';
?>