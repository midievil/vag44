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
	
	function DeleteNotification(id, userid)
	{
		$.ajax(	{	type: "POST",
						url:"/response/notificationsresponse.php",
						data:"action=delete"+
							"&id="+id+"&userid="+userid,
							success:	function(result){
								var oldCount = $("#aNotificationsCount").text() * 1;
								oldCount -= 1;
								$("#aNotificationsCount").text(oldCount);
						}
					});
	}
	
	$(document).ready(function() {
		$('#myCarousel').carousel({
		     interval: 10000
		    });
	})
		
</script>
{/literal}