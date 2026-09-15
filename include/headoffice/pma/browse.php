<?php
$conDB = array ( 
                     'select'       =>  'table_name'
                    ,'where'        =>  array(
                                                 'table_schema' =>  LMS_NAME
                                                ,'table_type'   =>  'BASE TABLE'
                                            )
                    ,'return_type'  =>  'all'
                );
$DB_TABLES = $dblms->getRows('information_schema.tables', $conDB);

$count          = 0;
$table_exist    = 0;
$table_name     = '';
$primary_key    = '';
$is_deleted     = '';
$column_name_id = '';
$filters        = 'search';

if (!empty($_GET['table_name'])) {
    $table_name      = cleanvars($_GET['table_name']);
    $filters        .= '&table_name='.$table_name.'';

    // TABLE EXIST OR NOT
    $condition = array ( 
                         'select'        =>  'table_name'
                        ,'where' 	    =>  array( 
                                                     'table_schema' => LMS_NAME
                                                    ,'table_name'   => cleanvars($table_name)
                                                )
                        ,'return_type'  =>  'all' 
                    );
    $tableChk = $dblms->getRows('information_schema.tables', $condition);
    if($tableChk){
        $table_exist = 1;

        // ALL COL NAME
        $condition = array ( 
                            'select'        =>  'column_name'
                            ,'where' 	    =>  array( 
                                                        'table_schema' => LMS_NAME
                                                        ,'table_name'   => cleanvars($table_name)
                                                    )
                            ,'return_type'  =>  'all' 
                        );
        $column_name_all = $dblms->getRows('information_schema.columns', $condition);

        // COL NAME PRIMARY
        $condition = array ( 
                            'select'        =>  'column_name'
                            ,'where' 	    =>  array( 
                                                        'table_schema' => LMS_NAME
                                                        ,'table_name'   => cleanvars($table_name)
                                                        ,'column_key'   => 'PRI'
                                                    )
                            ,'return_type'  =>  'single' 
                        );
        $pri_key = $dblms->getRows('information_schema.columns', $condition);
        $column_name_id = $pri_key['column_name'];
    }
}

