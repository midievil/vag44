<?php
	class CarDB
	{ 
		public static function getCarByID($carid)
		{
			$query = "
			select	C.*,
					M.Name			ModelName,
					M.VendorID		VendorID,
					M.Generations	GenerationsList,
					M.Doors			DoorsList,
					V.Name			VendorName,
					P.Title			DescriptionTitle,
					P.Text			Description,
					P.ID			PostID,
					B.ID			BlogID,
					E.Name			EngineName,
					E.Size			EngineSize,
					E.Fuel			EngineFuel
			from	Cars C
			join	Models M on M.ID = C.ModelID
			join	Vendors V on V.ID = M.VendorID
			left join
					Blogs B on B.UserID = C.UserID and B.CarID = C.ID
			left join
					Posts P on P.IsCarDescription = 1 and P.BlogID = B.ID
			left join
					Engines E on E.ID = C.EngineID
			where	C.ID=$carid
					limit	1";
			return fDB::fquery($query);
		}
		
		public static function getCarsByUserID($userid)
		{
			$query = "
			select	C.*,
					M.Name			ModelName,
					M.VendorID		VendorID,
					M.Generations	GenerationsList,
					M.Doors			DoorsList,
					V.Name			VendorName,
					E.Name			EngineName,
					E.Size			EngineSize,
					E.HP			EngineHP,
					E.Fuel			EngineFuel,
					P.ID			PostID
			from	Cars C
			join	Models M on M.ID = C.ModelID
			join	Vendors V on V.ID = M.VendorID
			left join
					Engines E on E.ID = C.EngineID
			left join
					Blogs B on B.UserID = C.UserID and B.CarID = C.ID
			left join
					Posts P on P.IsCarDescription = 1 and P.BlogID = B.ID
			where	C.UserID=$userid";
			fDB::fexec("SET SQL_BIG_SELECTS=1");
			return fDB::fqueryAll($query);
		}
		
		public static function getCarsByVendor($vendorid)
		{
			mysql_query("SET SQL_BIG_SELECTS=1");
			try {
				$query = "
				select	C.*,
						M.Name			ModelName,
						M.VendorID		VendorID,
						M.Generations	GenerationsList,
						M.Doors			DoorsList,
						V.Name			VendorName,
						E.Name			EngineName,
						E.Size			EngineSize,
						E.HP			EngineHP,
						E.Fuel			EngineFuel,
						P.ID			PostID,
						P.Title			PostTitle,
						U.Name			UserName,
						''				UserTitle
				from	Cars C
				join	Models M on M.ID = C.ModelID
				join	Vendors V on V.ID = M.VendorID
				join	Users U on U.ID = C.UserID
				join	Engines E on E.ID = C.EngineID
				join	Blogs B on B.UserID = C.UserID and B.CarID = C.ID
				join	Posts P on P.IsCarDescription = 1 and P.BlogID = B.ID
				where	M.VendorID=$vendorid OR $vendorid = 0";
				return fDB::fqueryAll($query);
			}
			catch (Exception $e)
			{
				var_dump($e);
			}
		}
		
		public static function getCarsByModel($modelid)
		{
			$query = "
			select	C.*,
					M.Name			ModelName,
					M.VendorID		VendorID,
					M.Generations	GenerationsList,
					M.Doors			DoorsList,
					V.Name			VendorName,
					E.Name			EngineName,
					E.Size			EngineSize,
					E.HP			EngineHP,
					E.Fuel			EngineFuel,
					P.ID			PostID,
					P.Title			PostTitle,
					U.Name			UserName,
					U.Title			UserTitle
			from	Cars C
			join	Models M on M.ID = C.ModelID
			join	Vendors V on V.ID = M.VendorID
			join	Users U on U.ID = C.UserID
			join	Engines E on E.ID = C.EngineID
			join	Blogs B on B.UserID = C.UserID and B.CarID = C.ID
			join	Posts P on P.IsCarDescription = 1 and P.BlogID = B.ID
			where	C.ModelID=$modelid";
			return fDB::fqueryAll($query);
		}
		
		public static function getEngineByID($id)
		{
			$query = "
			select	ID,
					E.Size,
					E.Name,
					E.HP
			from	Engines E 
			where	E.ID = $id";
			return fDB::fquery($query);
		}
		
		public static function getEnginesByModelID($modelID, $selectedgeneration)
		{
			$query = "
				select	ETM.EngineID	ID,
						E.Size,					
						E.Name,
						E.HP
				from	EnginesToModels ETM
				join	Engines E on E.ID = ETM.EngineID
				where	ETM.ModelID = $modelID
						and	ETM.Generation = $selectedgeneration
				order	by	E.Size,
						E.Name";
			return fDB::fqueryAll($query);
		}
		
		public static function getVendors()
		{
			return fDB::fqueryAll("select ID, Name from Vendors where IsVag=1");			
		}
		
		public static function getCarVendorsForMenu()
		{
			$query = "
			SELECT	V.ID,
					V.Name,
					count(C.ID) CountOnSite
			FROM	Vendors V
			join	Models M on M.VendorID = V.ID
			left join
					Cars C on C.ModelID = M.ID
			Group by V.Name
			order by CountOnSite desc";
			return fDB::fqueryAll($query);
		}
		
		public static function getCarModelsForMenu($vendorID)
		{
			$query = "
			SELECT	M.ID,
				M.Name,
				count(C.ID) CountOnSite
			FROM	Models M
			JOIN  	Cars C on C.ModelID = M.ID
			JOIN	Users U on U.ID = C.UserID
			WHERE	M.VendorID = $vendorID
					and	U.Visible = 1
			Group by M.Name
					order by CountOnSite desc";
			return fDB::fqueryAll($query);
		}
		
		public static function getModelByID($modelID)
		{
			$query = "select * from Models where ID = $modelID";
			return fDB::fquery($query);
		}			

		public static function getEngines()
		{
			$query = "select * from Engines order by Name";
			return fDB::fqueryAll($query);
		}
		
		public static function getCarServiceHistory($carID, $onlyWithIntervals)
		{
			$query = "
			select
			S.ID,
			S.OperationID,
			SO.Name Operation,
			SO.MessageText,
			S.Date,
			S.Comment,
			S.Mileage,
			S.DontRemind,
			CASE
			WHEN	SI.Time IS NULL THEN ''
			WHEN	SI.Time like '%m' THEN DATE_ADD(S.Date, INTERVAL  REPLACE(SI.Time, 'm', '') MONTH)
			ELSE	''
			END	NextTime,
			CASE
			WHEN	SI.Time IS NULL THEN -10000
			WHEN	SI.Time like '%m'
			THEN DATEDIFF(
			DATE_ADD(
			S.Date,
			INTERVAL  REPLACE(SI.Time, 'm', '') MONTH),
			'".getCurrentDay()."')
			ELSE	-10000
			END	DaysLeft,
			SI.Mileage	NextMileage
			from	Service S
			join	ServiceOperatons SO on SO.ID = S.OperationID
			" . ( $onlyWithIntervals ? "" : "left" ) . " join
			ServiceIntervals SI on SI.OperationID = SO.ID
			where	S.CarID = $carID
			ORDER	BY S.Date,
			S.Mileage";
		
			return fDB::fqueryAll($query);
		}
	}
?>