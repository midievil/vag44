<?PHP

	$blogid = mLogic::$urlVariables['blogid'];
	$userid = $_SESSION["loggeduserid"];
	
	global $breadCrumbs;
	
	$blog = new Blog($blogid);
	
	if(empty($blog->ID))
	{
		templater::assign('message', 'Такого блога нет. Кажется, вам кто-то дал неверный адрес.');
	}
	else
	{	
		if($blog->UserID == $userid)
		{
			$breadCrumbs[] = new BreadCrumb('Ваши блоги', '/blogs/user/' . $blog->UserID); 		
		}
		else
		{
			$breadCrumbs[] = new BreadCrumb('Блоги пользователя <b>' . $blog->User()->Name . '</b>', '/blogs/user/' . $blog->UserID);		
		}
		
		$breadCrumbs[] = new BreadCrumb($blog->Name, '');
		templater::assign('breadCrumbs', $breadCrumbs);

		templater::assign('posts', $blog->Posts());		
	}
		
	templater::display();
?>

<!-- <table> -->
<!-- 	<tr> -->
<!-- 		<td class='innercontent'> -->

// <?PHP
	
// 	$result = getPosts($blogid);
// 	if(mysql_num_rows($result) == 0)
// 	{
// 		echo "Здесь пока пусто.";
// 	}
// 	else
// 	{
// 		while($row = mysql_fetch_assoc($result))
// 		{
// 			showPostFace($row, "list", true);
// 		}
// 	}
	
// ?>

<!-- 		</td> -->
<!-- 	</tr> -->
<!-- </table> -->