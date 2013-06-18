<?php
	$categories = array();
	templater::assign('includeMainMenu', true);
	$categories = array();
	
	$tagCategoryRows = getTagCategoriesForMainPage($currentUser->CategoriesOrder);	
	
	foreach ($tagCategoryRows as $row)
	{
		$category = new TagCategory($row["ID"]);
		$category->MakeFromRow($row);
		$category->Init();
				
		$categories[] = $category;
	}
	
	templater::assign('categories', $categories);	
	
	if($currentUser->IsLogged())
	{
		templater::assign('user_notifications', $currentUser->Notifications());
	}
	
	templater::display();
	
?>