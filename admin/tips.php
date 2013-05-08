<?PHP
	drawPageTitle("tips", "Советы", "");
?>
<script>
	function addTipToDB()
	{
		$.ajax(	{	type: "POST",	
							url:"/response/messagingresponse.php",
							data:"action=addtip&header="+$("#tbHeader").val()+"&text="+$("#tbText").val()+(editedTipID!=undefined ? ("&id="+editedTipID ) : ""),
							success:	function(result){											
											if(trim(result) == "ok")
											{
												window.location = window.location;
											}
											else
											{	
												$("#aError").text(result);
											}
										}
					}
					);
	}
	
	var editedTipID = undefined;
	function editTip(id, title, text)
	{
		editedTipID = id;
		$("#tbHeader").val(title);
		$("#tbText").val(text);
		$('#divAdd').show();
	}
	
	function addTip()
	{
		editedTipID = undefined;
		$("#tbHeader").val("");
		$("#tbText").val("");
		$('#divAdd').show();
	}
</script>
<table>
	<tr>
		<td class='innercontent'>
			<a id='aError'></a>
			<a class='hand' onclick="addTip();">Добавить</a>
			<div id='divAdd' class='hidden'>
				Заголовок:<br /><input id='tbHeader' /> <br />
				Текст:<br /><textarea id='tbText'></textarea><br />
				<a class='hand' onclick="addTipToDB();">ок</a>
				<a class='hand' onclick="$('#divAdd').hide()">отмена</a>
			</div>
<?PHP
	
	$tips = getTips();
	while($tip = mysql_fetch_assoc($tips))
	{
		
		echo "<br /><a onclick=\"editTip(" .  $tip["ID"] . ", '" .  $tip["Title"] . "', '" .  $tip["Text"] . "')\">" . $tip["Title"] ."</a>";
	}
?>
		</td>
	</tr>
</table>