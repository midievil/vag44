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
    
    public static function GetMessages($chatID, $fromMessageID) 
    {
        $query = "SELECT * FROM ChatMessages WHERE ChatID=$chatID AND ID>$fromMessageID ORDER BY ID";
        return fDB::fqueryAll($query);
    }
}
