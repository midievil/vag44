<?php

	global $breadCrumbs;
	
	$module = mLogic::$urlVariables['admin'];
	$display = ''; 
	
	templater::assign('includeMainMenu', true);
	
	$breadCrumbs[] = new BreadCrumb("Админка", '/admin');
	
	if($module == 'feedback')
	{
		$breadCrumbs[] = new BreadCrumb("Нам фидбэк", '');
		$display = '';
	}

	templater::assign('breadCrumbs', $breadCrumbs);		
		
	templater::display();
	
?>