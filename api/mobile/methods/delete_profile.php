<?php
if($data_arr['method_name'] == "delete_profile") { 
		
    if(!empty($data_arr['std_id']) && !empty($data_arr['user_id'])) {
        $values = array(
                         'id_deleted'	=>	cleanvars($data_arr['user_id'])
                        ,'is_deleted'	=>	'1'
                        ,'ip_deleted'	=>	cleanvars($ip)
                        ,'date_deleted'	=>	date('Y-m-d G:i:s')
                    );
        $sqlDel = $dblms->Update(STUDENTS,  $values , "WHERE std_id  = '".cleanvars($data_arr['std_id'])."'");
        $sqlDel = $dblms->Update(ADMINS,    $values , "WHERE adm_id       = '".cleanvars($data_arr['user_id'])."'");
        if($sqlDel) {
            $rowjson['success'] = 1;	
            $rowjson['MSG'] 	= 'Profile deleted successfully';
        } else {
            $rowjson['success'] = 0;	
            $rowjson['MSG'] 	= 'Profile not deleted, please try again';
        }
        
    } else {
        $rowjson['success'] = 0;	
        $rowjson['MSG'] 	= 'Please enter all required fields';
    }
}