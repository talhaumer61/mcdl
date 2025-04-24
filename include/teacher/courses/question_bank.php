<?php
include_once (LMS_VIEW.'/query.php');
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="'.$iconPg.' align-bottom me-1"></i>'.moduleName(LMS_VIEW).'</h5>';
            if(!LMS_EDIT_ID && !isset($_GET['add'])){
                echo'
                <div class="flex-shrink-0">
                    <a class="btn btn-primary btn-xs" href="?add&'.$redirection.'"><i class="ri-add-circle-line align-bottom me-1"></i>'.moduleName(LMS_VIEW).'</a>
                </div>';
            }
            echo'
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12" >';
                if (isset($_GET['add'])) {
                    include_once (LMS_VIEW.'/add.php');
                } else if (!empty(LMS_EDIT_ID)) {
                    include_once (LMS_VIEW.'/edit.php');
                } else {
                    include_once (LMS_VIEW.'/list.php');
                }
                echo'
            </div>
        </div>
    </div>
</div>';
?>
<script>
    CKEDITOR.replace('ckeditor1');
    function get_QuestionType(id = ''){
        if (id == 1) {
            $('#qns_marks').val(2);
            $('#multipleCh').attr("style","display: none;");
        } else if (id == 2) {
            $('#qns_marks').val(5);
            $('#multipleCh').attr("style","display: none;");
        } else if (id == 3) {
            $('#qns_marks').val(1);
            $('#multipleCh').attr("style","display: block;");
        } else {
            $('#multipleCh').attr("style","display: none;");
        }
    }
    <?php
    if(!empty($row['qns_type'])){
        echo 'get_QuestionType('.$row['qns_type'].');';   
    } 
    ?>
    document.getElementById('fileInput').addEventListener('change', function() {
        var maxFileSize = 5 * 1024 * 1024;
        var errorMessage = document.getElementById('errorMessage');
        var selectedFile = this.files[0];

        if (selectedFile && selectedFile.size > maxFileSize) {
            errorMessage.style.display = 'block';
            this.value = '';
        } else {
            errorMessage.style.display = 'none';
        }
    });
</script>