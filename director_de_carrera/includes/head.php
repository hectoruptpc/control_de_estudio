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

if (!isUser()) {
    header('location: ../usuario/home.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="es-Es" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF8">
<meta http-equiv="Content-type" content="text/html; charset=UTF8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Gestión Académica">
<meta name="author" content="Sistema de Gestión">
<title><?php echo $titulopag; ?></title>
<?php echo $bootstrap_head;?>
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
    .navbar {
        background-color: #fd7e14 !important;
    }
    .navbar-brand, .nav-link {
        color: white !important;
    }
    .dropdown-menu {
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
    .dropdown-item:hover {
        background-color: #fff5eb;
    }
</style>
</head>
<body>
<div class="container">
<!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #fd7e14;">
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

            <!-- Icono de Mensajería con Notificación -->
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

            <!-- Menú de Asignación de Docentes -->
            <li class="nav-item">
              <a title="Asignar Docente al Programa" class="nav-link" href="asignar_docente.php">
                <i class="fas fa-chalkboard-teacher fa-fw"></i> Asignar Docente
              </a>
            </li>

            <!-- Menú de Gestión Académica -->
            <li id="dropdown-academico" class="nav-item dropdown">
                <a title="Gestión Académica" class="nav-link dropdown-toggle" href="#" id="navbarDropdownAcademico" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-book fa-fw"></i> Académico
                </a>
                <div id="dropdown-academico-menu" class="dropdown-menu" aria-labelledby="navbarDropdownAcademico">
                    <a title="Programas Académicos" class="dropdown-item" href="programas_academicos.php">
                        <i class="fas fa-graduation-cap fa-fw"></i> Programas
                    </a>
                    <a title="Planificación Curricular" class="dropdown-item" href="planificacion_curricular.php">
                        <i class="fas fa-calendar-alt fa-fw"></i> Planificación
                    </a>
                </div>
            </li>

            <!-- Menú de Reportes -->
            <li id="dropdown-reportes" class="nav-item dropdown">
                <a title="Reportes y Estadísticas" class="nav-link dropdown-toggle" href="#" id="navbarDropdownReportes" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-chart-line fa-fw"></i> Reportes
                </a>
                <div id="dropdown-reportes-menu" class="dropdown-menu" aria-labelledby="navbarDropdownReportes">
                    <a title="Estadísticas de Estudiantes" class="dropdown-item" href="estadisticas_estudiantes.php">
                        <i class="fas fa-user-graduate fa-fw"></i> Estadísticas Estudiantiles
                    </a>
                    <a title="Rendimiento Académico" class="dropdown-item" href="rendimiento_academico.php">
                        <i class="fas fa-chart-bar fa-fw"></i> Rendimiento Académico
                    </a>
                </div>
            </li>

            <!-- Menú de Ajustes -->
            <li id="dropdown-ajustes" class="nav-item dropdown">
                <a title="Ir a Ajustes" class="nav-link dropdown-toggle" href="#" id="navbarDropdownAjustes" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-cogs fa-fw"></i> Ajustes
                </a>
                <div id="dropdown-ajustes-menu" class="dropdown-menu" aria-labelledby="navbarDropdownAjustes">
                    <a title="Mi Perfil" class="dropdown-item" href="mi_perfil.php">
                        <i class="fas fa-user fa-fw"></i> Mi Perfil
                    </a>
                    <div class="dropdown-divider"></div>
                    <a title="Salir del Sistema" class="dropdown-item" href="../index.php?logout='1'">
                        <i class="fas fa-sign-out-alt fa-fw"></i> Salir
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