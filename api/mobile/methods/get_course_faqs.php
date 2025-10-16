<?php
if($data_arr['method_name'] == "get_course_faqs") { 

    if(empty($data_arr['curs_id']) || $data_arr['curs_id'] > 0){
        $faqs = array();
        $curs_id = $data_arr['curs_id'] ?? 0;
        $condition = array ( 
                     'select'       =>	'question,answer'
                    ,'where'        =>	array( 
                                               'id_curs' 	     => cleanvars($curs_id)
                                              ,'is_deleted' 	 => '0'  
                                            )
                    ,'return_type'	=>	'all'
                  ); 
        $COURSES_FAQS = $dblms->getRows(COURSES_FAQS, $condition);
        if($COURSES_FAQS) {
            foreach ($COURSES_FAQS as $val) {
                $faq['question'] = html_entity_decode($val['question']) ?? '';
                $faq['answer']   = html_entity_decode($val['answer']) ?? '';
                array_push($faqs, $faq);
            }
            $rowjson['success'] 		= 1;
            $rowjson['MSG'] 			= 'Course FAQs Fetched Successfully';
            $rowjson['faqs'] 			= $faqs;
        } else {
            $rowjson['success'] 		= 0;
            $rowjson['MSG'] 			= 'No FAQs Found';
        }
    }
    else {
        $rowjson['success'] 		= 0;
        $rowjson['MSG'] 			= 'Invalid Course ID';
    }
}
?>
