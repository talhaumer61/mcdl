<?php
if($data_arr['method_name'] == "user_signup") { 
    if(!empty($data_arr['email']) && !empty($data_arr['fullname']) && !empty($data_arr['gender']) && !empty($data_arr['phone']) && !empty($data_arr['password'])){

        $org_id = 0;
        $next_check = true;

        if (isset($data_arr['org_referral']) && !empty($data_arr['org_referral'])) {
            $org_referral = trim($data_arr['org_referral']);

            // Extract referral code from the URL if it contains "/signup/"
            if (strpos($org_referral, '/signup/') !== false) {
                // Get the last part after "/signup/"
                $referral_code = basename(parse_url($org_referral, PHP_URL_PATH));
            } else {
                // If it's not a link, use as it is
                $referral_code = $org_referral;
            }

            if (!empty($referral_code)) {
                $condition  = [ 
                                'select'        =>  'o.org_id, o.org_referral_link, o.org_link_to',
                                'join'          =>  'INNER JOIN '.ADMINS.' AS a ON a.adm_id = o.id_loginid',
                                'where'         =>  [
                                                        'a.adm_logintype'     => 8,
                                                        'a.adm_status'        => 1,
                                                        'a.is_deleted'        => 0,
                                                        'o.org_status'        => 1,
                                                        'o.is_deleted'        => 0,
                                                        'o.org_referral_link' => cleanvars($referral_code),
                                                    ],
                                'search_by'     =>  'AND DATE(o.org_link_to) >= CURDATE()',
                                'return_type'   =>  'single',
                            ]; 
                $ORGANIZATIONS = $dblms->getRows(SKILL_AMBASSADOR.' AS o', $condition);

                if($ORGANIZATIONS){                            
                    // Now assign the extracted code
                    $org_id = cleanvars($ORGANIZATIONS['org_id']);
                } else {
                    // response
                    $rowjson['success'] = 0;
                    $rowjson['MSG'] 	= 'Referral link/code is invalid';
                    $next_check = false;
                }
            }
        }

        if($next_check == true){
            $condition	=	array ( 
                                        'select'		=> "adm_email"
                                        ,'where'		=> array( 
                                                                    'adm_email'         => cleanvars($data_arr['email'])
                                                                    ,'is_deleted'		=> '0'
                                                                )
                                        ,'return_type'	=> 'count'
                                    ); 
            if($dblms->getRows(ADMINS, $condition)) {
                $rowjson['success'] = 0;	
                $rowjson['MSG'] 	= 'Email already in use!';
            } else {
                $array 				= get_PasswordVerify($data_arr['password']);
                $std_password 		= $array['hashPassword'];
                $salt 				= $array['salt'];

                $values = array(
                                    'adm_status'		=> 1
                                    ,'adm_type'			=> 3
                                    ,'adm_logintype'	=> 3
                                    ,'is_teacher'		=> 1
                                    ,'adm_fullname'		=> cleanvars($data_arr['fullname'])
                                    ,'adm_email'		=> cleanvars($data_arr['email'])
                                    ,'adm_phone'		=> cleanvars($data_arr['phone'])
                                    ,'adm_userpass'		=> cleanvars($std_password)
                                    ,'adm_salt'			=> cleanvars($salt)
                                    ,'date_added'		=> date('Y-m-d G:i:s')
                                );
                $sqlAdmin = $dblms->Insert(ADMINS, $values);

                if($sqlAdmin) { 
                    $latestID =	$dblms->lastestid();

                    $stdValues = array(
                                         'std_loginid'			=> cleanvars($latestID)
                                        ,'std_status'			=> 1
                                        ,'std_level'			=> 1
                                        ,'std_name'				=> cleanvars($data_arr['fullname'])
                                        ,'std_gender'			=> cleanvars($data_arr['gender'])
                                        ,'id_org'               => cleanvars($org_id)
                                        ,'date_added'			=> date('Y-m-d G:i:s')
                                        ,'id_added'				=> cleanvars($latestID)
                                    );
                    $sqlStd = $dblms->Insert(STUDENTS, $stdValues);
                    if($sqlStd){
                        
                        $stdID = $dblms->lastestid();

                        get_SendMail([
                            'sender'        => SMTP_EMAIL,
                            'senderName'    => SITE_NAME,
                            'receiver'      => cleanvars($data_arr['email']),
                            'receiverName'  => cleanvars($data_arr['fullname']),
                            'subject'       => "Welcome to ".TITLE_HEADER.", Your Journey Begins Here!",
                            'body'          => '
                                <p>
                                    We are excited to have you join our learning community! This email contains all the essential information to get you started on DODL.
                                    <br>
                                    <a href="https://youtu.be/5ZEUEok9Mig" target="_blank">Information video!</a>
                                    <br>
                                    <br>
                                    <b>Warm regards,</b>
                                    <br>
                                    Support Team
                                    <br>
                                    <br>
                                    '.SMTP_EMAIL.'
                                    <br>
                                    '.SITE_NAME.' <b>('.TITLE_HEADER.')</b>
                                    <br>
                                    <b>Minhaj University Lahore</b>
                                </p>
                            ',
                            'tokken'        => SMTP_TOKEN,
                        ], 'send-mail');
                    }

                    $rowjson['success'] 		= 1;
                    $rowjson['MSG'] 			= 'Account Created Successfully';
                    // from admin
                    $rowjson['user_id'] 		= "$latestID";
                    $rowjson['user_type'] 		= "3";
                    $rowjson['user_username']	= "";
                    $rowjson['user_email']		= $data_arr['email'];
                    $rowjson['user_fullname']	= $data_arr['fullname'];
                    $rowjson['user_phone']		= $data_arr['phone'];
                    $rowjson['user_photo'] 		= SITE_URL.'uploads/images/default_male.jpg';

                    // from std
                    $rowjson['std_id'] 			= "$stdID";
                    $rowjson['std_level']		= "1";
                    $rowjson['std_gender']		= $data_arr['gender'];
                    $rowjson['is_teacher']		= "1";
                    
                    
                    // emply and organization
                    $rowjson['emply_request']	= "";
                    $rowjson['std_org']		    = $org_id ? "$org_id" : '';
                    
                } else {
                    // response
                    $rowjson['success'] = 0;
                    $rowjson['MSG'] 	= 'Something went wrong!';
                }
            }
        }
    } else {        
        // response
        $rowjson['success'] = 0;
        $rowjson['MSG'] 	= 'Invalid Parameters. All fields required!';
    }
} 