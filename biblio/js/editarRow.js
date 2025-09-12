(function() {


//editar elementos
$(`.editar`).click(function(event){

event.preventDefault();

var ruta= this.getAttribute("href");


    (async() => {

                      const {value:title } = await Swal.fire({

                                title:'¿Estás seguro de que desea editar este elemento?',
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

                             

                                window.location = ruta;
   

                             }


                          })();




});

})();



