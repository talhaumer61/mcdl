<?php 
$rootDir = 'discounts/';
include_once ($rootDir.'/query.php');
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
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-lg-12">';
                if (LMS_VIEW == 'add') {
                    include_once ($rootDir.'/add.php');
                } else if (!empty(LMS_EDIT_ID)) {
                    include_once ($rootDir.'/edit.php');
                } else {
                    include_once ($rootDir.'/list.php');
                }
                echo'
            </div>
        </div>
    </div>
</div>';
?>
<script>
document.querySelectorAll('[id^="ckeditor"]').forEach(function(element) {
    CKEDITOR.replace(element);
});
</script>