<?php
// DELETE RECORD
if(isset($_GET['deleteid']) && isset($_GET['std_loginid'])) {
	$latestId = $_GET['deleteid'];

	$values = array(
						 'id_deleted'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'		=>	'1'
						,'ip_deleted'		=>	cleanvars($ip)
						,'date_deleted'		=>	date('Y-m-d G:i:s')
					);   
	$sqlDel = $dblms->Update(STUDENTS , $values , "WHERE std_id  = '".cleanvars($latestId)."'");
	if($sqlDel) { 
		$values = array(
							 'id_deleted'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'is_deleted'		=>	'1'
							,'ip_deleted'		=>	cleanvars($ip)
							,'date_deleted'		=>	date('Y-m-d G:i:s')
						);   
		$sqlDelAdm = $dblms->Update(ADMINS , $values , "WHERE adm_id  = '".cleanvars($_GET['std_loginid'])."'");
		if($sqlDelAdm){
			sendRemark('Student Login Deleted', '3', cleanvars($_GET['std_loginid']));
		}

		sendRemark(moduleName(false).' Deleted', '3', cleanvars($latestId));
		sessionMsg('Warning', 'Record Successfully Deleted.', 'warning');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}
}
?>