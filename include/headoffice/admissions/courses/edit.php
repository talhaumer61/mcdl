<?php
// COURSE TO EDIT
$condition = array ( 
                     'select' 	    =>  ' curs_id
                                        , curs_status
                                        , id_level
                                        , id_cat
                                        , sequencing_category
                                        , curs_domain
                                        , id_dept
                                        , id_faculty
                                        , curs_code
                                        , curs_name
                                        , curs_meta
                                        , curs_keyword
                                        , cur_credithours_theory
                                        , cur_credithours_practical
                                        , curs_credit_hours
                                        , curs_pre_requisite
                                        , curs_specialization
                                        , curs_type
                                        , curs_detail
                                        , curs_about
                                        , what_you_learn
                                        , how_it_work
                                        , curs_skills
                                        , curs_references
                                        , id_lang 
                                        , curs_video
                                        , curs_startdate
                                        , curs_enddate'
                    ,'where' 	    =>  array( 
                                            'curs_id'    => $_GET['id']
                                        )
                    ,'return_type'  =>  'single'
                ); 
$curs = $dblms->getRows(COURSES, $condition);
$sequencing_category = explode( ',', $curs['sequencing_category']);
$curs_skills = explode( ',', $curs['curs_skills']);
$what_you_learn = json_decode(html_entity_decode($curs['what_you_learn']), true); // JSON DECODE

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

// COURSE CAT
$condition = array ( 
                         'select'       =>  'cat_id, cat_name, cat_code'
                        ,'where'        =>  array(
                                                     'cat_status'   =>  '1'
                                                    ,'is_deleted'   =>  '0'
                                                )
                        ,'order_by'     =>  'cat_name'
                        ,'return_type'  =>  'all'
                    ); 
$courseCats = $dblms->getRows(COURSES_CATEGORIES, $condition);

// COURSES
$condition = array ( 
                         'select'       =>  'curs_id, curs_name, curs_code'
                        ,'where'        =>  array(
                                                     'curs_status'  =>  '1'
                                                    ,'is_deleted'   =>  '0'
                                                )
                        ,'order_by'     =>  'curs_name'
                        ,'return_type'  =>  'all'
                    ); 
$courses = $dblms->getRows(COURSES, $condition);

// PROGRAMS CAT
$condition = array ( 
                         'select'       =>  'cat_id, cat_name, cat_code'
                        ,'where'        =>  array(
                                                     'cat_status'   =>  '1'
                                                    ,'is_deleted'   =>  '0'
                                                )
                        ,'order_by'     =>  'cat_name'
                        ,'return_type'  =>  'all'
                    ); 
$programsCat = $dblms->getRows(PROGRAMS_CATEGORIES, $condition);

// LANGUAGES
$condition = array ( 
                         'select'       =>  'lang_id, lang_name, lang_code'
                        ,'where'        =>  array(
                                                    'lang_status'   =>  '1'
                                                )
                        ,'order_by'     =>  'lang_name'
                        ,'return_type'  =>  'all'
                    ); 
$languages = $dblms->getRows(LANGUAGES, $condition);

// SKILLS
$condition = array ( 
                         'select'       =>  'skill_id, skill_name'
                        ,'where'        =>  array(
                                                    'skill_status'   =>  '1'
                                                )
                        ,'order_by'     =>  'skill_ordering'
                        ,'return_type'  =>  'all'
                    ); 
$course_skills = $dblms->getRows(COURSES_SKILLS, $condition);

// TEXT EDITORS
$textareaFields = array(
                         'curs_about'      => array( 'id' => 'ckeditor0', 'title' => 'About'          , 'required' => '1', 'class' => 'col-md-6' )
                        ,'how_it_work'     => array( 'id' => 'ckeditor1', 'title' => 'How it Work'    , 'required' => '1', 'class' => 'col-md-6' )
                    );
