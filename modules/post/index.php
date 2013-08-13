<?php

	global $breadCrumbs;
	
	$postID = mLogic::$urlVariables['post'];
	
	$post = new Post($postID);
	$post->Init();
	
	$post->Text = TextFunctions::pasteUploadedImage($post->Text, $post->ID);
	$post->Text = TextFunctions::formatText($post->Text);
	
	templater::assign('post', $post);
	
	
	templater::assign('postComment', $comment);	
	
	
	//$breadCrumbs[] = new BreadCrumb($category->Name, '');

	templater::assign('includeMainMenu', true);

	$parent = $post->getParent();
	if($parent != null)
	{		
		$parent2 = $parent->getParent();	
		if($parent2 != null)
		{
			$breadCrumbs[] = new BreadCrumb($parent2->Name, '/category/'.$parent2->ID);			
		}
		
		$breadCrumbs[] = new BreadCrumb($parent->Name, '/category/'.$parent->ID);
	}	
	templater::assign('breadCrumbs', $breadCrumbs);	
	
	
	$rawGalleryItems = array();
	$galleryPreviews = array();
	$i=1;	
	while(file_exists(SITE_DIR."img/postpics/$postID"."_$i.jpg"))
	{
		$rawGalleryItems[] = "/img/postpics/$postID"."_$i.jpg";
		$galleryPreviews[] = "/img/postpics/previews/$postID"."_$i.jpg";
		FileFunctions::createPicPreview(SITE_DIR."img/postpics", $postID . "_$i.jpg", 200);
		$i++;
	}	
	if($post->GalleryID && $post->GalleryID != -1)
	{
		$galleryItemRows = getGalleryItems($post->GalleryID);
		while($galleryItemRow = mysql_fetch_assoc($galleryItemRows))
		{
			$fileName = $post->UserID ."_" . $post->GalleryID . "_" . $galleryItemRow["ID"] . ".jpg";			
			FileFunctions::createPicPreview(SITE_DIR."img/gallery", $fileName, 200);
			
			$rawGalleryItems[] = "/img/gallery/$fileName";
			$galleryPreviews[] = "/img/gallery/previews/$fileName";
			
		}
	}
	templater::assign('galleryItems', $rawGalleryItems);
	
	
	//	Рендерим пейджинг в любом случае, т.к. юзер может переключить режим асинхронно
	$currentPage = $_GET["page"];
	if(!$currentPage)
	{
		$currentPage = 1;
	}
	else if($currentPage == 'last')
	{
		$currentPage = ceil($post->CommentsCount / $currentUser->PageSize);
	}
	
	$paging = new Paging($currentUser->PageSize, $post->CommentsCount, $currentPage, "showComments($post->ID, \"list\", %pagenumber%)");
	templater::assign('commentsPaging', $paging);		

	
	
	templater::assign('rating', RenderFunctions::renderRatingForPost($post->Rating, $post->ID, $post->UserID));	
		
	templater::display();
?>