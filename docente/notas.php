<?php
// Iniciar sesión PRIMERO
require_once('../funciones/functions.php');

// Verificar autenticación y rol
if (!isLoggedIn() || !isDocente()) {
    $_SESSION['msg'] = "Debes iniciar sesión como docente para acceder";
    header('location: ../login.php');
    exit();
}

// Obtener ID del docente directamente de la sesión
if (isset($_SESSION['user']['id'])) {
    $docente_id = (int)$_SESSION['user']['id'];
} elseif (isset($_SESSION['id'])) {
    $docente_id = (int)$_SESSION['id'];
} elseif (isset($_SESSION['user_id'])) {
    $docente_id = (int)$_SESSION['user_id'];
} else {
    die("Error: No se pudo identificar al usuario");
}

// Obtener secciones del docente
function obtenerSeccionesDocente($docente_id) {
    global $db;
    
    $query = "SELECT s.id_seccion, s.codigo_seccion, c.nombre_carrera, 
                     t.nombre_trayecto, pa.nombre_periodo,
                     m.id_materia, m.nombre_materia, m.cod_materia, t.numero_trayecto
              FROM secciones s
              INNER JOIN docente_seccion ds ON s.id_seccion = ds.id_seccion
              INNER JOIN carreras c ON s.id_carrera = c.id_carrera
              INNER JOIN trayectos t ON s.id_trayecto = t.id_trayecto
              INNER JOIN periodos_academicos pa ON s.id_periodo = pa.id_periodo
              INNER JOIN materias m ON ds.id_materia = m.id_materia
              WHERE ds.id_usuario = ? 
              AND (ds.estatus = 'activo' OR ds.estatus = 1)
              ORDER BY pa.fecha_inicio DESC, c.nombre_carrera";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $docente_id);
    $stmt->execute();
    
    return $stmt->get_result();
}

$result_secciones = obtenerSeccionesDocente($docente_id);

// HTML
$titulopag = "Registro de Notas";
include("includes/head.php");
?>

