<?PHP
	if(!isset($_SESSION["loggeduser"]) || !isset($_SESSION["loggeduserid"]))
	{
		echo "<a class='messageheader'>Ой!</a><br /><br />";
		return;
	}	

	$userid = $_SESSION["loggeduserid"];
	$blogid = $_GET["showblog"];
	if(!$blogid)
	{	
		$blogid = $_GET["blogid"];
	}
	
	$blog = getBlogByID($blogid);
	$blogName = $blog["Name"];
	
	$path = "<a href='/'>Главная</a> <a>›</a> ";
	if($blog["UserID"] == $userid)
	{
		$path .= "<a href='/blogs/user/" . $blog["UserID"] . "'>Ваши блоги</a>";
	}
	else
	{
		$path .= "<a href='/blogs/user/" . $blog["UserID"] . "'>Блоги пользователя " . $blog["UserName"] . "</a>";
	}
	
	$path .= " <a>›</a> <a>$blogName</a>";
	
	drawPageTitle("blog", $blogName, $content, $comment, $path);
?>

<table>
	<tr>
		<td class='innercontent'>

<?PHP
	
	$result = getPosts($blogid);
	if(mysql_num_rows($result) == 0)
	{
		echo "Здесь пока пусто.";
	}
	else
	{
		while($row = mysql_fetch_assoc($result))
		{
			showPostFace($row, "list", true);
		}
	}
	
?>

		</td>
	</tr>
</table>