/**
 * Digitale Unterschrift
 */
$(document).ready(function () {
    initialize();
    
    $(".mit_unterschrift").on('click', function() {
    	var image = document.getElementById("unterschrift").toDataURL("image/png");
    	$("#hidden_unterschrift").val(image);
    });
 });

 // Umrechnung der X, Y Position im Bezug zum Bildschirm auf Koordinaten innerhalb des Canvas-Containers
 function getPosition(mouseEvent, signatureCanvas) {
    var x, y;
    if (mouseEvent.pageX != undefined && mouseEvent.pageY != undefined) {
       x = mouseEvent.pageX;
       y = mouseEvent.pageY;
    } else {
       x = mouseEvent.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
       y = mouseEvent.clientY + document.body.scrollTop + document.documentElement.scrollTop;
    }

    return { X: x - signatureCanvas.offsetLeft, Y: y - signatureCanvas.offsetTop };
 }

 function initialize() {
   // initialisieren des Canvaselements
    var signatureCanvas = document.getElementById("unterschrift");
    var context = signatureCanvas.getContext("2d");
    context.strokeStyle = 'Black';   // Linienfarbe - Schwarz

    // Überprüfung ob  TOUCHSCREEN ( iPad, Android, ... )
    var is_touch_device = 'ontouchstart' in document.documentElement;

    if (is_touch_device) {
       //   TOUCHSCREEN ja - Tracker wird erzeugt
       var drawer = {
          isDrawing: false,
          touchstart: function (coors) {
             context.beginPath();
             context.moveTo(coors.x, coors.y);
             this.isDrawing = true;
          },
          touchmove: function (coors) {
             if (this.isDrawing) {
                context.lineTo(coors.x, coors.y);
                context.stroke();
             }
          },
          touchend: function (coors) {
             if (this.isDrawing) {
                this.touchmove(coors);
                this.isDrawing = false;
             }
          }
       };

       // Funktion gibt touch-ereignisse an den Tracker ( drawer ) weiter
       function draw(event) {

          // Koordinaten des Fingers in Bezug zum body Element
          var coors = {
             x: event.targetTouches[0].pageX,
             y: event.targetTouches[0].pageY
          };

          // Abstand des Canvaselements zum Elternelement (body)
          var obj = signatureCanvas;

          if (obj.offsetParent) {
             // Errechnen der Fingerkoordinaten innerhalb des Canvaselements
             do {
                coors.x -= obj.offsetLeft;
                coors.y -= obj.offsetTop;
             }
			  
             while ((obj = obj.offsetParent) != null);
          }

          // übergabe der Koordinaten an den Tracker
          drawer[event.type](coors);
       }


       // Event listener für Beginn Berührung Bewegung und Ende Berührung 
       signatureCanvas.addEventListener('touchstart', draw, false);
       signatureCanvas.addEventListener('touchmove', draw, false);
       signatureCanvas.addEventListener('touchend', draw, false);

       // Scrollen der HTML Seite verhindern wenn der Finer im Canvaselement bewegt wird 
       signatureCanvas.addEventListener('touchmove', function (event) {
          event.preventDefault();
       }, false); 
    }
    else {
       // Fallback für Desktops - Zeichnen mit der Maus erlauben
       // Start der Zeichnung mit dem Event mousedown 
       
       $("#unterschrift").mousedown(function (mouseEvent) {
          var position = getPosition(mouseEvent, signatureCanvas);

          context.moveTo(position.X, position.Y);
          context.beginPath();

          // Zeichnen der Linie bis Event mouseup oder Verlassen des Canvaselements mit dem Cursors (mouseout)
          $(this).mousemove(function (mouseEvent) {
             drawLine(mouseEvent, signatureCanvas, context);
          }).mouseup(function (mouseEvent) {
             finishDrawing(mouseEvent, signatureCanvas, context);
          }).mouseout(function (mouseEvent) {
             finishDrawing(mouseEvent, signatureCanvas, context);
          });
       });

    }
 }

 // Zeichnen der Linie zu den jeweiligen x y Koordinaten
 function drawLine(mouseEvent, signatureCanvas, context) {

    var position = getPosition(mouseEvent, signatureCanvas);

    context.lineTo(position.X, position.Y);
    context.stroke();
 }

 // Funktion zum Beenden Der Linie 
 function finishDrawing(mouseEvent, signatureCanvas, context) {
    // Zeichnen der Linie zu den letzten Koordinaten
    drawLine(mouseEvent, signatureCanvas, context);

    context.closePath();

    // Maus Events abschalten
    $(signatureCanvas).unbind("mousemove")
                .unbind("mouseup")
                .unbind("mouseout");
 }
