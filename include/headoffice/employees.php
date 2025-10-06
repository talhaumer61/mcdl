<?php 
include_once ('employees/query.php');
echo' 
<title>'.moduleName().' - '.TITLE_HEADER.'</title>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">'.moduleName().'</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item"><a href="'.moduleName().'.php" class="text-primary">'.moduleName(false).'</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-lg-12">';
                if (isset($_GET['add'])) {
                    include_once ('employees/add.php');
                } else if (!empty($_GET['id'])) {
                    include_once ('employees/edit.php');
                } else {
                    include_once ('employees/list.php');
                }
                echo'
            </div>
        </div>
    </div>
</div>';
?>
<script>
    function get_EmailValidation(emply_email, emply_id = '') {
        if (emply_email != '') {
            if (isValidEmail(emply_email)) {
                $.ajax({
                    type    : "POST",
                    url     : "include/ajax/get_EmployeeEmailVali.php",
                    data    :   {
                                    'emply_email' : emply_email
                                },
                    success: function(rps) {
                        console.log(rps);
                        if (rps == '1') {
                            $("#emply_email").removeClass("error-border");
                            $("#emailError").html("*");
                            $("#add_submit_btn").html('<button type="submit" class="btn btn-primary btn-sm" name="submit_'+((emply_id!=1)?'add':'edit')+'" id="submit_add"><i class="ri-'+((emply_id!=1)?'add':'edit')+'-circle-line align-bottom me-1"></i>'+((emply_id!=1)?'Add':'Edit')+' Employee</button>');
                        } else {
                            $("#emply_email").addClass("error-border");
                            $("#emailError").html("* Email Already Exists");
                            $("#add_submit_btn").html('');
                        }
                    }
                });
            } else {
                $("#emply_email").addClass("error-border");
                $("#emailError").html("* Invalid Email");
                $("#add_submit_btn").html('');
            }
        } else {
            $("#add_submit_btn").html('');
        } 
    }
    function isValidEmail(email) {
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        return emailPattern.test(email);
    }
</script>