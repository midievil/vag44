<?PHP
	function connectToDB()
	{
		require_once "constants.php";
		
		$link = mysql_connect(DB_SERV, DB_USER, DB_PASS);
		if (!$link) 
		{
		    die('Could not connect: ' . mysql_error());
		}
		$db = mysql_select_db(DB_NAME, $link);
		if (!$db) 
		{
			die('DB error');
		}
		
		mysql_query("SET CHARACTER SET utf8_unicode_ci");
		mysql_query("SET NAMES 'utf8'");
	}
	
	function writeExToLog($e)
	{
		$file = fopen("/log.txt", "a+");
		fwrite($file, $e->getMessage()." at ".getFile()." (".getLine()."). \n".getTraceAsString()."\n\n\n\n" );
	}

	function writeLog($message)
	{
		$file = fopen("log.txt", "a+");
		fputs($file, '
		
		--------------------------------------' . $message);
		fclose($file);
	}

	//в
?>