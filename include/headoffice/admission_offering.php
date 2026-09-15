<?php 
$rootDir = 'admissions/';
include_once ($rootDir.moduleName().'/query.php');
echo' 
<title>'.moduleName(false).' - '.TITLE_HEADER.'</title>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">'.moduleName(false).'</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Programs</a></li>
                            <li class="breadcrumb-item"><a href="'.moduleName().'.php" class="text-primary">'.moduleName(false).'</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-lg-12">';
                if (LMS_VIEW == 'add') {
                    include_once ($rootDir.moduleName().'/add.php');
                } else if (!empty($_GET['edit_id'])) {
                    include_once ($rootDir.moduleName().'/edit.php');
                } else {
                    include_once ($rootDir.moduleName().'/list.php');
                }
                echo'
            </div>
        </div>
    </div>
</div>
<script>
    let input = $("input[name=\'admoff_amount\']");
    function get_offering_degree(id_offering_type,id_cat=0,admoff_degree=0) {
        if(id_cat == 0){
            input.attr("readonly", false);
            input.val(0);
        }
        $.ajax({
            type: "POST",
            url: "include/ajax/get_offering_degree_detail.php",
            data: {id_offering_type,id_cat,admoff_degree},
            success: function(result) {
                $("#offering_detail").html("");
                $("#offering_detail").html(result);
            }
        });
    }
    function program_amount(item){
        let Amount = $("[name=\'amount-"+item+"\']").data("amount");
        input.val(Amount);
        input.attr("readonly", true);
    }
</script>';
?>