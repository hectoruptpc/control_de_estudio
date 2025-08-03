<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Horarios Docentes Individuales";
include('../funciones/functions.php');

include("includes/head.php");
?>

<div class="container-fluid">
    <div class="row">
        <main class="col-md-12 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?php echo $titulopag; ?></h1>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                    + Nuevo Docente
                </button>
            </div>

            <ul class="nav nav-tabs mb-4" id="teacherTabs" role="tablist">
                <!-- Las pestañas de profesores se generarán dinámicamente -->
            </ul>
            
            <div class="tab-content" id="teacherTabsContent">
                <!-- Los horarios de cada profesor se generarán dinámicamente -->
            </div>
        </main>
    </div>
</div>

<!-- Modal para agregar docente -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTeacherModalLabel">Agregar Nuevo Docente</h5>
                <button type="button" class="btn-close" data-bs-close="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="teacher-form">
                    <div class="mb-3">
                        <label for="teacher-name" class="form-label">Nombre del Docente</label>
                        <input type="text" class="form-control" id="teacher-name" required>
                    </div>
                    <div class="mb-3">
                        <label for="teacher-code" class="form-label">Código/Número</label>
                        <input type="text" class="form-control" id="teacher-code">
                    </div>
                    <div class="mb-3">
                        <label for="teacher-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="teacher-email">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="save-teacher">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar horario -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" aria-labelledby="addScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addScheduleModalLabel">Agregar Nueva Clase</h5>
                <button type="button" class="btn-close" data-bs-close="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="schedule-form">
                    <input type="hidden" id="current-teacher-id">
                    <div class="mb-3">
                        <label for="subject" class="form-label">Asignatura</label>
                        <input type="text" class="form-control" id="subject" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="day" class="form-label">Día</label>
                            <select class="form-select" id="day" required>
                                <option value="" selected disabled>Seleccionar día</option>
                                <option value="1">Lunes</option>
                                <option value="2">Martes</option>
                                <option value="3">Miércoles</option>
                                <option value="4">Jueves</option>
                                <option value="5">Viernes</option>
                                <option value="6">Sábado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="classroom" class="form-label">Aula</label>
                            <input type="text" class="form-control" id="classroom" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start-time" class="form-label">Hora Inicio</label>
                            <select class="form-select" id="start-time" required>
                                <option value="" selected disabled>Seleccionar hora</option>
                                <?php for($h=7; $h<=20; $h++): ?>
                                    <option value="<?php echo str_pad($h, 2, '0', STR_PAD_LEFT); ?>:00"><?php echo str_pad($h, 2, '0', STR_PAD_LEFT); ?>:00</option>
                                    <?php if($h < 20): ?>
                                        <option value="<?php echo str_pad($h, 2, '0', STR_PAD_LEFT); ?>:30"><?php echo str_pad($h, 2, '0', STR_PAD_LEFT); ?>:30</option>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="end-time" class="form-label">Hora Fin</label>
                            <select class="form-select" id="end-time" required>
                                <option value="" selected disabled>Seleccionar hora</option>
                                <?php for($h=7; $h<=20; $h++): ?>
                                    <option value="<?php echo str_pad($h, 2, '0', STR_PAD_LEFT); ?>:00"><?php echo str_pad($h, 2, '0', STR_PAD_LEFT); ?>:00</option>
                                    <?php if($h < 20): ?>
                                        <option value="<?php echo str_pad($h, 2, '0', STR_PAD_LEFT); ?>:30"><?php echo str_pad($h, 2, '0', STR_PAD_LEFT); ?>:30</option>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="save-schedule">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos de ejemplo
    let teachers = [
        {
            id: 1,
            name: "Prof. Martínez",
            code: "PROF-001",
            email: "martinez@escuela.edu",
            schedule: [
                {
                    subject: "Matemáticas",
                    day: 1, // Lunes
                    startTime: "08:00",
                    endTime: "10:00",
                    classroom: "Aula 101"
                },
                {
                    subject: "Física",
                    day: 3, // Miércoles
                    startTime: "10:30",
                    endTime: "12:00",
                    classroom: "Aula 102"
                }
            ]
        },
        {
            id: 2,
            name: "Prof. González",
            code: "PROF-002",
            email: "gonzalez@escuela.edu",
            schedule: [
                {
                    subject: "Literatura",
                    day: 2, // Martes
                    startTime: "09:30",
                    endTime: "11:00",
                    classroom: "Aula 203"
                },
                {
                    subject: "Historia",
                    day: 4, // Jueves
                    startTime: "14:00",
                    endTime: "16:00",
                    classroom: "Aula 205"
                }
            ]
        }
    ];

    // Inicializar la interfaz
    renderTeacherTabs();
    renderFirstTeacherSchedule();

    // Renderizar las pestañas de profesores
    function renderTeacherTabs() {
        const $teacherTabs = $('#teacherTabs');
        $teacherTabs.empty();

        teachers.forEach((teacher, index) => {
            const activeClass = index === 0 ? 'active' : '';
            const selected = index === 0 ? 'true' : 'false';
            
            $teacherTabs.append(`
                <li class="nav-item" role="presentation">
                    <button class="nav-link ${activeClass}" 
                            id="teacher-${teacher.id}-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#teacher-${teacher.id}" 
                            type="button" 
                            role="tab" 
                            aria-controls="teacher-${teacher.id}" 
                            aria-selected="${selected}"
                            data-teacher-id="${teacher.id}">
                        ${teacher.name}
                    </button>
                </li>
            `);
        });
    }

    // Renderizar los horarios de todos los profesores
    function renderAllSchedules() {
        const $teacherTabsContent = $('#teacherTabsContent');
        $teacherTabsContent.empty();

        teachers.forEach((teacher, index) => {
            const activeClass = index === 0 ? 'show active' : '';
            
            $teacherTabsContent.append(`
                <div class="tab-pane fade ${activeClass}" 
                     id="teacher-${teacher.id}" 
                     role="tabpanel" 
                     aria-labelledby="teacher-${teacher.id}-tab">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>Horario de ${teacher.name}</h4>
                        <button class="btn btn-sm btn-outline-primary add-schedule-btn" 
                                data-teacher-id="${teacher.id}">
                            + Agregar Clase
                        </button>
                    </div>
                    <div class="teacher-schedule" id="teacher-${teacher.id}-schedule"></div>
                </div>
            `);

            renderTeacherSchedule(teacher.id);
        });
    }

    // Renderizar el horario del primer profesor
    function renderFirstTeacherSchedule() {
        if (teachers.length > 0) {
            renderAllSchedules();
        }
    }

    // Renderizar el horario de un profesor específico
    function renderTeacherSchedule(teacherId) {
        const teacher = teachers.find(t => t.id == teacherId);
        if (!teacher) return;

        const $scheduleContainer = $(`#teacher-${teacherId}-schedule`);
        $scheduleContainer.empty();

        // Crear tabla de horario
        const $table = $(`
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Hora</th>
                            <th>Lunes</th>
                            <th>Martes</th>
                            <th>Miércoles</th>
                            <th>Jueves</th>
                            <th>Viernes</th>
                            <th>Sábado</th>
                        </tr>
                    </thead>
                    <tbody class="schedule-body"></tbody>
                </table>
            </div>
        `);

        const $tbody = $table.find('.schedule-body');

        // Crear franjas horarias de 7:00 a 21:00 en intervalos de 30 minutos
        for(let h = 7; h < 21; h++) {
            for(let m = 0; m < 60; m += 30) {
                const time = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
                const $row = $(`<tr data-time="${time}"></tr>`);
                
                // Columna de hora
                $row.append(`<td class="fw-bold">${time}</td>`);
                
                // Columnas para cada día (Lunes a Sábado)
                for(let day = 1; day <= 6; day++) {
                    $row.append('<td></td>');
                }
                
                $tbody.append($row);
            }
        }

        // Agregar los horarios del profesor
        teacher.schedule.forEach(schedule => {
            addScheduleToTable(teacherId, schedule);
        });

        $scheduleContainer.append($table);
    }

    // Agregar un horario a la tabla de un profesor
    function addScheduleToTable(teacherId, schedule) {
        const startParts = schedule.startTime.split(':');
        const endParts = schedule.endTime.split(':');
        
        const startHour = parseInt(startParts[0]);
        const startMin = parseInt(startParts[1]);
        const endHour = parseInt(endParts[0]);
        const endMin = parseInt(endParts[1]);
        
        // Calcular filas que ocupará este horario
        const startRow = (startHour - 7) * 2 + (startMin === 30 ? 1 : 0);
        const endRow = (endHour - 7) * 2 + (endMin === 30 ? 1 : 0);
        const rowSpan = endRow - startRow;
        
        // Obtener todas las celdas de tiempo
        const $timeCells = $(`#teacher-${teacherId}-schedule tr[data-time="${schedule.startTime}"] td`);
        
        // Celda del día correspondiente (día + 1 porque la columna 0 es la hora)
        const $dayCell = $timeCells.eq(schedule.day);
        
        // Crear elemento del horario
        const $scheduleItem = $(`
            <div class="p-2 bg-primary bg-opacity-10 rounded">
                <div class="fw-bold text-primary">${schedule.subject}</div>
                <small class="text-muted">${schedule.classroom}</small>
            </div>
        `);
        
        // Ocupar el espacio necesario
        $dayCell.attr('rowspan', rowSpan).html($scheduleItem);
        
        // Eliminar celdas que ahora están ocupadas
        for(let i = 1; i < rowSpan; i++) {
            $(`#teacher-${teacherId}-schedule tr[data-time="${schedule.startTime}"]`).next().find('td').eq(schedule.day).remove();
        }
    }

    // Guardar nuevo docente
    $('#save-teacher').click(function() {
        const name = $('#teacher-name').val();
        const code = $('#teacher-code').val();
        const email = $('#teacher-email').val();

        if (!name) {
            alert('Por favor ingrese el nombre del docente');
            return;
        }

        const newTeacher = {
            id: teachers.length > 0 ? Math.max(...teachers.map(t => t.id)) + 1 : 1,
            name: name,
            code: code,
            email: email,
            schedule: []
        };

        teachers.push(newTeacher);
        
        // Actualizar la interfaz
        renderTeacherTabs();
        renderAllSchedules();
        
        // Seleccionar el nuevo profesor
        $(`#teacher-${newTeacher.id}-tab`).tab('show');
        
        // Cerrar modal y limpiar formulario
        $('#addTeacherModal').modal('hide');
        $('#teacher-form')[0].reset();
    });

    // Guardar nuevo horario
    $('#save-schedule').click(function() {
        const teacherId = $('#current-teacher-id').val();
        const subject = $('#subject').val();
        const day = $('#day').val();
        const classroom = $('#classroom').val();
        const startTime = $('#start-time').val();
        const endTime = $('#end-time').val();

        if (!teacherId || !subject || !day || !classroom || !startTime || !endTime) {
            alert('Por favor complete todos los campos');
            return;
        }

        // Validar que la hora de fin sea mayor que la de inicio
        if (startTime >= endTime) {
            alert('La hora de fin debe ser posterior a la hora de inicio');
            return;
        }

        const newSchedule = {
            subject: subject,
            day: parseInt(day),
            startTime: startTime,
            endTime: endTime,
            classroom: classroom
        };

        // Agregar el horario al profesor correspondiente
        const teacherIndex = teachers.findIndex(t => t.id == teacherId);
        if (teacherIndex !== -1) {
            teachers[teacherIndex].schedule.push(newSchedule);
            renderTeacherSchedule(teacherId);
        }
        
        // Cerrar modal y limpiar formulario
        $('#addScheduleModal').modal('hide');
        $('#schedule-form')[0].reset();
    });

    // Manejar clic en botón "Agregar Clase"
    $(document).on('click', '.add-schedule-btn', function() {
        const teacherId = $(this).data('teacher-id');
        $('#current-teacher-id').val(teacherId);
        $('#addScheduleModal').modal('show');
    });
});
</script>

<?php include("includes/footer.php"); ?>