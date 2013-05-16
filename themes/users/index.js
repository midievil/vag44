{literal}
<script>
	function authorizeUser(userid)
	{
		$.ajax({	type: "POST",	
					url:"/response/userresponse.php",
					data: "action=authorize&userid="+userid,
					success: function(result) {						
						window.location = window.location;
					}
				});
	}
	
	function showUsers(page)
	{
//		$("#tblUserList tr.user").remove();
		//$("#tblUserList").append("<tr class='loading'><td colspan='10' style='height:300px' align='center'><img src='/img/loading.gif' /></td></tr>");
		$.ajax({	type: "POST",	
					url:"/response/userresponse.php",
					data: "action=listusers&page="+page,
					success: function(result) {						
						$("#tblUserList tr.user").remove();
						$("#tblUserList tr.loading").remove();
						$("#tblUserList").append(result);
					}
				});
	}
	

	$(document).ready(function(){
		showUsers(1);
	});

</script>
{/literal}