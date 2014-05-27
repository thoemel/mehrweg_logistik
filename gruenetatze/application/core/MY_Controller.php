<?php
class MY_Controller extends CI_Controller {

	/**
	 * Array zum weiterleiten von Variablen an den View
	 *
	 * @var array
	 */
	protected $data = array();


	public function __construct()
	{
		parent::__construct();

		date_default_timezone_set('Europe/Zurich');
		setlocale(LC_ALL, 'de_CH.utf-8');
		
		//Transactions für die Entwicklung ausschalten.
		$this->db->trans_off();

		// Profiling infos für die Entwicklung einschalten
		if ('development' == ENVIRONMENT) {
// 	           $this->output->enable_profiler(TRUE);
		}


		// Statistik
		$this->load->model('Statistik');
		Statistik::registriere();
		
		// Navigation
		$this->navigation();
		
	} // End of function __construct

	
	/**
	 * Fügt dem $data array ein neues Element hinzu. Diese Methode dient nur der
	 * bequemeren Eingabe in den Kontrollern.
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	protected function addData($key, $value)
	{
		$this->data[$key] = $value;
	}

	
	/**
	 * Fügt einem Element des $data array weiteren Text hinzu. 
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	protected function appendData($key, $value)
	{
		$oldData = '';
		if (!empty($this->data[$key])) {
			$oldData = $this->data[$key] . '<br>';
		}
		$this->data[$key] = $oldData . $value;
	}
	
	
	/**
	 * Erstelle eine Navigation entsprechend der Rolle des eingeloggten Benutzers.
	 */
	private function navigation()
	{
		$arrNavi = array(); // Route => Anzeige
		
		switch ($this->session->userdata('rolle_id')) {
			case 5:	// Rikscha
				$arrNavi = array(
					'rikscha/lager_checkout' 	=> 'Checkout',
					'rikscha/takeaway' 			=> 'Takeaway',
					'rikscha/lager_checkin'		=> 'Checkin',
					'rikscha/mw_logistik'		=> 'MW-Logistik',
					'login/logout'				=> 'Logout',
				);
				break;
			case 6:	// Admin
				$arrNavi = array(
					'admin/index'				=> 'Benutzerverwaltung',
					'login/logout'				=> 'Logout',
				);
				break;
			case 7:	// Projektleitung
				$arrNavi = array(
					'admin/index'				=> 'Benutzerverwaltung',
					'pl/neues_material'			=> 'Neues&nbsp;Material',
					'pl/wo_ist_die_ware'		=> 'Wo&nbsp;ist&nbsp;die&nbsp;Ware?',
					'pl/rechnung_stellen'		=> 'Rechnung&nbsp;stellen',
					'login/logout'				=> 'Logout',
				);
				break;
			default:
				$arrNavi = array(
					'login/login'				=> 'Login',
				);
		}

		if (!$this->session->userdata('logged_in')) {
			$arrNavi = array('login/login' => 'Login');
		}
		
		$this->addData('arrNavi', $arrNavi);
		$this->addData('navi', $this->load->view('navi', $this->data, true));
		return;
	}
	
	
	/**
	 * Check if user is logged in. Set flashdata and redirect if not
	 */
	protected function requireLoggedIn()
	{
		if (!$this->session->userdata('logged_in')) {
			$this->session->set_flashdata('error', 'Benutzer ist nicht eingeloggt.');
			Redirect();
		}
		
	}
	

	/**
	 * Displays a 403 Forbidden page
	 */
	protected function show_403()
	{
		$this->output->set_status_header(403, 'Forbidden');
		$this->load->view('v_403', $this->data);
		return;
	}


}
