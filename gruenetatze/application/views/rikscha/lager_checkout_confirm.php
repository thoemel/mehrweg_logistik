<?php 
include APPPATH . 'views/header.php';

echo '

<div class="page-header">
	<h1>Rikscha im Lager</h1>
</div>
				
<div class="row">
	<div class="col-xs-6">
		<p>Du hast Folgendes aus dem Lager geholt:</p>
		<ul class="list-group">
			<li class="list-group-item">
				<span class="badge">' . $tk . '</span>
				Transportkisten sauber
			</li>
			<li class="list-group-item">
				<span class="badge">' . $bbb . '</span>
				Bring Back Boxen sauber
			</li>
		</ul>
	</div>		
</div>
';

echo form_open_multipart('rikscha/lager_checkout_speichern', 
		array('id' => 'rikscha_lager_checkout', 
				'role' => 'form', 
				'class' => 'form-horizontal'));
echo form_hidden('tk', $tk);
echo form_hidden('bbb', $bbb);
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
	<div class="row">
		<div class="btn btn-warning">
			Hier fehlt noch die Unterschrift!
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" class="btn btn-default">Bestätigen</button>
		</div>
	</div>

</form>
</div>';

include APPPATH . 'views/footer.php';