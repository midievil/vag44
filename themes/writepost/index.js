<script type="text/javascript">

	var uploadPath = '{if $post->ID}postfiles&postid={$post->ID}{else}newfiles&userid={$currentUser->ID}{/if}';
	
	var imgUploadPath = '{if $post->ID}postpics&postid={$post->ID}{else}newpics&userid={$currentUser->ID}{/if}';
		
{literal}
		
	function getImageName(source)
	{
		var picName = source.replace('/img/newpics/', '').replace('/img/postpics/', '').replace('/uploads/newfiles/', '').replace('/uploads/postfiles/', '').replace('.jpg', '');
		var picParts = picName.split('_');
		if(picParts.length == 2)
		{
			return picParts[1];
		}
		else if (picParts.length == 1)
		{
			return picParts[0];
		}
		
		return picName;
	}
	
	function getFileName(source)
	{
		var picName = source.replace('/img/newpics/', '').replace('/img/postpics/', '').replace('/uploads/newfiles/', '').replace('/uploads/postfiles/', '');
		return picName;
	}
		
	var userPicsCount = 0;
	
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
                url:'/tools/doajaxfileupload.php?fileElementId=fileToUpload&path='+uploadPath, 
                secureuri:false,
                fileElementId:'fileToUpload',				
                dataType: 'json',
                success: function (data, status)
                {
                    if(typeof(data.error) != 'undefined')
                    {
                        if(data.error != '')
                        {
                            alert(data.error);
                        }
                        else
                        {
                            $("#tblPostFiles td:last").after("<td align='center'><img style='width:50px;' src='/img/file.jpg' onclick='insertFile(\""+getFileName(data.msg)+"\")' /><br />"+getFileName(data.msg)+"</td>");
							userPicsCount++;
							if(userPicsCount % 7 == 0)
							{
								$("#tblPostPictures tr:last").after("<tr><td></td></tr>");
							}
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

	
	function ajaxImageUpload()
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
                url:'/tools/doajaxfileupload.php?fileElementId=imageToUpload&path='+imgUploadPath, 
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
                            $("#tblPostPictures td:last").after("<td><img style='max-width:100px; max-height:100px;' src='" + data.msg + "' onclick='insertPic(\""+data.msg+"\")' /><br />"+getImageName(data.msg)+"</td>");
							userPicsCount++;
							if(userPicsCount % 7 == 0)
							{
								$("#tblPostPictures tr:last").after("<tr><td></td></tr>")
							}
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
	
	
	function insertPic(picName)
	{	
		$('#tbPostText').ckeditorGet().insertText("[img]" + getImageName(picName) + "[/img]");
	}
	
	function insertFile(picName)
	{
		$('#tbPostText').ckeditorGet().insertText("[file]" + getFileName(picName) + "[/file]");
	}	
	</script>
{/literal}