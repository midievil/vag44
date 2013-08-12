<?php

	function formatPhone($phone)
	{
		return substr($phone, 0, 3)." ".substr($phone, 3, 3)." ".substr($phone, 6);
	}
	
	function getSocialNetworkName($url)
	{
		if(stripos($url, 'facebook.com') > 0)
		{
			return "facebook";
		}
		else if(stripos($url, 'vkontakte.ru') > -1 || stripos($url, 'vk.com') > -1)
		{
			return "вконтакте";
		}
		else if(stripos($url, 'drive2.ru') > -1)
		{
			return "Drive2";
		}
		return "";
	}
	
	function getSocialNetworkInnerName($url)
	{
		if(stripos($url, 'facebook.com') > 0)
		{
			return "facebook";
		}
		else if(stripos($url, 'vkontakte.ru') > -1  || stripos($url, 'vk.com') > -1)
		{
			return "vkontakte";
		}
		else if(stripos($url, 'drive2.ru') > -1)
		{
			return "drive2";
		}
		return "";
	}
	
	templater::assign('includeMainMenu', true);
	
	
	
	$user = new User(mLogic::$urlVariables['userid']);	
	templater::assign('user', $user);
	
	
	global $breadCrumbs;
	$breadCrumbs[] = new BreadCrumb("Люди", "/users");
	$breadCrumbs[] = new BreadCrumb($user->Name);
	templater::assign('breadCrumbs', $breadCrumbs);
	

	$reg = DateFunctions::getDateAtText($user->RegisterDate); 
	if($reg)
	{
		$comment .= "С нами с $reg.<br />";		
	}
	
	$comment .= "Группа: $user->GroupName";
	
	templater::assign('comment', $comment);
		
	
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
		$ratingText .= "<div class=\"span2 pull-left nowrap\"><a href=/user/" . $ratingRow["FromUserID"] . ">".$ratingRow["FromUserName"]."</a> (+".$ratingRow["Value"].")&nbsp;</div>";
	
		$colIndex++;
		if($colIndex == $columnsCount)
		{
			$colIndex = 0;
			$ratingText .= "<br />";
		}
	}
	templater::assign('ratingList', $ratingText);

	$rating = "<a class='btn popover-bottom' rel='popover' data-toggle='popover' title='' data-content='$ratingText' data-original-title='Рейтинг'>$user->Rating</a>";
	templater::assign('rating', $rating);
	
	
	templater::assign('blogs', $user->Blogs());
	
	templater::assign('galleries', $user->Galleries());
	
	templater::display();
	
?>