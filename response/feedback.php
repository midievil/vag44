<?PHP
	error_reporting(0);
	if(!$sessionstarted) {	session_start(); $sessionstarted = 1; }	
	if(!$headersent)
	{
		header('Content-type: text/html; charset=utf-8');
		$headersent = 1;
	}
	
	require_once "../constants.php";	
	require_once "../miscfunctions.php";	
	require_once "../inc/class.db.php";
	require_once "../inc/class.fdb.php";
	
	$userid = -1;
	$header = $_GET["header"];
	$text = $_GET["text"];
	
	if(	$userid && $header && $text	)
	{
		$query = "insert into FeedBacks(UserID, Header, Text) values ($userid, '$header', '$text')";
		if(fDB::fexec($query))
		{		
			echo "ok";
			return;
		}
	}
	else
	{
		if(!$userid)		
		{
			echo "nouser&";
		}
		if(!$header)
		{
			echo "noheader&";
		}
		if(!$text)
		{
			echo "notext&";
		}
	}
	echo "error";
	return;
?>