<?php
echo 'Rechnung für ' . $firma->name . ';
Periode: Von ' . (strftime('%d. %B %Y', strtotime($rechnung->datum_von))) . ' bis ' . (strftime('%d. %B %Y', strtotime($rechnung->datum_bis))) . ';
Zusammenfassung;
Was;Anzahl;Preis/Stk;Preis total
TK ausgeliefert;' . $rechnung->tk_hin . ';' . $preise[1]['preis'] . ';' . ($rechnung->tk_hin * $preise[1]['preis']) . '
BBB ausgeliefert;' . $rechnung->bbb_hin . ';' . $preise[2]['preis'] . ';' . ($rechnung->bbb_hin * $preise[2]['preis']) . '
TK gebraucht abgeholt;' . $rechnung->tk_zurueck . ';' . $preise[3]['preis'] . ';' . ($rechnung->tk_zurueck * $preise[3]['preis']) . '
BBB gebraucht abgeholt;' . $rechnung->bbb_zurueck . ';' . $preise[4]['preis'] . ';' . ($rechnung->bbb_zurueck * $preise[4]['preis']) . '
BBB defekt ohne Depot;' . $rechnung->bbb_zurueck_defekt_ohne . ';' . $preise[6]['preis'] . ';' . ($rechnung->bbb_zurueck_defekt_ohne * $preise[6]['preis']) . '
BBB defekt mit Depot;' . $rechnung->bbb_zurueck_defekt_mit . ';' . $preise[5]['preis'] . ';' . ($rechnung->bbb_zurueck_defekt_mit * $preise[5]['preis']) . '
Total zu unseren Gunsten;' . '---' . ';' . '---' . ';' . $rechnung->saldo . '

';

// Mofifikationen
echo 'Buchungen;
Was;Richtung;Anzahl;Zeitpunkt;Preis/Stk.;Preis Summe';
foreach ($rechnung->get_modifikationen() as $mod) {
	// TODO Das ist unsicher. Falls wir plötzlich mehr als 1 Rikscha haben, geht das nicht mehr.
	$richtung = $mod->firma_id_von == 3 ? 'hin' : 'zurück';
	$faktor = $mod->firma_id_von == 3 ? 1 : -1;
	echo '
' . $waren[$mod->ware_id]->name 
. ';' . $richtung 
. ';' . $mod->anzahl 
. ';' . $mod->zeitpunkt 
. ';' . $preise[$mod->ware_id]['preis'] 
. ';' . $preise[$mod->ware_id]['preis'] * $mod->anzahl * $faktor . ';';
}
