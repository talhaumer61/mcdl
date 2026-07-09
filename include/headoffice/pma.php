<?php 
error_reporting(E_ALL);
include_once (moduleName().'/query.php');
echo' 
<title>'.moduleName(false).' - '.TITLE_HEADER.'</title>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">'.moduleName(false).'</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item"><a href="'.moduleName().'.php" class="text-primary">'.moduleName(false).'</a></li>
                            <li class="breadcrumb-item"><a href="'.moduleName().'.php?view=sql" class="text-primary">SQL</a></li>
                            <li class="breadcrumb-item"><a href="'.moduleName().'.php?view=insert_update" class="text-primary">Insert / Update</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-lg-12">';
                if(LMS_VIEW == 'sql'){
                    include_once (moduleName().'/sql.php');
                }else if (LMS_VIEW == 'insert_update'){
                    include_once (moduleName().'/insert_update.php');
                }else{
                    include_once (moduleName().'/browse.php');
                }
                echo'
            </div>
        </div>
    </div>
</div>';
?>