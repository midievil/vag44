<?php /* Smarty version 2.6.22, created on 2013-08-01 17:06:58
         compiled from controls/paging.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'controls/paging.html', 1, false),)), $this); ?>
<?php if (count($this->_tpl_vars['paging']->Pages) > 1): ?>
<div class="pagination row">	
  <ul>    
  	<?php $_from = $this->_tpl_vars['paging']->Pages; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['page']):
?>
    <li page='<?php echo $this->_tpl_vars['page']->PageNumber; ?>
' <?php if ($this->_tpl_vars['page']->Selected == 1): ?> class="active"<?php endif; ?>><a class='hand' <?php if ($this->_tpl_vars['onclick']): ?>onclick='<?php echo $this->_tpl_vars['onclick']; ?>
(<?php echo $this->_tpl_vars['page']->PageNumber; ?>
)'<?php endif; ?>><?php echo $this->_tpl_vars['page']->PageNumber; ?>
</a></li>    
    <?php endforeach; endif; unset($_from); ?>
  </ul>
</div>
<?php endif; ?>