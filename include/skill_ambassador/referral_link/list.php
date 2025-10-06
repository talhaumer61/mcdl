<?php
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>'.moduleName(false).' List</h5>
        </div>
    </div>
    <div class="card-body">';
        if (!empty($_SESSION['userlogininfo']['LOGINORGANIZATIONID'])) {
            $condition  =   [ 
                                'select'        =>  'o.org_percentage, o.org_profit_percentage, o.org_referral_link',
                                'where' 	    =>  [
                                                        'o.org_status'      => 1,
                                                        'o.is_deleted'      => 0,
                                                        'o.org_id'          => $_SESSION['userlogininfo']['LOGINORGANIZATIONID'],
                                                    ],
                                'return_type'  =>  'single',
            ]; 
            $ORGANIZATIONS = $dblms->getRows(SKILL_AMBASSADOR.' AS o', $condition);
            echo'
            <div class="row mb-2">
                <div class="col-md-12 text-center">
                    <lord-icon
                        src="https://cdn.lordicon.com/ibydboev.json"
                        trigger="loop"
                        state="morph-open"
                        style="width:150px;height:150px"
                        colors="primary:#405189,secondary:#0ab39c">
                    </lord-icon>
                </div>
                <div class="col-md-12 text-center">
                    <h4>
                    You have access to refer 
                    <span class="badge badge-gradient-success">'.$ORGANIZATIONS['org_percentage'].'%</span> 
                    discount on any courses
                    <span class="badge badge-gradient-success">'.$ORGANIZATIONS['org_referral_link'].'</span> 
                    you can give dicount to your students.</h4>
                    <p class="text-muted">Don\'t worry this link is not harmfull. 😊</p>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col text-center">
                    <button type="button" onclick="copyToClipboard(\''.WEBSITE_URL.'signup/'.$ORGANIZATIONS['org_referral_link'].'\')" class="btn btn-outline-success btn-load">
                        <span class="bx bx-copy me-1"></span>Copy Link
                    </button>
                </div>
            </div>';
        } else {
            echo'
            <div class="noresult" style="display: block">
                <div class="text-center">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
                    </lord-icon>
                    <h5 class="mt-2">Sorry! No Record Found</h5>
                    <p class="text-muted">We\'ve searched Record and We did not find any for you search.</p>
                </div>
            </div>';
        }
        echo'
    </div>
</div>';
?>