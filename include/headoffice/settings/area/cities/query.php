<?php
    // ADD RECORD
	if(isset($_POST['submit_add'])) {

		$condition	=	array ( 
								'select' 	=>	'city_id',
								'where' 	=>	array( 
														'city_name'	=>	cleanvars($_POST['city_name'])
														,'is_deleted'	=>	'0'
													),
								'return_type' 	=> 'count'
							); 
		if($dblms->getRows(CITIES, $condition)) {
			sessionMsg('Error','Record Already Exists.','danger');
			header("Location: cities.php", true, 301);
			exit();
		}else{
			$values = array(
								'city_name'			=>	cleanvars($_POST['city_name'])
								,'city_latitude'	=>	cleanvars($_POST['city_latitude'])
								,'city_longitude'	=>	cleanvars($_POST['city_longitude'])
								,'city_codedigit'	=>	cleanvars($_POST['city_codedigit'])
								,'city_codealpha'	=>	cleanvars($_POST['city_codealpha'])
								,'id_country'		=>	cleanvars($_POST['id_country'])
								,'id_state'			=>	cleanvars($_POST['id_state'])
								,'id_substate'		=>	cleanvars($_POST['id_substate'])
								,'city_status'		=>	cleanvars($_POST['city_status'])
								,'city_ordering'	=>	cleanvars($_POST['city_ordering'])
								,'id_added'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,'date_added'		=>	date('Y-m-d G:i:s')
							); 
			
			$sqllms		= 	$dblms->insert(CITIES, $values);
			$city_id	=	$dblms->lastestid();

			if($sqllms) { 
				
			$latestID   =	$dblms->lastestid();
				sendRemark('City Added ID:'.$latestID, '1');
				sessionMsg('Successfully', 'Record Successfully Added.', 'success');
				header("Location: cities.php", true, 301);
				exit();
			}
		}
	}

    // EDIT RECORD
	if(isset($_POST['submit_edit'])) {

		$condition	=	array ( 
								'select' 	=> "city_id",
								'where' 	=> array( 
														'city_name'	=>	cleanvars($_POST['city_name'])
														,'is_deleted'	=>	'0'
													),
								'not_equal' 	=> array( 
														'city_id'		=>	cleanvars($_POST['city_id'])
													),					
								'return_type' 	=> 'count' 
							  ); 
		if($dblms->getRows(CITIES, $condition)) {
			sessionMsg('Error','Record Already Exists.','danger');
			header("Location: cities.php", true, 301);
			exit();
		}else{
			$values = array(
								'city_name'			=>	cleanvars($_POST['city_name'])
								,'city_latitude'	=>	cleanvars($_POST['city_latitude'])
								,'city_longitude'	=>	cleanvars($_POST['city_longitude'])
								,'city_codedigit'	=>	cleanvars($_POST['city_codedigit'])
								,'city_codealpha'	=>	cleanvars($_POST['city_codealpha'])
								,'id_country'		=>	cleanvars($_POST['id_country'])
								,'id_state'			=>	cleanvars($_POST['id_state'])
								,'id_substate'		=>	cleanvars($_POST['id_substate'])
								,'city_status'		=>	cleanvars($_POST['city_status'])
								,'city_ordering'	=>	cleanvars($_POST['city_ordering'])
								,'id_modify'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,'date_modify'		=>	date('Y-m-d G:i:s')

							);   
			$sqllms = $dblms->Update(CITIES , $values , "WHERE city_id  = '".cleanvars($_POST['city_id'])."'");

			if($sqllms) { 
							
			$latestID = $_POST['city_id'];
				sendRemark('City Updates ID:'.cleanvars($latestID), '2');
				sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
				header("Location: cities.php", true, 301);
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
		$sqlDel = $dblms->Update(CITIES , $values , "WHERE city_id  = '".cleanvars($_GET['deleteid'])."'");

		if($sqlDel) { 
			sendRemark('Deleted City #:'.cleanvars($_GET['deleteid']), '3');
			sessionMsg('Warning', 'Record Successfully Deleted.', 'warning');
			header("Location: cities.php", true, 301);
			exit();
		}
	}
?>