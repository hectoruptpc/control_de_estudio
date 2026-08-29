<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once('../funciones/functions.php');

// CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('tipos_pago');

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

// Asegurar token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
$success = '';

// ====================================================
// CONTROLADORES POST (ARANCELES, BANCOS Y MÉTODOS)
// ====================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Error de seguridad. Token CSRF inválido.";
    } 
    // 1. Guardar Tipo de Pago / Arancel (Crear / Editar)
    elseif (isset($_POST['guardar_tipo_pago'])) {
        $id       = intval($_POST['tipo_pago_id'] ?? 0);
        $tipopago = trim($_POST['tipopago'] ?? '');
        $precio   = floatval($_POST['precio'] ?? 0);
        $status   = isset($_POST['status']) ? intval($_POST['status']) : 1;
        
        $validacion = validarTipoPago($tipopago, $precio);
        if (!$validacion['success']) {
            $error = $validacion['message'];
        } else {
            if ($id <= 0) {
                $resp = crearTipoPago($tipopago, $precio, $status);
            } else {
                $resp = actualizarTipoPago($id, $tipopago, $precio, $status);
            }
            if ($resp['success']) {
                $success = $resp['message'];
            } else {
                $error = $resp['message'];
            }
        }
    }
    // 2. Cambiar Estado de Tipo de Pago (Habilitar / Deshabilitar)
    elseif (isset($_POST['toggle_status_tipo_pago'])) {
        $id = intval($_POST['id']);
        $nuevo_status = intval($_POST['nuevo_status']);
        if (cambiarEstadoTipoPago($id, $nuevo_status)) {
            $success = "Estado del arancel actualizado exitosamente.";
        } else {
            $error = "No se pudo cambiar el estado.";
        }
    }
    // 3. Eliminar Tipo de Pago
    elseif (isset($_POST['eliminar_tipo_pago'])) {
        $id = intval($_POST['id']);
        $resp = eliminarTipoPago($id);
        if ($resp['success']) {
            $success = $resp['message'];
        } else {
            $error = $resp['message'];
        }
    }
    // 4. Guardar Método de Pago (Crear / Editar)
    elseif (isset($_POST['guardar_metodo_pago'])) {
        $id                   = intval($_POST['metodo_id'] ?? 0);
        $nombre               = trim($_POST['nombre'] ?? '');
        $codigo               = trim($_POST['codigo'] ?? '');
        $icono                = trim($_POST['icono'] ?? 'fas fa-money-check-alt');
        $descripcion          = trim($_POST['descripcion'] ?? '');
        $requiere_banco       = isset($_POST['requiere_banco']) ? intval($_POST['requiere_banco']) : 0;
        $requiere_comprobante = isset($_POST['requiere_comprobante']) ? intval($_POST['requiere_comprobante']) : 1;
        $status               = isset($_POST['status']) ? intval($_POST['status']) : 1;
        
        if (empty($nombre)) {
            $error = "El nombre del método de pago es requerido.";
        } else {
            if ($id <= 0) {
                $resp = crearMetodoPago($nombre, $codigo, $icono, $descripcion, $requiere_banco, $requiere_comprobante, $status);
            } else {
                $resp = actualizarMetodoPago($id, $nombre, $icono, $descripcion, $requiere_banco, $requiere_comprobante, $status);
            }
            if ($resp['success']) {
                $success = $resp['message'];
            } else {
                $error = $resp['message'];
            }
        }
    }
    // 5. Cambiar Estado de Método de Pago (Habilitar / Deshabilitar)
    elseif (isset($_POST['toggle_status_metodo_pago'])) {
        $id = intval($_POST['id']);
        $nuevo_status = intval($_POST['nuevo_status']);
        if (cambiarEstadoMetodoPago($id, $nuevo_status)) {
            $success = "Estado del método de pago actualizado exitosamente.";
        } else {
            $error = "No se pudo cambiar el estado del método.";
        }
    }
    // 6. Eliminar Método de Pago
    elseif (isset($_POST['eliminar_metodo_pago'])) {
        $id = intval($_POST['id']);
        $resp = eliminarMetodoPago($id);
        if ($resp['success']) {
            $success = $resp['message'];
        } else {
            $error = $resp['message'];
        }
    }
    // 7. Guardar Banco (Crear / Editar)
    elseif (isset($_POST['guardar_banco'])) {
        $id                  = intval($_POST['banco_id'] ?? 0);
        $nombre_banco        = trim($_POST['nombre_banco'] ?? '');
        $codigo_banco        = trim($_POST['codigo_banco'] ?? '');
        $tipo_cuenta         = trim($_POST['tipo_cuenta'] ?? 'Corriente');
        $numero_cuenta       = trim($_POST['numero_cuenta'] ?? '');
        $titular             = trim($_POST['titular'] ?? '');
        $rif_cedula          = trim($_POST['rif_cedula'] ?? '');
        $telefono_pago_movil = trim($_POST['telefono_pago_movil'] ?? '');
        $status              = isset($_POST['status']) ? intval($_POST['status']) : 1;
        
        if (empty($nombre_banco)) {
            $error = "El nombre del banco es requerido.";
        } else {
            if ($id <= 0) {
                $resp = crearBanco($nombre_banco, $codigo_banco, $tipo_cuenta, $numero_cuenta, $titular, $rif_cedula, $telefono_pago_movil, $status);
            } else {
                $resp = actualizarBanco($id, $nombre_banco, $codigo_banco, $tipo_cuenta, $numero_cuenta, $titular, $rif_cedula, $telefono_pago_movil, $status);
            }
            if ($resp['success']) {
                $success = $resp['message'];
            } else {
                $error = $resp['message'];
            }
        }
    }
    // 8. Cambiar Estado Independiente por Canal (Pago Móvil / Transferencia)
    elseif (isset($_POST['toggle_status_canal_banco'])) {
        $id           = intval($_POST['id']);
        $canal        = trim($_POST['canal'] ?? '');
        $nuevo_status = intval($_POST['nuevo_status']);
        if (cambiarEstadoCanalBanco($id, $canal, $nuevo_status)) {
            $nombre_canal = ($canal === 'pago_movil') ? 'Pago Móvil' : 'Transferencia';
            $success = "Estado de {$nombre_canal} para este banco actualizado exitosamente.";
        } else {
            $error = "No se pudo cambiar el estado.";
        }
    }
    // 9. Cambiar Estado General de Banco en Directorio
    elseif (isset($_POST['toggle_status_banco'])) {
        $id = intval($_POST['id']);
        $nuevo_status = intval($_POST['nuevo_status']);
        if (cambiarEstadoBanco($id, $nuevo_status)) {
            $success = "Estado general del banco actualizado exitosamente.";
        } else {
            $error = "No se pudo cambiar el estado del banco.";
        }
    }
    // 10. Eliminar Banco
    elseif (isset($_POST['eliminar_banco'])) {
        $id = intval($_POST['id']);
        $resp = eliminarBanco($id);
        if ($resp['success']) {
            $success = $resp['message'];
        } else {
            $error = $resp['message'];
        }
    }
}

