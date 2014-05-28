<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 * Weiterleiten zur Standard-Aktion des Users
	 * 
	 * @return	void
	 */
	public function dispatch()
	{
		// Require user to be logged in.
		$this->requireLoggedIn();
		
		// Gemäss Tabelle "rolle"
		switch ($this->session->userdata('rolle_id')) {
			case 1:
				$this->session->set_flashdata('error', 'Time is an illusion. And tea time doublish so.');
				redirect('login/logout');
				break;
			case 2:
				$this->session->set_flashdata('error', 'Liebes Takeaway. Du bist hier nicht vorgesehen.');
				redirect('login/logout');
				break;
			case 3:
				redirect('login/logout');
				break;
			case 4:
				$this->session->set_flashdata('error', 'MW-Logistik ist noch nict vorgesehen.');
				redirect('login/logout');
				break;
			case 5:
				redirect('rikscha/lager_checkout');
				break;
			case 6:
				$this->session->set_flashdata('success', 'Hallo, lieber Admin!');
				redirect('admin/index');
				break;
			case 7:
				redirect('pl/index');
				break;
			default:
				redirect('login/logout');
		}
		return;
	}
	
	
	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		$this->form();
	}
	
	
	/**
	 * Show the login form
	 */
	public function form()
	{
		$this->load->view('login/formular', $this->data);
	}
	
	
	public function logMeIn()
	{
		if($this->simpleloginsecure->login($this->input->post('username'), $this->input->post('password'))) {
			// success
			$this->dispatch();
		} else {
			// failure
			$this->session->set_flashdata('error', 'Login fehlgeschlagen');
			redirect('login/form');
		}
		return;
	}
	
	
	public function logout()
	{
		$this->simpleloginsecure->logout();
		redirect();
	}
	
	
	/**
	 * @deprecated
	 * @see admin.php
	 */
	public function save()
	{
		$this->form_validation->set_rules('username', 'E-Mail', 'trim|required');
		$this->form_validation->set_rules('password', 'Passwort', 'trim|required|min_length[8]');
		
		if (false === $this->form_validation->run()) {
			$this->form();
			return;
		}
		
		$this->simpleloginsecure->create($this->input->post('username'), $this->input->post('password'));
		redirect('auswahl');
	}
	
	
	public function showChoices()
	{
		// Require user to be logged in.
		$this->requireLoggedIn();
		
		$this->load->view('login/auswahl', $this->data);
		return;
	}
}
