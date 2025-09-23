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

// Verificar autenticación y rol
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['msg'] = "Debes iniciar sesión como administrador para acceder";
    header('location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es-Es">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
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
    /* Estilos para alinear correctamente todos los elementos del navbar */
    .navbar-nav .nav-item {
        display: flex;
        align-items: center;
    }
    .navbar-nav .nav-link {
        display: flex;
        align-items: center;
        height: 100%;
        padding: 0.5rem 1rem;
    }
    .navbar-nav .dropdown-toggle::after {
        margin-left: 0.5rem;
    }
    /* Asegurar que los dropdowns se muestren correctamente */
    .dropdown-menu {
        z-index: 1030; /* Mayor que el z-index del navbar (1020) */
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

            <!-- Botón individual de Pagos (no desplegable) -->

            <?php if (tienePermiso('pagos')): ?>
            <li class="nav-item">
              <a title="Gestión de Pagos" class="nav-link" href="registro_pagos.php">
                <i class="fas fa-money-bill-wave fa-fw"></i> Pagos
              </a>
            </li>
            <?php endif; ?>

            <li class="nav-item dropdown">
              <a title="Gestión de Estudiantes" class="nav-link dropdown-toggle" href="#" id="navbarDropdownEstudiantes" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-users fa-fw"></i> Estudiantes
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownEstudiantes">
                <a title="Ver Todos los Estudiantes" class="dropdown-item" href="estudiantes.php">
                  <i class="fa fa-users fa-fw"></i> Ver todos los Estudiantes
                </a>
                <?php if (tienePermiso('agregar_estudiante')): ?>
                  <a title="Agregar Estudiante" class="dropdown-item" href="agregar_estudiante.php">
                    <i class="fa fa-user-plus fa-fw"></i> Agregar Estudiante
                  </a>
                <?php endif; ?>

                <?php if (tienePermiso('gestionar_secciones')): ?>
                <a title="Gestionar Secciones" class="dropdown-item" href="gestion_seccion.php">
                  <i class="fas fa-object-group fa-fw"></i> Gestionar Secciones
                </a>
                <?php endif; ?>
              </div>
            </li>


            <li class="nav-item dropdown">
                <a title="Gestión de Pensum" class="nav-link dropdown-toggle" href="#" id="navbarDropdownPensum" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-book fa-fw"></i> Pensum
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownPensum">
                    
                        <a title="Agregar Nueva Carrera" class="dropdown-item" href="agregar_carrera.php">
                            <i class="fas fa-plus-circle fa-fw"></i> Gestion de Programas
                        </a>
                    
                    
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

            <li class="nav-item dropdown">
                <a title="Gestión de Docentes" class="nav-link dropdown-toggle" href="#" id="navbarDropdownDocentes" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-chalkboard-teacher fa-fw"></i> Docentes
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownDocentes">
                  
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

                <a title="Gestionar Directores de Carrera" class="dropdown-item" href="directores_carrera.php">
                    <i class="fas fa-user-plus fa-fw"></i> Gestionar Directores de Carrera
                </a>
                
                </div>
            </li>

            <li class="nav-item dropdown">
                <a title="Gestión de Notas" class="nav-link dropdown-toggle" href="#" id="navbarDropdownNotas" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-graduation-cap fa-fw"></i> Notas
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownNotas">
                    <a title="Registrar Notas" class="dropdown-item" href="admin_notas_pendientes.php">
                        <i class="fas fa-edit fa-fw"></i> Notas Cargadas
                    </a>
                    <a title="Consultar Notas" class="dropdown-item" href="consulta_notas.php">
                        <i class="fas fa-search fa-fw"></i> Consultar Notas
                    </a>
                    <!-- Nueva opción: Consultar Notas Pasadas -->
                    <a title="Consultar Notas Pasadas" class="dropdown-item" href="../pagina_en_construccion.php">
                        <i class="fas fa-history fa-fw"></i> Consultar Notas Pasadas
                    </a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a title="Ir a Ajustes" class="nav-link dropdown-toggle" href="#" id="navbarDropdownAjustes" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-cogs fa-fw"></i> Ajustes
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownAjustes">
                    
                    <!-- Nueva opción: Cambiar Perfil -->
                    <a title="Cambiar Perfil de Usuario" class="dropdown-item" href="../profile_selector.php">
                        <i class="fas fa-user-edit fa-fw"></i> Cambiar Perfil
                    </a>
                    
                    <!-- Nuevas opciones: Auditoría y Respaldo -->
                    <div class="dropdown-divider"></div>
                    <a title="Auditoría del Sistema" class="dropdown-item" href="auditoria.php">
                        <i class="fas fa-clipboard-list fa-fw"></i> Auditoría
                    </a>
                    <a title="Respaldo de Base de Datos" class="dropdown-item" href="respaldo_bd.php">
                        <i class="fas fa-database fa-fw"></i> Respaldo BD
                    </a>
                    
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
                <i class="fas fa-money-bill fa-fw"></i> Tipos de Pago
            </a>
                          <!-- Nueva opción: Tipos de Horario -->
            <a title="Gestionar tipos de horario" class="dropdown-item" href="tipos_horario.php">
                <i class="fas fa-clock fa-fw"></i> Tipos de Horario
            </a>
 <a title="Gestionar horarios por personal" class="dropdown-item" href="gestion_horario_personal.php">
                <i class="fas fa-user-clock fa-fw"></i> Horarios por Personal
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
    <div class="container" style="margin-top: 80px;">
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



<!-- Bootstrap 4.6 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">



<!-- Incluir jsPDF y html2canvas para generar el PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printable-area, #printable-area * {
            visibility: visible;
        }
        #printable-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
        .card {
            border: none;
            box-shadow: none;
        }
        .table {
            font-size: 12px;
        }
        h4 {
            page-break-after: avoid;
        }
        .card-body {
            padding: 0;
        }
        .accordion .collapse {
            display: block !important;
            opacity: 1;
        }
    }
