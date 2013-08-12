<?PHP
	//error_reporting(0);
	session_start();
	
	include("../constants.php");
	
	include("../db.php");
	connectToDB();
	
	include("../userlogic.php");
	include("../db/GalleryDB.php");	
	include("../miscfunctions.php");	
	
	$error = "";	
	$msg = "";
	
	$fileElementName = $_GET["fileElementId"];	
	if(!$fileElementName)
	{
		$fileElementName = "fileToUpload";
	}
	
	if(!empty($_FILES[$fileElementName]['error']))
	{
		switch($_FILES[$fileElementName]['error'])
		{

			case '1':
				$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
				break;
			case '2':
				$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				break;
			case '3':
				$error = 'The uploaded file was only partially uploaded';
				break;
			case '4':
				$error = 'No file was uploaded.';
				break;
			case '6':
				$error = 'Missing a temporary folder';
				break;
			case '7':
				$error = 'Failed to write file to disk';
				break;
			case '8':
				$error = 'File upload stopped by extension';
				break;
			case '999':
			default:
				$error = 'No error code avaiable';
		}
		writeLog("\n\nImage Upload Error: " . $error . "; Errorcode: " . $_FILES[$fileElementName]['error'] . "\n");
	}
	elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
	{
		$error = 'No file was uploaded..';
	}
	else 
	{
			$filename = $_FILES[$fileElementName]['name'];
			
			if($userID = currentUserID())
			{
				if($fileElementName == "imageToUpload")
				{
					$msg .= " File Name: " . $_FILES[$fileElementName]['name'] . ", ";
					$msg .= " File Size: " . @filesize($_FILES[$fileElementName]['tmp_name']) . ", ";
					$msg .= " Path: " . $_GET["path"] . ", ";
					$path = $_GET["path"];
					
					if(TextFunctions::endsWith(strtolower($filename), ".jpg") || TextFunctions::endsWith(strtolower($filename), ".jpeg"))
					{
						$msg .= " Gal: " . $_GET["galleryid"] . ", ";
						
						$width = 1280;
						
						switch($path)
						{
							case "gallery":
								$itemid = insertGalleryItem($_GET["galleryid"]);
								$name = $userID."_".$_GET["galleryid"]."_".$itemid.".jpg";												
								break;
								
							case "postpics":
								$i=1;
								$postid = $_GET["postid"];
								while(file_exists("../img/postpics/$postid"."_$i.jpg"))
								{
									$i++;
								}
								$name = $postid."_$i.jpg";
								$msg = "/img/postpics/$name";
								break;
								
							case "newpics":
								$i=1;
								$userid = $_GET["userid"];
								while(file_exists("../img/newpics/$userid"."_$i.jpg"))
								{
									$i++;
								}
								$name = $userid."_$i.jpg";
								$msg = "/img/newpics/$name";
								break;
								
							case "userpics":
								$width = 100;
								$userid = $_GET["userid"];
								$name = "$userid.jpg";
								$file = "/img/userpics/$name";
								break;
						}
						$path = "../img/".$_GET["path"];
														
						move_uploaded_file($_FILES[$fileElementName]['tmp_name'], "$path/$name");
						
						include('simpleimage.php');
						$image = new SimpleImage();
						$image->load("$path/$name");
						if($width < $image->getWidth())
						{
							$image->resizeToWidth($width);
						}					
						$image->save("$path/$name");
					}
					else
					{
						$error = "Формат изображения должен быть JPG (JPEG)";
						writeLog("\n\nIncorrect File Format: " . $filename . "\n");
					}
				}
				else if($fileElementName == "fileToUpload")
				{
					switch($path)
					{
						
					}
					$msg = "File Uploaded";
				}
			}
			else
			{
				$error = 'You are not logged.';
			}
				
			//for security reason, we force to remove all uploaded file
			//@unlink($_FILES['fileToUpload']);		
	}
	echo "{";
	echo				"error: '" . $error . "',\n";
	echo				"msg: '" . $msg . "',\n";
	echo				"file: '" . $file . "'\n";
	echo "}";
?> 