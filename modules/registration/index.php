<?PHP

	if(mLogic::$urlVariables['registration'] == 'congratulations')
	{
		templater::display('registration/congratulations.html');
	}
	else
	{
		templater::display();
	}
?>