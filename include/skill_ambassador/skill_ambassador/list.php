<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search&'.$redirection.'';

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= 'AND (o.org_name LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}

$condition  =   [ 
                    'select'        =>  'o.org_id, o.org_status, o.org_percentage, o.id_loginid, o.org_profit_percentage, a.adm_photo, o.org_name, o.org_reg, o.org_phone, o.org_telephone, o.org_whatsapp, o.org_referral_link, o.org_link_from, o.org_link_to, o.org_type, o.allow_add_members, o.parent_org',
                    'join'          =>  'INNER JOIN '.ADMINS.' AS a ON a.adm_id = o.id_loginid',
                    'where' 	    =>  [
                                            'a.adm_logintype'   => 8,
                                            'o.org_type'        => 2,
                                            'o.is_deleted'      => 0,
                                            'o.parent_org'      => cleanvars($_SESSION['userlogininfo']['LOGINORGANIZATIONID']),
                                        ],
                    'search_by'    =>  ''.$search_query.'',
                    'group_by'     =>  ' o.org_id ',
                    'return_type'  =>  'count',
]; 
$count = $dblms->getRows(SKILL_AMBASSADOR.' AS o', $condition);
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>'.moduleName(false).' List</h5>
            <div class="flex-shrink-0">
                <a href="?view=add" class="btn btn-primary btn-sm"><i class="ri-add-circle-line align-bottom me-1"></i>'.moduleName(false).'</a>
            </div>
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
        $prev        = $page - 1;
        $next        = $page + 1;
        $lastpage    = ceil($count / $Limit);   //lastpage = total pages // items per page, rounded up
        $lpm1        = $lastpage - 1;        

        $condition['order_by'] = "o.org_id  DESC LIMIT ".($page - 1) * $Limit.",$Limit";
        $condition['return_type'] = 'all';

        $rowsList = $dblms->getRows(SKILL_AMBASSADOR.' AS o', $condition);
        if ($rowsList) {
            echo'
            <div class="table-responsive table-card">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>                        
                            <th width="40" class="text-center">Sr.</th>
                            <th>Name</th>
                            <th width="100" class="text-center">Discount</th>
                            <th width="100" class="text-center">Profit</th>
                            <th width="200" class="text-center">Referral Link</th>
                            <th width="200" class="text-center">Contact</th>
                            <th width="50" class="text-center">Referral</th>
                            <th width="50" class="text-center">Status</th>
                            <th width="50" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $srno = (($page == 1)?0:($page-1)*$Limit);
                        foreach ($rowsList as $row) {
                            $srno++;
                            $adm_photo = ((!empty($row['adm_photo']) && file_exists('uploads/images/organization/'.$row['adm_photo'])) ? 'uploads/images/organization/'.$row['adm_photo'].'' : 'uploads/default.png');
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
                                                <h5 class="fs-14 mb-2">
                                                    <a href="" onclick="showAjaxModalView(\'include/modals/'.$rootDir.'/view.php?view_id='.$row['org_id'].'\');" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" title="View Detail">
                                                        '.$row['org_name'].'
                                                    </a>
                                                </h5>
                                                <p class="text-muted mb-0">'.get_skill_ambassador_type($row['org_type']).'</p>
                                            </div>
                                        </div>
                                    </span>
                                </td>
                                <td class="text-center">'.$row['org_percentage'].'%</td>
                                <td class="text-center">'.$row['org_profit_percentage'].'%</td>
                                <td class="text-center">'.$row['org_referral_link'].'<a class="copy-message cursor-pointer" title="Copy" onclick="copyToClipboard(\''.WEBSITE_URL.'signup/'.$row['org_referral_link'].'\');"><i class="ri-file-copy-line ms-2 text-muted align-bottom"></i></a></td>
                                <td class="text-center">
                                    <span>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-0">Phone: '.$row['org_phone'].'</p>
                                                <p class="text-muted mb-0">Telephone: '.$row['org_telephone'].'</p>
                                                <p class="text-muted mb-0">Whatsapp: '.$row['org_whatsapp'].'</p>
                                            </div>
                                        </div>
                                    </span>
                                </td>
                                <td class="text-center">'.((date('Y-m-d', strtotime($row['org_link_from'])) <= date('Y-m-d') && date('Y-m-d', strtotime($row['org_link_to'])) >= date('Y-m-d'))?'<span class="badge badge-gradient-success">Allowed</span>':'<span class="badge badge-gradient-danger">Not Allowed</span>').'</td>
                                <td class="text-center">'.get_leave($row['org_status']).'</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                            <li><a class="dropdown-item" onclick="showAjaxModalView(\'include/modals/'.$rootDir.'/view.php?view_id='.$row['org_id'].'\');" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="mdi mdi-eye align-bottom me-2 text-muted"></i> View Detail</a></li>
                                            <li><a class="dropdown-item" onclick="showAjaxModalZoom(\'include/modals/'.$rootDir.'change_password/edit.php?adm_id='.$row['id_loginid'].'\');"><i class="ri-lock-password-line align-bottom me-2 text-muted"></i> Change Password</a></li>
                                            <li><a class="dropdown-item" href="?edit_id='.$row['org_id'].'"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                            <li><a class="dropdown-item" onclick="confirm_modal(\''.moduleName().'.php?deleteid='.$row['org_id'].'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
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
                    <!--<p class="text-muted">We\'ve searched more than 150+ Orders We did not find any orders for you search.</p>-->
                </div>
            </div>';
        }
        echo'
    </div>
</div>';
?>