<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rikscha extends MY_Controller {

	
	public function __construct()
	{
		parent::__construct();
		
		// All methods require user to be logged in.
		$this->requireLoggedIn();
	} // End of function __construct()
	
	
	/**
	 * Falls nicht anders in der URL gewünscht, wird die index-Methode aufgerufen.
	 */
	public function index()
	{
		/*
		 * Um sicher zu gehen, dass wir im richtigen Ressort gemeldet sind,
		 * wird hier das Ressort gewechselt. Der eigentliche Einstieg in das 
		 * Ressort ist "einstieg_private".
		 */
		if ('privatannahme' != $this->session->userdata('user_ressort')) {
			redirect('login/dispatch/privatannahme');
		} else {
			$this->einstieg_private();
		}
		return ;
	} // End of function index()
	
	
	/**
	 * Formular für UC1.3, wenn die Rikscha im Lager die Ware auf das Fahrzeug lädt.
	 * Rikscha nimmt saubere TK.
	 */
	public function lager_checkin()
	{
		$rikscha_id = $this->session->userdata('firma_id');
		$this->addData('bestand', Bestand::fuer_firma($rikscha_id));
		$this->load->view('rikscha/lager_checkin', $this->data);
		return ;
	} // End of function lager_checkin()
	
	
	/**
	 * Speicher-Aktion, wenn Rikscha Ware aus dem Lager lädt.
	 * Wir nehmen an, dass nur volle oder leere TK geladen werden.
	 */
	public function lager_checkin_speichern()
	{
		$rikscha_id = $this->session->userdata('firma_id');
		$lager_id = 5; // Wird sich nicht ändern, solange wir nur ein Lager haben.
		
		$bestaetigung = $this->input->post('bestaetigung');
		
		if (1 != $bestaetigung) {
			show_error('Bestätigung muss angekreuzt sein.');
		}
		
		// Modifikationen
		foreach (Bestand::fuer_firma($rikscha_id) as $row) {
			Modifikation::speichere($row->ware_id, $rikscha_id, $lager_id, $row->anzahl);
			if (in_array($row->ware_id, array(5,6,7))) {
				// Defekte Ware geht nach Castor, da wo allesVergangene landet.
				$castor_id = 8;
				Modifikation::speichere($row->ware_id, $rikscha_id, $castor_id, $row->anzahl);
			}
		}

		// View vorbereiten und laden
		$this->load->view('rikscha/lager_checkin_confirm', $this->data);
		return ;
	} // End of function lager_checkin_speichern()
	
	
	/**
	 * Formular für UC1.3, wenn die Rikscha im Lager die Ware auf das Fahrzeug lädt.
	 * Rikscha nimmt saubere TK.
	 */
	public function lager_checkout()
	{
		if (false !== $this->input->post('tk') || false !== $this->input->post('bbb')) {
			// Formular wurde abgeschickt -> Bestätigungsseite
			$tk = (set_value('tk')) ? set_value('tk') : 0;
			$bbb = (set_value('bbb')) ? set_value('bbb') : 0;
			$this->addData('tk', $tk);
			$this->addData('bbb', $bbb);
			$this->load->view('rikscha/lager_checkout_confirm', $this->data);
		} else {
			$this->load->view('rikscha/lager_checkout', $this->data);
		}
		
		return ;
	} // End of function lager_checkout()
	
	
	/**
	 * Speicher-Aktion, wenn Rikscha Ware aus dem Lager lädt.
	 * Wir nehmen an, dass nur volle oder leere TK geladen werden.
	 */
	public function lager_checkout_speichern()
	{
		$ret = true;
		$rikscha_id = $this->session->userdata('firma_id');
		$lager_id = 5; // Wird sich nicht ändern, solange wir nur ein Lager haben.
		
		// Formulardaten validieren
		$fv = array(
				array(
						'field'   => 'tk',
						'label'   => 'Transportkisten',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'bbb',
						'label'   => 'Bring Back Boxen',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'depotkarten',
						'label'   => 'Depotkarten',
						'rules'   => 'is_natural'
				),
		);
		
		$this->form_validation->set_rules($fv);
		if (false === $this->form_validation->run()) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('rikscha/lager_checkout');
			return ;
		}
		
		$tk = $this->input->post('tk');
		$bbb = $this->input->post('bbb');
		$depotkarten = $this->input->post('depotkarten');
		
		// Modifikationen
		if (0 < $tk) {
			$ret = $ret & Modifikation::speichere(1, $lager_id, $rikscha_id, $tk);
		}
		if (0 < $bbb) {
			$ret = $ret & Modifikation::speichere(2, $lager_id, $rikscha_id, $bbb);
		}
		if (0 < $depotkarten) {
			$ret = $ret & Modifikation::speichere(8, $lager_id, $rikscha_id, $depotkarten);
		}

		if (!$ret) {
			$this->session->set_flashdata('error', 'Aus dem Lager genommene Ware wurde nicht richtig gespeichert.');
			redirect('rikscha/lager_checkout');
			return;
		}
		
		redirect('rikscha/takeaway');
		return ;
	} // End of function lager_checkout_speichern
	
	
	/**
	 * Formular für UC1.1, wenn die Rikscha beim Takeaway ist.
	 * Rikscha bringt saubere TK und BBB
	 * Rikscha nimmt TK und BBB sauber, gebraucht und defekt
	 */
	public function takeaway()
	{
		// Dropdown mit Takeaways
		$options = array();
		$firmaQR = Firma::getAll(2);
		foreach ($firmaQR as $myFirma) {
			$options[$myFirma->firma_id] = $myFirma->name;
		}
		$ta_dropdown = form_dropdown('ta_id', $options);
		$this->addData('ta_dropdown', $ta_dropdown);
		
		$this->load->view('rikscha/takeaway', $this->data);
		return ;
	} // End of function takeaway()
	
	
	/**
	 * Zusammenstellung der Formularwerte und Vorbereitung Eingabe der Unterschrift.
	 */
	public function takeaway_confirm()
	{
		// Formulardaten validieren
		$fv = array(
				array(
						'field'   => 'ta_id',
						'label'   => 'Takeaway Unternehmen',
						'rules'   => 'required|is_natural_no_zero'
				),
				array(
						'field'   => 'tk_sauber_bringt',
						'label'   => 'Transportkisten sauber',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'depotkarten_bringt',
						'label'   => 'Depotkarten',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'bbb_sauber_bringt',
						'label'   => 'Bring Back Boxen sauber',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'tk_dreckig_ganz',
						'label'   => 'Transportkisten gebraucht',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'bbb_defekt_mit_depot',
						'label'   => 'Bring Back Boxen defekt mit Depotrückgabe',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'bbb_defekt_ohne_depot',
						'label'   => 'Bring Back Boxen defekt ohne Depotrückgabe',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'tk_defekt',
						'label'   => 'Transportkisten defekt',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'depotkarten_holt',
						'label'   => 'Depotkarten',
						'rules'   => 'is_natural'
				),
		);

		$this->form_validation->set_rules($fv);
		if (false === $this->form_validation->run()) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('rikscha/takeaway');
			return ;
		}
		
		$ta_id = $this->input->post('ta_id');
		$tk_sauber_bringt = $this->input->post('tk_sauber_bringt');
		$bbb_sauber_bringt = $this->input->post('bbb_sauber_bringt');
		$depotkarten_bringt = $this->input->post('depotkarten_bringt');
		$tk_dreckig_ganz = $this->input->post('tk_dreckig_ganz');
		$bbb_dreckig_ganz = $this->input->post('bbb_dreckig_ganz');
		$bbb_defekt_mit_depot = $this->input->post('bbb_defekt_mit_depot');
		$bbb_defekt_ohne_depot = $this->input->post('bbb_defekt_ohne_depot');
		$tk_defekt = $this->input->post('tk_defekt');
		$depotkarten_holt = $this->input->post('depotkarten_holt');
		
		$ta = new Firma();
		$ta->find($ta_id);
		
		// View laden
		$this->addData('ta', $ta);
		$this->addData('tk_sauber_bringt', $tk_sauber_bringt);
		$this->addData('bbb_sauber_bringt', $bbb_sauber_bringt);
		$this->addData('depotkarten_bringt', $depotkarten_bringt);
		$this->addData('tk_dreckig_ganz', $tk_dreckig_ganz);
		$this->addData('bbb_dreckig_ganz', $bbb_dreckig_ganz);
		$this->addData('bbb_defekt_mit_depot', $bbb_defekt_mit_depot);
		$this->addData('bbb_defekt_ohne_depot', $bbb_defekt_ohne_depot);
		$this->addData('tk_defekt', $tk_defekt);
		$this->addData('depotkarten_holt', $depotkarten_holt);
		$this->addData('additionalJS', '<script src="' . base_url() . 'js/unterschrift.js"></script>');
		$this->load->view('rikscha/takeaway_confirm', $this->data);
		
		return;
	} // End of function takeaway_confirm
	
	
	/**
	 * Nachdem der Takeaway bestätigt hat.
	 * TK und BBB werden verschoben
	 */
	public function takeaway_speichern()
	{
		$ret = true;
		$rikscha_id = $this->session->userdata('firma_id');
		

		// Formulardaten validieren
		$fv = array(
				array(
						'field'   => 'ta_id',
						'label'   => 'Takeaway Unternehmen',
						'rules'   => 'required|is_natural_no_zero'
				),
				array(
						'field'   => 'tk_sauber_bringt',
						'label'   => 'Transportkisten sauber',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'bbb_sauber_bringt',
						'label'   => 'Bring Back Boxen sauber',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'depotkarten_bringt',
						'label'   => 'Depotkarten',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'tk_dreckig_ganz',
						'label'   => 'Transportkisten gebraucht',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'bbb_defekt_mit_depot',
						'label'   => 'Bring Back Boxen defekt mit Depotrückgabe',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'bbb_defekt_ohne_depot',
						'label'   => 'Bring Back Boxen defekt ohne Depotrückgabe',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'tk_defekt',
						'label'   => 'Transportkisten defekt',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'depotkarten_holt',
						'label'   => 'Depotkarten',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'unterschrift',
						'label'   => 'Unterschrift',
						'rules'   => 'required'
				),
		);
		
		$this->form_validation->set_rules($fv);
		if (false === $this->form_validation->run()) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('rikscha/takeaway');
			return ;
		}
		
		$ta_id = $this->input->post('ta_id');
		$tk_sauber_bringt = $this->input->post('tk_sauber_bringt');
		$bbb_sauber_bringt = $this->input->post('bbb_sauber_bringt');
		$depotkarten_bringt = $this->input->post('depotkarten_bringt');
		$tk_dreckig_ganz = $this->input->post('tk_dreckig_ganz');
		$bbb_dreckig_ganz = $this->input->post('bbb_dreckig_ganz');
		$bbb_defekt_mit_depot = $this->input->post('bbb_defekt_mit_depot');
		$bbb_defekt_ohne_depot = $this->input->post('bbb_defekt_ohne_depot');
		$tk_defekt = $this->input->post('tk_defekt');
		$depotkarten_holt = $this->input->post('depotkarten_holt');
		$unterschrift = $this->input->post('unterschrift');
		
		/*
		 * Unterschrift als Bild speichern
		 */
		// Unötige Zeichenketten aus dem übergebenen String entfernen/ersetzen
		$unterschrift = str_replace('data:image/png;base64,', '', $unterschrift);
		$unterschrift = str_replace(' ', '+', $unterschrift);
		// String in Bild umwandeln
		$picture = base64_decode($unterschrift);
		// Pfad/Dateinamen festlegen
		$file = FCPATH . 'uploads/unterschrift_' . microtime(true) . '.png';
		// Bilddaten in Datei schreiben
		$return = file_put_contents($file, $picture);
		if( !$return ) {
			show_error('kann Unterschrift nicht speichern.');
		}
		
		
		/*
		 * Modifikationen (Bestand wird von der Modifikation erledigt)
		 */
		if ($tk_sauber_bringt) {
			$ret = $ret && Modifikation::speichere(1, $rikscha_id, $ta_id, $tk_sauber_bringt);
		}
		if ($bbb_sauber_bringt) {
			$ret = $ret && Modifikation::speichere(2, $rikscha_id, $ta_id, $bbb_sauber_bringt);
		}
		if ($depotkarten_bringt) {
			$ret = $ret && Modifikation::speichere(8, $rikscha_id, $ta_id, $depotkarten_bringt);
		}
		if ($tk_dreckig_ganz) {
			$ret = $ret && Modifikation::speichere(3, $ta_id, $rikscha_id, $tk_dreckig_ganz);
		}
		if ($bbb_dreckig_ganz) {
			$ret = $ret && Modifikation::speichere(4, $ta_id, $rikscha_id, $bbb_dreckig_ganz);
		}
		if ($bbb_defekt_mit_depot) {
			$ret = $ret && Modifikation::speichere(5, $ta_id, $rikscha_id, $bbb_defekt_mit_depot);
		}
		if ($bbb_defekt_ohne_depot) {
			$ret = $ret && Modifikation::speichere(6, $ta_id, $rikscha_id, $bbb_defekt_ohne_depot);
		}
		if ($tk_defekt) {
			$ret = $ret && Modifikation::speichere(7, $ta_id, $rikscha_id, $tk_defekt);
		}
		if ($depotkarten_holt) {
			$ret = $ret && Modifikation::speichere(8, $ta_id, $rikscha_id, $depotkarten_holt);
		}
		
		if ($ret) {
			$this->session->set_flashdata('success', 'Angaben wurden gespeichert. Bereit für nächsten Takeaway.');
		} else {
			$this->session->set_flashdata('error', 'Beim Speichern ging etwas schief.<br>Am Besten schreibst du auf einen Zettel, was du gemacht hast!');
		}
		
		
		redirect('rikscha/takeaway');
		return;
	} // End of function takeaway_speichern
	
	
	/**
	 * Formular für UC_NaN, wenn MW-Logistiker Ware bei Rikscha holt und bringt.
	 * MW-Logistik bringt saubere TK und BBB
	 * MW-Logistik nimmt TK und BBB sauber, gebraucht und defekt
	 */
	public function mw_logistik()
	{
		$lager_id = 5; // Ändert sich nicht, solange wir nur 1 Lager haben.
		// Dropdown mit MW-Logistikern
		$options = array();
		$firmaQR = Firma::getAll(4); // rolle_id 4 ist MW-Logistik
		foreach ($firmaQR as $myFirma) {
			$options[$myFirma->firma_id] = $myFirma->name;
		}
		$mwl_dropdown = form_dropdown('mwl_id', $options);
		$this->addData('mwl_dropdown', $mwl_dropdown);
		$this->addData('mw_logistiker', $options);
		
		// TK gebraucht und BBB gebraucht vor-ausfüllen
		$bestand = Bestand::fuer_firma($lager_id);
		foreach ($bestand as $row) {
			switch ($row->ware_id) {
				case 3:
					$tk_dreckig_ganz = $row->anzahl;
					break;
				case 4:
					$bbb_dreckig_ganz = $row->anzahl;
					break;
				default:
					// Andere Ware interessiert hier nicht.
			}
		}
		$tk_dreckig_ganz = isset($tk_dreckig_ganz) ? $tk_dreckig_ganz : '';
		$bbb_dreckig_ganz = isset($bbb_dreckig_ganz) ? $bbb_dreckig_ganz : '';
		$this->addData('tk_dreckig_ganz', $tk_dreckig_ganz);
		$this->addData('bbb_dreckig_ganz', $bbb_dreckig_ganz);
		
		$this->load->view('rikscha/mw_logistik', $this->data);
		return ;
	} // End of function mw_logistik
	
	
	/**
	 * Zusammenstellung der Formularwerte und Vorbereitung Eingabe der Unterschrift.
	 */
	public function mw_logistik_confirm()
	{
		// Formulardaten validieren
		$fv = array(
				array(
						'field'   => 'mwl_id',
						'label'   => 'MW-Logistik Unternehmen',
						'rules'   => 'required|is_natural_no_zero'
				),
				array(
						'field'   => 'tk_sauber_bringt',
						'label'   => 'Transportkisten sauber',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'bbb_sauber_bringt',
						'label'   => 'Bring Back Boxen sauber',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'tk_dreckig_ganz',
						'label'   => 'Transportkisten gebraucht',
						'rules'   => 'is_natural'
				),
		);

		$this->form_validation->set_rules($fv);
		if (false === $this->form_validation->run()) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('rikscha/mw_logistik');
			return ;
		}
		
		$mwl_id = $this->input->post('mwl_id');
		$tk_sauber_bringt = $this->input->post('tk_sauber_bringt');
		$bbb_sauber_bringt = $this->input->post('bbb_sauber_bringt');
		$tk_dreckig_ganz = $this->input->post('tk_dreckig_ganz');
		$bbb_dreckig_ganz = $this->input->post('bbb_dreckig_ganz');
		
		$mwl = new Firma();
		$mwl->find($mwl_id);
		
		// View laden
		$this->addData('mwl', $mwl);
		$this->addData('tk_sauber_bringt', $tk_sauber_bringt);
		$this->addData('bbb_sauber_bringt', $bbb_sauber_bringt);
		$this->addData('tk_dreckig_ganz', $tk_dreckig_ganz);
		$this->addData('bbb_dreckig_ganz', $bbb_dreckig_ganz);
		$this->load->view('rikscha/mw_logistik_confirm', $this->data);
		
		return;
	} // End of function mw_logistik_confirm
	
	
	/**
	 * Nachdem die MW-Logistik bestätigt hat.
	 * TK und BBB werden verschoben
	 */
	public function mw_logistik_speichern()
	{
		$ret = true;
		$rikscha_id = $this->session->userdata('firma_id');
		$lager_id = 5;
		

		// Formulardaten validieren
		$fv = array(
				array(
						'field'   => 'mwl_id',
						'label'   => 'MW-Logistik Unternehmen',
						'rules'   => 'required|is_natural_no_zero'
				),
				array(
						'field'   => 'tk_sauber_bringt',
						'label'   => 'Transportkisten sauber',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'bbb_sauber_bringt',
						'label'   => 'Bring Back Boxen sauber',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'tk_dreckig_ganz',
						'label'   => 'Transportkisten gebraucht',
						'rules'   => 'is_natural'
				),
				array(
						'field'   => 'bestaetigung',
						'label'   => 'Bestätigung',
						'rules'   => 'required|is_natural'
				),
		);
		
		$this->form_validation->set_rules($fv);
		if (false === $this->form_validation->run()) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('rikscha/mw_logistik');
			return ;
		}
		
		$mwl_id = $this->input->post('mwl_id');
		$tk_sauber_bringt = $this->input->post('tk_sauber_bringt');
		$bbb_sauber_bringt = $this->input->post('bbb_sauber_bringt');
		$tk_dreckig_ganz = $this->input->post('tk_dreckig_ganz');
		$bbb_dreckig_ganz = $this->input->post('bbb_dreckig_ganz');
		
		// Modifikationen (Bestand wird von der Modifikation erledigt)
		if ($tk_sauber_bringt) {
			$ret = $ret && Modifikation::speichere(1, $mwl_id, $lager_id, $tk_sauber_bringt);
		}
		if ($bbb_sauber_bringt) {
			$ret = $ret && Modifikation::speichere(2, $mwl_id, $lager_id, $bbb_sauber_bringt);
		}
		if ($tk_dreckig_ganz) {
			$ret = $ret && Modifikation::speichere(3, $lager_id, $mwl_id, $tk_dreckig_ganz);
		}
		if ($bbb_dreckig_ganz) {
			$ret = $ret && Modifikation::speichere(4, $lager_id, $mwl_id, $bbb_dreckig_ganz);
		}
		
		if ($ret) {
			$this->session->set_flashdata('success', 'Angaben wurden gespeichert. MW-Logistiker darf gehen ;-)');
		} else {
			$this->session->set_flashdata('error', 'Beim Speichern ging etwas schief.<br>Am Besten schreibst du auf einen Zettel, was du gemacht hast!');
		}
		
		
		redirect('rikscha/mw_logistik');
		return;
	} // End of function mw_logistik_speichern
	
	
}
