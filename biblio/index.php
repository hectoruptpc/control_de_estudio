<?php
include('conexion.php');
error_reporting(0);
session_start();


?>

<!DOCTYPE html>  
<html lang="es">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Sistema de registro de proyectos socio tecnologicos</title>  
   
    <link rel="stylesheet" href="css/styles.css"> 
     <link rel="stylesheet" href="css/bootstrap.min.css"> 
    <link rel="stylesheet" href="css/datatables.min.css">
    <link rel="stylesheet" href="css/tabla.css"> 
    <link rel="stylesheet" href="iconos/icon.css"> 
    <link rel="stylesheet" href="css/pago.css"> 

    <!--librerias javaScript-->
    <script src="js/sweetAlert.js"></script> 
    <script src="js/jquery-3.5.1.min.js"></script> 

    
</head>  


<body>
<!--este nos contine la imagen del menbrete-->  
 <div class="img"></div>  


        <header>  
        <nav class="navbar2">
        	<div class="cabesera">

        	<img src="img/logo.png" width="150px" height="150px"/> 
        	<?php if (isset($_SESSION['tipoUser'])): ?>
        	<!--esto solo se mostrara si se a iniciado sesión-->	
            <h1>Sistema de registro de proyectos socio tecnologicos</h1>
            <?php endif ?> 
            
            <?php if (!isset($_SESSION['tipoUser'])): ?>
            <!--esto solo se mostrara si no se a iniciado sesión-->	
             <h1>Sistema de registro de proyectos socio tecnologicos</h1>
            <?php endif ?> 
            
            </div> 
            
             <?php if (isset($_SESSION['tipoUser'])): ?>

              <div class="menu" id="menu"><i class="icon icon-menu"></i></div>
        	<!--esto solo se mostrara si se a iniciado sesión-->	
             <ul for="menu" class="nav-list"> 
          
               
                <li><a href="index.php?biblioteca=1">Inicio</a></li> 
                <li><a href="#">Registros <i class="icon icon-arrow-down3"></i></a>
                	<ul>

                    <?php if ($_SESSION['tipoUser'] =='1'): ?>
                      <!--esto solo hara el administrador padre-->
                      <li><a href="index.php?formAdmin=1">Usuario</a></li> 
                      <li><a href="index.php?formCarr=1">Carrera</a></li> 
                    <?php endif ?> 

                
                
                <li><a href="index.php?formGraduado=1">Graduado</a></li> 
                		
                	</ul>
                </li>  
               
              

                
              <li><a href="#">Consultas <i class="icon icon-arrow-down3"></i></a>
                  <ul>

                

                <?php if ($_SESSION['tipoUser'] =='1'): ?>
                 
                  <!--esto solo hara el administrador padre-->
               	<li><a href="index.php?tablaAdmin=1">Usuarios</a></li>
                <li><a href="index.php?tablaCarr=1">Carreras</a></li>
                
                <?php endif ?> 

                
                 
                  </ul>
               </li>  
                

                <li><a href="#">Reportes <i class="icon icon-arrow-down3"></i></a>
                  <ul>

                    <li><a href="index.php?tablaReporAlum=1">Reportes de Graduados</a></li>
                    <li><a href="index.php?tablaReporActo=1">Reportes de Tipo de Acto</a></li>

                  </ul>

                </li>
                

               

               	

               	<li><a href="#">Gestión <i class="icon icon-arrow-down3"></i></a>
               		<ul>
               			<li><a href="index.php?confiAdmin=1">Configuración de Administrador</a></li>
               			 
               		</ul>
               		

               	</li>
                
                <li><a href="salir.php">Salir</a></li>   
            
            </ul> 
            <?php endif ?> 
            
        </nav>  
    </header>   

    <?php

    if (!isset($_SESSION['tipoUser'])) { //si no se a iniciado sesión se nos mostrara el login
	include('login.php');
	
	}


	if (isset($_SESSION['tipoUser'])) { //si se a iniciado sesión se nos mostrara el contenido principal del sistema

		if (isset($_GET['biblioteca'])) {//listado de los folios de los estudiantes
			
      include('biblioteca.php');//pantalla principal

		}else if (isset($_GET['formGraduado'])) {//registro de folios

			include('formGraduado.php');
		
    }else if (isset($_GET['formCarr'])) {//registro de carreras

      include('formCarr.php');

    }else if (isset($_GET['formAdmin'])) {//registro de administradores

      include('formAdmin.php');
      
      //consultas
    }else if (isset($_GET['tablaCarr'])) {//consulta de carreras

      include('tablaCarr.php');

    }else if (isset($_GET['tablaAdmin'])) {//consulta de administradores

      include('tablaAdmin.php');
    
    }else if(isset($_GET['confiAdmin'])){//cofiguracion del administrador

       include('confiAdmin.php');

       
  }else if(isset($_GET['tablaReporAlum'])){//reporte de graduados

       include('tablaReporAlum.php');

       
  }if(isset($_GET['tablaReporActo'])){//reporte de tipos de actos de graduados

     include('tablaReporActo.php');

  }else{

      //include('biblioteca.php');//patalla principal
    } 



	}

    ?>

    <footer>  
        <p>&copy; 2024 Sistema de registro de libro de actas. Todos los derechos reservados.</p>  
    </footer>  

<script src="js/menu.js"></script>

</body>  
</html>

