<?php
/**
 * Hier werden die Daten für eine Rechnung zusammengestellt
 * @author thoemel
 *
 */
class Rechnung extends CI_Model {
	public $abrechnung_id = NULL;
	public $datum_von = NULL;
	public $datum_bis = NULL;
	public $firma_id = NULL;
	public $tk_hin = 0;
	public $bbb_hin = 0;
	public $tk_zurueck = 0;
	public $bbb_zurueck = 0;
	public $bbb_zurueck_defekt_mit = 0;
	public $bbb_zurueck_defekt_ohne = 0;
	public $tk_defekt_zurueck = 0;
	public $depotkarte = 0;
	public $saldo = 0;
	
	/**
	 * Modifikationen für diese Rechnungsperiode
	 * @var array($id => $row)
	 */
	private $modifikationen = array();
	
	/**
	 * Mapping zwischen den ware_id der Datenbank und der Klassenvariablen
	 * @var array($ware_id => $attribute_name)
	 */
	private $ware_attribute_map = array(
		1 => 'tk_hin',
		2 => 'bbb_hin',
		3 => 'tk_zurueck',
		4 => 'bbb_zurueck',
		5 => 'bbb_zurueck_defekt_mit',
		6 => 'bbb_zurueck_defekt_ohne',
		7 => 'tk_defekt_zurueck',
		8 => 'depotkarte',
	);
	
