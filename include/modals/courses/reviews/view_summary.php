<?php 
require_once ("../../../dbsetting/lms_vars_config.php");
require_once ("../../../dbsetting/classdbconection.php");
require_once ("../../../functions/functions.php");
require_once ("../../../functions/login_func.php");
$dblms = new dblms();

// FEEDBACK
$condition = array(
                     'select'       =>  'f.std_name, f.cert_name, f.date_generated'
                    ,'where'        =>  array(
                                                'f.feedback_id'     =>	cleanvars($_GET['view_id'])
                                            )
                    ,'return_type'  =>  'single'
                );
$STUDENT_FEEDBACK = $dblms->getRows(STUDENT_FEEDBACK.' f', $condition, $sql);

// FEEDBACK DETAIL
$condition = array(
                     'select'       =>  'd.*, fq.question, fq.options, fq.type'
                    ,'join'         =>  'INNER JOIN '.FEEDBACK_QUESTIONS.' fq ON fq.id = d.id_question'
                    ,'where'        =>  array(
                                                'd.id_feedback'     =>	cleanvars($_GET['view_id'])
                                            )
                    ,'order_by'     =>  'd.id_question'
                    ,'return_type'  =>  'all'
                );
$STUDENT_FEEDBACK_DETAIL = $dblms->getRows(STUDENT_FEEDBACK_DETAIL.' d', $condition, $sql);
$sr = 0;
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-primary p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-eye-line align-bottom me-1"></i>'.moduleName(false).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <table class="table border">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="4" class="text-center">Section 1: General Information</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th width="100">Student:</th>
                                    <td>'.$STUDENT_FEEDBACK['std_name'].'</td>
                                    <th width="100">Date:</th>
                                    <td width="150">'.date('d M, Y', strtotime($STUDENT_FEEDBACK['date_generated'])).'</td>
                                </tr>
                                <tr>
                                    <th width="100">Course:</th>
                                    <td colspan="3">'.$STUDENT_FEEDBACK['cert_name'].'</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <table class="table border">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="5" class="text-center">Section 2: Course Feedback</th>
                                </tr>
                            </thead>
                            <tbody>';
                                foreach ($STUDENT_FEEDBACK_DETAIL as $keyRow => $valRow) {                                    
                                    if(in_array($valRow['id_question'], array(1,2,3,4))){                                        
                                        $sr++;
                                        echo'
                                        <tr>
                                            <th colspan="5">'.$sr.'. '.$valRow['question'].'</th>
                                        </tr>
                                        <tr>';
                                            if($valRow['type'] == 3){
                                                $options = json_decode(html_entity_decode($valRow['options']), true);
                                                foreach ($options as $keyOpt => $valOpt) {
                                                    echo'
                                                    <td width="20%">';
                                                        if(!empty($valOpt)){
                                                            echo '<i class="me-1 align-bottom '.($keyOpt == $valRow['answer'] ? 'ri-checkbox-circle-fill text-success' : ' ri-checkbox-blank-circle-line text-danger').'"></i>';
                                                            echo $valOpt;
                                                        echo'
                                                    </td>';
                                                    }
                                                }
                                            } else {
                                                echo'
                                                <td colspan="5">'.(!empty($valRow['answer']) ? $valRow['answer'] : '<i class="text-danger">Student didn\'t answer</i>').'</td>';
                                            }
                                            echo'
                                        </tr>';
                                    }
                                }
                                echo'
                            </tbody>
                        </table>
                        
                        <table class="table border">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="5" class="text-center">Section 3: Instructor Feedback</th>
                                </tr>
                            </thead>
                            <tbody>';
                                foreach ($STUDENT_FEEDBACK_DETAIL as $keyRow => $valRow) {                                    
                                    if(in_array($valRow['id_question'], array(5,6))){
                                        $sr++;
                                        echo'
                                        <tr>
                                            <th colspan="5">'.$sr.'. '.$valRow['question'].'</th>
                                        </tr>
                                        <tr>';
                                            if($valRow['type'] == 3){
                                                $options = json_decode(html_entity_decode($valRow['options']), true);
                                                foreach ($options as $keyOpt => $valOpt) {
                                                    echo'
                                                    <td width="20%">';
                                                        if(!empty($valOpt)){
                                                            echo '<i class="me-1 align-bottom '.($keyOpt == $valRow['answer'] ? 'ri-checkbox-circle-fill text-success' : ' ri-checkbox-blank-circle-line text-danger').'"></i>';
                                                            echo $valOpt;
                                                        echo'
                                                    </td>';
                                                    }
                                                }
                                            } else {
                                                echo'
                                                <td colspan="5">'.(!empty($valRow['answer']) ? $valRow['answer'] : '<i class="text-danger">Student didn\'t answer</i>').'</td>';
                                            }
                                            echo'
                                        </tr>';
                                    }
                                }
                                echo'
                            </tbody>
                        </table>
                        
                        <table class="table border">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="5" class="text-center">Section 4: Course Experience</th>
                                </tr>
                            </thead>
                            <tbody>';
                                foreach ($STUDENT_FEEDBACK_DETAIL as $keyRow => $valRow) {                                    
                                    if(in_array($valRow['id_question'], array(7,8))){
                                        $sr++;
                                        echo'
                                        <tr>
                                            <th colspan="5">'.$sr.'. '.$valRow['question'].'</th>
                                        </tr>
                                        <tr>';
                                            if($valRow['type'] == 3){
                                                $options = json_decode(html_entity_decode($valRow['options']), true);
                                                foreach ($options as $keyOpt => $valOpt) {
                                                    echo'
                                                    <td width="20%">';
                                                        if(!empty($valOpt)){
                                                            echo '<i class="me-1 align-bottom '.($keyOpt == $valRow['answer'] ? 'ri-checkbox-circle-fill text-success' : ' ri-checkbox-blank-circle-line text-danger').'"></i>';
                                                            echo $valOpt;
                                                        echo'
                                                    </td>';
                                                    }
                                                }
                                            } else {
                                                echo'
                                                <td colspan="5">'.(!empty($valRow['answer']) ? $valRow['answer'] : '<i class="text-danger">Student didn\'t answer</i>').'</td>';
                                            }
                                            echo'
                                        </tr>';
                                    }
                                }
                                echo'
                            </tbody>
                        </table>
                        
                        <table class="table border">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="5" class="text-center">Section 5: Assessment</th>
                                </tr>
                            </thead>
                            <tbody>';
                                foreach ($STUDENT_FEEDBACK_DETAIL as $keyRow => $valRow) {                                    
                                    if(in_array($valRow['id_question'], array(9))){
                                        $sr++;
                                        echo'
                                        <tr>
                                            <th colspan="5">'.$sr.'. '.$valRow['question'].'</th>
                                        </tr>
                                        <tr>';
                                            if($valRow['type'] == 3){
                                                $options = json_decode(html_entity_decode($valRow['options']), true);
                                                foreach ($options as $keyOpt => $valOpt) {
                                                    echo'
                                                    <td width="20%">';
                                                        if(!empty($valOpt)){
                                                            echo '<i class="me-1 align-bottom '.($keyOpt == $valRow['answer'] ? 'ri-checkbox-circle-fill text-success' : ' ri-checkbox-blank-circle-line text-danger').'"></i>';
                                                            echo $valOpt;
                                                        echo'
                                                    </td>';
                                                    }
                                                }
                                            } else {
                                                echo'
                                                <td colspan="5">'.(!empty($valRow['answer']) ? $valRow['answer'] : '<i class="text-danger">Student didn\'t answer</i>').'</td>';
                                            }
                                            echo'
                                        </tr>';
                                    }
                                }
                                echo'
                            </tbody>
                        </table>
                        
                        <table class="table border">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="5" class="text-center">Section 6: Overall Satisfaction</th>
                                </tr>
                            </thead>
                            <tbody>';
                                foreach ($STUDENT_FEEDBACK_DETAIL as $keyRow => $valRow) {                                    
                                    if(in_array($valRow['id_question'], array(10,11,12,13,14,15,16))){
                                        $sr++;
                                        echo'
                                        <tr>
                                            <th colspan="5">'.$sr.'. '.$valRow['question'].'</th>
                                        </tr>
                                        <tr>';
                                            if($valRow['type'] == 3){
                                                $options = json_decode(html_entity_decode($valRow['options']), true);
                                                foreach ($options as $keyOpt => $valOpt) {
                                                    echo'
                                                    <td width="20%">';
                                                        if(!empty($valOpt)){
                                                            echo '<i class="me-1 align-bottom '.($keyOpt == $valRow['answer'] ? 'ri-checkbox-circle-fill text-success' : ' ri-checkbox-blank-circle-line text-danger').'"></i>';
                                                            echo $valOpt;
                                                        echo'
                                                    </td>';
                                                    }
                                                }
                                            } else {
                                                echo'
                                                <td colspan="5">'.(!empty($valRow['answer']) ? $valRow['answer'] : '<i class="text-danger">Student didn\'t answer</i>').'</td>';
                                            }
                                            echo'
                                        </tr>';
                                    }
                                }
                                echo'
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>