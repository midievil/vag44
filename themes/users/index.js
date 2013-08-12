{literal}
<script>
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

						$('[rel=\"popover\"]').popover({'placement':'top'});
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