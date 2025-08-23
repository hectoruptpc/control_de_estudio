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

if (!isEstudiante()) {
    header('location: ../usuario/home.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="es-Es" xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta charset="UTF8">
<meta http-equiv="Content-type" content="text/html; charset=UTF8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Gestión Estudiantil">
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
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #2196F3;">
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

            <!-- Icono de Mensajería con Notificación para Estudiantes -->
            <li class="nav-item nav-item-mensajes">
              <a title="Sistema de Mensajería" class="nav-link position-relative" href="mensajeria_estudiantes.php">
                <i class="fas fa-envelope fa-fw"></i> Mensajes
                <?php if ($mensajes_no_leidos > 0): ?>
                  <span class="badge badge-danger badge-notificacion">
                    <?= $mensajes_no_leidos ?>
                  </span>
                <?php endif; ?>
              </a>
            </li>

            <li id="dropdown-clases" class="nav-item dropdown">
              <a title="Mis Clases" class="nav-link dropdown-toggle" href="#" id="navbarDropdownClases" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-book fa-fw"></i> Mis Clases
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownClases">
                <a title="Ver Mis Clases" class="dropdown-item" href="mis_clases.php">
                  <i class="fas fa-list fa-fw"></i> Ver Mis Clases
                </a>
                <a title="Horario" class="dropdown-item" href="horario.php">
                  <i class="fas fa-calendar-alt fa-fw"></i> Mi Horario
                </a>
                <a title="Secciones" class="dropdown-item" href="mis_secciones.php">
                  <i class="fas fa-columns fa-fw"></i> Mis Secciones
                </a>
                <a title="Pensum Académico" class="dropdown-item" href="mi_pensum.php">
                  <i class="fas fa-graduation-cap fa-fw"></i> Mi Pensum
                </a>
              </div>
            </li>

            <li id="dropdown-calificaciones" class="nav-item dropdown">
              <a title="Mis Calificaciones" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-graduation-cap fa-fw"></i> Mis Calificaciones
              </a>
              <div id="dropdown-calificaciones-menu" class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a title="Ver Calificaciones" class="dropdown-item" href="mis_notas.php">
                      <i class="fas fa-list-ol fa-fw"></i> Ver Mis Notas
                  </a>
                  <a title="Progreso Académico" class="dropdown-item" href="progreso.php">
                      <i class="fas fa-chart-line fa-fw"></i> Progreso Académico
                  </a>
                  <a title="Reportes" class="dropdown-item" href="reportes.php">
                      <i class="fas fa-file-alt fa-fw"></i> Reportes
                  </a>
              </div>
            </li>

            <li id="dropdown-actividades" class="nav-item dropdown">
              <a title="Mis Actividades" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-tasks fa-fw"></i> Mis Actividades
              </a>
              <div id="dropdown-actividades-menu" class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a title="Tareas Pendientes" class="dropdown-item" href="tareas.php">
                      <i class="fas fa-clipboard-list fa-fw"></i> Tareas Pendientes
                  </a>
                  <a title="Exámenes Programados" class="dropdown-item" href="examenes.php">
                      <i class="fas fa-edit fa-fw"></i> Exámenes Programados
                  </a>
                  <a title="Entregas" class="dropdown-item" href="entregas.php">
                      <i class="fas fa-file-upload fa-fw"></i> Mis Entregas
                  </a>
              </div>
            </li>

            <li id="dropdown-ajustes" class="nav-item dropdown">
              <a title="Ir a Ajustes" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-cogs fa-fw"></i>  Ajustes
              </a>
              <div id="dropdown-ajus" class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a title="Perfil" class="dropdown-item" href="mi_perfil.php"><i class="fa fa-user fa-fw"></i> Mi Perfil</a>
                <a title="Mensajeria" class="dropdown-item" href="mensajeria.php"><i class="fa fa-envelope fa-fw"></i> Mensajería</a>
                <a title="Asistencias" class="dropdown-item" href="mis_asistencias.php"><i class="fas fa-calendar-check fa-fw"></i> Mis Asistencias</a>
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