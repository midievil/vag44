<?PHP	
	if($_GET["userid"])
	{
		$blogUser = new User($_GET["userid"]);		
	}
	else
	{
		$blogUser = $currentUser;
	}
		
	$path = "<a href='/'>Главная</a> <a>›</a> ";
	if($blogUser->ID == $currentUser->ID)
	{
		$title = "Ваши блоги";
		$path .= "<a>Ваши блоги</a>";
	}
	else
	{
		$title = "Блоги пользователя " . $blogUser->Name;
		$path .= "<a>Блоги пользователя " . $blogUser->Name . "</a>";
	}
	$content .= "Есть два типа блогов: автомобильные и персональные. 
		<br /><br />Автомобильный привязан к вашему автомобилю, их может быть <nobr>несколько &mdash; по одному</nobr> на каждый автомобиль. 
		<br /><br />Персональный блог предназначен для личных записей, а также записей, не связанных непосредственно с вашим автомобилем.";
	
	drawPageTitle("userblogs", $title, $content, $comment, $path);
	

?>

<table>
	<tr>
		<td class='innercontent'>

<?PHP	
	$blogs = getUserBlogs($blogUser->ID);
	
	while($row = mysql_fetch_assoc($blogs))
	{
		echo "<a href='/blog/".($row["ID"])."'>".($row["Name"])."</a><br />";
	}
	
	if(mysql_num_rows($blogs) == 0)
	{
		echo "пока нет блогов";		
	}

	if($currentUser->IsLogged() && $blogUser->ID == $currentUser->ID)
	{
		if(!personalBlogExists($blogUser->ID))
		{
			echo "У вас нет персонального блога. <a href='#' onclick='createPersonalBlog($blogUser->ID);'>Создать?</a>";
		}
		
		$carRows = getCarsWithNoBlogs($blogUser->ID);
		foreach($carRows as $row)
		{
	
			$carid = $row["ID"];
			
			$car = CarDB::getCarByID($carid);
			
			echo "<br />У вас есть автомобиль ".(getCarDescriptionByID($carid)).", но нет блога. <a class='hand' onclick='createCarBlog($carid)' id='aCreateCarBlog$carid'>Создать</a>?";
		}			
		
	}		
?>
		</td>
	</tr>
</table>