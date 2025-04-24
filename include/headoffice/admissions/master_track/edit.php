<?php
$condition = array ( 
                         'select'        =>  'mas_status, mas_name, mas_shortdetail, mas_detail, mas_prg_detail, mas_metakeyword, mas_metadescription, mas_amount, id_skills,mas_video, mas_duration, id_mstcat'
                        ,'where' 	    =>  array( 
                                                    'mas_id'    => cleanvars(LMS_EDIT_ID)
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$row = $dblms->getRows(MASTER_TRACK, $condition);

//MASTER TRACK DETAIL
$condition = array ( 
                         'select'       =>  'id_category, id_curs'
                        ,'where'        =>  array(
                                                    'id_mas'  =>  cleanvars(LMS_EDIT_ID)
                                                )
                        ,'order_by'     =>  'id ASC'
                        ,'return_type'  =>  'all'
                    ); 
$details = $dblms->getRows(MASTER_TRACK_DETAIL, $condition);

//COURSES CATEGORY
$condition_category = array ( 
                                 'select'       =>  'cat_id, cat_name'
                                ,'where'        =>  array(
                                                             'cat_status'  =>  1
                                                            ,'is_deleted'   =>  0
                                                        )
                                ,'order_by'     =>  'cat_name ASC'
                                ,'return_type'  =>  'all'
                            ); 
$COURSES_CATEGORIES = $dblms->getRows(COURSES_CATEGORIES, $condition_category);

// SKILLS
$condition = array ( 
                         'select'       =>  'skill_id, skill_name'
                        ,'where'        =>  array(
                                                    'skill_status'   =>  '1'
                                                    ,'is_deleted'    => 0
                                                )
                        ,'order_by'     =>  'skill_ordering'
                        ,'return_type'  =>  'all'
                    ); 
$course_skills = $dblms->getRows(COURSES_SKILLS, $condition);

// MASTER TRACK CATEGORY
$condition = array ( 
                         'select'       =>  'mstcat_id, mstcat_name'
                        ,'where'        =>  array(
                                                     'mstcat_status'    =>  '1'
                                                    ,'is_deleted'       =>  '0'
                                                )
                        ,'order_by'     =>  'mstcat_name'
                        ,'return_type'  =>  'all'
                    ); 
$mstcategories = $dblms->getRows(MASTER_TRACK_CATEGORIES, $condition);

// COURSES
$condition_courses = array ( 
                                 'select'       =>  'curs_name'
                                ,'where'        =>  array(
                                                            'curs_status'   =>  '1'
                                                            ,'is_deleted'   =>  '0'
                                                        )
                                ,'order_by'     =>  'curs_name'
                                ,'return_type'  =>  'single'
                            );
echo'
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header bg-info">
                <h5 class="mb-0 modal-title"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(0).'</h5>
            </div>
            <form enctype="multipart/form-data" autocomplete="off" method="post" accept-charset="utf-8">
                <div class="card-body create-project-main">
                    <div class="card mb-3">
                        <div class="card-header alert-primary">
                            <h6 class="mb-0"><i class="bx bx-info-circle align-middle fs-18 me-1"></i>Basic Information <span class="text-danger">(Mandatory)</span></h6>
                        </div>
                        <div class="card-body border">
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="mas_name" value="'.$row['mas_name'].'"  class="form-control" required="">
                                </div>
                                <div class="col">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" name="mas_status" data-choices required="">
                                        <option value="">Choose one</option>';
                                        foreach (get_status() as $key => $status):
                                            echo'<option value="'.$key.'" '.($key == $row['mas_status']?'selected':'').'>'.$status.'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </div>
                            </div>      
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header alert-primary">
                            <h6 class="mb-0"><i class="bx bx-image align-middle  fs-18 me-1"></i>Attachments <span class="text-info">(Optional)</span></h6>
                        </div>
                        <div class="card-body border">
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label">Icon</label>
                                    <input type="file" name="mas_icon" id="mas_icon" accept="image/*" class="form-control" />
                                </div>
                                <div class="col">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="mas_photo" id="mas_photo" accept="image/*" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header alert-primary">
                            <h6 class="mb-0"><i class="bx bx-paperclip align-middle  fs-18 me-1"></i>Detail <span class="text-danger">(Mandatory)</span></h6>
                        </div>
                        <div class="card-body border">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Short Description <span class="text-danger">*</span></label>
                                        <input class="form-control" name="mas_shortdetail" value="'.$row['mas_shortdetail'].'" required=""></input>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="ckeditor" name="mas_detail" required="">'.html_entity_decode($row['mas_detail']).'</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Program Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="mas_prg_detail" id="ckeditor1"  required="">'.html_entity_decode($row['mas_prg_detail']).'</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header alert-primary">
                            <h6 class="mb-0"><i class="bx bx-paperclip align-middle  fs-18 me-1"></i>Information <span class="text-danger">(Mandatory)</span></h6>
                        </div>
                        <div class="card-body border">
                            <div class="row mb-2">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Skills <span class="text-danger">*</span></label>
                                        <select class="form-control" name="id_skills[]" data-choices data-choices-removeItem multiple required>
                                            <option value="">Choose multiple</option>';
                                            foreach ($course_skills as $skill):
                                                echo'<option value="'.$skill['skill_id'].'" '.(in_array($skill['skill_id'],explode(",",$row['id_skills'])) ? 'selected': '').'>'.$skill['skill_name'].'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Master Track Category <span class="text-danger">*</span></label>
                                        <select class="form-control" name="id_mstcat" data-choices data-choices-removeItem required>
                                            <option value="">Choose one</option>';
                                            foreach ($mstcategories as $category):
                                                echo'<option value="'.$category['mstcat_id'].'" '.($category['mstcat_id'] == $row['id_mstcat']?'selected':'').'>'.$category['mstcat_name'].'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <label class="form-label">Duration (Months) <span class="text-danger">*</span></label>
                                    <input type="number" name="mas_duration" class="form-control" value="'.$row['mas_duration'].'" required="">
                                </div>
                                <div class="col">
                                    <label class="form-label">Introduction Video (YT Short Code) *<span class="text-danger">*</span></label>
                                    <input type="text" name="mas_video" value="'.$row['mas_video'].'"  class="form-control" required="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header alert-primary">
                            <h6 class="mb-0"><i class="bx bx-paperclip align-middle  fs-18 me-1"></i>Courses <span class="text-danger">(Mandatory)</span></h6>
                        </div>
                        <div class="card-body border">
                            <div class="row mb-2">
                                <div class="col-5">
                                    <label class="form-label">Courses Categories <span class="text-danger">*</span></label>
                                    <select class="form-control" name="cat_id" data-choices onchange="get_courses(this.value)">
                                        <option value="">Choose one</option>';
                                        foreach ($COURSES_CATEGORIES as $key => $value):
                                            echo'<option value="'.$value['cat_id'].'">'.$value['cat_name'].'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </div>
                                <div class="col-5" id="cat">
                                    <label class="form-label">Courses <span class="text-danger">*</span></label>
                                    <select class="form-control" data-choices data-choices-removeItem multiple >
                                        <option value="">Select Course Category First</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label class="form-label"></label>
                                    <button type="button" class="btn btn-info form-control" onclick="add_courses()">Add</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label class="form-label">List of courses </label>
                                    <ul class="list-group" id="list_of_courses">';
                                    foreach ($details as $key => $value) {
                                        // category code
                                        $condition_category['where']['cat_id'] = $value['id_category'];
                                        $condition_category['return_type'] = 'single';
                                        $category = $dblms->getRows(COURSES_CATEGORIES, $condition_category);
                                        // course code
                                        $condition_courses['where']['curs_id'] = $value['id_curs'];
                                        $course = $dblms->getRows(COURSES, $condition_courses);

                                        echo '<li class="list-group-item d-flex justify-content-between align-items-center">'
                                                    .$course['curs_name'] . ' ( '.$category['cat_name'].' )' .
                                                    '<input type="hidden" name="id_curs[]" value="'.$value['id_curs'].'">
                                                    <input type="hidden" name="id_cat[]" value="'.$value['id_category'].'">
                                                    <span onclick="remove_list(this)"><a class="btn btn-danger btn-xs"><i class="mdi mdi-delete-outline"></i></a></span>
                                                </li>';
                                    }
                                    echo '</ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header alert-primary">
                            <h6 class="mb-0"><i class="bx bx-paperclip align-middle  fs-18 me-1"></i>Meta Data <span class="text-primary">(Optional)</span></h6>
                        </div>
                        <div class="card-body border">
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label">Keywords</label>
                                    <input type="text" class="form-control" name="mas_metakeyword" value="'.$row['mas_metakeyword'].'" id="choices-text-remove-button" data-choices data-choices-limit="10" data-choices-removeItem/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="mas_metadescription" >'.$row['mas_metadescription'].'</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="'.moduleName().'.php" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
                        <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(0).'</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>';
?>