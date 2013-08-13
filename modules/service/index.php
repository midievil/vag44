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
	
	templater::assign('cars', $currentUser->Cars());
		
	templater::display();
	die;
?>