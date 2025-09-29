<?php
if($data_arr['method_name'] == "update_profile_details") { 

    if( $data_arr['std_id'] > 0){
        // PROFILE DETAIL
        $condition = array ( 
                         'select' 		=>	'std_loginid'
                        ,'where' 		=>	array( 
                                                     'std_status'       => '1'
                                                    ,'std_id'     => cleanvars($data_arr['std_id']) 
                                                ) 
                        ,'return_type'	=>	'single'
                    ); 
        $row = $dblms->getRows(STUDENTS, $condition);
        if($row['std_loginid']) {
            $condition = array ( 
                         'select' 		=>	'adm_id'
                         ,'where' 		=>	array( 
                                                    'adm_username'     => cleanvars($data_arr['user_name']) 
                                                    ,'adm_status'       => '1'
                                                    ,'is_deleted'       => '0'
                                                ) 
                        ,'return_type'	=>	'single'
                    ); 
            $row = $dblms->getRows(ADMINS, $condition);
            if ( $row['adm_id'] ) {
                $rowjson['success'] 		= 0;
                $rowjson['MSG'] 			= 'Username Already Exists';
            }
            else {
                $values = array(
                            'adm_fullname'			=> cleanvars($data_arr['full_name'])
                            ,'adm_username'			=> cleanvars($data_arr['user_name'])
                            ,'adm_phone'			=> cleanvars($data_arr['phone'])
                            ,'id_modify'            => cleanvars($data_arr['std_id'])
                            ,'date_modify'          => date('Y-m-d G:i:s')
                        );     
                $updateAdmin = $dblms->update(ADMINS, $values, "WHERE adm_id = '".$row['std_loginid']."'");
                if($updateAdmin){
                    $values = array(
                            'std_dob'   			=> cleanvars($data_arr['dob'])
                            ,'id_country'  			=> cleanvars($data_arr['country'])
                            ,'std_address_1'		=> cleanvars($data_arr['permanent_address'])
                            ,'std_address_2'		=> cleanvars($data_arr['postal_address'])
                            ,'city_name'    		=> cleanvars($data_arr['city'])
                            ,'std_postal_address'	=> cleanvars($data_arr['zip_code'])
                            ,'std_gender'         	=> cleanvars($data_arr['gender'])
                            ,'id_modify'            => cleanvars($data_arr['std_id'])
                            ,'date_modify'          => date('Y-m-d G:i:s')
                        );
                    $sqllms = $dblms->update(STUDENTS, $values, "WHERE std_id = '".$data_arr['std_id']."'");
                    if($sqllms){
                        $rowjson['success'] 		= 1;
                        $rowjson['MSG'] 			= 'Profile Updated Successfully';
                    }
                    else {
                        $rowjson['success'] 		= 0;
                        $rowjson['MSG'] 			= 'Profile Not Updated';
                    }
                }
                else {
                    $rowjson['success'] 		= 0;
                    $rowjson['MSG'] 			= 'Profile Not Updated';
                }
            }
        }
        else {
            $rowjson['success'] 		= 0;
            $rowjson['MSG'] 			= 'No Student Found';
        }
    }
    else {
        $rowjson['success'] 		= 0;
        $rowjson['MSG'] 			= 'Invalid Student ID';
    }
}
?>
