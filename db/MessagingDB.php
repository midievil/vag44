<?php
	require_once 'inc/class.db.php';
	require_once 'inc/class.fdb.php';

	class MessagingDB
	{

		public static function checkPrivateMessages($userid)
		{
			$query = "
			select	Count(*) Count
			from	PrivateMessages
			where	ToUserID = $userid
					and DeletedByReceiver = 0
					and	`Read` = 0";
			$result = fDB::fquery($query);
			return $result['Count'];
			
		}
		
		public static function getPrivateMessageByID($id)
		{
			$currentUser = User::CurrentUser();
			if($currentUser->IsLogged())
			{
				$query = "
				select	M.*,
						TU.Name ToUserName,
						FU.Name FromUserName
				from	PrivateMessages M
				join	Users TU on TU.ID = M.ToUserID
				join	Users FU on FU.ID = M.FromUserID
				where	M.ID = $id
				order by PostDate desc";
		
				return fDB::fquery($query);
			}
			return "";
		}
		
		public static function getPrivateMessages($mode)
		{
			if(strtolower($mode) == "in")
			{
				$userField = "ToUserID";
				$deletedField = "DeletedByReceiver";
			}
			else
			{
				$userField = "FromUserID";
				$deletedField = "DeletedBySender";
			}
			
			$currentUser = User::CurrentUser();
			if($currentUser->IsLogged())
			{
				$query = "
					select	M.*,
							TU.Name ToUserName,
							FU.Name FromUserName
					from	PrivateMessages M
					join	Users TU on TU.ID = M.ToUserID
					join	Users FU on FU.ID = M.FromUserID
					where	$userField = $currentUser->ID
							and $deletedField = 0
					order by PostDate desc";
				return fDB::fqueryAll($query);
			}
				
			return "";
		}
	}
?>