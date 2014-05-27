<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Rikscha im Lager</h1>
	<p>
		Die Rikscha bringt Ware ins Lager.
	</p>
';

echo form_open_multipart('rikscha/lager_checkin_speichern', 
		array('id' => 'rikscha_lager_checkin', 
				'role' => 'form', 
				'class' => 'form-horizontal'));

echo '
	<table class="table table-striped table-bordered table-condensed">
		<thead>
		<tr>
			<th scope="col">Ware</th>
			<th scope="col">Anzahl</th>
		</tr>
		</thead>
		<tbody>';
foreach ($bestand as $row) {
	echo '
		<tr>
			<td>' . $row->ware . '</td>
			<td>' . $row->anzahl . '</td>
		</tr>';
}
echo '
		</tbody>
	</table>
	
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
							
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" class="btn btn-default">Speichern</button>
		</div>
	</div>

</form>
</div>';

include APPPATH . 'views/footer.php';