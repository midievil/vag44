<?

	require_once "db/NotificationsDB.php";
	require_once "inc/class.mLoadable.php";

	class Notification extends Loadable
	{
		public $UserID;
		public $Text;
		public $TypeID;
		public $Comment;
		public $Date;
		public $Read;
				
		public function Load()
		{			
			$row = NotificationsDB::getNotificationByID($this->ID);			
			$this->MakeFromRow($row);			
		}
		
		private $user = null;
		public function User()
		{
			if($this->user == null)
			{
				$this->user = new User($this->UserID);
			}
			return $this->user;
		}
		
		public function Type()
		{
			if($this->Read)
			{
				return 'read';
			}
			
			switch($this->TypeID)
			{
				case 1:
					return 'info';
				case 2:
					return 'error';
				case 3:
					return 'success';
			}
		}
		
		public function DateText()
		{
			return DateFunctions::getDateTimeAtText($this->Date);
		}
	}
	
?>