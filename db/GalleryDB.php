<?PHP		
	//error_reporting(0);	
	
	function userGalleryExists($userid)
	{
		return fDB::fquery("select COUNT(*) from Galleries where UserID = $userid") > 0;		
	}
	
	function createUserGallery($userid, $name)
	{
		return fDB::fexec("insert into Galleries (UserID, Name) values ($userid, '$name')") > 0;		
	}
	
	function getUserGalleries($userid)
	{
		return  fDB::fqueryAll("
			select	G.ID,
					G.Name,
					G.UserID,
					(select min(ID) from GalleryItems where GalleryID = G.ID) Thumbnail
			from	Galleries G
			where UserID = $userid");
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