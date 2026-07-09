<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;

require __DIR__ . '/../../vendor/autoload.php'; // adjust if vendor/ is elsewhere

$response = array();

// Token checks (keep your logic)
if (!defined('TOKKEN')) {
    $response[] = ['status'=>false, 'msg'=>'tokken-missing'];
    echo json_encode($response);
    exit;
}
if (TOKKEN !== MATCHING_TOKKEN) {
    $response[] = ['status'=>false, 'msg'=>'tokken-not-matched'];
    echo json_encode($response);
    exit;
}

// === OAuth2 credentials (move to env/config ideally) ===
$clientId     = CLIENT_ID;      // from Google Cloud
$clientSecret = CLIENT_SECRET;  // from Google Cloud
$refreshToken = REFRESH_TOKEN;  // from OAuth Playground
$email        = SMTP_USER;      // e.g. noreply.dodl@mul.edu.pk

// === Prepare receivers ===
$RECEIVER      = array_filter(array_map('trim', explode(',', RECEIVER)));
$RECEIVER_NAME = array_filter(array_map('trim', explode(',', RECEIVER_NAME)));
$CC            = trim(CC);
$BCC           = trim(BCC);

$recordFlag = true;
$latestID = null;

foreach ($RECEIVER as $mailkey => $mailvalue) {

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->Port       = SMTP_PORT;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPAuth   = true;
        $mail->AuthType   = 'XOAUTH2';
        $mail->CharSet    = 'UTF-8';

        // OAuth2 provider
        $provider = new Google([
            'clientId'     => $clientId,
            'clientSecret' => $clientSecret,
        ]);

        // Set OAuth
        $mail->setOAuth(new OAuth([
            'provider'     => $provider,
            'clientId'     => $clientId,
            'clientSecret' => $clientSecret,
            'refreshToken' => $refreshToken,
            'userName'     => $email,
        ]));

        // Recipients
        $mail->setFrom($email, defined('SENDER_NAME') ? SENDER_NAME : 'DODL - Notifications');
        $mail->addAddress($mailvalue, $RECEIVER_NAME[$mailkey] ?? '');

        if (!empty($CC)) {
            $mail->addCC(CC, CC_NAME);
        }
        if (!empty($BCC)) {
            $mail->addBCC(BCC, BCC_NAME);
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = SUBJECT;
        $mail->Body    = html_entity_decode(BODY);
        $mail->AltBody = strip_tags(html_entity_decode(BODY));

        // Debugging (optional)
        $mail->SMTPDebug  = 3; 
        $mail->Debugoutput = function($str, $level) {
            error_log("PHPMailer-OAuth2: [$level] $str");
        };

        // Send
        $sent = $mail->send();

        if ($sent) {
            $response[] = [
                'status'    => true,
                'is_sent'   => true,
                'date_time' => date('Y-m-d G:i:s'),
                'msg'       => 'sent',
                'email'     => $mailvalue,
            ];
        } else {
            $response[] = [
                'status'    => false,
                'is_sent'   => false,
                'date_time' => date('Y-m-d G:i:s'),
                'msg'       => $mail->ErrorInfo,
                'email'     => $mailvalue,
            ];
        }

    } catch (Exception $e) {
        error_log('PHPMailer OAuth2 error: ' . $e->getMessage());
        $response[] = [
            'status' => false,
            'msg'    => 'mailer-exception',
            'error'  => $e->getMessage(),
        ];
    }
}

// Output results
echo json_encode($response);
exit;