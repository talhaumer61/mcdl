<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search&'.$redirection.'';

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= 'AND (emply_name LIKE "%'.$search_word.'%" OR emply_email LIKE "%'.$search_word.'%" OR emply_fathername LIKE "%'.$search_word.'%" OR emply_cnic LIKE "%'.$search_word.'%" OR emply_phone LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}

$condition = array ( 
                         'select'       =>  "emply_id, emply_request, emply_photo, emply_status, emply_ordering, emply_name, emply_fathername, emply_dob, emply_cnic, emply_religion, emply_phone, emply_email, emply_gender, emply_blood, emply_marital, id_added"
                        ,'where' 	    =>  array( 
                                                    'is_deleted'    => 0
                                                )
                        ,'search_by'    =>  ''.$search_query.''
                        ,'order_by'     =>  'emply_id DESC'
                        ,'return_type'  =>  'count'
                   ); 
$count = $dblms->getRows(EMPLOYEES, $condition);
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>'.moduleName(false).' List</h5>
            <div class="flex-shrink-0">
                <a class="btn btn-primary btn-sm" href="'.moduleName().'.php?add"><i class="ri-add-circle-line align-bottom me-1"></i>'.moduleName(false).'</a>
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

        $condition['order_by'] = "emply_id DESC LIMIT ".($page - 1) * $Limit.",$Limit";
        $condition['return_type'] = 'all';

        $rowsList = $dblms->getRows(EMPLOYEES, $condition);
        if ($rowsList) {
            echo'
            <div class="table-responsive table-card">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr style="vertical-align: middle;">     
                            <th width="40" class="text-center">Sr.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>CNIC</th>
                            <th>DOB</th>
                            <th>Religion</th>
                            <th width="40" class="text-center">Request</th>
                            <th width="70" class="text-center">Status</th>
                            <th width="100" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                        foreach ($rowsList as $row) {
                            $emply_photo = ((!empty($row['emply_photo']) && file_exists('uploads/images/employees/'.$row['emply_photo'])) ? 'uploads/images/employees/'.$row['emply_photo'].'' : 'uploads/default.png');
                            
                            $srno++;
                            echo '
                            <tr style="vertical-align: middle;">
                                <td class="text-center">'.$srno.'</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-sm bg-light rounded p-1">
                                                <img src="'.$emply_photo.'" class="img-fluid d-block" style="height=:100%;">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="fs-14 mb-1">'.$row['emply_name'].'</h5>
                                            <p class="text-muted mb-0"><span class="fw-medium">'.$row['emply_fathername'].'</span></p>
                                        </div>
                                    </div>
                                </td>
                                <td>'.$row['emply_email'].'</td>
                                <td>'.$row['emply_phone'].'</td>
                                <td>'.$row['emply_cnic'].'</td>
                                <td>'.$row['emply_dob'].'</td>
                                <td><span class="badge badge-soft-primary">'.get_religion($row['emply_religion']).'</span></td>
                                <td class="text-center">'.get_leave($row['emply_request']).'</td>
                                <td class="text-center">'.get_status($row['emply_status']).'</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                            <li><a class="dropdown-item" onclick="showAjaxModalView(\'include/modals/employees/view.php?emply_id='.$row['emply_id'].'\');" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>';
                                            if($row['emply_request'] == '2'){
                                                echo'
                                                <li><a class="dropdown-item text-success" onclick="showAjaxModalZoom(\'include/modals/employees/approve_reject.php?emply_id='.$row['emply_id'].'&emply_name='.$row['emply_name'].'&adm_id='.$row['id_added'].'&emply_request=1\');"><i class="ri-send-plane-fill align-bottom me-2"></i> Approve Instructor</a></li>
                                                <li><a class="dropdown-item text-danger" onclick="showAjaxModalZoom(\'include/modals/employees/approve_reject.php?emply_id='.$row['emply_id'].'&emply_name='.$row['emply_name'].'&adm_id='.$row['id_added'].'&emply_request=3\');"><i class="ri-close-circle-line align-bottom me-2"></i> Reject & Close</a></li>';
                                            }
                                            echo'
                                            <li><a class="dropdown-item" href="?id='.$row['emply_id'].'"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                            <li><a class="dropdown-item" onclick="confirm_modal(\'employees.php?deleteid='.$row['emply_id'].'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
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