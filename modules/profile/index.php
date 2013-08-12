<?php

	global $breadCrumbs;
	$breadCrumbs[] = new BreadCrumb('Ваш профиль', '');
	templater::assign('breadCrumbs', $breadCrumbs);

	templater::assign('includeMainMenu', true);

	if(!$currentUser->IsLogged())
	{
		return;
	}

	$comment = "		
			<b>$currentUser->Name</b>
			<br />группа: $currentUser->GroupName
			<br />рейтинг: $currentUser->Rating";
	templater::assign('comment', $comment);
	
	
	$setupModule = !empty(mLogic::$urlVariables['profile']) ? mLogic::$urlVariables['profile'] : "about";
	templater::assign('setupModule', $setupModule);
	
	$currentUserpicfile = "img/userpics/$currentUser->ID.jpg";
	if(file_exists(SITE_DIR.$currentUserpicfile))
	{		
		templater::assign('userPic', $currentUserpicfile);
	}
	
	$birthDateSelector = RenderFunctions::getDateSelector("BirthDate", $currentUser->BirthDate);
	templater::assign('birthDateSelector', $birthDateSelector);
		
	
		
	templater::display();
?>