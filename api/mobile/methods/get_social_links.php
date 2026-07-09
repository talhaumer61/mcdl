<?php
if ($data_arr['method_name'] == "get_social_links") { 

    $social_links = [
        'facebook'  => '',
        'twitter'   => '',
        'linkedin'  => '',
        'instagram' => '',
        'youtube'   => ''
    ];

    $rowjson = [
        'success' => 0,
        'MSG'     => 'Invalid Student ID'
    ];

    if (!empty($data_arr['std_id']) && $data_arr['std_id'] > 0) {
        $condition = [ 
            'select'      => 'std_sociallinks',
            'where'       => ['std_id' => cleanvars($data_arr['std_id'])],
            'return_type' => 'single'
        ]; 
        $row = $dblms->getRows(STUDENTS, $condition);

        $social	= json_decode(html_entity_decode($row['std_sociallinks']), true);

        foreach ($social as $key => $value) {
            $social_links[strtolower(get_social_links($key)['name'])] = $value;
        }

        $rowjson = [
            'success'      => 1,
            'MSG'          => 'Social Links Fetched'
        ];
    }
    $rowjson['social_links'] = $social_links;
}
?>
