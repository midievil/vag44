<?PHP	
	
	
	require_once "db/BlogDB.php";
	require_once "inc/class.mLoadable.php";
	

	class Blog extends Loadable
	{
		public $UserID;
		public $CarID;
		public $Name;
		public $Comment;
		
		public function Load()
		{			
			$row = getBlogByID($this->ID);			
			$this->MakeFromRow($row);			
		}
		
		private $user = null;
		public function User()
		{
			if($this->user == null)
			{
				$this->user = new User($this->UserID);
			}
			return $this->user;
		}
		
		private $posts = null;
		public function Posts()
		{
			if($this->posts == null)
			{
				$this->posts = array();
				
				$postRows = BlogDB::getPostsByBlogID($this->ID);
				foreach ($postRows as $postRow)
				{
					$post = new Post();
					$post->MakeFromRow($postRow);
					
					$post->Init();
					
					$post->Text = TextFunctions::pasteUploadedImage($post->Text, $post->ID);
					$post->Text = TextFunctions::formatText($post->Text);
					
					$this->posts[] = $post;
				}
			}
			return $this->posts;
		}
	}

	class TagCategoryChild extends Loadable
	{
		public $ParentID;
		
		private $parentObject = 'notloaded';
		public function getParent()
		{
			if($this->parentObject == 'notloaded')
			{
				if($this->ParentID)
				{
					$this->parentObject = new TagCategory($this->ParentID);
				}
				else
				{
					$this->parentObject = null;
				}
			}
			return $this->parentObject;
		}
		
		public function setParent($value)
		{
			$this->parentObject = $value;
		}
		
		public function getLevel()
		{
			if($this->getParent() != null)
			{				
				return $this->getParent()->getLevel()+1;
			}
			else
			{
				return 0;
			}
		}
		
		public function getImage()
		{
			if(file_exists("./img/categoryimages/".$this->ID.".jpg"))
			{
				return $this->ID;
			}
			if($po = $this->getParent())
			{
				return $po->getImage();
			}
			return "default";
		}
		
		public function getImageForMainPage()
		{
			if(file_exists("./img/categoryimages/".$this->ID.".png"))
			{
				return $this->ID . '.png';
			}
			return "";
		}
	}

	class TagCategory extends TagCategoryChild
	{		
		public $Name;
		public $Comment;
		public $OrderID;
		public $Visible;
		public $LastPostID;
		public $LastCommentID;
		public $LastCommentDate;
		public $LastCommentUserID;
		public $LastPostDate;
		public $LastPostUserID;
		public $ChildCount;
		public $ChildCategories;
		public $ChildPosts;
		
		public $LastCommentHint;
				
		public function Load()
		{
			$row = loadTagCategoryByID($this->ID);			
			$this->MakeFromRow($row);			
		}
		
		public function Init()
		{
			$this->LastCommentHint = getLastCommentHint($this->LastCommentID, $this->LastPostID);
		}
					
		private $childObjects = 'notloaded';
		public function getChildObjects()
		{
			if($this->childObjects == 'notloaded')
			{
				$this->childObjects = array();
				$this->ChildCategories = array();
				$this->ChildPosts = array();
				
				$childCategoryRows = getChildCategoriesByCategoryID($this->ID, "date");
				while($childCategoryRow = mysql_fetch_assoc($childCategoryRows))
				{
					$childCategory = new TagCategory();
					$childCategory->MakeFromRow($childCategoryRow);
					$childCategory->Init();
					$childCategory->setParent($this);
					$this->childObjects[] = $childCategory;
					
					$this->ChildCategories[] = $childCategory;
				}
				
				$childPostRows = getPostsByCategoryID($this->ID);
				while($childPostRow = mysql_fetch_assoc($childPostRows))
				{
					$childPost = new Post();
					$childPost->MakeFromRow($childPostRow);
					$childPost->Init();
					$childPost->setParent($this);
					$this->childObjects[] = $childPost;
					
					$this->ChildPosts[] = $childPost;
				}
			}
			return $this->childObjects;
		}
	}	//	class TagCategory
	
	class Post extends TagCategoryChild
	{
		public $BlogID;
		public $UserID;
		public $Title;
		public $Text;
		public $Date;
		public $AlwaysOnTop;
		public $ShowComments;
		public $LastCommentID;
		public $LastCommentUserID;
		public $Visible;
		public $IsCarDescription;
		public $GalleryID;
		public $Closed;
		public $CommentsCount;
		public $VisitsCount;
		public $User;
		public $BlogCarID;
		public $Rating;
				
		public function Load()
		{	
			$row = getPostByID($this->ID);			
			$this->MakeFromRow($row);	
		}
		
		public function Init()
		{					
			$user = new User($this->UserID);
			$this->User = $user;
		}
		
		public function LastCommentHint()
		{
			return getLastCommentHint($this->LastCommentID, -1);
		}
		
		public function CommentsCountHint()
		{
			return RenderFunctions::getCommentsCountHint($this->CommentsCount);
		}
		
		public function VisitsCountHint()
		{
			return RenderFunctions::getVisitsCountHint($this->VisitsCount);
		}
		
		public function CreateDate()
		{
			return DateFunctions::getDateTimeAtText($this->Date);
		}
		
		public function CreateShortDate()
		{
			return DateFunctions::getDateAtText($this->Date);
		}
		
		public function Comment()
		{			
			global $i18n;
			$comment = $i18n['wrote'][$this->User->Gender == '' ? 'm' : $this->User->Gender]. " <a id='aPostUser".$this->ID."' href='/user/".$this->User->ID."' rel='popover' data-content='".$this->User->GetDescriptionForPopup()."'><b>" . $this->User->Name . "</b></a> в свой ";
			if($this->BlogCarID)
			{
				$car = new Car($this->BlogCarID);
				$carRow = CarDB::getCarByID($this->BlogCarID);
				$carDescription = $car->getShortDescription() . ' ' . $car->getEngineDescription();
			
				if($car->PostID && $car->PostID != -1)
				{
					$comment .= " блог о <a href='/post/" . $car->PostID . "'> $carDescription </a>";
				}
				else
				{
					$comment .= " блог о $carDescription";
				}
			}
			else
			{
				$comment .= "персональный блог";
			}
			$date = DateFunctions::getDateTimeAtText($this->Date);
			if($date)
			{
				$comment .= '<br />'.$date;
			}
			return $comment;
		}
		
		private $blog = 'notloaded';
		public function getBlog()
		{
			if($this->blog == 'notloaded')
			{
				if($this->BlogID)
				{
					$this->blog = new Blog($this->BlogID);
				}
				else
				{
					$this->blog = null;
				}
			}
			return $this->blog;
		}
		
		function RenderQuickPaging($hint = '')
		{
			global $currentUser;
			
			$result = '';
            
            $result .= '<div class="pagination row small hidden"><ul>';
            
            if($currentUser->PageSize > 0 && $this->CommentsCount > $currentUser->PageSize)
			{	
                if($hint)
                {
                    $result .= '<li class="simple"><a class="simple">'.$hint.'</a></li>';
                }
                $pagesCount = ceil($this->CommentsCount / $currentUser->PageSize);
				for($page = 1; $page <= $pagesCount; $page++)
				{	
					if($pagesCount > 5)
					{
						if($page > 2 && $page <= $pagesCount-2)
						{
							if($page == 3)
							{
								$result .= "<li page='$page'><a>...</a></li>";
							}
							continue;
						}
					}
					$result .= "<li page='$page'><a class='hand' href='/post/$this->ID&page=$page'>$page</a>";
				}
			}
			
			$result .= "</ul></div>";
			
			return $result;
		}		
	}	//	class Post
	
	class Comment extends Loadable
	{		
		public $PostID;
		public $CommentID;
		public $UserID;
		public $Date;
		public $Text;
		
		public $UserName;
		public $UserTitle;
		public $Level = 1;
		public $Rating;
		public $Closed;
		
		public function Load()
		{
			$row = BlogDB::getCommentByID($this->ID);
			$this->MakeFromRow($row);
		}
		
		public function Render()
		{
 			global $currentUser;
 			$commentUser = new User($this->UserID);
		
 			if(!$listtype)
 			{			
 				$listtype = $currentUser->ListType;
 			}
								
 			$commentLink = '';
 			$quoteLink = '';
			if(!$this->Closed && $currentUser->IsLogged())
			{
				if($commentUser->ID != $currentUser->ID)
				{					
					$commentLink = "<a class='buttonsundercomment hand' onclick='answerComment($this->ID);'>Ответить</a>";
					$quoteLink = "&nbsp;·&nbsp;<a class='buttonsundercomment hand' onmouseover='copyToQuote(\"$commentUser->Name\", \"Comment\", $this->ID)' onclick='quoteSelection(\"$commentUser->Name\", \"Comment\", $this->ID); return false;'>Цитата</a>";
				}
				
				if($commentUser->ID == $currentUser->ID || $currentUser->IsAdmin())
				{			
					$editLink = ($quoteLink != '' ? '&nbsp;·&nbsp;' : '') . "<a class='buttonsundercomment hand' onclick='editComment($this->ID);'>Редактировать</a>";					
				}
			}
			
 			$tabulation = $listtype == 'tree' ? "margin-left:".(($this->Level-1)*20 + 10)."px;" : '';
			
			if($this->CommentID)
			{
				//if($listtype=='list')
				{				
					$parentComment = BlogDB::getCommentByID($this->CommentID);
					$parentComment = preg_replace("/\[quote=(.+)](.*)\[\/quote]/", "", $parentComment);						
//						$quotedcomment = "<span class='quote' onclick='highlightParentComment($this->CommentID);' href='#comment$this->CommentID'>".User::UserIcon()."<b>".($parentComment["UserName"])."</b>: ".($parentComment["Text"])."</span><br />";
					$quotedcomment = " » <i class='icon-user icon-black'></i> <a href='/user/".$parentComment["UserID"]."' rel='popover' data-content='<blockquote>".$parentComment["Text"]."</blockquote>' data-original-title='Ответ на комментарий <i class=\"icon-user\"></i><strong>".$parentComment["UserName"]."</strong>' ><strong>".$parentComment["UserName"]."</strong></a>";		
				}
				
				$class = "Comment$this->CommentID";				
			}
			else
			{
				$class = "Post";				
			}
			
			$userPic = $commentUser->RenderUserPic('comment', $this->ID, 30);
						
				
				if($commentUser->ID == $currentUser->ID || $currentUser->IsAdmin())
				{
					$editForm = "
					<div class='row hidden' id='trEditComment$this->ID'>
						<div class='row span10'>
							<textarea id='tbEditComment$this->ID' class='span10' rows='5'>$this->Text</textarea>
						</div>
						<div class='row span10'>
							<button class='btn btn-primary' onclick='updateComment($this->ID, $this->PostID)'>Сохранить</button>
							<button class='btn' onclick='hideEditComment($this->ID);'>Отмена</button>									
						</div>
					</div>";
				}
				
				if($currentUser->IsLogged())
				{				
					$answerForm = "<div class='hidden row' id='trAnswerComment$this->ID'>
						<div class='row span10'>
							<textarea id='tbAnswerComment$this->ID' class='span10' rows='5'></textarea>
						</div>
						<div class='row span10'>
							<button class='btn btn-primary' onclick='writeCommentForComment($this->ID, $this->PostID); return false;'>Написать</button>
							<button class='btn' onclick='hideAnswerComment($this->ID);'>Отмена</button>
						</div>
					</div>";
				}
				$descriptionRow = "<div class='row'>".DateFunctions::getDateTimeAtText($this->Date) . ( $currentUser->IsLogged() ? " $commentLink$quoteLink" : "" ).	$editLink . "</div>";
				
				$rating = RenderFunctions::renderRatingForComment($this->Rating, $this->PostID, $this->ID, $this->UserID);
				
				$result = "
					<div class='span10 comment row' style='$tabulation'>
						<div class='well row'>
							<div class='span1'>
								$userPic
							</div>				
							<div class='span8'>
								<i class='icon-user icon-black'></i> 
								<a href='/user/".$commentUser->ID."' rel='popover' data-content='".$commentUser->GetDescriptionForPopup()."' data-original-title='".$commentUser->Name."' >
								<strong>$commentUser->Name</strong></a>
								$quotedcomment<br />
								" . TextFunctions::formatText($this->Text) . "								
							</div>
							<div class='span1 pull-right'>$rating</div>
						</div>
						
						$descriptionRow
						$editForm
						$answerForm
						<br />						
					</div><br />
				";
				
			if($listtype == 'tree' && $this->Level<50)
			{
				$subComments = BlogDB::getCommentsByComment($this->ID, $this->Level+1);			
				foreach ($subComments as $subCommentRow)
				{
					$childComment = new Comment();
					$childComment->MakeFromRow($subCommentRow);
					$childComment->PostID = $this->PostID;
					$childComment->Level = $this->Level+1;
					
					$result .= $childComment->Render();
				}
				if(count($subComments) == 0)
				{	
					$result .= "<tr class='Comment$this->ID"."Child'></tr>";
				}
			}
			
			return $result;
		}
		
		
		public function RenderSimple()
		{
 			global $currentUser;
 			$commentUser = new User($this->UserID);		
 								
 			$commentLink = '';
 			$quoteLink = '';
			
 			if($this->CommentID)
			{
				$parentComment = BlogDB::getCommentByID($this->CommentID);
				$parentComment = preg_replace("/\[quote=(.+)](.*)\[\/quote]/", "", $parentComment);						
				$quotedcomment = " » <i class='icon-user icon-black'></i> <a href='/user/".$parentComment["UserID"]."'><strong>".$parentComment["UserName"]."</strong></a>";
			}
				
             $descriptionRow = "<div class='row'>".DateFunctions::getDateTimeAtText($this->Date)."</div>";
						
			$result = "
				<div>
					<div class='well'>									
						<div class=''>
							<i class='icon-user icon-black'></i> 
							<a href='/user/".$commentUser->ID."' rel='popover' data-content='".$commentUser->GetDescriptionForPopup()."' data-original-title='".$commentUser->Name."' >
							<strong>$commentUser->Name</strong></a>
							$quotedcomment<br />
							" . TextFunctions::removeQuotes( TextFunctions::formatText($this->Text) ) . "<br /><br />
						</div>
					</div>
					<div class='span5'>
						$descriptionRow					
					</div>
					<br />						
				</div><br />
			";
				
			
			
			return $result;
		}
	}
	
	function getLastCommentHint($lastCommentID, $lastPostID)
	{			
		$lastPost = new Post();
		$lastComment = new Comment();
		
		if($lastPostID)
		{
			$lastPost = new Post($lastPostID);			
		}
		
		if($lastCommentID)
		{
			$lastComment = new Comment($lastCommentID);
		}
		
		if($lastComment->ID || $lastPost->ID)
		{
			if($lastComment->ID && $lastComment->Date > $lastPost->Date)
			{				
				$lastComment = new Comment($lastCommentID);
				$lastPost = new Post($lastComment->PostID);
								
				$date = $lastComment->Date;
				$user = new User($lastComment->UserID);
				$comment_last = "Последний комментарий";
			}
			else 
			{	
				if($lastPostID == -1)
				{
					return "";
				}
				$date = $lastPost->Date;
				$user = new User($lastPost->UserID);
				$comment_last = "Написан пост";	
			}	
			
			if($lastPost && $lastPost->ID)
			{
				if($lastPostID != -1)
				{
					$link = '
					<a title="'.$lastPost->Title.'" class="lastposttitle" href="/post/' . $lastPost->ID . '?page=last">' . $lastPost->Title . '</a>
					<br />';
				}
			}
			$userDescription = $user->GetDescriptionForPopup();
			
			return "<div style='overflow: hidden; white-space:nowrap; width: 200px'>
                        <div class='span2 pull-left' >". $user->RenderUserPic($id, 'TagCategory'.rand(1, 1000), 30) . "</div>
					    <div class='span8 nowrap pull-left' style='height: 60px; padding-left:25px'>
					        $link<a title='$comment_last' class='simple'><i class='icon-comment'></i>" .DateFunctions::getDateTimeAtText($date) . "</a><br />от <a href='/user/".$user->ID."'><i class='icon-user'></i>" . $user->Name . '</a><br />
				        </div><br />
                        
                    </div>'.$lastPost->RenderQuickPaging('Страницы:');
		}
		else
		{
			return "";
			$userpic = "";
		}
	}
	
	function getCarsWithNoBlogs($userid)
	{
		$query = "
			select	C.ID
			from	Cars C			
			left join
					Blogs B on B.CarID = C.ID					
			where	C.UserID=$userid
					and	B.ID is NULL";
		return fDB::fqueryAll($query);		
	}
	
	function getPosts($blogid)
	{
		$query  = "
			select	P.*,
					U.ID UserID, 
					U.Name UserName, 
					U.Title UserTitle 
			from	Posts P 
			join	Blogs B on B.ID = P.BlogID
			join	Users U on U.ID = B.UserID
			where	P.BlogID = $blogid
			order	by P.Date desc" ;
		return mysql_query($query);
	}
		
	function getUserBlogs($userid)
	{
		$query  = "select * from Blogs where UserID = $userid";
		$result = fDB::fqueryAll($query);
		return $result;
	}
	
	function personalBlogExists($userid)
	{
		$query = "select count(*) Count from Blogs where UserID = $userid and (CarID is null or CarID = -1)";		
		if($result = mysql_query($query))
		{
			if($row = mysql_fetch_assoc($result))
			{
				if($row["Count"] != 0)
				{
					return true;
				}
			}
		}
		return false;
	}
	
	function carBlogExists($carid)
	{
		$query = "select count(*) Count from Blogs where CarID = $carid";		
		return fDB::fscalar($query ) > 0;
	}
	
	function carPostExists($carid)
	{
		$query = "
			select	count(*) Count 
			from	Posts P
			join	Blogs B on B.ID = P.BlogID
			where	B.CarID = $carid
					and P.IsCarDescription = 1";
		if($result = mysql_query($query))
		{
			if($row = mysql_fetch_assoc($result))
			{
				if($row["Count"] != 0)
				{
					return true;
				}
			}
		}
		return false;
	}
		
	function createPersonalBlog($userid, $blogName)
	{
		//if(!personalBlogExists($userid))
		{		
			try
			{
				$query = "insert into Blogs (UserID, Name, `Comment`) values ($userid, '$blogName', '')";
				fDB::fexec($query);
				return true;
			}
			catch(Exception $e)
			{
				var_dump($e);
				//	ToDo: Catch
			}
		}
		return false;
	}
	
	function createCarBlog($carid)
	{		
		$carinstance = new Car($carid);		
		$userid = $carinstance->UserID;
		
		$car = $carinstance->getShortDescription();		
		
		$query="select COUNT(ID) AS cnt from Blogs where CarID=$carid";		
		if(fDB::fscalar($query) > 0)
		{
			return 0;
		}
		if($car)
		{
			$query = "
			insert	into Blogs (
					UserID,
					CarID,
					Name, 
					`Comment`	)
			values(	$userid,
					$carid,
					'$car',
					'')";
		}
		
		return fDB::fexec($query);		
	}
	
	function createCarPost($carid, $categoryID)
	{
		$carinstance = new Car($carid);
		$blogID = $carinstance->BlogID;
		
		$car = $carinstance->getShortDescription();
		$date = DateFunctions::getCurrentDateText();
		
		$query = "
			insert	into Posts (
					BlogID,
					Title,
					Text, 
					Date,
					IsCarDescription	)
			values(	
					$blogID,
					'$car',
					'',
					'$date',
					1
					)";
		$result = fDB::fexec($query);
		$id = fDB::lastID();
				
		$query = "insert into TagCategoriesToPosts (PostID, TagCategoryID) values ($id, $categoryID)";		
		fDB::fexec($query);
		
		return $id;
	}
	
	function createCarBlogIfNotExists($carid)
	{	
		if(!carBlogExists($carid))
		{		
			$id = createCarBlog($carid);
			return $id;	//	means that exists
		}
		else
		{
			return -1;	//	means that exists
		}
	}
	
	function createCarPostIfNotExists($carid, $categoryID)
	{
		if(!carPostExists($carid))
		{			
			$id = createCarPost($carid, $categoryID);
			return $id;	//	means that exists
		}
		else
		{
			return -1;	//	means that exists
		}
	}
	
	function getTagCategoryByName($name)
	{
		$query = "
			select	*
			from	TagCategories
			where	Name = '$name'";
		$result = mysql_query($query);
		//writeLog($query);
		if($result)
		{			
			return mysql_fetch_assoc($result);
		}
		return "NULL";
	}	
	
	function getRatingForPost($postID)
	{
		$query = "
			select	U.Name,
					U.ID
			from	Rating R
			join	Users U on U.ID = R.FromUserID
			where 	PostID = $postID
					and	IFNULL(CommentID, -1) = -1";
		return mysql_query($query);
	}
	
	function getRatingForComment($commentID)
	{
		$query = "
			select	U.Name,
					U.ID
			from	Rating R
			join	Users U on U.ID = R.FromUserID
			where 	CommentID = $commentID";
		return mysql_query($query);
	}
?>