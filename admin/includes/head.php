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
<!-- VIEWPORT CRÍTICO PARA MÓVILES -->
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Gestion">
<meta name="author" content="Hector Marulanda">
<title><?php echo $titulopag; ?></title>

<!-- CSS NECESARIOS -->
<!-- Bootstrap 4.6 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    /* ESTILOS GENERALES */
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
    
    /* MEJORAS ESPECÍFICAS PARA DROPDOWNS */
    
    /* Alineación de elementos del navbar */
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
    
    /* Dropdown optimizado - SOLUCIÓN AL PROBLEMA */
    .dropdown-menu {
        z-index: 1050; /* Mayor prioridad para que se muestre por encima del navbar */
        border: 1px solid rgba(0,0,0,.15);
        box-shadow: 0 2px 10px rgba(0,0,0,.1);
        max-height: 70vh; /* Limitar altura máxima */
        overflow-y: auto; /* Scroll si es muy largo */
    }
    
    /* Asegurar que los dropdowns no se corten */
    .navbar-nav .dropdown-menu {
        position: absolute !important;
    }
    
    /* Mejorar el botón hamburguesa en móviles */
    .navbar-toggler {
        border: none;
        padding: 0.25rem 0.5rem;
    }
    
    .navbar-toggler:focus {
        outline: none;
        box-shadow: none;
    }
    
    /* Elementos de dropdown más fáciles de tocar */
    .dropdown-item {
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
        white-space: normal; /* Permitir que el texto se ajuste */
    }
    
    .dropdown-toggle::after {
        margin-left: 0.3em;
        vertical-align: 0.15em;
    }
    
    /* ESPACIO PARA EL NAVBAR FIJO */
    body {
        padding-top: 76px; /* Compensar altura del navbar fijo */
    }
    
    /* ESTILOS ESPECÍFICOS PARA DISPOSITIVOS MÓVILES */
    @media (max-width: 991.98px) {
        .navbar-nav .nav-link {
            padding: 0.75rem 1rem; /* Más padding para mejor tacto */
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: none;
            background-color: #f8f9fa; /* Fondo más claro para móviles */
            position: static !important; /* En móviles, los dropdowns son estáticos */
            transform: none !important;
            margin-left: 1rem;
            max-height: 50vh; /* Menor altura en móviles */
        }
        
        .dropdown-item {
            padding: 0.5rem 1.5rem; /* Más espacio para tocar fácilmente */
        }
        
        /* Mejorar el colapso del navbar en móviles */
        .navbar-collapse {
            padding: 1rem 0;
            max-height: calc(100vh - 76px); /* Limitar altura del menú colapsado */
            overflow-y: auto; /* Scroll si el menú es muy largo */
        }
        
        /* Asegurar que los dropdowns sean claramente visibles en móviles */
        .dropdown-menu.show {
            display: block !important;
        }
    }
    
    /* MEJORAS PARA DROPDOWNS LARGOS EN ESCRITORIO */
    @media (min-width: 992px) {
        .dropdown-menu {
            max-height: 60vh; /* Altura máxima en desktop */
            overflow-y: auto; /* Scroll para dropdowns muy largos */
        }
    }
    
    /* ESTILOS PARA IMPRESIÓN */
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
    
    /* ESTILOS PERSONALIZADOS DEL PANEL */
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
    
    /* Colores específicos para tarjetas */
    .pagos-card { border-bottom: 4px solid #28a745; }
    .pagos-card .card-icon { color: #28a745; }
    .soporte-card { border-bottom: 4px solid #ffc107; }
    .soporte-card .card-icon { color: #ffc107; }
    .mensajeria-card { border-bottom: 4px solid #17a2b8; }
    .mensajeria-card .card-icon { color: #17a2b8; }
    .auditoria-card { border-bottom: 4px solid #6f42c1; }
    .auditoria-card .card-icon { color: #6f42c1; }
    
    /* Estilos para centrar tarjetas en responsive */
    .cards-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 1.5rem;
    }
    .card-wrapper {
        flex: 0 0 auto;
        width: 270px;
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
</head>
<body>
<div class="container">
    <!-- NAVEGACIÓN PRINCIPAL -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #c2d9fe; z-index: 1030;">
        <div class="container">
            <!-- Logo -->
            <a title="Cargar Inicio" class="navbar-brand" href="index.php">
                <?php echo $logopertenencia; ?>
            </a>
            
            <!-- Botón hamburguesa para móviles -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" 
                    aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Contenido colapsable del navbar -->
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    
                    <!-- Inicio -->
                    <li class="nav-item">
                        <a title="Cargar Inicio" class="nav-link" href="index.php">
                            <i class="fas fa-home fa-fw"></i> Inicio
                        </a>
                    </li>

                    <!-- Mensajería con notificación -->
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

                    <!-- Pagos (individual) -->
                    <?php if (tienePermiso('pagos')): ?>
                    <li class="nav-item">
                        <a title="Gestión de Pagos" class="nav-link" href="registro_pagos.php">
                            <i class="fas fa-money-bill-wave fa-fw"></i> Pagos
                        </a>
                    </li>
                    <?php endif; ?>

                    <!-- DROPDOWN: Estudiantes -->
                    <li class="nav-item dropdown">
                        <a title="Gestión de Estudiantes" class="nav-link dropdown-toggle" href="#" 
                           id="navbarDropdownEstudiantes" role="button" data-toggle="dropdown" 
                           aria-haspopup="true" aria-expanded="false">
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

                            <?php if (tienePermiso('secciones')): ?>
                            <a title="Gestionar Secciones" class="dropdown-item" href="gestion_seccion.php">
                                <i class="fas fa-object-group fa-fw"></i> Gestionar Secciones
                            </a>
                            <?php endif; ?>

                            <?php if (tienePermiso('grado')): ?>
                                <a title="Títulos y Relaciones con Materias" class="dropdown-item" href="grado.php">
                                    <i class="fas fa-graduation-cap fa-fw"></i> Grado
                                </a>
                            <?php endif; ?>
                        </div>
                    </li>

                    <!-- DROPDOWN: Pensum -->
                    <li class="nav-item dropdown">
                        <a title="Gestión de Pensum" class="nav-link dropdown-toggle" href="#" 
                           id="navbarDropdownPensum" role="button" data-toggle="dropdown" 
                           aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-book fa-fw"></i> Pensum
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownPensum">
                            <a title="Agregar Nueva Carrera" class="dropdown-item" href="agregar_carrera.php">
                                <i class="fas fa-plus-circle fa-fw"></i> Gestion de Programas
                            </a>
                            
                            <a title="Asignaturas" class="dropdown-item" href="materia.php">
                                <i class="fas fa-book-open fa-fw"></i> Asignaturas
                            </a>
                            
                            <?php if (tienePermiso('rela_materia_carrera')): ?>
                            <a title="Relacionar Materias con Carreras" class="dropdown-item" href="carrera_materias.php">
                                <i class="fas fa-link fa-fw"></i> Relacionar Materias-Carreras
                            </a>
                            <?php endif; ?>

                            <?php if (tienePermiso('periodos_academicos')): ?>
                            <a title="Periodos Académicos" class="dropdown-item" href="periodos_academicos.php">
                                <i class="fas fa-calendar fa-fw"></i> Periodos Académicos
                            </a>
                            <?php endif; ?>
                        </div>
                    </li>

                    <!-- DROPDOWN: Docentes -->
                    <li class="nav-item dropdown">
                        <a title="Gestión de Docentes" class="nav-link dropdown-toggle" href="#" 
                           id="navbarDropdownDocentes" role="button" data-toggle="dropdown" 
                           aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-chalkboard-teacher fa-fw"></i> Docentes
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownDocentes">
                            <a title="Registrar Nuevo Docente" class="dropdown-item" href="add_docente.php">
                                <i class="fas fa-user-plus fa-fw"></i> Gestionar Docente
                            </a>

                            <!-- Asignar Secciones -->
                            <?php if (tienePermiso('asig_secciones')): ?>
                            <a title="Asignación de Secciones" class="dropdown-item" href="asignar_secciones.php">
                                <i class="fas fa-object-group fa-fw"></i> Asignar Secciones
                            </a>
                            <?php endif; ?>
                            
                            <?php if (tienePermiso('asig_cursos')): ?>
                            <a title="Asignación de Cursos" class="dropdown-item" href="asignacion_cursos.php">
                                <i class="fas fa-tasks fa-fw"></i> Asignar Cursos
                            </a>
                            <?php endif; ?>

                            <?php if (tienePermiso('horarios')): ?>
                            <a title="Horarios Docentes" class="dropdown-item" href="horarios_docentes.php">
                                <i class="fas fa-calendar-alt fa-fw"></i> Horarios
                            </a>
                            <?php endif; ?>

                            <?php if (tienePermiso('gestion_director_carrera')): ?>
                            <a title="Gestionar Directores de Carrera" class="dropdown-item" href="directores_carrera.php">
                                <i class="fas fa-user-plus fa-fw"></i> Gestionar Directores de Carrera
                            </a>
                            <?php endif; ?>
                        </div>
                    </li>

                    <!-- DROPDOWN: Notas -->
                    <li class="nav-item dropdown">
                        <a title="Gestión de Notas" class="nav-link dropdown-toggle" href="#" 
                           id="navbarDropdownNotas" role="button" data-toggle="dropdown" 
                           aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-graduation-cap fa-fw"></i> Notas
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownNotas">
                            <?php if (tienePermiso('notas_cargadas')): ?>
                                <a title="Registrar Notas" class="dropdown-item" href="admin_notas_pendientes.php">
                                    <i class="fas fa-edit fa-fw"></i> Notas Cargadas
                                </a>
                            <?php endif; ?>

                            <?php if (tienePermiso('consultar_notas')): ?>
                                <a title="Consultar Notas" class="dropdown-item" href="consulta_notas.php">
                                    <i class="fas fa-search fa-fw"></i> Consultar Notas
                                </a>
                            <?php endif; ?>
                            
                            <!-- Consultar Notas Pasadas -->
                            <?php if (tienePermiso('consultar_notas_pasadas')): ?>
                            <a title="Consultar Notas Pasadas" class="dropdown-item" href="notas_pasadas.php">
                                <i class="fas fa-history fa-fw"></i> Consultar Notas Pasadas
                            </a>
                            <?php endif; ?>

                            <a title="Consultar Notas Pasadas" class="dropdown-item" href="../pagina_en_construccion.php">
                                <i class="fas fa-pencil-alt fa-fw"></i> Corrección de Notas
                            </a>
                        </div>
                    </li>

                    <!-- DROPDOWN: Ajustes -->
                    <li class="nav-item dropdown">
                        <a title="Ir a Ajustes" class="nav-link dropdown-toggle" href="#" 
                           id="navbarDropdownAjustes" role="button" data-toggle="dropdown" 
                           aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-cogs fa-fw"></i> Ajustes
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownAjustes">
                            
                            <!-- Cambiar Perfil -->
                            <a title="Cambiar Perfil de Usuario" class="dropdown-item" href="../profile_selector.php">
                                <i class="fas fa-user-edit fa-fw"></i> Cambiar Perfil
                            </a>
                            
                            <div class="dropdown-divider"></div>
                            
                            <!-- Auditoría -->
                            <?php if (tienePermiso('auditoria')): ?>
                            <a title="Auditoría del Sistema" class="dropdown-item" href="auditoria.php">
                                <i class="fas fa-clipboard-list fa-fw"></i> Auditoría
                            </a>
                            <?php endif; ?>

                            <!-- Respaldo BD -->
                            <?php if (tienePermiso('respaldo_bd')): ?>
                            <a title="Respaldo de Base de Datos" class="dropdown-item" href="respaldo_bd.php">
                                <i class="fas fa-database fa-fw"></i> Respaldo BD
                            </a>
                            <?php endif; ?>
                            
                            <!-- Títulos y Relaciones con Materias -->
                            <div class="dropdown-divider"></div>
                            <?php if (tienePermiso('titulos_re_materia')): ?>
                            <a title="Títulos y Relaciones con Materias" class="dropdown-item" href="titulos_relaciones_materias.php">
                                <i class="fas fa-graduation-cap fa-fw"></i> Títulos y Relaciones con Materias
                            </a>
                            <?php endif; ?>
                            
                            <!-- Niveles de Acceso -->
                            <?php if (tienePermiso('editar_acceso')): ?>
                                <div class="dropdown-divider"></div>
                                <a title="Editar Niveles de Acceso" class="dropdown-item" href="editar_accesos.php">
                                    <i class="fas fa-user-lock fa-fw"></i> Niveles de Acceso
                                </a>
                            <?php endif; ?>
                            
                            <!-- Valores Predefinidos -->
                            <div class="dropdown-divider"></div>
                            <?php if (tienePermiso('editar_valores')): ?>
                            <a title="Editar Valores del Sistema" class="dropdown-item" href="valores_predefinidos.php">
                                <i class="fas fa-edit fa-fw"></i> Valores Predefinidos
                            </a>
                            <?php endif; ?>

                            <!-- Tipos de Pago -->
                            <?php if (tienePermiso('tipos_pago')): ?>
                            <a title="Editar tipos de pago" class="dropdown-item" href="tipo_pago.php">
                                <i class="fas fa-money-bill fa-fw"></i> Tipos de Pago
                            </a>
                            <?php endif; ?>

                            <!-- Tipos de Horario -->
                            <?php if (tienePermiso('tipos_horario')): ?>
                            <a title="Gestionar tipos de horario" class="dropdown-item" href="tipos_horario.php">
                                <i class="fas fa-clock fa-fw"></i> Tipos de Horario
                            </a>
                            <?php endif; ?>

                            <!-- Horarios por Personal -->
                            <?php if (tienePermiso('horario_personal')): ?>
                            <a title="Gestionar horarios por personal" class="dropdown-item" href="gestion_horario_personal.php">
                                <i class="fas fa-user-clock fa-fw"></i> Horarios por Personal
                            </a>
                            <?php endif; ?>
                            
                            <!-- Cerrar Sesión -->
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

    <!-- CONTENIDO PRINCIPAL -->
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

<!-- MODAL DE CONFIRMACIÓN PARA CERRAR SESIÓN -->
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

<!-- SCRIPTS JAVASCRIPT NECESARIOS -->
<!-- jQuery primero, luego Popper.js, luego Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

<!-- Scripts para PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
// FUNCIÓN PARA ACTUALIZAR NOTIFICACIONES
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

// MEJORAS ESPECÍFICAS PARA DROPDOWNS
document.addEventListener('DOMContentLoaded', function() {
    // 1. Manejar el clic en el enlace de logout
    document.getElementById('logoutLink').addEventListener('click', function(e) {
        e.preventDefault();
        $('#logoutModal').modal('show');
    });
    
    // 2. Manejar la confirmación de logout
    document.getElementById('confirmLogout').addEventListener('click', function(e) {
        e.preventDefault();
        $('#logoutModal').modal('hide');
        setTimeout(function() {
            window.location.href = '../logout.php';
        }, 500);
    });
    
    // 3. SOLUCIÓN PARA DROPDOWNS - Cerrar al hacer clic fuera
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').removeClass('show');
        }
    });
    
    // 4. Prevenir que los dropdowns se cierren al hacer clic dentro
    $('.dropdown-menu').on('click', function(e) {
        e.stopPropagation();
    });
    
    // 5. MEJORA PARA MÓVILES: Manejar dropdowns en móviles
    if (window.innerWidth <= 991) {
        $('.dropdown-toggle').on('click', function(e) {
            e.preventDefault();
            var $parent = $(this).closest('.dropdown');
            var $menu = $parent.find('.dropdown-menu');
            
            // Cerrar otros dropdowns abiertos
            $('.dropdown-menu').not($menu).removeClass('show');
            
            // Alternar el actual
            $menu.toggleClass('show');
        });
    }
    
    // 6. Actualizar notificaciones al cargar la página
    actualizarNotificaciones();
});

// Actualizar notificaciones cada 30 segundos
setInterval(actualizarNotificaciones, 30000);

// 7. DETECCIÓN DE DISPOSITIVO MÓVIL Y MEJORAS ADICIONALES
if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
    // Aplicar mejoras específicas para móviles
    document.addEventListener('touchstart', function() {}, {passive: true});
    
    // Prevenir zoom en elementos interactivos
    document.addEventListener('touchmove', function(e) {
        if (e.target.tagName === 'SELECT' || e.target.tagName === 'INPUT') {
            e.preventDefault();
        }
    }, {passive: false});
}
</script>

</body>
</html>