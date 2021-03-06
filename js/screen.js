var isChat = false;
function showRegister(){
	$("#divMain").load("/res/register.php");
	return false;
}

function showGreetings(){
	$("#divMain").load("/res/greetings.php");
	$.cookie("sawGreetings", "1", { expires: 365 } );
}

function showContent()
{
	$.ajax({	type: "POST",	
				url:"/res/maincontent.php",				
				success: function(result){
					//console.log(result);
					$("#divMain").html(result);
				}
			});	
}

function showLeft()
{
	$.ajax({	type: "POST",	
				url:"/res/leftmenu.php",				
				success: function(result){
					//console.log(result);
					$("#divLeft ").html(result);
				}
			});	
}

function showBlog(blogid, postid)
{		
	$.ajax({	type: "POST",	
				url:"/res/viewblog.php",
				data: "blogid="+blogid,
				success: function(result){
					$("#divMain").html(result);
					
					if(postid != null)
					{
						var url = $("#post"+postid).attr("value");
						//alert(url);
						var destination = $(url).offset().top; 
						$("body").animate({ scrollTop: destination}, 1100 );
					}
				}
			});
}

function showResource(url, needLogin){
	needLogin = false;
	if($.cookie('vag44login') != null || (needLogin != null && needLogin == false))
	{
		$("#divMain").load(url);
	}
	else
	{
		$("#divMain").load("/res/notlogged.html");
	}
	return false;
}

function viewProfile(userid)
{
	$.ajax({	type: "POST",	
				url:"/res/viewprofile.php",
				data: "userid="+userid,
				success: function(result){
					$("#divMain").html(result);
				}
			});
}

function alignElement(elementName, align)
{
	var left = ($(window).width() - $("#"+elementName).width() - 35);	
	$("#"+elementName).attr("style", "left:"+left);
}

function alignElementToBottom(elementName)
{
	var top = $(document).height() - $("#"+elementName).height() - 35;	

	$("#"+elementName).attr("style", "top:"+top);
}

function toggleVisibility(elementIndex)
{
	if($("#"+elementIndex).is(':visible'))
	{
		$("#"+elementIndex).hide();
	}
	else
	{		
		$("#"+elementIndex).show();
	}
}

function toggleCategoryVisibility(elementIndex)
{
	if($(".trCategory"+elementIndex+"Child").is(':visible'))
	{
		$("#btnShowCategory"+elementIndex).attr('src', '/img/btnShow.gif');
		$(".trCategory"+elementIndex+"Child").hide();
	}
	else
	{
		$("#btnShowCategory"+elementIndex).attr('src', '/img/btnHide.gif');
		$(".trCategory"+elementIndex+"Child").show();
	}
}

function redirectToMainPage() {
    window.location = '/';
}

function redirectToCategory(elementIndex, event)
{
	if(event.which == 1)
	{
		window.location = '/category/' + elementIndex;
	}
}

function highlightTableRow(rowName)
{
	$("#"+rowName).addClass('highlighted');
	
	if(categoryImages != undefined && categoryImages[rowName] != undefined)
	{
		$("#"+rowName+" td.content_left").css( { 'background-image' : 'url(/img/categoryimages/'+categoryImages[rowName]+')', 'background-position' : 'bottom left', 'background-repeat' : 'no-repeat' } );
	}
}

function unhighlightTableRow(rowName)
{
	$("#"+rowName).removeClass('highlighted');
	$("#"+rowName+" td.content_left").removeAttr('style');
}

function enlargeUserPic(userPicID)
{	
	var position = $("#"+userPicID).offset();	
	$("#divUserPic").html("<table cellspacing='0' cellpadding='0' onmouseout='hideEnlargedUserPic()'><tr><td><img class='userphoto' src='"+$("#"+userPicID).attr('src')+"' /></td></tr></table>");	
	$("#divUserPic").show();
	
	var left = position.left + $("#"+userPicID).width() / 2 - $("#divUserPic").width()/2;
	var top = position.top + $("#"+userPicID).height() / 2 - $("#divUserPic").height()/2;
	
	$("#divUserPic").css({ "top": top });
	$("#divUserPic").css({ "left": left });
	$("#divUserPic").show();
}

function hideEnlargedUserPic()
{
	$("#divUserPic").hide();
}

function hidePostComments()
{
	$("#trComments").hide(); 
	$("a.HideComments").hide();
	$("a.ShowComments").show();	
}

function showPostComments()
{
	$("#trComments").show(); 
	$("a.HideComments").show();
	$("a.ShowComments").hide();
}

function showComentsList(postid)
{
	$("div.paging").show();
	showPostComments();
	showComments(postid, "list", 1);
	$("#aCommentsTree").show();
	$("#aCommentsList").hide();
}

function showComentsTree(postid)
{
	$("div.paging").hide();
	showPostComments();
	showComments(postid, "tree");
	$("#aCommentsTree").hide();
	$("#aCommentsList").show();
}

