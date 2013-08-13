<?PHP

	$messages = array();
	if($currentUser->IsLogged())
	{	
		$privateMessagesCount = MessagingDB::checkPrivateMessages($currentUser->ID);
		templater::assign('privateMessagesCount', $privateMessagesCount);
		if($privateMessagesCount > 0)
		{
			$messages[] = "у вас есть непрочитанные сообщения. Вы можете прочесть их <a href='/privatemessages'>здесь</a>.";
		}
			
		if($currentUser->Email == "")
		{	
			$messages[] = "у вас не указан e-mail. Это может вызвать некоторые затруднения в случае потери пароля. Указать его можно <a href='/profile/contacts'>здесь</a>.";
		}
		
		if($currentUser->CategoriesOrder == "")
		{
			$messages[] = "Появилась возможность упорядочивать разделы по дате последнего комментария. Настроить это можно <a href='/profile/settings'>здесь</a>.";
		}
		
		if($currentUser->PageSize == -1)
		{
			$messages[] = "Появилась возможность просматривать комментарии постранично. Настроить это можно <a href='/profile/settings'>здесь</a>.";
		}
		
		if($currentUser->BirthDate == "1900-01-01")
		{
			$messages[] = "У вас не указан день рождения. Указать его или отказаться от этого можно <a href='/profile/about'>здесь</a>.";
		}
		
		foreach ($currentUser->Cars() as $car)
		{
			if(!$car->InPast)
			{
				if($car->IsVag)
				{
					if(!$car->EngineName)
					{
						$messages[] = "У вашего автомобиля " . $car->getShortDescription() . " не указан двигатель. Вы можете указать его <a href='/profile/cars'>здесь</a><br/>";
					}
					if($car->PostID && !file_exists("img/postpics/" . $car->PostID . "_1.jpg"))
					{
						$messages[] = "В описании вашего автомобиля " . $car->getShortDescription() . " нет ни одного фото, поэтому он не отображается в списке клубных автомобилей. Вы можете добавить фото <a href='/editpost/" . $car->PostID . "'>здесь</a>";
					}
				}
				
				$serviceRows = CarDB::getCarServiceHistory($car->ID, true);
				
				$foundInsurance = 0;
				foreach($serviceRows as $serviceRow)
				{
					if($serviceRow["OperationID"] == 11 || $serviceRow["OperationID"] == 12)
					{
						$foundInsurance = 1;
					}
				
					if(!$serviceRow["DontRemind"])
					{
						$days = $serviceRow["DaysLeft"];
						
						if($days != -10000)
						{
							if($days < 30)
							{
								$messages[] = "" . DateFunctions::getDaysCountHint($days) . " " . ($days > 0 ? "истекает" : "истек") . " срок " . ($serviceRow["MessageText"] ? $serviceRow["MessageText"] : "операции " . $serviceRow["Operation"]) . " на ваш " . $car->getShortDescription() . "! <a class='hand' onclick='dontRemindService(".$serviceRow["ID"].");'>(больше не напоминать)</a>";
							}
						}
					}
				}
				
				if(!$foundInsurance)
				{
					$messages[] = "У вашего автомобиля " . $car->getShortDescription() . " не указана дата страхования ОСАГО. Если вы <a href='/service'>укажете её</a>, мы напомним вам, когда нужно продлить страховку";
				}
			}
		}
		
		templater::assign('userMessages', $messages);
	}	
?>