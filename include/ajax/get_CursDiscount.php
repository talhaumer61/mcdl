<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";
echo '
<script src="assets/js/app.js"></script>
<script src="assets/js/jquery.js"></script>';
if (($_POST['_discount_method'] == 'fixed' || $_POST['_discount_method'] == 'percentage') && isset($_POST['id_type']) && isset($_POST['_edit'])) {
   $condition = array ( 
                         'select'       =>  'dd.discount_detail_id, dd.id_curs, dd.discount_type, dd.discount, c.curs_id, c.curs_name, c.curs_icon, c.curs_photo, ao.admoff_amount'
                        ,'join' 	    =>  'INNER JOIN '.COURSES.' AS c ON c.curs_id = dd.id_curs
                                                INNER JOIN '.ADMISSION_OFFERING.' AS ao ON ( ao.admoff_type IN (3,4) AND ao.admoff_degree = dd.id_curs )'
                        ,'where' 	    =>  array( 
                                                    'dd.id_setup'         => cleanvars($_POST['_edit'])
                                                )
                        ,'order_by'     =>  ' dd.discount_detail_id ASC '
                        ,'return_type'  =>  'all'
                    ); 
    $DISCOUNT_DETAIL = $dblms->getRows(DISCOUNT_DETAIL.' AS dd', $condition);
    if ($DISCOUNT_DETAIL) {
        echo'
        <div class="col mb-2 mt-2">
            <label class="form-label">Courses <span class="text-danger">*</span></label>
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40" class="text-center"></th>
                        <th>Name</th>
                        <th width="150" class="text-end">Amount</th>
                        <th width="150" class="text-end">After Discount</th>
                    </tr>
                </thead>
                <tbody>';
                    foreach ($DISCOUNT_DETAIL as $cursKey => $row) {
                        $curs_icon = ((!empty($row['curs_icon']) && file_exists('uploads/images/courses/icons/'.$row['curs_icon'])) ? 'uploads/images/courses/icons/'.$row['curs_icon'].'' : ''.SITE_URL.'uploads/default.png');
                        echo '
                        <tr style="vertical-align: middle;">
                            <td class="text-center">
                                <input type="hidden" name="id_all_curs['.$cursKey.']" value="'.$row['curs_id'].'"/>
                                <div class="form-check form-switch">
                                    <input class="form-check-input cursCheckAll" type="checkbox" role="switch" id="cursCheck_'.$cursKey.'" name="which_curs['.$cursKey.']" value="1" checked />
                                    <script>
                                        $("#cursCheck_'.$cursKey.'").change(function() {
                                            if ($(this).is(":checked")) {
                                                $(".cursCheck_'.$cursKey.'_Show").show();
                                            } else {
                                                $(".cursCheck_'.$cursKey.'_Show").hide();
                                            }
                                        });
                                    </script>
                                </div>
                            </td>
                            <td>
                                <span>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-sm bg-light rounded p-1">
                                                <img src="'.$curs_icon.'" alt="" class="img-fluid d-block">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="fs-14 mb-1">'.$row['curs_name'].'</h5>
                                        </div>
                                    </div>
                                </span>
                            </td>
                            <td class="text-end cursCheck_'.$cursKey.'_Show">
                                '.number_format($row['admoff_amount']).'.00
                            </td>
                            <td class="text-end cursCheck_'.$cursKey.'_Show">';
                                if ($_POST['_discount_method'] == 'fixed') {
                                    echo'
                                    '.number_format(($row['admoff_amount'] - cleanvars($_POST['_discount_fixed_value']))).'.00';
                                } 
                                if ($_POST['_discount_method'] == 'percentage') {
                                    echo'
                                    '.number_format(($row['admoff_amount'] - ($row['admoff_amount'] * (cleanvars($_POST['_discount_percentage_value']) / 100) ))).'.00';
                                }
                                echo'
                            </td>
                        </tr>';
                    }
                    echo'
                </tbody>
            </table>
        </div>';
    } else {
        echo'
        <span class="text-danger">No Course Found</span>';
    }
}

