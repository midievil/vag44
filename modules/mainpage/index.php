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
	
	$newsRows = BlogDB::getPostsByBlogID(1, 'P.Date', 'desc');
	$news = array();
	foreach($newsRows as $newsRow)
	{
		$newNews = new Post();
		$newNews->MakeFromRow($newsRow);
		$news[] = $newNews;
	}
	templater::assign('news', $news);
	
	templater::display();
?>