echo'
<div class="row mb-5">
    <div class="col-lg-12 col-md-12">
        <div class="card mb-3">
            <div class="card-header bg-info">
                <h5 class="mb-0 text-white"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Course</h5>
            </div>
            <form action="courses.php" enctype="multipart/form-data" autocomplete="off" method="post" accept-charset="utf-8">
                <input type="hidden" name="curs_id" class="form-control" value="'.$curs['curs_id'].'" required>
                <div class="card-body create-project-main">
                    <div class="card mb-3">
                        <div class="card-header alert-dark">
                            <h6 class="mb-0"><i class="bx bx-info-circle align-bottom fs-18 me-1"></i>  Basic Information</h6>
                        </div>
                        <div class="card-body border">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="curs_name" value="'.$curs['curs_name'].'" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Code <span class="text-danger">*</span></label>
                                        <input type="text" name="curs_code" value="'.$curs['curs_name'].'" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-control" data-choices name="curs_status" required>
                                            <option value="">Choose one</option>';
                                            foreach (get_status() as $key => $status):
                                                echo'<option value="'.$key.'" '.($curs['curs_status'] == $key ? 'selected' : '').'>'.$status.'</option>';
                                            endforeach;
                                            echo'
                                        </select>
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
                                                echo'<option value="'.$dept['dept_id'].'" '.($curs['id_dept'] == $dept['dept_id'] ? 'selected' : '').'>'.$dept['dept_name'].' - '.$dept['dept_code'].'</option>';
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
                                                echo'<option value="'.$faculty['faculty_id'].'" '.($curs['id_faculty'] == $faculty['faculty_id'] ? 'selected' : '').'>'.$faculty['faculty_name'].' - '.$faculty['faculty_code'].'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Course Category <span class="text-danger">*</span></label>
                                        <select class="form-control" data-choices name="id_cat" required>
                                            <option value="">Choose one</option>';
                                            foreach ($courseCats as $cat):
                                                echo'<option value="'.$cat['cat_id'].'" '.($curs['id_cat'] == $cat['cat_id'] ? 'selected' : '').'>'.$cat['cat_name'].' - '.$cat['cat_code'].'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                            </div>                    
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Course Type <span class="text-danger">*</span></label>
                                        <select class="form-control" data-choices name="curs_type" required>
                                            <option value="">Choose one</option>';
                                            foreach (get_curs_type() as $key => $value):
                                                echo'<option value="'.$key.'" '.($curs['curs_type'] == $key ? 'selected' : '').'>'.$value.'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Course Domain <span class="text-danger">*</span></label>
                                        <select class="form-control" data-choices name="curs_domain" required>
                                            <option value="">Choose one</option>';
                                            foreach (get_curs_domain() as $key => $value):
                                                echo'<option value="'.$key.'" '.($curs['curs_domain'] == $key ? 'selected' : '').'>'.$value.'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Specialization <span class="text-danger">*</span></label>
                                        <select class="form-control" data-choices name="curs_specialization" required>
                                            <option value="">Choose one</option>';
                                            foreach (get_is_publish() as $key => $value):
                                                echo'<option value="'.$key.'" '.($curs['curs_specialization'] == $key ? 'selected' : '').'>'.$value.'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Level <span class="text-danger">*</span></label>
                                        <select class="form-control" data-choices name="id_level" required>
                                            <option value="">Choose one</option>';
                                            foreach (get_course_level() as $key => $value):
                                                echo'<option value="'.$key.'" '.($curs['id_level'] == $key ? 'selected' : '').'>'.$value.'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Pre Requisite <span class="text-danger">*</span></label>
                                        <select class="form-control" data-choices name="curs_pre_requisite" required>
                                            <option value="">Choose one</option>';
                                            foreach ($courses as $course):
                                                echo'<option value="'.$course['curs_id'].'" '.($curs['curs_pre_requisite'] == $course['curs_id'] ? 'selected' : '').'>'.$course['curs_name'].' - '.$course['curs_code'].'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Squencing Category <span class="text-danger">*</span></label>
                                        <select class="form-control" data-choices name="sequencing_category[]" data-choices-removeItem multiple required>
                                            <option value="">Choose multiple</option>';
                                            foreach ($programsCat as $cat):
                                                echo'<option value="'.$cat['cat_id'].'" '.(in_array($cat['cat_id'], $sequencing_category) ? 'selected': '').'>'.$cat['cat_name'].' - '.$cat['cat_code'].'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Language <span class="text-danger">*</span></label>
                                        <select class="form-control" data-choices name="id_lang" required>
                                            <option value="">Choose one</option>';
                                            foreach ($languages as $lang):
                                                echo'<option value="'.$lang['lang_id'].'" '.($curs['id_lang'] == $lang['lang_id'] ? 'selected' : '').'>'.$lang['lang_name'].' - '.$lang['lang_code'].'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Credit Hours (Theory) <span class="text-danger">*</span></label>
                                        <input type="number" name="cur_credithours_theory" value="'.$curs['cur_credithours_theory'].'" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Credit Hours (Practical) <span class="text-danger">*</span></label>
                                        <input type="number" name="cur_credithours_practical" value="'.$curs['cur_credithours_practical'].'" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="text" name="curs_startdate" value="'.$curs['curs_startdate'].'"  data-provider="flatpickr" class="form-control" required="">
                                </div>
                                <div class="col">
                                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="text" name="curs_enddate" value="'.$curs['curs_enddate'].'"  data-provider="flatpickr" class="form-control" required="">
                                </div>
                                <div class="col">
                                    <label class="form-label">Introduction Video (YT Short Code) *<span class="text-danger">*</span></label>
                                    <input type="text" name="curs_video" value="'.$curs['curs_video'].'"  class="form-control" required="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header alert-primary">
                            <h6 class="mb-0"><i class="bx bx-image align-bottom fs-18 me-1"></i> Images</h6>
                        </div>
                        <div class="card-body border">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Icon </label>
                                        <input type="file" name="curs_icon" accept="image/*" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Image </label>
                                        <input type="file" name="curs_photo" accept="image/*" class="form-control"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header alert-secondary">
                            <h6 class="mb-0"><i class="bx bx-paperclip align-middle fs-18 me-1"></i> Additional Detail</h6>
                        </div>
                        <div class="card-body border">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Meta Keywords </label>
                                        <input type="text" class="form-control" name="curs_keyword" id="choices-text-remove-button" value="'.$curs['curs_keyword'].'" data-choices data-choices-limit="10" data-choices-removeItem/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Meta Description</label>
                                        <textarea class="form-control" name="curs_meta">'.$curs['curs_meta'].'</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">References </label>
                                        <textarea class="form-control" name="curs_references">'.$curs['curs_references'].'</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Detail </label>
                                        <textarea class="form-control" name="curs_detail" id="ckeditor2">'.html_entity_decode($curs['curs_detail']).'</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Skills <span class="text-danger">*</span></label>
                                        <select class="form-control" data-choices name="curs_skills[]" data-choices-removeItem multiple required>
                                            <option value="">Choose multiple</option>';
                                            foreach ($course_skills as $skill):
                                                echo'<option value="'.$skill['skill_id'].'" '.(in_array($skill['skill_id'], $curs_skills) ? 'selected': '').'>'.$skill['skill_name'].'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">What Will You Learn <span class="text-danger">*</span> <button class="btn btn-primary btn-sm" id="duplicateButton"><i class="ri-add-circle-line align-bottom"></i></button></label>
                                    </div>
                                </div>
                            </div>';
                            foreach ($what_you_learn as $key => $value) {
                                echo'                                
                                <div class="row" id="what_you_work_div">
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="ri-double-quotes-l"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="what_you_learn[]" value="'.$value.'">
                                            <button class="btn btn-danger delete-button" '.($key == '0' ? 'disabled' : 'style="display: inline-block;"').'><i class="bx bx-trash-alt"></i></button>
                                        </div>
                                    </div>
                                </div>';
                            }
                            echo'
                            <div id="targetDiv"></div>
                            <div class="row">';
                                foreach($textareaFields as $name => $field): 
                                    echo '
                                    <div class="'.$field['class'].'">
                                        <div class="mb-2">
                                            <label class="form-label">'.$field['title'].' '.(($field['required'])? '<span class="text-danger">*</span>': '').'</label>
                                            <textarea class="form-control" id="'.$field['id'].'" name="'.$name.'" '.($field['required']? 'required': '').'>'.html_entity_decode($curs[$name]).'</textarea>
                                        </div>
                                    </div>';
                                endforeach;
                                echo '
                            </div>
                        </div>
                    </div>
                </div>
                <div class="expanel-footer modal-footer">
                    <button type="submit" class="btn btn-primary" name="submit_edit">Edit Course</button> 
                    <a href="courses.php" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>';
include 'script.php';
?>