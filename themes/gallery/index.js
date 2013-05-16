{literal}
<script>
function ajaxFileUpload()
{
    //starting setting some animation when the ajax starts and completes
    $("#loading")
    .ajaxStart(function(){
        $(this).show();
    })
    .ajaxComplete(function(){
        $(this).hide();
    });
    
    $.ajaxFileUpload
    (
        {
            url:'/tools/doajaxfileupload.php?fileElementId=imageToUpload&path=gallery&galleryid={/literal}{$gallery.ID}{literal}', 
            secureuri:false,
            fileElementId:'imageToUpload',				
            dataType: 'json',
            success: function (data, status)
            {
                if(typeof(data.error) != 'undefined')
                {
                    if(data.error != '')
                    {
                        alert(data.error);
                    }else
                    {
                        window.location = window.location;
                    }
                }
            },
            error: function (data, status, e)
            {
                alert(e);
            }
        }
    )
    
    return false;

} 

function editComment(id)
{
	cancelEditComment();
	$("#aComment"+id).hide();
	$("#tbComment"+id).show();
	$(".edit"+id).show();
}

function cancelEditComment()
{
	$("a.gallerycomment").show();
	$("input.gallerycomment").hide();	
}

function updateComment(id, text, type)
{
	$.ajax({	type:	"POST",	
				url:	"/response/galleryresponse.php",
				data:	"action=update"+type+"comment&id="+id+"&text="+text,
				success: function(result){
					result = trim(result);
					if(result == "ok")
					{	
						$("a.gallerycomment").show();
						$("input.gallerycomment").hide();
						$("#aComment"+id).text(text);
					}
				}
			});
}

function commentKeyPress(key, id, type)
{
	if(key == 13)
	{
		updateComment(id, $("#tbComment"+id).val(), type);
	}
	else if(key == 27)
	{
		cancelEditComment();
	}
}	

function showEditButton(galleryID)
{
	if($("#aComment" + galleryID).is(":visible"))
	{
		$("#btnEditName"+galleryID).show();
		$(".public"+galleryID).show();
	}
}

function hideEditButton(galleryID)
{
	$("#btnEditName"+galleryID).hide();
	$(".public"+galleryID).hide();
}

function addGallery()
{	
	$.ajax({	type:	"POST",	
				url:	"/response/galleryresponse.php",
				data:	"action=addgallery",
				success: function(result){
					result = trim(result);
					if(result == "ok")
					{	
						window.location = "/gallery/";
					}
				}
			});
}

function changePublic(id)
{	
	var val = $("#cbPublic"+id).is(':checked') ? '1' : '0';
	$.ajax({	type:	"POST",	
				url:	"/response/galleryresponse.php",
				data:	"action=changepublic&id="+id+"&val="+val,
				success: function(result){
					result = trim(result);					
				}
			});
}

$(".innercontent").click( function() {
	cancelEditComment();	
});

</script>
{/literal}