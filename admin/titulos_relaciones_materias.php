<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Gestión de Títulos y Materias";
include('../funciones/functions.php');

// **1. Manejar solicitudes AJAX para búsqueda en tiempo real**
if (isset($_GET['ajax_type'])) {
    header('Content-Type: application/json');
    $search = $db->real_escape_string($_GET['search'] ?? '');

    // **Búsqueda de Títulos**
    if ($_GET['ajax_type'] == 'search_titles') {
        $query = "SELECT * FROM titulos WHERE nombre LIKE '%$search%' OR descripcion LIKE '%$search%' ORDER BY nombre";
        $result = $db->query($query);
        
        $output = '';
        if ($result->num_rows > 0) {
            while ($title = $result->fetch_assoc()) {
                $output .= '<tr>
                    <td>'.$title['id'].'</td>
                    <td><strong>'.htmlspecialchars($title['nombre']).'</strong><br><small class="text-muted">'.htmlspecialchars(substr($title['descripcion'], 0, 30)).'...</small></td>
                    <td>
                        <button class="btn btn-sm btn-info edit-title" data-id="'.$title['id'].'" data-nombre="'.htmlspecialchars($title['nombre']).'" data-descripcion="'.htmlspecialchars($title['descripcion']).'">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form method="POST" onsubmit="return confirm(\'¿Eliminar?\')" style="display: inline;">
                            <input type="hidden" name="id_titulo" value="'.$title['id'].'">
                            <button type="submit" name="delete_title" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>';
            }
        } else {
            $output = '<tr><td colspan="3" class="text-center py-4">No se encontraron títulos</td></tr>';
        }
        echo json_encode(['html' => $output]);
        exit;
    }

    // **Búsqueda de Relaciones**
    if ($_GET['ajax_type'] == 'search_relations') {
        $query = "SELECT tm.*, t.nombre AS titulo, m.nombre_materia, m.cod_materia 
                  FROM titulo_materia tm
                  JOIN titulos t ON tm.id_titulo = t.id
                  JOIN materias m ON tm.id_materia = m.id_materia
                  WHERE t.nombre LIKE '%$search%' OR m.nombre_materia LIKE '%$search%' OR m.cod_materia LIKE '%$search%'
                  ORDER BY t.nombre";
        $result = $db->query($query);
        
        $output = '';
        if ($result->num_rows > 0) {
            while ($rel = $result->fetch_assoc()) {
                $output .= '<tr>
                    <td>'.$rel['id_relacion'].'</td>
                    <td>'.htmlspecialchars($rel['titulo']).'</td>
                    <td>'.htmlspecialchars($rel['cod_materia']).' - '.htmlspecialchars($rel['nombre_materia']).'</td>
                    <td><span class="badge badge-secondary">'.$rel['prioridad'].'</span></td>
                    <td>
                        <form method="POST" onsubmit="return confirm(\'¿Eliminar?\')">
                            <input type="hidden" name="id_relacion" value="'.$rel['id_relacion'].'">
                            <button type="submit" name="delete_relationship" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>';
            }
        } else {
            $output = '<tr><td colspan="5" class="text-center py-4">No se encontraron relaciones</td></tr>';
        }
        echo json_encode(['html' => $output]);
        exit;
    }
}

// **2. Procesar formularios POST (CRUD)**
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // **Agregar título**
    if (isset($_POST['add_title'])) {
        $nombre = $db->real_escape_string($_POST['nombre']);
        $descripcion = $db->real_escape_string($_POST['descripcion']);
        $db->query("INSERT INTO titulos (nombre, descripcion) VALUES ('$nombre', '$descripcion')");
        $_SESSION['message'] = "Título agregado correctamente";
        $_SESSION['message_type'] = "success";
    }

    // **Editar título**
    if (isset($_POST['edit_title'])) {
        $id = $db->real_escape_string($_POST['id_titulo']);
        $nombre = $db->real_escape_string($_POST['nombre']);
        $descripcion = $db->real_escape_string($_POST['descripcion']);
        $db->query("UPDATE titulos SET nombre = '$nombre', descripcion = '$descripcion' WHERE id = '$id'");
        $_SESSION['message'] = "Título actualizado correctamente";
        $_SESSION['message_type'] = "success";
    }

    // **Agregar relación**
    if (isset($_POST['relate_title_subject'])) {
        $id_titulo = $db->real_escape_string($_POST['id_titulo']);
        $id_materia = $db->real_escape_string($_POST['id_materia']);
        $prioridad = $db->real_escape_string($_POST['prioridad']);
        $db->query("INSERT INTO titulo_materia (id_titulo, id_materia, prioridad) VALUES ('$id_titulo', '$id_materia', '$prioridad')");
        $_SESSION['message'] = "Relación creada correctamente";
        $_SESSION['message_type'] = "success";
    }

    // **Eliminar título**
    if (isset($_POST['delete_title'])) {
        $id = $db->real_escape_string($_POST['id_titulo']);
        $db->query("DELETE FROM titulos WHERE id = '$id'");
        $_SESSION['message'] = "Título eliminado";
        $_SESSION['message_type'] = "danger";
    }

    // **Eliminar relación**
    if (isset($_POST['delete_relationship'])) {
        $id = $db->real_escape_string($_POST['id_relacion']);
        $db->query("DELETE FROM titulo_materia WHERE id_relacion = '$id'");
        $_SESSION['message'] = "Relación eliminada";
        $_SESSION['message_type'] = "danger";
    }

    header("Location: ".$_SERVER['PHP_SELF']); // Evitar reenvío de formulario
    exit;
}

