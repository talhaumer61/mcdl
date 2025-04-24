<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search&'.$redirection.'';

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= 'AND (d.discount_name LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}

if (!empty($_GET['id_type'])) {
    $id_type         = $_GET['id_type'];
    $search_query   .= 'AND d.id_type = "'.$id_type.'"';
    $filters        .= '&id_type='.$id_type.'';
}

$condition = array ( 
                    'select'        =>  'd.discount_id, d.id_type, d.discount_status, d.discount_name, d.discount_from, d.discount_to'
                    ,'join'         =>  'INNER JOIN '.DISCOUNT_DETAIL.' AS dd ON d.discount_id = dd.id_setup'
                    ,'where' 	    =>  array( 
                                                'd.is_deleted'    => 0
                                            )
                    ,'search_by'    =>  ''.$search_query.''
                    ,'group_by'     =>  ' d.discount_id '
                    ,'return_type'  =>  'count'
                   ); 
$count = $dblms->getRows(DISCOUNT.' AS d', $condition);
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
            <div class="col-5">
                <form class="form-horizontal" id="form" enctype="multipart/form-data" method="get" autocomplete="off" accept-charset="utf-8">
                    <div class="input-group mb-3">
                        <select class="form-control" name="id_type" id="id_type" data-choices>
                            <option value="">Choose one</option>';
                            foreach (get_offering_type() as $key => $status):
                                if(in_array($key, [3,4])){
                                    echo'<option value="'.$key.'" '.($key == $id_type ? 'selected' : '').'>'.$status.'</option>';
                                }
                            endforeach;
                            echo'
                        </select>
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

        $condition['order_by'] = "discount_id DESC LIMIT ".($page - 1) * $Limit.",$Limit";
        $condition['return_type'] = 'all';

        $rowsList = $dblms->getRows(DISCOUNT.' AS d', $condition);
        if ($rowsList) {
            echo'
            <div class="table-responsive table-card">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>                        
                            <th width="40" class="text-center">Sr.</th>
                            <th>Name</th>
                            <th width="200" class="text-center">Type</th>
                            <th width="200" class="text-center">Duration</th>
                            <th width="50" class="text-center">Status</th>
                            <th width="50" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $srno = (($page == 1)?0:($page-1)*$Limit);
                        foreach ($rowsList as $row) {
                            $srno++;
                            echo '
                            <tr style="vertical-align: middle;">
                                <td class="text-center">'.$srno.'</td>
                                <td><h5 class="fs-14 mb-1">'.$row['discount_name'].'</h5></td>
                                <td class="text-center">'.get_enroll_type($row['id_type']).'</td>
                                <td class="text-center">'.date('d M, Y', strtotime(cleanvars($row['discount_from']))).' to '.date('d M, Y', strtotime(cleanvars($row['discount_to']))).'</td>
                                <td class="text-center">'.get_status($row['discount_status']).'</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                            <li><a class="dropdown-item" href="?edit_id='.$row['discount_id'].'"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                            <li><a class="dropdown-item" onclick="confirm_modal(\''.moduleName().'.php?deleteid='.$row['discount_id'].'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
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