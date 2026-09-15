<?php
// ADMISSION_OFFERING
$condition = array ( 
                     'select'       => "c.curs_id, c.curs_name"
                    ,'join'         => 'INNER JOIN '.COURSES.' AS c ON c.curs_id = ao.admoff_degree'
                    ,'where' 	    =>  array( 
                                                 'c.curs_status'    => 1
                                                ,'c.is_deleted'     => 0
                                                ,'ao.admoff_status' => 1
                                                ,'ao.is_deleted'    => 0
                                                ,'ao.admoff_type'   => 3
                                            )
                    ,'order_by'     =>  'c.curs_id ASC'
                    ,'group_by'     =>  ' c.curs_id '
                    ,'return_type'  =>  'all' 
                   ); 
$ADMISSION_OFFERING = $dblms->getRows(ADMISSION_OFFERING.' AS ao ', $condition);
// ADMISSION_OFFERING
$condition = array ( 
                     'select'       => "c.curs_id, c.curs_name"
                    ,'join'         => 'INNER JOIN '.COURSES.' AS c ON c.curs_id = ao.admoff_degree'
                    ,'where' 	    =>  array( 
                                                 'c.curs_status'    => 1
                                                ,'c.is_deleted'     => 0
                                                ,'ao.admoff_status' => 1
                                                ,'ao.is_deleted'    => 0
                                                ,'ao.admoff_type'   => 3
                                            )
                    ,'order_by'     =>  'c.curs_id ASC'
                    ,'group_by'     =>  ' c.curs_id '
                    ,'return_type'  =>  'all' 
                   ); 
$ADMISSION_OFFERING = $dblms->getRows(ADMISSION_OFFERING.' AS ao ', $condition);
// ADMINS
$condition = array ( 
                     'select'       => "adm_id, adm_fullname, adm_photo, is_teacher"
                    ,'where' 	    =>  array( 
                                                 'a.adm_status'    => 1
                                                ,'a.is_deleted'    => 0
                                            )
                    ,'not_equal' 	=>	array( 
                                                'a.is_teacher'	    =>	0
                                            )	
                    ,'search_by'    =>  ' AND NOT EXISTS (
                                                SELECT 1 
                                                FROM '.REFERRAL_CONTROL.' AS r 
                                                WHERE FIND_IN_SET(a.adm_id, r.id_user)
                                                AND r.ref_date_time_to > "'.date('Y-m-d G:i:s').'"
                                            ) '
                    ,'return_type'  =>  'all' 
                   ); 
$ADMINS = $dblms->getRows(ADMINS.' AS a ', $condition , $sql);
echo'
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h5 class="mb-0 modal-title"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(false).'</h5>
            </div>
            <form enctype="multipart/form-data" autocomplete="off" method="post" accept-charset="utf-8">
                <div class="card-body create-project-main">
                    <div class="row mb-2">
                        <div class="col">
                            <div class="card mb-3">
                                <div class="card-header alert-primary">
                                    Students And Teachers Referral Access
                                </div>
                                <div class="card-body border">
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label class="form-label">Students And Teachers</label>
                                            <select class="form-control" name="id_user[]" data-choices data-choices-removeitem multiple>
                                                <option value="">Choose one</option>';
                                                foreach ($ADMINS as $key => $val):
                                                    echo'<option value="'.$val['adm_id'].'">'.($val['is_teacher'] == 2?' (Teacher) ':($val['is_teacher'] == 1?' (Student) ':' (Both) ')).' '.$val['adm_fullname'].'</option>';
                                                endforeach;
                                                echo'
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card mb-3">
                                <div class="card-header alert-primary">
                                    Course Referral Access
                                </div>
                                <div class="card-body border">
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label class="form-label">Courses</label>
                                            <select class="form-control" name="id_curs[]" data-choices data-choices-removeitem multiple>
                                                <option value="">Choose one</option>';
                                                foreach ($ADMISSION_OFFERING as $key => $val):
                                                    echo'<option value="'.$val['curs_id'].'">'.$val['curs_name'].'</option>';
                                                endforeach;
                                                echo'
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <div class="card mb-3">
                                <div class="card-header alert-primary">
                                    Percentage, Date & Time Control
                                </div>
                                <div class="card-body border">
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label class="form-label">Referral Percentage <span class="text-danger">*</span></label>
                                            <input type="number" min="1" max="100" name="ref_percentage" id="ref_percentage" class="form-control" required/>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" name="ref_status" data-choices required="">
                                                <option value="">Choose one</option>';
                                                foreach (get_status() as $key => $status):
                                                    echo'<option value="'.$key.'">'.$status.'</option>';
                                                endforeach;
                                                echo'
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label class="form-label">Date & Time From <span class="text-danger">*</span></label>
                                            <input type="text" name="ref_date_time_from" id="ref_date_time_from" data-provider="flatpickr" data-minDate="'.date("Y-m-d").'" data-date-format="Y-m-d" data-enable-time class="form-control" required/>
                                        </div>                                        
                                        <div class="col">
                                            <label class="form-label">Date & Time To <span class="text-danger">*</span></label>
                                            <input type="text" name="ref_date_time_to" id="ref_date_time_to" data-provider="flatpickr" data-minDate="'.date("Y-m-d").'" data-date-format="Y-m-d" data-enable-time class="form-control" required/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <div class="card mb-3">
                                <div class="card-header alert-primary">
                                    Extra Information
                                </div>
                                <div class="card-body border">
                                    <label class="form-label">Remarks</label>
                                    <textarea class="form-control" name="ref_remarks"></textarea>
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