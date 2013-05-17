<?PHP
	error_reporting(0);
		
	session_start();
	header('Content-type: text/html; charset=utf-8');
	
	if(!$_REQUEST["action"])
	{
		return;
	}
	
	chdir("..");		
	
	require_once "constants.php";	
	require_once "miscfunctions.php";	
	require_once "db.php";
	connectToDB();		
	require_once "carlogic.php";
	require_once "userlogic.php";
	require_once "bloglogic.php";
	require_once "tools/simpleimage.php";
	
	$currentUser = User::CurrentUser();
	
	function dbgTime($point)
	{
		return;
		if(stripos($_SERVER['SERVER_NAME'], 'local') !== false)
		{
			$date = getdate();
			echo "POINT $point: " . $date["seconds"] . "." . $date[0] . "<br />";
		}
	}
		
	switch($_REQUEST["action"])
	{
		case "createblog":
			$userid = $_POST["userid"];
			try
			{
				if(createPersonalBlog($userid))
				{
					echo "ok";
				}
			}
			catch(Exception $e)
			{
				echo "error";
			}			
			return;
			
		case "createcarblog":
			$carid = $_POST["carid"];
			if(	createCarBlog($carid) > 0)
			{
				echo "ok";
			}
			else
			{
				echo "no";
			}
			return;
			
		case "renameblog":
			$blogID = $_REQUEST["id"];
			$newName = $_REQUEST["newname"];
			BlogDB::renameBlog($blogID, $newName);
			echo "ok";
			return;
			
		case "writepost":	
			$currentUser = User::CurrentUser();
			
			if($currentUser->IsLogged())
			{
				$blogid = $_POST["blogid"];
				$tagcategoryid = $_POST["tagcategoryid"];
				$postid = $_POST["postid"];
				$title = replaceSymbols(unescape($_POST["title"]));
				$text = replaceSymbols(unescape($_POST["text"]));
				$galleryid = $_POST["galleryid"];
				$date = getCurrentDateText();
				
				$blog = getBlogByID($blogid);
				if($blog && ($blog["UserID"] == $currentUser->ID || $currentUser->IsAdmin()))
				{				
					if($postid)
					{
						$query = "
							update Posts
							set	Title = '$title',
								Text = '$text',
								GalleryID = $galleryid,
								BlogID = $blogid
							where ID = $postid";						
						fDB::fexec($query);
					}
					else
					{
						$query = "
							insert	into Posts (
									BlogID,
									Title,
									Text,
									Date,
									GalleryID)
							values(	$blogid,
									'$title',
									'$text',
									'$date',
									$galleryid	)";
						fDB::fexec($query);
						if(!fDB::lastID())
						{
							echo "error";
						}						
					}
					
					if(!$postid)
					{
						$postid = fDB::lastID();
						$query = "insert into TagCategoriesToPosts(TagCategoryID, PostID) values($tagcategoryid, $postid)";
						fDB::fexec($query);
						
						$query = "update TagCategories set LastPostID=$postid where ID=$tagcategoryid";
						fDB::fexec($query);
						
						$query = "select ParentID from TagCategories where ID=$tagcategoryid LIMIT 1";
						$parentID = fDB::fscalar($query);
						if(!empty($parentID))
						{
							$query = "update TagCategories set LastPostID=$postid where ID=$parentID";
							fDB::fexec($query);
						}
						
						$text = str_replace('[file]'.$currentUser->ID.'_', '[file]'.$postid.'_' ,$text);
						$query = "
							update Posts
							set	Text = '$text'
							where ID = $postid";						
						fDB::fexec($query);
					}
					else
					{	
						$query = "
						update	TagCategoriesToPosts 
						set		TagCategoryID = $tagcategoryid
						where	PostID=$postid";			
						fDB::fexec($query);
					}
					
					$num = 1;					
					while(file_exists("img/newpics/$currentUser->ID"."_$num.jpg"))
					{						
						rename("img/newpics/$currentUser->ID"."_$num.jpg", "img/postpics/$postid"."_$num.jpg");					
						$num++;
					}				

					if($dir = scandir("uploads/newfiles"))
					{
						foreach ($dir as $i=>$entry) 
						{
							if (startsWith($entry, $currentUser->ID.'_'))
							{
								$newentry = str_replace($currentUser->ID.'_', $postid.'_', $entry);
								rename("uploads/newfiles/$entry", "uploads/postfiles/$newentry");								
							}
						}
					}
					
					echo "ok";
				}
			}
			return;
		
		case "writecomment":
			$currentUser = User::CurrentUser();
			
			if($currentUser->IsLogged())
			{
				$postid = $_POST["postid"];
				$text = replaceSymbols(unescape($_POST["text"]));			
				$date = getCurrentDateText();			
				
				$query = "
					insert	into Comments (
							PostID,	
							UserID,	
							Date, 
							Text	)
					values(	$postid, 
							$currentUser->ID, 
							'$date', 
							'$text'	)";				
				
				if(fDB::fexec($query))
				{
					$id = fDB::lastID();
					
					if($id)
					{
						$query = "update	Posts	set		LastCommentID = $id	where	ID = $postid";
						fDB::fexec($query);
						
						$query = "select TagCategoryID from TagCategoriesToPosts where PostID=$postid LIMIT 1";
						$tagCategoryID = fDB::fscalar($query);
						if(!empty($tagCategoryID))
						{
							$query = "update TagCategories set LastCommentID=$id where ID=$tagCategoryID";
							fDB::fexec($query);
							
							$query = "select ParentID from TagCategories where ID=$tagCategoryID LIMIT 1";
							$parentID = fDB::fscalar($query);
							if(!empty($parentID))
							{
								$query = "update TagCategories set LastCommentID=$id where ID=$parentID";
								fDB::fexec($query);
							}
						}
						
						$comment = new Comment($id);						
						echo $comment->Render();												
						echo '|#lstcmnt#|'.$comment->ID;
						
						return;
					}
				}
				else
				{
					//writeQueryErrorToLog($query, mysql_error());
					echo "error";
				}
			}
			else
			{
				echo "notlogged";
			}
						
			return;
			
		case "writecommentforcomment":
			$currentUser = User::CurrentUser();
		
			$commentid = $_POST["commentid"];
			$postid = $_POST["postid"];
			$parentlevel = $_POST["parentlevel"];
			$text = replaceSymbols(unescape($_POST["text"]));
			$date = getCurrentDateText();
						
			$query = "
				insert	into Comments (
						PostID,	
						CommentID,	
						UserID,	
						Date, 
						Text	)
				values(	$postid,
						$commentid,
						$currentUser->ID,
						'$date',
						'$text'	)";
						
			if(fDB::fexec($query))
			{
				$id = fDB::lastID();
				
				if($id)
				{
					$query = "
					update	Posts 
					set		LastCommentID = $id
					where	ID = $postid";					
					fDB::fexec($query);
					
					$query = "select TagCategoryID from TagCategoriesToPosts where PostID=$postid LIMIT 1";
					$tagCategoryID = fDB::fscalar($query);
					if(!empty($tagCategoryID))
					{
						$query = "update TagCategories set LastCommentID=$id where ID=$tagCategoryID";
						fDB::fexec($query);
						
						$query = "select ParentID from TagCategories where ID=$tagCategoryID LIMIT 1";
						$parentID = fDB::fscalar($query);
						if(!empty($parentID))
						{
							$query = "update TagCategories set LastCommentID=$id where ID=$parentID";
							fDB::fexec($query);
						}
					}					
										
					$comment = new Comment($id);					
					$comment->Level = $parentlevel+1;					
					echo $comment->Render();
					echo '|#lstcmnt#|'.$comment->ID;
					return;
				}
			}
			else
			{
				echo "error";
			}
			
			return;			
			
		case "showcomments":
			try
			{			
				dbgTime("c1");
				$_SESSION["lastShownCommentID"] = -1;								
				
				$postid = $_REQUEST["postid"];			
				$listtype = $_REQUEST["listtype"];
				$page = $_REQUEST["page"];
				
				$currentUser = User::CurrentUser();
				dbgTime("c2");
				if(!$listtype)
				{	
					$listtype = $currentUser->ListType;					
				}
				else if($currentUser->IsLogged())
				{
					$query = "update Users set ListType='$listtype' where ID = ".($currentUser->ID)." and ListType != '$listtype'";					
					if(fDB::fexec($query))
					{
						$currentUser = User::CurrentUser();
					}
				}
				dbgTime("c3");
				
				$comments = "";
								
				if($listtype == 'tree')
				{
					$comments = BlogDB::getCommentsByPostID($postid);
				}
				else if ($listtype == 'list')
				{					
					$comments = BlogDB::getFlatCommentsByPostID($postid, $page);					
				}
				dbgTime("c4");
				
				echo "<div class='span10 row'></div>";
				
				if(count($comments) > 0)
				{	
					foreach($comments as $row)
					{
						$comment = new Comment();
						$comment->MakeFromRow($row);
						echo $comment->Render();						
					}					
						echo "<script>$('[rel=\"popover\"]').popover({'placement':'top'});</script>";
				}
				else
				{
					echo "<div class='well row'>комментариев нет</div>";
				}								
			}
			catch (Exception $e)
			{
				writeLog($e);
			}
			return;
			
		case "shownewcomments": 
			$postid = $_REQUEST["postid"];
			$lastCommentID = intval($_REQUEST["lastcommentid"]);
			
			try
			{
				if(!$lastCommentID)
				{
					$lastCommentID = 0;
				}
				
				$comments = BlogDB::getNewCommentsByPostID($postid, $lastCommentID);				
				foreach($comments as $row)
				{
					$comment = new Comment();
					$comment->MakeFromRow($row);
					echo $comment->Render();				
				}
				
				$post = new Post($postid);
				echo '|#lstcmnt#|';

				if (!empty($post)) { echo $post->LastCommentID; } else {echo '-1';}
			}
			catch (Exception $e)
			{
				writeLog($e);
			}
			return;
		
		case "updatecomment":
		
			$currentUser = User::CurrentUser();
			
			$commentid = $_POST["commentid"];
			$text = replaceSymbols(unescape($_POST["text"]));
			
			$comment = getCommentByID($commentid);
						
			if ($comment && ($comment["UserID"] == $currentUser->ID || $currentUser->IsAdmin()))
			{
				if($text)
				{
					$query = "
						update	Comments 
						set		Text = '$text'
						where	ID = $commentid";
				}
				else
				{
					$query = "
						delete from	Comments 
						where	ID = $commentid";
				}
					
				fDB::fexec($query);				
				echo "ok";
			}
			return;
			
		case "updatetag":
			if(User::CurrentUser()->IsAdmin())
			{
				$id = $_POST["id"];
				$name = $_POST["name"];
				if($name)
				{
					$query = "
						update	TagCategories
						set		Name = '$name'
						where	ID = $id";
				}
				else
				{
					$query = "
						delete from	TagCategories
						where	ID = $id";
				}
				fDB::fexec($query);
				
				echo "ok";
				return;
			}
			
			echo "notadmin";
			return;
			
		case "addtag":
		
			$name = $_POST["name"];
			$parentid = $_POST["parentid"];
			if(!$parentid)
			{
				$parentid = 'NULL';
			}
			$query = "
				insert	into	TagCategories (
						Name, 
						ParentID	)
				values(	'$name',
						$parentid	)";
			//writeLog($query);
			fDB::fexec($query);
			echo "ok";
			return;
			
		case "assigntagcategorytopost";
			$postid = $_POST["postid"];
			$tagCategory = getTagCategoryByName($_POST["tagcategory"]);
			$tagcategoryid = $tagCategory["ID"];
			
			$query = "
				insert into TagCategoriesToPosts (
						PostID,
						TagCategoryID)
				values(	$postid,
						$tagcategoryid )";	
			fDB::fexec($query);
			
			echo "ok";
			return;
			
		case "closepost":
			$postid = $_POST["postid"];
			if(User::CurrentUser()->IsAdmin())
			{
				$query = "
					update	Posts
					set		Closed = 1
					where	ID = $postid";
				fDB::fexec($query);
				echo "ok";
				return;
			}
			echo "notadmin";
			return;
	}
?>