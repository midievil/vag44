<?php

	global $breadCrumbs;
	$breadCrumbs[] = new BreadCrumb('Пользователи', '');

	templater::assign('includeMainMenu', true);

	$usersCount = getUsersCount();
	templater::assign('usersCount', $usersCount);
	
	$paging = new Paging(20, $usersCount, 1, "showUsers(%pagenumber%)");	
	templater::assign('usersPaging', $paging);
	
	templater::assign('breadCrumbs', $breadCrumbs);
		
	templater::display();
?>