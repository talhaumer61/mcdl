<?php
if (TOKKEN) {
    if (TOKKEN == MATCHING_TOKKEN) {
        include "../assets/PHPMailer/PHPMailerAutoload.php";
        $mail = new PHPMailer;
        error_reporting(0);
        ini_set('memory_limit', '-1');
        switch (CONTROLER):
            case 'send-single-mail':
                if (ZONE == 'account-verification') {
                    $mail->setFrom(SENDER,SENDER_NAME);
                    $mail->addAddress(RECEIVER,RECEIVER_NAME);
                    $mail->Subject    = SUBJECT;
                    $mail->isHTML(true);
                    $mail->Body       = html_entity_decode(html_entity_decode(!empty(CUSTOM_BODY)?CUSTOM_BODY: get_AppLanguage(ZONE)));
                    $mail->AltBody    = 'This is a plain-text message body';
                    if ($mail->send()) {
                        $response = array(
                            'status'        => boolval(true),
                            'is_send'       => boolval(true),
                            'date_time'     => date('Y-m-d G:i:s'),
                            'msg'           => 'sent',
                            'email'         => RECEIVER,
                        );
                    } else {
                        $response = array(
                            'status'        => boolval(false),
                            'is_send'       => boolval(false),
                            'date_time'     => date('Y-m-d G:i:s'),
                            'msg'           => 'not-sent',
                            'email'         => RECEIVER,
                        );
                    }
                }
            break;
            case 'send-multiple-mail':
                if (ZONE == 'account-verification') {
                    $RECEIVER       = explode(',',RECEIVER);
                    $RECEIVER_NAME  = explode(',',RECEIVER_NAME);
                    foreach ($RECEIVER as $mailkey => $mailvalue) {
                        $mail->setFrom(SENDER,SENDER_NAME);
                        $mail->addAddress($RECEIVER[$mailkey],$RECEIVER_NAME[$mailkey]);
                        $mail->Subject    = SUBJECT;
                        $mail->isHTML(HTML);
                        $mail->Body       = html_entity_decode(html_entity_decode(!empty(CUSTOM_BODY)?CUSTOM_BODY: get_AppLanguage(ZONE)));
                        $mail->AltBody    = 'This is a plain-text message body';
                        if ($mail->send()) {
                            array_push($response, array(
                                'status'        => boolval(true),
                                'is_send'       => boolval(true),
                                'date_time'     => date('Y-m-d G:i:s'),
                                'msg'           => 'sent',
                                'email'         => $RECEIVER[$mailkey],
                            ));
                        } else {
                            array_push($response, array(
                                'status'        => boolval(false),
                                'is_send'       => boolval(false),
                                'date_time'     => date('Y-m-d G:i:s'),
                                'msg'           => 'not-sent',
                                'email'         => $RECEIVER[$mailkey],
                            ));
                        }
                    }
                }
            break;
            default:
                $response = array(
                    'status'  => boolval(false),
                    'msg'     => 'path-not-defiend',
                );
            break;
        endswitch;
    } else {
        $response = array(
            'status'  => boolval(false),
            'msg'     => 'tokken-not-matched',
        );
    }
} else {
    $response = array(
        'status'  => boolval(false),
        'msg'     => 'tokken-missing',
    );
}
?>