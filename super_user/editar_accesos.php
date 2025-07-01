<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Gestión de Niveles de Acceso";
include('../funciones/functions.php');

if (!isAdmin()) {
    header('location: ../usuario/home.php');
}

// Verificar permisos
if (!isset($_SESSION['user']['editar_acceso']) || $_SESSION['user']['editar_acceso'] != 1) {
    header('Location: index.php');
    exit();
}

// Procesar formulario de permisos
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guardar'])) {
    global $db;
    
    if (isset($_POST['permisos']) && is_array($_POST['permisos'])) {
        foreach ($_POST['permisos'] as $user_id => $permisos) {
            if (!is_numeric($user_id)) continue;
            
            $estudiante = isset($permisos['estudiante']) ? 1 : 0;
            $docente = isset($permisos['docente']) ? 1 : 0;
            $admin = isset($permisos['admin']) ? 1 : 0;
            $super_user = isset($permisos['super_user']) ? 1 : 0;
            $editar_user = isset($permisos['editar_user']) ? 1 : 0;
            $editar_nota = isset($permisos['editar_nota']) ? 1 : 0;
            $editar_acceso = isset($permisos['editar_acceso']) ? 1 : 0;
            
            $query = "UPDATE users SET 
                     estudiante = ?, 
                     docente = ?, 
                     admin = ?, 
                     super_user = ?, 
                     editar_user = ?, 
                     editar_nota = ?, 
                     editar_acceso = ?
                     WHERE id = ?";
            
            $stmt = $db->prepare($query);
            if ($stmt) {
                $stmt->bind_param("iiiiiiii", 
                    $estudiante, 
                    $docente, 
                    $admin, 
                    $super_user, 
                    $editar_user, 
                    $editar_nota, 
                    $editar_acceso, 
                    $user_id
                );
                $stmt->execute();
                $stmt->close();
            }
        }
        
        $_SESSION['msg'] = "Permisos actualizados correctamente";
        header('Location: editar_accesos.php');
        exit();
    }
}

include("includes/head.php");
?>

<div class="container mt-5">
    <h2 class="mb-4"><i class="fas fa-user-lock"></i> Gestión de Niveles de Acceso</h2>
    
    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
    <?php endif; ?>
    
    <!-- Controles de Filtrado y Búsqueda -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="filtro-accesos" class="form-label">Filtrar:</label>
                    <select id="filtro-accesos" class="custom-select d-block w-100">
                        <option value="personal">Personal</option>
                        <option value="estudiantes">Solo estudiantes</option>
                        <option value="sin-accesos">Sin accesos</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="buscador" class="form-label">Buscar:</label>
                    <div class="input-group">
                        <input type="text" id="buscador" class="form-control" placeholder="Buscar usuario...">
                        <button id="limpiar-busqueda" class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Formulario de Permisos -->
    <form method="POST" action="">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Usuario</th>
                        <th>Estudiante</th>
                        <th>Docente</th>
                        <th>Admin</th>
                        <th>Super User</th>
                        <th>Editar Usuarios</th>
                        <th>Editar Notas</th>
                        <th>Editar Accesos</th>
                    </tr>
                </thead>
                <tbody id="tabla-usuarios">
                    <?php
                    global $db;
                    $query = "SELECT id, username, estudiante, docente, admin, super_user, editar_user, editar_nota, editar_acceso FROM users ORDER BY username";
                    $result = $db->query($query);
                    
                    if ($result && $result->num_rows > 0):
                        while ($user = $result->fetch_assoc()):
                            // Determinar tipo de usuario
                            $esEstudiante = $user['estudiante'];
                            $tieneAccesos = $user['docente'] || $user['admin'] || $user['super_user'] || 
                                           $user['editar_user'] || $user['editar_nota'] || $user['editar_acceso'];
                            
                            $clases = 'fila-usuario';
                            $clases .= $esEstudiante ? ' estudiante' : '';
                            $clases .= $tieneAccesos ? ' personal' : '';
                            $clases .= (!$esEstudiante && !$tieneAccesos) ? ' sin-accesos' : '';
                    ?>
                    <tr class="<?= $clases ?>" data-nombre="<?= htmlspecialchars(strtolower($user['username'])) ?>">
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][estudiante]" <?= $user['estudiante'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][docente]" <?= $user['docente'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][admin]" <?= $user['admin'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][super_user]" <?= $user['super_user'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][editar_user]" <?= $user['editar_user'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][editar_nota]" <?= $user['editar_nota'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][editar_acceso]" <?= $user['editar_acceso'] ? 'checked' : '' ?>>
                        </td>
                    </tr>
                    <?php
                        endwhile;
                    else:
                    ?>
                    <tr>
                        <td colspan="8" class="text-center">No hay usuarios registrados</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="text-right mt-3">
            <button type="submit" name="guardar" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>

<!-- JavaScript para el filtrado en tiempo real -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filtroAccesos = document.getElementById('filtro-accesos');
    const buscador = document.getElementById('buscador');
    const limpiarBusqueda = document.getElementById('limpiar-busqueda');
    const filasUsuarios = document.querySelectorAll('#tabla-usuarios tr.fila-usuario');
    
    // Función para aplicar ambos filtros
    function aplicarFiltros() {
        const filtro = filtroAccesos.value;
        const textoBusqueda = buscador.value.toLowerCase();
        
        filasUsuarios.forEach(fila => {
            const esEstudiante = fila.classList.contains('estudiante');
            const esPersonal = fila.classList.contains('personal');
            const esSinAccesos = fila.classList.contains('sin-accesos');
            const nombreUsuario = fila.getAttribute('data-nombre');
            const coincideBusqueda = nombreUsuario.includes(textoBusqueda);
            
            // Aplicar filtro principal
            let mostrarFila = false;
            
            switch(filtro) {
                case 'personal':
                    mostrarFila = esPersonal;
                    break;
                case 'estudiantes':
                    mostrarFila = esEstudiante;
                    break;
                case 'sin-accesos':
                    mostrarFila = esSinAccesos;
                    break;
            }
            
            // Aplicar búsqueda
            if (textoBusqueda && !coincideBusqueda) {
                mostrarFila = false;
            }
            
            // Mostrar/ocultar fila según los filtros
            fila.style.display = mostrarFila ? '' : 'none';
        });
    }
    
    // Event listeners
    filtroAccesos.addEventListener('change', aplicarFiltros);
    buscador.addEventListener('input', aplicarFiltros);
    
    limpiarBusqueda.addEventListener('click', function() {
        buscador.value = '';
        aplicarFiltros();
    });
    
    // Aplicar filtros al cargar la página (mostrar solo personal por defecto)
    aplicarFiltros();
});
</script>

<?php include("includes/footer.php"); ?>