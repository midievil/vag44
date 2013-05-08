<?PHP

/**
 * @author Artem
 *
 */
	class Loadable
	{
		public $ID;
		
		public function __construct($id) 
		{
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
		
		public function Load()
		{		
			echo "Not Implemented";
		}		
	}

	class LoadableCollection
	{
		public function Load()
		{
			echo "Not Implemented";
		}
		
		public $rows = null;
		public $Items = array();
		public function Fill()
		{
			echo "Not Implemented";
		}
	}
?>