	/**
	 * Welche Ware hat welchen Preis?
	 * @var array($ware->id => array(Felder aus der DB)
	 */
	private $preise = array();
		
	
	/**
	 * Konstruktor.
	 */
	public function __construct()
	{
		// Do nothing
		parent::__construct();
	}
	
	
	/**
	 * Berechne den Saldo, setze die Klassenvariable entsprechend und gib ihn zurück.
	 * 
	 * @return	float	$this->saldo
	 */
	private function berechne_saldo()
	{
		$this->saldo = 0;
		$preise = $this->get_preise();
		
		$this->saldo = 
			$this->tk_hin * $preise[1]['preis']
			+ $this->bbb_hin * $preise[2]['preis']
			- $this->tk_zurueck * $preise[3]['preis']
			- $this->bbb_zurueck * $preise[4]['preis']
			- $this->bbb_zurueck_defekt_mit * $preise[5]['preis']
			- $this->bbb_zurueck_defekt_ohne * $preise[6]['preis'];
		
		return $this->saldo;
	}
	
	
	/**
	 * Gib das End-Datum der letzten Rechnung dieser Firma.
	 * @param int		$firma_id
	 * @return string	Y-m-d
	 */
	public static function datum_letzte_fuer_firma($firma_id)
	{
		$CI =& get_instance();
		
		$CI->db->where('firma_id', $firma_id);
		$CI->db->order_by('datum_bis', 'desc');
		$query = $CI->db->get('abrechnung', 1);
		if (0 == $query->num_rows()) {
			return '';
		}
		return $query->row()->datum_bis;
	}
	
	
	/**
	 * Falls das gewählte Datum in der Periode einer bereits gespeicherten Rechnung
	 * ist, lade diese.
	 * 
	 * @param String	$datum_bis	Y-m-d
	 * @return boolean	True, falls gefunden.
	 */
	private function finde_fuer_datum($datum_bis)
	{
		$sql = 'SELECT	*
				FROM 	abrechnung
				WHERE	firma_id = ?
				AND		datum_bis >= ?
				ORDER BY datum_bis asc
				LIMIT	1';
		$query = $this->db->query($sql, array($this->firma_id, $datum_bis));
		if (0 == $query->num_rows()) {
			return false;
		}

		$this->abrechnung_id = $query->row()->abrechnung_id;
		$this->bbb_hin = $query->row()->bbb_hin;
		$this->bbb_zurueck = $query->row()->bbb_zurueck;
		$this->bbb_zurueck_defekt_mit = $query->row()->bbb_zurueck_defekt_mit;
		$this->bbb_zurueck_defekt_ohne = $query->row()->bbb_zurueck_defekt_ohne;
		$this->datum_bis = $query->row()->datum_bis;
		$this->datum_von = $query->row()->datum_von;
		$this->saldo = $query->row()->saldo;
		$this->tk_hin = $query->row()->tk_hin;
		$this->tk_zurueck = $query->row()->tk_zurueck;
		
		$this->get_modifikationen();
		
		return true;
	}
	
	
	/**
	 * Hole alle Modifikationen zu dieser Rechnungsperiode.
	 * NB: Vom 1. Tag nur bringt, vom letzten Tag nur holt.
	 */
	public function get_modifikationen()
	{
		$mods = $this->modifikationen;
		if (!empty($mods)) {
			return $mods;
		}
		
		$this->modifikationen = Modifikation::fuer_rechnung(
			$this->firma_id,
			$this->datum_von,
			$this->datum_bis
		);
		
		return $this->modifikationen;
	}
	
	
	/**
	 * Hole Preise der Waren aus der DB und setze sie in das Klassenattribut
	 */
	public function get_preise()
	{
		$preise = $this->preise;
		if (!empty($preise)) {
			return $preise;
		}
		
		$query = $this->db->get('ware');
		foreach ($query->result_array() as $row) {
			$preise[$row['ware_id']] = $row;
		}
		$this->preise = $preise;
		
		return $preise;
	}
	
	
	/**
	 * Gib alle ids und periodendaten für alle Rechnungen einer Firma
	 * @param int		$firma_id
	 * @return array	Key: abrechnung_id, value: DB-record
	 */
	public static function ids_fuer_firma($firma_id)
	{
		$arrOut = array();
		$CI =& get_instance();
		
		$CI->db->where('firma_id', $firma_id);
		$CI->db->order_by('datum_bis', 'desc');
		$query = $CI->db->get('abrechnung');
		if (0 == $query->num_rows()) {
			return '';
		}
		foreach ($query->result() as $row) {
			$arrOut[$row->abrechnung_id] = $row;
		}
		return $arrOut;
	}
	
	
	/**
	 * Initialisiert die Rechnung für die letzte Periode.
	 * Der Beginn der letzten Periode ist entweder aus der letzten Rechnung in der DB
	 * ersichtlich, oder wird auf die erste Modifikation gesetzt, für den Fall, wenn
	 * eine Firma noch nie eine Rechnung erhalten hat. 
	 * 
	 * @param int		$firma_id
	 * @param String	$datum_bis
	 * @return void
	 */
	public function init($firma_id, $datum_bis)
	{
		$this->firma_id = $firma_id;
		$this->datum_bis = $datum_bis;
		
		// Aus DB auslesen, falls schon vorhanden
		if ($this->finde_fuer_datum($datum_bis)) {
			return;
		}
		
		/*
		 * Beginn der Periode herausfinden. Ist gleiches Datum, wie Enddatum 
		 * der letzten Periode, weil die gebrachten Waren dort nicht berücksichtigt
		 * worden sind.
		 */
		$this->db->where('firma_id', $firma_id);
		$this->db->order_by('datum_von', 'desc');
		$query = $this->db->get('abrechnung', 1);
		if (1 == $query->num_rows()) {
			$dt = new DateTime($query->row()->datum_bis);
			$this->datum_von = $dt->format('Y-m-d');
		} else {
			// Noch keine Rechnung in der DB -> suche erste Modifikation der Firma
			$sql = 'SELECT	zeitpunkt
					FROM	modifikation
					WHERE	firma_id_von = ?
					OR		firma_id_zu = ?
					ORDER BY zeitpunkt asc
					LIMIT 1';
			$params = array($firma_id, $firma_id);
			$query = $this->db->query($sql, $params);
			if (0 == $query->num_rows()) {
				throw new Exception('Rechnung::init() - Weder Rechnungen noch Modifikationen für diese Firma gefunden.');
				return;
			}
			$this->datum_von = substr($query->row()->zeitpunkt, 0, 10);
		}
		
		// Modifikationen für diese Periode
		$mods = Modifikation::summe_fuer_rechnung($firma_id, $this->datum_von, $this->datum_bis);
		if (empty($mods)) {
			show_error('Keine Modifikationen gefunden für den Zeitraum zwischen ' . $this->datum_von . ' und ' . $this->datum_bis . '.');
			return ;
		}
		
		$map = $this->ware_attribute_map;
		foreach ($mods as $row) {
			if (isset($map[$row['ware_id']])) {
				$attrName = $map[$row['ware_id']];
				$this->$attrName = $row['summe'];
			}
		}
		
		// Saldo berechnen
		$this->berechne_saldo();
		
		$this->speichere();
		
		return;
	} // End of function init()
	
	
	/**
	 * Lösche eine bestehende Rechnung
	 * @param	int	$abrechnung_id
	 * @return	boolean				True, falls der DB-Befehl erfolgreich.
	 */
	public static function loesche($abrechnung_id)
	{
		$CI =& get_instance();
		$CI->db->where('abrechnung_id', $abrechnung_id);
		return $CI->db->delete('abrechnung');
	}
	
	
	/**
	 * Eine Modifikation speichern
	 * 
	 * @return bool
	 */
	private function speichere()
	{
		$ret = false;
		
		$this->db->set('firma_id', $this->firma_id);
		$this->db->set('datum_von', $this->datum_von);
		$this->db->set('datum_bis', $this->datum_bis);
		$this->db->set('tk_hin', $this->tk_hin);
		$this->db->set('bbb_hin', $this->bbb_hin);
		$this->db->set('tk_zurueck', $this->tk_zurueck);
		$this->db->set('bbb_zurueck', $this->bbb_zurueck);
		$this->db->set('bbb_zurueck_defekt_mit', $this->bbb_zurueck_defekt_mit);
		$this->db->set('bbb_zurueck_defekt_ohne', $this->bbb_zurueck_defekt_ohne);
		$this->db->set('saldo', $this->saldo);
		if (!$this->abrechnung_id) {
			$this->db->insert('abrechnung');
			$this->abrechnung_id = $this->db->insert_id();
		} else {
			$this->db->where('abrechnung_id', $this->abrechnung_id);
			$this->db->update('abrechnung');
		}

		
		return $ret;
	} // End of function getAll()
	
	
}