<div class="container-fluid">
    <h2 class="my-4">Registro de Notas</h2>
    
    <!-- Secciones del docente -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5>Secciones y Materias</h5>
        </div>
        <div class="card-body">
            <?php if ($result_secciones->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Sección</th>
                                <th>Carrera</th>
                                <th>Trayecto</th>
                                <th>Periodo</th>
                                <th>Materia</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($seccion = $result_secciones->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($seccion['codigo_seccion']) ?></td>
                                    <td><?= htmlspecialchars($seccion['nombre_carrera']) ?></td>
                                    <td><?= htmlspecialchars($seccion['nombre_trayecto']) ?></td>
                                    <td><?= htmlspecialchars($seccion['nombre_periodo']) ?></td>
                                    <td><?= htmlspecialchars($seccion['nombre_materia']) ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary btn-cargar" 
                                                data-seccion="<?= $seccion['id_seccion'] ?>"
                                                data-materia="<?= $seccion['id_materia'] ?>">
                                            <i class="fas fa-users"></i> Cargar Estudiantes
                                        </button>
                                        <button class="btn btn-sm btn-success btn-descargar-pdf" 
                                                data-seccion="<?= $seccion['id_seccion'] ?>"
                                                data-materia="<?= $seccion['id_materia'] ?>">
                                            <i class="fas fa-download"></i> Planilla PDF
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    No tienes secciones asignadas
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Resultados -->
    <div id="resultados">
        <div class="text-right mb-3" id="volver-container" style="display: none;">
            <button class="btn btn-secondary" id="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver a Secciones
            </button>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Cargar estudiantes
    $('.btn-cargar').click(function() {
        const seccionId = $(this).data('seccion');
        const materiaId = $(this).data('materia');
        
        $('#resultados').html(`
            <div class="text-right mb-3" id="volver-container">
                <button class="btn btn-secondary" id="btn-volver">
                    <i class="fas fa-arrow-left"></i> Volver a Secciones
                </button>
            </div>
            <div class="text-center py-4">
                <div class="spinner-border text-primary"></div>
                <p>Cargando estudiantes...</p>
            </div>
        `);
        
        fetch('cargar_estudiantes.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `seccion_id=${seccionId}&materia_id=${materiaId}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.text();
        })
        .then(html => {
            $('#resultados').html(`
                <div class="text-right mb-3" id="volver-container">
                    <button class="btn btn-secondary" id="btn-volver">
                        <i class="fas fa-arrow-left"></i> Volver a Secciones
                    </button>
                </div>
                ${html}
            `);
        })
        .catch(error => {
            $('#resultados').html(`
                <div class="text-right mb-3" id="volver-container">
                    <button class="btn btn-secondary" id="btn-volver">
                        <i class="fas fa-arrow-left"></i> Volver a Secciones
                    </button>
                </div>
                <div class="alert alert-danger">
                    Error: ${error.message}
                </div>
            `);
        });
    });
    
    // Función para volver a secciones
    $(document).on('click', '#btn-volver', function() {
        $('#resultados').html(`
            <div class="text-right mb-3" id="volver-container" style="display: none;">
                <button class="btn btn-secondary" id="btn-volver">
                    <i class="fas fa-arrow-left"></i> Volver a Secciones
                </button>
            </div>
        `);
    });
    
    // Descargar planilla PDF
    $(document).on('click', '.btn-descargar-pdf', function() {
        const seccionId = $(this).data('seccion');
        const materiaId = $(this).data('materia');
        
        // Mostrar loading
        const btn = $(this);
        const originalHtml = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Generando...');
        btn.prop('disabled', true);
        
        // Descargar PDF
        window.location.href = `descargar_planilla.php?seccion_id=${seccionId}&materia_id=${materiaId}`;
        
        // Restaurar botón después de 3 segundos
        setTimeout(() => {
            btn.html(originalHtml);
            btn.prop('disabled', false);
        }, 3000);
    });
    
    // Guardar notas
    $(document).on('submit', '#form-notas', function(e) {
        e.preventDefault();
        
        $('#resultados').html(`
            <div class="text-right mb-3" id="volver-container">
                <button class="btn btn-secondary" id="btn-volver">
                    <i class="fas fa-arrow-left"></i> Volver a Secciones
                </button>
            </div>
            <div class="text-center py-4">
                <div class="spinner-border text-success"></div>
                <p>Guardando notas y soporte...</p>
            </div>
        `);
        
        const formData = new FormData(this);
        
        fetch('guardar_notas.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            $('#resultados').html(`
                <div class="text-right mb-3" id="volver-container">
                    <button class="btn btn-secondary" id="btn-volver">
                        <i class="fas fa-arrow-left"></i> Volver a Secciones
                    </button>
                </div>
                <div class="alert alert-success">
                    ${result}
                </div>
            `);
        })
        .catch(error => {
            $('#resultados').html(`
                <div class="text-right mb-3" id="volver-container">
                    <button class="btn btn-secondary" id="btn-volver">
                        <i class="fas fa-arrow-left"></i> Volver a Secciones
                    </button>
                </div>
                <div class="alert alert-danger">
                    Error al guardar: ${error.message}
                </div>
            `);
        });
    });
    
    // Preview de imagen antes de subir
    $(document).on('change', '.soporte-grupo', function() {
        const file = this.files[0];
        const preview = $('#preview-grupo');
        const fileName = $('#nombre-archivo-grupo');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (file.type.startsWith('image/')) {
                    preview.html(`<img src="${e.target.result}" class="img-thumbnail" style="max-height: 150px;">`);
                } else {
                    preview.html(`
                        <div class="alert alert-info text-center">
                            <i class="fas fa-file-pdf fa-3x"></i><br>
                            <strong>Archivo PDF</strong>
                        </div>
                    `);
                }
                fileName.text(file.name);
            }
            reader.readAsDataURL(file);
        } else {
            preview.html('<small class="text-muted">No se ha seleccionado ningún archivo</small>');
            fileName.text('Ningún archivo seleccionado');
        }
    });
});
</script>

<?php include("includes/footer.php"); ?>