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
            $editar_valores = isset($permisos['editar_valores']) ? 1 : 0;
            $editar_estudiante = isset($permisos['editar_estudiante']) ? 1 : 0;
            $agregar_estudiante = isset($permisos['agregar_estudiante']) ? 1 : 0;
            $agregar_docente = isset($permisos['agregar_docente']) ? 1 : 0;
            $editar_docente = isset($permisos['editar_docente']) ? 1 : 0;
            $agregar_carrera = isset($permisos['agregar_carrera']) ? 1 : 0;
            $agregar_materia = isset($permisos['agregar_materia']) ? 1 : 0;
            $editar_materia = isset($permisos['editar_materia']) ? 1 : 0;
            
            // Nuevos permisos
            $pagos = isset($permisos['pagos']) ? 1 : 0;
            $auditoria = isset($permisos['auditoria']) ? 1 : 0;
            $secciones = isset($permisos['secciones']) ? 1 : 0;
            $rela_materia_carrera = isset($permisos['rela_materia_carrera']) ? 1 : 0;
            $periodos_academicos = isset($permisos['periodos_academicos']) ? 1 : 0;
            $asig_secciones = isset($permisos['asig_secciones']) ? 1 : 0;
            $asig_cursos = isset($permisos['asig_cursos']) ? 1 : 0;
            $horarios = isset($permisos['horarios']) ? 1 : 0;
            $gestion_director_carrera = isset($permisos['gestion_director_carrera']) ? 1 : 0;
            $notas_cargadas = isset($permisos['notas_cargadas']) ? 1 : 0;
            $consultar_notas = isset($permisos['consultar_notas']) ? 1 : 0;
            $consultar_notas_pasadas = isset($permisos['consultar_notas_pasadas']) ? 1 : 0;
            $tipos_pago = isset($permisos['tipos_pago']) ? 1 : 0;
            $tipos_horario = isset($permisos['tipos_horario']) ? 1 : 0;
            $horario_personal = isset($permisos['horario_personal']) ? 1 : 0;
            $respaldo_bd = isset($permisos['respaldo_bd']) ? 1 : 0;
            
            $query = "UPDATE users SET 
                     estudiante = ?, 
                     docente = ?, 
                     admin = ?, 
                     super_user = ?, 
                     editar_user = ?, 
                     editar_nota = ?, 
                     editar_acceso = ?,
                     editar_valores = ?,
                     editar_estudiante = ?,
                     agregar_estudiante = ?,
                     agregar_docente = ?,
                     editar_docente = ?,
                     agregar_carrera = ?,
                     agregar_materia = ?,
                     editar_materia = ?,
                     pagos = ?,
                     auditoria = ?,
                     secciones = ?,
                     rela_materia_carrera = ?,
                     periodos_academicos = ?,
                     asig_secciones = ?,
                     asig_cursos = ?,
                     horarios = ?,
                     gestion_director_carrera = ?,
                     notas_cargadas = ?,
                     consultar_notas = ?,
                     consultar_notas_pasadas = ?,
                     tipos_pago = ?,
                     tipos_horario = ?,
                     horario_personal = ?,
                     respaldo_bd = ?
                     WHERE id = ?";
            
            $stmt = $db->prepare($query);
            if ($stmt) {
                $stmt->bind_param("iiiiiiiiiiiiiiiiiiiiiiiiiiiiiii", 
                    $estudiante, 
                    $docente, 
                    $admin, 
                    $super_user, 
                    $editar_user, 
                    $editar_nota, 
                    $editar_acceso,
                    $editar_valores,
                    $editar_estudiante,
                    $agregar_estudiante,
                    $agregar_docente,
                    $editar_docente,
                    $agregar_carrera,
                    $agregar_materia,
                    $editar_materia,
                    $pagos,
                    $auditoria,
                    $secciones,
                    $rela_materia_carrera,
                    $periodos_academicos,
                    $asig_secciones,
                    $asig_cursos,
                    $horarios,
                    $gestion_director_carrera,
                    $notas_cargadas,
                    $consultar_notas,
                    $consultar_notas_pasadas,
                    $tipos_pago,
                    $tipos_horario,
                    $horario_personal,
                    $respaldo_bd,
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
                        <th>Editar Valores</th>
                        <th>Editar Estudiantes</th>
                        <th>Agregar Estudiantes</th>
                        <th>Agregar Docentes</th>
                        <th>Editar Docentes</th>
                        <th>Agregar Carrera</th>
                        <th>Agregar Materia</th>
                        <th>Editar Materia</th>
                        <!-- Nuevos permisos -->
                        <th>Pagos</th>
                        <th>Auditoría</th>
                        <th>Secciones</th>
                        <th>Relación Materia-Carrera</th>
                        <th>Periodos Académicos</th>
                        <th>Asignar Secciones</th>
                        <th>Asignar Cursos</th>
                        <th>Horarios</th>
                        <th>Gestión Director Carrera</th>
                        <th>Notas Cargadas</th>
                        <th>Consultar Notas</th>
                        <th>Consultar Notas Pasadas</th>
                        <th>Tipos de Pago</th>
                        <th>Tipos de Horario</th>
                        <th>Horario Personal</th>
                        <th>Respaldo BD</th>
                    </tr>
                </thead>
                <tbody id="tabla-usuarios">
                    <?php
                    global $db;
                    $query = "SELECT id, username, estudiante, docente, admin, super_user, editar_user, editar_nota, editar_acceso, editar_valores, editar_estudiante, agregar_estudiante, agregar_docente, editar_docente, agregar_carrera, agregar_materia, editar_materia, pagos, auditoria, secciones, rela_materia_carrera, periodos_academicos, asig_secciones, asig_cursos, horarios, gestion_director_carrera, notas_cargadas, consultar_notas, consultar_notas_pasadas, tipos_pago, tipos_horario, horario_personal, respaldo_bd FROM users ORDER BY username";
                    $result = $db->query($query);
                    
                    if ($result && $result->num_rows > 0):
                        while ($user = $result->fetch_assoc()):
                            // Determinar tipo de usuario
                            $esEstudiante = $user['estudiante'];
                            $tieneAccesos = $user['docente'] || $user['admin'] || $user['super_user'] || 
                                           $user['editar_user'] || $user['editar_nota'] || $user['editar_acceso'] || 
                                           $user['editar_valores'] || $user['editar_estudiante'] || $user['agregar_estudiante'] || 
                                           $user['agregar_docente'] || $user['editar_docente'] || $user['agregar_carrera'] || 
                                           $user['agregar_materia'] || $user['editar_materia'] || $user['pagos'] || 
                                           $user['auditoria'] || $user['secciones'] || $user['rela_materia_carrera'] || 
                                           $user['periodos_academicos'] || $user['asig_secciones'] || $user['asig_cursos'] || 
                                           $user['horarios'] || $user['gestion_director_carrera'] || $user['notas_cargadas'] || 
                                           $user['consultar_notas'] || $user['consultar_notas_pasadas'] || $user['tipos_pago'] || 
                                           $user['tipos_horario'] || $user['horario_personal'] || $user['respaldo_bd'];
                            
                            $clases = 'fila-usuario';
                            $clases .= $esEstudiante ? ' estudiante' : '';
                            $clases .= $tieneAccesos ? ' personal' : '';
                            $clases .= (!$esEstudiante && !$tieneAccesos) ? ' sin-accesos' : '';
                    ?>
                    <tr class="<?= $clases ?>" data-nombre="<?= htmlspecialchars(strtolower($user['username'])) ?>">
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <!-- Permisos originales -->
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
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][editar_valores]" <?= $user['editar_valores'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][editar_estudiante]" <?= $user['editar_estudiante'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][agregar_estudiante]" <?= $user['agregar_estudiante'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][agregar_docente]" <?= $user['agregar_docente'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][editar_docente]" <?= $user['editar_docente'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][agregar_carrera]" <?= $user['agregar_carrera'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][agregar_materia]" <?= $user['agregar_materia'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][editar_materia]" <?= $user['editar_materia'] ? 'checked' : '' ?>>
                        </td>
                        
                        <!-- Nuevos permisos -->
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][pagos]" <?= $user['pagos'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][auditoria]" <?= $user['auditoria'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][secciones]" <?= $user['secciones'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][rela_materia_carrera]" <?= $user['rela_materia_carrera'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][periodos_academicos]" <?= $user['periodos_academicos'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][asig_secciones]" <?= $user['asig_secciones'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][asig_cursos]" <?= $user['asig_cursos'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][horarios]" <?= $user['horarios'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][gestion_director_carrera]" <?= $user['gestion_director_carrera'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][notas_cargadas]" <?= $user['notas_cargadas'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][consultar_notas]" <?= $user['consultar_notas'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][consultar_notas_pasadas]" <?= $user['consultar_notas_pasadas'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][tipos_pago]" <?= $user['tipos_pago'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][tipos_horario]" <?= $user['tipos_horario'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][horario_personal]" <?= $user['horario_personal'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="permisos[<?= (int)$user['id'] ?>][respaldo_bd]" <?= $user['respaldo_bd'] ? 'checked' : '' ?>>
                        </td>
                    </tr>
                    <?php
                        endwhile;
                    else:
                    ?>
                    <tr>
                        <td colspan="33" class="text-center">No hay usuarios registrados</td>
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