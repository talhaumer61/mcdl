<?php
if ($data_arr['method_name'] == "submit_discussion_board") {
    
    if( !empty($data_arr['std_id']) && !empty($data_arr['discussion_id']) && !empty($data_arr['detail'])) {
        $std_id = $data_arr['std_id'];
        $discussion_id = $data_arr['discussion_id'];
        $values = array (
                            "id_std"           => cleanvars($std_id)
                            ,"id_discussion"	=> cleanvars($discussion_id)
                            ,"dst_detail"       => cleanvars($data_arr['detail'])
        );
        $sqllms  = $dblms->insert(COURSES_DISCUSSIONSTUDENTS, $values);
        if ($sqllms) {
            $rowjson['success'] = 1;
            $rowjson['MSG'] = 'Discussion Submitted Successfully';
        }
        else{
            $rowjson['success'] = 0;
            $rowjson['MSG'] = 'Error while submitting discussion. Please try again!';
        }
    }
    else{
        $rowjson['success'] = 0;
        $rowjson['MSG'] = 'Required parameters are mission';
    }
}
?>