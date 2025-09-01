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
    .user-info {
        background-color: #f8f9fa;
        border-left: 4px solid #007bff;
    }
</style>
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

            <!-- Icono de Mensajería con Notificación - POSICIÓN CORREGIDA -->
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
                <a title="Ver Todos los Estudiantes" class="dropdown-item" href="estudiantes.php">
                  <i class="fa fa-users fa-fw"></i> Ver todos los Estudiantes
                </a>
                <?php if ($_SESSION['user']['agregar_estudiante'] == 1): ?>
                  <a title="Agregar Estudiante" class="dropdown-item" href="agregar_estudiante.php">
                    <i class="fa fa-user-plus fa-fw"></i> Agregar Estudiante
                  </a>
                <?php endif; ?>
                <a title="Gestionar Secciones" class="dropdown-item" href="gestion_seccion.php">
                  <i class="fas fa-object-group fa-fw"></i> Gestionar Secciones
                </a>
                
              </div>
            </li>

            <li id="dropdown-pensum" class="nav-item dropdown">
                <a title="Gestión de Pensum" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-book fa-fw"></i> Pensum
                </a>
                <div id="dropdown-pensum-menu" class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php if ($_SESSION['user']['agregar_carrera'] == 1): ?>
                        <a title="Agregar Nueva Carrera" class="dropdown-item" href="agregar_carrera.php">
                            <i class="fas fa-plus-circle fa-fw"></i> Agregar Carrera
                        </a>
                    <?php endif; ?>
                    
                    <a title="Asignaturas" class="dropdown-item" href="materia.php">
                        <i class="fas fa-book-open fa-fw"></i> Asignaturas
                    </a>
                    <a title="Relacionar Materias con Carreras" class="dropdown-item" href="carrera_materias.php">
                        <i class="fas fa-link fa-fw"></i> Relacionar Materias-Carreras
                    </a>
                    <a title="Periodos Académicos" class="dropdown-item" href="periodos_academicos.php">
                        <i class="fas fa-calendar fa-fw"></i> Periodos Académicos
                    </a>
                </div>
            </li>

            <li id="dropdown-docentes" class="nav-item dropdown">
                <a title="Gestión de Docentes" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-chalkboard-teacher fa-fw"></i> Docentes
                </a>
                <div id="dropdown-docentes-menu" class="dropdown-menu" aria-labelledby="navbarDropdown">
                  
                <a title="Registrar Nuevo Docente" class="dropdown-item" href="add_docente.php">
                    <i class="fas fa-user-plus fa-fw"></i> Gestionar Docente
                </a>

                <!-- Nueva opción: Asignar Secciones -->
                <a title="Asignación de Secciones" class="dropdown-item" href="asignar_secciones.php">
                    <i class="fas fa-object-group fa-fw"></i> Asignar Secciones
                </a>
                   
                <a title="Asignación de Cursos" class="dropdown-item" href="asignacion_cursos.php">
                    <i class="fas fa-tasks fa-fw"></i> Asignar Cursos
                </a>
                <a title="Horarios Docentes" class="dropdown-item" href="horarios_docentes.php">
                    <i class="fas fa-calendar-alt fa-fw"></i> Horarios
                </a>
                
                </div>
            </li>

            <li id="dropdown-notas" class="nav-item dropdown">
                <a title="Gestión de Notas" class="nav-link dropdown-toggle" href="#" id="navbarDropdownNotas" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-graduation-cap fa-fw"></i> Notas
                </a>
                <div id="dropdown-notas-menu" class="dropdown-menu" aria-labelledby="navbarDropdownNotas">
                    <a title="Registrar Notas" class="dropdown-item" href="admin_notas_pendientes.php">
                        <i class="fas fa-edit fa-fw"></i> Notas Cargadas
                    </a>
                    <a title="Consultar Notas" class="dropdown-item" href="consulta_notas.php">
                        <i class="fas fa-search fa-fw"></i> Consultar Notas
                    </a>
                    
                </div>
            </li>

            <li id="dropdown-ajustes" class="nav-item dropdown">
                <a title="Ir a Ajustes" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-cogs fa-fw"></i> Ajustes
                </a>
                <div id="dropdown-ajus" class="dropdown-menu" aria-labelledby="navbarDropdown">
                    
                    
                    <!-- Nuevo apartado de Títulos y Relaciones con Materias -->
                    <div class="dropdown-divider"></div>
                    <a title="Títulos y Relaciones con Materias" class="dropdown-item" href="titulos_relaciones_materias.php">
                        <i class="fas fa-graduation-cap fa-fw"></i> Títulos y Relaciones con Materias
                    </a>
                    
                   
                    
                    <!-- Opción exclusiva para usuarios con editar_acceso = 1 -->
                    <?php if ($_SESSION['user']['editar_acceso'] == 1): ?>
                        <div class="dropdown-divider"></div>
                        <a title="Editar Niveles de Acceso" class="dropdown-item" href="editar_accesos.php">
                            <i class="fas fa-user-lock fa-fw"></i> Niveles de Acceso
                        </a>
                    <?php endif; ?>
                    
                    <!-- Opción exclusiva para usuarios con editar_valores = 1 -->
                    <?php if (isset($_SESSION['user']['editar_valores']) && $_SESSION['user']['editar_valores'] == 1): ?>
                        <div class="dropdown-divider"></div>
                        <a title="Editar Valores del Sistema" class="dropdown-item" href="valores_predefinidos.php">
                            <i class="fas fa-edit fa-fw"></i> Valores Predefinidos
                        </a>
                        <a title="Editar tipos de pago" class="dropdown-item" href="tipo_pago.php">
                            <i class="fas fa-edit fa-fw"></i> tipos de pago
                        </a>
                    <?php endif; ?>
                    
                    <div class="dropdown-divider"></div>
                    <a title="Salir del Sistema" class="dropdown-item" href="#" id="logoutLink">
                        <i class="fas fa-sign-out-alt fa-fw"></i> Cerrar Sesión
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

