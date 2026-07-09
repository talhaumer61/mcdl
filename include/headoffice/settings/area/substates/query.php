<?php
    // ADD SUBSTATE
	if(isset($_POST['submit_add'])) {

		$condition	=	array ( 
								'select' 	=> "substate_id",
								'where' 	=> array( 
														'substate_name'	=>	cleanvars($_POST['substate_name'])
														,'is_deleted'	=>	'0'	
													),
								'return_type' 	=> 'count' 
							  ); 
		if($dblms->getRows(SUB_STATES, $condition)) {
			sessionMsg('Error','Record Already Exists.','danger');
			header("Location: substates.php", true, 301);
			exit();
		}else{

			$values = array(
							'substate_name'			=>    cleanvars($_POST['substate_name'])
							,'substate_latitude'    =>    cleanvars($_POST['substate_latitude'])
							,'substate_longitude'   =>    cleanvars($_POST['substate_longitude'])
							,'id_country'           =>    cleanvars($_POST['id_country'])
							,'id_state'             =>    cleanvars($_POST['id_state'])
							,'substate_status'      =>    cleanvars($_POST['substate_status'])
							,'substate_ordering'    =>    cleanvars($_POST['substate_ordering'])
							,'id_added'             =>    cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'        	=>    date('Y-m-d G:i:s')
						   );   
			$sqllms  		= 	$dblms->insert(SUB_STATES, $values);
			$substate_id    =	$dblms->lastestid();

			if($sqllms) { 
				$latestID   =	$dblms->lastestid();
				sendRemark('Substate Added ID:'.$latestID, '1');
				sessionMsg('Successfully', 'Record Successfully Added.', 'success');
				header("Location: substates.php", true, 301);
				exit();
			}
		}
	}

    // EDIT SUBSTATE
	if(isset($_POST['submit_edit'])) {
		
		$condition	=	array ( 
								'select' 	=> "substate_id",
								'where' 	=> array( 
														'substate_name'	=>	cleanvars($_POST['substate_name'])
														,'is_deleted'	=>	'0'	
													),
								'not_equal' 	=> array( 
															'substate_id'	=>	cleanvars($_POST['substate_id'])
														),
								'return_type' 	=> 'count' 
							  ); 
		if($dblms->getRows(SUB_STATES, $condition)) {
			sessionMsg('Error','Record Already Exists.','danger');
			header("Location: substates.php", true, 301);
			exit();
		}else{
			$values = array(
								'substate_name'         =>    cleanvars($_POST['substate_name'])
								,'substate_latitude'    =>    cleanvars($_POST['substate_latitude'])
								,'substate_longitude'	=>    cleanvars($_POST['substate_longitude'])
								,'id_country'           =>    cleanvars($_POST['id_country'])
								,'id_state'             =>    cleanvars($_POST['id_state'])
								,'substate_status'      =>    cleanvars($_POST['substate_status'])
								,'substate_ordering'    =>    cleanvars($_POST['substate_ordering'])
								,'id_modify'            =>    cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,'date_modify'          =>    date('Y-m-d G:i:s')

							);  
			$sqllms = $dblms->Update(SUB_STATES , $values , "WHERE substate_id  = '".cleanvars($_POST['substate_id'])."'");

			if($sqllms) { 
				$latestID = $_POST['substate_id'];
				sendRemark('Substate Updates ID:'.cleanvars($latestID), '2');
				sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
				header("Location: substates.php", true, 301);
				exit();
			}

		}
	}

	// DELETE SUBSTATE
	if(isset($_GET['deleteid'])) {
		
		$values = array(
						'id_deleted'	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'	=>	'1'
						,'ip_deleted'	=>	cleanvars($ip)
						,'date_deleted'	=>	date('Y-m-d G:i:s')
					   );   
		$sqlDel = $dblms->Update(SUB_STATES , $values , "WHERE substate_id  = '".cleanvars($_GET['deleteid'])."'");

		if($sqlDel) { 
			sendRemark('Deleted Substate #:'.cleanvars($_GET['deleteid']), '3');
			sessionMsg('Warning', 'Record Successfully Deleted.', 'warning');
			header("Location: substates.php", true, 301);
			exit();
		}
	}
?>