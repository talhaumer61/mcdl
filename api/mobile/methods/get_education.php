<?php
if($data_arr['method_name'] == "get_education") { 
    $education        = array();

    if(empty($data_arr['std_id']) || $data_arr['std_id'] > 0){
        //Student Education
        $condition = array ( 
                                'select' 		=>	'id, program, institute, year,grade'
                                ,'where' 		=>	array( 
                                                            'id_std'           => cleanvars($data_arr['std_id']) 
                                                            ,'is_deleted'       => 0 
                                                        ) 
                                ,'return_type'	=>	'all'
        ); 
        $STUDENT_EDUCATIONS = $dblms->getRows(STUDENT_EDUCATIONS, $condition);
        if (!empty($STUDENT_EDUCATIONS)) {
            foreach ($STUDENT_EDUCATIONS as $edu) {
                $education[] = [
                    'education_id'      => $edu['id'],
                    'education_program' => $edu['program'],
                    'education_year'    => $edu['year'],
                    'education_grade'   => $edu['grade']
                ];

                $rowjson['success'] 		= 1;
                $rowjson['MSG'] 			= 'Education Fetched Successfully';
            }
        } else {
            $rowjson['success'] 		= 0;
            $rowjson['MSG'] 			= 'No Education Found';
        }
    } else {
        $rowjson['success'] 		= 0;
        $rowjson['MSG'] 			= 'Invalid Student ID';
    }
    $rowjson['list_education'] = $education;
}
?>
