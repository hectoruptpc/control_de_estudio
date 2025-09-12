(function() {
//luego de eliminar esta funcion nos dira
//que tabla nos mostrara 

var direccion=$('#contenedor')[0].getAttribute('url');//direcion de la consulta

function consult(mos,txt, url) {
 
  $.ajax({ 

       type: "POST",
       url:url,
       dataType:"html",
        data: {
       mos:mos,
        txt:txt

       },
       async:false


  }).done(function(rd) {

    if ($("#contenido_tabla").length>0) {//para que pararesca en las tablas

       $('#contenido_tabla').html(rd);
    }else{
      $('#contenedor').html(rd);//para que pararesca en un contenedor
    }
    
  });
}



 
//eliminar elementos
$(`[name='bin']`).click(function(event){

event.preventDefault();

var ruta= this.getAttribute("href");


    (async() => {

                      const {value:title } = await Swal.fire({

                                title:'¿Estás seguro de que desea eliminar este elemento?',
                                backdrop:true,
                                allowOutsideClick:false,
                                allowEscapeKey:false,
                                stopKeydownPropagation:false,
                                toast:false,
                                icon: 'question',
                                confirmButtonText:'Si',
                                showCancelButton:'true',
                                cancelButtonText:'No',
                                cancelButtonAriaLabel:'boton de canselacion',
                                



                                     customClass:{  

                                        popup:'eliminar',
                                  
                                  
                     
                                 }
                        
                             });

                             if(title){

                             

                                //window.location = ruta;

                                   $.ajax({
                                    type: "POST",
                                    url:ruta,
                                    dataType:"text",
                                    data: {
                                        id:ruta
                                    }
       

                                }).done(function(rd) {

                                    if (rd == 5) {


                                     if ($("#contenido_tabla").length>0) {//para que pararesca en las tablas

                                        $(consult($('#mos').val(),$('#buscar').val(), direccion));
                                      
                                      }else{//los libros

                                        $(consult('10000',$('#text').val(), direccion));
                                      }

                                      

                                    }


                                 })




                               

                             }


                          })();




});

})();



