<?php 
include APPPATH . 'views/header.php';

echo '

<div class="page-header">
	<h1>MW-Logistik Unternehmen bei Rikscha</h1>
</div>
				
<div class="row">
	<div class="col-xs-6">
		<h2>MW-Logistik bringt:</h2>
		<ul class="list-group">
			<li class="list-group-item">
				<span class="badge">' . $tk_sauber_bringt . '</span>
				Transportkisten sauber
			</li>
			<li class="list-group-item">
				<span class="badge">' . $bbb_sauber_bringt . '</span>
				Bring Back Boxen sauber
			</li>
		</ul>
		<h2>Rikscha Kurier gibt</h2>
		<ul class="list-group">
			<li class="list-group-item">
				<span class="badge">' . $tk_dreckig_ganz . '</span>
				Transportkisten gebraucht
			</li>
			<li class="list-group-item">
				<span class="badge">' . $bbb_dreckig_ganz . '</span>
				Bring Back Boxen gebraucht
			</li>
		</ul>
	</div>		
</div>
';

echo '
<div class="row">
	<h2>Bestätigung</h2>
</div>';
echo form_open_multipart('rikscha/mw_logistik_speichern', 
		array('id' => 'rikscha_mw_logistik_confirm', 
				'role' => 'form', 
				'class' => 'form-horizontal'));
echo form_hidden('mwl_id', $mwl->id);
echo form_hidden('tk_sauber_bringt', $tk_sauber_bringt);
echo form_hidden('bbb_sauber_bringt', $bbb_sauber_bringt);
echo form_hidden('tk_dreckig_ganz', $tk_dreckig_ganz);
echo form_hidden('bbb_dreckig_ganz', $bbb_dreckig_ganz);
echo '
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<div class="checkbox">
				<label>
					' . form_checkbox('bestaetigung', '1', false) . '
					Ja, diese Angaben stimmen.
				</label>
			</div>
		</div>
	</div>
	<div class="row btn btn-warning">
		Hier fehlt noch die Unterschrift!
	</div>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" class="btn btn-default">Bestätigen</button>
		</div>
	</div>

</form>
</div>';


include APPPATH . 'views/footer.php';