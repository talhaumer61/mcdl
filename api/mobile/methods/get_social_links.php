<?php
if ($data_arr['method_name'] == "get_social_links") { 

    $rowjson = [
        'success' => 0,
        'MSG'     => 'Invalid Student ID',
        'social_links' => []
    ];

    if (!empty($data_arr['std_id']) && $data_arr['std_id'] > 0) {
        $condition = [ 
            'select'      => 'std_sociallinks',
            'where'       => ['std_id' => cleanvars($data_arr['std_id'])],
            'return_type' => 'single'
        ]; 
        $row = $dblms->getRows(STUDENTS, $condition);
        $data = $row['std_sociallinks'] ?? '';

        // Pre-fill all platforms as empty
        $social_links = [
            'facebook'  => '',
            'twitter'   => '',
            'instagram' => '',
            'linkedin'  => '',
            'youtube'   => ''
        ];

        if ($data !== '') {
            // Extract all URLs in one go
            if (preg_match_all('/https?:\/\/[^\"]+/', $data, $matches)) {
                $links = $matches[0];

                // Map domains to platforms
                $map = [
                    'facebook.com'  => 'facebook',
                    'instagram.com' => 'instagram',
                    'twitter.com'   => 'twitter',
                    'linkedin.com'  => 'linkedin',
                    'youtube.com'   => 'youtube',
                ];

                foreach ($links as $url) {
                    foreach ($map as $domain => $platform) {
                        if (stripos($url, $domain) !== false) {
                            $social_links[$platform] = $url;
                            break; // stop checking once matched
                        }
                    }
                }
            }
        }

        $rowjson = [
            'success'      => 1,
            'MSG'          => 'Social Links Fetched',
            'social_links' => $social_links
        ];
    }
}
?>
