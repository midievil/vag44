<?PHP
	
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	error_reporting(0);
	
	session_start();
	
	
	header('Content-type: text/html; charset=utf-8');	

	if(!$_REQUEST["action"])
	{
		return;
	}
	
	chdir('..');
		

	require_once "constants.php";
	require_once "db.php";		
	require_once "inc/class.db.php";
	require_once "inc/class.fdb.php";
	connectToDB();	
	require_once "carlogic.php";
	require_once "userlogic.php";
		
	switch($_REQUEST["action"])
	{
		case "getvendors":
			$onlyvag = isset($_REQUEST["onlyvag"]) ? $_REQUEST["onlyvag"] : true;
			$withoutall = isset($_REQUEST["withoutall"]) ? $_REQUEST["withoutall"] : false;
			
			$result = CarDB::getVendors($onlyvag);		
			
			echo "<option value='-1'></option>";
			
			foreach ($result as $row)
			{
				echo "<option value=".($row["ID"]).">".($row["Name"])."</option>";
			}
			
			if(!$withoutall)
			{
				echo "<option value='-2'>Другой</option>";
			}
			return;
			
		case "getmodels":
			$result = fDB::fqueryAll("select ID, Name from Models where Visible=1 AND VendorID=".($_REQUEST["vendorid"]));
			if(count($result) > 0)
			{
				echo "<option value='-1'></option>";
				foreach ($result as $row)
				{
					echo "<option value=".($row["ID"]).">".($row["Name"])."</option>";
				}
			}
			return;
		case "getgenerations":
			$modelid = $_REQUEST["modelid"];
			echo "<option></option>";
			getGenerationsByModelID($modelid, "");
			return;		
							
		case "addengine":
			$size =		$_REQUEST["size"];
			$fuel =		$_REQUEST["fuel"];
			$names	=	$_REQUEST["name"];
			$valves =	$_REQUEST["valves"];
			$cilinders=	$_REQUEST["cilinders"];
			$layout =	$_REQUEST["layout"];
			$hp		=	$_REQUEST["hp"];		
			$cc	=		$_REQUEST["cc"];
			
			$names = explode(';', $names);
			$i=0;			
			$isOK = true;
			while($name = $names[$i++])
			{
				$query = "select * from Engines where Name='$name'" ;
				$result = mysql_query($query);
				if(mysql_num_rows($result))
				{
					//echo "exists";
					continue;
				}
										
				$query = "insert into Engines (Size, Fuel, Name, Valves, Cilinders, Layout, HP, CC) values ('$size', '$fuel', '$name', $valves, $cilinders, '$layout', $hp, $cc)" ;
			
				if(fDB::fexec($query))
				{
					$isOK = false;
					//writeQueryErrorToLog($query, mysql_error());
				}
			}
						
			
			if($isOK)
			{
				echo "ok";
			}
			else
			{
				//writeQueryErrorToLog($query, mysql_error());
				echo "error";
			}
			return;
			
		case "assignengine":
			$vendor		=	$_REQUEST["vendor"];
			$model		=	$_REQUEST["model"];
			$generation	=	$_REQUEST["generation"];
			$engine		=	$_REQUEST["engine"];
			
			$result = mysql_query("select * from EnginesToModels where EngineID=$engine and ModelID=$model and Generation=$generation");
			if(mysql_num_rows($result) > 0)
			{
				echo "assigned";
			}
			else
			{				
				if(fDB::fexec("insert into EnginesToModels (EngineID, ModelID, Generation) values ($engine, $model, $generation)"))
				{
					echo "ok";
				}
				else
				{
					echo "error";
				}
			}
			return;
			
		case "addcar":						
			 $modelid = !empty($_REQUEST["modelid"]) && $_REQUEST["modelid"] != "null" ? $_REQUEST["modelid"] : -1;
			 $name = !empty($_REQUEST["name"]) ? $_REQUEST["name"] : "";
			 $currentUser = User::CurrentUser();
			 if($currentUser->IsLogged())
			 {
				$query = "insert into Cars(UserID, ModelID, Name) values ($currentUser->ID, $modelid, '$name')";
				
				if(fDB::fexec($query))
				{
					echo "ok";
				}
				else
				{
					//writeQueryErrorToLog($query, mysql_error());
					echo "error";
				}
			 }
			
			return;
			
		case "getengines":
			$carid = $_REQUEST["carid"];
			$gen = $_REQUEST["gen"];
			
			$query = "
				select	C.ModelID
				from	Cars C
				where	C.ID = $carid";
			$result = mysql_query($query);
			if(mysql_num_rows($result))
			{
				if($row = mysql_fetch_assoc($result))
				{
					echo getEngineSelect($row["ModelID"], $gen, "", $carid)	;				
				}
			}
			return;
			
		case "getknowlegeengines":
			$vendorid = $_REQUEST["vendorid"];
			$modelid = $_REQUEST["modelid"];
			$generation = $_REQUEST["generation"];
			echo getEnginesForKnowlege($vendorid, $modelid, $generation);
			return;
			
		case "getdoors":
			$carid = $_REQUEST["carid"];
			$car = CarDB::getCarByID($carid);
			
			echo getDoorsSelect($car["DoorsList"], "", $carid );
			return;
			
		case "addservice";
			$carid = $_REQUEST["carid"];
			$operation = $_REQUEST["operation"];
			$date = $_REQUEST["date"];
			$mileage = $_REQUEST["mileage"];
			$comment = $_REQUEST["comment"];
			
			$dateParts = explode('.', $date);
			$date = $dateParts[2]."-".$dateParts[1]."-".$dateParts[0];
			
			$query = "insert into Service(CarID, OperationID, Date, Mileage, Comment) values ($carid, $operation, '$date', $mileage, '$comment')";				
				mysql_query($query);
				
				if(mysql_affected_rows() == 1)
				{
					echo "ok";
				}
				else
				{
					//writeQueryErrorToLog($query, mysql_error());
					echo "error";
				}
			return;
			
		case "editservicecomment":
			$id = $_REQUEST["id"];
			$comment = $_REQUEST["comment"];
			
			$query = "
				update	Service
				set		Comment = '$comment'
				where	ID = $id";
						
			if(fDB::fexec($query) == 1)
			{
				echo "ok";
			}
			else
			{
				echo "error";
			}
			return;
			
		case "editservicedate":
			$id = $_REQUEST["id"];
			$date = $_REQUEST["date"];
			
			$query = "
				update	Service
				set		Date = '$date'
				where	ID = $id";			
			
			if(fDB::fexec($query) == 1)
			{
				echo "ok";
			}
			else
			{
				//writeQueryErrorToLog($query, mysql_error());
				echo "error";
			}
			return;
			
		case "editservicemileage":
			$id = $_REQUEST["id"];
			$mileage = $_REQUEST["mileage"];
					
			$query = "
				update	Service
				set		Mileage = $mileage
				where	ID = $id";
					
			if(fDB::fexec($query) == 1)
			{
				echo "ok";
			}
			else
			{		
				echo "error";
			}
			return;
			
		case "dontremindservice":
			$id = $_REQUEST["id"];
			
			$query = "
				update	Service
				set		DontRemind = 1
				where	ID = $id";
			mysql_query($query);
			
			if(mysql_affected_rows() == 1)
			{
				echo "ok";
			}
			else
			{
				//writeQueryErrorToLog($query, mysql_error());
				echo "error";
			}
			return;
	}
?>