</style>

<!-- ESTILOS PERSONALIZADOS DEL PANEL DE ADMINISTRACION -->

<style>
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dashboard-header {
            background: linear-gradient(120deg, #4e73df 0%, #224abe 100%);
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .feature-card {
            border: none;
            border-radius: 10px;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        .card-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }
        .pagos-card {
            border-bottom: 4px solid #28a745;
        }
        .pagos-card .card-icon {
            color: #28a745;
        }
        .soporte-card {
            border-bottom: 4px solid #ffc107;
        }
        .soporte-card .card-icon {
            color: #ffc107;
        }
        .mensajeria-card {
            border-bottom: 4px solid #17a2b8;
        }
        .mensajeria-card .card-icon {
            color: #17a2b8;
        }
        .auditoria-card {
            border-bottom: 4px solid #6f42c1;
        }
        .auditoria-card .card-icon {
            color: #6f42c1;
        }
        .btn-access {
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-pagos {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }
        .btn-pagos:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .btn-soporte {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }
        .btn-soporte:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }
        .btn-mensajeria {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }
        .btn-mensajeria:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
        .btn-auditoria {
            background-color: #6f42c1;
            border-color: #6f42c1;
            color: white;
        }
        .btn-auditoria:hover {
            background-color: #5a359c;
            border-color: #523091;
        }
        .welcome-message {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        /* Nuevos estilos para centrar las tarjetas */
        .cards-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        .card-wrapper {
            flex: 0 0 auto;
            width: 270px; /* Ancho fijo para consistencia */
        }
        @media (max-width: 1200px) {
            .card-wrapper {
                width: 250px;
            }
        }
        @media (max-width: 768px) {
            .cards-container {
                justify-content: center;
            }
            .card-wrapper {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>



</body>
</html>