<?php
class Ware extends CI_Model {
	public $name = '';
	public $id = 0;
	public $preis = 0;
	
		
	
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
		$this->db->where('ware_id', $id);
		$query = $this->db->get('ware', 1);
		if ($query->num_rows() !== 1) {
			throw new Exception('Keine Ware mit dieser ID gefunden');
			exit;
		}

		// Public Felder
		$this->id = $id;
		$this->name = $query->row()->name;
		$this->preis = $query->row()->preis;
		
		return $query->row();
	}
	
	
	/**
	 * Liefere alle Waren aus der DB
	 * @return array($id => Object with fields of database table 'ware')
	 */
	public static function getAll()
	{
		$arrOut = array();
		$CI =& get_instance();
		
		$query = $CI->db->get('ware');
		if (0 == $query->num_rows()) {
			return $arrOut;
		}
		foreach ($query->result() as $row) {
			$arrOut[$row->ware_id] = $row;
		}
		
		return $arrOut;
	} // End of function getAll()
	
	
}