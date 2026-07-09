<?php
if($data_arr['method_name'] == "add_new_password") { 

    if(!empty($data_arr['user_id']) && $data_arr['user_id'] > 0){
        // FETCH USER
        $condition = array ( 
                             'select' 		=>	'adm_id'
                            ,'where' 		=>	array( 
                                                         'adm_id'       => cleanvars($data_arr['user_id']) 
                                                        ,'adm_status'   => '1'
                                                        ,'is_deleted'   => '0'
                                                    ) 
                            ,'return_type'	=>	'single'
                        );
        $row = $dblms->getRows(ADMINS, $condition); 

        if($row['adm_id']) {
		    $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
            $password = hash('sha256', $data_arr['new_password'] . $salt);
            for ($round = 0; $round < 65536; $round++) {
                $password = hash('sha256', $password . $salt);
            }
            $values = array(
                             'adm_userpass'			=> cleanvars($password)
                            ,'adm_salt'             => cleanvars($salt)
                            ,'id_modify'            => cleanvars($data_arr['user_id'])
                            ,'date_modify'          => date('Y-m-d G:i:s')
            ); 
            $sqllms = $dblms->update(ADMINS, $values, "WHERE adm_id = '".cleanvars($data_arr['user_id'])."'");
            if($sqllms) {
                $rowjson['success'] 		= 1;
                $rowjson['MSG'] 			= 'Password updated successfully';
            }
            else {
                $rowjson['success'] 		= 0;
                $rowjson['MSG'] 			= 'Password not updated, please try again';
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
