<?php
// Iniciar sesión PRIMERO
require_once('../funciones/functions.php');

// Verificar autenticación y rol
if (!isLoggedIn() || !isDocente()) {
    $_SESSION['msg'] = "Debes iniciar sesión como docente para acceder";
    header('location: ../login.php');
    exit();
}

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

// Obtener ID del docente directamente de la sesión
$docente_id = obtenerIdUsuario();

if (!$docente_id) {
    die("Error: No se pudo identificar al usuario");
}

// Obtener secciones del docente
$result_secciones = obtenerSeccionesDocente($docente_id);

// HTML
$titulopag = "Registro de Notas";
include("includes/head.php");
?>

<!-- Añadir meta viewport y estilos responsivos adicionales -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
<style>
    /* Estilos responsivos adicionales */
    @media (max-width: 768px) {
        /* Contenedor principal */
        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        /* Títulos más pequeños en móvil */
        h2.my-4 {
            font-size: 1.5rem;
            margin-top: 1rem !important;
            margin-bottom: 1rem !important;
        }
        
        .card-header h5 {
            font-size: 1rem;
        }
        
        /* Tabla responsiva con scroll horizontal */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* Botones en columna para móvil */
        .btn-group-mobile {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .btn-group-mobile .btn {
            width: 100%;
            margin: 0 !important;
        }
        
        /* Botones individuales en móvil */
        .btn-sm {
            padding: 6px 8px;
            font-size: 0.75rem;
            margin-bottom: 5px;
            display: inline-block;
            width: auto;
        }
        
        /* Para la columna de acciones en móvil */
        td:last-child {
            min-width: 180px;
        }
        
        /* Tarjeta de estudiante en móvil (para cuando se cargan estudiantes) */
        .estudiante-card {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px;
            background: #f9f9f9;
        }
        
        /* Formularios responsivos */
        .form-group input,
        .form-group select,
        .form-group textarea {
            font-size: 16px !important; /* Evita zoom en iOS */
        }
        
        /* Labels más pequeños en móvil */
        label {
            font-size: 0.85rem;
        }
        
        /* Modales responsivos */
        .modal-dialog {
            margin: 10px;
            max-width: calc(100% - 20px);
        }
        
        .modal-body {
            padding: 12px;
        }
        
        /* Tabla de preview en móvil */
        #preview-table {
            font-size: 0.75rem;
        }
        
        #preview-table th,
        #preview-table td {
            padding: 6px;
            white-space: nowrap;
        }
        
        /* Botón volver mejor posicionado */
        #volver-container {
            position: sticky;
            top: 0;
            background: white;
            z-index: 100;
            padding: 10px 0;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }
        
        /* Spinner centrado correctamente */
        .text-center.py-4 {
            padding: 2rem 0;
        }
        
        /* Ajustes para inputs de notas */
        input[type="number"] {
            font-size: 16px;
            width: 80px;
        }
        
        /* Cards en general */
        .card {
            margin-bottom: 15px;
        }
        
        /* Alertas */
        .alert {
            font-size: 0.85rem;
            padding: 10px;
        }
    }
    
    /* Para pantallas muy pequeñas (menos de 480px) */
    @media (max-width: 480px) {
        .btn-sm {
            font-size: 0.7rem;
            padding: 5px 6px;
        }
        
        td:last-child {
            min-width: 200px;
        }
        
        .table th,
        .table td {
            padding: 6px;
            font-size: 0.75rem;
        }
        
        h2.my-4 {
            font-size: 1.3rem;
        }
    }
    
    /* Estilos para la vista de estudiantes (mejora visual) */
    .estudiante-row {
        transition: all 0.3s ease;
    }
    
    .estudiante-row:hover {
        background-color: #f5f5f5;
    }
    
    /* Botones de acción en grupo */
    .acciones-botones {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        align-items: center;
    }
    
    @media (max-width: 768px) {
        .acciones-botones {
            flex-direction: column;
            align-items: stretch;
        }
        
        .acciones-botones .btn,
        .acciones-botones label {
            width: 100%;
            margin: 2px 0 !important;
            text-align: center;
        }
    }
