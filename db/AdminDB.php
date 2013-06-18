<?php
	require_once 'inc/class.db.php';
	require_once 'inc/class.fdb.php';
	
	
	class AdminDB
	{
	
		public static function getFeedBackByID($id)
		{
			$query = "
					select	F.*,
							U.Name UserName
					from	FeedBacks F
					left join
							Users U on U.ID = F.UserID
					where	F.ID = $id";
			
			return fDB::fquery($query);
		}
		
		public static function getFeedBacks()
		{
			$query = "
				select	F.*,
						U.Name UserName
				from	FeedBacks F
				left join
						Users U on U.ID = F.UserID
				where	`Read` = 0";
			return fDB::fqueryAll($query);
		}
		
		public static function setFeedbakRead($id)
		{
			$query = "
				update	FeedBacks
				set		`Read` = 1
				where	ID = $id";
			fDB::fexec($query);
				
		}
	}
?>