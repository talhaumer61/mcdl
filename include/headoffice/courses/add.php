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

// COURSE CAT
$condition = array ( 
                         'select'       =>  'cat_id, cat_name, cat_code'
                        ,'where'        =>  array(
                                                     'cat_status'   =>  '1'
                                                    ,'is_deleted'   =>  '0'
                                                    ,'id_type'      =>  $_SESSION['id_type']
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
        <div class="card">
            <div class="card-header bg-primary">
                <h5 class="modal-title mb-0"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.$pageTitle.'</h5>
            </div>
            <form enctype="multipart/form-data" autocomplete="off" method="post" accept-charset="utf-8">
                <div class="card-body create-project-main">
                    <div class="card mb-3">
                        <div class="card-header alert-dark">
                            <h6 class="mb-0"><i class="bx bx-info-circle align-middle fs-18 me-1"></i>Basic Information</h6>
                        </div>
                        <div class="card-body border">
                            <div class="row">                            
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Course Category <span class="text-danger">*</span></label>
                                        <select class="form-control" name="id_cat[]" data-choices data-choices-removeItem multiple required>
                                            <option value="">Choose multiple</option>';
                                            foreach ($courseCats as $cat):
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
                                        <label class="form-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="curs_name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Course Type <span class="text-danger">*</span></label>
                                        <select class="form-control" name="curs_type" data-choices required>
                                            <option value="">Choose one</option>';
                                            foreach (get_curs_type() as $key => $value):
                                                echo'<option value="'.$key.'">'.$value.'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-control" name="curs_status" data-choices required>
                                            <option value="">Choose one</option>';
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
                                        <label class="form-label">Deparments <span class="text-danger">*</span></label>
                                        <select class="form-control" name="id_dept" data-choices required>
                                            <option value="">Choose one</option>';
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
                                            <option value="">Choose one</option>';
                                            foreach ($Faculties as $faculty):
                                                echo'<option value="'.$faculty['faculty_id'].'">'.$faculty['faculty_name'].' - '.$faculty['faculty_code'].'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Specialization <span class="text-danger">*</span></label>
                                        <select class="form-control" name="curs_specialization" data-choices required>
                                            <option value="">Choose one</option>';
                                            foreach (get_is_publish() as $key => $value):
                                                echo'<option value="'.$key.'">'.$value.'</option>';
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
                                        <select class="form-control" name="id_level" data-choices required>
                                            <option value="">Choose one</option>';
                                            foreach (get_course_level() as $key => $value):
                                                echo'<option value="'.$key.'">'.$value.'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Pre Requisite </label>
                                        <select class="form-control" name="curs_pre_requisite" data-choices>
                                            <option value="">Choose one</option>';
                                            foreach ($courses as $curs):
                                                echo'<option value="'.$curs['curs_id'].'">'.$curs['curs_name'].' - '.$curs['curs_code'].'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>                                
                                <div class="col">
                                    <label class="form-label">Introduction Video (YT Short Code) <span class="text-danger">*</span></label>
                                    <input type="text" name="curs_video"  class="form-control" required="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Language <span class="text-danger">*</span></label>
                                        <select class="form-control" name="id_lang[]" data-choices data-choices-removeItem multiple required>
                                            <option value="">Choose one</option>';
                                            foreach ($languages as $lang):
                                                echo'<option value="'.$lang['lang_id'].'">'.$lang['lang_name'].' - '.$lang['lang_code'].'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Credit Hours (Theory)</label>
                                        <input type="number" name="cur_credithours_theory" class="form-control" >
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Credit Hours (Practical)</label>
                                        <input type="number" name="cur_credithours_practical" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Squencing Category </label>
                                        <select class="form-control" name="sequencing_category[]" data-choices data-choices-removeItem multiple >
                                            <option value="">Choose multiple</option>';
                                            foreach ($programsCat as $cat):
                                                echo'<option value="'.$cat['cat_id'].'">'.$cat['cat_name'].' - '.$cat['cat_code'].'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Course Wise <span class="text-danger">*</span></label>
                                        <select class="form-control" id="curs_wise" name="curs_wise" data-choices required>
                                            <option value="">Choose one</option>';
                                            foreach (get_CourseWise() as $key => $val):
                                                echo'<option value="'.$key.'">'.$val.'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col mb-2" id="duration">
                                    <label class="form-label">Duration <span class="text-danger">*</span></label>
                                    <select class="form-control" data-choices name="duration" required>
                                        <option value=""> Choose Course Wise First</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Rating </label>
                                        <select class="form-control" name="curs_rating" data-choices data-choices-removeItem>
                                            <option value="">Choose one</option>';
                                            for ($i=1; $i <=5 ; $i++) { 
                                                echo'<option value="'.$i.'">'.$i.' Star</option>';
                                            }
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Course Hours</label>
                                        <input type="number" name="curs_hours" class="form-control">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Type <span class="text-danger">*</span></label>
                                        <select class="form-control" name="curs_type_status" data-choices required>
                                            <option value="">Choose one</option>';
                                            foreach (get_curs_status() as $key => $value):
                                                echo'<option value="'.$key.'">'.$value.'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                            </div>';
                            /*
                            echo'
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2 mt-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" name="best_seller" id="best_seller" value="1">
                                            <label class="form-check-label">Best Seller</label>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                            */
                            echo'
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header alert-primary">
                            <h6 class="mb-0"><i class="bx bx-image align-middle  fs-18 me-1"></i>Images</h6>
                        </div>
                        <div class="card-body border">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Icon <span class="text-danger">*</span></label>
                                        <input type="file" name="curs_icon" accept="image/*" class="form-control" required/>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Image <span class="text-danger">*</span></label>
                                        <input type="file" name="curs_photo" accept="image/*" class="form-control" required/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header alert-secondary">
                            <h6 class="mb-0"><i class="bx bx-paperclip align-middle  fs-18 me-1"></i>Additional Detail</h6>
                        </div>
                        <div class="card-body border">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Meta Keywords </label>
                                        <input type="text" class="form-control" name="curs_keyword" id="choices-text-remove-button" data-choices data-choices-limit="10" data-choices-removeItem/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Meta Description </label>
                                        <textarea class="form-control" name="curs_meta"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">References </label>
                                        <textarea class="form-control" name="curs_references"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Detail </label>
                                        <textarea class="form-control" id="ckeditor2" name="curs_detail"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Skills <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="curs_skills" data-choices data-choices-limit="10" data-choices-removeItem/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">What Will You Learn  <button class="btn btn-primary btn-sm" id="duplicateButton" type="button"><i class="ri-add-circle-line align-bottom"></i></button></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="what_you_work_div">
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="ri-double-quotes-l"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="what_you_learn[]">
                                        <button class="btn btn-danger delete-button" disabled><i class="bx bx-trash-alt"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div id="targetDiv"></div>
                            <div class="row">';
                                foreach($textareaFields as $name => $field): 
                                    echo '
                                    <div class="'.$field['class'].'">
                                        <div class="mb-2">
                                            <label class="form-label">'.$field['title'].' '.(($field['required'])? '<span class="text-danger">*</span>': '').'</label>
                                            <textarea class="form-control" id="'.$field['id'].'" name="'.$name.'" '.($field['required']? 'required': '').'></textarea>
                                        </div>
                                    </div>';
                                endforeach;
                                echo '
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="'.moduleName().'.php?'.$redirection.'" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
                        <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.$pageTitle.'</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>';
?>