if (($_POST['_discount_method'] == 'fixed' || $_POST['_discount_method'] == 'percentage') && isset($_POST['id_type']) && !isset($_POST['_edit'])) {
    // COURSES
    $condition = array ( 
                         'select'       => 'ao.admoff_amount, cc.curs_id, cc.curs_name, cc.curs_icon, cc.curs_photo, cc.curs_code'
                        ,'join'         =>'INNER JOIN '.COURSES.' AS cc ON cc.curs_id = ao.admoff_degree'
                        ,'where' 	    =>  array( 
                                                     'cc.curs_status'       => 1
                                                    ,'cc.is_deleted'        => 0
                                                    ,'ao.admoff_status'     => 1
                                                    ,'ao.is_deleted'        => 0
                                                    ,'ao.admoff_type'       => $_POST['id_type']
                                                )
                        ,'groupt_by'    =>  ' admoff_id '
                        ,'return_type'  =>  'all'
                    );
    $COURSES = $dblms->getRows(ADMISSION_OFFERING.' AS ao', $condition, $sql);
    if ($COURSES) {
        $dateArray 		    = explode(' to ', $_POST['discount_date']);
        $selectedStartDate  = date('Y-m-d',strtotime($dateArray[0]));
        $selectedEndDate    = date('Y-m-d',strtotime($dateArray[1]));
        echo'
        <div class="col mb-2 mt-2">
            <label class="form-label">Courses <span class="text-danger">*</span></label>
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>                        
                        <th width="40" class="text-center"></th>
                        <th>Name</th>
                        <th width="150" class="text-end">Amount</th>
                        <th width="150" class="text-end">After Discount</th>
                    </tr>
                </thead>
                <tbody>';
                    foreach ($COURSES as $cursKey => $row) {
                        $dateFlag   = true;
                        $condition = array ( 
                                             'select'       => 'dd.discount_detail_id, d.discount_name, d.discount_from, d.discount_to'
                                            ,'join'         => 'INNER JOIN '.DISCOUNT_DETAIL.' AS dd on d.discount_id = dd.id_setup AND dd.id_curs = '.$row['curs_id'].''
                                            ,'where' 	    =>  array( 
                                                                         'd.discount_status'    => 1
                                                                        ,'d.is_deleted'         => 0
                                                                    )
                                            ,'group_by'     =>  ' dd.discount_detail_id '
                                            ,'order_by'     =>  ' dd.discount_detail_id DESC'
                                            ,'return_type'  =>  'all'
                                        );
                        $DISCOUNT = $dblms->getRows(DISCOUNT.' AS d', $condition, $sql);
                        foreach ($DISCOUNT as $discountKey => $discountValue) {
                            $startDate  = $discountValue['discount_from'];
                            $endDate    = $discountValue['discount_to'];
                            if ($selectedEndDate >= $startDate && $selectedStartDate <= $endDate) {
                                $dateFlag = false;
                            }
                        }
                        $curs_icon = ((!empty($row['curs_icon']) && file_exists('uploads/images/courses/icons/'.$row['curs_icon'])) ? 'uploads/images/courses/icons/'.$row['curs_icon'].'' : ''.SITE_URL.'uploads/default.png');
                        echo '
                        <tr style="vertical-align: middle;">
                            <td class="text-center">';
                                if ($dateFlag) {
                                    echo'
                                    <input type="hidden" name="id_all_curs['.$cursKey.']" value="'.$row['curs_id'].'"/>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input cursCheckAll" type="checkbox" role="switch" id="cursCheck_'.$cursKey.'" name="which_curs['.$cursKey.']" value="1" checked />
                                        <script>
                                            $("#cursCheck_'.$cursKey.'").change(function() {
                                                if ($(this).is(":checked")) {
                                                    $(".cursCheck_'.$cursKey.'_Show").show();
                                                } else {
                                                    $(".cursCheck_'.$cursKey.'_Show").hide();
                                                }
                                            });
                                        </script>
                                    </div>';
                                }
                                echo'
                            </td>
                            <td>
                                <span>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-sm bg-light rounded p-1">
                                                <img src="'.$curs_icon.'" alt="" class="img-fluid d-block">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="fs-14 mb-1">'.$row['curs_name'].'</h5>
                                            <p class="text-muted mb-0">';
                                                foreach ($DISCOUNT as $discountKey => $discountValue) {
                                                    echo'                                                
                                                    <span class="badge badge-gradient-success"><b>'.$discountValue['discount_name'].'</b>: '.date('d M, Y',strtotime($discountValue['discount_from'])).' to '.date('d M, Y',strtotime($discountValue['discount_to'])).'</span>';
                                                }
                                                echo'
                                            </p>
                                        </div>
                                    </div>
                                </span>
                            </td>
                            <td class="text-end cursCheck_'.$cursKey.'_Show">
                                '.number_format($row['admoff_amount']).'.00
                            </td>
                            <td class="text-end cursCheck_'.$cursKey.'_Show">';
                                if ($dateFlag) {
                                    if ($_POST['_discount_method'] == 'fixed') {
                                        echo'
                                        '.number_format(($row['admoff_amount'] - cleanvars($_POST['_discount_fixed_value']))).'.00';
                                    } 
                                    if ($_POST['_discount_method'] == 'percentage') {
                                        echo'
                                        '.number_format(($row['admoff_amount'] - ($row['admoff_amount'] * (cleanvars($_POST['_discount_percentage_value']) / 100) ))).'.00';
                                    }
                                }
                                echo'
                            </td>
                        </tr>';
                    }
                    echo'
                </tbody>
            </table>
        </div>';
    } else {
        echo'
        <span class="text-danger">No Course Found</span>';
    }
}

