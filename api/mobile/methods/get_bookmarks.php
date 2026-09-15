<?php
if($data_arr['method_name'] == "get_bookmarks") { 

    $bookmarks	=   array();

    $condition = array (
                            'select' 		=>	'w.wl_id, w.id_curs, w.id_mas, w.id_ad_prg, c.curs_name, c.curs_photo, c.curs_href, mt.mas_name, mt.mas_photo, mt.mas_href, ap.program, p.prg_name, p.prg_photo, p.prg_href'
                            ,'join' 		=>	'LEFT JOIN '.COURSES.' c ON c.curs_id = w.id_curs
                                                LEFT JOIN '.MASTER_TRACK.' mt ON mt.mas_id = w.id_mas
                                                LEFT JOIN '.PROGRAMS.' p ON p.prg_id = w.id_ad_prg
                                                LEFT JOIN '.ADMISSION_PROGRAMS.' ap ON ap.id = w.id_ad_prg' 
                            ,'where' 		=>	array( 
                                                        'w.id_std'    => cleanvars($data_arr['user_id']) 
                                                    ) 
                            ,'order_by'     =>  'w.wl_id DESC'
                            ,'return_type'	=>	'all'
                        ); 
    $WISHLIST = $dblms->getRows(WISHLIST.' w', $condition);

    if(!empty($WISHLIST)):

        foreach ($WISHLIST as $row) {
            $photo = SITE_URL.'uploads/images/default_curs.jpg';
            if($row['prg_name']){
                $type       = '1';
                $href       = 'degree-detail/'.$row['prg_href'];
                $idWish     = $row['id_ad_prg'];
                $name       = $row['prg_name'];
                $duration   = $row['prg_duration']. ' Year';
                $price   	= 'Rs. '.number_format($row['prg_total_package']);
                $file_url   = SITE_URL.'uploads/images/programs/'.$row['prg_photo'];
            }elseif($row['mas_name']){
                $type       = '2';
                $href       = 'master-track-detail/'.$row['mas_href'];
                $idWish     = $row['id_mas'];
                $name       = $row['mas_name'];
                $duration   = $row['mas_duration']. ' Month';
                $price   	= 'Rs. '.number_format($row['mas_amount']);
                $file_url   = SITE_URL.'uploads/images/admissions/master_track/'.$row['mas_photo'];
            }elseif($row['curs_name']){
                $type       = '3';
                $href       = 'courses/'.$row['curs_href'];
                $idWish     = $row['id_curs'];
                $name       = $row['curs_name'];
                $duration   = $row['curs_duration']. ' Week';
                $price   	= 'Rs. '.number_format($row['admoff_amount']);
                $file_url   = SITE_URL.'uploads/images/courses/'.$row['curs_photo'];
            }
            if (check_file_exists($file_url)) {
                $photo = $file_url;
            }

            $dataBookmark['id_user']	= $data_arr['user_id'];
            $dataBookmark['id_type'] 	= $type;
            $dataBookmark['id'] 		= $idWish;
            $dataBookmark['name'] 		= $name;
            $dataBookmark['category'] 	= get_offering_type($type);
            $dataBookmark['rating'] 	= "4.".rand(0, 9);
            $dataBookmark['duration'] 	= $duration;
            $dataBookmark['price'] 		= $price;
            $dataBookmark['photo'] 		= $photo;

            array_push($bookmarks, $dataBookmark);
        }
    endif;
    $rowjson['userbookmarks']	= $bookmarks;
} 