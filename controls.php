<?PHP
	class BaseMenu
	{
		public $tabs = array();
		public $ID;
		
		public function __construct($id)
		{
			$this->ID = $id;			
		}
		
		public function AddTab($tab)
		{
			$this->tabs[count($this->tabs)] = $tab;		
			$tab->ParentMenu = $this;			
			$tab->IndexOf = count($this->tabs);
		}
	}
	
	class TabMenu extends BaseMenu
	{
		public function Render()
		{
			$result = "<div class='TabMenuContainer' valign='bottom'>";
			foreach($this->tabs as $tab)
			{
				$result .= $tab->Render();
			}
			$result .= "</div>";
			return $result;
		}
	}
	
	class MenuTab
	{
		public $text = "";
		public $popup = "";		
		public $url = "";
		public $Selected = false;
		public $ParentMenu;
		public $IndexOf;
	}
	
	class RedirectMenuTab extends MenuTab
	{		
		public function __construct($text, $popup, $url, $selected)
		{
			$this->text = $text;
			$this->popup = $popup;
			$this->url = $url;	
			$this->Selected = $selected;
		}
		
		public function Render()
		{			
			$id = $this->ParentMenu->ID."Item".$this->IndexOf;
			return "
				<span class='tab " . ( $this->Selected ? " selected" : "" ). "'> 
					<a id='$id'" . renderPopup($this->popup) . " href='$this->url'>$this->text</a>
				</span>";
		}		
	}
	
	
	
	
	class CarsMenu extends TabMenu
	{
		public function __construct($id, $selectedItem)
		{
			parent::__construct($id);
			
			if(mLogic::$currentAction == 'users')
			{
				$usersSelected = 1;
			}
			else if (!isset($selectedItem) && $_GET["showresource"] == "")
			{
				$forumSelected = 1;
			}
			
			$this->AddTab(new RedirectMenuTab("Форум", '', '/', $forumSelected));
			$this->AddTab(new RedirectMenuTab("Люди", '', '/users', $usersSelected));
			$this->AddTab(new RedirectMenuTab('Автомобили', $popup, '/cars', ($selectedItem == "0")));
			
			$vendors = CarDB::getCarVendorsForMenu();
			foreach ($vendors as $vendor)
			{
				$cars = CarDB::getCarsByVendor($vendor["ID"]);
				$count = 0;
				foreach ($cars as $car)
				{
					if(file_exists("img/postpics/" . $car["PostID"] . "_1.jpg" ))
					{
						$count++;
					}
				}
				
				$popup = "На сайте: " . $vendor["CountOnSite"] . "<br />С описанием: " . $count;								
				$selected = ($vendor["ID"] == $selectedItem);
				
				$this->AddTab(new RedirectMenuTab($vendor["Name"], $popup, '/'.$vendor["Name"], $selected));
			}
		}
	}
		
	class PagingPage
	{
		public $PageNumber;
		public $Selected = false;
		public $Function = '';
	}
	
	class Paging
	{
		public $ID;
		public $PageSize;
		public $ItemsCount;
		public $CurrentPage;
		public $ControlID;
		public $Pages;
		public $ItemTemplate = "";
		
		public function __construct($pageSize, $itemsCount, $currentPage, $itemTemplate)
		{
			$this->PageSize = $pageSize;
			$this->ItemsCount = $itemsCount;
			$this->CurrentPage = $currentPage;
			$this->ItemTemplate = $itemTemplate;
			
			if($this->ID)
			{
				$this->ControlID = "paging".$this->ID;
			}
			else
			{
				$this->ControlID = "paging".rand(1, 1000);
			}
			
			$this->Pages = Array();
			for($page = 1; $page <= $this->PagesCount(); $page++)
			{
				$pagingPage = new PagingPage();
				$pagingPage->PageNumber = $page;
				$pagingPage->Selected = $page == $this->CurrentPage ? 1:0;
				$pagingPage->Function = str_replace('%pagenumber%', $page, $this->ItemTemplate);				
				
				$this->Pages[] = $pagingPage;
			}
		}
		
		public function PagesCount()
		{	
			if($this->ItemsCount > $this->PageSize && $this->PageSize > 0)
			{
				return ceil($this->ItemsCount/$this->PageSize);
			}
			return 0;
		}
		
		public function Render()
		{
			if($this->ID)
			{
				$controlID="paging".$this->ID;
			}
			else
			{
				$controlID="paging".rand(1, 1000);
			}
				
			$result = '';
			$postID = $row["ID"];
			$pageSize = $row["PageSize"];
		
			for($page = 1; $page <= $this->PagesCount(); $page++)
			{
			$result .= "
			<span class='paging $controlID " . ($page == $this->CurrentPage ? "selected" : "") . " rounded' page='$page'>
					<a class='paging hand' onclick='" . str_replace('%pagenumber%', $page, $this->ItemTemplate) . "; switchPage(\"$controlID\", $page)'>$page</a>
					</span>";
			}
		
				
			if($result)
			{
			$result = "<span class='paging nowrap'><a class='comment'>РЎС‚СЂР°РЅРёС†С‹:</a>$result</span>";
			}
		
			return $result;
		}		
	}
	
	class BreadCrumb
	{
		public $Text = '';
		public $Link = '';
		public function __construct($text, $link)
		{
			$this->Text = $text;
			$this->Link = $link;
		}
		
	}
?>