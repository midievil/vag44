{literal}
<script>

function showPassword()
{
	if(isValid == true)
	{
		$("#trRegisterPassword").show();
		$("#trRegisterPasswordConfirm").show();
	}
	else
	{
		$("#trRegisterPassword").hide();
		$("#trRegisterPasswordConfirm").hide();
	}
}

function showRestRegistrationInfo()
{
	if(isValid == true)
	{
		$("#trRegisterFirstName").show();	
		$("#trRegisterLastName").show();	
		$("#trRegisterFrom").show();			
		$("#trRegisterHaveCar").show();				
	}
	else
	{
		$("#trRegisterFirstName").hide();	
		$("#trRegisterLastName").hide();
		$("#trRegisterFrom").hide();			
		$("#trRegisterHaveCar").hide();						
	}
}

function showCar()
{
	if(isValid == true)
	{
		$("#trRegisterOK").show();
		
		$("#trRegisterCar").show();
		$.ajax(	{	type: "POST",	
					url:"/response/carresponse.php",
					data:"action=getvendors",
					success:	function(result){
									$("#ddlRegisterVendor").html(result);
									$("#ddlRegisterVendor").val(-1);
									$("#ddlRegisterModel").val(-1);
								}
					}
				);
	}
}

function showModel(vendorID)
{
	if(isValid == true)
	{
		$("#ddlRegisterModel").html('');
		if(vendorID == -2)
		{
			$("#ddlRegisterModel").hide();
			$("#tbRegstrationOtherCar").show();
		}
		else if(vendorID != -1)
		{
			$("#tbRegstrationOtherCar").hide();
			$("#ddlRegisterModel").show();
			$.ajax(	{	type: "POST",	
						url:"response/carresponse.php",
						data:"action=getmodels&vendorid="+vendorID,
						success:	function(result){
										$("#ddlRegisterModel").html(result);
										$("#ddlRegisterModel").val(-1);											
									}
				}
				);
		}
		else
		{
			$("#ddlRegisterModel").hide();
		}
	}
}
function hideCar()
{
	$("#trRegisterOK").show();
	$("#trRegisterCar").hide();
	$("#ddlRegisterVendor").val("-1");
	$("#ddlRegisterModel").val("-1");
}

function showEmail()
{
	$("#trRegisterEmail").show();
}

function checkEmail()
{
	var email = $("#tbRegisterEmail").val();
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (!filter.test(email)) {
		isValid = false;
	}
	else
	{
		isValid = true;			
	}
	showRestRegistrationInfo();
}

function register()
{
	if(isValid == true)
	{
		var modelid = $("#ddlRegisterModel").val();
		if (modelid == null){
			modelid=-1;
		}
		
		$("#trRegisterOK").hide();
		
		$.ajax(	{	
					type: "POST",
					url:"/response/userresponse.php",
					data:"action=add&name="+$("#tbRegisterNick").val()+
						"&email="+$("#tbRegisterEmail").val()+
						"&lastname="+$("#tbRegisterLastName").val()+
						"&firstname="+$("#tbRegisterFirstName").val()+
						"&from="+$("#tbRegisterFrom").val()+
						"&modelid="+modelid+
						"&pass="+$("#tbRegisterPassword").val(),
					success:	function(result){													
						if(trim(result)=='ok')
						{
							$("#divMain").load("/registration/congratulations");																
						}
						else if(trim(result)=='exists')
						{							
							$("#tdRegisterResult").html("Какая досада! Пользователь с таким именем уже успел появиться в системе с момента проверки. Это или очень странное совпадение, или наш скрипт глючит под действием торсионных полей. Попробуйте войти в систему с вашим паролем, и если это удастся - значит всё в порядке.");
						}							
					}
				}
				);
	}
}
</script>
{/literal}