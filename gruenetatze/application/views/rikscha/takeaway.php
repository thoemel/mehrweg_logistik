<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Rikscha beim Takeaway</h1>
';

echo form_open_multipart('rikscha/takeaway_confirm', 
		array('id' => 'rikscha_takeaway', 
				'role' => 'form', 
				'class' => 'form-horizontal'));

echo '
	<div class="form-group">
		<label for="ta_id" class="col-sm-5 control-label">Partner</label>
		<div class="col-sm-6 col-md-4 col-lg-4">
			' . $ta_dropdown . '
		</div>
	</div>
	<h2>Rikscha liefert</h2>
	<div class="form-group">
		<label for="tk_sauber_bringt" class="col-sm-5 control-label">Transportkisten sauber</label>
		<div class="col-sm-2">
			<input type="number" id="tk_sauber_bringt" name="tk_sauber_bringt" value="'.set_value("tk_sauber_bringt").'" class="focusPlease form-control"
		</div>
	</div>
	<div class="form-group">
		<label for="bbb_sauber_bringt" class="col-sm-5 control-label">Bring-Back-Boxen sauber</label>
		<div class="col-sm-2">
			' . form_input(array(
					'id' => 'bbb_sauber_bringt', 
					'name' => 'bbb_sauber_bringt', 
					'value' => set_value('bbb_sauber_bringt'), 
					'class' => 'form-control', 
					'type' => 'number')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="depotkarten_bringt" class="col-sm-5 control-label">Depotkarten</label>
		<div class="col-sm-2">
			' . form_input(array(
					'id' => 'depotkarten_bringt', 
					'name' => 'depotkarten_bringt', 
					'value' => set_value('depotkarten_bringt'), 
					'class' => 'form-control', 
					'type' => 'number')) . '
		</div>
	</div>
					
	<h2>Rikscha holt</h2>
	<div class="form-group">
		<label for="tk_dreckig_ganz" class="col-sm-5 control-label">Transportkisten gebraucht</label>
		<div class="col-sm-2">
			' . form_input(array(
					'id' => 'tk_dreckig_ganz', 
					'name' => 'tk_dreckig_ganz', 
					'value' => set_value('tk_dreckig_ganz'), 
					'class' => 'form-control', 
					'type' => 'number')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="bbb_dreckig_ganz" class="col-sm-5 control-label">Bring-Back-Boxen gebraucht</label>
		<div class="col-sm-2">
			' . form_input(array(
					'id' => 'bbb_dreckig_ganz', 
					'name' => 'bbb_dreckig_ganz', 
					'value' => set_value('bbb_dreckig_ganz'), 
					'class' => 'form-control', 
					'type' => 'number')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="tk_defekt" class="col-sm-5 control-label">Transportkisten defekt</label>
		<div class="col-sm-2">
			' . form_input(array(
					'id' => 'tk_defekt', 
					'name' => 'tk_defekt', 
					'value' => set_value('tk_defekt'), 
					'class' => 'form-control', 
					'type' => 'number')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="bbb_defekt_mit_depot" class="col-sm-5 control-label">BBB defekt mit Depotrückgabe</label>
		<div class="col-sm-2">
			' . form_input(array(
					'id' => 'bbb_defekt_mit_depot', 
					'name' => 'bbb_defekt_mit_depot', 
					'value' => set_value('bbb_defekt_mit_depot'), 
					'class' => 'form-control', 
					'type' => 'number')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="bbb_defekt_ohne_depot" class="col-sm-5 control-label">BBB defekt ohne Depotrückgabe</label>
		<div class="col-sm-2">
			' . form_input(array(
					'id' => 'bbb_defekt_ohne_depot', 
					'name' => 'bbb_defekt_ohne_depot', 
					'value' => set_value('bbb_defekt_ohne_depot'), 
					'class' => 'form-control', 
					'type' => 'number')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="depotkarten_holt" class="col-sm-5 control-label">Depotkarten</label>
		<div class="col-sm-2">
			' . form_input(array(
					'id' => 'depotkarten_holt', 
					'name' => 'depotkarten_holt', 
					'value' => set_value('depotkarten_holt'), 
					'class' => 'form-control', 
					'type' => 'number')) . '
		</div>
	</div>
					

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" class="btn btn-default">Weiter</button>
		</div>
	</div>

</form>
</div>';

include APPPATH . 'views/footer.php';