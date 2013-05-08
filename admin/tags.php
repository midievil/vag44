<?PHP
	if(!User::CurrentUser()->IsAdmin())
	{
		return;
	}
?>

<script>
	function updateTagCategory(id)
	{		
		$.ajax({	type:	"POST",	
				url:	"/response/blogresponse.php",
				data:	"action=updatetag&id="+id+"&name="+$("#tbEditTagCategory"+id).val(),
				success: function(result){
					result = trim(result);
					if(result == "ok")
					{	
						window.location = '/admin/tags.php';
					}
				}
			});
	}
	
	function addTagCategory(parentID)
	{
		$.ajax({	type:	"POST",	
				url:	"/response/blogresponse.php",
				data:	"action=addtag&name="+$("#tbEditTagCategoryAdd"+parentID).val()+"&parentid="+parentID,
				success: function(result){
					alert(result);
					result = trim(result);
					if(result == "ok")
					{						
						showResource('/admin/tags.php', false);
					}
				}
			});
	}
	
	function assignTagCategoryToPost(id)
	{
		alert("action=assigntagcategorytopost&postid="+id+"tagcategoryid="+$("#tbAssignTagCategoryToPost"+id).val());
		$.ajax({	type:	"POST",	
				url:	"/response/blogresponse.php",
				data:	"action=assigntagcategorytopost&postid="+id+"&tagcategory="+$("#tbAssignTagCategoryToPost"+id).val(),
				success: function(result){
					alert(result);
					result = trim(result);
					if(result == "ok")
					{						
						showResource('/admin/tags.php', false);
					}
				}
			});
	}
</script>
	
<?PHP	
	function showTagCategories($parentid, $level)
	{
		$tab = "";				
		for($i=1; $i<$level;$i++)
		{
			$tab = "&nbsp;&nbsp;$tab";
		}
	
		$query = "
			select	*
			from	TagCategories
			".	(	$parentid ? "
			where	ParentID = $parentid"
					: "
			where	ParentID is null");
		$tags = mysql_query($query);
		//writeLog($query);
		if($tags)
		{
			while($row = mysql_fetch_assoc($tags))
			{
				$id = $row["ID"];				
				
				echo "<br />$tab<input type='text' id='tbEditTagCategory$id' value='".$row["Name"]."' /> <a href='#' onclick='updateTagCategory($id)'>Сохранить</a>";
				
				showTagCategories($id, ($level+1));
			}
		}		
		
		echo "<br />$tab<input type='text' id='tbEditTagCategoryAdd$parentid' value='' /> <a href='#' onclick='addTagCategory($parentid)'>Добавить</a>";
	}
	
	function showUncategorizedPost()
	{
		$query = "
			select	P.*
			from	Posts P
			left join
					TagCategoriesToPosts TCTP on TCTP.PostID = P.ID
			where	TCTP.PostID is null";
		
		$posts = mysql_query($query);
		//writeLog($query);
		if($posts)
		{
			while($row = mysql_fetch_assoc($posts))
			{
				$id = $row["ID"];
				$text = $row["Text"];
				$title = $row["Title"];
				echo "
					<br />
					<b>$title</b><br />
					$text
					<br />
					Тэги: <input type='text' id='tbAssignTagCategoryToPost$id' value='' /> <a href='#' onclick='assignTagCategoryToPost($id)'>Сохранить</a>
					<br />
					<br />";
			}
		}
	}
?>

<a class='messageheader'>Категории тэгов</a>

<?PHP	
	showTagCategories(0, 1);
?>

<br />
<br />
<a class='messageheader'>Неклассифицированные посты</a>
<?PHP	
	showUncategorizedPost();
?>