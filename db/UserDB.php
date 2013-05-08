<?PHP

	

	class UserDB
	{
		public static function loadUserByID($id)
		{
			$userRows = $_SESSION["userrows"];
		
			if($userRows[$id])
			{
				return $userRows[$id];
			}
			elseif($id)
			{
				$query = "
				select	U.ID,
						U.Name,
						U.Title,
						U.FirstName,
						U.LastName,
						U.From,
						U.GroupID,
						U.Password,
						U.Email,
						U.ShowEmail,
						U.Phone,
						U.ICQ,
						U.Social,
						U.Visible,
						U.ListType,
						U.PageSize,
						U.CategoriesOrder,
						U.LastVisit,
						U.BirthDate,
						U.RegisterDate,
						U.Gender,
						G.DisplayName GroupName,
						G.Name GroupInnerName,
						count(R.ID) Rating
				from	Users U
				join	UserGroups G on G.ID = U.GroupID
				left join
						Rating R on R.ToUserID = U.ID
				where	U.ID=$id
				group by
						U.ID,
						U.Name,
						U.Title,
						U.FirstName,
						U.LastName,
						U.From,
						U.GroupID,
						U.Password,
						U.Email,
						U.ShowEmail,
						U.Phone,
						U.ICQ,
						U.Social,
						U.Visible,
						U.ListType,
						U.PageSize,
						U.CategoriesOrder,
						U.LastVisit,
						U.BirthDate,
						U.RegisterDate,
						G.DisplayName,
						G.Name";
				$result = fDB::fquery($query);		
	
				if($result)
				{
					$userRows[$id] = $result;
					$_SESSION["userrows"] = $userRows;
					return $userRows[$id];
				}
			}			
			return false;
		}
		
		public static function getUserList($page)
		{
			if($page)
			{
				$paging = "Limit " . (($page-1) * 20) . ", 20";
			}
			$query = "
			select	U.ID,
					U.Name,
					U.Title,
					U.FirstName,
					U.LastName,
					U.From,
					U.GroupID,
					U.Password,
					U.Email,
					U.ShowEmail,
					U.Phone,
					U.ICQ,
					U.Social,
					U.Visible,
					U.ListType,
					U.PageSize,
					U.CategoriesOrder,
					U.LastVisit,
					U.BirthDate,
					U.RegisterDate,
					U.Gender,
					G.DisplayName GroupName,
					G.Name GroupInnerName,
					count(R.ID) Rating
			from	Users U
			join	UserGroups G on G.ID = U.GroupID
			left join
					Rating R on R.ToUserID = U.ID
			where	Visible = 1
			group by
					U.ID,
					U.Name,
					U.Title,
					U.FirstName,
					U.LastName,
					U.From,
					U.GroupID,
					U.Password,
					U.Email,
					U.ShowEmail,
					U.Phone,
					U.ICQ,
					U.Social,
					U.Visible,
					U.ListType,
					U.PageSize,
					U.CategoriesOrder,
					U.LastVisit,
					U.BirthDate,
					U.RegisterDate,
					G.DisplayName,
					G.Name
			order	by CAST(U.Name AS CHAR CHARACTER SET utf8)
			$paging	";
			return fDB::fqueryAll($query);
		}
		
		public static function getUserRatingByID($id)
		{	
			if($id)
			{
				$query = "
					select	U.ID FromUserID,
							U.Name FromUserName,
							Count(*) Value
					from	Rating	R
					join	Users U on U.ID = R.FromUserID
					where	ToUserID = $id
					group by U.ID,
							U.Name
					";
				return	fDB::fqueryAll($query);
			}
		}
	}

	function removeUserFromCache($id)
	{
		$userRows = $_SESSION["userrows"];
		$userRows[$id] = "";
		$_SESSION["userrows"] = $userRows;
	}
	
	function getUsersCount()
	{	
		$query = "
			select	COUNT(ID)	Cnt
			from	Users
			where	Visible = 1";
		return fDB::fscalar($query);
	}
	
	
	function getUsersWithBirthday()
	{
		$date = getCurrentDay();
		$date = explode('-', $date);
		
		$day = $date[2];
		$month = $date[1];
		$year = $date[0];
				
		$query = "
					select	U.Name,
							P.ID PostID
					from	Users U
					left join 
							BirthdayPosts BP on BP.UserID = U.ID
					left join
							Posts P on	P.ID = BP.PostID
										and	DAYOFMONTH(P.Date) >= $day
										and	MONTH(P.Date) = $month
										and	YEAR(P.Date) = $year
					where	DAYOFMONTH(BirthDate) = $day
							and	MONTH(BirthDate) = $month";
							
		//$result = mysql_query($query);
		$result = fDB::fqueryAll($query);
		return $result;
	}
?>