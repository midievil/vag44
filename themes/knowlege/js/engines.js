{literal}
<script>
	function reloadWithVendor()
	{
		//window.location = "?showknowlege=engines&vendorid=" + $("#ddlVendor").val();		
		if($("#ddlVendor").val() != "")
		{			
			$.ajax({	
				type: "POST",
				url: "/response/carresponse.php",
				data: "action=getmodels&vendorid="+$("#ddlVendor").val(),
				success:	function(result) 	
				{
					$("#tdModel").show();
					$("#ddlModel").html(result);
				}
			});
		}
		else
		{
			$("#tdModel").hide();
			$("#ddlModel").html("");
		}
		
		$.ajax({	
				type: "POST",
				url: "/response/carresponse.php",
				data: "action=getknowlegeengines&vendorid="+$("#ddlVendor").val(),
				success:	function(result) 	
				{
					$("#aEnginesTable").html(result);
				}
			});
	}
	
	function reloadWithModel()
	{		
		//window.location = "?showknowlege=engines&vendorid=" + $("#ddlVendor").val() + "&modelid="+ $("#ddlModel").val();
		var modelid = $("#ddlModel").val();
		if(modelid == -1)
		{
			modelid = "";
		}
		if(modelid != "")
		{			
			$.ajax({	
				type: "POST",
				url: "/response/carresponse.php",
				data: "action=getgenerations&modelid="+modelid,
				success:	function(result) 	
				{
					$("#tdGeneration").show();
					$("#ddlGeneration").html(result);
				}
			});
		}
		else
		{
			$("#tdGeneration").hide();
			$("#ddlGeneration").html("");
		}
		
		$.ajax({	
				type: "POST",
				url: "/response/carresponse.php",
				data: "action=getknowlegeengines&modelid="+modelid,
				success:	function(result) 	
				{
					$("#aEnginesTable").html(result);
				}
			});
	}
	
	function reloadWithGeneration()
	{
		//window.location = "?showknowlege=engines&vendorid=" + $("#ddlVendor").val() + "&modelid="+ $("#ddlModel").val() + "&generation=" + $("#ddlGeneration").val();		
		$.ajax({	
				type: "POST",
				url: "/response/carresponse.php",
				data: "action=getknowlegeengines&modelid="+$("#ddlModel").val()+"&generation="+$("#ddlGeneration").val(),
				success:	function(result) 	
				{
					$("#aEnginesTable").html(result);
				}
			});
	}
	
	function addEngine(size, fuel, name, hp, cilinders, layout, valves, cc)
	{		
		$.ajax({	
				type: "POST",
				url: "/response/carresponse.php",
				data: "action=addengine&size="+size+"&fuel="+fuel+"&name="+name+"&hp="+hp+"&cilinders="+cilinders+"&layout="+layout+"&valves="+valves+"&cc="+cc,
				success:	function(result) 	
				{
					alert(result);
					if(trim(result) == 'exists')
					{
						alert('Двигатель с таким кодом уде есть');
					}
					else if(trim(result) == 'ok')
					{
						alert('Added');
					}
				}
			});
	}
	
	function assignEngineToModel()
	{
		if($("#ddlVendor").val() == "")
		{
			alert('Select Vendor');
		}
		else if($("#ddlModel").val() == "")
		{
			alert('Select Model');
		}
		else if($("#ddlGeneration").val() == "")
		{
			alert('Select Generation');
		}
		else if($("#ddlEngineToAssign").val() == "")
		{
			alert('Engine');
		}
		
		var vendor = $("#ddlVendor").val();
		var model = $("#ddlModel").val();
		var generation = $("#ddlGeneration").val();
		var engine = $("#ddlEngineToAssign").val();
		
		$.ajax({	
				type: "POST",
				url: "/response/carresponse.php",
				data: "action=assignengine&vendor="+vendor+"&model="+model+"&generation="+generation+"&engine="+engine,
				success: function(result) 	
				{					
					if(trim(result) == 'assigned')
					{
						alert('Already Assigned');
					}
					else if(trim(result) == 'noengine')
					{
						alert('Already Assigned');
					}
					else if(trim(result) == 'ok')
					{
						alert('Added');
					}
				}
			});
	}
</script>
{/literal}