<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');



// Verificar si el usuario es superusuario
if (!isset($_SESSION['superuser']) || $_SESSION['superuser'] !== true) {
    header("Location: login.php");
    exit();
}

$titulopag = "Administración de Datos Predefinidos";
include('../funciones/functions.php');


// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $tabla = $_POST['tabla'] ?? '';
        $id = $_POST['id'] ?? '';
        $valor = trim($_POST['valor'] ?? '');
        
        try {
            switch ($_POST['accion']) {
                case 'agregar':
                    if (!empty($valor)) {
                        $stmt = $db->prepare("INSERT INTO $tabla (".$tabla.") VALUES (?)");
                        $stmt->bind_param("s", $valor);
                        $stmt->execute();
                        $_SESSION['mensaje'] = "Registro agregado correctamente";
                    }
                    break;
                    
                case 'editar':
                    if (!empty($id) && !empty($valor)) {
                        $campo = ($tabla === 'status') ? 'status' : $tabla;
                        $stmt = $db->prepare("UPDATE $tabla SET $campo = ? WHERE id = ?");
                        $stmt->bind_param("si", $valor, $id);
                        $stmt->execute();
                        $_SESSION['mensaje'] = "Registro actualizado correctamente";
                    }
                    break;
                    
                case 'eliminar':
                    if (!empty($id)) {
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

// Obtener todos los datos
$tiposCedula = obtenerTiposCedula($db);
$estadosCiviles = obtenerEstadosCiviless($db);
$tiposVivienda = obtenerTiposVivienda($db);
$tenenciasVivienda = obtenerTenenciaViviendas($db);
$opcionesStatus = obtenerOpcionesStatus($db);

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
    </ul>
    
    <div class="tab-content" id="myTabContent">
        <!-- Tabla Status -->
        <div class="tab-pane fade show active" id="status" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Estatus</h6>
                </div>
                <div class="card-body">
                    <form method="POST" class="mb-4">
                        <input type="hidden" name="tabla" value="status">
                        <div class="form-row">
                            <div class="col-md-8">
                                <input type="text" name="valor" class="form-control" placeholder="Nuevo estatus" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" name="accion" value="agregar" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                    </form>
                    
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
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="tabla" value="status">
                                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                                            <input type="text" name="valor" value="<?php echo $status; ?>" class="form-control d-inline w-auto">
                                            <button type="submit" name="accion" value="editar" class="btn btn-sm btn-warning">Editar</button>
                                            <button type="submit" name="accion" value="eliminar" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro?')">Eliminar</button>
                                        </form>
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
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Estados Civiles</h6>
                </div>
                <div class="card-body">
                    <form method="POST" class="mb-4">
                        <input type="hidden" name="tabla" value="estado_civil">
                        <div class="form-row">
                            <div class="col-md-8">
                                <input type="text" name="valor" class="form-control" placeholder="Nuevo estado civil" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" name="accion" value="agregar" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                    </form>
                    
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
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="tabla" value="estado_civil">
                                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                                            <input type="text" name="valor" value="<?php echo $estado; ?>" class="form-control d-inline w-auto">
                                            <button type="submit" name="accion" value="editar" class="btn btn-sm btn-warning">Editar</button>
                                            <button type="submit" name="accion" value="eliminar" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro?')">Eliminar</button>
                                        </form>
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
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tenencia de Vivienda</h6>
                </div>
                <div class="card-body">
                    <form method="POST" class="mb-4">
                        <input type="hidden" name="tabla" value="tenencia_vivienda">
                        <div class="form-row">
                            <div class="col-md-8">
                                <input type="text" name="valor" class="form-control" placeholder="Nueva tenencia" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" name="accion" value="agregar" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                    </form>
                    
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
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="tabla" value="tenencia_vivienda">
                                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                                            <input type="text" name="valor" value="<?php echo $tenencia; ?>" class="form-control d-inline w-auto">
                                            <button type="submit" name="accion" value="editar" class="btn btn-sm btn-warning">Editar</button>
                                            <button type="submit" name="accion" value="eliminar" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro?')">Eliminar</button>
                                        </form>
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
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tipos de Cédula</h6>
                </div>
                <div class="card-body">
                    <form method="POST" class="mb-4">
                        <input type="hidden" name="tabla" value="tipo_cedula">
                        <div class="form-row">
                            <div class="col-md-8">
                                <input type="text" name="valor" class="form-control" placeholder="Nuevo tipo de cédula" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" name="accion" value="agregar" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                    </form>
                    
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
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="tabla" value="tipo_cedula">
                                            <input type="hidden" name="id" value="<?php echo $tipo['id']; ?>">
                                            <input type="text" name="valor" value="<?php echo $tipo['tipo']; ?>" class="form-control d-inline w-auto">
                                            <button type="submit" name="accion" value="editar" class="btn btn-sm btn-warning">Editar</button>
                                            <button type="submit" name="accion" value="eliminar" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro?')">Eliminar</button>
                                        </form>
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
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tipos de Vivienda</h6>
                </div>
                <div class="card-body">
                    <form method="POST" class="mb-4">
                        <input type="hidden" name="tabla" value="tipo_vivienda">
                        <div class="form-row">
                            <div class="col-md-8">
                                <input type="text" name="valor" class="form-control" placeholder="Nuevo tipo de vivienda" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" name="accion" value="agregar" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                    </form>
                    
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
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="tabla" value="tipo_vivienda">
                                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                                            <input type="text" name="valor" value="<?php echo $vivienda; ?>" class="form-control d-inline w-auto">
                                            <button type="submit" name="accion" value="editar" class="btn btn-sm btn-warning">Editar</button>
                                            <button type="submit" name="accion" value="eliminar" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro?')">Eliminar</button>
                                        </form>
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

<?php include("includes/footer.php"); ?>