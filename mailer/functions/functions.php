<?php
function get_PasswordVerify($password = '', $passwordMatching = '' , $saltMatching = '') {
	if (empty($passwordMatching) && empty($saltMatching)) {
		$array = array();
		$array['password'] = $password;
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$password = hash('sha256', $password . $salt);
		for ($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}
		$array['hashPassword'] 		= $password;
		$array['salt'] 				= $salt;
		return $array;
	} else if (!empty($password) && !empty($passwordMatching) && !empty($saltMatching)) {
		$password = hash('sha256', $password . $saltMatching);
		for ($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $saltMatching);
		}
		if ($password == $passwordMatching) {
			return true;
		} else {
			return false;	
		}
	} else {
		return false;
	}
}
function get_dataHashing($str = '', $flag = true) {
    if (!empty($str)) {
    	$e_username 		= $str;
    	$e_key 				= "m^@c$&d#~l";
    	$e_chiper 			= "AES-128-CTR";
    	$e_iv 				= "4327890237234803";
    	$e_option			= 0;
    	return (($flag)?openssl_encrypt($e_username,$e_chiper,$e_key,$e_option,$e_iv):openssl_decrypt($e_username,$e_chiper,$e_key,$e_option,$e_iv));
    } else {
        return false;
    }
}
function get_dataHashingOnlyExp($str = '', $flag = true) {
    if (!empty($str)) {
        $e_key     = "m^@c$&d#~l";
        $e_chiper  = "AES-128-CTR";
        $e_iv      = "4327890237234803";
        $e_option  = 0;

        if ($flag) {
            // Encrypt and then encode to base64
            $encrypted = openssl_encrypt($str, $e_chiper, $e_key, $e_option, $e_iv);
            return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($encrypted));
        } else {
            // Decode from base64 and then decrypt
            $decoded = base64_decode(str_replace(['-', '_'], ['+', '/'], $str));
            return openssl_decrypt($decoded, $e_chiper, $e_key, $e_option, $e_iv);
        }
    } else {
        return false;
    }
}
function moduleName($flag = true) {
	$fileName = strstr(basename($_SERVER['REQUEST_URI']), '.php', true);
	if (gettype($flag) == 'string') {		
		$flag = str_replace('_',' ',$flag);
		$flag = str_replace('-',' ',$flag);
		$flag = ucwords(strtolower($flag));
		return $flag;
	}
	if ($flag) {
		return strtolower($fileName);
	} else {
		$fileName = str_replace('_',' ',$fileName);
		$fileName = str_replace('-',' ',$fileName);
		return ucwords(strtolower($fileName));
	}
}
function to_seo_url($str){
	// if($str !== mb_convert_encoding( mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
	// $str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
    $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
    $str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $str);
    $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
    $str = preg_replace(array('`[^a-z0-9]i','[-]+`'), '-', $str);
    $str = trim($str, '-');
	$str = strtolower($str);
    return $str;
}
function cleanvars($str){ 
	return is_array($str) ? array_map('cleanvars', $str) : str_replace("\\", "\\\\", htmlspecialchars( stripslashes($str), ENT_QUOTES)); 
}
?>