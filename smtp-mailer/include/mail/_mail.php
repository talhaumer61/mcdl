<?php
if (TOKKEN) {
    if (TOKKEN == MATCHING_TOKKEN) {
        include "../../assets/PHPMailer/PHPMailerAutoload.php";
        $mail = new PHPMailer;
        error_reporting(0);
        ini_set('memory_limit', '-1');
        $RECEIVER       = explode(',',RECEIVER);
        $RECEIVER_NAME  = explode(',',RECEIVER_NAME);
        $recordFlag     = true;
        $latestID       = '';
        foreach ($RECEIVER as $mailkey => $mailvalue) {
            $mail->setFrom(SENDER,SENDER_NAME);
            $mail->addAddress($RECEIVER[$mailkey],$RECEIVER_NAME[$mailkey]);
            if (!empty(CC)) {
                $mail->addCC(CC, CC_NAME);
            }
            if (!empty(BCC)) {
                $mail->addBCC(BCC, BCC_NAME);
            }
            $mail->Subject    = SUBJECT;
            $mail->isHTML(true);
            $mail->Body       = html_entity_decode(html_entity_decode(BODY));
            $mail->AltBody    = 'This is a plain-text message body';
            if ($mail->send()) {
                $mailSentFlag     = 'sent';
                array_push($response, array(
                    'status'        => boolval(true),
                    'is_sent'       => boolval(true),
                    'date_time'     => date('Y-m-d G:i:s'),
                    'msg'           => $mailSentFlag,
                    'email'         => $RECEIVER[$mailkey],
                ));
            } else {
                $mailSentFlag     = 'not-sent';
                array_push($response, array(
                    'status'        => boolval(false),
                    'is_sent'       => boolval(false),
                    'date_time'     => date('Y-m-d G:i:s'),
                    'msg'           => $mailSentFlag,
                    'email'         => $RECEIVER[$mailkey],
                ));
            }
            if ($recordFlag) {
                $recordFlag = false;
                $MAIL_SEND  = $dblms->insert(MAIL_SEND, array(
                        'is_sent'           =>	$mailSentFlag,
                        'mail_slug'         =>	to_seo_url(SUBJECT),
                        'mail_subject'      =>	SUBJECT,
                        'mail_body'         =>	BODY,
                        'date_added'		=>	date('Y-m-d G:i:s'),
                        'ip_added'			=>	IP,
                ));
                $latestID = $dblms->lastestid();
            } else {
                $MAIL_SEND = true;
            }       
            if ($MAIL_SEND) {
                $dblms->insert(MAIL_SEND_DETAIL, array(
                        'id_mail'           =>	$latestID,
                        'mail_sender'       =>	SENDER,
                        'mail_sender_name'  =>	SENDER_NAME,
                        'mail_reciver'      =>	$RECEIVER[$mailkey],
                        'mail_reciver_name' =>	$RECEIVER_NAME[$mailkey],
                        'mail_cc'           =>	CC,
                        'mail_cc_name'      =>	CC_NAME,
                        'mail_bcc'          =>	BCC,
                        'mail_bcc_name'		=>	BCC_NAME,
                ));
            }
        }
    } else {
        array_push($response, array(
            'status'  => boolval(false),
            'msg'     => 'tokken-not-matched',
        ));
    }
} else {
    array_push($response, array(
        'status'  => boolval(false),
        'msg'     => 'tokken-missing',
    ));
}