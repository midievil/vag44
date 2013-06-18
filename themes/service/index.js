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
	$(".showeditcomment"+commentid).show();
	$(".hideeditcomment"+commentid).hide();
}

function hideServiceCommentEdit(commentid)
{		
	$(".showeditcomment"+commentid).hide();
	$(".hideeditcomment"+commentid).show();
}

function showServiceDateEdit(commentid)
{		
	$(".showeditdate"+commentid).show();
	$(".hideeditdate"+commentid).hide();
}

function hideServiceDateEdit(commentid)
{		
	$(".showeditdate"+commentid).hide();
	$(".hideeditdate"+commentid).show();
}

function showServiceMileageEdit(commentid)
{		
	$(".showeditmileage"+commentid).show();
	$(".hideeditmileage"+commentid).hide();
}

function hideServiceMileageEdit(commentid)
{		
	$(".showeditmileage"+commentid).hide();
	$(".hideeditmileage"+commentid).show();
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

function saveMileage(key, commentid)
{	
	if(key == 13)
	{
		var isValid = true;
		var userMileage = $("#tbServiceMileage"+commentid).val();
		
		if(checkNumber(userMileage) == false)
		{
			isValid = false;
			alert('Введите число');
			return false;
		}
			
		$.ajax(	{	type: "POST",
					url:"/response/carresponse.php",
					data:"action=editservicemileage"+
						"&id="+commentid+
						"&mileage="+userMileage,
					success:	function(result){
						if(trim(result)=='ok')
						{
							$("#tbServiceMileage"+commentid).blur();
							hideServiceMileageEdit(commentid);
							$("#aServiceMileage"+commentid).text($("#tbServiceMileage"+commentid).val());
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
			alert('Формат даты - дд.мм.гггг');
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
							hideServiceDateEdit(commentid);
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
		alert('Неверный формат даты');
		return false;
	}
	
	if($("#ddlAddOperation"+carid).val() == "")
	{
		alert('Не выбрана операция');
	}
	
	if(!checkNumber($("#tbMileage"+carid).val()))
	{
		alert('Неверно указан пробег');
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