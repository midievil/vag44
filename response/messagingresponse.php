<?PHP
	error_reporting(0);

	session_start();
	header('Content-type: text/html; charset=utf-8');
	
	$action = $_POST["action"];
	if(!$action)
	{
		return;
	}
	
	chdir('..');
	
	require_once "constants.php";	
	require_once "miscfunctions.php";	
	require_once "db.php";
	connectToDB();		
	require_once "userlogic.php";	
	require_once "messaginglogic.php";	

	require_once 'inc/class.templater.php';
	
	switch($action)
	{
		case "sendprivate":
			$currentUserID = $_SESSION["loggeduserid"];
			if(!$currentUserID)
			{
				echo "notlogged";
				return;
			}
			
			$headertext = trim($_POST["headertext"]);
			$messagetext = trim($_POST["messagetext"]);
			$userid = trim($_POST["userid"]);
			
			$norender = $_POST["norender"];
						
			if($headertext=="")
			{
				echo "noheader";
				return;
			}
			else if($messagetext=="")
			{
				echo "notext";
				return;
			}
			else if (!$userid)
			{
				echo "nouser";
				return;
			}
			
			$date = getCurrentDateText();
			
			$query = "
				insert	into PrivateMessages (
						FromUserID, 
						ToUserID, 
						Header, 
						Text,
						PostDate ) 
				values(	$currentUserID,
						$userid,
						'$headertext',
						'$messagetext',
						'$date')";			
			
			if(fDB::fexec($query))
			{
				if($norender)
				{
					echo "ok";
				}
				else
				{
					$message = new PrivateMessage(fDB::lastID());
					templater::assign('message', $message);
					templater::display('controls/privateMessage.html');
				}				
			}
			else
			{
				echo "error";
			}			
			return;
			
		case "deleteprivate":
			$id = trim($_POST["id"]);
			$currentUserID = $_SESSION["loggeduserid"];			
			if($currentUserID)
			{
				$query = "
					UPDATE	PrivateMessages
					SET		DeletedBySender		= CASE WHEN FromUserID = $currentUserID THEN 1 ELSE DeletedBySender END,
							DeletedByReceiver	= CASE WHEN ToUserID = $currentUserID THEN 1 ELSE DeletedByReceiver END
					WHERE	ID=$id";
				
				if(fDB::fexec($query))
				{
					echo "ok";
				}
				else
				{				
					echo "error";
				}
				return;
			}
			else
			{
				echo "notlogged";
				return;
			}
			return;
			
		case "setread":
			$id = trim($_REQUEST["id"]);
			$currentUser = User::CurrentUser();
			if($currentUser->IsLogged())
			{
				$query = "
				update	PrivateMessages
				set		`Read` = 1
				where	ToUserID = $currentUser->ID
						and	ID = $id";
				if(fDB::fexec($query))			
				{
					echo "ok";
				}
				else
				{
					echo "error";
				}
			}
			else
			{
				echo "notlogged";
				return;
			}
			return;
			
		case "addtip":
			$header = $_POST["header"];
			$text = $_POST["text"];
			$id = $_POST["id"];
			
			if($id)
			{
				if($text)
				{
					$query = "
						update Tips
						set		Title = '$header',
								Text = '$text'
						where	ID = $id";
				}
				else
				{
					$query = "
						delete from Tips						
						where	ID = $id";
				}
			}			
			else
			{
				$query = "insert into Tips(Title, Text) values ('$header','$text')";
			}
			fDB::fexec($query);
			echo "ok";
			return;
	}
?>