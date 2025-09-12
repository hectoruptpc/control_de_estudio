/*======================================*/
/*=       documento java script        =*/
/*=	 para el funcionamiento del login  =*/
/*=    y validacion de los datos       =*/
/*======================================*/


(function() {

$('#form').submit(function (event){

  event.preventDefault();


             var formData = new FormData(document.getElementById("form"));
 
             $.ajax({//inicio del envio por ajax
                 url: "validarLogin.php",
                 type: "post",
                 dataType: "text",
                 data: formData,
                 cache: false,
                 contentType: false,
              processData: false
             })
                 .done(function(res){
 
                   console.log(res);
 
                   if (res == 5) {

                       window.location = 'index.php?biblioteca=1';    

                   }else if(res == 1){
//error al ingresar datos no validos por el formulario/login

                    $("#noVilid")[0].style.display = 'block';
 
                    }
 

 
                 });//fin del envio por ajax

});


}())