//este archivo es para mantener la pantalla 
//que selecciono el usuario cuando la recargue


(function() {

//scrips de las paginas

var scripts= [
`<script src="js/jquery-3.5.1.min.js"></script>`,
`<script src="js/sweetAlert.js"></script>`,
`<script src="js/entrar.js?time=<?php echo date('s'); ?>"></script>`,
`<script src="js/pagina.js?time=<?php echo date('s'); ?>"></script> `
];



//pantalla de correos al cargar la pagina
function pagina1(url) {
  
  $.ajax({
       url: url,
       dataType:"html",
       async:false
  }).done(function(resultado) {
    const datos = resultado;
    var array = datos.split(',');
    var nav = array[0];
    var main = array[1];

    $('#main').html(main);
    //$('#nav').html(nav);

  })
}

//menu

function menu() {
  
  $.ajax({

       url: "modulo1/correos.php",
       dataType:"html",
       async:false


  }).done(function(resultado) {
    const datos = resultado;
    var array = datos.split(',');
    var nav = array[0];
 
    $('#nav').html(nav);
   
    
  })
}


//contenido del main
function contenido(url) {
  
  $.ajax({

       url: url,
       dataType:"html",
       async:false


  }).done(function(resultado) {
    
    $('#main').html(resultado);
     
  })
}

//remover selección y añadir
function SeleccionObject(objeto) {

objeto.classList.add('selecionado');
for (var i = 0; i < $('#nav a').length-1; i++) {
    if (objeto !== $('#nav a')[i]) {
      $('#nav a')[i].classList.remove('selecionado');
    }
     
  }

}



////////////
if ($('#tipoUser').length>0) {

//esto es para al recargar la pagina se mantenga en el mismo ligar que el usuario
//la dejo 
valor=$('#tipoUser')[0].getAttribute('value');

if (valor == '0') {//envió de mensajes
   menu('modulo1/correos.php?f=1');
   pagina1('modulo1/correos.php');
   SeleccionObject($('#email')[0]);

}else if (valor == '1') {//registro de sección

  menu('modulo1/correos.php');
  contenido('registroseccion.php');
  SeleccionObject($('#registro')[0]);

}else if (valor == '2') {//registro de estudiantes

  menu('modulo1/correos.php');
  contenido('FormregistroAlum.php');
  SeleccionObject($('#registroAlum')[0]);

}else if (valor == '3') {//registro de carrera

  menu('modulo1/correos.php');
  contenido('FormregistroCarr.php');
  SeleccionObject($('#registroCrr')[0]);
}



}

//cambio de paginas

$('#registro').click(function() {//registro de sección
  //menu('modulo1/correos.php?f=1');
  contenido('registroseccion.php');
  SeleccionObject(this);


});

$('#registroAlum').click(function() {//registro de estudiante

  //menu('modulo1/correos.php?f=1');
  contenido('FormregistroAlum.php');
  SeleccionObject(this);

});

$('#email').click(function() {//enviar mensajes

  pagina1('modulo1/correos.php');
  SeleccionObject(this);


});


$('#registroCrr').click(function() {//registro de carreras

  //menu('modulo1/correos.php?f=1');
  contenido('FormregistroCarr.php');
  SeleccionObject(this);

});


})();