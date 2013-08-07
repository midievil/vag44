<?php /* Smarty version 2.6.22, created on 2013-08-01 17:02:41
         compiled from controls/notification.html */ ?>
<div class="alert alert-<?php echo $this->_tpl_vars['notification']->Type(); ?>
" id='divNotification<?php echo $this->_tpl_vars['notification']->ID; ?>
'>
	<a class="close hand" data-dismiss="alert" onclick='DeleteNotification(<?php echo $this->_tpl_vars['notification']->ID; ?>
, <?php echo $this->_tpl_vars['currentUser']->ID; ?>
, <?php echo $this->_tpl_vars['notification']->Read; ?>
)'>&times;</a>
	<?php echo $this->_tpl_vars['notification']->DateText(); ?>
<br /><br />
	<?php echo $this->_tpl_vars['notification']->Text; ?>

	<?php if ($this->_tpl_vars['notification']->Read == false): ?>	
		<a class='btn btn-small read' onclick='MarkReadNotification(<?php echo $this->_tpl_vars['notification']->ID; ?>
, <?php echo $this->_tpl_vars['currentUser']->ID; ?>
)'><?php echo $this->_tpl_vars['i18n']['read'][$this->_tpl_vars['gender']]; ?>
</a>
	<?php endif; ?>
</div>