<?php
$table_name     = '';
$column_name_id = '';
$id_edit        = '';
$method         = '';
$sql_query      = '';
$search_by      = '';

if(LMS_VIEW == 'insert_update' && !empty($_GET['table_name']) && !empty($_GET['primary_key']) && !empty($_GET['id']) && !empty($_GET['method']) && $_GET['method'] == 'update'){
    $table_name     = cleanvars($_GET['table_name']);
    $column_name_id = cleanvars($_GET['primary_key']);
    $id_edit        = cleanvars($_GET['id']);
    $method         = cleanvars($_GET['method']);
    $sql_query      = 'UPDATE `'.$table_name.'` SET ';
    $search_by      = 'WHERE '.$column_name_id.' != "" ';

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

    // RECORD TO EDIT
    $condition = array ( 
                         'select'       =>  '*'
                        ,'where'        =>  array(
                                                    ''.$column_name_id.''   =>  $id_edit
                                                )
                        ,'return_type'  =>  'single'
                    );
    $row = $dblms->getRows($table_name, $condition, $sql);

    // MOLD UPDATE QUERY
    foreach ($column_name_all as $key => $value) {
        $print_col_name  = $value['column_name'];
        $sql_query      .= '`'.$print_col_name.'` = "'.$row[$print_col_name].'",';
    }
    $sql_query  = rtrim($sql_query, ",");
    $sql_query .= ' WHERE `'.$column_name_id.'` = "'.$id_edit.'" ';
}
else if(isset($_POST['sql_query']) && !empty($_POST['sql_query'])){
    $sql_query = $_POST['sql_query'];
}
else{
    $sql_query = '';
}
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>Insert / Update</h5>
        </div>
    </div>
    <div class="card-body">
        <form class="form-horizontal" id="form" enctype="multipart/form-data" method="post" autocomplete="off" accept-charset="utf-8">
            <div class="col-12">
                <input type="hidden" name="table_name" value="'.$table_name.'"/>
                <input type="hidden" name="primary_key" value="'.$column_name_id.'"/>
                <input type="hidden" name="id" value="'.$id_edit.'"/>
                <input type="hidden" name="method" value="'.$method.'"/>
                <textarea class="form-control" rows="10" name="sql_query" placeholder="Insert or Update Query">'.$sql_query.'</textarea>
            </div>
            <div class="col-12 mt-3 text-end">
                <button type="submit" class="btn btn-primary rounded-pill btn-sm" name="submit_query"><i class="ri-check-fill align-bottom"></i> Go</button>
            </div>
        </form>
    </div>
</div>';
?>