if($table_exist == 1){
    $search_by      = 'WHERE '.$column_name_id.' != "" ';

    if (!empty($_GET['primary_key'])) {
        $primary_key     = cleanvars($_GET['primary_key']);
        $filters        .= '&primary_key='.$primary_key.'';
        $search_by     .= 'AND '.$column_name_id.' IN ('.$primary_key.')';
    }

    if (!empty($_GET['is_deleted'])) {
        $is_deleted     = cleanvars($_GET['is_deleted']);
        $filters       .= '&is_deleted='.$is_deleted.'';
        $search_by     .= 'AND is_deleted = '.$is_deleted.'';
    }

    if(!empty($table_name)){    
        $condition = array ( 
                            'select'       =>  '*'
                            ,'search_by'    =>  ''.$search_by.''
                            ,'order_by'     =>  ''.$column_name_id.' ASC'
                            ,'return_type'  =>  'count'
                        );
        $count = $dblms->getRows($table_name, $condition, $sql);
    }
}
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>Browse</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row justify-content-end">
            <div class="col-4">
                '.($table_exist == 1 ? '<pre class="mt-3">Total Record Found: '.$count.'<pre>' : '').'
            </div>
            <div class="col-8">
                <form class="form-horizontal" id="form" enctype="multipart/form-data" method="get" autocomplete="off" accept-charset="utf-8">
                    <div class="input-group mb-3">
                        <input class="form-control" list="table_names" placeholder="Table Name" name="table_name" value="'.$table_name.'" title="Table Name" required>
                        <datalist id="table_names">';
                            foreach ($DB_TABLES as $key => $val):
                                echo'<option value="'.$val['table_name'].'">';
                            endforeach;
                            echo'
                        </datalist>
                        <input type="text" class="form-control" placeholder="Primary ID" name="primary_key" value="'.$primary_key.'" title="Primary Key of the table">
                        <input type="number" class="form-control" placeholder="1=deleted" max="1" name="is_deleted" value="'.$is_deleted.'" title="value 1 to get only deleted record">
                        <button type="submit" class="btn btn-primary btn-sm" name="search" title="Search"><i class="ri-search-2-line"></i></button>
                    </div>
                </form>
            </div>
        </div>';
        if ($page == 0 || empty($page)) { $page = 1; }
        $prev       = $page - 1;
        $next       = $page + 1;
        $lastpage   = ceil($count / $Limit);   //lastpage = total pages // items per page, rounded up
        $lpm1       = $lastpage - 1;

        $condition['order_by'] = "$column_name_id ASC LIMIT ".($page - 1) * $Limit.",$Limit";
        $condition['return_type'] = 'all';

        if(!empty($table_name) && $table_exist == 1){
            $rowsList = $dblms->getRows($table_name, $condition, $sql);
            if (!empty($rowsList)) {
                echo'
                <div class="table-responsive table-card">
                    <table class="table table-nowrap mb-0">
                        <thead class="table-light">
                            <tr style="vertical-align: middle;">
                                <th width="40" class="text-center text-primary">Options</th>
                                <th width="40" class="text-center text-primary">Sr.</th>';
                                foreach ($column_name_all as $key => $value) {
                                    echo'<th>'.$value['column_name'].'</th>';
                                }
                                echo'
                            </tr>
                        </thead>
                        <tbody>';
                            $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                            foreach ($rowsList as $row){
                                $get_query = 'UPDATE `'.$table_name.'` SET ';
                                $srno++;
                                echo'
                                <tr>';
                                    echo'
                                    <td class="text-center text-primary">
                                        <a class="btn btn-xs btn-danger" onclick="confirm_modal(\''.moduleName().'.php?deleteid='.$row[$column_name_id].'&table_name='.$table_name.'&col_name='.$column_name_id.'\');"><i class="ri-delete-bin-fill align-bottom"></i></a>
                                        <a class="btn btn-xs btn-info" href="'.moduleName().'.php?view=insert_update&table_name='.$table_name.'&primary_key='.$column_name_id.'&id='.$row[$column_name_id].'&method=update"><i class="ri-edit-fill align-bottom"></i></a>
                                    </td>
                                    <td class="text-center text-primary">'.$srno.'</td>';
                                    foreach ($column_name_all as $key => $value) {
                                        $print_col_name = $value['column_name'];
                                        echo'<td>'.$row[$print_col_name].'</td>';
                                        $get_query .= '`'.$print_col_name.'` = "'.$row[$print_col_name].'",';
                                    }
                                    $get_query  = rtrim($get_query, ",");
                                    $get_query .= ' WHERE `'.$column_name_id.'` = "'.$row[$column_name_id].'" ';
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
                <div class="table-responsive table-card">
                    <table class="table table-nowrap mb-0">
                        <thead class="table-light">
                            <tr style="vertical-align: middle;">
                                <th width="40" class="text-center text-primary">Structure =></th>';
                                foreach ($column_name_all as $key => $value) {
                                    echo'<th>'.$value['column_name'].'</th>';
                                }
                                echo'
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="noresult" style="display: block">
                    <div class="text-center">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
                        </lord-icon>
                        <h5 class="mt-2">Sorry! No Record Found</h5>
                        <!--<p class="text-muted">We\'ve searched more than 150+ Orders We did not find any orders for you search.</p>-->
                    </div>
                </div>';
            }
        } else {
            echo'            
            <div class="noresult" style="display: block">
                <div class="text-center">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
                    </lord-icon>
                    <h5 class="mt-2">Sorry! Table Doesn\'t Exists.</h5>
                    <!--<p class="text-muted">We\'ve searched more than 150+ Orders We did not find any orders for you search.</p>-->
                </div>
            </div>';
        }
        echo'
    </div>
</div>';
?>