<?php 
$rootDir = 'admissions/';
include_once (''.$rootDir.''.moduleName().'/query.php');
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
                            <li class="breadcrumb-item active">'.moduleName(false).'</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-lg-12">';
                if (LMS_VIEW == 'add') {
                    include_once (''.$rootDir.''.moduleName().'/add.php');
                } else if (!empty(LMS_EDIT_ID)) {
                    include_once (''.$rootDir.''.moduleName().'/edit.php');
                } else {
                    include_once (''.$rootDir.''.moduleName().'/list.php');
                }
                echo'
            </div>
        </div>
    </div>
</div>
<script>
function remove_list(item){
    $(item).parent().remove()';
    foreach (get_degree_course_type() as $key => $value) {
        echo '
        record = $("#'.to_seo_url($value).'");
        if(record.children().length === 0){
            record.html("<li class=\'list-group-item x-auto text-danger\'><b>No Record Found</b></li>");
        }';
    }
echo '
}
function add_courses(){
    let cat = $("[name=\'id_cat\']");';
    foreach (get_degree_course_type() as $key => $value) {
        echo '
        $("[data-course-type='.$key.'] option:selected").each(function(){
            let course = $(this).val();
            let skip = false;
            $("input[name^=\'id_curs\'][name$=\'[]\']").each(function() {
                if ($(this).val() === course) {
                  skip = true; 
                }
              });
            if(!skip){
                str =   `<li class="list-group-item d-flex justify-content-between align-items-center">`
                            + $(this).text() + ` ( `+ cat.text() +` )` + 
                            `<input type="hidden" name="id_curs['.$key.'][`+cat.val()+`][]" value="`+$(this).val()+`">
                            <span onclick="remove_list(this)"><a class="btn btn-danger btn-xs"><i class="mdi mdi-delete-outline"></i></a></span>
                        </li>`
                $("#'.to_seo_url($value).'").append(str)
            }
        });';
    }
    echo '
}
</script>
';
?>
<script>
    document.querySelectorAll('[id^="ckeditor"]').forEach(function(element) {
        CKEDITOR.replace(element);
    });
    function get_courses(id_cat) {
        if (id_cat !== '') {
            $.ajax({
                type: "POST",
                url: "include/ajax/get_courses.php",
                data: {id_cat},
                success: function(result) {
                    $(".courses_section").html('');
                    $(".courses_section").html(result);
                }
            });
        } else {
            $(".courses_section").html('');
        }
    }
</script>