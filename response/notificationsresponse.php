<?PHP
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	error_reporting(0);
		
	session_start();
	header('Content-type: text/html; charset=utf-8');
	
	if(!$_REQUEST["action"])
	{
		return;
	}
	
	chdir("..");		
	
	require_once "constants.php";
	require_once "db/NotificationsDB.php";
	require_once "userlogic.php";
	
	
	switch($_REQUEST["action"])
	{
		case "delete":
			$id = $_REQUEST["id"];
			$userID = $_REQUEST["userid"];
			if(User::CurrentUser()->IsLogged())
			{
				NotificationsDB::deleteNotificationByID($id, $userID);
				echo NotificationsDB::getNotificationsCountByUserID($userID);
				return;
			}
			return;
			
		case "read":
			$id = $_REQUEST["id"];
			$userID = $_REQUEST["userid"];
			if(User::CurrentUser()->IsLogged())
			{
				NotificationsDB::markReadNotificationByID($id, $userID);
				echo NotificationsDB::getNotificationsCountByUserID($userID);
				return;
			}
			return;
	}
?>