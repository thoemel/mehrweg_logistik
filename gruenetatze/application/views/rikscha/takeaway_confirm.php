<?php 
include APPPATH . 'views/header.php';

echo '

<div class="page-header">
	<h1>Rikscha bei ' . $ta->name . '</h1>
</div>
				
<div class="row">
	<div class="col-xs-6">
		<h2>Grüne Tatze bringt:</h2>
		<ul class="list-group">
			<li class="list-group-item">
				<span class="badge">' . $tk_sauber_bringt . '</span>
				Transportkisten sauber
			</li>
			<li class="list-group-item">
				<span class="badge">' . $bbb_sauber_bringt . '</span>
				Bring Back Boxen sauber
			</li>
			<li class="list-group-item">
				<span class="badge">' . $depotkarten_bringt . '</span>
				Depotkarten
			</li>
		</ul>
		<h2>Grüne Tatze holt</h2>
		<ul class="list-group">
			<li class="list-group-item">
				<span class="badge">' . $tk_dreckig_ganz . '</span>
				Transportkisten gebraucht
			</li>
			<li class="list-group-item">
				<span class="badge">' . $bbb_dreckig_ganz . '</span>
				Bring Back Boxen gebraucht
			</li>
			<li class="list-group-item">
				<span class="badge">' . $bbb_defekt_mit_depot . '</span>
				Bring Back Boxen defekt mit Depotrückgabe
			</li>
			<li class="list-group-item">
				<span class="badge">' . $bbb_defekt_ohne_depot . '</span>
				Bring Back Boxen defekt ohne Depotrückgabe
			</li>
			<li class="list-group-item">
				<span class="badge">' . $tk_defekt . '</span>
				Transportkisten defekt
			</li>
			<li class="list-group-item">
				<span class="badge">' . $depotkarten_holt . '</span>
				Depotkarten
			</li>
		</ul>
	</div>		
</div>
';

echo '
<div class="row">
	<h2>Bestätigung</h2>
</div>';
echo form_open_multipart('rikscha/takeaway_speichern', 
		array('id' => 'rikscha_takeaway_confirm', 
				'role' => 'form', 
				'class' => 'form-horizontal'));
echo form_hidden('ta_id', $ta->id);
echo form_hidden('tk_sauber_bringt', $tk_sauber_bringt);
echo form_hidden('bbb_sauber_bringt', $bbb_sauber_bringt);
echo form_hidden('depotkarten_bringt', $depotkarten_bringt);
echo form_hidden('tk_dreckig_ganz', $tk_dreckig_ganz);
echo form_hidden('bbb_dreckig_ganz', $bbb_dreckig_ganz);
echo form_hidden('bbb_defekt_mit_depot', $bbb_defekt_mit_depot);
echo form_hidden('bbb_defekt_ohne_depot', $bbb_defekt_ohne_depot);
echo form_hidden('tk_defekt', $tk_defekt);
echo form_hidden('depotkarten_holt', $depotkarten_holt);
echo '<input type="hidden" name="unterschrift" id="hidden_unterschrift">';
echo '
	<div class="form-group">
		<div class="col-xs-4">
			<button type="button" class="btn btn-warning clearCanvas">Unterschrift neu</button>
		</div>
	</div>
	<div class="row">
		<!-- Width und height müssen mit den Werten im CSS übereinstimmen! -->
		<canvas id="unterschrift" class="unterschrift" width="600" height="200"></canvas>
	</div>
	<div class="form-group">
		<div class="col-xs-4">
			<button type="submit" class="btn btn-success mit_unterschrift">Bestätigen</button>
		</div>
	</div>

</form>
</div>';


include APPPATH . 'views/footer.php';