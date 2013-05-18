{literal}
<script>
function setMessageRead(id)
{
	$.ajax({	type:	"POST",	
		url:	"/response/messagingresponse.php",
		data:	"action=setread&id=" + id,
		success: function(result){
			if(trim(result) == "ok")
			{						
				$("#trMessage" + id + " a.read").hide();
			}			
		}
	});
}


function deletePrivateMessage(id)
{
	$.ajax({	type:	"POST",	
			url:	"/response/messagingresponse.php",
			data:	"action=deleteprivate&id=" + id,
			success: function(result){
				//alert(result);
				if(trim(result) == "ok")
				{						
					$("#trMessage" + id).hide();
				}
				else if(trim(result) == "notlogged")
				{
					alert("Кажется, вы вышли из системы. Попробуйте обновить страницу.");
				}
				else if(trim(result) == "error")
				{						
					alert("Что-то не сработало. Пожалуйста, сообщите администратору (обратная связь)");
				}
				
			}
		});
}

function answerMessage(id)
{
	$("#trAnswerMessage"+id).show();
}

function hideAnswer(id)
{
	$("#trAnswerMessage"+id).hide();
}

function cancelMessage(id)
{
	hideAnswer(id);
	$("#tbAnswerMessage"+id).val('');		
}

function sendMessage(targetUserID, sourceMessageID)
{	
	hideAnswer(sourceMessageID);
	$.ajax({	type: "POST",	
				url:"/response/messagingresponse.php",
				data: "action=sendprivate&userid="+targetUserID+"&headertext="+$("#tbAnswerText"+sourceMessageID).val()+"&messagetext="+$("#tbAnswerMessage"+sourceMessageID).val(),
				success: function(result) {					
					if(trim(result)=='error')
					{	
						alert('Возникла проблема. Пожалуйста, попробуйте позже.');							
					}
					else if(trim(result)=='noheader')
					{
						alert('Не указан заголовок');
					}
					else if(trim(result)=='notext')
					{
						alert('Не указан текст сообщения');
					}
					else
					{
						cancelMessage(sourceMessageID);
						$("#divMessagesOut a.start").after(result);
						alert('Ваше сообщение отправлено');
					}							
				}
			});
}

$(document).ready(function() {
	switchPrivateMessages("In");
});


var galleryItems = new Array();
</script>
{/literal}