if (isset($_POST['_discount_method']) && $_POST['_discount_method'] == 'date' && isset($_POST['id_type']) && !isset($_POST['_edit'])) {
    $condition = array ( 
                        'select'       => 'ao.admoff_amount, cc.curs_id, cc.curs_name, cc.curs_icon, cc.curs_photo, cc.curs_code'
                        ,'join'         =>'INNER JOIN '.COURSES.' AS cc ON cc.curs_id = ao.admoff_degree'
                        ,'where' 	    =>  array( 
                                                     'cc.curs_status'       => 1
                                                    ,'cc.is_deleted'        => 0
                                                    ,'ao.admoff_status'     => 1
                                                    ,'ao.is_deleted'        => 0
                                                    ,'ao.admoff_type'       => $_POST['id_type']
                                                )
                        ,'groupt_by'    =>  ' admoff_id '
                        ,'return_type'  =>  'all'
                    );
    $COURSES = $dblms->getRows(ADMISSION_OFFERING.' AS ao', $condition, $sql);
    if ($COURSES) {
        $dateArray 		    = explode(' to ', $_POST['discount_date']);
        $selectedStartDate  = date('Y-m-d',strtotime($dateArray[0]));
        $selectedEndDate    = date('Y-m-d',strtotime($dateArray[1]));
        echo'
        <div class="col mb-2 mt-2">
            <label class="form-label">'.get_offering_type($_POST['id_type']).' <span class="text-danger">*</span></label>
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>                        
                        <th width="40" class="text-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input cursCheckAll" type="checkbox" role="switch" id="cursCheck__all" value="1">
                            </div>
                            <script>
                                $("#cursCheck__all").change(function() {
                                    if ($("#cursCheck__all").is(":checked")) {
                                        $(".cursCheck__all_Show").prop("checked", true);
                                        $(".cursCheck__all_Show").each(function(index) {
                                            $("."+$(this).attr("id")+"_Show").show();
                                        });
                                    } else {
                                        $(".cursCheck__all_Show").prop("checked", false);
                                        $(".cursCheck__all_Show").each(function(index) {
                                            $("."+$(this).attr("id")+"_Show").hide();
                                        });
                                    }
                                });
                            </script>
                        </th>
                        <th>Name</th>
                        <th width="150" class="text-end">Amount</th>
                        <th width="300" class="text-center">Discount Type</th>
                        <th width="200" class="text-center">Discount</th>
                        <th width="150" class="text-end">After Discount</th>
                    </tr>
                </thead>
                <tbody>';
                    $currentDate = date('Y-m-d');
                    foreach ($COURSES as $cursKey => $row) {
                        $dateFlag   = true;
                        $condition = array ( 
                                             'select'       => 'dd.discount_detail_id, d.discount_name, d.discount_from, d.discount_to'
                                            ,'join'         => 'INNER JOIN '.DISCOUNT_DETAIL.' AS dd on d.discount_id = dd.id_setup AND dd.id_curs = '.$row['curs_id'].''
                                            ,'where' 	    =>  array( 
                                                                         'd.discount_status'    => 1
                                                                        ,'d.is_deleted'         => 0
                                                                    )
                                            ,'group_by'     =>  ' dd.discount_detail_id '
                                            ,'order_by'     =>  ' dd.discount_detail_id DESC'
                                            ,'return_type'  =>  'all'
                                        );
                        $DISCOUNT = $dblms->getRows(DISCOUNT.' AS d', $condition, $sql);
                        foreach ($DISCOUNT as $discountKey => $discountValue) {
                            $startDate  = $discountValue['discount_from'];
                            $endDate    = $discountValue['discount_to'];
                            if ($selectedEndDate >= $startDate && $selectedStartDate <= $endDate) {
                                $dateFlag = false;
                            }
                        }
                        $curs_icon = ((!empty($row['curs_icon']) && file_exists('uploads/images/courses/icons/'.$row['curs_icon'])) ? 'uploads/images/courses/icons/'.$row['curs_icon'].'' : ''.SITE_URL.'uploads/default.png');
                        echo '
                        <tr style="vertical-align: middle;">
                            <td class="text-center">';
                                if ($dateFlag) {
                                    echo'
                                    <input type="hidden" name="id_custom_curs['.$cursKey.']" value="'.$row['curs_id'].'"/>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input cursCheckAll cursCheck__all_Show" type="checkbox" role="switch" name="which_curs['.$cursKey.']" id="cursCheck_'.$cursKey.'" value="1">
                                    </div>
                                    <script>
                                        $("#cursCheck_'.$cursKey.'").change(function() {
                                            if ($(this).is(":checked")) {
                                                $(".cursCheck_'.$cursKey.'_Show").show();
                                            } else {
                                                $(".cursCheck_'.$cursKey.'_Show").hide();
                                            }
                                        });
                                    </script>';
                                }
                                echo'
                            </td>
                            <td>
                                <span>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-sm bg-light rounded p-1">
                                                <img src="'.$curs_icon.'" alt="" class="img-fluid d-block">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="fs-14 mb-1">'.$row['curs_name'].'</h5>
                                            <p class="text-muted mb-0">';
                                                foreach ($DISCOUNT as $discountKey => $discountValue) {
                                                    echo'                                                
                                                    <span class="badge badge-gradient-success"><b>'.$discountValue['discount_name'].'</b>: '.date('d M, Y',strtotime($discountValue['discount_from'])).' to '.date('d M, Y',strtotime($discountValue['discount_to'])).'</span>';
                                                }
                                                echo'
                                            </p>
                                        </div>
                                    </div>
                                </span>
                            </td>
                            <td class="text-end" id="orignal_amount'.$cursKey.'">
                                <script>
                                    var orignal_amount'.$cursKey.' = '.$row['admoff_amount'].';
                                </script>
                                '.number_format($row['admoff_amount']).'.00
                            </td>
                            <td class="cursCheck_'.$cursKey.'_Show" style="display: none;">
                                <select class="form-control" name="discount_type['.$cursKey.']" data-choices onchange="get_DiscountType_'.$cursKey.'(this.value);">
                                    <option value="">Select</option>';
                                    foreach (get_DiscountType() as $key => $value):
                                        echo'<option value="'.$key.'">'.$value.'</option>';
                                    endforeach;
                                    echo'
                                </select>
                                <script>
                                    function get_DiscountType_'.$cursKey.'(type) {
                                        if (type == 1) {
                                            $("#get_DiscountType_'.$cursKey.'_show").html(`<input type="text" name="discount['.$cursKey.']" onkeyup="discount_fixed'.$cursKey.'(this.value)" class="form-control" placeholder="Fixed: 0.00">`);
                                        } else if (type == 2) {
                                            $("#get_DiscountType_'.$cursKey.'_show").html(`<input type="text" name="discount['.$cursKey.']" onkeyup="percentage_fixed'.$cursKey.'(this.value)" class="form-control" placeholder="Percentage: 1-100%">`);
                                        } else {
                                            $("#get_DiscountType_'.$cursKey.'_show").html(`<span class="text-danger">Select Any Type</span>`);
                                        }
                                    }

                                    function discount_fixed'.$cursKey.'(discounted_amount) {
                                        var after_discount_'.$cursKey.' = (orignal_amount'.$cursKey.' - discounted_amount)
                                        $("#after_discount'.$cursKey.'_show").show();
                                        $("#after_discount'.$cursKey.'_show").html(after_discount_'.$cursKey.'.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                                    }
                                    function percentage_fixed'.$cursKey.'(discounted_percentage) {
                                        var after_discount_percentage'.$cursKey.' = (orignal_amount'.$cursKey.' * (discounted_percentage / 100) )
                                        var after_discount_'.$cursKey.' = (orignal_amount'.$cursKey.' - after_discount_percentage'.$cursKey.')
                                        $("#after_discount'.$cursKey.'_show").show();
                                        $("#after_discount'.$cursKey.'_show").html(after_discount_'.$cursKey.'.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                                    }
                                </script>
                            </td>
                            <td class="cursCheck_'.$cursKey.'_Show" id="get_DiscountType_'.$cursKey.'_show" style="display: none;">
                                
                            </td>
                            <td class="text-end" id="after_discount'.$cursKey.'_show" style="display: none;">
                                
                            </td>
                        </tr>';
                    }
                    echo'
                </tbody>
            </table>
        </div>';
    } else {
        echo'
        <span class="text-danger">No Course Found</span>';
    }
}

if (isset($_POST['_discount_method']) && $_POST['_discount_method'] == 'date' && isset($_POST['id_type']) && isset($_POST['_edit'])) {
    $condition = array ( 
                        'select'       => 'ao.admoff_amount, cc.curs_id, cc.curs_name, cc.curs_icon, cc.curs_photo, cc.curs_code'
                        ,'join'         =>'INNER JOIN '.COURSES.' AS cc ON cc.curs_id = ao.admoff_degree
                                            INNER JOIN '.DISCOUNT.' AS d ON d.discount_id = '.cleanvars($_POST['_edit']).'
                                            INNER JOIN '.DISCOUNT_DETAIL.' AS dd ON d.discount_id = dd.id_setup AND dd.id_curs = cc.curs_id'
                        ,'where' 	    =>  array( 
                                                     'cc.curs_status'       => 1
                                                    ,'cc.is_deleted'        => 0
                                                    ,'ao.admoff_status'     => 1
                                                    ,'ao.is_deleted'        => 0
                                                    ,'ao.admoff_type'       => $_POST['id_type']
                                                )
                        ,'groupt_by'    =>  ' admoff_id '
                        ,'return_type'  =>  'all'
                    );
    $COURSES = $dblms->getRows(ADMISSION_OFFERING.' AS ao', $condition, $sql);
    if ($COURSES) {
        $dateArray 		    = explode(' to ', $_POST['discount_date']);
        $selectedStartDate  = date('Y-m-d',strtotime($dateArray[0]));
        $selectedEndDate    = date('Y-m-d',strtotime($dateArray[1]));
        echo'
        <div class="col mb-2 mt-2">
            <label class="form-label">Courses <span class="text-danger">*</span></label>
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>                        
                        <th width="40" class="text-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input cursCheckAll" type="checkbox" role="switch" id="cursCheck__all" value="1">
                            </div>
                            <script>
                                $("#cursCheck__all").change(function() {
                                    if ($("#cursCheck__all").is(":checked")) {
                                        $(".cursCheck__all_Show").prop("checked", true);
                                        $(".cursCheck__all_Show").each(function(index) {
                                            $("."+$(this).attr("id")+"_Show").show();
                                        });
                                    } else {
                                        $(".cursCheck__all_Show").prop("checked", false);
                                        $(".cursCheck__all_Show").each(function(index) {
                                            $("."+$(this).attr("id")+"_Show").hide();
                                        });
                                    }
                                });
                            </script>
                        </th>
                        <th>Name</th>
                        <th width="150" class="text-end">Amount</th>
                        <th width="300" class="text-center">Discount Type</th>
                        <th width="200" class="text-center">Discount</th>
                        <th width="150" class="text-end">After Discount</th>
                    </tr>
                </thead>
                <tbody>';
                    $currentDate = date('Y-m-d');
                    foreach ($COURSES as $cursKey => $row) {
                        $dateFlag   = true;
                        $condition = array ( 
                                             'select'       => 'dd.discount_detail_id, d.discount_name, d.discount_from, d.discount_to'
                                            ,'join'         => 'INNER JOIN '.DISCOUNT_DETAIL.' AS dd on d.discount_id = dd.id_setup AND dd.id_curs = '.$row['curs_id'].''
                                            ,'where' 	    =>  array( 
                                                                         'd.discount_status'    => 1
                                                                        ,'d.is_deleted'         => 0
                                                                    )
                                            ,'not_equal'    =>  array(
                                                                        'd.discount_id'         =>  $_POST['_edit']
                                                                    )
                                            ,'group_by'     =>  ' dd.discount_detail_id '
                                            ,'order_by'     =>  ' dd.discount_detail_id DESC'
                                            ,'return_type'  =>  'all'
                                        );
                        $DISCOUNT = $dblms->getRows(DISCOUNT.' AS d', $condition, $sql);
                        foreach ($DISCOUNT as $discountKey => $discountValue) {
                            $startDate  = $discountValue['discount_from'];
                            $endDate    = $discountValue['discount_to'];
                            if ($selectedEndDate >= $startDate && $selectedStartDate <= $endDate) {
                                $dateFlag = false;
                            }
                        }
                        $curs_icon = ((!empty($row['curs_icon']) && file_exists('uploads/images/courses/icons/'.$row['curs_icon'])) ? 'uploads/images/courses/icons/'.$row['curs_icon'].'' : ''.SITE_URL.'uploads/default.png');
                        echo '
                        <tr style="vertical-align: middle;">
                            <td class="text-center">';
                                if ($dateFlag) {
                                    echo'
                                    <input type="hidden" name="id_custom_curs['.$cursKey.']" value="'.$row['curs_id'].'"/>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input cursCheckAll cursCheck__all_Show" type="checkbox" role="switch" name="which_curs['.$cursKey.']" id="cursCheck_'.$cursKey.'" value="1">
                                    </div>
                                    <script>
                                        $("#cursCheck_'.$cursKey.'").change(function() {
                                            if ($(this).is(":checked")) {
                                                $(".cursCheck_'.$cursKey.'_Show").show();
                                            } else {
                                                $(".cursCheck_'.$cursKey.'_Show").hide();
                                            }
                                        });
                                    </script>';
                                }
                                echo'
                            </td>
                            <td>
                                <span>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-sm bg-light rounded p-1">
                                                <img src="'.$curs_icon.'" alt="" class="img-fluid d-block">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="fs-14 mb-1">'.$row['curs_name'].'</h5>
                                            <p class="text-muted mb-0">';
                                                foreach ($DISCOUNT as $discountKey => $discountValue) {
                                                    echo'                                                
                                                    <span class="badge badge-gradient-success"><b>'.$discountValue['discount_name'].'</b>: '.date('d M, Y',strtotime($discountValue['discount_from'])).' to '.date('d M, Y',strtotime($discountValue['discount_to'])).'</span>';
                                                }
                                                echo'
                                            </p>
                                        </div>
                                    </div>
                                </span>
                            </td>
                            <td class="text-end" id="orignal_amount'.$cursKey.'">
                                <script>
                                    var orignal_amount'.$cursKey.' = '.$row['admoff_amount'].';
                                </script>
                                '.number_format($row['admoff_amount']).'.00
                            </td>
                            <td class="cursCheck_'.$cursKey.'_Show" style="display: none;">
                                <select class="form-control" name="discount_type['.$cursKey.']" data-choices onchange="get_DiscountType_'.$cursKey.'(this.value);">
                                    <option value="">Select</option>';
                                    foreach (get_DiscountType() as $key => $value):
                                        echo'<option value="'.$key.'">'.$value.'</option>';
                                    endforeach;
                                    echo'
                                </select>
                                <script>
                                    function get_DiscountType_'.$cursKey.'(type) {
                                        if (type == 1) {
                                            $("#get_DiscountType_'.$cursKey.'_show").html(`<input type="text" name="discount['.$cursKey.']" onkeyup="discount_fixed'.$cursKey.'(this.value)" class="form-control" placeholder="Fixed: 0.00">`);
                                        } else if (type == 2) {
                                            $("#get_DiscountType_'.$cursKey.'_show").html(`<input type="text" name="discount['.$cursKey.']" onkeyup="percentage_fixed'.$cursKey.'(this.value)" class="form-control" placeholder="Percentage: 1-100%">`);
                                        } else {
                                            $("#get_DiscountType_'.$cursKey.'_show").html(`<span class="text-danger">Select Any Type</span>`);
                                        }
                                    }

                                    function discount_fixed'.$cursKey.'(discounted_amount) {
                                        var after_discount_'.$cursKey.' = (orignal_amount'.$cursKey.' - discounted_amount)
                                        $("#after_discount'.$cursKey.'_show").show();
                                        $("#after_discount'.$cursKey.'_show").html(after_discount_'.$cursKey.'.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                                    }
                                    function percentage_fixed'.$cursKey.'(discounted_percentage) {
                                        var after_discount_percentage'.$cursKey.' = (orignal_amount'.$cursKey.' * (discounted_percentage / 100) )
                                        var after_discount_'.$cursKey.' = (orignal_amount'.$cursKey.' - after_discount_percentage'.$cursKey.')
                                        $("#after_discount'.$cursKey.'_show").show();
                                        $("#after_discount'.$cursKey.'_show").html(after_discount_'.$cursKey.'.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                                    }
                                </script>
                            </td>
                            <td class="cursCheck_'.$cursKey.'_Show" id="get_DiscountType_'.$cursKey.'_show" style="display: none;">
                                
                            </td>
                            <td class="text-end" id="after_discount'.$cursKey.'_show" style="display: none;">
                                
                            </td>
                        </tr>';
                    }
                    echo'
                </tbody>
            </table>
        </div>';
    } else {
        echo'
        <span class="text-danger">No Course Found</span>';
    }
}

if (isset($_POST['_discount_method']) && $_POST['_discount_method'] == 'discount_type') {
    echo '
    <div class="row">
        <div class="col">
            <label class="form-label">Discount Type <span class="text-danger">*</span></label>
            <select class="form-control" name="discount_type" data-choices onchange="discount_on_all_courses(this.value);">
                <option value="">Select</option>';
                foreach (get_DiscountType() as $key => $value):
                    echo'<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                echo'
            </select>
        </div>
        <div class="col discount_on_all_courses_show" style="display: none;">

        </div>
    </div>
    <div class="row" id="show_all_courses_after_discount">

    </div>';
}
?>