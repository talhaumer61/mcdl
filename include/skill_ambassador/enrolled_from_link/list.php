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
                    'select'        =>  'po.org_name as parent_name, po.org_profit_percentage as parent_profit, o.org_name, o.org_type, s.id_org, a.adm_photo, a.adm_email, a.adm_username, a.adm_status, o.org_profit_percentage, f.challan_id, f.challan_no, f.paid_amount, f.currency_code, f.id_enroll',
                    'join'          =>  'INNER JOIN '.ADMINS.' AS a ON s.std_loginid = a.adm_id
                                         INNER JOIN '.ENROLLED_COURSES.' AS ec ON ec.id_std = s.std_id AND ec.id_org = s.id_org AND ec.secs_status = 1 AND ec.is_deleted = 0
                                         INNER JOIN '.SKILL_AMBASSADOR.' o ON o.org_id = s.id_org AND o.org_status = 1 AND o.is_deleted = 0
                                         LEFT JOIN '.SKILL_AMBASSADOR.' po ON po.org_id = o.parent_org AND po.org_status = 1 AND po.is_deleted = 0
                                         INNER JOIN '.CHALLANS.' AS f ON f.id_enroll = ec.secs_id',
                    'where'         =>  [
                                            's.is_deleted'   =>  0,
                                            's.std_status'   =>  1,
                                        ],
                    'search_by'     =>  ' '.$search_query.' ',
                    'group_by'      =>  ' a.adm_id, f.challan_id',
                    'return_type'   =>  'count',
                ];
$count = $dblms->getRows(STUDENTS.' AS s ', $condition);
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
                        <input type="text" class="form-control" placeholder="Search..." name="search_word" value="'.$search_word.'">
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
                            <th class="text-center" width="100">Challan No</th>
                            <th class="text-center" width="100">Enrollments</th>
                            <th>Referral</th>
                            <th class="text-center">Paid Challan</th>';
                            if($SUB_MEMBERS){
                                echo'<th class="text-center">Member Profit</th>';
                            }
                            echo'
                            <th class="text-center">Profit</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                        foreach ($rowslist as $row) {
                            $countEnroll = count(explode(",", $row['id_enroll']));
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
                                <td class="text-center"><a href="javascript();" title="View Detail" onclick="showAjaxModalView(\'include/modals/challans/view.php?challan_id='.$row['challan_id'].'\');" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">'.$row['challan_no'].'</a></td>
                                <td class="text-center">'.$countEnroll.'</td>
                                <td>'.($row['id_org'] == $_SESSION['userlogininfo']['LOGINORGANIZATIONID'] ? 'Self' : $row['org_name']).'</td>';
                                if(!$SUB_MEMBERS){
                                    if($_SESSION['userlogininfo']['LOGINORGANIZATIONID'] == $row['id_org']){
                                        echo '
                                        <td class="text-center text-success">'.$row['currency_code'].' '.$row['paid_amount'].'</td>
                                        <td class="text-center text-success">'.$row['currency_code'].' '.(($row['paid_amount']/100)*$row['org_profit_percentage']).'</td>';
                                    } 
                                } else if($SUB_MEMBERS){
                                    echo '
                                    <td class="text-center text-success">'.$row['currency_code'].' '.$row['paid_amount'].'</td>
                                    <td class="text-center text-success">'.($row['parent_profit'] != '' ? $row['currency_code'].' '.(($row['paid_amount']/100)*($row['parent_profit'] - $row['org_profit_percentage'])) : '').'</td>
                                    <td class="text-center text-success">'.$row['currency_code'].' '.(($row['paid_amount']/100)*$row['org_profit_percentage']).'</td>';
                                }
                                echo'
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