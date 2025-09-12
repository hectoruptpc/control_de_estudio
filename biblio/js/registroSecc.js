//archvo para la el registro de secciones
(function() {
	

function validar(event) {//validacion del formulario
	event.preventDefault();


var trayecto = $('#trayecto').val(),
	secc = $('#secc').val();

if (trayecto == 0 ) {

	
	
	 Swal.fire({

                                    title:'Complete el campo "Trayecto" ',
                                    backdrop:true,
                                    background:'#FFF',
                                    allowOutsideClick:false,
                                    allowEscapeKey:false,
                                    stopKeydownPropagation:false,
                                    toast:true,
                                    position:'top',
                                    icon: 'warning'


                                    
                        
                             });

	

}


if (secc == 0 ) {

	
	
	 Swal.fire({

                                    title:'Complete el campo "Sección" ',
                                    backdrop:true,
                                    background:'#FFF',
                                    allowOutsideClick:false,
                                    allowEscapeKey:false,
                                    stopKeydownPropagation:false,
                                    toast:true,
                                    position:'top',
                                    icon: 'warning'


                                    
                        
                             });

	

}

//envió de los datos del formulario a la base de datos


if (trayecto !== '' && secc !== '') {

 			 var f = $(this);
             var formData = new FormData(document.getElementById("form"));
             formData.append(f.attr("nam"), $('#modulo').val());
             formData.append(f.attr("nam"), $('#trayecto').val());
             formData.append(f.attr("name"), $('#secc').val());
             formData.append(f.attr("name"), $('#envio').val());

              $.ajax({//inicio del envio por ajax
                 url: "registroSecc.php",
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
                  Swal.fire({

                                title:'Se ha creado la sección',
                                backdrop:true,
                                allowOutsideClick:false,
                                allowEscapeKey:false,
                                stopKeydownPropagation:false,
                                toast:false,
                                icon: 'success'
                        
                            });   
                           
                        $('#form')[0].reset();  
                        $("#noVilid")[0].style.display = 'none'; 

                         
                          
 
                   }else if(res == 1){
//error al ingresar datos no validos por el formulario/login

                    $("#noVilid")[0].style.display = 'block';
 
 

                   }
 
 
 
 
 
 
 
 
 
 
 
                 });//fin del envio por ajax




}



}


var validarDatos = function(event) {

	validar(event);

}

$('#envio').click(validarDatos);


})();