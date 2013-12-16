<?PHP
	$action = $_POST["action"];
	if(!$action)
	{
		return;
	}
	
	switch($action)
	{
		case "getdir":
			echo getcwd();
			return;
		case "fitimage":	
			$source = $_POST["srcimage"];
			$path = $_POST["path"];
			$side = $_POST["side"];
			$destination = $_POST["destination"];
			$leavebigcopy = $_POST["leavebigcopy"];
			$multiple = $_POST["multiple"];
			
			if($source[0]=="/")
			{
				$source = substr($source, 1);
			}
			
			if(!file_exists($source))
			{
				echo "error=No file $source;";
				return;
			}
						
			$imagedata = getimagesize($source);
			
			$w = $imagedata[0];
			$h = $imagedata[1];
			
			if ($h > $w) {
				$new_w = ($side / $h) * $w;
				$new_h = $side;	
			} else {
				$new_h = ($side / $w) * $h;
				$new_w = $side;
			}
			
			if($new_h > $h || $new_w > $w)
			{
				$new_h = $h;
				$new_w = $w;
			}
	
			$im2 = ImageCreateTrueColor($new_w, $new_h);
			$image = ImageCreateFromJpeg($source);
			imagecopyResampled ($im2, $image, 0, 0, 0, 0, $new_w, $new_h, $imagedata[0], $imagedata[1]);
			
			$num = "";
			if($multiple)
			{
				$num = 1;
				while(file_exists("$path/$destination"."_$num.jpg"))
				{
					$num++;
				}
				$num = "_$num";
			}
			
			imagejpeg($im2, "$path/$destination$num.jpg");
			if($leavebigcopy)
			{
				imagejpeg($image, "$path/$destination$num"."_big.jpg");
			}
			unlink($source);
			
			echo "ok=/$path/$destination$num";
			return;
	}
?>