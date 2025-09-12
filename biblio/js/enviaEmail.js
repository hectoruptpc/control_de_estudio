(function() {
function select3() {  

  $('#secc').select2({

  placeholder:'Seleccione Seccion'


  });

$('#alums').select2({

  placeholder:'Seleccione Estudiantes'


  });

}
select3();

//buscar a los estudiantes
var secc=[];
var trayecto=[];
var seccion =[];//seccion que se enviara a la bd
var trayectos =[];//trayecto que se enviara a la bd
var i=0;

 $('#secc').change(function() {
  
i=0;
        // Obtener todas las opciones seleccionadas
        var selectedOptions = Array.from(this.selectedOptions);
        
        // Iterar sobre las opciones seleccionadas
        selectedOptions.forEach(option => {

            const optgroup = option.parentElement;
            const groupLabel = optgroup.label; // Obtiene el título del optgroup
            const valorGroupLabel = optgroup.getAttribute('value'); // Obtiene el título del optgroup
            const value = option.value; // Obtiene el valor de la opción seleccionada
            secc[i]=value;
            seccion[i]=value;
            trayectos[i]=valorGroupLabel;
            trayecto[i]=valorGroupLabel;
            i++
            //console.log(`Opción seleccionada: ${value}, Valor del Optgroup: ${valorGroupLabel}`);
        });

       console.log(`Secciones: ${secc}
        Trayecto: ${trayecto} `);

        $.ajax({//inicio

       type: "POST",
       url: 'alumConten.php',
       dataType:"text",
       data:{
        secc:secc,
        trayecto:trayecto
       }
       
  }).done(function(resultado) {
    
    $('#alums').html(resultado);
    while (secc.length > 0) {
    secc.shift();
    trayecto.shift(); // Elimina el primer elemento
}
   
     
  })//fin


    });


 var exito=false;
function isConnectedToInternet() {//funcion para verificar la conexion a internet
    return fetch('https://www.google.com/', { method: 'HEAD', mode: 'no-cors' })
        .then(() => {
            console.log("El dispositivo tiene acceso a Internet.");
            exito=true;
            return true;
            
        })
        .catch(() => {
            console.log("El dispositivo no tiene acceso a Internet.");
            exito=false;
            return false;
            
        });
}

// Verificar conexión a Internet
isConnectedToInternet();


//enviar mensajes

document.getElementById('form').addEventListener('submit', function(event) {
            event.preventDefault();

// Verificar conexión a Internet
isConnectedToInternet();



if(exito==true){

if ($('#secc').val() == '' || $('#alums').val() == '' || $('#cabesa').val() == '' || $('#correo').val() == '' || $('#message').val() == '') {

 Swal.fire({

                                    title:'Complete los campos',
                                    backdrop:true,
                                    background:'#FFF',
                                    allowOutsideClick:false,
                                    allowEscapeKey:false,
                                    stopKeydownPropagation:false,
                                    toast:true,
                                    position:'top',
                                    icon: 'warning'


                                    
                        
                             });

}else if($('#secc').val() !== '' && $('#alums').val() !== '' && $('#cabesa').val() !== '' && $('#correo').val() !== '' && $('#message').val() !== ''){
     
      emailjs.init("1wWTBGdfce5nuY-rw"); // Reemplaza con tu User ID

    // Obtener todas las opciones seleccionadas
        var selects = Array.from($("#alums")[0].selectedOptions);
        var j=0;
        var email=[];
        var cedula=[];
        // Iterar sobre las opciones seleccionadas
        selects.forEach(option => {

            const value = option.value; // Obtiene el valor de la opción seleccionada
            email[j]=value;
            cedula[j]=option.getAttribute('cedula');
            j++
            //console.log(`Opción seleccionada: ${value}, Valor del Optgroup: ${valorGroupLabel}`);
        });


            const emails = email.map(email => email.trim());//quita el espasiado de los emails 
            const message = document.getElementById('message').value;
            const cabesa = document.getElementById('cabesa').value;//para el encabesado
            const correo = document.getElementById('correo').value;//correo del que envia el mensaje
            
            

            emails.forEach(email => {
                const templateParams = {
                    
                    message: message,
                    to_email: emails,
                    emailjs_name:cabesa,
                    emailjs_email:correo,
                };

                emailjs.send('service_tefmp64', 'template_poj1m43', templateParams)
                    .then(function(response) {
                        
                        //alert('Éxito:', response.status, response.text);
                             
                    }, function(error) {
                        console.log('Error:', error);
                        
                    });
            });

//eviar los datos por ajax
        
var modulo=$('#modulo').val();

             $.ajax({//inicio de ajax

       type: "POST",
       url: 'registrarEmail.php',
       dataType:"text",
       data:{
        seccion:seccion,
        trayectos:trayectos,
        cedula:cedula,
        emails:emails,
        cabesa:cabesa,
        correo:correo,//este es el corredo del que envia el mensaje
        message:message,
        modulo:modulo

       }
       
  }).done(function(resultado) {
    
   
    console.log(resultado);

if (resultado== 5) {

   //si todo los datos son validos no mostrara un mensaje de
 //envió exitoso 
                  (async() => {

                      const {value:title } = await Swal.fire({

                                title:'Mensaje enviado',
                                backdrop:true,
                                allowOutsideClick:false,
                                allowEscapeKey:false,
                                stopKeydownPropagation:false,
                                toast:false,
                                icon: 'success'
                        
                            }); 

                      if (title) {


                    $('#form')[0].reset();
                    $('#secc').val('').trigger('change');
                    $('#alums').val('').trigger('change');
                    location.reload();


                      }


                    })();


                 

                    

}







     
  })//fin de ajax
        
}


}else if(exito==false){//mostrar una alerta de que no tiene conexión a internet
            
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: 'Ha ocurrido un error. Por favor, verifique su conexión a internet.',
                confirmButtonText: 'Aceptar'
            });



}

        }); 



})();