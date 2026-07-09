<?php
if($data_arr['method_name'] == "update_profile_details") { 

    if( $data_arr['std_id'] > 0){

        foreach ($data_arr as $key => $value) {
            $data_array[$key] = trim($value);
        }
        $data_arr = $data_array;

        // PROFILE DETAIL
        $condition = array ( 
                         'select' 		=>	'std_loginid'
                        ,'where' 		=>	array( 
                                                     'std_status'   => '1'
                                                    ,'is_deleted'   => '0'
                                                    ,'std_id'       => cleanvars($data_arr['std_id']) 
                                                ) 
                        ,'return_type'	=>	'single'
                    ); 
        $row = $dblms->getRows(STUDENTS, $condition);
        if($row['std_loginid']) {

            // UPDATE STUDENT TABLE
            $values = array(
                                 'std_dob'   			=> cleanvars($data_arr['dob'])
                                ,'std_name'  			=> cleanvars($data_arr['full_name'])
                                ,'id_country'  			=> cleanvars($data_arr['country_id'])
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
                // UPDATE ADMIN TABLE
                $values = array(
                                     'adm_fullname'			=> cleanvars($data_arr['full_name'])
                                    ,'adm_phone'			=> cleanvars($data_arr['phone'])
                                    ,'adm_username'         => ''
                                    ,'id_modify'            => cleanvars($data_arr['std_id'])
                                    ,'date_modify'          => date('Y-m-d G:i:s')
                                );                
                // CHECK USERNAME SET AND EXISTS OR NOT
                if(isset($data_arr['username']) && $data_arr['username'] != ''){                    
                    $data_arr['username'] = preg_replace('/\s+/', '', $data_arr['username']);
                    if(strlen($data_arr['username']) < 5){
                        $rowjson['MSG']         = 'Profile Updated without Username, Username must be at least 5 characters long';
                    } else {
                        $condition = array ( 
                                                'select' 		=>	'adm_id'
                                                ,'where' 		=>	array( 
                                                                            'adm_username'     => cleanvars($data_arr['username'])
                                                                            ,'is_deleted'       => '0'
                                                                        ) 
                                                ,'return_type'	=>	'single'
                                            ); 
                        $ADMINS = $dblms->getRows(ADMINS, $condition);
                        if ( $ADMINS['adm_id'] ) {
                            $rowjson['MSG']         = 'Profile Updated without Username, Username Already Exists';
                        } else {
                            // UPDATE USERNAME
                            $values['adm_username'] = cleanvars($data_arr['username']);
                            $rowjson['MSG']         = 'Profile Updated Successfully';
                        }
                    }
                } else {
                    $rowjson['MSG']         = 'Profile Updated without Username';
                }
                $updateAdmin = $dblms->update(ADMINS, $values, " WHERE adm_id = '".$row['std_loginid']."'");
                if($updateAdmin && $sqllms){
                    $rowjson['success'] 		= 1;
                } else {
                    $rowjson['success'] 		= 0;
                    $rowjson['MSG'] 			= 'Profile Not Updated';
                }
            } else {
                $rowjson['success'] 		= 0;
                $rowjson['MSG'] 			= 'Profile Not Updated';
            }
        } else {
            $rowjson['success'] 		= 0;
            $rowjson['MSG'] 			= 'Student not found';
        }
    } else {
        $rowjson['success'] 		= 0;
        $rowjson['MSG'] 			= 'Invalid Student ID';
    }
}
?>
