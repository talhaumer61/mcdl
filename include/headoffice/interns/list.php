<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search';

if (!empty($_GET['search_word'])) {
    $search_word  = $_GET['search_word'];

    $search_query .= 'AND (
        full_name LIKE "%'.$search_word.'%" 
        OR father_name LIKE "%'.$search_word.'%" 
        OR cnic LIKE "%'.$search_word.'%" 
        OR phone LIKE "%'.$search_word.'%" 
        OR email LIKE "%'.$search_word.'%"
    )';
    $filters .= '&search_word='.$search_word;
}

$condition = array (
    'select' => "
        id, full_name, father_name, status, id_role, gender,
        email, phone, cnic, highest_qualification,
        photo, applied_date, selection_date, joining_date, leaving_date
    ",
    'where' => array(
        'is_deleted' => 0
    ),
    'search_by' => $search_query,
    'order_by' => 'id DESC',
    'return_type' => 'count'
);
$count = $dblms->getRows(INTERNS, $condition);
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

        $condition['order_by'] = "id DESC LIMIT ".($page - 1) * $Limit.",$Limit";
        $condition['return_type'] = 'all';

        $rowsList = $dblms->getRows(INTERNS, $condition);
        if ($rowsList) {
            echo'
            <div class="table-responsive table-card">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr style="vertical-align: middle;">
                            <th class="text-center">Sr.</th>
                            <th>Intern</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>CNIC</th>
                            <th>Role</th>
                            <th>Qualification</th>
                            <th>Status</th>
                            <th class="text-center">Dates</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                        foreach ($rowsList as $row) {                            
                            if($row['gender'] == '2'){
                                $photo = SITE_URL.'uploads/images/default_female.jpg';
                            } else {            
                                $photo = SITE_URL.'uploads/images/default_male.jpg';
                            }
                            if(!empty($row['photo']) && file_exists('uploads/images/interns/'.$row['photo'])){
                                $photo = SITE_URL.'uploads/images/interns/'.$row['photo'];
                            }
                            $srno++;
                            echo '
                            <tr style="vertical-align: middle;">
                                <td class="text-center">'.$srno.'</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-sm bg-light rounded p-1">
                                                <img src="'.$photo.'" class="img-fluid d-block" style="height=:100%;">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="fs-14 mb-1">'.$row['full_name'].'</h5>
                                            <p class="text-muted mb-0">'.$row['father_name'].'</p>
                                        </div>
                                    </div>
                                </td>
                                <td>'.$row['email'].'</td>
                                <td>'.$row['phone'].'</td>
                                <td>'.$row['cnic'].'</td>
                                <td>'.getInternRoles($row['id_role']).'</td>
                                <td>'.get_educationtypes($row['highest_qualification']).'</td>
                                <td>'.getInternStatus($row['status']).'</td>

                                <td class="text-center">
                                    <small>
                                        A: '.$row['applied_date'].'<br>
                                        S: '.$row['selection_date'].'<br>
                                        J: '.$row['joining_date'].'<br>
                                        L: '.$row['leaving_date'].'
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                            <li><a class="dropdown-item" onclick="showAjaxModalView(\'include/modals/interns/view.php?edit_id='.$row['id'].'\');" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                                            <li><a class="dropdown-item" href="?view=documents&edit_id='.$row['id'].'&full_name='.$row['full_name'].'"><i class="ri-folder-5-fill align-bottom me-2 text-muted"></i> Documents</a></li>
                                            <li><a class="dropdown-item" href="?view=edit&edit_id='.$row['id'].'"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                            <li><a class="dropdown-item text-danger" onclick="confirm_modal(\'interns.php?deleteid='.$row['id'].'\');"><i class="ri-delete-bin-fill align-bottom me-2"></i> Delete</a></li>
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