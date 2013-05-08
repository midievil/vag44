<?PHP


class templater{
	
	static private $_instance = null;
	static public $engine = null;


	public function __construct(){
		require_once SITE_DIR . 'inc/smarty/Smarty.class.php';

		$smarty = new Smarty;
		$smarty->template_dir = SITE_DIR.'/themes/';
		$smarty->compile_dir  = SITE_DIR.'/tmp/';
		$smarty->config_dir   = SITE_DIR.'/themes/';
		$smarty->cache_dir    = SITE_DIR.'/tmp/';

		self::$engine = $smarty;
	}


	public static function init(){
		if ( self::$_instance == null ) {
			self::$_instance = new templater();
		}	
		
	}

	public static function assign($tpl_var, $value = null){
		self::init();
		self::$engine->assign($tpl_var, $value);
	}


	public static function display($page = ''){		
		self::init();
		
		if ( !empty($page) ){
			return self::$engine->display($page);
		}
		else {
			$action = mLogic::$currentAction;
			$subAction = mLogic::$currentSubAction;
			
			if ( $action != null && $subAction != null ){
				$subAction = $subAction[0] . '_' . $subAction[1];
				if ( file_exists(self::$engine->template_dir . $action . '/' . $subAction . '.tpl.html' ) ) {
					return self::$engine->display($action . '/' . $subAction . '.tpl.html');
				}
				else {
					return self::$engine->display($action . '/index.tpl.html');
				}	
			}
			else if ( $action != null ){
					return self::$engine->display($action . '/index.tpl.html');
			}
			
			//else if not defined action
			return self::$engine->display(mLogic::$defaultAction. '/index.tpl.html');
		}
	}

	public static function fetch($page){
		self::init();

		if ( !empty($page) ){
			return self::$engine->fetch($page);
		}
	}


	public static function setThemesDir($path){
		self::$engine->template_dir = $path;
		self::$engine->config_dir = $path;
		self::$engine->compile_dir = $path . 'tmp/';
		self::$engine->cache_dir = $path . 'tmp/';

	}

}



?>