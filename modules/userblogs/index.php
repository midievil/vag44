<?php

	templater::assign('includeMainMenu', true);
	
	global $breadCrumbs;
	$user = new User(mLogic::$urlVariables['userid']);	
	templater::assign('user', $user);
	
	templater::assign('registerDate', getDateAtText($user->RegisterDate));
	templater::assign('phone', formatPhone($user->Phone));
	
	$social = explode ("\n", trim($user->Social));
	$socialNetworks = array();
	if($social && $social[0])
	{
		$i = 0;
		$socialFormatted = "";
		while($url = $social[$i++])
		{
			if($socialName = getSocialNetworkInnerName($url))
			{
				$network['url'] = $url;
				$network['name'] = getSocialNetworkName($url);
				$network['innername'] = $socialName;
				$socialNetworks[] = $network;
			}
		}
	}
	templater::assign('social', $socialNetworks);
	
	
	templater::assign('carslist', getCarsListByUserID($user->ID));
	
	$rating = UserDB::getUserRatingByID($user->ID);
	$ratingText = "";
	
	$columnsCount = ceil(count($rating) / 10);
	$colIndex = 0;
	foreach ($rating as $ratingRow)
	{
		if($colIndex == 0)
		{
			$ratingText .= "<tr>";
		}
		$ratingText .= "<td><a href=/user/" . $ratingRow["FromUserID"] . ">".$ratingRow["FromUserName"]."</a> (+".$ratingRow["Value"].")&nbsp;</td>";
	
		$colIndex++;
		if($colIndex == $columnsCount)
		{
			$colIndex = 0;
			$ratingText .= "</tr>";
		}
	}
	templater::assign('ratingList', renderPopup($ratingText));
	
	
	templater::display();
	
?>