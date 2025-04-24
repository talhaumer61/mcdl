<?php 
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/functions.php";

echo '<script src="assets/js/app.js"></script>';
if(isset($_POST['curs_wise'])){
    echo '
    <label class="form-label">Duration <span class="text-danger">*</span></label>
    <select class="form-control" data-choices name="duration" required>
        <option value=""> Choose one</option>';
        foreach(get_LessonWeeks() as $key => $value):
            echo '<option value="'.$key.'">'.$value.' '.get_CourseWise($_POST['curs_wise']).'</option>';
        endforeach;
        echo '
    </select>'; 
}
?>