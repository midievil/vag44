<?php /* Smarty version 2.6.22, created on 2013-08-01 17:02:40
         compiled from controls/maintabmenu.html */ ?>
			<ul id="mainTabMenu" class="nav nav-pills">
					<li <?php if ($this->_tpl_vars['active'] == 'home' || $this->_tpl_vars['active'] == null): ?>class="active"<?php endif; ?>><a href="#home" data-toggle="tab" <?php if ($this->_tpl_vars['active'] != 'home' && $this->_tpl_vars['active'] != null): ?>onclick="Redirect('');"<?php endif; ?>>Форум</a></li>
					<li <?php if ($this->_tpl_vars['active'] == 'feed'): ?>class="active"<?php endif; ?>><a href="#feed" data-toggle="tab" <?php if ($this->_tpl_vars['active'] != 'feed' && $this->_tpl_vars['active'] != null): ?>onclick="Redirect('feed');"<?php endif; ?>>Что нового?
					<?php if ($this->_tpl_vars['user_notifications_count'] > 0): ?>
						<span class="label label-important" id='aNotificationsCount'><?php echo $this->_tpl_vars['user_notifications_count']; ?>
</span>
					<?php endif; ?>
					</a></li>
					<li <?php if ($this->_tpl_vars['active'] == 'users'): ?>class="active"<?php endif; ?>><a href="#users" data-toggle="tab" onclick="Redirect('users');">Люди</a></li>
					<li class="dropdown <?php if ($this->_tpl_vars['active'] == 'cars'): ?> active<?php endif; ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Автомобили <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a data-toggle="tab" onclick='GoToCars("cars");'>Все</a></li>
							<li><a data-toggle="tab" onclick='GoToCars("VolksWagen");'>Volkswagen</a></li>
							<li><a data-toggle="tab" onclick='GoToCars("Audi");'>Audi</a></li>
							<li><a data-toggle="tab" onclick='GoToCars("Skoda");'>Skoda</a></li>
							<li><a data-toggle="tab" onclick='GoToCars("Seat");'>Seat</a></li>
						</ul>
					</li>					
				</ul>