</style>

<div class="container-fluid">
    <h2 class="my-4">
        <i class="fas fa-chalkboard-teacher"></i> Registro de Notas
    </h2>
    
    <!-- Secciones del docente -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-book"></i> Secciones y Materias
            </h5>
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
                                <tr class="seccion-row">
                                    <td data-label="Sección"><?= htmlspecialchars($seccion['codigo_seccion']) ?></td>
                                    <td data-label="Carrera"><?= htmlspecialchars($seccion['nombre_carrera']) ?></td>
                                    <td data-label="Trayecto"><?= htmlspecialchars($seccion['nombre_trayecto']) ?></td>
                                    <td data-label="Periodo"><?= htmlspecialchars($seccion['nombre_periodo']) ?></td>
                                    <td data-label="Materia"><?= htmlspecialchars($seccion['nombre_materia']) ?></td>
                                    <td data-label="Acciones">
                                        <div class="acciones-botones">
                                            <button class="btn btn-sm btn-primary btn-cargar" 
                                                    data-seccion="<?= $seccion['id_seccion'] ?>"
                                                    data-materia="<?= $seccion['id_materia'] ?>">
                                                <i class="fas fa-users"></i> Cargar
                                            </button>
                                            <button class="btn btn-sm btn-success btn-descargar-pdf" 
                                                    data-seccion="<?= $seccion['id_seccion'] ?>"
                                                    data-materia="<?= $seccion['id_materia'] ?>">
                                                <i class="fas fa-download"></i> PDF
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary btn-descargar-csv"
                                                    data-seccion="<?= $seccion['id_seccion'] ?>"
                                                    data-materia="<?= $seccion['id_materia'] ?>">
                                                <i class="fas fa-file-csv"></i> CSV
                                            </button>
                                            <label class="btn btn-sm btn-outline-primary mb-0" style="cursor:pointer;">
                                                <i class="fas fa-file-upload"></i> Importar
                                                <input type="file" accept=".csv,text/csv,application/vnd.ms-excel" class="d-none input-import-csv" data-seccion="<?= $seccion['id_seccion'] ?>" data-materia="<?= $seccion['id_materia'] ?>">
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-triangle"></i> No tienes secciones asignadas
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Resultados -->
    <div id="resultados">
        <div class="text-right mb-3" id="volver-container" style="display: none;">
            <button class="btn btn-secondary btn-block-mobile" id="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver a Secciones
            </button>
        </div>
    </div>
</div>

<!-- Modal resultado (éxito / error) -->
<div class="modal fade" id="modalResultado" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" id="modalResultadoHeader">
                <h5 class="modal-title" id="modalResultadoTitle">Resultado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalResultadoBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal preview import CSV -->
