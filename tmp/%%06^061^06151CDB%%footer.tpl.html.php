<?php /* Smarty version 2.6.22, created on 2013-08-01 17:02:41
         compiled from footer.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'footer.tpl.html', 16, false),)), $this); ?>
					
				</div> 
		
		<hr>
		
		</div>
	</div>
	
	<div class='row-fluid'>
		<div class='span3 pull-left'></div>
		<div class='span9' style="padding-left: 30px; padding-right: 30px;">
      <footer>
			<?php if ($this->_tpl_vars['currentUser']->IsLogged()): ?>
					<div style='padding-top:42px;' align='left'>
							<b>Сейчас на сайте:</b>
							<?php if (count($this->_tpl_vars['onlineUsers']) > 0): ?>
								<?php $_from = $this->_tpl_vars['onlineUsers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['userscycle'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['userscycle']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['user']):
        $this->_foreach['userscycle']['iteration']++;
?>
									<a href="/user/<?php echo $this->_tpl_vars['user']->ID; ?>
" rel="popover" data-content="<?php echo $this->_tpl_vars['user']->GetDescriptionForPopup(); ?>
" data-original-title="Пользователь <strong><?php echo $this->_tpl_vars['user']->Name; ?>
</strong>"><?php echo $this->_tpl_vars['user']->Name; ?>
</a>
									<?php if ($this->_foreach['userscycle']['iteration'] < count($this->_tpl_vars['onlineUsers'])): ?>,<?php endif; ?> 									
								<?php endforeach; endif; unset($_from); ?>
								<?php echo '
									<script>
										$("a[rel=\'tooltip\']").tooltip();
									</script>
									'; ?>
									
							<?php else: ?>
							никого
							<?php endif; ?> 
							<br /><br />					
	 				</div>
	 			<?php endif; ?> 
      </footer>
      </div>
      </div>
	  
	  <div class="container">
	  <div class="navbar navbar-inverse">
          <div class="navbar-inner">            
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>            
            <!-- Responsive Navbar Part 2: Place all navbar contents you want collapsed withing .navbar-collapse.collapse. -->
            <div class="nav-collapse collapse">
              <ul class="nav">
				<li><a href='/user/1'>© midievil 2013</a></li>
                <li class="active"><a href="/"><i class="icon-home icon-white"></i> Домой</a></li>
			  </ul>
			  <ul class="nav pull-right">
                <li><a href='/feedback/'><i class="icon-bullhorn icon-white"></i> Обратная связь</a></li>
                <li><a href='/post/55' ><i class="icon-book icon-white"></i> Книга жалоб</a></li>
                <li><a href='/post/11' ><i class="icon-ok icon-white"></i> Улучшение сайта</a></li>
				<li><a href='/post/143' ><i class="icon-heart icon-white"></i> Помощь сайту</a></li>                
              </ul>
            </div><!--/.nav-collapse -->
          </div><!-- /.navbar-inner -->
        </div>
		</div>
		
		</div><!--/span-->
	</body>
</html>	

<?php echo '
<script>
$(\'.list\').list();
$(".hidden").hide();

$(\'.popover-bottom\').popover({\'placement\':\'bottom\'});
$(\'[rel="popover"]\').filter(\'[class!="popover-bottom"]\').popover({\'placement\':\'top\'});


</script>
'; ?>