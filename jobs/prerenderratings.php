<?PHP
	
	include("../db.php");	
	connectToDB();
		
	$result = mysql_query("SELECT ToUserID, IFNULL(COUNT(*), 0) AS Rating FROM Rating GROUP BY ToUserID");
	while($row = mysql_fetch_assoc($result))
	{
		$userid = $row['ToUserID'];
		$rating = $row['Rating'];
		$query = "UPDATE Users SET Rating=$rating WHERE ID=$userid";
		echo $query."<br />";
		mysql_query($query);		
	}
	
	
	$comments = mysql_query("SELECT PostID, IFNULL(COUNT(*), 0) AS CommentsCount FROM Comments GROUP BY 1");	
	$posts = array();
	while($row = mysql_fetch_assoc($comments))
	{
		$postID = $row['PostID'];
		$posts["$postID"]['Count'] = $row['CommentsCount'];
	}
	
	$rating = mysql_query("SELECT PostID, IFNULL(COUNT(*), 0) AS Rating FROM Rating WHERE CommentID = -1 GROUP BY 1");
	while($row = mysql_fetch_assoc($rating))
	{
		$postID = $row['PostID'];
		$posts["$postID"]['Rating'] = $row['Rating'];
		
	}
	foreach($posts as $postID => $val)
	{
		$rating = $val['Rating'];
		$count = $val['Count'];
		
		if(empty($rating)) { $rating = '0';}
		if(empty($count)) { $count = '0';}
		$query = "UPDATE Posts SET Rating=$rating, CommentsCount=$count WHERE ID=$postID";
		//echo $query."<br />";
		mysql_query($query);		
	}
	
	

	$result = mysql_query("SELECT CommentID, IFNULL(COUNT(*), 0) AS Rating FROM Rating WHERE CommentID != -1  GROUP BY 1");
	
	$ratings = array();
	while($row = mysql_fetch_assoc($result))
	{
		$rating = $row['Rating'];
		$commID = $row['CommentID'];
		$ratings["$rating"][] = $commID;
		
		
		// $query = "UPDATE Comments SET Rating=$rating WHERE ID=$commID";
		// echo $query.'<br />';
		// mysql_query($query);
	}
	
	foreach($ratings as $rating => $comments)
	{
		$ids = "";
		foreach($comments as $comment)
		{
			$ids .= "$comment,";
		}
		$ids .= "-1";
		$query = "UPDATE Comments SET Rating = $rating WHERE ID IN ($ids)";
		//echo $query.'<br />';
		mysql_query($query);		
	}
	
	echo "Ok";
?>