<div class="modal fade" id="modalPreviewCSV" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Preview de CSV</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="preview-summary" class="mb-3"></div>
                <div class="table-responsive" style="max-height:400px; overflow:auto;">
                    <table class="table table-sm table-bordered" id="preview-table">
                        <thead>
                            <tr>
                                <th>Línea</th>
                                <th>Cédula</th>
                                <th>Nombres</th>
                                <th>Nota</th>
                                <th>Campo</th>
                                <th>Mensaje</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btn-apply-csv">Aplicar al formulario</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Cargar estudiantes
    $('.btn-cargar').click(function() {
        const seccionId = $(this).data('seccion');
        const materiaId = $(this).data('materia');
        
        // Guardar posición del scroll
        const scrollPosition = $(window).scrollTop();
        
        $('#resultados').html(`
            <div class="text-right mb-3" id="volver-container">
                <button class="btn btn-secondary btn-block-mobile" id="btn-volver">
                    <i class="fas fa-arrow-left"></i> Volver a Secciones
                </button>
            </div>
            <div class="text-center py-5">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
                <p class="mt-3">Cargando estudiantes...</p>
            </div>
        `);
        
        // Scroll suave al inicio
        $('html, body').animate({ scrollTop: 0 }, 300);
        
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
                    <button class="btn btn-secondary btn-block-mobile" id="btn-volver">
                        <i class="fas fa-arrow-left"></i> Volver a Secciones
                    </button>
                </div>
                ${html}
            `);
            
            // Añadir clases responsivas a los inputs si es necesario
            $('input[type="number"]').addClass('form-control');
        })
        .catch(error => {
            $('#resultados').html(`
                <div class="text-right mb-3" id="volver-container">
                    <button class="btn btn-secondary btn-block-mobile" id="btn-volver">
                        <i class="fas fa-arrow-left"></i> Volver a Secciones
                    </button>
                </div>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> Error: ${error.message}
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
        
        // Scroll suave hacia las secciones
        $('html, body').animate({ 
            scrollTop: $('.card').first().offset().top - 20 
        }, 400);
    });
    
    // Descargar planilla PDF
    $(document).on('click', '.btn-descargar-pdf', function() {
        const seccionId = $(this).data('seccion');
        const materiaId = $(this).data('materia');
        
        // Mostrar loading
        const btn = $(this);
        const originalHtml = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i>');
        btn.prop('disabled', true);
        
        // Descargar PDF
        window.location.href = `descargar_planilla.php?seccion_id=${seccionId}&materia_id=${materiaId}`;
        
        // Restaurar botón después de 2 segundos
        setTimeout(() => {
            btn.html(originalHtml);
            btn.prop('disabled', false);
        }, 2000);
    });

    // Descargar plantilla CSV
    $(document).on('click', '.btn-descargar-csv', function() {
        const seccionId = $(this).data('seccion');
        const materiaId = $(this).data('materia');
        window.location.href = `descargar_planilla_csv.php?seccion_id=${seccionId}&materia_id=${materiaId}`;
    });

    // Importar CSV (input change)
    $(document).on('change', '.input-import-csv', function(e) {
        const file = this.files[0];
        const seccionId = $(this).data('seccion');
        const materiaId = $(this).data('materia');
        if (!file) return;

        const fd = new FormData();
        fd.append('file', file);
        fd.append('seccion_id', seccionId);
        fd.append('materia_id', materiaId);
        const tray = $('#trayecto_actual').val() || 0;
        fd.append('trayecto_actual', tray);

        // Mostrar modal y spinner
        $('#preview-table tbody').html('<tr><td colspan="6" class="text-center py-4"><div class="spinner-border text-info"></div> Procesando...</td></td>');
        $('#preview-summary').html('<span class="text-info">Procesando archivo...</span>');
        $('#modalPreviewCSV').modal('show');

        fetch('import_preview_notas.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    $('#preview-summary').html(`<div class="alert alert-danger">${data.error}</div>`);
                    return;
                }

                const rows = data.previewRows || [];
                const summary = data.summary || {};
                $('#preview-summary').html(`
                    <div class="alert alert-info">
                        <strong>Total:</strong> ${summary.total} | 
                        <strong>Válidas:</strong> ${summary.validas} | 
                        <strong>Inválidas:</strong> ${summary.invalidas}
                    </div>
                `);

                const tbody = $('#preview-table tbody');
                tbody.empty();
                rows.forEach(r => {
                    const tr = $('<tr>');
                    tr.append($('<td>').text(r.line));
                    tr.append($('<td>').text(r.idusuario || r.identificador || ''));
                    tr.append($('<td>').text(r.nombre || ''));
                    tr.append($('<td>').text(r.nota));
                    tr.append($('<td>').text(r.campo || ('trayecto_' + ($('#trayecto_actual').val() || 0))));
                    tr.append($('<td>').html(r.mensaje));
                    tr.data('row', r);
                    tbody.append(tr);
                });
            })
            .catch(err => {
                $('#preview-summary').html(`<div class="alert alert-danger">Error: ${err.message}</div>`);
            });
    });

    // Aplicar CSV al formulario
    $('#btn-apply-csv').click(function() {
        const rows = [];
        $('#preview-table tbody tr').each(function() {
            const r = $(this).data('row');
            if (r && r.valido) rows.push(r);
        });

        if (rows.length === 0) {
            alert('No hay filas válidas para aplicar');
            return;
        }

        let applied = 0;
        let missing = 0;
        rows.forEach(r => {
            const estudianteId = r.estudiante_id;
            const nota = r.nota;
            const campoTrayecto = r.campo || ('trayecto_' + ($('#trayecto_actual').val() || 0));
            const selector = `input[name="notas[${estudianteId}][${campoTrayecto}]"]`;
            const input = document.querySelector(selector);
            if (input) {
                input.value = nota;
                // Disparar evento change para actualizar cualquier validación
                $(input).trigger('change');
                applied++;
            } else {
                missing++;
            }
        });

        $('#modalPreviewCSV').modal('hide');
        let msg = `✅ Se aplicaron ${applied} notas al formulario.`;
        if (missing) msg += ` ⚠️ ${missing} entradas no se encontraron.`;
        alert(msg);
    });
    
    // Guardar notas
    $(document).on('submit', '#form-notas', function(e) {
        e.preventDefault();
        
        // Confirmar antes de guardar en móvil
        const confirmMsg = confirm('¿Estás seguro de guardar las notas?');
        if (!confirmMsg) return;
        
        $('#resultados').html(`
            <div class="text-right mb-3" id="volver-container">
                <button class="btn btn-secondary btn-block-mobile" id="btn-volver">
                    <i class="fas fa-arrow-left"></i> Volver a Secciones
                </button>
            </div>
            <div class="text-center py-5">
                <div class="spinner-border text-success" style="width: 3rem; height: 3rem;"></div>
                <p class="mt-3">Guardando notas y soporte...</p>
            </div>
        `);
        
        const formData = new FormData(this);
        
        fetch('guardar_notas.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            $('#resultados').html(`
                <div class="text-right mb-3" id="volver-container">
                    <button class="btn btn-secondary btn-block-mobile" id="btn-volver">
                        <i class="fas fa-arrow-left"></i> Volver a Secciones
                    </button>
                </div>
            `);

            const header = $('#modalResultadoHeader');
            const title = $('#modalResultadoTitle');
            const body = $('#modalResultadoBody');

            if (data.success) {
                header.removeClass('bg-danger').addClass('bg-success text-white');
                title.text('Éxito');
                let soporteInfo = '';
                if (data.soporte) {
                    soporteInfo = '<p class="mb-2"><i class="fas fa-check-circle"></i> <strong>Soporte:</strong> Subido correctamente.</p>';
                } else {
                    soporteInfo = '<p class="mb-2 text-warning"><i class="fas fa-info-circle"></i> <strong>Soporte:</strong> No se subió.</p>';
                }
                body.html(soporteInfo + '<div class="alert alert-success">' + data.message + '</div>');
            } else {
                header.removeClass('bg-success').addClass('bg-danger text-white');
                title.text('Error');
                body.html(`<div class="alert alert-danger">${data.message}</div>`);
            }

            $('#modalResultado').modal('show');
        })
        .catch(error => {
            $('#resultados').html(`
                <div class="text-right mb-3" id="volver-container">
                    <button class="btn btn-secondary btn-block-mobile" id="btn-volver">
                        <i class="fas fa-arrow-left"></i> Volver a Secciones
                    </button>
                </div>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error al guardar: ${error.message}
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
                    preview.html(`<img src="${e.target.result}" class="img-fluid img-thumbnail" style="max-height: 150px;">`);
                } else {
                    preview.html(`
                        <div class="alert alert-info text-center">
                            <i class="fas fa-file-pdf fa-3x"></i><br>
                            <strong>${file.name}</strong>
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
    
    // Mejorar experiencia en inputs numéricos en móvil
    $(document).on('focus', 'input[type="number"]', function() {
        $(this).attr('inputmode', 'numeric');
    });
});
</script>

<?php include("includes/footer.php"); ?>