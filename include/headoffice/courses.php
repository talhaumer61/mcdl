<?php 
$_SESSION['id_type'] = (!empty($_REQUEST['id_type']) ? cleanvars($_REQUEST['id_type']) : '1');
$redirection = 'id_type='.$_SESSION['id_type'].'';
$pageTitle = ($_SESSION['id_type'] == 2 ? 'e-Trainings' : 'Courses');
include_once (moduleName().'/query.php');
echo' 
<title>'.$pageTitle.' - '.TITLE_HEADER.'</title>

<style type="text/css">
        .table-responsive {
            overflow: visible; /* Allow dropdown to be fully visible */
        }
        .table td {
            position: relative; /* Ensures dropdown aligns correctly */
        }
        .dropdown-menu {
            z-index: 9999;
            position: absolute;
            right: 0;
            top: 100%;
            display: none;
            background-color: white;
            border: 1px solid #ddd;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
        }
        .dropdown.show .dropdown-menu {
            display: block;
        }
    </style>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">'.$pageTitle.'</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item"><a href="'.moduleName().'.php?'.$redirection.'" class="text-primary">'.$pageTitle.'</a></li>
                            '.(!empty(LMS_VIEW) ? '<li class="breadcrumb-item"><a href="'.moduleName().'.php?edit&'.$redirection.'&id='.$_GET['id'].'&view='.LMS_VIEW.'" class="text-primary">'.moduleName(LMS_VIEW).'</a></li>' : '').'
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-lg-12">';
                if(isset($_GET['add'])){
                    include_once (moduleName().'/add.php');
                } elseif(isset($_GET['edit'])){
                    if(LMS_VIEW == 'enrolled_students'){
                        include_once (moduleName().'/enrolled_students.php');
                    } else {
                        include_once (moduleName().'/edit.php');
                    }
                }else{
                    include_once (moduleName().'/list.php');
                }
                echo'
            </div>
        </div>
    </div>
</div>';
include_once ('courses/script.php');
?>