<?php /* Smarty version 2.6.22, created on 2013-08-01 17:30:40
         compiled from controls/newsblock.html */ ?>
				<ul class="nav nav-list">
					<li class="nav-header">Новости</li>
					<?php $_from = $this->_tpl_vars['news']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['news'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['news']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['newsItem']):
        $this->_foreach['news']['iteration']++;
?>
						<?php if ($this->_foreach['news']['iteration'] <= 10): ?>
							<li ><a href="/post/<?php echo $this->_tpl_vars['newsItem']->ID; ?>
"><?php echo $this->_tpl_vars['newsItem']->Title; ?>
 <br /> 
							<small>(<?php echo $this->_tpl_vars['newsItem']->CreateShortDate(); ?>
)</small></a></li>
						<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>					
	            </ul>