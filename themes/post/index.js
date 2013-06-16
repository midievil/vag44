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
		$('#slideShowModal div.item').removeClass('active');
		$('#slideShowModal div.pic'+picID).addClass('active');
		$('#slideShowModal').modal();
	}	
	
	function EnlargePic(picUrl)
	{	
		$('#enlargePicModal img.picture').attr('src', picUrl);
		$('#enlargePicModal').modal();
	}
</script>
{/literal}