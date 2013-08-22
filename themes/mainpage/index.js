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
	
	function DeleteNotification(id, userid, wasRead)
	{
		$.ajax(	{	type: "POST",
						url:"/response/notificationsresponse.php",
						data:"action=delete"+
							"&id="+id+"&userid="+userid,
							success:	function(result){
								if(!isNaN(result*1))
								{
									$("#aNotificationsCount").text(result);
								}
						}
					});
	}
	
	function MarkReadNotification(id, userid, redirectUrl)
	{
		$.ajax(	{	type: "POST",
						url:"/response/notificationsresponse.php",
						data:"action=read"+
							"&id="+id+"&userid="+userid,
							success:	function(result){
								if(!isNaN(result*1))
								{
									$("#aNotificationsCount").text(result);								
									
									$("#divNotification"+id).removeClass('alert-success');
									$("#divNotification"+id).removeClass('alert-info');
									$("#divNotification"+id).removeClass('alert-error');
									
									$("#divNotification"+id+" a.read").hide();
									
									if(redirectUrl != undefined)
									{
										window.location = redirectUrl; 
									}
								}
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