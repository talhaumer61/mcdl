<?php
if($data_arr['method_name'] == "get_profile_details") { 
    $profile        = array();
    $countries      = array();

    if(empty($data_arr['std_id']) || $data_arr['std_id'] > 0){
        // PROFILE DETAIL
        $condition = array ( 
                         'select' 		=>	's.std_id, a.adm_fullname, a.adm_username, a.adm_photo, a.adm_phone, a.adm_email, s.std_dob, s.id_country, s.std_address_1, s.std_address_2, s.city_name, s.std_postal_address, s.std_gender, c.country_name'
                        ,'join' 		=>	'INNER JOIN '.ADMINS.' a ON s.std_loginid = a.adm_id AND a.adm_status = 1 AND a.is_deleted = 0
                                             LEFT JOIN '.COUNTRIES.' c ON s.id_country = c.country_id AND c.country_status = 1 AND c.is_deleted = 0'
                        ,'where' 		=>	array( 
                                                     's.is_deleted'   => 0
                                                    ,'s.std_status'   => 1
                                                    ,'s.std_id'       => cleanvars($data_arr['std_id']) 
                                                ) 
                        ,'return_type'	=>	'single'
                    ); 
        $row = $dblms->getRows(STUDENTS.' s', $condition);

        if($row['std_id']) {
            // CHECK FILE EXIST
            if($row['std_gender'] == '2'){
                $photo = SITE_URL.'uploads/images/default_female.jpg';
            } else {
                $photo = SITE_URL.'uploads/images/default_male.jpg';
            }

            if(!empty($row['adm_photo'])){
                $photo = SITE_URL.'uploads/images/admin/'.$row['adm_photo'];
            }

            $condition = array ( 
                         'select' 		=>	'country_id, country_name'
                        ,'where' 		=>	array( 
                                                     'country_status'   => '1'
                                                    ,'is_deleted'       => '0'
                                                ) 
                        ,'return_type'	=>	'all'
                    ); 
            $COUNTRIES = $dblms->getRows(COUNTRIES, $condition);
            if (!empty($COUNTRIES)) {
                foreach ($COUNTRIES as $country) {
                    $countries[] = [
                        'country_id'   => $country['country_id'],
                        'country_name' => $country['country_name']
                    ];
                }
            }
            $profile['photo']               = ( $photo );
            $profile['full_name']           = ( $row['adm_fullname'] ) ? $row['adm_fullname'] : '';
            $profile['username']            = ( $row['adm_username'] ) ? $row['adm_username'] : '';
            $profile['phone']               = ( $row['adm_phone'] ) ? $row['adm_phone'] : '';
            $profile['gender']              = ( $row['std_gender'] )? $row['std_gender'] : '';
            $profile['email']               = ( $row['adm_email'] ) ? $row['adm_email'] : '';
            $profile['dob']                 = ( $row['std_dob'] ) ? $row['std_dob'] : '';
            $profile['country_id']          = ( $row['id_country'] ) ? "".intval($row['id_country'])."" : '';
            $profile['country_name']        = ( $row['country_name'] ) ? "".$row['country_name']."" : '';
            $profile['city']                = ( $row['city_name'] ) ? $row['city_name'] : '';
            $profile['zip_code']            = ( $row['std_postal_address'] ) ? $row['std_postal_address'] : '';
            $profile['permanent_address']   = ( $row['std_address_1'] ) ? $row['std_address_1'] : '';
            $profile['postal_address']      = ( $row['std_address_2'] ) ? $row['std_address_2'] : '';

            // COUNTRIES LIST
            $profile['list_countries']      = $countries;

            $rowjson['success'] 		= 1;
            $rowjson['MSG'] 			= 'Profile Detail Fetched Successfully';
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
    $rowjson['profile_detail'] = $profile;
}
?>
