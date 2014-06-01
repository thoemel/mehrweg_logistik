<?php
class Modifikation extends CI_Model {
	
		
	
	/**
	 * Konstruktor.
	 */
	public function __construct()
	{
		// Do nothing
		parent::__construct();
	}
	
	
	/**
	 * Gib alle Modifikationen betreffend einer Firma für eine Rechnungs-Periode
	 * NB: Vom 1. Tag nur bringt, vom letzten Tag nur holt.
	 * 
	 * @param int		$firma_id
	 * @param String	$datum_von
	 * @param String	$datum_bis
	 * @return Array mit StdClass Objekten mit Attributen der Tabelle "modifikation"
	 */
	public static function fuer_rechnung($firma_id, $datum_von, $datum_bis)
	{
		$arr_out = array();
		$CI =& get_instance();
		
		// Modifikationen für diese Periode aus der DB lesen
		$sql = 'SELECT	*
				FROM	modifikation
				WHERE	zeitpunkt BETWEEN ? AND ?
				AND (firma_id_von = ? OR firma_id_zu = ?)
				ORDER BY zeitpunkt asc';
		$params = array(
				$datum_von.' 00:00:00', 
				$datum_bis.' 23:59:59', 
				$firma_id, 
				$firma_id
		);
		$query = $CI->db->query($sql, $params);
		$last_query = $CI->db->last_query();
		if (0 == $query->num_rows()) {
			return NULL;
		}
		foreach ($query->result() as $row) {
			if (substr($row->zeitpunkt, 0, 10) == $datum_von && $row->firma_id_von == $firma_id) {
				continue;
			}
			if (substr($row->zeitpunkt, 0, 10) == $datum_bis && $row->firma_id_zu == $firma_id) {
				continue;
			}
			$arr_out[] = $row;
		}
		
		
		return $arr_out;
	}
	
	
	/**
	 * Gib die Summen aller Modifikationen betreffend einer Firma für eine Rechnungs-Periode
	 * 
	 * @param int		$firma_id
	 * @param String	$datum_von
	 * @param String	$datum_bis
	 * @return array(array('ware_id' => $ware_id, 'summe' => $summe))
	 */
	public static function summe_fuer_rechnung($firma_id, $datum_von, $datum_bis)
	{
		$arr_out = array();
		$CI =& get_instance();
		
		// Modifikationen für diese Periode aus der DB lesen
		$sql = 'SELECT	*
				FROM	modifikation
				WHERE	zeitpunkt BETWEEN ? AND ?
				AND (firma_id_von = ? OR firma_id_zu = ?)
				ORDER BY zeitpunkt asc';
		$params = array(
				$datum_von.' 00:00:00', 
				$datum_bis.' 23:59:59', 
				$firma_id, 
				$firma_id
		);
		$query = $CI->db->query($sql, $params);
		$last_query = $CI->db->last_query();
		if (0 == $query->num_rows()) {
			return NULL;
		}
		
		// Vom 1. Tag nur hin, vom letzten Tag nur zurück
		$sums = array();
		foreach ($query->result() as $row) {
			if (substr($row->zeitpunkt, 0, 10) == $datum_von && $row->firma_id_von == $firma_id) {
				continue;
			}
			if (substr($row->zeitpunkt, 0, 10) == $datum_bis && $row->firma_id_zu == $firma_id) {
				continue;
			}
			if (isset($sums[$row->ware_id])) {
				$sums[$row->ware_id] += $row->anzahl;
			} else {
				$sums[$row->ware_id] = $row->anzahl;
			}
			
		}
		foreach ($sums as $ware_id => $summe) {
			$arr_out[] = array('ware_id' => $ware_id, 'summe' => $summe);
		}
		
		return $arr_out;
	}
	
	
	/**
	 * Eine Modifikation speichern
	 * @param	int		$ware_id
	 * @param	int		$firma_id_von
	 * @param	int		$firma_id_zu
	 * @param	int		$anzahl
	 * @return bool
	 */
	public static function speichere($ware_id, $firma_id_von, $firma_id_zu, $anzahl)
	{
		$ret = false;
		$CI =& get_instance();

		$CI->db->set('ware_id', $ware_id);
		$CI->db->set('firma_id_von', $firma_id_von);
		$CI->db->set('firma_id_zu', $firma_id_zu);
		$CI->db->set('anzahl', $anzahl);
		$CI->db->set('zeitpunkt', date('Y-m-d H:i:s'));
		$ret = $CI->db->insert('modifikation');

		$ret = $ret && Bestand::aendere($firma_id_von, $ware_id, (-1 * $anzahl));
		$ret = $ret && Bestand::aendere($firma_id_zu, $ware_id, $anzahl);
		
		return $ret;
	} // End of function getAll()
	
	
}