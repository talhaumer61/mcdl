<?php
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
            <div class="card-header bg-primary">
                <h5 class="mb-0 modal-title"><i class="ri-add-circle-line align-bottom me-1"></i>Add Program</h5>
            </div>
            <form action="programs.php" enctype="multipart/form-data" autocomplete="off" method="post" accept-charset="utf-8">  
                <div class="card-body create-project-main">                    
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="prg_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Short Name <span class="text-danger">*</span></label>
                                <input type="text" name="prg_shortname" class="form-control" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Code <span class="text-danger">*</span></label>
                                <input type="text" name="prg_code" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Ordering <span class="text-danger">*</span></label>
                                <input type="text" name="prg_ordering" value="'.get_ordering(PROGRAMS).'" readonly class="form-control" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Is Publish <span class="text-danger">*</span></label>
                                <select class="form-control" name="prg_publish" data-choices required>
                                    <option value="">Choose One</option>';
                                    foreach (get_is_publish() as $key => $value):
                                        echo'<option value="'.$key.'">'.$value.'</option>';
                                    endforeach;
                                    echo'
                                </select>
                            </div>
                        </div>   
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control form-select" name="prg_status" data-choices required>
                                    <option value="">Choose One</option>';
                                    foreach (get_status() as $key => $status):
                                        echo'<option value="'.$key.'">'.$status.'</option>';
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
                                <input type="number" name="prg_semesters" class="form-control" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" name="prg_credithours" class="form-control" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Duration (Years) <span class="text-danger">*</span></label>
                                <input type="number" name="prg_duration" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Deparments <span class="text-danger">*</span></label>
                                <select class="form-control" name="id_dept" data-choices required>
                                    <option value="">Choose One</option>';
                                    foreach ($Departments as $dept):
                                        echo'<option value="'.$dept['dept_id'].'">'.$dept['dept_name'].' - '.$dept['dept_code'].'</option>';
                                    endforeach;
                                    echo'
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Faculty <span class="text-danger">*</span></label>
                                <select class="form-control" name="id_faculty" data-choices required>
                                    <option value="">Choose One</option>';
                                    foreach ($Faculties as $faculty):
                                        echo'<option value="'.$faculty['faculty_id'].'">'.$faculty['faculty_name'].' - '.$faculty['faculty_code'].'</option>';
                                    endforeach;
                                    echo'
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Program Category <span class="text-danger">*</span></label>
                                <select class="form-control" name="id_cat" data-choices required>
                                    <option value="">Choose One</option>';
                                    foreach ($ProgramCats as $cat):
                                        echo'<option value="'.$cat['cat_id'].'">'.$cat['cat_name'].' - '.$cat['cat_code'].'</option>';
                                    endforeach;
                                    echo'
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Icon <span class="text-danger">*</span></label>
                                <input type="file" name="prg_icon" accept="image/*" class="form-control" required/>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Profile Image <span class="text-danger">*</span></label>
                                <input type="file" name="prg_photo" accept="image/*" class="form-control" required/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Meta Keywords <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="prg_keyword" id="choices-text-remove-button" data-choices data-choices-limit="10" data-choices-removeItem/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Meta Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="prg_meta" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Introduction </label>
                                <textarea class="form-control" name="prg_intro" id="ckeditor0"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Detail </label>
                                <textarea class="form-control" name="prg_detail" id="ckeditor1"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label class="form-label">Remarks </label>
                                <textarea class="form-control" name="prg_remarks"></textarea>
                            </div>
                        </div>
                    </div>
                </div>                
                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="'.moduleName().'.php" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
                        <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(false).'</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>';
?>