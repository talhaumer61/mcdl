<?php
if($data_arr['method_name'] == "search") { 
    $saerch_results	= array();

    $condition = array ( 
                            'select' 		    =>	'ap.id, ap.program, p.prg_name, p.prg_photo, mt.mas_name, mt.mas_photo, mt.mas_id, c.curs_id, c.curs_name, c.curs_photo'
                            ,'join'             =>  'LEFT JOIN '.ADMISSION_PROGRAMS.' ap ON (ap.id = ao.admoff_degree AND ap.is_deleted = 0)
                                                        LEFT JOIN '.MASTER_TRACK.' mt ON (mt.mas_id = ao.admoff_degree AND mt.is_deleted = 0)
                                                        LEFT JOIN '.COURSES.' c ON (c.curs_id = ao.admoff_degree AND c.is_deleted = 0)
                                                        LEFT JOIN '.PROGRAMS.' p ON (p.prg_id  = ap.id_prg AND p.is_deleted = 0)'
                            ,'where' 		    =>	array( 
                                                        'ao.is_deleted' 	    => '0'
                                                        )
                            ,'search_by'        =>  ' AND (ap.program LIKE "%'.$data_arr['query'].'%" 
                                                                    OR 
                                                        mt.mas_name LIKE "%'.$data_arr['query'].'%" 
                                                                    OR 
                                                        c.curs_name LIKE "%'.$data_arr['query'].'%")'
                            ,'order_by'     =>  'ao.admoff_id'
                            ,'return_type'	=>	'all'
                        );
    $ADMISSION_OFFERING = $dblms->getRows(ADMISSION_OFFERING.' ao', $condition);

    foreach($ADMISSION_OFFERING AS $key => $val):
        $photo = SITE_URL.'uploads/images/default_curs.jpg';
        if($val['prg_name']){
            $type       = '1';
            $id         = $val['id'];
            $name       = $val['prg_name'];
            $file_url   = SITE_URL.'uploads/images/programs/'.$val['prg_photo'];
        }elseif($val['mas_name']){
            $type       = '2';
            $id         = $val['mas_id'];
            $name       = $val['mas_name'];
            $file_url   = SITE_URL.'uploads/images/admissions/master_track/'.$val['mas_photo'];
        }elseif($val['curs_name']){
            $type       = '3';
            $id       	= $val['curs_id'];
            $name       = $val['curs_name'];
            $file_url   = SITE_URL.'uploads/images/courses/'.$val['curs_photo'];
        }
        if (check_file_exists($file_url)) {
            $photo = $file_url;
        }

        $dataResult['id']			= $id;
        $dataResult['type'] 		= $type;
        $dataResult['type_name']	= get_offering_type($type);
        $dataResult['name'] 		= html_entity_decode($name);
        $dataResult['photo'] 		= $photo;

        array_push($saerch_results, $dataResult);
    endforeach;

    $rowjson['saerch_results']		= $saerch_results;
} 