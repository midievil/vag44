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
            url:'/response/doajaxfileupload.php?fileElementId=imageToUpload&path=gallery&galleryid={/literal}{$gallery.ID}{literal}', 
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
	$("#tbComment"+id).focus();
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
				},
				error: function ()
				{
					
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

function slideShow(picID)
{	
	$('#slideShowModal div.item').removeClass('active');
	$('ol.carousel-indicators li').removeClass('active');
	$('#slideShowModal div.pic'+picID).addClass('active');
	$('ol.carousel-indicators li[data-slide-to="'+picID+'"]').addClass('active');
	$('#slideShowModal').modal();
}

function editGallery(id)
{
	$('#editGalleryID').val(id);
	
	var text = $('div.gallery'+id+' a.galleryname').text();
	$("#tbEditGalleryName").val(text);
	
	var pub = $('div.gallery'+id+' input.gallerypublic').val();
	$("#cbEditGalleryPublic").prop('checked', pub==1);
	
	
	$('#renameModal').modal();
}

function saveGallery()
{
	var id=$('#editGalleryID').val();
	var name = $("#tbEditGalleryName").val();
	var pub = $("#cbEditGalleryPublic").prop('checked');
	
	$.ajax({	type:	"POST",	
		url:	"/response/galleryresponse.php",
		data:	"action=savegallery&id="+id+"&name="+name+"&pub="+pub,
		success: function(result){
			result = trim(result);
			if(result == "ok")
			{	
				$('div.gallery'+id+' a.galleryname').text(name);
				$('div.gallery'+id+' input.gallerypublic').val(pub);
				$('#renameModal').modal('hide');
			}
		}
	});
}



$(".innercontent").click( function() {
	cancelEditComment();	
});

</script>
{/literal}