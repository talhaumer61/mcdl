<?php
$condition = array(
                         'select'       =>  'qs.qzstd_obtain_marks, qs.qzstd_pass_fail, q.quiz_totalmarks, q.quiz_passingmarks, q.quiz_title, s.std_name'
                        ,'join'         =>  'INNER JOIN '.QUIZ_STUDENT_DETAILS.' AS qsd ON qsd.id_qzstd = qs.qzstd_id
                                                INNER JOIN '.QUIZ.' AS q ON qs.id_quiz = q.quiz_id
                                                    INNER JOIN '.STUDENTS.' AS s ON qs.id_std = s.std_id'
                        ,'where'        =>  array(  
                                                     'qs.is_deleted'    => 0
                                                    ,'qs.id_quiz'       => cleanvars(LMS_EDIT_ID)
                                            )
                        ,'group_by'     =>  'qs.qzstd_id'
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
    </div>
    <div clas="mb-2">
        <table class="table table-bordered align-middle">
            <tr class="table-light">
                <th>Students</th>
                <th width="150" class="text-center">Pass/Fail</th>
                <th width="150" class="text-center">Total Marks</th>
                <th width="150" class="text-center">Passing Marks</th>
                <th width="150" class="text-center">Obtain Marks</th>
            </tr>';
            $quiz_totalmarks    = 0;
            $quiz_passingmarks  = 0;
            $qzstd_obtain_marks = 0;
            foreach($QUIZ_STUDENTS AS $key => $val) {            
                echo'
                <tr>
                    <td>'.moduleName($val['std_name']).'</td>
                    <td class="text-center bg-'.($val['qzstd_pass_fail'] == 1 ? 'success' : 'danger').'">'.($val['qzstd_pass_fail'] == 1?'Pass':'Fail').'</td>
                    <td class="text-center">'.$val['quiz_totalmarks'].'</td>
                    <td class="text-center">'.$val['quiz_passingmarks'].'</td>
                    <td class="text-center">'.$val['qzstd_obtain_marks'].'</td>
                </tr>';
                $quiz_totalmarks    += $val['quiz_totalmarks'];
                $quiz_passingmarks  += $val['quiz_passingmarks'];
                $qzstd_obtain_marks += $val['qzstd_obtain_marks'];
            }          
            echo'
                <tr>
                    <th class="text-end" colspan="2">Total of Quiz</th>
                    <th class="text-center">'.$quiz_totalmarks.'</th>
                    <th class="text-center">'.$quiz_passingmarks.'</th>
                    <th class="text-center">'.$qzstd_obtain_marks.'</th>
                </tr>';     
            echo'
        </table>
    </div>
    <div class="hstack gap-2 justify-content-end">
        <a href="'.moduleName().'.php?'.$redirection.'" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
    </div>
</form>';
?>