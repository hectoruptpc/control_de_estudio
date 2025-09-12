//archivo javascript para el registro
//de los estudiantes graduados y para ver 
// el contenido del select sección
(function() {
	

function enviar(event) {
	event.preventDefault();
  var titulo='';
  
  if ($('#id').val()=='') {
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

                        if ($('#id').val()=='') {
                          window.location = 'index.php?formGraduado=1'; 
                        }else{
                          window.location = 'index.php?biblioteca=1';  
                        }

                       


                     }  


                     })();

                         

                   }else if(res == 1){
//error al ingresar datos no validos por el formulario/login

                    $("#noVilid").css('display' , 'block');
                    $("#noVilid").text('!La cédula ingresada ya se encuentra registrada¡');
 
                    }else if (res == 2) {
                      $("#noVilid").css('display' , 'block');
                    $("#noVilid").text('!El folio ya esta registrado a otro nombre¡');
                    }
 

 
                 });//fin del envio por ajax



}

$('#form').submit(function (event) {
 enviar(event);
});

////////////////////////////////
//llamar tipo de titulo

$('#carr').change(function() {

var carr=this.value;
    $.ajax({ 

       type: "POST",
       url:'tipoTitulo.php',
       dataType:"html",
        data: {
        carrera:carr

       },
       async:false


  }).done(function(rd) {

   

       $('#titulo').html(rd);

       console.log(rd);
   
    
  });
 
});



})();