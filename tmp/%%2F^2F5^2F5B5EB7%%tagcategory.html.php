<?php /* Smarty version 2.6.22, created on 2013-08-01 17:02:40
         compiled from controls/tagcategory.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'controls/tagcategory.html', 20, false),)), $this); ?>
				<?php $this->assign('image_file', $this->_tpl_vars['category']->getImageForMainPage()); ?>
				<?php if ($this->_tpl_vars['image_file'] != ''): ?>
					<script>categoryImages['trTagCategory<?php echo $this->_tpl_vars['category']->ID; ?>
']='<?php echo $this->_tpl_vars['image_file']; ?>
';</script>
				<?php endif; ?>
				
				<?php $this->assign('childObjects', $this->_tpl_vars['category']->getChildObjects()); ?>			
				<div class="accordion-group">
		              <div class="accordion-heading" style='height:90px;'>
						<div class="span8">
		                <a class="accordion-toggle pull-left" data-toggle="collapse" href="#collapseTC<?php echo $this->_tpl_vars['category']->ID; ?>
" data-parent="#accordionMain">
							<h4><?php echo $this->_tpl_vars['category']->Name; ?>
</h4>
		                </a>
						<a class="btn" style='margin-top:10px;' href="/category/<?php echo $this->_tpl_vars['category']->ID; ?>
">»</a>
						<a style='margin-top:10px;' class="accordion-toggle nowrap" data-toggle="collapse" data-parent="#accordionMain" href="#collapseTC<?php echo $this->_tpl_vars['category']->ID; ?>
"><small><?php echo $this->_tpl_vars['category']->Comment; ?>
</small></a>
						</div>
						<div class='pull-right span3' style='padding-right:20px; padding-top:5px;  text-align:right;'>						
							<?php echo $this->_tpl_vars['category']->LastCommentHint; ?>

						</div>
		              </div>	
					<?php if (count($this->_tpl_vars['childObjects']) > 0): ?>
		              <div id="collapseTC<?php echo $this->_tpl_vars['category']->ID; ?>
" class="accordion-body collapse" style="height: 0px;">
		                <div class="accordion-inner">
							<div class="list" style='height:250px'>
								<ul>
							<?php $_from = $this->_tpl_vars['category']->ChildCategories; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['child']):
?>
								<?php if ($this->_tpl_vars['child']->Name != null): ?>
									<li class='hand listSelected' onclick='redirectToCategory(<?php echo $this->_tpl_vars['child']->ID; ?>
, event);'>[Категория] <?php echo $this->_tpl_vars['child']->Name; ?>
 </li>
								<?php endif; ?>
							<?php endforeach; endif; unset($_from); ?>
							
							<?php $_from = $this->_tpl_vars['category']->ChildPosts; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['child']):
?>
								<?php if ($this->_tpl_vars['child']->Title != null): ?>
									<li class='hand' onclick="Redirect('post/<?php echo $this->_tpl_vars['child']->ID; ?>
', event);"><?php echo $this->_tpl_vars['child']->Title; ?>
</li>
								<?php endif; ?>
							<?php endforeach; endif; unset($_from); ?>
								</ul>
							</div>
		                </div>
		              </div>
					<?php endif; ?>
		            </div>    