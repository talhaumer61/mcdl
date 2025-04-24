<?php
 // ADMIN DATA
 $condition = array ( 
                        'select'        =>  'adm_username, adm_fullname, adm_email, adm_phone, adm_photo, adm_type',
                        'where'         =>  array(
                                                        'adm_id'  =>  $_SESSION['userlogininfo']['LOGINIDA']
                                                    ), 
                        'return_type'   =>  'single'
                    ); 
$row = $dblms->getRows(ADMINS, $condition);

echo '
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="assets/images/profile-bg.jpg" class="profile-wid-img" alt="">
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-3 col-xl-4 col-lg-4">
            <div class="card mt-n5">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <img src="'.SITE_URL.'uploads/images/admin/'.$row['adm_photo'].'" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                        </div>
                        <h5 class="fs-16 mb-1">'.$row['adm_fullname'].'</h5>
                        <p class="text-muted mb-0">'.get_admtypes($row['adm_type']).'</p>
                    </div>
                    <hr>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Full Name:</th>
                                <td align="right">'.$row['adm_fullname'].'</td>
                            </tr>
                            <tr>
                                <th>Username:</th>
                                <td align="right">'.$row['adm_username'].'</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td align="right">'.$row['adm_email'].'</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td align="right">'.$row['adm_phone'].'</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xxl-9 col-xl-8 col-lg-8">
            <div class="card mt-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link text-body active" id="active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="fas fa-home"></i> Personal Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-body" data-bs-toggle="tab" href="#changePassword" role="tab">
                                <i class="far fa-user"></i> Change Password
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form action="profile.php" enctype="multipart/form-data" autocomplete="off" method="post" accept-charset="utf-8">  
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="adm_fullnameInput">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" name="adm_fullname" class="form-control" required id="adm_fullnameInput" placeholder="Enter your firstname" value="'.$row['adm_fullname'].'">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="cleave-phone">Phone <span class="text-danger">*</span></label>
                                            <input type="text" name="adm_phone" class="form-control" required id="cleave-phone" placeholder="xxxx-xxxxxxx"  value="'.$row['adm_phone'].'">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="adm_emailInput">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="adm_email" class="form-control" required id="adm_emailInput" value="'.$row['adm_email'].'" >
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label>Profile Picture <span class="text-danger">*</span></label>
                                            <input type="file" name="adm_photo" accept="image/*" class="form-control" data-bs-height="100"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end mt-3">
                                            <button type="submit" class="btn btn-primary" name="submit_profile">Update Profile</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="changePassword" role="tabpanel">
                            <form action="profile.php" autocomplete="off" method="post" accept-charset="utf-8"> 
                                <div class="row g-2">
                                    <div class="col-lg-12">
                                        <div>
                                            <label for="newpasswordInput">New Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="cnfrm_pass" required id="newpasswordInput" placeholder="Enter new password">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end mt-3">
                                            <button type="submit" class="btn btn-primary" name="chnage_pass">Change Password</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
';
?>