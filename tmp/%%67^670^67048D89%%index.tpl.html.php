<?php /* Smarty version 2.6.22, created on 2013-08-01 17:02:40
         compiled from mainpage/index.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'mainpage/index.tpl.html', 10, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mainpage/index.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "controls/mainbanner.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "notifications/index.tpl.html", 'smarty_include_vars' => array('jsfile' => 'notifications')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<?php $this->assign('gender', ((is_array($_tmp=@$this->_tpl_vars['currentUser']->Gender)) ? $this->_run_mod_handler('default', true, $_tmp, 'm') : smarty_modifier_default($_tmp, 'm'))); ?>

	          <div class="row-fluid">
	            
	          </div><!--/row-->
	          <div class="row-fluid">
	            
	          </div><!--/row-->				
				<?php if ($_SERVER['REQUEST_URI'] == '/feed'): ?><?php $this->assign('active', 'feed'); ?><?php endif; ?>
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'controls/maintabmenu.html', 'smarty_include_vars' => array('active' => $this->_tpl_vars['active'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				
				<div id="myTabContent" class="tab-content">
		            <div class="tab-pane fade <?php if ($this->_tpl_vars['active'] != 'feed'): ?>active in<?php endif; ?>" id="home">
						<div class="accordion" id="accordionMain">
							<?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['category']):
?>
								<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "controls/tagcategory.html", 'smarty_include_vars' => array('category' => $this->_tpl_vars['category'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
							<?php endforeach; endif; unset($_from); ?>					
				        </div>
		            </div>
		            <div class="tab-pane fade <?php if ($this->_tpl_vars['active'] == 'feed'): ?>active in<?php endif; ?>" id="feed">
						<div class='span8' id='divNotifications'>
						<?php $_from = $this->_tpl_vars['user_notifications']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['notification']):
?>
							<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "controls/notification.html", 'smarty_include_vars' => array('notification' => $this->_tpl_vars['notification'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
						<?php endforeach; else: ?>
							<p>Пока здесь ничего нет, но скоро обязательно что-то появится. Обещаем.</p>
						<?php endif; unset($_from); ?>
						</div>
		            </div>		            
				</div>  
	
<br />


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>