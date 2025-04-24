<?php 
require_once ("../../../dbsetting/lms_vars_config.php");
require_once ("../../../dbsetting/classdbconection.php");
require_once ("../../../functions/functions.php");
require_once ("../../../functions/login_func.php");
$dblms = new dblms();

$condition = array(
                     'select'       =>  'id, status, caption, detail, date_start, date_end, is_midterm, id_week, total_marks, passing_marks'
                    ,'where'        =>  array(
                                                 'is_deleted'   => 0
                                                ,'id'           => cleanvars(LMS_EDIT_ID)
                                            )
                    ,'return_type'  =>  'single'
);
$row = $dblms->getRows(COURSES_ASSIGNMENTS, $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="edit_id" value="'.LMS_EDIT_ID.'">
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="caption" value="'.$row['caption'].'" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">'.get_CourseWise($_GET['curs_wise']).' <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="id_week" required="">
                            <option value=""> Choose one</option>';
                            foreach(get_LessonWeeks() as $key => $val):
                                echo'<option value="'.$key.'" '.($key == $row['id_week'] ? 'selected' : '').'>'.get_CourseWise($_GET['curs_wise']).' '.$val.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Attach File</label>
                        <input class="form-control" type="file" accept=".docx, .ppt, .pdf, xlsx, xls" name="fileattach">
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Total Marks <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="total_marks" value="'.$row['total_marks'].'" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Passing Marks <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" required name="passing_marks" value="'.$row['passing_marks'].'">
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Mid Term Assignment?</label>
                        <select class="form-control" data-choices name="is_midterm">
                            <option value="">Choose one</option>';
                            foreach(get_is_publish() as $key => $status):
                                echo'<option value="'.$key.'" '.($row['is_midterm'] == $key ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo '
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices required name="status">
                            <option value="">Choose one</option>';
                            $statuses = get_status();
                            foreach($statuses as $key => $status):
                                echo'<option value="'.$key.'" '.($row['status'] == $key ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo '
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Detail <span class="text-danger">*</span></label>
                        <textarea name="detail" class="form-control" rows="3" required>'.$row['detail'].'</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>