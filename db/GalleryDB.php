<?PHP		
	//error_reporting(0);	
	
	
	class GalleryDB
	{
		
		public static function getUserGalleries($userid)
		{
			return  fDB::fqueryAll("
				select	G.ID,
						G.Name,
						G.UserID,
						G.Public,
						(select min(ID) from GalleryItems where GalleryID = G.ID) Thumbnail						
				from	Galleries G
				where UserID = $userid");			
		}
		
		public static function saveGallery($id, $name, $pub)
		{
			if(empty($value))
			{
				$value='0';
			}
			fDB::fexec("UPDATE Galleries SET Public = $pub, Name='$name' WHERE ID=$id");
			return true;
		}
	}
	
	function userGalleryExists($userid)
	{
		return fDB::fquery("select COUNT(*) from Galleries where UserID = $userid") > 0;		
	}
	
	function createUserGallery($userid, $name)
	{
		return fDB::fexec("insert into Galleries (UserID, Name) values ($userid, '$name')") > 0;		
	}
	
	function getGalleryByID($galleryID)
	{
		return fDB::fquery("
			select	*
			from	Galleries
			where	ID = $galleryID");		
	}
	
	function getGalleryItems($galleryID)
	{
		return fDB::fqueryAll("
			select	*
			from	GalleryItems
			where	GalleryID = $galleryID");		
	}	
	
	function insertGalleryItem($galleryID)
	{
		fDB::fexec("insert into GalleryItems (GalleryID, Comment) values($galleryID, '')");
		return fDB::lastID();
	}
?>