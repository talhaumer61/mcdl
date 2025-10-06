<?php
// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	if($_POST['due_date'] != ''){
		$values = array(
							 'due_date'			=> date('Y-m-d',strtotime($_POST['due_date']))
							,'date_modify'		=> date('Y-m-d G:i:s')
							,'id_modify'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						);
		$sqllms = $dblms->Update(CHALLANS, $values , "WHERE challan_id  = '".cleanvars($_POST['challan_id'])."'");

		if($sqllms){
			// REMAKRS
			sendRemark("Challan Due date updated", '2', $_POST['challan_id']);
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// UPDATE PAID / UNPAID
if(isset($_POST['update_challan'])) {
	// APPROVE
	if($_POST['status'] == '1'){
		$values = array(
							 'status'			=> 1
							,'paid_amount'		=> cleanvars($_POST['total_amount'])
							,'paid_date'		=> date('Y-m-d')
							,'date_modify'		=> date('Y-m-d G:i:s')
							,'id_modify'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						);
		$sqllms = $dblms->Update(CHALLANS, $values , "WHERE challan_id  = '".cleanvars($_POST['challan_id'])."'");

		if($sqllms){
			$values = array(
								 'secs_status'			=> '1'
								,'id_modify'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,'date_modify'			=> date('Y-m-d G:i:s')
							); 
			$sqllms = $dblms->Update(ENROLLED_COURSES, $values , "WHERE secs_id IN (".cleanvars($_POST['id_enroll']).") ");

			$values = array(
								 'trans_status'			=> 1
								,'id_std'               => cleanvars($_POST['id_std'])
								,'trans_no'				=> cleanvars($_POST['challan_no'])
								,'id_enroll'			=> cleanvars($_POST['id_enroll'])
								,'trans_amount'         => cleanvars($_POST['total_amount'])
								,'date'           		=> date('Y-m-d')
								,'id_added'             => cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,'date_added'           => date('Y-m-d G:i:s')
							); 
			$sqllms = $dblms->insert(TRANSACTION, $values);

			// REMAKRS
			sendRemark("Challan Paid", '2', $_POST['challan_id']);
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}

	}	
	// REJECT
	elseif($_POST['status'] == '3'){
		$values = array(
							 'status'			=> 3
							,'date_modify'		=> date('Y-m-d G:i:s')
							,'id_modify'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						);
		$sqllms = $dblms->Update(CHALLANS, $values , "WHERE challan_id  = '".cleanvars($_POST['challan_id'])."'");

		if($sqllms){
			$values = array(
								 'secs_status'			=> '3'
								,'id_modify'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,'date_modify'			=> date('Y-m-d G:i:s')
							); 
			$sqllms = $dblms->Update(ENROLLED_COURSES, $values , "WHERE secs_id IN (".cleanvars($_POST['id_enroll']).") ");

			// REMAKRS
			sendRemark("Challan marked Unpaid", '2', $_POST['challan_id']);
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {
	$latestID = $_GET['deleteid'];
	$values = array(
						 'id_deleted'	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'	=>	'1'
						,'ip_deleted'	=>	cleanvars($ip)
						,'date_deleted'	=>	date('Y-m-d G:i:s')
					);
	$sqlDel = $dblms->Update(CHALLANS, $values , "WHERE challan_id = '".cleanvars($latestID)."'");

	if($sqlDel) {
		sendRemark(moduleName(false).' Deleted', '3', $latestID);
		sessionMsg("Warning", "Record Successfully Deleted.", "warning");
		exit();
		header("Location: ".moduleName().".php", true, 301);
	}
}
?>