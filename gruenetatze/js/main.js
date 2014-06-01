

$(document).ready(function() {
	
	// Das letzte Formularfeld mit Klasse "focusPlease" auf der Seite kriegt den Fokus.
	$('.focusPlease').last().focus();

	// Datepicker defaults
	$.datepicker.setDefaults({
		maxDate: new Date(),
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        duration: 'fast',
        hideIfNoPrevNext: true,
        showOn: 'focus',
    });
	
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
 