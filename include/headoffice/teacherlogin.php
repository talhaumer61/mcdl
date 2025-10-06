<?php 
include_once ('teacherlogin/query.php');
echo' 
<title>Teacher Login - '.TITLE_HEADER.'</title>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Teacher Login</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Employees</a></li>
                            <li class="breadcrumb-item"><a href="'.moduleName().'.php" class="text-primary">Teacher Login</a></li>  
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-lg-12">';
                include_once ('teacherlogin/list.php');
                echo'
            </div>
        </div>
    </div>
</div>';
?>