<?php /* Smarty version 2.6.22, created on 2013-08-01 17:09:03
         compiled from user/index.js */ ?>
<?php echo '
<script>
	function sendMessage(targetUserID)
	{			
		$(".modal-footer .btn-primary").addClass(\'disabled\');
		$.ajax({	type: "POST",	
					url:"/response/messagingresponse.php",
					data: "action=sendprivate&norender=1&&userid="+targetUserID+"&headertext="+$("#tbPrivateMessageHeader").val()+"&messagetext="+$("#tbPrivateMessageText").val(),
					success: function(result) {						
						if(trim(result)==\'ok\')
						{						
							$(\'#privateMessage\').modal(\'hide\')
							$(".modal-footer .btn-primary").removeClass(\'disabled\');
						}
						else if(trim(result)==\'noheader\')
						{
							alert(\'Не указан заголовок\');
						}
						else if(trim(result)==\'notext\')
						{
							alert(\'Не указан текст сообщения\');
						}
						else if(trim(result)==\'error\')
						{
							alert(\'Возникла проблема. Пожалуйста, попробуйте позже.\');
						}							
					}
				});
	}

</script>
'; ?>