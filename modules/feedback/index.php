<?php

	global $breadCrumbs;
	
	$breadCrumbs[] = new BreadCrumb("Обратная связь", '');

	templater::assign('includeMainMenu', true);

	templater::assign('breadCrumbs', $breadCrumbs);	
		
	templater::display();
	
?>