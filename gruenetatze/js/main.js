

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
	
	// Rechnung löschen bestätigen
	$('#rechnung_loeschen').on('click', rechnung_loeschen_bestaetigen);
});
 

/**
 * Zeigt einen Modal-Dialog, um das Löschen einer Rechnung zu bestätigen
 * @param event
 */
function rechnung_loeschen_bestaetigen(event) {
	event.preventDefault();
	$('<div></div>').appendTo('body')
	  .html('<div><p>Wirklich löschen?</p></div>')
	  .dialog({
	      modal: true, title: 'Rechnung löschen', zIndex: 10000, autoOpen: true,
	      width: '200px', resizable: false,
	      buttons: {
	          Ja: function () {
	        	  location.assign($('#rechnung_loeschen').attr('href'));
	              $(this).dialog("close");
	          },
	          Nein: function () {
	              $(this).dialog("close");
	          }
	      },
	      close: function (event, ui) {
	          $(this).remove();
	      }
	});
}