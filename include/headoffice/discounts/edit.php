<?php
$condition = array ( 
                    'select'        =>  'd.discount_id, d.id_type, d.discount_status, d.discount_name, d.discount_from, d.discount_to, d.is_all, d.discount_description'
                    ,'join'         =>  'INNER JOIN '.DISCOUNT_DETAIL.' AS dd ON d.discount_id = dd.id_setup'
                    ,'where' 	    =>  array( 
                                                 'd.is_deleted'          => 0
                                                ,'d.discount_id'         => cleanvars(LMS_EDIT_ID)
                                            )
                    ,'return_type'  =>  'single'
                   ); 
$DISCOUNT = $dblms->getRows(DISCOUNT.' AS d', $condition);
$selectedStartDate  = date('Y-m-d',strtotime($DISCOUNT['discount_from']));
$selectedEndDate    = date('Y-m-d',strtotime($DISCOUNT['discount_to']));
echo'
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header bg-info">
                <h5 class="mb-0 modal-title"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(0).'</h5>
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
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label class="form-label">Name <span class="text-danger">*</span></label>
                                            <input type="text" name="discount_name" class="form-control" value="'.$DISCOUNT['discount_name'].'" required="">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" name="discount_status" data-choices required="">
                                                <option value="">Choose one</option>';
                                                foreach (get_status() as $key => $status):
                                                    echo'<option value="'.$key.'" '.($key == $DISCOUNT['discount_status']?'selected':'').'>'.$status.'</option>';
                                                endforeach;
                                                echo'
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label class="form-label">Type <span class="text-danger">*</span></label>
                                            <select class="form-control" name="id_type" id="id_type" data-choices disabled required="">
                                                <option value="">Choose one</option>';
                                                foreach (get_offering_type() as $key => $status):
                                                    if(in_array($key, [3,4])){
                                                        echo'<option value="'.$key.'" '.($key == $DISCOUNT['id_type'] ? 'selected' : '').'>'.$status.'</option>';
                                                    }
                                                endforeach;
                                                echo'
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Date <span class="text-danger">*</span></label>
                                            <input type="text" name="discount_date" id="discount_date" data-provider="flatpickr" data-minDate="'.date("Y-m-d").'" value="'.date('Y-m-d', strtotime(cleanvars($DISCOUNT['discount_from']))).' to '.date('Y-m-d', strtotime(cleanvars($DISCOUNT['discount_to']))).'" data-date-format="Y-m-d" data-range-date="true" class="form-control" required/>
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
                                        <input class="form-check-input" '.(!$DISCOUNT?'':'hidden').' type="checkbox" role="switch" name="on_all_courses" id="on_all_courses" value="1" '.($DISCOUNT['is_all'] == 1?'checked':'').' >
                                        <label class="form-check-label" '.(!$DISCOUNT?'':'hidden').' for="on_all_courses">On All Courses </label>
                                    </div>
                                </div>';
                                $condition = array ( 
                                                     'select'       =>  'dd.discount_detail_id, dd.id_curs, d.discount_name, d.discount_from, d.discount_to, dd.discount_type, dd.discount, c.curs_id, c.curs_name, c.curs_icon, c.curs_photo, ao.admoff_amount'
                                                    ,'join' 	    =>  'INNER JOIN '.COURSES.' AS c ON c.curs_id = dd.id_curs
                                                                         INNER JOIN '.ADMISSION_OFFERING.' AS ao ON ( ao.admoff_type IN (3,4) AND ao.admoff_degree = dd.id_curs )
                                                                         INNER JOIN '.DISCOUNT.' AS d ON d.discount_id = dd.id_setup'
                                                    ,'where' 	    =>  array( 
                                                                                'dd.id_setup'         => cleanvars(LMS_EDIT_ID)
                                                                            )
                                                    ,'order_by'     =>  ' dd.discount_detail_id ASC '
                                                    ,'return_type'  =>  'all'
                                                ); 
                                $DISCOUNT_DETAIL = $dblms->getRows(DISCOUNT_DETAIL.' AS dd', $condition);
                                if ($DISCOUNT['is_all'] == 1) {
                                    echo'
                                    <div class="card-body border specfic_discount" style="display: '.($DISCOUNT['is_all'] == 1?'show':'none').';">
                                        <div class="row">
                                            <div class="col">
                                                <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                                                <select class="form-control" name="discount_type" data-choices onchange="discount_on_all_courses(this.value);">
                                                    <option value="">Select</option>';
                                                    foreach (get_DiscountType() as $key => $value):
                                                        echo'<option value="'.$key.'" '.($key == $DISCOUNT_DETAIL[0]['discount_type']?'selected':'').'>'.$value.'</option>';
                                                    endforeach;
                                                    echo'
                                                </select>
                                            </div>
                                            <div class="col discount_on_all_courses_show" style="display: '.(!empty($DISCOUNT_DETAIL[0]['discount_type'])?'show':'none').';">';
                                                if ($DISCOUNT_DETAIL[0]['discount_type'] == 1) {
                                                    echo'
                                                    <div class="col">
                                                        <label class="form-label">Fixed <span class="text-danger">*</span></label>
                                                        <input type="text" name="discount" id="discount" class="form-control" value="'.$DISCOUNT_DETAIL[0]['discount'].'" onkeyup="discount_fixed_on_courses_apply(this.value);" placeholder="Fixed: 0.00" />
                                                    </div>';
                                                }
                                                if ($DISCOUNT_DETAIL[0]['discount_type'] == 2) {
                                                    echo'
                                                    <div class="col">
                                                        <label class="form-label">Percentage <span class="text-danger">*</span></label>
                                                        <input type="text" name="discount" id="discount" class="form-control" value="'.$DISCOUNT_DETAIL[0]['discount'].'" onkeyup="discount_percentage_on_courses_apply(this.value);" placeholder="Percentage: 1-100%" />
                                                    </div>';
                                                }
                                                echo'
                                            </div>
                                        </div>
                                        <div class="row" id="show_all_courses_after_discount">';
                                            if ($DISCOUNT_DETAIL) {
                                                echo'
                                                <div class="col mb-2 mt-2">
                                                    <label class="form-label">'.get_offering_type($DISCOUNT['id_type']).' <span class="text-danger">*</span></label>
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
                                                                <input type="hidden" name="id_all_curs['.$cursKey.']" value="'.$row['curs_id'].'" />
                                                                <tr style="vertical-align: middle;">
                                                                    <td class="text-center">
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
                                                                                    <h5 class="fs-14 mb-1">
                                                                                        <a onclick="showAjaxModalZoom(\'include/modals/'.moduleName().'/view_summary.php?view_id='.$row['curs_id'].'\');" href="javascript:;">'.$row['curs_name'].'</a>
                                                                                    </h5>
                                                                                </div>
                                                                            </div>
                                                                        </span>
                                                                    </td>
                                                                    <td class="text-end cursCheck_'.$cursKey.'_Show">
                                                                        '.number_format($row['admoff_amount']).'.00
                                                                    </td>
                                                                    <td class="text-end cursCheck_'.$cursKey.'_Show">';
                                                                        if ($row['discount_type'] == 1) {
                                                                            echo'
                                                                            '.number_format(($row['admoff_amount'] - cleanvars($row['discount']))).'.00';
                                                                        } 
                                                                        if ($row['discount_type'] == 2) {
                                                                            echo'
                                                                            '.number_format(($row['admoff_amount'] - ($row['admoff_amount'] * (cleanvars($row['discount']) / 100) ))).'.00';
                                                                        }
                                                                        echo'
                                                                    </td>
                                                                </tr>';
                                                            }
                                                            echo'
                                                        </tbody>
                                                    </table>
                                                </div>';
                                            }
                                            echo'
                                        </div>
                                    </div>';
                                } else {
                                    echo'
                                    <div class="card-body border custom_discount" style="display: '.($DISCOUNT['is_all'] == 1?'none':'show').';">
                                        <div class="row">';
                                            if ($DISCOUNT_DETAIL) {
                                                echo'
                                                <div class="col mb-2 mt-2">
                                                    <label class="form-label">'.get_offering_type($DISCOUNT['id_type']).' <span class="text-danger">*</span></label>
                                                    <table class="table mb-0">
                                                        <thead class="table-light">
                                                            <tr>                        
                                                                <th width="40" class="text-center">
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input cursCheckAll" type="checkbox" role="switch" name="which_curs['.$cursKey.']" id="cursCheck__all" value="1" checked>
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
                                                            foreach ($DISCOUNT_DETAIL as $cursKey => $row) {
                                                                $curs_icon = ((!empty($row['curs_icon']) && file_exists('uploads/images/courses/icons/'.$row['curs_icon'])) ? 'uploads/images/courses/icons/'.$row['curs_icon'].'' : ''.SITE_URL.'uploads/default.png');
                                                                echo '
                                                                <tr style="vertical-align: middle;">
                                                                    <td class="text-center">
                                                                        <input type="hidden" name="id_custom_curs['.$cursKey.']" value="'.$row['curs_id'].'" />
                                                                        <div class="form-check form-switch">
                                                                            <input class="form-check-input cursCheckAll cursCheck__all_Show" type="checkbox" role="switch" name="which_curs['.$cursKey.']" id="cursCheck_'.$cursKey.'" value="1" checked>
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
                                                                    <td class="cursCheck_'.$cursKey.'_Show">
                                                                        <select class="form-control" name="discount_type['.$cursKey.']" data-choices onchange="get_DiscountType_'.$cursKey.'(this.value);">
                                                                            <option value="">Select</option>';
                                                                            foreach (get_DiscountType() as $key => $value):
                                                                                echo'<option value="'.$key.'" '.($key == $row['discount_type']?'selected':'').'>'.$value.'</option>';
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
                                                                    <td class="cursCheck_'.$cursKey.'_Show" id="get_DiscountType_'.$cursKey.'_show">';
                                                                        if ($row['discount_type'] == 1) {
                                                                            echo'
                                                                            <input type="text" name="discount['.$cursKey.']" onkeyup="discount_fixed'.$cursKey.'(this.value)" value="'.$row['discount'].'" class="form-control" placeholder="Fixed: 0.00">';
                                                                        } 
                                                                        if ($row['discount_type'] == 2) {
                                                                            echo'
                                                                            <input type="text" name="discount['.$cursKey.']" onkeyup="percentage_fixed'.$cursKey.'(this.value)" value="'.$row['discount'].'" class="form-control" placeholder="Percentage: 1-100%">';
                                                                        }
                                                                        echo'
                                                                    </td>
                                                                    <td class="text-end" id="after_discount'.$cursKey.'_show">';
                                                                        if ($row['discount_type'] == 1) {
                                                                            echo'
                                                                            '.number_format(($row['admoff_amount'] - cleanvars($row['discount']))).'.00';
                                                                        } 
                                                                        if ($row['discount_type'] == 2) {
                                                                            echo'
                                                                            '.number_format(($row['admoff_amount'] - ($row['admoff_amount'] * (cleanvars($row['discount']) / 100) ))).'.00';
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
                                            echo'
                                        </div>
                                    </div>';
                                }
                                echo'
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
                                    <textarea class="form-control" name="discount_description">'.html_entity_decode(html_entity_decode($DISCOUNT['discount_description'])).'</textarea>
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
<script>
    // SHOW DISCOUNT ON DATE CHANGE BEHALF OF TYPE
    $("#discount_date").on('input', function () {
        var discountDate = $("#discount_date").val();
        var idType = $("#id_type").val();

        // Check if id_type is selected
        if (!idType) {
            alert("Type must be selected");
            $("#discount_date").val(""); // Clear the date field
            return; // Stop further execution if id_type is not selected
        }

        const dateArray = discountDate.split(" ");
        if (dateArray[1] === 'to') {
            $("#discount").val("");
            $("#show_all_courses_after_discount").html("");
            $(".curs_card").show();
            $.ajax({
                type: "post",
                url: "include/ajax/get_CursDiscount.php",
                data: {
                    "discount_date"     : discountDate,
                    "id_type"           : idType, // 3 = course, 4 = trainings
                    "_discount_method"  : "date"
                    ,"_edit"            : "<?= cleanvars(LMS_EDIT_ID); ?>"
                },
                success: function (response) {
                    $(".custom_discount").html(response);
                }
            });
        }
    });

    // ON CHANGE DISCOUNT TYPE - SHOW FIXED OR PERCENT FIELD
    function discount_on_all_courses(type) {
        if (type == 1) {
            $(".discount_on_all_courses_show").show();
            $(".discount_on_all_courses_show").html(`
                <label class="form-label">Fixed <span class="text-danger">*</span></label>
                <input type="text" name="discount" id="discount" class="form-control" onkeyup="discount_fixed_on_courses_apply(this.value);" placeholder="Fixed: 0.00" />
            `);
        } else if (type == 2) {
            $(".discount_on_all_courses_show").show();
            $(".discount_on_all_courses_show").html(`
                <label class="form-label">Percentage <span class="text-danger">*</span></label>
                <input type="text" name="discount" id="discount" class="form-control" onkeyup="discount_percentage_on_courses_apply(this.value);" placeholder="Percentage: 1-100%" />
            `);
        } else {
            $(".discount_on_all_courses_show").hide();
        }
    }

    // ON CLICK APPLY ON ALL - SHOW DISCOUNT TYPE
    $("#on_all_courses").change(function() {
        if ($(this).is(":checked")) {
            $(".custom_discount").hide();
            $(".specfic_discount").show();
            $(".custom_discount").find('select').each(function(index) {
                $(this).prop('disabled', false);
            });
            $(".custom_discount").find('input').each(function(index) {
                $(this).prop('disabled', false);
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

    // APPLY ON ALL FIXED
    function discount_fixed_on_courses_apply (discount_fixed_value){
        if (discount_fixed_value != '') {
            var discountDate    = $("#discount_date").val();
            var idType = $("#id_type").val();

            // Check if id_type is selected
            if (!idType) {
                alert("Type must be selected");
                $("#discount_date").val(""); // Clear the date field
                return; // Stop further execution if id_type is not selected
            }

            $.ajax({
                type   : "post"
                ,url    : "include/ajax/get_CursDiscount.php"
                ,data   : {
                    "_discount_fixed_value"     : discount_fixed_value
                    ,"discount_date"            : discountDate
                    ,"id_type"                  : idType
                    ,"_discount_method"         : "fixed"
                    ,"_edit"                    : "<?= cleanvars(LMS_EDIT_ID); ?>"
                }
                ,success: function (response) {
                    $("#show_all_courses_after_discount").html(response);
                }
            });
        }
    }

    // APPLY ON ALL PERCENT
    function discount_percentage_on_courses_apply (discount_percentage_value){
        if (discount_percentage_value != '') {
            var discountDate    = $("#discount_date").val();
            var idType = $("#id_type").val();

            // Check if id_type is selected
            if (!idType) {
                alert("Type must be selected");
                $("#discount_date").val(""); // Clear the date field
                return; // Stop further execution if id_type is not selected
            }

            $.ajax({
                type   : "post"
                ,url    : "include/ajax/get_CursDiscount.php"
                ,data   : {
                    "_discount_percentage_value"    : discount_percentage_value
                    ,"discount_date"                : discountDate
                    ,"id_type"                      : idType
                    ,"_discount_method"             : "percentage"
                    ,"_edit"                        : "<?= cleanvars(LMS_EDIT_ID); ?>"
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