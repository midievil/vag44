<?PHP
	try
	{
		templater::assign('includeMainMenu', true);
		
		$galleryUserID = mLogic::$urlVariables["userid"];
		$currentGalleryID = mLogic::$urlVariables["id"];	
		
		
		if(!$galleryUserID && !$currentGalleryID)
		{
			$galleryUserID = $currentUser->ID;
		}
		
		if($currentGalleryID)
		{
			$gallery = getGalleryByID($currentGalleryID);
			templater::assign('gallery', $gallery);
		}
		
		if(!$galleryUserID && !empty($gallery))
		{				
			$galleryUserID =  $gallery['UserID'];
		}
		
		if($galleryUserID)
		{
			$galleryUser = new User($galleryUserID);
		}
		else
		{
			$galleryUser = new User(-1);
		}
		
		templater::assign('galleryUser', $galleryUser);
		
		global $breadCrumbs;
		
		$title = "";
			
		if($currentUser->IsLogged() && $currentUser->ID == $galleryUser->ID)
		{
			$breadCrumbs[] = new BreadCrumb('Ваши фотоальбомы', '/gallery/user/' . $galleryUser->ID);		
			templater::assign('title', 'Ваши фотоальбомы');		
		}
		else if ($galleryUser->ID != -1)
		{
			templater::assign('title', 'Фотоальбомы пользователя ' . $galleryUser->Name) ;
			$breadCrumbs[] = new BreadCrumb('Фотоальбомы пользователя ' . $galleryUser->Name, '/gallery/user/' . $galleryUser->ID);		
		}
		else
		{
			templater::assign('title', 'Галерея') ;
			$breadCrumbs[] = new BreadCrumb('Галерея', '/gallery/');
		}
		
		if($currentGalleryID)
		{		
			if(!empty($gallery))
			{
				templater::assign('title', $gallery["Name"]);
				$breadCrumbs[] = new BreadCrumb($gallery["Name"], '');			
			}
			else
			{
				templater::assign('title', "oops...");
				templater::assign('comment', "Такой галереи не существует. Возможно, вам дали битую ссылку?");
				$breadCrumbs[] = new BreadCrumb('Фотоальбомы');
			}
		}
		
		templater::assign('breadCrumbs', $breadCrumbs);
		
		if ($galleryUser->ID != -1)
		{			
			if(empty($gallery))
			{
				templater::assign('mode', 'galleries');
				
				$rowCounter = 0;
				
				if(!userGalleryExists($galleryUser->ID))
				{
					createUserGallery($galleryUser->ID, "Фотоальбом");
				}
				
				$rawGalleries = $galleryUser->Galleries();
				$galleries = array();
				foreach ($rawGalleries as $gallery)
				{
					if($gallery["Public"] || ($currentUser->IsLogged() && $galleryUser->ID == $currentUser->ID))
					{
						$thumbFile = '/img/gallery/' . $galleryUser->ID . '_' . $gallery['ID'] . '_' . $gallery["Thumbnail"] . '.jpg';
						if(!file_exists(".$thumbFile"))
						{
							$thumbFile = "/img/noimage.jpg";
						}
					
						$gallery['ThumbFile'] = $thumbFile;				
						$galleries[] = $gallery;
					}
				}
				
				templater::assign('galleries', $galleries);			
			}
			else
			{	
				templater::assign('mode', 'pictures');			
				
				$rawGalleryItems = getGalleryItems($currentGalleryID);			
				$galleryItems = array();
				
				foreach($rawGalleryItems as $galleryItem)
				{
					$fileName = $galleryUser->ID . '_' . $currentGalleryID . '_' . $galleryItem['ID'] . '.jpg';
					
					createPicPreview("img/gallery", $fileName, 200);
					
					$galleryItem['ThumbFile'] = "/img/gallery/previews/$fileName";
					$galleryItem['BigFile'] = "/img/gallery/$fileName";
					
					$galleryItems[] = $galleryItem;
				}			
				
				templater::assign('galleryItems', $galleryItems);				
			}
		}
		templater::display();
	}
	catch (Ecception $e)
	{
		var_dump($e);
	}
	
	
?>