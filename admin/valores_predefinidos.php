<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Administración de Datos Predefinidos";
include('../funciones/functions.php');

if (!isAdmin()) {
    header('location: ../usuario/home.php');
    exit();
}

// Mapeo de tablas a sus campos correspondientes
$tablasCampos = [
    'status' => 'status',
    'estado_civil' => 'estado_civil',
    'tenencia_vivienda' => 'tenencia',
    'tipo_cedula' => 'tipo',
    'tipo_vivienda' => 'vivienda',
    'ingresos' => 'ingreso',
    'genero' => 'genero',
    'tipo_formacion' => 'tipo'
];

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion']) && isset($_POST['tabla']) && array_key_exists($_POST['tabla'], $tablasCampos)) {
        $tabla = $_POST['tabla'];
        $campo = $tablasCampos[$tabla];
        $id = $_POST['id'] ?? '';
        $nuevo_id = $_POST['nuevo_id'] ?? $id;
        $valor = trim($_POST['valor'] ?? '');
        
        try {
            switch ($_POST['accion']) {
                case 'agregar':
                    if (!empty($valor)) {
                        if (!empty($nuevo_id)) {
                            // Verificar si el ID ya existe
                            $check = $db->prepare("SELECT id FROM $tabla WHERE id = ?");
                            $check->bind_param("i", $nuevo_id);
                            $check->execute();
                            $check->store_result();
                            
                            if ($check->num_rows > 0) {
                                $_SESSION['error'] = "Error: El ID $nuevo_id ya existe";
                                header("Location: ".$_SERVER['PHP_SELF']);
                                exit();
                            }
                            
                            $stmt = $db->prepare("INSERT INTO $tabla (id, $campo) VALUES (?, ?)");
                            $stmt->bind_param("is", $nuevo_id, $valor);
                        } else {
                            $stmt = $db->prepare("INSERT INTO $tabla ($campo) VALUES (?)");
                            $stmt->bind_param("s", $valor);
                        }
                        $stmt->execute();
                        $_SESSION['mensaje'] = "Registro agregado correctamente";
                    }
                    break;
                    
                case 'editar':
                    if (($id !== '' && $id !== null) && $valor !== '') {
                        if ($nuevo_id != $id) {
                            // Verificar si el nuevo ID ya existe
                            $check = $db->prepare("SELECT id FROM $tabla WHERE id = ? AND id != ?");
                            $check->bind_param("ii", $nuevo_id, $id);
                            $check->execute();
                            $check->store_result();
                            
                            if ($check->num_rows > 0) {
                                $_SESSION['error'] = "Error: El ID $nuevo_id ya existe";
                                header("Location: ".$_SERVER['PHP_SELF']);
                                exit();
                            }
                            
                            $stmt = $db->prepare("UPDATE $tabla SET id = ?, $campo = ? WHERE id = ?");
                            $stmt->bind_param("isi", $nuevo_id, $valor, $id);
                        } else {
                            $stmt = $db->prepare("UPDATE $tabla SET $campo = ? WHERE id = ?");
                            $stmt->bind_param("si", $valor, $id);
                        }
                        $stmt->execute();
                        $_SESSION['mensaje'] = "Registro actualizado correctamente";
                    }
                    break;
                    
                case 'eliminar':
                    if ($id !== '' && $id !== null) {
                        $stmt = $db->prepare("DELETE FROM $tabla WHERE id = ?");
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $_SESSION['mensaje'] = "Registro eliminado correctamente";
                    }
                    break;
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
        
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

// Obtener todos los datos usando las funciones de functions.php
$tiposCedula = obtenerTiposCedula($db);
$estadosCiviles = obtenerEstadosCiviless($db);
$tiposVivienda = obtenerTiposVivienda($db);
$tenenciasVivienda = obtenerTenenciaViviendas($db);
$opcionesStatus = obtenerOpcionesStatus($db);
$ingresos = obtenerIngresos($db);
$generos = obtenerGeneros($db);
$tiposFormacion = obtenerTiposFormacion($db);

include("includes/head.php");
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?php echo $titulopag; ?></h1>
    
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="status-tab" data-toggle="tab" href="#status" role="tab">Estatus</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="civil-tab" data-toggle="tab" href="#civil" role="tab">Estado Civil</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tenencia-tab" data-toggle="tab" href="#tenencia" role="tab">Tenencia Vivienda</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="cedula-tab" data-toggle="tab" href="#cedula" role="tab">Tipo Cédula</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="vivienda-tab" data-toggle="tab" href="#vivienda" role="tab">Tipo Vivienda</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="ingresos-tab" data-toggle="tab" href="#ingresos" role="tab">Ingresos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="genero-tab" data-toggle="tab" href="#genero" role="tab">Género</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="formacion-tab" data-toggle="tab" href="#formacion" role="tab">Tipo Formación</a>
        </li>
    </ul>
    
    <div class="tab-content" id="myTabContent">
        <!-- Tabla Status -->
        <div class="tab-pane fade show active" id="status" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Estatus</h6>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#agregarStatusModal">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Estatus</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($opcionesStatus as $id => $status): ?>
                                <tr>
                                    <td><?php echo $id; ?></td>
                                    <td><?php echo $status; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editarStatusModal<?php echo $id; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#eliminarStatusModal<?php echo $id; ?>">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla Estado Civil -->
        <div class="tab-pane fade" id="civil" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Estados Civiles</h6>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#agregarCivilModal">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Estado Civil</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($estadosCiviles as $id => $estado): ?>
                                <tr>
                                    <td><?php echo $id; ?></td>
                                    <td><?php echo $estado; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editarCivilModal<?php echo $id; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#eliminarCivilModal<?php echo $id; ?>">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla Tenencia Vivienda -->
        <div class="tab-pane fade" id="tenencia" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Tenencia de Vivienda</h6>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#agregarTenenciaModal">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tenencia</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tenenciasVivienda as $id => $tenencia): ?>
                                <tr>
                                    <td><?php echo $id; ?></td>
                                    <td><?php echo $tenencia; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editarTenenciaModal<?php echo $id; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#eliminarTenenciaModal<?php echo $id; ?>">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla Tipo Cédula -->
        <div class="tab-pane fade" id="cedula" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Tipos de Cédula</h6>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#agregarCedulaModal">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tipo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tiposCedula as $tipo): ?>
                                <tr>
                                    <td><?php echo $tipo['id']; ?></td>
                                    <td><?php echo $tipo['tipo']; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editarCedulaModal<?php echo $tipo['id']; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#eliminarCedulaModal<?php echo $tipo['id']; ?>">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla Tipo Vivienda -->
        <div class="tab-pane fade" id="vivienda" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Tipos de Vivienda</h6>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#agregarViviendaModal">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Vivienda</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tiposVivienda as $id => $vivienda): ?>
                                <tr>
                                    <td><?php echo $id; ?></td>
                                    <td><?php echo $vivienda; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editarViviendaModal<?php echo $id; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#eliminarViviendaModal<?php echo $id; ?>">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla Ingresos -->
        <div class="tab-pane fade" id="ingresos" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Ingresos</h6>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#agregarIngresoModal">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ingreso</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ingresos as $id => $ingreso): ?>
                                <tr>
                                    <td><?php echo $id; ?></td>
                                    <td><?php echo $ingreso; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editarIngresoModal<?php echo $id; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#eliminarIngresoModal<?php echo $id; ?>">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla Género -->
        <div class="tab-pane fade" id="genero" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Géneros</h6>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#agregarGeneroModal">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Género</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($generos as $id => $genero): ?>
                                <tr>
                                    <td><?php echo $id; ?></td>
                                    <td><?php echo htmlspecialchars($genero); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editarGeneroModal<?php echo $id; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#eliminarGeneroModal<?php echo $id; ?>">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla Tipo Formación -->
        <div class="tab-pane fade" id="formacion" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Tipos de Formación</h6>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#agregarFormacionModal">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tipo de Formación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tiposFormacion as $id => $formacion): ?>
                                <tr>
                                    <td><?php echo $id; ?></td>
                                    <td><?php echo $formacion; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editarFormacionModal<?php echo $id; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#eliminarFormacionModal<?php echo $id; ?>">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modales para Status -->
<div class="modal fade" id="agregarStatusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Estatus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="status">
                    <div class="form-group">
                        <label for="nuevo_id_status">ID (opcional)</label>
                        <input type="number" class="form-control" id="nuevo_id_status" name="nuevo_id">
                    </div>
                    <div class="form-group">
                        <label for="valor_status">Estatus</label>
                        <input type="text" class="form-control" id="valor_status" name="valor" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="agregar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php foreach ($opcionesStatus as $id => $status): ?>
<!-- Modal Editar Status -->
<div class="modal fade" id="editarStatusModal<?php echo $id; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Estatus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="status">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="form-group">
                        <label for="valor_status_<?php echo $id; ?>">Estatus</label>
                        <input type="text" class="form-control" id="valor_status_<?php echo $id; ?>" name="valor" value="<?php echo $status; ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="editar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Status -->
<div class="modal fade" id="eliminarStatusModal<?php echo $id; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar Estatus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="status">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <p>¿Está seguro que desea eliminar el estatus "<?php echo $status; ?>"?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="eliminar" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Modales para Estado Civil -->
<div class="modal fade" id="agregarCivilModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Estado Civil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="estado_civil">
                    <div class="form-group">
                        <label for="nuevo_id_civil">ID (opcional)</label>
                        <input type="number" class="form-control" id="nuevo_id_civil" name="nuevo_id">
                    </div>
                    <div class="form-group">
                        <label for="valor_civil">Estado Civil</label>
                        <input type="text" class="form-control" id="valor_civil" name="valor" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="agregar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php foreach ($estadosCiviles as $id => $estado): ?>
<!-- Modal Editar Estado Civil -->
<div class="modal fade" id="editarCivilModal<?php echo $id; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Estado Civil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="estado_civil">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="form-group">
                        <label for="valor_civil_<?php echo $id; ?>">Estado Civil</label>
                        <input type="text" class="form-control" id="valor_civil_<?php echo $id; ?>" name="valor" value="<?php echo $estado; ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="editar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Estado Civil -->
<div class="modal fade" id="eliminarCivilModal<?php echo $id; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar Estado Civil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="estado_civil">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <p>¿Está seguro que desea eliminar el estado civil "<?php echo $estado; ?>"?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="eliminar" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Modales para Tenencia Vivienda -->
<div class="modal fade" id="agregarTenenciaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Tenencia de Vivienda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="tenencia_vivienda">
                    <div class="form-group">
                        <label for="nuevo_id_tenencia">ID (opcional)</label>
                        <input type="number" class="form-control" id="nuevo_id_tenencia" name="nuevo_id">
                    </div>
                    <div class="form-group">
                        <label for="valor_tenencia">Tenencia</label>
                        <input type="text" class="form-control" id="valor_tenencia" name="valor" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="agregar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php foreach ($tenenciasVivienda as $id => $tenencia): ?>
<!-- Modal Editar Tenencia Vivienda -->
<div class="modal fade" id="editarTenenciaModal<?php echo $id; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Tenencia de Vivienda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="tenencia_vivienda">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="form-group">
                        <label for="valor_tenencia_<?php echo $id; ?>">Tenencia</label>
                        <input type="text" class="form-control" id="valor_tenencia_<?php echo $id; ?>" name="valor" value="<?php echo $tenencia; ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="editar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Tenencia Vivienda -->
<div class="modal fade" id="eliminarTenenciaModal<?php echo $id; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar Tenencia de Vivienda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="tenencia_vivienda">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <p>¿Está seguro que desea eliminar la tenencia "<?php echo $tenencia; ?>"?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="eliminar" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Modales para Tipo Cédula -->
<div class="modal fade" id="agregarCedulaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Tipo de Cédula</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="tipo_cedula">
                    <div class="form-group">
                        <label for="nuevo_id_cedula">ID (opcional)</label>
                        <input type="number" class="form-control" id="nuevo_id_cedula" name="nuevo_id">
                    </div>
                    <div class="form-group">
                        <label for="valor_cedula">Tipo de Cédula</label>
                        <input type="text" class="form-control" id="valor_cedula" name="valor" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="agregar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php foreach ($tiposCedula as $tipo): ?>
<!-- Modal Editar Tipo Cédula -->
<div class="modal fade" id="editarCedulaModal<?php echo $tipo['id']; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Tipo de Cédula</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="tipo_cedula">
                    <input type="hidden" name="id" value="<?php echo $tipo['id']; ?>">
                    <div class="form-group">
                        <label for="valor_cedula_<?php echo $tipo['id']; ?>">Tipo de Cédula</label>
                        <input type="text" class="form-control" id="valor_cedula_<?php echo $tipo['id']; ?>" name="valor" value="<?php echo $tipo['tipo']; ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="editar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Tipo Cédula -->
<div class="modal fade" id="eliminarCedulaModal<?php echo $tipo['id']; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar Tipo de Cédula</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="tipo_cedula">
                    <input type="hidden" name="id" value="<?php echo $tipo['id']; ?>">
                    <p>¿Está seguro que desea eliminar el tipo de cédula "<?php echo $tipo['tipo']; ?>"?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="eliminar" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Modales para Tipo Vivienda -->
<div class="modal fade" id="agregarViviendaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Tipo de Vivienda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="tipo_vivienda">
                    <div class="form-group">
                        <label for="nuevo_id_vivienda">ID (opcional)</label>
                        <input type="number" class="form-control" id="nuevo_id_vivienda" name="nuevo_id">
                    </div>
                    <div class="form-group">
                        <label for="valor_vivienda">Tipo de Vivienda</label>
                        <input type="text" class="form-control" id="valor_vivienda" name="valor" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="agregar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php foreach ($tiposVivienda as $id => $vivienda): ?>
<!-- Modal Editar Tipo Vivienda -->
<div class="modal fade" id="editarViviendaModal<?php echo $id; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Tipo de Vivienda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="tipo_vivienda">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="form-group">
                        <label for="valor_vivienda_<?php echo $id; ?>">Tipo de Vivienda</label>
                        <input type="text" class="form-control" id="valor_vivienda_<?php echo $id; ?>" name="valor" value="<?php echo $vivienda; ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="editar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Tipo Vivienda -->
<div class="modal fade" id="eliminarViviendaModal<?php echo $id; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar Tipo de Vivienda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="tipo_vivienda">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <p>¿Está seguro que desea eliminar el tipo de vivienda "<?php echo $vivienda; ?>"?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="eliminar" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Modales para Ingresos -->
<div class="modal fade" id="agregarIngresoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Ingreso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="ingresos">
                    <div class="form-group">
                        <label for="nuevo_id_ingreso">ID (opcional)</label>
                        <input type="number" class="form-control" id="nuevo_id_ingreso" name="nuevo_id">
                    </div>
                    <div class="form-group">
                        <label for="valor_ingreso">Ingreso</label>
                        <input type="text" class="form-control" id="valor_ingreso" name="valor" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="agregar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php foreach ($ingresos as $id => $ingreso): ?>
<!-- Modal Editar Ingreso -->
<div class="modal fade" id="editarIngresoModal<?php echo $id; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Ingreso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="ingresos">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="form-group">
                        <label for="valor_ingreso_<?php echo $id; ?>">Ingreso</label>
                        <input type="text" class="form-control" id="valor_ingreso_<?php echo $id; ?>" name="valor" value="<?php echo $ingreso; ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="editar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Ingreso -->
<div class="modal fade" id="eliminarIngresoModal<?php echo $id; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar Ingreso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="ingresos">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <p>¿Está seguro que desea eliminar el ingreso "<?php echo $ingreso; ?>"?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="eliminar" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Modales para Género -->
<div class="modal fade" id="agregarGeneroModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Género</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="genero">
                    <div class="form-group">
                        <label for="nuevo_id_genero">ID (opcional)</label>
                        <input type="number" class="form-control" id="nuevo_id_genero" name="nuevo_id">
                    </div>
                    <div class="form-group">
                        <label for="valor_genero">Género</label>
                        <input type="text" class="form-control" id="valor_genero" name="valor" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="agregar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php foreach ($generos as $id => $genero): ?>
<!-- Modal Editar Género -->
<div class="modal fade" id="editarGeneroModal<?php echo $id; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Género</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="genero">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="form-group">
                        <label for="valor_genero_<?php echo $id; ?>">Género</label>
                        <input type="text" class="form-control" id="valor_genero_<?php echo $id; ?>" name="valor" value="<?php echo htmlspecialchars($genero); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="editar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Género -->
<div class="modal fade" id="eliminarGeneroModal<?php echo $id; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar Género</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="genero">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <p>¿Está seguro que desea eliminar el género "<?php echo htmlspecialchars($genero); ?>"?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="eliminar" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Modales para Tipo Formación -->
<div class="modal fade" id="agregarFormacionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Tipo de Formación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="tipo_formacion">
                    <div class="form-group">
                        <label for="nuevo_id_formacion">ID (opcional)</label>
                        <input type="number" class="form-control" id="nuevo_id_formacion" name="nuevo_id">
                    </div>
                    <div class="form-group">
                        <label for="valor_formacion">Tipo de Formación</label>
                        <input type="text" class="form-control" id="valor_formacion" name="valor" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="agregar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php foreach ($tiposFormacion as $id => $formacion): ?>
<!-- Modal Editar Tipo Formación -->
<div class="modal fade" id="editarFormacionModal<?php echo $id; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Tipo de Formación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="tipo_formacion">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="form-group">
                        <label for="valor_formacion_<?php echo $id; ?>">Tipo de Formación</label>
                        <input type="text" class="form-control" id="valor_formacion_<?php echo $id; ?>" name="valor" value="<?php echo $formacion; ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="editar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Tipo Formación -->
<div class="modal fade" id="eliminarFormacionModal<?php echo $id; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar Tipo de Formación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tabla" value="tipo_formacion">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <p>¿Está seguro que desea eliminar el tipo de formación "<?php echo $formacion; ?>"?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="accion" value="eliminar" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php include("includes/footer.php"); ?>