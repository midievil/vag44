<?php
	
	templater::assign('includeMainMenu', true);

	$module = '';

	if(mLogic::$urlVariables['knowlege'])
	{	
		$breadCrumbs[] = new BreadCrumb('База знаний', '/knowlege/');
		
		switch (mLogic::$urlVariables['knowlege'])
		{
			case "engines":
				$vendorID = $_GET["vendorid"];
				$modelID = $_GET["modelid"];
				$generation = $_GET["generation"];
				
				templater::assign('vendorID', $vendorID);
				templater::assign('modelID', $modelID);
				templater::assign('generation', $generation);
				
				templater::assign('vendors', CarDB::getVendors());
				
				if( $vendorID )
				{
					templater::assign('models',	getModelsByVendorID($vendorID));
						
					if( $modelID )
					{
						$model = CarDB::getModelByID($modelID);
						templater::assign('generations', explode(';', $model['generations']));
					}
				}
				
				templater::assign('engines', CarDB::getEngines());
				
				templater::assign('list', getEnginesForKnowlege($vendorID, $modelID, $generation));
				
				$module = 'knowlege/engines.tpl.html';
				break;
		}		
	}
	else
	{
		$breadCrumbs[] = new BreadCrumb('База знаний', '');
	}	
	
	templater::assign('breadCrumbs', $breadCrumbs);
	templater::display($module);
	
?>