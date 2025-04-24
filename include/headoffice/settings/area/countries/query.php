<?php
    // ADD RECORD
	if(isset($_POST['submit_add'])) {

		$condition	=	array ( 
								'select' 	=> "country_id",
								'where' 	=> array( 
														'country_name'		=>	cleanvars($_POST['country_name'])
														,'is_deleted'	=>	'0'	
													),
								'return_type' 	=> 'count' 
							  ); 
		if($dblms->getRows(COUNTRIES, $condition)) {
			sessionMsg('Error','Record Already Exists.','danger');
			header("Location: countries.php", true, 301);
			exit();
		}else{
		
			$values = array(
								'country_name'			=>	cleanvars($_POST['country_name'])
								,'country_callingcode'	=>	cleanvars($_POST['country_callingcode'])
								,'country_iso2digit'	=>	cleanvars($_POST['country_iso2digit'])
								,'country_iso3digit'	=>	cleanvars($_POST['country_iso3digit'])
								,'country_latitude'		=>	cleanvars($_POST['country_latitude'])
								,'country_longitude'	=>	cleanvars($_POST['country_longitude'])
								,'id_timezone'			=>	cleanvars($_POST['id_timezone'])
								,'id_currency'			=>	cleanvars($_POST['id_currency'])
								,'id_region'			=>	cleanvars($_POST['id_region'])
								,'country_status'		=>	cleanvars($_POST['country_status'])
								,'country_ordering'		=>	cleanvars($_POST['country_ordering'])
								,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,'date_added'			=>	date('Y-m-d G:i:s')
							);   
			$sqllms  	 = $dblms->insert(COUNTRIES, $values);

			if($sqllms) { 
				$latestID   =	$dblms->lastestid();
				sendRemark('Country Added ID:'.$latestID, '1');
				sessionMsg('Successfully', 'Record Successfully Added.', 'success');
				header("Location: countries.php", true, 301);
				exit();
			}
		}
	}

    // EDIT RECORD
	if(isset($_POST['submit_edit'])) {

		$condition	=	array ( 
								'select' 	=> "country_id",
								'where' 	=> array( 
														'country_name'		=>	cleanvars($_POST['country_name'])
														,'is_deleted'	=>	'0'	
													),
								'not_equal'	=>	array( 
														'country_id'		=>	cleanvars($_POST['country_id'])	
													),
								'return_type' 	=> 'count' 
							  ); 
		if($dblms->getRows(COUNTRIES, $condition)) {
			sessionMsg('Error','Record Already Exists.','danger');
			header("Location: countries.php", true, 301);
			exit();
		}else{
		
			$values = array(
								'country_name'			=>	cleanvars($_POST['country_name'])
								,'country_callingcode'	=>  cleanvars($_POST['country_callingcode'])
								,'country_iso2digit'	=>  cleanvars($_POST['country_iso2digit'])
								,'country_iso3digit'	=>  cleanvars($_POST['country_iso3digit'])
								,'country_latitude'		=>  cleanvars($_POST['country_latitude'])
								,'country_longitude'	=>  cleanvars($_POST['country_longitude'])
								,'id_timezone'			=>  cleanvars($_POST['id_timezone'])
								,'id_currency'			=>  cleanvars($_POST['id_currency'])
								,'id_region'			=>  cleanvars($_POST['id_region'])
								,'country_status'       =>  cleanvars($_POST['country_status'])
								,'country_ordering'		=>	cleanvars($_POST['country_ordering'])
								,'id_modify'          	=>  cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,'date_modify'        	=>  date('Y-m-d G:i:s')

						   );   

			$sqllms = $dblms->Update(COUNTRIES , $values , "WHERE country_id  = '".cleanvars($_POST['country_id'])."'");

			if($sqllms) { 
				$latestID = $_POST['country_id'];
				sendRemark('Country Updates ID:'.cleanvars($latestID), '2');
				sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
				header("Location: countries.php", true, 301);
				exit();
			}
		}
	}

	// DELETE RECORD
	if(isset($_GET['deleteid'])) {
		
		$values = array(
						'id_deleted'	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'	=>	'1'
						,'ip_deleted'	=>	cleanvars($ip)
						,'date_deleted'	=>	date('Y-m-d G:i:s')
					   );   
		$sqlDel = $dblms->Update(COUNTRIES , $values , "WHERE country_id  = '".cleanvars($_GET['deleteid'])."'");

		if($sqlDel) { 
			sendRemark('Deleted SKILLS #:'.cleanvars($_GET['deleteid']), '3');
			sessionMsg('Warning', 'Record Successfully Deleted.', 'warning');
			header("Location: countries.php", true, 301);
			exit();
		}
	}
?>