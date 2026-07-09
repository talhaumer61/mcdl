<?php
if($data_arr['method_name'] == "check_old_password") { 

    if(!empty($data_arr['user_id']) && $data_arr['user_id'] > 0){
        // FETCH USER
        $condition = array ( 
                         'select' 		=>	'adm_id, adm_salt, adm_userpass'
                        ,'where' 		=>	array( 
                                                     'adm_id'       => cleanvars($data_arr['user_id']) 
                                                    ,'adm_status'   => '1'
                                                    ,'is_deleted'   => '0'
                                                ) 
                        ,'return_type'	=>	'single'
                    ); 
        $row = $dblms->getRows(ADMINS, $condition);

        if($row['adm_id']) {
            $adm_userpass 	= cleanvars($data_arr['current_password']);
            $salt 		    = $row['adm_salt'];
			$password 	    = hash('sha256', $adm_userpass . $salt);
            for ($round = 0; $round < 65536; $round++) {
				$password = hash('sha256', $password . $salt);
			}

			if($password == $row['adm_userpass']) { 	
                $rowjson['success'] 		= 1;
                $rowjson['MSG'] 			= 'Password is correct';
            }
            else {
                $rowjson['success'] 		= 0;
                $rowjson['MSG'] 			= 'Incorrect current password';
            }
        }
        else {
            $rowjson['success'] 		= 0;
            $rowjson['MSG'] 			= 'Invalid User ID';
        }
    }
    else {
        $rowjson['success'] 		= 0;
        $rowjson['MSG'] 			= 'User ID is required';
    }
}
?>
