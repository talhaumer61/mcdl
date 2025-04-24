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
                                    <div class="row mb-2">
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
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label class="form-label">Type <span class="text-danger">*</span></label>
                                            <select class="form-control" name="id_type" id="id_type" data-choices required="">
                                                <option value="">Choose one</option>';
                                                foreach (get_offering_type() as $key => $status):
                                                    if(in_array($key, [3,4])){
                                                        echo'<option value="'.$key.'">'.$status.'</option>';
                                                    }
                                                endforeach;
                                                echo'
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Date <span class="text-danger">*</span></label>
                                            <input type="text" name="discount_date" id="discount_date" data-provider="flatpickr" data-date-format="Y-m-d" data-minDate="'.date("Y-m-d").'" data-range-date="true" class="form-control" required/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <div class="card mb-3 curs_card" style="display: none;">
                                <div class="card-header alert-primary">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="on_all_courses" id="on_all_courses" value="1">
                                        <label class="form-check-label" for="on_all_courses">Apply On All</label>
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
                                    <div class="row">
                                    
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
                    "discount_date": discountDate,
                    "id_type": idType, // 3 = course, 4 = trainings
                    "_discount_method": "date"
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
        if ($("#on_all_courses").is(":checked")) {
            $(".custom_discount").hide();
            $(".specfic_discount").show();
            $.ajax({
                 type   : "post"
                ,url    : "include/ajax/get_CursDiscount.php"
                ,data   : {
                    "_discount_method" : "discount_type"
                }
                ,success: function (response) {
                    $(".specfic_discount").html(response);
                }
            });
            $(".specfic_discount").html();
            $(".custom_discount").html("");
        } else {
            $(".specfic_discount").hide();
            $(".custom_discount").show();
            $(".specfic_discount").html("");
            var discountDate    = $("#discount_date").val();
            const dateArray     = discountDate.split(" ");
            if (dateArray[3] == 'to') {
                $("#discount").val("");
                $("#show_all_courses_after_discount").html("");
                $(".curs_card").show();
                $.ajax({
                    type   : "post"
                    ,url    : "include/ajax/get_CursDiscount.php"
                    ,data   : {
                        "discount_date"    : discountDate
                        ,"_discount_method" : "date"
                    }
                    ,success: function (response) {
                        $(".custom_discount").html(response);
                    }
                });
            }
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
                     "_discount_fixed_value"    : discount_fixed_value
                    ,"discount_date"            : discountDate
                    ,"id_type"                  : idType
                    ,"_discount_method"         : "fixed"
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
                     "_discount_percentage_value"   : discount_percentage_value
                    ,"discount_date"                : discountDate
                    ,"id_type"                      : idType
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