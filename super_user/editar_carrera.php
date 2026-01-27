<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Editar Carrera";
require_once '../funciones/functions.php';

// Verificar si se recibió ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: lista_carreras.php");
    exit();
}

$id = (int)$_GET['id'];
$carrera = obtenerCarreraPorId($id);

if (!$carrera) {
    header("Location: lista_carreras.php");
    exit();
}

// Convertir semestres a años para el formulario
$duracion_anios = $carrera['duracion_semestres'] / 2;

// Separar títulos si existen
$titulos = explode(' / ', $carrera['titulo_otorga']);
$titulo_principal = $titulos[0];
$titulo_opcional = $titulos[1] ?? '';

// Procesar el formulario
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre_carrera'] ?? '');
    $codigo = trim($_POST['cod_carrera'] ?? '');
    $tipo_formacion = trim($_POST['tipo_formacion'] ?? '');
    $duracion_anios = (int)($_POST['duracion_anios'] ?? 0);
    $titulo_principal = trim($_POST['titulo_principal'] ?? '');
    $titulo_opcional = trim($_POST['titulo_opcional'] ?? '');
    $activa = isset($_POST['activa']) ? 1 : 0;
    
    // Validación básica
    $camposObligatorios = [
        'nombre_carrera' => $nombre,
        'cod_carrera' => $codigo,
        'tipo_formacion' => $tipo_formacion,
        'duracion_anios' => $duracion_anios,
        'titulo_principal' => $titulo_principal
    ];
    
    $camposVacios = array_filter($camposObligatorios, function($valor) {
        return empty($valor);
    });
    
    if (empty($camposVacios)) {
        $resultado = actualizarCarrera(
            $id,
            $nombre,
            $codigo,
            $tipo_formacion,
            $duracion_anios,
            $titulo_principal,
            $titulo_opcional,
            $activa
        );
        
        if ($resultado['success']) {
            $mensaje = '<div class="alert alert-success">' . $resultado['message'] . '</div>';
            // Actualizar los datos mostrados
            $carrera = obtenerCarreraPorId($id);
            $duracion_anios = $carrera['duracion_semestres'] / 2;
            $titulos = explode(' / ', $carrera['titulo_otorga']);
            $titulo_principal = $titulos[0];
            $titulo_opcional = $titulos[1] ?? '';
        } else {
            $mensaje = '<div class="alert alert-danger">' . $resultado['message'] . '</div>';
        }
    } else {
        $mensaje = '<div class="alert alert-warning">Los siguientes campos son obligatorios: ' . 
                   implode(', ', array_keys($camposVacios)) . '</div>';
    }
}

include("includes/head.php");
?>

<div class="container mt-4">
    <h2>Editar Programa Académico</h2>
    
    <?php echo $mensaje; ?>
    
    <form method="post" action="">
        <div class="form-group">
            <label for="nombre_carrera">Nombre del Programa:</label>
            <input type="text" class="form-control" id="nombre_carrera" name="nombre_carrera" 
                   value="<?= htmlspecialchars($carrera['nombre_carrera']) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="cod_carrera">Código del Programa:</label>
            <input type="text" class="form-control" id="cod_carrera" name="cod_carrera" 
                   value="<?= htmlspecialchars($carrera['cod_carrera']) ?>" required>
            <small class="form-text text-muted">Código único que identifica el programa</small>
        </div>

        <div class="form-group">
            <label for="tipo_formacion">Tipo de Formación:</label>
            <input type="text" class="form-control" id="tipo_formacion" name="tipo_formacion" 
                   value="<?= htmlspecialchars($carrera['tipo_formacion']) ?>" required>
            <small class="form-text text-muted">Ejemplo: PNF, PTF, Carrera Tradicional, etc.</small>
        </div>
        
        <div class="form-group">
            <label for="duracion_anios">Duración en Años:</label>
            <input type="number" class="form-control" id="duracion_anios" name="duracion_anios" 
                   min="1" max="6" value="<?= $duracion_anios ?>" required>
            <small class="form-text text-muted">Duración total del programa en años</small>
        </div>
        
        <div class="form-group">
            <label for="titulo_principal">Título Principal:</label>
            <input type="text" class="form-control" id="titulo_principal" name="titulo_principal" 
                   value="<?= htmlspecialchars($titulo_principal) ?>" required>
            <small class="form-text text-muted">Título obtenido al completar el programa</small>
        </div>
        
        <div class="form-group">
            <label for="titulo_opcional">Segundo Título (opcional):</label>
            <input type="text" class="form-control" id="titulo_opcional" name="titulo_opcional" 
                   value="<?= htmlspecialchars($titulo_opcional) ?>">
            <small class="form-text text-muted">Título adicional obtenido al completar extensiones del programa (si aplica)</small>
        </div>
        
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="activa" name="activa" 
                   <?= $carrera['activa'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="activa">Programa activo</label>
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="lista_carreras.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include("includes/footer.php"); ?>