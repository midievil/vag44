<?php /* Smarty version 2.6.22, created on 2013-08-01 17:02:40
         compiled from notifications/index.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'notifications/index.tpl.html', 2, false),)), $this); ?>
	<?php if ($this->_tpl_vars['currentUser']->IsAdmin()): ?>
		<?php if (count($this->_tpl_vars['unauthorizedUsers']) > 0 || count($this->_tpl_vars['feedbacks']) > 0): ?>
	<div class="alert alert-block alert-error fade in">		
		<button class="close" data-dismiss="alert">×</button>
			<?php $_from = $this->_tpl_vars['feedbacks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['feedback']):
?>
				Новый фидбэк: <a class='button' href='/admin/feedback/<?php echo $this->_tpl_vars['feedback']['ID']; ?>
'><?php echo $this->_tpl_vars['feedback']['Header']; ?>
</a><?php if ($this->_tpl_vars['feedback']['UserName'] != ''): ?> от  <?php echo $this->_tpl_vars['feedback']['UserName']; ?>
<?php endif; ?><br />
			<?php endforeach; endif; unset($_from); ?>
			
			<?php $_from = $this->_tpl_vars['unauthorizedUsers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['user']):
?>
				Новый пользователь: <a class='button' href='/user/<?php echo $this->_tpl_vars['user']['ID']; ?>
'><?php echo $this->_tpl_vars['user']['Name']; ?>
</a><br />
			<?php endforeach; endif; unset($_from); ?>
			
			<?php if (count($this->_tpl_vars['unauthorizedUsers']) == 0 && count($this->_tpl_vars['feedbacks']) == 0): ?>
				Для админа новостей сегодня нет. Как-то даже странно.
			<?php endif; ?>
		
	</div>
		<?php endif; ?>
	<?php endif; ?>	
	
	<?php if ($this->_tpl_vars['currentUser']->IsLogged()): ?>
		<?php if (count($this->_tpl_vars['userMessages']) > 0): ?>
	<div class="alert fade in">
		<button class="close" data-dismiss="alert">×</button>
				<ul>	
				<?php $_from = $this->_tpl_vars['userMessages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['message']):
?>
					<li><?php echo $this->_tpl_vars['message']; ?>
</li>
				<?php endforeach; endif; unset($_from); ?>
				</ul>
	</div>
		<?php endif; ?>
	<?php endif; ?>