<?php
class Bestand extends CI_Model {
	
		
	
	/**
	 * Konstruktor.
	 */
	public function __construct()
	{
		// Do nothing
		parent::__construct();
	}
	
	
	/**
	 * Einen Bestand speichern
	 * @param	int		$firma_id
	 * @param	int		$ware_id
	 * @param	int		$anzahl			Kann negativ sein.
	 * @return bool
	 */
	public static function aendere($firma_id, $ware_id, $anzahl)
	{
		$ret = false;
		$CI =& get_instance();

		$CI->db->where('firma_id', $firma_id);
		$CI->db->where('ware_id', $ware_id);
		$query = $CI->db->get('bestand', 1);
		if (1 == $query->num_rows()) {
			$CI->db->where('firma_id', $firma_id);
			$CI->db->where('ware_id', $ware_id);
			$CI->db->set('anzahl', ($query->row()->anzahl + $anzahl));
			$ret = $CI->db->update('bestand');
		} else {
			$CI->db->set('firma_id', $firma_id);
			$CI->db->set('ware_id', $ware_id);
			$CI->db->set('anzahl', $anzahl);
			$ret = $CI->db->insert('bestand');
		}
		
		return $ret;
	} // End of function getAll()
	
	
	/**
	 * Zeige den Bestand aller Waren für eine Firma
	 * @param int	$firma_id
	 * @return stdClass			Entweder leer oder $query->result()
	 */
	public static function fuer_firma($firma_id)
	{
		$ret = new stdClass();
		$CI =& get_instance();
		
		$sql = 'SELECT	ware.ware_id, ware.name as ware, bestand.anzahl
				FROM	bestand
				INNER JOIN ware using(ware_id)
				WHERE bestand.firma_id = ?
				ORDER BY ware.ware_id';
		$query = $CI->db->query($sql, array($firma_id));
		if (0 < $query->num_rows()) {
			$ret = $query->result();
		}
		
		return $ret;
	}
	
	
	/**
	 * Zeige den Bestand aller Waren für das ganze System
	 * Es wird dabei nicht nach sauber und gebraucht unterschieden, sondern 
	 * es werden TK und BBB gemischt angegeben.
	 * Defekte Ware wird nicht beachtet.
	 * @return array(warenname als Key, darin array mit Rollenname als Key)
	 */
	public static function gesamt()
	{
		$CI =& get_instance();
		$rollen = array(
						'Takeaway'		=> 0,
						'MW-Logistik'	=> 0,
						'Lager'			=> 0,
						'Rikscha'		=> 0
				);
		$ware = array(
				'TK' => $rollen,
				'BBB' => $rollen,
				'Depotkarte' => $rollen,
		);
		$sql = 'SELECT	ware.name as ware, rolle.name as rolle, sum(bestand.anzahl) as anzahl 
				FROM	bestand
				INNER JOIN firma ON bestand.firma_id = firma.firma_id
				INNER JOIN rolle ON firma.rolle_id = rolle.rolle_id
				INNER JOIN ware ON bestand.ware_id = ware.ware_id
				GROUP BY ware.ware_id, rolle.rolle_id';
		$query = $CI->db->query($sql);
		if (0 == $query->num_rows()) {
			return $ware;
		}
		foreach ($query->result() as $row) {
			if (!in_array($row->rolle, array_keys($rollen))) {
				continue;
			}
			switch ($row->ware) {
				case 'TK sauber':
				case 'TK gebraucht':
					$ware['TK'][$row->rolle] += $row->anzahl;
					break;
				case 'BBB sauber':
				case 'BBB gebraucht':
					$ware['BBB'][$row->rolle] += $row->anzahl;
					break;
				case 'Depotkarte':
					$ware['Depotkarte'][$row->rolle] += $row->anzahl;
					break;
				default:
					show_error('Bestand::gesamt() - nicht vorgesehene Ware');
			}
		}
	
		return $ware;
	}
	
	
	
}