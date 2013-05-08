<?php

	templater::assign('includeMainMenu', true);
	
	global $breadCrumbs;
	
	$user = null;
	if(isset(mLogic::$urlVariables["userid"]))
	{
		$blogsUserID = mLogic::$urlVariables["userid"];	
		$user = new User($blogsUserID);
		
	}
	else if ($currentUser->IsLogged())
	{		
		$blogsUserID = $currentUser->ID;
		$user = $currentUser;
		templater::assign('title', "Ваши блоги");
		templater::assign('comment', "Это список ваших блогов. Вы можете написать пост в любой из них");
	}
	
	if($user != null)
	{
		templater::assign('blogs', $user->Blogs());
	}
	
	templater::assign('blogsUser', $user);
	
	
	
	
	templater::display();
	
?>