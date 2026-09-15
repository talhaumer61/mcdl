<?php
require_once("../dbsetting/lms_vars_config.php");
require_once("../dbsetting/classdbconection.php");
require_once("../functions/functions.php");
$dblms = new dblms();
require_once("../functions/login_func.php");
checkCpanelLMSALogin();
echo '
<script src="assets/js/app.js"></script>';

if (isset($_POST['id_cat'])) {
	$condition = array ( 
							 'select'       =>  'curs_id, curs_name, curs_code'
							,'where'        =>  array(
														 'curs_status'  =>  1
														,'is_deleted'   =>  0
														,'id_cat'   =>  cleanvars($_POST['id_cat'])
												)
							,'order_by'     =>  'curs_name ASC'
							,'return_type'  =>  'all'
						); 
	$COURSES = $dblms->getRows(COURSES, $condition);
	foreach (get_degree_course_type() as $key => $value):
		echo'
		<div class="row mb-2">
			<div class="col courses_section">
				<label class="form-label">'.$value.' <span class="text-danger">*</span></label>
				<select class="form-control" data-course-type="'.$key.'" name="id_cur[]" data-choices data-choices-removeItem multiple>
					<option value="">Choose atleast one</option>';
					foreach ($COURSES as $key => $value):
						echo'<option value="'.$value['curs_id'].'">'.$value['curs_name'].' ('.$value['curs_code'].')</option>';
					endforeach;
					echo'
				</select>
			</div>
		</div>';
	endforeach;
	echo '
	<div class="row mb-2">
		<div class="col">
			<button type="button" class="btn btn-info form-control" onclick="add_courses()">Add</button>
		</div>		
	</div>';
}
?>