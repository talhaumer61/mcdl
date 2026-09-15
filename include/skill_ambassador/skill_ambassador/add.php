<?php
$year = date('y');

$sqlQuery = $dblms->querylms("SELECT 
                                IFNULL(
                                    CONCAT(
                                        'SA-', $year, '-', 
                                        LPAD(
                                            CAST(SUBSTRING_INDEX(MAX(org_reg), '-', -1) AS UNSIGNED) + 1, 
                                            5, '0'
                                        )
                                    ), 
                                    CONCAT('SA-', $year, '-00001')
                                ) AS new_org_reg
                                FROM ".SKILL_AMBASSADOR."
                                WHERE org_reg LIKE CONCAT('SA-', $year, '-%')
                            ");
$valQuery = mysqli_fetch_array($sqlQuery);
echo'
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h5 class="mb-0 modal-title"><i class="ri-add-circle-line align-bottom me-1"></i>Add ' . moduleName(false) . '</h5>
            </div>
            <form enctype="multipart/form-data" autocomplete="off" method="post" accept-charset="utf-8">
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
                                            <input type="text" name="org_name" class="form-control" required="">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Registration Number <span class="text-danger">*</span></label>
                                            <input type="text" name="org_reg" class="form-control" value="'.$valQuery['new_org_reg'].'" required readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label class="form-label">Discount Percentage <span class="text-danger">*</span> <span class="text-primary">(1-25)</span></label>
                                            <input type="number" name="org_percentage" id="org_percentage" class="form-control" value="25" required min="1" max="25">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Profilt Percentage <span class="text-danger">*</span> <span class="text-primary">(1-20)</span></label>
                                            <input type="number" name="org_profit_percentage" id="org_profit_percentage" class="form-control" value="20" required min="1" max="25">
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
                                            <label class="form-label">User Name <span class="text-danger">*</span></label>
                                            <input type="text" name="adm_username" id="adm_username" class="form-control" required="">
                                            <small id="username_error"></small>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="text" name="org_email" class="form-control" required="">
                                            <small id="email_error"></small>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label class="form-label">Photo <span class="text-danger">*</span></label>
                                            <input type="file" name="org_photo" class="form-control" required="">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Password <span class="text-danger">*</span></label>
                                            <input type="text" name="adm_userpass" class="form-control" required="">
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
                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Link</label>
                                            <input type="text" name="org_referral_link" id="org_referral_link" class="form-control" required="" value="_'.$valQuery['new_org_reg'].'" readonly="">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Link Expiry <span class="text-danger">*</span></label>
                                            <input type="text" name="org_referral_link_expiry" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true" required>
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
                                            <input type="text" name="org_phone" class="form-control" required="">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Telephone <span class="text-danger">*</span></label>
                                            <input type="text" name="org_telephone" class="form-control" required="">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">What\'s App <span class="text-danger">*</span></label>
                                            <input type="text" name="org_whatsapp" class="form-control" required="">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label class="form-label">Address </label>
                                            <textarea class="form-control" name="org_address"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="' . moduleName() . '.php" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
                        <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add ' . moduleName(0) . '</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>';
?>