// **3. Interfaz HTML (Bootstrap 4)**
include("includes/head.php");
?>

<div class="container-fluid">
    <!-- Alertas -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?? 'success' ?> alert-dismissible fade show">
            <?= $_SESSION['message'] ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
    <?php endif; ?>

    <!-- Modal para editar título -->
    <div class="modal fade" id="editTitleModal" tabindex="-1" role="dialog" aria-labelledby="editTitleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTitleModalLabel">Editar Título</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id_titulo" id="editTitleId">
                        <div class="form-group">
                            <label for="editTitleName">Nombre del título</label>
                            <input type="text" name="nombre" class="form-control" id="editTitleName" required>
                        </div>
                        <div class="form-group">
                            <label for="editTitleDescription">Descripción</label>
                            <textarea name="descripcion" class="form-control" id="editTitleDescription" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="edit_title" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- **Panel de Títulos** -->
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Títulos</h5>
                </div>
                <div class="card-body">
                    <!-- Buscador -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" id="searchTitles" class="form-control" placeholder="Buscar títulos...">
                    </div>

                    <!-- Tabla de resultados (se actualiza con AJAX) -->
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="titlesResults">
                                <!-- Los resultados se cargan aquí con JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Formulario para agregar título -->
                    <form method="POST" class="mt-3">
                        <div class="form-group">
                            <input type="text" name="nombre" class="form-control" placeholder="Nombre del título" required>
                        </div>
                        <div class="form-group">
                            <textarea name="descripcion" class="form-control" placeholder="Descripción"></textarea>
                        </div>
                        <button type="submit" name="add_title" class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i> Agregar Título
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- **Panel de Relaciones** -->
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-link"></i> Relaciones con Materias</h5>
                </div>
                <div class="card-body">
                    <!-- Buscador -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" id="searchRelations" class="form-control" placeholder="Buscar relaciones...">
                    </div>

                    <!-- Tabla de relaciones (se actualiza con AJAX) -->
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Título</th>
                                    <th>Materia</th>
                                    <th>Prioridad</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="relationsResults">
                                <!-- Los resultados se cargan aquí con JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Formulario para crear relación -->
                    <form method="POST" class="mt-3">
                        <div class="form-row">
                            <div class="col-md-5">
                                <select name="id_titulo" class="form-control" required>
                                    <option value="">Seleccionar título</option>
                                    <?php
                                    $titulos = $db->query("SELECT * FROM titulos ORDER BY nombre");
                                    while ($t = $titulos->fetch_assoc()) {
                                        echo "<option value='{$t['id']}'>{$t['nombre']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <select name="id_materia" class="form-control" required>
                                    <option value="">Seleccionar materia</option>
                                    <?php
                                    $materias = $db->query("SELECT * FROM materias ORDER BY nombre_materia");
                                    while ($m = $materias->fetch_assoc()) {
                                        echo "<option value='{$m['id_materia']}'>{$m['cod_materia']} - {$m['nombre_materia']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="prioridad" class="form-control" placeholder="Prioridad" min="1" value="1" required>
                            </div>
                        </div>
                        <button type="submit" name="relate_title_subject" class="btn btn-success btn-block mt-2">
                            <i class="fas fa-link"></i> Crear Relación
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- **Script para búsqueda en tiempo real y edición** -->
<script>
$(document).ready(function() {
    // Función para buscar títulos
    function searchTitles(query) {
        $.get("?ajax_type=search_titles&search=" + encodeURIComponent(query), function(data) {
            $("#titlesResults").html(data.html);
        }, 'json');
    }

    // Función para buscar relaciones
    function searchRelations(query) {
        $.get("?ajax_type=search_relations&search=" + encodeURIComponent(query), function(data) {
            $("#relationsResults").html(data.html);
        }, 'json');
    }

    // Eventos para búsqueda en tiempo real
    $("#searchTitles").on("input", function() {
        searchTitles($(this).val());
    });

    $("#searchRelations").on("input", function() {
        searchRelations($(this).val());
    });

    // Evento para editar título
    $(document).on('click', '.edit-title', function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        var descripcion = $(this).data('descripcion');
        
        $('#editTitleId').val(id);
        $('#editTitleName').val(nombre);
        $('#editTitleDescription').val(descripcion);
        
        $('#editTitleModal').modal('show');
    });

    // Cargar datos iniciales
    searchTitles('');
    searchRelations('');
});
</script>

<?php include("includes/footer.php"); ?>