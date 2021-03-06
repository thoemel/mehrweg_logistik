<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pl extends MY_Controller {

	
	public function __construct()
	{
		parent::__construct();
		
		// All methods require user to be logged in.
		$this->requireLoggedIn();
	}
	
	
	/**
	 * Sag hallo
	 */
	public function index()
	{
		$this->load->view('pl/index', $this->data);
		return ;
	}
	
	
	/**
	 * Formular für neues Material anzeigen
	 */
	public function neues_material()
	{
		$this->load->view('pl/neues_material', $this->data);
		return ;
	}
	
	
	/**
	 * Neues Material in DB speichern
	 */
	public function neues_material_speichern()
	{
		// Formulardaten validieren
		$config = array(
				array(
						'field'   => 'tk',
						'label'   => 'Transportkisten',
						'rules'   => 'integer'
				),
				array(
						'field'   => 'bbb',
						'label'   => 'Bring Back Boxen',
						'rules'   => 'integer'
				),
				array(
						'field'   => 'depotkarten',
						'label'   => 'Depotkarten',
						'rules'   => 'integer'
				),
		);
		$this->form_validation->set_rules($config);
		if (false === $this->form_validation->run()) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('pl/neues_material');
			return ;
		}

		$tk = $this->input->post('tk');
		$bbb = $this->input->post('bbb');
		$depotkarten = $this->input->post('depotkarten');
		
		$suc = Modifikation::speichere(1, 4, 6, $tk); // TK von Betlegeuse (4) zu Wäscherei (6)
		$suc = $suc && Modifikation::speichere(2, 4, 6, $bbb); // BBB von Betlegeuse (4) zu Wäscherei (6)
		$suc = $suc && Modifikation::speichere(8, 4, 5, $depotkarten); // Depotkarten von Betlegeuse (4) zu Lager (5)
		
		if (!$suc) {
			$this->session->set_flashdata('error', 'Ware Speichern ist schief gelaufen.');
			redirect('pl/neues_material');
		}

		$this->addData('tk', $tk);
		$this->addData('bbb', $bbb);
		$this->addData('depotkarten', $depotkarten);
		$this->load->view('pl/neues_material_confirm', $this->data);
		return;
	} // End of function neues_material_speichern
	
	
	/**
	 * 
	 * @param int		$firma_id
	 * @param String	$view		html (default) oder csv
	 */
	public function rechnung_fuer($firma_id, $view = 'html', $abrechnung_id = 0)
	{
		// Input validation
		$firma_id = (int)$firma_id;
		if (!$firma_id) {
			$this->session->set_flashdata('error', 'Ungültige Firma.');
			redirect('pl/rechnung_stellen');
			return;
		}
		$view = ('csv' == $view && 0 < $abrechnung_id) ? 'csv' : 'html';
		
		// Detailansicht für eine Firma
		$firma = new Firma();
		try {
			$firma->find($firma_id);
		} catch (Exception $e) {
			$this->session->set_flashdata('error', 'Keine Firma mit dieser ID gefunden.');
			redirect('pl/rechnung_stellen');
			return ;
		}
		
		$abrechnung_ids = Rechnung::ids_fuer_firma($firma_id);
		if (!empty($abrechnung_ids)) {
			$dt_tmp = new DateTime(reset($abrechnung_ids)->datum_bis);
			$letzte_rechnung_datum_bis = $dt_tmp->format('Y-m-d');
		} else {
			$letzte_rechnung_datum_bis = '';
		}
		
		$rechnung = new Rechnung();
		if (isset($abrechnung_ids[$abrechnung_id])) {
			$row = $abrechnung_ids[$abrechnung_id];
			$rechnung->init($firma_id, $row->datum_bis);
		}
		
		$this->addData('firma', $firma);
		$this->addData('abrechnung_ids', $abrechnung_ids);
		$this->addData('rechnung', $rechnung);
		$this->addData('preise', $rechnung->get_preise());
		$this->addData('letzte_rechnung_datum_bis', $letzte_rechnung_datum_bis);
		$this->addData('waren', Ware::getAll());
		
		switch ($view) {
			case 'csv':
				$filename = 'Rec_GT_'.(str_replace('.', '', $firma->name)).'_'.$rechnung->datum_von.'_'.$rechnung->datum_bis.'.csv';
				$filename = preg_replace("([^\w\s\d\-_~,;:\[\]\(\].]|[\.]{2,})", '', $filename);
				$filename = str_replace(' ', '_', $filename);
				$this->output->set_header('Content-type: text/csv');
				$this->output->set_header('Content-disposition: attachment;filename='.$filename);
				$this->load->view('pl/rechnung_csv', $this->data);
				break;
			case 'html':
			default:
				$this->load->view('pl/rechnung_html', $this->data);
		}
		
		return ;
	}
	
	
	/**
	 * Löscht eine Rechnung
	 * @param int	$firma_id
	 * @param int	$abrechnung_id
	 */
	public function rechnung_loeschen($firma_id, $abrechnung_id)
	{
		// TODO Vielleicht noch Input prüfen?
		if (Rechnung::loesche($abrechnung_id)) {
			$this->session->set_flashdata('success', 'Rechnung gelöscht');
		} else {
			$this->session->set_flashdata('error', 'Rechnung konnte nicht gelöscht werden.');
		}
		redirect('pl/rechnung_fuer/'.$firma_id);
	}
	
	
	/**
	 * Erstelle eine Rechnung für eine neue Periode und zeige ihre Details an.
	 * 
	 * @uses $this->rechnung_fuer()
	 */
	public function rechnung_neue_periode()
	{
		// Formulardaten validieren
		$config = array(
				array(
						'field'   => 'firma_id',
						'label'   => 'Firma',
						'rules'   => 'is_natural_no_zero|required'
				),
				array(
						'field'   => 'datum_bis',
						'label'   => 'Bis-Datum',
						'rules'   => 'required'
				),
		);
		$this->form_validation->set_rules($config);
		if (false === $this->form_validation->run()) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('pl/rechnung_stellen');
			return ;
		}
		
		$firma_id = $this->input->post('firma_id');
		$datum_bis = $this->input->post('datum_bis');
		
		$firma = new Firma();
		$firma->find($firma_id);
		$rechnung = $firma->getRechnung($datum_bis);
		
		$this->rechnung_fuer($firma_id, 'html', $rechnung->abrechnung_id);
		return;
	}
	
	
	/**
	 * Liste aller Takeaways
	 */
	public function rechnung_stellen()
	{
		$this->addData('firmen', Firma::getAll(2));
		$this->load->view('pl/rechnung_liste', $this->data);
		return ;
	}
	
	
	/**
	 * Statistischer Zusammenzug der Ware im System
	 */
	public function wo_ist_die_ware()
	{
		$this->addData('ware', Bestand::gesamt());
		$this->load->view('pl/wo_ist_die_ware', $this->data);
	}
	
	
}
