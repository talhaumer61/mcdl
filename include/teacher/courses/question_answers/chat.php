<?php
$condition = array(
                     'select'       => 's.std_gender, a.adm_fullname, a.adm_username, a.adm_photo'
                    ,'join'         => 'INNER JOIN '.ADMINS.' a ON a.adm_id = s.std_loginid AND a.is_deleted = 0'
                    ,'where'        => array(
                                                 's.is_deleted'     =>  0
                                                ,'s.std_id'         =>  cleanvars($_GET['id_std'])
                                            )
                    ,'return_type'  => 'single'
                );
$rowStd = $dblms->getRows(STUDENTS.' s', $condition);

if($rowStd){
    if($rowStd['std_gender'] == '2'){
        $adm_photo = SITE_URL.'uploads/images/default_female.jpg';
    }else{            
        $adm_photo = SITE_URL.'uploads/images/default_male.jpg';
    }
    if(!empty($rowStd['adm_photo'])){
        $file_url = SITE_URL.'uploads/images/admin/'.$rowStd['adm_photo'];
        if (check_file_exists($file_url)) {
            $adm_photo = $file_url;
        }
    }

    // MARK READ ALL MSG
    $values = array(
                        'read_status'   =>	1
					);
	$sqllms = $dblms->Update(QUESTION_ANSWERS, $values, " WHERE id_curs = '".CURS_ID."' AND id_user = '".$_GET['id_std']."' AND type = '1' ");
    echo'
    <div class="user-chat w-100">
        <div class="chat-content d-lg-flex">
            <!-- start chat conversation section -->
            <div class="w-100 position-relative">
                <!-- conversation user -->
                <div class="position-relative">
                    <div class="p-3 user-chat-topbar">
                        <div class="row align-items-center">
                            <div class="col-sm-4 col-8">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 d-block d-lg-none me-3">
                                        <a href="javascript: void(0);" class="user-chat-remove fs-18 p-1"><i class="ri-arrow-left-s-line align-bottom"></i></a>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 chat-user-img online user-own-img align-self-center me-3 ms-0">
                                                <img src="'.$adm_photo.'" class="rounded-circle avatar-sm" alt="">
                                                <span class="user-status"></span>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="text-truncate mb-0 fs-16">'.$rowStd['adm_fullname'].'</h5>
                                                <p class="text-truncate text-muted fs-14 mb-0 userStatus"><small>@'.$rowStd['adm_username'].'</small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8 col-4">
                                <ul class="list-inline user-chat-nav text-end mb-0">
                                    <a class="btn btn-soft-primary btn-sm" href="?'.$redirection.'"><i class="ri-reply-line"></i></a>
                                    <li class="list-inline-item m-0">
                                        <div class="dropdown">
                                            <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                                <li><a class="dropdown-item" onclick="confirm_modal(\''.moduleName().'.php?deleteidCurs='.CURS_ID.'&id_std='.$_GET['id_std'].'&'.$redirection.'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete Thread</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="position-relative">
                        <div class="chat-conversation p-3 p-lg-4" data-simplebar="init">
                            <div class="simplebar-wrapper">
                                <div class="simplebar-mask">
                                    <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                        <div class="simplebar-content-wrapper  chat-messages" tabindex="0" role="region" aria-label="scrollable content">
                                            <div class="simplebar-content" style="padding: 24px;">
                                                <ul class="list-unstyled chat-conversation-list">';
                                                    $condition = array(
                                                                         'select'       => 'qa.id, qa.status, qa.type, qa.message, qa.datetime_sent'
                                                                        ,'where'        => array(
                                                                                                     'qa.id_curs'       =>  cleanvars(CURS_ID)
                                                                                                    ,'qa.is_deleted'    =>  0
                                                                                                )
                                                                        ,'search_by'    => ' AND (qa.id_user = '.$_GET['id_std'].' OR qa.reply_to = '.$_GET['id_std'].')'
                                                                        ,'order_by'     => 'qa.datetime_sent ASC'
                                                                        ,'return_type'  => 'all'
                                                                    );
                                                    $QUESTION_ANSWERS = $dblms->getRows(QUESTION_ANSWERS.' qa', $condition, $sql);
                                                    if($QUESTION_ANSWERS){
                                                        foreach ($QUESTION_ANSWERS as $keyQA => $valQA) {
                                                            echo'
                                                            <li class="chat-list '.($valQA['type'] == 1 ? 'left' : 'right').'">
                                                                <div class="conversation-list">
                                                                    <div class="user-chat-content">
                                                                        <div class="ctext-wrap">
                                                                            <div class="ctext-wrap-content">
                                                                                <p class="mb-0 ctext-content">'.$valQA['message'].'</p>
                                                                            </div>
                                                                            <div class="dropdown align-self-start message-box-drop">
                                                                                <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    <i class="ri-more-2-fill"></i>
                                                                                </a>
                                                                                <div class="dropdown-menu">';
                                                                                    echo'<a class="dropdown-item copy-message cursor-pointer" onclick="copyToClipboard(\''.$valQA['message'].'\');"><i class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>';
                                                                                    if($valQA['type'] == 2){
                                                                                        echo'<a class="dropdown-item cursor-pointer" onclick="showAjaxModalZoom(\'include/modals/'.moduleName().'/'.LMS_VIEW.'/edit.php?edit_id='.$valQA['id'].'&id_std='.$_GET['id_std'].'&'.$redirection.'\');"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>';
                                                                                        echo'<a class="dropdown-item delete-item cursor-pointer" onclick="confirm_modal(\''.moduleName().'.php?deleteidMsj='.$valQA['id'].'&id_std='.$_GET['id_std'].'&'.$redirection.'\');"><i class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Delete</a>';
                                                                                    }
                                                                                    echo'
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="conversation-name"><small class="text-muted time">'.date('d M, Y h:i A', strtotime($valQA['datetime_sent'])).' - '.get_msg_status($valQA['status']).'</small></div>
                                                                    </div>
                                                                </div>
                                                            </li>';
                                                        }
                                                    }
                                                    echo'
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chat-input-section pt-4">
                        <div class="row g-0 align-items-center">
                            <div class="col">
                                <input type="text" class="form-control chat-input bg-light border-light" id="std_message" placeholder="Type your message..." autocomplete="off">
                            </div>
                            <div class="col-auto">
                                <div class="chat-input-links ms-2">
                                    <div class="links-list-item">
                                        <button class="btn btn-success" id="send_message">
                                            <i class="ri-send-plane-2-fill align-bottom"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';
    echo'
    <script>
        $(document).ready(function() {
            $(".chat-messages").scrollTop($(".chat-messages")[0].scrollHeight);
            // Function to load messages
            function loadMessages() {
                $(".chat-messages").load(location.href + " .chat-messages > *", function() {
                    // $(".chat-messages").scrollTop($(".chat-messages")[0].scrollHeight);
                });
            }

            // Set an interval to refresh messages every 10 seconds
            setInterval(loadMessages, 10000); // 10000 milliseconds = 10 seconds
            
            loadMessages();

            $("#send_message").click(function() { 
                var std_message = $("#std_message").val().trim();
                if (std_message !== "") {
                    var id_lecture  = "";
                    var id_curs     = "'.CURS_ID.'";
                    var id_std      = "'.$_GET['id_std'].'";
                    $.ajax({
                        type: "POST",
                        url: "include/ajax/get_tracking.php",
                        data: {
                            "id_lecture"    : id_lecture,
                            "id_curs"       : id_curs,
                            "std_message"   : std_message,
                            "id_std"        : id_std,
                            "_method"       : "student_teacher_qna"
                        },
                        success: function(response) {
                            console.log(response);
                            $(".chat-conversation-list").append(response);
                            $(".chat-messages").scrollTop($(".chat-messages")[0].scrollHeight);
                            $("#std_message").val("");
                            // $(".chat-messages").load(location.href + " .chat-messages");
                        }
                    });
                }
            });
        });
    </script>';
}
?>