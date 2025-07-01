<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Estudiantes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="../usuario/css/styles_agregar_estudiante.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="text-center"><i class="fas fa-user-graduate me-2"></i>Registro de Estudiantes</h3>
                    </div>
                    <div class="card-body">
                        <form id="estudianteForm" action="./guardar/guardar_estudiante.php" method="POST" novalidate>
                            <!-- Sección de Datos Personales -->
                            <fieldset class="mb-4">
                                <legend class="fs-5 border-bottom pb-2">Datos Personales</legend>
                                
                                <!-- Cédula -->
                                <div class="row mb-3">
                                    <div class="col-md-2">
                                        <label for="tipoCedula" class="form-label">Tipo Cédula</label>
                                        <select class="form-select" id="tipoCedula" name="tipoCedula" required>
                                            <option value="V">V-</option>
                                            <option value="E">E-</option>
                                        </select>
                                    </div>
                                    <div class="col-md-10">
                                        <label for="cedula" class="form-label">Número de Cédula</label>
                                        <input type="text" class="form-control" id="cedula" name="cedula" placeholder="Ej: 12345678" pattern="[0-9]{6,8}" required>
                                        <div class="invalid-feedback">
                                            Por favor ingrese un número de cédula válido (6-8 dígitos).
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Nombres y Apellidos -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="nombres" class="form-label">Nombres</label>
                                        <input type="text" class="form-control" id="nombres" name="nombres" required>
                                        <div class="invalid-feedback">
                                            Por favor ingrese los nombres del estudiante.
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="apellidos" class="form-label">Apellidos</label>
                                        <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                                        <div class="invalid-feedback">
                                            Por favor ingrese los apellidos del estudiante.
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Género y Fecha Nacimiento -->
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Género</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="genero" id="masculino" value="M" checked required>
                                            <label class="form-check-label" for="masculino">
                                                Masculino (M)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="genero" id="femenino" value="F" required>
                                            <label class="form-check-label" for="femenino">
                                                Femenino (F)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" required>
                                        <div class="invalid-feedback">
                                            Por favor seleccione la fecha de nacimiento.
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="edad" class="form-label">Edad</label>
                                        <input type="text" class="form-control" id="edad" name="edad" readonly>
                                    </div>
                                </div>
                                
                                <!-- Estado Civil -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="estadoCivil" class="form-label">Estado Civil</label>
                                        <select class="form-select" id="estadoCivil" name="estadoCivil" required>
                                            <option value="" selected disabled>Seleccione...</option>
                                            <option value="Soltero/a">Soltero/a</option>
                                            <option value="Casado/a">Casado/a</option>
                                            <option value="Divorciado/a">Divorciado/a</option>
                                            <option value="Viudo/a">Viudo/a</option>
                                            <option value="Unión Libre">Unión Libre</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor seleccione el estado civil.
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            
                            <!-- Sección de Ubicación -->
                            <fieldset class="mb-4">
                                <legend class="fs-5 border-bottom pb-2">Datos de Ubicación</legend>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="estado" class="form-label">Estado</label>
                                        <select class="form-select" id="estado" name="estado" required>
                                            <option value="" selected disabled>Seleccione...</option>
                                            <option value="Amazonas">Amazonas</option>
                                            <option value="Anzoátegui">Anzoátegui</option>
                                            <option value="Apure">Apure</option>
                                            <option value="Aragua">Aragua</option>
                                            <option value="Barinas">Barinas</option>
                                            <option value="Bolívar">Bolívar</option>
                                            <option value="Carabobo">Carabobo</option>
                                            <option value="Cojedes">Cojedes</option>
                                            <option value="Delta Amacuro">Delta Amacuro</option>
                                            <option value="Distrito Capital">Distrito Capital</option>
                                            <option value="Falcón">Falcón</option>
                                            <option value="Guárico">Guárico</option>
                                            <option value="Lara">Lara</option>
                                            <option value="Mérida">Mérida</option>
                                            <option value="Miranda">Miranda</option>
                                            <option value="Monagas">Monagas</option>
                                            <option value="Nueva Esparta">Nueva Esparta</option>
                                            <option value="Portuguesa">Portuguesa</option>
                                            <option value="Sucre">Sucre</option>
                                            <option value="Táchira">Táchira</option>
                                            <option value="Trujillo">Trujillo</option>
                                            <option value="Vargas">Vargas</option>
                                            <option value="Yaracuy">Yaracuy</option>
                                            <option value="Zulia">Zulia</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor seleccione el estado.
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="municipio" class="form-label">Municipio</label>
                                        <input type="text" class="form-control" id="municipio" name="municipio" required>
                                        <div class="invalid-feedback">
                                            Por favor ingrese el municipio.
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="parroquia" class="form-label">Parroquia</label>
                                        <input type="text" class="form-control" id="parroquia" name="parroquia" required>
                                        <div class="invalid-feedback">
                                            Por favor ingrese la parroquia.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="direccion" class="form-label">Dirección Completa</label>
                                        <textarea class="form-control" id="direccion" name="direccion" rows="2" required></textarea>
                                        <div class="invalid-feedback">
                                            Por favor ingrese la dirección.
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            
                            <!-- Sección de Datos Académicos y Contacto -->
                            <fieldset class="mb-4">
                                <legend class="fs-5 border-bottom pb-2">Datos Académicos y Contacto</legend>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="carrera" class="form-label">Carrera</label>
                                        <select class="form-select" id="carrera" name="carrera" required>
                                            <option value="" selected disabled>Seleccione...</option>
                                            <option value="Ingeniería Informática">Ingeniería Informática</option>
                                            <option value="Ingeniería Civil">Ingeniería Civil</option>
                                            <option value="Medicina">Medicina</option>
                                            <option value="Derecho">Derecho</option>
                                            <option value="Administración">Administración</option>
                                            <option value="Contaduría">Contaduría</option>
                                            <option value="Psicología">Psicología</option>
                                            <option value="Educación">Educación</option>
                                            <option value="Arquitectura">Arquitectura</option>
                                            <option value="Comunicación Social">Comunicación Social</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor seleccione la carrera.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="telefono1" class="form-label">Teléfono Principal</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="tel" class="form-control" id="telefono1" name="telefono1" placeholder="Ej: 04121234567" pattern="[0-9]{11}" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Por favor ingrese un número de teléfono válido (11 dígitos).
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="telefono2" class="form-label">Teléfono Secundario (Opcional)</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="tel" class="form-control" id="telefono2" name="telefono2" placeholder="Ej: 04241234567" pattern="[0-9]{11}">
                                        </div>
                                        <div class="invalid-feedback">
                                            Si ingresa un teléfono secundario, debe ser válido (11 dígitos).
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="correo" class="form-label">Correo Electrónico</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" class="form-control" id="correo" name="correo" placeholder="ejemplo@dominio.com" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Por favor ingrese un correo electrónico válido.
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="reset" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-eraser me-1"></i> Limpiar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Guardar Estudiante
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript personalizado -->
    <script>
       document.addEventListener('DOMContentLoaded', function() {
    const formulario = document.getElementById('estudianteForm');
    
    // Calcular edad automáticamente
    document.getElementById('fechaNacimiento').addEventListener('change', function() {
        const fechaNacimiento = new Date(this.value);
        if (isNaN(fechaNacimiento.getTime())) return;
        
        const hoy = new Date();
        let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
        const mes = hoy.getMonth() - fechaNacimiento.getMonth();
        
        if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
            edad--;
        }
        
        document.getElementById('edad').value = edad;
    });
    
    // Manejar envío del formulario
    formulario.addEventListener('submit', async function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Validación del formulario
        if (!this.checkValidity()) {
            this.classList.add('was-validated');
            return;
        }
        
        const botonSubmit = this.querySelector('button[type="submit"]');
        const textoOriginal = botonSubmit.innerHTML;
        
        try {
            // Mostrar estado de carga
            botonSubmit.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
            botonSubmit.disabled = true;
            
            // Obtener datos del formulario
            const formData = new FormData(this);
            const datos = {};
            
            // Convertir FormData a objeto
            for (const [key, value] of formData.entries()) {
                datos[key] = value;
            }
            
            // Enviar datos al servidor
            const respuesta = await fetch(this.action, {
    method: 'POST', // Fuerza método POST
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        tipoCedula: datos.tipoCedula,
        cedula: datos.cedula,
        nombres: datos.nombres,
        apellidos: datos.apellidos,
        genero: datos.genero,
        fechaNacimiento: datos.fechaNacimiento,
        edad: datos.edad,
        estadoCivil: datos.estadoCivil,
        estado: datos.estado,
        municipio: datos.municipio,
        parroquia: datos.parroquia,
        direccion: datos.direccion,
        carrera: datos.carrera,
        telefono1: datos.telefono1,
        telefono2: datos.telefono2 || null, // Manejo de valor opcional
        correo: datos.correo
    })
});
            
            if (!respuesta.ok) {
                throw new Error(`Error HTTP: ${respuesta.status}`);
            }
            
            const resultado = await respuesta.json();
            
            if (resultado.success) {
                // Mostrar mensaje de éxito
                mostrarMensaje('¡Éxito!', resultado.message, 'success');
                formulario.reset();
                formulario.classList.remove('was-validated');
            } else {
                throw new Error(resultado.message || 'Error desconocido del servidor');
            }
            
        } catch (error) {
            console.error('Error:', error);
            mostrarMensaje('Error', error.message, 'error');
            
        } finally {
            // Restaurar botón
            botonSubmit.innerHTML = textoOriginal;
            botonSubmit.disabled = false;
        }
    });
    
    // Función para mostrar mensajes al usuario
    function mostrarMensaje(titulo, mensaje, tipo) {
        // Puedes implementar un sistema de notificaciones más elegante
        alert(`${titulo}: ${mensaje}`);
        
        // Ejemplo con Bootstrap Toast (descomenta si lo tienes implementado)
        /*
        const toast = new bootstrap.Toast(document.getElementById('notificacionToast'));
        document.getElementById('toastTitulo').textContent = titulo;
        document.getElementById('toastMensaje').textContent = mensaje;
        document.getElementById('notificacionToast').classList.add(`text-bg-${tipo}`);
        toast.show();
        */
    }
});
    </script>
</body>
</html>