{literal}
<script>
	function sendMessage(targetUserID)
	{			
		$.ajax({	type: "POST",	
					url:"/response/messagingresponse.php",
					data: "action=sendprivate&norender=1&&userid="+targetUserID+"&headertext="+$("#tbPrivateMessageHeader").val()+"&messagetext="+$("#tbPrivateMessageText").val(),
					success: function(result) {						
						if(trim(result)=='ok')
						{						
							cancelMessage();
							alert('Ваше сообщение отправлено');
						}
						else if(trim(result)=='noheader')
						{
							alert('Не указан заголовок');
						}
						else if(trim(result)=='notext')
						{
							alert('Не указан текст сообщения');
						}
						else if(trim(result)=='error')
						{
							alert('Возникла проблема. Пожалуйста, попробуйте позже.');
						}							
					}
				});
	}

	function cancelMessage()
	{
		$("#divPrivateMessage").hide();
		$("#tbPrivateMessageHeader").val('');
		$("#tbPrivateMessageText").val('');
	}
</script>
{/literal}