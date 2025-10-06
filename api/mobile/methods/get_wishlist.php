<?php
if ($data_arr['method_name'] == "get_wishlist") {

    $wishlist = [];
	if(isset($data_arr['std_id']) && $data_arr['std_id'] != '') {
		// Valid Student ID
		$std_id = $data_arr['std_id'];

        // 🔹 Query
        $condition = array (
                         'select' 		=>	'w.wl_id, w.id_curs, w.id_mas, w.id_ad_prg, w.id_type, c.curs_name, c.curs_photo, 
                                            mt.mas_name, mt.mas_photo, mt.mas_href, ap.program, p.prg_name, p.prg_photo, p.prg_href'
                        ,'join' 		=>	'LEFT JOIN '.COURSES.' c ON c.curs_id = id_curs
                                             LEFT JOIN '.MASTER_TRACK.' mt ON mt.mas_id = w.id_mas
                                             LEFT JOIN '.PROGRAMS.' p ON p.prg_id = w.id_ad_prg
                                             LEFT JOIN '.ADMISSION_PROGRAMS.' ap ON ap.id = w.id_ad_prg' 
                        ,'where' 		=>	array( 
                                                    'w.id_std'    => cleanvars($std_id) 
                                                ) 
                        ,'order_by'     =>  'w.wl_id DESC'
                        ,'return_type'	=>	'all'
                    ); 
        $WISHLIST = $dblms->getRows(WISHLIST.' w', $condition,$sql);

        if ($WISHLIST) {
            foreach ($WISHLIST as $row) {
                $type_id = $row['id_type'];
                $type_name = get_offering_type($row['id_type']);
                $idWish = '';
                $name = '';
                $photo = SITE_URL . 'uploads/images/default_curs.jpg';
                

                // 🔹 Detect type & assign details
                if ($row['id_type'] == 1) {
                    $idWish = $row['id_ad_prg'];
                    $name   = $row['prg_name'];
                    if(!empty($row['prg_photo'])){
                        $photo = SITE_URL . 'uploads/images/programs/' . $row['prg_photo'];
                    }
                } elseif ($row['id_type'] == 2) {
                    $idWish = $row['id_mas'];
                    $name   = $row['mas_name'];
                    if(!empty($row['mas_photo'])){
                        $photo = SITE_URL . 'uploads/images/admissions/master_track/' . $row['mas_photo'];
                    }
                } elseif ($row['id_type'] == 3 || $row['id_type'] == 4) {
                    $idWish = $row['id_curs'];
                    $name   = $row['curs_name'];
                    if(!empty($row['curs_photo'])){
                        $photo = SITE_URL . 'uploads/images/courses/' . $row['curs_photo'];
                    }
                }

                $wishlist[] = array(
                    'wishlist_id'  => $row['wl_id'],
                    'item_id'      => $idWish,
                    'item_name'    => $name,
                    'type_id'      => $type_id,
                    'type_name'    => $type_name,
                    'photo'        => $photo
                );
            }

            $rowjson['success'] = 1;
            $rowjson['MSG'] = 'Wishlist fetched successfully.';
        } else {
            $rowjson['success'] = 0;
            $rowjson['MSG'] = 'No wishlist found.';
            $rowjson['wishlist'] = [];
        }
	} else {
		$rowjson['success'] = 0;
		$rowjson['MSG'] = 'Student ID is required.';
	}
	$rowjson['wishlist'] = $wishlist;

	
}
