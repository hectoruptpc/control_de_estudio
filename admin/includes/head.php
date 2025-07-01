<?php

if (!isLoggedIn()) {
    $_SESSION['here'] = $_SERVER['REQUEST_URI'];
    $_SESSION['msg'] = $msn_iniciar_sesion;
	header('location: ../login.php');
	die();
}

if (!isAdmin()) {
    header('location: ../usuario/home.php');
}
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="es-Es" xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta charset="UTF8">
<meta http-equiv="Content-type" content="text/html; charset=UTF8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Gestion">
<meta name="author" content="Hector Marulanda">

<title><?php echo $titulopag; ?></title>

<?php echo $bootstrap_head; ?>
</head>

<body>

<div class="container">
<!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #c2d9fe;">
      <div class="container">
        <a title="Cargar Inicio" class="navbar-brand" href="index.php">
          <?php echo $logopertenencia; ?>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a title="Cargar Inicio" class="nav-link" href="index.php"><i class="fas fa-home fa-fw"></i> Inicio
                <span class="sr-only">(current)</span>
              </a>
            </li>

            <li id="dropdown-estudiantes" class="nav-item dropdown position-relative">
  <a title="Gestión de Estudiantes" class="nav-link dropdown-toggle" href="#" id="navbarDropdownEstudiantes" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-users fa-fw"></i> Estudiantes
  </a>
  <div class="dropdown-menu position-absolute" aria-labelledby="navbarDropdownEstudiantes" style="left: 0; top: 100%;">
    <a title="Ver Todos los Estudiantes" class="dropdown-item" href="estudiantes.php">
      <i class="fa fa-users fa-fw"></i> Ver todos los Estudiantes
    </a>
    <a title="Agregar Estudiante" class="dropdown-item" href="agregar_estudiante.php">
      <i class="fa fa-user-plus fa-fw"></i> Agregar Estudiante
    </a>
    <a title="Historial Académico" class="dropdown-item" href="historial_academico.php">
      <i class="fas fa-history fa-fw"></i> Historial Académico
    </a>
    <a title="Constancia de Estudio" class="dropdown-item" href="constancia_estudio.php">
      <i class="fas fa-file-certificate fa-fw"></i> Constancia de Estudio
    </a>
  </div>
</li>

<li id="dropdown-pensum" class="nav-item dropdown">
    <a title="Gestión de Pensum" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-book fa-fw"></i> Pensum
    </a>
    <div id="dropdown-pensum-menu" class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a title="Agregar Nueva Carrera" class="dropdown-item" href="agregar_carrera.php">
            <i class="fas fa-plus-circle fa-fw"></i> Agregar Carrera
        </a>
        <a title="Ver Pensum Actual" class="dropdown-item" href="lista_carreras.php">
            <i class="fas fa-graduation-cap fa-fw"></i> Ver Pensum
        </a>
        <a title="Asignaturas" class="dropdown-item" href="materia.php">
            <i class="fas fa-book-open fa-fw"></i> Asignaturas
        </a>
        <a title="Plan de Estudio" class="dropdown-item" href="carrera_materias.php">
            <i class="fas fa-calendar-alt fa-fw"></i> Plan de Estudio
        </a>
    </div>
</li>

<li id="dropdown-docentes" class="nav-item dropdown">
    <a title="Gestión de Docentes" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-chalkboard-teacher fa-fw"></i> Docentes
    </a>
    <div id="dropdown-docentes-menu" class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a title="Registrar Nuevo Docente" class="dropdown-item" href="add_docente.php">
            <i class="fas fa-user-plus fa-fw"></i> Nuevo Docente
        </a>
        <a title="Listado de Docentes" class="dropdown-item" href="list_docentes.php">
            <i class="fas fa-users fa-fw"></i> Listado Completo
        </a>
        <a title="Asignación de Cursos" class="dropdown-item" href="asignacion_cursos.php">
            <i class="fas fa-tasks fa-fw"></i> Asignar Cursos
        </a>
        <a title="Horarios Docentes" class="dropdown-item" href="horarios_docentes.php">
            <i class="fas fa-calendar-alt fa-fw"></i> Horarios
        </a>
        <a title="Evaluaciones Docentes" class="dropdown-item" href="evaluaciones_docentes.php">
            <i class="fas fa-star-half-alt fa-fw"></i> Evaluaciones
        </a>
    </div>
</li>


      </li>
      <li id="dropdown-ajustes" class="nav-item dropdown">
    <a title="Ir a Ajustes" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-cogs fa-fw"></i> Ajustes
    </a>
    <div id="dropdown-ajus" class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a title="Comentarios" class="dropdown-item" href="comentarios.php"><i class="fa fa-comments fa-fw"></i> Comentarios</a>
        <a title="Mensajeria" class="dropdown-item" href="mensajeria.php"><i class="fa fa-envelope fa-fw"></i> Mensajeria</a>
        <a title="Gestor de Contenido" class="dropdown-item" href="gestor_contenido.php"><i class="fa fa-file-contract fa-fw"></i> Contenido</a>
        <a title="Google Groups" class="dropdown-item" href="gg.php"><i class="fab fa-google-plus-g"></i> Google Groups</a>
        <a title="Creador 1" class="dropdown-item" href="test2.php"><i class="fa fa-wrench fa-fw"></i> Creador 2</a>
        <a title="Creador Mensajes" class="dropdown-item" href="cm.php"><i class="fa fa-wrench fa-fw"></i> Creador Mensajes</a>
        <a title="Reportes" class="dropdown-item" href="reportes.php"><i class="far fa-flag"></i> Reportes</a>
        <a title="Mantenimiento" class="dropdown-item" href="mantenimiento.php"><i class="fas fa-wrench"></i> Mantenimiento</a>
        
        <!-- Opción exclusiva para usuarios con editar_acceso = 1 -->
        <?php if ($_SESSION['user']['editar_acceso'] == 1): ?>
            <div class="dropdown-divider"></div>
            <a title="Editar Niveles de Acceso" class="dropdown-item" href="editar_accesos.php">
                <i class="fas fa-user-lock fa-fw"></i> Niveles de Acceso
            </a>
        <?php endif; ?>
        
        <div class="dropdown-divider"></div>
        <a title="Salir del Sistema" class="nav-link" href="../index.php?logout='1'">
            <i class="fas fa-sign-out-alt"></i> Salir
        </a>
    </div>
</li>

          </ul>
        </div>
      </div>
    </nav>
    <div class="container">
    <div class="row">
<div class="col-sm-6">
    <b class="mt-5"><?php echo 'Bienvenido ' .$_SESSION['user']['nombre']; ?></b>
    </div>

    <div class="col-sm-6">
<?php
  echo '<p class="text-right">';
  echo $fads;
  echo "<br>";
  echo $ip;
  echo "<br>";
  echo $nombrepag;
?>

</div>
</div>

</div>
</div>
