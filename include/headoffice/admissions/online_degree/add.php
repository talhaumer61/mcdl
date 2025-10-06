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
echo'
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h5 class="mb-0 text-white"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(false).'</h5>
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
                                    <input type="text" name="deg_name" class="form-control" required="">
                                </div>
                                <div class="col">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" name="deg_status" data-choices required="">
                                        <option value="">Choose one</option>';
                                        foreach (get_status() as $key => $status):
                                            echo'<option value="'.$key.'">'.$status.'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label">Facility <span class="text-danger">*</span></label>
                                    <select class="form-control" name="id_faculty" data-choices required="">
                                        <option value="">Choose one</option>';
                                        foreach ($FACILITY as $key => $value):
                                            echo'<option value="'.$value['faculty_id'].'">'.$value['faculty_name'].'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </div>
                            </div> 
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="text" name="deg_startdate"  data-provider="flatpickr" class="form-control" required="">
                                </div>
                                <div class="col">
                                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="text" name="deg_enddate"  data-provider="flatpickr" class="form-control" required="">
                                </div>
                                <div class="col">
                                    <label class="form-label">Introduction Video (YT Short Code) <span class="text-danger">*</span></label>
                                    <input type="text" name="deg_video"  class="form-control" required="">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header alert-primary">
                            <h6 class="mb-0"><i class="bx bx-image align-middle  fs-18 me-1"></i>Attachments <span class="text-danger">(Mandatory)</span></h6>
                        </div>
                        <div class="card-body border">
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label">Icon <span class="text-danger">*</span></label>
                                    <input type="file" name="deg_icon" id="deg_icon" accept="image/*" class="form-control" required/>
                                </div>
                                <div class="col">
                                    <label class="form-label">Image <span class="text-danger">*</span></label>
                                    <input type="file" name="deg_photo" id="deg_photo" accept="image/*" class="form-control" required/>
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
                                        <input class="form-control" name="deg_shortdetail" required=""></input>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="ckeditor" name="deg_detail" required=""></textarea>
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
                                    <select class="form-control" name="id_cat" onchange="get_courses(this.value);" data-choices required="">
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
                                        echo'
                                        <label class="form-label">List of '.$value.'</label>
                                        <ul class="list-group mb-2 mt-2" id="'.to_seo_url($value).'">
                                            <li class="list-group-item x-auto text-danger">
                                                <b>No Record Found</b> 
                                            </li>
                                        </ul>';
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
                                            echo'<option value="'.$key.'">'.$value.'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label">How Many Semester <span class="text-danger">*</span></label>
                                    <select class="form-control" name="deg_semester" data-choices required="">
                                        <option value="">Choose one</option>';
                                        foreach (get_semester() as $key => $value):
                                            echo'<option value="'.$key.'">'.$value.'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label">Fee Per Semester <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="deg_feepersemester" placeholder="$ 0.00" required=""></input>
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
                                    <input type="text" class="form-control" name="deg_metakeyword" id="choices-text-remove-button" data-choices data-choices-limit="10" data-choices-removeItem/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="deg_metadescription"></textarea>
                                </div>
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