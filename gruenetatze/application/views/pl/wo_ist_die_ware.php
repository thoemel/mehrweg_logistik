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
			<th scope="col">Rikscha-Lager</th>
			<th scope="col">Rikscha-Fahrz.</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th scope="row">TK sauber</th>
			<td>' . $ware['TK sauber']['Takeaway'] . '</td>
			<td>' . $ware['TK sauber']['MW-Logistik'] . '</td>
			<td>' . $ware['TK sauber']['Lager'] . '</td>
			<td>' . $ware['TK sauber']['Rikscha'] . '</td>
		</tr>
		<tr>
			<th scope="row">BBB sauber</th>
			<td>' . $ware['BBB sauber']['Takeaway'] . '</td>
			<td>' . $ware['BBB sauber']['MW-Logistik'] . '</td>
			<td>' . $ware['BBB sauber']['Lager'] . '</td>
			<td>' . $ware['BBB sauber']['Rikscha'] . '</td>
		</tr>
		<tr>
			<th scope="row">TK gebraucht</th>
			<td>' . $ware['TK gebraucht']['Takeaway'] . '</td>
			<td>' . $ware['TK gebraucht']['MW-Logistik'] . '</td>
			<td>' . $ware['TK gebraucht']['Lager'] . '</td>
			<td>' . $ware['TK gebraucht']['Rikscha'] . '</td>
		</tr>
		<tr>
			<th scope="row">BBB gebraucht</th>
			<td>' . $ware['BBB gebraucht']['Takeaway'] . '</td>
			<td>' . $ware['BBB gebraucht']['MW-Logistik'] . '</td>
			<td>' . $ware['BBB gebraucht']['Lager'] . '</td>
			<td>' . $ware['BBB gebraucht']['Rikscha'] . '</td>
		</tr>
		<tr>
			<th scope="row">Depotkarten</th>
			<td>' . $ware['Depotkarte']['Takeaway'] . '</td>
			<td>' . $ware['Depotkarte']['MW-Logistik'] . '</td>
			<td>' . $ware['Depotkarte']['Lager'] . '</td>
			<td>' . $ware['Depotkarte']['Rikscha'] . '</td>
		</tr>
	</tbody>
	</table>
					
<pre>'.print_r($ware, true).'</pre>';

include APPPATH . 'views/footer.php';