<?php
// INSERT OR UPDATE QUERY
if(isset($_POST['submit_query'])){
    if (!empty($_POST['sql_query'])) {
        $sqllms  = $dblms->querylms($_POST['sql_query']);
        if ($sqllms) {
            $affected_rows = mysqli_affected_rows($dblms->getConnectlms());
            if ($affected_rows > 0) {
                sessionMsg("Success", "Record Modified. Affected Rows:".$affected_rows, "success");
            } else {
                sessionMsg("Info", "No Records Affected", "info");
            }
            header("Location: " . moduleName() . ".php?view=insert_update", true, 301);
            exit();
        } else {
            sessionMsg("Error", "Query Error", "danger");
            header("Location: " . moduleName() . ".php?view=insert_update", true, 301);
            exit();
        }
    } else {
        sessionMsg("Error", "Query Box Empty", "danger");
        header("Location: ".moduleName().".php?view=insert_update", true, 301);
        exit();
    }
}

// DELETE PERMANENTLY
if(isset($_GET['deleteid']) && !empty($_GET['deleteid'])){
    $sqllms	= $dblms->querylms('DELETE FROM '.$_GET['table_name'].' WHERE '.$_GET['col_name'].' = '.$_GET['deleteid'].'');
    if($sqllms){
        sessionMsg("Success", "Record Permanently Deleted", "success");
        exit();
        header("Location: ".moduleName().".php", true, 301);
    }else{
        sessionMsg("Error", "Record not Deleted", "danger");
        exit();
        header("Location: ".moduleName().".php", true, 301);
    }
}
?>