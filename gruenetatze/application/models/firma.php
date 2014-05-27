<?php
class Firma extends CI_Model {
	public $adresse = '	';
	public $email = '';
	public $iban = '';
	public $rolle_id = 0;
	public $name = '';
	public $id = 0;
	
		
	
	/**
	 * Konstruktor.
	 */
	public function __construct()
	{
		// Do nothing
		parent::__construct();
	}
	
	
	/**
	 * Sucht in der DB nach dem Händler mit der entsprechenden ID.
	 * 
	 * @throws	Exception, falls kein Händler gefunden.
	 * @param	int			$id
	 * @return	stdClass	Klasse mit allen DB-Feldern als Attributen
	 */
	public function find($id)
	{
		$this->db->where('firma_id', $id);
		$query = $this->db->get('firma', 1);
		if ($query->num_rows() !== 1) {
			throw new Exception('Keine Firma mit dieser ID gefunden');
			exit;
		}

		// Public Felder
		$this->id = $id;
		$this->adresse = $query->row()->adresse;
		$this->email = $query->row()->email;
		$this->firma_id = $query->row()->firma_id;
		$this->iban = $query->row()->iban;
		$this->name = $query->row()->name;
		$this->rolle_id = $query->row()->rolle_id;
		
		return $query->row();
	}
	
	
	/**
	 * Liefere alle Firmen aus der DB
	 * @param	int	$rolle_id	Nur Firmen mit dieser Rolle
	 * @return Array of objects with fields of database table 'firma'
	 */
	public static function getAll($rolle_id = NULL)
	{
		$arrOut = array();
		$CI =& get_instance();
		
		if ($rolle_id !== NULL) {
			$CI->db->where('rolle_id', $rolle_id);
		}
		$CI->db->order_by('name', 'asc');
		$query = $CI->db->get('firma');
		if (0 == $query->num_rows()) {
			return $arrOut;
		}
		
		return $query->result();
	} // End of function getAll()
	
	
	
	/**
	 * Gib die Rechnung für ein bestimmtes Datum
	 * @param String $datum_bis		Y-m-d
	 * @return Rechnung	Rechnungs-Objekt
	 */
	public function getRechnung($datum_bis = '')
	{
		$this->load->model('rechnung');
		$rechnung = new Rechnung();
		if (empty($datum_bis)) {
			$datum_bis = Rechnung::datum_letzte_fuer_firma($this->id);
		}
		if (empty($datum_bis)) {
			return $rechnung;
		}
		$rechnung->init($this->id, $datum_bis);
		return $rechnung;
	}
	
	
}