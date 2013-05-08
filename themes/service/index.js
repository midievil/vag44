{literal}
<script>	
function showAddOperation(carid)
{
	$("#trAddRecord"+carid).show();
}

function cancelAddOperation(carid)
{
	$("#trAddRecord"+carid).hide();
}

var editedComment = true;
function showServiceCommentEdit(commentid)
{		
	$("#tbServiceComent"+commentid).show();
	$("#aServiceComent"+commentid).hide();
}

function hideServiceCommentEdit(commentid)
{		
	if(editedComment != commentid)
	{
		$("#tbServiceComent"+commentid).hide();
		$("#aServiceComent"+commentid).show();
		editedComment = -1;
	}
}

function showServiceDateEdit(commentid)
{		
	$("#tbServiceDate"+commentid).show();
	$("#aServiceDate"+commentid).hide();
}

function hideServiceDateEdit(commentid, anyway)
{		
	if(editedComment != commentid || anyway)
	{
		$("#tbServiceDate"+commentid).hide();
		$("#aServiceDate"+commentid).show();
		editedComment = -1;
	}
}

function saveComment(key, commentid)
{
	if(key == 13)
	{
		$.ajax(	{	type: "POST",
					url:"/response/carresponse.php",
					data:"action=editservicecomment"+
						"&id="+commentid+
						"&comment="+$("#tbServiceComent"+commentid).val(),
					success:	function(result){
						if(trim(result)=='ok')
						{
							$("#tbServiceComent"+commentid).blur();
							hideServiceCommentEdit(commentid);
							$("#aServiceComent"+commentid).text($("#tbServiceComent"+commentid).val());
						}					
					}
				});
	}
}

function saveDate(key, commentid)
{	
	if(key == 13)
	{
		var isValid = true;
		var userdate = $("#tbServiceDate"+commentid).val();
		var splits = userdate.split('.');
		if(splits == null || splits.length != 3 || splits[0] > 31 || splits[1] > 12 || splits[2] < 2005)	//	ToDo: you should understand, dude =)
		{
			isValid = false;
		}
					
		if(!isValid)
		{
			alert('Неверная дата');
			return false;
		}
		
		var textdate = splits[2] + '-' + splits[1] + '-' + splits[0];
	
		$.ajax(	{	type: "POST",
					url:"/response/carresponse.php",
					data:"action=editservicedate"+
						"&id="+commentid+
						"&date="+textdate,
					success:	function(result){
						if(trim(result)=='ok')
						{
							$("#tbServiceDate"+commentid).blur();
							hideServiceDateEdit(commentid, true);
							$("#aServiceDate"+commentid).text($("#tbServiceDate"+commentid).val());
						}					
					}
				});
	}
}

$("body").click(function() {
	hideServiceCommentEdit(editedComment);
});		

function isValid(carid)
{
	if(!checkDate($("#tbDate"+carid).val()))
	{
		alert('Неверно введена дата');
		return false;
	}
	
	if($("#ddlAddOperation"+carid).val() == "")
	{
		alert('Выберите операцию');
	}
	
	if(!checkNumber($("#tbMileage"+carid).val()))
	{
		alert('Неверно введен пробег');
	}
	return true;
}

function addOperation(carid)
{		
	if(isValid(carid))
	{			
		$.ajax(	{	type: "POST",
					url:"/response/carresponse.php",
					data:"action=addservice"+
						"&carid="+carid+
						"&operation="+$("#ddlAddOperation"+carid).val()+
						"&date="+$("#tbDate"+carid).val()+
						"&mileage="+$("#tbMileage"+carid).val()+
						"&comment="+$("#tbComment"+carid).val(),
					success:	function(result){													
						if(trim(result)=='ok')
						{
							cancelAddOperation(carid);
							window.location = window.location;
						}					
					}
				}
				);
	}
}
</script>

{/literal}