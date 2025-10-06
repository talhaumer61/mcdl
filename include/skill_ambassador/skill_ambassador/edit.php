<?php
$condition  =   [ 
                    'select'        =>  'o.org_id, o.org_status, o.org_percentage, o.org_profit_percentage, o.id_loginid, a.adm_photo, a.adm_username, o.org_name, o.org_reg, o.org_email, o.org_phone, o.org_telephone, o.org_whatsapp, o.org_referral_link, o.org_link_from, o.org_link_to, o.org_address, o.org_type, o.allow_add_members, o.parent_org',
                    'join'          =>  'INNER JOIN '.ADMINS.' AS a ON a.adm_id = o.id_loginid',
                    'where' 	    =>  [
                                            'a.adm_logintype'   => 8,
                                            'o.is_deleted'      => 0,
                                            'o.org_id'          => cleanvars(LMS_EDIT_ID),
                                        ],
                    'return_type'  =>  'single',
                ]; 
$row = $dblms->getRows(SKILL_AMBASSADOR.' AS o', $condition, $sql);

// GET SKILL AMBASSADOR - TYPE ORG
$condition  =   [ 
                    'select'        =>  'o.org_id, o.org_name, o.org_reg',
                    'where' 	    =>  [
                                            'o.org_type'            =>  1,
                                            'o.org_status'          =>  1,
                                            'o.allow_add_members'   =>  1,
                                            'o.is_deleted'          =>  0,
                                        ],
                    'not_equal'     =>  [
                                            'o.org_id'              =>  cleanvars(LMS_EDIT_ID)
                                        ],
                    'return_type'   =>  'all',
]; 
$SKILL_AMBASSADOR = $dblms->getRows(SKILL_AMBASSADOR.' AS o', $condition);
echo'
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header bg-info">
                <h5 class="mb-0 modal-title"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(0).'</h5>
            </div>
            <form enctype="multipart/form-data" autocomplete="off" method="post" accept-charset="utf-8">
                <input type="hidden" name="adm_id" value="'.$row['id_loginid'].'">
                <div class="card-body create-project-main">
                    <div class="row mb-2">
                        <div class="col">
                            <div class="card mb-3">
                                <div class="card-header alert-primary">
                                    Basic Information
                                </div>
                                <div class="card-body border">
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label class="form-label">Name <span class="text-danger">*</span></label>
                                            <input type="text" name="org_name" value="'.$row['org_name'].'" class="form-control" required="">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Registration Number <span class="text-danger">*</span></label>
                                            <input type="text" name="org_reg" value="'.$row['org_reg'].'" class="form-control" readonly required="">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label class="form-label">Discount Percentage <span class="text-danger">*</span> <span class="text-primary">(1-25)</span></label>
                                            <input type="number" name="org_percentage" id="org_percentage" class="form-control" value="'.$row['org_percentage'].'" required min="1" max="25">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Profilt Percentage <span class="text-danger">*</span> <span class="text-primary">(1-20)</span></label>
                                            <input type="number" name="org_profit_percentage" id="org_profit_percentage" class="form-control" value="'.$row['org_profit_percentage'].'" required min="1" max="25">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <div class="card mb-3">
                                <div class="card-header alert-primary">
                                    Login Information
                                </div>
                                <div class="card-body border">
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label class="form-label">User Name <span class="text-primary">(Not changeable.)</span></label>
                                            <input type="text" name="adm_username" value="'.$row['adm_username'].'" id="adm_username" class="form-control" required="" readonly="">
                                            <small id="username_error"></small>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Email <span class="text-primary">(Not changeable.)</span></label>
                                            <input type="text" name="org_email" value="'.$row['org_email'].'" class="form-control" required="" readonly="">
                                            <small id="email_error"></small>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label class="form-label">Photo</label>
                                            <input type="file" name="org_photo" class="form-control">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Password <span class="text-primary">(If you enter password it will chnaged password.)</span></label>
                                            <input type="text" name="adm_userpass" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <div class="card mb-3">
                                <div class="card-header alert-primary">
                                    Referral Information
                                </div>
                                <div class="card-body border">
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label class="form-label">Link</label>
                                            <input type="text" name="org_referral_link" id="org_referral_link" value="'.$row['org_referral_link'].'" class="form-control" required="" readonly="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <div class="card mb-3">
                                <div class="card-header alert-primary">
                                    Contact Information
                                </div>
                                <div class="card-body border">
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                                            <input type="text" name="org_phone" value="'.$row['org_phone'].'" class="form-control" required="">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Telephone <span class="text-danger">*</span></label>
                                            <input type="text" name="org_telephone" value="'.$row['org_telephone'].'" class="form-control" required="">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">What\'s App <span class="text-danger">*</span></label>
                                            <input type="text" name="org_whatsapp" value="'.$row['org_whatsapp'].'" class="form-control" required="">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label class="form-label">Address </label>
                                            <textarea class="form-control" name="org_address">'.$row['org_address'].'</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="'.moduleName().'.php" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
                        <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(0).'</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>';
?>