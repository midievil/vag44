<?PHP

	require_once 'inc/class.db.php';
	require_once 'inc/class.fdb.php';


	class NotificationsDB
	{		
	
		public static function getNotificationByID($id)
		{
			$query  = "
				select	*
				from	Notifications
				where	ID = $id limit 1";		
			return fDB::fquery($query);			
		}
		
		public static function getNotificationsByUserID($userID)
		{
			$query  = "
				select	*
				from	Notifications
				where	UserID = $userID";
			return fDB::fqueryAll($query);
		}
		
		public static function deleteNotificationByID($id, $userID)
		{
			$query  = "
				delete
				from	Notifications
				where	ID = $id 
						AND UserID = $userID";
			return fDB::fexec($query);
		}
		
		public static function addNotification($userID, $text, $type, $date)
		{
			$text = str_replace("'", "''", $text);
			$query = "
				INSERT INTO	notifications (	UserID, Text, TypeID, Date ) VALUES ($userID, '$text', $type, '$date')";
				echo $query;
			return fDB::fexec($query);
		}
	}

	
	
?>