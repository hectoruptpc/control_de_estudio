<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Registro de Pagos";
include('../funciones/functions.php');
include("includes/head.php");

// Procesar formulario de pago
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resultado = registrarPago($_POST);
    
    if ($resultado['success']) {
        $mensaje_exito = $resultado['message'];
    } else {
        $mensaje_error = $resultado['message'];
    }
}

// Obtener pagos agrupados por día
$pagos_por_dia = obtenerPagosPorDia();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Encabezado -->
            <div class="page-header d-flex justify-content-between align-items-center mb-4">
                <h1 class="page-title">
                    <i class="fas fa-money-bill-wave text-success"></i> <?php echo $titulopag; ?>
                </h1>
            </div>
            
            <!-- Formulario de registro -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus-circle"></i> Registrar Nuevo Pago
                    </h3>
                </div>
                <div class="card-body">
                    <?php if (isset($mensaje_exito)): ?>
                        <div class="alert alert-success"><?php echo $mensaje_exito; ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($mensaje_error)): ?>
                        <div class="alert alert-danger"><?php echo $mensaje_error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tipo_pago" class="form-label">Tipo de Pago *</label>
                                    <select class="custom-select d-block w-100" id="tipo_pago" name="tipo_pago" required>
                                    <option value="">Seleccione...</option>
                                        <option value="inscripcion">Inscripción</option>
                                        <option value="reincorporacion_estudio_expediente">Reincorporación/Estudio de Expediente</option>
                                        <option value="cambio_programa">Cambio de Programa</option>
                                        <option value="cambio_sede">Cambio de Sede</option>
                                        <option value="inscripcion_pasantias_practica_profesional">Inscripción de Pasantías/Práctica Profesional</option>
                                        <option value="expedicion_constancia_certificada_notas">Expedición de Constancia Certificada de Notas</option>
                                        <option value="expedicion_constancia_simple_notas">Expedición de Constancia Simple de Notas</option>
                                        <option value="expedicion_constancia_buena_conducta">Expedición de Constancia de Buena Conducta</option>
                                        <option value="expedicion_constancia_culminacion_academica">Expedición de Constancia de Culminación Académica</option>
                                        <option value="expedicion_constancia_estudios">Expedición de Constancia de Estudios</option>
                                        <option value="expedicion_constancia_inscripcion">Expedición de Constancia de Inscripción</option>
                                        <option value="expedicion_constancia_servicio_comunitario">Expedición de Constancia de Servicio Comunitario</option>
                                        <option value="carnet_estudiantil">Carnet Estudiantil</option>
                                        <option value="uniforme_franela_estudiantil">Uniforme (Franela) Estudiantil</option>
                                        <option value="certificado_titulo">Certificado de Título</option>
                                        <option value="autenticacion_titulo">Autenticación de Título</option>
                                        <option value="pensum_estudios_certificados">Pensum de Estudios Certificados</option>
                                        <option value="programas_analiticos_vigencia_programas">Programas Analíticos/Vigencia de Programas</option>
                                        <option value="expedicion_constancia_modalidad_estudios">Expedición de Constancia de Modalidad de Estudios</option>
                                        <option value="certificacion_acta_grado">Certificación de Acta de Grado</option>
                                        <option value="grado_titulo_medalla_notas_certificadas_ubicacion_rango_buena_conducta_servicio_comunitario">Grado</option>
                                        <option value="derecho_grado">Derecho a Grado</option>
                                        <option value="certificacion_saberes">Certificación de Saberes</option>
                                        <option value="examen_suficiencia">Examen de Suficiencia</option>
                                        <option value="examen_extraordinario">Examen Extraordinario</option>
                                        <option value="cursos">Cursos</option>
                                        <option value="talleres">Talleres</option>
                                        <option value="diplomado">Diplomado</option>
                                        <option value="especializacion">Especialización</option>
                                        <option value="maestria">Maestría</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3" id="otro_concepto_container" style="display: none;">
                                    <label for="otro_concepto" class="form-label">Especifique el concepto *</label>
                                    <input type="text" class="form-control" id="otro_concepto" name="otro_concepto">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="monto" class="form-label">Monto *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" class="form-control" id="monto" name="monto" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                            <div class="mb-3">
    <label for="buscar_estudiante" class="form-label">Buscar Estudiante por Cédula (opcional)</label>
    <div class="input-group">
        <input type="text" class="form-control" id="buscar_estudiante" 
               placeholder="Ingrese cédula del estudiante" autocomplete="off">
        <button class="btn btn-outline-secondary" type="button" id="btn-buscar-estudiante">
            <i class="fas fa-search"></i> Buscar
        </button>
    </div>
    <div id="resultados-busqueda" class="mt-2" style="display: none;">
        <div class="list-group" id="lista-estudiantes"></div>
    </div>
    <input type="hidden" id="estudiante_id" name="estudiante_id" value="">
    <div id="estudiante-seleccionado" class="mt-2" style="display: none;">
        <div class="alert alert-info p-2 d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-user-check"></i> 
                <span id="estudiante-info"></span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger" id="quitar-estudiante">
                <i class="fas fa-times"></i> Quitar
            </button>
        </div>
    </div>
