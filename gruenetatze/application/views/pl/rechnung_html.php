<?php 
include APPPATH . 'views/header.php';

echo '
<div class="page-header">
	<h1>Rechnungen für ' . $firma->name . '</h1>
</div>';


/*
 * Neue Rechnung erstellen
 */
echo '
<div class="row">
	<h2>Neue Rechnung erfassen</h2>
</div>
' . form_open_multipart('pl/rechnung_neue_periode', 
		array('id' => 'rechnung_neue_periode', 
				'role' => 'form', 
				'class' => 'form-horizontal')) . '
	' . form_hidden('firma_id', $firma->id) . '
	<div class="form-group">
		<label for="datum_von" class="col-sm-2 control-label hat-dp">Datum von</label>
		<div class="col-sm-4">
			' . form_input(array(
					'id' => 'datum_von', 
					'name' => 'datum_von', 
					'value' => $letzte_rechnung_datum_bis, 
					'class' => 'form-control', 
					'disabled' => 'disabled')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="datum_bis" class="col-sm-2 control-label">Datum bis</label>
		<div class="col-sm-4">
			' . form_input(array(
					'id' => 'datum_bis', 
					'name' => 'datum_bis', 
					'value' => set_value('datum_bis'), 
					'class' => 'form-control hat-dp')) . '
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" class="btn btn-default">Erstellen</button>
		</div>
	</div>

</form>
';

/*
 * Liste bestehender Rechnungen
 */
if (!empty($abrechnung_ids)) {
	echo '
	<div class="row">
		<h2>Bisherige Rechnungen</h2>
		<ul class="list-group col-sm-6">';
	foreach ($abrechnung_ids as $abrechnung_id => $row) {
		echo '
				<li class="list-group-item">
					<span class="badge">' . anchor('pl/rechnung_fuer/' . $firma->firma_id . '/csv/' . $abrechnung_id, 'CSV') . '</span>
					<span class="badge">' . anchor('pl/rechnung_fuer/' . $firma->firma_id . '/html/' . $abrechnung_id . '#details', 'HTML') . '</span>
					' . $row->datum_von . '&nbsp;-&nbsp;' . $row->datum_bis . '
				</li>';
	}
	echo '
		</ul>
	</div>';
} // End if Rechnungen


if (!empty($abrechnung_ids)) {
	echo '
	<a name="details"></a>
	<div class="row">
		<h2>
			Periode: Von ' . (strftime('%d. %B %Y', strtotime($rechnung->datum_von))) . ' bis ' . (strftime('%d. %B %Y', strtotime($rechnung->datum_bis))) . '
		</h2>
		<h3>Zusammenfassung</h3>
		<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th scope="col">Was</th>
				<th scope="col">Anzahl</th>
				<th scope="col">Preis/Stk</th>
				<th scope="col">Preis total</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th scope="row">TK ausgeliefert</th>
				<td>' . $rechnung->tk_hin . '</td>
				<td>' . $preise[1]['preis'] . '</td>
				<td>' . number_format($rechnung->tk_hin * $preise[1]['preis'], 2) . '</td>
			</tr>
			<tr>
				<th scope="row">BBB ausgeliefert</th>
				<td>' . $rechnung->bbb_hin . '</td>
				<td>' . $preise[2]['preis'] . '</td>
				<td>' . number_format($rechnung->bbb_hin * $preise[2]['preis'], 2) . '</td>
			</tr>
			<tr>
				<th scope="row">TK gebraucht abgeholt</th>
				<td>' . $rechnung->tk_zurueck . '</td>
				<td>' . $preise[3]['preis'] . '</td>
				<td>' . number_format($rechnung->tk_zurueck * $preise[3]['preis'], 2) . '</td>
			</tr>
			<tr>
				<th scope="row">BBB gebraucht abgeholt</th>
				<td>' . $rechnung->bbb_zurueck . '</td>
				<td>' . $preise[4]['preis'] . '</td>
				<td>' . number_format($rechnung->bbb_zurueck * $preise[4]['preis'], 2) . '</td>
			</tr>
			<tr>
				<th scope="row">BBB defekt ohne Depot</th>
				<td>' . $rechnung->bbb_zurueck_defekt_ohne . '</td>
				<td>' . $preise[6]['preis'] . '</td>
				<td>' . number_format($rechnung->bbb_zurueck_defekt_ohne * $preise[6]['preis'], 2) . '</td>
			</tr>
			<tr>
				<th scope="row">BBB defekt mit Depot</th>
				<td>' . $rechnung->bbb_zurueck_defekt_mit . '</td>
				<td>' . $preise[5]['preis'] . '</td>
				<td>' . number_format($rechnung->bbb_zurueck_defekt_mit * $preise[5]['preis'], 2) . '</td>
			</tr>
			<tr>
				<th scope="row">Total zu unseren Gunsten</th>
				<td>' . '---' . '</td>
				<td>' . '---' . '</td>
				<td>' . number_format($rechnung->saldo, 2) . '</td>
			</tr>
		</tbody>
		</table>';
	
	/*
	 * Modifikationen
	 */
	echo '
		<h3>Buchungen</h3>
		<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th scope="col">Was</th>
				<th scope="col">Richtung</th>
				<th scope="col">Anzahl</th>
				<th scope="col">Zeitpunkt</th>
				<th scope="col">Preis/Stk.</th>
				<th scope="col">Preis Summe</th>
			</tr>
		</thead>
		<tbody>';
	foreach ($rechnung->get_modifikationen() as $mod) {
		// TODO Das ist unsicher. Falls wir plötzlich mehr als 1 Rikscha haben, geht das nicht mehr.
		$richtung = $mod->firma_id_von == 3 ? 'hin' : 'zurück';
		$faktor = $mod->firma_id_von == 3 ? 1 : -1;
		echo '
			<tr>
				<td>' . $waren[$mod->ware_id]->name . '</td>
				<td>' . $richtung . '</td>
				<td>' . $mod->anzahl . '</td>
				<td>' . $mod->zeitpunkt . '</td>
				<td>' . $preise[$mod->ware_id]['preis'] . '</td>
				<td>' . $preise[$mod->ware_id]['preis'] * $mod->anzahl * $faktor . '</td>
			</tr>';
	}
	echo '
		</tbody>
		</table>
	</div>';
	
} // End if Rechnung


include APPPATH . 'views/footer.php';