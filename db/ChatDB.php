<?php

/**
 * ChatDB short summary.
 *
 * ChatDB description.
 *
 * @version 1.0
 * @author artem
 */

require_once 'inc/class.db.php';
require_once 'inc/class.fdb.php';

class ChatDB
{
    public static function AddMessage($chatID, $userID, $message, $date) 
    {
        $query = "INSERT INTO ChatMessages (ChatID, UserID, Message, Date) VALUES ($chatID, $userID, '$message', '$date')";
        return fDB::fexec($query);
    }
    
    public static function GetMessages($chatID, $fromMessageID, $top) 
    {
    	if($fromMessageID == -1)
    	{
    		$fromMessageID = fdb::fscalar("SELECT ID from ChatMessages ORDER BY ID DESC LIMIT 20, 1");
    		$toMessageID = $fromMessageID + 200; 
    	}
    	    	
    	if($top=="true")
    	{   	
    		$toMessageID = $fromMessageID;
    		$fromMessageID = $fromMessageID - 20;
    	}
    	
        $query = "SELECT * FROM ChatMessages WHERE ChatID=$chatID AND ID > $fromMessageID ". ($toMessageID ? " AND ID < $toMessageID" : '');
        //echo $query ;    	 
        return fDB::fqueryAll($query);
    }
    
    
    public static function SetVisibility($userID, $newValue)
    {
    	$query = "UPDATE Users SET ShowChat=".($newValue ? "1" : "0")." WHERE ID=$userID";
    	//echo $query ;
    	return fDB::fexec($query);
    }
    
    public static function SetSound($userID, $newValue)
    {
    	$query = "UPDATE Users SET SoundChat=".($newValue ? "1" : "0")." WHERE ID=$userID";
    	//echo $query ;
    	return fDB::fexec($query);
    }
    
    public static function SetCompact($userID, $newValue)
    {
    	$query = "UPDATE Users SET CompactChat=".($newValue ? "1" : "0")." WHERE ID=$userID";
    	//echo $query ;
    	return fDB::fexec($query);
    }
    
    public static function SetEnter($userID, $newValue)
    {
    	$query = "UPDATE Users SET EnterChat=".($newValue ? "1" : "0")." WHERE ID=$userID";
    	//echo $query ;
    	return fDB::fexec($query);
    }
}
