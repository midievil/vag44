function trim(string)
{
	return string.replace(/(^\s+)|(\s+$)/g, "");
}

function makeSelectionBold(controlName)
{
	//	ToDo: write normal code, nod indian.
	
	var selStart = $("#"+controlName).attr("selectionStart");
	var selEnd = $("#"+controlName).attr("selectionEnd");
	var text = $("#"+controlName).val();
	
	var textBefore = text.substr(0, selStart);
	var textAfter = text.substr(selEnd);
	var textSelected = text.substr(selStart, selEnd-selStart);
	
	$("#"+controlName).val(textBefore + "[b]" + textSelected + "[/b]" + textAfter)		
}

function createPersonalBlog(userID){
	$.ajax({	type:	"POST",	
				url:	"/response/blogresponse.php",
				data:	"action=createblog&userid="+userID,
				success: function(result){
					//alert(result);
					if(trim(result) == "ok")
					{						
						showResource('/res/userblogs.php', false);
					}
				}
			});
}

function createCarBlog(carID){
	$("#aCreateCarBlog"+carID).hide();
	$.ajax({	type:	"POST",	
				url:	"/response/blogresponse.php",
				data:	"action=createcarblog&carid="+carID,
				success: function(result){
					//alert(result);
					if(trim(result) == "ok")
					{
						window.location = window.location;
						showResource('/res/userblogs.php', false);
					}					
				}
			});
}

function savePost(blogid, tagcategoryid, postid)
{	
	$(".writecommentbutton").attr('disabled', 'disabled');
	$.ajax({	type:	"POST",	
				url:	"/response/blogresponse.php",
				data:	"action=writepost&blogid="+blogid+"&tagcategoryid="+tagcategoryid+
						"&title="+replaceSymbols($("#tbPostTitle").val())+"&text="+replaceSymbols($("#tbPostText").val())+
						"&galleryid="+$("#ddlGallery").val()+
						(postid != null ? ("&postid="+postid) : "" ),
				success: function(result){
					if(trim(result) == "ok")
					{
						//window.location = "?showblog="+blogid+"#post"+postid;
						window.location = "/category/"+tagcategoryid;
					}
					else
					{
						alert('Что-то не сработало. Попробуйте еще раз или обратитесь в раздел "Работа сайта". \nПриносим извинения за неудобства');
					}
					$(".writecommentbutton").removeAttr('disabled');
				}
			});				
}

