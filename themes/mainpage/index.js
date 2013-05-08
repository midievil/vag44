{literal}

<script>
	var categoryImages = new Array();

	function dontRemindService(id)
	{
		if(confirm("Точно больше не напоминать?"))
		{
			$.ajax(	{	type: "POST",
						url:"/response/carresponse.php",
						data:"action=dontremindservice"+
							"&id="+id,
						success:	function(result){
							if(trim(result)=='ok')
							{
								alert("Мы больше не будем напоминать вам об этом");
							}					
						}
					});
		}
	}
</script>
{/literal}