<?php

	global $breadCrumbs;
	
	$module = mLogic::$urlVariables['admin'];
	$display = ''; 
	
	require_once 'db/AdminDB.php';
	
	templater::assign('includeMainMenu', true);
	
	$breadCrumbs[] = new BreadCrumb("Админка", '/admin');
	
	
	if($module == 'feedback')
	{
		$breadCrumbs[] = new BreadCrumb("фидбэк", '');
		$display = '';
		
		if($_GET['todo'])
		{
			switch ($_GET['todo'])
			{
				case "read":
					AdminDB::setFeedbakRead(mLogic::$urlVariables['feedbackid']);
					header('Location: http://' . GENERAL_DOMAIN . '/');
					die;
				
			}
		}
		
		$feedBack = AdminDB::getFeedBackByID(mLogic::$urlVariables['feedbackid']);

		templater::assign('feedback', $feedBack);
		
		templater::display('admin/feedback.html');
		return;
	}

	templater::assign('breadCrumbs', $breadCrumbs);		
		
	templater::display();
	
?>