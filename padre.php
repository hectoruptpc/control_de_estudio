<!doctype html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <title>PADRE</title>

</head>
<body>
    <iframe src="iframe.html"></iframe>
<br>
<div id="data2">
   <input type="text" size="20">
</div>




<script>
    var mensaje = document.querySelector(".data2");

    var recibirMensaje = function( evento ){
      if (evento.origin == "https://virtual.jesuministrosymas.com.ve"){
        mensaje.innerHTML = "Emisor: " + evento.data;

        // Aquí evento.source es una referencia al objeto window del emisor.
        // evento.origin contiene el origen del emisor: "http://emisor.com"
        evento.source.postMessage("iPad Pepito, por que solo iPad es «too mainstream».", evento.origin);
      }

    };

    window.addEventListener("message", recibirMensaje, false);
  </script>





<?php
function generateRandomString($A) {

return substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $A);

}
$generado = generateRandomString(50);
echo $generado;

 ?>
  </body>
</html>
