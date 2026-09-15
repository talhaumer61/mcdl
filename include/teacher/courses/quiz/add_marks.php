<?php
$condition = array(
                     'select'       =>  's.std_id, s.std_name, q.quiz_title'
                    ,'join'         =>  'INNER JOIN '.STUDENTS.' AS s ON s.std_id = qs.id_std
                                            INNER JOIN '.QUIZ.' AS q ON q.quiz_id = qs.id_quiz'
                    ,'where'        =>  array(  
                                                 'qs.is_deleted'    => 0
                                                ,'qs.id_quiz'       => cleanvars(LMS_EDIT_ID)
                                        )
                    ,'return_type'  =>  'all'
);
$QUIZ_STUDENTS = $dblms->getRows(QUIZ_STUDENTS.' AS qs', $condition);
echo'
<form autocomplete="off" class="form-validate" id="get_QnsFrom" enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <input type="hidden" name="id" value="'.cleanvars($_GET['id']).'">
    <input type="hidden" name="view" value="'.cleanvars($_GET['view']).'">
    <div class="row mb-2">
        <div class="col">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" value="'.$QUIZ_STUDENTS[0]['quiz_title'].'" readonly="">
        </div>
        <div class="col">
            <label class="form-label">Students <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="id_std" onchange="get_stdQuiz(this.value);">
                <option value=""> Choose one</option>';
                foreach($QUIZ_STUDENTS as $key => $val):
                    echo'<option value="'.$val['std_id'].'">'.moduleName($val['std_name']).'</option>';
                endforeach;
                echo'
            </select>
        </div>
    </div>
    <div clas="mb-2" id="show_stdQuiz"></div>
    <div class="hstack gap-2 justify-content-end">
        <a href="'.moduleName().'.php?'.$redirection.'" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
    </div>
</form>
<script>
    function get_stdQuiz(id_std, quiz_title){
        if (id_std != "") {
            $.ajax({
                 url        : "include/ajax/get_stdQuiz.php"
                ,method     : "POST"
                ,data       : {
                     "id_std" : id_std
                    ,"id_quiz" : "'.LMS_EDIT_ID.'"
                }
                ,success    : function(data) {
                    $("#show_stdQuiz").html(data);        
                }
            });
        } else {
            $("#show_stdQuiz").html("");
        }
    }
</script>';
?>