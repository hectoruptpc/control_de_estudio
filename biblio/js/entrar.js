/*==========================================*/
/*=   "entrar.js" documento java script    =*/
/*=	  para el funcionamiento del login     =*/
/*=	 para que el usuario entre al sistema  =*/
/*=	   o regrese a la pagina inicial       =*/
/*==========================================*/

(function() {
	function contenido(datos, url) {
  
  $.ajax({

       type: "POST",
       url: url,
       dataType:"html",
       data: {datos:datos}


  }).done(function(resultado) {

    $('.contenido').html(resultado);
    
  })
}
$('.login').click(function(event){

	event.preventDefault();
	contenido(1, 'login.php'); 
	$('.contenido')[0].classList.add('container2');
  $('.login')[0].style.display = 'none';

});


$('.inicio').click(function(event){

	event.preventDefault();
	contenido(0, 'inicio.php');
	$('.contenido')[0].classList.remove('container2');
  $('.login')[0].style.display = 'block';

});


})();