<?PHP
	
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