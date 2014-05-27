<?php
include APPPATH . 'views/header.php';

echo '<h1>Benutzer-Administration</h1>';

echo '<div id="registeredUsers" class="side">';
echo heading('Registrierte Benutzer', 2);

echo '<div>';
echo anchor('admin/userForm', '&nbsp;', array('title' => 'Neuen Benutzer anlegen', 'class' => 'glyphicon glyphicon-plus-sign'));
echo anchor('admin/userForm', 'Neuen Benutzer anlegen');
echo '</div>';

echo '</div>
		
	<div class="main">';

if (count($registeredUsers) > 0) {
	echo '<ul>';
    foreach ($registeredUsers as $user) {
        echo '<li>';
        echo anchor('admin/switchToUser/' . $user->id, 
                    '&nbsp;',
					array('title' => 'als '.$user->email.' einloggen', 'class' => 'glyphicon glyphicon-share'));
        echo '&nbsp;&nbsp;';
        echo anchor('admin/userForm/' . $user->id, 
                    '&nbsp;',
        			array('title' => 'editieren', 'class' => 'glyphicon glyphicon-edit'));
        echo '&nbsp;&nbsp;';
        echo anchor('admin/deleteUser/' . $user->id, 
                    '&nbsp;',
                    array('title' => 'Benutzer löschen', 'class' => 'glyphicon glyphicon-minus-sign', 'onclick' => 'return window.confirm(\'Willst Du den Benuztzer wirklich löschen?\');'));
        echo '&nbsp;&nbsp;';
        echo $user->email . ' (' . $user->firma_name . ')';
        echo '</li>';
    }
    echo '</ul>';
}

echo '<div>';
echo anchor('admin/userForm', '&nbsp;', array('title' => 'Neuen Benutzer anlegen', 'class' => 'glyphicon glyphicon-plus-sign'));
echo anchor('admin/userForm', 'Neuen Benutzer anlegen');
echo '</div>';

echo '</div>';

include APPPATH . 'views/footer.php';