<?php
if ($data_arr['method_name'] == "update_social_links") { 

    if (!empty($data_arr['std_id']) && $data_arr['std_id'] > 0) {

        // Allowed platforms
        $platforms = ['facebook','twitter','instagram','linkedin','youtube'];

        $social = [];
        $i=0;
        foreach ($platforms as $platform) {
            $i++;
            $social[$i] = trim($data_arr[$platform]);
        }

        $values = [
            'std_sociallinks' => json_encode($social, JSON_UNESCAPED_UNICODE),
            'id_modify'       => cleanvars($data_arr['std_id']),
            'date_modify'     => date('Y-m-d H:i:s')
        ];

        $sql = $dblms->Update(STUDENTS, $values, "WHERE std_id = '".cleanvars($data_arr['std_id'])."'");

        if ($sql) {
            $rowjson['success'] 		= 1;
            $rowjson['MSG'] 			= 'Social Links Updated Successfully';
        } else {
            $rowjson['success'] 		= 0;
            $rowjson['MSG'] 			= 'Failed to update social links. Please try again.';
        }
    } else {
        $rowjson['success'] 		= 0;
        $rowjson['MSG'] 			= 'Student ID is required';
    }
}
?>
