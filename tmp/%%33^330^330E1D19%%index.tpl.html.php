<?php /* Smarty version 2.6.22, created on 2013-08-01 17:16:05
         compiled from user/index.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'user/index.tpl.html', 57, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "controls/breadcrumbs.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user/index.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['user']->Name != ''): ?>

	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "controls/pagetitle.html", 'smarty_include_vars' => array('pageTitle' => $this->_tpl_vars['user']->Name,'pageComment' => $this->_tpl_vars['comment'],'sideContent' => $this->_tpl_vars['rating'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	
	
	<div class='span2'>
		<?php echo $this->_tpl_vars['user']->RenderUserPic('EditProfile','',100); ?>

		<br />		
	</div>

	<div class='span8'>
	
		<?php if ($this->_tpl_vars['user']->GetFullName() != ""): ?>
			<div class='row'>
				<div class='span2 text-right'>Имя</div>
				<div class='span3'><a class='btn disabled'><?php echo $this->_tpl_vars['user']->GetFullName(); ?>
</a></div>
			</div><br />
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['user']->From): ?>
			<div class='row'>
				<div class='span2 text-right'>Город</div>
				<div class='span3'><a class='btn disabled'><?php echo $this->_tpl_vars['user']->From; ?>
</a></div>
			</div><br />
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['currentUser']->IsLogged()): ?>
		
				
			<?php if ($this->_tpl_vars['user']->ShowEmail): ?>
				<div class='row'>
					<div class='span2 text-right'>e-mail</div>
					<div class='span3'><a class='btn disabled'><?php echo $this->_tpl_vars['user']->Email; ?>
</a></div>
				</div>	<br />			
			<?php endif; ?>
			
			<?php if ($this->_tpl_vars['icq']): ?>
				<div class='row'>
					<div class='span2 text-right'>ICQ</div>
					<div class='span3'><a class='btn disabled'><?php echo $this->_tpl_vars['user']->ICQ; ?>
</a></div>
				</div>			<br />	
			<?php endif; ?>
			
			<?php if ($this->_tpl_vars['user']->Phone): ?>
				<div class='row'>
					<div class='span2 text-right'>Телефон</div>
					<div class='span3'><a class='btn disabled'>+7 <?php echo $this->_tpl_vars['phone']; ?>
</a></div>
				</div>				<br />
			<?php endif; ?>
			
			<?php if (count($this->_tpl_vars['social']) > 0): ?>
				<div class='row'>
					<div class='span2 text-right'>Соцсети</div>
					<div class='span3'>
						<?php $_from = $this->_tpl_vars['social']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sn']):
?>
							<a href='<?php echo $this->_tpl_vars['sn']['url']; ?>
' target='blank'>
							<img style='width:15px; height:15px; margin:-2px;' src='/img/social/<?php echo $this->_tpl_vars['sn']['innername']; ?>
.jpg' />&nbsp;<?php echo $this->_tpl_vars['sn']['name']; ?>

							</a> 
						<?php endforeach; endif; unset($_from); ?>
					</div>
				</div>				<br />
			<?php endif; ?>
		<?php endif; ?>
		
	
	
	<?php if ($this->_tpl_vars['carslist']): ?>
		<div class='row'>
			<div class='span2 text-right'>Автомобиль</div>
			<div class='span7'><?php echo $this->_tpl_vars['carslist']; ?>
</div>
		</div>				<br />			
	<?php endif; ?>
	
	
	<?php if (count($this->_tpl_vars['blogs']) > 0): ?>
		<div class='row'>
			<div class='span2 text-right'>Блоги</div>
			<div class='span5'>
				<?php $_from = $this->_tpl_vars['blogs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['blog']):
?>
		 		<a href='/blog/<?php echo $this->_tpl_vars['blog']->ID; ?>
'><?php echo $this->_tpl_vars['blog']->Name; ?>
</a><br />
			 	<?php endforeach; endif; unset($_from); ?>
			</div>
		</div>				<br />
	
	 
	<?php endif; ?>
	
	
	<?php if (count($this->_tpl_vars['galleries']) > 0): ?>
		<div class='row'>
			<div class='span2 text-right'>Фотоальбомы</div>
			<div class='span5'>
				<?php $_from = $this->_tpl_vars['galleries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['gallery']):
?>
					<?php if ($this->_tpl_vars['gallery']['Public']): ?>
			 		<a href='/gallery/id/<?php echo $this->_tpl_vars['gallery']['ID']; ?>
'><?php echo $this->_tpl_vars['gallery']['Name']; ?>
</a><br />
					<?php endif; ?>
			 	<?php endforeach; endif; unset($_from); ?>
			</div>
		</div>				<br /> 
		
						
	<?php endif; ?>
		<div class='row'>
			<div class='span2 text-right'></div>
			<div class='span5'>
				<?php if ($this->_tpl_vars['currentUser']->IsLogged() && $this->_tpl_vars['currentUser']->ID != $this->_tpl_vars['user']->ID): ?>
					<button type="button" data-toggle="modal" data-target="#privateMessage" class='btn btn-primary btn-small'>Написать личное сообщение</button>
				<?php endif; ?>
				
				<?php if (! $this->_tpl_vars['user']->IsAuthorized() && $this->_tpl_vars['currentUser']->IsAdmin()): ?>
					<button type="button" data-toggle="modal" class='authorize btn btn-warning btn-small' onclick='authorizeUser(<?php echo $this->_tpl_vars['user']->ID; ?>
);'>Авторизовать</button>
				<?php endif; ?>
			</div>
		</div>
	
	</div>
	
	
	
	
	
		
		<?php if ($this->_tpl_vars['currentUser']->IsLogged() && $this->_tpl_vars['currentUser']->ID != $this->_tpl_vars['user']->ID): ?>
			<br/><br/>
			
			<div class="modal hide fade" id='privateMessage'>
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			    <h3>Личное сообщение для <?php echo $this->_tpl_vars['user']->Name; ?>
</h3>
			  </div>
			  <div class="modal-body">
			  
			  	<form class="form-horizontal">
				<div class="control-group">
				    <label class="control-label" for="tbPrivateMessageHeader">Заголовок</label>
				    <div class="controls">				      
				      <input type='text' id='tbPrivateMessageHeader' placeholder="заголовок" class='span10' />
				    </div>
				    <br />
				    <label class="control-label" for="tbPrivateMessageText">Текст</label>
				    <div class="controls">				      
				      <textarea id='tbPrivateMessageText' rows='5' class='span10' placeholder='текст'></textarea>
				    </div>
				</div>		  
			    
			</div>			  
			  <div class="modal-footer">			    
			    <a href="#" class="btn btn-primary" onclick='sendMessage(<?php echo $this->_tpl_vars['user']->ID; ?>
);'>Отправить</a>
			    <a href="#" class="btn" data-dismiss="modal">Отмена</a>
			  </div>
			</div>
			
			
		<?php endif; ?>


			<a id='aComment'></a>		
<?php else: ?>
Такого пользователя у нас нет. Похоже, вы что-то перепутали =(
<?php endif; ?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>