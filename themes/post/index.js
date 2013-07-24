<script>

	var page = {$commentsPaging->CurrentPage};
	var listType = '{$currentUser->ListType}';
	var postid = {$post->ID};
{literal}

	function AnswerPost()
	{
		$("#trCommentForPost").show();
	}
	
	function AnswerPostBottom()
	{
		$("#trCommentForPostBottom").show();
	}
	
	function CancelAnswerPost()
	{
		$("#trCommentForPost").hide();
	}
	
	function CancelAnswerPostBottom()
	{
		$("#trCommentForPostBottom").hide();
	}
	
	function ChangePage(page)
	{
		showComments(postid, listType, page);
		$("div.pagination ul li").removeClass('active');
		$("div.pagination ul li[page='"+page+"']").addClass('active');
	}
	
	function slideShow(picID)
	{	
		//$('#modal-gallery modal-image').innerHtml('<img src="/" />');
		//$('#modal-gallery div.item').removeClass('active');
		//$('#modal-gallery div.pic'+picID).addClass('active');
		//$('#modal-gallery').modal();
	}	
	
	function EnlargePic(picUrl)
	{	
		$('#modal-enlarge .modal-image').html('<img src='+picUrl+' />');
		$('#modal-enlarge').modal();	
		
	}
</script>
{/literal}