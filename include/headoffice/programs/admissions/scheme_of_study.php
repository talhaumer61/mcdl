<?php
// CATEGORY
$condition = array ( 
                         'select'       =>  'cat_id, cat_name'
                        ,'where'        =>  array(
                                                     'cat_status'  =>  '1'
                                                    ,'is_deleted'   =>  '0'
                                                )
                        ,'order_by'     =>  'cat_name'
                        ,'return_type'  =>  'all'
                    ); 
$CATEGORY = $dblms->getRows(COURSES_CATEGORIES, $condition);

$condition = array ( 
                         'select'       =>  'sch.id_curs, cs.cat_name, cs.cat_id'
                        ,'join'         =>  'INNER JOIN '.COURSES_CATEGORIES.' cs on cs.cat_id = sch.id_cat'
                        ,'where'        =>  array(
                                                    'sch.id_ad_prg'     =>  cleanvars($_GET['id'])
                                                )
                        ,'return_type'  =>  'all'
                    );
$coursescondition = array ( 
                                 'select'       =>  'curs_name, curs_code'
                                ,'where'        =>  array(
                                                             'curs_status'  =>  1
                                                            ,'is_deleted'   =>  0
                                                    )
                                ,'return_type'  =>  'single'
                            );
echo'
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header bg-info">
                <h5 class="modal-title"><i class="ri-book-mark-line align-bottom me-1"></i>Make '.moduleName(LMS_VIEW).'</h5>
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
                                    <label class="form-label">Program <span class="text-danger">*</span></label>
                                    <input type="hidden" class="form-control" name="id" value="'.$_GET['id'].'" readonly/>
                                    <input type="text" class="form-control" value="'.$_GET['program'].'" readonly/>
                                </div>
                                <div class="col">
                                    <label class="form-label">Session <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="'.$_GET['sess_name'].'" readonly/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header alert-primary">
                            <h6 class="mb-0"><i class="bx bx-paperclip align-middle fs-18 me-1"></i>Courses <span class="text-danger">(Mandatory)</span></h6>
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
                                            <select class="form-control" data-choices data-choice-multiple data-course-type="'.$key.'">
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
                                        $condition['where']['id_curstype'] = $key;
                                        $PROGRAMS_STUDY_SCHEME = $dblms->getRows(PROGRAMS_STUDY_SCHEME.' sch', $condition); 
                                        echo'
                                        <label class="form-label mt-3 mb-1">List of '.$value.'</label>
                                        <ul class="list-group" id="'.to_seo_url($value).'">';
                                            if ($PROGRAMS_STUDY_SCHEME) {
                                                foreach ($PROGRAMS_STUDY_SCHEME as $detail) {
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
                                            }else{
                                                echo'
                                                <li class="list-group-item x-auto text-danger" data-remove="true">
                                                    <b>No Record Found</b> 
                                                </li>';
                                            }
                                            echo'
                                        </ul>';
                                    }
                                    echo'
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="'.moduleName().'.php" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
                        <button type="submit" class="btn btn-info btn-sm" name="make_study_scheme"><i class="ri-book-mark-line align-bottom me-1"></i>Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>';
?>