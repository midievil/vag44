<?php
	global $breadCrumbs;
	
	$post = new Post();
	$post->UserID = $currentUser->ID;
	
	$comment = '';
	
	if(mLogic::$urlVariables['postid'])
	{
		$post->ID = mLogic::$urlVariables['postid'];
		$post->Load();	

		$breadCrumbs[] = new BreadCrumb($post->getParent()->Name, '');
		$comment = "Вы пишете в раздел «".$post->getParent()->Name."». Всё, что вы скажете, может и будет использовано.";
	}
	elseif(mLogic::$urlVariables['forumid'])
	{		
		$post->ParentID = mLogic::$urlVariables['forumid'];		
		
		$breadCrumbs[] = new BreadCrumb($post->getParent()->Name, '');
		$comment = "Вы пишете в раздел «".$post->getParent()->Name."». Всё, что вы скажете, может и будет использовано.";
	}
	elseif(mLogic::$urlVariables['blogid'])
	{
		$post->BlogID = mLogic::$urlVariables['blogid'];
		
		$breadCrumbs[] = new BreadCrumb($post->getBlog()->Name, '');
		$comment = "Вы пишете в свой блог «".$post->getBlog()->Name."». Всё, что вы скажете, может и будет использовано.";
	}
	elseif(mLogic::$urlVariables['carid'])
	{
		$carid = mLogic::$urlVariables['carid'];		
		createCarBlogIfNotExists($carid);		
		createCarPostIfNotExists($carid);		
				
		$query = "
			select	P.ID PostID, B.ID BlogID
			from	Posts P
			join	Blogs B on B.ID = P.BlogID
			where	B.CarID = $carid
					and P.IsCarDescription = 1";
		$row = fDB::fquery($query);
		$post->ID = $row["PostID"];
		$post->Load();
	}	
	
	$pics = array();
	$num=1;
	if($post->ID)
	{
		while(file_exists("img/postpics/$post->ID"."_$num.jpg"))
		{
			$pics[] = "/img/postpics/$post->ID"."_$num.jpg";
			$num++;
		}
	}
	else
	{
		while(file_exists("img/newpics/$currentUser->ID"."_$num.jpg"))
		{
			$pics[] = "/img/newpics/$currentUser->ID"."_$num.jpg";			
			$num++;
		}
	}
	templater::assign('pics', $pics);
	
	
	$galleries = $currentUser->Galleries();
	templater::assign('galleries', $galleries);

	
	$files = array();
	if($dir = scandir("./uploads/postfiles"))
	{
		foreach ($dir as $i=>$entry)
		{
			if(TextFunctions::startsWith($entry, $post->ID.'_'))
			{
				$files[] = $entry;
			}
		}
	}
	
	
	templater::assign('post', $post);
	
	
	
	$title = $post->ID ? "Редактировать пост" : "Новый пост";
	templater::assign('comment', $comment );
	templater::assign('title', $title);
	
	
	$breadCrumbs[] = new BreadCrumb($title, '');

	templater::assign('includeMainMenu', true);

	templater::assign('breadCrumbs', $breadCrumbs);
			
	$tagCategories = getTagCategoriesList();
	templater::assign('tagCategories', $tagCategories);
	
	$blogs = getUserBlogs($post->UserID);
	templater::assign('blogs', $blogs);
	
	
		
	templater::display();
?>