function highlightParentComment(commentID, unhighlight)
{	
	$(".commentrelated").removeClass("commentrelated");
	$("#trComment"+commentID).addClass("commentrelated");
}

function goTop()
{
	$(document).scrollTop(0);
}

function goBottom()
{
	//$(document).scrollTop($(document).height()+1000);
	$("html, body").animate({ scrollTop: $(document).height()+10000 }, "slow");
}

function showPopup(sender, content)
{
	if(content != '')
	{
		$("#tdPopupInfo").html(content);
		
		var position = $("#"+sender.id).offset();
		var left = position.left + sender.offsetWidth / 2 - $("#divPopup").width()/2;
		var top = position.top - $("#divPopup").height() - 8;
		
		var pointerLeft = left + ($("#divPopup").width()/2) - ($("#divPopupPointer").width()/2)
		var pointerTop = top + $("#divPopup").height() - 1;
			
		$("#divPopup").css({ "position": "absolute", "top": top, "left": left, "z-index": 100, "background-color": "#ffffff" });
		$("#divPopupPointer").css({ "position": "absolute", "top": pointerTop, "left": pointerLeft, "z-index": 100 });
		$(".popupbox").show();	
	}	
}

function hidePopup(sender)
{
	$("#tdPopupInfo").html("");
	$(".popupbox").hide();
}

function expandCut(sender)
{	
	var parentDiv = sender.parentElement;	
	var aButton = parentDiv.children[0];
	
	if(parentDiv.classList.contains('expanded'))
	{
		parentDiv.style['height'] = 15;
		parentDiv.classList.remove('expanded');
		aButton.innerText = 'Показать';
	}
	else
	{
		var targetHeight = parentDiv.children[1].offsetHeight;
		parentDiv.style['height'] = targetHeight;
		parentDiv.classList.add('expanded');
		aButton.innerText = 'Скрыть';
	}
}


function switchTab(controlName, messagesMode)
{
	var mode = messagesMode;
	$("#tbl" + controlName + "TabContent tr td.selected").hide();
	$("#tbl" + controlName + "TabContent tr td.selected").removeClass("selected");
	$("#tbl" + controlName + "TabMenu tr td.selected").removeClass("selected");
	
		
	$("#tbl" + controlName + "TabMenu tr td." + messagesMode).addClass("selected");
	$("#tbl" + controlName + "TabContent tr td." + messagesMode).addClass("selected");
	$("#tbl" + controlName + "TabContent tr td.selected").show();
	//$("td.selected").removeClass("selected");
	//$("#tdPrivateMessages" + messagesMode).addClass("selected");
	//$("#tdMessages" + messagesMode).show();
}

function switchPage(controlID, page)	
{
	$("div."+controlID).removeClass("selected");
	$("div."+controlID+"[page="+page+"]").addClass("selected");	
}

function scrollDiv(name, pix)
{
	var maxLeft = $("#"+name).parent().offset().left;
	var minLeft = $("#"+name).parent().width() - $("#"+name)[0].scrollWidth + $("#"+name).parent().offset().left;
	
	var mtop = $("#"+name).offset().top;	
	var mleft = $("#"+name).offset().left*1+pix;
	
	if(mleft > maxLeft) {mleft=maxLeft;}
	if(mleft < minLeft) {mleft=minLeft;}
	
	$("#"+name).offset({top: mtop, left:mleft});	
	
}

function resizeScreen() {
	if(isChat == true){
		$("div.newsblock").hide();
		$("div.chatblock").show();
		$("div.chatblock").css("margin-top", "50px");
		$("#divChat").css("height", ($(window).height() - 100) + "px");
	}
	else
	{
		var newHeight = ($(window).height() - 250) / 2;
		
		$contentDiv = $("#contentDiv");
		
		$("#leftDiv").removeClass("span1");
		$("#leftDiv").addClass("span3");
		$contentDiv.removeClass("span12");
		$contentDiv.addClass("span9");
		
		var divLeft = $contentDiv.position().left;
		if(divLeft<260)
		{
			$("div.newsblock").hide();
			$("div.chatblock").hide();
			$("#leftDiv").removeClass("span3");
			$("#leftDiv").addClass("span1");
			
			$contentDiv.removeClass("span9");
			$contentDiv.addClass("span12");
		}
		else
		{	
			$("div.newsblock").show();
			$("div.chatblock").show();
			
			$("div.newsblock ul").height((newHeight)+"px");
			$("div.newsblock ul").css('width', divLeft - 75);
			
			$("div.chatblock").height((newHeight)+"px");	
			$("div.chatblock").css("margin-top", (newHeight+90)+"px");
			$("#divChat").css('width', divLeft - 10);
			$("#divChat div.messages").height( (newHeight) - 55);
		}
	}	
}