<?PHP
	if(!$sessionstarted) {	session_start(); $sessionstarted = 1; }	
	if(!$headersent)
	{
		header('Content-type: text/html; charset=utf-8');
		$headersent = 1;
	}
	chdir("..");
	require_once "constants.php";	
	require_once "miscfunctions.php";	
	require_once 'db/AdminDB.php';
	
	$userid = -1;
	$header = $_GET["header"];
	$text = $_GET["text"];
	
    if(	$userid && $header && $text	)
	{
		if(AdminDB::addFeedback($userid, $header, $text))
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