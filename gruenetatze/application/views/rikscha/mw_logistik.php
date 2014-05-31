<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>MW-Logistik Unternehmen kommt ins Zwischenlager</h1>
';

echo form_open_multipart('rikscha/mw_logistik_confirm', 
		array('id' => 'rikscha_mw_logistik', 
				'role' => 'form', 
				'class' => 'form-horizontal'));
if (1 == count($mw_logistiker)) {
	// Wäscherei hardcoded, solange wir nur eine haben
	echo form_hidden('mwl_id', 6);
} else {
	// Dropdown, sobald mehrere Wäschereien
	echo '
	<div class="form-group">
		<label for="ta_id" class="col-sm-5 control-label">MW-Logistik Unternehmen</label>
		<div class="col-sm-6 col-md-4 col-lg-4">
			' . $mwl_dropdown . '
		</div>
	</div>';
}
echo '
	<h2>MW-Logistik bringt</h2>
	<div class="form-group">
		<label for="tk_sauber_bringt" class="col-sm-5 control-label">Transportkisten sauber</label>
		<div class="col-sm-2">
			' . form_input(array('id' => 'tk_sauber_bringt', 'name' => 'tk_sauber_bringt', 'value' => set_value('tk_sauber_bringt'), 'class' => 'focusPlease form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="bbb_sauber_bringt" class="col-sm-5 control-label">Bring-Back-Boxen sauber</label>
		<div class="col-sm-2">
			' . form_input(array('id' => 'bbb_sauber_bringt', 'name' => 'bbb_sauber_bringt', 'value' => set_value('bbb_sauber_bringt'), 'class' => 'form-control')) . '
		</div>
	</div>
					
	<h2>Grüne Tatze gibt</h2>
	<div class="form-group">
		<label for="tk_dreckig_ganz" class="col-sm-5 control-label">Transportkisten gebraucht</label>
		<div class="col-sm-2">
			' . form_input(array('id' => 'tk_dreckig_ganz', 'name' => 'tk_dreckig_ganz', 'value' => $tk_dreckig_ganz, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="bbb_dreckig_ganz" class="col-sm-5 control-label">Bring-Back-Boxen gebraucht</label>
		<div class="col-sm-2">
			' . form_input(array('id' => 'bbb_dreckig_ganz', 'name' => 'bbb_dreckig_ganz', 'value' => $bbb_dreckig_ganz, 'class' => 'form-control')) . '
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