<?php
if($data_arr['method_name'] == "verify_otp") {
	if(isset($data_arr['email']) && isset($data_arr['otp']) && !empty($data_arr['email']) && !empty($data_arr['otp'])){			
		$condition = array(
							 'select'       =>  'cod_code, cod_time, is_used'
							,'where'        =>  array(
														 'adm_email'    =>	cleanvars($data_arr['email'])
														,'is_used'      =>	0
													)
							,'order_by'     =>  'cod_id DESC'
							,'return_type'  =>  'single'
		);
		$VERIFICATION_CODES = $dblms->getRows(VERIFICATION_CODES, $condition);
		if ($VERIFICATION_CODES['cod_code']) {
			$giveTime       = date('G:i:s', strtotime($VERIFICATION_CODES['cod_time'] . ' +180 seconds'));
			$takkenTime     = date('G:i:s');
			if ($giveTime >= $takkenTime) {
				if ($VERIFICATION_CODES['cod_code'] == cleanvars($data_arr['otp'])) {
					$DELETE_PREVIOUS = $dblms->querylms("DELETE FROM ".VERIFICATION_CODES." WHERE adm_email = '".cleanvars($data_arr['email'])."'");
			
					// response
					$rowjson['success'] = 1;
					$rowjson['MSG'] 	= 'OTP verified';
				} else {
					// response
					$rowjson['success'] = 0;
					$rowjson['MSG'] 	= 'OTP Error';
				}
			} else {
				// response
				$rowjson['success'] = 0;
				$rowjson['MSG'] 	= 'OTP Expired';
			}
		} else {			
			// response
			$rowjson['success'] = 0;
			$rowjson['MSG'] 	= 'OTP not found. request again!';
		}
	}
} 