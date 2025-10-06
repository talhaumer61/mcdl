<?php
// PROGRAM TO EDIT
$condition = array ( 
                     'select' 	    =>  ' prg_id
                                        , prg_status
                                        , prg_publish
                                        , prg_ordering
                                        , prg_code
                                        , prg_name
                                        , prg_shortname
                                        , prg_intro
                                        , prg_meta
                                        , prg_keyword
                                        , prg_semesters
                                        , prg_credithours
                                        , prg_duration
                                        , prg_detail
                                        , prg_remarks
                                        , id_dept
                                        , id_faculty
                                        , id_cat'
                    ,'where' 	    =>  array( 
                                            'prg_id'    => $_GET['id']
                                        )
                    ,'return_type'  =>  'single'
                ); 
$programs 	= $dblms->getRows(PROGRAMS, $condition);

// DEPARTMENTS
$condition = array ( 
                         'select'       =>  'dept_id, dept_name, dept_code'
                        ,'where'        =>  array(
                                                     'dept_status'  =>  '1'
                                                    ,'is_deleted'   =>  '0'
                                                )
                        ,'order_by'     =>  'dept_name'
                        ,'return_type'  =>  'all'
                    ); 
$Departments = $dblms->getRows(DEPARTMENTS, $condition);

// FACULTIES
$condition = array ( 
                         'select'       =>  'faculty_id, faculty_name, faculty_code'
                        ,'where'        =>  array(
                                                     'faculty_status'  =>  '1'
                                                    ,'is_deleted'   =>  '0'
                                                )
                        ,'order_by'     =>  'faculty_name'
                        ,'return_type'  =>  'all'
                    ); 
$Faculties = $dblms->getRows(FACULTIES, $condition);

// PROGRAM CAT
$condition = array ( 
                         'select'       =>  'cat_id, cat_name, cat_code'
                        ,'where'        =>  array(
                                                     'cat_status'  =>  '1'
                                                    ,'is_deleted'   =>  '0'
                                                )
                        ,'order_by'     =>  'cat_name'
                        ,'return_type'  =>  'all'
                    ); 
$ProgramCats = $dblms->getRows(PROGRAMS_CATEGORIES, $condition);
echo'
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header bg-info">
                <h5 class="mb-0 modal-title"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Program</h5>
            </div>
            <form action="programs.php" enctype="multipart/form-data" autocomplete="off" method="post" accept-charset="utf-8">  
                <div class="card-body create-project-main">
                    <div class="row">
                        <div class="col">
                            <input type="hidden" name="prg_id" class="form-control" value="'.$programs['prg_id'].'" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="prg_name" class="form-control" value="'.$programs['prg_name'].'" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Short Name <span class="text-danger">*</span></label>
                                <input type="text" name="prg_shortname" class="form-control" value="'.$programs['prg_shortname'].'" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Code <span class="text-danger">*</span></label>
                                <input type="text" name="prg_code" class="form-control" value="'.$programs['prg_code'].'" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Ordering <span class="text-danger">*</span></label>
                                <input type="text" name="prg_ordering" class="form-control" value="'.$programs['prg_ordering'].'" readonly required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Is Publish <span class="text-danger">*</span></label>
                                <select class="form-control" data-choices name="prg_publish" required>
                                    <option value="">Choose one</option>';
                                    foreach (get_is_publish() as $key => $value):
                                        echo'<option value="'.$key.'" '.($programs['prg_publish'] == $key ? 'selected' : '').'>'.$value.'</option>';
                                    endforeach;
                                    echo'
                                </select>
                            </div>
                        </div>   
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control select2-show-search form-select" name="prg_status" required>
                                    <option value="">Choose one</option>';
                                    foreach (get_status() as $key => $status):
                                        echo'<option value="'.$key.'" '.($programs['prg_status'] == $key ? 'selected' : '').'>'.$status.'</option>';
                                    endforeach;
                                    echo'
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Semesters <span class="text-danger">*</span></label>
                                <input type="number" name="prg_semesters" class="form-control" value="'.$programs['prg_semesters'].'" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" name="prg_credithours" class="form-control" value="'.$programs['prg_credithours'].'" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Duration (Years) <span class="text-danger">*</span></label>
                                <input type="number" name="prg_duration" class="form-control" value="'.$programs['prg_duration'].'" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Deparments <span class="text-danger">*</span></label>
                                <select class="form-control" data-choices name="id_dept" required>
                                    <option value="">Choose one</option>';
                                    foreach ($Departments as $dept):
                                        echo'<option value="'.$dept['dept_id'].'" '.($programs['id_dept'] == $dept['dept_id'] ? 'selected' : '').'>'.$dept['dept_name'].' - '.$dept['dept_code'].'</option>';
                                    endforeach;
                                    echo'
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Faculty <span class="text-danger">*</span></label>
                                <select class="form-control" data-choices name="id_faculty" required>
                                    <option value="">Choose one</option>';
                                    foreach ($Faculties as $faculty):
                                        echo'<option value="'.$faculty['faculty_id'].'" '.($programs['id_faculty'] == $faculty['faculty_id'] ? 'selected' : '').'>'.$faculty['faculty_name'].' - '.$faculty['faculty_code'].'</option>';
                                    endforeach;
                                    echo'
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Program Category <span class="text-danger">*</span></label>
                                <select class="form-control" data-choices name="id_cat" required>
                                    <option value="">Choose one</option>';
                                    foreach ($ProgramCats as $cat):
                                        echo'<option value="'.$cat['cat_id'].'" '.($programs['id_cat'] == $cat['cat_id'] ? 'selected' : '').'>'.$cat['cat_name'].' - '.$cat['cat_code'].'</option>';
                                    endforeach;
                                    echo'
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Icon </label>
                                <input type="file" name="prg_icon" accept="image/*" class="form-control" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Profile Image </label>
                                <input type="file" name="prg_photo" accept="image/*" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Meta Keywords <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="prg_keyword" value="'.$programs['prg_keyword'].'" id="choices-text-remove-button" data-choices data-choices-limit="10" data-choices-removeItem/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Meta Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="prg_meta" required>'.$programs['prg_meta'].'</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Intro </label>
                                <textarea class="form-control" id="ckeditor0" name="prg_intro">'.html_entity_decode($programs['prg_intro']).'</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Detail </label>
                                <textarea class="form-control" id="ckeditor1" name="prg_detail">'.html_entity_decode($programs['prg_detail']).'</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Remarks </label>
                                <textarea class="form-control" name="prg_remarks">'.$programs['prg_remarks'].'</textarea>
                            </div>
                        </div>
                    </div>
                </div>                
                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="'.moduleName().'.php" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
                        <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(false).'</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>';
?>