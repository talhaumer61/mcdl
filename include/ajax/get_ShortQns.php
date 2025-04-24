<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";
    $con    = array(
                         'select'   =>  ' DISTINCT qb.qns_id, qb.qns_question, qb.qns_marks, qb.qns_type, qb.qns_level'
                        ,'join'     =>  'INNER JOIN '.COURSES_LESSONS.' AS cl ON (FIND_IN_SET(cl.lesson_id,qb.id_lesson) AND cl.id_curs = '.cleanvars($_GET['id']).' AND cl.id_week = '.cleanvars($_GET['id_week']).')'
                        ,'where'    =>  array(
                                                     'qb.qns_status' =>  1
                                                    ,'qb.is_deleted' =>  0
                                                    ,'qb.qns_level'  =>  cleanvars($_GET['difficulty_level_s'])
                                                    ,'qb.qns_type'   =>  cleanvars($_GET['qns_type_s'])
                                                    ,'qb.id_curs'    =>  cleanvars($_GET['id'])
                                            )
                        ,'limit'        =>  ''.cleanvars($_GET['no_of_question_s']).''
                        ,'group_by'     =>  'qb.qns_id, cl.lesson_id'
                        ,'order_by'     =>  'RAND()'
                        ,'return_type'  =>  'all'
    );
    $QUESTION_BANK  = $dblms->getRows(QUESTION_BANK.' AS qb', $con);
    if ($QUESTION_BANK) {
        echo'
        <script src="assets/js/app.js"></script>
        <div class="mt-2">
            <table class="table table-bordered align-middle">
                <tr>
                    <th>Short Questions</th>
                    <th width="10" class="text-center">Marks</th>
                </tr>';
                $totolMarks = 0;
                foreach($QUESTION_BANK AS $key => $val) {            
                    echo'
                    <tr>
                        <td>
                            <input type="hidden" value="'.$val['qns_question'].'" name="qns_question[]">
                            <input type="hidden" value="'.$_GET['qns_type_s'].'" name="qns_type[]">
                            <input type="hidden" value="'.$val['qns_level'].'" name="qns_level[]">
                            Q'.($key+1).': '.strip_tags(html_entity_decode(html_entity_decode($val['qns_question']))).'
                        </td>
                        <td class="text-center">
                            '.$val['qns_marks'].'
                            <input type="hidden" value="'.$val['qns_marks'].'" name="qns_marks[]">
                        </td>
                    </tr>';
                    $totolMarks += $val['qns_marks'];
                }                
                echo'
                <tr>
                    <th class="text-end" colspan="1">Total Marks</th>
                    <th width="100" class="text-center" colspan="4">'.$totolMarks.'</th>
                    <input type="hidden" value="'.$totolMarks.'" name="quiz_totalmarks_s">
                </tr>
            </table>
        </div>';
    }
?>