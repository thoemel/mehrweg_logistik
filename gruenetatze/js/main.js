// provision
var provision = 0;

$(document).ready(function() {
	
	// Das letzte Formularfeld mit Klasse "focusPlease" auf der Seite kriegt den Fokus.
	$('.focusPlease').last().focus();

	// Datepicker auf default-Feldern
	$('.hat-dp').datepicker();
	
	/*
	 * Layer einblenden, wannimmer ein Formular abgeschickt wird.
	 * Dies damit nicht ein zweites mal gescannt wird, 
	 * solange der erste Request noch nicht fertig ist.
	 */
	$('form').on('submit', function() {
		$('#confirmation').show();
	});
});
 