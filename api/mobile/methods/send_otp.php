<?php
if($data_arr['method_name'] == "send_otp") {
	if(isset($data_arr['email'])) {
		$condition = array(
							 'select'       =>  'adm_id'
							,'where'        =>  array(
														 'is_deleted'       => 0
														,'adm_email'        => $data_arr['email']
													)
							,'return_type'  =>  'single'
						);
		if($dblms->getRows(ADMINS, $condition)) {			
			$rowjson['success'] = 0;	
			$rowjson['MSG'] 	= 'Email already in use';
		} else {
			// SEND VERIFICATION CODE
			$DELETE_PREVIOUS = $dblms->querylms("DELETE FROM ".VERIFICATION_CODES." WHERE adm_email = '".cleanvars($data_arr['email'])."'");
			if ($DELETE_PREVIOUS) {
				$OTP_CODE = get_VerificationCode();
				$values = array(
									 'adm_email'    =>	cleanvars($data_arr['email'])
									,'cod_code'     =>	$OTP_CODE
									,'is_used'      =>	0
									,'cod_time'     =>	date('G:i:s')
								); 
				$VERIFICATION_CODES = $dblms->insert(VERIFICATION_CODES, $values);
				if ($VERIFICATION_CODES) {
					$customBody = '
						<table style="font-family: \'Open Sans\', Arial, sans-serif; border-radius: 50px; width: 900px; max-width: 900px;" align="center">
							<thead>
								<tr>
									<td align="center" style="background-color: #ebeef5;">
										<table style="font-family: \'Open Sans\', Arial, sans-serif; background-color: #ebeef5; width: 900px; max-width: 900px;" align="center">
											<thead>
												<tr>
													<td align="center" style="background-color: #ebeef5; padding: 20px;">
														<a href="'.SITE_URL.'" target="_blank">
															<img src="'.SITE_URL.'assets/img/logo/logo.png" width="168" height="auto" style="display: block; width: 168px; height: auto;">
														</a>
													</td>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td align="center" style="background-color: #ebeef5; padding: 20px;">
														<table>
															<tbody>
																<tr>
																	<td style="padding: 10px;">Dear Student,</td>
																</tr>
																<tr>
																	<td style="padding: 10px;">
																		Thank you for registering with '.SITE_NAME.'. Please use the following 4-digit code to verify your email address:
																	</td>
																</tr>
																<tr>
																	<td style="padding: 10px;">
																		<div style="text-align: center; margin-top: 30px; margin-bottom: 20px;">
																			<table align="center">
																				<tr>';
																					for ($i = 0; $i < 4; $i++) {
																						$customBody .= '
																						<td style="background-color: green; color: white; width: 50px; height: 50px; border-radius: 5px; text-align: center; font-size: 24px; font-weight: bold; user-select: none;">
																							'.substr($OTP_CODE, $i, 1).'
																						</td>';
																						if ($i < 5) {
																							$customBody .= '
																							<td style="width: 10px;"></td>';
																						}
																					}
																					$customBody .= '
																				</tr>
																			</table>
																		</div>
																	</td>
																</tr>
																<tr>
																	<td style="padding: 10px;">This code will expire in 3 minutes. If you did not request this, please ignore this email.</td>
																</tr>
																<tr>
																	<td style="padding: 10px;">Sincerely,<br>'.TITLE_HEADER.'</td>
																</tr>
																<tr>
																	<td align="center" style="padding: 10px;">
																		<br>
																		<br>© '.date('Y').' '.TITLE_HEADER.'
																		<br>'.SITE_ADDRESS.'
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</thead>
						</table>';
					get_SendMail([
						'sender'        => SMTP_EMAIL,
						'senderName'    => SITE_NAME,
						'receiver'      => cleanvars($data_arr['email']),
						'receiverName'  => 'Student',
						'subject'       => "Account verification mail (OTP: ".$OTP_CODE.")",
						'body'          => $customBody,
						'tokken'        => SMTP_TOKEN,
					], 'send-mail');

					// response
					$rowjson['success'] 		= 1;
					$rowjson['MSG'] 			= 'OTP successfully sent to email!';
				} else {
					// response
					$rowjson['success'] 		= 0;
					$rowjson['MSG'] 			= 'Something went wrong. OTP not sent!';
				}
			} else {
				// response
				$rowjson['success'] 		= 0;
				$rowjson['MSG'] 			= 'Something went wrong';
			}			
		}
	} else {
		// response
		$rowjson['success'] 		= 0;
		$rowjson['MSG'] 			= 'Email is required!';
	}	
} 