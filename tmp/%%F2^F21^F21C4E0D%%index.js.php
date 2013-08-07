<?php /* Smarty version 2.6.22, created on 2013-08-01 17:06:58
         compiled from users/index.js */ ?>
<?php echo '
<script>
	function showUsers(page)
	{
		$("div.pagination ul li").removeClass(\'active\');
		$("div.pagination ul li[page=\'"+page+"\']").addClass(\'active\');
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
		$(\'#slideShowModal div.item\').removeClass(\'active\');
		$(\'#slideShowModal div.pic\'+picID).addClass(\'active\');
		$(\'#slideShowModal\').modal();
	}

</script>
'; ?>