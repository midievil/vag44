<?
	include("../db.php");	
	connectToDB();
	
	mysql_query("truncate table PrerenderVisits");
	mysql_query("
		INSERT INTO	PrerenderVisits (PostID, Visits, VisitsToday)
		SELECT	PostID, count(*), 0 
		FROM	Visits
		GROUP BY PostID
		");
		
	$visits = mysql_query("SELECT PostID, Visits FROM PrerenderVisits");
	while($visit = mysql_fetch_assoc($visits))
	{
		$cnt = $visit['Visits'];
		$postID = $visit['PostID'];
		mysql_query("UPDATE Posts SET VisitsCount=$cnt WHERE ID=$postID");		
	}
	
?>