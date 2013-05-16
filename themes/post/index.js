
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
		
</script>
{/literal}