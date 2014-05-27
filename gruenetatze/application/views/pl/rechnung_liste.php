<?php 
include APPPATH . 'views/header.php';

echo '
<div class="page-header">
	<h1>Rechnung stellen</h1>
</div>';


echo '
<div class="row">
	<div class="col-xs-6">
		<h2>Firmen</h2>
		<div class="list-group col-sm-4">';
foreach ($firmen as $firma) {
	echo '
			' . anchor('pl/rechnung_fuer/' . $firma->firma_id . '/html', 
					$firma->name, 
					array('class'=>'list-group-item')) . '';
}

echo '
		</div>
	</div>
</div>';


include APPPATH . 'views/footer.php';