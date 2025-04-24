<?php 
$rootDir = 'reports/';
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
                            <li class="breadcrumb-item"><a href="javascript: void(0);">'.moduleName(false).'</a></li>
                            <li class="breadcrumb-item"><a href="'.moduleName().'.php?view='.LMS_VIEW.'" class="text-primary">'.$reports[LMS_VIEW].'</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-lg-12">';
                if (LMS_VIEW == 'course_completion_report') {
                    include_once ($rootDir.'/'.LMS_VIEW.'.php');
                } elseif (LMS_VIEW == 'certificates_report') {
                    include_once ($rootDir.'/'.LMS_VIEW.'.php');
                } elseif (LMS_VIEW == 'offered_certificates_report') {
                    include_once ($rootDir.'/'.LMS_VIEW.'.php');
                } elseif (LMS_VIEW == 'enrolled_certificates_report') {
                    include_once ($rootDir.'/'.LMS_VIEW.'.php');
                } elseif (LMS_VIEW == 'quiz_report') {
                    include_once ($rootDir.'/'.LMS_VIEW.'.php');
                }
                echo'
            </div>
        </div>
    </div>
</div>';
?>