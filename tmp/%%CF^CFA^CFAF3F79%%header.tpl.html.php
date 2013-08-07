<?php /* Smarty version 2.6.22, created on 2013-08-01 17:30:39
         compiled from header.tpl.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>VAG Club Кострома</title>
		
		<meta http-equiv="keywords" content="Volkswagen Кострома, Клуб VAG в Костроме, VAG Кострома, Skoda кострома, Audi Кострома, Seat кострома">
		<meta name="keywords" content="Volkswagen Кострома, Клуб VAG в Костроме, VAG Кострома, Skoda кострома, Audi Кострома, Seat кострома">
		<meta http-equiv="description" content="Клуб владельцев и поклонников автомобилей Volkswagen, Skoda, Audi, Seat в Костроме и Костромской области. ">
	
				
		<link rel="stylesheet" type="text/css" href="/bootstrap/css/bootstrap.min.css" />		
		<link rel="stylesheet" type="text/css" href="/bootstrap/css/bootstrap-list.css" />
				<link rel="stylesheet" type="text/css" href="/bootstrap/css/bootstrap-image-gallery.min.css">
		<link rel="stylesheet" type="text/css" href="/css/popupbox.css?version=16.10.2012" />
		<link rel="stylesheet" type="text/css" href="/css/gallery.css?version=3" />	
		<link rel="shortcut icon" href="/img/favicon.ico">
		<link rel="stylesheet" type="text/css" href="/css/main.min.css?version=12" />
				

<!--[if lt IE 7]><link rel="stylesheet" href="css/bootstrap-ie6.css"><![endif]-->

		
			
		
		<script type="text/javascript" src="/js/jquery/js/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="/js/jquery/js/jquery-ui-1.10.3.custom.min.js"></script>
		
		<script type="text/javascript" src="/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/bootstrap/js/bootstrap-list.js"></script>
		<script type="text/javascript" src="/bootstrap/js/bootstrap-tooltip.js"></script>
		<script type="text/javascript" src="/bootstrap/js/bootstrap-popover.js"></script>
		<script type="text/javascript" src="/bootstrap/js/bootstrap-image-gallery.min.js"></script>
		
		

		<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
		<script type="text/javascript" src="/ckeditor/adapters/jquery.js"></script>
		<script type="text/javascript" src="/js/functions.js?version=3"></script>	
		<script type="text/javascript" src="/js/screen.js?version=19"></script>
		<script type="text/javascript" src="/js/user.js?version=14"></script>
		<script type="text/javascript" src="/js/blogging.js?version=33"></script>
		<script type="text/javascript" src="/js/gallery.js?version=9"></script>
		<script type="text/javascript" src="/js/ajaxfileupload.js"></script>
		<script>
		<?php if ($this->_tpl_vars['currentUser']->IsLogged()): ?>
			markOnline(<?php echo $this->_tpl_vars['currentUser']->ID; ?>
);
		<?php endif; ?>			
			
		var categoryImages = new Array();
		
		<?php echo '
		function highlightPrivateMessages()
		{
				$("#aPrivateMessages").animate( {color: \'#dd3333\' }, "slow", function() {
						$("#aPrivateMessages").animate( {color: \'#ffffff\' }, "slow");
						highlightPrivateMessages();
					}
				);
				
		}
		
		$(function() {
			$.ajax({	type: "POST",	
					url:"/response/userresponse.php",
					data:	"action=setresolution&width="+document.width,
					success: function(result){
						//alert(result);
						//var screen_width = '; ?>
<?php echo $this->_tpl_vars['screen_width']; ?>
<?php echo ';
						//if(result != screen_width)
						//{
							//alert(result + " " + screen_width);
						//}
					}
					});
		    
		});
		'; ?>

			
		</script>
	</head>
	
	<body>		
		<div id="divGallery" style="position:absolute; display:none; z-index:2;">
	 
		</div>
		<div id="divGalleryBack" style="position:absolute; display:none; z-index:1;">
	
		</div>		
						
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class='navbar-inner'>
				<div class="container-fluid">
					<img src='/img/logo_small.png' class='pull-left hand' style='margin-right: 10px' onclick='window.location="/";' /> 					<a href='/' class='brand' style='color:#ffffff'>Kostroma VAG Club</a>
					
					<?php if ($this->_tpl_vars['currentUser']->IsLogged()): ?>
						<div class="nav-collapse collapse">
							<ul class="nav pull-right">
																<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class='icon-user icon-white'></i> <?php echo $this->_tpl_vars['currentUser']->Name; ?>
 <b class="caret"></b></a>
									<ul class="dropdown-menu">
									  <li><a href="/profile/about">О себе</a></li>
									  <li><a href="/profile/cars">Мои авто</a></li>
									  <li><a href="/profile/settings">Настройки</a></li>
									  <li><a href="/profile/changepass">Сменить пароль</a></li>
									  <li class="divider"></li>
									  <li><a id='aLogout' href='/?action=logoff'>выход</a></li>
									</ul>
								</li>								
								
								<?php if ($this->_tpl_vars['screen_width'] < 1200): ?>
									<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown"> Ещё <b class="caret"></b></a>
										<ul class="dropdown-menu">
								<?php endif; ?>
								<li>
									<a href='/service' >сервисная книжка</a>
								</li>
				
								<li>
									<a href='/blog' ><b>блоги</b></a>
								</li>
								<li>
									<a href='/gallery' >галерея</a>
								</li>
								<?php if ($this->_tpl_vars['screen_width'] < 1200): ?>
										</ul>
									</li>
								<?php endif; ?>
								<li>
								<?php if ($this->_tpl_vars['privateMessagesCount'] > 0): ?>
									<a id='aPrivateMessages' href='/privatemessages' class='active'>личные сообщения (<?php echo $this->_tpl_vars['privateMessagesCount']; ?>
)</a>
									<script>
										highlightPrivateMessages();
									</script>
								<?php else: ?>
									<a id='aPrivateMessages' href='/privatemessages'>личные сообщения</a>
								<?php endif; ?>
								</li>
								<li>
									<form class="navbar-form pull-right">  
									  <input type="text" class="span2" id='tbSearch' onkeyup="searchKeyPressed(event);">  
									  <img src='/img/search_inv.jpg' class='hand search' onclick='redirectToSearch()' title='Искать' />
									</form>
								</li>
								<li>
									&nbsp;&nbsp;&nbsp;&nbsp;
								</li>
							</ul>
						</div>
					<?php else: ?>
						<div class="nav-collapse collapse">
						<nobr>
							<ul class="nav">								
								<li>
									<form class="navbar-form pull-left">	
										<input id='tbLoginName' name='tbLoginName' type='text' class='span2' placeholder='логин' />
										<input id='tbLoginPass' name='tbLoginPass' type='password' class='span2' placeholder='пароль' />
										<button onclick='checkLogin(); return false;' class="btn">Войти</button>								
									</form>
								</li>
								<li><a href='/restorepassword'>Забыли пароль?</a></li>								
								<li><a id='aRegister' href='/registration'><b>Регистрация</b></a></li>
							</ul>							
							
							<nobr>
						</div>
						
						
					<?php endif; ?>	
				
				</div>	
				
			</div>
		</div>
		
				
		<div class="container-fluid" id='wrap'>
	      <div class="row-fluid" id='main'>
	        <div class="span3">
	          <div class="well sidebar-nav sidebar-nav-fixed span3">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "controls/newsblock.html", 'smarty_include_vars' => array('news' => $this->_tpl_vars['news'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>	            
	          </div><!--/.well -->
	        </div><!--/span-->
	        <div class="span9">
	          
			
		
		<?php echo '
		<script>	
			$(".login").keypress(function(e) {
				if(e.keyCode == 13)
				{
					checkLogin();		
				}	
			});						
		</script>
		'; ?>

		
		