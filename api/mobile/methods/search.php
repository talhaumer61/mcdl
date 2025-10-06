<?php
if($data_arr['method_name'] == "search") { 
    $search_word = trim($data_arr['query']);
    $search_results	= array();

    if($search_word == ''){
        $rowjson['success']         =   0;
        $rowjson['MSG']             =   'Search word is empty';
        $rowjson['search_results']  =   $search_results;
    } else {
        $condition = array ( 
                                'select' 		    =>	'ao.admoff_type, ap.id, ap.program, p.prg_photo, mt.mas_id, mt.mas_name, mt.mas_photo, c.curs_id, c.curs_name, c.curs_photo, c.curs_type_status'
                                ,'join'             =>  'LEFT JOIN '.ADMISSION_PROGRAMS.' ap ON ap.id = ao.admoff_degree AND ap.is_deleted = 0
                                                         LEFT JOIN '.MASTER_TRACK.' mt ON mt.mas_id = ao.admoff_degree AND mt.is_deleted = 0
                                                         LEFT JOIN '.COURSES.' c ON c.curs_id = ao.admoff_degree AND c.is_deleted = 0
                                                         LEFT JOIN '.PROGRAMS.' p ON p.prg_id  = ap.id_prg AND p.is_deleted = 0'
                                ,'where' 		    =>	[ 
                                                            'ao.is_deleted' 	    => '0'
                                                        ]
                                ,'search_by'        =>  'AND (ap.program LIKE "%'.$search_word.'%" 
                                                                        OR 
                                                            mt.mas_name LIKE "%'.$search_word.'%" 
                                                                        OR 
                                                            c.curs_name LIKE "%'.$search_word.'%")'
                                ,'order_by'     =>  'ao.admoff_id'
                                ,'return_type'	=>	'all'
                            );
        $ADMISSION_OFFERING = $dblms->getRows(ADMISSION_OFFERING.' ao', $condition);

        if ($ADMISSION_OFFERING && $search_word != "") {
            foreach($ADMISSION_OFFERING as $key => $val):
                $photo = SITE_URL.'uploads/images/default_curs.jpg';
                if($val['admoff_type'] == 1){
                    $id         = $val['id'];
                    $name       = $val['prg_name'];
                    $photo      = (!empty($val['prg_photo']) ? SITE_URL.'uploads/images/programs/'.$val['prg_photo'] : '');
                } else if($val['admoff_type'] == 2){
                    $id         = $val['mas_id'];
                    $name       = $val['mas_name'];
                    $photo      = (!empty($val['mas_photo']) ? SITE_URL.'uploads/images/admissions/master_track/'.$val['mas_photo'] : '');
                } else if($val['admoff_type'] == 3 || $val['admoff_type'] == 4){
                    $id         = $val['curs_id'];
                    $name       = $val['curs_name'];
                    $curs_type  = $val['curs_type_status'];
                    $photo      = (!empty($val['curs_photo']) ? SITE_URL.'uploads/images/courses/'.$val['curs_photo'] : '');
                }
                
                $dataResult['id']			    = $id;
                $dataResult['type'] 		    = $val['admoff_type'];
                $dataResult['type_name']	    = get_offering_type($val['admoff_type']);
                $dataResult['name'] 		    = html_entity_decode($name);
                $dataResult['photo'] 		    = $photo;
                $dataResult['course_type']      = $curs_type ?? '0';

                array_push($search_results, $dataResult);
            endforeach;
            
            $rowjson['success']         = 1;
            $rowjson['MSG'] 			= 'Search results found';
        } else {
            $rowjson['success']         = 0;
            $rowjson['MSG'] 			= 'no results found';
        }
        $rowjson['search_results']  = $search_results;
    }
}