<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Bestätigung Checkin</h1>
	<div class="alert alert-success">
		Die Ware wurde gespeichert.
	</div>
	<h2>Und jetzt?</h2>
	<div class="list-group col-sm-4">
		' . anchor('rikscha/lager_checkout', 'Neuer Checkout aus dem Lager', array('class'=>'list-group-item')) . '
				' . anchor('login/logout', 'Logout', array('class'=>'list-group-item')) . '
	</div>
</div>';

include APPPATH . 'views/footer.php';