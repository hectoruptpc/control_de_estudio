//archivo javascript para el registro
//de los administraores 
(function() {
	

function enviar(event) {
	event.preventDefault();
  var titulo='';
   if($('#conf').length > 0){
     titulo='Configuración exitosa'; 
   }else if ($('#id').val()=='') {
     titulo='Registro exitoso';                     
  }else if ($('#id').val()!=='') {
     titulo='Edición exitosa'; 
  }

             var formData = new FormData(document.getElementById("form"));
             var url = document.getElementById("form").getAttribute('url');
 
             $.ajax({//inicio del envio por ajax
                 url: url,
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

                    //si todo los datos son validos no mostrara un mensaje de
 //envió exitoso 
                  (async() => {

                      const {value:title } = await Swal.fire({

                                title:titulo,
                                backdrop:true,
                                allowOutsideClick:false,
                                allowEscapeKey:false,
                                stopKeydownPropagation:false,
                                toast:false,
                                icon: 'success'
                        
                            }); 

                      if (title) {

                         if($('#conf').length > 0){

                            window.location = 'index.php?biblioteca=1';

                        }else if ($('#id').val()=='') {
                          window.location = 'index.php?formAdmin=1'; 
                        }else{
                          window.location = 'index.php?tablaAdmin=1';  
                        }

                       


                     }  })();

                         

                   }else if(res == 1){
//error al ingresar datos no validos por el formulario/login

                    $("#noVilid")[0].style.display = 'block';
 
                    }
 

 
                 });//fin del envio por ajax



}

$('#form').submit(function (event) {
 enviar(event);
});

})();