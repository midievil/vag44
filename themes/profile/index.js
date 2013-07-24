<script type="text/javascript">
	var isValid = true;

	var userID = {$currentUser->ID};
	
	var userCarsList = '{foreach from=$currentUser->Cars() item=car name=carscycle}{if $smarty.foreach.carscycle.iteration != 1};{/if}{$car->ID}{/foreach}';
{literal}

	
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
                url:'/response/doajaxfileupload.php?fileElementId=imageToUpload&path=userpics&userid='+userID, 
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
                            $("#imgUserPic").attr('src', data.file+"?version="+Math.random());
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
	
	function  updateUserCar(userid, carid)
	{
		$.ajax({	type: "POST",	
					url:"/response/userresponse.php",
					data: "action=updateusercar&userid="+userid+
								"&carid="+carid+"&generation="+$("#ddlEditCarGeneration"+carid).val()+"&engineid="+$("#ddlEditCarEngine"+carid).val()+
								"&doors="+$("#ddlEditCarDoors"+carid).val()+"&color="+$("#tbColor"+carid).val()+"&year="+$("#tbYear"+carid).val()+"&mileage="+$("#tbMileage"+carid).val()+
								"&carcomment="+$("#tbEditCarComment"+carid).val()+
								"&inpast="+($("#cbInPast"+carid).attr('checked')?"1":"0"),
					success: function(result){
						//alert(result);
						if(trim(result) == "ok")
						{
							$("#aComment").html('Изменения успешно сохранены');							
						}
						else if(trim(result) == "incorrectuser")
						{
							$("#aComment").html('Какая-то проблема с учетной записью. Попробуйте выйти и снова зайти.');
						}
						else
						{
							$("#aComment").html("Что-то пошло не так. Если это случилось уже не в первый раз, обратитесь к администратору с помощью <a href='/feedback'>формы обратной связи</a>");
						}
					}
			});
	}

	function  updateUser(id)
	{		
		var pageSize = -1;
		if($("input[name='cbCategoriesPaging']:checked").val() == 'on')
		{
			pageSize = $("#ddlPageSize").val();
		}
		else if($("input[name='cbCategoriesPaging']:checked").val() == 'off')
		{
			pageSize = '0';
		}
		$.ajax({	type: "POST",	
					url:"/response/userresponse.php",
					data:	"action=update&id="+id+
							"&firstname="+$("#tbEditProfileFirstName").val()+"&lastname="+$("#tbEditProfileLastName").val()+
							"&title="+$("#tbEditProfileTitle").val()+
							"&gender="+$("input[name='rblGender']:checked").val()+
							"&from="+$("#tbEditProfileFrom").val()+
							"&email="+$("#tbEditProfileEmail").val()+"&showemail="+($("#cbEditProfileShowEmail").attr('checked')?"1":"0")+
							"&icq="+$("#tbEditProfileICQ").val()+"&phone="+$("#tbEditProfilePhone").val()+"&social="+$("#tbEditProfileSocial").val()+
							"&listtype="+$("input[name='ddlListType']:checked").val()+"&categoriesorder="+$("input[name='ddlCategoriesOrder']:checked").val()+"&pagesize="+pageSize+
							"&birthdate="+$("#ddlDateYearBirthDate").val()+"-"+$("#ddlDateMonthBirthDate").val()+"-"+$("#ddlDateDayBirthDate").val(),
					success: function(result){
						//alert(result);
						if(trim(result) == "ok")
						{
							$("#aComment").html('Изменения успешно сохранены');
							
							var carIDs = userCarsList.split(';');
							for(var i=0; i<carIDs.length; i++)
							{
								updateUserCar(id, carIDs[i]);
							}
							
						}
						else if(trim(result) == "incorrectuser")
						{
							$("#aComment").html('Какая-то проблема с учетной записью. Попробуйте выйти и снова зайти.');
						}
						else
						{
							$("#aComment").html("Что-то пошло не так. Если это случилось уже не в первый раз, обратитесь к администратору с помощью <a href='/feedback'>формы обратной связи</a>");
						}
					}
			});
	}
	
	function stopAskingBirthday()
	{
		$.ajax({	type: "POST",	
					url:"/response/userresponse.php",
					data: "action=stopaskingbirthday",
					success: function(result){
						//alert(result);
						if(trim(result) == "ok")
						{
							$("#aComment").html('Мы больше не будем напоминать вам об указании дня рождения.');
						}
						else
						{
							$("#aComment").html("Что-то пошло не так. Если это случилось уже не в первый раз, обратитесь к администратору с помощью <a href='/feedback'>формы обратной связи</a>");
						}
					}
					});
	}
	
	function selectCar()
	{
		if(isValid == true)
		{
			$("#trSelectCar").show();
			$("#ddlSelectCarVendor").hide();
			
			$.ajax(	{	type: "POST",	
						url:"/response/carresponse.php",
						data:"action=getvendors&withoutall=yes&onlyvag=0",
						success:	function(result){
										$("#ddlSelectCarVendor").html(result);
										$("#ddlSelectCarVendor").val(-1);
										$("#ddlSelectCarModel").val(-1);
										$("#ddlSelectCarVendor").show();
									}
						}
					);
		}
	}
	
	function selectModel(vendorID)
	{
		if(isValid == true)
		{
			$("#ddlSelectCarModel").html('');			
			$("#btnSaveCar").addClass('disabled');
			
			$("#tbCarName").val('');
			$("#ddlSelectCarModel").val(-1);
			
			if(vendorID != -1)
			{
				showCarSaveButton();
				$("#ddlSelectCarModel").hide();
				$("#tbCarName").hide();
				$.ajax(	{	type: "POST",	
							url:"/response/carresponse.php",
							data:"action=getmodels&vendorid="+vendorID,
							success:	function(result){
											if(result == '')
											{													
												$("#tbCarName").show();
												showCarSaveButton(undefined);
											}
											else
											{
												$("#ddlSelectCarModel").html(result);																									
												$("#ddlSelectCarModel").show();
												showCarSaveButton(undefined);
											}											
										}
					}
					);
			}
		}
	}
	
	function showCarSaveButton(modelID)
	{
		if((modelID == undefined || modelID == -1) && $("#tbCarName").val() == '')
		{
			$("#btnSaveCar").addClass('disabled');
		}
		else
		{
			$("#btnSaveCar").removeClass('disabled');
		}
	}
	
	function addCar()
	{
		if($("#btnSaveCar").hasClass("disabled")) {
			return false;
		}
		$.ajax(	{	type: "POST",	
							url:"/response/carresponse.php",
							data:"action=addcar&modelid="+$("#ddlSelectCarModel").val()+"&name="+$("#tbCarName").val(),
							success:	function(result){
											window.location = "/profile/cars";
										}
					}
					);
	}
	
	function getEngines(carid, gen)
	{
		$.ajax(	{	type: "POST",	
							url:"/response/carresponse.php",
							data:"action=getengines&carid="+carid+"&gen="+gen,
							success:	function(result){
											$("#tdEditCarEngine"+carid).html(result);
										}
					}
					);
	}
	
	function getDoors(carid)
	{
		$.ajax(	{	type: "POST",	
							url:"/response/carresponse.php",
							data:"action=getdoors&carid="+carid,
							success:	function(result){
											$("#tdEditCarDoors"+carid).html(result);
										}
					}
					);
	}
	
	function generationChanged(carid)
	{
		$(".editcarstuff"+carid).attr("disabled", "true");
		var gen = $("#ddlEditCarGeneration"+carid).val();
		
		if(gen != undefined && gen != "-1")
		{
			getEngines(carid, gen);
			getDoors(carid);
		}
	}
	
	function switchEditProfile(controlName)
	{
		$("td.editprofile").hide();
		$("td." + controlName).show();
		
		$("span.selected").removeClass("selected");
		$("span." + controlName).addClass("selected");
	}
	
	function setPagingVisibility()
	{
		
		if($("input[name='cbCategoriesPaging']:checked").val() == "off")
		{
			$("#ddlPageSize").prop('disabled', true);
		}
		else
		{
			$("#ddlPageSize").prop('disabled', false);
		}
	}
</script>
{/literal}