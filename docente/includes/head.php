<?php
// Función para contar mensajes no leídos
function contarMensajesNoLeidos($user_id) {
    global $db;
    
    $query = "SELECT COUNT(*) as total 
              FROM mensajeria 
              WHERE id_usuario_destinatario = ? 
              AND leido = FALSE 
              AND eliminado_destinatario = FALSE";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['total'];
}

// Contar mensajes no leídos para el usuario actual
$mensajes_no_leidos = 0;
if (isset($_SESSION['user']['id'])) {
    $mensajes_no_leidos = contarMensajesNoLeidos($_SESSION['user']['id']);
}

if (!isLoggedIn()) {
    $_SESSION['here'] = $_SERVER['REQUEST_URI'];
    $_SESSION['msg'] = $msn_iniciar_sesion;
    header('location: ../login.php');
    die();
}

if (!isDocente()) {
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
<meta name="author" content="Jose Herrera">

<title><?php echo $titulopag; ?></title>

<?php echo $bootstrap_head; ?>
<style>
    .nav-item-mensajes {
        position: relative;
    }
    .badge-notificacion {
        position: absolute;
        top: 3px;
        right: 3px;
        font-size: 0.6em;
        padding: 3px 6px;
    }
</style>
</head>

<body>

<div class="container">
<!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #4CAF50;">
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

            <!-- Icono de Mensajería con Notificación para Docentes -->
            <li class="nav-item nav-item-mensajes">
              <a title="Sistema de Mensajería" class="nav-link position-relative" href="mensajeria.php">
                <i class="fas fa-envelope fa-fw"></i> Mensajes
                <?php if ($mensajes_no_leidos > 0): ?>
                  <span class="badge badge-danger badge-notificacion">
                    <?= $mensajes_no_leidos ?>
                  </span>
                <?php endif; ?>
              </a>
            </li>

            <li id="dropdown-estudiantes" class="nav-item dropdown position-relative">
              <a title="Gestión de Estudiantes" class="nav-link dropdown-toggle" href="#" id="navbarDropdownEstudiantes" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-users fa-fw"></i> Estudiantes
              </a>
              <div class="dropdown-menu position-absolute" aria-labelledby="navbarDropdownEstudiantes" style="left: 0; top: 100%;">
                <a title="Ver Mis Estudiantes" class="dropdown-item" href="mis_estudiantes.php">
                  <i class="fa fa-users fa-fw"></i> Mis Estudiantes
                </a>
                <a title="Calificaciones" class="dropdown-item" href="calificaciones.php">
                  <i class="fas fa-graduation-cap fa-fw"></i> Calificaciones
                </a>
                <a title="Asistencia" class="dropdown-item" href="asistencia.php">
                  <i class="fas fa-calendar-check fa-fw"></i> Asistencia
                </a>
              </div>
            </li>

            <li id="dropdown-cursos" class="nav-item dropdown">
              <a title="Gestión de Cursos" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-book fa-fw"></i> Mis Cursos
              </a>
              <div id="dropdown-cursos-menu" class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a title="Ver Mis Cursos" class="dropdown-item" href="mis_cursos.php">
                      <i class="fas fa-list fa-fw"></i> Ver Mis Cursos
                  </a>
                  <a title="Material Didáctico" class="dropdown-item" href="material_didactico.php">
                      <i class="fas fa-file-alt fa-fw"></i> Material Didáctico
                  </a>
                  <a title="Planificación" class="dropdown-item" href="planificacion.php">
                      <i class="fas fa-tasks fa-fw"></i> Planificación
                  </a>
              </div>
            </li>

            <li id="dropdown-evaluaciones" class="nav-item dropdown">
              <a title="Gestión de Evaluaciones" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-clipboard-check fa-fw"></i> Notas
              </a>
              <div id="dropdown-evaluaciones-menu" class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a title="Crear Evaluación" class="dropdown-item" href="notas.php">
                      <i class="fas fa-plus-circle fa-fw"></i> Cargar Notas
                  </a>
                  <a title="Lista de Evaluaciones" class="dropdown-item" href="lista_evaluaciones.php">
                      <i class="fas fa-list-ol fa-fw"></i> Lista de Evaluaciones
                  </a>
                  <a title="Resultados" class="dropdown-item" href="resultados_evaluaciones.php">
                      <i class="fas fa-chart-bar fa-fw"></i> Resultados
                  </a>
              </div>
            </li>

            <li id="dropdown-ajustes" class="nav-item dropdown">
              <a title="Ir a Ajustes" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-cogs fa-fw"></i>  Ajustes
              </a>
              <div id="dropdown-ajus" class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a title="Perfil" class="dropdown-item" href="perfil.php"><i class="fa fa-user fa-fw"></i> Mi Perfil</a>
                
                <a title="Horario" class="dropdown-item" href="mi_horario.php"><i class="fas fa-calendar-alt fa-fw"></i> Mi Horario</a>
                <div class="dropdown-divider"></div>
                <a title="Salir del Sistema" class="nav-link" href="../index.php?logout='1'"><i class="fas fa-sign-out-alt"></i> Salir</a>
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

<!-- Script para actualizar notificaciones cada 30 segundos -->
<script>
function actualizarNotificaciones() {
    fetch('../funciones/contar_mensajes_no_leidos.php')
        .then(response => response.json())
        .then(data => {
            const link = document.querySelector('.nav-link[href="mensajeria.php"]');
            const badge = link.querySelector('.badge-notificacion');
            
            if (data.mensajes_no_leidos > 0) {
                if (badge) {
                    badge.textContent = data.mensajes_no_leidos;
                } else {
                    // Crear el badge si no existe
                    const newBadge = document.createElement('span');
                    newBadge.className = 'badge badge-danger badge-notificacion';
                    newBadge.textContent = data.mensajes_no_leidos;
                    link.appendChild(newBadge);
                }
            } else {
                // Eliminar el badge si no hay mensajes
                if (badge) {
                    badge.remove();
                }
            }
        })
        .catch(error => console.error('Error:', error));
}

// Actualizar cada 30 segundos
setInterval(actualizarNotificaciones, 30000);

// Actualizar también al cargar la página
document.addEventListener('DOMContentLoaded', actualizarNotificaciones);
</script>

</body>
</html>