</div>
                                
<div class="mb-3">
    <label for="referencia" class="form-label">Referencia</label>
    <textarea class="form-control" id="referencia" name="referencia" rows="2"></textarea>
</div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Registrar Pago
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Listado de pagos por día -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-list"></i> Historial de Pagos
                    </h3>
                </div>
                <div class="card-body">
                    <?php if (!$pagos_por_dia || count($pagos_por_dia) === 0): ?>
                        <div class="alert alert-info">No hay pagos registrados</div>
                    <?php else: ?>
                        <?php foreach ($pagos_por_dia as $dia): ?>
                            <div class="mb-4 border-bottom pb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h4 class="mb-0">
                                        <i class="far fa-calendar-alt text-primary"></i> 
                                        <?php echo date('d/m/Y', strtotime($dia['dia'])); ?>
                                    </h4>
                                    <span class="badge bg-primary rounded-pill">
                                        <?php echo $dia['cantidad_pagos']; ?> pago(s) - Total: $<?php echo number_format($dia['total_dia'], 2, ',', '.'); ?>
                                    </span>
                                </div>
                                
                                <?php $detalles_pagos = obtenerDetallesPagos($dia['dia']); ?>
                                <?php if ($detalles_pagos && count($detalles_pagos) > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                <th>Concepto</th>
            <th>Estudiante</th>
            <th>Monto</th>
            <th>Hora</th>
            <th>Referencia</th>
            <th>Registrado por</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($detalles_pagos as $pago): ?>
                                                    <tr>
                                                        <td>
                                                            <?php 
                                                             $conceptos = [
                                                                'inscripcion' => 'Inscripción',
                                                                'reincorporacion_estudio_expediente' => 'Reincorporación/Estudio de Expediente',
                                                                'cambio_programa' => 'Cambio de Programa',
                                                                'cambio_sede' => 'Cambio de Sede',
                                                                'inscripcion_pasantias_practica_profesional' => 'Inscripción de Pasantías/Práctica Profesional',
                                                                'expedicion_constancia_certificada_notas' => 'Expedición de Constancia Certificada de Notas',
                                                                'expedicion_constancia_simple_notas' => 'Expedición de Constancia Simple de Notas',
                                                                'expedicion_constancia_buena_conducta' => 'Expedición de Constancia de Buena Conducta',
                                                                'expedicion_constancia_culminacion_academica' => 'Expedición de Constancia de Culminación Académica',
                                                                'expedicion_constancia_estudios' => 'Expedición de Constancia de Estudios',
                                                                'expedicion_constancia_inscripcion' => 'Expedición de Constancia de Inscripción',
                                                                'expedicion_constancia_servicio_comunitario' => 'Expedición de Constancia de Servicio Comunitario',
                                                                'carnet_estudiantil' => 'Carnet Estudiantil',
                                                                'uniforme_franela_estudiantil' => 'Uniforme (Franela) Estudiantil',
                                                                'certificado_titulo' => 'Certificado de Título',
                                                                'autenticacion_titulo' => 'Autenticación de Título',
                                                                'pensum_estudios_certificados' => 'Pensum de Estudios Certificados',
                                                                'programas_analiticos_vigencia_programas' => 'Programas Analíticos/Vigencia de Programas',
                                                                'expedicion_constancia_modalidad_estudios' => 'Expedición de Constancia de Modalidad de Estudios',
                                                                'certificacion_acta_grado' => 'Certificación de Acta de Grado',
                                                                'grado_titulo_medalla_notas_certificadas_ubicacion_rango_buena_conducta_servicio_comunitario' => 'Grado (Título, Medalla, Notas Certificadas, Ubicación y Rango, Buena Conducta, Servicio Comunitario)',
                                                                'derecho_grado' => 'Derecho a Grado',
                                                                'certificacion_saberes' => 'Certificación de Saberes',
                                                                'examen_suficiencia' => 'Examen de Suficiencia',
                                                                'examen_extraordinario' => 'Examen Extraordinario',
                                                                'cursos' => 'Cursos',
                                                                'talleres' => 'Talleres',
                                                                'diplomado' => 'Diplomado',
                                                                'especializacion' => 'Especialización',
                                                                'maestria' => 'Maestría'
                                                            ];
                                                            
                                                            echo $pago['tipo_pago'] == 'otro' ? $pago['otro_concepto'] : ($conceptos[$pago['tipo_pago']] ?? $pago['tipo_pago']);
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $pago['estudiante_id'] ? "{$pago['estudiante_nombre']}" : 'N/A'; ?>
                                                        </td>
                                                        <td>$<?php echo number_format($pago['monto'], 2, ',', '.'); ?></td>
                                                        <td><?php echo date('H:i', strtotime($pago['fecha_pago'])); ?></td>
                                                        <td><?php echo $pago['referencia'] ?? 'N/A'; ?></td>
                                                        <td><?php echo $pago['registrado_por_nombre'] ?? 'Sistema'; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-warning">No hay detalles de pagos para este día</div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Mostrar/ocultar campo "otro concepto" según selección
document.getElementById('tipo_pago').addEventListener('change', function() {
    const otroContainer = document.getElementById('otro_concepto_container');
    otroContainer.style.display = this.value === 'otro' ? 'block' : 'none';
    
    if (this.value !== 'otro') {
        document.getElementById('otro_concepto').value = '';
    }
});



// Buscar estudiante por cédula
document.getElementById('btn-buscar-estudiante').addEventListener('click', buscarEstudiante);
document.getElementById('buscar_estudiante').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        buscarEstudiante();
    }
});

