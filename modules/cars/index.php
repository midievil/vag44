<?PHP


	templater::assign('includeMainMenu', true);

	global $breadCrumbs;
	$breadCrumbs[] = new BreadCrumb('Клубные авто', '/cars');

	$vendorid = mLogic::$urlVariables["vendorid"];	
	$modelid = mLogic::$urlVariables["modelid"];
	
	$vendor = getVendorByID($vendorid);
	$model = getModelByID($modelid);
		
	templater::assign('vendor', $vendor);
	templater::assign('model', $model);
	
	$carRows = array();

	if($vendor)
	{
		$breadCrumbs[] = new BreadCrumb($vendor["Name"], '/'.$vendor["Name"]);
		templater::assign('title', $vendor["Name"]);
		templater::assign('comment', "Машин на сайте: " . $vendor["CountOnSite"]);
		
		if($model)
		{
			$breadCrumbs[] = new BreadCrumb($model["Name"], '');
			templater::assign('title', $model["Name"]);
			templater::assign('comment', "Машин на сайте: " . $model["CountOnSite"]);
			$carRows = CarDB::getCarsByModel($modelid);
		}
		else if($vendor["CountOnSite"] > 0)
		{
			templater::assign('models', CarDB::getCarModelsForMenu($vendorid));
			$carRows = CarDB::getCarsByVendor($vendorid);			
		}
	}
	else
	{
		$carRows = CarDB::getCarsByVendor(0);
	}
	

	templater::assign('breadCrumbs', $breadCrumbs);
	
	
	$carDescriptions = array();
	foreach($carRows as $carRow)
	{		
		$car = new Car();
		$car->MakeFromRow($carRow);
		
		$cardesc = drawCarFaceWithUser($car);
		if($cardesc)
		{
			$carDescriptions[] = $cardesc; 
		}				
	}
	templater::assign('carDescriptions', $carDescriptions);
	
	templater::display();
	die;
?>