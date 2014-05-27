<?php
$config = array ();
$config ['login'] = array (
		array (
				'field' => 'username',
				'label' => 'E-Mail',
				'rules' => 'trim|required' 
		),
		array (
				'field' => 'password',
				'label' => 'Passwort',
				'rules' => 'trim|required' 
		) 
);

$config ['createUser'] = array (
		array (
				'field' => 'email',
				'label' => 'E-Mail',
				'rules' => 'trim|required|valid_email' 
		),
		array (
				'field' => 'pw',
				'label' => 'Passwort',
				'rules' => 'trim' 
		),
		array (
				'field' => 'role',
				'label' => 'Rolle',
				'rules' => 'trim|required' 
		),
		array (
				'field' => 'rolle_id',
				'label' => 'Rolle für Grüne Tatze',
				'rules' => 'trim|required|is_natural_no_zero' 
		) 
);

$config ['editUser'] = $config ['createUser'];
$config ['editUser'] [] = array (
				'field' => 'id',
				'label' => 'user_id',
				'rules' => 'trim|required|is_natural_no_zero' 
);
$config ['editUser'] [] = array (
				'field' => 'rolle_id',
				'label' => 'Rolle für Grüne Tatze',
				'rules' => 'trim|required|is_natural_no_zero' 
		);

