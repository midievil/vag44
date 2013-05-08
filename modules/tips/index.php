<?php

	$tip = mLogic::$urlVariables['tip'];		
	$file = 'tips/' . $tip . '.tpl.html';
	
	if(file_exists('themes/'.$file))
	{
		templater::display($file);
	}
	
	templater::display();
	
?>