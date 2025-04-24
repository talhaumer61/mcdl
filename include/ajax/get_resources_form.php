
<?php
require_once ('../dbsetting/lms_vars_config.php');
require_once ('../dbsetting/classdbconection.php');
$dblms = new dblms();
require_once ('../functions/login_func.php');
require_once ('../functions/functions.php');

if(isset($_POST['id_type']) && !empty($_POST['id_type'])){    
    echo'
    <script src="assets/js/app.js"></script>
    <div class="row">
        <div class="col mb-2">
            <label class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="file_name" required/>
        </div>';
        if($_POST['id_type'] == 1 || $_POST['id_type'] == 5){
            echo'
            <div class="col mb-2">
                <label class="form-label">Open With <span class="text-danger">*</span></label>
                <select class="form-control" data-choices required id="open_with" name="open_with">
                    <option value="">Choose one</option>';
                    foreach($fileopenwith as $key => $value):
                        echo'<option value="'.$value.'">'.$value.'</option>';
                    endforeach;
                    echo'
                </select>
            </div>';
        }
        echo'
    </div>';
    if($_POST['id_type'] == 1 || $_POST['id_type'] == 5){
        echo'
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Attach File <span class="text-danger">*</span></label>
                <input class="form-control" type="file" accept=".pdf, .xlsx, .xls, .doc, .docx, .ppt, .pptx, .png, .jpg, .jpeg, .rar, .zip" name="file" id="fileInput" required>
                <p id="errorMessage" class="text-danger" style="display: none;">File must be less than 5MB.</p>
                <div class="text-primary mt-2">Upload valid files. Only <span class="text-danger fw-bold">pdf, xlsx, xls, doc, docx, ppt, pptx, png, jpg, jpeg, rar, zip</span> are allowed.</div>
            </div>
        </div>';
    }
    if($_POST['id_type'] == 2){
        echo'      
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Embed Video Link <span class="text-danger">*</span></label>
                <textarea class="form-control" name="embedcode"></textarea>
            </div>
        </div>';
    }
    if($_POST['id_type'] == 3 || $_POST['id_type'] == 4){
        echo'      
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">'.($_POST['id_type'] == 3 ? 'Drive Link' : 'URL').' <span class="text-danger">*</span></label>
                <textarea class="form-control" name="url"></textarea>
            </div>
        </div>';
    }
    echo'
    <div class="row">
        <div class="col mb-2">
            <label class="form-label">Detail <span class="text-danger">*</span></label>
            <textarea class="form-control" name="detail"></textarea>
        </div>
    </div>'; 
}else{
    echo'<h5 class="card-body text-center bg-body border rounded-2 mt-3"> Select Resource Type</h5>';
}
include_once ('../teacher/courses/course_resources/script.php');
?>