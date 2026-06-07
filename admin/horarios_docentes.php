<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Gestión de Horarios Docentes";
include('../funciones/functions.php');

cargarPermisosUsuario();
verificarPermiso('horarios');
visita();

global $db;

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax_action'])) {
    switch($_POST['ajax_action']) {
        case 'get_docentes':
            $query = "SELECT DISTINCT u.id, u.nombre FROM docente_seccion ds JOIN users u ON ds.id_usuario = u.id WHERE ds.estatus = 1 ORDER BY u.nombre";
            $result = $db->query($query);
            $options = '<option value="">Seleccionar docente...</option>';
            while($row = $result->fetch_assoc()) {
                $options .= "<option value='{$row['id']}'>{$row['nombre']}</option>";
            }
            echo $options;
            exit();
            
        case 'get_horario':
            $id_docente = (int)$_POST['id_docente'];
            if(!$id_docente) exit();
            
            $query_nombre = "SELECT nombre FROM users WHERE id = ?";
            $stmt = $db->prepare($query_nombre);
            $stmt->bind_param("i", $id_docente);
            $stmt->execute();
            $nombre_docente = $stmt->get_result()->fetch_assoc()['nombre'] ?? 'Docente';
            $stmt->close();
            
            $query = "SELECT h.id_horario, h.dia, TIME_FORMAT(h.hora_inicio, '%H:%i') as hora_inicio, 
                             TIME_FORMAT(h.hora_fin, '%H:%i') as hora_fin, h.aula,
                             m.nombre_materia as materia, c.nombre_carrera, s.codigo_seccion
                      FROM horarios h
                      JOIN docente_seccion ds ON h.id_docente_seccion = ds.id_docente_seccion
                      JOIN materias m ON ds.id_materia = m.id_materia
                      JOIN secciones s ON ds.id_seccion = s.id_seccion
                      JOIN carreras c ON s.id_carrera = c.id_carrera
                      WHERE ds.id_usuario = ?
                      ORDER BY h.dia, h.hora_inicio";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id_docente);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $horarios = [];
            while($row = $result->fetch_assoc()) {
                $horarios[] = $row;
            }
            
            $dias_semana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
            $horas_tabla = [];
            for($h=7;$h<=20;$h++) {
                $horas_tabla[] = sprintf("%02d:00", $h);
                if($h<20) $horas_tabla[] = sprintf("%02d:30", $h);
            }
            
            $horarios_por_dia = array_fill(0,6,[]);
            foreach($horarios as $h) {
                $horarios_por_dia[(int)$h['dia']][] = $h;
            }
            
            $html = '<div class="pdf-header" style="display:none; text-align:center; margin-bottom:5px;">
                        <strong>UNIVERSIDAD POLITECNICA TERRITORIAL DE PUERTO CABELLO</strong><br>
                        HORARIO DE CLASES<br>
                        Docente: ' . htmlspecialchars($nombre_docente) . ' | Fecha: ' . date('d/m/Y') . '
                    </div>';
            $html .= '<div class="table-responsive">
                        <table class="table table-bordered text-center tabla-horario">
                            <thead class="thead-dark">
                                <tr><th class="hora-col">HORA</th>';
            foreach($dias_semana as $dia) $html .= "<th>$dia</th>";
            $html .= '</thead><tbody>';
            
            $skip_cells = [];
            foreach($horas_tabla as $hora) {
                $html .= '<tr><td class="hora-col" style="padding:2px;">'.$hora.'</div>';
                for($dia=0;$dia<=5;$dia++) {
                    if(isset($skip_cells[$dia][$hora])) continue;
                    $clase = null;
                    foreach($horarios_por_dia[$dia] as $c) {
                        if($hora >= $c['hora_inicio'] && $hora < $c['hora_fin']) {
                            $clase = $c;
                            break;
                        }
                    }
                    if($clase) {
                        $h_ini = strtotime($hora);
                        $h_fin = strtotime($clase['hora_fin']);
                        $rowspan = ($h_fin - $h_ini) / 1800;
                        $temp = $h_ini;
                        for($i=1;$i<$rowspan;$i++) {
                            $temp += 1800;
                            $skip_cells[$dia][date('H:i',$temp)] = true;
                        }
                        $html .= '<td rowspan="'.$rowspan.'" class="clase-asignada" style="padding:2px; line-height:1.2;">
                                    <strong>'.htmlspecialchars($clase['materia']).'</strong><br>
                                    '.htmlspecialchars($clase['codigo_seccion']).' - '.htmlspecialchars($clase['aula']).'
                                    <br><button class="btn-eliminar no-print" data-id="'.$clase['id_horario'].'" style="margin-top:2px;">Eliminar</button>
                                </div>';
                    } else {
                        $html .= '<td class="celda-vacia" style="padding:2px;"></div>';
                    }
                }
                $html .= '</tr>';
            }
          
            
            echo $html;
            exit();
            
        case 'get_docente_materias':
            $id_docente = (int)$_POST['id_docente'];
            $query = "SELECT ds.id_docente_seccion, m.nombre_materia, c.nombre_carrera, s.codigo_seccion
                      FROM docente_seccion ds
                      JOIN materias m ON ds.id_materia = m.id_materia
                      JOIN secciones s ON ds.id_seccion = s.id_seccion
                      JOIN carreras c ON s.id_carrera = c.id_carrera
                      WHERE ds.id_usuario = ? AND ds.estatus = 1
                      ORDER BY c.nombre_carrera, m.nombre_materia";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id_docente);
            $stmt->execute();
            $result = $stmt->get_result();
            $options = '<option value="">Seleccionar materia...</option>';
            while($row = $result->fetch_assoc()) {
                $options .= "<option value='{$row['id_docente_seccion']}'>".htmlspecialchars($row['nombre_materia'])." - ".htmlspecialchars($row['nombre_carrera'])." (".htmlspecialchars($row['codigo_seccion']).")</option>";
            }
            echo $options;
            exit();
            
        case 'guardar_asignacion':
            $dia = (int)$_POST['dia'];
            $hora_inicio = $_POST['hora_inicio'];
            $hora_fin = $_POST['hora_fin'];
            $id_docente_seccion = (int)$_POST['id_docente_seccion'];
            $aula = $_POST['aula'];
            $id_docente = (int)$_POST['id_docente'];
            
            if($dia < 0 || $dia > 5 || strtotime($hora_fin) <= strtotime($hora_inicio)) {
                echo json_encode(['success' => false, 'message' => 'Datos invalidos']);
                exit();
            }
            
            $query = "SELECT COUNT(*) as c FROM horarios h JOIN docente_seccion ds ON h.id_docente_seccion = ds.id_docente_seccion WHERE ds.id_usuario = ? AND h.dia = ? AND ((h.hora_inicio < ? AND h.hora_fin > ?) OR (h.hora_inicio >= ? AND h.hora_inicio < ?))";
            $stmt = $db->prepare($query);
            $stmt->bind_param("iissss", $id_docente, $dia, $hora_fin, $hora_inicio, $hora_inicio, $hora_fin);
            $stmt->execute();
            if($stmt->get_result()->fetch_assoc()['c'] > 0) {
                echo json_encode(['success' => false, 'message' => 'El docente ya tiene clase en este horario']);
                exit();
            }
            
            $query = "SELECT COUNT(*) as c FROM horarios WHERE dia = ? AND aula = ? AND ((hora_inicio < ? AND hora_fin > ?) OR (hora_inicio >= ? AND hora_inicio < ?))";
            $stmt = $db->prepare($query);
            $stmt->bind_param("isssss", $dia, $aula, $hora_fin, $hora_inicio, $hora_inicio, $hora_fin);
            $stmt->execute();
            if($stmt->get_result()->fetch_assoc()['c'] > 0) {
                echo json_encode(['success' => false, 'message' => 'El aula ya esta ocupada']);
                exit();
            }
            
            $query = "INSERT INTO horarios (id_docente_seccion, dia, hora_inicio, hora_fin, aula) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("iisss", $id_docente_seccion, $dia, $hora_inicio, $hora_fin, $aula);
            echo json_encode(['success' => $stmt->execute()]);
            exit();
            
        case 'eliminar_asignacion':
            $id_horario = (int)$_POST['id_horario'];
            $query = "DELETE FROM horarios WHERE id_horario = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id_horario);
            echo json_encode(['success' => $stmt->execute()]);
            exit();
    }
}

