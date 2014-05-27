<?php 
include APPPATH . 'views/header.php';

echo '
<div class="jumbotron">
	<h1>Cockpit</h1>
	<p>Hallo liebe Jeannette, was willst du jetzt tun?</p>
</div>
<div>
	<ul class="nav nav-pills">
		';
foreach ($arrNavi as $route => $display) {
	if ('login/logout' == $route) {
		continue;
	}
	echo '
		<li>' . anchor($route, $display) . '</li>';
}
echo '
	</ul>
</div>';
		

include APPPATH . 'views/footer.php';