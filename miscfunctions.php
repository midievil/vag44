<?PHP
		
	function getFileNameToInclude($get)
	{
		$get = explode("?", $get);
		return $get[0];
	}
	
	function getCurrentDateText()
	{
		date_default_timezone_set("Europe/Moscow");
		return date("Y-m-d G:i:s.u");
	}
	
	function getCurrentDay()
	{
		date_default_timezone_set("Europe/Moscow");
		return date("Y-m-d");
	}
	
	function getCurrentShortDateText()
	{
		date_default_timezone_set("Europe/Moscow");
		return date("d.m.Y");
	}
	
	function getDateAtText($dateFromDB)
	{
		if($dateFromDB)
		{
			$date = explode(" ", $dateFromDB);
			$date = explode("-", $date[0]);
			return $date[2].".".$date[1].".".$date[0];
		}
		else
		{
			return "";
		}
	}
	
	function getDateTimeAtText($date)
	{				
		//$dtPostTest = $dtNow["year"]."-0".$dtNow["mon"]."-".$dtNow["mday"]." 0".$dtNow["hours"].":".$dtNow["minutes"].":".$dtNow["seconds"]."<br />";
		date_default_timezone_set("Europe/Moscow");
		
		$dtNow = getdate();	
		
		
		require_once "constants.php";
				
		$dtPost = date_create($date." +".TIME_SHIFT." hours");
		$date = date_create($date." +".TIME_SHIFT." hours");
				
		$dtPost = date_format($dtPost, "Y-m-d-H-i-s");
		$dtPost = explode('-', $dtPost);		
		
		$timestampNow =		(mktime(0,0,0,	$dtNow['mon'],	$dtNow['mday'],	$dtNow['year']	));
		$timestampPost = 	(mktime(0,0,0,	$dtPost[1], 	$dtPost[2],		intval($dtPost[0])		));
		$difference = floor(($timestampPost - $timestampNow)/86400);		
		
		//echo "$dtPost[3], $dtPost[4], $dtPost[5], ".($dtPost[1]*1).", 	$dtPost[2],		$dtPost[0]<br />";
		//echo $dtNow['hours'].", ".$dtNow['minutes'].", ".$dtNow['seconds'].", ".$dtNow['mon'].", ".$dtNow['mday'].", ".$dtNow['year']."<br />";
		//echo $difference."<br />";
		
		if($difference == 0)
		{
			return date_format($date, "<b>Сегодня</b> в G:i");
		}
		else if($difference == -1)
		{			
			return date_format($date, "<b>Вчера</b> в G:i");
		}
		else
		{
			return date_format($date, "d.m.Y в G:i");
		}
	}
	
	function pasteUploadedImage($text, $postid)
	{
		$text = preg_replace("/\[img](\d+)\[\/img]/",
                     "<a class='hand' onclick='galleryItem=\${1}; ShowImageGallery(\"\${1}\", true);'>
						<img class='postpic' src=\"/img/postpics/$postid"."_\${1}.jpg\" /></a></br>",
					 $text);		
		return $text;
	}
	
	function pasteExternalImages($text)
	{
		$text = str_replace("[img]", "<br /><img style='max-width:700px; max-height:400px;' src='", $text);
		$text = str_replace("[/img]", "' /></a></br />", $text);
		return $text;
	}
	
	function pasteLinks($text)
	{
		//	Вставка линков вида [url=То_Что_Видит_Юзер]url[/url]
		$text = preg_replace(	'/(\[url=(.*)\])(.*)(\[\/url\])/i', 
	                    "<a href='$3'>$2</a><br />", 
						$text);

		
		$text = ereg_replace("[^\"'](https?|ftp|file)://[^<>[:space:]]+[[:alnum:]/]",
                     "<a href=\"\\0\">\\0</a>", $text);		
		return $text;
	}
	
	function getFileExtension($url)
	{
		$matches = "";
		preg_match_all(	'/(\.jpg|\.jpeg|\.gif|\.png)/i', 
	                    $url,
						$matches,
						PREG_PATTERN_ORDER);
		return $matches[0][0];
	}
			
	function createThumbnailsForGalleryPics($url)
	{
		global $domain;
		if(stripos($url, $domain) && stripos($url, '/gallery/'))
		{			
			if(stripos($url, '/previews/'))
			{
				$splits = explode('/previews/', $url);
			}
			else
			{	$splits = explode('/gallery/', $url);
			}
			
			return $splits[1];
		}
		return "";
	}
			
	function createThumbnailsForForeignPics($url)
	{		
		global $domain;
		if(stripos($url, $domain.'/ckeditor/'))
		{
			return "";
		}
		$query = "select ID from ForeignPictures where URL='$url' LIMIT 1";
		$result = mysql_query($query);
				
		$ext = getFileExtension($url);
		
		if(mysql_errno())
		{
			writeLog(mysql_error());
		}
				
		if(mysql_num_rows($result))
		{
			$result = mysql_fetch_assoc($result);
			$id = $result["ID"];
		}
		else
		{
			$query = "insert into ForeignPictures (URL, Comment) values ('$url', '')";
			mysql_query($query);
			$id = mysql_insert_id();
		}
		
		$fileName = $id.$ext;
		if(!file_exists("img/foreign/$fileName"))
		{
			//return "";
			$fp = fopen($url, "rb");
			if($fp)
			{
				$fd = fopen("img/foreign/$fileName", "w");
				if ($fd) {
					while (!feof($fp)) {
						$st = fread($fp, 4096);
						fwrite($fd, $st);
					}
				}
				fclose($fp);
				fclose($fd);
			}
			else
			{
				fclose($fp);
				return;
			}
					
		
			$image = new SimpleImage();				
			$image->load("$url");
			// $image->save("img/foreign/$fileName");
			
			$image->resizeToWidth(100);
			$image->save("img/foreign/previews/$fileName");
		}
		return $fileName;
		
		// return "";
	}

	function pastePics($text)
	{		
		$tryPreviews = 1;
		if($tryPreviews == 1)
		{	
			//$text = preg_replace('/<img.*"(http:\/\/[^<>[:space:]]+[[:alnum:]\/](.jpg|.jpeg|.gif|.png))"[^<>]+.*\/>/iu', "\\1", $text);
			$matches = "";
			preg_match_all(
						'/[^"](https?|ftp|file):\/\/[^<>[:space:]]+[[:alnum:]\/](.jpg|.jpeg|.gif|.png)/i', 
	                    $text,
						$matches,
						PREG_PATTERN_ORDER);

			$matches = $matches[0];
			$i=0;			
			while($url = trim($matches[$i]))
			{	
				$newUrl = createThumbnailsForGalleryPics($url);
				if($newUrl)
				{
					$text = preg_replace('/[^"]'.str_replace("/", "\/", $url).'/i', "<br /><img class='hand' onclick='ShowImageGallery(\"/img/gallery/$newUrl\", false);' src='/img/gallery/previews/$newUrl' /><br /><a class='comment'>Щелкните для увеличения</a>", $text);
				}
				else
				{
					$newUrl = createThumbnailsForForeignPics($url);
					if($newUrl && file_exists("img/foreign/$newUrl"))
					{
						$text = preg_replace('/[^"]'.str_replace("/", "\/", $url).'/i', "<br /><img class='hand' onclick='ShowImageGallery(\"/img/foreign/$newUrl\", false);' src='/img/foreign/previews/$newUrl' /><br /><a class='comment'>Щелкните для увеличения</a>", $text);
					}
					else
					{
						$text = str_replace($url, "<br /><img class='commentpic' src='$url' /><br />", $text);						
					}
				}				
				$i++;
			}
		}
		else
		{
			$text = ereg_replace("(https?|ftp|file)://[^<>[:space:]]+[[:alnum:]/](.jpg|.jpeg|.gif)",
	                     "<br /><a onclick='ShowImageGallery(\"\\0\", false);'><img style='max-width:700px; max-height:400px; cursor:pointer; cursor:hand' src=\"\\0\" /></a><br />", $text);
		}
		return $text;
		
	}
	
	function formatText($text)
	{
		$text = " ".$text." ";
		$text = preg_replace("/\[quote=(.+)](.*)\[\/quote]/", "<a class='quote'>\n<b>$1:</b> $2</a>", $text);
		
		$text = preg_replace("/\[file](.+)\[\/file]/", "\n<a class='comment' href='/uploads/postfiles/$1' target='_blank'><img width='50px' src='/img/file.jpg' title='скачать'><br />$1</a>", $text);
				
		if(!strpos($text, '<p>'))
		{
			$text = str_replace("\n", " <br/> ", $text);		
		}
		$text = str_replace("[i]", "<a class='textitalic'>", $text);
		$text = str_replace("[/i]", "</a>", $text);
		$text = str_replace("[b]", "<a class='textbold'>", $text);
		$text = str_replace("[/b]", "</a>", $text);
		$text = str_replace("[s]", "<strike>", $text);
		$text = str_replace("[/s]", "</strike>", $text);
		
		$text = str_replace("[cut]", "<div class='cut'>
										<a class='comment hand' onclick='expandCut(this);'>Показать</a>
										<div class='undercut'>", $text);
		$text = str_replace("[/cut]", "</div></div>", $text);
		
		$text = pastePics($text);
		$text = pasteLinks($text);
		
		$text = str_replace("[IMG]", "", $text);
		$text = str_replace("[/IMG]", "", $text);
		$text = str_replace("[img]", "", $text);
		$text = str_replace("[/img]", "", $text);
		
		$text = str_replace("_smile.gif", "_smile.gif?version=new", $text);
		
		return $text;
	}
	
	function replaceSymbols($text)
	{
		$text = str_replace("[plus]", "+", $text);
		$text = str_replace("[percent]", "%", $text);
		$text = str_replace("[singlequote]", "'", $text);
		$text = str_replace("'", "\'", $text);
		$text = str_replace("[ampersand]", "&", $text);
		return $text;
	}
		
	function getPostsCountHint($count)
	{
		if($count == 0)		
		{
			return "нет постов";
		}
		else if($count % 10 == 0)
		{
			return "$count постов";
		}
		else if($count % 10 == 1 && $count != 11)
		{
			return "$count пост";
		}
		else if(($count < 10 || $count > 20) && $count % 10 >= 2 && $count % 10 <= 4)
		{
			return "$count поста";
		}
		else
		{
			return "$count постов";
		}
	}
	
	function getCommentsCountHint($count)
	{
		if($count == 0)		
		{
			return "нет ответов";
		}
		else if($count % 10 == 1 && $count != 11)
		{
			return "$count ответ";
		}
		else if(($count < 10 || $count > 20) && $count % 10 >= 2 && $count % 10 <= 4)
		{
			return "$count ответа";
		}
		else
		{
			return "$count ответов";
		}
	}
	
	function getVisitsCountHint($count)
	{
		if($count == 0)		
		{
			return "";
		}
		else if($count % 10 == 1 && $count != 11)
		{
			return "$count просмотр";
		}
		else if(($count < 10 || $count > 20) && $count % 10 >= 2 && $count % 10 <= 4)
		{
			return "$count просмотра";
		}
		else
		{
			return "$count просмотров";
		}
	}
	
	function startsWith($haystack, $needle)
	{
	    $length = strlen($needle);
	    return (substr($haystack, 0, $length) == $needle);
	}

	function endsWith($haystack, $needle)
	{
	    $length = strlen($needle);
	    $start  = $length * -1; //negative
	    return (substr($haystack, $start) == $needle);
	}
	
	
	function unescape($source)
	{
	    $decodedStr = "";
	    $pos = 0;
	    $len = strlen ($source);
	    while ($pos < $len) 
	    {
	        $charAt = substr ($source, $pos, 1);
	        if ($charAt == '%') 
	        {
	            $pos++;
	            $charAt = substr ($source, $pos, 1);
	            if ($charAt == 'u') 
	            {
	                $pos++;
	                $unicodeHexVal = substr ($source, $pos, 4);
	                $unicode = hexdec ($unicodeHexVal);
	                $entity = "&#". $unicode . ';';
	                $decodedStr .= utf8_encode ($entity);
	                $pos += 4;
	            }
	            else 
	            {
	                $hexVal = substr ($source, $pos, 2);
	                $decodedStr .= chr (hexdec ($hexVal));
	                $pos += 2;
	            }
	        }
	        else
	        {
	                $decodedStr .= $charAt;
	            $pos++;
	        }
	    }
	    
	    static $trans_tbl;
	    $decodedStr = preg_replace('~&#x([0-9a-f]+);~ei', 'code2utf(hexdec("\\1"))', $decodedStr);
	    $decodedStr = preg_replace('~&#([0-9]+);~e', 'code2utf(\\1)', $decodedStr);
	    if (!isset($trans_tbl))
	    {
	        $trans_tbl = array();
	        foreach (get_html_translation_table(HTML_ENTITIES) as $val=>$key)
	        $trans_tbl[$key] = utf8_encode($val);
	    }
	   
	    return strtr($decodedStr, $trans_tbl);
	    
	    return $decodedStr;
	}

	function code2utf($num)
	{
	    if ($num < 128) return chr($num);
	    if ($num < 2048) return chr(($num >> 6) + 192) . chr(($num & 63) + 128);
	    if ($num < 65536) return chr(($num >> 12) + 224) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
	    if ($num < 2097152) return chr(($num >> 18) + 240) . chr((($num >> 12) & 63) + 128) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
	    return '';
	} 
	
	function drawPageTitle($pageCode, $title, $content, $comment, $path)
	{
		global $currentUser;
		
		if(startsWith($pageCode, "userpic"))
		{	
			$splits = explode("=", $pageCode);
			if(file_exists("./img/userpics/" . $splits[1]))
			{
				$img = "/img/userpics/" . $splits[1];
			}
		}
		
		if(!$img)
		{
			if(file_exists("./img/categoryimages/$pageCode.jpg"))
			{
				$img = "/img/categoryimages/$pageCode";
			}
			else 
			{
				$vendor = getCarVendorByUserID($currentUser->ID);			
				if($vendor && file_exists("/img/categoryimages/$vendor.jpg"))
				{
					$img = "/img/categoryimages/$vendor";
				}
				else
				{
					$img = "/img/categoryimages/default";
				}
			}
		}
		
		echo "
<div class='categorytitle'>
	<table cellspacing='0' >
		<tr>
			<td colspan='2' class='path'>
				$path
			</td>
		</tr>
		<tr>"
			. ( $pageCode != 'none' ?
			"<td class='icon' rowspan='2'>
				<img class='pagetitle' src='$img.jpg' />			
			</td>" : "" ). "
			<td class='title'>
				$title
			</td>
		</tr>
		<tr>
			<td class='comment'>
				$comment
			</td>
		</tr>" . ( $content ? "
		<tr>
			<td colspan='2' class='content'>
				$content
			</td>
		</tr>" : "" ) . "
	</table>
</div>";
	}
	
	function getDateSelector($controlName, $selectedDate)
	{
		if($selectedDate == "1900-01-01")
		{
			$year = "0";
			$month = "0";
			$day = "0";
		}
		else
		{
			$dateParts = explode("-", $selectedDate);
			$year = $dateParts[0];
			$month = $dateParts[1];
			$day = $dateParts[2];
		}
		
		$monthNames = array(1 => 'январь', 2 => 'февраль', 3 => 'март', 4 => 'апрель', 5 => 'май', 6 => 'июнь', 7 => 'июль', 8 => 'август', 9 => 'сентябрь', 10 => 'октябрь', 11 => 'ноябрь', 12 => 'декабрь');
		
		$result = "";
		
		$result .= "<select id='ddlDateDay$controlName' class='input-mini'><option value='0'></option>";
		for($i=1; $i<=31; $i++)
		{	
			$result .= "<option value='$i' " . ($i == $day ? "selected" : "") . ">$i</option>";
		}
		$result .= '</select>';
		
		$result .= "<select id='ddlDateMonth$controlName' class='input-medium'><option value='0'></option>";
		for($i=1; $i<=12; $i++)
		{	
			$result .= "<option value='$i' " . ($i == $month ? "selected" : "") . ">" . $monthNames[$i] . "</option>";
		}
		$result .= '</select>';
		
		$result .= "<select id='ddlDateYear$controlName' class='input-small add-on'><option value='1900'></option>";
		for($i=1950; $i<2000; $i++)
		{	
			$result .= "<option value='$i' " . ($i == $year ? "selected" : "") . ">$i</option>";
		}
		$result .= "</select>";
		
		return $result;
	}
	
	function createPicPreview($path, $filename, $width)
	{
		if(!file_exists("$path/previews/$filename"))
		{		
			$image = new SimpleImage();
			$image->load("$path/$filename");
			$image->resizeToWidth($width);										
			$image->save("$path/previews/$filename");
		}
	}	
	
	function getDaysCountHint($count)
	{
		if($count == 0)		
		{
			return "сегодня";
		}
		else if($count == 1)
		{
			return "завтра";
		}		
		else if($count == 2)
		{
			return "послезавтра";
		}
		else if($count % 10 == 1 && $count != 11)
		{
			return "через $count день";
		}		
		else if(($count < 10 || $count > 20) && $count % 10 >= 2 && $count % 10 <= 4)
		{
			return "через $count дня";
		}		
		else if ($count < 0)
		{
			$count = 0 - $count;
			if($count == 1)
			{
				return "вчера";
			}
			else if($count % 10 == 1 && $count != 11)
			{
				return "$count день назад";
			}
			else if(($count < 10 || $count > 20) && $count % 10 >= 2 && $count % 10 <= 4)
			{
				return "$count дня назад";
			}
			else
			{				
				return "$count дней назад ";// . ($count % 10);
			}
		}
		else
		{
			return "через $count дней";
		}
		
	}
	
	function renderPopup($content)
	{
		if($content)
		{
			return "onmouseover='showPopup(this, \"$content\");' onmouseout='hidePopup(this);'";
		}
		return "";
	}
	
	function renderRatingForPost($value, $postid, $postUserID)
	{
		global $currentUser;
		
		$level = $value > 2 ? "btn-success" : "";
				
		$result = "";
		$alreadyIncreased = false;
		
		if($value > 0)
		{			
			$popupText = "Этот пост нравится:";
			$ratingRecords = getRatingForPost($postid);
			while($row = mysql_fetch_assoc($ratingRecords))
			{
				$popupText .=  "\n" . $row["Name"];
				if($row["ID"] == $currentUser->ID)
				{
					$alreadyIncreased = true;
				}
			}
		}
		else
		{
			$popupText = "Рейтинг этого поста";
		}
		
		$result .=  "<a class='btn $level' href='#' rel='tooltip' data-original-title='$popupText'>$value</a>";
		if(!$alreadyIncreased && $currentUser->IsLogged() && $currentUser->ID != $postUserID)
		{		
			$result .=  "&nbsp;<a rel='tooltip' data-original-title='Повысить рейтинг' class='btn' onclick='thankUser($postid, -1, $postUserID)'>+1</a>";
			
			
		}
		return $result;
	}
	
	function renderRatingForComment($value, $postid, $commentid, $commentUserID)
	{
		global $currentUser;
		$level = $value > 2 ? "good" : "neutral";
		
		$result = "";
		$alreadyIncreased = false;
		
		if($value > 0)
		{			
			$popupText = "<b>Этот комментарий нравится:</b>";
			$ratingRecords = getRatingForComment($commentid);
			while($row = mysql_fetch_assoc($ratingRecords))
			{
				$popupText .=  "<br />" . $row["Name"];
				if($row["ID"] == $currentUser->ID)
				{
					$alreadyIncreased = true;
				}
			}
		}
		else
		{
			$popupText = "<b>Рейтинг этого комментария</b>";
		}
		
		$result .=  "<span id='divCommentRating$commentid' class='rating $level' " . renderPopup("$popupText") . ">$value</span>";
		if(!$alreadyIncreased && $currentUser->IsLogged() && $currentUser->ID != $commentUserID)
		{
			$result .=  "&nbsp;<a id='aCommentIncrease$commentid' class='hand increasecomment' onclick='thankUser($postid, $commentid, $commentUserID)'>+1</a>";
		}
		return "<span class='nowrap'>$result</span>";
	}
	
	function getRandomCatchPhrase()
	{
		$query = "select COUNT(ID) CNT from CatchPhrases";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		$count = $row["CNT"];
		
		$pos = rand(1, $count) - 1;
		
		$query = "select Text from CatchPhrases	LIMIT $pos, 1";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		return $row["Text"];
	}
?>