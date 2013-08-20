<?PHP
	
	class UserDB
	{
		public static function loadUserByID($id)
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
						U.Rating,
						U.ShowChat,
						U.SoundChat,
						U.CompactChat,
						U.EnterChat
				from	Users U
				join	UserGroups G on G.ID = U.GroupID				
				where	U.ID=$id";
			$result = fDB::fquery($query);		

			if($result)
			{
				return $result;
			}
					
			return false;
		}
		
		public static function getUserList($page = 0)
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
					U.Rating,
					U.ShowChat,
					U.SoundChat,
					U.CompactChat,
					U.EnterChat
			from	Users U
			join	UserGroups G on G.ID = U.GroupID
			where	Visible = 1			
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
		$date = DateFunctions::getCurrentDay();
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