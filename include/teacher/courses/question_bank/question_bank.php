
<?php
include_once ('query.php');
$condition = array ( 
                         'select' 	    =>  'qns_id, qns_status, qns_question, qns_file, qns_level, qns_type, qns_marks'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           => 0
                                                    ,'id_session'           => cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    ,'id_campus'            => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                    ,'id_teacher'           => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                )
                        ,'order_by'     =>  'qns_id DESC'
                        ,'return_type'  =>  'all' 
                    ); 
$QUESTION_BANK    = $dblms->getRows(QUESTION_BANK, $condition);
echo'
<div class="card mb-5">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-information-line align-bottom me-1"></i>'.$courseMenu[cleanvars($_GET['view'])]['title'].'</h5>
            <div class="flex-shrink-0">
                <a href="courses.php?id='.cleanvars($_GET['id']).'&view='.cleanvars($_GET['view']).'&add" class="btn btn-primary btn-sm"><i class="ri-add-circle-line align-bottom me-1"></i>Question</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12" >
                <table class="table table-bordered table-nowrap align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" width="10">Sr.</th>
                            <th>Question</th>
                            <th class="text-center" width="50">Level</th>
                            <th class="text-center" width="50">Type</th>
                            <th class="text-center" width="50">Marks</th>
                            <th width="35">Status</th>
                            <th width="35">Action</th>
                        </tr>
                    </thead>';
                    if ($QUESTION_BANK) {
                        $sr=0;
                        foreach($QUESTION_BANK as $key => $val):
                            $sr++;
                            echo'
                            <tbody>
                                <tr>
                                    <td class="text-center">'.$sr.'</td>
                                    <td>'.html_entity_decode(html_entity_decode($val['qns_question'])).'</td>
                                    <td class="text-center">'.get_QnsLevel($val['qns_level']).'</td>
                                    <td class="text-center">'.get_QnsType($val['qns_type']).'</td>
                                    <td class="text-center">'.$val['qns_marks'].'</td>
                                    <td class="text-center">'.get_status($val['qns_status']).'</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">';
                                                if($val['qns_file']){
                                                    echo'<li><a href="uploads/files/question_bank/'.$val['qns_file'].'" class="dropdown-item" target="_blank"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View File</a></li>';
                                                }
                                                echo'
                                                <li><a class="dropdown-item" href="courses.php?id='.cleanvars($_GET['id']).'&view='.cleanvars($_GET['view']).'&qns_id='.$val['qns_id'].'"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                                <li><a class="dropdown-item" onclick="confirm_modal(\'courses.php?deleteid='.$val['qns_id'].'&id='.cleanvars($_GET['id']).'&view='.cleanvars($_GET['view']).'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>';
                        endforeach;
                    } else {
                        echo'
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center">*** No Record Found ***</td>
                            </tr>
                        </tbody>';
                    }
                    echo'
                </table>
            </div>
        </div>
    </div>
</div>';
?>