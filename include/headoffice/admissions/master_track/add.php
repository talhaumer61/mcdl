<?php
//COURSES CATEGORY
$condition = array ( 
                         'select'       =>  'cat_id, cat_name'
                        ,'where'        =>  array(
                                                     'cat_status'  =>  1
                                                    ,'is_deleted'   =>  0
                                            )
                        ,'order_by'     =>  'cat_name ASC'
                        ,'return_type'  =>  'all'
                    ); 
$COURSES_CATEGORIES = $dblms->getRows(COURSES_CATEGORIES, $condition);

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
echo'
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h5 class="mb-0 modal-title"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(false).'</h5>
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
                                    <input type="text" name="mas_name" class="form-control" required="">
                                </div>
                                <div class="col">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" name="mas_status" data-choices required="">
                                        <option value="">Choose one</option>';
                                        foreach (get_status() as $key => $status):
                                            echo'<option value="'.$key.'">'.$status.'</option>';
                                        endforeach;
                                        echo'
                                    </select>
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
                                    <input type="file" name="mas_icon" id="mas_icon" accept="image/*" class="form-control" required/>
                                </div>
                                <div class="col">
                                    <label class="form-label">Image <span class="text-danger">*</span></label>
                                    <input type="file" name="mas_photo" id="mas_photo" accept="image/*" class="form-control" required/>
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
                                        <input class="form-control" name="mas_shortdetail" required=""></input>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="mas_detail" id="ckeditor"  required=""></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Program Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="mas_prg_detail" id="ckeditor1"  required=""></textarea>
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
                        <div class="row">
                            <div class="col">
                                <div class="mb-2">
                                    <label class="form-label">Skills <span class="text-danger">*</span></label>
                                    <select class="form-control" name="id_skills[]" data-choices data-choices-removeItem multiple required>
                                        <option value="">Choose multiple</option>';
                                        foreach ($course_skills as $skill):
                                            echo'<option value="'.$skill['skill_id'].'">'.$skill['skill_name'].'</option>';
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
                                                echo'<option value="'.$category['mstcat_id'].'">'.$category['mstcat_name'].'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <label class="form-label">Duration (Months) <span class="text-danger">*</span></label>
                                    <input type="number" name="mas_duration" class="form-control" required="">
                                </div>
                                <div class="col">
                                    <label class="form-label">Introduction Video (YT Short Code) <span class="text-danger">*</span></label>
                                    <input type="text" name="mas_video"  class="form-control" required="">
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
                                    <select class="form-control" name="cat_id" data-choices onchange="get_courses(this.value)" required="">
                                        <option value="">Choose one</option>';
                                        foreach ($COURSES_CATEGORIES as $key => $value):
                                            echo'<option value="'.$value['cat_id'].'">'.$value['cat_name'].'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </div>
                                <div class="col-5" id="cat">
                                    <label class="form-label">Courses <span class="text-danger">*</span></label>
                                    <select class="form-control" data-choices data-choices-removeItem multiple required="">
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
                                    <ul class="list-group" id="list_of_courses">
                                    </ul>
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
                                    <input type="text" class="form-control" name="mas_metakeyword" id="choices-text-remove-button" data-choices data-choices-limit="10" data-choices-removeItem/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="mas_metadescription"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="'.moduleName().'.php" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
                        <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(0).'</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>';
?>
<script>
    $(document).ready(function(){
        function alertMsg(msg,color){
            Toastify({
                newWindow   : !0,
                text        : ""+msg+"",
                gravity     : "top",
                position    : "right",
                className   : "bg-"+color+"",
                stopOnFocus : !0,
                offset      : "50",
                duration    : "2000",
                close       : "close",
                style       : "style",
            }).showToast();
        }
        // $('#degree_form').on('submit',function(e){
        //     e.preventDefault();
        //     var formData = new FormData(this);
        //     jQuery.ajax({
        //         url         : "include/ajax/get_degree_data.php?view=<?= cleanvars($rootDir).moduleName().'/'; ?>", 
        //         type        : "POST",
        //         data        : formData, 
        //         contentType : false, 
        //         processData : false, 
        //         success:function(result) {
        //             if (result === 'mas_already_exist') {
        //                 alertMsg('Degree Already In Your Darft List','warning');
        //             } else if (result === 'mas_added_to_draft') {
        //                 alertMsg('Degree Added In Your Darft List','success');
        //                 $('#mas_name').val('');
        //             } else {

        //             }
        //         }
        //     }); 
        // });
    });
</script>