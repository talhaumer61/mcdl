<?php
include '../dbsetting/lms_vars_config.php';
include '../dbsetting/classdbconection.php';
include '../functions/functions.php';
$dblms = new dblms();
$values     = array( 
                        'qns_marks' => $_POST['qns_marks'] 
);
$sqllms = $dblms->Update(QUIZ_STUDENT_DETAILS, $values, "WHERE dtl_id = '".$_POST['dtl_id']."'");
if ($sqllms) {
    $condition = array(
                         'select'       =>  'q.quiz_passingmarks, SUM(qsd.qns_marks) AS qns_obtain_marks'
                        ,'join'         =>  'INNER JOIN '.QUIZ_STUDENT_DETAILS.' AS qsd ON qsd.id_qzstd = qs.qzstd_id
                                                INNER JOIN '.QUIZ.' AS q ON q.quiz_id = qs.id_quiz'
                        ,'where'        =>  array(  
                                                     'qs.is_deleted'    => 0
                                                    ,'qs.id_quiz'       => cleanvars($_POST['id_quiz'])
                                                    ,'qs.id_std'        => cleanvars($_POST['id_std'])
                                            )
                        ,'return_type'  =>  'single'
    );
    $QUIZ_STUDENTS = $dblms->getRows(QUIZ_STUDENTS.' AS qs', $condition);
    if ($QUIZ_STUDENTS) {
        $values     = array( 
                                'qzstd_obtain_marks' => $QUIZ_STUDENTS['qns_obtain_marks'] 
        );
        $values['qzstd_pass_fail'] = ($QUIZ_STUDENTS['quiz_passingmarks'] <= $QUIZ_STUDENTS['qns_obtain_marks']?'1':'0');
        $sqllms = $dblms->Update(QUIZ_STUDENTS, $values, "WHERE id_quiz = '".cleanvars($_POST['id_quiz'])."' AND id_std = '".cleanvars($_POST['id_std'])."'");
    }
}
?>
