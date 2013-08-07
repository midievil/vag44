<?php /* Smarty version 2.6.22, created on 2013-08-01 17:31:19
         compiled from controls/mainbanner.html */ ?>
	<div id="myCarousel" class="carousel slide" style='margin-top: -30px;'>
	  <ol class="carousel-indicators">
	    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
	    <li data-target="#myCarousel" data-slide-to="1"></li>
	    <li data-target="#myCarousel" data-slide-to="2"></li>
	  </ol>
	  <!-- Carousel items -->
	  <div class="carousel-inner">
        <div class="active item" style='height:300px; background-image: url(/img/carousel/party.jpg)'>
		    	<div class="container">
		            <div class="carousel-caption">
						<h1 style='color:#FF4530; font-weight: bold; text-shadow: 3px 3px #000000'>VAG Summer Weekend #2</h1>
						<p class="lead" style="font-size: 18pt; font-weight: bold; text-shadow: 2px 2px #000000">
		              	Вы опять пропустили всё самое интересное?<br />
		              	Мы повторим всё для вас через две недели. Приезжайте! 
		              	</p>
						<p>&nbsp;</p>
		              <p>
		              <a href="/post/856?page=last" class="btn btn-primary btn-large">Я приеду »</a></p>
		            </div>
		          </div>
		    </div>
	    <div class="item" style='height:300px; background-image: url(/img/carousel/1.jpg)'>
	    	<div class="container">
	            <div class="carousel-caption">
	              <h1>VAG 44</h1>
	              <p class="lead">
	              	Мы &mdash; сообщество владельцев и энтузиастов автомобилей концерна Volkswagen AG.<br />
			Мы общаемся и помогаем друг другу.<br />
			<?php if ($this->_tpl_vars['currentUser']->IsLogged()): ?>
				Мы рады, что вы &mdash; один из нас.
			<?php else: ?>
				Присоединяйтесь.
			<?php endif; ?>
					</p>
					<p>&nbsp;</p>
	              <p>
	              	<a href="/post/184" class="btn btn-primary btn-large">Наш FAQ »</a></p>
	            </div>
	          </div>
	    	</div>
	    <div class="item" style='height:300px; background-image: url(/img/carousel/2.jpg)'>
	    	<div class="container">
	            <div class="carousel-caption">
					<h1>Наши авто</h1>
					<p class="lead">
	              	Мы делимся историей своих автомобилей.<br />
	              	Многим из нас есть что рассказать. 
	              	</p>
					<p>&nbsp;</p>
	              <p>
	              <a href="/cars" class="btn btn-primary btn-large">Посмотреть »</a></p>
	            </div>
	          </div>
	    </div>
	    <div class="item" style='height:300px; background-image: url(/img/carousel/3.jpg)'>
	    	<div class="container">
	            <div class="carousel-caption">
					<h1>Люди</h1>
					<p class="lead">
	              	Мы дружим и общаемся не только на сайте.<br />
	              	Мы собираемся в оффлайне и весело проводим время. 
	              	</p>
					<p>&nbsp;</p>
	              <p>
	              <a href="/category/6" class="btn btn-primary btn-large">Встречи »</a></p>
	            </div>
	          </div>
	    </div>
	  </div>
	  <!-- Carousel nav -->
	  <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
	  <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
	</div>