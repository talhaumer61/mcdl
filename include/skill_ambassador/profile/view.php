<?php
$condition  =   [ 
                    'select'        =>  'o.org_reg, o.org_telephone, o.org_whatsapp,o.org_city, o.org_address, o.org_type, o.allow_add_members, o.parent_org, o.org_link_from, o.org_link_to, o.cv_file, o.cv_url',
                    'where' 	    =>  [
                                            'o.is_deleted'      => 0,
                                            'o.org_id'          => cleanvars($_SESSION['userlogininfo']['LOGINORGANIZATIONID']),
                                        ],
                    'return_type'  =>  'single',
                ]; 
$row = $dblms->getRows(SKILL_AMBASSADOR.' AS o', $condition, $sql);

$condition  =   [ 
    'select'        =>  'id, id_bank, account_title, account_number, account_iban',
    'where' 	    =>  [
                            'is_deleted'    => 0,
                            'status'        => 1,
                            'id_org'        => cleanvars($_SESSION['userlogininfo']['LOGINORGANIZATIONID']),
                        ],
    'return_type'  =>  'single',
]; 
$rowBank = $dblms->getRows(SA_BANK_DETAILS, $condition, $sql);

$condition = [ 
    'select' => 'id, name, qualification, field_expertise, experience, certification, skill_strength, city',
    'where'  => [
        'is_deleted' => 0,
        'status'     => 1,
        'id_org'     => cleanvars($_SESSION['userlogininfo']['LOGINORGANIZATIONID']),
    ],
    'return_type' => 'single',
];
$rowEdu = $dblms->getRows(SA_EDU_DETAILS, $condition, $sql);

