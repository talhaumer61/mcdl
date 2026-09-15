<?php
    // ADD CURRENCY
	if(isset($_POST['submit_add'])) {

		$condition	=	array ( 
								'select' 	=> "currency_id",
								'where' 	=> array( 
														'currency_name'	=>	cleanvars($_POST['currency_name'])
														,'is_deleted'	=>	'0'	
													),
								'return_type' 	=> 'count' 
							  ); 
		if($dblms->getRows(CURRENCIES, $condition)) {
			sessionMsg('Error', 'Record Already Exists.', 'danger');
			header("Location: currencies.php", true, 301);
			exit();
		}else{
			$values = array(
							'currency_name'             =>	cleanvars($_POST['currency_name'])
							,'currency_code'  		    =>	cleanvars($_POST['currency_code'])
							,'currency_symbol'		    =>	cleanvars($_POST['currency_symbol'])
							,'currency_position'  		=>	cleanvars($_POST['currency_position'])
							,'currency_fractionalunits'	=>	cleanvars($_POST['currency_fractionalunits'])
							,'currency_status'          =>	cleanvars($_POST['currency_status'])
							,'currency_ordering'        =>	cleanvars($_POST['currency_ordering'])
							,'id_added'          	    =>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'          	    =>	date('Y-m-d G:i:s')
						   );   
			$sqllms  	=	$dblms->insert(CURRENCIES, $values);

			if($sqllms) {
				$latestID   =	$dblms->lastestid();
				sendRemark('Currency Added ID:'.$latestID, '1');
				sessionMsg('Successfully', 'Record Successfully Added.', 'success');
				header("Location: currencies.php", true, 301);
				exit();
			}
		}
	}

    // EDIT CURRENCY
	if(isset($_POST['submit_edit'])) {

		$condition	=	array ( 
								'select' 	=> "currency_id",
								'where' 	=> array( 
														'currency_name'	=>	cleanvars($_POST['currency_name'])
														,'is_deleted'	=>	'0'	
													),
								'not_equal' => array( 
														'currency_id' => cleanvars($_POST['currency_id']) 
													),
								'return_type' 	=> 'count' 
							  ); 
		if($dblms->getRows(CURRENCIES, $condition)) {
			sessionMsg('Error', 'Record Already Exists.', 'danger');
			header("Location: currencies.php", true, 301);
			exit();
		}else{

			$values	=	array(
								'currency_name'             =>	cleanvars($_POST['currency_name'])
								,'currency_code'  		    =>	cleanvars($_POST['currency_code'])
								,'currency_symbol'		    =>	cleanvars($_POST['currency_symbol'])
								,'currency_position'  		=>	cleanvars($_POST['currency_position'])
								,'currency_fractionalunits'	=>	cleanvars($_POST['currency_fractionalunits'])
								,'currency_status'          =>	cleanvars($_POST['currency_status'])
								,'currency_ordering'        =>	cleanvars($_POST['currency_ordering'])
								,'id_modify'          		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,'date_modify'				=>	date('Y-m-d G:i:s')

						    );   
			$sqllms = $dblms->Update(CURRENCIES , $values , "WHERE currency_id  = '".cleanvars($_POST['currency_id'])."'");

			if($sqllms) { 
				$latestID = $_POST['currency_id'];
				sendRemark('Currency Updates ID:'.cleanvars($latestID), '2');
				sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
				header("Location: currencies.php", true, 301);
				exit();
			}

		}
	}

	// DELETE CURRENCY
	if(isset($_GET['deleteid'])) {
		
		$values = array(
						'id_deleted'	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'	=>	'1'
						,'ip_deleted'	=>	cleanvars($ip)
						,'date_deleted'	=>	date('Y-m-d G:i:s')
					   );   
		$sqlDel = $dblms->Update(CURRENCIES , $values , "WHERE currency_id  = '".cleanvars($_GET['deleteid'])."'");

		if($sqlDel) { 
			sendRemark('Deleted Currency #:'.cleanvars($_GET['deleteid']), '3');
			sessionMsg('Warning', 'Record Successfully Deleted.', 'warning');
			header("Location: currencies.php", true, 301);
			exit();
		}
	}
?>