<?php
if($data_arr['method_name'] == "save_notebook"){

    if( !empty($data_arr['user_id']) && !empty($data_arr['std_id']) && !empty($data_arr['item_id']) && !empty($data_arr['week_id']) && !empty($data_arr['lecture_id']) ){

        $std_id    = $data_arr['std_id'];
        $user_id    = $data_arr['user_id'];
        $course_id = $data_arr['item_id'];
        $week_id   = $data_arr['week_id'];
        $lecture_id= $data_arr['lecture_id'];
        $id_mas = isset($data_arr['id_mas']) ? $data_arr['id_mas'] : 0;
        $id_ad_prg = isset($data_arr['id_ad_prg']) ? $data_arr['id_ad_prg'] : 0;
        $my_notebook = isset($data_arr['my_notebook']) ? $data_arr['my_notebook'] : '';


        $values = array(
                        'my_note_pad' => cleanvars($my_notebook)
                    ); 
        $sqllms = $dblms->Update(LECTURE_TRACKING, $values , 'WHERE id_week = '.cleanvars($week_id).' AND id_lecture = '.cleanvars($lecture_id).' AND id_curs = '.cleanvars($course_id).' AND id_mas = '.cleanvars($id_mas).' AND id_ad_prg = '.cleanvars($id_ad_prg).' AND id_std = '.cleanvars($std_id).'');
        if($sqllms){
            $rowjson['success'] = 1;
            $rowjson['MSG'] = 'Notebook saved successfully.'; 
        }
        else{
            $rowjson['success'] = 0;
            $rowjson['MSG'] = 'Failed to save notebook. Please try again.'; 
        }
        
    }
    else{
        $rowjson['success'] = 0;
        $rowjson['MSG'] = 'Please provide all required data.'; 
    }
}
?>