<!-- Modal de Confirmación para Cerrar Sesión -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="logoutModalLabel">
                    <i class="fas fa-sign-out-alt mr-2"></i>Confirmar Cierre de Sesión
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>¿Está seguro de que desea cerrar la sesión?</strong>
                </div>
                <p>Será redirigido a la página de inicio de sesión.</p>
                <div class="user-info bg-light p-3 rounded">
                    <p class="mb-1"><strong>Usuario:</strong> <?php echo $_SESSION['user']['nombre'] ?? 'Usuario'; ?></p>
                    <p class="mb-0"><strong>Rol:</strong> <?php echo $_SESSION['user']['tipo_usuario'] ?? 'No definido'; ?></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </button>
                <a href="../logout.php" class="btn btn-danger" id="confirmLogout">
                    <i class="fas fa-sign-out-alt mr-2"></i>Sí, Cerrar Sesión
                </a>
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

// Script para manejar el modal de logout
document.addEventListener('DOMContentLoaded', function() {
    // Manejar el clic en el enlace de logout
    document.getElementById('logoutLink').addEventListener('click', function(e) {
        e.preventDefault(); // Prevenir el comportamiento por defecto
        $('#logoutModal').modal('show'); // Mostrar el modal
    });
    
    // Manejar la confirmación de logout
    document.getElementById('confirmLogout').addEventListener('click', function(e) {
        e.preventDefault(); // Prevenir cualquier acción por defecto
        
        // Cerrar el modal
        $('#logoutModal').modal('hide');
        
        // Redirigir después de que el modal se haya ocultado
        setTimeout(function() {
            window.location.href = '../logout.php';
        }, 500);
    });
    
    // Actualizar también al cargar la página
    actualizarNotificaciones();
});
</script>

</body>
</html>