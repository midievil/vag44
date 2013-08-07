<?php /* Smarty version 2.6.22, created on 2013-08-01 17:06:58
         compiled from controls/breadcrumbs.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'controls/breadcrumbs.html', 5, false),)), $this); ?>
<ul class="breadcrumb">
	<?php $_from = $this->_tpl_vars['breadCrumbs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['cycle'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cycle']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['breadCrumb']):
        $this->_foreach['cycle']['iteration']++;
?>		
		<li <?php if ($this->_tpl_vars['breadCrumb']->Link == ''): ?>class="active"<?php endif; ?>>
			<?php if ($this->_tpl_vars['breadCrumb']->Link != ''): ?><a href="<?php echo $this->_tpl_vars['breadCrumb']->Link; ?>
"><?php echo $this->_tpl_vars['breadCrumb']->Text; ?>
</a><?php else: ?><?php echo $this->_tpl_vars['breadCrumb']->Text; ?>
<?php endif; ?>
			<?php if ($this->_foreach['cycle']['iteration'] < count($this->_tpl_vars['breadCrumbs'])): ?><span class="divider">/</span><?php endif; ?> 
		</li>
	<?php endforeach; endif; unset($_from); ?>
  
</ul>