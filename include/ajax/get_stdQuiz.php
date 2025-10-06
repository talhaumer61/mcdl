<?php
include '../dbsetting/lms_vars_config.php';
include '../dbsetting/classdbconection.php';
include '../functions/functions.php';
$dblms = new dblms();
$condition = array(
                     'select'       =>  'SUM(CASE WHEN qsd.is_true = 1 THEN qsd.qns_marks ELSE NULL END) AS totalMultipleChoice'
                    ,'join'         =>  'INNER JOIN '.QUIZ_STUDENT_DETAILS.' AS qsd ON qsd.id_qzstd = qs.qzstd_id'
                    ,'where'        =>  array(  
                                                 'qs.is_deleted'    => 0
                                                ,'qs.id_quiz'       => cleanvars($_POST['id_quiz'])
                                                ,'qs.id_std'        => cleanvars($_POST['id_std'])
                                        )
                    ,'return_type'  =>  'single'
);
$QUIZ_STUDENTS = $dblms->getRows(QUIZ_STUDENTS.' AS qs', $condition);
echo'
<div class="row mb-2">
    <div class="col">
        <label class="form-label">Marks In Multiple Choice</label>
        <input type="text" class="form-control" value="'.$QUIZ_STUDENTS['totalMultipleChoice'].'" name="totalMultipleChoice" readonly="">
    </div>
</div>
<div class="card mb-3">
    <div class="card-header alert-dark p-2">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>Submitted Short Question Answer</h5>
        </div>
    </div>
    <div class="card-body border">
        <div class="row">
            <div class="mt-2">
                <table class="table table-bordered align-middle">
                    <tr>
                        <th>Short Questions</th>
                        <th width="100" class="text-center">Marks</th>
                    </tr>';
                    $condition = array(
                                         'select'       =>  'qsd.dtl_id, qq.quiz_qns_id, qq.quiz_qns_question, qq.quiz_qns_marks, qsd.qns_answer, qsd.qns_marks'
                                        ,'join'         =>  'INNER JOIN '.QUIZ_STUDENT_DETAILS.' AS qsd ON qsd.id_qzstd = qs.qzstd_id
                                                                INNER JOIN '.QUIZ_QUESTIONS.' AS qq ON (qq.quiz_qns_id = qsd.id_quiz_qns AND qq.quiz_qns_type = 1 AND qq.id_quiz = '.cleanvars($_POST['id_quiz']).')'
                                        ,'where'        =>  array(  
                                                                     'qs.is_deleted'    => 0
                                                                    ,'qs.id_quiz'       => cleanvars($_POST['id_quiz'])
                                                                    ,'qs.id_std'        => cleanvars($_POST['id_std'])
                                                            )
                                        ,'return_type'  =>  'all'
                    );
                    $QUIZ_STUDENTS = $dblms->getRows(QUIZ_STUDENTS.' AS qs', $condition);
                    foreach($QUIZ_STUDENTS AS $key => $val) {            
                        echo'
                        <tr>
                            <td>
                                <b>Q'.($key+1).': '.html_entity_decode(html_entity_decode(html_entity_decode(html_entity_decode($val['quiz_qns_question'])))).'</b>
                                <p>'.html_entity_decode(html_entity_decode(html_entity_decode(html_entity_decode($val['qns_answer'])))).'</p>
                            </td>
                            <td class="text-center">
                                <label class="form-label">Max('.$val['quiz_qns_marks'].') Marks <span class="text-danger">*</span></label>
                                <input class="form-control text-center qns_marks_'.$key.'" type="number" value="'.(!empty($val['qns_marks'])?$val['qns_marks']:'').'" >
                            </td>
                        </tr>
                        <script>
                            $(".qns_marks_'.$key.'").on("keyup", function() {
                                if ($(this).val() > '.$val['quiz_qns_marks'].') {
                                    $(this).removeAttr("onchange");
                                    $(this).val(0);
                                } else {
                                    $(this).attr("onchange","get_autoSaveMarks(this.value, '.$val['dtl_id'].')");
                                }
                            });
                        </script>';
                    }               
                    echo'
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function get_autoSaveMarks(qns_marks, dtl_id){
        $.ajax({
             url        : "include/ajax/get_autoSaveMarks.php"
            ,method     : "POST"
            ,data       : {
                 "qns_marks"    : qns_marks
                ,"dtl_id"       : dtl_id
                ,"id_quiz"      : '.cleanvars($_POST['id_quiz']).'
                ,"id_std"       : '.cleanvars($_POST['id_std']).'
            }
            ,success    : function() {
                notification("Success","Marks Auto Saved","success");
            }
        });
    }
    function notification(title, text, color){
        Toastify({
            newWindow: !0,
            text: `${title}! ${text}`,
            gravity: "top",
            position: "right",
            className: `bg-${color}`,
            stopOnFocus: !0,
            offset: "50",
            duration: "2000",
            close: true,
            style: "style",
        }).showToast();
    }
</script>';
?>