// Consultar registros
$tipos_pago   = obtenerTiposPago(false);
$bancos       = obtenerBancos(false);
$metodos_pago = obtenerMetodosPago(false);

// Filtrar bancos por capacidades
$bancos_con_pago_movil = array_filter($bancos, function($b) {
    return !empty(trim($b['telefono_pago_movil'] ?? ''));
});
$bancos_con_transferencia = array_filter($bancos, function($b) {
    return !empty(trim($b['numero_cuenta'] ?? ''));
});

$titulopag = "Gestión de Métodos de Pago, Aranceles y Bancos - Super Usuario";
include("includes/head.php");
?>

<style>
.bank-card {
    border-radius: 14px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    position: relative;
}
.bank-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
}
.bank-card-header-pm {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #ffffff;
    padding: 16px 20px;
}
.bank-card-header-trans {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #ffffff;
    padding: 16px 20px;
}
.bank-card-header-inactive {
    background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%) !important;
}
.copy-badge {
    cursor: pointer;
    transition: all 0.2s;
    background: #f1f5f9;
    border: 1px dashed #cbd5e1;
    color: #334155;
    padding: 6px 12px;
    border-radius: 8px;
    font-family: monospace;
    font-size: 0.95rem;
}
.copy-badge:hover {
    background: #e2e8f0;
    border-color: #94a3b8;
    color: #0f172a;
}
.nav-pills-custom .nav-link {
    border-radius: 10px;
    font-weight: 600;
    color: #475569;
    padding: 12px 20px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    margin-right: 8px;
    margin-bottom: 8px;
    transition: all 0.2s;
}
.nav-pills-custom .nav-link.active {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}
.nav-pills-custom .nav-link:hover:not(.active) {
    background: #f1f5f9;
    color: #1e293b;
}
</style>

