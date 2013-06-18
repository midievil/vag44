<?PHP

/**
 * @author Artem
 *
 */
class mLogic {

	//site actions
	public static $siteModules = array(
		'admin' => 'admin',
		'mainpage' => 'mainpage',
		'feed' => 'mainpage',
		'registration' => 'registration',			
		'restorepassword' => 'restorepassword',
		'rules' => 'rules',
		'feedback' => 'feedback',
		'knowlege' => 'knowlege',
		'users' => 'users',
		'user' => 'user',
		'userblogs' => 'userblogs',
		'category' => 'category',
		'post' => 'post',
		'profile' => 'profile',
		'createpost' => 'writepost',
		'writetoblog' => 'writepost',
		'writeforum' => 'writepost',
		'editpost' => 'writepost',
		'editcar' => 'writepost',
		'privatemessages' => 'privatemessages',
		'search' => 'search',
		'gallery' => 'gallery',
		'blog' => 'blog',
		'service' => 'service',
		'cars' => 'cars',
		'tips' => 'tips'
	);

	//sub actions (do, tab, etc.)
	public static $subActions = array(
		'do' => array(
			'edit' => 'edit', 
			'save' => 'save', 
			'add' => 'add', 
			'delete' => 'delete',
            'make_register' => 'make_register',
			'view' => 'view',
			'success'=> 'success',
			'fail'=> 'fail',
			'log'=> 'log',
			'status'=> 'status',
			'savelogo'=> 'savelogo',
			'droplogo'=> 'droplogo',			
		),
		'tab' => array (
			'change' => 'change', 
			'changeheader' => 'changeheader', 
		),
	);

	public static $notfoundAction = '404';
	//	ToDo: may be changed according to domain	
	public static $defaultAction = 'mainpage';
	
	public static $currentUserId = null;
	public static $currentUserLogin = null;

	public static $urlParams = null;
	public static $urlVariables = null;
	
	public static $currentAction = null;
	public static $currentSubAction = null;

	
	
	public static function getAction($url) {
		
		if (isset($_SESSION['uid']) && $_SESSION['uid'] > 0)
		{
			self::$defaultAction = 'enter';
		}

		if ( empty($url) ){
			return self::$defaultAction;
		}

		$parts = explode("/", $url);
		if ( empty($parts[0]) ){
			array_shift($parts);
		}
		self::$urlParams = $parts;
		
		if ( empty($parts) ) {
			return self::$defaultAction;
		}
				
		if ( !empty($parts[0]) ){
			$tst = explode("\?", $parts[0]);
			if ( isset($tst[1]) ){
				$parts[0] = $tst[0];
			}
		}
			
		if ( isset(self::$siteModules[$parts[0]]) ){
			if($parts[0] == 'cars')
			{
				self::$urlVariables['vendorid'] = 0;				
			}
			return self::$siteModules[$parts[0]];
		}
		
		if ( !empty($parts[0]) )
		{
			$vendors = CarDB::getVendors();
			foreach ($vendors as $vendor)
			{
				if(strtolower($vendor['Name']) == strtolower($parts[0]))
				{
					if(self::$urlVariables == null){
						self::$urlVariables = array();
					}
					self::$urlVariables['vendorid'] = $vendor['ID'];
					
					if ( !empty($parts[1]) )
					{
						$models = CarDB::getCarModelsForMenu($vendor['ID']);
						foreach ($models as $model)
						{
							if(strtolower($model['Name']) == strtolower($parts[1]))
							{
								self::$urlVariables['modelid'] = $model['ID'];
							}
						}
					}						
					
					return "cars";
				}
			}
				
			return self::$notfoundAction;
		}
		
		return self::$defaultAction;

	}


	
	
