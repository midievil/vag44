<?PHP

	$blogid = mLogic::$urlVariables['blogid'];
	$blogUserID = mLogic::$urlVariables['userid'];
	$userid = $_SESSION["loggeduserid"];
	
	global $breadCrumbs;
	
	if(!empty($blogid))
	{
		$blog = new Blog($blogid);
		
		$user = new User($blog->UserID);
		templater::assign('blogs_user', $user);
		
		templater::assign('title', $blog->Name);
		
		if($blog->UserID == $currentUser->ID)
		{
			$breadCrumbs[] = new BreadCrumb('Ваши блоги', '/blog/user/' . $blog->UserID);
			templater::assign('comment', $i18n['blog_your']);			
		}
		else
		{
			$breadCrumbs[] = new BreadCrumb('Блоги пользователя <b>' . $blog->User()->Name . '</b>', '/blog/user/' . $blog->UserID);			
			templater::assign('comment', sprintf($i18n['blog_users'], $user->Name));
		}
		
		$breadCrumbs[] = new BreadCrumb($blog->Name, '');
				
		templater::assign('blog', $blog);
		templater::assign('posts', $blog->Posts());
	}
	elseif (!empty($blogUserID) || $currentUser->IsLogged())
	{
		if(!empty($blogUserID))
		{
			$user = new User($blogUserID);
		}
		else
		{
			$user = $currentUser;
		}
		
		templater::assign('blogs_user', $user);
		templater::assign('blogs', $user->Blogs());
		
		if($user->ID == $currentUser->ID)
		{
			$breadCrumbs[] = new BreadCrumb('Ваши блоги', '');
			templater::assign('title', 'Ваши блоги');
			templater::assign('comment', 'Здесь список ваших блогов. Их может быть несколько. Хоть весь блогами обложитесь.');
		}
		else 
		{
			$breadCrumbs[] = new BreadCrumb('Блоги пользователя <b>' . $user->Name . '</b>', '');
			templater::assign('title', sprintf($i18n['users_blogs'], $user->Name));
		}
	}
	else
	{	
		templater::assign('message', 'Такого блога нет. Кажется, вам кто-то дал неверный адрес.');				
	}
		
	templater::assign('breadCrumbs', $breadCrumbs);
	
	templater::display();
?>