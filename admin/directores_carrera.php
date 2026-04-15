<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Asignar Carreras a Directores";
include('../funciones/functions.php');

// CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('gestion_director_carrera');

// Verificar permisos de administrador
if (!isAdmin()) {
    header('location: ../usuario/home.php');
    exit();
}

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

// Procesar la asignación de carrera
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asignar_carrera'])) {
    $id_usuario = intval($_POST['id_usuario']);
    $id_carrera = intval($_POST['carrera']);
    
    if (asignarCarreraDirector($id_usuario, $id_carrera)) {
        $_SESSION['success'] = "Carrera asignada correctamente al director.";
    } else {
        $_SESSION['error'] = "Error al asignar la carrera al director.";
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Procesar la eliminación de asignación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_asignacion'])) {
    $id_usuario = intval($_POST['id_usuario']);
    
    if (eliminarAsignacionCarrera($id_usuario)) {
        $_SESSION['success'] = "Asignación de carrera eliminada correctamente.";
    } else {
        $_SESSION['error'] = "Error al eliminar la asignación de carrera.";
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

include("includes/head.php");
?>

<!-- Estilos responsivos adicionales -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
<style>
    /* Estilos responsivos generales */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        /* Títulos */
        h4.mb-0 {
            font-size: 1.2rem;
        }
        
        h5.mb-0 {
            font-size: 1rem;
        }
        
        /* Tarjetas en columna */
        .row > .col-md-6 {
            margin-bottom: 20px;
        }
        
        /* Formularios responsivos */
        .form-group {
            margin-bottom: 15px;
        }
        
        select.form-control {
            font-size: 16px !important; /* Evita zoom en iOS */
        }
        
        /* Botones */
        .btn-block {
            width: 100%;
            padding: 10px;
        }
        
        /* Tabla responsiva */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            min-width: 500px;
        }
        
        .table th,
        .table td {
            padding: 10px 8px;
            font-size: 0.8rem;
        }
        
        /* Lista de directores sin asignación */
        .list-group-item {
            flex-direction: column;
            align-items: flex-start !important;
            text-align: left;
        }
        
        .list-group-item .badge {
            margin-top: 8px;
            align-self: flex-start;
        }
        
        /* Alertas responsivas */
        .alert {
            font-size: 0.85rem;
            padding: 12px;
        }
        
        .alert h5 {
            font-size: 0.95rem;
            margin-bottom: 8px;
        }
        
        /* Card headers */
        .card-header {
            padding: 12px 15px;
        }
        
        .card-header h4,
        .card-header h5 {
            font-size: 1rem;
        }
        
        /* Espaciado general */
        .mt-4 {
            margin-top: 1rem !important;
        }
        
        .mb-4 {
            margin-bottom: 1rem !important;
        }
    }
    
    @media (max-width: 480px) {
        .table th,
        .table td {
            padding: 8px 6px;
            font-size: 0.7rem;
        }
        
        .btn-sm {
            padding: 5px 8px;
            font-size: 0.7rem;
        }
        
        .list-group-item {
            font-size: 0.8rem;
            padding: 10px;
        }
        
        .badge {
            font-size: 0.65rem;
            padding: 4px 8px;
        }
        
        .alert {
            font-size: 0.75rem;
            padding: 10px;
        }
    }
    
    /* Estilos adicionales */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.02);
    }
    
    .btn-sm i {
        margin-right: 3px;
    }
    
    .list-group-item .badge {
        font-size: 0.75rem;
    }
    
    /* Animaciones suaves */
    .card {
        transition: box-shadow 0.2s ease;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    /* Mejora visual para selectores */
    select.form-control:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    
    /* Botón deshabilitado */
    .btn:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }
