<?php

    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    error_reporting(E_ERROR);
    //error_reporting(0);
	
	session_start();
	
	
	header('Content-type: text/html; charset=utf-8');	

	if(!$_REQUEST["action"])
	{
		return;
	}
	
	chdir('..');
		

	require_once "constants.php";    
	require_once "db/ChatDB.php";
	require_once "userlogic.php";
    require_once "carlogic.php";
    
    require_once "miscfunctions.php";
    
    global $currentUserID;
    
    $currentUser = User::CurrentUser();
    
    //static $lastChatUpdate = '1';
    
    switch($_REQUEST["action"])
    {
        case "writemessage":            
            ChatDB::AddMessage(-1, User::CurrentUserID(), $_REQUEST['message'], DateFunctions::getCurrentDateText());
            return;
            
        case "getmessages":
        	
        	$result = '';
        	
        	//$lastChatUpdate++;
        	
        	$res['lastupdate'] = $lastChatUpdate;
        	
        	$messages = ChatDB::GetMessages(-1, $_REQUEST['fromid'], $_REQUEST['top']);
            if(count($messages) > 0)
            {
                foreach($messages as $message){
                    $result .= RenderFunctions::RenderChatMessage($message, $currentUser->CompactChat);
                }
                $res['html'] = $result;
                $res['lastid'] = $messages[count($messages) -1]['ID'];
                $res['firstid'] = $messages[0]['ID'];                
            }
            else {
                $res['lastid'] = 'nomessages';
                $res['html'] = '';
            }
            
            echo json_encode($res);
            
            return;
            
        case "setvisible":        	
        	ChatDB::SetVisibility(User::CurrentUserID(), $_REQUEST['newvalue']);
        	return;
        	
       	case "setsound":
        	ChatDB::SetSound(User::CurrentUserID(), $_REQUEST['newvalue']);
        	return;
        	
        case "setcompact":
        	ChatDB::SetCompact(User::CurrentUserID(), $_REQUEST['newvalue']);
        	return;
        	
      	case "setenter":
        	ChatDB::SetEnter(User::CurrentUserID(), $_REQUEST['newvalue']);
        	return;
    }
?>