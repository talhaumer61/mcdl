<?php
// MENU
$referralMenu   =   [
                        'referral_control'          => [ 'title' => 'Referral Control'          , 'view' => 'referral_control'          , 'icon' => 'ri-book-open-line'       , 'color' => 'primary'],
                        'referred_control'          => [ 'title' => 'Referred Control'          , 'view' => 'referred_control'          , 'icon' => 'ri-calendar-todo-line'   , 'color' => 'info'],
                        'referred_from_you'         => [ 'title' => 'Referred From You'         , 'view' => 'referred_from_you'         , 'icon' => 'ri-file-copy-2-line'     , 'color' => 'success'],
                        'enrolled_from_referred'    => [ 'title' => 'Enrolled From Referred'    , 'view' => 'enrolled_from_referred'    , 'icon' => 'ri-file-copy-2-line'     , 'color' => 'danger'],
                    ];
// URL VARIABLE TO MANAGE REDIRECTION WITHIN MODULE
if (!empty($_GET['id']) && !empty($_GET['view'])) {
    $redirection = "id=".cleanvars($_GET['id'])."&view=".LMS_VIEW."";
} else {
    $redirection = "";
}
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
                            if (!empty($_GET['view'])) {
                                echo'
                                <li class="breadcrumb-item"><a href="'.moduleName().'.php" class="text-primary">'.moduleName(false).'</a></li>';
                            }
                            echo'
                            <li class="breadcrumb-item"><a href="'.moduleName().'.php'.(!empty($redirection)?$redirection:'').'" class="text-primary">'.moduleName((!empty(LMS_VIEW)?LMS_VIEW:false)).'</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>';
        if (empty($_GET['view'])) {
            $condition  =   [ 
                                'select' 	    => 'c.curs_id, c.curs_name, c.curs_code',
                                'join'          => 'INNER JOIN '.COURSES.' AS c ON FIND_IN_SET(c.curs_id, rc.id_curs) AND c.curs_status = 1 AND c.is_deleted = 0 ',
                                'where' 	    => [
                                                        'rc.ref_status'     =>  1,
                                                        'rc.is_deleted'     =>  0,
                                                ],
                                'search_by'     => ' AND rc.ref_date_time_from < "'.date('Y-m-d G:i:s').'" AND rc.ref_date_time_to > "'.date('Y-m-d G:i:s').'" AND FIND_IN_SET('.$_SESSION['userlogininfo']['LOGINIDA'].', rc.id_user) ',
                                'order_by'      => ' c.curs_id DESC ',
                                'return_type'   => 'all',
                            ]; 
            $REFERRAL_CONTROL = $dblms->getRows(REFERRAL_CONTROL.' AS rc', $condition);
            echo'
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-5">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>'.moduleName(false).'</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">';
                                if ($REFERRAL_CONTROL) {
                                    foreach ($REFERRAL_CONTROL as $curs) {
                                        echo'
                                        <div class="col-12" >
                                            <div class="card mb-3">
                                                <div class="card-header alert-dark">
                                                    <h5 class="mb-0">'.$curs['curs_code'].' - '.$curs['curs_name'].'</h5>
                                                </div>
                                                <div class="card-body border">
                                                    <div class="row">';
                                                        foreach ($referralMenu as $key => $value) {
                                                            echo'                                    
                                                            <div class="col">
                                                                <a class="dropdown-icon-item" href="?id='.$curs['curs_id'].'&view='.$key.'">
                                                                    <i class="'.$value['icon'].' text-'.$value['color'].'" style="font-size: 2.5rem;"></i>
                                                                    <span>'.$value['title'].'</span>
                                                                </a>
                                                            </div>';
                                                        }
                                                        echo'
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                                    }
                                } else {
                                    echo'
                                    <div class="noresult" style="display: block">
                                        <div class="text-center">
                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
                                            </lord-icon>
                                            <h5 class="mt-2">Sorry! No Allocated Courses Found.</h5>
                                            <!--<p class="text-muted">We\'ve searched more than 150+ Orders We did not find any orders for you search.</p>-->
                                        </div>
                                    </div>';
                                }
                                echo'
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        } else {
            // REFERRAL_CONTROL
            $condition  =   [
                                'select'       =>  'rc.ref_id, c.curs_id, c.curs_name, c.curs_code',
                                'join'         =>  'INNER JOIN '.COURSES.' AS c ON c.curs_id = '.cleanvars($_GET['id']).' AND c.curs_status = 1 AND c.is_deleted = 0',
                                'where'        =>  [
                                                        'rc.is_deleted' => 0,
                                                        'rc.ref_status' => 1,
                                                    ],
                                'search_by'    =>  ' AND rc.ref_date_time_from < "'.date('Y-m-d G:i:s').'" AND rc.ref_date_time_to > "'.date('Y-m-d G:i:s').'" AND FIND_IN_SET('.cleanvars($_GET['id']).', rc.id_curs) AND FIND_IN_SET('.cleanvars($_SESSION['userlogininfo']['LOGINIDA']).', rc.id_user)',
                                'return_type'  =>  'single',
                            ];
            $REFERRAL_CONTROL = $dblms->getRows(REFERRAL_CONTROL.' AS rc', $condition);
            if ($REFERRAL_CONTROL) {
                echo'
                <div class="row mb-5">
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ri-menu-4-fill align-bottom me-1"></i>'.moduleName(false).' Menu</h5>
                            </div>
                            <div class="card-body p-0"> 
                                <ul class="list-group">';
                                    foreach ($referralMenu as $key => $value) {
                                        echo'
                                        <li class="list-group-item"><a href="?id='.cleanvars($REFERRAL_CONTROL['curs_id']).'&view='.$key.'" class="text-'.(LMS_VIEW == $key ? 'danger' : 'dark').'"><i class="'.$value['icon'].' align-middle lh-1 me-2"></i>'.$value['title'].'</a></li>';                                            
                                    }
                                    echo'
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">';
                        foreach ($referralMenu as $key => $value) {
                            if(LMS_VIEW == $key){
                                $iconPg = $value['icon'];
                                include_once (moduleName().'/'.$key.'.php');
                            }
                        }
                        echo'
                    </div>
                </div>';
            }else{
                header('Location: '.moduleName().'.php');
            }
        }
        echo'
    </div>
</div>';