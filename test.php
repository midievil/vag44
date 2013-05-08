<?PHP
function get_page_as_browser($url, $with_timeout = 10, $with_cookies = false, $with_redirects = true)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
 
	if ($with_cookies)
	{
		curl_setopt($ch, CURLOPT_COOKIEFILE,  'cookiefile');
		curl_setopt($ch, CURLOPT_COOKIEJAR,  'cookiefile');
	}
 
	if ($with_redirects) curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
 
	curl_setopt($ch, CURLOPT_TIMEOUT, $with_timeout);
	curl_setopt($ch, CURLOPT_USERAGENT, get_random_user_agent());
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
 
	$ret = trim(curl_exec($ch));
	curl_close($ch);	
 
	return $ret;
}
function get_random_user_agent()
{
     $uas = array(
       'Mozilla/4.0 (compatible; MSIE 6.0; Windows 98)',
       'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0; .NET CLR 1.0.3705)',
       'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; Maxthon)',
       'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; bgft)',
       'Mozilla/4.5b1 [en] (X11; I; Linux 2.0.35 i586)',
       'Mozilla/5.0 (compatible; Konqueror/2.2.2; Linux 2.4.14-xfs; X11; i686)',
       'Mozilla/5.0 (Macintosh; U; PPC; en-US; rv:0.9.2) Gecko/20010726 Netscape6/6.1',
       'Mozilla/5.0 (Windows; U; Win98; en-US; rv:0.9.2) Gecko/20010726 Netscape6/6.1',
       'Mozilla/5.0 (X11; U; Linux 2.4.2-2 i586; en-US; m18) Gecko/20010131 Netscape6/6.01',
       'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:0.9.3) Gecko/20010801',
       'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.0.7) Gecko/20060909 Firefox/1.5.0.7',
       'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.6) Gecko/20040413 Epiphany/1.2.1',
       'Opera/9.0 (Windows NT 5.1; U; en)',
       'Opera/8.51 (Windows NT 5.1; U; en)',
       'Opera/7.21 (Windows NT 5.1; U)',
       'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT)',
       'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
       'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.0.6) Gecko/20060928 Firefox/1.5.0.6',
       'Opera/9.02 (Windows NT 5.1; U; en)',
       'Opera/8.54 (Windows NT 5.1; U; en)'
     );
 
     return $uas[rand(0, count($uas)-1)];
}


	function customError($errno, $errstr)
	  {
	  echo "<b>Error:</b> [$errno] $errstr<br />";
	  echo "Ending Script";
	  die();
	  }

	  set_error_handler("customError");
	  
	  
	  ini_set('user_agent','PHP');
	  
 
	  
	  //echo get_page_as_browser("http://www.ya.ru/");
	 
	
	  echo "start";	
  
	$fp = fopen("http://www.byaki.net/uploads/posts/2011-12/1323626094_1.jpg", "rb");
	if($fp)
	{
		echo "ok";		
	}
	else
	{
		echo "no:";
	}
	
	echo "<br>";
	
	$fp = fopen("http://vag44.net/img/bluelogo.png?version=winter", "r");
	if($fp)
	{
		echo "ok";		
	}
	else
	{
		echo "no:";
	}
	
	
?>