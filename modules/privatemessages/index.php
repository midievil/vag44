<?PHP
	
	templater::assign('includeMainMenu', true);
	
	$breadCrumbs[] = new BreadCrumb('Личные сообщения', '');
	templater::assign('breadCrumbs', $breadCrumbs);	

	$messagesIn = new PrivateMessagesCollection('in');
	
	$messagesOut = new PrivateMessagesCollection('out');
	
	$i=0;
	
	templater::assign('messagesIn', $messagesIn->Items);	
	templater::assign('messagesOut', $messagesOut->Items);	
		
	templater::display();
?>
