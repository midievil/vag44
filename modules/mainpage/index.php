<?php
	templater::assign('includeMainMenu', true);
	
	$tagCategoryRows = getTagCategoriesForMainPage($currentUser->CategoriesOrder);
	
	$categories = array();
	foreach ($tagCategoryRows as $row)
	{
		$category = new TagCategory($row["ID"]);
		$category->MakeFromRow($row);
		$category->Init();
				
		$categories[] = $category;
	}
	templater::assign('categories', $categories);	
	
	templater::display();
?>