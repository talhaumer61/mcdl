<?php
$condition = array ( 
                     'select'       => "DISTINCT cc.cat_id, cc.cat_name"
                    ,'join'         => 'INNER JOIN '.COURSES_CATEGORIES.' AS cc ON cc.cat_id = ao.id_cat'
                    ,'where' 	    =>  array( 
                                                 'cc.cat_status'    => 1
                                                ,'cc.is_deleted'    => 0
                                                ,'ao.admoff_status' => 1
                                                ,'ao.is_deleted'    => 0
                                                ,'ao.admoff_type'   => 3
                                            )
                    ,'order_by'     =>  'cc.cat_id ASC'
                    ,'group_by'     =>  ' cc.cat_id '
                    ,'return_type'  =>  'all' 
                   ); 
$COURSES_CATEGORIES = $dblms->getRows(ADMISSION_OFFERING.' AS ao ', $condition);
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
                                    Basic Information
                                </div>
                                <div class="card-body border">
                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Name <span class="text-danger">*</span></label>
                                            <input type="text" name="discount_name" class="form-control" required="">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" name="discount_status" data-choices required="">
                                                <option value="">Choose one</option>';
                                                foreach (get_status() as $key => $status):
                                                    echo'<option value="'.$key.'">'.$status.'</option>';
                                                endforeach;
                                                echo'
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Date <span class="text-danger">*</span></label>
                                            <input type="text" name="discount_date" id="discount_date" data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true" class="form-control" required/>
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
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="on_all_courses" id="on_all_courses" value="1">
                                        <label class="form-check-label" for="on_all_courses">On All Courses</label>
                                    </div>
                                </div>
                                <div class="card-body border specfic_discount" style="display: none;">
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

                                    </div>
                                </div>
                                <div class="card-body border custom_discount">
                                    <div class="row">';
                                        // COURSES
                                        $condition = array ( 
                                                            'select'       => 'ao.admoff_amount, cc.curs_id, cc.curs_name, cc.curs_icon, cc.curs_photo, cc.curs_code'
                                                            ,'join'         =>'INNER JOIN '.COURSES.' AS cc ON cc.curs_id = ao.admoff_degree'
                                                            ,'where' 	    =>  array( 
                                                                                        'cc.curs_status'       => 1
                                                                                        ,'cc.is_deleted'        => 0
                                                                                        ,'ao.admoff_status'     => 1
                                                                                        ,'ao.is_deleted'        => 0
                                                                                        ,'ao.admoff_type'       => 3
                                                                                    )
                                                            ,'groupt_by'    =>  ' admoff_id '
                                                            ,'return_type'  =>  'all'
                                                        );
                                        $COURSES = $dblms->getRows(ADMISSION_OFFERING.' AS ao', $condition, $sql);
                                        if ($COURSES) {
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
                                                        foreach ($COURSES as $cursKey => $row) {
                                                            $condition = array ( 
                                                                                 'select'       => 'dd.discount_detail_id'
                                                                                ,'join'         => 'INNER JOIN '.DISCOUNT.' AS d on d.discount_id = dd.id_setup and d.is_deleted = 0'
                                                                                ,'where' 	    =>  array( 
                                                                                                            'dd.id_curs'   => $row['curs_id']
                                                                                                        )
                                                                                ,'group_by'     =>  ' dd.discount_detail_id '
                                                                                ,'return_type'  =>  'single'
                                                                            );
                                                            $DISCOUNT_DETAIL = $dblms->getRows(DISCOUNT_DETAIL.' AS dd', $condition, $sql);
                                                            if (!$DISCOUNT_DETAIL) {
                                                                $curs_icon = ((!empty($row['curs_icon']) && file_exists('uploads/images/courses/icons/'.$row['curs_icon'])) ? 'uploads/images/courses/icons/'.$row['curs_icon'].'' : ''.SITE_URL.'uploads/default.png');
                                                                echo '
                                                                <tr style="vertical-align: middle;">
                                                                    <td class="text-center">
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
                                                                        </script>
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
                                                                                    <h5 class="fs-14 mb-1">
                                                                                        <a onclick="showAjaxModalZoom(\'include/modals/'.moduleName().'/view_summary.php?view_id='.$row['curs_id'].'\');" href="javascript:;">'.$row['curs_name'].'</a>
                                                                                    </h5>
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
                                                        }
                                                        echo'
                                                    </tbody>
                                                </table>
                                            </div>';
                                        } else {
                                            echo'
                                            <span class="text-danger">No Course Found</span>';
                                        }
                                        echo'
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
                                    <textarea class="form-control" name="discount_description"></textarea>
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
    function discount_on_all_courses(type) {
        if (type == 1) {
            $(".discount_on_all_courses_show").show();
            $(".discount_on_all_courses_show").html(`
                <label class="form-label">Fixed <span class="text-danger">*</span></label>
                <input type="text" name="discount" class="form-control" onkeyup="discount_fixed_on_courses_apply(this.value);" placeholder="Fixed: 0.00" />
            `);
        } else if (type == 2) {
            $(".discount_on_all_courses_show").show();
            $(".discount_on_all_courses_show").html(`
                <label class="form-label">Percentage <span class="text-danger">*</span></label>
                <input type="text" name="discount" class="form-control" onkeyup="discount_percentage_on_courses_apply(this.value);" placeholder="Percentage: 1-100%" />
            `);
        } else {
            $(".discount_on_all_courses_show").hide();
        }
    }
    $("#on_all_courses").change(function() {
        if ($(this).is(":checked")) {
            $(".custom_discount").hide();
            $(".specfic_discount").show();
            $(".custom_discount").find('select').each(function(index) {
                $(this).prop('disabled', true);
            });
            $(".custom_discount").find('input').each(function(index) {
                $(this).prop('disabled', true);
            });
        } else {
            $(".specfic_discount").hide();
            $(".custom_discount").show();
            $(".specfic_discount").find('select').each(function(index) {
                $(this).prop('disabled', true);
            });
            $(".specfic_discount").find('input').each(function(index) {
                $(this).prop('disabled', true);
            });
        }
    });
    function discount_fixed_on_courses_apply (discount_fixed_value){
        if (discount_fixed_value != '') {
            $.ajax({
                 type   : "post"
                ,url    : "include/ajax/get_CursDiscount.php"
                ,data   : {
                    "_discount_fixed_value"    : discount_fixed_value
                    ,"_discount_method"         : "fixed"
                }
                ,success: function (response) {
                    $("#show_all_courses_after_discount").html(response);
                }
            });
        }
    }
    function discount_percentage_on_courses_apply (discount_percentage_value){
        if (discount_percentage_value != '') {
            $.ajax({
                 type   : "post"
                ,url    : "include/ajax/get_CursDiscount.php"
                ,data   : {
                    "_discount_percentage_value"   : discount_percentage_value
                    ,"_discount_method"             : "percentage"
                }
                ,success: function (response) {
                    $("#show_all_courses_after_discount").html(response);
                }
            });
        } else {
            $("#show_all_courses_after_discount").html("");
        }
    }
</script>