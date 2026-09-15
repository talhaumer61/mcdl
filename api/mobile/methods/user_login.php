<?php
if($data_arr['method_name'] == "user_login") { 
		
    if(!empty($data_arr['username']) && !empty($data_arr['password'])) {

        $adm_username = $data_arr['username'];
        $adm_userpass = $data_arr['password'];

        $loginconditions = array ( 
                                     'select' 		=>	'a.*, s.std_id, s.std_level, s.std_gender, s.id_org, e.emply_request'
                                    ,'join' 		=>	'LEFT JOIN '.STUDENTS.' s ON s.std_loginid = a.adm_id AND s.std_status = 1 AND s.is_deleted = 0
                                                         LEFT JOIN '.EMPLOYEES.' e ON e.id_added = a.adm_id AND e.emply_status = 1 AND e.is_deleted = 0'
                                    ,'where' 		=>	array( 
                                                                    'a.adm_status'		=> '1'
                                                                ,'a.is_deleted' 	=> '0'
                                                            )
                                    ,'search_by'	=>	' AND a.is_teacher IN (1,3) AND (a.adm_username = "'.$adm_username.'" OR a.adm_email = "'.$adm_username.'" )'
                                    ,'return_type'	=>	'single'
        ); 		
        $row = $dblms->getRows(ADMINS.' a', $loginconditions);
        if ($row) {

            // PASSWORD HASHING
            $salt 		= $row['adm_salt'];
            $password 	= hash('sha256', $adm_userpass . $salt);			
            for ($round = 0; $round < 65536; $round++) {
                $password = hash('sha256', $password . $salt);
            }

            if($password == $row['adm_userpass']) { 
                $dataLog = array(
                                     'login_type'		=> cleanvars($row['adm_logintype'])
                                    ,'id_login_id'		=> cleanvars($row['adm_id'])
                                    ,'user_name'		=> cleanvars($row['adm_username'])
                                    ,'user_pass'		=> cleanvars($adm_userpass)
                                    ,'email'			=> cleanvars($row['adm_email'])
                                    ,'id_campus'		=> cleanvars($row['id_campus'])
                                    ,'dated'			=> date("Y-m-d G:i:s")
                );	
                $sqllmslog = $dblms->Insert(LOGIN_HISTORY , $dataLog);

                // CHECK FILE EXIST
                if($row['std_gender'] == '2'){
                    $photo = SITE_URL.'uploads/images/default_female.jpg';
                }else{            
                    $photo = SITE_URL.'uploads/images/default_male.jpg';
                }

                if(!empty($row['adm_photo'])){
                    $photo = SITE_URL.'uploads/images/admin/'.$row['adm_photo'];
                }
            
                $rowjson['success'] 		= 1;
                $rowjson['MSG'] 			= 'Login Successfully';

                // from admin
                $rowjson['user_id'] 		= $row['adm_id'];
                $rowjson['user_type'] 		= $row['adm_type'];
                $rowjson['user_username']	= $row['adm_username'];
                $rowjson['user_email']		= $row['adm_email'];
                $rowjson['user_fullname']	= $row['adm_fullname'];
                $rowjson['user_phone']		= $row['adm_phone'];
                $rowjson['user_photo'] 		= $photo;

                // from std
                $rowjson['std_id'] 			= $row['std_id'] ?? '';
                $rowjson['std_level']		= $row['std_level'] ?? '';
                $rowjson['std_gender']		= $row['std_gender'] ?? '';
                $rowjson['is_teacher']		= $row['is_teacher'] ?? '';

                // emply and organization
                $rowjson['emply_request']	= $row['emply_request'] ?? '';
                $rowjson['std_org']		    = $row['id_org'] ?? '';

            } else {
                $rowjson['success'] = 0;	
                $rowjson['MSG'] 	= 'Invalid Password';
            }
            
        } else {
            $rowjson['success'] = 0;	
            $rowjson['MSG'] 	= 'Username / Email not found';
        }	
        
    } else {
        $rowjson['success'] = 0;	
        $rowjson['MSG'] 	= 'Credentials Required';
    }
}