function buscarEstudiante() {
    const cedula = document.getElementById('buscar_estudiante').value.trim();
    
    if (cedula.length < 2) {
        alert('Por favor ingrese al menos 2 caracteres para buscar');
        return;
    }
    
    fetch(`buscar_estudiante.php?cedula=${encodeURIComponent(cedula)}`)
        .then(response => response.json())
        .then(data => {
            const lista = document.getElementById('lista-estudiantes');
            lista.innerHTML = '';
            
            if (data.length === 0) {
                lista.innerHTML = '<div class="list-group-item">No se encontraron estudiantes</div>';
            } else {
                data.forEach(estudiante => {
                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = 'list-group-item list-group-item-action';
                    item.innerHTML = `<strong>${estudiante.cedula}</strong> - ${estudiante.nombre}`;
                    item.onclick = function() {
                        seleccionarEstudiante(estudiante);
                    };
                    lista.appendChild(item);
                });
            }
            
            document.getElementById('resultados-busqueda').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error al buscar el estudiante');
        });
}

function seleccionarEstudiante(estudiante) {
    document.getElementById('estudiante_id').value = estudiante.id;
    document.getElementById('estudiante-info').textContent = 
        `${estudiante.cedula} - ${estudiante.nombre}`;
    document.getElementById('estudiante-seleccionado').style.display = 'block';
    document.getElementById('resultados-busqueda').style.display = 'none';
    document.getElementById('buscar_estudiante').value = '';
}

document.getElementById('quitar-estudiante').addEventListener('click', function() {
    document.getElementById('estudiante_id').value = '';
    document.getElementById('estudiante-seleccionado').style.display = 'none';
    document.getElementById('buscar_estudiante').value = '';
    document.getElementById('buscar_estudiante').focus();
});





</script>

<?php include("includes/footer.php"); ?>