<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Rikscha im Lager</h1>
	<p>
		Du hast Folgendes aus dem Lager geholt:
	</p>
';

echo form_open_multipart('rikscha/lager_checkout_speichern', 
		array('id' => 'rikscha_lager_checkout', 
				'role' => 'form', 
				'class' => 'form-horizontal'));

echo '
	<div class="form-group">
		<label for="tk_sauber_voll" class="col-sm-4 control-label">Transportkisten sauber</label>
		<div class="col-sm-2">
			' . form_input(array(
					'id' => 'tk', 
					'name' => 'tk', 
					'value' => '', 
					'class' => 'focusPlease form-control', 
					'type' => 'number')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="bbb" class="col-sm-4 control-label">Bring Back Boxen sauber</label>
		<div class="col-sm-2">
			' . form_input(array(
					'id' => 'bbb', 
					'name' => 'bbb', 
					'value' => '', 
					'class' => 'form-control', 
					'type' => 'number')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="depotkarten" class="col-sm-4 control-label">Depotkarten</label>
		<div class="col-sm-2">
			' . form_input(array(
					'id' => 'depotkarten', 
					'name' => 'depotkarten', 
					'value' => '', 
					'class' => 'form-control', 
					'type' => 'number')) . '
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-6">
			<button type="submit" class="btn btn-default">Speichern</button>
		</div>
	</div>

</form>
</div>';

include APPPATH . 'views/footer.php';