<?php
require_once('../funciones/functions.php');

// Verificar ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die('<div class="alert alert-danger">ID de estudiante no válido</div>');
}

// Obtener datos
$estudiante = obtenerEstudiantePorId($id);
if (isset($estudiante['error'])) {
    die('<div class="alert alert-danger">'.$estudiante['error'].'</div>');
}
?>

<div class="modal-header">
    <h5 class="modal-title">Detalles del Estudiante</h5>
    
</div>

<div class="modal-body">
    <!-- Primera fila: Datos básicos -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Identificación</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Nombre completo:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['nombre'] ?? '') ?></dd>

                        <dt class="col-sm-5">Cédula:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['idusuario'] ?? '') ?></dd>

                        <dt class="col-sm-5">ID Sistema:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['id'] ?? '') ?></dd>

                        <dt class="col-sm-5">Usuario:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['username'] ?? '') ?></dd>
                    </dl>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Datos Personales</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Género:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['genero'] ?? '') ?></dd>

                        <dt class="col-sm-5">Estado Civil:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['edo_civil'] ?? '') ?></dd>

                        <dt class="col-sm-5">Fecha Nacimiento:</dt>
                        <dd class="col-sm-7"><?= !empty($estudiante['fecha_nac']) ? date('d/m/Y', strtotime($estudiante['fecha_nac'])) : 'No especificado' ?></dd>

                        <dt class="col-sm-5">Etnia:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['etnia'] ?? 'No especificado') ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

   <!-- Segunda fila: Información académica -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Información Académica</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Carrera:</dt>
                    <dd class="col-sm-7">
                        <?php
                        // Obtener el nombre de la carrera directamente
                        if(isset($estudiante['carrera']) && !empty($estudiante['carrera'])) {
                            global $db;
                            $id_carrera = $estudiante['carrera'];
                            $query = $db->query("SELECT nombre_carrera FROM carreras WHERE id_carrera = $id_carrera");
                            $carrera = $query->fetch_assoc();
                            echo htmlspecialchars($carrera['nombre_carrera'] ?? 'Carrera no encontrada');
                        } else {
                            echo 'No especificada';
                        }
                        ?>
                    </dd>

                    <dt class="col-sm-5">Estado:</dt>
                    <dd class="col-sm-7"><?= ($estudiante['status'] ?? 0) == 1 ? 'Activo' : 'Inactivo' ?></dd>

                    <dt class="col-sm-5">Fecha Ingreso:</dt>
                    <dd class="col-sm-7"><?= !empty($estudiante['fecha_ingreso']) ? date('d/m/Y', strtotime($estudiante['fecha_ingreso'])) : 'No especificado' ?></dd>

                    <dt class="col-sm-5">Última Actualización:</dt>
                    <dd class="col-sm-7"><?= !empty($estudiante['fecha_act']) ? date('d/m/Y', strtotime($estudiante['fecha_act'])) : 'No especificado' ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Historial Académico</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Títulos Obtenidos:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['titulos'] ?? 'Ninguno registrado') ?></dd>

                        <dt class="col-sm-5">Institutos Anteriores:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['institutos'] ?? 'Ninguno registrado') ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Tercera fila: Contacto y salud -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Información de Contacto</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Email:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['email'] ?? 'No especificado') ?></dd>

                        <dt class="col-sm-5">Teléfono Principal:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['tlf'] ?? 'No especificado') ?></dd>

                        <dt class="col-sm-5">Celular:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['cel'] ?? 'No especificado') ?></dd>

                        <dt class="col-sm-5">Teléfono Opcional:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['num_telf_opc'] ?? 'No especificado') ?></dd>
                    </dl>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Datos de Salud</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Enfermedades:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['enfermedad'] ?? 'Ninguna registrada') ?></dd>

                        <dt class="col-sm-5">Discapacidad:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['discapacidad'] ?? 'Ninguna registrada') ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Cuarta fila: Dirección -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Dirección Residencial</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Dirección:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['direccion'] ?? 'No especificado') ?></dd>

                        <dt class="col-sm-5">Casa/Apartamento:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['casaapto'] ?? 'No especificado') ?></dd>

                        <dt class="col-sm-5">Punto de Referencia:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['punto_referencia'] ?? 'No especificado') ?></dd>
                    </dl>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Ubicación Geográfica</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Estado:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['estado'] ?? 'No especificado') ?></dd>

                        <dt class="col-sm-5">Municipio:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['municipio'] ?? 'No especificado') ?></dd>

                        <dt class="col-sm-5">Parroquia:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['parroquia'] ?? 'No especificado') ?></dd>

                        <dt class="col-sm-5">Ciudad:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['ciudad'] ?? 'No especificado') ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Quinta fila: Situación familiar y vivienda -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Situación Familiar</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Grupo Familiar:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['grupo_familiar'] ?? 'No especificado') ?></dd>

                        <dt class="col-sm-5">Personas a Cargo:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['acargo_usted'] ?? 'No especificado') ?></dd>

                        <dt class="col-sm-5">Fuente de Ingresos:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['fuente_ingresos'] ?? 'No especificado') ?></dd>
                    </dl>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Situación de Vivienda</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Tipo de Vivienda:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['tipo_vivienda'] ?? 'No especificado') ?></dd>

                        <dt class="col-sm-5">Tenencia Vivienda:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($estudiante['tenencia_vivienda'] ?? 'No especificado') ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
</div>