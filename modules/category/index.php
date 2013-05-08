<?php

	$categoryID = mLogic::$urlVariables['category'];

	if(!$categoryID)
	{
		templater::display('404.html');
		die;
	}
	
	$category = new TagCategory($categoryID);
	$category->Init();
	if($category->getChildObjects() != null)
	{		
		templater::assign('childCategories', $category->ChildCategories);
		templater::assign('childPosts', $category->ChildPosts);
	}
	templater::assign('category', $category);	

	global $breadCrumbs;
	
	$breadCrumbs[] = new BreadCrumb($category->Name, '');

	templater::assign('includeMainMenu', true);

	templater::assign('breadCrumbs', $breadCrumbs);
		
	templater::display();
?>