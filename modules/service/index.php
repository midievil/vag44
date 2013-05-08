<?PHP		
	if(!$currentUser->IsLogged())
	{
		echo "Вы не вошли";
		return;
	}
	
	$breadCrumbs[] = new BreadCrumb('Сервисная книжка', '');
	templater::assign('breadCrumbs', $breadCrumbs);

		
	
	$selectedCarID = mLogic::$urlVariables["id"];
	$serviceOperationsSelect = getServiceOperationsSelect();
	
	templater::assign('serviceOperationsSelect', $serviceOperationsSelect);

	
	$carRows = CarDB::getCarsByUserID($currentUser->ID);
	
	$cars = array();
	
	foreach ($carRows as $carRow)
	{
		$car = new Car();
		$car->MakeFromRow($carRow);
		$cars[] = $car;			
	}
	templater::assign('cars', $cars);
		
	templater::display();
	die;
?>