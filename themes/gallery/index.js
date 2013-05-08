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
	$("a.gallerycomment").show();
	$("input.gallerycomment").hide();
	$("#aComment"+id).hide();
	$("#tbComment"+id).show();
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
}	

function showEditButton(galleryID)
{
	if($("#aComment" + galleryID).is(":visible"))
	{
		$("#btnEditName"+galleryID).show();
	}
}

function hideEditButton(galleryID)
{
	$("#btnEditName"+galleryID).hide();
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


</script>
{/literal}