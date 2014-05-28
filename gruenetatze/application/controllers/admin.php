<?php
/**
 * Administrative tasks for IDB Kantone project
 *
 * @author web@meteotest.ch
 */
class Admin extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('M_user');
	}

	/**
	 * Deletes an existing user
	 * @param int $id
	 */
	public function deleteUser($id)
	{
		// User must be superadmin
		if (!in_array($this->session->userdata('rolle_id'), array(6,7))) {
			$this->show_403();
			return ;
		}
		
		if ($this->simpleloginsecure->delete($id)) {
			$this->session->set_flashdata('success', "Benutzer gelöscht");
		} else {
			$this->session->set_flashdata('error', "Benutzer konte nicht gelöscht werden.");
		}

		redirect('admin');
	}


	/**
	 * Edits credentials of an existing user
	 */
	public function editUser()
	{
		// User must be superadmin
		if (!in_array($this->session->userdata('rolle_id'), array(6,7))) {
			$this->show_403();
			return ;
		}
		
		if ($this->form_validation->run('editUser') === false) {
			// Not registered because of wrong input
			$formValues = array();
			$formValues['email'] = set_value('email');
			$formValues['role'] = set_value('role');
			$formValues['rolle_id'] = set_value('rolle_id');
			$this->addData('formValues', $formValues);
			$this->userForm($this->input->post('id'));
			return;
		}
		
		$myUser = new M_user();
		$myUser->fetch($this->input->post('id'));
		$myUser->email = $this->input->post('email');
		$myUser->role = $this->input->post('role');
		$myUser->rolle_id = $this->input->post('rolle_id');

		$pw_old = $this->input->post('pw_old');
		$pw_new = $this->input->post('pw_new');
		if (!empty($pw_old) && !empty($pw_new)) {
			if (!$this->simpleloginsecure->edit_password($myUser->email, $pw_old, $pw_new)) {
				log_message('error', 'Benutzer erstellen fehlgeschlagen.');
				return false;
			}
		}
		
		if($myUser->save()) {
			$this->session->set_flashdata('success', 'Benutzer speichern erfolgreich.');
		} else {
			// Not registered because of technical reason
			$this->session->set_flashdata('error', 'Benutzer speichern fehlgeschlagen.');
		}
			
		redirect('admin');

	} // End of function editUser()


	public function index()
	{
		// User must be superadmin
		if (!in_array($this->session->userdata('rolle_id'), array(6,7))) {
			$this->show_403();
			return ;
		}
		
		// Get List of users
		$this->addData('registeredUsers', $this->M_user->all());


		$this->load->view('admin/index', $this->data);
		
		return;
	}


	/**
	 * Creates a user in the database
	 *
	 */
	public function registerUser()
	{
		// User must be superadmin
		if (!in_array($this->session->userdata('rolle_id'), array(6,7))) {
			$this->show_403();
			return ;
		}
		
		if ($this->form_validation->run('createUser') === false) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('admin');
		}

		$myUser = new M_user();
		$myUser->email		= $this->input->post('email');
		$myUser->password	= $this->input->post('pw');
		$myUser->role		= $this->input->post('role');
		$myUser->rolle_id	= $this->input->post('rolle_id');
		if ($myUser->save()) {
			$this->session->set_flashdata('success', 'Benutzer erstellen erfolgreich.');
		} else {
			$this->session->set_flashdata('error', 'Benutzer erstellen fehlgeschlagen.');
		}

		redirect('admin');
	}
	
	
	/**
	 * With this method a superadmin user can see the application as if he were 
	 * another user.
	 * 
	 * @param	int	$user_id
	 * @return	void
	 */
	public function switchToUser($user_id)
	{
		// User must be superadmin
		if (!in_array($this->session->userdata('rolle_id'), array(6,7))) {
			$this->show_403();
			return ;
		}
		
		$newUser = new M_user();
		if (!$newUser->fetch($user_id)) {
			throw new Exception('No user found with this id');
			return;
		}
		$this->session->set_userdata('user_id', $newUser->id);
		$this->session->set_userdata('user_role', $newUser->role);
		$this->session->set_userdata('user_email', $newUser->email);
		$this->session->set_userdata('rolle_id', $newUser->rolle_id);
		$this->session->set_userdata('firma_id', $newUser->firma_id);
		$this->session->set_userdata('firma_name', $newUser->firma_name);
		$this->session->set_userdata('logged_in', true);
		
		$this->session->set_flashdata('success', 'Eingeloggt als ' . $newUser->email);
		redirect('login/dispatch');
		return;
	}


	/**
	 * Shows the edit form for a user.
	 * Used for create and edit
	 * 
	 * @param	String	$userId	
	 */
	public function userForm($userId = '')
	{
		// User must be superadmin
		if (!in_array($this->session->userdata('rolle_id'), array(6,7))) {
			$this->show_403();
			return ;
		}
		
		$userId = intval($userId);
		
		// Get Roles (Für Superadmin)
		$roles = $this->M_user->roles();
		$this->addData('roles', $roles);
		
		// Rollen aus DB
		$rolle_ids = $this->M_user->rollen_ids();
		$this->addData('rolle_ids', $rolle_ids);
		
		// If form_validation failed formValues are already populated
		if (!isset($this->data['formValues'])) {
			$formValues = array();
			$user = new M_user();
			if (0 < $userId) {
				$user->fetch($userId);
				$formValues['pw_old'] = '';
				$formValues['pw_new'] = '';
			} else {
				$formValues['pw'] = '';
			}
			$formValues['id'] = $user->id;
			$formValues['email'] = $user->email;
			$formValues['role'] = $user->role;
			$formValues['rolle_id'] = $user->rolle_id;
			$this->addData('formValues', $formValues);
		} 
		
		$formAction = $userId ? 'admin/editUser' : 'admin/registerUser';
		$this->addData('formAction', $formAction);
			
		$this->load->view('admin/user_form', $this->data);
		
		return;
	}
}