function replaceSymbols(text)
{	
	return text.replace(/\%/g, '[percent]').replace(/\+/g, '[plus]').replace(/\'/g, '[singlequote]').replace(/\&/g, '[ampersand]');
}

var commentLock = "";
var currentListType = "";

function updateComment(commentID, postID)
{
	if(commentLock == "")
	{		
		commentLock = "lock";
		var text = replaceSymbols($("#tbEditComment"+commentID).val());
		$(".writecommentbutton").attr('disabled', 'disabled');
		
		var currentLevel = $("#trComment" + commentID).attr("level");
		
		var page = $("span.paging span.selected").attr("page");
		$.ajax({	type:	"POST",	
					url:	"/response/blogresponse.php",
					data:	"action=updatecomment&commentid="+commentID+"&text="+text+"&level="+currentLevel,
					success: function(result){
						//alert(result);
						if(trim(result) != "error")
						{			
							if(trim(result) == 'deleted')
							{
								$('div.comment[commentid="'+commentID+'"]').replaceWith('<div class="span10 comment row"><div class="well row">Комментарий удален</div></div>');
							}
							else
							{
								$('div.comment[commentid="'+commentID+'"]').replaceWith(result);
							}
							
							commentLock = "";
						}
					}
				});
	}
}


var loadingTdText = '<div class="progress progress-striped active span2"><div class="bar" style="width: 100%;"></div></div>';
var addedComments = 0;
function writeComment(postID)
{
	if(commentLock == "")
	{
		commentLock = "lock";
		
		var text = replaceSymbols($("#tbCommentForPost").val());
		if(text == "")
		{
			text = replaceSymbols($("#tbCommentForPostBottom").val());
		}
		
		$(".writecommentbutton").attr('disabled', 'disabled');
		
		$("#trCommentForPost").hide();
		$("#trCommentForPostBottom").hide();
		$("#tbCommentForPost").val('');
		$("#tbCommentForPostBottom").val('');
		
		addedComments++;
		$("div.commentlist div.comment:last").after(loadingTdText);		
		$(document).scrollTop($("div.commentlist div.comment:last").offset().top);
		
		//window.clearTimeout();
		
		$.ajax({	type:	"POST",	
					url:	"/response/blogresponse.php",
					data:	"action=writecomment&postid="+postID+"&text="+text,
					success: function(result){
						if(trim(result) != "error")
						{
							var parts = result.split('|#lstcmnt#|');							
							if(parts[1] != '-1')
							{
								lastCommentID = parts[1]; 
							}
							 
							$("div.commentlist div.comment:last").after(parts[0]);
							$("div.commentlist div.progress").hide();
							$("div.commentlist div.nocomments").hide();
							commentLock = "";
							//showComments(postID);							
							//showBlog(blogid);							
						}
						
						//window.setTimeout(function() { showNewComments(postID, lastCommentID); }, 10000);
					}
				});
	}
}

function writeCommentForComment(commentID, postID)
{	
	if(commentLock == "")
	{
		commentLock = "lock";
		var text = replaceSymbols($("#tbAnswerComment"+commentID).val());
		$(".writecommentbutton").attr('disabled', 'disabled');
		
		$("#trAnswerComment"+commentID).hide();
		$("#tbAnswerComment"+commentID).val('');
		
		addedComments++;
		if(currentListType == "tree")
		{
			$("tr.Comment"+commentID+"Child:last").after("<tr id='trNewComment" + addedComments + "'>"+loadingTdText+"</tr>");		
		}
		else
		{
			$("div.commentlist div.comment:last").after(loadingTdText);		
		}
								
		//if($("#trNewComment" + addedComments).offset().top  - $(window).scrollTop() > $(window).height())
		{
			//$(document).scrollTop($("#trNewComment"+addedComments).offset().top - $(window).height() + 100);
		}
		
		var currentLevel = $("#trComment" + commentID).attr("level");
		
		//window.clearTimeout();
		
		$.ajax({	type:	"POST",	
					url:	"/response/blogresponse.php",
					data:	"action=writecommentforcomment&postid="+postID+"&commentid="+commentID+"&text="+text+"&parentlevel="+currentLevel,
					success: function(result){
						
						var parts = result.split('|#lstcmnt#|');							
						if(parts[1] != '-1')
						{
							lastCommentID = parts[1]; 
						}
						
						if(trim(result) != "error")
						{		
							 
							$("div.commentlist div.comment:last").after(parts[0]);
							$("div.commentlist div.progress").hide();
							$("div.commentlist div.nocomments").hide();
							//$("#trNewComment"+addedComments).hide();
							//$("#trComment" + commentID).addClass("commentrelated");
							commentLock = "";
							
							//showComments(postID);							
							//showBlog(blogid);
						}
						
						//window.setTimeout(function() { showNewComments(postID, lastCommentID); }, 10000);
					}
				});
	}
}

function showComments(postID, listType, currentPage)
{
	if(currentPage == undefined)
	{
		currentPage = -1;
	}
	
	if(listType == undefined)
	{
		listType = '';
	}
	
	currentListType = listType;
	
	$("#trComments").html(loadingTdText);
	$("#trComments").show();
	
	$("span.paging span.selected").removeClass("selected");
	$("span.paging span[page="+currentPage+"]").addClass("selected");
	
	$("#trAnswerPostBottom").hide();
	$("#trPagingBottom").hide();
	
	$.ajax({	type:	"POST",	
				url:	"/response/blogresponse.php",
				data:	"action=showcomments&postid="+postID+"&listtype="+listType+"&page="+currentPage,
				success: function(result){
					if(trim(result) != "error")
					{	
						$("#trComments").html(result).ready(function() { if(page == 'last') {goBottom();} });
						$("#trComments").show();
						$(".writecommentbutton").removeAttr('disabled');
						commentLock = "";
												
						$("#tblPostComments").width($("#trComments").width());
						
						if($("#tblPostComments").height() > ($(window).height() - 100))						
						{
							$("#divAnswerPostBottom").show();
							$("#divPagingBottom").show();
						}
						
						if(page == 'last')
						{
							page = -1;							
						}
					}
				}
			});
}

function showNewComments(postID)
{
	if(currentListType == "list")
	{
		var oldLast = lastCommentID;
		$.ajax({	type:	"POST",	
					url:	"/response/blogresponse.php",
					data:	"action=shownewcomments&postid="+postID+"&lastcommentid="+lastCommentID,
					success: function(result){
						if(trim(result) != "error")
						{	
							if(oldLast != lastCommentID)
							{
								showNewComments(postID);
								return;
							}
							
							var parts = result.split('|#lstcmnt#|');							
							if(parts[1] != '-1')
							{								
								lastCommentID = parts[1];
							}
							
							$("div.commentlist div.comment:last").after(parts[0]).ready(function() { });
							window.setTimeout(function() { showNewComments(postID); }, 10000);
						}
					}
				});		
	}	
}

function getSelectedText() {
    if (window.getSelection) {
		txt = window.getSelection().toString();
	} else if (document.getSelection) {
		txt = document.getSelection();                
	} else if (document.selection) {
		txt = document.selection.createRange().text;
	}
	return txt;
}

var quote = '';
function copyToQuote(userName, type, id)
{
	quote = getSelectedText();
	quote = quote.replace(/\n/g, ' ');	
}

function quoteSelection(userName, type, id)
{	
	var source = $("#tbAnswer"+type+id).val();
	$("#tbAnswer"+type+id).val(source + (source != "" ? "\n" : "") + '[quote='+userName+']' + quote + '[/quote]');
	answerComment(id);
}

function quotePostSelection(userName, type, id)
{	
	var source = $("#tbCommentForPost"+id).val();
	$("#trCommentForPost"+id).show();	
	$("#tbCommentForPost"+id).val(source + (source != "" ? "\n" : "") + '[quote='+userName+']' + quote + '[/quote]');
}

function redirectToSearch()
{
	var findWhat = $('#tbSearch').val();
	if(trim(findWhat) != "")
	{
		window.location = "/search?req=" + findWhat;
	}
}

function searchKeyPressed(event)
{
	if(event.keyCode == 13)
	{
		redirectToSearch();
	}
}

function closePost(id)
{
	$.ajax({	type:	"POST",	
				url:	"/response/blogresponse.php",
				data:	"action=closepost&postid="+id,
				success: function(result){					
					alert(result);
				}
			});
}

function editComment(id)
{
	$("#trEditComment"+id).show();
	//$("#tbEditComment"+id).ckeditor( function() { });
}
function hideEditComment(id)
{
	$("#trEditComment"+id).hide();
}

function answerComment(id)
{
	$("#trAnswerComment"+id).show();
	//$("#tbAnswerComment"+id).ckeditor( function() { });
}
function hideAnswerComment(id)
{
	$("#trAnswerComment"+id).hide();	
}