	public static function getOtherParams($ar = array()){
		if ( empty($ar) ){
			$ar = self::$urlParams;
		}

		$ret = array();
		
		if(self::$urlVariables != null)
		{ 
			$ret = self::$urlVariables; 
		}
		
		foreach ( $ar as $pos => $act ){
			foreach ( self::$subActions as $gAction => $variants ){
				if ( isset($variants[$act]) ){
					$ret[$gAction] = $variants[$act];
				}
			}

			if ( $act == 'category' && isset($ar[$pos+1]) ){ /* category id */
				$ret['category'] = intval($ar[$pos+1]);
			}
			elseif ( $act == 'post' && isset($ar[$pos+1]) ){ /* category id */
				$ret['post'] = intval($ar[$pos+1]);
			}
			elseif ( $act == 'profile' && isset($ar[$pos+1]) ){ /* category id */
				$ret['profile'] = $ar[$pos+1];
			}
			elseif ( ($act == 'createpost' || $act == 'writeforum') && isset($ar[$pos+1]) ){ /* category id */
				$ret['forumid'] = intval($ar[$pos+1]);
			}
			elseif ( $act == 'editpost' && isset($ar[$pos+1]) ){ /* category id */
				$ret['postid'] = intval($ar[$pos+1]);
			}
			elseif ( $act == 'writetoblog' && isset($ar[$pos+1]) ){ /* category id */
				$ret['blogid'] = intval($ar[$pos+1]);
			}
			elseif ( $act == 'editcar' && isset($ar[$pos+1]) ){ /* category id */
				$ret['carid'] = intval($ar[$pos+1]);				
			}
			elseif ( $act == 'user' && isset($ar[$pos+1]) ){ /* category id */
				$ret['userid'] = intval($ar[$pos+1]);
			}
			elseif ( $act == 'knowlege' && isset($ar[$pos+1]) ){ /* category id */
				$ret['knowlege'] = $ar[$pos+1];
			}
			elseif ( $act == 'privatemessages' && isset($ar[$pos+1]) ){ /* category id */
				$ret['privatemessages'] = $ar[$pos+1];
			}
			elseif ( $act == 'search' && isset($ar[$pos+1]) ){ /* category id */
				$ret['search'] = $ar[$pos+1];
			}
			
			elseif ( $act == 'registration' && isset($ar[$pos+1]) ){ /* category id */
				$ret['registration'] = $ar[$pos+1];
			}
			
			elseif ( $act == 'admin' && isset($ar[$pos+1]) ){ /* category id */
				$ret['admin'] = $ar[$pos+1];
			}
			
			elseif ( $act == 'id' && isset($ar[$pos+1]) ){ /* category id */
				$ret['id'] = intval($ar[$pos+1]);
			}
			
			elseif ( $act == 'cars' ){ /* category id */				
				$ret['vendorid'] = 0;
			}
			
			elseif ( $act == 'blog' ){ /* blog id */
				if(is_numeric($ar[$pos+1]))
				{
					$ret['blogid'] = $ar[$pos+1];
				}
			}
			
			elseif ( $act == 'tips' ){ /* category id */
				$ret['tip'] = $ar[$pos+1];
			}
			
			elseif ( $act == 'userblogs' ){ /* category id */
				$ret['userid'] = $ar[$pos+1];
			}
		}


		return $ret;
	}


	public static $debug = false;
	public static function start(){
		
		$_GET['URL_REQUESTED'] = trim($_GET['URL_REQUESTED'], '/');
		
		if($_REQUEST['debug'])
		{
			self::$debug = true;
		}
		
		$srv = strtolower($_SERVER['HTTP_HOST']);
		$subDomain = preg_replace('/\.?'. preg_quote(GENERAL_DOMAIN) . '/Umsi', "", $srv);

		if ( preg_match("/^www\./Umsi", $subDomain) ){
			$subDomain = preg_replace("/^www\./Umsi", '', $subDomain);
		}				
                
		if ( $subDomain != $srv && !empty($subDomain) && $subDomain != -1 && $subDomain != '.' && $subDomain != 'www' ){
			if ( $_GET['URL_REQUESTED'][0] != '/' ){
				$_GET['URL_REQUESTED'] = '/' . $_GET['URL_REQUESTED'];
			}

			$_GET['URL_REQUESTED'] = '/' . $subDomain .  $_GET['URL_REQUESTED'];
		}


		if ( empty($_GET['URL_REQUESTED']) ){
			$_GET['URL_REQUESTED'] = '/';
		}


		$action = self::getAction($_GET['URL_REQUESTED']);
		//$action = self::getAction($_SERVER['REQUEST_URI']);
		self::$currentAction = $_GET['action'] = $_REQUEST['action'] = $action;

		self::$urlVariables = self::getOtherParams(self::$urlParams);
		if ( count(self::$urlVariables) > 0 ){
			foreach ( self::$urlVariables as $k => $v ){
				$_GET[$k] = $_REQUEST[$k] = $v;

				if ( $k == 'tab' ){					
					self::$currentSubAction = array( $k, $v );
				}

			}
		}					
	}
	
	
	public static function localSite()
	{
		return stripos(GENERAL_DOMAIN, 'local') === false;
	}
	
	
	/**
	 * @param int $id
	 * @return int
	 */
	public static function modifyID($id)
	{
		if( self::$currentUserId >= 1100000 && stripos(GENERAL_DOMAIN, 'ortox.ru') !== false && $id < 1100000 )
		{	
			return $id + 1100000;
		}
		else
		{
			return $id;
		}
	}
	
	public static function debugMessage($msg, $die = false)
	{
		if(self::$debug)
		{
			echo $msg.'<br />';
			if($die)
			{
				die;
			}
		}
	}
}


?>