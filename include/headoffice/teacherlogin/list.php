<?php
$search_word    = '';
$search_query   = ' AND is_teacher IN (2,3)';
$filters        = 'search&'.$redirection.'';

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= 'AND (adm_fullname LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}

$condition = array(
                     'select'       =>  'adm_id, adm_status, adm_fullname, adm_username, adm_phone, adm_email, adm_photo'
                    ,'where'        =>  array(
                                                 'is_deleted'       => 0
                                                ,'adm_logintype'    => 3
                                                ,'adm_type'         => 3
                                            )
                    ,'search_by'    =>  ''.$search_query.''
                    ,'order_by'     =>  'adm_id DESC'
                    ,'return_type'  =>  'count'
);
$count = $dblms->getRows(ADMINS, $condition);
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>Login List</h5>
            <div class="flex-shrink-0">
                <a class="btn btn-primary btn-sm" onclick="showAjaxModalZoom(\'include/modals/teacherlogin/add.php\');"><i class="ri-add-circle-line align-bottom me-1"></i>Teacher Login</a>
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
        $prev       = $page - 1;
        $next       = $page + 1;
        $lastpage   = ceil($count / $Limit);   //lastpage = total pages // items per page, rounded up
        $lpm1       = $lastpage - 1;

        $condition['order_by'] = "adm_id DESC LIMIT " . ($page - 1) * $Limit . ",$Limit";
        $condition['return_type'] = 'all';

        $rowsList = $dblms->getRows(ADMINS, $condition);
        if ($rowsList) {
            echo'
            <div class="table-responsive table-card">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40" class="text-center">Sr.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th width="70" class="text-center">Status</th>
                            <th width="100" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                        foreach ($rowsList as $row) {
                            $srno++;
                            echo '
                            <tr style="vertical-align: middle;">
                                <td class="text-center">'.$srno.'</td>
                                <td>
                                    <span>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm bg-light rounded p-1">
                                                    <img src="uploads/images/admin/'.$row['adm_photo'].'" alt="" class="img-fluid d-block">
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fs-14 mb-1">
                                                    <a href="apps-ecommerce-product-details.html" class="text-dark">'.$row['adm_fullname'].'</a>
                                                </h5>
                                                <p class="text-muted mb-0">Username : <span class="fw-medium">'.$row['adm_username'].'</span></p>
                                            </div>
                                        </div>
                                    </span>
                                </td>
                                <td>'.$row['adm_email'].'</td>
                                <td>'.$row['adm_phone'].'</td>
                                <td class="text-center">'.get_status($row['adm_status']).'</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                            <li><a class="dropdown-item" onclick="showAjaxModalZoom(\'include/modals/teacherlogin/edit.php?adm_id='.$row['adm_id'].'\');"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                            <li><a class="dropdown-item" onclick="confirm_modal(\'teacherlogin.php?deleteid='.$row['adm_id'].'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
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