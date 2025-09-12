/*
codigo javaScropt para ver los archivos pdf
al pulsar descargar

*/

(function() {
	 

  

            var verFolio = function (url) {
              
  
              (async() => {
  
                        const {value:title } = await Swal.fire({
  
                                      html:`<link rel="stylesheet" type="text/css" href="css/alertPDF.css">
                                      <!--h2 style="color:#777; position: relative; top: -5px; z-index: 1000;">
                                      Horario de Clases</h2-->
                                      <iframe class="contenPDF" src="${url.getAttribute('href')}" title="Página 1"></iframe>
                                      `,
                                      padding:'1rem',
                                      grow:'fullscreen',
                                      allowOutsideClick:false,
                                      allowEscapeKey:false,
                                      stopKeydownPropagation:false,
                                      showCancelButton:false,
                                      showConfirmButton:false,
                                      showCloseButton:true,
                                      closeButtonAriaLabel:'boton de canselacion'
  
  
                          
                               });
  
  
                            })();
            
  
  
            }
  
 $('.verFolio').click(function(event){

event.preventDefault();
  verFolio(this);

});







})();