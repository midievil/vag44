<?PHP

	require_once 'db/MessagingDB.php';
	require_once "inc/class.mLoadable.php";
	
	class PrivateMessage extends Loadable
	{
		public $FromUserID = -1;
		public $ToUserID = -1;
		public $Header = '';
		public $Text = '';
		public $Read = false;
		public $PostDate = null;
		public $DeletedByReceiver = false; 
		public $DeletedBySender = false;
		public $FromUserName = '';
		public $ToUserName = '';
		
		public $UserID = -1;
		public $UserName = '';
		
		public function Load()
		{	
			$row = MessagingDB::getPrivateMessageByID($this->ID);
			$this->MakeFromRow($row);
			$this->Init();
		}
		
		public function PostDateText()
		{			
			return getDateTimeAtText($this->PostDate);
		}
		
		public function Direction()
		{
			global $currentUser;
			return $this->ToUserID == $currentUser->ID ? 'In' : 'Out';
		}
		
		public function Init()
		{			
			if($this->Direction() == 'In')
			{
				$this->UserID = $this->FromUserID;
				$this->UserName = $this->FromUserName;				
			}
			else
			{
				$this->UserID = $this->ToUserID;
				$this->UserName = $this->ToUserName;
			}
		}
		
		private $User = null;		
	}
	
	class PrivateMessagesCollection extends LoadableCollection
	{
		private $mode = '';
		public function __construct($mode)
		{
			if($mode)
			{				
				$this->mode = $mode;
				$this->Load();
				$this->Fill();
			}
		}
		
		public function Load()
		{
			$this->rows = MessagingDB::getPrivateMessages($this->mode);
		}
		
		public function Fill()
		{
			foreach ($this->rows as $row)
			{
				$item = new PrivateMessage();
				$item->MakeFromRow($row);
				$item->Init();
				$this->Items[] = $item;
			}			
		}
	}

	function getFeedBacks()
	{
		$query = "
				select	F.*,
						U.Name UserName
				from	FeedBacks F
				left join
						Users U on U.ID = F.UserID
				where	`Read` = 0";
		return fDB::fqueryAll($query);			
	}
	
	function getFeedBackByID($id)
	{
		$query = "
				update	FeedBacks
				set		`Read` = 1
				where	ID = $id";
		fDB::fexec($query);	
		$query = "
				select	F.*,
						U.Name UserName
				from	FeedBacks F
				left join
						Users U on U.ID = F.UserID
				where	F.ID = $id";
		
		return fDB::fquery($query);
	}
	
	function getTips()
	{
		$query = "select * from Tips";
		return fDB::fqueryAll($query);	
	}
?>