include("includes/head.php");
?>

<style>
.tabla-horario {
    width: 100%;
    border-collapse: collapse;
    font-size: 9px;
}
.tabla-horario th {
    background-color: #2c3e50;
    color: white;
    border: 1px solid #495057;
    padding: 3px;
}
.tabla-horario td {
    border: 1px solid #6c757d;
    padding: 2px;
    vertical-align: middle;
}
.hora-col {
    background-color: #f8f9fa;
    font-weight: bold;
    width: 40px;
}
.clase-asignada {
    background-color: #d4edda;
    color: #155724;
}
.celda-vacia {
    background-color: #f8f9fa;
}
.btn-eliminar {
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 2px;
    padding: 1px 4px;
    font-size: 8px;
    cursor: pointer;
}
@media print {
    .no-print, .btn, .card-header, .navbar, .container>.row, .modal {
        display: none !important;
    }
    .pdf-header {
        display: block !important;
    }
    @page {
        size: landscape;
        margin: 0.2cm;
    }
    body {
        margin: 0;
        padding: 0;
    }
    .tabla-horario th {
        background-color: #2c3e50 !important;
        color: white !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .clase-asignada {
        background-color: #d4edda !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .tabla-horario td {
        padding: 1px !important;
    }
}
</style>

<div class="container-fluid py-2">
    <div class="row mb-2 no-print">
        <div class="col-12 d-flex justify-content-between">
            <h2 class="h4 mb-0">Horarios Docentes</h2>
            
        </div>
    </div>
    
    <div class="card shadow mb-3 no-print">
        <div class="card-header py-2">Seleccionar Docente</div>
        <div class="card-body py-2">
            <form id="filtroHorarioDocente" class="form-inline">
                <select class="form-control form-control-sm mr-2" id="docente" style="width:300px" required>
                    <option value="">-- Seleccionar docente --</option>
                    <?php
                    $docentes = $db->query("SELECT DISTINCT u.id, u.nombre FROM docente_seccion ds JOIN users u ON ds.id_usuario = u.id WHERE ds.estatus = 1 ORDER BY u.nombre");
                    while($d = $docentes->fetch_assoc()) {
                        echo "<option value='{$d['id']}'>" . htmlspecialchars($d['nombre']) . "</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary btn-sm mr-2">Cargar</button>
                <button type="button" id="btnImprimir" class="btn btn-info btn-sm">Imprimir</button>
            </form>
        </div>
    </div>
    
    <div class="card shadow">
        <div class="card-header py-1 no-print">Horario Semanal</div>
        <div class="card-body py-2">
            <div id="horarioDocenteContainer">
                <div class="alert alert-info text-center py-2">Seleccione un docente para visualizar su horario.</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="asignarMateriaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title">Asignar Clase</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body py-2">
                <form id="formAsignarMateria">
                    <input type="hidden" name="ajax_action" value="guardar_asignacion">
                    <input type="hidden" id="celdaDia" name="dia">
                    <input type="hidden" id="idDocenteActual" name="id_docente">
                    
                    <div class="form-group">
                        <label>Materia</label>
                        <select class="form-control form-control-sm" id="selectDocenteMateria" name="id_docente_seccion" required>
                            <option value="">Cargando...</option>
                        </select>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Hora Inicio</label>
                            <select class="form-control form-control-sm" id="horaInicio" name="hora_inicio" required>
                                <?php for($h=7;$h<=20;$h++): ?>
                                    <option value="<?=sprintf("%02d:00",$h)?>"><?=sprintf("%02d:00",$h)?></option>
                                    <?php if($h<20): ?>
                                        <option value="<?=sprintf("%02d:30",$h)?>"><?=sprintf("%02d:30",$h)?></option>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Hora Fin</label>
                            <select class="form-control form-control-sm" id="horaFin" name="hora_fin" required>
                                <?php for($h=7;$h<=20;$h++): ?>
                                    <option value="<?=sprintf("%02d:00",$h)?>"><?=sprintf("%02d:00",$h)?></option>
                                    <option value="<?=sprintf("%02d:30",$h)?>"><?=sprintf("%02d:30",$h)?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Aula</label>
                        <select class="form-control form-control-sm" id="aulaAsignada" name="aula" required>
                            <?php
                            $aulas = $db->query("SELECT CONCAT(nave, ' - ', aula) as aula_nombre FROM aulas ORDER BY nave, aula");
                            while($aula = $aulas->fetch_assoc()) {
                                echo "<option value='".htmlspecialchars($aula['aula_nombre'])."'>".htmlspecialchars($aula['aula_nombre'])."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-sm" id="btnGuardarAsignacion">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    $('#filtroHorarioDocente').submit(function(e) {
        e.preventDefault();
        var idDocente = $('#docente').val();
        if(!idDocente) { alert('Seleccione un docente'); return; }
        
        $('#horarioDocenteContainer').html('<div class="text-center py-3"><div class="spinner-border"></div><p>Cargando...</p></div>');
        
        $.ajax({
            url: '',
            type: 'POST',
            data: { ajax_action: 'get_horario', id_docente: idDocente },
            success: function(response) { $('#horarioDocenteContainer').html(response); },
            error: function() { $('#horarioDocenteContainer').html('<div class="alert alert-danger">Error</div>'); }
        });
    });
    
    $('#btnImprimir').click(function() { window.print(); });
    
    $(document).on('click', '.celda-vacia', function() {
        var dia = $(this).index() - 1;
        var hora = $(this).closest('tr').find('.hora-col').text();
        var idDocente = $('#docente').val();
        
        $('#celdaDia').val(dia);
        $('#horaInicio').val(hora);
        $('#idDocenteActual').val(idDocente);
        
        var hp = hora.split(':');
        var hn = parseInt(hp[0]), mn = parseInt(hp[1]) + 30;
        if(mn >= 60) { hn++; mn = 0; }
        $('#horaFin').val((hn<10?'0'+hn:hn)+':'+(mn<10?'0'+mn:mn));
        
        $.ajax({
            url: '',
            type: 'POST',
            data: { ajax_action: 'get_docente_materias', id_docente: idDocente },
            success: function(response) {
                $('#selectDocenteMateria').html(response);
                $('#asignarMateriaModal').modal('show');
            }
        });
    });
    
    $('#btnGuardarAsignacion').click(function() {
        var formData = $('#formAsignarMateria').serialize() + '&id_docente=' + $('#idDocenteActual').val();
        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#asignarMateriaModal').modal('hide');
                    alert('Clase asignada');
                    $('#filtroHorarioDocente').submit();
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    });
    
    $(document).on('click', '.btn-eliminar', function(e) {
        e.stopPropagation();
        if(confirm('Eliminar?')) {
            var idHorario = $(this).data('id');
            $.ajax({
                url: '',
                type: 'POST',
                data: { ajax_action: 'eliminar_asignacion', id_horario: idHorario },
                dataType: 'json',
                success: function(response) {
                    if(response.success) $('#filtroHorarioDocente').submit();
                }
            });
        }
    });
});

function generarReporte() {
    var idDocente = $('#docente').val();
    if(!idDocente) { alert('Seleccione un docente'); return; }
    
    var contenido = $('#horarioDocenteContainer').html();
    var nombreDocente = $('#docente option:selected').text();
    
    var ventana = window.open('', '_blank');
    ventana.document.write('<html><head><title>Horario - ' + nombreDocente + '</title>');
    ventana.document.write('<style>');
    ventana.document.write('body{padding:3px;margin:0;font-family:Arial}');
    ventana.document.write('.tabla-horario{width:100%;border-collapse:collapse;font-size:7px}');
    ventana.document.write('.tabla-horario th{background-color:#2c3e50;color:#fff;padding:2px}');
    ventana.document.write('.tabla-horario td{border:1px solid #6c757d;padding:1px}');
    ventana.document.write('.clase-asignada{background-color:#d4edda}');
    ventana.document.write('.hora-col{background-color:#f8f9fa;width:35px}');
    ventana.document.write('.text-center{text-align:center}');
    ventana.document.write('</style>');
    ventana.document.write('</head><body>');
    ventana.document.write('<div class="text-center" style="margin-bottom:3px;font-size:8px">');
    ventana.document.write('<b>UNIVERSIDAD POLITECNICA TERRITORIAL DE PUERTO CABELLO</b><br>');
    ventana.document.write('HORARIO DE CLASES<br>');
    ventana.document.write('Docente: ' + nombreDocente + ' | Fecha: ' + new Date().toLocaleDateString('es-ES'));
    ventana.document.write('</div>');
    ventana.document.write(contenido);
    ventana.document.write('</body></html>');
    ventana.document.close();
    
    setTimeout(function() { ventana.print(); ventana.close(); }, 500);
}
</script>

<?php include("includes/footer.php"); ?>