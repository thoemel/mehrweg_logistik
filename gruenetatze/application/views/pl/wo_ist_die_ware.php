<?php 
include APPPATH . 'views/header.php';

echo '
<div class="page-header">
	<h1>Wo ist die Ware?</h1>
</div>';


echo '
	<table class="table table-striped table-bordered table-condensed">
	<thead>
		<tr>
			<th scope="col"></th>
			<th scope="col">Takeaway</th>
			<th scope="col">MW-Logistik</th>
			<th scope="col">Zwischenlager</th>
			<th scope="col">Rikscha (Fahrz.)</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th scope="row">Transportkisten</th>
			<td>' . $ware['TK']['Takeaway'] . '</td>
			<td>' . $ware['TK']['MW-Logistik'] . '</td>
			<td>' . $ware['TK']['Lager'] . '</td>
			<td>' . $ware['TK']['Rikscha'] . '</td>
		</tr>
		<tr>
			<th scope="row">Bring Back Boxen</th>
			<td>' . $ware['BBB']['Takeaway'] . '</td>
			<td>' . $ware['BBB']['MW-Logistik'] . '</td>
			<td>' . $ware['BBB']['Lager'] . '</td>
			<td>' . $ware['BBB']['Rikscha'] . '</td>
		</tr>
			<th scope="row">Depotkarten</th>
			<td>' . $ware['Depotkarte']['Takeaway'] . '</td>
			<td>' . $ware['Depotkarte']['MW-Logistik'] . '</td>
			<td>' . $ware['Depotkarte']['Lager'] . '</td>
			<td>' . $ware['Depotkarte']['Rikscha'] . '</td>
		</tr>
	</tbody>
	</table>';

include APPPATH . 'views/footer.php';