{literal}
<script>
	function ShowRenameBlog()	
	{
		$(".renameblog").show();
	}
	
	function RenameBlog(blogID)
	{
		var newName = replaceSymbols($("input.renameblog").val());
		if(newName == '')
		{
			alert('Имя не должно быть пустым');
			return;
		}
		
		$.ajax({	type:	"POST",	
				url:	"/response/blogresponse.php",
				data:	"action=renameblog&id="+blogID+"&newname="+newName,
				success: function(result){
					//alert(result);
					if(trim(result) == "ok")
					{
						$("div.categorytitle td.title").text(newName);
						$(".renameblog").hide();
					}					
				}
			});
	}
</script>
{/literal}