$certifications = !empty($rowEdu['certification']) ? json_decode(html_entity_decode($rowEdu['certification']), true) : [''];
$skills = !empty($rowEdu['skill_strength']) ? json_decode(html_entity_decode($rowEdu['skill_strength']), true) : [''];

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
                        <img src="'.$_SESSION['userlogininfo']['LOGINPHOTO'].'" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                    </div>
                    <h5 class="fs-16 mb-1">'.$_SESSION['userlogininfo']['LOGINNAME'].'</h5>
                    <p class="text-muted mb-0">'.get_admtypes($_SESSION['userlogininfo']['LOGINTYPE']).'</p>
                </div>
                <table class="table">
                    <tbody>
                        <tr>
                            <th>Reg No:</th>
                            <td align="right">'.$row['org_reg'].'</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td align="right">'.$_SESSION['userlogininfo']['LOGINNAME'].'</td>
                        </tr>
                        <tr>
                            <th>Username:</th>
                            <td align="right">'.$_SESSION['userlogininfo']['LOGINUSER'].'</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td align="right">'.$_SESSION['userlogininfo']['LOGINEMAIL'].'</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td align="right">'.$_SESSION['userlogininfo']['LOGINPHONE'].'</td>
                        </tr>
                        <tr>
                            <th>Refferal Allow:</th>
                            <td align="right">'.($row['org_link_from'] == NULL ? '' : date('Y-m-d', strtotime($row['org_link_from'])).' to '.date('Y-m-d', strtotime($row['org_link_to']))).'</td>
                        </tr>
                        <tr>
                            <th>Members Allow:</th>
                            <td align="right">'.get_YesNoStatus($row['allow_add_members']).'</td>
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
                    </li>';
                    if(!empty($row['cv_file']) || !empty($row['cv_url'])){
                        echo'
                        <li class="nav-item">
                            <a class="nav-link text-body" data-bs-toggle="tab" href="#showCV" role="tab">
                                <i class="far fa-user"></i> Portfolio
                            </a>
                        </li>';
                    }
                    echo'
                    <li class="nav-item">
                        <a class="nav-link text-body" data-bs-toggle="tab" href="#eduDetail" role="tab">
                            <i class="far fa-user"></i> Education
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-body" data-bs-toggle="tab" href="#bankDetail" role="tab">
                            <i class="far fa-user"></i> Bank Detail
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
                            <input type="hidden" name="org_reg" value="'.$row['org_reg'].'">
                            <div class="row mb-2">
                                <div class="col">
                                    <label for="adm_fullnameInput">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="adm_fullname" class="form-control" required id="adm_fullnameInput" placeholder="Enter your firstname" value="'.$_SESSION['userlogininfo']['LOGINNAME'].'">
                                </div>
                                <div class="col">
                                    <label for="cleave-phone">Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="adm_phone" class="form-control" required id="cleave-phone" placeholder="xxxx-xxxxxxx"  value="'.$_SESSION['userlogininfo']['LOGINPHONE'].'">
                                </div>
                            </div>                            
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label">Telephone</label>
                                    <input type="text" name="org_telephone" value="'.$row['org_telephone'].'" class="form-control">
                                </div>
                                <div class="col">
                                    <label class="form-label">What\'s App</label>
                                    <input type="text" name="org_whatsapp" value="'.$row['org_whatsapp'].'" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label for="adm_emailInput">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="adm_email" class="form-control" required id="adm_emailInput" value="'.$_SESSION['userlogininfo']['LOGINEMAIL'].'" >
                                </div>
                                <div class="col">
                                    <label for="org_cityInput">City <span class="text-danger">*</span></label>
                                    <input type="text" name="org_city" class="form-control" required id="org_cityInput" value="'.$row['org_city'].'" >
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label>CV File</label>
                                    <input type="file" name="cv_file" accept="jpg, .jpeg, .png, .pdf" class="form-control" data-bs-height="100"/>
                                    <span class="text-danger">JPG, JPEG, PNG, PDF</span>
                                </div>
                                <div class="col">
                                    <label>Profile Picture</label>
                                    <input type="file" name="adm_photo" accept="jpg, .jpeg, .png" class="form-control" data-bs-height="100"/>
                                    <span class="text-danger">JPG, JPEG, PNG</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label for="cv_urlInput">CV Url Link</label>
                                    <input type="url" name="cv_url" class="form-control" id="cv_urlInput" value="'.$row['cv_url'].'" >
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label">Address </label>
                                    <textarea class="form-control" name="org_address">'.$row['org_address'].'</textarea>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary" name="submit_profile">Update Profile</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>';
                    if(!empty($row['cv_file']) || !empty($row['cv_url'])){
                        echo'
                        <div class="tab-pane" id="showCV" role="tabpanel">';
                            if(!empty($row['cv_file'])){
                                echo'
                                <label class="form-label">CV FIle</label>
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <div class="col-auto">
                                            <div class="d-flex border align-items-center  border-dashed p-2 rounded position-relative">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-file-pdf-line fs-24 text-danger"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h6 class="mb-0"><a href="'.SITE_URL.'uploads/files/organization_cv/'.$row['cv_file'].'" target="_blank" class="stretched-link">'.$row['cv_file'].'</a></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                            }
                            if(!empty($row['cv_url'])){
                                echo'
                                <label class="form-label">CV Url</label>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-auto">
                                            <div class="d-flex border align-items-center  border-dashed p-2 rounded position-relative">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-links-line fs-24 text-danger"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h6 class="mb-0"><a href="'.$row['cv_url'].'" target="_blank" class="stretched-link">'.$row['cv_url'].'</a></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                            }
                            echo'
                        </div>';
                    }                    
                    echo'
                    <div class="tab-pane" id="eduDetail" role="tabpanel">
                        <form action="'.moduleName().'.php" autocomplete="off" method="post" accept-charset="utf-8"> 
                            <input type="hidden" name="id" value="'.$rowEdu['id'].'">
                            
                            <div class="row mb-2">
                                <div class="col">
                                    <label for="nameInput">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" required id="nameInput" value="'.$rowEdu['name'].'">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col">
                                    <label for="qualificationInput">Qualification <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="qualification" required id="qualificationInput" value="'.$rowEdu['qualification'].'">
                                </div>
                                <div class="col">
                                    <label for="fieldExpertiseInput">Field Expertise</label>
                                    <input type="text" class="form-control" name="field_expertise" id="fieldExpertiseInput" value="'.$rowEdu['field_expertise'].'">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col">
                                    <label for="experienceInput">Experience (Years)</label>
                                    <input type="number" min="0" class="form-control" name="experience" id="experienceInput" value="'.$rowEdu['experience'].'">
                                </div>
                                <div class="col">
                                    <label for="cityInput">City</label>
                                    <input type="text" class="form-control" name="city" id="cityInput" value="'.$rowEdu['city'].'">
                                </div>
                            </div>

                            <!-- Certifications -->
                            <div class="row mb-2">
                                <div class="col">
                                    <label>Certifications (if any)</label>
                                    <div id="certificationGroup">';
                                        foreach ($certifications as $index => $certification):
                                            echo '
                                            <div class="input-group mb-1">
                                                <input type="text" name="certifications[]" class="form-control" value="'.htmlspecialchars($certification).'">';
                                                if ($index > 0):
                                                    echo '<button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button>';
                                                endif;
                                            echo '
                                            </div>';
                                        endforeach;
                                    echo '
                                    </div>
                                    <button type="button" class="btn btn-sm btn-secondary mt-1" onclick="addRow(\'certificationGroup\', \'certifications[]\')">Add More</button>
                                </div>
                            </div>

                            <!-- Skills and Strengths -->
                            <div class="row mb-2">
                                <div class="col">
                                    <label>Skills and Strengths</label>
                                    <div id="skillGroup">';
                                        foreach ($skills as $index => $skill):
                                            echo '
                                            <div class="input-group mb-1">
                                                <input type="text" name="skills[]" class="form-control" value="'.htmlspecialchars($skill).'">';
                                                if ($index > 0):
                                                    echo '<button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button>';
                                                endif;
                                            echo '
                                            </div>';
                                        endforeach;
                                    echo '
                                    </div>
                                    <button type="button" class="btn btn-sm btn-secondary mt-1" onclick="addRow(\'skillGroup\', \'skills[]\')">Add More</button>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col">
                                    <div class="hstack gap-2 justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary" name="academic_details">Save Details</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <script>
                            function addRow(containerId, inputName) {
                                const container = document.getElementById(containerId);
                                const wrapper = document.createElement("div");
                                wrapper.className = "input-group mb-1";

                                const input = document.createElement("input");
                                input.type = "text";
                                input.name = inputName;
                                input.className = "form-control";

                                const removeBtn = document.createElement("button");
                                removeBtn.type = "button";
                                removeBtn.className = "btn btn-danger";
                                removeBtn.textContent = "Remove";
                                removeBtn.onclick = function () {
                                    removeRow(removeBtn, containerId);
                                };

                                wrapper.appendChild(input);
                                wrapper.appendChild(removeBtn);
                                container.appendChild(wrapper);
                            }

                            function removeRow(button, containerId = null) {
                                const parent = button.parentNode;
                                const container = containerId ? document.getElementById(containerId) : parent.parentNode;
                                const items = container.getElementsByClassName("input-group");

                                if (items.length > 1) {
                                    container.removeChild(parent);
                                } else {
                                    alert("At least one field must remain.");
                                }
                            }
                        </script>

                    </div>
                    <div class="tab-pane" id="bankDetail" role="tabpanel">
                        <form action="'.moduleName().'.php" autocomplete="off" method="post" accept-charset="utf-8"> 
                            <input type="hidden" name="id" value="'.$rowBank['id'].'">
                            <div class="row mb-2">
                                <div class="col">
                                    <label for="id_bankInput">Bank <span class="text-danger">*</span></label>
                                    <select class="form-control" data-choices name="id_bank" required>
                                        <option value=""> Choose one</option>';
                                        foreach(get_listBanks() as $key => $val):
                                            echo'<option value="'.$key.'" '.($key == $rowBank['id_bank'] ? 'selected' : '').'>'.$val.'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="account_titleInput">Account Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="account_title" required id="account_titleInput" value="'.$rowBank['account_title'].'">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label for="account_numberInput">Account Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="account_number" required id="account_numberInput" value="'.$rowBank['account_number'].'">
                                </div>
                                <div class="col">
                                    <label for="account_ibanInput">Account IBAN</label>
                                    <input type="text" class="form-control" name="account_iban" id="account_ibanInput" value="'.$rowBank['account_iban'].'">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <div class="hstack gap-2 justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary" name="bank_details">Save Changes</button>
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
</div>';
?>