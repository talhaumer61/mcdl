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
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admissions</a></li>
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
                } else if (!empty(LMS_EDIT_ID)) {
                    include_once ($rootDir.moduleName().'/edit.php');
                } else {
                    include_once ($rootDir.moduleName().'/list.php');
                }
                echo'
            </div>
        </div>
    </div>
</div>';
?>
<script>
document.querySelectorAll('[id^="ckeditor"]').forEach(function(element) {
    CKEDITOR.replace(element);
});
function get_courses(id_cat){
    $.ajax({
        url : "include/ajax/get_courses_cat.php",
        type : "POST",
        data : {id_cat},
        success : function(response){
            $("#cat").html(response);
        }
    })
}
function remove_list(item){
    $(item).parent().remove()
}
function add_courses(){
    cat = $("select[name='cat_id']")
    $("select[name='id_cur[]'] option:selected").each(function(){
        str =   `<li class='list-group-item d-flex justify-content-between align-items-center'>`
                    +$(this).text() + ` ( `+cat.text()+` )` +
                    `<input type='hidden' name='id_curs[]' value='`+$(this).val()+`'>
                    <input type='hidden' name='id_cat[]' value='`+cat.val()+`'>
                    <span onclick='remove_list(this)'><a class='btn btn-danger btn-xs'><i class="mdi mdi-delete-outline"></i></a></span>
                 </li>`
        $("#list_of_courses").append(str)
    });
}
</script>