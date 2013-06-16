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
		$("div.pagination ul li").removeClass('active');
		$("div.pagination ul li[page='"+page+"']").addClass('active');
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
	
	function slideShow(picID)
	{	
		$('#slideShowModal div.item').removeClass('active');
		$('#slideShowModal div.pic'+picID).addClass('active');
		$('#slideShowModal').modal();
	}

</script>
{/literal}