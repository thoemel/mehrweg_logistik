<?php 
include APPPATH . 'views/header.php';

echo '
<div class="page-header">
	<h1>Neues Material einfliegen</h1>
	<p>
		Hier trägst du ein, wenn neues Material produziert wurde und ins System 
		aufgenommen wird. Das System geht davon aus, dass neue Transportkisten und 
		Bring Back Boxen direkt zum Lager beim MW-Logistik-Unternehmen gehen.<br>
		Neue Depotkarten gehen ins Zwischenlager beim Rikscha-Kurier.
		<div class="alert alert-warning">
			Falls das nicht der Fall ist, müssen manuell Einträge in die Datenbank
			gemacht werden!
		</div>
	</p>
</div>';

echo form_open_multipart('pl/neues_material_speichern',
		array('id' => 'neues_material_speichern',
				'role' => 'form',
				'class' => 'form-horizontal'));

echo '
	<div class="form-group">
		<label for="tk" class="col-xs-4 control-label">Transportkisten</label>
		<div class="col-xs-2">
			' . form_input(array('id' => 'tk', 'name' => 'tk', 'value' => '', 'class' => 'focusPlease form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="bbb" class="col-xs-4 control-label">Bring Back Boxen</label>
		<div class="col-xs-2">
			' . form_input(array('id' => 'bbb', 'name' => 'bbb', 'value' => '', 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="depotkarten" class="col-xs-4 control-label">Depotkarten</label>
		<div class="col-xs-2">
			' . form_input(array('id' => 'depotkarten', 'name' => 'depotkarten', 'value' => '', 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" class="btn btn-default">Speichern</button>
		</div>
	</div>

</form>';


include APPPATH . 'views/footer.php';