<?php
if (!empty(LMS_VIEW)) {
    $redirection = "view=".LMS_VIEW."";
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
                                <li class="breadcrumb-item"><a href="'.moduleName().'.php?'.$redirection.'" class="text-primary">'.moduleName(LMS_VIEW).'</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-lg-3 mb-3">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="ri-menu-4-fill align-bottom me-1"></i>Profile Menu</h5>
                        </div>
                        <div class="card-body p-0"> 
                            <ul class="list-group">';
                                foreach ($profileMenu as $key => $value) {
                                    echo'
                                    <li class="list-group-item"><a onclick="window.location.href=\''.moduleName().'.php?view='.$key.'\';" class="text-'.(LMS_VIEW == $key ? 'danger' : 'dark').'" style="cursor: pointer;">
                                        <i class="'.$value['icon'].' align-middle lh-1 me-2"></i>'.$value['title'].'</a>
                                    </li>';
                                }
                                echo'
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">';
                    foreach ($profileMenu as $key => $value) {
                        if (LMS_VIEW == $key) {
                            $profileIcon = (isset($_GET['add']) ? 'ri-add-circle-line' : (LMS_EDIT_ID ? 'ri-edit-circle-line' : $profileMenu[$key]['icon']));
                            include_once (moduleName().'/'.$key.'.php');
                        } 
                    }
                    echo'
                </div>
            </div>
        </div>
    </div>';
} else {
    header('location: '.moduleName().'.php?view='.array_keys($profileMenu)[0].'');
}
?>