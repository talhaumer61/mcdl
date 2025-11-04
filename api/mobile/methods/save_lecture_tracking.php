<?php
if($data_arr['method_name'] == "save_lecture_tracking"){

    if( !empty( $data_arr['tracking_mode'] ) && !empty($data_arr['user_id']) && !empty($data_arr['std_id']) && !empty($data_arr['item_id']) && !empty($data_arr['week_id']) && !empty($data_arr['content_id']) ){

        $std_id    = $data_arr['std_id'];
        $user_id    = $data_arr['user_id'];
        $course_id = $data_arr['item_id'];
        $week_id   = $data_arr['week_id'];
        $content_id= $data_arr['content_id'];
        $id_mas = isset($data_arr['id_mas']) ? $data_arr['id_mas'] : 0;
        $id_ad_prg = isset($data_arr['id_ad_prg']) ? $data_arr['id_ad_prg'] : 0;
        $video_duration = isset($data_arr['video_duration']) ? $data_arr['video_duration'] : 0;

        // reading material completed
        if( $data_arr['tracking_mode'] == 1 ){
            $con    = array(
                         'select'       => 'track_id,is_completed'
                        ,'where'        => array(
                                                     'id_lecture'   => $content_id
                                                    ,'id_curs'      => $course_id
                                                    ,'id_mas'       => $id_mas
                                                    ,'id_ad_prg'    => $id_ad_prg
                                                    ,'id_std'       => $std_id
                                                    ,'is_deleted'   => 0
                                            )
                        ,'return_type'  => 'count'
            );
            $chk = $dblms->getRows(LECTURE_TRACKING,$con);
            if (!$chk) {
                $values = array(
                                'track_status'     =>	1
                                ,'is_completed'		=>	2
                                ,'track_mood'		=>	'reading_metrial'
                                ,'id_std'		    =>	$std_id
                                ,'id_week'	        =>	$week_id
                                ,'id_lecture'	    =>	$content_id
                                ,'id_curs'	        =>	$course_id
                                ,'id_mas'           =>  $id_mas
                                ,'id_ad_prg'        =>  $id_ad_prg
                                ,'id_added'		    =>	$user_id
                                ,'date_added'	    =>	date('Y-m-d G:i:s')
                ); 
                $sqllms	    =	$dblms->insert(LECTURE_TRACKING, $values);
                if ($sqllms) {
                    $rowjson['success'] = 1;
                    $rowjson['MSG'] = 'reading material marked as completed.'; 
                }
                else{
                    $rowjson['success'] = 0;
                    $rowjson['MSG'] = 'Error in marking as completed, please try again.'; 
                }
            }
            else{
                if($chk['is_completed'] != 2){
                    $values = array(
                            'is_completed' =>	2
                            ,'track_mood'	=>	'reading_metrial'
                            ,'id_modify'	=>	$user_id
                            ,'date_modify'	=>	date('Y-m-d G:i:s')
                    ); 
                    $sqllms = $dblms->Update(LECTURE_TRACKING, $values , 'WHERE  track_id  = '.$chk['track_id'].'');
                    if ($sqllms) {
                        $rowjson['success'] = 1;
                        $rowjson['MSG'] = 'reading material marked as completed.'; 
                    }
                    else{
                        $rowjson['success'] = 0;
                        $rowjson['MSG'] = 'Error in marking as completed, please try again.';
                    }
                }
                else {
                    $rowjson['success'] = 0;
                    $rowjson['MSG'] = 'Already marked as completed.';
                }
            }
        }
        // video playing
        elseif( $data_arr['tracking_mode'] == 2 ){
            $con    = array(
                         'select'       => 'track_id,is_completed'
                        ,'where'        => array(
                                                     'id_lecture'   => $content_id
                                                    ,'id_curs'      => $course_id
                                                    ,'id_mas'       => $id_mas
                                                    ,'id_ad_prg'    => $id_ad_prg
                                                    ,'id_std'       => $std_id
                                                    ,'is_deleted'   => 0
                                            )
                        ,'return_type'  => 'single'
            );
            $chk = $dblms->getRows(LECTURE_TRACKING,$con, $sql);
            if ($chk) {
                $rowjson['success'] = 0;
                $rowjson['MSG'] = 'Tracking already exists.';
            } else {
                $values = array(
                                'track_status'     =>	1
                                ,'is_completed'		=>	1 // 1 = playing, 2 = completed
                                ,'id_week'		    =>	$week_id
                                ,'track_mood'		=>	'playing'
                                ,'id_std'		    =>	$std_id
                                ,'id_lecture'	    =>	$content_id
                                ,'id_curs'	        =>	$course_id
                                ,'id_mas'           =>  $id_mas
                                ,'id_ad_prg'        =>  $id_ad_prg
                                ,'video_duration'	=>	$video_duration
                                ,'id_added'		    =>	$user_id
                                ,'date_added'	    =>	date('Y-m-d G:i:s')
                );
                $sqllms = $dblms->Insert(LECTURE_TRACKING, $values);
                if ($sqllms) {
                    $rowjson['success'] = 1;
                    $rowjson['MSG'] = 'Tracking saved successfully.'; 
                }
                else{
                    $rowjson['success'] = 0;
                    $rowjson['MSG'] = 'Error in saving tracking, please try again.';
                }
            }
        }
        // video completed
        elseif( $data_arr['tracking_mode'] == 3 ){
            $con    = array(
                         'select'       => 'track_id,is_completed'
                        ,'where'        => array(
                                                     'id_lecture'   => $content_id
                                                    ,'id_curs'      => $course_id
                                                    ,'id_mas'       => $id_mas
                                                    ,'id_ad_prg'    => $id_ad_prg
                                                    ,'id_std'       => $std_id
                                                    ,'is_deleted'   => 0
                                            )
                        ,'return_type'  => 'single'
            );
            $chk = $dblms->getRows(LECTURE_TRACKING,$con, $sql);
            if ($chk) {
                if($chk['is_completed'] != 2){
                    $values = array(
                            'is_completed' =>	2
                            ,'track_mood'	=>	'completed'
                            ,'id_modify'	=>	$user_id
                            ,'date_modify'	=>	date('Y-m-d G:i:s')
                    ); 
                    $sqllms = $dblms->Update(LECTURE_TRACKING, $values , 'WHERE  track_id  = '.$chk['track_id'].'');
                    if ($sqllms) {
                        $rowjson['success'] = 1;
                        $rowjson['MSG'] = 'Marked as completed.'; 
                    }
                    else{
                        $rowjson['success'] = 0;
                        $rowjson['MSG'] = 'Error in marking as completed, please try again.';
                    }
                } else {
                    $rowjson['success'] = 0;
                    $rowjson['MSG'] = 'Already marked as completed.';
                }
            }
            else {
                $rowjson['success'] = 0;
                $rowjson['MSG'] = 'No tracking found to mark as completed.';
            }
        }
        else{
            $rowjson['success'] = 0;
            $rowjson['MSG'] = 'Invalid tracking mode.'; 
        }
        
    }
    else{
        $rowjson['success'] = 0;
        $rowjson['MSG'] = 'Please provide all required data.'; 
    }
}
?>