<div class="container-fluid px-2 px-sm-3 px-md-4 py-3">
    <!-- Cabecera y Botones de Acción Rápida -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center my-3 pb-2 border-bottom">
        <div>
            <h2 class="mb-1 text-dark font-weight-bold" style="font-size: 1.5rem;">
                <i class="fas fa-wallet text-primary mr-2"></i>Métodos de Pago, Aranceles y Bancos
            </h2>
            <p class="text-muted small mb-0">Administre los métodos de pago habilitados, cuentas institucionales independientes, aranceles y el directorio bancario.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <button type="button" class="btn btn-outline-primary shadow-sm font-weight-bold mr-2" data-toggle="modal" data-target="#modalTipoPago">
                <i class="fas fa-plus-circle mr-1"></i> Nuevo Arancel
            </button>
            <button type="button" class="btn btn-info shadow-sm font-weight-bold mr-2 text-white" data-toggle="modal" data-target="#modalMetodoPago">
                <i class="fas fa-money-check-alt mr-1"></i> Nuevo Método de Pago
            </button>
            <button type="button" class="btn btn-success shadow-sm font-weight-bold" data-toggle="modal" data-target="#modalBanco">
                <i class="fas fa-university mr-1"></i> Registrar Banco / Cuenta
            </button>
        </div>
    </div>
    
    <!-- Mensajes de Alerta -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-1"></i> <?= htmlspecialchars($error); ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-1"></i> <?= htmlspecialchars($success); ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    <?php endif; ?>

    <!-- Pestañas Principales -->
    <ul class="nav nav-pills nav-pills-custom mb-4" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="tab-trans-link" data-toggle="pill" href="#tab-transferencias" role="tab">
                <i class="fas fa-university mr-2 text-primary"></i> Transferencias (UPTPC)
                <span class="badge badge-light ml-1 text-dark"><?= count($bancos_con_transferencia) ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-pm-link" data-toggle="pill" href="#tab-pago-movil" role="tab">
                <i class="fas fa-mobile-alt mr-2 text-success"></i> Pago Móvil (UPTPC)
                <span class="badge badge-light ml-1 text-dark"><?= count($bancos_con_pago_movil) ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-metodos-link" data-toggle="pill" href="#tab-metodos" role="tab">
                <i class="fas fa-sliders-h mr-2 text-info"></i> Métodos y Formas de Pago
                <span class="badge badge-light ml-1 text-dark"><?= count($metodos_pago) ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-aranceles-link" data-toggle="pill" href="#tab-aranceles" role="tab">
                <i class="fas fa-tags mr-2 text-warning"></i> Catálogo de Aranceles
                <span class="badge badge-light ml-1 text-dark"><?= count($tipos_pago) ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-directorio-link" data-toggle="pill" href="#tab-directorio-bancos" role="tab">
                <i class="fas fa-landmark mr-2 text-secondary"></i> Directorio de Bancos
                <span class="badge badge-light ml-1 text-dark"><?= count($bancos) ?></span>
            </a>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        <!-- ======================================================== -->
        <!-- PESTAÑA: PAGO MÓVIL UPTPC -->
        <!-- ======================================================== -->
        <div class="tab-pane fade" id="tab-pago-movil" role="tabpanel">
            <div class="row">
                <?php if (!empty($bancos_con_pago_movil)): ?>
                    <?php foreach ($bancos_con_pago_movil as $b): 
                        $pm_activo = !empty($b['status_pago_movil']);
                    ?>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="bank-card shadow-sm h-100">
                                <div class="bank-card-header-pm <?= !$pm_activo ? 'bank-card-header-inactive' : '' ?> d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0 font-weight-bold"><i class="fas fa-landmark mr-2"></i><?= htmlspecialchars($b['nombre_banco']) ?></h5>
                                        <?php if (!empty($b['codigo_banco'])): ?>
                                            <span class="badge badge-light text-dark">Código: <?= htmlspecialchars($b['codigo_banco']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="badge <?= $pm_activo ? 'badge-light text-success font-weight-bold' : 'badge-dark' ?>">
                                        <?= $pm_activo ? 'Pago Móvil Activo' : 'Pago Móvil Inactivo' ?>
                                    </span>
                                </div>
                                <div class="card-body p-3">
                                    <div class="mb-3">
                                        <label class="text-muted small font-weight-bold mb-1 d-block">TELÉFONO PAGO MÓVIL:</label>
                                        <div class="copy-badge d-flex justify-content-between align-items-center" onclick="copiarTexto('<?= htmlspecialchars($b['telefono_pago_movil']) ?>', this)">
                                            <span class="font-weight-bold text-success" style="font-size: 1.1rem;"><?= htmlspecialchars($b['telefono_pago_movil']) ?></span>
                                            <i class="fas fa-copy text-muted" title="Copiar teléfono"></i>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label class="text-muted small font-weight-bold mb-1 d-block">CÉDULA / RIF:</label>
                                            <div class="copy-badge d-flex justify-content-between align-items-center" onclick="copiarTexto('<?= htmlspecialchars($b['rif_cedula'] ?: 'G-20005608-8') ?>', this)">
                                                <span class="font-weight-bold"><?= htmlspecialchars($b['rif_cedula'] ?: 'G-20005608-8') ?></span>
                                                <i class="fas fa-copy text-muted"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label class="text-muted small font-weight-bold mb-1 d-block">TITULAR:</label>
                                            <div class="text-truncate font-weight-bold text-dark" title="<?= htmlspecialchars($b['titular'] ?: 'UPTPC') ?>">
                                                <?= htmlspecialchars($b['titular'] ?: 'UPTPC') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-light border-top p-2 d-flex justify-content-between align-items-center">
                                    <form method="POST" action="" class="d-inline mb-0">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                        <input type="hidden" name="canal" value="pago_movil">
                                        <input type="hidden" name="nuevo_status" value="<?= $pm_activo ? '0' : '1' ?>">
                                        <button type="submit" name="toggle_status_canal_banco" class="btn btn-sm <?= $pm_activo ? 'btn-outline-success' : 'btn-outline-secondary' ?>">
                                            <i class="fas <?= $pm_activo ? 'fa-toggle-on' : 'fa-toggle-off' ?> mr-1"></i>
                                            <?= $pm_activo ? 'Habilitado' : 'Deshabilitado' ?>
                                        </button>
                                    </form>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-editar-banco-modal" 
                                                data-id="<?= $b['id'] ?>" 
                                                data-nombre="<?= htmlspecialchars($b['nombre_banco']) ?>" 
                                                data-codigo="<?= htmlspecialchars($b['codigo_banco'] ?? '') ?>" 
                                                data-tipo="<?= htmlspecialchars($b['tipo_cuenta'] ?? 'Corriente') ?>" 
                                                data-numero="<?= htmlspecialchars($b['numero_cuenta'] ?? '') ?>" 
                                                data-titular="<?= htmlspecialchars($b['titular'] ?? '') ?>" 
                                                data-rif="<?= htmlspecialchars($b['rif_cedula'] ?? '') ?>" 
                                                data-telefono="<?= htmlspecialchars($b['telefono_pago_movil'] ?? '') ?>" 
                                                data-status="<?= $b['status'] ?? 1 ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-abrir-eliminar-banco"
                                                data-id="<?= $b['id'] ?>"
                                                data-nombre="<?= htmlspecialchars($b['nombre_banco']) ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="fas fa-mobile-alt fa-3x mb-3 text-muted"></i>
                        <h5>No hay cuentas con Pago Móvil configuradas.</h5>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- PESTAÑA: TRANSFERENCIAS BANCARIAS UPTPC (PREDETERMINADA) -->
        <!-- ======================================================== -->
        <div class="tab-pane fade show active" id="tab-transferencias" role="tabpanel">
            <div class="row">
                <?php if (!empty($bancos_con_transferencia)): ?>
                    <?php foreach ($bancos_con_transferencia as $b): 
                        $trans_activa = !empty($b['status_transferencia']);
                    ?>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="bank-card shadow-sm h-100">
                                <div class="bank-card-header-trans <?= !$trans_activa ? 'bank-card-header-inactive' : '' ?> d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0 font-weight-bold"><i class="fas fa-university mr-2"></i><?= htmlspecialchars($b['nombre_banco']) ?></h5>
                                        <span class="badge badge-light text-primary"><?= htmlspecialchars($b['tipo_cuenta'] ?: 'Cuenta Corriente') ?></span>
                                    </div>
                                    <span class="badge <?= $trans_activa ? 'badge-light text-primary font-weight-bold' : 'badge-dark' ?>">
                                        <?= $trans_activa ? 'Transferencia Activa' : 'Transferencia Inactiva' ?>
                                    </span>
                                </div>
                                <div class="card-body p-3">
                                    <div class="mb-3">
                                        <label class="text-muted small font-weight-bold mb-1 d-block">NÚMERO DE CUENTA (20 DÍGITOS):</label>
                                        <div class="copy-badge d-flex justify-content-between align-items-center" onclick="copiarTexto('<?= htmlspecialchars($b['numero_cuenta']) ?>', this)">
                                            <span class="font-weight-bold text-primary" style="letter-spacing: 1px;"><?= htmlspecialchars($b['numero_cuenta']) ?></span>
                                            <i class="fas fa-copy text-muted" title="Copiar cuenta"></i>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <label class="text-muted small font-weight-bold mb-1 d-block">TITULAR:</label>
                                            <div class="text-truncate font-weight-bold text-dark"><?= htmlspecialchars($b['titular'] ?: 'UPTPC') ?></div>
                                        </div>
                                        <div class="col-6">
                                            <label class="text-muted small font-weight-bold mb-1 d-block">RIF INSTITUCIONAL:</label>
                                            <div class="font-weight-bold text-dark"><?= htmlspecialchars($b['rif_cedula'] ?: 'G-20005608-8') ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-light border-top p-2 d-flex justify-content-between align-items-center">
                                    <form method="POST" action="" class="d-inline mb-0">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                        <input type="hidden" name="canal" value="transferencia">
                                        <input type="hidden" name="nuevo_status" value="<?= $trans_activa ? '0' : '1' ?>">
                                        <button type="submit" name="toggle_status_canal_banco" class="btn btn-sm <?= $trans_activa ? 'btn-outline-primary' : 'btn-outline-secondary' ?>">
                                            <i class="fas <?= $trans_activa ? 'fa-toggle-on' : 'fa-toggle-off' ?> mr-1"></i>
                                            <?= $trans_activa ? 'Habilitada' : 'Deshabilitada' ?>
                                        </button>
                                    </form>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-editar-banco-modal" 
                                                data-id="<?= $b['id'] ?>" 
                                                data-nombre="<?= htmlspecialchars($b['nombre_banco']) ?>" 
                                                data-codigo="<?= htmlspecialchars($b['codigo_banco'] ?? '') ?>" 
                                                data-tipo="<?= htmlspecialchars($b['tipo_cuenta'] ?? 'Corriente') ?>" 
                                                data-numero="<?= htmlspecialchars($b['numero_cuenta'] ?? '') ?>" 
                                                data-titular="<?= htmlspecialchars($b['titular'] ?? '') ?>" 
                                                data-rif="<?= htmlspecialchars($b['rif_cedula'] ?? '') ?>" 
                                                data-telefono="<?= htmlspecialchars($b['telefono_pago_movil'] ?? '') ?>" 
                                                data-status="<?= $b['status'] ?? 1 ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-abrir-eliminar-banco"
                                                data-id="<?= $b['id'] ?>"
                                                data-nombre="<?= htmlspecialchars($b['nombre_banco']) ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="fas fa-exchange-alt fa-3x mb-3 text-muted"></i>
                        <h5>No hay cuentas de transferencias bancarias registradas.</h5>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- PESTAÑA 3: MÉTODOS Y FORMAS DE PAGO (TOGGLEABLE) -->
        <!-- ======================================================== -->
        <div class="tab-pane fade" id="tab-metodos" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1 text-dark font-weight-bold"><i class="fas fa-sliders-h text-info mr-2"></i>Catálogo de Métodos y Formas de Pago</h5>
                        <p class="text-muted small mb-0">Active o desactive los métodos que los estudiantes y administradores pueden seleccionar al pagar (Pago Móvil, Transferencia, Cripto, etc.).</p>
                    </div>
                    <button type="button" class="btn btn-info btn-sm shadow-sm font-weight-bold text-white" data-toggle="modal" data-target="#modalMetodoPago">
                        <i class="fas fa-plus mr-1"></i> Crear Nuevo Método
                    </button>
                </div>
                <div class="card-body p-2 p-sm-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped table-sm">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Método / Forma de Pago</th>
                                    <th>Descripción</th>
                                    <th style="width: 140px;">Requiere Banco</th>
                                    <th style="width: 150px;">Estado en Sistema</th>
                                    <th style="width: 110px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($metodos_pago)): ?>
                                    <?php foreach ($metodos_pago as $idx => $m): ?>
                                        <tr>
                                            <td class="text-center align-middle"><?= $idx + 1 ?></td>
                                            <td class="font-weight-bold align-middle">
                                                <i class="<?= htmlspecialchars($m['icono'] ?: 'fas fa-money-bill') ?> text-primary mr-2"></i>
                                                <?= htmlspecialchars($m['nombre']) ?>
                                            </td>
                                            <td class="align-middle text-muted small">
                                                <?= htmlspecialchars($m['descripcion'] ?: 'Sin descripción') ?>
                                            </td>
                                            <td class="text-center align-middle small">
                                                <?= !empty($m['requiere_banco']) ? '<span class="badge badge-light border text-dark">Sí (Receptor)</span>' : '<span class="badge badge-light text-muted">No requiere</span>' ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <form method="POST" action="" class="d-inline">
                                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                    <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                                    <input type="hidden" name="nuevo_status" value="<?= empty($m['status']) ? '1' : '0' ?>">
                                                    <button type="submit" name="toggle_status_metodo_pago" class="btn btn-sm <?= !empty($m['status']) ? 'btn-success' : 'btn-secondary' ?>" title="Alternar activación">
                                                        <?= !empty($m['status']) ? '<i class="fas fa-check-circle mr-1"></i>Habilitado' : '<i class="fas fa-ban mr-1"></i>Deshabilitado' ?>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary btn-editar-metodo-modal" 
                                                            data-id="<?= $m['id'] ?>" 
                                                            data-nombre="<?= htmlspecialchars($m['nombre']) ?>" 
                                                            data-codigo="<?= htmlspecialchars($m['codigo']) ?>" 
                                                            data-icono="<?= htmlspecialchars($m['icono']) ?>" 
                                                            data-descripcion="<?= htmlspecialchars($m['descripcion'] ?? '') ?>" 
                                                            data-requiere_banco="<?= $m['requiere_banco'] ?>" 
                                                            data-requiere_comprobante="<?= $m['requiere_comprobante'] ?>" 
                                                            data-status="<?= $m['status'] ?>" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger btn-abrir-eliminar-metodo"
                                                            data-id="<?= $m['id'] ?>"
                                                            data-nombre="<?= htmlspecialchars($m['nombre']) ?>"
                                                            title="Eliminar">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center py-4 text-muted">No hay métodos de pago registrados.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- PESTAÑA 4: ARANCELES Y CONCEPTOS -->
        <!-- ======================================================== -->
        <div class="tab-pane fade" id="tab-aranceles" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-dark font-weight-bold"><i class="fas fa-tags text-warning mr-2"></i>Catálogo Oficial de Aranceles</h5>
                    <button type="button" class="btn btn-primary btn-sm shadow-sm font-weight-bold" data-toggle="modal" data-target="#modalTipoPago">
                        <i class="fas fa-plus mr-1"></i> Crear Concepto
                    </button>
                </div>
                <div class="card-body p-2 p-sm-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped table-sm">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Concepto / Motivo de Arancel</th>
                                    <th style="width: 160px;">Precio (Bs)</th>
                                    <th style="width: 150px;">Estado</th>
                                    <th style="width: 120px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($tipos_pago)): ?>
                                    <?php foreach ($tipos_pago as $idx => $tp): ?>
                                        <tr>
                                            <td class="text-center align-middle"><?= $idx + 1 ?></td>
                                            <td class="font-weight-bold align-middle"><?= htmlspecialchars($tp['tipopago']) ?></td>
                                            <td class="text-right font-weight-bold text-success align-middle" style="font-size: 1.05rem;">
                                                Bs <?= number_format($tp['precio'], 2, ',', '.') ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <form method="POST" action="" class="d-inline">
                                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                    <input type="hidden" name="id" value="<?= $tp['id'] ?>">
                                                    <input type="hidden" name="nuevo_status" value="<?= empty($tp['status']) ? '1' : '0' ?>">
                                                    <button type="submit" name="toggle_status_tipo_pago" class="btn btn-sm <?= !empty($tp['status']) ? 'btn-success' : 'btn-secondary' ?>" title="Alternar visibilidad">
                                                        <?= !empty($tp['status']) ? '<i class="fas fa-check-circle mr-1"></i>Habilitado' : '<i class="fas fa-ban mr-1"></i>Deshabilitado' ?>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary btn-editar-tipo-modal" 
                                                            data-id="<?= $tp['id'] ?>" 
                                                            data-tipopago="<?= htmlspecialchars($tp['tipopago']) ?>" 
                                                            data-precio="<?= htmlspecialchars($tp['precio']) ?>" 
                                                            data-status="<?= $tp['status'] ?? 1 ?>" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger btn-abrir-eliminar-tipo"
                                                            data-id="<?= $tp['id'] ?>"
                                                            data-nombre="<?= htmlspecialchars($tp['tipopago']) ?>"
                                                            title="Eliminar">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center py-4 text-muted">No se han registrado conceptos arancelarios.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- PESTAÑA 5: DIRECTORIO DE BANCOS NACIONALES -->
        <!-- ======================================================== -->
        <div class="tab-pane fade" id="tab-directorio-bancos" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <h5 class="mb-1 text-dark font-weight-bold"><i class="fas fa-landmark text-secondary mr-2"></i>Directorio General de Bancos</h5>
                        <p class="text-muted small mb-0">Listado de bancos disponibles en el sistema para selección de estudiantes y recepción de pagos.</p>
                    </div>
                    <div class="mt-2 mt-md-0 d-flex gap-2">
                        <input type="text" id="filtro_directorio_bancos" class="form-control form-control-sm mr-2" placeholder="Buscar banco..." style="max-width: 220px;">
                        <button type="button" class="btn btn-success btn-sm shadow-sm font-weight-bold" data-toggle="modal" data-target="#modalBanco">
                            <i class="fas fa-plus mr-1"></i> Agregar Banco
                        </button>
                    </div>
                </div>
                <div class="card-body p-2 p-sm-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped table-sm" id="tabla_directorio_bancos">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Nombre del Banco</th>
                                    <th style="width: 90px;">Código</th>
                                    <th>Cuentas / Datos Institucionales</th>
                                    <th style="width: 140px;">Transferencia</th>
                                    <th style="width: 140px;">Pago Móvil</th>
                                    <th style="width: 120px;">Banco General</th>
                                    <th style="width: 100px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($bancos)): ?>
                                    <?php foreach ($bancos as $idx => $b): 
                                        $tiene_cuenta = !empty(trim($b['numero_cuenta'] ?? ''));
                                        $tiene_pm     = !empty(trim($b['telefono_pago_movil'] ?? ''));
                                    ?>
                                        <tr class="banco-fila">
                                            <td class="text-center align-middle"><?= $idx + 1 ?></td>
                                            <td class="font-weight-bold align-middle banco-nombre">
                                                <i class="fas fa-university text-secondary mr-2"></i><?= htmlspecialchars($b['nombre_banco']) ?>
                                            </td>
                                            <td class="text-center align-middle font-weight-bold text-primary">
                                                <?= htmlspecialchars($b['codigo_banco'] ?: 'N/A') ?>
                                            </td>
                                            <td class="align-middle small">
                                                <?php if ($tiene_cuenta): ?>
                                                    <div><strong>Cuenta:</strong> <?= htmlspecialchars($b['numero_cuenta']) ?> (<?= htmlspecialchars($b['tipo_cuenta'] ?: 'Corriente') ?>)</div>
                                                <?php endif; ?>
                                                <?php if ($tiene_pm): ?>
                                                    <div class="text-success"><strong>Pago Móvil:</strong> <?= htmlspecialchars($b['telefono_pago_movil']) ?></div>
                                                <?php endif; ?>
                                                <?php if (!$tiene_cuenta && !$tiene_pm): ?>
                                                    <span class="text-muted italic">Sin datos institucionales (Banco Emisor)</span>
                                                <?php endif; ?>
                                            </td>
                                            <!-- Estado Transferencia -->
                                            <td class="text-center align-middle">
                                                <?php if ($tiene_cuenta): ?>
                                                    <form method="POST" action="" class="d-inline">
                                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                        <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                                        <input type="hidden" name="canal" value="transferencia">
                                                        <input type="hidden" name="nuevo_status" value="<?= empty($b['status_transferencia']) ? '1' : '0' ?>">
                                                        <button type="submit" name="toggle_status_canal_banco" class="btn btn-sm <?= !empty($b['status_transferencia']) ? 'btn-outline-primary' : 'btn-outline-secondary' ?>">
                                                            <?= !empty($b['status_transferencia']) ? '<i class="fas fa-check-circle mr-1"></i>Activa' : '<i class="fas fa-ban mr-1"></i>Inactiva' ?>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="text-muted small">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <!-- Estado Pago Móvil -->
                                            <td class="text-center align-middle">
                                                <?php if ($tiene_pm): ?>
                                                    <form method="POST" action="" class="d-inline">
                                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                        <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                                        <input type="hidden" name="canal" value="pago_movil">
                                                        <input type="hidden" name="nuevo_status" value="<?= empty($b['status_pago_movil']) ? '1' : '0' ?>">
                                                        <button type="submit" name="toggle_status_canal_banco" class="btn btn-sm <?= !empty($b['status_pago_movil']) ? 'btn-outline-success' : 'btn-outline-secondary' ?>">
                                                            <?= !empty($b['status_pago_movil']) ? '<i class="fas fa-check-circle mr-1"></i>Activo' : '<i class="fas fa-ban mr-1"></i>Inactivo' ?>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="text-muted small">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <!-- Estado General Banco -->
                                            <td class="text-center align-middle">
                                                <form method="POST" action="" class="d-inline">
                                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                    <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                                    <input type="hidden" name="nuevo_status" value="<?= empty($b['status']) ? '1' : '0' ?>">
                                                    <button type="submit" name="toggle_status_banco" class="btn btn-sm <?= !empty($b['status']) ? 'btn-success' : 'btn-secondary' ?>" title="Alternar visibilidad general">
                                                        <?= !empty($b['status']) ? '<i class="fas fa-check-circle mr-1"></i>Habilitado' : '<i class="fas fa-ban mr-1"></i>Deshabilitado' ?>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary btn-editar-banco-modal" 
                                                            data-id="<?= $b['id'] ?>" 
                                                            data-nombre="<?= htmlspecialchars($b['nombre_banco']) ?>" 
                                                            data-codigo="<?= htmlspecialchars($b['codigo_banco'] ?? '') ?>" 
                                                            data-tipo="<?= htmlspecialchars($b['tipo_cuenta'] ?? 'Corriente') ?>" 
                                                            data-numero="<?= htmlspecialchars($b['numero_cuenta'] ?? '') ?>" 
                                                            data-titular="<?= htmlspecialchars($b['titular'] ?? '') ?>" 
                                                            data-rif="<?= htmlspecialchars($b['rif_cedula'] ?? '') ?>" 
                                                            data-telefono="<?= htmlspecialchars($b['telefono_pago_movil'] ?? '') ?>" 
                                                            data-status="<?= $b['status'] ?? 1 ?>" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger btn-abrir-eliminar-banco"
                                                            data-id="<?= $b['id'] ?>"
                                                            data-nombre="<?= htmlspecialchars($b['nombre_banco']) ?>"
                                                            title="Eliminar">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="8" class="text-center py-4 text-muted">No hay bancos registrados en el directorio.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL 1: CREAR / EDITAR TIPO DE PAGO (ARANCEL) -->
<!-- ======================================================== -->
<div class="modal fade" id="modalTipoPago" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="" id="formModalTipoPago">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="tipo_pago_id" id="modal_tipo_pago_id" value="0">
                
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTituloTipoPago"><i class="fas fa-tags mr-2"></i>Nuevo Concepto / Arancel</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="modal_tipopago" class="font-weight-bold">Nombre del Concepto / Arancel: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_tipopago" name="tipopago" 
                               placeholder="Ej: Expedición de Constancia de Estudios" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="modal_precio" class="font-weight-bold">Precio Arancelario (Bs): <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bold">Bs</span>
                            </div>
                            <input type="number" class="form-control font-weight-bold" id="modal_precio" name="precio" 
                                   step="0.01" min="0" value="0.00" required>
                        </div>
                        <small class="form-text text-muted">Coloque 0.00 para aranceles gratuitos o de monto variable.</small>
                    </div>
                    
                    <div class="form-group mb-0">
                        <label for="modal_status_tipo" class="font-weight-bold">Estado:</label>
                        <select class="custom-select" id="modal_status_tipo" name="status">
                            <option value="1">Habilitado (Visible para estudiantes y pagos)</option>
                            <option value="0">Deshabilitado (Oculto)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="guardar_tipo_pago" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Guardar Arancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL 2: CREAR / EDITAR MÉTODO DE PAGO (NUEVO) -->
<!-- ======================================================== -->
<div class="modal fade" id="modalMetodoPago" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="" id="formModalMetodoPago">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="metodo_id" id="modal_metodo_id" value="0">
                
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalTituloMetodo"><i class="fas fa-money-check-alt mr-2"></i>Registrar Forma / Método de Pago</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="modal_metodo_nombre" class="font-weight-bold">Nombre del Método: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_metodo_nombre" name="nombre" 
                               placeholder="Ej: Binance Pay / USDT, Zelle, Punto de Venta..." required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="modal_metodo_codigo" class="font-weight-bold">Código / Identificador:</label>
                            <input type="text" class="form-control" id="modal_metodo_codigo" name="codigo" 
                                   placeholder="Ej: binance, zelle...">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="modal_metodo_icono" class="font-weight-bold">Clase de Icono (FontAwesome):</label>
                            <input type="text" class="form-control" id="modal_metodo_icono" name="icono" 
                                   value="fas fa-money-check-alt">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="modal_metodo_desc" class="font-weight-bold">Descripción / Instrucciones:</label>
                        <textarea class="form-control" id="modal_metodo_desc" name="descripcion" rows="2" placeholder="Detalles de la forma de pago..."></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="modal_metodo_banco" class="font-weight-bold">¿Requiere Banco Receptor?:</label>
                            <select class="custom-select" id="modal_metodo_banco" name="requiere_banco">
                                <option value="1">Sí (Vinculado a cuentas bancarias)</option>
                                <option value="0">No (Directo, Taquilla o Cripto)</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="modal_metodo_status" class="font-weight-bold">Estado Inicial:</label>
                            <select class="custom-select" id="modal_metodo_status" name="status">
                                <option value="1">Habilitado (Activo para pagos)</option>
                                <option value="0">Deshabilitado (Inactivo / Oculto)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="guardar_metodo_pago" class="btn btn-info font-weight-bold text-white">
                        <i class="fas fa-save mr-1"></i> Guardar Método
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL 3: CREAR / EDITAR BANCO O CUENTA -->
<!-- ======================================================== -->
<div class="modal fade" id="modalBanco" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="" id="formModalBanco">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="banco_id" id="modal_banco_id" value="0">
                
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalTituloBanco"><i class="fas fa-university mr-2"></i>Registrar Banco / Cuenta</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="modal_nombre_banco" class="font-weight-bold">Nombre del Banco: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_nombre_banco" name="nombre_banco" 
                               placeholder="Ej: Banco de Venezuela, Banesco, Banco Mercantil..." required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="modal_codigo_banco" class="font-weight-bold">Código Bancario (4 dígitos):</label>
                            <input type="text" class="form-control" id="modal_codigo_banco" name="codigo_banco" 
                                   placeholder="Ej: 0102, 0134, 0105..." maxlength="10">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="modal_tipo_cuenta" class="font-weight-bold">Tipo de Cuenta (Opcional):</label>
                            <select class="custom-select" id="modal_tipo_cuenta" name="tipo_cuenta">
                                <option value="Corriente">Cuenta Corriente</option>
                                <option value="Ahorro">Cuenta de Ahorros</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="modal_numero_cuenta" class="font-weight-bold">Número de Cuenta Institucional (Opcional si es solo emisor):</label>
                        <input type="text" class="form-control font-weight-bold" id="modal_numero_cuenta" name="numero_cuenta" 
                               placeholder="Ej: 0102-0123-45-0000123456" maxlength="30">
                        <small class="text-muted">Deje en blanco si la universidad no tiene una cuenta receptora en este banco.</small>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="modal_titular" class="font-weight-bold">Titular de la Cuenta:</label>
                            <input type="text" class="form-control" id="modal_titular" name="titular" 
                                   placeholder="Ej: UPTPC">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="modal_rif_cedula" class="font-weight-bold">RIF / Cédula del Titular:</label>
                            <input type="text" class="form-control" id="modal_rif_cedula" name="rif_cedula" 
                                   placeholder="Ej: G-20005608-8">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="modal_telefono_pago_movil" class="font-weight-bold">
                                <i class="fas fa-mobile-alt text-success mr-1"></i> Teléfono para Pago Móvil (Opcional):
                            </label>
                            <input type="text" class="form-control" id="modal_telefono_pago_movil" name="telefono_pago_movil" 
                                   placeholder="Ej: 0414-1234567">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="modal_status_banco" class="font-weight-bold">Estado General:</label>
                            <select class="custom-select" id="modal_status_banco" name="status">
                                <option value="1">Habilitado (Disponible en el sistema)</option>
                                <option value="0">Deshabilitado (Oculto)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="guardar_banco" class="btn btn-success font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Guardar Banco
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL 4: CONFIRMACIÓN DE ELIMINACIÓN DE MÉTODO DE PAGO -->
<!-- ======================================================== -->
<div class="modal fade" id="modalEliminarMetodoPago" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="id" id="eliminar_metodo_id" value="0">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i>Eliminar Método de Pago</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <p class="mb-2">¿Está seguro de que desea eliminar este método de pago del sistema?</p>
                    <div class="alert alert-warning font-weight-bold" id="eliminar_metodo_nombre"></div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="eliminar_metodo_pago" class="btn btn-danger font-weight-bold">
                        <i class="fas fa-trash-alt mr-1"></i> Sí, Eliminar Método
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL 5: CONFIRMACIÓN DE ELIMINACIÓN DE ARANCEL -->
<!-- ======================================================== -->
<div class="modal fade" id="modalEliminarTipoPago" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="id" id="eliminar_tipo_id" value="0">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i>Eliminar Concepto Arancelario</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <p class="mb-2">¿Está seguro de que desea eliminar permanentemente el arancel?</p>
                    <div class="alert alert-warning font-weight-bold" id="eliminar_tipo_nombre"></div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="eliminar_tipo_pago" class="btn btn-danger font-weight-bold">
                        <i class="fas fa-trash-alt mr-1"></i> Sí, Eliminar Arancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL 6: CONFIRMACIÓN DE ELIMINACIÓN DE BANCO -->
<!-- ======================================================== -->
<div class="modal fade" id="modalEliminarBanco" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="id" id="eliminar_banco_id" value="0">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i>Eliminar Banco / Cuenta</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <i class="fas fa-university fa-3x text-danger mb-3"></i>
                    <p class="mb-2">¿Está seguro de que desea eliminar este banco del sistema?</p>
                    <div class="alert alert-warning font-weight-bold" id="eliminar_banco_nombre"></div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="eliminar_banco" class="btn btn-danger font-weight-bold">
                        <i class="fas fa-trash-alt mr-1"></i> Sí, Eliminar Banco
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function copiarTexto(texto, el) {
    if (!texto) return;
    navigator.clipboard.writeText(texto).then(() => {
        const icon = el.querySelector('i');
        if (icon) {
            const originalClass = icon.className;
            icon.className = 'fas fa-check text-success';
            setTimeout(() => {
                icon.className = originalClass;
            }, 1500);
        }
    }).catch(err => {
        console.error('Error al copiar:', err);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Filtro directorio de bancos
    const inputFiltroBancos = document.getElementById('filtro_directorio_bancos');
    if (inputFiltroBancos) {
        inputFiltroBancos.addEventListener('input', function() {
            const term = this.value.toLowerCase().trim();
            document.querySelectorAll('#tabla_directorio_bancos tbody tr.banco-fila').forEach(tr => {
                const text = tr.textContent.toLowerCase();
                tr.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }

    // Modal Editar Método de Pago
    document.querySelectorAll('.btn-editar-metodo-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('modal_metodo_id').value = this.getAttribute('data-id');
            document.getElementById('modal_metodo_nombre').value = this.getAttribute('data-nombre');
            document.getElementById('modal_metodo_codigo').value = this.getAttribute('data-codigo');
            document.getElementById('modal_metodo_icono').value = this.getAttribute('data-icono');
            document.getElementById('modal_metodo_desc').value = this.getAttribute('data-descripcion');
            document.getElementById('modal_metodo_banco').value = this.getAttribute('data-requiere_banco');
            document.getElementById('modal_metodo_status').value = this.getAttribute('data-status');
            
            document.getElementById('modalTituloMetodo').innerHTML = '<i class="fas fa-edit mr-2"></i>Editar Método de Pago';
            $('#modalMetodoPago').modal('show');
        });
    });

    $('#modalMetodoPago').on('hidden.bs.modal', function () {
        document.getElementById('formModalMetodoPago').reset();
        document.getElementById('modal_metodo_id').value = '0';
        document.getElementById('modalTituloMetodo').innerHTML = '<i class="fas fa-money-check-alt mr-2"></i>Registrar Forma / Método de Pago';
    });

    // Modal Eliminar Método de Pago
    document.querySelectorAll('.btn-abrir-eliminar-metodo').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('eliminar_metodo_id').value = this.getAttribute('data-id');
            document.getElementById('eliminar_metodo_nombre').textContent = this.getAttribute('data-nombre');
            $('#modalEliminarMetodoPago').modal('show');
        });
    });

    // Modal Editar Tipo de Pago
    document.querySelectorAll('.btn-editar-tipo-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('modal_tipo_pago_id').value = this.getAttribute('data-id');
            document.getElementById('modal_tipopago').value = this.getAttribute('data-tipopago');
            document.getElementById('modal_precio').value = this.getAttribute('data-precio');
            document.getElementById('modal_status_tipo').value = this.getAttribute('data-status');
            
            document.getElementById('modalTituloTipoPago').innerHTML = '<i class="fas fa-edit mr-2"></i>Editar Concepto / Arancel';
            $('#modalTipoPago').modal('show');
        });
    });

    $('#modalTipoPago').on('hidden.bs.modal', function () {
        document.getElementById('formModalTipoPago').reset();
        document.getElementById('modal_tipo_pago_id').value = '0';
        document.getElementById('modalTituloTipoPago').innerHTML = '<i class="fas fa-tags mr-2"></i>Nuevo Concepto / Arancel';
    });

    // Modal Eliminar Tipo de Pago
    document.querySelectorAll('.btn-abrir-eliminar-tipo').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('eliminar_tipo_id').value = this.getAttribute('data-id');
            document.getElementById('eliminar_tipo_nombre').textContent = this.getAttribute('data-nombre');
            $('#modalEliminarTipoPago').modal('show');
        });
    });

    // Modal Editar Banco
    document.querySelectorAll('.btn-editar-banco-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('modal_banco_id').value = this.getAttribute('data-id');
            document.getElementById('modal_nombre_banco').value = this.getAttribute('data-nombre');
            document.getElementById('modal_codigo_banco').value = this.getAttribute('data-codigo');
            document.getElementById('modal_tipo_cuenta').value = this.getAttribute('data-tipo');
            document.getElementById('modal_numero_cuenta').value = this.getAttribute('data-numero');
            document.getElementById('modal_titular').value = this.getAttribute('data-titular');
            document.getElementById('modal_rif_cedula').value = this.getAttribute('data-rif');
            document.getElementById('modal_telefono_pago_movil').value = this.getAttribute('data-telefono');
            document.getElementById('modal_status_banco').value = this.getAttribute('data-status');
            
            document.getElementById('modalTituloBanco').innerHTML = '<i class="fas fa-edit mr-2"></i>Editar Banco / Cuenta';
            $('#modalBanco').modal('show');
        });
    });

    $('#modalBanco').on('hidden.bs.modal', function () {
        document.getElementById('formModalBanco').reset();
        document.getElementById('modal_banco_id').value = '0';
        document.getElementById('modalTituloBanco').innerHTML = '<i class="fas fa-university mr-2"></i>Registrar Banco / Cuenta';
    });

    // Modal Eliminar Banco
    document.querySelectorAll('.btn-abrir-eliminar-banco').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('eliminar_banco_id').value = this.getAttribute('data-id');
            document.getElementById('eliminar_banco_nombre').textContent = this.getAttribute('data-nombre');
            $('#modalEliminarBanco').modal('show');
        });
    });
});
</script>

<?php include("includes/footer.php"); ?>