<?php
include APPPATH . 'views/header.php';

echo '<div id="userForm">';
echo heading('Benutzer', 2);

echo form_open($formAction);
if ($formValues['id']) {
	echo form_hidden('id', $formValues['id']);
}
echo "<label>E-mail</label>";
echo form_error('email');
echo form_input('email', $formValues['email']) . "<br>";
if (isset($formValues['pw_new'])) {
	echo "<label>Altes Passwort</label>";
	echo form_error('pw_old');
	echo form_password('pw_old', $formValues['pw_old']) . "<br>";
	echo "<label>Neues Passwort</label>";
	echo form_error('pw_new');
	echo form_password('pw_new', $formValues['pw_new']) . "<br>";
} else {
	echo "<label>Passwort</label>";
	echo form_error('pw');
	echo form_password('pw', $formValues['pw']) . "<br>";
}
echo "<label>Rolle</label>";
echo form_error('role');
echo form_dropdown('role', $roles, $formValues['role']) . "<br>";
echo "<label>Rolle für Grüne Tatze</label>";
echo form_error('rolle_id');
echo form_dropdown('rolle_id', $rolle_ids, $formValues['rolle_id']) . "<br>";
echo form_submit('submit', 'speichern');
echo form_close();

echo '</div>';

include APPPATH . 'views/footer.php';