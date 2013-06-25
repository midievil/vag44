<?PHP
function dbgTime($point)
{
	return;
 	if(stripos($_SERVER['SERVER_NAME'], 'local') !== false)
 	{	
 		$date = getdate();
 		echo "POINT $point: " . $date["seconds"] . "." . $date[0] . "<br />";
 	}
}
try
{
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	error_reporting(0);
		
	session_start();	
	header('Content-type: text/html; charset=utf-8');
	
	dbgTime(1);
	
	if($_GET["action"] == "logoff")
	{		
		$_SESSION["vag44login"] = "";
		setcookie("vag44login", "", time()-1000);
		$_SESSION["vag44pass"] = "";
		setcookie("vag44pass", "", time()-1000);
		echo "Как, уже уходите?";
		echo "<script>window.setTimeout(function() {	window.location = '/'; }, 500);</script>";
		die;
	}	
		
	require_once "constants.php";
	
	require_once "db.php";	
	require_once 'inc/class.db.php';
	require_once 'inc/class.fdb.php';
	
	connectToDB();
	
	require_once "bloglogic.php";
	require_once "messaginglogic.php";
	require_once "userlogic.php";
	require_once "carlogic.php";
	require_once "db/GalleryDB.php";
	require_once "db/AdminDB.php";
	require_once "miscfunctions.php";	
	require_once "tools/simpleimage.php";
	require_once "controls.php";
	
	require_once 'inc/class.mLogic.php';
	require_once 'inc/class.templater.php';
	require_once 'inc/class.mNotification.php';
	require_once "ckeditor/ckeditor.php";	
	require_once 'i18n/ru.php';	
	
	dbgTime(2);
	
	if(checkIfUserIsLogged()) {}
	
	mLogic::start();
	
	dbgTime(3);
	
	templater::assign('i18n', $i18n);
	templater::assign('ourCarsCategoryID', $ourCarsCategoryID);
	templater::assign('currentAction', mLogic::$currentAction);
	
	templater::assign('screen_width', $_SESSION['screen_width'] ? $_SESSION['screen_width'] : '0');
	
	
	$currentUser = User::CurrentUser();
	templater::assign('currentUser', $currentUser);
	
	templater::assign('currentDate', getCurrentShortDateText());
	
	
	$carsMenu = new CarsMenu("mnuMain", mLogic::$urlVariables["vendorid"]);
	templater::assign('carsMenu', $carsMenu);
	
	
	$catchPhrase = getRandomCatchPhrase();
	templater::assign('catchPhrase', $catchPhrase);
		
	$birthdayUsers = getUsersWithBirthday();
	templater::assign('birthdayUsers', $birthdayUsers);
	
	dbgTime(4);
	
	if($currentUser->IsAdmin())
	{
		$feedbacks = AdminDB::getFeedBacks();
		templater::assign('feedbacks', $feedbacks);
		
		$unauthorizedUsers = fDB::fqueryAll("select * from Users where GroupID = 3 AND Visible = 1");
		templater::assign('unauthorizedUsers', $unauthorizedUsers);
	}
	
	dbgTime(5);
	
	if($currentUser->IsLogged())
	{
		templater::assign('onlineUsers', getOnlineUsers());
	}
		
	dbgTime(6);
		
	include 'modules/personalscreen.php';
	
	dbgTime(7);
	
	$newsRows = BlogDB::getPostsByBlogID(1, 'P.Date', 'desc');
	$news = array();
	foreach($newsRows as $newsRow)
	{
		$newNews = new Post();
		$newNews->MakeFromRow($newsRow);
		$news[] = $newNews;
	}
	templater::assign('news', $news);
	
	$breadCrumbs = Array();
	$breadCrumbs[] = new BreadCrumb('Главная страница', '/');
	
	if(isset(mLogic::$currentAction) && is_file('modules/' . mLogic::$currentAction . '/index.php'))
	{	
		require_once SITE_DIR .  'modules/' . mLogic::$currentAction . '/index.php';
	}
	
	dbgTime(8);
	
	saveVisit();
	
	dbgTime(9);
	
	die;
	
}
catch(Exception $e)
{
//	var_dump($e);
}
?>					