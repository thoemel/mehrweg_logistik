<?php 
include APPPATH . 'views/header.php';

echo '
<div class="page-header">
	<h1>Neues Material ist registriert</h1>
</div>
		
<div class="row">
	<div class="col-xs-6">
		<p>Folgendes Material ist neu hinzugekommen:</p>
		<ul class="list-group">
			<li class="list-group-item">
				<span class="badge">' . $tk . '</span>
				Transportkisten
			</li>
			<li class="list-group-item">
				<span class="badge">' . $bbb . '</span>
				Bring Back Boxen
			</li>
			<li class="list-group-item">
				<span class="badge">' . $depotkarten . '</span>
				Depotkarten
			</li>
		</ul>
	</div>		
</div>';


include APPPATH . 'views/footer.php';