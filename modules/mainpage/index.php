<?php

	$categories = array();
	templater::assign('includeMainMenu', true);
	$categories = array();

	$tagCategoryRows = getTagCategoriesForMainPage($currentUser->CategoriesOrder);	

	try{
	foreach ($tagCategoryRows as $row)
	{
		$category = new TagCategory($row["ID"]);
		$category->MakeFromRow($row);
		$category->Init();
				
		$categories[] = $category;
	}
	}
	catch(Exception $e){
		//var_dump($e);
	}

	templater::assign('categories', $categories);	
	
	if($currentUser->IsLogged())
	{
		templater::assign('user_notifications', $currentUser->Notifications());
		templater::assign('user_notifications_count', $currentUser->NotificationsCount());
	}
	
	templater::display();	
?>