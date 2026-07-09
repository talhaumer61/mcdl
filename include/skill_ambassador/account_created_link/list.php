<?php
$search_word    = '';
$search_query   = ' AND s.id_org = '.$_SESSION['userlogininfo']['LOGINORGANIZATIONID'].'';
$filters        = 'search&'.$redirection.'';

if($_SESSION['userlogininfo']['LOGINTYPE'] == 4){
    $condition  =   [ 
                        'select'        =>  'GROUP_CONCAT(o.org_id) as sub_members',
                        'join'          =>  'INNER JOIN '.ADMINS.' AS a ON a.adm_id = o.id_loginid AND a.adm_status = 1 AND a.is_deleted = 0',
                        'where' 	    =>  [
                                                'a.adm_logintype'   => 8,
                                                'a.adm_type'        => 5,
                                                'o.org_type'        => 2,
                                                'o.org_status'      => 1,
                                                'o.is_deleted'      => 0,
                                                'o.parent_org'      => cleanvars($_SESSION['userlogininfo']['LOGINORGANIZATIONID']),
                                            ],
                        'return_type'  =>  'single',
    ]; 
    $SUB_MEMBERS = $dblms->getRows(SKILL_AMBASSADOR.' AS o', $condition, $sql);
    if($SUB_MEMBERS['sub_members'] != ''){
        $search_query = ' AND (s.id_org = '.$_SESSION['userlogininfo']['LOGINORGANIZATIONID'].' OR s.id_org IN ('.$SUB_MEMBERS['sub_members'].'))';
    }
}

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= " AND (a.adm_email LIKE '%".$search_word."%' OR a.adm_fullname LIKE '%".$search_word."%' OR a.adm_username LIKE '%".$search_word."%')";
    $filters        .= '&search_word='.$search_word.'';
}

$condition  =   [
                    'select'        =>  'o.org_name, s.id_org, s.city_name, a.adm_phone, a.adm_photo, a.adm_email, a.adm_fullname, a.adm_username, a.adm_status, COUNT(ec.secs_id) AS curs_count',
                    'join'          =>  'INNER JOIN '.ADMINS.' AS a ON s.std_loginid = a.adm_id AND a.adm_status = 1 AND a.is_deleted = 0
                                         INNER JOIN '.SKILL_AMBASSADOR.' o ON o.org_id = s.id_org AND o.org_status = 1 AND o.is_deleted = 0
                                         LEFT JOIN '.ENROLLED_COURSES.' AS ec ON ec.id_std = s.std_id AND ec.id_org = s.id_org AND ec.secs_status = 1',
                    'where'         =>  [
                                            's.is_deleted'   =>  0,
                                            's.std_status'   =>  1,
                                            // 's.id_org'       =>  $_SESSION['userlogininfo']['LOGINORGANIZATIONID'],
                                        ],
                    'search_by'     =>  ''.$search_query.'',
                    'group_by'      =>  'a.adm_id',
                    'return_type'   =>  'count',
                ];                
$count = $dblms->getRows(STUDENTS.' AS s ', $condition, $sql);
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>'.moduleName(false).' List</h5>
        </div>
    </div>
    <div class="card-body">        
        <div class="row justify-content-end">
            <div class="col-3">
                <form class="form-horizontal" id="form" enctype="multipart/form-data" method="get" autocomplete="off" accept-charset="utf-8">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search..." id="searchInput" name="search_word" value="'.$search_word.'">
                        <button type="submit" class="btn btn-primary btn-sm" name="search"><i class="ri-search-2-line"></i></button>
                    </div>
                </form>
            </div>
        </div>';        
        if ($page == 0 || empty($page)) { $page = 1; }
        $prev       = $page - 1;
        $next       = $page + 1;
        $lastpage   = ceil($count / $Limit);   //lastpage = total pages // items per page, rounded up
        $lpm1       = $lastpage - 1;
        
        $condition['order_by']      = " a.adm_id DESC LIMIT " . ($page - 1) * $Limit . ",$Limit";
        $condition['return_type']   = 'all';
        
        $rowslist = $dblms->getRows(STUDENTS.' AS s ', $condition);
        if ($rowslist) {
            echo'
            <div class="table-responsive table-card">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="10">Sr.</th>
                            <th>Student</th>
                            <th>Full Name</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Referral</th>
                            <th class="text-center">Total Courses</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                        foreach ($rowslist as $row) {
                            $srno++;
                            $adm_photo = ((!empty($row['adm_photo']) && file_exists('uploads/images/admin/'.$row['adm_photo'])) ? 'uploads/images/admin/'.$row['adm_photo'].'' : 'uploads/default.png');
                            echo '
                            <tr style="vertical-align: middle;">
                                <td class="text-center">'.$srno.'</td>
                                <td>
                                    <span>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm bg-light rounded p-1">
                                                    <img src="'.$adm_photo.'" alt="" class="img-fluid d-block">
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fs-14 mb-2">'.$row['adm_email'].'</h5>
                                                <p class="text-muted mb-0">@'.$row['adm_username'].'</p>
                                            </div>
                                        </div>
                                    </span>
                                </td>
                                <td>'.$row['adm_fullname'].'</td>
                                <td>'.$row['adm_phone'].'</td>
                                <td>'.$row['city_name'].'</td>
                                <td>'.($row['id_org'] == $_SESSION['userlogininfo']['LOGINORGANIZATIONID'] ? 'Self' : $row['org_name']).'</td>
                                <td class="text-center">'.$row['curs_count'].'</td>
                            </tr>';
                        }
                        echo'
                    </tbody>
                </table>';                
                include_once('include/pagination.php');
                echo'
            </div>';
        } else {
            echo'
            <div class="noresult" style="display: block">
                <div class="text-center">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
                    </lord-icon>
                    <h5 class="mt-2">Sorry! No Record Found</h5>
                    <p class="text-muted">We\'ve searched '.$count.' Record and We did not find any for you search.</p>
                </div>
            </div>';
        }
        echo'
    </div>
</div>';
?>

