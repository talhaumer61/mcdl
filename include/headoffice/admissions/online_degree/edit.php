<?php
// CATEGORY
$condition = array ( 
                         'select'       =>  'cat_id,cat_name'
                        ,'where'        =>  array(
                                                     'cat_status'  =>  '1'
                                                    ,'is_deleted'   =>  '0'
                                                )
                        ,'order_by'     =>  'cat_name'
                        ,'return_type'  =>  'all'
                    ); 
$CATEGORY = $dblms->getRows(COURSES_CATEGORIES, $condition);
// FACILITY
$condition = array ( 
                         'select'       =>  'faculty_id,faculty_name'
                        ,'where'        =>  array(
                                                     'faculty_status'  =>  '1'
                                                    ,'is_deleted'   =>  '0'
                                                )
                        ,'order_by'     =>  'faculty_name'
                        ,'return_type'  =>  'all'
                    ); 
$FACILITY = $dblms->getRows(FACULTIES, $condition);
// DEGREE
$condition = array ( 
                         'select'        =>  'deg_id,deg_status,deg_name,deg_metakeyword, deg_metadescription,deg_detail,deg_shortdetail,deg_feepersemester,deg_semester,id_degtype,id_cat, deg_startdate, deg_enddate, deg_video, id_faculty'
                        ,'where' 	    =>  array( 
                                                     'is_deleted'    => 0
                                                    ,'deg_id'        => cleanvars(LMS_EDIT_ID)
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$DEGREE = $dblms->getRows(DEGREE, $condition);

$coursescondition = array ( 
    'select'       =>  'curs_name, curs_code'
   ,'where'        =>  array(
                                'curs_status'  =>  1
                               ,'is_deleted'   =>  0
                       )
   ,'return_type'  =>  'single'
);

$condition = array ( 
    'select'       =>  'd.id_curs, cs.cat_name, cs.cat_id'
    ,'where'       => array(
                                 'id_deg'       => cleanvars(LMS_EDIT_ID)
                            )
    ,'join'         => 'inner join '.COURSES_CATEGORIES.' cs on cs.cat_id=d.id_cat'
    ,'return_type'  =>  'all'
);

echo'
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header bg-info">
                <h5 class="mb-0 text-white"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(0).'</h5>
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
                                    <input type="text" name="deg_name" value="'.$DEGREE['deg_name'].'" class="form-control" required="">
                                </div>
                                <div class="col">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" name="deg_status" data-choices required="">
                                        <option value="">Choose one</option>';
                                        foreach (get_status() as $key => $status):
                                            echo'<option value="'.$key.'" '.($key == $DEGREE['deg_status'] ? 'selected':'').'>'.$status.'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label">Facility <span class="text-danger">*</span></label>
                                    <select class="form-control" name="id_faculty" data-choices required="">
                                        <option value="">Choose one</option>';
                                        foreach ($FACILITY as $key => $value):
                                            echo'<option value="'.$value['faculty_id'].'" '.($value['faculty_id'] == $DEGREE['id_faculty'] ? 'selected' : '').'>'.$value['faculty_name'].'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </div>
                            </div> 
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="text" name="deg_startdate" value="'.$DEGREE['deg_startdate'].'"  data-provider="flatpickr" class="form-control" required="">
                                </div>
                                <div class="col">
                                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="text" name="deg_enddate" value="'.$DEGREE['deg_enddate'].'"  data-provider="flatpickr" class="form-control" required="">
                                </div>
                                <div class="col">
                                    <label class="form-label">Introduction Video (YT Short Code) <span class="text-danger">*</span></label>
                                    <input type="text" name="deg_video" value="'.$DEGREE['deg_video'].'"  class="form-control" required="">
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
                                    <input type="file" name="deg_icon" id="deg_icon" accept="image/*" class="form-control"/>
                                </div>
                                <div class="col">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="deg_photo" id="deg_photo" accept="image/*" class="form-control"/>
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
                                        <input class="form-control" name="deg_shortdetail" value="'.$DEGREE['deg_shortdetail'].'" required=""></input>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="ckeditor" name="deg_detail" required="">'.html_entity_decode($DEGREE['deg_detail']).'</textarea>
                                    </div>
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
                                <div class="col">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-control" name="id_cat" onchange="get_courses(this.value);" data-choices>
                                        <option value="">Choose one</option>';
                                        foreach ($CATEGORY as $key => $value):
                                            echo'<option value="'.$value['cat_id'].'">'.$value['cat_name'].'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </div>
                            </div>
                            <div class="courses_section">';
                            foreach (get_degree_course_type() as $key => $value):
                                echo'
                                <div class="row mb-2">
                                    <div class="col courses_section">
                                        <label class="form-label">'.$value.' <span class="text-danger">*</span></label>
                                        <select class="form-control" data-course-type="'.$key.'">
                                            <option value="">Choose Category First</option>
                                        </select>
                                    </div>
                                </div>';
                            endforeach;
                            echo '
                            </div>
                            <div class="row">
                                <div class="col">';
                                    foreach (get_degree_course_type() as $key => $value) {
                                        $condition['where']['id_curstype']=$key;
                                        $DEGREE_DETAIL = $dblms->getRows(DEGREE_DETAIL.' d', $condition); 
                                        echo '
                                        <label class="form-label mt-3 mb-1">List of '.$value.'</label>
                                        <ul class="list-group" id="'.to_seo_url($value).'">';
                                        if ($DEGREE_DETAIL) {
                                            foreach ($DEGREE_DETAIL as $detail) {
                                                foreach (explode(',',$detail['id_curs']) as $curs) {
                                                    $coursescondition['where']['curs_id'] = $curs;
                                                    $COURSES = $dblms->getRows(COURSES, $coursescondition);
                                                    echo '
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        '.$COURSES['curs_name'].' ('.$COURSES['curs_code'].') ( '.$detail['cat_name'].' ) 
                                                        <input type="hidden" name="id_curs['.$key.']['.$detail['cat_id'].'][]" value="'.$curs.'">
                                                        <span onclick="remove_list(this)"><a class="btn btn-danger btn-xs"><i class="mdi mdi-delete-outline"></i></a></span>
                                                    </li>';
                                                } 
                                            }    
                                        } else{
                                            echo '
                                            <li class="list-group-item x-auto text-danger">
                                                <b>No Record Found</b> 
                                            </li>';
                                        }                             
                                        echo'</ul>';
                                    }
                                echo'
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header alert-primary">
                            <h6 class="mb-0"><i class="bx bx-paperclip align-middle  fs-18 me-1"></i>Semester & Financing <span class="text-danger">(Mandatory)</span></h6>
                        </div>
                        <div class="card-body border">
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label">Program <span class="text-danger">*</span></label>
                                    <select class="form-control" name="id_degtype" data-choices required="">
                                        <option value="">Choose one</option>';
                                        foreach (get_educationtypes() as $key => $value):
                                            echo'<option value="'.$key.'" '.($key == $DEGREE['id_degtype'] ? 'selected' : '').'>'.$value.'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label">How Many Semester <span class="text-danger">*</span></label>
                                    <select class="form-control" name="deg_semester" data-choices required="">
                                        <option value="">Choose one</option>';
                                        foreach (get_semester() as $key => $value):
                                            echo'<option value="'.$key.'" '.($key == $DEGREE['deg_semester'] ? 'selected' : '').'>'.$value.'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label">Fee Per Semester <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="deg_feepersemester" value="'.$DEGREE['deg_feepersemester'].'" placeholder="$ 0.00" required=""></input>
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
                                    <input type="text" class="form-control" name="deg_metakeyword" value="'.$DEGREE['deg_metakeyword'].'" id="choices-text-remove-button" data-choices data-choices-limit="10" data-choices-removeItem/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="deg_metadescription">'.($DEGREE['deg_metadescription']).'</textarea>
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