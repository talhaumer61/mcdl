<?php 
if($_SESSION['userlogininfo']['LOGINTYPE'] == 1 && LMS_VIEW == 'account_created' && !empty(LMS_EDIT_ID)){
    $_SESSION['userlogininfo']['LOGINORGANIZATIONID'] = get_dataHashingOnlyExp(LMS_EDIT_ID, false);
    $redirection = 'view='.LMS_VIEW.'&edit_id='.LMS_EDIT_ID;
}

$rootDir = 'account_created_link/';
echo' 
<title>'.moduleName(false).' - '.TITLE_HEADER.'</title>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">'.moduleName(false).'</h4> 
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>';
                            if(!empty(LMS_EDIT_ID)){
                                echo'<li class="breadcrumb-item"><a href="skill_ambassador.php" class="text-primary">Skill Ambassador</a></li>';
                            }
                            echo'
                            <li class="breadcrumb-item"><a href="'.moduleName().'.php?'.$redirection.'" class="text-primary">'.moduleName(false).'</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-lg-12">';
                include_once ($rootDir.'/list.php');
                echo'
            </div>
        </div>
    </div>
</div>';
?>