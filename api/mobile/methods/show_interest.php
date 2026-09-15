<?php
if($data_arr['method_name'] == "show_interest") { 
		
    if(!empty($data_arr['email']) && !empty($data_arr['id']) && !empty($data_arr['type'])) {
        $condition	=	array ( 
                                 'select' 	=> "id"
                                ,'where' 	=> array( 
                                                        'email'        =>	cleanvars($data_arr['email'])
                                                        ,'id_interest'  =>	cleanvars($data_arr['id'])	
                                                        ,'type'         =>	cleanvars($data_arr['type'])		
                                                    )
                                ,'return_type' 	=> 'count' 
						    ); 
        if($dblms->getRows(STUDENT_INTERESTED_COURSES, $condition)) {
            $rowjson['success'] = 0;
            $rowjson['MSG'] 	= 'Already shown interest for this '.get_offering_type($data_arr['type']);
        }else{
            $values = array(
                                 'status'           => 1
                                ,'name'             => cleanvars($data_arr['name'])
                                ,'email'            => cleanvars($data_arr['email'])
                                ,'type'             => cleanvars($data_arr['type'])
                                ,'id_interest'      => cleanvars($data_arr['id'])
                                ,'city'             => cleanvars($data_arr['city'])
                                ,'remarks'          => cleanvars($data_arr['remarks'])
                                ,'ip_posted'        => cleanvars(LMS_IP)
                                ,'date_posted'      => date('Y-m-d G:i:s')
                            ); 
            $sqllms = $dblms->insert(STUDENT_INTERESTED_COURSES, $values);
                
            if($sqllms){
                $rowjson['success'] = 1;	
                $rowjson['MSG'] 	= 'Interest recorded successfully!';
            } else {
                $rowjson['success'] = 0;	
                $rowjson['MSG'] 	= 'Some error occurred, please try again.';        
            }
        }
        
    } else {
        $rowjson['success'] = 0;	
        $rowjson['MSG'] 	= 'Please provide all required information.';
    }
}