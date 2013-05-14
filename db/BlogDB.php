<?PHP

	require_once 'inc/class.db.php';
	require_once 'inc/class.fdb.php';


	class BlogDB
	{
		
		public static function getCommentsByPostID($postid)
		{
			$query  = "
			select	C.*,
					U.Name UserName,
					U.Title UserTitle,
					(	select COUNT(ID) from Rating where CommentID = C.ID )	Rating
			from	Comments C
			join	Users U	on U.ID = C.UserID
			where	C.PostID = $postid
					and C.CommentID is NULL
			order	by C.Date" ;
			//echo $query;
			return fDB::fqueryAll($query);
		}
		
		public static function getFlatCommentsByPostID($postid, $page)
		{
			global $currentUser;
			if($page && $page != -1)
			{
				if($currentUser->PageSize > 0)
				{
					$startPos = ($page-1) * $currentUser->PageSize;
					$paging = "LIMIT $startPos, " . $currentUser->PageSize;
				}
			}
			$query  = "
			select	C.*,
					U.Name UserName,
					U.Title UserTitle,
					0 Level,
					(	select COUNT(ID) from Rating where CommentID = C.ID )	Rating
			from	Comments C
			join	Users U	on U.ID = C.UserID
			where	C.PostID = $postid
			order	by C.Date
			$paging" ;
			return fDB::fqueryAll($query);
		}
		
		public static function getCommentsByComment($commentid, $level)
		{
			$query  = "
			select	C.*,
					U.Name UserName,
					U.Title UserTitle,
					(	select COUNT(ID) from Rating where CommentID = C.ID )	Rating
			from	Comments C
			join	Users U	on U.ID = C.UserID
			where	C.CommentID = $commentid
			order	by C.Date" ;
			return fDB::fqueryAll($query);
		}
		
		public static function getNewCommentsByPostID($postid, $lastCommentID)
		{
			$query  = "
			select	C.*,
					U.Name UserName,
					U.Title UserTitle,
					(	select COUNT(ID) from Rating where CommentID = C.ID )	Rating
			from	Comments C
			join	Users U	on U.ID = C.UserID
			where	C.PostID = $postid
					and	C.ID > $lastCommentID
			order	by C.ID" ;
			return fDB::fqueryAll($query);
		}
		
		public static function getBlogsByUser($userID)
		{
			$query  = "
			select	B.*
			from	Blogs B
			where	B.UserID = $userID";
			$result = fDB::fqueryAll($query);
			return $result;
		}
		
		public static function getPostsByBlogID($blogID, $sortField, $sortDir)
		{
			if(empty($sortField))
			{
				$sortField = "case
						when P.Date > IFNULL(C.Date, '1900-01-01') then P.Date
						else IFNULL(C.Date, '1900-01-01')
					end";
				$sortDir = 'desc';
			}
			
			$query = "
			select	P.ID,
					P.Title,
					P.Text,
					P.Date,
					IFNULL(C.Date, '1900-01-01')	LastCommentDate,
					P.LastCommentID,
					(select COUNT(ID) from Comments where PostID = P.ID) CommentsCount,
					U.ID UserID,
					U.Name UserName,
					U.Title UserTitle,
					TCTP.TagCategoryID ParentID,
					P.Closed,
					P.VisitsCount
			from	TagCategoriesToPosts TCTP
			join	Posts P on P.ID = TCTP.PostID
			join	Blogs B on B.ID = P.BlogID
			join	Users U on U.ID = B.UserID
			left join
			Comments C on C.ID = P.LastCommentID
			where	B.ID = $blogID
					and	P.Text != ''
			order by $sortField
					 $sortDir";
			$result = fDB::fqueryAll($query);
			return $result;
		}
		
		public static function renameBlog($blogID, $newName)
		{
			$query = "UPDATE blogs SET Name='$newName' WHERE ID=$blogID";
			$result = fDB::fexec($query);
			return true;
		}
	}

	function loadTagCategoryByID($id)
	{		
		$query  = "
			select	TC.*,
					(	select count(*) from TagCategories TCC where TCC.ParentID = TC.ID) ChildCount
			from	TagCategories TC
			left join
					TagCategories TCP on TCP.ID = TC.ParentID
			where	TC.ID = $id";
		$result = mysql_query($query);
		return mysql_fetch_assoc($result);		
	}

	function getTagCategoriesForMainPage($order)
	{		
		$query  = "
			select	TC.ID,
					TC.Name,
					TC.Comment,
					TC.LastPostID,
					TC.LastCommentID
			from	TagCategories TC			
			left join
					Posts P on P.ID = TC.LastPostID			
			left join
					Comments C on C.ID = TC.LastCommentID			
			where	TC.ParentID IS NULL
					and	TC.Visible=1
			order	by " . ( $order == "date" ? " 
					case
						when P.Date > IFNULL(C.Date, '1900-01-01') then P.Date
						else IFNULL(C.Date, '1900-01-01')
					end Desc, " : "" ) . "
					TC.OrderID" ;
		return fDB::fqueryAll($query);
	}
	
	function getTagCategoriesList()
	{		
		$query  = "
			select	TC.ID ParentID,
					TC.Name ParentName,
					IFNULL(TC1.ID, TC.ID) ID,
					IFNULL(TC1.Name, TC.Name) Name
			from	TagCategories TC
			left join
					TagCategories TC1 on	TC1.ParentID = TC.ID
											or TC1.ID = TC.ID
			where	TC.ParentID IS NULL 
			group	by TC.ID,
					TC.Name,
					TC1.ID,
					TC1.Name
			order	by TC.OrderID,
					TC.Name,
					TC1.OrderID,
					TC1.Name";
		return fDB::fqueryAll($query);
	}
	
	function getPostsByCategoryID($id)
	{
		$query = "
			select	P.ID,
					P.Title, 
					P.Text,
					P.Date,
					IFNULL(C.Date, '1900-01-01')	LastCommentDate,
					P.LastCommentID,
					(select COUNT(ID) from Comments where PostID = P.ID) CommentsCount,
					U.ID UserID, 
					U.Name UserName, 
					U.Title UserTitle,
					TCTP.TagCategoryID ParentID,					
					P.Closed,
					P.VisitsCount
			from	TagCategoriesToPosts TCTP
			join	Posts P on P.ID = TCTP.PostID
			join	Blogs B on B.ID = P.BlogID
			join	Users U on U.ID = B.UserID			
			left join
					Comments C on C.ID = P.LastCommentID
			where	TCTP.TagCategoryID = $id
					and	P.Text != ''			
			order by 
					case
						when P.Date > IFNULL(C.Date, '1900-01-01') then P.Date
						else IFNULL(C.Date, '1900-01-01')
					end desc";
		return mysql_query($query);
	}
	
	function getChildCategoriesByCategoryID($id, $order)
	{
		$parentFilter = $id ? "where	TC.ParentID = $id" : "";
		$query = "
			select	TC.ID, 
					TC.ParentID,
					TC.Name,
					TC.LastPostID,
					TC.LastCommentID,
					IFNULL(C.Date, '1900-01-01')	LastCommentDate,
					IFNULL(P.Date, '1900-01-01')	LastPostDate,					
					(	select	COUNT(P1.ID) 
						from	TagCategoriesToPosts TCTP1 
						join	Posts P1 on P1.ID = TCTP1.PostID 
								and P1.Visible=1 
						where	TagCategoryID=TC.ID)  ChildCount
			from	TagCategories TC
			left join
					Posts P on P.ID = TC.LastPostID
			left join
					Comments C on C.ID = TC.LastCommentID
			$parentFilter
			
			order	by " . ( $order == "date" ? " 
					case
						when P.Date > IFNULL(C.Date, '1900-01-01') then P.Date
						else IFNULL(C.Date, '1900-01-01')
					end Desc, " : "" ) . "
					TC.OrderID";
					
		return mysql_query($query);
	}
	
	function getPostByID($postid)
	{
		$query  = "
			select	P.*, 
					B.Name,
					B.UserID,
					B.CarID BlogCarID,
					U.Name UserName,
					U.Title UserTitle,
					TCTP.TagCategoryID ParentID,
					(	select COUNT(ID) from Rating where PostID = $postid	and IFNULL(CommentID, -1)=-1 )	Rating,
					(	select COUNT(ID) from Comments where PostID = $postid) CommentsCount
			from	Posts P			
			join	Blogs B on B.ID = P.BlogID 
			join	Users U on U.ID = B.UserID
			left join Cars C
					on C.ID = B.CarID
			left join
					TagCategoriesToPosts TCTP on TCTP.PostID = P.ID
			where	P.ID = $postid limit 1";		
		$result = mysql_query($query);
		if($row = mysql_fetch_assoc($result))
		{
			return $row;
		}
		return "";
	}
	
	function getBlogByID($blogid)
	{		
		$query  = "
			select	B.*,
					U.Name UserName
			from	Blogs B
			join	Users U on U.ID = B.UserID
			where	B.ID = $blogid";
		$result = mysql_query($query);
		if($row = mysql_fetch_assoc($result))
		{
			return $row;
		}
		return "";
	}
	
	/*
		C O M M E N T S
	*/
	
	function getCommentByID($id)
	{
		$query  = "
			select	C.*, 
					U.Name UserName,
					U.Title UserTitle,
					(	select COUNT(ID) from Rating where CommentID = C.ID )	Rating,
					P.Closed
			from	Comments C
			join	Users U	on U.ID = C.UserID
			join	Posts P on P.ID = C.PostID
			where	C.ID = $id" ;
		//echo $query;
		$result = mysql_query($query);
		if(mysql_num_rows($result) == 0)
		{
			return;
		}
		return mysql_fetch_assoc($result);
	}
	
	
?>