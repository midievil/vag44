<?PHP
	header('Content-type: text/html; charset=utf-8');	
	session_start();	

	if(!$_POST["action"] && !$_GET["action"])
	{
		return;
	}
	
	require_once("../constants.php");
	require_once("../db.php");
	require_once '../inc/class.db.php';
	require_once '../inc/class.fdb.php';	
	connectToDB();	
	require_once("../miscfunctions.php");
	require_once("../userlogic.php");
	require_once("../db/UserDB.php");
	require_once("../db/GalleryDB.php");
	
	$currentUser = User::CurrentUser();
	
	switch($_POST["action"]){
		case "addgallery":
			if($currentUser->IsLogged())
			{
				mysql_query("insert into Galleries (UserID, Name) values ($currentUser->ID, 'Фотоальбом')");
				if(mysql_affected_rows() == 1)
				{
					echo "ok";
					return;
				}
			}
			break;
			
		case "updateitemcomment":
			$comment = $_POST["text"];
			$id = $_POST["id"];
			mysql_query("update GalleryItems set Comment='$comment' where ID=$id and GalleryID in (select ID from Galleries where UserID = $currentUser->ID)");
			if(mysql_affected_rows() == 1)
			{
				echo "ok";
				return;
			}
			return;
		
		case "updategallerycomment":			
			$name = $_POST["text"];
			$id = $_POST["id"];
			mysql_query("update Galleries set Name='$name' where ID=$id and UserID=$currentUser->ID");
			if(mysql_affected_rows() == 1)
			{
				echo "ok";
				return;
			}
			return;
			
		case "changepublic":
			$id = $_POST["id"];
			$val = $_POST["val"];
			if(GalleryDB::changeGalleryPublic($id, $val))
			{
				echo "ok";
			}
			return;
	}

?>