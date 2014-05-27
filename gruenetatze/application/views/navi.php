<?php
echo	'
	<nav class="navbar navbar-inverse navbar-static-top" role="navigation">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#vb-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			' . anchor('', img('img/logo_gruenetatze_klein.png').'&nbsp;Grüne&nbsp;Tatze', array('class'=>'navbar-brand')) . '
		</div><!-- End of navbar-header -->

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="vb-navbar-collapse-1">
			<ul class="nav navbar-nav">';
foreach ($arrNavi as $route => $display) {
	$class = (uri_string() == $route) ? ' class="active"' : '';
	echo '
				<li' . $class . '>' . anchor($route, $display) . '</li>';
}
echo '
			</ul>
		</div><!-- /.navbar-collapse -->
	</nav>';
?>
