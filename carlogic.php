<?PHP	
	//	Логика обращается к БД, поэтому все файлы, которые используют функции отсюда, должны заранее подключать db.php
	
	require_once 'db/CarDB.php';
	
	class Car
	{
		public $ID = -1;
		public $UserID = -1;
		public $ModelID = -1;
		public $Generation = 0;
		public $Name = '';
		public $Description = '';
		public $Status = 0;
		public $Year = 0;
		public $EngineID = -1;
		public $GearBoxID; 
		public $Doors;
		public $Color = '';
		public $Mileage = 0;
		public $InPast = 0;		
		public $ModelName;
		public $ModelVisible;
		public $VendorID;
		public $GenerationsList;
		public $DoorsList;
		public $VendorName;
		public $EngineName;
		public $EngineSize;
		public $EngineHP;
		public $EngineFuel;
		public $PostID;
		public $IsVag;
		
		public function __construct($id = null) {
			if($id)
			{
				$this->ID = $id;
				$this->Load();
			}
		}
		
		public function MakeFromRow($row)
		{
			foreach($row as $key=>$val)
			{
				$this->$key = $val;
			}
		}
		
		private function Load()
		{
			$row = CarDB::getCarByID($this->ID);
			$this->MakeFromRow($row);
		}
		
		public function Generations()
		{
			return explode(";", $this->GenerationsList);
		}
		
		public function GenerationName()
		{
			$generations = $this->Generations();
			return $generations[$this->Generation];
		}
		
		public function DoorsList()
		{
			return explode(";", $this->DoorsList);
		}
		
		private $engines = null;
		public function Engines()
		{
			if($this->engines == null)
			{
				$engineRows = CarDB::getEnginesByModelID($this->ModelID, $this->Generation);
				$this->engines = array();
				foreach ($engineRows as $engineRow)
				{
					$engine = new Engine();
					$engine->MakeFromRow($engineRow);
					$this->engines[] = $engine;
				}
			}
			return $this->engines;
		}
		
		public function getShortDescription()
		{
			if(!$this->ModelVisible)
			{
				return $this->Name;
			}
			return $this->VendorName . ' ' . $this->ModelName . ' ' . $this->GenerationName();
		}
		
		public function getEngineDescription()
		{
			if($this->EngineName != '')
			{
				return $this->EngineSize . $this->EngineFuel . " (" . $this->EngineName . ")";
			}
		}
		
		private $serviceHistory = null;
		public function ServiceHistory($withIntervalsOnly)
		{
			if($this->serviceHistory == null)
			{
				$this->serviceHistory =	CarDB::getCarServiceHistory($this->ID, $withIntervalsOnly);
				for($i=0; $i<count($this->serviceHistory); $i++)
				{
					$this->serviceHistory[$i]['DateText'] = getDateAtText($this->serviceHistory[$i]['Date']);
					$this->serviceHistory[$i]['NextDateText'] = getDateAtText($this->serviceHistory[$i]['NextTime']);
				}
			}			
			return $this->serviceHistory;
		}
	}
	
	
	class Engine
	{
		public $ID = -1;	
		public $Size = 0;
		public $Name = '';
		public $HP = 0;		
	
		public function __construct($id) {
			if($id)
			{
				$this->ID = $id;
				$this->Load();
			}
		}
	
		public function MakeFromRow($row)
		{
			foreach($row as $key=>$val)
			{
				$this->$key = $val;
			}
		}
	
		private function Load()
		{
			$row = CarDB::getEngineByID($this->ID);
			$this->MakeFromRow($row);
		}	
	}
	
	
	function getVendorByID($id)
	{
		return mysql_fetch_assoc(
			mysql_query("
				select	V.ID, 
						V.Name,
						count(C.ID) CountOnSite
				from	Vendors V
				join	Models M	on M.VendorID = V.ID
				left join
						Cars C		on C.ModelID = M.ID
				where	V.ID = $id
				group	by V.ID, 
						V.Name"));
	}
	
	function getModelByID($id)
	{
		return mysql_fetch_assoc(
			mysql_query("
				select	M.ID, 
						M.Name,
						V.ID VendorID,
						V.Name VendorName,
						count(C.ID) CountOnSite
				from	Models M
				join	Vendors V	on V.ID = M.VendorID
				left join
						Cars C		on C.ModelID = M.ID
				where	M.ID = $id
				group	by M.ID, 
						M.Name,
						V.ID,
						V.Name"));
	}
		
	function getCarGenerationFromList($list, $index)
	{
		$generations = explode(";", $list);		
		return $generations[$index];
	}
	
	function getCarDescriptionFromRow($row)
	{
		return '';
	}
	
	function getCarDescriptionByID($carid)
	{	
		$row = CarDB::getCarByID($carid);
		return getCarDescriptionFromRow($row);
	}
	
	function getEngineSelect($modelID, $selectedgeneration, $carEngineID, $carid)
	{
		$query = "
			select	ETM.EngineID,
					E.Size,					
					E.Name,
					E.HP
			from	EnginesToModels ETM
			join	Engines E on E.ID = ETM.EngineID
			where	ETM.ModelID = $modelID
					and	ETM.Generation = $selectedgeneration
			order	by	E.Size,
					E.Name";
		$rows = mysql_query($query);
				
		$result = "<select id='ddlEditCarEngine$carid' class='editcarstuff$carid'>";
		$result = "$result<option value='-1' ".($carEngineID == $engineID ? "selected" : "")."> </option>";		
		while($row = mysql_fetch_assoc($rows))
		{
			$engineID = $row["EngineID"];
			$engineName = $row["Name"];
			$size = $row["Size"];
			$hp = $row["HP"];
			
			$result = "$result<option value='$engineID' ".($carEngineID == $engineID ? "selected" : "").">$size $engineName $hp л.с.</option>";		
		}
		$result = "$result</select>";		
		return $result;
	}
	
	function getEnginesForKnowlege($vendorID, $modelID, $generation)
	{
		$vendorFilter = "";
		$modelFilter = "";
		$generationFilter = "";
		
		if($modelID)
		{
			$modelFilter = "
					and	ModelID = $modelID";
		}
		else
		{
			$vendorFilter = $vendorID ? "
					and	ModelID in (select ID from Models where VendorID=$vendorID)" : "";
		}
		
		$generationFilter = $generation != "" ? "
				and	ETM.Generation = $generation" : "";
	
	
		$query = "
			select	E.*
			from	Engines E
			left join 
					EnginesToModels ETM on ETM.EngineID = E.ID
			where	1=1 $vendorFilter $modelFilter $generationFilter
			group	by	Size,
					HP,
					Name
			order	by	case
							when Fuel in ('TD', 'D', 'TDI', 'SDI') then 1
							else 0
						end,
					cc,
					HP,
					Name";
	
		$rows = mysql_query($query);
		
		$result = "Выбрано: " . mysql_num_rows($rows);
		$result .= "<table class='list' cellspacing='0' cellpadding='0'><tr class='header'><td>Объем</td><td>Код</td><td>Мощность</td><td>Цилиндры</td><td>Клапана</td><td>Объем, см<sup>3</sup></td><td></td><td></td><td></td></tr>";
		while($row = mysql_fetch_assoc($rows))
		{
			$size = $row["Size"];
			if(strlen($size) == 1) {$size=$size.".0";}
			$name = $row["Name"];
			$hp = $row["HP"];
			$cyl = $row["Layout"].$row["Cilinders"];
			$valves = $row["Valves"];
			$cc = $row["CC"];
			$fuel = $row["Fuel"];
			$result .= "<tr><td>$size $fuel</td><td>$name</td><td>$hp</td><td>$cyl</td><td>$valves</td><td>$cc</td></tr>";
		}
		
		$result .= "</table>";
		return $result;
	}
	
	function getDoorsSelect($string, $selectedDoors, $carid)
	{		
		$string = " ;$string";
		$doors = explode(";", $string);
				
		$result = "<select id='ddlEditCarDoors$carid' class='editcarstuff$carid'>";
		foreach($doors as $door)
		{				
			$result = "$result<option value='".($door == " " ? "-1" : $door)."' ".($selectedDoors == $door ? "selected" : "").">$door</option>";			
		}
		$result = "$result</select>";
		
		return $result;
	}
	
	
	
	function getModelsByVendorID($vendorid)
	{
		$query = "
			select	M.*
			from	Models M 
			where	M.VendorID=$vendorid";
		return mysql_query($query);
	}
	
	function getCarsListByUserID($userid)
	{
		$result = "";
		if($cars = CarDB::getCarsByUserID($userid))
		{
			foreach($cars as $carrow)
			{
				if($result != "")
				{
					$result = $result.",<br /> ";
				}
				$genlist = explode(";", $carrow[GenerationsList]);
				$generation = $genlist[$carrow["Generation"]] ? $genlist[$carrow["Generation"]]." " : "" ;
				
				$carid = $carrow["ID"];
				$size = $carrow["EngineSize"] > 0 ? $carrow["EngineSize"].$carrow["EngineFuel"]."&nbsp;" : "";
				$hp = $carrow["EngineHP"] ? "(".$carrow["EngineHP"]."&nbsp;л.с.)&nbsp;" : "";
				$doors = $carrow["Doors"] && $carrow["Doors"] > 0 ? $carrow["Doors"]."дв.&nbsp;" : "";
				$year = $carrow["Year"] > 0 ? $carrow["Year"]."г.&nbsp;" : "";
				$mileage = $carrow["Mileage"] > 0 ? $carrow["Mileage"]."км&nbsp;" : "";
				$postid = $carrow["PostID"];
				$inpast = $carrow["InPast"] ? " (был)" : "";
				
				
				if($_SESSION["loggeduserid"] && $postid)
				{	
					$linkStart = "<a href='/post/$postid'>";
					$linkEnd = "</a>";
				}
				
				$description = "$generation&nbsp;$size&nbsp;".$carrow["EngineName"].$hp.$doors.$carrow["Color"]." ".$year.$mileage.$inpast;
				$result .= $linkStart.$carrow["VendorName"]."&nbsp;".$carrow["ModelName"]."&nbsp;$description".$linkEnd;
			}
		}
		return $result;
	}
	
	function getShortCarsListByUserID($userid)
	{
		$result = "";
		if($cars = CarDB::getCarsByUserID($userid))
		{
			foreach($cars as $carrow)
			{
				if($result != "")
				{
					$result = $result."\n";
				}
				$genlist = explode(";", $carrow[GenerationsList]);
				$generation = $genlist[$carrow["Generation"]] ? $genlist[$carrow["Generation"]]." " : "" ;
								
				$size = $carrow["EngineSize"] > 0 ? $carrow["EngineSize"].$carrow["EngineFuel"]."&nbsp;" : "";
				$hp = $carrow["EngineHP"] ? "(".$carrow["EngineHP"]."&nbsp;л.с.)&nbsp;" : "";				
				$year = $carrow["Year"] > 0 ? $carrow["Year"]."г.&nbsp;" : "";
								
				$description = "$generation&nbsp;$size&nbsp;".$carrow["EngineName"].$hp." ".$year;
				$result = $result.$carrow["VendorName"]."&nbsp;".$carrow["ModelName"]."&nbsp;$description";
			}
		}
		return $result;
	}
	
	function getServiceOperationsSelect()
	{
		$query = "
			select	*
			from	ServiceOperatons
			ORDER BY Name";
		
		$select = "<option value=''></option>";
		$result = fDB::fqueryAll($query);
		foreach($result as $row)
		{
			$select .= "<option value='".$row["ID"]."'>".$row["Name"]."</option>";
		}
		return $select;
	}
	
	function getCarVendorByUserID($userid)
	{	
		$query = "
				select	V.Name
				from	Cars C
				join 	Models M on M.ID = C.ModelID
				join	Vendors V on V.ID = M.VendorID
				where	C.UserID = $userid
				limit	0,1";
		$result = mysql_query($query);
		if($row = mysql_fetch_assoc($result))
		{
			return $row["Name"];
		}
		return "";
	}
	
	function drawCarFaceWithUser($car)
	{
		$picPath = "img/carpics/" . $car->ID . ".jpg";
		if(!file_exists($picPath) && file_exists("img/postpics/" . $car->PostID . "_1.jpg"))
		{
			$image = new SimpleImage();
			$image->load("img/postpics/" . $car->PostID . "_1.jpg");
			$image->resizeToWidth(80);										
			$image->save($picPath);
		}
		
		if(file_exists($picPath))
		{
			return "
			<table>
				<tr>
					<td rowspan='2'>
						<img width='80px' src='/img/carpics/" . $car->ID . ".jpg' />
					</td>
					<td>
						<a href='/post/" . $car->PostID . "'>" . ($car->PostTitle ? $car->PostTitle : $car->getShortDescription()) ."</a>
					</td>
				</tr>
				<tr>
					<td class='comment'>
						" . $car->getShortDescription() . " <br />
						" . $car->EngineSize . " " . $car->EngineName . " (" . $car->EngineHP . " л.с.). Год выпуска: " . $car->Year . "<br />
						Владелец: " . $car->UserTitle . " " . $car->UserName . "
					</td>
				</tr>
				<tr>
					<td colspan='2'>
						
					</td>
				</tr>
			</table>
				";
			return true;
		}
		return "";		
	}
	
	function getGenerationsByModelID($modelid, $selected)
	{
		$query = "
		select	Generations
		from	Models
		where	ID=$modelid	";
		$row = fDB::fquery($query);
		$i=0;
		if($row)
		{
			echo $row["Generations"];
			$gens = explode(";", $row["Generations"]);
			while($gens[$i])
			{
				echo "<option value='$i' ".("$selected" == "$i" ? "selected" : "").">".$gens[$i]."</option>";
				$i++;
			}
		}
	}

	
?>