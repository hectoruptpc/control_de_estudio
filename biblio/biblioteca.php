<?php
//este archivo es para el contenio de los
//folios de los estudiantes 

include('conexion.php');
session_start();

?>

    <main>  
        <section id="disponibles">  
            <h2 style="text-align: center;">Folios de Graduados</h2>  

<!--buscador -->

            <div class="buscador-contenedor">  
                <form id="busLibro" class="buscador">  
                    <input id="text" type="text" placeholder="Buscar folio..." aria-label="Buscar libro">  
                    <button type="submit">Buscar</button>  
                </form>  
            </div> 

<!--listado -->




            <div url="contenlistLibro.php" id="contenedor" class="libros-contenedor">  
              
              <!--el contenido de aqui viene el archivo contenlistLibro.php--> 
            
            </div>



        </section>  

        <section id="contacto">  
            <h2>Contacto</h2>  
            <p>Para más información, contáctanos al correo: info@bibliotecamunicipal.com</p>  
        </section>  
    </main>  
    <!--librerias javaScript-->
<script src="js/sweetAlert.js"></script> 
<script src="js/jquery-3.5.1.min.js"></script> 
<script src="js/consultas.js"></script>

