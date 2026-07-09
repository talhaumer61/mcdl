<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";
    $con    = array(
                         'select'   =>  ' DISTINCT qb.qns_id, qb.qns_question, qb.qns_marks, qb.qns_type, qb.qns_level'
                        ,'join'     =>  'INNER JOIN '.COURSES_LESSONS.' AS cl ON (FIND_IN_SET(cl.lesson_id,qb.id_lesson) AND cl.id_curs = '.cleanvars($_GET['id']).' AND cl.id_week = '.cleanvars($_GET['id_week']).')
                                        LEFT JOIN '.QUESTION_BANK_DETAIL.' AS qbd ON qbd.id_qns = qb.qns_id'
                        ,'where'    =>  array(
                                                     'qb.qns_status' =>  1
                                                    ,'qb.is_deleted' =>  0
                                                    ,'qb.qns_level'  =>  cleanvars($_GET['difficulty_level_m'])
                                                    ,'qb.qns_type'   =>  cleanvars($_GET['qns_type_m'])
                                                    ,'qb.id_curs'    =>  cleanvars($_GET['id'])
                                            )
                        ,'limit'        =>  ''.cleanvars($_GET['no_of_question_m']).''
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
                    <th>Multiple Choice Questions</th>
                    <th width="100" class="text-center">Option 1</th>
                    <th width="100" class="text-center">Option 2</th>
                    <th width="100" class="text-center">Option 3</th>
                    <th width="100" class="text-center">Option 4</th>
                    <th width="10" class="text-center">Marks</th>
                </tr>';
                $totolMarks = 0;
                foreach($QUESTION_BANK AS $key => $val) {            
                    echo'
                    <tr>
                        <td>
                            <input type="hidden" value="'.$val['qns_question'].'" name="qns_question[]">
                            <input type="hidden" value="'.$_GET['qns_type_m'].'" name="qns_type[]">
                            <input type="hidden" value="'.$val['qns_level'].'" name="qns_level[]">
                            Q'.($key+1).': '.strip_tags(html_entity_decode(html_entity_decode($val['qns_question']))).'
                        </td>';
                        $condition = array(
                                             'select'       =>  'option_id, qns_option, option_true'
                                            ,'where'        =>  array(  
                                                                        'id_qns' => cleanvars($val['qns_id'])
                                                                    )
                                            ,'order_by'     =>  'option_id ASC'
                                            ,'return_type'  =>  'all'
                        );
                        $QUESTION_BANK_DETAIL = $dblms->getRows(QUESTION_BANK_DETAIL, $condition);
                        $qns_options = array();
                        foreach($QUESTION_BANK_DETAIL as $oKey => $oVal) {
                            $qns_options[$oKey] = array(
                                'option_key'	=> ($oKey == 0?'a':($oKey == 1?'b':($oKey == 2?'c':'d')))
                               ,'qns_option'	=> $oVal['qns_option']
                               ,'option_true'	=> $oVal['option_true']
                            );
                            echo'
                            <td class="text-'.($oVal['option_true'] == 1?'success':'danger').' text-center">
                                '.($oVal['option_true'] == 1?'<i class="mdi mdi-check-bold align-middle"></i>':'').' 
                                '.moduleName($oVal['qns_option']).'
                            </td>';
                        }
                        echo'
                        <td class="text-center">
                            '.$val['qns_marks'].'
                            <textarea name="qns_options[]" hidden>'.json_encode($qns_options,JSON_UNESCAPED_UNICODE).'</textarea>
                            <input type="hidden" value="'.$val['qns_marks'].'" name="qns_marks[]">
                        </td>
                    </tr>';
                    $totolMarks += $val['qns_marks'];
                }                
                echo'
                <tr>
                    <th class="text-end" colspan="5">Total Marks</th>
                    <th width="100" class="text-center" colspan="4">'.$totolMarks.'</th>
                    <input type="hidden" value="'.$totolMarks.'" name="quiz_totalmarks_m">
                </tr>
            </table>
        </div>';
    }
?>