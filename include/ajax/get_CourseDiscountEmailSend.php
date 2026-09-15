<?php
require_once ('../dbsetting/lms_vars_config.php');
require_once ('../dbsetting/classdbconection.php');
$dblms = new dblms();
require_once ('../functions/login_func.php');
require_once ('../functions/functions.php');
if ($_POST['_type'] == 'discount_email_send') {
    $sendMailRes                = [];
    // REFERRAL_CONTROL
    $condition	                =	array ( 
                             'select'		=>	'r.ref_id, r.ref_percentage'
                            ,'where'		=>	array( 
                                                         'r.ref_id'	        =>	cleanvars($_POST['id_ref'])
                                                        ,'r.ref_status'	    =>	1
                                                        ,'r.is_deleted'		=>	0
                                                )
                            ,'return_type' 	=>	'single' 
    ); 
    $REFERRAL_CONTROL           = $dblms->getRows(REFERRAL_CONTROL.' AS r', $condition);
    if ($REFERRAL_CONTROL) {
        // COURSES
        $condition	                =	array ( 
                                'select'		=>	'c.curs_href, c.curs_name'
                                ,'where'		=>	array( 
                                                             'c.curs_id'	    =>	cleanvars($_POST['id_curs'])
                                                            ,'c.curs_status'	=>	1
                                                            ,'c.is_deleted'		=>	0
                                                    )
                                ,'return_type' 	=>	'single' 
        ); 
        $COURSES                    = $dblms->getRows(COURSES.' AS c', $condition);
        // ADMIN
        $condition	                =	array ( 
                                'select'		=>	'a.adm_fullname'
                                ,'where'		=>	array( 
                                                            'a.adm_status'	    =>	1
                                                            ,'a.is_deleted'		=>	0
                                                    )
                                ,'return_type' 	=>	'single' 
        ); 
        foreach (explode(',', $_POST['std_emails']) AS $key => $val) {
            $sendTo                 = cleanvars($val);
            $sendFlag               = false;
            $condition['where']['a.adm_email']  = $sendTo;
            $ADMINS                 = $dblms->getRows(ADMINS.' AS a', $condition);
            get_SendMail([
                'sender'        => SMTP_EMAIL,
                'senderName'    => SITE_NAME,
                'receiver'      => $sendTo,
                'receiverName'  => (!empty($ADMINS['adm_fullname'])?moduleName($ADMINS['adm_fullname']):'Student'),
                'subject'       => "Friendly Reminder: Complete Your Course (".$COURSES['curs_name'].") on ".TITLE_HEADER."",
                'body'          => '
                    <table style="font-family:"Open Sans",Arial,sans-serif;background-color:#315a2c;border-radius:50px;width:900px;max-width:900px;" align="center">
                        <thead>
                            <tr>
                                <td width="150"></td>
                                <td align="center" style="background-color:#ebeef5">
                                    <table style="font-family:"Open Sans",Arial,sans-serif;background-color:#ebeef5;width:900px;max-width:900px;" align="center">
                                        <thead>
                                            <tr>
                                                <td align="center" style="background-color:#ebeef5 padding: 20px;">
                                                    <a href="'.WEBSITE_URL.'" target="_blank">
                                                        <img src="'.SITE_URL.'assets/images/brand/logo.png" width="168" style="display:block;width:168px;height:auto" height="auto">
                                                    </a>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td align="center" style="background-color:#ebeef5 padding: 20px;">
                                                    <table>
                                                        <tbody>
                                                            <tr><td style="padding: 10px;">Dear '.(!empty($ADMINS['adm_fullname'])?moduleName($ADMINS['adm_fullname']):'Student').',</td></tr>
                                                            <tr>
                                                                <td style="padding: 10px;">
                                                                    We are thrilled to offer you an exclusive <span style="color: green;">'.$REFERRAL_CONTROL['ref_percentage'].'%</span> discount on our <span style="color: green;">('.$COURSES['curs_name'].')</span> course as a token of our appreciation for your dedication and hard work. This special offer is our way of supporting your educational journey and helping you achieve your academic goals. Don\'t miss out on this opportunity to enhance your skills. click on the button bellow and checkout your discount. We look forward to continuing to support your learning and growth.
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding: 10px;">
                                                                    <div style="text-align:center;margin-top:30px;margin-bottom:20px">
                                                                        <a href="'.WEBSITE_URL.'courses/'.$COURSES['curs_href'].'/'.get_dataHashingOnlyExp($COURSES['curs_href'].','.$REFERRAL_CONTROL['ref_percentage'].','.$REFERRAL_CONTROL['ref_id'].','.$sendTo.',teacher',true).'" align="center" style="display:inline-block!important;background-color:green;color: white;width:280px;border-radius:5px;margin:0 auto;padding:12px;border:0px" name="enroll" type="submit" class="btn btn-enroll w-100">Enroll Now'.(!empty($ref_percentage)?'<span class="text-warning"> '.$REFERRAL_CONTROL['ref_percentage'].'% Off </span> On This Course':'').'</a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr><td style="padding: 10px;">When you click the button above, you will have the opportunity to get discounted course.</td></tr>
                                                            <tr>
                                                                <td style="padding: 10px;">
                                                                    Sincerely<br>
                                                                    '.TITLE_HEADER.'
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="center" style="padding: 10px;">
                                                                    <br>
                                                                    <br>© '.date('Y').' '.TITLE_HEADER.'
                                                                    <br>'.SITE_ADDRESS.'
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td width="150"></td>
                            </tr>
                        </thead>
                    </table>
                ',
                'tokken'        => SMTP_TOKEN,
            ], 'send-mail');
            // REFERRAL_TEACHER_SHARING
            $values =   [
                            'ref_shr_status'    => 1,
                            'std_emails'        => cleanvars($sendTo),
                            'id_curs'           => cleanvars($_POST['id_curs']),
                            'id_ref'            => cleanvars($_POST['id_ref']),
                            'id_added'          => cleanvars($_SESSION['userlogininfo']['LOGINIDA']),
                            'date_added'        => date('Y-m-d G:i:s'),
                        ];
            $REFERRAL_TEACHER_SHARING   = $dblms->insert(REFERRAL_TEACHER_SHARING, $values);
            if ($REFERRAL_TEACHER_SHARING) {
                $sendFlag = true;
            }
            $refEmailFlag = $sendTo.'|'.(boolval($sendFlag)?'1':'0');
            array_push($sendMailRes, $refEmailFlag);
            // SEND MAIL END
        }
        echo implode(',',$sendMailRes);
    }
}
?>