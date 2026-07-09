<?php
include '../dbsetting/lms_vars_config.php';
include '../dbsetting/classdbconection.php';
include '../functions/functions.php';
session_start();
$dblms = new dblms();

if ($_POST['_method'] === 'student_teacher_qna') {
    if($_POST['std_message'] != ''){        
        $values = array(
                             'status'           =>  1
                            ,'type'             =>  2
                            ,'read_status'      =>  2
                            ,'id_user'          =>  cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
                            ,'reply_to'	        =>  cleanvars($_POST['id_std'])
                            ,'id_curs'	        =>  cleanvars($_POST['id_curs'])
                            ,'message'          =>  cleanvars($_POST['std_message'])
                            ,'ip_user'          =>  cleanvars(LMS_IP)
                            ,'datetime_sent'    =>  date('Y-m-d G:i:s')
                            ,'id_added'         =>  cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
                            ,'date_added'       =>  date('Y-m-d G:i:s')
                        ); 
        $sqllms = $dblms->insert(QUESTION_ANSWERS, $values);
        if($sqllms){
		    $latestID = $dblms->lastestid();
            $values = array (
                                 'id_user'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
                                ,'id_record'	=>	cleanvars($latestID)
                                ,'filename'		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
                                ,'action'		=>	1
                                ,'dated'		=>	date('Y-m-d G:i:s')
                                ,'ip'			=>	cleanvars(LMS_IP)
                                ,'remarks'		=>	"Message Sent"
                                ,'id_campus'	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                            );
            $sqlRemarks = $dblms->insert(LOGS, $values);            
            echo'
            <li class="chat-list right">
                <div class="conversation-list">
                    <div class="user-chat-content">
                        <div class="ctext-wrap">
                            <div class="ctext-wrap-content">
                                <p class="mb-0 ctext-content">'.htmlspecialchars($_POST['std_message']).'</p>
                            </div>
                            <div class="dropdown align-self-start message-box-drop">
                                <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ri-more-2-fill"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item copy-message cursor-pointer" onclick="copyToClipboard(\''.$_POST['std_message'].'\');"><i class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>
                                    <a class="dropdown-item cursor-pointer" onclick="showAjaxModalZoom(\'include/modals/'.moduleName().'/'.LMS_VIEW.'/edit.php?edit_id='.$latestID.'&id_std='.$_POST['id_std'].'&'.$redirection.'\');"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>
                                    <a class="dropdown-item delete-item cursor-pointer" onclick="confirm_modal(\''.moduleName().'.php?deleteidMsj='.$latestID.'&id_std='.$_POST['id_std'].'&'.$redirection.'\');"><i class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Delete</a>
                                </div>
                            </div>
                        </div>
                        <div class="conversation-name"><small class="text-muted time">'.date('d M, Y h:i A').' - '.get_msg_status(1).'</small></div>
                    </div>
                </div>
            </li>';
        }
    }
}
?>
