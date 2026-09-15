<?php
$condition = array(
                     'select'       =>  '*'
                    ,'where'        =>  array(
                                                 'status'   =>  1
                                                ,'id'       =>  cleanvars(LMS_EDIT_ID)
                                            )
                    ,'return_type'  =>  'single'
                );
$TEACHER_INTEREST = $dblms->getRows(TEACHER_INTEREST, $condition);
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i><span class="text-primary">'.$TEACHER_INTEREST['fullname'].'</span> request detail</h5>
            <div class="flex-shrink-0">
                <a class="btn btn-primary btn-xs" href="'.moduleName().'.php"><i class="ri-reply-fill align-bottom me-1"></i>Back</a>
                <button onclick="print_report(\'printResult\')" class="mr-xs btn btn-danger btn-xs"><i class="ri-printer-line align-middle"></i> Print</button>
            </div>
        </div>
    </div>
    <div class="card-body">    
        <div class="table-responsive table-card" id="printResult">
            <div id="header" style="display:none;">
                <h5 class="text-center mb-3">'.moduleName(false).'</h5>
            </div>
            <table class="table border mb-3">
                <thead class="table-light">
                    <tr>
                        <th colspan="6" class="text-center">Section 1: Personal Information</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th width="150">Fullname:</th>
                        <td>'.$TEACHER_INTEREST['fullname'].'</td>
                        <th width="150">Department:</th>
                        <td>'.$TEACHER_INTEREST['department'].'</td>
                        <th width="150">Designation:</th>
                        <td>'.$TEACHER_INTEREST['designation'].'</td>
                    </tr>
                    <tr>
                        <th width="150">Organization/Institute:</th>
                        <td colspan="5">'.$TEACHER_INTEREST['organization'].'</td>
                    </tr>
                    <tr>
                        <th width="150">Teaching Exp:</th>
                        <td>'.$TEACHER_INTEREST['teaching_experience'].'</td>
                        <th width="150">Professional Exp:</th>
                        <td>'.$TEACHER_INTEREST['professional_experience'].'</td>
                        <th width="150">Email :</th>
                        <td>'.$TEACHER_INTEREST['email'].'</td>
                    </tr>
                    <tr>
                        <th width="150">Phone No:</th>
                        <td>'.$TEACHER_INTEREST['phone_no'].'</td>
                        <th width="150">LinkedIn Profile:</th>
                        <td>'.$TEACHER_INTEREST['linkedin_link'].'</td>
                        <th width="150">Youtube Link:</th>
                        <td>'.$TEACHER_INTEREST['youtube_link'].'</td>
                    </tr>
                </tbody>
            </table>';
            $queNo=0;
            foreach (get_teacher_interest_section() as $key => $value) {
                // ENGAGEMENT QUESTIONS
                $conditions = array ( 
                                         'select'       =>	'iq.id, iq.type, iq.id_section, iq.question, iq.options, d.*'
                                        ,'join'         =>	'LEFT JOIN '.TEACHER_INTEREST_DETAIL.' d ON d.id_question = iq.id AND d.id_setup = '.LMS_EDIT_ID.''
                                        ,'where'        =>	array( 
                                                                 'iq.status'       =>  1
                                                                ,'iq.is_deleted'   =>  0
                                                                ,'iq.id_section'   =>  cleanvars($key)
                                                            )
                                        ,'return_type'	=>	'all'
                                    );
                $TEACHER_INTEREST_QUESTIONS = $dblms->getRows(TEACHER_INTEREST_QUESTIONS.' iq', $conditions,$sql);
                if($TEACHER_INTEREST_QUESTIONS){
                    $sr=0;
                    echo'
                    <table class="table border mb-3">
                        <thead class="table-light">
                            <tr>
                                <th colspan="5" class="text-center">Section '.$TEACHER_INTEREST_QUESTIONS[0]['id_section'].': '.get_teacher_interest_section($TEACHER_INTEREST_QUESTIONS[0]['id_section']).'</th>
                            </tr>
                        </thead>
                        <tbody>';
                            foreach ($TEACHER_INTEREST_QUESTIONS as $keyQue => $valRow) {
                                $sr++;
                                $queNo++;
                                $keyOpt = '';
                                echo'                                
                                <tr>
                                    <th colspan="5">'.$sr.'. '.$valRow['question'].'</th>
                                </tr>';
                                if($valRow['type'] == 3){
                                    echo'
                                    <tr>';
                                        $options = json_decode(html_entity_decode($valRow['options']), true);
                                        foreach ($options as $keyOpt => $valOpt) {
                                            echo'
                                            <td width="20%">';
                                                if(!empty($valOpt)){
                                                    echo '<i class="me-1 align-bottom '.($keyOpt == $valRow['option_selected'] ? 'ri-checkbox-circle-fill text-success' : ' ri-checkbox-blank-circle-line text-danger').'"></i>';
                                                    echo $valOpt;
                                                }
                                                echo'
                                            </td>';
                                        }
                                        echo'
                                    </tr>';
                                    if($valRow['option_selected'] == '5' && $valOpt != ''){
                                        echo'
                                        <tr>
                                            <td colspan="5" class="text-success">'.$valRow['description_answer'].'</td>
                                        </tr>';
                                    }
                                } else {
                                    echo'
                                    <tr>
                                        <td colspan="5" class="text-success">'.$valRow['description_answer'].'</td>
                                    </tr>';
                                }
                            }
                            echo'
                        </tbody>
                    </table>';
                }
            }
            echo'
        </div>
    </div>
</div>';
?>