</style>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-chalkboard-teacher"></i> Asignar Carreras a Directores
                    </h4>
                </div>
                <div class="card-body">
                    <?php
                    // Mostrar mensajes de éxito o error
                    if (isset($_SESSION['success'])) {
                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                        echo '<i class="fas fa-check-circle"></i> ' . $_SESSION['success'];
                        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        echo '<span aria-hidden="true">&times;</span>';
                        echo '</button>';
                        echo '</div>';
                        unset($_SESSION['success']);
                    }
                    
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                        echo '<i class="fas fa-exclamation-triangle"></i> ' . $_SESSION['error'];
                        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        echo '<span aria-hidden="true">&times;</span>';
                        echo '</button>';
                        echo '</div>';
                        unset($_SESSION['error']);
                    }
                    ?>
                    
                    <div class="alert alert-info">
                        <h5 class="alert-heading">
                            <i class="fas fa-info-circle"></i> Información importante
                        </h5>
                        <p class="mb-0">
                            En este módulo puede asignar carreras a los Directores de Carrera. 
                            Los directores solo podrán ver información relacionada con su carrera asignada en su módulo correspondiente.
                        </p>
                    </div>

                    <div class="row">
                        <!-- Columna izquierda - Asignar nueva carrera -->
                        <div class="col-12 col-md-6 mb-4 mb-md-0">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-plus-circle"></i> Asignar Nueva Carrera
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        <div class="form-group">
                                            <label for="usuario">
                                                <i class="fas fa-user-tie"></i> Seleccionar Director de Carrera:
                                            </label>
                                            <select class="form-control" id="usuario" name="id_usuario" required>
                                                <option value="">-- Seleccionar Director --</option>
                                                <?php
                                                $usuarios = obtenerUsuariosParaDirectores();
                                                if (empty($usuarios)) {
                                                    echo '<option value="" disabled>No hay directores disponibles sin carrera asignada</option>';
                                                } else {
                                                    foreach ($usuarios as $usuario) {
                                                        echo '<option value="' . $usuario['id'] . '">' . 
                                                             htmlspecialchars($usuario['nombre']) . ' (' . 
                                                             htmlspecialchars($usuario['username']) . ')' . 
                                                             '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <?php if (empty($usuarios)): ?>
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle"></i> Todos los directores de carrera ya tienen una carrera asignada.
                                            </small>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="carrera">
                                                <i class="fas fa-graduation-cap"></i> Seleccionar Carrera:
                                            </label>
                                            <select class="form-control" id="carrera" name="carrera" required>
                                                <option value="">-- Seleccionar Carrera --</option>
                                                <?php
                                                $carreras = obtenerTodasLasCarreras();
                                                foreach ($carreras as $carrera) {
                                                    echo '<option value="' . $carrera['id'] . '">' . 
                                                         htmlspecialchars($carrera['nombre']) . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        <button type="submit" name="asignar_carrera" class="btn btn-success btn-block" <?php echo empty($usuarios) ? 'disabled' : ''; ?>>
                                            <i class="fas fa-save"></i> Asignar Carrera
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Columna derecha - Directores con carrera asignada -->
                        <div class="col-12 col-md-6">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-list"></i> Directores con Carrera Asignada
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">
                                        <i class="fas fa-users"></i> Lista de directores de carrera con sus carreras asignadas:
                                    </p>
                                    
                                    <?php
                                    $directores = obtenerDirectoresDeCarrera();
                                    
                                    if (empty($directores)) {
                                        echo '<div class="alert alert-secondary">';
                                        echo '<p class="mb-0"><i class="fas fa-info-circle"></i> ';
                                        echo 'No hay directores de carrera registrados en el sistema.</p>';
                                        echo '</div>';
                                    } else {
                                        $directoresConAsignacion = array_filter($directores, function($director) {
                                            return !empty($director['carrera_di']) && $director['carrera_di'] != 0;
                                        });
                                        
                                        $directoresSinAsignacion = array_filter($directores, function($director) {
                                            return empty($director['carrera_di']) || $director['carrera_di'] == 0;
                                        });
                                        
                                        if (empty($directoresConAsignacion)) {
                                            echo '<div class="alert alert-warning">';
                                            echo '<p class="mb-0"><i class="fas fa-exclamation-triangle"></i> ';
                                            echo 'No hay directores con carreras asignadas.</p>';
                                            echo '</div>';
                                        } else {
                                            echo '<div class="table-responsive">';
                                            echo '<table class="table table-striped table-bordered table-hover">';
                                            echo '<thead class="thead-dark">';
                                            echo '<tr>';
                                            echo '<th><i class="fas fa-user"></i> Nombre</th>';
                                            echo '<th><i class="fas fa-id-card"></i> Usuario</th>';
                                            echo '<th><i class="fas fa-graduation-cap"></i> Carrera Asignada</th>';
                                            echo '<th><i class="fas fa-cog"></i> Acciones</th>';
                                            echo '</tr>';
                                            echo '</thead>';
                                            echo '<tbody>';
                                            
                                            foreach ($directoresConAsignacion as $director) {
                                                echo '<tr>';
                                                echo '<td><strong>' . htmlspecialchars($director['nombre']) . '</strong></td>';
                                                echo '<td>' . htmlspecialchars($director['username']) . '</td>';
                                                echo '<td>';
                                                if (!empty($director['nombre_carrera'])) {
                                                    echo '<span class="badge badge-info">' . htmlspecialchars($director['nombre_carrera']) . '</span>';
                                                } else {
                                                    echo '<span class="badge badge-danger">Carrera no encontrada</span>';
                                                }
                                                echo '</td>';
                                                echo '<td>';
                                                echo '<form method="POST" class="d-inline-block">';
                                                echo '<input type="hidden" name="id_usuario" value="' . $director['id'] . '">';
                                                echo '<button type="submit" name="eliminar_asignacion" class="btn btn-sm btn-danger" onclick="return confirm(\'¿Está seguro de que desea quitar esta asignación?\')">';
                                                echo '<i class="fas fa-times"></i> <span class="d-none d-md-inline">Quitar</span>';
                                                echo '</button>';
                                                echo '</form>';
                                                echo '</td>';
                                                echo '</tr>';
                                            }
                                            
                                            echo '</tbody>';
                                            echo '</table>';
                                            echo '</div>';
                                        }
                                        
                                        // Mostrar directores sin asignación
                                        if (!empty($directoresSinAsignacion)) {
                                            echo '<div class="mt-4">';
                                            echo '<h6 class="text-muted mb-3">';
                                            echo '<i class="fas fa-user-clock"></i> Directores sin carrera asignada:';
                                            echo '</h6>';
                                            echo '<div class="list-group">';
                                            foreach ($directoresSinAsignacion as $director) {
                                                echo '<div class="list-group-item d-flex justify-content-between align-items-center">';
                                                echo '<div>';
                                                echo '<strong>' . htmlspecialchars($director['nombre']) . '</strong><br>';
                                                echo '<small class="text-muted">' . htmlspecialchars($director['username']) . '</small>';
                                                echo '</div>';
                                                echo '<span class="badge badge-warning">Sin asignar</span>';
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Mejorar experiencia en dispositivos móviles
$(document).ready(function() {
    // Auto-cerrar alertas después de 5 segundos
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
    
    // Mejorar selects en móvil
    if (/Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent)) {
        $('select').each(function() {
            $(this).attr('data-native-menu', 'true');
        });
    }
    
    // Confirmación con mejor UX
    $('form button[type="submit"][name="eliminar_asignacion"]').click(function(e) {
        if (!confirm('¿Está seguro de que desea quitar esta asignación?\n\nEsta acción no se puede deshacer.')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>

<?php include("includes/footer.php"); ?>