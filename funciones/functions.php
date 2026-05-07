
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Asegura que los includes relativos dentro de este archivo
// se resuelvan contra la carpeta /funciones en sistemas case-sensitive
chdir(__DIR__);

    include('variables.php');
    include('conexion.php');
    include('cabecera_footer.php');
    include('selector_operador.php');
    include('limite_planes.php');
    include('botoneras.php');
    include('geolocalizacion.php');
    include('registrar.php');
    include('enviar_email.php');


// Función para decodificar y devolver la URL
function g($v) {
    return base64_decode($v);
}

// Asignación de las URLs decodificadas
$github_file_url = g($a);
$github_sin_acceso = g($b);
$oa = g($oa);
$ob = g($ob);
$oc = g($oc);
$od = g($od);


// Función para leer el contenido del archivo
// function readGitHubFile($url) {
//   $options = [
//       "http" => [
//           "method" => "GET",
//           "header" => "User-Agent: PHP" // Necesario para acceder a GitHub
//       ]
//   ];
// 
//   $context = stream_context_create($options);
//   $file_content = file_get_contents($url, false, $context);
// 
//   if ($file_content === FALSE) {
//       return null;
//   } else {
//       return trim($file_content);
//   }
// }
// 
// $qa = readGitHubFile($github_file_url);
// $qe = readGitHubFile($github_sin_acceso);
// 
// function checkAccessKey($url) {
//   global $qe, $oa, $ob, $oc, $od;
//   $oe = readGitHubFile($url);
// 
//   if ($oe === $oa) { 
// } elseif ($oe === $ob) {
//     echo $oc; 
//     echo $qe;
//     exit(); 
// } else {
//     echo $od; 
//     exit(); 
// }
// 
// }
// 
// checkAccessKey($github_file_url);


if (isset($_GET['logout'])) {
  unset($_SESSION['user']);
  $datos_cookie = session_get_cookie_params();
  setcookie("PHPSESSID","",time()-3600,"/");
  session_destroy();
  header("location: login.php");
  exit;
}

//desactivar periodos vencidos

// ▼ Añade esto al inicio del archivo (después de la conexión a la BD) ▼
if (!function_exists('desactivarPeriodosVencidos')) {
function desactivarPeriodosVencidos($db) {
    $query = "UPDATE periodos_academicos SET activo = 0 
              WHERE fecha_fin < CURDATE() AND activo = 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    // Intentar devolver filas afectadas desde el statement si está disponible
    if (isset($stmt) && property_exists($stmt, 'affected_rows')) {
        return $stmt->affected_rows;
    }
    return $db->affected_rows; // Retorna cuántos periodos desactivó (fallback)
}
}

// Ejecutar la función cada vez que alguien entre al sistema
// Desactivación automática de periodos vencidos DESHABILITADA por decisión administrativa.
// Ahora los periodos deben ser desactivados/gestionados manualmente desde la UI.
// Si en el futuro se requiere reactivar la ejecución automática, descomentar la siguiente línea:
// desactivarPeriodosVencidos($db);







// VISUALIZACION DE ESTUDIANTES


function obtenerEstudiantes() {
    global $db;
    
    $estudiantes = [];
    $query = "SELECT 
                u.id,
                u.idusuario AS cedula,
                u.nombre,
                c.nombre_carrera AS carrera,
                u.genero,
                u.tlf AS num_telf,
                u.email AS correo,
                u.fecha_ingreso,
                u.fecha_nac,
                u.embarazada,
                u.status
              FROM users u
              LEFT JOIN carreras c ON u.carrera = c.id_carrera
              WHERE u.user_type = ?
              ORDER BY u.fecha_ingreso DESC";
    
    if ($stmt = $db->prepare($query)) {
        $tipoUsuario = 'estudiante';
        $stmt->bind_param("s", $tipoUsuario);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $estudiantes[] = $row;
            }
            $stmt->close();
            return $estudiantes;
        } else {
            $stmt->close();
            return ['error' => "Error al ejecutar la consulta: " . $stmt->error];
        }
    } else {
        return ['error' => "Error al preparar la consulta: " . $db->error];
    }
}

/**
* Obtiene los detalles completos de un estudiante por su ID
* @param int $id ID del estudiante
* @return array Array con los detalles del estudiante o mensaje de error
*/
function obtenerDetalleEstudiante($id) {
    global $db;
    
    $query = "SELECT 
                e.*,
                c.nombre_carrera AS carrera_nombre
              FROM estudiantes e
              LEFT JOIN carreras c ON e.carrera = c.id_carrera
              WHERE e.id = ?";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $estudiante = $result->fetch_assoc();
        $stmt->close();
        
        if ($estudiante) {
            $estudiante['carrera'] = $estudiante['carrera_nombre'];
            return $estudiante;
        } else {
            return ['error' => "Estudiante no encontrado"];
        }
    } else {
        return ['error' => "Error al obtener detalle del estudiante: " . $stmt->error];
    }
}

/**
 * Funciones para el manejo de estudiantes (users)
 */

function obtenerCarreras($format = 'array') {  // Eliminamos el parámetro $includeOther que ya no necesitamos
    global $db;
    
    $carreras = [];
    $query = "SELECT DISTINCT carrera FROM users 
              WHERE carrera IS NOT NULL AND carrera != '' 
              ORDER BY carrera ASC";
    
    if ($stmt = $db->prepare($query)) {
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $carreras[] = $row['carrera'];
        }
        
        $stmt->close();
        
        return $carreras;  // Simplemente retornamos las carreras sin modificar
    } else {
        error_log("Error al preparar consulta de carreras: " . $db->error);
        return [];
    }
}


function obtenerTodasLasCarreras() {
    global $db;
    
    $carreras = [];
    $query = "SELECT id_carrera, nombre_carrera FROM carreras 
              WHERE activa = 1 AND id_carrera != 0 
              ORDER BY nombre_carrera ASC";
    
    if ($stmt = $db->prepare($query)) {
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $carreras[] = [
                'id' => $row['id_carrera'],
                'nombre' => $row['nombre_carrera']
            ];
        }
        
        $stmt->close();
        return $carreras;
    } else {
        error_log("Error al obtener carreras: " . $db->error);
        return [];
    }
}




/**
* Muestra el estado del estudiante con icono y color adecuado
*/
function mostrarEstadoEstudiante($status) {
  $estados = [
      'Activo' => ['icono' => 'fa-circle-check', 'color' => 'text-success'],
      'Inactivo' => ['icono' => 'fa-circle-pause', 'color' => 'text-danger'],
      'Egresado' => ['icono' => 'fa-graduation-cap', 'color' => 'text-primary'],
      'Graduado' => ['icono' => 'fa-award', 'color' => 'text-warning'],
      'default' => ['icono' => 'fa-circle-question', 'color' => 'text-secondary']
  ];
  
  $config = $estados[$status] ?? $estados['default'];
  
  return '<span class="'.$config['color'].'">
            <i class="fas '.$config['icono'].' me-1"></i>
            '.htmlspecialchars($status).'
        </span>';
}

function validarDatosEstudiante($data) {
    $errors = [];
    $validados = [];
    
    // Validación de cédula
    if (empty($data['idusuario'])) {
        $errors['idusuario'] = "La cédula es obligatoria";
    } else {
        // Extraer tipo y número si viene en formato V-12345678
        if (preg_match('/^([VE])-(\d{6,9})$/', $data['idusuario'], $matches)) {
            $validados['idusuario'] = strtoupper($matches[1]) . '-' . $matches[2];
        } else {
            $errors['idusuario'] = "Formato de cédula inválido. Use: V-12345678 o E-12345678";
        }
    }
    
    // Validación de nombre (permite apóstrofes, NO números)
    if (empty($data['nombre'])) {
        $errors['nombre'] = "El nombre completo es obligatorio";
    } else {
        $nombre = trim($data['nombre']);
        if (preg_match("/[0-9]/", $nombre)) {
            $errors['nombre'] = "El nombre no puede contener números";
        } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']+$/u", $nombre)) {
            $errors['nombre'] = "El nombre contiene caracteres no permitidos. Solo letras, espacios y apóstrofes (')";
        } elseif (strlen($nombre) < 2) {
            $errors['nombre'] = "El nombre es demasiado corto (mínimo 2 caracteres)";
        } elseif (strlen($nombre) > 100) {
            $errors['nombre'] = "El nombre es demasiado largo (máximo 100 caracteres)";
        } else {
            $validados['nombre'] = $nombre;
        }
    }
    
    // Validación de correo
    if (empty($data['email'])) {
        $errors['email'] = "El correo electrónico es obligatorio";
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Correo electrónico no válido. Ejemplo: estudiante@universidad.edu";
    } else {
        $validados['email'] = trim($data['email']);
    }
    
    // Validación de teléfono
    if (empty($data['tlf'])) {
        $errors['tlf'] = "El teléfono es obligatorio";
    } else {
        $telefono_limpio = preg_replace('/\D/', '', $data['tlf']);
        if (!preg_match('/^[0-9]{10,11}$/', $telefono_limpio)) {
            $errors['tlf'] = "Teléfono inválido. Debe tener 10 u 11 dígitos";
        } else {
            $validados['tlf'] = $data['tlf'];
        }
    }
    
    // Validación de fecha de nacimiento
    if (empty($data['fecha_nac'])) {
        $errors['fecha_nac'] = "La fecha de nacimiento es obligatoria";
    } else {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fecha_nac'])) {
            $errors['fecha_nac'] = "Formato de fecha inválido. Use: AAAA-MM-DD";
        } else {
            $fechaNac = DateTime::createFromFormat('Y-m-d', $data['fecha_nac']);
            $hoy = new DateTime();
            $edadMinima = (new DateTime())->modify('-15 years');
            
            if (!$fechaNac) {
                $errors['fecha_nac'] = "Fecha de nacimiento no válida";
            } elseif ($fechaNac > $hoy) {
                $errors['fecha_nac'] = "La fecha de nacimiento no puede ser futura";
            } elseif ($fechaNac > $edadMinima) {
                $errors['fecha_nac'] = "El estudiante debe tener al menos 15 años";
            } else {
                $validados['fecha_nac'] = $data['fecha_nac'];
            }
        }
    }
    
    // Validación de fecha de ingreso
    if (empty($data['fecha_ingreso'])) {
        $errors['fecha_ingreso'] = "La fecha de ingreso es obligatoria";
    } else {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fecha_ingreso'])) {
            $errors['fecha_ingreso'] = "Formato de fecha inválido. Use: AAAA-MM-DD";
        } elseif (!empty($validados['fecha_nac'])) {
            $fechaIngreso = DateTime::createFromFormat('Y-m-d', $data['fecha_ingreso']);
            $fechaNac = DateTime::createFromFormat('Y-m-d', $validados['fecha_nac']);
            
            if ($fechaIngreso < $fechaNac) {
                $errors['fecha_ingreso'] = "La fecha de ingreso no puede ser anterior a la fecha de nacimiento";
            } else {
                $validados['fecha_ingreso'] = $data['fecha_ingreso'];
            }
        } else {
            $validados['fecha_ingreso'] = $data['fecha_ingreso'];
        }
    }
    
    // Validar otros campos requeridos
    $camposRequeridos = [
        'carrera' => 'Carrera',
        'genero' => 'Género',
        'edo_civil' => 'Estado civil',
        'estado' => 'Estado',
        'municipio' => 'Municipio',
        'direccion' => 'Dirección',
        'status' => 'Estado del estudiante'
    ];
    
    foreach ($camposRequeridos as $campo => $nombre) {
        if (empty($data[$campo])) {
            $errors[$campo] = "El campo '$nombre' es requerido";
        } else {
            $validados[$campo] = trim($data[$campo]);
        }
    }

    if (!empty($data['genero']) && $data['genero'] === 'Femenino') {
        if ($data['embarazada'] !== '0' && $data['embarazada'] !== '1') {
            $errors['embarazada'] = "Debe indicar si la estudiante está embarazada o no";
        } else {
            $validados['embarazada'] = (int)$data['embarazada'];
        }
    } else {
        $validados['embarazada'] = isset($data['embarazada']) && ($data['embarazada'] === '0' || $data['embarazada'] === '1')
            ? (int)$data['embarazada']
            : 0;
    }
    
    // Validar campos opcionales que permiten apóstrofes
    $camposConApostrofes = [
        'etnia' => 'Etnia',
        'direccion' => 'Dirección',
        'punto_referencia' => 'Punto de referencia',
        'enfermedad' => 'Enfermedades',
        'discapacida' => 'Discapacidad'
    ];
    
    foreach ($camposConApostrofes as $campo => $nombre) {
        if (!empty($data[$campo])) {
            if (!preg_match("/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s'\",.;:¡!¿?()\-]+$/u", trim($data[$campo]))) {
                $errors[$campo] = "El campo '$nombre' contiene caracteres no válidos";
            } else {
                $validados[$campo] = trim($data[$campo]);
            }
        }
    }
    
    // Validar celular si existe
    if (!empty($data['cel'])) {
        $celular_limpio = preg_replace('/\D/', '', $data['cel']);
        if (!preg_match('/^[0-9]{10,11}$/', $celular_limpio)) {
            $errors['cel'] = "Celular inválido. Debe tener 10 u 11 dígitos";
        } else {
            $validados['cel'] = trim($data['cel']);
        }
    }
    
    // Validar teléfono opcional si existe
    if (!empty($data['num_telf_opc'])) {
        $telefono_opc_limpio = preg_replace('/\D/', '', $data['num_telf_opc']);
        if (!preg_match('/^[0-9]{10,11}$/', $telefono_opc_limpio)) {
            $errors['num_telf_opc'] = "Teléfono opcional inválido. Debe tener 10 u 11 dígitos";
        } else {
            $validados['num_telf_opc'] = trim($data['num_telf_opc']);
        }
    }
    
    // Validar campos numéricos
    if (isset($data['grupo_familiar']) && $data['grupo_familiar'] !== '') {
        if (!is_numeric($data['grupo_familiar']) || $data['grupo_familiar'] < 0) {
            $errors['grupo_familiar'] = "El grupo familiar debe ser un número positivo";
        } else {
            $validados['grupo_familiar'] = (int)$data['grupo_familiar'];
        }
    }
    
    if (isset($data['acargo_usted']) && $data['acargo_usted'] !== '') {
        if (!is_numeric($data['acargo_usted']) || $data['acargo_usted'] < 0) {
            $errors['acargo_usted'] = "Las personas a cargo deben ser un número positivo";
        } else {
            $validados['acargo_usted'] = (int)$data['acargo_usted'];
        }
    }
    
    // Foto de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        if ($_FILES['foto_perfil']['size'] > 5 * 1024 * 1024) {
            $errors['foto_perfil'] = "La foto no debe superar los 5MB";
        }
        
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
        $extension = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $extensionesPermitidas)) {
            $errors['foto_perfil'] = "Solo se permiten archivos JPG, JPEG, PNG, WEBP y PDF";
        }
    }
    
    return [
        'data' => $validados,
        'errors' => $errors
    ];
}

/**
 * Inserta un estudiante en la base de datos
 * @param array $datos Datos del estudiante ya validados
 * @return array Resultado de la operación
 */
function insertarEstudiante($datos) {
    global $db;
    
    if (!$db) {
        return [
            'success' => false,
            'message' => 'Error de conexión a la base de datos'
        ];
    }
    
    $nombreFoto = '';
    
    try {
        // Iniciar transacción
        $db->begin_transaction();

        // ============================
        // 1. MANEJAR FOTO DE PERFIL
        // ============================
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
            $resultadoFoto = subirFotoPerfil($_FILES['foto_perfil']);
            if (isset($resultadoFoto['success']) && $resultadoFoto['success']) {
                $nombreFoto = $resultadoFoto['nombre_archivo'];
            }
        } elseif (!empty($datos['foto_perfil'])) {
            // Si ya viene una foto desde una preinscripción, conservarla.
            $nombreFoto = $datos['foto_perfil'];
        }

        // ============================
        // 2. PREPARAR DATOS BÁSICOS - AHORA EL USERNAME ES LA CÉDULA
        // ============================
        // El username será exactamente el idusuario (cédula con formato V-12345678 o E-12345678)
        $username = $datos['idusuario']; // Ahora usa directamente la cédula como username
        
        // Verificar si el username ya existe
        $username = generarUsernameUnico($username); // Esta función verificará si la cédula ya existe
        
        // La contraseña también será la cédula (se encripta)
        $password = password_hash($datos['idusuario'], PASSWORD_DEFAULT);
        $fecha_act = date('Y-m-d H:i:s');
        $api_key = bin2hex(random_bytes(16));

        // ============================
        // 3. CONFIGURAR VALORES PARA LA TABLA users
        // ============================
        $valores = [
            // Campos principales
            'idusuario' => $datos['idusuario'],
            'nombre' => $datos['nombre'],
            'username' => $username,
            'email' => !empty($datos['email']) ? $datos['email'] : null,
            'tlf' => !empty($datos['tlf']) ? $datos['tlf'] : null,
            'cel' => $datos['cel'] ?? '',
            'direccion' => $datos['direccion'] ?? null,
            'ciudad' => $datos['municipio'] ?? '',
            'estado' => $datos['estado'] ?? null,
            'municipio' => $datos['municipio'] ?? null,
            'parroquia' => $datos['parroquia'] ?? null,
            'etnia' => $datos['etnia'] ?? '',
            'casaapto' => $datos['casaapto'] ?? 'No especificado',
            'punto_referencia' => $datos['punto_referencia'] ?? '',
            'grupo_familiar' => isset($datos['grupo_familiar']) ? (int)$datos['grupo_familiar'] : 0,
            'acargo_usted' => isset($datos['acargo_usted']) ? (int)$datos['acargo_usted'] : 0,
            'fuente_ingresos' => $datos['fuente_ingresos'] ?? '',
            'tipo_vivienda' => $datos['tipo_vivienda'] ?? '',
            'tenencia_vivienda' => $datos['tenencia_vivienda'] ?? '',
            'enfermedad' => $datos['enfermedad'] ?? '',
            'discapacidad' => $datos['discapacidad'] ?? '',
            'titulos' => !empty($datos['titulos']) ? implode('|||', $datos['titulos']) : '',
            'institutos' => !empty($datos['institutos']) ? implode('|||', $datos['institutos']) : '',
            'potencialidades' => $datos['potencialidades'] ?? '',
            
            // Campos de fechas y estado
            'fecha_ingreso' => $datos['fecha_ingreso'] ?? null,
            'fecha_act' => $fecha_act,
            'status' => $datos['status'] ?? 'Activo',
            'user_type' => 'estudiante',
            
            // Campos de autenticación
            'password' => $password,
            'api_key' => $api_key,
            
            // Campos académicos
            'carrera' => $datos['carrera'] ?? null,
            'carrera_di' => $datos['carrera'] ?? null,
            'genero' => $datos['genero'] ?? null,
            'embarazada' => isset($datos['embarazada']) ? (int) $datos['embarazada'] : 0,
            'edo_civil' => $datos['edo_civil'] ?? null,
            'fecha_nac' => $datos['fecha_nac'] ?? null,
            'num_telf_opc' => $datos['num_telf_opc'] ?? '',
            
            // Foto de perfil
            'foto_perfil' => $nombreFoto,
            
            // Campos de permisos/roles (todos a 0 excepto estudiante)
            'usuario' => 0,
            'estudiante' => 1,
            'docente' => 0,
            'admin' => 0,
            'super_user' => 0,
            'editar_user' => 0,
            'editar_nota' => 0,
            'editar_acceso' => 0,
            'editar_valores' => 0,
            'editar_estudiante' => 0,
            'agregar_estudiante' => 0,
            'agregar_docente' => 0,
            'editar_docente' => 0,
            'agregar_carrera' => 0,
            'agregar_materia' => 0,
            'editar_materia' => 0,
            'pagos' => 0,
            'auditoria' => 0,
            'secciones' => 0,
            'rela_materia_carrera' => 0,
            'periodos_academicos' => 0,
            'asig_secciones' => 0,
            'asig_cursos' => 0,
            'horarios' => 0,
            'gestion_director_carrera' => 0,
            'notas_cargadas' => 0,
            'consultar_notas' => 0,
            'consultar_notas_pasadas' => 0,
            'tipos_pago' => 0,
            'tipos_horario' => 0,
            'horario_personal' => 0,
            'respaldo_bd' => 0,
            'gestionar_carrera' => 0,
            'gestion_periodo_academico' => 0,
            'gestion_asig_cursos' => 0,
            'gestion_horario' => 0,
            'titulos_re_materia' => 0,
            'grado' => 0,
            'gestion_grado' => 0,
            'visita' => 0
        ];

        // ============================
        // 4. PREPARAR CONSULTA SQL PARA users
        // ============================
        $columnas = [];
        $placeholders = [];
        $tipos = '';
        $valoresBind = [];
        
        foreach ($valores as $columna => $valor) {
            $columnas[] = $columna;
            $placeholders[] = '?';
            
            // Determinar tipo de dato
            if (in_array($columna, [
                'grupo_familiar', 'acargo_usted', 'usuario', 'estudiante', 'docente', 'admin', 
                'super_user', 'editar_user', 'editar_nota', 'editar_acceso',
                'editar_valores', 'editar_estudiante', 'agregar_estudiante', 'agregar_docente', 
                'editar_docente', 'agregar_carrera', 'agregar_materia', 'editar_materia', 'pagos', 
                'auditoria', 'secciones', 'rela_materia_carrera', 'periodos_academicos', 'asig_secciones', 
                'asig_cursos', 'horarios', 'gestion_director_carrera', 'notas_cargadas', 
                'consultar_notas', 'consultar_notas_pasadas', 'tipos_pago', 'tipos_horario', 
                'horario_personal', 'respaldo_bd', 'gestionar_carrera', 'gestion_periodo_academico', 
                'gestion_asig_cursos', 'gestion_horario', 'titulos_re_materia', 'grado', 
                'gestion_grado', 'visita'
            ])) {
                $tipos .= 'i'; // Entero
                $valoresBind[] = (int)$valor;
            } else {
                $tipos .= 's'; // String
                $valoresBind[] = $valor;
            }
        }
        
        $columnasStr = implode(', ', array_map(function($col) {
            return "`$col`";
        }, $columnas));
        
        $placeholdersStr = implode(', ', $placeholders);
        
        $sql = "INSERT INTO users ($columnasStr) VALUES ($placeholdersStr)";

        // ============================
        // 5. EJECUTAR INSERCIÓN EN users
        // ============================
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar consulta: " . $db->error);
        }
        
        $stmt->bind_param($tipos, ...$valoresBind);
        
        if (!$stmt->execute()) {
            $error = $stmt->error;
            $errno = $stmt->errno;
            
            if ($errno == 1062) { // Duplicate entry
                if (strpos($error, 'idusuario') !== false) {
                    throw new Exception("La cédula {$datos['idusuario']} ya está registrada en el sistema.", 409);
                } elseif (strpos($error, 'username') !== false) {
                    throw new Exception("El nombre de usuario ya existe.", 409);
                } elseif (strpos($error, 'email') !== false) {
                    throw new Exception("El correo electrónico ya está registrado.", 409);
                } else {
                    throw new Exception("Registro duplicado.", 409);
                }
            } elseif ($errno == 1452) { // Foreign key constraint
                throw new Exception("Error: La carrera seleccionada no existe.", 400);
            } else {
                throw new Exception("Error al guardar en la base de datos: " . $error, 500);
            }
        }
        
        $userId = $stmt->insert_id;
        $stmt->close();

        // ============================
        // 6. INSERTAR TÍTULOS OBTENIDOS
        // ============================
        if (!empty($datos['titulos']) && is_array($datos['titulos']) && 
            !empty($datos['institutos']) && is_array($datos['institutos'])) {
            
            $titulos = array_filter(array_map('trim', $datos['titulos']));
            $institutos = array_filter(array_map('trim', $datos['institutos']));
            
            $count = min(count($titulos), count($institutos));
            
            if ($count > 0) {
                $sqlTitulos = "INSERT INTO titulos_obtenidos (id_usuario, nombre, titulo_obtenido, instituto) 
                               VALUES (?, ?, ?, ?)";
                
                $stmtTitulos = $db->prepare($sqlTitulos);
                
                if ($stmtTitulos) {
                    for ($i = 0; $i < $count; $i++) {
                        if (!empty($titulos[$i]) && !empty($institutos[$i])) {
                            $stmtTitulos->bind_param(
                                "isss", 
                                $userId,
                                $datos['nombre'],
                                $titulos[$i],
                                $institutos[$i]
                            );
                            
                            if (!$stmtTitulos->execute()) {
                                error_log("Error al insertar título $i: " . $stmtTitulos->error);
                                // Continuar con los demás títulos
                            }
                        }
                    }
                    
                    $stmtTitulos->close();
                }
            }
        }

        // ============================
        // 7. REGISTRAR EN AUDITORÍA
        // ============================
        if (function_exists('registrarAuditoria')) {
            $valores_nuevos = [
                'idusuario' => $datos['idusuario'],
                'nombre' => $datos['nombre'],
                'username' => $username,
                'carrera' => $datos['carrera'] ?? '',
                'status' => $datos['status'] ?? 'Activo'
            ];
            
            registrarAuditoria(
                "INSERT", 
                "users", 
                $userId, 
                null, 
                $valores_nuevos, 
                "Estudiantes", 
                "Registro de nuevo estudiante. Username: " . $username
            );
        }

        // ============================
        // 8. CONFIRMAR TRANSACCIÓN
        // ============================
        $db->commit();

        // ============================
        // 9. RESPUESTA EXITOSA
        // ============================
        return [
            'success' => true,
            'message' => '✅ Estudiante registrado exitosamente' . 
                        (!empty($nombreFoto) ? ' con foto de perfil.' : '.') .
                        ' Usuario y contraseña: ' . $datos['idusuario'],
            'id' => $userId,
            'username' => $username,
            'foto_perfil' => $nombreFoto,
            'cedula' => $datos['idusuario']
        ];

    } catch(Exception $e) {
        // ============================
        // 10. MANEJAR ERRORES
        // ============================
        if (isset($db) && method_exists($db, 'rollback')) {
            try {
                $db->rollback();
            } catch (Exception $rollbackError) {
                error_log("Error al hacer rollback: " . $rollbackError->getMessage());
            }
        }
        
        // Eliminar foto si se subió
        if (!empty($nombreFoto)) {
            $rutaFoto = '../foto_perfil/' . $nombreFoto;
            if (file_exists($rutaFoto)) {
                @unlink($rutaFoto);
            }
        }
        
        // Registrar error en auditoría
        if (function_exists('registrarAuditoria')) {
            registrarAuditoria(
                "ERROR", 
                "users", 
                null, 
                null, 
                [
                    'nombre' => $datos['nombre'] ?? '',
                    'idusuario' => $datos['idusuario'] ?? '',
                    'error' => $e->getMessage()
                ], 
                "Estudiantes", 
                "Error al registrar estudiante"
            );
        }
        
        error_log("Error en insertarEstudiante: " . $e->getMessage());
        
        return [
            'success' => false,
            'message' => '❌ ' . $e->getMessage(),
            'error_code' => $e->getCode()
        ];
    }
}

/**
 * Verifica si una cédula ya existe en users
 */
function estudianteExiste($idusuario) {
    global $db;

    $query = "SELECT COUNT(*) AS count FROM users WHERE idusuario = ? OR username = ?";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param('ss', $idusuario, $idusuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return isset($row['count']) && (int)$row['count'] > 0;
}

/**
 * Verifica si ya existe una preinscripción pendiente para la misma cédula
 */
function preinscripcionPendienteExiste($idusuario) {
    global $db;

    $query = "SELECT COUNT(*) AS count FROM preinscripcion WHERE idusuario = ? AND status = 'Pendiente'";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param('s', $idusuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return isset($row['count']) && (int)$row['count'] > 0;
}

function obtenerPreinscripcionRechazada($idusuario) {
    global $db;

    $query = "SELECT * FROM preinscripcion WHERE idusuario = ? AND status = 'Rechazada' ORDER BY id DESC LIMIT 1";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param('s', $idusuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return $row ?: null;
}

/**
 * Inserta una preinscripción en la tabla preinscripcion
 */
function insertarPreinscripcion($datos) {
    global $db;

    if (empty($datos['idusuario'])) {
        return [
            'success' => false,
            'message' => 'La cédula es obligatoria para la preinscripción.'
        ];
    }

    if (estudianteExiste($datos['idusuario'])) {
        return [
            'success' => false,
            'message' => 'Ya existe un estudiante con esta cédula. Si ya estás inscrito, inicia sesión con tus datos.'
        ];
    }

    if (preinscripcionPendienteExiste($datos['idusuario'])) {
        return [
            'success' => false,
            'message' => 'Ya existe una preinscripción pendiente con esta cédula. Por favor espera la revisión administrativa.'
        ];
    }

    if (!empty($datos['carrera']) && !empty($datos['turno']) && !hayCupoDisponible($datos['carrera'], $datos['turno'])) {
        return [
            'success' => false,
            'message' => 'No hay cupos disponibles para la carrera seleccionada en el turno ' . htmlspecialchars($datos['turno']) . '. Contacte con Secretaría.'
        ];
    }

    $preinscripcionRechazada = obtenerPreinscripcionRechazada($datos['idusuario']);
    $fotoPerfil = '';
    $fechaAct = date('Y-m-d H:i:s');
    $datos['status'] = $datos['status'] ?? 'Pendiente';
    $datos['fecha_ingreso'] = $datos['fecha_ingreso'] ?? date('Y-m-d');
    $datos['fecha_act'] = $fechaAct;
    $datos['username'] = $datos['idusuario'];
    $datos['user_type'] = 'preinscrito';

    // Normalizar arrays relacionados con títulos para mantener índices alineados
    $titulos = [];
    $institutos = [];
    $paisTitulos = [];
    $legalizadoTitulos = [];
    $maxFilasTitulos = max(
        count($datos['titulos'] ?? []),
        count($datos['institutos'] ?? []),
        count($datos['pais_titulo'] ?? []),
        count($datos['legalizado_titulo'] ?? [])
    );

    for ($i = 0; $i < $maxFilasTitulos; $i++) {
        $titulo = trim($datos['titulos'][$i] ?? '');
        $instituto = trim($datos['institutos'][$i] ?? '');
        $paisTitulo = trim($datos['pais_titulo'][$i] ?? '');
        $legalizadoTitulo = trim($datos['legalizado_titulo'][$i] ?? '');

        if ($titulo === '' && $instituto === '' && $paisTitulo === '' && $legalizadoTitulo === '') {
            continue;
        }

        $titulos[] = $titulo;
        $institutos[] = $instituto;
        $paisTitulos[] = $paisTitulo;
        $legalizadoTitulos[] = $legalizadoTitulo;
    }

    try {
        $db->begin_transaction();

        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
            $resultadoFoto = subirFotoPerfil($_FILES['foto_perfil']);
            if (isset($resultadoFoto['success']) && $resultadoFoto['success']) {
                $fotoPerfil = $resultadoFoto['nombre_archivo'];
            }
        }

        $valores = [
            'idusuario' => $datos['idusuario'],
            'nombre' => $datos['nombre'] ?? null,
            'username' => $datos['username'],
            'email' => !empty($datos['email']) ? $datos['email'] : null,
            'tlf' => !empty($datos['tlf']) ? $datos['tlf'] : null,
            'cel' => $datos['cel'] ?? '',
            'direccion' => $datos['direccion'] ?? null,
            'ciudad' => $datos['municipio'] ?? '',
            'estado' => $datos['estado'] ?? null,
            'municipio' => $datos['municipio'] ?? null,
            'parroquia' => $datos['parroquia'] ?? null,
            'etnia' => $datos['etnia'] ?? '',
            'casaapto' => $datos['casaapto'] ?? 'No especificado',
            'comuna' => $datos['comuna'] ?? '',
            'punto_referencia' => $datos['punto_referencia'] ?? '',
            'grupo_familiar' => isset($datos['grupo_familiar']) ? (int)$datos['grupo_familiar'] : 0,
            'acargo_usted' => isset($datos['acargo_usted']) ? (int)$datos['acargo_usted'] : 0,
            'fuente_ingresos' => $datos['fuente_ingresos'] ?? '',
            'tipo_vivienda' => $datos['tipo_vivienda'] ?? '',
            'tenencia_vivienda' => $datos['tenencia_vivienda'] ?? '',
            'enfermedad' => $datos['enfermedad'] ?? '',
            'discapacidad' => $datos['discapacidad'] ?? '',
            'titulos' => !empty($titulos) ? implode('|||', $titulos) : '',
            'institutos' => !empty($institutos) ? implode('|||', $institutos) : '',
            'pais_titulo' => !empty($paisTitulos) ? implode('|||', $paisTitulos) : '',
            'legalizado_titulo' => !empty($legalizadoTitulos) ? implode('|||', $legalizadoTitulos) : '',
            'sede' => $datos['sede'] ?? null,
            'turno' => $datos['turno'] ?? null,
            'potencialidades' => $datos['potencialidades'] ?? '',
            'carrera' => $datos['carrera'] ?? null,
            'genero' => $datos['genero'] ?? null,
            'edo_civil' => $datos['edo_civil'] ?? null,
            'fecha_nac' => $datos['fecha_nac'] ?? null,
            'embarazada' => isset($datos['embarazada']) ? (int)$datos['embarazada'] : 0,
            'num_telf_opc' => $datos['num_telf_opc'] ?? '',
            'fecha_ingreso' => $datos['fecha_ingreso'],
            'fecha_act' => $fechaAct,
            'status' => $datos['status'],
            'user_type' => $datos['user_type'],
            'foto_perfil' => $fotoPerfil,
            'aprobado_por' => null,
            'fecha_aprobado' => null,
            'rechazado_por' => null,
            'fecha_rechazo' => null,
            'motivo_rechazo' => $datos['motivo_rechazo'] ?? null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $columnas = array_keys($valores);
        $placeholders = array_fill(0, count($columnas), '?');
        $tipos = '';
        $valoresBind = [];

        foreach ($valores as $valor) {
            $tipos .= is_int($valor) ? 'i' : 's';
            $valoresBind[] = $valor;
        }

        if ($preinscripcionRechazada) {
            $updateColumns = [];
            $updateValues = [];
            $updateTypes = '';
            foreach ($valores as $key => $valor) {
                if ($key === 'created_at' || $key === 'updated_at') {
                    continue;
                }
                $updateColumns[] = "`$key` = ?";
                $updateTypes .= is_int($valor) ? 'i' : 's';
                $updateValues[] = $valor;
            }

            $updateValues[] = $preinscripcionRechazada['id'];
            $updateTypes .= 'i';

            $sql = "UPDATE preinscripcion SET " . implode(', ', $updateColumns) . " WHERE id = ?";
            $stmt = $db->prepare($sql);
            if (!$stmt) {
                throw new Exception('Error al preparar actualización de preinscripción: ' . $db->error);
            }
            $stmt->bind_param($updateTypes, ...$updateValues);
            if (!$stmt->execute()) {
                throw new Exception('Error al actualizar la preinscripción rechazada: ' . $stmt->error);
            }
            $preinscripcionId = $preinscripcionRechazada['id'];
            $stmt->close();
        } else {
            $sql = "INSERT INTO preinscripcion (`" . implode('`, `', $columnas) . "`) VALUES (" . implode(', ', $placeholders) . ")";
            $stmt = $db->prepare($sql);
            if (!$stmt) {
                throw new Exception('Error al preparar consulta de preinscripción: ' . $db->error);
            }
            $stmt->bind_param($tipos, ...$valoresBind);
            if (!$stmt->execute()) {
                throw new Exception('Error al guardar la preinscripción: ' . $stmt->error);
            }
            $preinscripcionId = $stmt->insert_id;
            $stmt->close();
        }

        $db->commit();

        return [
            'success' => true,
            'message' => '✅ Preinscripción enviada correctamente. Serás contactado cuando el equipo admin la revise.',
            'id' => $preinscripcionId
        ];
    } catch (Exception $e) {
        if (isset($db) && method_exists($db, 'rollback')) {
            $db->rollback();
        }
        if (!empty($fotoPerfil)) {
            $rutaFoto = __DIR__ . '/../foto_perfil/' . $fotoPerfil;
            if (file_exists($rutaFoto)) {
                @unlink($rutaFoto);
            }
        }

        return [
            'success' => false,
            'message' => '❌ ' . $e->getMessage()
        ];
    }
}

/**
 * Obtiene las preinscripciones pendientes
 */
function obtenerPreinscripcionesPendientes($busqueda = null) {
    global $db;

    $preinscripciones = [];
    $busqueda = trim((string)$busqueda);
    $query = "SELECT * FROM preinscripcion WHERE status = 'Pendiente'";

    if ($busqueda !== '') {
        $query .= " AND (idusuario LIKE ? OR nombre LIKE ? OR username LIKE ? OR email LIKE ? OR tlf LIKE ? OR cel LIKE ? OR direccion LIKE ? OR punto_referencia LIKE ? OR potencialidades LIKE ? OR estado LIKE ? OR municipio LIKE ? OR parroquia LIKE ? )";
    }

    $query .= " ORDER BY fecha_ingreso DESC";

    if ($stmt = $db->prepare($query)) {
        if ($busqueda !== '') {
            $like = '%' . $busqueda . '%';
            $stmt->bind_param('ssssssssssss', $like, $like, $like, $like, $like, $like, $like, $like, $like, $like, $like, $like);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $preinscripciones[] = $row;
        }
        $stmt->close();
    }
    return $preinscripciones;
}

/**
 * Obtiene una preinscripción por ID
 */
function obtenerPreinscripcionPorId($id) {
    global $db;

    $query = "SELECT * FROM preinscripcion WHERE id = ? LIMIT 1";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $preinscripcion = $result->fetch_assoc();
    $stmt->close();
    return $preinscripcion ?: null;
}

/**
 * Devuelve la configuración de secretaría por clave
 */
function obtenerConfiguracionSecretaria($clave, $default = null) {
    global $db;

    $query = "SELECT valor FROM secretaria_config WHERE clave = ? LIMIT 1";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        return $default;
    }
    $stmt->bind_param('s', $clave);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return $row['valor'] ?? $default;
}

/**
 * Guarda o actualiza una configuración de secretaría
 */
function guardarConfiguracionSecretaria($clave, $valor) {
    global $db;

    $query = "INSERT INTO secretaria_config (clave, valor) VALUES (?, ?) ON DUPLICATE KEY UPDATE valor = VALUES(valor)";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param('ss', $clave, $valor);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

/**
 * Obtiene los cupos configurados por carrera y turno
 */
function obtenerCuposSecretaria() {
    global $db;

    $cupos = [];
    $query = "SELECT carrera_id, turno, cupos_totales, numero_secciones FROM secretaria_cupos";
    $stmt = $db->prepare($query);
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $cupos[$row['carrera_id']][$row['turno']] = [
                'cupos_totales' => (int)$row['cupos_totales'],
                'numero_secciones' => (int)$row['numero_secciones']
            ];
        }
        $stmt->close();
    }
    return $cupos;
}

/**
 * Guarda o actualiza cupos por carrera y turno
 */
function guardarCupoSecretaria($carreraId, $turno, $cuposTotales, $numeroSecciones = 1) {
    global $db;

    $query = "INSERT INTO secretaria_cupos (carrera_id, turno, cupos_totales, numero_secciones) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE cupos_totales = VALUES(cupos_totales), numero_secciones = VALUES(numero_secciones)";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param('isii', $carreraId, $turno, $cuposTotales, $numeroSecciones);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

/**
 * Obtiene lista de números de sección autorizados por Secretaría para una carrera y turno.
 */
function obtenerNumerosSeccionDisponibles($carreraId, $turno) {
    $cupos = obtenerCuposSecretaria();
    if (empty($cupos[$carreraId][$turno]) || (int)$cupos[$carreraId][$turno]['numero_secciones'] <= 0) {
        return [];
    }

    $max = (int)$cupos[$carreraId][$turno]['numero_secciones'];
    global $db;

    $query = "SELECT numero_seccion FROM secciones WHERE id_carrera = ? AND turno = ? AND status IN ('Pendiente', 'Aprobada')";
    $stmt = $db->prepare($query);
    $usedNumbers = [];
    if ($stmt) {
        $stmt->bind_param('is', $carreraId, $turno);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $usedNumbers[] = (int)$row['numero_seccion'];
        }
        $stmt->close();
    }

    $result = [];
    for ($i = 1; $i <= $max; $i++) {
        $result[] = [
            'numero' => $i,
            'disponible' => !in_array($i, $usedNumbers, true)
        ];
    }
    return $result;
}

/**
 * Obtiene lista de códigos de sección disponibles según rangos definidos.
 */
function obtenerCodigosSeccionDisponibles($carreraId, $turno) {
    global $db;
    $rangos = [];
    $query = "SELECT codigo_inicio, codigo_fin FROM codigos_secciones WHERE id_carrera = ? ORDER BY codigo_inicio";
    $stmt = $db->prepare($query);
    if ($stmt) {
        $stmt->bind_param('i', $carreraId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $rangos[] = $row;
        }
        $stmt->close();
    }

    if (empty($rangos)) {
        return [];
    }

    $usedCodes = [];
    $query_usados = "SELECT DISTINCT CAST(codigo_seccion AS UNSIGNED) as codigo_num FROM secciones WHERE id_carrera = ? AND turno = ? AND codigo_seccion REGEXP '^[0-9]+$' AND status IN ('Pendiente', 'Aprobada')";
    $stmt_usados = $db->prepare($query_usados);
    if ($stmt_usados) {
        $stmt_usados->bind_param('is', $carreraId, $turno);
        $stmt_usados->execute();
        $result_usados = $stmt_usados->get_result();
        while ($row = $result_usados->fetch_assoc()) {
            $usedCodes[] = (int)$row['codigo_num'];
        }
        $stmt_usados->close();
    }

    $available = [];
    foreach ($rangos as $rango) {
        for ($codigo = (int)$rango['codigo_inicio']; $codigo <= (int)$rango['codigo_fin']; $codigo++) {
            if (!in_array($codigo, $usedCodes, true)) {
                $available[] = $codigo;
            }
        }
    }

    return $available;
}

/**
 * Cuenta cuántas preinscripciones están usando cupos para una carrera+turno
 */
function contarPreinscripcionesPorCupo($carreraId, $turno) {
    global $db;

    $checkTable = $db->query("SHOW TABLES LIKE 'preinscripcion'");
    if (!$checkTable || $checkTable->num_rows === 0) {
        return 0;
    }

    $query = "SELECT COUNT(*) AS total FROM preinscripcion WHERE carrera = ? AND turno = ? AND status IN ('Pendiente', 'Aprobada')";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        return 0;
    }
    $stmt->bind_param('is', $carreraId, $turno);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return (int)($row['total'] ?? 0);
}

/**
 * Comprueba si hay cupos disponibles para una carrera y turno
 */
function hayCupoDisponible($carreraId, $turno) {
    global $db;
    
    // Validar parámetros
    if (empty($carreraId) || empty($turno)) {
        error_log("hayCupoDisponible: Parámetros vacíos - carrera: $carreraId, turno: $turno");
        return false;
    }
    
    // Obtener cupos de la tabla secretaria_cupos
    $query = "SELECT cupos_totales FROM secretaria_cupos WHERE carrera_id = ? AND turno = ?";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        error_log("Error preparando consulta hayCupoDisponible: " . $db->error);
        return false;
    }
    
    $stmt->bind_param('is', $carreraId, $turno);
    $stmt->execute();
    $result = $stmt->get_result();
    $cupo = $result->fetch_assoc();
    
    if (!$cupo) {
        error_log("hayCupoDisponible: No hay configuración de cupos para carrera_id: $carreraId, turno: $turno");
        return false;
    }
    
    $total = (int)$cupo['cupos_totales'];
    if ($total <= 0) {
        error_log("hayCupoDisponible: Cupos totales es $total para carrera_id: $carreraId, turno: $turno");
        return false;
    }
    
    $ocupados = contarPreinscripcionesAprobadasPorCarreraYTurno($carreraId, $turno);
    $disponibles = $total - $ocupados;
    
    error_log("hayCupoDisponible: Carrera $carreraId, Turno $turno - Cupos totales: $total, Ocupados: $ocupados, Disponibles: $disponibles");
    
    return $ocupados < $total;
}

/**
 * Cuenta cuántas preinscripciones APROBADAS existen para una carrera y turno
 * NOTA: Cambié el nombre para evitar conflicto con la función existente
 */
function contarPreinscripcionesAprobadasPorCarreraYTurno($carreraId, $turno) {
    global $db;
    
    // Contar preinscripciones APROBADAS (no pendientes)
    $query = "SELECT COUNT(*) as total 
              FROM preinscripcion 
              WHERE carrera = ? AND turno = ? AND status = 'Aprobada'";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        error_log("Error preparando contarPreinscripcionesAprobadasPorCarreraYTurno: " . $db->error);
        return 0;
    }
    
    $stmt->bind_param('is', $carreraId, $turno);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return (int)($row['total'] ?? 0);
}

/**
 * Obtiene o crea automáticamente la configuración de cupos para una carrera y turno
 */
function obtenerOCrearCupos($carreraId, $turno, $cuposPorDefecto = 30) {
    global $db;
    
    // Verificar si existe
    $query = "SELECT id, cupos_totales FROM secretaria_cupos WHERE carrera_id = ? AND turno = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('is', $carreraId, $turno);
    $stmt->execute();
    $result = $stmt->get_result();
    $cupo = $result->fetch_assoc();
    
    if (!$cupo) {
        // Crear cupos por defecto
        $insert = $db->prepare("INSERT INTO secretaria_cupos (carrera_id, turno, cupos_totales, numero_secciones, updated_at) VALUES (?, ?, ?, 1, NOW())");
        $insert->bind_param('isi', $carreraId, $turno, $cuposPorDefecto);
        if ($insert->execute()) {
            error_log("Cupos creados automáticamente para carrera $carreraId, turno $turno con $cuposPorDefecto cupos");
            return $cuposPorDefecto;
        }
        return 0;
    }
    
    return (int)$cupo['cupos_totales'];
}

/**
 * Obtiene secciones aprobadas por carrera y turno
 */
function obtenerSeccionesTrayecto0($carreraId, $turno = null) {
    global $db;

    $secciones = [];
    if ($turno === null) {
        $query = "SELECT s.id_seccion AS id, s.numero_seccion, s.capacidad_maxima AS capacidad FROM secciones s JOIN trayectos t ON s.id_trayecto = t.id_trayecto WHERE s.id_carrera = ? AND t.numero_trayecto = 0 AND s.estatus = 'activa' ORDER BY s.numero_seccion";
        $stmt = $db->prepare($query);
        if ($stmt) {
            $stmt->bind_param('i', $carreraId);
        }
    } else {
        $query = "SELECT s.id_seccion AS id, s.numero_seccion, s.capacidad_maxima AS capacidad FROM secciones s JOIN trayectos t ON s.id_trayecto = t.id_trayecto WHERE s.id_carrera = ? AND s.turno = ? AND t.numero_trayecto = 0 AND s.estatus = 'activa' ORDER BY s.numero_seccion";
        $stmt = $db->prepare($query);
        if ($stmt) {
            $stmt->bind_param('is', $carreraId, $turno);
        }
    }

    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $secciones[] = $row;
        }
        $stmt->close();
    }
    return $secciones;
}

/**
 * Cuenta estudiantes en una sección
 */
function contarEstudiantesEnSeccion($seccionId) {
    global $db;

    $query = "SELECT COUNT(*) AS total FROM users WHERE seccion_id = ?";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        return 0;
    }
    $stmt->bind_param('i', $seccionId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return (int)($row['total'] ?? 0);
}

/**
 * Asigna sección automáticamente al estudiante
 * Si userId es null, asigna estudiantes sin sección de la carrera a secciones disponibles
 */
function asignarSeccionAutomatica($carreraId, $turno, $userId) {
    global $db;

    if ($userId !== null) {
        // Asignar un estudiante específico
        $secciones = obtenerSeccionesTrayecto0($carreraId, $turno);
        if (empty($secciones)) {
            return false; // No hay secciones
        }

        foreach ($secciones as $seccion) {
            $ocupados = contarEstudiantesEnSeccion($seccion['id']);
            if ($ocupados < $seccion['capacidad']) {
                $query = "UPDATE users SET seccion_id = ? WHERE id = ?";
                $stmt = $db->prepare($query);
                if ($stmt) {
                    $stmt->bind_param('ii', $seccion['id'], $userId);
                    $result = $stmt->execute();
                    $stmt->close();
                    return $result;
                }
            }
        }

        return false; // Todas llenas
    }

    // Asignar estudiantes sin sección de la carrera y turno a secciones disponibles
    $secciones = obtenerSeccionesTrayecto0($carreraId, $turno);
    if (empty($secciones)) {
        return false;
    }

    // Obtener estudiantes sin sección asignada de esta carrera y turno
    $query_estudiantes = "SELECT id FROM users WHERE carrera = ? AND turno = ? AND seccion_id IS NULL AND estudiante = 1 AND status = 'Activo'";
    $stmt_est = $db->prepare($query_estudiantes);
    if (!$stmt_est) {
        return false;
    }
    $stmt_est->bind_param('is', $carreraId, $turno);
    $stmt_est->execute();
    $result_est = $stmt_est->get_result();
    $estudiantes_sin_seccion = [];
    while ($row = $result_est->fetch_assoc()) {
        $estudiantes_sin_seccion[] = $row['id'];
    }
    $stmt_est->close();

    if (empty($estudiantes_sin_seccion)) {
        return true; // No hay estudiantes esperando, pero no es error
    }

    $asignados = 0;
    foreach ($secciones as $seccion) {
        $ocupados = contarEstudiantesEnSeccion($seccion['id']);
        $capacidad_disponible = $seccion['capacidad'] - $ocupados;

        if ($capacidad_disponible <= 0) {
            continue;
        }

        $estudiantes_a_asignar = array_slice($estudiantes_sin_seccion, $asignados, $capacidad_disponible);
        if (empty($estudiantes_a_asignar)) {
            break;
        }

        $placeholders = str_repeat('?,', count($estudiantes_a_asignar) - 1) . '?';
        $query_asignar = "UPDATE users SET seccion_id = ? WHERE id IN ($placeholders)";
        $stmt_asignar = $db->prepare($query_asignar);
        if ($stmt_asignar) {
            $params = array_merge([$seccion['id']], $estudiantes_a_asignar);
            $stmt_asignar->bind_param(str_repeat('i', count($params)), ...$params);
            $stmt_asignar->execute();
            $asignados += $stmt_asignar->affected_rows;
            $stmt_asignar->close();
        }

        if ($asignados >= count($estudiantes_sin_seccion)) {
            break;
        }
    }

    return $asignados > 0;
}

/**
 * Crea una nueva sección (para directores)
 */
function crearSeccionDirector($carreraId, $turno, $numeroSeccion, $capacidad, $horario, $createdBy, $codigoSeccion, $idTrayecto, $idPeriodo, $inicia) {
    global $db;
    
    $query = "INSERT INTO secciones (codigo_seccion, id_carrera, turno, numero_seccion, capacidad_maxima, horario, created_by, estatus, id_trayecto, id_periodo, inicia, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'inactiva', ?, ?, ?, NOW())";
    
    $stmt = $db->prepare($query);
    if (!$stmt) {
        error_log("Error prepare: " . $db->error);
        return false;
    }
    
    // s = string, i = integer
    $stmt->bind_param('sisiisiiis', 
        $codigoSeccion, 
        $carreraId, 
        $turno, 
        $numeroSeccion, 
        $capacidad, 
        $horario, 
        $createdBy, 
        $idTrayecto, 
        $idPeriodo, 
        $inicia
    );
    
    $result = $stmt->execute();
    
    if (!$result) {
        error_log("Error execute: " . $stmt->error);
    }
    
    $stmt->close();
    return $result;
}

/**
 * Aprueba una sección
 */
function aprobarSeccion($seccionId, $approvedBy) {
    global $db;

    // Primero obtener información de la sección antes de aprobar
    $query_info = "SELECT id_carrera, turno FROM secciones WHERE id_seccion = ?";
    $stmt_info = $db->prepare($query_info);
    if (!$stmt_info) {
        return false;
    }
    $stmt_info->bind_param('i', $seccionId);
    $stmt_info->execute();
    $result_info = $stmt_info->get_result();
    $seccion_info = $result_info->fetch_assoc();
    $stmt_info->close();

    if (!$seccion_info) {
        return false; // Sección no existe
    }

    // Aprobar la sección
    $query = "UPDATE secciones SET status = 'Aprobada', approved_by = ?, approved_at = NOW() WHERE id_seccion = ?";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param('ii', $approvedBy, $seccionId);
    $result = $stmt->execute();
    $stmt->close();

    if ($result) {
        // Asignar estudiantes automáticamente a la sección aprobada
        asignarSeccionAutomatica($seccion_info['id_carrera'], $seccion_info['turno'], null);
    }

    return $result;
}

/**
 * Elimina una sección junto con horarios y asignaciones activas de estudiantes.
 *
 * @param int $seccionId ID de la sección
 * @param int|null $performedBy ID del usuario que ejecuta la eliminación
 * @return bool True si se eliminó correctamente
 */
function eliminarSeccion($seccionId, $performedBy = null) {
    global $db;

    if (!is_numeric($seccionId) || $seccionId <= 0) {
        error_log("eliminarSeccion: ID de sección inválido: $seccionId");
        return false;
    }

    try {
        $db->begin_transaction();

        // Deshabilitar restricciones de clave foránea temporalmente
        $db->query("SET FOREIGN_KEY_CHECKS = 0");

        // Verificar que la sección existe
        $stmt = $db->prepare("SELECT id_seccion, status FROM secciones WHERE id_seccion = ?");
        if (!$stmt) {
            throw new Exception("Error preparando SELECT secciones: " . $db->error);
        }
        $stmt->bind_param('i', $seccionId);
        if (!$stmt->execute()) {
            throw new Exception("Error ejecutando SELECT secciones: " . $stmt->error);
        }
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            throw new Exception("La sección con ID $seccionId no existe");
        }
        $seccion = $result->fetch_assoc();
        $stmt->close();

        error_log("Intentando eliminar sección $seccionId con status: " . $seccion['status']);

        // Eliminar asignaciones de docente para la sección PRIMERO
        // Esto automáticamente eliminará los horarios gracias a ON DELETE CASCADE
        $stmt = $db->prepare("DELETE FROM docente_seccion WHERE id_seccion = ?");
        if (!$stmt) {
            throw new Exception("Error preparando DELETE docente_seccion: " . $db->error);
        }
        $stmt->bind_param('i', $seccionId);
        if (!$stmt->execute()) {
            throw new Exception("Error ejecutando DELETE docente_seccion: " . $stmt->error);
        }
        $docentes_eliminados = $stmt->affected_rows;
        $stmt->close();

        // Eliminar estudiantes de la sección DESPUÉS
        $stmt = $db->prepare("DELETE FROM estudiante_seccion WHERE id_seccion = ?");
        if (!$stmt) {
            throw new Exception("Error preparando DELETE estudiante_seccion: " . $db->error);
        }
        $stmt->bind_param('i', $seccionId);
        if (!$stmt->execute()) {
            throw new Exception("Error ejecutando DELETE estudiante_seccion: " . $stmt->error);
        }
        $estudiantes_eliminados = $stmt->affected_rows;
        $stmt->close();

        // Finalmente eliminar la sección
        $stmt = $db->prepare("DELETE FROM secciones WHERE id_seccion = ?");
        if (!$stmt) {
            throw new Exception("Error preparando DELETE secciones: " . $db->error);
        }
        $stmt->bind_param('i', $seccionId);
        if (!$stmt->execute()) {
            throw new Exception("Error ejecutando DELETE secciones: " . $stmt->error);
        }
        $stmt->close();

        $db->commit();

        // Log de éxito
        error_log("Sección $seccionId eliminada exitosamente. Docentes eliminados: $docentes_eliminados, Estudiantes eliminados: $estudiantes_eliminados");

        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "DELETE",
                    "secciones",
                    $seccionId,
                    null,
                    [
                        'performed_by' => $performedBy,
                        'seccion_id' => $seccionId
                    ],
                    "Secciones",
                    "Eliminación de sección y limpieza de horarios/estudiantes"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría eliminarSeccion: " . $e->getMessage());
            }
        }

        return true;
    } catch (Exception $e) {
        $db->rollback();
        error_log("Error en eliminarSeccion: " . $e->getMessage());
        return false;
    }
}

/**
 * Rechaza una sección
 */
function rechazarSeccion($seccionId, $approvedBy) {
    return eliminarSeccion($seccionId, $approvedBy);
}

/**
 * Obtiene secciones pendientes de aprobación
 */
function obtenerSeccionesPendientes() {
    global $db;

    $secciones = [];
    $query = "SELECT s.*, s.id_seccion AS id, s.capacidad_maxima AS capacidad, c.nombre_carrera AS carrera_nombre, u.nombre AS creador_nombre FROM secciones s JOIN carreras c ON s.id_carrera = c.id_carrera LEFT JOIN users u ON s.created_by = u.id WHERE s.status = 'Pendiente' ORDER BY s.created_at DESC";
    $result = $db->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $secciones[] = $row;
        }
    }
    return $secciones;
}

/**
 * Acepta una preinscripción y crea el usuario en la tabla users
 */
function aceptarPreinscripcion($id, $adminId) {
    global $db;

    $preinscripcion = obtenerPreinscripcionPorId($id);
    if (!$preinscripcion) {
        return [
            'success' => false,
            'message' => 'Preinscripción no encontrada.'
        ];
    }

    if (estudianteExiste($preinscripcion['idusuario'])) {
        return [
            'success' => false,
            'message' => 'Ya existe un estudiante registrado con esta cédula.'
        ];
    }

    $datos = [
        'idusuario' => $preinscripcion['idusuario'],
        'nombre' => $preinscripcion['nombre'],
        'email' => $preinscripcion['email'],
        'tlf' => $preinscripcion['tlf'],
        'cel' => $preinscripcion['cel'],
        'direccion' => $preinscripcion['direccion'],
        'municipio' => $preinscripcion['municipio'],
        'estado' => $preinscripcion['estado'],
        'parroquia' => $preinscripcion['parroquia'],
        'etnia' => $preinscripcion['etnia'],
        'casaapto' => $preinscripcion['casaapto'],
        'punto_referencia' => $preinscripcion['punto_referencia'],
        'grupo_familiar' => $preinscripcion['grupo_familiar'],
        'acargo_usted' => $preinscripcion['acargo_usted'],
        'fuente_ingresos' => $preinscripcion['fuente_ingresos'],
        'tipo_vivienda' => $preinscripcion['tipo_vivienda'],
        'tenencia_vivienda' => $preinscripcion['tenencia_vivienda'],
        'enfermedad' => $preinscripcion['enfermedad'],
        'discapacidad' => $preinscripcion['discapacidad'],
        'titulos' => !empty($preinscripcion['titulos']) ? explode('|||', $preinscripcion['titulos']) : [],
        'institutos' => !empty($preinscripcion['institutos']) ? explode('|||', $preinscripcion['institutos']) : [],
        'potencialidades' => $preinscripcion['potencialidades'],
        'carrera' => $preinscripcion['carrera'],
        'genero' => $preinscripcion['genero'],
        'edo_civil' => $preinscripcion['edo_civil'],
        'fecha_nac' => $preinscripcion['fecha_nac'],
        'embarazada' => $preinscripcion['embarazada'],
        'num_telf_opc' => $preinscripcion['num_telf_opc'],
        'fecha_ingreso' => $preinscripcion['fecha_ingreso'],
        'status' => 1, // 🔥 CAMBIADO: 1 = Activo (entero, no string)
        'foto_perfil' => $preinscripcion['foto_perfil'] ?? ''
    ];

    $resultado = insertarEstudiante($datos);
    if (!$resultado['success']) {
        return $resultado;
    }

    // Asignar sección automáticamente
    asignarSeccionAutomatica($preinscripcion['carrera'], $preinscripcion['turno'], $resultado['id']);

    $query = "UPDATE preinscripcion SET status = 'Aprobada', aprobado_por = ?, fecha_aprobado = NOW() WHERE id = ?";
    $stmt = $db->prepare($query);
    if ($stmt) {
        $stmt->bind_param('ii', $adminId, $id);
        $stmt->execute();
        $stmt->close();
    }

    return [
        'success' => true,
        'message' => '✅ Preinscripción aceptada y estudiante creado en el sistema.',
        'user_id' => $resultado['id']
    ];
}

/**
 * Rechaza una preinscripción
 */
function rechazarPreinscripcion($id, $adminId, $motivo = null) {
    global $db;

    $query = "UPDATE preinscripcion SET status = 'Rechazada', rechazado_por = ?, fecha_rechazo = NOW(), motivo_rechazo = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        return [
            'success' => false,
            'message' => 'Error al preparar el rechazo de la preinscripción.'
        ];
    }

    $stmt->bind_param('isi', $adminId, $motivo, $id);
    if (!$stmt->execute()) {
        $stmt->close();
        return [
            'success' => false,
            'message' => 'Error al rechazar la preinscripción: ' . $stmt->error
        ];
    }

    $stmt->close();
    return [
        'success' => true,
        'message' => 'Preinscripción rechazada correctamente.'
    ];
}

/**
 * Función auxiliar para verificar si el username (cédula) ya existe
 * Si ya existe, lanza una excepción porque la cédula debe ser única
 */
function generarUsernameUnico($usernameBase) {
    global $db;
    
    // Verificar si el username ya existe
    $sql = "SELECT COUNT(*) as count FROM users WHERE username = ?";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        // Si hay error en la consulta, retornar el username actual
        return $usernameBase;
    }
    
    $stmt->bind_param('s', $usernameBase);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    if ($row['count'] > 0) {
        // Si la cédula ya existe, lanzar excepción
        throw new Exception("La cédula {$usernameBase} ya está registrada en el sistema.", 409);
    }
    
    // Si no existe, retornar la cédula como username
    return $usernameBase;
}

/**
 * Función para subir foto de perfil (debes tenerla ya definida)
 */
function subirFotoPerfil($archivo) {
    $directorio = '../foto_perfil/';
    
    // Crear directorio si no existe
    if (!file_exists($directorio)) {
        mkdir($directorio, 0777, true);
    }
    
    // Validar error de subida
    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        return [
            'success' => false,
            'message' => 'Error al subir archivo: código ' . $archivo['error']
        ];
    }
    
    // Validar tamaño (5MB máximo)
    $tamanoMaximo = 5 * 1024 * 1024; // 5MB en bytes
    if ($archivo['size'] > $tamanoMaximo) {
        return [
            'success' => false,
            'message' => 'El archivo excede el tamaño máximo de 5MB'
        ];
    }
    
    // Validar tipo de archivo
    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
    $nombreArchivo = $archivo['name'];
    $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
    
    if (!in_array($extension, $extensionesPermitidas)) {
        return [
            'success' => false,
            'message' => 'Formato de archivo no permitido. Use: JPG, JPEG, PNG, WEBP o PDF'
        ];
    }
    
    // Generar nombre único para el archivo
    $nombreUnico = uniqid('foto_', true) . '.' . $extension;
    $rutaDestino = $directorio . $nombreUnico;
    
    // Mover archivo subido
    if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
        return [
            'success' => true,
            'nombre_archivo' => $nombreUnico,
            'ruta' => $rutaDestino,
            'extension' => $extension
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Error al guardar el archivo en el servidor'
        ];
    }
}

// Función subirFotoPerfil ya definida anteriormente más arriba; evitamos redeclararla aquí.

// Función para validar datos de estudiante
function validarEstudiante($datos) {
    $errores = [];
    
    // Validar campos requeridos
    $camposRequeridos = [
        'idusuario', 'nombre', 'carrera', 'genero', 'edo_civil',
        'estado', 'municipio', 'direccion', 'fecha_nac',
        'tlf', 'email', 'fecha_ingreso', 'status'
    ];
    
    foreach ($camposRequeridos as $campo) {
        if (empty($datos[$campo])) {
            $errores[] = "El campo " . str_replace('_', ' ', $campo) . " es requerido";
        }
    }

    if (isset($datos['user_type']) && $datos['user_type'] === 'preinscrito' && empty($datos['turno'])) {
        $errores[] = 'El campo turno es requerido para la preinscripción';
    }
    
    // Validación especial para carrera cuando se selecciona "OTRA"
    if (isset($datos['carrera']) && $datos['carrera'] === 'OTRA' && empty($datos['otra_carrera'])) {
        $errores[] = "Debe especificar el nombre de la carrera cuando selecciona 'OTRA'";
    }
    
    // Validar email
    if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Por favor ingrese un correo electrónico válido';
    }

    // Validar embarazo si el género es femenino
    if (isset($datos['genero']) && $datos['genero'] === 'Femenino') {
        if (!isset($datos['embarazada']) || $datos['embarazada'] === '') {
            $errores[] = 'El campo "embarazada" es obligatorio cuando el género es femenino';
        } elseif (!in_array($datos['embarazada'], ['0', '1', 'Si', 'No'], true)) {
            $errores[] = 'El campo "embarazada" debe ser 0, 1, Si o No';
        }
    }
    
    // Validar teléfono (al menos 10 dígitos)
    if (strlen($datos['tlf']) < 10) {
        $errores[] = 'El teléfono debe tener al menos 10 dígitos';
    }
    
    // Validar que la fecha de ingreso no sea anterior a la de nacimiento
    $fechaNac = new DateTime($datos['fecha_nac']);
    $fechaIngreso = new DateTime($datos['fecha_ingreso']);
    if ($fechaIngreso < $fechaNac) {
        $errores[] = 'La fecha de ingreso no puede ser anterior a la fecha de nacimiento';
    }
    
    return empty($errores) ? true : $errores;
}





// Función para obtener estados civiles
function obtenerEstadosCiviles() {
  return [
      'Soltero/a',
      'Casado/a',
      'Divorciado/a',
      'Viudo/a',
      'Unión Libre'
  ];
}

// Función para obtener estados de estudiante
function obtenerEstadosEstudiante() {
  return [
      'Activo',
      'Inactivo',
      'Egresado',
      'Graduado'
  ];
}

// Función para obtener estudiante por ID
function obtenerEstudiantePorId($id) {
    global $db; // Asumiendo que $db es tu conexión MySQLi
    
    // Validar que el ID sea numérico
    if (!is_numeric($id)) {
        return ['error' => 'ID de estudiante no válido'];
    }
    
    // Preparar la consulta
    $query = "SELECT * FROM users WHERE id = ? AND estudiante = 1";
    $stmt = $db->prepare($query);
    
    if (!$stmt) {
        return ['error' => 'Error al preparar la consulta: ' . $db->error];
    }
    
    // Vincular parámetro y ejecutar
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    // Obtener resultado
    $result = $stmt->get_result();
    
    if (!$result) {
        return ['error' => 'Error al obtener resultados: ' . $stmt->error];
    }
    
    $estudiante = $result->fetch_assoc();
    $stmt->close();
    
    if (!$estudiante) {
        return ['error' => 'Estudiante no encontrado'];
    }
    
    return $estudiante;
}

/**
 * Actualiza los datos de un estudiante con validación y manejo de apóstrofes
 * @param array $datos Datos del estudiante a actualizar
 * @return array Resultado de la operación
 */
function actualizarEstudiante(array $datos): array {
    global $db;
    
    if (!$db) {
        return [
            'success' => false,
            'message' => '❌ Error: No hay conexión a la base de datos. Contacte al administrador.'
        ];
    }
    
    $nombreFoto = '';
    $fotoEliminada = false;
    
    try {
        // Iniciar transacción
        $db->begin_transaction();
        
        // ============================
        // 1. VALIDACIÓN COMPLETA DE DATOS
        // ============================
        $errores_validacion = [];
        
        // Validar ID
        if (empty($datos['id']) || !is_numeric($datos['id'])) {
            $errores_validacion[] = "ID de estudiante no válido";
        }
        
        // Validar cédula
        if (empty($datos['idusuario'])) {
            $errores_validacion[] = "La cédula es obligatoria";
        } elseif (!preg_match('/^[VE]-\d{6,9}$/', $datos['idusuario'])) {
            $errores_validacion[] = "Formato de cédula inválido. Debe ser V-12345678 o E-12345678";
        }
        
        // Validar nombre (permite apóstrofes, NO números)
        if (empty($datos['nombre'])) {
            $errores_validacion[] = "El nombre completo es obligatorio";
        } else {
            $nombre = trim($datos['nombre']);
            if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']+$/u", $nombre)) {
                if (preg_match("/[0-9]/", $nombre)) {
                    $errores_validacion[] = "El nombre no puede contener números. Solo letras, espacios y apóstrofes (')";
                } elseif (preg_match("/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']/u", $nombre)) {
                    $errores_validacion[] = "El nombre contiene caracteres no permitidos. Solo letras, espacios y apóstrofes (')";
                }
            }
            
            if (strlen($nombre) < 2) {
                $errores_validacion[] = "El nombre es demasiado corto (mínimo 2 caracteres)";
            }
            if (strlen($nombre) > 100) {
                $errores_validacion[] = "El nombre es demasiado largo (máximo 100 caracteres)";
            }
        }
        
        // Validar email
        if (!empty($datos['email'])) {
            if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
                $errores_validacion[] = "Correo electrónico no válido. Ejemplo: estudiante@universidad.edu";
            }
            if (strlen($datos['email']) > 100) {
                $errores_validacion[] = "El correo electrónico es demasiado largo (máximo 100 caracteres)";
            }
        }
        
        // Validar teléfono
        if (!empty($datos['tlf'])) {
            $telefono_limpio = preg_replace('/\D/', '', $datos['tlf']);
            if (!preg_match('/^[0-9]{10,11}$/', $telefono_limpio)) {
                $errores_validacion[] = "Teléfono principal inválido. Debe tener 10 u 11 dígitos numéricos";
            }
        }
        
        // Validar celular
        if (!empty($datos['cel'])) {
            $celular_limpio = preg_replace('/\D/', '', $datos['cel']);
            if (!preg_match('/^[0-9]{10,11}$/', $celular_limpio)) {
                $errores_validacion[] = "Celular inválido. Debe tener 10 u 11 dígitos numéricos";
            }
        }
        
        // Validar fecha de nacimiento
        if (!empty($datos['fecha_nac'])) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $datos['fecha_nac'])) {
                $errores_validacion[] = "Formato de fecha de nacimiento inválido. Use: AAAA-MM-DD";
            } else {
                $fechaNac = DateTime::createFromFormat('Y-m-d', $datos['fecha_nac']);
                $hoy = new DateTime();
                $edadMinima = (new DateTime())->modify('-15 years');
                
                if (!$fechaNac) {
                    $errores_validacion[] = "Fecha de nacimiento no válida";
                } elseif ($fechaNac > $hoy) {
                    $errores_validacion[] = "La fecha de nacimiento no puede ser futura";
                } elseif ($fechaNac > $edadMinima) {
                    $errores_validacion[] = "El estudiante debe tener al menos 15 años";
                }
            }
        }
        
        // Validar fecha de ingreso
        if (!empty($datos['fecha_ingreso'])) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $datos['fecha_ingreso'])) {
                $errores_validacion[] = "Formato de fecha de ingreso inválido. Use: AAAA-MM-DD";
            } elseif (!empty($datos['fecha_nac'])) {
                $fechaIngreso = DateTime::createFromFormat('Y-m-d', $datos['fecha_ingreso']);
                $fechaNac = DateTime::createFromFormat('Y-m-d', $datos['fecha_nac']);
                
                if ($fechaIngreso < $fechaNac) {
                    $errores_validacion[] = "La fecha de ingreso no puede ser anterior a la fecha de nacimiento";
                }
            }
        }
        
        // Validar campos de texto con caracteres permitidos
        $camposTexto = [
            'etnia' => 'Etnia',
            'direccion' => 'Dirección',
            'punto_referencia' => 'Punto de referencia',
            'enfermedad' => 'Enfermedades',
            'discapacidad' => 'Discapacidad',
            'titulos' => 'Títulos obtenidos',
            'institutos' => 'Instituciones'
        ];
        
        foreach ($camposTexto as $campo => $nombre) {
            if (!empty($datos[$campo])) {
                if (!preg_match("/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s'\".,;:¡!¿?()\-]+$/u", $datos[$campo])) {
                    $errores_validacion[] = "El campo '$nombre' contiene caracteres no permitidos. Solo letras, números, espacios y signos comunes";
                }
                
                if (strlen($datos[$campo]) > 255) {
                    $errores_validacion[] = "El campo '$nombre' es demasiado largo (máximo 255 caracteres)";
                }
            }
        }
        
        // Validar campos numéricos
        if (isset($datos['grupo_familiar']) && $datos['grupo_familiar'] < 0) {
            $errores_validacion[] = "El grupo familiar no puede ser negativo";
        }
        
        if (isset($datos['acargo_usted']) && $datos['acargo_usted'] < 0) {
            $errores_validacion[] = "Las personas a cargo no pueden ser negativas";
        }
        
        // Si hay errores de validación, lanzar excepción
        if (!empty($errores_validacion)) {
            $mensajeError = "❌ ERRORES DE VALIDACIÓN:\n\n• " . implode("\n• ", $errores_validacion);
            throw new Exception($mensajeError, 400);
        }
        
        // ============================
        // 2. OBTENER VALORES ANTIGUOS PARA AUDITORÍA
        // ============================
        $query_antiguo = "SELECT * FROM users WHERE id = ?";
        $stmt_antiguo = $db->prepare($query_antiguo);
        $stmt_antiguo->bind_param("i", $datos['id']);
        $stmt_antiguo->execute();
        $result_antiguo = $stmt_antiguo->get_result();
        
        if ($result_antiguo->num_rows === 0) {
            throw new Exception("❌ Estudiante no encontrado en la base de datos", 404);
        }
        
        $valores_antiguos = $result_antiguo->fetch_assoc();
        $stmt_antiguo->close();
        
        // ============================
        // 3. VERIFICAR QUE LA CÉDULA NO EXISTA EN OTRO USUARIO
        // ============================
        $query_verificar = "SELECT id, nombre FROM users WHERE idusuario = ? AND id != ?";
        $stmt_verificar = $db->prepare($query_verificar);
        $stmt_verificar->bind_param("si", $datos['idusuario'], $datos['id']);
        $stmt_verificar->execute();
        $result_verificar = $stmt_verificar->get_result();
        
        if ($result_verificar->num_rows > 0) {
            $usuario_existente = $result_verificar->fetch_assoc();
            throw new Exception("❌ La cédula ya está registrada para el estudiante: " . $usuario_existente['nombre'], 409);
        }
        $stmt_verificar->close();
        
        // ============================
        // 4. MANEJAR FOTO DE PERFIL
        // ============================
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
            $resultadoFoto = subirFotoPerfil($_FILES['foto_perfil']);
            if (isset($resultadoFoto['success']) && $resultadoFoto['success']) {
                $nombreFoto = $resultadoFoto['nombre_archivo'];
                $datos['foto_perfil'] = $nombreFoto;
                
                if (!empty($valores_antiguos['foto_perfil'])) {
                    $rutaFotoAntigua = '../foto_perfil/' . $valores_antiguos['foto_perfil'];
                    if (file_exists($rutaFotoAntigua)) {
                        unlink($rutaFotoAntigua);
                        $fotoEliminada = true;
                    }
                }
            } else {
                throw new Exception('❌ Error al subir foto: ' . ($resultadoFoto['message'] ?? 'Error desconocido'), 400);
            }
        }
        
        // ============================
        // 5. CONSTRUIR CONSULTA DE ACTUALIZACIÓN
        // ============================
        $campos = [];
        $valores_bind = [];
        $tipos = '';
        
        $camposMapeo = [
            'idusuario' => 's',
            'nombre' => 's',
            'username' => 's',
            'email' => 's',
            'tlf' => 's',
            'cel' => 's',
            'num_telf_opc' => 's',
            'direccion' => 's',
            'estado' => 's',
            'municipio' => 's',
            'parroquia' => 's',
            'ciudad' => 's',
            'etnia' => 's',
            'casaapto' => 's',
            'punto_referencia' => 's',
            'grupo_familiar' => 'i',
            'acargo_usted' => 'i',
            'fuente_ingresos' => 's',
            'tipo_vivienda' => 's',
            'tenencia_vivienda' => 's',
            'enfermedad' => 's',
            'discapacidad' => 's',
            'titulos' => 's',
            'institutos' => 's',
            'carrera' => 's',
            'genero' => 's',
            'embarazada' => 'i',
            'edo_civil' => 's',
            'fecha_nac' => 's',
            'fecha_ingreso' => 's',
            'status' => 'i',
            'foto_perfil' => 's'
        ];
        
        foreach ($camposMapeo as $campo => $tipo) {
            if (array_key_exists($campo, $datos)) {
                $campos[] = "`$campo` = ?";
                $valores_bind[] = $datos[$campo];
                $tipos .= $tipo;
            }
        }
        
        $campos[] = "fecha_act = NOW()";
        
        if (empty($campos)) {
            throw new Exception('❌ No hay campos para actualizar', 400);
        }
        
        $valores_bind[] = $datos['id'];
        $tipos .= 'i';
        
        $sql = "UPDATE users SET " . implode(', ', $campos) . " WHERE id = ?";
        
        // ============================
        // 6. EJECUTAR ACTUALIZACIÓN
        // ============================
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            throw new Exception("❌ Error en la preparación de la consulta: " . $db->error, 500);
        }
        
        $stmt->bind_param($tipos, ...$valores_bind);
        
        if (!$stmt->execute()) {
            $error = $stmt->error;
            $errno = $stmt->errno;
            
            if ($errno == 1062) {
                throw new Exception("❌ Error: Registro duplicado. La cédula o correo ya existe.", 409);
            } else {
                throw new Exception("❌ Error al ejecutar la consulta: " . $error, 500);
            }
        }
        
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        
        // ============================
        // 7. ACTUALIZAR TÍTULOS OBTENIDOS EN TABLA SEPARADA
        // ============================
        $sqlEliminarTitulos = "DELETE FROM titulos_obtenidos WHERE id_usuario = ?";
        $stmtEliminar = $db->prepare($sqlEliminarTitulos);
        $stmtEliminar->bind_param("i", $datos['id']);
        $stmtEliminar->execute();
        $stmtEliminar->close();
        
        if (!empty($datos['titulos']) && !empty($datos['institutos'])) {
            if (is_string($datos['titulos'])) {
                $titulos = explode('|||', $datos['titulos']);
                $institutos = explode('|||', $datos['institutos']);
            } else {
                $titulos = $datos['titulos'];
                $institutos = $datos['institutos'];
            }
            
            $titulos = array_filter(array_map('trim', (array)$titulos));
            $institutos = array_filter(array_map('trim', (array)$institutos));
            
            $count = min(count($titulos), count($institutos));
            
            if ($count > 0) {
                $sqlTitulos = "INSERT INTO titulos_obtenidos (id_usuario, nombre, titulo_obtenido, instituto) 
                               VALUES (?, ?, ?, ?)";
                
                $stmtTitulos = $db->prepare($sqlTitulos);
                
                if ($stmtTitulos) {
                    for ($i = 0; $i < $count; $i++) {
                        if (!empty($titulos[$i]) && !empty($institutos[$i])) {
                            $stmtTitulos->bind_param(
                                "isss", 
                                $datos['id'],
                                $datos['nombre'],
                                $titulos[$i],
                                $institutos[$i]
                            );
                            
                            if (!$stmtTitulos->execute()) {
                                error_log("Error al insertar título $i: " . $stmtTitulos->error);
                            }
                        }
                    }
                    
                    $stmtTitulos->close();
                }
            }
        }
        
        // ============================
        // 8. REGISTRAR AUDITORÍA
        // ============================
        if (function_exists('registrarAuditoria')) {
            $cambios = [];
            foreach ($datos as $key => $valor) {
                if (isset($valores_antiguos[$key]) && $valores_antiguos[$key] != $valor) {
                    $cambios[$key] = [
                        'antiguo' => $valores_antiguos[$key],
                        'nuevo' => $valor
                    ];
                }
            }
            
            if (!empty($cambios) || $fotoEliminada) {
                $descripcion = "Actualización de datos de estudiante";
                if ($fotoEliminada) {
                    $descripcion .= " (foto actualizada)";
                }
                
                registrarAuditoria(
                    "UPDATE", 
                    "users", 
                    $datos['id'], 
                    $valores_antiguos, 
                    $datos, 
                    "Estudiantes", 
                    $descripcion
                );
            }
        }
        
        // ============================
        // 9. CONFIRMAR TRANSACCIÓN
        // ============================
        $db->commit();
        
        // ============================
        // 10. PREPARAR RESPUESTA
        // ============================
        return [
            'success' => true,
            'message' => $affectedRows > 0 
                ? '✅ Estudiante actualizado exitosamente' 
                : 'ℹ️ No se realizaron cambios (los datos son iguales)',
            'affected_rows' => $affectedRows,
            'cambios' => $cambios ?? [],
            'foto_actualizada' => !empty($nombreFoto)
        ];
        
    } catch(Exception $e) {
        if (isset($db) && method_exists($db, 'rollback')) {
            try {
                $db->rollback();
            } catch (Exception $rollbackError) {
                error_log("Error al hacer rollback: " . $rollbackError->getMessage());
            }
        }
        
        if (!empty($nombreFoto)) {
            $rutaFoto = '../foto_perfil/' . $nombreFoto;
            if (file_exists($rutaFoto)) {
                @unlink($rutaFoto);
            }
        }
        
        $mensajeUsuario = $e->getMessage();
        $codigoError = $e->getCode();
        
        if (function_exists('registrarAuditoria')) {
            registrarAuditoria(
                "ERROR", 
                "users", 
                $datos['id'] ?? null, 
                null, 
                [
                    'nombre' => $datos['nombre'] ?? '',
                    'idusuario' => $datos['idusuario'] ?? '',
                    'error' => substr($e->getMessage(), 0, 200)
                ], 
                "Estudiantes", 
                "Error al actualizar estudiante"
            );
        }
        
        error_log("Error en actualizarEstudiante: " . $e->getMessage());
        
        return [
            'success' => false,
            'message' => $mensajeUsuario,
            'error_code' => $codigoError
        ];
    }
}

function procesarCSVEstudiantes($tmpFilePath, $originalName) {
    global $db;
    
    // Validar extensión del archivo
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    if ($extension !== 'csv') {
        return ['success' => false, 'message' => 'El archivo debe tener extensión .csv'];
    }
    
    // Abrir el archivo CSV
    if (($handle = fopen($tmpFilePath, "r")) === FALSE) {
        return ['success' => false, 'message' => 'No se pudo abrir el archivo CSV'];
    }
    
    // Leer encabezados
    $headers = fgetcsv($handle, 1000, ",");
    if ($headers === FALSE) {
        fclose($handle);
        return ['success' => false, 'message' => 'El archivo CSV está vacío o no tiene el formato correcto'];
    }
    
    // Mapeo de campos esperados
    $camposEsperados = [
        'idusuario', 'nombre', 'email', 'tlf', 'cel', 'direccion', 'ciudad', 
        'estado', 'municipio', 'parroquia', 'fecha_ingreso', 'status', 'carrera', 
        'genero', 'edo_civil', 'fecha_nac', 'num_telf_opc', 'etnia', 'casaapto', 
        'punto_referencia', 'grupo_familiar', 'acargo_usted', 'fuente_ingresos', 
        'tipo_vivienda', 'tenencia_vivienda', 'enfermedad', 'discapacidad', 
        'titulos', 'institutos'
    ];
    
    // Verificar encabezados requeridos
    $headersLower = array_map('strtolower', $headers);
    $requiredFields = ['idusuario', 'nombre', 'email', 'tlf', 'direccion', 'estado', 
                      'municipio', 'fecha_ingreso', 'status', 'carrera', 'genero', 
                      'edo_civil', 'fecha_nac'];
    
    $missingHeaders = array_diff(array_map('strtolower', $requiredFields), $headersLower);
    
    if (!empty($missingHeaders)) {
        fclose($handle);
        return ['success' => false, 'message' => 'Faltan los siguientes encabezados requeridos en el CSV: ' . implode(', ', $missingHeaders)];
    }
    
    // Mapear índices de columnas
    $columnMap = [];
    foreach ($headers as $index => $header) {
        $headerLower = strtolower($header);
        if (in_array($headerLower, $camposEsperados)) {
            $columnMap[$headerLower] = $index;
        }
    }
    
    // Procesar cada fila del CSV
    $lineNumber = 1;
    $successCount = 0;
    $errorCount = 0;
    $errors = [];
    
    // Iniciar transacción
    $db->begin_transaction();
    
    try {
        // Preparar statement para verificar existencia
        $checkStmt = $db->prepare("SELECT id FROM users WHERE idusuario = ? LIMIT 1");
        if (!$checkStmt) {
            throw new Exception("Error al preparar consulta de verificación: " . $db->error);
        }
        
        // Preparar statement para títulos obtenidos
        $titulosStmt = $db->prepare("INSERT INTO titulos_obtenidos (id_usuario, nombre, titulo_obtenido, instituto) VALUES (?, ?, ?, ?)");
        if (!$titulosStmt) {
            throw new Exception("Error al preparar consulta de títulos: " . $db->error);
        }
        
        // Inicializar statement de inserción
        $insertStmt = null;
        $lastFields = null;
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $lineNumber++;
            
            if (empty(implode('', $data))) {
                continue;
            }
            
            // Preparar datos del estudiante
            $estudiante = [
                'usuario' => 0,
                'estudiante' => 1,
                'docente' => 0,
                'admin' => 0,
                'super_user' => 0,
                'editar_user' => 0,
                'editar_nota' => 0,
                'editar_acceso' => 0,
                'potencialidades' => '',
                'editar_valores' => 0,
                'editar_estudiante' => 0,
                'agregar_estudiante' => 0,
                'agregar_docente' => 0,
                'editar_docente' => 0,
                'agregar_carrera' => 0,
                'agregar_materia' => 0,
                'editar_materia' => 0,
                'user_type' => 'estudiante',
                'api_key' => '',
                'fecha_act' => date('Y-m-d H:i:s')
            ];
            
            // Mapear datos del CSV
            foreach ($columnMap as $field => $index) {
                if (isset($data[$index])) {
                    $estudiante[$field] = trim($data[$index]);
                }
            }
            
            // Validar campos requeridos
            $missingFields = [];
            foreach ($requiredFields as $field) {
                if (empty($estudiante[$field])) {
                    $missingFields[] = $field;
                }
            }
            
            if (!empty($missingFields)) {
                $errors[] = "Línea $lineNumber: Faltan campos requeridos: " . implode(', ', $missingFields);
                $errorCount++;
                continue;
            }
            
            // Validar email
            if (!filter_var($estudiante['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Línea $lineNumber: Correo electrónico no válido: " . $estudiante['email'];
                $errorCount++;
                continue;
            }
            
            // Verificar si el idusuario ya existe
            $checkStmt->bind_param("s", $estudiante['idusuario']);
            $checkStmt->execute();
            $checkStmt->store_result();
            
            if ($checkStmt->num_rows > 0) {
                $errors[] = "Línea $lineNumber: La cédula ya existe: " . $estudiante['idusuario'];
                $errorCount++;
                $checkStmt->free_result();
                continue;
            }
            $checkStmt->free_result();
            
            // Valores calculados
            $estudiante['username'] = strtolower(str_replace(' ', '.', $estudiante['nombre']));
            $cedulaLimpia = substr($estudiante['idusuario'], 2);
            $estudiante['password'] = md5($cedulaLimpia);
            
            // Preparar consulta de inserción dinámica solo si los campos cambiaron
            $currentFields = array_keys($estudiante);
            if (!$insertStmt || $currentFields !== $lastFields) {
                if ($insertStmt) {
                    $insertStmt->close();
                }
                
                $fields = $currentFields;
                $placeholders = implode(', ', array_fill(0, count($fields), '?'));
                $types = str_repeat('s', count($fields));
                
                $sql = "INSERT INTO users (" . implode(', ', $fields) . ") VALUES ($placeholders)";
                $insertStmt = $db->prepare($sql);
                $lastFields = $currentFields;
                
                if (!$insertStmt) {
                    throw new Exception("Error en preparación: " . $db->error);
                }
            }
            
            // Vincular parámetros y ejecutar
            $params = array_values($estudiante);
            $insertStmt->bind_param($types, ...$params);
            
            if ($insertStmt->execute()) {
                $userId = $insertStmt->insert_id;
                $successCount++;
                
                // Procesar títulos obtenidos si existen
                if (!empty($estudiante['titulos']) && !empty($estudiante['institutos'])) {
                    $titulos = explode(',', $estudiante['titulos']);
                    $institutos = explode(',', $estudiante['institutos']);
                    
                    $titulos = array_map('trim', $titulos);
                    $institutos = array_map('trim', $institutos);
                    $count = min(count($titulos), count($institutos));
                    
                    for ($i = 0; $i < $count; $i++) {
                        $titulosStmt->bind_param(
                            "isss", 
                            $userId,
                            $estudiante['nombre'],
                            $titulos[$i],
                            $institutos[$i]
                        );
                        if (!$titulosStmt->execute()) {
                            throw new Exception("Error al insertar título: " . $titulosStmt->error);
                        }
                    }
                }
            } else {
                $errors[] = "Línea $lineNumber: Error al insertar: " . $insertStmt->error;
                $errorCount++;
            }
        }
        
        // Cerrar statements
        if ($checkStmt) $checkStmt->close();
        if ($insertStmt) $insertStmt->close();
        if ($titulosStmt) $titulosStmt->close();
        
        $db->commit();
    } catch (Exception $e) {
        $db->rollback();
        if (isset($checkStmt)) $checkStmt->close();
        if (isset($insertStmt)) $insertStmt->close();
        if (isset($titulosStmt)) $titulosStmt->close();
        fclose($handle);
        return ['success' => false, 'message' => 'Error durante la importación: ' . $e->getMessage()];
    }
    
    fclose($handle);
    
    // Preparar mensaje de resultado
    $message = "Proceso completado. ";
    $message .= "Estudiantes insertados: $successCount. ";
    $message .= "Errores: $errorCount.";
    
    if (!empty($errors)) {
        $message .= "\nErrores detallados:\n" . implode("\n", array_slice($errors, 0, 10));
        if (count($errors) > 10) {
            $message .= "\n... y " . (count($errors) - 10) . " más";
        }
    }
    
    return [
        'success' => $errorCount === 0,
        'message' => $message,
        'inserted' => $successCount,
        'errors' => $errorCount,
        'error_details' => $errors
    ];
}



// FUNCIONES PARA GESTIONAR CARRERAS

// Función para obtener carreras desde la tabla carreras
function obtenerListaCompletaCarreras(bool $soloActivas = false): array {
    global $db;
    
    try {
        // Construir consulta base
        $query = "SELECT id_carrera, nombre_carrera, cod_carrera, activa FROM carreras";
        
        // Agregar condición si es necesario
        if ($soloActivas) {
            $query .= " WHERE activa = ?";
        }
        
        $query .= " ORDER BY nombre_carrera";
        
        // Preparar la consulta
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error al preparar consulta: " . $db->error);
        }
        
        // Vincular parámetro si es necesario
        if ($soloActivas) {
            $activa = 1;
            $stmt->bind_param("i", $activa);
        }
        
        // Ejecutar consulta
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar consulta: " . $stmt->error);
        }
        
        // Obtener resultados
        $result = $stmt->get_result();
        $carreras = $result->fetch_all(MYSQLI_ASSOC);
        
        // Liberar recursos
        $result->free();
        $stmt->close();
        
        return $carreras;
        
    } catch (Exception $e) {
        error_log("Error al obtener carreras: " . $e->getMessage());
        return [];
    }
}

function cambiarEstadoCarrera($id_carrera, $estado) {
    global $db;
    
    try {
        // Obtener información actual de la carrera para auditoría
        $carrera_actual = obtenerCarreraPorId($id_carrera);
        
        $query = "UPDATE carreras SET activa = ? WHERE id_carrera = ?";
        $stmt = $db->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $db->error);
        }
        
        $stmt->bind_param("ii", $estado, $id_carrera);
        $resultado = $stmt->execute();
        
        // Verificar si realmente hubo cambios
        if ($resultado && $stmt->affected_rows > 0) {
            // REGISTRAR EN AUDITORÍA - CAMBIO DE ESTADO DE CARRERA
            if (function_exists('registrarAuditoria')) {
                try {
                    $estado_texto_anterior = $carrera_actual['activa'] ? 'Activa' : 'Inactiva';
                    $estado_texto_nuevo = $estado ? 'Activa' : 'Inactiva';
                    
                    registrarAuditoria(
                        "UPDATE", 
                        "carreras", 
                        $id_carrera, 
                        [
                            'activa' => $carrera_actual['activa'],
                            'estado_anterior' => $estado_texto_anterior
                        ], 
                        [
                            'activa' => $estado,
                            'estado_nuevo' => $estado_texto_nuevo,
                            'nombre_carrera' => $carrera_actual['nombre_carrera'] ?? '',
                            'cod_carrera' => $carrera_actual['cod_carrera'] ?? ''
                        ], 
                        "Carreras", 
                        "Cambio de estado de carrera"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría cambiarEstadoCarrera: " . $e->getMessage());
                }
            }
            
            return true;
        }
        
        return false;
        
    } catch (Exception $e) {
        error_log("Error en cambiarEstadoCarrera: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL CAMBIAR ESTADO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "carreras", 
                    $id_carrera, 
                    null, 
                    [
                        'id_carrera' => $id_carrera,
                        'estado_solicitado' => $estado,
                        'error' => $e->getMessage()
                    ], 
                    "Carreras", 
                    "Error al cambiar estado de carrera"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error cambiarEstadoCarrera: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

// Función para agregar nuevas carreras
function registrarNuevaCarrera(
    string $nombre, 
    string $codigo, 
    string $tipo_formacion, 
    int $duracion_anios,
    string $titulo_principal,
    string $titulo_opcional = '',
    string $vigencia_fecha = null
): array {
    global $db;
    
    try {
        // Validar duración
        if ($duracion_anios < 1 || $duracion_anios > 6) {
            return [
                'success' => false,
                'message' => 'La duración debe estar entre 1 y 6 años'
            ];
        }

        // Validar título principal
        if (empty($titulo_principal)) {
            return [
                'success' => false,
                'message' => 'El título principal es obligatorio'
            ];
        }

        // Convertir años a semestres
        $duracion_semestres = $duracion_anios * 2;

        // Verificar duplicados con transacción
        $db->begin_transaction();
        
        // 1. Verificar si el código ya existe
        $checkStmt = $db->prepare("SELECT id_carrera FROM carreras WHERE cod_carrera = ? FOR UPDATE");
        if (!$checkStmt) {
            throw new Exception("Error al preparar consulta de verificación: " . $db->error);
        }
        
        $checkStmt->bind_param("s", $codigo);
        if (!$checkStmt->execute()) {
            throw new Exception("Error al verificar código: " . $checkStmt->error);
        }
        
        $checkStmt->store_result();
        
        if ($checkStmt->num_rows > 0) {
            $checkStmt->close();
            $db->rollback();
            return [
                'success' => false,
                'message' => 'El código de carrera ya existe'
            ];
        }
        $checkStmt->close();
        
        // 2. Insertar nueva carrera (permitir guardar la fecha de vigencia en created_at)
        $insertStmt = $db->prepare("INSERT INTO carreras 
            (nombre_carrera, cod_carrera, tipo_formacion, duracion_semestres, titulo_otorga, otro_titulo, descripcion, created_at, activa) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)");
        
        if (!$insertStmt) {
            throw new Exception("Error al preparar inserción: " . $db->error);
        }
        
        // Construir descripción con los títulos
        $descripcion = "Título principal: $titulo_principal";
        if (!empty($titulo_opcional)) {
            $descripcion .= "\nTítulo opcional: $titulo_opcional";
        }
        
        // Si no se proporcionó una fecha de vigencia, usar la fecha actual
        if (empty($vigencia_fecha)) {
            $vigencia_fecha = date('Y-m-d H:i:s');
        } else {
            // Normalizar formato a YYYY-MM-DD HH:MM:SS si solo se envió YYYY-MM-DD
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $vigencia_fecha)) {
                $vigencia_fecha = $vigencia_fecha . ' 00:00:00';
            }
        }

        $insertStmt->bind_param(
            "sssissss", 
            $nombre, 
            $codigo, 
            $tipo_formacion,
            $duracion_semestres,
            $titulo_principal,  // Solo el título principal
            $titulo_opcional,   // Título opcional por separado
            $descripcion,
            $vigencia_fecha
        );
        
        if (!$insertStmt->execute()) {
            throw new Exception("Error al insertar carrera: " . $insertStmt->error);
        }
        
        $insertId = $db->insert_id;
        $insertStmt->close();
        
        $db->commit();
        
        // REGISTRAR EN AUDITORÍA - NUEVA CARRERA CREADA
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "INSERT", 
                    "carreras", 
                    $insertId, 
                    null, 
                    [
                        'nombre_carrera' => $nombre,
                        'cod_carrera' => $codigo,
                        'tipo_formacion' => $tipo_formacion,
                        'duracion_semestres' => $duracion_semestres,
                        'duracion_anios' => $duracion_anios,
                        'titulo_principal' => $titulo_principal,
                        'titulo_opcional' => $titulo_opcional,
                        'vigencia_fecha' => $vigencia_fecha,
                        'activa' => 1
                    ], 
                    "Carreras", 
                    "Nueva carrera registrada"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría registrarNuevaCarrera: " . $e->getMessage());
            }
        }
        
        return [
            'success' => true,
            'message' => 'Carrera registrada exitosamente',
            'id_carrera' => $insertId
        ];
        
    } catch (Exception $e) {
        if (isset($db) && method_exists($db, 'rollback')) {
            $db->rollback();
        }
        error_log("Error en registrarNuevaCarrera: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL REGISTRAR CARRERA
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "carreras", 
                    null, 
                    null, 
                    [
                        'nombre_carrera' => $nombre,
                        'cod_carrera' => $codigo,
                        'error' => $e->getMessage()
                    ], 
                    "Carreras", 
                    "Error al registrar nueva carrera"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error registrarNuevaCarrera: " . $auditError->getMessage());
            }
        }
        
        return [
            'success' => false,
            'message' => 'Error al registrar carrera: ' . $e->getMessage()
        ];
    }
}

// Función para obtener una carrera específica por ID
function obtenerCarreraPorId($id) {
    global $db;
    
    // Validación adicional por si acaso
    if (!is_numeric($id) || $id <= 0) {
        error_log("ID inválido pasado a obtenerCarreraPorId: " . $id);
        return false;
    }
    
    $query = "SELECT id_carrera, nombre_carrera, cod_carrera, activa, 
                     duracion_semestres, titulo_otorga, otro_titulo, descripcion, tipo_formacion 
              FROM carreras 
              WHERE id_carrera = ? 
              LIMIT 1";
    
    try {
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error al obtener resultados: " . $stmt->error);
        }
        
        $data = $result->fetch_assoc();
        $stmt->close();
        
        return $data;
    } catch (Exception $e) {
        error_log("Error en obtenerCarreraPorId: " . $e->getMessage());
        return false;
    }
}

function actualizarCarrera(
    int $id,
    string $nombre,
    string $codigo,
    string $tipo_formacion,
    int $duracion_semestres,
    string $titulo_principal,
    string $titulo_opcional = '',
    string $descripcion = '',
    int $activa = 1
): array {
    global $db;
    
    try {
        // Obtener datos actuales para auditoría
        $datos_actuales = obtenerCarreraPorId($id);
        if (!$datos_actuales) {
            return [
                'success' => false,
                'message' => 'Carrera no encontrada'
            ];
        }

        // Validar título principal
        if (empty($titulo_principal)) {
            return [
                'success' => false,
                'message' => 'El título principal es obligatorio'
            ];
        }

        // Verificar duplicados (excluyendo el registro actual)
        $db->begin_transaction();
        
        $checkStmt = $db->prepare("SELECT id_carrera FROM carreras WHERE cod_carrera = ? AND id_carrera != ? FOR UPDATE");
        if (!$checkStmt) {
            throw new Exception("Error al preparar consulta de verificación: " . $db->error);
        }
        
        $checkStmt->bind_param("si", $codigo, $id);
        if (!$checkStmt->execute()) {
            throw new Exception("Error al verificar código: " . $checkStmt->error);
        }
        
        $checkStmt->store_result();
        
        if ($checkStmt->num_rows > 0) {
            $checkStmt->close();
            $db->rollback();
            return [
                'success' => false,
                'message' => 'El código de carrera ya está en uso por otro programa'
            ];
        }
        $checkStmt->close();
        
        // Actualizar carrera
        $updateStmt = $db->prepare("UPDATE carreras SET 
            nombre_carrera = ?,
            cod_carrera = ?,
            tipo_formacion = ?,
            duracion_semestres = ?,
            titulo_otorga = ?,
            otro_titulo = ?,
            descripcion = ?,
            activa = ?
            WHERE id_carrera = ?");
        
        if (!$updateStmt) {
            throw new Exception("Error al preparar actualización: " . $db->error);
        }
        
        // Construir descripción actualizada
        $descripcion_actualizada = "Título principal: $titulo_principal";
        if (!empty($titulo_opcional)) {
            $descripcion_actualizada .= "\nTítulo opcional: $titulo_opcional";
        }
        
        $updateStmt->bind_param(
            "sssissiii",
            $nombre,
            $codigo,
            $tipo_formacion,
            $duracion_semestres,
            $titulo_principal,  // Solo el título principal
            $titulo_opcional,   // Título opcional por separado
            $descripcion_actualizada,
            $activa,
            $id
        );
        
        if (!$updateStmt->execute()) {
            throw new Exception("Error al actualizar carrera: " . $updateStmt->error);
        }
        
        $affected_rows = $updateStmt->affected_rows;
        $updateStmt->close();
        $db->commit();
        
        // REGISTRAR EN AUDITORÍA - ACTUALIZACIÓN DE CARRERA
        if ($affected_rows > 0 && function_exists('registrarAuditoria')) {
            try {
                $valores_antiguos_audit = [];
                $valores_nuevos_audit = [];
                
                // Comparar campos modificados
                $campos_auditar = [
                    'nombre_carrera', 'cod_carrera', 'tipo_formacion', 'duracion_semestres',
                    'titulo_otorga', 'otro_titulo', 'activa'
                ];
                
                foreach ($campos_auditar as $campo) {
                    $valor_antiguo = $datos_actuales[$campo] ?? null;
                    $valor_nuevo = $$campo ?? null;
                    
                    // Para campos específicos
                    if ($campo === 'titulo_otorga') {
                        $valor_nuevo = $titulo_principal;
                    } elseif ($campo === 'otro_titulo') {
                        $valor_nuevo = $titulo_opcional;
                    }
                    
                    if ($valor_antiguo != $valor_nuevo) {
                        $valores_antiguos_audit[$campo] = $valor_antiguo;
                        $valores_nuevos_audit[$campo] = $valor_nuevo;
                    }
                }
                
                if (!empty($valores_nuevos_audit)) {
                    registrarAuditoria(
                        "UPDATE", 
                        "carreras", 
                        $id, 
                        $valores_antiguos_audit, 
                        $valores_nuevos_audit, 
                        "Carreras", 
                        "Actualización de datos de carrera"
                    );
                }
            } catch (Exception $e) {
                error_log("Error en auditoría actualizarCarrera: " . $e->getMessage());
            }
        }
        
        return [
            'success' => true,
            'message' => 'Programa académico actualizado exitosamente',
            'affected_rows' => $affected_rows
        ];
        
    } catch (Exception $e) {
        if (isset($db) && method_exists($db, 'rollback')) {
            $db->rollback();
        }
        error_log("Error en actualizarCarrera: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL ACTUALIZAR CARRERA
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "carreras", 
                    $id, 
                    null, 
                    [
                        'nombre_carrera' => $nombre,
                        'cod_carrera' => $codigo,
                        'error' => $e->getMessage()
                    ], 
                    "Carreras", 
                    "Error al actualizar carrera"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error actualizarCarrera: " . $auditError->getMessage());
            }
        }
        
        return [
            'success' => false,
            'message' => 'Error al actualizar programa: ' . $e->getMessage()
        ];
    }
}

/**
 * Función para eliminar carrera (eliminación lógica)
 */
function eliminarCarrera($id_carrera): array {
    global $db;
    
    try {
        // Obtener información de la carrera antes de eliminar
        $carrera_info = obtenerCarreraPorId($id_carrera);
        if (!$carrera_info) {
            return [
                'success' => false,
                'message' => 'Carrera no encontrada'
            ];
        }
        
        // Verificar si hay estudiantes asociados a esta carrera
        $check_estudiantes = $db->prepare("SELECT COUNT(*) as total FROM users WHERE carrera = ? AND estudiante = 1 AND status = 1");
        if ($check_estudiantes) {
            $check_estudiantes->bind_param("i", $id_carrera);
            $check_estudiantes->execute();
            $result = $check_estudiantes->get_result();
            $estudiantes_count = $result->fetch_assoc()['total'];
            $check_estudiantes->close();
            
            if ($estudiantes_count > 0) {
                return [
                    'success' => false,
                    'message' => "No se puede eliminar la carrera porque tiene $estudiantes_count estudiante(s) asociado(s)"
                ];
            }
        }
        
        // Realizar eliminación lógica (desactivar)
        $query = "UPDATE carreras SET activa = 0 WHERE id_carrera = ?";
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("i", $id_carrera);
        $resultado = $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        
        if ($resultado && $affected_rows > 0) {
            // REGISTRAR EN AUDITORÍA - ELIMINACIÓN DE CARRERA
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "UPDATE", 
                        "carreras", 
                        $id_carrera, 
                        [
                            'activa' => $carrera_info['activa'],
                            'nombre_carrera' => $carrera_info['nombre_carrera'],
                            'cod_carrera' => $carrera_info['cod_carrera']
                        ], 
                        [
                            'activa' => 0,
                            'estado' => 'Eliminada (desactivada)'
                        ], 
                        "Carreras", 
                        "Eliminación lógica de carrera"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría eliminarCarrera: " . $e->getMessage());
                }
            }
            
            return [
                'success' => true,
                'message' => 'Carrera eliminada exitosamente',
                'affected_rows' => $affected_rows
            ];
        } else {
            return [
                'success' => false,
                'message' => 'No se realizaron cambios en la carrera'
            ];
        }
        
    } catch (Exception $e) {
        error_log("Error en eliminarCarrera: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL ELIMINAR CARRERA
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "carreras", 
                    $id_carrera, 
                    null, 
                    [
                        'id_carrera' => $id_carrera,
                        'error' => $e->getMessage()
                    ], 
                    "Carreras", 
                    "Error al eliminar carrera"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error eliminarCarrera: " . $auditError->getMessage());
            }
        }
        
        return [
            'success' => false,
            'message' => 'Error al eliminar carrera: ' . $e->getMessage()
        ];
    }
}



//DOCENTES


function insertarDocente(array $datos): array {
    global $db;
    
    try {
        // Validar campos requeridos
        $camposRequeridos = ['nombre', 'tipo_documento', 'documento', 'email', 'telefono', 
                          'direccion', 'estado_residencia', 'municipio', 'genero', 
                          'estado_civil', 'fecha_nacimiento', 'estado_laboral'];
        
        $faltantes = array_diff($camposRequeridos, array_keys($datos));
        if (!empty($faltantes)) {
            throw new Exception("Faltan campos requeridos: " . implode(', ', $faltantes));
        }

        // Obtener el texto del tipo de documento
        $stmtTipo = $db->prepare("SELECT tipo FROM tipo_cedula WHERE id = ?");
        $stmtTipo->bind_param("i", $datos['tipo_documento']);
        $stmtTipo->execute();
        $stmtTipo->bind_result($tipo_documento_texto);
        $stmtTipo->fetch();
        $stmtTipo->close();

        // Concatenar tipo y documento SIN guión
        $idusuario = $tipo_documento_texto . $datos['documento'];
        $cedulaLimpia = $datos['documento']; // Usar solo el número de documento

        // Verificar si existe la carrera especificada o usar "No Especificado" (ID 0)
        if (isset($datos['carrera']) && $datos['carrera'] !== '') {
            $stmt = $db->prepare("SELECT id_carrera FROM carreras WHERE id_carrera = ?");
            $stmt->bind_param("i", $datos['carrera']);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows === 0) {
                $stmt->close();
                throw new Exception("La carrera especificada no existe");
            }
            $stmt->close();
        } else {
            // Si no se especifica carrera, usar ID 0 ("No Especificado")
            $datos['carrera'] = 0;
        }

        // 1. Preparación de datos
        $username = strtolower(str_replace(' ', '.', $datos['nombre']));
        $password = password_hash($cedulaLimpia, PASSWORD_DEFAULT); // Usar cedulaLimpia
        $fecha_act = date('Y-m-d H:i:s');
        $api_key = bin2hex(random_bytes(16));

        // 2. Conversión de arrays a strings
        $potencialidades = isset($datos['potencialidades']) ? 
                         (is_array($datos['potencialidades']) ? 
                          implode(', ', array_filter($datos['potencialidades'])) : 
                          $datos['potencialidades']) : '';
        
        // Iniciar transacción
        $db->begin_transaction();

        // Verificar si el usuario ya existe
        $checkStmt = $db->prepare("SELECT id FROM users WHERE idusuario = ? LIMIT 1");
        $checkStmt->bind_param("s", $idusuario);
        $checkStmt->execute();
        $checkStmt->store_result();
        
        if ($checkStmt->num_rows > 0) {
            $checkStmt->close();
            $db->rollback();
            return [
                'success' => false,
                'message' => 'El docente ya está registrado'
            ];
        }
        $checkStmt->close();

        // 3. Configuración de roles y valores por defecto
        $config = [
            'roles' => [
                'usuario' => 0,
                'estudiante' => 0,
                'docente' => 1,
                'admin' => 0,
                'super_user' => 0,
                'editar_user' => 0,
                'editar_nota' => 0,
                'editar_acceso' => 0,
                'editar_valores' => 0,
                'editar_estudiante' => 0,
                'agregar_estudiante' => 0,
                'agregar_docente' => 0,
                'editar_docente' => 0,
                'editar_materia' => 0,
                'agregar_materia' => 0,
                'agregar_carrera' => 0
            ],
            'defaults' => [
                'cel' => $datos['celular'] ?? '',
                'ciudad' => $datos['municipio'] ?? '',
                'num_telf_opc' => $datos['telefono_secundario'] ?? '',
                'etnia' => $datos['etnia'] ?? '',
                'casaapto' => $datos['casa_apto'] ?? 'No especificado',
                'punto_referencia' => $datos['punto_referencia'] ?? '',
                'grupo_familiar' => $datos['grupo_familiar'] ?? 0,
                'acargo_usted' => $datos['acargo_usted'] ?? 0,
                'fuente_ingresos' => $datos['fuente_ingresos'] ?? '',
                'tipo_vivienda' => $datos['tipo_vivienda'] ?? '',
                'tenencia_vivienda' => $datos['tenencia_vivienda'] ?? '',
                'enfermedad' => $datos['enfermedad'] ?? '',
                'discapacidad' => $datos['discapacidad'] ?? '',
                'titulos' => '',
                'institutos' => '',
                'potencialidades' => $potencialidades,
                'api_key' => $api_key,
                'fecha_ingreso' => $datos['fecha_ingreso'] ?? date('Y-m-d'),
                'carrera' => $datos['carrera']
            ]
        ];

        // 4. Combinar todos los valores
        $valores = array_merge(
            [
                'idusuario' => $idusuario,
                'nombre' => $datos['nombre'],
                'username' => $username,
                'email' => $datos['email'],
                'tlf' => $datos['telefono'],
                'direccion' => $datos['direccion'],
                'estado' => $datos['estado_residencia'],
                'municipio' => $datos['municipio'],
                'parroquia' => $datos['parroquia'] ?? '',
                'status' => ($datos['estado_laboral'] == 'Activo') ? 1 : 0,
                'user_type' => 'docente',
                'password' => $password,
                'genero' => $datos['genero'],
                'edo_civil' => $datos['estado_civil'],
                'fecha_nac' => $datos['fecha_nacimiento'],
                'fecha_act' => $fecha_act,
                'potencialidades' => $potencialidades
            ],
            $config['defaults'],
            $config['roles']
        );

        // 5. Construir e ejecutar consulta de inserción
        $fields = array_keys($valores);
        $placeholders = implode(', ', array_fill(0, count($valores), '?'));
        $types = '';
        foreach ($valores as $valor) {
            if (is_int($valor)) {
                $types .= 'i';
            } elseif (is_double($valor)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        
        $sql = "INSERT INTO users (" . implode(', ', $fields) . ") VALUES ($placeholders)";
        $stmt = $db->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }

        $params = array_values($valores);
        $stmt->bind_param($types, ...$params);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar: " . $stmt->error);
        }

        $idInsertado = $stmt->insert_id;
        $stmt->close();
        
        // 6. Insertar títulos obtenidos si existen
        if ((!empty($datos['titulos_main']) && !empty($datos['institutos_main'])) || 
            (!empty($datos['titulos']) && !empty($datos['institutos']))) {
            
            $sqlTitulos = "INSERT INTO titulos_obtenidos (id_usuario, nombre, titulo_obtenido, instituto) VALUES (?, ?, ?, ?)";
            $stmtTitulos = $db->prepare($sqlTitulos);
            
            if (!$stmtTitulos) {
                throw new Exception("Error al preparar consulta de títulos: " . $db->error);
            }
            
            // Insertar título principal si existe
            if (!empty($datos['titulos_main']) && !empty($datos['institutos_main'])) {
                $stmtTitulos->bind_param(
                    "isss", 
                    $idInsertado,
                    $datos['nombre'],
                    $datos['titulos_main'],
                    $datos['institutos_main']
                );
                if (!$stmtTitulos->execute()) {
                    throw new Exception("Error al insertar título principal: " . $stmtTitulos->error);
                }
            }
            
            // Insertar títulos adicionales si existen
            if (!empty($datos['titulos']) && !empty($datos['institutos'])) {
                $titulos = is_array($datos['titulos']) ? $datos['titulos'] : [$datos['titulos']];
                $institutos = is_array($datos['institutos']) ? $datos['institutos'] : [$datos['institutos']];
                
                $count = min(count($titulos), count($institutos));
                
                for ($i = 0; $i < $count; $i++) {
                    $stmtTitulos->bind_param(
                        "isss", 
                        $idInsertado,
                        $datos['nombre'],
                        $titulos[$i],
                        $institutos[$i]
                    );
                    if (!$stmtTitulos->execute()) {
                        throw new Exception("Error al insertar título adicional: " . $stmtTitulos->error);
                    }
                }
            }
            
            $stmtTitulos->close();
        }

        // 7. REGISTRAR EN AUDITORÍA - NUEVO DOCENTE
        if (function_exists('registrarAuditoria')) {
            try {
                $valores_nuevos = [
                    'idusuario' => $idusuario,
                    'nombre' => $datos['nombre'],
                    'email' => $datos['email'],
                    'carrera' => $datos['carrera'] ?? 0,
                    'estado_laboral' => $datos['estado_laboral'],
                    'tipo_documento' => $tipo_documento_texto,
                    'documento' => $datos['documento'],
                    'telefono' => $datos['telefono'],
                    'genero' => $datos['genero'],
                    'estado_civil' => $datos['estado_civil']
                ];
                
                registrarAuditoria(
                    "INSERT", 
                    "users", 
                    $idInsertado, 
                    null, 
                    $valores_nuevos, 
                    "Docentes", 
                    "Registro de nuevo docente"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría insertarDocente: " . $e->getMessage());
            }
        }

        // Confirmar transacción
        $db->commit();

        return [
            'success' => true,
            'message' => 'Docente registrado exitosamente',
            'id' => $idInsertado,
            'username' => $username,
            'password_temp' => $cedulaLimpia // Usar cedulaLimpia en lugar del documento completo
        ];

    } catch(Exception $e) {
        // Revertir transacción en caso de error
        if (isset($db) && method_exists($db, 'rollback')) {
            $db->rollback();
        }
        
        // REGISTRAR EN AUDITORÍA - ERROR AL REGISTRAR DOCENTE
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "users", 
                    null, 
                    null, 
                    [
                        'nombre' => $datos['nombre'] ?? '',
                        'idusuario' => $idusuario ?? '',
                        'error' => $e->getMessage()
                    ], 
                    "Docentes", 
                    "Error al registrar docente"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error insertarDocente: " . $auditError->getMessage());
            }
        }
        
        error_log("Error en insertarDocente: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Error al registrar docente: ' . $e->getMessage()
        ];
    }
}

function validarDocente($datos) {
    $errores = [];
    
    // Validar email
    if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Por favor ingrese un correo electrónico válido';
    }
    
    // Validar teléfono (al menos 10 dígitos)
    if (strlen($datos['telefono']) < 10) {
        $errores[] = 'El teléfono debe tener al menos 10 dígitos';
    }
    
    // Validar documento
    if (empty($datos['documento']) || !is_numeric($datos['documento'])) {
        $errores[] = 'El documento debe ser un número válido';
    }
    
    // Validar campos requeridos
    $camposRequeridos = [
        'tipo_documento', 'documento', 'nombre', 'potencialidades', 'genero', 
        'estado_civil', 'estado_residencia', 'municipio', 'direccion',
        'fecha_nacimiento', 'telefono', 'email', 'fecha_contratacion'
    ];
    
    foreach ($camposRequeridos as $campo) {
        if (empty($datos[$campo])) {
            $nombreCampo = str_replace('_', ' ', $campo);
            $errores[] = "El campo $nombreCampo es requerido";
        }
    }
    
    return empty($errores) ? true : $errores;
}

/**
 * Obtiene los datos de un docente según su ID.
 *
 * @param int $id ID del docente a buscar
 * @return array|null Array asociativo con los datos del docente, o null si no se encuentra
 */
function obtenerDocentePorId($id) {
    // Accede a la conexión de la base de datos definida globalmente
    global $db;
    
    try {
        // Consulta SQL con un parámetro placeholder (?) para prevenir inyecciones SQL
        $query = "SELECT * FROM users WHERE id = ? AND docente = 1";
        
        // Prepara la sentencia SQL
        if ($stmt = $db->prepare($query)) {
            // Asocia el parámetro $id al placeholder, indicando que es un entero ("i")
            $stmt->bind_param("i", $id);
            
            // Ejecuta la consulta preparada
            $stmt->execute();
            
            // Obtiene el resultado de la consulta
            $result = $stmt->get_result();
            
            // Verifica si se encontraron registros
            if ($result->num_rows > 0) {
                // Retorna los datos del docente como un array asociativo
                return $result->fetch_assoc();
            }
            
            // Cierra el statement para liberar recursos
            $stmt->close();
        }
        
        // Retorna null si no se encuentra el docente o hay un error
        return null;
        
    } catch (Exception $e) {
        error_log("Error en obtenerDocentePorId: " . $e->getMessage());
        return null;
    }
}

/**
 * Actualiza los datos de un docente en la base de datos.
 * 
 * @param array $datos Array asociativo con los datos del docente a actualizar
 * @return array Array con:
 *               - 'success': boolean que indica si la operación fue exitosa
 *               - 'message': string con mensaje descriptivo del resultado
 */
function actualizarDocente($datos) {
    // Accede a la conexión de la base de datos definida globalmente
    global $db;
    
    try {
        // Obtener datos actuales del docente para auditoría
        $docente_id = isset($datos['id']) ? intval($datos['id']) : 0;
        $datos_antiguos = null;
        
        if (function_exists('obtenerDocentePorId')) {
            $datos_antiguos = obtenerDocentePorId($docente_id);
        }
        
        // Consulta SQL con placeholders (?) para todos los valores a actualizar
        $sql = "UPDATE users SET 
                nombre = ?,
                email = ?,
                tlf = ?,
                cel = ?,
                direccion = ?,
                estado = ?,
                municipio = ?,
                parroquia = ?,
                status = ?,
                carrera = ?,
                genero = ?,
                edo_civil = ?,
                fecha_nac = ?,
                num_telf_opc = ?,
                titulos = ?,
                institutos = ?,
                fecha_ingreso = ?
                WHERE id = ? AND docente = 1";  // Solo actualiza si es docente

        // Prepara la sentencia SQL
        $stmt = $db->prepare($sql);
        
        // Verifica si la preparación fue exitosa
        if (!$stmt) {
            throw new Exception("Error en la preparación: " . $db->error);
        }
        
        // Preparar valores y prevenir notices si faltan claves
        $nombre = $datos['nombre'] ?? '';
        $email = $datos['email'] ?? '';
        $tlf = $datos['tlf'] ?? '';
        $cel = $datos['cel'] ?? '';
        $direccion = $datos['direccion'] ?? '';
        $estado = $datos['estado'] ?? '';
        $municipio = $datos['municipio'] ?? '';
        $parroquia = $datos['parroquia'] ?? '';
        $status = $datos['status'] ?? '';
        $carrera = $datos['carrera'] ?? '';
        $genero = $datos['genero'] ?? '';
        $edo_civil = $datos['edo_civil'] ?? '';
        $fecha_nac = $datos['fecha_nac'] ?? null;
        $num_telf_opc = $datos['num_telf_opc'] ?? '';
        $titulos = $datos['titulos'] ?? '';
        $institutos = $datos['institutos'] ?? '';
        $fecha_ingreso = $datos['fecha_ingreso'] ?? null;
        
        // Si no se proporcionó fecha_ingreso, intentar usar el valor actual en la BD
        if (empty($fecha_ingreso)) {
            try {
                $qry = $db->prepare("SELECT fecha_ingreso FROM users WHERE id = ? AND docente = 1");
                if ($qry) {
                    $qry->bind_param('i', $docente_id);
                    if ($qry->execute()) {
                        $res = $qry->get_result();
                        $row = $res ? $res->fetch_assoc() : null;
                        if (!empty($row['fecha_ingreso'])) {
                            $fecha_ingreso = $row['fecha_ingreso'];
                        } else {
                            // Fallback: fecha actual en formato YYYY-MM-DD para evitar NULL
                            $fecha_ingreso = date('Y-m-d');
                        }
                    } else {
                        $fecha_ingreso = date('Y-m-d');
                    }
                    $qry->close();
                } else {
                    $fecha_ingreso = date('Y-m-d');
                }
            } catch (Exception $e) {
                $fecha_ingreso = date('Y-m-d');
                error_log("Error al obtener fecha_ingreso: " . $e->getMessage());
            }
        }

        // Vincula los parámetros a la sentencia preparada
        // s = string, i = integer
        // 17 strings (s) y 1 integer (i) al final => 18 parámetros
        $bindTypes = "sssssssssssssssssi"; // 17 s + i

        if (!$stmt->bind_param(
            $bindTypes,
            $nombre,
            $email,
            $tlf,
            $cel,
            $direccion,
            $estado,
            $municipio,
            $parroquia,
            $status,
            $carrera,
            $genero,
            $edo_civil,
            $fecha_nac,
            $num_telf_opc,
            $titulos,
            $institutos,
            $fecha_ingreso,
            $docente_id
        )) {
            throw new Exception("Error en bind_param: " . $stmt->error);
        }

        // Ejecuta la sentencia preparada
        if (!$stmt->execute()) {
            // Si falla la ejecución, lanzar excepción para que el catch la capture y retorne JSON limpio
            throw new Exception("Error en execute: " . $stmt->error);
        }
        
        $affected_rows = $stmt->affected_rows;
        $success = $affected_rows > 0;
        
        // REGISTRAR EN AUDITORÍA - ACTUALIZACIÓN DE DOCENTE
        if ($success && function_exists('registrarAuditoria')) {
            try {
                // Preparar datos para auditoría
                $valores_antiguos_audit = [];
                $valores_nuevos_audit = [];
                
                if ($datos_antiguos) {
                    // Campos principales para auditoría
                    $campos_auditar = [
                        'nombre', 'email', 'tlf', 'cel', 'direccion', 'estado', 
                        'municipio', 'parroquia', 'status', 'carrera', 'genero',
                        'edo_civil', 'fecha_nac', 'num_telf_opc', 'titulos', 
                        'institutos', 'fecha_ingreso'
                    ];
                    
                    foreach ($campos_auditar as $campo) {
                        $valor_antiguo = $datos_antiguos[$campo] ?? null;
                        $valor_nuevo = $datos[$campo] ?? null;
                        
                        // Solo registrar si hay cambio
                        if ($valor_antiguo != $valor_nuevo) {
                            $valores_antiguos_audit[$campo] = $valor_antiguo;
                            $valores_nuevos_audit[$campo] = $valor_nuevo;
                        }
                    }
                } else {
                    // Si no se pudieron obtener datos antiguos, registrar todos los nuevos
                    $valores_nuevos_audit = [
                        'nombre' => $nombre,
                        'email' => $email,
                        'status' => $status,
                        'carrera' => $carrera
                    ];
                }
                
                registrarAuditoria(
                    "UPDATE", 
                    "users", 
                    $docente_id, 
                    $valores_antiguos_audit, 
                    $valores_nuevos_audit, 
                    "Docentes", 
                    "Actualización de datos de docente"
                );
                
            } catch (Exception $e) {
                error_log("Error en auditoría actualizarDocente: " . $e->getMessage());
            }
        }
        
        // Retorna un array con el resultado de la operación
        return [
            'success' => $success,  // True si se actualizó alguna fila
            'message' => $success 
                ? 'Docente actualizado correctamente' 
                : 'No se realizaron cambios',  // Puede ocurrir si los datos son iguales
            'affected_rows' => $affected_rows
        ];
        
    } catch(Exception $e) {
        // REGISTRAR EN AUDITORÍA - ERROR AL ACTUALIZAR DOCENTE
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "users", 
                    isset($datos['id']) ? intval($datos['id']) : null, 
                    null, 
                    [
                        'nombre' => $datos['nombre'] ?? '',
                        'error' => $e->getMessage()
                    ], 
                    "Docentes", 
                    "Error al actualizar docente"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error actualizarDocente: " . $auditError->getMessage());
            }
        }
        
        error_log("Error en actualizarDocente: " . $e->getMessage());
        
        // Manejo de errores: retorna información sobre el error
        return [
            'success' => false,
            'message' => 'Error al actualizar: ' . $e->getMessage()
        ];
    } finally {
        // Asegura que el statement se cierre si existe
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}

function obtenerDocentes() {
    global $db;
    
    try {
        $docentes = [];
        
        // Preparamos la consulta
        $stmt = $db->prepare("SELECT id, idusuario, nombre, email, tlf, status 
                             FROM users 
                             WHERE docente = 1 
                             ORDER BY nombre ASC");
        
        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $db->error);
        }
        
        // Ejecutamos la consulta
        if (!$stmt->execute()) {
            throw new Exception('Error al ejecutar la consulta: ' . $stmt->error);
        }
        
        // Obtenemos el resultado
        $result = $stmt->get_result();
        
        // Obtenemos todos los registros como array asociativo
        while ($row = $result->fetch_assoc()) {
            $docentes[] = $row;
        }
        
        // Cerramos el statement
        $stmt->close();
        
        return $docentes;
        
    } catch (Exception $e) {
        error_log("Error en obtenerDocentes: " . $e->getMessage());
        return [];
    }
}

/**
 * Obtiene todos los registros de docentes de la base de datos.
 * 
 * Utiliza sentencias preparadas para mayor seguridad.
 * 
 * @return array Array asociativo con todos los docentes encontrados, ordenados por nombre
 */
function getDocentes() {
    // Accede a la conexión de la base de datos definida globalmente
    global $db;
    
    try {
        // Inicializa el array que contendrá los resultados
        $docentes = array();
        
        // Consulta SQL con parámetro para docente (aunque sea fijo, lo preparamos igual)
        $sql = "SELECT * FROM users WHERE docente = ? ORDER BY nombre ASC";
        
        // Prepara la sentencia SQL
        if ($stmt = $db->prepare($sql)) {
            // Valor fijo para docente (1 = true)
            $docente = 1;
            
            // Vincula el parámetro (aunque sea fijo, se trata como parámetro)
            $stmt->bind_param("i", $docente);
            
            // Ejecuta la consulta
            $stmt->execute();
            
            // Obtiene el resultado
            $result = $stmt->get_result();
            
            // Recorre los resultados y los agrega al array
            while ($row = $result->fetch_assoc()) {
                $docentes[] = $row;
            }
            
            // Cierra el statement y libera memoria
            $stmt->close();
            
            // Libera el resultado (no es estrictamente necesario ya que close() lo hace)
            if (isset($result)) {
                $result->free();
            }
        }
        
        // Retorna el array de docentes (vacío si no hay resultados)
        return $docentes;
        
    } catch (Exception $e) {
        error_log("Error en getDocentes: " . $e->getMessage());
        return [];
    }
}

/**
 * Cambia el estado de un docente (activo/inactivo)
 * 
 * @param int $docente_id ID del docente
 * @param int $nuevo_estado Nuevo estado (1 para activo, 0 para inactivo)
 * @return array Resultado de la operación
 */
function cambiarEstadoDocente($docente_id, $nuevo_estado) {
    global $db;
    
    try {
        // Obtener información actual del docente para auditoría
        $docente_actual = obtenerDocentePorId($docente_id);
        if (!$docente_actual) {
            return [
                'success' => false,
                'message' => 'Docente no encontrado'
            ];
        }

        $stmt = $db->prepare("UPDATE users SET status = ? WHERE id = ? AND docente = 1");
        
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("ii", $nuevo_estado, $docente_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        
        // REGISTRAR EN AUDITORÍA - CAMBIO DE ESTADO DE DOCENTE
        if ($affected_rows > 0 && function_exists('registrarAuditoria')) {
            try {
                $estado_texto_anterior = $docente_actual['status'] ? 'Activo' : 'Inactivo';
                $estado_texto_nuevo = $nuevo_estado ? 'Activo' : 'Inactivo';
                
                registrarAuditoria(
                    "UPDATE", 
                    "users", 
                    $docente_id, 
                    [
                        'status' => $docente_actual['status'],
                        'estado_anterior' => $estado_texto_anterior
                    ], 
                    [
                        'status' => $nuevo_estado,
                        'estado_nuevo' => $estado_texto_nuevo,
                        'nombre_docente' => $docente_actual['nombre'],
                        'idusuario' => $docente_actual['idusuario']
                    ], 
                    "Docentes", 
                    "Cambio de estado de docente"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría cambiarEstadoDocente: " . $e->getMessage());
            }
        }
        
        return [
            'success' => true,
            'message' => 'Estado del docente actualizado exitosamente',
            'affected_rows' => $affected_rows
        ];
        
    } catch (Exception $e) {
        error_log("Error en cambiarEstadoDocente: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL CAMBIAR ESTADO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "users", 
                    $docente_id, 
                    null, 
                    [
                        'nuevo_estado' => $nuevo_estado,
                        'error' => $e->getMessage()
                    ], 
                    "Docentes", 
                    "Error al cambiar estado de docente"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error cambiarEstadoDocente: " . $auditError->getMessage());
            }
        }
        
        return [
            'success' => false,
            'message' => 'Error al cambiar estado del docente: ' . $e->getMessage()
        ];
    }
}

/**
 * Elimina un docente (eliminación lógica)
 * 
 * @param int $docente_id ID del docente a eliminar
 * @return array Resultado de la operación
 */
function eliminarDocente($docente_id) {
    global $db;
    
    try {
        // Obtener información del docente antes de eliminar
        $docente_info = obtenerDocentePorId($docente_id);
        if (!$docente_info) {
            return [
                'success' => false,
                'message' => 'Docente no encontrado'
            ];
        }

        // Verificar si el docente tiene asignaciones activas
        $check_asignaciones = $db->prepare("SELECT COUNT(*) as total FROM docente_seccion WHERE id_usuario = ? AND estatus = 1");
        if ($check_asignaciones) {
            $check_asignaciones->bind_param("i", $docente_id);
            $check_asignaciones->execute();
            $result = $check_asignaciones->get_result();
            $asignaciones_count = $result->fetch_assoc()['total'];
            $check_asignaciones->close();
            
            if ($asignaciones_count > 0) {
                return [
                    'success' => false,
                    'message' => "No se puede eliminar el docente porque tiene $asignaciones_count asignación(es) activa(s)"
                ];
            }
        }

        // Realizar eliminación lógica (desactivar)
        $query = "UPDATE users SET status = 0, docente = 0 WHERE id = ?";
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("i", $docente_id);
        $resultado = $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        
        if ($resultado && $affected_rows > 0) {
            // REGISTRAR EN AUDITORÍA - ELIMINACIÓN DE DOCENTE
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "UPDATE", 
                        "users", 
                        $docente_id, 
                        [
                            'status' => $docente_info['status'],
                            'docente' => $docente_info['docente'],
                            'nombre_docente' => $docente_info['nombre'],
                            'idusuario' => $docente_info['idusuario']
                        ], 
                        [
                            'status' => 0,
                            'docente' => 0,
                            'estado' => 'Eliminado (desactivado)'
                        ], 
                        "Docentes", 
                        "Eliminación lógica de docente"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría eliminarDocente: " . $e->getMessage());
                }
            }
            
            return [
                'success' => true,
                'message' => 'Docente eliminado exitosamente',
                'affected_rows' => $affected_rows
            ];
        } else {
            return [
                'success' => false,
                'message' => 'No se realizaron cambios en el docente'
            ];
        }
        
    } catch (Exception $e) {
        error_log("Error en eliminarDocente: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL ELIMINAR DOCENTE
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "users", 
                    $docente_id, 
                    null, 
                    [
                        'docente_id' => $docente_id,
                        'error' => $e->getMessage()
                    ], 
                    "Docentes", 
                    "Error al eliminar docente"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error eliminarDocente: " . $auditError->getMessage());
            }
        }
        
        return [
            'success' => false,
            'message' => 'Error al eliminar docente: ' . $e->getMessage()
        ];
    }
}





/**
 * Obtiene usuarios según su tipo (docente o no docente)
 * 
 * @param int $tipo 1 para docentes, 0 para no docentes
 * @return array Lista de usuarios encontrados
 */
function getUsersByType($tipo) {
  // Accedemos a la conexión MySQLi global
  global $db;
  
  // Inicializamos el array que contendrá los resultados
  $users = [];
  
  // Definimos la consulta SQL con parámetro preparado
  $query = "SELECT * FROM users WHERE docente = ?";
  
  // Preparamos la sentencia SQL
  if ($stmt = $db->prepare($query)) {
      try {
          // Vinculamos el parámetro (i = integer)
          $stmt->bind_param("i", $tipo);
          
          // Ejecutamos la consulta
          $stmt->execute();
          
          // Obtenemos el resultado
          $result = $stmt->get_result();
          
          // Recorremos los resultados y los agregamos al array
          while ($row = $result->fetch_assoc()) {
              $users[] = $row;
          }
          
          // Liberamos la memoria del resultado
          $result->free();
          
      } catch (Exception $e) {
          // Registramos el error (opcional)
          error_log("Error en getUsersByType: " . $e->getMessage());
      } finally {
          // Cerramos el statement en cualquier caso
          $stmt->close();
      }
  } else {
      // Registramos error si falla la preparación
      error_log("Error preparando consulta: " . $db->error);
  }
  
  // Retornamos el array de usuarios (vacío si no hay resultados)
  return $users;
}


/**
 * Actualiza el estado de un usuario en la base de datos
 * 
 * @param int $user_id ID del usuario a actualizar
 * @param string $new_status Nuevo estado del usuario
 * @return array Resultado de la operación con:
 *               - 'success': boolean indicando si la actualización fue exitosa
 *               - 'affected_rows': número de filas afectadas
 *               - 'message': mensaje descriptivo del resultado
 */
function updateUserStatus($user_id, $new_status) {
  global $db; // Conexión MySQLi global
  
  $query = "UPDATE users SET status = ? WHERE id = ?";
  
  // Preparamos la sentencia
  if ($stmt = $db->prepare($query)) {
      try {
          // Vinculamos parámetros (s = string, i = integer)
          $stmt->bind_param("si", $new_status, $user_id);
          
          // Ejecutamos la actualización
          $execute_result = $stmt->execute();
          
          // Obtenemos filas afectadas
          $affected_rows = $stmt->affected_rows;
          
          // Retornamos información detallada del resultado
          return [
              'success' => $execute_result,
              'affected_rows' => $affected_rows,
              'message' => $affected_rows > 0 
                  ? 'Estado actualizado correctamente' 
                  : 'No se modificó ningún registro (ID no encontrado o mismo estado)'
          ];
          
      } catch (Exception $e) {
          // Error durante la ejecución
          return [
              'success' => false,
              'affected_rows' => 0,
              'message' => 'Error al actualizar: ' . $e->getMessage()
          ];
      } finally {
          // Cerramos el statement siempre
          $stmt->close();
      }
  } else {
      // Error en preparación de la consulta
      return [
          'success' => false,
          'affected_rows' => 0,
          'message' => 'Error preparando consulta: ' . $db->error
      ];
  }
}



//CARRERA-MATERIAS**********************************************************



/**
 * Obtiene todas las carreras activas de la base de datos
 * 
 * @return array Listado de carreras activas con sus datos básicos
 *               Cada elemento contiene: id_carrera, nombre_carrera, cod_carrera
 *               Array vacío si no hay resultados o en caso de error
 */
function obtenerCarrerasActivas() {
    global $db; // Conexión MySQLi global
    
    $carreras = []; // Array para almacenar resultados
    
    // Consulta SQL con parámetro para estado activo
    $query = "SELECT id_carrera, nombre_carrera, cod_carrera, created_at 
              FROM carreras 
              WHERE activa = ? 
              ORDER BY nombre_carrera";
    
    // Preparamos la sentencia
    if ($stmt = $db->prepare($query)) {
        try {
            // Valor para carreras activas (1 = true)
            $activa = 1;
            
            // Vinculamos parámetro (i = integer)
            $stmt->bind_param("i", $activa);
            
            // Ejecutamos la consulta
            $stmt->execute();
            
            // Obtenemos resultados
            $result = $stmt->get_result();
            
            // Procesamos cada fila
            while ($row = $result->fetch_assoc()) {
                $carreras[] = $row;
            }
            
            // Liberamos memoria del resultado
            $result->free();
            
        } catch (Exception $e) {
            // Registramos error sin interrumpir el flujo
            error_log("Error en obtenerCarrerasActivas: " . $e->getMessage());
        } finally {
            // Cerramos el statement siempre
            $stmt->close();
        }
    } else {
        // Error en preparación de consulta
        error_log("Error preparando consulta: " . $db->error);
    }
    
    return $carreras;
}

/**
 * Obtiene todas las carreras (activas e inactivas)
 */
function obtenerCarrerasCompleta() {
    global $db;
    $carreras = [];
    $query = "SELECT id_carrera, nombre_carrera, cod_carrera, activa, created_at FROM carreras ORDER BY nombre_carrera";
    if ($stmt = $db->prepare($query)) {
        try {
            if (!$stmt->execute()) throw new Exception('Error al ejecutar consulta: ' . $stmt->error);
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $carreras[] = $row;
            }
            $result->free();
        } catch (Exception $e) {
            error_log('Error en obtenerTodasLasCarreras: ' . $e->getMessage());
        } finally {
            $stmt->close();
        }
    } else {
        error_log('Error preparando obtenerTodasLasCarreras: ' . $db->error);
    }

    return $carreras;
}

/**
 * Obtiene los años (distinct) en los que existe una carrera con el mismo código
 * útil para mostrar versiones históricas por año
 */
function obtenerAniosPorCodigoCarrera($cod_carrera) {
    global $db;

    $anios = [];
    // Buscar años en la tabla carreras
    $query1 = "SELECT DISTINCT YEAR(created_at) AS anio FROM carreras WHERE cod_carrera = ? AND created_at IS NOT NULL";
    if ($stmt = $db->prepare($query1)) {
        $stmt->bind_param('s', $cod_carrera);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                if (!empty($row['anio']) && !in_array($row['anio'], $anios)) $anios[] = $row['anio'];
            }
            $result->free();
        }
        $stmt->close();
    }

    // Buscar años en la tabla de versiones si existe
    $check = $db->query("SHOW TABLES LIKE 'carrera_versiones'");
    if ($check && $check->num_rows > 0) {
        $query2 = "SELECT DISTINCT YEAR(fecha_vigencia) AS anio FROM carrera_versiones v JOIN carreras c ON v.id_carrera = c.id_carrera WHERE c.cod_carrera = ? AND v.fecha_vigencia IS NOT NULL";
        if ($stmt = $db->prepare($query2)) {
            $stmt->bind_param('s', $cod_carrera);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    if (!empty($row['anio']) && !in_array($row['anio'], $anios)) $anios[] = $row['anio'];
                }
                $result->free();
            }
            $stmt->close();
        }
    }

    // Ordenar desc
    rsort($anios);

    return $anios;
}

/**
 * Obtiene versiones (id + fecha) para un código de carrera
 */
function obtenerVersionesPorCodigoCarrera($cod_carrera) {
    global $db;
    $versions = [];

    // Verificar si la tabla de versiones existe antes de consultar
    $check = $db->query("SHOW TABLES LIKE 'carrera_versiones'");
    if ($check && $check->num_rows > 0) {
        $query = "SELECT v.id_version, v.id_carrera, v.fecha_vigencia FROM carrera_versiones v JOIN carreras c ON v.id_carrera = c.id_carrera WHERE c.cod_carrera = ? ORDER BY v.fecha_vigencia DESC";
        if ($stmt = $db->prepare($query)) {
            $stmt->bind_param('s', $cod_carrera);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    $row['anio'] = !empty($row['fecha_vigencia']) ? date('Y', strtotime($row['fecha_vigencia'])) : null;
                    $versions[] = $row;
                }
                $res->free();
            }
            $stmt->close();
        }
    }

    return $versions;
}

/**
 * Obtiene versiones por id_carrera (útil cuando cod_carrera está vacío o cambió)
 */
function obtenerVersionesPorIdCarrera($id_carrera) {
    global $db;
    $versions = [];

    $check = $db->query("SHOW TABLES LIKE 'carrera_versiones'");
    if ($check && $check->num_rows > 0) {
        $query = "SELECT id_version, id_carrera, fecha_vigencia FROM carrera_versiones WHERE id_carrera = ? ORDER BY fecha_vigencia DESC";
        if ($stmt = $db->prepare($query)) {
            $stmt->bind_param('i', $id_carrera);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    $row['anio'] = !empty($row['fecha_vigencia']) ? date('Y', strtotime($row['fecha_vigencia'])) : null;
                    $versions[] = $row;
                }
                $res->free();
            }
            $stmt->close();
        }
    }

    return $versions;
}

/**
 * Asigna una materia a una versión específica de carrera
 */
function asignarMateriaAVersion($id_version, $id_materia, $semestre) {
    global $db;
    try {
        $stmt = $db->prepare("INSERT INTO version_materia (id_version, id_materia, semestre) VALUES (?, ?, ?)");
        if (!$stmt) throw new Exception('Error al preparar insert version_materia: ' . $db->error);
        $stmt->bind_param('iii', $id_version, $id_materia, $semestre);
        if (!$stmt->execute()) throw new Exception('Error al ejecutar insert version_materia: ' . $stmt->error);
        $id = $db->insert_id;
        $stmt->close();
        return ['success' => true, 'insert_id' => $id];
    } catch (Exception $e) {
        error_log('Error en asignarMateriaAVersion: ' . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

/**
 * Obtiene materias asignadas para una versión de carrera
 */
function obtenerMateriasAsignadasVersion($id_version) {
    global $db;
    $materias = [];
    $query = "SELECT vm.id, vm.id_version, vm.id_materia, vm.semestre, m.cod_materia, m.nombre_materia FROM version_materia vm JOIN materias m ON vm.id_materia = m.id_materia WHERE vm.id_version = ? ORDER BY vm.semestre";
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param('i', $id_version);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $materias[] = $row;
            }
            $res->free();
        }
        $stmt->close();
    }
    return $materias;
}

/**
 * Elimina asignación de materia en versión
 */
function eliminarAsignacionVersion($id) {
    global $db;
    try {
        $stmt = $db->prepare("DELETE FROM version_materia WHERE id = ?");
        if (!$stmt) throw new Exception('Error al preparar delete version_materia: ' . $db->error);
        $stmt->bind_param('i', $id);
        if (!$stmt->execute()) throw new Exception('Error al ejecutar delete version_materia: ' . $stmt->error);
        $affected = $stmt->affected_rows;
        $stmt->close();
        return ['success' => true, 'affected_rows' => $affected];
    } catch (Exception $e) {
        error_log('Error en eliminarAsignacionVersion: ' . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

/**
 * Duplica una carrera como nueva versión con una fecha de vigencia distinta.
 * Opcionalmente copia las asignaciones de materias (carrera_materia).
 * Devuelve array con success + message + new_id si aplica.
 */
function duplicarCarrera(int $id_carrera_original, string $nueva_fecha, bool $copiar_materias = true): array {
    global $db;

    try {
        // Obtener carrera original
        $stmt = $db->prepare("SELECT nombre_carrera, cod_carrera, activa, duracion_semestres, titulo_otorga, otro_titulo, descripcion, tipo_formacion FROM carreras WHERE id_carrera = ? LIMIT 1");
        if (!$stmt) throw new Exception('Error al preparar consulta de carrera: ' . $db->error);
        $stmt->bind_param('i', $id_carrera_original);
        $stmt->execute();
        $res = $stmt->get_result();
        if (!$res || $res->num_rows === 0) {
            $stmt->close();
            return ['success' => false, 'message' => 'Carrera original no encontrada'];
        }
        $orig = $res->fetch_assoc();
        $stmt->close();

        // Normalizar fecha
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $nueva_fecha)) {
            $nueva_fecha = $nueva_fecha . ' 00:00:00';
        }

        // Crear tablas de versiones si no existen
        $db->query("CREATE TABLE IF NOT EXISTS carrera_versiones (
            id_version INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            id_carrera INT NOT NULL,
            fecha_vigencia DATETIME NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX (id_carrera),
            FOREIGN KEY (id_carrera) REFERENCES carreras(id_carrera) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $db->query("CREATE TABLE IF NOT EXISTS version_materia (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            id_version INT NOT NULL,
            id_materia INT NOT NULL,
            semestre INT NOT NULL,
            INDEX (id_version),
            FOREIGN KEY (id_version) REFERENCES carrera_versiones(id_version) ON DELETE CASCADE,
            FOREIGN KEY (id_materia) REFERENCES materias(id_materia) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $db->begin_transaction();

        // Insertar versión
        $insert = $db->prepare("INSERT INTO carrera_versiones (id_carrera, fecha_vigencia) VALUES (?, ?)");
        if (!$insert) throw new Exception('Error al preparar inserción version: ' . $db->error);
        $insert->bind_param('is', $id_carrera_original, $nueva_fecha);
        if (!$insert->execute()) {
            $err = $insert->error;
            $insert->close();
            $db->rollback();
            throw new Exception('Error al insertar versión: ' . $err);
        }

        $new_version_id = $db->insert_id;
        $insert->close();

        // Copiar asignaciones de materias a version_materia si se solicita
        if ($copiar_materias) {
            $sel = $db->prepare("SELECT id_materia, semestre FROM carrera_materia WHERE id_carrera = ?");
            if (!$sel) throw new Exception('Error al preparar selección de materias: ' . $db->error);
            $sel->bind_param('i', $id_carrera_original);
            $sel->execute();
            $resm = $sel->get_result();

            $ins = $db->prepare("INSERT INTO version_materia (id_version, id_materia, semestre) VALUES (?, ?, ?)");
            if (!$ins) {
                $sel->close();
                throw new Exception('Error al preparar inserción de materia en version: ' . $db->error);
            }

            while ($row = $resm->fetch_assoc()) {
                $id_materia = (int)$row['id_materia'];
                $semestre = (int)$row['semestre'];
                $ins->bind_param('iii', $new_version_id, $id_materia, $semestre);
                if (!$ins->execute()) {
                    $ins->close();
                    $sel->close();
                    $db->rollback();
                    throw new Exception('Error al insertar relación materia-version: ' . $ins->error);
                }
            }

            $ins->close();
            $sel->close();
        }

        $db->commit();

        // Auditoría si aplica
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria('INSERT', 'carrera_versiones', $new_version_id, null, ['copiado_de' => $id_carrera_original, 'fecha_vigencia' => $nueva_fecha], 'Carreras', 'Creación de versión de carrera');
            } catch (Exception $e) {
                error_log('Error en auditoría duplicarCarrera: ' . $e->getMessage());
            }
        }

        return ['success' => true, 'message' => 'Versión creada correctamente', 'new_version_id' => $new_version_id];

    } catch (Exception $e) {
        if (isset($db) && method_exists($db, 'rollback')) $db->rollback();
        error_log('Error en duplicarCarrera: ' . $e->getMessage());
        return ['success' => false, 'message' => 'Error al duplicar carrera: ' . $e->getMessage()];
    }
}

/**
 * Obtiene las materias disponibles que no están asignadas a una carrera específica
 * 
 * @param int $id_carrera ID de la carrera para filtrar materias no asignadas
 * @return array Listado de materias disponibles con sus datos básicos
 *               Cada elemento contiene: id_materia, cod_materia, nombre_materia
 *               Array vacío si no hay resultados o en caso de error
 */
function obtenerMateriasDisponibles($id_carrera) {
    global $db; // Conexión MySQLi global
    
    $materias = []; // Array para almacenar resultados
    
    // Consulta SQL con parámetros preparados
    $query = "SELECT m.id_materia, m.cod_materia, m.nombre_materia 
              FROM materias m
              WHERE m.activa = 1 
              AND m.id_materia NOT IN (
                  SELECT cm.id_materia 
                  FROM carrera_materia cm 
                  WHERE cm.id_carrera = ?
              ) 
              ORDER BY m.nombre_materia";
    
    // Preparamos la sentencia
    if ($stmt = $db->prepare($query)) {
        try {
            // Vinculamos parámetro (i = integer)
            $stmt->bind_param("i", $id_carrera);
            
            // Ejecutamos la consulta
            $stmt->execute();
            
            // Obtenemos resultados
            $result = $stmt->get_result();
            
            // Procesamos cada fila
            while ($row = $result->fetch_assoc()) {
                $materias[] = $row;
            }
            
            // Liberamos memoria del resultado
            $result->free();
            
        } catch (Exception $e) {
            // Registramos error sin interrumpir el flujo
            error_log("Error en obtenerMateriasDisponibles: " . $e->getMessage());
        } finally {
            // Cerramos el statement siempre
            $stmt->close();
        }
    } else {
        // Error en preparación de consulta
        error_log("Error preparando consulta: " . $db->error);
    }
    
    return $materias;
}

/**
 * Obtiene las materias asignadas a una carrera específica con sus detalles
 * 
 * @param int $id_carrera ID de la carrera para filtrar materias asignadas
 * @return array Listado de materias asignadas con sus datos:
 *               - id_materia
 *               - cod_materia
 *               - nombre_materia
 *               - semestre
 *               - id_relacion
 *               Array vacío si no hay resultados o en caso de error
 */
function obtenerMateriasAsignadas($id_carrera) {
    global $db; // Conexión MySQLi global
    
    $materias = []; // Array para almacenar resultados
    
    // Consulta SQL con JOIN y parámetro preparado
    $query = "SELECT m.id_materia, m.cod_materia, m.nombre_materia, 
                     cm.semestre, cm.id_relacion
              FROM carrera_materia cm
              JOIN materias m ON cm.id_materia = m.id_materia
              WHERE cm.id_carrera = ?
              ORDER BY cm.semestre, m.nombre_materia";
    
    // Preparamos la sentencia
    if ($stmt = $db->prepare($query)) {
        try {
            // Vinculamos parámetro (i = integer)
            $stmt->bind_param("i", $id_carrera);
            
            // Ejecutamos la consulta
            $stmt->execute();
            
            // Obtenemos resultados
            $result = $stmt->get_result();
            
            // Procesamos cada fila
            while ($row = $result->fetch_assoc()) {
                $materias[] = $row;
            }
            
            // Liberamos memoria del resultado
            $result->free();
            
        } catch (Exception $e) {
            // Registramos error sin interrumpir el flujo
            error_log("Error en obtenerMateriasAsignadas: " . $e->getMessage());
        } finally {
            // Cerramos el statement siempre
            $stmt->close();
        }
    } else {
        // Error en preparación de consulta
        error_log("Error preparando consulta: " . $db->error);
    }
    
    return $materias;
}

/**
 * Asigna una materia a una carrera con un semestre específico
 * 
 * @param int $id_carrera ID de la carrera
 * @param int $id_materia ID de la materia
 * @param int $semestre Número de semestre para la asignación
 * @return array Resultado de la operación con:
 *               - 'success': boolean indicando éxito
 *               - 'message': string descriptivo
 */
function asignarMateriaACarrera($id_carrera, $id_materia, $semestre) {
    global $db;

    try {
        // Obtener información para auditoría
        $carrera_info = obtenerCarreraPorId($id_carrera);
        $materia_info = obtenerMateriaPorId($db, $id_materia);

        // 1. Verificar si ya existe la asignación (con prepared statement)
        $check_query = "SELECT id_relacion FROM carrera_materia 
                       WHERE id_carrera = ? AND id_materia = ?";
        
        if ($check_stmt = $db->prepare($check_query)) {
            $check_stmt->bind_param("ii", $id_carrera, $id_materia);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                $check_stmt->close();
                return [
                    'success' => false, 
                    'message' => 'La materia ya está asignada a esta carrera'
                ];
            }
            $check_stmt->close();
        } else {
            throw new Exception('Error al verificar asignación: ' . $db->error);
        }

        // 2. Insertar nueva asignación (con prepared statement)
        $insert_query = "INSERT INTO carrera_materia 
                        (id_carrera, id_materia, semestre) 
                        VALUES (?, ?, ?)";
        
        if ($insert_stmt = $db->prepare($insert_query)) {
            $insert_stmt->bind_param("iii", $id_carrera, $id_materia, $semestre);
            $execute_result = $insert_stmt->execute();
            $insert_id = $db->insert_id;
            $insert_stmt->close();
            
            if ($execute_result) {
                // REGISTRAR EN AUDITORÍA - ASIGNACIÓN DE MATERIA A CARRERA
                if (function_exists('registrarAuditoria')) {
                    try {
                        registrarAuditoria(
                            "INSERT", 
                            "carrera_materia", 
                            $insert_id, 
                            null, 
                            [
                                'id_carrera' => $id_carrera,
                                'carrera_nombre' => $carrera_info['nombre_carrera'] ?? 'Desconocida',
                                'carrera_codigo' => $carrera_info['cod_carrera'] ?? '',
                                'id_materia' => $id_materia,
                                'materia_nombre' => $materia_info['nombre_materia'] ?? 'Desconocida',
                                'materia_codigo' => $materia_info['cod_materia'] ?? '',
                                'semestre' => $semestre
                            ], 
                            "Carreras-Materias", 
                            "Asignación de materia a carrera"
                        );
                    } catch (Exception $e) {
                        error_log("Error en auditoría asignarMateriaACarrera: " . $e->getMessage());
                    }
                }
                
                return [
                    'success' => true,
                    'message' => 'Materia asignada correctamente',
                    'insert_id' => $insert_id
                ];
            } else {
                throw new Exception('Error al asignar: ' . $db->error);
            }
        } else {
            throw new Exception('Error preparando consulta: ' . $db->error);
        }
        
    } catch (Exception $e) {
        error_log("Error en asignarMateriaACarrera: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL ASIGNAR MATERIA
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "carrera_materia", 
                    null, 
                    null, 
                    [
                        'id_carrera' => $id_carrera,
                        'id_materia' => $id_materia,
                        'semestre' => $semestre,
                        'error' => $e->getMessage()
                    ], 
                    "Carreras-Materias", 
                    "Error al asignar materia a carrera"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error asignarMateriaACarrera: " . $auditError->getMessage());
            }
        }
        
        return [
            'success' => false,
            'message' => 'Error al asignar materia: ' . $e->getMessage()
        ];
    }
}

/**
 * Asegura que la tabla `prelaciones` exista.
 */
function asegurarTablaPrelaciones() {
    global $db;
    $db->query("CREATE TABLE IF NOT EXISTS prelaciones (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        id_carrera INT NOT NULL,
        id_materia INT NOT NULL,
        id_prerequisito INT NOT NULL,
        tipo VARCHAR(50) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (id_carrera),
        INDEX (id_materia),
        INDEX (id_prerequisito),
        FOREIGN KEY (id_carrera) REFERENCES carreras(id_carrera) ON DELETE CASCADE,
        FOREIGN KEY (id_materia) REFERENCES materias(id_materia) ON DELETE CASCADE,
        FOREIGN KEY (id_prerequisito) REFERENCES materias(id_materia) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

/**
 * Agrega una prelación (prerequisito) para una materia en una carrera
 */
function agregarPrelacion($id_carrera, $id_materia, $id_prerequisito, $tipo = null) {
    global $db;
    try {
        asegurarTablaPrelaciones();
        // Evitar duplicados
        $check = $db->prepare("SELECT id FROM prelaciones WHERE id_carrera = ? AND id_materia = ? AND id_prerequisito = ? LIMIT 1");
        if (!$check) throw new Exception('Error al preparar comprobación de prelación: ' . $db->error);
        $check->bind_param('iii', $id_carrera, $id_materia, $id_prerequisito);
        $check->execute();
        $res = $check->get_result();
        if ($res && $res->num_rows > 0) {
            $check->close();
            return ['success' => false, 'message' => 'La prelación ya existe'];
        }
        $check->close();

        $stmt = $db->prepare("INSERT INTO prelaciones (id_carrera, id_materia, id_prerequisito, tipo) VALUES (?, ?, ?, ?)");
        if (!$stmt) throw new Exception('Error al preparar inserción de prelación: ' . $db->error);
        $stmt->bind_param('iiis', $id_carrera, $id_materia, $id_prerequisito, $tipo);
        if (!$stmt->execute()) throw new Exception('Error al ejecutar inserción de prelación: ' . $stmt->error);
        $insert_id = $db->insert_id;
        $stmt->close();
        return ['success' => true, 'message' => 'Prelación agregada', 'id' => $insert_id];
    } catch (Exception $e) {
        error_log('Error en agregarPrelacion: ' . $e->getMessage());
        return ['success' => false, 'message' => 'Error al agregar prelación: ' . $e->getMessage()];
    }
}

/**
 * Elimina una prelación por id
 */
function eliminarPrelacion($id) {
    global $db;
    try {
        asegurarTablaPrelaciones();
        $stmt = $db->prepare("DELETE FROM prelaciones WHERE id = ?");
        if (!$stmt) throw new Exception('Error al preparar delete prelación: ' . $db->error);
        $stmt->bind_param('i', $id);
        if (!$stmt->execute()) throw new Exception('Error al ejecutar delete prelación: ' . $stmt->error);
        $affected = $stmt->affected_rows;
        $stmt->close();
        return ['success' => true, 'affected_rows' => $affected];
    } catch (Exception $e) {
        error_log('Error en eliminarPrelacion: ' . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

/**
 * Obtiene prelaciones para una carrera
 */
function obtenerPrelacionesPorCarrera($id_carrera) {
    global $db;
    $rows = [];
    asegurarTablaPrelaciones();
    $query = "SELECT p.id, p.id_carrera, p.id_materia, p.id_prerequisito, p.tipo,
                     m1.cod_materia AS cod_materia, m1.nombre_materia AS nombre_materia,
                     m2.cod_materia AS cod_prereq, m2.nombre_materia AS nombre_prereq
              FROM prelaciones p
              JOIN materias m1 ON p.id_materia = m1.id_materia
              JOIN materias m2 ON p.id_prerequisito = m2.id_materia
              WHERE p.id_carrera = ?
              ORDER BY m1.trayecto, m1.nombre_materia";
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param('i', $id_carrera);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) $rows[] = $r;
            $res->free();
        }
        $stmt->close();
    }
    return $rows;
}

/**
 * --- Mallas (pensums) ---
 * Tabla `mallas`: id_malla, id_carrera, codigo_malla, anio, descripcion, created_at
 * Tabla `malla_materia`: id, id_malla, id_materia, semestre
 */

function asegurarTablaMallas() {
    global $db;
    $db->query("CREATE TABLE IF NOT EXISTS mallas (
        id_malla INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        id_carrera INT NOT NULL,
        codigo_malla VARCHAR(100) NOT NULL UNIQUE,
        anio INT NOT NULL,
        descripcion TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (id_carrera),
        FOREIGN KEY (id_carrera) REFERENCES carreras(id_carrera) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->query("CREATE TABLE IF NOT EXISTS malla_materia (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        id_malla INT NOT NULL,
        id_materia INT NOT NULL,
        semestre INT NOT NULL,
        INDEX (id_malla),
        FOREIGN KEY (id_malla) REFERENCES mallas(id_malla) ON DELETE CASCADE,
        FOREIGN KEY (id_materia) REFERENCES materias(id_materia) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

/**
 * Crear una nueva malla para una carrera
 * codigo_malla se recomienda generar como cod_carrera + anio (numérico)
 */
function crearMalla($id_carrera, $anio, $codigo_malla = null, $descripcion = null) {
    global $db;
    try {
        asegurarTablaMallas();
        if (empty($codigo_malla) || !preg_match('/^[0-9]+$/', $codigo_malla)) {
            // generar código numérico (solo dígitos) basado en codigo de carrera + año
            $c = obtenerCarreraPorId($id_carrera);
            $cod = $c['cod_carrera'] ?? '';
            $codigo_malla = generarCodigoMallaNumerico($cod, $anio, $id_carrera);
        }

        // Evitar duplicados por codigo
        $check = $db->prepare("SELECT id_malla FROM mallas WHERE codigo_malla = ? LIMIT 1");
        $check->bind_param('s', $codigo_malla);
        $check->execute();
        $res = $check->get_result();
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $check->close();
            return ['success' => false, 'message' => 'Código de malla ya existe', 'id_malla' => $row['id_malla']];
        }
        $check->close();

        $stmt = $db->prepare("INSERT INTO mallas (id_carrera, codigo_malla, anio, descripcion) VALUES (?, ?, ?, ?)");
        if (!$stmt) throw new Exception('Error al preparar inserción malla: ' . $db->error);
        $stmt->bind_param('isis', $id_carrera, $codigo_malla, $anio, $descripcion);
        if (!$stmt->execute()) throw new Exception('Error al insertar malla: ' . $stmt->error);
        $id = $db->insert_id;
        $stmt->close();
        return ['success' => true, 'message' => 'Malla creada', 'id_malla' => $id];
    } catch (Exception $e) {
        error_log('Error en crearMalla: ' . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

/**
 * Genera un código de malla compuesto solo por dígitos.
 * - extrae dígitos del `cod_carrera` si existe
 * - si no hay dígitos usa el id_carrera
 * - concatena el año (4 dígitos)
 * - garantiza unicidad añadiendo sufijo numérico si es necesario
 */
function generarCodigoMallaNumerico($cod_carrera, $anio, $id_carrera = null) {
    global $db;
    // extraer solo dígitos
    $digits = '';
    if (!empty($cod_carrera)) {
        // buscar la primera ocurrencia de 5 dígitos
        if (preg_match('/(\d{5})/', $cod_carrera, $m)) {
            $digits = $m[1];
        } else {
            // si no hay 5 consecutivos, extraer todos los dígitos y tomar los primeros 5
            preg_match_all('/\d+/', $cod_carrera, $m2);
            if (!empty($m2[0])) {
                $all = implode('', $m2[0]);
                $digits = substr($all, 0, 5);
            } else {
                // convertir letras a números (A=01..Z=26) y tomar primeros 5
                $s = strtoupper($cod_carrera);
                $mapped = '';
                for ($i = 0; $i < strlen($s); $i++) {
                    $ch = $s[$i];
                    if (ctype_alpha($ch)) {
                        $val = ord($ch) - ord('A') + 1;
                        $mapped .= str_pad($val, 2, '0', STR_PAD_LEFT);
                        if (strlen($mapped) >= 5) break;
                    }
                }
                $digits = substr($mapped, 0, 5);
            }
        }
    }
    // asegurarse de tener exactamente 5 dígitos: pad o trim
    $digits = preg_replace('/\D/', '', $digits);
    if (strlen($digits) < 5) {
        // si no alcanzamos 5, usar id_carrera padded
        if (!empty($id_carrera)) {
            $digits = str_pad(substr(preg_replace('/\D/', '', strval($id_carrera)), 0, 5), 5, '0', STR_PAD_LEFT);
        } else {
            $digits = str_pad($digits, 5, '0', STR_PAD_LEFT);
        }
    } elseif (strlen($digits) > 5) {
        $digits = substr($digits, 0, 5);
    }
    $anio_str = str_pad(intval($anio), 4, '0', STR_PAD_LEFT);
    $base = $digits . $anio_str;

    // asegurar solo dígitos
    $base = preg_replace('/\D/', '', $base);

    $codigo = $base;
    $sufijo = 0;
    while (true) {
        // comprobar existencia
        $stmt = $db->prepare("SELECT id_malla FROM mallas WHERE codigo_malla = ? LIMIT 1");
        if (!$stmt) break; // en caso de error, devolver lo que haya
        $stmt->bind_param('s', $codigo);
        $stmt->execute();
        $res = $stmt->get_result();
        $exists = ($res && $res->num_rows > 0);
        $stmt->close();
        if (!$exists) break;
        $sufijo++;
        $codigo = $base . str_pad($sufijo, 3, '0', STR_PAD_LEFT);
        // proteger bucle infinito
        if ($sufijo > 9999) break;
    }

    return $codigo;
}

/**
 * Normaliza códigos de mallas existentes: asegura que sean solo dígitos
 * Si una malla tiene codigo no numérico intenta regenerarlo y actualizar.
 */
function normalizarCodigosMallas() {
    global $db;
    asegurarTablaMallas();
    $updated = 0;
    $errors = [];
    $res = $db->query("SELECT id_malla, id_carrera, codigo_malla, anio FROM mallas");
    if (!$res) return ['updated' => 0, 'errors' => ['Error listando mallas: ' . $db->error]];
    while ($row = $res->fetch_assoc()) {
        if (!preg_match('/^[0-9]+$/', $row['codigo_malla'])) {
            $nuevo = generarCodigoMallaNumerico('', $row['anio'], $row['id_carrera']);
            $stmt = $db->prepare("UPDATE mallas SET codigo_malla = ? WHERE id_malla = ?");
            if ($stmt) {
                $stmt->bind_param('si', $nuevo, $row['id_malla']);
                if ($stmt->execute()) { $updated++; } else { $errors[] = 'Error update id ' . $row['id_malla'] . ': ' . $stmt->error; }
                $stmt->close();
            } else {
                $errors[] = 'Error preparar update id ' . $row['id_malla'] . ': ' . $db->error;
            }
        }
    }
    return ['updated' => $updated, 'errors' => $errors];
}

function obtenerMallasPorCarrera($id_carrera) {
    global $db;
    asegurarTablaMallas();
    $rows = [];
    $query = "SELECT id_malla, id_carrera, codigo_malla, anio, descripcion, created_at FROM mallas WHERE id_carrera = ? ORDER BY anio DESC";
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param('i', $id_carrera);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) $rows[] = $r;
            $res->free();
        }
        $stmt->close();
    }
    return $rows;
}

function obtenerMallaPorId($id_malla) {
    global $db;
    asegurarTablaMallas();
    $query = "SELECT id_malla, id_carrera, codigo_malla, anio, descripcion, created_at FROM mallas WHERE id_malla = ? LIMIT 1";
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param('i', $id_malla);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            $res->free();
            $stmt->close();
            return $row;
        }
        $stmt->close();
    }
    return null;
}

function asignarMateriaAMalla($id_malla, $id_materia, $semestre) {
    global $db;
    try {
        asegurarTablaMallas();
        // evitar duplicado
        $check = $db->prepare("SELECT id FROM malla_materia WHERE id_malla = ? AND id_materia = ? LIMIT 1");
        $check->bind_param('ii', $id_malla, $id_materia);
        $check->execute();
        $res = $check->get_result();
        if ($res && $res->num_rows > 0) { $check->close(); return ['success' => false, 'message' => 'Materia ya asignada a la malla']; }
        $check->close();

        $stmt = $db->prepare("INSERT INTO malla_materia (id_malla, id_materia, semestre) VALUES (?, ?, ?)");
        if (!$stmt) throw new Exception('Error al preparar insert malla_materia: ' . $db->error);
        $stmt->bind_param('iii', $id_malla, $id_materia, $semestre);
        if (!$stmt->execute()) throw new Exception('Error al insertar malla_materia: ' . $stmt->error);
        $id = $db->insert_id;
        $stmt->close();
        return ['success' => true, 'message' => 'Asignación creada', 'id' => $id];
    } catch (Exception $e) {
        error_log('Error en asignarMateriaAMalla: ' . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

function obtenerMateriasDeMalla($id_malla) {
    global $db;
    asegurarTablaMallas();
    $rows = [];
    $query = "SELECT mm.id, mm.id_malla, mm.id_materia, mm.semestre, m.cod_materia, m.nombre_materia FROM malla_materia mm JOIN materias m ON mm.id_materia = m.id_materia WHERE mm.id_malla = ? ORDER BY mm.semestre, m.nombre_materia";
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param('i', $id_malla);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) $rows[] = $r;
            $res->free();
        }
        $stmt->close();
    }
    return $rows;
}

function eliminarAsignacionMalla($id) {
    global $db;
    try {
        asegurarTablaMallas();
        $stmt = $db->prepare("DELETE FROM malla_materia WHERE id = ?");
        if (!$stmt) throw new Exception('Error al preparar delete malla_materia: ' . $db->error);
        $stmt->bind_param('i', $id);
        if (!$stmt->execute()) throw new Exception('Error al ejecutar delete malla_materia: ' . $stmt->error);
        $aff = $stmt->affected_rows;
        $stmt->close();
        return ['success' => true, 'affected_rows' => $aff];
    } catch (Exception $e) {
        error_log('Error en eliminarAsignacionMalla: ' . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

function eliminarMalla($id_malla) {
    global $db;
    try {
        asegurarTablaMallas();
        $stmt = $db->prepare("DELETE FROM mallas WHERE id_malla = ?");
        if (!$stmt) throw new Exception('Error al preparar delete malla: ' . $db->error);
        $stmt->bind_param('i', $id_malla);
        if (!$stmt->execute()) throw new Exception('Error al ejecutar delete malla: ' . $stmt->error);
        $aff = $stmt->affected_rows;
        $stmt->close();
        return ['success' => true, 'affected_rows' => $aff];
    } catch (Exception $e) {
        error_log('Error en eliminarMalla: ' . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

/**
 * Migrar datos desde `carrera_versiones`/`version_materia` a `mallas`/`malla_materia`.
 * Devuelve array con resultados y conteos.
 */
function migrarVersionesAMallas() {
    global $db;
    $result = ['created_mallas' => 0, 'created_asignaciones' => 0, 'skipped_mallas' => 0, 'errors' => []];
    try {
        asegurarTablaMallas();

        // comprobar si tablas de versiones existen
        $res = $db->query("SHOW TABLES LIKE 'carrera_versiones'");
        if (!$res || $res->num_rows === 0) {
            return ['message' => 'No existe tabla carrera_versiones', 'created_mallas' => 0];
        }

        $rv = $db->query("SELECT id_version, id_carrera, fecha_vigencia, created_at FROM carrera_versiones ORDER BY id_version");
        if (!$rv) throw new Exception('Error leyendo carrera_versiones: ' . $db->error);

        while ($row = $rv->fetch_assoc()) {
            $anio = null;
            if (!empty($row['fecha_vigencia'])) {
                $anio = intval(date('Y', strtotime($row['fecha_vigencia'])));
            } elseif (!empty($row['created_at'])) {
                $anio = intval(date('Y', strtotime($row['created_at'])));
            } else {
                $anio = intval(date('Y'));
            }

            // generar codigo_malla: tomar cod_carrera si disponible
            $c = obtenerCarreraPorId($row['id_carrera']);
            $cod = $c['cod_carrera'] ?? ('C' . $row['id_carrera']);
            $codigo_malla = $cod . '_' . $anio;

            // crear malla (si existe, saltar)
            $check = $db->prepare("SELECT id_malla FROM mallas WHERE codigo_malla = ? LIMIT 1");
            $check->bind_param('s', $codigo_malla);
            $check->execute();
            $rcheck = $check->get_result();
            if ($rcheck && $rcheck->num_rows > 0) {
                $existing = $rcheck->fetch_assoc();
                $id_malla = $existing['id_malla'];
                $result['skipped_mallas']++;
                $check->close();
            } else {
                $check->close();
                $ins = $db->prepare("INSERT INTO mallas (id_carrera, codigo_malla, anio, descripcion) VALUES (?, ?, ?, ?)");
                $desc = 'Migrada desde carrera_versiones id_version=' . $row['id_version'];
                $ins->bind_param('isis', $row['id_carrera'], $codigo_malla, $anio, $desc);
                if (!$ins->execute()) { $result['errors'][] = 'Error insert malla: ' . $ins->error; $ins->close(); continue; }
                $id_malla = $db->insert_id;
                $ins->close();
                $result['created_mallas']++;
            }

            // copiar asignaciones de version_materia (si existe tabla)
            $rv2 = $db->query("SHOW TABLES LIKE 'version_materia'");
            if ($rv2 && $rv2->num_rows > 0) {
                $q = $db->prepare("SELECT id_materia, semestre FROM version_materia WHERE id_version = ?");
                $q->bind_param('i', $row['id_version']);
                if ($q->execute()) {
                    $res2 = $q->get_result();
                    while ($as = $res2->fetch_assoc()) {
                        // insertar en malla_materia si no existe
                        $chk = $db->prepare("SELECT id FROM malla_materia WHERE id_malla = ? AND id_materia = ? LIMIT 1");
                        $chk->bind_param('ii', $id_malla, $as['id_materia']);
                        $chk->execute();
                        $rchk = $chk->get_result();
                        if ($rchk && $rchk->num_rows > 0) { $chk->close(); continue; }
                        $chk->close();

                        $ins2 = $db->prepare("INSERT INTO malla_materia (id_malla, id_materia, semestre) VALUES (?, ?, ?)");
                        $ins2->bind_param('iii', $id_malla, $as['id_materia'], $as['semestre']);
                        if ($ins2->execute()) { $result['created_asignaciones']++; }
                        else { $result['errors'][] = 'Error insert malla_materia: ' . $ins2->error; }
                        $ins2->close();
                    }
                    $res2->free();
                }
                $q->close();
            }
        }

        $rv->free();
        return $result;
    } catch (Exception $e) {
        $result['errors'][] = $e->getMessage();
        return $result;
    }
}

/**
 * Elimina una asignación de materia a carrera
 * 
 * @param int $id_relacion ID de la relación materia-carrera a eliminar
 * @return array Resultado de la operación con:
 *               - 'success': boolean indicando éxito
 *               - 'message': string descriptivo
 */
function eliminarAsignacionMateria($id_relacion) {
    global $db;

    try {
        // 1. Obtener información de la relación para auditoría
        $info_query = "SELECT cm.*, c.nombre_carrera, c.cod_carrera, 
                              m.nombre_materia, m.cod_materia
                       FROM carrera_materia cm
                       JOIN carreras c ON cm.id_carrera = c.id_carrera
                       JOIN materias m ON cm.id_materia = m.id_materia
                       WHERE cm.id_relacion = ?";
        
        $info_stmt = $db->prepare($info_query);
        if (!$info_stmt) {
            throw new Exception('Error al preparar consulta de información: ' . $db->error);
        }
        
        $info_stmt->bind_param("i", $id_relacion);
        $info_stmt->execute();
        $info_result = $info_stmt->get_result();
        $relacion_info = $info_result->fetch_assoc();
        $info_stmt->close();
        
        if (!$relacion_info) {
            return [
                'success' => false, 
                'message' => 'No se encontró la relación especificada'
            ];
        }

        // 2. Eliminar asignación (con prepared statement)
        $delete_query = "DELETE FROM carrera_materia WHERE id_relacion = ?";
        
        if ($delete_stmt = $db->prepare($delete_query)) {
            $delete_stmt->bind_param("i", $id_relacion);
            $execute_result = $delete_stmt->execute();
            $affected_rows = $delete_stmt->affected_rows;
            $delete_stmt->close();
            
            if ($execute_result && $affected_rows > 0) {
                // REGISTRAR EN AUDITORÍA - ELIMINACIÓN DE ASIGNACIÓN
                if (function_exists('registrarAuditoria')) {
                    try {
                        registrarAuditoria(
                            "DELETE", 
                            "carrera_materia", 
                            $id_relacion, 
                            [
                                'id_carrera' => $relacion_info['id_carrera'],
                                'carrera_nombre' => $relacion_info['nombre_carrera'],
                                'carrera_codigo' => $relacion_info['cod_carrera'],
                                'id_materia' => $relacion_info['id_materia'],
                                'materia_nombre' => $relacion_info['nombre_materia'],
                                'materia_codigo' => $relacion_info['cod_materia'],
                                'semestre' => $relacion_info['semestre']
                            ], 
                            null, 
                            "Carreras-Materias", 
                            "Eliminación de asignación materia-carrera"
                        );
                    } catch (Exception $e) {
                        error_log("Error en auditoría eliminarAsignacionMateria: " . $e->getMessage());
                    }
                }
                
                return [
                    'success' => true,
                    'message' => 'Asignación eliminada correctamente',
                    'affected_rows' => $affected_rows
                ];
            } else {
                throw new Exception($affected_rows === 0 
                    ? 'No se encontró la asignación con el ID proporcionado' 
                    : 'Error al eliminar: ' . $db->error);
            }
        } else {
            throw new Exception('Error preparando consulta de eliminación: ' . $db->error);
        }
        
    } catch (Exception $e) {
        error_log("Error en eliminarAsignacionMateria: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL ELIMINAR ASIGNACIÓN
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "carrera_materia", 
                    $id_relacion, 
                    null, 
                    [
                        'id_relacion' => $id_relacion,
                        'error' => $e->getMessage()
                    ], 
                    "Carreras-Materias", 
                    "Error al eliminar asignación materia-carrera"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error eliminarAsignacionMateria: " . $auditError->getMessage());
            }
        }
        
        return [
            'success' => false,
            'message' => 'Error al eliminar asignación: ' . $e->getMessage()
        ];
    }
}

/**
 * Actualiza el semestre de una asignación materia-carrera
 * 
 * @param int $id_relacion ID de la relación materia-carrera
 * @param int $nuevo_semestre Nuevo número de semestre
 * @return array Resultado de la operación
 */
function actualizarSemestreAsignacion($id_relacion, $nuevo_semestre) {
    global $db;

    try {
        // Obtener información actual para auditoría
        $info_query = "SELECT cm.*, c.nombre_carrera, c.cod_carrera, 
                              m.nombre_materia, m.cod_materia
                       FROM carrera_materia cm
                       JOIN carreras c ON cm.id_carrera = c.id_carrera
                       JOIN materias m ON cm.id_materia = m.id_materia
                       WHERE cm.id_relacion = ?";
        
        $info_stmt = $db->prepare($info_query);
        if (!$info_stmt) {
            throw new Exception('Error al preparar consulta de información: ' . $db->error);
        }
        
        $info_stmt->bind_param("i", $id_relacion);
        $info_stmt->execute();
        $info_result = $info_stmt->get_result();
        $relacion_info = $info_result->fetch_assoc();
        $info_stmt->close();
        
        if (!$relacion_info) {
            return [
                'success' => false, 
                'message' => 'No se encontró la relación especificada'
            ];
        }

        // Actualizar el semestre
        $update_query = "UPDATE carrera_materia SET semestre = ? WHERE id_relacion = ?";
        
        if ($update_stmt = $db->prepare($update_query)) {
            $update_stmt->bind_param("ii", $nuevo_semestre, $id_relacion);
            $execute_result = $update_stmt->execute();
            $affected_rows = $update_stmt->affected_rows;
            $update_stmt->close();
            
            if ($execute_result && $affected_rows > 0) {
                // REGISTRAR EN AUDITORÍA - ACTUALIZACIÓN DE SEMESTRE
                if (function_exists('registrarAuditoria')) {
                    try {
                        registrarAuditoria(
                            "UPDATE", 
                            "carrera_materia", 
                            $id_relacion, 
                            [
                                'semestre_anterior' => $relacion_info['semestre'],
                                'id_carrera' => $relacion_info['id_carrera'],
                                'carrera_nombre' => $relacion_info['nombre_carrera'],
                                'id_materia' => $relacion_info['id_materia'],
                                'materia_nombre' => $relacion_info['nombre_materia']
                            ], 
                            [
                                'semestre_nuevo' => $nuevo_semestre,
                                'carrera_nombre' => $relacion_info['nombre_carrera'],
                                'materia_nombre' => $relacion_info['nombre_materia']
                            ], 
                            "Carreras-Materias", 
                            "Actualización de semestre en asignación materia-carrera"
                        );
                    } catch (Exception $e) {
                        error_log("Error en auditoría actualizarSemestreAsignacion: " . $e->getMessage());
                    }
                }
                
                return [
                    'success' => true,
                    'message' => 'Semestre actualizado correctamente',
                    'affected_rows' => $affected_rows
                ];
            } else {
                throw new Exception($affected_rows === 0 
                    ? 'No se encontró la asignación o no hubo cambios' 
                    : 'Error al actualizar: ' . $db->error);
            }
        } else {
            throw new Exception('Error preparando consulta de actualización: ' . $db->error);
        }
        
    } catch (Exception $e) {
        error_log("Error en actualizarSemestreAsignacion: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL ACTUALIZAR SEMESTRE
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "carrera_materia", 
                    $id_relacion, 
                    null, 
                    [
                        'id_relacion' => $id_relacion,
                        'nuevo_semestre' => $nuevo_semestre,
                        'error' => $e->getMessage()
                    ], 
                    "Carreras-Materias", 
                    "Error al actualizar semestre de asignación"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error actualizarSemestreAsignacion: " . $auditError->getMessage());
            }
        }
        
        return [
            'success' => false,
            'message' => 'Error al actualizar semestre: ' . $e->getMessage()
        ];
    }
}


/**
* Verificación básica de sesión usando $db
*/
function verificarSesion() {
  global $db;
  
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }
  
  if (!isset($_SESSION['usuario_id'])) {
      header('Location: login.php');
      exit;
  }
  
  // Opcional: verificar usuario en base de datos
  $usuario_id = $db->real_escape_string($_SESSION['usuario_id']);
  $query = "SELECT activo FROM usuarios WHERE id = '$usuario_id'";
  $result = $db->query($query);
  
  if ($result->num_rows === 0 || $result->fetch_assoc()['activo'] != 1) {
      session_destroy();
      header('Location: login.php');
      exit;
  }
}



//MATERIA



/**
 * Obtiene todas las materias de la base de datos ordenadas por nombre
 * 
 * @param mysqli $db Conexión a la base de datos MySQLi
 * @return array Listado de todas las materias con sus datos completos
 */
function obtenerMaterias($db) {
    // Inicializamos el array de resultados
    $materias = [];
    
    // Definimos la consulta SQL (aunque no tiene parámetros, usamos prepared statement por consistencia)
    $query = "SELECT * FROM materias ORDER BY nombre_materia";
    
    // Preparamos la sentencia
    if ($stmt = $db->prepare($query)) {
        try {
            // Ejecutamos la consulta (no necesita bind_param ya que no tiene parámetros)
            $stmt->execute();
            
            // Obtenemos el resultado
            $result = $stmt->get_result();
            
            // Recorremos los resultados
            while ($row = $result->fetch_assoc()) {
                $materias[] = $row;
            }
            
            // Liberamos memoria
            $result->free();
            
        } catch (Exception $e) {
            // Registramos errores silenciosamente
            error_log("Error en obtenerMaterias: " . $e->getMessage());
        } finally {
            // Cerramos el statement
            $stmt->close();
        }
    } else {
        error_log("Error preparando consulta: " . $db->error);
    }
    
    return $materias;
}

/**
 * Obtiene una materia de la base de datos por su ID usando MySQLi
 * 
 * @param mysqli $db Conexión a la base de datos MySQLi
 * @param int $id ID de la materia a buscar
 * @return array|null Array asociativo con los datos de la materia o null si no se encuentra
 */
function obtenerMateriaPorId($db, $id) {
    // Preparamos la consulta SQL con un parámetro de sustitución (?)
    $query = "SELECT * FROM materias WHERE id_materia = ?";
    
    // Preparamos la sentencia
    $stmt = $db->prepare($query);
    
    // Verificamos si la preparación fue exitosa
    if (!$stmt) {
        // En caso de error, podrías registrar el error o lanzar una excepción
        return null;
    }
    
    // Vinculamos el parámetro (i = integer)
    $stmt->bind_param("i", $id);
    
    // Ejecutamos la consulta
    $stmt->execute();
    
    // Obtenemos el resultado de la consulta
    $result = $stmt->get_result();
    
    // Cerramos la sentencia para liberar recursos
    $stmt->close();
    
    // Retornamos el resultado como array asociativo o null si no hay resultados
    return $result->fetch_assoc() ?: null;
}

/**
 * Crea una nueva materia en la base de datos con validación y sentencias preparadas
 * 
 * @param mysqli $db Conexión a la base de datos MySQLi
 * @param array $data Datos de la materia a crear
 * @return bool True si la creación fue exitosa, False en caso de error
 */
function crearMateria($db, $data) {
    try {
        // Validación del campo trayecto (1-5)
        $trayecto = isset($data['trayecto']) ? (int)$data['trayecto'] : 1;
        if ($trayecto < 1 || $trayecto > 5) {
            $trayecto = 1; // Valor por defecto si está fuera de rango
        }

        // Validación de campos booleanos
        $activa = isset($data['activa']) ? (int)(bool)$data['activa'] : 1;

        // Consulta SQL con sentencia preparada
        $query = "INSERT INTO materias (
                    cod_materia, 
                    nombre_materia, 
                    pnf_ptf, 
                    duracion_periodo, 
                    trayecto,
                    creditos, 
                    activa, 
                    horas_teoricas, 
                    horas_practicas,
                    horas_laboratorio,
                    horas_semanales,
                    created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        // Preparamos la sentencia
        $stmt = $db->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Error en preparación de query: " . $db->error);
        }

        // Validación y asignación de valores
        $cod_materia = $data['cod_materia'] ?? '';
        $nombre_materia = $data['nombre_materia'] ?? '';
        $pnf_ptf = $data['pnf_ptf'] ?? '';
        $duracion_periodo = isset($data['duracion_periodo']) ? (int)$data['duracion_periodo'] : 0;
        $creditos = isset($data['creditos']) ? (int)$data['creditos'] : 0;
        $horas_teoricas = isset($data['horas_teoricas']) ? (int)$data['horas_teoricas'] : 0;
        $horas_practicas = isset($data['horas_practicas']) ? (int)$data['horas_practicas'] : 0;
        $horas_laboratorio = isset($data['horas_laboratorio']) ? (int)$data['horas_laboratorio'] : 0;
        $horas_semanales = isset($data['horas_semanales']) ? (int)$data['horas_semanales'] : 0;

        // Vinculamos parámetros (tipos: s=string, i=integer)
        $stmt->bind_param("sssiiiiiiii", 
            $cod_materia,
            $nombre_materia,
            $pnf_ptf,
            $duracion_periodo,
            $trayecto,
            $creditos,
            $activa,
            $horas_teoricas,
            $horas_practicas,
            $horas_laboratorio,
            $horas_semanales
        );
        
        // Ejecutamos la sentencia
        $result = $stmt->execute();
        
        if (!$result) {
            throw new Exception("Error al ejecutar query: " . $stmt->error);
        }
        
        $materia_id = $stmt->insert_id;
        $stmt->close();
        
        // REGISTRAR EN AUDITORÍA - NUEVA MATERIA CREADA
        if ($result && function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "INSERT", 
                    "materias", 
                    $materia_id, 
                    null, 
                    [
                        'cod_materia' => $cod_materia,
                        'nombre_materia' => $nombre_materia,
                        'pnf_ptf' => $pnf_ptf,
                        'duracion_periodo' => $duracion_periodo,
                        'trayecto' => $trayecto,
                        'creditos' => $creditos,
                        'activa' => $activa,
                        'horas_teoricas' => $horas_teoricas,
                        'horas_practicas' => $horas_practicas,
                        'horas_laboratorio' => $horas_laboratorio,
                        'horas_semanales' => $horas_semanales
                    ], 
                    "Materias", 
                    "Nueva materia creada"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría crearMateria: " . $e->getMessage());
            }
        }
        
        return $result;
        
    } catch (Exception $e) {
        error_log("Error en crearMateria: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL CREAR MATERIA
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "materias", 
                    null, 
                    null, 
                    [
                        'cod_materia' => $data['cod_materia'] ?? '',
                        'nombre_materia' => $data['nombre_materia'] ?? '',
                        'error' => $e->getMessage()
                    ], 
                    "Materias", 
                    "Error al crear nueva materia"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error crearMateria: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

function actualizarMateria($db, $id, $data) {
    try {
        // Validar que el ID sea numérico
        if (!is_numeric($id)) {
            throw new Exception("ID de materia no válido");
        }

        // Obtener datos actuales para auditoría
        $datos_actuales = obtenerMateriaPorId($db, $id);
        if (!$datos_actuales) {
            throw new Exception("Materia no encontrada");
        }

        // Consulta SQL con sentencia preparada
        $query = "UPDATE materias SET 
            cod_materia = ?, 
            nombre_materia = ?, 
            pnf_ptf = ?,
            duracion_periodo = ?,
            creditos = ?, 
            activa = ?, 
            horas_teoricas = ?, 
            horas_practicas = ?,
            horas_laboratorio = ?,
            horas_semanales = ?,
            trayecto = ?
            WHERE id_materia = ?";
        
        // Preparamos la sentencia
        $stmt = $db->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Error en preparación de query: " . $db->error);
        }

        // Validación y saneamiento de datos
        $cod_materia = $data['cod_materia'] ?? '';
        $nombre_materia = $data['nombre_materia'] ?? '';
        $pnf_ptf = $data['pnf_ptf'] ?? '';
        $duracion_periodo = isset($data['duracion_periodo']) ? (int)$data['duracion_periodo'] : 0;
        $creditos = isset($data['creditos']) ? (int)$data['creditos'] : 0;
        $activa = isset($data['activa']) ? (int)(bool)$data['activa'] : 0;
        $horas_teoricas = isset($data['horas_teoricas']) ? (int)$data['horas_teoricas'] : 0;
        $horas_practicas = isset($data['horas_practicas']) ? (int)$data['horas_practicas'] : 0;
        $horas_laboratorio = isset($data['horas_laboratorio']) ? (int)$data['horas_laboratorio'] : 0;
        $horas_semanales = isset($data['horas_semanales']) ? (int)$data['horas_semanales'] : 0;
        $trayecto = isset($data['trayecto']) ? (int)$data['trayecto'] : 0;

        // Vinculamos parámetros (tipos: s=string, i=integer)
        $stmt->bind_param("sssiiiiiiiii",
            $cod_materia,
            $nombre_materia,
            $pnf_ptf,
            $duracion_periodo,
            $creditos,
            $activa,
            $horas_teoricas,
            $horas_practicas,
            $horas_laboratorio,
            $horas_semanales,
            $trayecto,
            $id
        );
        
        // Ejecutamos la sentencia
        $result = $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        
        if (!$result) {
            throw new Exception("Error al actualizar materia: " . $stmt->error);
        }
        
        // REGISTRAR EN AUDITORÍA - ACTUALIZACIÓN DE MATERIA
        if ($result && $affected_rows > 0 && function_exists('registrarAuditoria')) {
            try {
                $valores_antiguos_audit = [];
                $valores_nuevos_audit = [];
                
                // Comparar campos modificados
                $campos_auditar = [
                    'cod_materia', 'nombre_materia', 'pnf_ptf', 'duracion_periodo',
                    'creditos', 'activa', 'horas_teoricas', 'horas_practicas', 'horas_laboratorio', 'horas_semanales', 'trayecto'
                ];
                
                foreach ($campos_auditar as $campo) {
                    $valor_antiguo = $datos_actuales[$campo] ?? null;
                    $valor_nuevo = $data[$campo] ?? null;
                    
                    if ($valor_antiguo != $valor_nuevo) {
                        $valores_antiguos_audit[$campo] = $valor_antiguo;
                        $valores_nuevos_audit[$campo] = $valor_nuevo;
                    }
                }
                
                if (!empty($valores_nuevos_audit)) {
                    registrarAuditoria(
                        "UPDATE", 
                        "materias", 
                        $id, 
                        $valores_antiguos_audit, 
                        $valores_nuevos_audit, 
                        "Materias", 
                        "Actualización de datos de materia"
                    );
                }
            } catch (Exception $e) {
                error_log("Error en auditoría actualizarMateria: " . $e->getMessage());
            }
        }
        
        return $result;
        
    } catch (Exception $e) {
        error_log("Error en actualizarMateria: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL ACTUALIZAR MATERIA
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "materias", 
                    $id, 
                    null, 
                    [
                        'cod_materia' => $data['cod_materia'] ?? '',
                        'nombre_materia' => $data['nombre_materia'] ?? '',
                        'error' => $e->getMessage()
                    ], 
                    "Materias", 
                    "Error al actualizar materia"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error actualizarMateria: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

/**
 * Alterna el estado activo/inactivo de una materia
 * 
 * @param mysqli $db Conexión a la base de datos MySQLi
 * @param int $id ID de la materia a modificar
 * @return array|false Retorna el nuevo estado y datos básicos, o false en caso de error
 */
function toggleMateria($db, $id) {
    try {
        // Validación básica del ID
        if (!is_numeric($id)) {
            throw new Exception("ID de materia no válido");
        }

        // Obtener información actual para auditoría
        $materia_actual = obtenerMateriaPorId($db, $id);
        if (!$materia_actual) {
            throw new Exception("Materia no encontrada");
        }

        // Consulta directa para alternar el estado
        $query = "UPDATE materias SET activa = NOT activa WHERE id_materia = ?";
        $stmt = $db->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Error en preparación de consulta");
        }
        
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        
        if (!$result) {
            throw new Exception("Error al ejecutar consulta");
        }
        
        // REGISTRAR EN AUDITORÍA - CAMBIO DE ESTADO DE MATERIA
        if ($result && $affected_rows > 0 && function_exists('registrarAuditoria')) {
            try {
                $nuevo_estado = $materia_actual['activa'] ? 0 : 1;
                $estado_texto_anterior = $materia_actual['activa'] ? 'Activa' : 'Inactiva';
                $estado_texto_nuevo = $nuevo_estado ? 'Activa' : 'Inactiva';
                
                registrarAuditoria(
                    "UPDATE", 
                    "materias", 
                    $id, 
                    [
                        'activa' => $materia_actual['activa'],
                        'estado_anterior' => $estado_texto_anterior
                    ], 
                    [
                        'activa' => $nuevo_estado,
                        'estado_nuevo' => $estado_texto_nuevo,
                        'cod_materia' => $materia_actual['cod_materia'],
                        'nombre_materia' => $materia_actual['nombre_materia']
                    ], 
                    "Materias", 
                    "Cambio de estado de materia"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría toggleMateria: " . $e->getMessage());
            }
        }
        
        return $result;
        
    } catch (Exception $e) {
        error_log("Error en toggleMateria: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL CAMBIAR ESTADO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "materias", 
                    $id, 
                    null, 
                    [
                        'id_materia' => $id,
                        'error' => $e->getMessage()
                    ], 
                    "Materias", 
                    "Error al cambiar estado de materia"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error toggleMateria: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

/* ================================== */
/* FUNCIONES PARA MANEJO DE MATERIAS */
/* ================================== */

if (!function_exists('getAllMaterias')) {
    /**
     * Obtiene todas las materias de la base de datos usando sentencias preparadas
     * 
     * @return array Lista de materias o array vacío si hay error
     */
    function getAllMaterias() {
        global $db;
        
        // Consulta SQL para obtener todas las materias
         $query = "SELECT id, cod_materia, nombre_materia, creditos, 
                    horas_teoricas, horas_practicas, horas_laboratorio, horas_semanales, activa 
                FROM materias 
                ORDER BY nombre_materia ASC";
        
        // Preparamos la sentencia
        $stmt = $db->prepare($query);
        
        // Verificamos si la preparación fue exitosa
        if (!$stmt) {
            error_log("Error al preparar la consulta en getAllMaterias: ".$db->error);
            return [];
        }
        
        // Ejecutamos la sentencia
        if (!$stmt->execute()) {
            error_log("Error al ejecutar la consulta en getAllMaterias: ".$stmt->error);
            $stmt->close();
            return [];
        }
        
        // Obtenemos el resultado
        $result = $stmt->get_result();
        
        // Verificamos si obtuvimos resultados
        if (!$result) {
            error_log("Error al obtener resultados en getAllMaterias: ".$stmt->error);
            $stmt->close();
            return [];
        }
        
        // Procesamos los resultados
        $listaMaterias = [];
        while ($row = $result->fetch_assoc()) {
            $listaMaterias[] = $row;
        }
        
        // Cerramos la sentencia
        $stmt->close();
        
        return $listaMaterias;
    }
}

if (!function_exists('getMateriaById')) {
    /**
     * Obtiene una materia específica por su ID usando sentencias preparadas
     * 
     * @param int $id ID de la materia a buscar
     * @return array|null Array asociativo con los datos de la materia o null si no se encuentra o hay error
     */
    function getMateriaById($id) {
        global $db;
        
        // Validación básica del parámetro de entrada
        if (!is_numeric($id) || $id <= 0) {
            error_log("Error en getMateriaById: ID inválido");
            return null;
        }
        
        // Consulta SQL para obtener una materia por ID
         $query = "SELECT id, cod_materia, nombre_materia, creditos, 
                    horas_teoricas, horas_practicas, horas_laboratorio, horas_semanales, activa 
                FROM materias 
                WHERE id = ?";
        
        // Preparamos la sentencia
        $stmt = $db->prepare($query);
        
        // Verificamos si la preparación fue exitosa
        if (!$stmt) {
            error_log("Error al preparar la consulta en getMateriaById: ".$db->error);
            return null;
        }
        
        // Vinculamos el parámetro (i = integer)
        $bindResult = $stmt->bind_param("i", $id);
        if (!$bindResult) {
            error_log("Error al vincular parámetros en getMateriaById: ".$stmt->error);
            $stmt->close();
            return null;
        }
        
        // Ejecutamos la sentencia
        if (!$stmt->execute()) {
            error_log("Error al ejecutar la consulta en getMateriaById: ".$stmt->error);
            $stmt->close();
            return null;
        }
        
        // Obtenemos el resultado
        $result = $stmt->get_result();
        
        // Verificamos si obtuvimos resultados
        if (!$result) {
            error_log("Error al obtener resultados en getMateriaById: ".$stmt->error);
            $stmt->close();
            return null;
        }
        
        // Obtenemos la fila como array asociativo
        $materia = $result->fetch_assoc();
        
        // Cerramos la sentencia
        $stmt->close();
        
        // Retornamos el resultado (puede ser null si no se encontró la materia)
        return $materia;
    }
}

if (!function_exists('toggleMateriaStatus')) {
    /**
     * Cambia el estado activo/inactivo de una materia (toggle)
     * 
     * @param int $id ID de la materia a modificar
     * @return array|false Retorna un array con información del resultado o false en caso de error
     */
    function toggleMateriaStatus($id) {
        global $db;
        
        try {
            // Validación del ID de entrada
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID inválido ($id)");
            }
            
            // Obtener información actual para auditoría
            $materia_actual = getMateriaById($id);
            if (!$materia_actual) {
                throw new Exception("Materia no encontrada");
            }
            
            // Preparamos la consulta para cambiar el estado
            $query = "UPDATE materias SET activa = NOT activa WHERE id = ?";
            $stmt = $db->prepare($query);
            
            // Verificamos si la preparación fue exitosa
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: ".$db->error);
            }
            
            // Vinculamos el parámetro (i = integer)
            if (!$stmt->bind_param("i", $id)) {
                throw new Exception("Error al vincular parámetro: ".$stmt->error);
            }
            
            // Ejecutamos la consulta
            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar consulta: ".$stmt->error);
            }
            
            // Obtenemos el número de filas afectadas
            $affectedRows = $stmt->affected_rows;
            
            // Cerramos la sentencia
            $stmt->close();
            
            // Verificamos si realmente se actualizó algún registro
            if ($affectedRows === 0) {
                return [
                    'success' => false,
                    'message' => 'No se encontró la materia con el ID proporcionado',
                    'affected_rows' => 0
                ];
            }
            
            // REGISTRAR EN AUDITORÍA - CAMBIO DE ESTADO DE MATERIA
            if (function_exists('registrarAuditoria')) {
                try {
                    $nuevo_estado = $materia_actual['activa'] ? 0 : 1;
                    $estado_texto_anterior = $materia_actual['activa'] ? 'Activa' : 'Inactiva';
                    $estado_texto_nuevo = $nuevo_estado ? 'Activa' : 'Inactiva';
                    
                    registrarAuditoria(
                        "UPDATE", 
                        "materias", 
                        $id, 
                        [
                            'activa' => $materia_actual['activa'],
                            'estado_anterior' => $estado_texto_anterior
                        ], 
                        [
                            'activa' => $nuevo_estado,
                            'estado_nuevo' => $estado_texto_nuevo,
                            'cod_materia' => $materia_actual['cod_materia'],
                            'nombre_materia' => $materia_actual['nombre_materia']
                        ], 
                        "Materias", 
                        "Cambio de estado de materia"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría toggleMateriaStatus: " . $e->getMessage());
                }
            }
            
            // Retornamos información sobre la operación exitosa
            return [
                'success' => true,
                'message' => 'Estado de la materia actualizado correctamente',
                'affected_rows' => $affectedRows
            ];
            
        } catch (Exception $e) {
            error_log("Error en toggleMateriaStatus: " . $e->getMessage());
            
            // REGISTRAR EN AUDITORÍA - ERROR AL CAMBIAR ESTADO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "ERROR", 
                        "materias", 
                        $id, 
                        null, 
                        [
                            'id_materia' => $id,
                            'error' => $e->getMessage()
                        ], 
                        "Materias", 
                        "Error al cambiar estado de materia"
                    );
                } catch (Exception $auditError) {
                    error_log("Error en auditoría de error toggleMateriaStatus: " . $auditError->getMessage());
                }
            }
            
            return false;
        }
    }
}

if (!function_exists('guardarMateria')) {
    /**
     * Guarda o actualiza una materia en la base de datos
     * 
     * @param array $datos Array con los datos de la materia
     *        Requeridos: cod_materia, nombre_materia, creditos, horas_teoricas, horas_practicas, activa
     *        Opcional: id (para actualización)
     * @return array|false Retorna array con info de la operación o false en error grave
     */
    function guardarMateria($datos) {
        global $db;
        
        try {
            // Validación de datos requeridos
            $camposRequeridos = ['cod_materia', 'nombre_materia', 'creditos', 
                                'horas_teoricas', 'horas_practicas', 'activa'];
            
            foreach ($camposRequeridos as $campo) {
                if (!isset($datos[$campo])) {
                    throw new Exception("Falta el campo requerido '$campo'");
                }
            }
            
            // Determinar si es inserción o actualización
            $esNueva = empty($datos['id']);
            
            // Obtener datos actuales para auditoría si es actualización
            $datos_actuales = null;
            if (!$esNueva) {
                $datos_actuales = getMateriaById($datos['id']);
                if (!$datos_actuales) {
                    throw new Exception("No se encontró la materia con ID {$datos['id']}");
                }
            }
            
            if ($esNueva) {
                // INSERT de nueva materia
                $query = "INSERT INTO materias 
                         (cod_materia, nombre_materia, creditos, horas_teoricas, horas_practicas, horas_laboratorio, horas_semanales, activa) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                
                if (!$stmt) {
                    throw new Exception("Error preparando INSERT: ".$db->error);
                }
                
                $horas_laboratorio = $datos['horas_laboratorio'] ?? 0;
                $horas_semanales = $datos['horas_semanales'] ?? 0;

                $stmt->bind_param("ssiiiiii", 
                    $datos['cod_materia'],
                    $datos['nombre_materia'],
                    $datos['creditos'],
                    $datos['horas_teoricas'],
                    $datos['horas_practicas'],
                    $horas_laboratorio,
                    $horas_semanales,
                    $datos['activa']);
            } else {
                // UPDATE de materia existente
                $query = "UPDATE materias SET 
                         cod_materia = ?, 
                         nombre_materia = ?, 
                         creditos = ?, 
                         horas_teoricas = ?, 
                         horas_practicas = ?, 
                         horas_laboratorio = ?,
                         horas_semanales = ?,
                         activa = ? 
                         WHERE id = ?";
                $stmt = $db->prepare($query);
                
                if (!$stmt) {
                    throw new Exception("Error preparando UPDATE: ".$db->error);
                }
                
                $horas_laboratorio = $datos['horas_laboratorio'] ?? 0;
                $horas_semanales = $datos['horas_semanales'] ?? 0;

                $stmt->bind_param("ssiiiiiii", 
                    $datos['cod_materia'],
                    $datos['nombre_materia'],
                    $datos['creditos'],
                    $datos['horas_teoricas'],
                    $datos['horas_practicas'],
                    $horas_laboratorio,
                    $horas_semanales,
                    $datos['activa'],
                    $datos['id']);
            }
            
            // Ejecutar la consulta
            if (!$stmt->execute()) {
                throw new Exception("Error ejecutando consulta: ".$stmt->error);
            }
            
            // Obtener información del resultado
            $affectedRows = $stmt->affected_rows;
            $nuevoId = $esNueva ? $stmt->insert_id : null;
            
            $stmt->close();
            
            // Verificar si realmente se afectaron filas (especialmente en UPDATE)
            if (!$esNueva && $affectedRows === 0) {
                return [
                    'success' => false,
                    'message' => "No se encontró la materia con ID {$datos['id']} o no hubo cambios",
                    'affected_rows' => 0
                ];
            }
            
            // REGISTRAR EN AUDITORÍA
            if (function_exists('registrarAuditoria')) {
                try {
                    if ($esNueva) {
                        // Auditoría para nueva materia
                        registrarAuditoria(
                            "INSERT", 
                            "materias", 
                            $nuevoId, 
                            null, 
                            [
                                'cod_materia' => $datos['cod_materia'],
                                'nombre_materia' => $datos['nombre_materia'],
                                'creditos' => $datos['creditos'],
                                'horas_teoricas' => $datos['horas_teoricas'],
                                'horas_practicas' => $datos['horas_practicas'],
                                'horas_laboratorio' => $datos['horas_laboratorio'] ?? 0,
                                'horas_semanales' => $datos['horas_semanales'] ?? 0,
                                'activa' => $datos['activa']
                            ], 
                            "Materias", 
                            "Nueva materia creada"
                        );
                    } else {
                        // Auditoría para actualización
                        $valores_antiguos_audit = [];
                        $valores_nuevos_audit = [];
                        
                        $campos_auditar = ['cod_materia', 'nombre_materia', 'creditos', 'horas_teoricas', 'horas_practicas', 'horas_laboratorio', 'horas_semanales', 'activa'];
                        
                        foreach ($campos_auditar as $campo) {
                            $valor_antiguo = $datos_actuales[$campo] ?? null;
                            $valor_nuevo = $datos[$campo] ?? null;
                            
                            if ($valor_antiguo != $valor_nuevo) {
                                $valores_antiguos_audit[$campo] = $valor_antiguo;
                                $valores_nuevos_audit[$campo] = $valor_nuevo;
                            }
                        }
                        
                        if (!empty($valores_nuevos_audit)) {
                            registrarAuditoria(
                                "UPDATE", 
                                "materias", 
                                $datos['id'], 
                                $valores_antiguos_audit, 
                                $valores_nuevos_audit, 
                                "Materias", 
                                "Actualización de datos de materia"
                            );
                        }
                    }
                } catch (Exception $e) {
                    error_log("Error en auditoría guardarMateria: " . $e->getMessage());
                }
            }
            
            // Retornar resultado exitoso
            return [
                'success' => true,
                'message' => $esNueva ? "Materia creada exitosamente" : "Materia actualizada exitosamente",
                'affected_rows' => $affectedRows,
                'id' => $esNueva ? $nuevoId : $datos['id']
            ];
            
        } catch (Exception $e) {
            error_log("Excepción en guardarMateria: ".$e->getMessage());
            
            // REGISTRAR EN AUDITORÍA - ERROR
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "ERROR", 
                        "materias", 
                        $datos['id'] ?? null, 
                        null, 
                        [
                            'cod_materia' => $datos['cod_materia'] ?? '',
                            'nombre_materia' => $datos['nombre_materia'] ?? '',
                            'error' => $e->getMessage()
                        ], 
                        "Materias", 
                        "Error al guardar materia"
                    );
                } catch (Exception $auditError) {
                    error_log("Error en auditoría de error guardarMateria: " . $auditError->getMessage());
                }
            }
            
            if (isset($stmt) && $stmt instanceof mysqli_stmt) {
                $stmt->close();
            }
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}












//CAMBIAR A USERS

function editarDocente($datos) {
    global $db;
    try {
        // Validar campos requeridos (sin status)
        $required = ['nombre', 'username', 'email', 'tlf', 'genero', 'id'];
        foreach ($required as $field) {
            if (empty($datos[$field])) {
                throw new Exception("El campo $field es requerido");
            }
        }

        // Validar formato de email
        if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El formato del email es inválido");
        }

        // Preparar consulta (sin status)
        $stmt = $db->prepare("UPDATE users SET 
            nombre = ?,
            username = ?,
            email = ?,
            tlf = ?,
            cel = ?,
            genero = ?,
            direccion = ?,
            ciudad = ?,
            estado = ?,
            fecha_nac = ?,
            fecha_act = NOW()
            WHERE id = ?");

        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $db->error);
        }

        // Asignar valores por defecto a campos opcionales
        $datos['cel'] = $datos['cel'] ?? '';
        $datos['direccion'] = $datos['direccion'] ?? '';
        $datos['ciudad'] = $datos['ciudad'] ?? '';
        $datos['estado'] = $datos['estado'] ?? '';
        $datos['fecha_nac'] = $datos['fecha_nac'] ?? null;

        // Bind parameters (sin status)
        $stmt->bind_param(
            "ssssssssssi", // 10 's' y 1 'i' al final (id)
            $datos['nombre'],
            $datos['username'],
            $datos['email'],
            $datos['tlf'],
            $datos['cel'],
            $datos['genero'],
            $datos['direccion'],
            $datos['ciudad'],
            $datos['estado'],
            $datos['fecha_nac'],
            $datos['id']
        );

        $resultado = $stmt->execute();
        
        if (!$resultado) {
            throw new Exception("Error en la ejecución: " . $stmt->error);
        }

        $stmt->close();
        
        return [
            'success' => true,
            'message' => 'Docente actualizado correctamente',
            'affected_rows' => $db->affected_rows
        ];

    } catch (Exception $e) {
        error_log("Error en editarDocente: " . $e->getMessage());
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}


/**
* Deshabilita un docente cambiando su estado a Inactivo
* @param int $id ID del docente a deshabilitar
* @param string $razon Razón por la que se deshabilita
* @return bool True si se deshabilitó correctamente, False si hubo error
*/
function deshabilitarDocente($id, $razon) {
  global $db;
  
  try {
      // Actualizar estado
      $stmt = $db->prepare("UPDATE docentes SET estado = 'Inactivo' WHERE id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      
      // Registrar en historial (opcional)
      $stmt = $db->prepare("INSERT INTO historial_deshabilitaciones 
                          (docente_id, razon, fecha) VALUES (?, ?, NOW())");
      $stmt->bind_param("is", $id, $razon);
      $stmt->execute();
      
      return true;
  } catch (Exception $e) {
      error_log("Error al deshabilitar docente: " . $e->getMessage());
      return false;
  }
}




//LO DE ARRIBA HAY QUE ARREGLARLO



//PAGOS 


// ==============================================
// ARCHIVO: funciones/functions.php
// Funciones para edición y eliminación de pagos
// ==============================================

/**
 * Obtener un pago específico por ID
 */
function obtenerPagoPorId($pago_id) {
    global $db;
    
    $query = "SELECT p.*, u.nombre as nombre_estudiante, u.idusuario as cedula, 
                     tp.tipopago as nombre_tipo_pago,
                     ur.nombre as nombre_registrador
              FROM pagos p
              INNER JOIN users u ON p.estudiante_id = u.id
              INNER JOIN tipo_pago tp ON p.tipo_pago = tp.id
              INNER JOIN users ur ON p.registrado_por = ur.id
              WHERE p.id = ?";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $pago_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

/**
 * Actualizar un pago existente
 */
function actualizarPago($pago_id, $tipo_pago, $otro_concepto, $monto, $observaciones) {
    global $db;
    
    // Primero obtener los valores antiguos para auditoría
    $pago_antiguo = obtenerPagoPorId($pago_id);
    
    $query = "UPDATE pagos 
              SET tipo_pago = ?, otro_concepto = ?, monto = ?, observaciones = ?
              WHERE id = ?";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("isdsi", $tipo_pago, $otro_concepto, $monto, $observaciones, $pago_id);
    
    if ($stmt->execute()) {
        // Registrar en auditoría
        $valores_antiguos = [
            'tipo_pago' => $pago_antiguo['tipo_pago'],
            'otro_concepto' => $pago_antiguo['otro_concepto'],
            'monto' => $pago_antiguo['monto'],
            'observaciones' => $pago_antiguo['observaciones']
        ];
        
        $valores_nuevos = [
            'tipo_pago' => $tipo_pago,
            'otro_concepto' => $otro_concepto,
            'monto' => $monto,
            'observaciones' => $observaciones
        ];
        
        registrarAuditoria(
            "UPDATE", 
            "pagos", 
            $pago_id, 
            $valores_antiguos, 
            $valores_nuevos, 
            "Pagos", 
            "Actualización de pago"
        );
        
        return true;
    }
    
    return false;
}

/**
 * Eliminar un pago
 */
function eliminarPago($pago_id) {
    global $db;
    
    // Primero obtener los valores para auditoría
    $pago = obtenerPagoPorId($pago_id);
    
    $query = "DELETE FROM pagos WHERE id = ?";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $pago_id);
    
    if ($stmt->execute()) {
        // Registrar en auditoría
        $valores_antiguos = [
            'estudiante_id' => $pago['estudiante_id'],
            'tipo_pago' => $pago['tipo_pago'],
            'otro_concepto' => $pago['otro_concepto'],
            'monto' => $pago['monto'],
            'observaciones' => $pago['observaciones'],
            'fecha_pago' => $pago['fecha_pago'],
            'registrado_por' => $pago['registrado_por']
        ];
        
        registrarAuditoria(
            "DELETE", 
            "pagos", 
            $pago_id, 
            $valores_antiguos, 
            null, 
            "Pagos", 
            "Eliminación de pago"
        );
        
        return true;
    }
    
    return false;
}


// Función para obtener pagos por día
function obtenerPagosPorDia() {
    global $db;
    
    $sql = "SELECT DATE(fecha_pago) as dia, 
                   COUNT(*) as cantidad_pagos, 
                   SUM(monto) as total_dia 
            FROM pagos 
            GROUP BY DATE(fecha_pago) 
            ORDER BY dia DESC 
            LIMIT 30";
    
    $result = mysqli_query($db, $sql);
    
    if (!$result) {
        return ['success' => false, 'message' => 'Error al obtener pagos: ' . mysqli_error($db)];
    }
    
    $pagos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $pagos[] = $row;
    }
    
    return ['success' => true, 'data' => $pagos];
}



// Función para obtener detalles de pagos por día (CORREGIDA)
function obtenerDetallesPagos($dia) {
    global $db;
    
    $sql = "SELECT p.*, 
                   u.nombre as estudiante_nombre, 
                   u.idusuario as estudiante_cedula
            FROM pagos p 
            LEFT JOIN users u ON p.estudiante_id = u.id 
            WHERE DATE(p.fecha_pago) = ? 
            ORDER BY p.fecha_pago DESC";
    
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $dia);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (!$result) {
        return ['success' => false, 'message' => 'Error al obtener detalles: ' . mysqli_error($db)];
    }
    
    $detalles = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $detalles[] = $row;
    }
    
    return ['success' => true, 'data' => $detalles];
}

/**
 * Busca estudiantes EXCLUSIVAMENTE por cédula (idusuario)
 * @param string $cedula Cédula a buscar
 * @return array Datos del estudiante encontrado
 * @throws Exception Si ocurre un error
 */
function buscarEstudiantePorCedula($cedula) {
    global $db;
    
    try {
        // Consulta mejorada con más campos relevantes
        $query = "SELECT 
                    id, 
                    idusuario AS cedula, 
                    nombre,
                    carrera,
                    email,
                    tlf,
                    cel
                  FROM users 
                  WHERE idusuario LIKE CONCAT('%', ?, '%')
                  AND estudiante = 1
                  ORDER BY idusuario ASC
                  LIMIT 10";
        
        $stmt = $db->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $db->error);
        }
        
        $stmt->bind_param("s", $cedula);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $estudiantes = [];

        while ($row = $result->fetch_assoc()) {
            $estudiantes[] = [
                'id' => (int)$row['id'],
                'idusuario' => $row['cedula'],
                'cedula' => $row['cedula'],
                'nombre' => $row['nombre'],
                'carrera' => $row['carrera'] ?? 'No especificado',
                'contacto' => $row['cel'] ?: ($row['tlf'] ?: 'Sin teléfono'),
                'email' => $row['email'] ?? 'Sin email'
            ];
        }

        $stmt->close();

        // Compatibilidad: si sólo se encontró 1 estudiante, devolverlo como asociativo
        if (count($estudiantes) === 1) {
            return $estudiantes[0];
        }

        // Si hay múltiples resultados, devolver el arreglo
        return $estudiantes;
        
    } catch (Exception $e) {
        error_log("Error en buscarEstudiantePorCedula: " . $e->getMessage());
        throw new Exception("Error al buscar por cédula");
    }
}



//USERS

/**
 * Obtiene la lista completa de usuarios de la base de datos usando sentencias preparadas
 * 
 * @return array Array asociativo con todos los usuarios ordenados por nombre
 */
function obtenerListaCompletaUsuarios() {
  global $db; // Asume que $db es una conexión MySQLi válida
  
  // Preparar la consulta SQL
  $query = "SELECT id, nombre, username, email, status, 
                   super_user, admin, docente, estudiante, editar_user 
            FROM users 
            ORDER BY nombre ASC";
  
  // Preparar la sentencia
  $stmt = $db->prepare($query);
  
  if (!$stmt) {
      // Manejar error en la preparación
      die("Error al preparar la consulta: " . $db->error);
  }
  
  // Ejecutar la consulta
  $stmt->execute();
  
  // Obtener el resultado
  $result = $stmt->get_result();
  
  // Inicializar array para los usuarios
  $usuarios = array();
  
  // Verificar si hay resultados
  if ($result && $result->num_rows > 0) {
      // Recorrer todos los registros
      while ($row = $result->fetch_assoc()) {
          $usuarios[] = $row;
      }
  }
  
  // Cerrar la sentencia
  $stmt->close();
  
  return $usuarios;
}


//DATOS PREDEFINIDOS****************************************************************************************


/**
 * Genera el HTML para un select de tipos de formación
 * @param string $name Nombre del campo select
 * @param int|null $selected_id ID del tipo seleccionado (opcional)
 * @param string $class Clases CSS adicionales (opcional)
 * @return string HTML del select
 */
function selectTiposFormacion($name = 'tipo_formacion', $selected_id = null, $class = 'form-control') {
    global $db;
    $html = '<select class="'.$class.'" id="'.$name.'" name="'.$name.'" required>';
    $html .= '<option value="">Seleccione un tipo de formación</option>';
    
    $query = "SELECT id, tipo FROM tipo_formacion ORDER BY tipo";
    $result = $db->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $selected = ($selected_id == $row['id']) ? 'selected' : '';
            $html .= '<option value="'.htmlspecialchars($row['id']).'" '.$selected.'>'
                   . htmlspecialchars($row['tipo']) . '</option>';
        }
        $result->free();
    }
    
    $html .= '</select>';
    return $html;
}

function obtenerTiposFormacion($db) {
    $tipos = [];
    $query = "SELECT id, tipo FROM tipo_formacion ORDER BY id";
    $result = $db->query($query);
    while ($row = $result->fetch_assoc()) {
        $tipos[$row['id']] = $row['tipo'];
    }
    return $tipos;
}

function obtenerGeneros($db) {
    $generos = [];
    $query = "SELECT id, genero FROM genero ORDER BY id";
    
    try {
        $result = $db->query($query);
        if (!$result) {
            throw new Exception("Error en la consulta: " . $db->error);
        }
        
        while ($row = $result->fetch_assoc()) {
            $generos[$row['id']] = $row['genero'];
        }
        
        return $generos;
    } catch (Exception $e) {
        error_log("Error en obtenerGeneros: " . $e->getMessage());
        return [];
    }
}

function obtenerTiposCedula($db) {
    $tipos = [];
    
    if (!($db instanceof mysqli)) {
        throw new InvalidArgumentException("Se esperaba una conexión MySQLi válida");
    }
    
    $query = "SELECT id, tipo FROM tipo_cedula";
    
    try {
        if (!$stmt = $db->prepare($query)) {
            throw new Exception("Error al preparar la consulta: " . $db->error);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $tipos[] = $row;
        }
        
        $stmt->close();
        
        return $tipos;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [];
    }
}

function obtenerEstadosCiviless($db) {
    if (!($db instanceof mysqli)) {
        throw new InvalidArgumentException("Se esperaba una conexión MySQLi válida");
    }

    $estados = [];
    $query = "SELECT id, estado_civil FROM estado_civil ORDER BY estado_civil ASC";
    
    try {
        if (!$stmt = $db->prepare($query)) {
            throw new Exception("Error al preparar la consulta: " . $db->error);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $estados[$row['id']] = $row['estado_civil'];
        }
        
        return $estados;
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [];
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}

function obtenerTiposVivienda($db) {
    if (!($db instanceof mysqli)) {
        throw new InvalidArgumentException("Se esperaba una conexión MySQLi válida");
    }

    $viviendas = [];
    $query = "SELECT id, vivienda FROM tipo_vivienda ORDER BY vivienda ASC";
    
    try {
        if (!$stmt = $db->prepare($query)) {
            throw new Exception("Error al preparar la consulta: " . $db->error);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $viviendas[$row['id']] = $row['vivienda'];
        }
        
        return $viviendas;
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [];
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}

function obtenerTenenciaViviendas($db) {
    if (!($db instanceof mysqli)) {
        throw new InvalidArgumentException("Se esperaba una conexión MySQLi válida");
    }

    $tenencias = [];
    $query = "SELECT id, tenencia FROM tenencia_vivienda ORDER BY tenencia ASC";
    
    try {
        if (!$stmt = $db->prepare($query)) {
            throw new Exception("Error al preparar la consulta: " . $db->error);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $tenencias[$row['id']] = $row['tenencia'];
        }
        
        return $tenencias;
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [];
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}

function obtenerOpcionesStatus($db) {
    if (!($db instanceof mysqli)) {
        throw new InvalidArgumentException("Se esperaba una conexión MySQLi válida");
    }

    $statusOptions = [];
    $query = "SELECT id, status FROM status ORDER BY id ASC";
    try {
        if ($stmt = $db->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $statusOptions[$row['id']] = $row['status'];
            }
            $stmt->close();
        }
        return $statusOptions;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [];
    }
}

function obtenerIngresos($db) {
    $ingresos = [];
    $query = "SELECT id, ingreso FROM ingresos ORDER BY id";
    $result = $db->query($query);
    
    while ($row = $result->fetch_assoc()) {
        $ingresos[$row['id']] = $row['ingreso'];
    }
    
    return $ingresos;
}

// Función para procesar operaciones CRUD en datos predefinidos - CON AUDITORÍA
function procesarOperacionDatosPredefinidos($tabla, $accion, $id = null, $nuevo_id = null, $valor = '') {
    global $db;
    
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
    
    if (!array_key_exists($tabla, $tablasCampos)) {
        return ['success' => false, 'message' => 'Tabla no válida'];
    }
    
    $campo = $tablasCampos[$tabla];
    $valor = trim($valor);
    
    try {
        switch ($accion) {
            case 'agregar':
                if (empty($valor)) {
                    return ['success' => false, 'message' => 'El valor no puede estar vacío'];
                }
                
                if (!empty($nuevo_id)) {
                    // Verificar si el ID ya existe
                    $check = $db->prepare("SELECT id FROM $tabla WHERE id = ?");
                    $check->bind_param("i", $nuevo_id);
                    $check->execute();
                    $check->store_result();
                    
                    if ($check->num_rows > 0) {
                        return ['success' => false, 'message' => "Error: El ID $nuevo_id ya existe"];
                    }
                    
                    $stmt = $db->prepare("INSERT INTO $tabla (id, $campo) VALUES (?, ?)");
                    $stmt->bind_param("is", $nuevo_id, $valor);
                } else {
                    $stmt = $db->prepare("INSERT INTO $tabla ($campo) VALUES (?)");
                    $stmt->bind_param("s", $valor);
                }
                
                if ($stmt->execute()) {
                    $id_insertado = $nuevo_id ?: $db->insert_id;
                    
                    // REGISTRAR EN AUDITORÍA - REGISTRO AGREGADO
                    if (function_exists('registrarAuditoria')) {
                        try {
                            registrarAuditoria(
                                "INSERT", 
                                $tabla, 
                                $id_insertado, 
                                null, 
                                [
                                    'tabla' => $tabla,
                                    'campo' => $campo,
                                    'id' => $id_insertado,
                                    'valor' => $valor,
                                    'accion' => 'agregar',
                                    'usuario' => $_SESSION['user']['username'] ?? 'Desconocido',
                                    'usuario_id' => $_SESSION['user']['id'] ?? 0
                                ], 
                                "Datos Predefinidos", 
                                "Registro agregado en $tabla: $valor (ID: $id_insertado)"
                            );
                        } catch (Exception $e) {
                            error_log("Error en auditoría procesarOperacionDatosPredefinidos (agregar): " . $e->getMessage());
                        }
                    }
                    
                    return ['success' => true, 'message' => 'Registro agregado correctamente'];
                } else {
                    return ['success' => false, 'message' => 'Error al agregar registro: ' . $stmt->error];
                }
                break;
                
            case 'editar':
                if (($id === '' || $id === null) || $valor === '') {
                    return ['success' => false, 'message' => 'ID y valor son requeridos'];
                }
                
                // Obtener valor anterior para auditoría
                $stmt_actual = $db->prepare("SELECT $campo FROM $tabla WHERE id = ?");
                $stmt_actual->bind_param("i", $id);
                $stmt_actual->execute();
                $result_actual = $stmt_actual->get_result();
                
                if ($result_actual->num_rows === 0) {
                    return ['success' => false, 'message' => 'Registro no encontrado'];
                }
                
                $valor_anterior = $result_actual->fetch_assoc()[$campo];
                $stmt_actual->close();
                
                if ($nuevo_id != $id) {
                    // Verificar si el nuevo ID ya existe
                    $check = $db->prepare("SELECT id FROM $tabla WHERE id = ? AND id != ?");
                    $check->bind_param("ii", $nuevo_id, $id);
                    $check->execute();
                    $check->store_result();
                    
                    if ($check->num_rows > 0) {
                        return ['success' => false, 'message' => "Error: El ID $nuevo_id ya existe"];
                    }
                    
                    $stmt = $db->prepare("UPDATE $tabla SET id = ?, $campo = ? WHERE id = ?");
                    $stmt->bind_param("isi", $nuevo_id, $valor, $id);
                } else {
                    $stmt = $db->prepare("UPDATE $tabla SET $campo = ? WHERE id = ?");
                    $stmt->bind_param("si", $valor, $id);
                }
                
                if ($stmt->execute()) {
                    $id_final = $nuevo_id ?: $id;
                    
                    // REGISTRAR EN AUDITORÍA - REGISTRO EDITADO
                    if (function_exists('registrarAuditoria')) {
                        try {
                            $cambios = [];
                            if ($nuevo_id && $nuevo_id != $id) {
                                $cambios[] = "ID: $id → $nuevo_id";
                            }
                            if ($valor_anterior != $valor) {
                                $cambios[] = "$campo: $valor_anterior → $valor";
                            }
                            
                            registrarAuditoria(
                                "UPDATE", 
                                $tabla, 
                                $id_final, 
                                [
                                    'id_anterior' => $id,
                                    'valor_anterior' => $valor_anterior
                                ], 
                                [
                                    'tabla' => $tabla,
                                    'campo' => $campo,
                                    'id_nuevo' => $id_final,
                                    'valor_nuevo' => $valor,
                                    'accion' => 'editar',
                                    'cambios' => implode(', ', $cambios),
                                    'usuario' => $_SESSION['user']['username'] ?? 'Desconocido',
                                    'usuario_id' => $_SESSION['user']['id'] ?? 0
                                ], 
                                "Datos Predefinidos", 
                                "Registro editado en $tabla: " . implode(', ', $cambios)
                            );
                        } catch (Exception $e) {
                            error_log("Error en auditoría procesarOperacionDatosPredefinidos (editar): " . $e->getMessage());
                        }
                    }
                    
                    return ['success' => true, 'message' => 'Registro actualizado correctamente'];
                } else {
                    return ['success' => false, 'message' => 'Error al actualizar registro: ' . $stmt->error];
                }
                break;
                
            case 'eliminar':
                if ($id === '' || $id === null) {
                    return ['success' => false, 'message' => 'ID es requerido'];
                }
                
                // Obtener datos del registro para auditoría
                $stmt_actual = $db->prepare("SELECT $campo FROM $tabla WHERE id = ?");
                $stmt_actual->bind_param("i", $id);
                $stmt_actual->execute();
                $result_actual = $stmt_actual->get_result();
                
                if ($result_actual->num_rows === 0) {
                    return ['success' => false, 'message' => 'Registro no encontrado'];
                }
                
                $valor_eliminado = $result_actual->fetch_assoc()[$campo];
                $stmt_actual->close();
                
                $stmt = $db->prepare("DELETE FROM $tabla WHERE id = ?");
                $stmt->bind_param("i", $id);
                
                if ($stmt->execute()) {
                    // REGISTRAR EN AUDITORÍA - REGISTRO ELIMINADO
                    if (function_exists('registrarAuditoria')) {
                        try {
                            registrarAuditoria(
                                "DELETE", 
                                $tabla, 
                                $id, 
                                [
                                    'tabla' => $tabla,
                                    'campo' => $campo,
                                    'id' => $id,
                                    'valor' => $valor_eliminado
                                ], 
                                [
                                    'accion' => 'eliminar',
                                    'usuario' => $_SESSION['user']['username'] ?? 'Desconocido',
                                    'usuario_id' => $_SESSION['user']['id'] ?? 0
                                ], 
                                "Datos Predefinidos", 
                                "Registro eliminado de $tabla: $valor_eliminado (ID: $id)"
                            );
                        } catch (Exception $e) {
                            error_log("Error en auditoría procesarOperacionDatosPredefinidos (eliminar): " . $e->getMessage());
                        }
                    }
                    
                    return ['success' => true, 'message' => 'Registro eliminado correctamente'];
                } else {
                    return ['success' => false, 'message' => 'Error al eliminar registro: ' . $stmt->error];
                }
                break;
                
            default:
                return ['success' => false, 'message' => 'Acción no válida'];
        }
    } catch (Exception $e) {
        error_log("Error en procesarOperacionDatosPredefinidos: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR EN OPERACIÓN
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    $tabla, 
                    $id, 
                    null, 
                    [
                        'tabla' => $tabla,
                        'accion' => $accion,
                        'id' => $id,
                        'nuevo_id' => $nuevo_id,
                        'valor' => $valor,
                        'error' => $e->getMessage(),
                        'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                    ], 
                    "Datos Predefinidos", 
                    "Error en operación $accion en tabla $tabla"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error procesarOperacionDatosPredefinidos: " . $auditError->getMessage());
            }
        }
        
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }
}

// Función para verificar si un ID existe en una tabla - SOLO LECTURA, SIN AUDITORÍA
function verificarIdExistente($tabla, $id) {
    global $db;
    
    $stmt = $db->prepare("SELECT id FROM $tabla WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    
    return $stmt->num_rows > 0;
}






//FUNCIONES PARA LAS SECCIONES ***********************************************************************


// Constante para el mínimo de estudiantes requeridos
define('MINIMO_ESTUDIANTES', 0);

/**
 * Crea una nueva sección en la base de datos
 * @param mysqli $db Conexión a la base de datos
 * @param array $datos Datos de la sección (codigo_seccion, id_carrera, id_trayecto, id_periodo, capacidad_maxima, turno, inicia)
 * @return array Resultado de la operación (éxito, mensaje)
 */
function crearSeccion($db, $datos) {
    try {
        // Verificar que los datos necesarios existan
        if (empty($datos['codigo_seccion'])) {
            throw new Exception('El código de sección es obligatorio');
        }
        if (empty($datos['id_carrera'])) {
            throw new Exception('La carrera es obligatoria');
        }
        if (empty($datos['id_trayecto'])) {
            throw new Exception('El trayecto es obligatorio');
        }
        if (empty($datos['id_periodo'])) {
            throw new Exception('El período es obligatorio');
        }
        if (empty($datos['turno'])) {
            throw new Exception('El turno es obligatorio');
        }
        if (empty($datos['inicia'])) {
            throw new Exception('La fecha de inicio es obligatoria');
        }
        
        // Capacidad máxima por defecto 30 si no viene
        $capacidad_maxima = isset($datos['capacidad_maxima']) ? (int)$datos['capacidad_maxima'] : 30;
        
        // INSERT con 7 campos (sin status porque se maneja aparte)
        $stmt = $db->prepare("INSERT INTO secciones (codigo_seccion, id_carrera, id_trayecto, id_periodo, capacidad_maxima, turno, inicia, estatus) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, 'inactiva')");
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        // bind_param: 7 variables, 7 tipos (siiii s s? No: s=string, i=integer)
        // Orden: codigo(s), id_carrera(i), id_trayecto(i), id_periodo(i), capacidad_maxima(i), turno(s), inicia(s)
        // Tipos: "siiiiss" (7 caracteres)
        $codigo = $datos['codigo_seccion'];
        $id_carrera = $datos['id_carrera'];
        $id_trayecto = $datos['id_trayecto'];
        $id_periodo = $datos['id_periodo'];
        $turno = $datos['turno'];
        $inicia = $datos['inicia'];
        
        $stmt->bind_param("siiiiss", 
            $codigo, 
            $id_carrera, 
            $id_trayecto, 
            $id_periodo, 
            $capacidad_maxima,
            $turno,
            $inicia
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
        $seccion_id = $stmt->insert_id;
        $stmt->close();
        
        // REGISTRAR EN AUDITORÍA - NUEVA SECCIÓN CREADA
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "INSERT", 
                    "secciones", 
                    $seccion_id, 
                    null, 
                    [
                        'codigo_seccion' => $codigo,
                        'id_carrera' => $id_carrera,
                        'id_trayecto' => $id_trayecto,
                        'id_periodo' => $id_periodo,
                        'capacidad_maxima' => $capacidad_maxima,
                        'turno' => $turno,
                        'inicia' => $inicia,
                        'estatus' => 'activa'
                    ], 
                    "Secciones", 
                    "Creación de nueva sección"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría crearSeccion: " . $e->getMessage());
            }
        }
        
        return [
            'success' => true,
            'message' => "Sección creada exitosamente!",
            'id_seccion' => $seccion_id
        ];
    } catch (Exception $e) {
        error_log("Error en crearSeccion: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL CREAR SECCIÓN
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "secciones", 
                    null, 
                    null, 
                    [
                        'codigo_seccion' => $datos['codigo_seccion'] ?? '',
                        'error' => $e->getMessage()
                    ], 
                    "Secciones", 
                    "Error al crear sección"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error crearSeccion: " . $auditError->getMessage());
            }
        }
        
        return [
            'success' => false,
            'message' => "Error al crear sección: " . $e->getMessage()
        ];
    }
}

function editarSeccion($db, $datos) {
    try {
        // Obtener datos actuales para auditoría
        $datos_antiguos = obtenerDatosSeccion($db, $datos['id_seccion']);
        
        $stmt = $db->prepare("UPDATE secciones 
                            SET codigo_seccion = ?, 
                                id_carrera = ?, 
                                id_trayecto = ?, 
                                id_periodo = ?, 
                                capacidad_maxima = ?,
                                turno = ?,
                                inicia = ?
                            WHERE id_seccion = ?");
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $codigo = $datos['codigo_seccion'];
        $id_carrera = $datos['id_carrera'];
        $id_trayecto = $datos['id_trayecto'];
        $id_periodo = $datos['id_periodo'];
        $capacidad_maxima = isset($datos['capacidad_maxima']) ? (int)$datos['capacidad_maxima'] : 30;
        $turno = $datos['turno'];
        $inicia = $datos['inicia'];
        $id_seccion = $datos['id_seccion'];
        
        // 8 variables, 8 tipos: s i i i i s s i
        $stmt->bind_param("siiiissi", 
            $codigo, 
            $id_carrera, 
            $id_trayecto, 
            $id_periodo, 
            $capacidad_maxima,
            $turno,
            $inicia,
            $id_seccion
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        
        // REGISTRAR EN AUDITORÍA - EDICIÓN DE SECCIÓN
        if ($affected_rows > 0 && function_exists('registrarAuditoria')) {
            try {
                $valores_antiguos_audit = [];
                $valores_nuevos_audit = [];
                
                $campos_auditar = ['codigo_seccion', 'id_carrera', 'id_trayecto', 'id_periodo', 'capacidad_maxima', 'turno', 'inicia'];
                
                foreach ($campos_auditar as $campo) {
                    $valor_antiguo = $datos_antiguos[$campo] ?? null;
                    $valor_nuevo = $datos[$campo] ?? null;
                    
                    if ($valor_antiguo != $valor_nuevo) {
                        $valores_antiguos_audit[$campo] = $valor_antiguo;
                        $valores_nuevos_audit[$campo] = $valor_nuevo;
                    }
                }
                
                if (!empty($valores_nuevos_audit)) {
                    registrarAuditoria(
                        "UPDATE", 
                        "secciones", 
                        $datos['id_seccion'], 
                        $valores_antiguos_audit, 
                        $valores_nuevos_audit, 
                        "Secciones", 
                        "Edición de datos de sección"
                    );
                }
            } catch (Exception $e) {
                error_log("Error en auditoría editarSeccion: " . $e->getMessage());
            }
        }
        
        return [
            'success' => true,
            'message' => "Sección actualizada exitosamente!",
            'affected_rows' => $affected_rows
        ];
    } catch (Exception $e) {
        error_log("Error en editarSeccion: " . $e->getMessage());
        
        return [
            'success' => false,
            'message' => "Error al actualizar sección: " . $e->getMessage()
        ];
    }
}

/**
 * Asigna estudiantes a una sección
 * @param mysqli $db Conexión a la base de datos
 * @param int $seccion_id ID de la sección
 * @param array $estudiantes IDs de estudiantes a asignar
 * @return array Resultado de la operación (éxito, mensaje, warning)
 */
function asignarEstudiantes($db, $seccion_id, $estudiantes) {
    try {
        $db->begin_transaction();
        
        // Obtener información de la sección y período
        $seccion = obtenerInfoSeccionConPeriodo($db, $seccion_id);
        if (!$seccion) {
            throw new Exception("Sección no encontrada.");
        }
        
        // Verificar si el período está activo
        if ($seccion['periodo_activo'] == 0) {
            throw new Exception("No se pueden asignar estudiantes a una sección con período inactivo.");
        }
        
        $capacidad_maxima = $seccion['capacidad_maxima'];
        
        // Obtener estudiantes actualmente asignados
        $asignados_actuales = obtenerEstudiantesAsignados($db, $seccion_id);
        
        // Estudiantes a desactivar (estaban asignados pero no están en la nueva selección)
        $desactivar = array_diff($asignados_actuales, $estudiantes);
        
        // Estudiantes a activar (nuevos o que ya estaban)
        $activar = $estudiantes;
        
        // Verificar capacidad
        $nuevos_estudiantes = array_diff($activar, $asignados_actuales);
        $total_estudiantes = count($asignados_actuales) - count($desactivar) + count($nuevos_estudiantes);
        
        if ($total_estudiantes > $capacidad_maxima) {
            throw new Exception("No se pueden asignar más estudiantes. La capacidad máxima es $capacidad_maxima.");
        }
        
        // Desactivar los que ya no están seleccionados
        if (!empty($desactivar)) {
            desactivarEstudiantes($db, $seccion_id, $desactivar);
        }
        
        // Activar o insertar nuevos estudiantes
        if (!empty($activar)) {
            activarEstudiantes($db, $seccion_id, $activar);
        }
        
        // Actualizar estado de la sección según el número de estudiantes
        $count = contarEstudiantesActivos($db, $seccion_id);
        actualizarEstadoSeccion($db, $seccion_id, $count);
        
        $db->commit();
        
        // REGISTRAR EN AUDITORÍA - ASIGNACIÓN DE ESTUDIANTES
        if (function_exists('registrarAuditoria')) {
            try {
                $estudiantes_agregados = count($nuevos_estudiantes);
                $estudiantes_retirados = count($desactivar);
                
                registrarAuditoria(
                    "UPDATE", 
                    "estudiante_seccion", 
                    $seccion_id, 
                    [
                        'estudiantes_anteriores' => count($asignados_actuales),
                        'estudiantes_retirados' => $estudiantes_retirados
                    ], 
                    [
                        'estudiantes_nuevos' => $estudiantes_agregados,
                        'estudiantes_totales' => $count,
                        'estudiantes_asignados' => $estudiantes
                    ], 
                    "Secciones", 
                    "Asignación de estudiantes a sección"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría asignarEstudiantes: " . $e->getMessage());
            }
        }
        
        $result = [
            'success' => true,
            'message' => "Asignación de estudiantes actualizada!",
            'estudiantes_agregados' => count($nuevos_estudiantes),
            'estudiantes_retirados' => count($desactivar),
            'total_estudiantes' => $count
        ];
        
        if ($count >= MINIMO_ESTUDIANTES) {
            $result['message'] .= " La sección ha sido activada al alcanzar el mínimo requerido.";
        } else {
            $result['warning'] = "La sección permanecerá inactiva hasta tener al menos ".MINIMO_ESTUDIANTES." estudiantes (actualmente tiene $count).";
        }
        
        return $result;
    } catch (Exception $e) {
        $db->rollback();
        error_log("Error en asignarEstudiantes: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL ASIGNAR ESTUDIANTES
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "estudiante_seccion", 
                    $seccion_id, 
                    null, 
                    [
                        'estudiantes_intentados' => $estudiantes,
                        'error' => $e->getMessage()
                    ], 
                    "Secciones", 
                    "Error al asignar estudiantes a sección"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error asignarEstudiantes: " . $auditError->getMessage());
            }
        }
        
        return [
            'success' => false,
            'message' => "Error al asignar estudiantes: " . $e->getMessage()
        ];
    }
}

/**
 * Retira un estudiante de una sección
 * @param mysqli $db Conexión a la base de datos
 * @param int $seccion_id ID de la sección
 * @param int $usuario_id ID del usuario (estudiante)
 * @return array Resultado de la operación (éxito, mensaje)
 */
function retirarEstudiante($db, $seccion_id, $usuario_id) {
    try {
        $db->begin_transaction();
        
        // Obtener información del estudiante antes de retirarlo
        $estudiante_info = null;
        if (function_exists('obtenerEstudiantePorId')) {
            $estudiante_info = obtenerEstudiantePorId($usuario_id);
        }
        
        // Desactivar al estudiante en la sección
        $stmt = $db->prepare("UPDATE estudiante_seccion 
                             SET estatus = 'retirado'
                             WHERE id_seccion = ? AND id_usuario = ?");
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("ii", $seccion_id, $usuario_id);
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        
        // Verificar si se debe cambiar el estado de la sección
        $count = contarEstudiantesActivos($db, $seccion_id);
        actualizarEstadoSeccion($db, $seccion_id, $count);
        
        $db->commit();
        
        // REGISTRAR EN AUDITORÍA - RETIRO DE ESTUDIANTE
        if ($affected_rows > 0 && function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "UPDATE", 
                    "estudiante_seccion", 
                    $seccion_id, 
                    [
                        'estudiante_id' => $usuario_id,
                        'estudiante_nombre' => $estudiante_info['nombre'] ?? 'Desconocido',
                        'estatus_anterior' => 'activo'
                    ], 
                    [
                        'estudiante_id' => $usuario_id,
                        'estatus_nuevo' => 'retirado',
                        'total_estudiantes' => $count
                    ], 
                    "Secciones", 
                    "Retiro de estudiante de sección"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría retirarEstudiante: " . $e->getMessage());
            }
        }
        
        return [
            'success' => true,
            'message' => "Estudiante retirado exitosamente de la sección.",
            'affected_rows' => $affected_rows
        ];
    } catch (Exception $e) {
        $db->rollback();
        error_log("Error en retirarEstudiante: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL RETIRAR ESTUDIANTE
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "estudiante_seccion", 
                    $seccion_id, 
                    null, 
                    [
                        'estudiante_id' => $usuario_id,
                        'error' => $e->getMessage()
                    ], 
                    "Secciones", 
                    "Error al retirar estudiante de sección"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error retirarEstudiante: " . $auditError->getMessage());
            }
        }
        
        return [
            'success' => false,
            'message' => "Error al retirar estudiante: " . $e->getMessage()
        ];
    }
}

/**
 * Obtiene información de sección con estado de período
 * @param mysqli $db Conexión a la base de datos
 * @param int $seccion_id ID de la sección
 * @return array|null Datos de la sección o null si no se encuentra
 */
function obtenerInfoSeccionConPeriodo($db, $seccion_id) {
    $stmt = $db->prepare("SELECT s.capacidad_maxima, p.activo as periodo_activo 
                         FROM secciones s
                         JOIN periodos_academicos p ON s.id_periodo = p.id_periodo
                         WHERE s.id_seccion = ?");
    if (!$stmt) {
        error_log("Error en preparación obtenerInfoSeccionConPeriodo: " . $db->error);
        return null;
    }
    
    $stmt->bind_param("i", $seccion_id);
    if (!$stmt->execute()) {
        error_log("Error en ejecución obtenerInfoSeccionConPeriodo: " . $stmt->error);
        return null;
    }
    
    $result = $stmt->get_result();
    $seccion = $result->fetch_assoc();
    $stmt->close();
    return $seccion;
}

/**
 * Obtiene los IDs de estudiantes asignados a una sección
 * @param mysqli $db Conexión a la base de datos
 * @param int $seccion_id ID de la sección
 * @return array IDs de estudiantes asignados
 */
function obtenerEstudiantesAsignados($db, $seccion_id) {
    $asignados = [];
    $stmt = $db->prepare("SELECT id_usuario FROM estudiante_seccion WHERE id_seccion = ? AND estatus = 'activo'");
    if (!$stmt) {
        error_log("Error en preparación obtenerEstudiantesAsignados: " . $db->error);
        return $asignados;
    }
    
    $stmt->bind_param("i", $seccion_id);
    if (!$stmt->execute()) {
        error_log("Error en ejecución obtenerEstudiantesAsignados: " . $stmt->error);
        return $asignados;
    }
    
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $asignados[] = $row['id_usuario'];
    }
    $stmt->close();
    return $asignados;
}

/**
 * Desactiva estudiantes de una sección
 * @param mysqli $db Conexión a la base de datos
 * @param int $seccion_id ID de la sección
 * @param array $estudiantes IDs de estudiantes a desactivar
 */
function desactivarEstudiantes($db, $seccion_id, $estudiantes) {
    if (empty($estudiantes)) return;
    
    $placeholders = implode(',', array_fill(0, count($estudiantes), '?'));
    $types = str_repeat('i', count($estudiantes));
    
    $stmt = $db->prepare("UPDATE estudiante_seccion 
                        SET estatus = 'retirado'
                        WHERE id_seccion = ? 
                        AND id_usuario IN ($placeholders)");
    if (!$stmt) {
        error_log("Error en preparación desactivarEstudiantes: " . $db->error);
        return;
    }
    
    $params = array_merge([$seccion_id], $estudiantes);
    $stmt->bind_param(str_repeat('i', count($params)), ...$params);
    if (!$stmt->execute()) {
        error_log("Error en ejecución desactivarEstudiantes: " . $stmt->error);
    }
    $stmt->close();
}

/**
 * Activa estudiantes en una sección
 * @param mysqli $db Conexión a la base de datos
 * @param int $seccion_id ID de la sección
 * @param array $estudiantes IDs de estudiantes a activar
 */
function activarEstudiantes($db, $seccion_id, $estudiantes) {
    if (empty($estudiantes)) return;
    
    $placeholders = implode(',', array_fill(0, count($estudiantes), '(?,?,CURDATE(),\'activo\')'));
    
    $stmt = $db->prepare("INSERT INTO estudiante_seccion (id_usuario, id_seccion, fecha_inscripcion, estatus)
                        VALUES $placeholders
                        ON DUPLICATE KEY UPDATE estatus = 'activo'");
    if (!$stmt) {
        error_log("Error en preparación activarEstudiantes: " . $db->error);
        return;
    }
    
    $params = [];
    foreach ($estudiantes as $est_id) {
        $params[] = $est_id;
        $params[] = $seccion_id;
    }
    
    $stmt->bind_param(str_repeat('i', count($params)), ...$params);
    if (!$stmt->execute()) {
        error_log("Error en ejecución activarEstudiantes: " . $stmt->error);
    }
    $stmt->close();
}

/**
 * Cuenta estudiantes activos en una sección
 * @param mysqli $db Conexión a la base de datos
 * @param int $seccion_id ID de la sección
 * @return int Número de estudiantes activos
 */
function contarEstudiantesActivos($db, $seccion_id) {
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM estudiante_seccion 
                        WHERE id_seccion = ? AND estatus = 'activo'");
    if (!$stmt) {
        error_log("Error en preparación contarEstudiantesActivos: " . $db->error);
        return 0;
    }
    
    $stmt->bind_param("i", $seccion_id);
    if (!$stmt->execute()) {
        error_log("Error en ejecución contarEstudiantesActivos: " . $stmt->error);
        return 0;
    }
    
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['total'];
    $stmt->close();
    return $count;
}

/**
 * Actualiza el estado de una sección según el número de estudiantes
 * @param mysqli $db Conexión a la base de datos
 * @param int $seccion_id ID de la sección
 * @param int $count Número de estudiantes activos
 */
function actualizarEstadoSeccion($db, $seccion_id, $count) {
    // Obtener información del período
    $stmt = $db->prepare("SELECT p.activo FROM secciones s
                         JOIN periodos_academicos p ON s.id_periodo = p.id_periodo
                         WHERE s.id_seccion = ?");
    if (!$stmt) {
        error_log("Error en preparación actualizarEstadoSeccion: " . $db->error);
        return;
    }
    
    $stmt->bind_param("i", $seccion_id);
    if (!$stmt->execute()) {
        error_log("Error en ejecución actualizarEstadoSeccion: " . $stmt->error);
        return;
    }
    
    $result = $stmt->get_result();
    $periodo = $result->fetch_assoc();
    $stmt->close();
    
    // Determinar el nuevo estado
    if ($periodo['activo'] == 0) {
        $nuevo_estatus = 'inactiva'; // Siempre inactiva si el período está inactivo
    } else {
        $nuevo_estatus = ($count >= MINIMO_ESTUDIANTES) ? 'activa' : 'inactiva';
    }
    
    // Obtener estado anterior para auditoría
    $estado_anterior = null;
    $stmt_ant = $db->prepare("SELECT estatus FROM secciones WHERE id_seccion = ?");
    if ($stmt_ant) {
        $stmt_ant->bind_param("i", $seccion_id);
        if ($stmt_ant->execute()) {
            $result_ant = $stmt_ant->get_result();
            $row_ant = $result_ant->fetch_assoc();
            $estado_anterior = $row_ant['estatus'];
        }
        $stmt_ant->close();
    }
    
    // Actualizar el estado de la sección
    $stmt = $db->prepare("UPDATE secciones SET estatus = ? WHERE id_seccion = ?");
    if (!$stmt) {
        error_log("Error en preparación actualizarEstadoSeccion: " . $db->error);
        return;
    }
    
    $stmt->bind_param("si", $nuevo_estatus, $seccion_id);
    if (!$stmt->execute()) {
        error_log("Error en ejecución actualizarEstadoSeccion: " . $stmt->error);
        return;
    }
    $stmt->close();
    
    // REGISTRAR EN AUDITORÍA - CAMBIO DE ESTADO DE SECCIÓN
    if ($estado_anterior != $nuevo_estatus && function_exists('registrarAuditoria')) {
        try {
            registrarAuditoria(
                "UPDATE", 
                "secciones", 
                $seccion_id, 
                ['estatus' => $estado_anterior], 
                [
                    'estatus' => $nuevo_estatus,
                    'estudiantes_activos' => $count,
                    'minimo_requerido' => MINIMO_ESTUDIANTES
                ], 
                "Secciones", 
                "Cambio de estado de sección"
            );
        } catch (Exception $e) {
            error_log("Error en auditoría actualizarEstadoSeccion: " . $e->getMessage());
        }
    }
}

/**
 * Desactiva todas las secciones de un período académico cuando este se desactiva
 * @param mysqli $db Conexión a la base de datos
 * @param int $periodo_id ID del período académico
 */
function desactivarSeccionesDePeriodo($db, $periodo_id) {
    $stmt = $db->prepare("UPDATE secciones SET estatus = 'inactiva' WHERE id_periodo = ?");
    if (!$stmt) {
        error_log("Error en preparación desactivarSeccionesDePeriodo: " . $db->error);
        return;
    }
    
    $stmt->bind_param("i", $periodo_id);
    if (!$stmt->execute()) {
        error_log("Error en ejecución desactivarSeccionesDePeriodo: " . $stmt->error);
    }
    $stmt->close();
}

/**
 * Obtiene el listado de secciones con información relevante
 * @param mysqli $db Conexión a la base de datos
 * @return array Listado de secciones
 */
function obtenerListadoSecciones($db) {
    $stmt = $db->prepare("SELECT 
                            s.id_seccion, 
                            s.codigo_seccion, 
                            c.nombre_carrera, 
                            t.numero_trayecto, 
                            p.nombre_periodo, 
                            p.activo as periodo_activo, 
                            s.capacidad_maxima,
                            s.inicia,  
                            s.status,
                            CASE WHEN p.activo = 0 THEN 'inactiva' ELSE s.estatus END as estatus,
                            COUNT(es.id_usuario) as inscritos
                          FROM secciones s
                          JOIN carreras c ON s.id_carrera = c.id_carrera
                          JOIN trayectos t ON s.id_trayecto = t.id_trayecto
                          JOIN periodos_academicos p ON s.id_periodo = p.id_periodo
                          LEFT JOIN estudiante_seccion es ON s.id_seccion = es.id_seccion AND es.estatus = 'activo'
                          GROUP BY s.id_seccion
                          ORDER BY p.nombre_periodo DESC, s.codigo_seccion");
    if (!$stmt) {
        error_log("Error en preparación obtenerListadoSecciones: " . $db->error);
        return [];
    }
    
    if (!$stmt->execute()) {
        error_log("Error en ejecución obtenerListadoSecciones: " . $stmt->error);
        return [];
    }
    
    $result = $stmt->get_result();
    $secciones = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $secciones;
}

/**
 * Obtiene los datos de una sección para edición
 * @param mysqli $db Conexión a la base de datos
 * @param int $seccion_id ID de la sección
 * @return array Datos de la sección
 */
function obtenerDatosSeccion($db, $seccion_id) {
    $stmt = $db->prepare("SELECT * FROM secciones WHERE id_seccion = ?");
    if (!$stmt) {
        error_log("Error en preparación obtenerDatosSeccion: " . $db->error);
        return [];
    }
    
    $stmt->bind_param("i", $seccion_id);
    if (!$stmt->execute()) {
        error_log("Error en ejecución obtenerDatosSeccion: " . $stmt->error);
        return [];
    }
    
    $result = $stmt->get_result();
    $seccion = $result->fetch_assoc();
    $stmt->close();
    return $seccion ?: [];
}

/**
 * Obtiene los datos para los selects del formulario de sección
 * @param mysqli $db Conexión a la base de datos
 * @return array Datos para los selects (carreras, trayectos, periodos)
 */
function obtenerDatosSelects($db) {
    // Carreras - Excluyendo la que tiene id_carrera = 0 (No especificado)
    $stmt = $db->prepare("SELECT id_carrera, nombre_carrera FROM carreras WHERE activa = 1 AND id_carrera != 0");
    if (!$stmt) {
        error_log("Error en preparación obtenerDatosSelects carreras: " . $db->error);
        $carreras = [];
    } else {
        if (!$stmt->execute()) {
            error_log("Error en ejecución obtenerDatosSelects carreras: " . $stmt->error);
            $carreras = [];
        } else {
            $result = $stmt->get_result();
            $carreras = $result->fetch_all(MYSQLI_ASSOC);
        }
        $stmt->close();
    }
    
    // Trayectos
    $stmt = $db->prepare("SELECT id_trayecto, numero_trayecto FROM trayectos ORDER BY numero_trayecto");
    if (!$stmt) {
        error_log("Error en preparación obtenerDatosSelects trayectos: " . $db->error);
        $trayectos = [];
    } else {
        if (!$stmt->execute()) {
            error_log("Error en ejecución obtenerDatosSelects trayectos: " . $stmt->error);
            $trayectos = [];
        } else {
            $result = $stmt->get_result();
            $trayectos = $result->fetch_all(MYSQLI_ASSOC);
        }
        $stmt->close();
    }
    
    // Periodos
    $stmt = $db->prepare("SELECT id_periodo, nombre_periodo FROM periodos_academicos WHERE activo = 1");
    if (!$stmt) {
        error_log("Error en preparación obtenerDatosSelects periodos: " . $db->error);
        $periodos = [];
    } else {
        if (!$stmt->execute()) {
            error_log("Error en ejecución obtenerDatosSelects periodos: " . $stmt->error);
            $periodos = [];
        } else {
            $result = $stmt->get_result();
            $periodos = $result->fetch_all(MYSQLI_ASSOC);
        }
        $stmt->close();
    }
    
    return [
        'carreras' => $carreras,
        'trayectos' => $trayectos,
        'periodos' => $periodos
    ];
}

/**
 * Obtiene información detallada de una sección
 * @param mysqli $db Conexión a la base de datos
 * @param int $seccion_id ID de la sección
 * @return array Datos detallados de la sección
 */
function obtenerDetalleSeccion($db, $seccion_id) {
    $stmt = $db->prepare("SELECT s.*, c.nombre_carrera, t.numero_trayecto, p.nombre_periodo, p.activo as periodo_activo,
                         CASE WHEN p.activo = 0 THEN 'inactiva' ELSE s.estatus END as estatus,
                         COUNT(es.id_usuario) as inscritos
                  FROM secciones s
                  JOIN carreras c ON s.id_carrera = c.id_carrera
                  JOIN trayectos t ON s.id_trayecto = t.id_trayecto
                  JOIN periodos_academicos p ON s.id_periodo = p.id_periodo
                  LEFT JOIN estudiante_seccion es ON s.id_seccion = es.id_seccion AND es.estatus = 'activo'
                  WHERE s.id_seccion = ?
                  GROUP BY s.id_seccion");
    if (!$stmt) {
        error_log("Error en preparación obtenerDetalleSeccion: " . $db->error);
        return [];
    }
    
    $stmt->bind_param("i", $seccion_id);
    if (!$stmt->execute()) {
        error_log("Error en ejecución obtenerDetalleSeccion: " . $stmt->error);
        return [];
    }
    
    $result = $stmt->get_result();
    $seccion = $result->fetch_assoc();
    $stmt->close();
    
    if (!isset($seccion['inscritos'])) {
        $seccion['inscritos'] = 0;
    }
    
    return $seccion ?: [];
}

/**
 * Obtiene los estudiantes asignados a una sección
 * @param mysqli $db Conexión a la base de datos
 * @param int $seccion_id ID de la sección
 * @return array Estudiantes asignados
 */
function obtenerEstudiantesDeSeccion($db, $seccion_id) {
    $stmt = $db->prepare("SELECT u.id, u.nombre, u.idusuario, es.fecha_inscripcion
                  FROM users u
                  JOIN estudiante_seccion es ON u.id = es.id_usuario
                  WHERE es.id_seccion = ? AND es.estatus = 'activo'
                  ORDER BY u.nombre");
    if (!$stmt) {
        error_log("Error en preparación obtenerEstudiantesDeSeccion: " . $db->error);
        return [];
    }
    
    $stmt->bind_param("i", $seccion_id);
    if (!$stmt->execute()) {
        error_log("Error en ejecución obtenerEstudiantesDeSeccion: " . $stmt->error);
        return [];
    }
    
    $result = $stmt->get_result();
    $estudiantes = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $estudiantes;
}

/**
 * Obtiene los estudiantes disponibles para asignar a una sección
 * @param mysqli $db Conexión a la base de datos
 * @param int $seccion_id ID de la sección
 * @param int $carrera_id ID de la carrera
 * @return array Estudiantes disponibles
 */
function obtenerEstudiantesDisponibles($db, $seccion_id, $carrera_id) {
    $stmt = $db->prepare("SELECT u.id, u.nombre, u.idusuario, 
                         (SELECT COUNT(*) FROM estudiante_seccion 
                          WHERE id_usuario = u.id AND id_seccion = ? AND estatus = 'activo') as asignado
                  FROM users u
                  WHERE u.estudiante = 1 AND u.status = 1 AND u.carrera = ?
                  ORDER BY u.nombre");
    if (!$stmt) {
        error_log("Error en preparación obtenerEstudiantesDisponibles: " . $db->error);
        return [];
    }
    
    $stmt->bind_param("ii", $seccion_id, $carrera_id);
    if (!$stmt->execute()) {
        error_log("Error en ejecución obtenerEstudiantesDisponibles: " . $stmt->error);
        return [];
    }
    
    $result = $stmt->get_result();
    $estudiantes = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $estudiantes;
}

/**
 * Muestra una alerta de error
 * @param string $mensaje Mensaje a mostrar
 */
function mostrarError($mensaje) {
    if (!empty($mensaje)) {
        echo '<div class="alert alert-danger">' . htmlspecialchars($mensaje) . '</div>';
    }
}

/**
 * Muestra una alerta de éxito
 * @param string $mensaje Mensaje a mostrar
 */
function mostrarExito($mensaje) {
    if (!empty($mensaje)) {
        echo '<div class="alert alert-success">' . htmlspecialchars($mensaje) . '</div>';
    }
}

/**
 * Muestra una alerta de advertencia
 * @param string $mensaje Mensaje a mostrar
 */
function mostrarAdvertencia($mensaje) {
    if (!empty($mensaje)) {
        echo '<div class="alert alert-warning">' . htmlspecialchars($mensaje) . '</div>';
    }
}

/**
 * Obtiene los horarios de una sección
 * @param mysqli $db Conexión a la base de datos
 * @param int $id_seccion ID de la sección
 * @return array Horarios de la sección
 */
function obtenerHorariosSeccion($db, $id_seccion) {
    // Validar entrada
    if (!is_numeric($id_seccion)) {
        error_log("ID de sección no válido: " . $id_seccion);
        return [];
    }

    $sql = "SELECT 
                h.id_horario,
                h.dia, 
                TIME_FORMAT(h.hora_inicio, '%H:%i') as hora_inicio,
                TIME_FORMAT(h.hora_fin, '%H:%i') as hora_fin, 
                h.aula,
                u.id as id_docente,
                u.nombre AS nombre_docente,
                m.id_materia,
                m.cod_materia,
                m.nombre_materia,
                m.creditos,
                m.trayecto
            FROM horarios h
            INNER JOIN docente_seccion ds ON h.id_docente_seccion = ds.id_docente_seccion
            INNER JOIN users u ON ds.id_usuario = u.id
            INNER JOIN materias m ON ds.id_materia = m.id_materia
            WHERE ds.id_seccion = ? AND ds.estatus = 1 AND m.activa = 1
            ORDER BY 
                FIELD(h.dia, 0, 1, 2, 3, 4, 5),
                h.hora_inicio";

    $stmt = $db->prepare($sql);
    if (!$stmt) {
        error_log("Error al preparar consulta: " . $db->error);
        return [];
    }

    $stmt->bind_param("i", $id_seccion);
    
    if (!$stmt->execute()) {
        error_log("Error al ejecutar consulta: " . $stmt->error);
        return [];
    }

    $result = $stmt->get_result();
    if (!$result) {
        error_log("Error al obtener resultados: " . $db->error);
        return [];
    }

    $horarios = $result->fetch_all(MYSQLI_ASSOC);
    
    // Convertir números de días a nombres
    $dias_semana = [
        0 => 'Lunes',
        1 => 'Martes',
        2 => 'Miércoles',
        3 => 'Jueves',
        4 => 'Viernes',
        5 => 'Sábado'
    ];
    
    foreach ($horarios as &$horario) {
        $numero_dia = (int)$horario['dia'];
        $horario['dia_nombre'] = $dias_semana[$numero_dia] ?? 'Desconocido';
    }
    
    return $horarios ?: [];
}

/**
 * Calcula cuántas filas debe ocupar una clase en la tabla de horarios
 * @param string $hora_inicio Hora de inicio
 * @param string $hora_fin Hora de fin
 * @param array $horas Array de horas disponibles
 * @return int Número de filas que debe ocupar
 */
function calcularRowspan($hora_inicio, $hora_fin, $horas) {
    $inicio = date('H:i', strtotime($hora_inicio));
    $fin = date('H:i', strtotime($hora_fin));
    
    $inicio_index = array_search($inicio, $horas);
    $fin_index = array_search($fin, $horas);
    
    if ($inicio_index === false || $fin_index === false) {
        return 1; // Por defecto 1 si no encontramos las horas
    }
    
    return $fin_index - $inicio_index;
}

/**
 * Obtiene información básica de una sección por su ID
 * @param mysqli $db Conexión a la base de datos
 * @param int $seccion_id ID de la sección
 * @return array Información básica de la sección
 */
function obtenerInfoBasicaSeccion($db, $seccion_id) {
    $stmt = $db->prepare("SELECT s.codigo_seccion, c.nombre_carrera, t.numero_trayecto, p.nombre_periodo
                         FROM secciones s
                         JOIN carreras c ON s.id_carrera = c.id_carrera
                         JOIN trayectos t ON s.id_trayecto = t.id_trayecto
                         JOIN periodos_academicos p ON s.id_periodo = p.id_periodo
                         WHERE s.id_seccion = ?");
    if (!$stmt) {
        error_log("Error en preparación obtenerInfoBasicaSeccion: " . $db->error);
        return [];
    }
    
    $stmt->bind_param("i", $seccion_id);
    if (!$stmt->execute()) {
        error_log("Error en ejecución obtenerInfoBasicaSeccion: " . $stmt->error);
        return [];
    }
    
    $result = $stmt->get_result();
    $seccion = $result->fetch_assoc();
    $stmt->close();
    return $seccion ?: [];
}

/**
 * Verifica si una sección existe y está activa
 * @param mysqli $db Conexión a la base de datos
 * @param int $seccion_id ID de la sección
 * @return bool True si la sección existe y está activa
 */
function seccionExisteYActiva($db, $seccion_id) {
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM secciones 
                         WHERE id_seccion = ? AND estatus = 'activa'");
    if (!$stmt) {
        error_log("Error en preparación seccionExisteYActiva: " . $db->error);
        return false;
    }
    
    $stmt->bind_param("i", $seccion_id);
    if (!$stmt->execute()) {
        error_log("Error en ejecución seccionExisteYActiva: " . $stmt->error);
        return false;
    }
    
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['total'];
    $stmt->close();
    return $count > 0;
}

/**
 * Obtiene el número total de secciones activas
 * @param mysqli $db Conexión a la base de datos
 * @return int Número total de secciones activas
 */
function obtenerTotalSeccionesActivas($db) {
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM secciones WHERE estatus = 'activa'");
    if (!$stmt) {
        error_log("Error en preparación obtenerTotalSeccionesActivas: " . $db->error);
        return 0;
    }
    
    if (!$stmt->execute()) {
        error_log("Error en ejecución obtenerTotalSeccionesActivas: " . $stmt->error);
        return 0;
    }
    
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['total'];
    $stmt->close();
    return $count;
}

/**
 * Obtiene las secciones por carrera
 * @param mysqli $db Conexión a la base de datos
 * @param int $carrera_id ID de la carrera
 * @return array Secciones de la carrera
 */
function obtenerSeccionesPorCarrera($db, $carrera_id) {
    $stmt = $db->prepare("SELECT s.id_seccion, s.codigo_seccion, t.numero_trayecto, p.nombre_periodo, s.estatus
                         FROM secciones s
                         JOIN trayectos t ON s.id_trayecto = t.id_trayecto
                         JOIN periodos_academicos p ON s.id_periodo = p.id_periodo
                         WHERE s.id_carrera = ? AND s.estatus = 'activa'
                         ORDER BY p.nombre_periodo DESC, t.numero_trayecto, s.codigo_seccion");
    if (!$stmt) {
        error_log("Error en preparación obtenerSeccionesPorCarrera: " . $db->error);
        return [];
    }
    
    $stmt->bind_param("i", $carrera_id);
    if (!$stmt->execute()) {
        error_log("Error en ejecución obtenerSeccionesPorCarrera: " . $stmt->error);
        return [];
    }
    
    $result = $stmt->get_result();
    $secciones = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $secciones;
}



/**
 * Obtiene secciones disponibles por carrera y turno
 */
function obtenerSeccionesDisponiblesPorCarreraYTurno($carrera_id, $turno) {
    global $db;
    
    $query = "SELECT 
        s.id_seccion,
        s.codigo_seccion,
        s.capacidad_maxima,
        s.turno,
        (SELECT COUNT(*) FROM estudiante_seccion WHERE id_seccion = s.id_seccion AND estatus = 'activo') as inscritos
    FROM secciones s
    INNER JOIN periodos_academicos p ON s.id_periodo = p.id_periodo
    WHERE s.id_carrera = ? 
        AND s.turno = ?
        AND p.activo = 1
        AND s.status = 'activa'
    ORDER BY s.codigo_seccion ASC";
    
    $stmt = $db->prepare($query);
    if (!$stmt) {
        error_log("Error en obtenerSeccionesDisponiblesPorCarreraYTurno: " . $db->error);
        return [];
    }
    
    $stmt->bind_param('is', $carrera_id, $turno);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $secciones = [];
    while ($row = $result->fetch_assoc()) {
        $cupos_disponibles = $row['capacidad_maxima'] - ($row['inscritos'] ?? 0);
        if ($cupos_disponibles > 0) {
            $row['cupos_disponibles'] = $cupos_disponibles;
            $secciones[] = $row;
        }
    }
    
    return $secciones;
}

/**
 * Obtiene todas las secciones de una carrera (incluyendo llenas o inactivas)
 */
function obtenerTodasSeccionesPorCarrera($carrera_id) {
    global $db;
    
    $query = "SELECT 
        s.id_seccion,
        s.codigo_seccion,
        s.capacidad_maxima,
        s.turno,
        s.status,
        p.activo as periodo_activo,
        (SELECT COUNT(*) FROM estudiante_seccion WHERE id_seccion = s.id_seccion AND estatus = 'activo') as inscritos
    FROM secciones s
    INNER JOIN periodos_academicos p ON s.id_periodo = p.id_periodo
    WHERE s.id_carrera = ?
    ORDER BY s.codigo_seccion ASC";
    
    $stmt = $db->prepare($query);
    if (!$stmt) {
        error_log("Error en obtenerTodasSeccionesPorCarrera: " . $db->error);
        return [];
    }
    
    $stmt->bind_param('i', $carrera_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $secciones = [];
    while ($row = $result->fetch_assoc()) {
        $row['inscritos'] = $row['inscritos'] ?? 0;
        $secciones[] = $row;
    }
    
    return $secciones;
}



/**
 * Asigna un estudiante a una sección específica (selección manual)
 */
function asignarEstudianteASeccionEspecifica($usuario_id, $seccion_id) {
    global $db;
    
    // Verificar cupo en la sección
    $query = "SELECT 
        s.id_seccion,
        s.codigo_seccion,
        s.capacidad_maxima,
        COUNT(e.id_usuario) as inscritos
    FROM secciones s
    LEFT JOIN estudiante_seccion e ON s.id_seccion = e.id_seccion
    WHERE s.id_seccion = ?
    GROUP BY s.id_seccion";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $seccion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $seccion = $result->fetch_assoc();
    
    if (!$seccion) {
        return ['success' => false, 'message' => 'La sección no existe'];
    }
    
    if ($seccion['inscritos'] >= $seccion['capacidad_maxima']) {
        return ['success' => false, 'message' => 'La sección no tiene cupos disponibles'];
    }
    
    // Verificar si ya está inscrito
    $check = $db->prepare("SELECT id FROM estudiante_seccion WHERE id_usuario = ? AND id_seccion = ?");
    $check->bind_param('ii', $usuario_id, $seccion_id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        return ['success' => false, 'message' => 'El estudiante ya está inscrito en esta sección'];
    }
    
    // Inscribir estudiante
    $fecha = date('Y-m-d H:i:s');
    $insert = $db->prepare("INSERT INTO estudiante_seccion (id_usuario, id_seccion, fecha_inscripcion, estatus) VALUES (?, ?, ?, 'activo')");
    $insert->bind_param('iis', $usuario_id, $seccion_id, $fecha);
    
    if ($insert->execute()) {
        return [
            'success' => true, 
            'message' => 'Estudiante asignado a la sección ' . $seccion['codigo_seccion'],
            'seccion' => $seccion['codigo_seccion']
        ];
    }
    
    return ['success' => false, 'message' => 'Error al asignar estudiante a la sección'];
}

/**
 * Asigna un estudiante a una sección disponible automáticamente
 */
function asignarEstudianteASeccionDisponible($usuario_id, $carrera_id, $turno) {
    global $db;
    
    // Buscar sección con cupo disponible (usando JOIN con periodos_academicos)
    $query = "SELECT 
        s.id_seccion,
        s.codigo_seccion,
        s.capacidad_maxima,
        (SELECT COUNT(*) FROM estudiante_seccion WHERE id_seccion = s.id_seccion AND estatus = 'activo') as inscritos
    FROM secciones s
    INNER JOIN periodos_academicos p ON s.id_periodo = p.id_periodo
    WHERE s.id_carrera = ? 
        AND s.turno = ?
        AND p.activo = 1
        AND s.status = 'activa'
    HAVING inscritos < capacidad_maxima
    ORDER BY inscritos ASC
    LIMIT 1";
    
    $stmt = $db->prepare($query);
    if (!$stmt) {
        error_log("Error prepare: " . $db->error);
        return ['success' => false, 'message' => 'Error al buscar sección: ' . $db->error];
    }
    
    $stmt->bind_param('is', $carrera_id, $turno);
    $stmt->execute();
    $result = $stmt->get_result();
    $seccion = $result->fetch_assoc();
    
    if (!$seccion) {
        error_log("No se encontró sección para carrera: $carrera_id, turno: $turno");
        return ['success' => false, 'message' => 'No hay secciones disponibles para esta carrera y turno'];
    }
    
    // Verificar si ya está inscrito
    $check = $db->prepare("SELECT id FROM estudiante_seccion WHERE id_usuario = ? AND id_seccion = ? AND estatus = 'activo'");
    if (!$check) {
        return ['success' => false, 'message' => 'Error al verificar inscripción: ' . $db->error];
    }
    
    $check->bind_param('ii', $usuario_id, $seccion['id_seccion']);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        return ['success' => false, 'message' => 'El estudiante ya está inscrito en esta sección'];
    }
    
    // Inscribir estudiante
    $fecha = date('Y-m-d H:i:s');
    $insert = $db->prepare("INSERT INTO estudiante_seccion (id_usuario, id_seccion, fecha_inscripcion, estatus) VALUES (?, ?, ?, 'activo')");
    if (!$insert) {
        return ['success' => false, 'message' => 'Error al preparar inserción: ' . $db->error];
    }
    
    $insert->bind_param('iis', $usuario_id, $seccion['id_seccion'], $fecha);
    
    if ($insert->execute()) {
        return [
            'success' => true, 
            'message' => 'Estudiante asignado a la sección ' . $seccion['codigo_seccion'],
            'seccion' => $seccion['codigo_seccion']
        ];
    }
    
    return ['success' => false, 'message' => 'Error al asignar estudiante a la sección: ' . $insert->error];
}

function aceptarPreinscripcionConSeccion($preinscripcion_id, $admin_id) {
    global $db;
    
    error_log("=== aceptarPreinscripcionConSeccion ===");
    error_log("preinscripcion_id: $preinscripcion_id");
    error_log("admin_id: $admin_id");
    
    $db->begin_transaction();
    
    try {
        // Obtener datos de la preinscripción
        $query = "SELECT * FROM preinscripcion WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $preinscripcion_id);
        $stmt->execute();
        $preinscripcion = $stmt->get_result()->fetch_assoc();
        
        if (!$preinscripcion) {
            throw new Exception('Preinscripción no encontrada');
        }
        
        error_log("Preinscripción encontrada - Carrera: {$preinscripcion['carrera']}, Turno: {$preinscripcion['turno']}, IDUsuario: {$preinscripcion['idusuario']}");
        
        if ($preinscripcion['status'] !== 'Pendiente') {
            throw new Exception('Esta preinscripción ya fue procesada');
        }
        
        // Verificar si el usuario ya existe
        $check_user = $db->prepare("SELECT id FROM users WHERE idusuario = ?");
        $check_user->bind_param('s', $preinscripcion['idusuario']);
        $check_user->execute();
        $existing_user = $check_user->get_result()->fetch_assoc();
        
        if ($existing_user) {
            $usuario_id = $existing_user['id'];
            error_log("Usuario ya existe - ID: $usuario_id");
        } else {
            // Crear nuevo usuario
            $password_hash = password_hash($preinscripcion['idusuario'], PASSWORD_DEFAULT);
            
            $insert_user = $db->prepare("INSERT INTO users (
                idusuario, nombre, username, email, tlf, cel, direccion, ciudad, estado, 
                municipio, parroquia, fecha_ingreso, fecha_nac, carrera, genero, embarazada, 
                edo_civil, estudiante, status, password, fecha_act
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, 1, 1, ?, NOW())");
            
            $insert_user->bind_param(
                'sssssssssssssssss',
                $preinscripcion['idusuario'],
                $preinscripcion['nombre'],
                $preinscripcion['username'],
                $preinscripcion['email'],
                $preinscripcion['tlf'],
                $preinscripcion['cel'],
                $preinscripcion['direccion'],
                $preinscripcion['ciudad'],
                $preinscripcion['estado'],
                $preinscripcion['municipio'],
                $preinscripcion['parroquia'],
                $preinscripcion['fecha_nac'],
                $preinscripcion['carrera'],
                $preinscripcion['genero'],
                $preinscripcion['embarazada'],
                $preinscripcion['edo_civil'],
                $password_hash
            );
            
            if (!$insert_user->execute()) {
                throw new Exception('Error al crear el usuario: ' . $insert_user->error);
            }
            
            $usuario_id = $db->insert_id;
            error_log("Usuario creado - ID: $usuario_id");
        }
        
        // Asignar a sección disponible
        $turno = $preinscripcion['turno'] ?? 'Diurno';
        error_log("Buscando sección para - carrera_id: {$preinscripcion['carrera']}, turno: $turno");
        
        $asignacion = asignarEstudianteASeccionDisponible($usuario_id, $preinscripcion['carrera'], $turno);
        
        error_log("Resultado de asignación: " . print_r($asignacion, true));
        
        if (!$asignacion['success']) {
            throw new Exception($asignacion['message']);
        }
        
        // Actualizar preinscripción
        $fecha_actual = date('Y-m-d H:i:s');
        $update = $db->prepare("UPDATE preinscripcion SET 
            status = 'Aprobada', 
            aprobado_por = ?, 
            fecha_aprobado = ?,
            updated_at = ?
            WHERE id = ?");
        $update->bind_param('issi', $admin_id, $fecha_actual, $fecha_actual, $preinscripcion_id);
        $update->execute();
        
        $db->commit();
        
        error_log("✅ Preinscripción aprobada exitosamente");
        
        return [
            'success' => true,
            'message' => 'Preinscripción aprobada exitosamente. ' . $asignacion['message'],
            'seccion_asignada' => $asignacion['seccion']
        ];
        
    } catch (Exception $e) {
        $db->rollback();
        error_log("❌ Error en aceptarPreinscripcionConSeccion: " . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}






//ASIGNAR SECCIONES A DOCENTES ***********************************************************************************************


/**
 * Obtiene las materias de un docente por carrera
 */
function obtenerMateriasDocentePorCarrera($id_docente, $id_carrera) {
    global $db;
    
    try {
        $query = "SELECT DISTINCT m.id_materia, m.nombre_materia, m.cod_materia 
                  FROM docente_materia dm
                  JOIN materias m ON dm.id_materia = m.id_materia
                  JOIN carrera_materia cm ON m.id_materia = cm.id_materia
                  WHERE dm.id_usuario = ?
                  AND cm.id_carrera = ?
                  ORDER BY m.nombre_materia";
        
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("ii", $id_docente, $id_carrera);
        
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $materias = array();
        
        while($row = $result->fetch_assoc()) {
            $materias[] = $row;
        }
        
        $stmt->close();
        return $materias;
        
    } catch (Exception $e) {
        error_log("Error en obtenerMateriasDocentePorCarrera: " . $e->getMessage());
        return array();
    }
}

/**
 * Procesa la asignación de una sección a un docente
 */
function procesarAsignacionSeccion($id_usuario, $id_seccion, $id_materia) {
    global $db;
    
    try {
        // Obtener información para auditoría
        $docente_info = obtenerDocentePorId($id_usuario);
        $seccion_info = obtenerDetalleSeccion($db, $id_seccion);
        $materia_info = obtenerMateriaPorId($db, $id_materia);

        // Verificar si ya existe la asignación
        $query = "SELECT id_docente_seccion FROM docente_seccion 
                  WHERE id_usuario = ? 
                  AND id_seccion = ?
                  AND id_materia = ?";
        
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("iii", $id_usuario, $id_seccion, $id_materia);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $stmt->close();
            return [
                'success' => false,
                'message' => 'Este docente ya tiene asignada esta sección con esta materia.',
                'type' => 'warning'
            ];
        }
        $stmt->close();

        // Insertar nueva asignación
        $query = "INSERT INTO docente_seccion (id_usuario, id_seccion, id_materia, fecha_asignacion) 
                  VALUES (?, ?, ?, NOW())";
        
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("iii", $id_usuario, $id_seccion, $id_materia);
        
        if($stmt->execute()) {
            $id_asignacion = $stmt->insert_id;
            $stmt->close();
            
            // REGISTRAR EN AUDITORÍA - ASIGNACIÓN DE SECCIÓN A DOCENTE
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "INSERT", 
                        "docente_seccion", 
                        $id_asignacion, 
                        null, 
                        [
                            'id_usuario' => $id_usuario,
                            'docente_nombre' => $docente_info['nombre'] ?? 'Desconocido',
                            'docente_cedula' => $docente_info['idusuario'] ?? '',
                            'id_seccion' => $id_seccion,
                            'seccion_codigo' => $seccion_info['codigo_seccion'] ?? 'Desconocida',
                            'carrera_seccion' => $seccion_info['nombre_carrera'] ?? 'Desconocida',
                            'id_materia' => $id_materia,
                            'materia_nombre' => $materia_info['nombre_materia'] ?? 'Desconocida',
                            'materia_codigo' => $materia_info['cod_materia'] ?? ''
                        ], 
                        "Asignaciones Docentes", 
                        "Asignación de sección a docente"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría procesarAsignacionSeccion: " . $e->getMessage());
                }
            }
            
            return [
                'success' => true,
                'message' => 'Asignación realizada correctamente.',
                'type' => 'success',
                'id_asignacion' => $id_asignacion
            ];
        } else {
            throw new Exception("Error al asignar: " . $stmt->error);
        }
        
    } catch (Exception $e) {
        error_log("Error en procesarAsignacionSeccion: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL ASIGNAR SECCIÓN
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "docente_seccion", 
                    null, 
                    null, 
                    [
                        'id_usuario' => $id_usuario,
                        'id_seccion' => $id_seccion,
                        'id_materia' => $id_materia,
                        'error' => $e->getMessage()
                    ], 
                    "Asignaciones Docentes", 
                    "Error al asignar sección a docente"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error procesarAsignacionSeccion: " . $auditError->getMessage());
            }
        }
        
        return [
            'success' => false,
            'message' => 'Error al asignar: ' . $e->getMessage(),
            'type' => 'danger'
        ];
    }
}

/**
 * Elimina una asignación de sección
 */
function eliminarAsignacionSeccion($id) {
    global $db;
    
    try {
        // Obtener información de la asignación para auditoría
        $info_query = "SELECT ds.*, u.nombre as docente_nombre, u.idusuario as docente_cedula,
                              s.codigo_seccion, c.nombre_carrera, m.nombre_materia, m.cod_materia
                       FROM docente_seccion ds
                       JOIN users u ON ds.id_usuario = u.id
                       JOIN secciones s ON ds.id_seccion = s.id_seccion
                       LEFT JOIN carreras c ON s.id_carrera = c.id_carrera
                       JOIN materias m ON ds.id_materia = m.id_materia
                       WHERE ds.id_docente_seccion = ?";
        
        $info_stmt = $db->prepare($info_query);
        if (!$info_stmt) {
            throw new Exception("Error en preparación de consulta de información: " . $db->error);
        }
        
        $info_stmt->bind_param("i", $id);
        $info_stmt->execute();
        $info_result = $info_stmt->get_result();
        $asignacion_info = $info_result->fetch_assoc();
        $info_stmt->close();
        
        if (!$asignacion_info) {
            return [
                'success' => false,
                'message' => 'No se encontró la asignación especificada.',
                'type' => 'warning'
            ];
        }

        // Eliminar asignación
        $query = "DELETE FROM docente_seccion WHERE id_docente_seccion = ?";
        $stmt = $db->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("i", $id);
        
        if($stmt->execute()) {
            $affected_rows = $stmt->affected_rows;
            $stmt->close();
            
            if ($affected_rows > 0) {
                // REGISTRAR EN AUDITORÍA - ELIMINACIÓN DE ASIGNACIÓN
                if (function_exists('registrarAuditoria')) {
                    try {
                        registrarAuditoria(
                            "DELETE", 
                            "docente_seccion", 
                            $id, 
                            [
                                'id_usuario' => $asignacion_info['id_usuario'],
                                'docente_nombre' => $asignacion_info['docente_nombre'],
                                'docente_cedula' => $asignacion_info['docente_cedula'],
                                'id_seccion' => $asignacion_info['id_seccion'],
                                'seccion_codigo' => $asignacion_info['codigo_seccion'],
                                'carrera_seccion' => $asignacion_info['nombre_carrera'],
                                'id_materia' => $asignacion_info['id_materia'],
                                'materia_nombre' => $asignacion_info['nombre_materia'],
                                'fecha_asignacion' => $asignacion_info['fecha_asignacion']
                            ], 
                            null, 
                            "Asignaciones Docentes", 
                            "Eliminación de asignación de sección a docente"
                        );
                    } catch (Exception $e) {
                        error_log("Error en auditoría eliminarAsignacionSeccion: " . $e->getMessage());
                    }
                }
                
                return [
                    'success' => true,
                    'message' => 'Asignación eliminada correctamente.',
                    'type' => 'success',
                    'affected_rows' => $affected_rows
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No se encontró la asignación especificada.',
                    'type' => 'warning'
                ];
            }
        } else {
            throw new Exception("Error al eliminar: " . $stmt->error);
        }
        
    } catch (Exception $e) {
        error_log("Error en eliminarAsignacionSeccion: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL ELIMINAR ASIGNACIÓN
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "docente_seccion", 
                    $id, 
                    null, 
                    [
                        'id_asignacion' => $id,
                        'error' => $e->getMessage()
                    ], 
                    "Asignaciones Docentes", 
                    "Error al eliminar asignación de sección"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error eliminarAsignacionSeccion: " . $auditError->getMessage());
            }
        }
        
        return [
            'success' => false,
            'message' => 'Error al eliminar: ' . $e->getMessage(),
            'type' => 'danger'
        ];
    }
}

/**
 * Obtiene la lista de docentes activos
 */
function obtenerDocentesActivos() {
    global $db;
    
    try {
        $query = "SELECT id, idusuario, nombre FROM users WHERE docente = 1 AND status = 1 ORDER BY nombre";
        $stmt = $db->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $docentes = array();
        
        while($row = $result->fetch_assoc()) {
            $docentes[] = $row;
        }
        
        $stmt->close();
        return $docentes;
        
    } catch (Exception $e) {
        error_log("Error en obtenerDocentesActivos: " . $e->getMessage());
        return array();
    }
}

/**
 * Obtiene las secciones activas
 */
function obtenerSeccionesActivas() {
    global $db;
    
    try {
        $query = "SELECT s.id_seccion, s.codigo_seccion, c.id_carrera, c.nombre_carrera 
                  FROM secciones s
                  LEFT JOIN carreras c ON s.id_carrera = c.id_carrera
                  WHERE s.estatus = 'activa' AND (c.activa = 1 OR c.activa IS NULL)
                  ORDER BY c.nombre_carrera, s.codigo_seccion";
        
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $secciones = array();
        
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $secciones[] = $row;
            }
        }
        
        $stmt->close();
        return $secciones;
        
    } catch (Exception $e) {
        error_log("Error en obtenerSeccionesActivas: " . $e->getMessage());
        return array();
    }
}

/**
 * Obtiene todas las asignaciones de secciones
 */
function obtenerAsignacionesSecciones() {
    global $db;
    
    try {
        $query = "SELECT ds.id_docente_seccion, u.nombre AS docente, 
                         s.codigo_seccion, c.nombre_carrera, ds.fecha_asignacion,
                         m.nombre_materia, m.cod_materia, u.idusuario as docente_cedula,
                         s.id_seccion, u.id as id_docente, m.id_materia
                  FROM docente_seccion ds
                  JOIN users u ON ds.id_usuario = u.id
                  JOIN secciones s ON ds.id_seccion = s.id_seccion
                  LEFT JOIN carreras c ON s.id_carrera = c.id_carrera
                  JOIN materias m ON ds.id_materia = m.id_materia
                  WHERE s.estatus = 'activa' AND (c.activa = 1 OR c.activa IS NULL)
                  ORDER BY ds.fecha_asignacion DESC";
        
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $asignaciones = array();
        
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $asignaciones[] = $row;
            }
        }
        
        $stmt->close();
        return $asignaciones;
        
    } catch (Exception $e) {
        error_log("Error en obtenerAsignacionesSecciones: " . $e->getMessage());
        return array();
    }
}

/**
 * Obtiene las materias disponibles para un docente en una sección específica
 */
function obtenerMateriasDisponiblesParaDocente($id_docente, $id_seccion) {
    global $db;
    
    try {
        // Obtener la carrera de la sección
        $query_carrera = "SELECT id_carrera FROM secciones WHERE id_seccion = ?";
        $stmt_carrera = $db->prepare($query_carrera);
        $stmt_carrera->bind_param("i", $id_seccion);
        $stmt_carrera->execute();
        $result_carrera = $stmt_carrera->get_result();
        $seccion_info = $result_carrera->fetch_assoc();
        $stmt_carrera->close();
        
        if (!$seccion_info) {
            return array();
        }
        
        $id_carrera = $seccion_info['id_carrera'];
        
        // Obtener materias del docente en esa carrera que no estén ya asignadas a la sección
        $query = "SELECT DISTINCT m.id_materia, m.nombre_materia, m.cod_materia 
                  FROM docente_materia dm
                  JOIN materias m ON dm.id_materia = m.id_materia
                  JOIN carrera_materia cm ON m.id_materia = cm.id_materia
                  WHERE dm.id_usuario = ?
                  AND cm.id_carrera = ?
                  AND m.id_materia NOT IN (
                      SELECT id_materia FROM docente_seccion 
                      WHERE id_seccion = ? AND id_usuario = ?
                  )
                  AND m.activa = 1
                  ORDER BY m.nombre_materia";
        
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("iiii", $id_docente, $id_carrera, $id_seccion, $id_docente);
        
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $materias = array();
        
        while($row = $result->fetch_assoc()) {
            $materias[] = $row;
        }
        
        $stmt->close();
        return $materias;
        
    } catch (Exception $e) {
        error_log("Error en obtenerMateriasDisponiblesParaDocente: " . $e->getMessage());
        return array();
    }
}












// FUNCIONES DE PERIODOS ACADEMICOS***********************************************************************


/**
 * Obtiene todos los periodos académicos
 * @param mysqli $db Conexión a la base de datos
 * @return array Lista de periodos académicos
 */
function obtenerPeriodosAcademicos($db) {
    try {
        // Nota: desactivación automática removida — los periodos se gestionan manualmente.
        // La llamada a desactivarPeriodosVencidos fue eliminada para evitar cambios automáticos.
        
        $query = "SELECT * FROM periodos_academicos ORDER BY created_at DESC";
        $stmt = $db->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $periodos = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $periodos;
        
    } catch (Exception $e) {
        error_log("Error en obtenerPeriodosAcademicos: " . $e->getMessage());
        return [];
    }
}

/**
 * Crea un nuevo periodo académico
 * @param mysqli $db Conexión a la base de datos
 * @param string $nombre Nombre del periodo
 * @param string $fecha_inicio Fecha de inicio (YYYY-MM-DD)
 * @param string $fecha_fin Fecha de fin (YYYY-MM-DD)
 * @return array Resultado de la operación
 */
function crearPeriodoAcademico($db, $nombre, $fecha_inicio, $fecha_fin) {
    try {
        // Validar fechas
        if (empty($nombre) || empty($fecha_inicio) || empty($fecha_fin)) {
            return [
                'success' => false,
                'message' => 'Todos los campos son obligatorios'
            ];
        }

        // Validar que la fecha de fin sea posterior a la de inicio
        if (strtotime($fecha_fin) <= strtotime($fecha_inicio)) {
            return [
                'success' => false,
                'message' => 'La fecha de fin debe ser posterior a la fecha de inicio'
            ];
        }

        $query = "INSERT INTO periodos_academicos (nombre_periodo, fecha_inicio, fecha_fin, activo, created_at) 
                  VALUES (?, ?, ?, 1, NOW())";
        $stmt = $db->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("sss", $nombre, $fecha_inicio, $fecha_fin);
        
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
        $periodo_id = $stmt->insert_id;
        $stmt->close();
        
        // REGISTRAR EN AUDITORÍA - NUEVO PERIODO ACADÉMICO CREADO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "INSERT", 
                    "periodos_academicos", 
                    $periodo_id, 
                    null, 
                    [
                        'nombre_periodo' => $nombre,
                        'fecha_inicio' => $fecha_inicio,
                        'fecha_fin' => $fecha_fin,
                        'activo' => 1
                    ], 
                    "Periodos Académicos", 
                    "Nuevo período académico creado"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría crearPeriodoAcademico: " . $e->getMessage());
            }
        }
        
        return [
            'success' => true,
            'message' => 'Período académico creado exitosamente',
            'id_periodo' => $periodo_id
        ];
        
    } catch (Exception $e) {
        error_log("Error en crearPeriodoAcademico: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL CREAR PERIODO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "periodos_academicos", 
                    null, 
                    null, 
                    [
                        'nombre_periodo' => $nombre,
                        'fecha_inicio' => $fecha_inicio,
                        'fecha_fin' => $fecha_fin,
                        'error' => $e->getMessage()
                    ], 
                    "Periodos Académicos", 
                    "Error al crear período académico"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error crearPeriodoAcademico: " . $auditError->getMessage());
            }
        }
        
        return [
            'success' => false,
            'message' => 'Error al crear período académico: ' . $e->getMessage()
        ];
    }
}

/**
 * Actualiza un periodo académico existente
 * @param mysqli $db Conexión a la base de datos
 * @param int $id ID del periodo
 * @param string $nombre Nuevo nombre del periodo
 * @param string $fecha_inicio Nueva fecha de inicio
 * @param string $fecha_fin Nueva fecha de fin
 * @return array Resultado de la operación
 */
function actualizarPeriodoAcademico($db, $id, $nombre, $fecha_inicio, $fecha_fin) {
    try {
        // Obtener datos actuales para auditoría
        $periodo_actual = obtenerPeriodoPorId($db, $id);
        if (!$periodo_actual) {
            return [
                'success' => false,
                'message' => 'Período académico no encontrado'
            ];
        }

        // Validar fechas
        if (empty($nombre) || empty($fecha_inicio) || empty($fecha_fin)) {
            return [
                'success' => false,
                'message' => 'Todos los campos son obligatorios'
            ];
        }

        // Validar que la fecha de fin sea posterior a la de inicio
        if (strtotime($fecha_fin) <= strtotime($fecha_inicio)) {
            return [
                'success' => false,
                'message' => 'La fecha de fin debe ser posterior a la fecha de inicio'
            ];
        }

        $query = "UPDATE periodos_academicos SET 
                  nombre_periodo = ?,
                  fecha_inicio = ?,
                  fecha_fin = ?
                  WHERE id_periodo = ?";
        $stmt = $db->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("sssi", $nombre, $fecha_inicio, $fecha_fin, $id);
        
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        
        // REGISTRAR EN AUDITORÍA - ACTUALIZACIÓN DE PERIODO ACADÉMICO
        if ($affected_rows > 0 && function_exists('registrarAuditoria')) {
            try {
                $valores_antiguos_audit = [];
                $valores_nuevos_audit = [];
                
                // Comparar campos modificados
                $campos_auditar = ['nombre_periodo', 'fecha_inicio', 'fecha_fin'];
                
                foreach ($campos_auditar as $campo) {
                    $valor_antiguo = $periodo_actual[$campo] ?? null;
                    $valor_nuevo = $$campo ?? null;
                    
                    if ($valor_antiguo != $valor_nuevo) {
                        $valores_antiguos_audit[$campo] = $valor_antiguo;
                        $valores_nuevos_audit[$campo] = $valor_nuevo;
                    }
                }
                
                if (!empty($valores_nuevos_audit)) {
                    registrarAuditoria(
                        "UPDATE", 
                        "periodos_academicos", 
                        $id, 
                        $valores_antiguos_audit, 
                        $valores_nuevos_audit, 
                        "Periodos Académicos", 
                        "Actualización de período académico"
                    );
                }
            } catch (Exception $e) {
                error_log("Error en auditoría actualizarPeriodoAcademico: " . $e->getMessage());
            }
        }
        
        return [
            'success' => true,
            'message' => 'Período académico actualizado exitosamente',
            'affected_rows' => $affected_rows
        ];
        
    } catch (Exception $e) {
        error_log("Error en actualizarPeriodoAcademico: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL ACTUALIZAR PERIODO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "periodos_academicos", 
                    $id, 
                    null, 
                    [
                        'nombre_periodo' => $nombre,
                        'fecha_inicio' => $fecha_inicio,
                        'fecha_fin' => $fecha_fin,
                        'error' => $e->getMessage()
                    ], 
                    "Periodos Académicos", 
                    "Error al actualizar período académico"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error actualizarPeriodoAcademico: " . $auditError->getMessage());
            }
        }
        
        return [
            'success' => false,
            'message' => 'Error al actualizar período académico: ' . $e->getMessage()
        ];
    }
}

/**
 * Cambia el estado de un período académico
 * @param mysqli $db Conexión a la base de datos
 * @param int $periodo_id ID del período
 * @param int $nuevo_estado Nuevo estado (1 para activo, 0 para inactivo)
 * @return array Resultado de la operación
 */
function cambiarEstadoPeriodo($db, $periodo_id, $nuevo_estado) {
    try {
        // Obtener información actual del período para auditoría
        $periodo_actual = obtenerPeriodoPorId($db, $periodo_id);
        if (!$periodo_actual) {
            return [
                'success' => false,
                'message' => 'Período académico no encontrado'
            ];
        }

        $stmt = $db->prepare("UPDATE periodos_academicos SET activo = ? WHERE id_periodo = ?");
        
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("ii", $nuevo_estado, $periodo_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        
        // Si se activó el período, actualizar estados de secciones
        if ($nuevo_estado == 1 && $affected_rows > 0) {
            actualizarEstadoSeccionesDePeriodo($db, $periodo_id);
        }
        
        // REGISTRAR EN AUDITORÍA - CAMBIO DE ESTADO DE PERIODO
        if ($affected_rows > 0 && function_exists('registrarAuditoria')) {
            try {
                $estado_texto_anterior = $periodo_actual['activo'] ? 'Activo' : 'Inactivo';
                $estado_texto_nuevo = $nuevo_estado ? 'Activo' : 'Inactivo';
                
                registrarAuditoria(
                    "UPDATE", 
                    "periodos_academicos", 
                    $periodo_id, 
                    [
                        'activo' => $periodo_actual['activo'],
                        'estado_anterior' => $estado_texto_anterior
                    ], 
                    [
                        'activo' => $nuevo_estado,
                        'estado_nuevo' => $estado_texto_nuevo,
                        'nombre_periodo' => $periodo_actual['nombre_periodo'],
                        'fecha_inicio' => $periodo_actual['fecha_inicio'],
                        'fecha_fin' => $periodo_actual['fecha_fin']
                    ], 
                    "Periodos Académicos", 
                    "Cambio de estado de período académico"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría cambiarEstadoPeriodo: " . $e->getMessage());
            }
        }
        
        return [
            'success' => true,
            'message' => 'Estado del período actualizado exitosamente',
            'affected_rows' => $affected_rows
        ];
        
    } catch (Exception $e) {
        error_log("Error al cambiar estado del período: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL CAMBIAR ESTADO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "periodos_academicos", 
                    $periodo_id, 
                    null, 
                    [
                        'nuevo_estado' => $nuevo_estado,
                        'error' => $e->getMessage()
                    ], 
                    "Periodos Académicos", 
                    "Error al cambiar estado de período académico"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error cambiarEstadoPeriodo: " . $auditError->getMessage());
            }
        }
        
        return [
            'success' => false,
            'message' => 'Error al cambiar estado del período: ' . $e->getMessage()
        ];
    }
}

/**
 * Actualiza el estado de todas las secciones de un período cuando este se activa
 * @param mysqli $db Conexión a la base de datos
 * @param int $periodo_id ID del período académico
 */
function actualizarEstadoSeccionesDePeriodo($db, $periodo_id) {
    try {
        // Obtener todas las secciones del período
        $stmt = $db->prepare("SELECT id_seccion FROM secciones WHERE id_periodo = ?");
        
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("i", $periodo_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $secciones = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        // Contador para auditoría
        $secciones_actualizadas = 0;
        
        // Actualizar el estado de cada sección
        foreach ($secciones as $seccion) {
            $count = contarEstudiantesActivos($db, $seccion['id_seccion']);
            $resultado = actualizarEstadoSeccion($db, $seccion['id_seccion'], $count);
            
            if ($resultado) {
                $secciones_actualizadas++;
            }
        }
        
        // REGISTRAR EN AUDITORÍA - ACTUALIZACIÓN MASIVA DE SECCIONES
        if ($secciones_actualizadas > 0 && function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "UPDATE", 
                    "secciones", 
                    null, 
                    null, 
                    [
                        'periodo_id' => $periodo_id,
                        'secciones_actualizadas' => $secciones_actualizadas,
                        'total_secciones' => count($secciones)
                    ], 
                    "Periodos Académicos", 
                    "Actualización masiva de estados de secciones por activación de período"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría actualizarEstadoSeccionesDePeriodo: " . $e->getMessage());
            }
        }
        
    } catch (Exception $e) {
        error_log("Error en actualizarEstadoSeccionesDePeriodo: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR EN ACTUALIZACIÓN MASIVA
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "secciones", 
                    null, 
                    null, 
                    [
                        'periodo_id' => $periodo_id,
                        'error' => $e->getMessage()
                    ], 
                    "Periodos Académicos", 
                    "Error en actualización masiva de secciones"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error actualizarEstadoSeccionesDePeriodo: " . $auditError->getMessage());
            }
        }
    }
}

/**
 * Obtiene un período académico por su ID
 * @param mysqli $db Conexión a la base de datos
 * @param int $id ID del período
 * @return array|null Datos del período o null si no existe
 */
function obtenerPeriodoPorId($db, $id) {
    try {
        $query = "SELECT * FROM periodos_academicos WHERE id_periodo = ?";
        $stmt = $db->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("i", $id);
        
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $periodo = $result->fetch_assoc();
        $stmt->close();
        
        return $periodo;
        
    } catch (Exception $e) {
        error_log("Error en obtenerPeriodoPorId: " . $e->getMessage());
        return null;
    }
}

/**
 * Desactiva automáticamente los períodos académicos vencidos
 * @param mysqli $db Conexión a la base de datos
 * @return int Número de períodos desactivados
 */
function desactivarPeriodosVencidos($db) {
    try {
        $fecha_actual = date('Y-m-d');
        
        $query = "UPDATE periodos_academicos SET activo = 0 
                  WHERE fecha_fin < ? AND activo = 1";
        $stmt = $db->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("s", $fecha_actual);
        
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        
        // REGISTRAR EN AUDITORÍA - DESACTIVACIÓN AUTOMÁTICA DE PERIODOS VENCIDOS
        if ($affected_rows > 0 && function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "UPDATE", 
                    "periodos_academicos", 
                    null, 
                    null, 
                    [
                        'periodos_desactivados' => $affected_rows,
                        'fecha_actual' => $fecha_actual
                    ], 
                    "Periodos Académicos", 
                    "Desactivación automática de períodos académicos vencidos"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría desactivarPeriodosVencidos: " . $e->getMessage());
            }
        }
        
        return $affected_rows;
        
    } catch (Exception $e) {
        error_log("Error en desactivarPeriodosVencidos: " . $e->getMessage());
        return 0;
    }
}






// PANEL DE ESTUDIANTE, SECCIONES ***********************************************************************



/**
 * Verifica si un usuario es estudiante (campo estudiante = 1)
 */
function esEstudiante($db, $user_id) {
    $sql = "SELECT estudiante FROM users WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        return $user['estudiante'] == 1;
    }
    
    return false;
}

/**
 * Obtiene las secciones en las que está inscrito un estudiante
 */
function obtenerSeccionesEstudiante($db, $estudiante_id) {
    $sql = "SELECT s.*, c.nombre_carrera, t.numero_trayecto, pa.nombre_periodo 
            FROM secciones s
            JOIN estudiante_seccion es ON s.id_seccion = es.id_seccion
            JOIN carreras c ON s.id_carrera = c.id_carrera
            JOIN trayectos t ON s.id_trayecto = t.id_trayecto
            JOIN periodos_academicos pa ON s.id_periodo = pa.id_periodo
            WHERE es.id_usuario = ? AND s.estatus = 'activa'";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $estudiante_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } catch (mysqli_sql_exception $e) {
        error_log("Error al obtener secciones: " . $e->getMessage());
        return [];
    }
}

/**
 * Obtiene los compañeros de sección (excluyendo al estudiante actual)
 */
function obtenerCompañerosSeccion($db, $seccion_id, $estudiante_id) {
    $sql = "SELECT u.id, u.nombre, u.username, u.email, u.tlf 
            FROM users u
            JOIN estudiante_seccion es ON u.id = es.id_usuario
            WHERE es.id_seccion = ? AND es.id_usuario != ? AND u.estudiante = 1";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $seccion_id, $estudiante_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } catch (mysqli_sql_exception $e) {
        error_log("Error al obtener compañeros: " . $e->getMessage());
        return [];
    }
}


//AUDITORIA ***********************************************************************


/**
 * Registrar acción en el sistema de auditoría
 */
function registrarAuditoria($accion, $tabla_afectada = null, $registro_id = null, 
                           $valores_antiguos = null, $valores_nuevos = null, 
                           $modulo_sistema = null, $descripcion = null) {
    global $db;
    
    // Solo registrar si hay un usuario logueado
    if (!isset($_SESSION['user']['id'])) {
        return false;
    }
    
    $usuario_id = $_SESSION['user']['id'];
    $ip_origen = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    // Convertir arrays a JSON para almacenamiento
    $valores_antiguos_json = $valores_antiguos ? json_encode($valores_antiguos) : null;
    $valores_nuevos_json = $valores_nuevos ? json_encode($valores_nuevos) : null;
    
    $query = "INSERT INTO auditoria (usuario_id, accion, tabla_afectada, registro_id, 
              fecha_hora, valores_antiguos, valores_nuevos, ip_origen, user_agent, 
              modulo_sistema, descripcion)
              VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("ississssss", $usuario_id, $accion, $tabla_afectada, $registro_id,
                     $valores_antiguos_json, $valores_nuevos_json, $ip_origen, 
                     $user_agent, $modulo_sistema, $descripcion);
    
    return $stmt->execute();
}



/**
 * Función para registrar el inicio de sesión
 */
function registrarLoginAuditoria($usuario_id, $exitoso = true) {
    $descripcion = $exitoso ? "Inicio de sesión exitoso" : "Intento de inicio de sesión fallido";
    
    registrarAuditoria(
        "LOGIN", 
        "users", 
        $usuario_id, 
        null, 
        null, 
        "Autenticación", 
        $descripcion
    );
}

/**
 * Función para registrar el cierre de sesión
 */
function registrarLogoutAuditoria($usuario_id) {
    registrarAuditoria(
        "LOGOUT", 
        "users", 
        $usuario_id, 
        null, 
        null, 
        "Autenticación", 
        "Cierre de sesión"
    );
}

/**
 * Obtener usuarios para el filtro de auditoría
 */
function obtenerUsuariosParaFiltro() {
    global $db;
    
    $query = "SELECT id, nombre, idusuario FROM users ORDER BY nombre";
    $result = $db->query($query);
    
    $usuarios = [];
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
    
    return $usuarios;
}

/**
 * Verificar si el usuario actual es administrador
 */
function esAdministrador() {
    if (!isset($_SESSION['user']['tipo_usuario'])) {
        return false;
    }
    
    // Asumiendo que el tipo_usuario 1 es administrador
    return $_SESSION['user']['tipo_usuario'] == 1;
}


/**
 * Obtener datos completos del estudiante para auditoría
 */
function obtenerDatosEstudianteCompletos($id) {
    global $db;
    
    $query = "SELECT u.*, c.nombre as nombre_carrera, g.nombre as nombre_genero
              FROM users u 
              LEFT JOIN carreras c ON u.carrera = c.id 
              LEFT JOIN generos g ON u.genero = g.id 
              WHERE u.id = ?";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return false;
    }
    
    return $result->fetch_assoc();
}

/**
 * Detectar cambios detallados para auditoría
 */
function detectarCambiosDetallados($datos_antiguos, $datos_nuevos) {
    $campos_modificados = [];
    $valores_antiguos = [];
    $valores_nuevos = [];
    
    $nombres_campos = [
        'nombre' => 'Nombre completo',
        'username' => 'Cédula/Usuario',
        'email' => 'Correo electrónico',
        'tlf' => 'Teléfono principal',
        'num_telf_opc' => 'Teléfono opcional',
        'carrera' => 'Programa/Carrera',
        'genero' => 'Género',
        'fecha_nac' => 'Fecha de nacimiento',
        'fecha_ingreso' => 'Fecha de ingreso',
        'status' => 'Estado'
    ];
    
    foreach ($nombres_campos as $campo => $nombre_legible) {
        $valor_antiguo = $datos_antiguos[$campo] ?? null;
        $valor_nuevo = $datos_nuevos[$campo] ?? null;
        
        if (normalizarValor($valor_antiguo, $campo) !== normalizarValor($valor_nuevo, $campo)) {
            $campos_modificados[$campo] = $nombre_legible;
            $valores_antiguos[$nombre_legible] = formatearValorParaAuditoria($valor_antiguo, $campo, $datos_antiguos);
            $valores_nuevos[$nombre_legible] = formatearValorParaAuditoria($valor_nuevo, $campo, $datos_nuevos);
        }
    }
    
    return [
        'campos_modificados' => $campos_modificados,
        'valores_antiguos' => $valores_antiguos,
        'valores_nuevos' => $valores_nuevos
    ];
}

/**
 * Normalizar valor para comparación
 */
function normalizarValor($valor, $campo) {
    if ($valor === null || $valor === '') {
        return null;
    }
    
    if (in_array($campo, ['carrera', 'genero', 'status'])) {
        return (int)$valor;
    }
    
    if (in_array($campo, ['fecha_nac', 'fecha_ingreso'])) {
        return $valor ? date('Y-m-d', strtotime($valor)) : null;
    }
    
    return trim($valor);
}

/**
 * Formatear valor para auditoría
 */
function formatearValorParaAuditoria($valor, $campo, $datos_completos = []) {
    if ($valor === null || $valor === '') {
        return 'No especificado';
    }
    
    switch ($campo) {
        case 'carrera':
            return $datos_completos['nombre_carrera'] ?? 'Carrera ' . $valor;
            
        case 'genero':
            return $datos_completos['nombre_genero'] ?? 'Género ' . $valor;
            
        case 'status':
            return $valor == 1 ? 'Activo' : 'Inactivo';
            
        case 'fecha_nac':
        case 'fecha_ingreso':
            return $valor ? date('d/m/Y', strtotime($valor)) : 'No especificado';
            
        default:
            return $valor;
    }
}

/**
 * Generar descripción detallada para auditoría
 */
function generarDescripcionAuditoria($cambios) {
    $descripcion = "Edición de estudiante. Campos modificados:\n";
    
    foreach ($cambios['campos_modificados'] as $campo => $nombre_legible) {
        $valor_antiguo = $cambios['valores_antiguos'][$nombre_legible] ?? 'No especificado';
        $valor_nuevo = $cambios['valores_nuevos'][$nombre_legible] ?? 'No especificado';
        
        $descripcion .= "• {$nombre_legible}: ";
        $descripcion .= "DE '{$valor_antiguo}' A '{$valor_nuevo}'\n";
    }
    
    return $descripcion;
}








//VISTA AUDITORIA ***********************************************************************


// ==============================================
// ARCHIVO: funciones/functions.php
// Funciones adicionales para el sistema de auditoría
// ==============================================

/**
 * Obtener registros de auditoría con filtros opcionales (versión mejorada)
 */
function obtenerRegistrosAuditoria($limite = 100, $fecha_inicio = null, $fecha_fin = null, $usuario_cedula = null, $accion = null, $modulo = null) {
    global $db;
    
    $query = "SELECT a.*, u.nombre as usuario_nombre, u.idusuario as usuario_cedula
              FROM auditoria a
              INNER JOIN users u ON a.usuario_id = u.id
              WHERE 1=1";
    
    $params = [];
    $types = "";
    
    if ($fecha_inicio) {
        $query .= " AND DATE(a.fecha_hora) >= ?";
        $params[] = $fecha_inicio;
        $types .= "s";
    }
    
    if ($fecha_fin) {
        $query .= " AND DATE(a.fecha_hora) <= ?";
        $params[] = $fecha_fin;
        $types .= "s";
    }
    
    if ($usuario_cedula) {
        $query .= " AND u.idusuario = ?";
        $params[] = $usuario_cedula;
        $types .= "s";
    }
    
    if ($accion) {
        $query .= " AND a.accion = ?";
        $params[] = $accion;
        $types .= "s";
    }
    
    if ($modulo) {
        $query .= " AND a.modulo_sistema = ?";
        $params[] = $modulo;
        $types .= "s";
    }
    
    $query .= " ORDER BY a.fecha_hora DESC LIMIT ?";
    $params[] = $limite;
    $types .= "i";
    
    $stmt = $db->prepare($query);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $registros = [];
    while ($row = $result->fetch_assoc()) {
        // Decodificar los JSON si existen
        if ($row['valores_antiguos']) {
            $row['valores_antiguos'] = json_decode($row['valores_antiguos'], true);
        }
        if ($row['valores_nuevos']) {
            $row['valores_nuevos'] = json_decode($row['valores_nuevos'], true);
        }
        $registros[] = $row;
    }
    
    return $registros;
}

/**
 * Obtener acciones únicas para filtros
 */
function obtenerAccionesUnicas() {
    global $db;
    
    $query = "SELECT DISTINCT accion FROM auditoria ORDER BY accion";
    $result = $db->query($query);
    
    $acciones = [];
    while ($row = $result->fetch_assoc()) {
        $acciones[] = $row['accion'];
    }
    
    return $acciones;
}

/**
 * Obtener módulos únicos para filtros
 */
function obtenerModulosUnicos() {
    global $db;
    
    $query = "SELECT DISTINCT modulo_sistema FROM auditoria WHERE modulo_sistema IS NOT NULL ORDER BY modulo_sistema";
    $result = $db->query($query);
    
    $modulos = [];
    while ($row = $result->fetch_assoc()) {
        $modulos[] = $row['modulo_sistema'];
    }
    
    return $modulos;
}

/**
 * Contar registros de hoy
 */
function contarRegistrosHoy() {
    global $db;
    
    $query = "SELECT COUNT(*) as total FROM auditoria WHERE DATE(fecha_hora) = CURDATE()";
    $result = $db->query($query);
    
    return $result->fetch_assoc()['total'] ?? 0;
}

/**
 * Contar acciones por tipo
 */
function contarAccionesPorTipo($tipo) {
    global $db;
    
    $query = "SELECT COUNT(*) as total FROM auditoria WHERE accion = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $tipo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc()['total'] ?? 0;
}



//MEMBRETES Y REPORTES PDF  ***********************************************************************


// Función para generar el código JavaScript del membrete
function generarMembreteJS() {
    $hoy = new DateTime();
    $fecha = $hoy->format('d/m/Y');
    
    return "
    function agregarMembretePDF(doc, pageWidth, margin) {
        // Cargar imagen del logo
        const logoImg = new Image();
        logoImg.crossOrigin = 'Anonymous';
        logoImg.src = '../images/uptpc.png';
        
        return new Promise((resolve) => {
            logoImg.onload = function() {
                // Agregar logo (arriba a la izquierda)
                doc.addImage(logoImg, 'PNG', margin, 10, 20, 20);
                
                // Agregar texto del membrete con fuente más pequeña
                doc.setFontSize(10); // Reducido de 12 a 10
                doc.setFont(undefined, 'bold');
                doc.text('REPÚBLICA BOLIVARIANA DE VENEZUELA', pageWidth / 2, 15, { align: 'center' });
                doc.text('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA', pageWidth / 2, 20, { align: 'center' });
                doc.text('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO', pageWidth / 2, 25, { align: 'center' });
                
                // Agregar fecha con fuente más pequeña
                doc.setFontSize(9); // Reducido para la fecha
                doc.setFont(undefined, 'normal');
                doc.text('$fecha', pageWidth - margin, 15, { align: 'right' });
                
                resolve(35); // Retornar posición Y después del membrete
            };
            
            logoImg.onerror = function() {
                // Fallback sin imagen con fuente más pequeña
                doc.setFontSize(10); // Reducido de 12 a 10
                doc.setFont(undefined, 'bold');
                doc.text('República Bolivariana de Venezuela', pageWidth / 2, 15, { align: 'center' });
                doc.text('Ministerio del Poder Popular para la Educación Universitaria', pageWidth / 2, 20, { align: 'center' });
                doc.text('Universidad Politécnica Territorial de Puerto Cabello', pageWidth / 2, 25, { align: 'center' });
                
                doc.setFontSize(9); // Reducido para la fecha
                doc.setFont(undefined, 'normal');
                doc.text('$fecha', pageWidth / 2, 32, { align: 'center' });
                
                resolve(40); // Retornar posición Y después del membrete
            };
        });
    }
    ";
}

// Función para generar PDF desde HTML
function generarPDFDesdeHTML($elementoHTML, $nombreArchivo = 'documento.pdf') {
    // Insertar el código JS del membrete (define agregarMembretePDF)
    echo generarMembreteJS();

    // Generar el PDF en cliente: primero obtener startY desde agregarMembretePDF (devuelve Promise)
    echo "<script>
        (function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'mm', 'a4');
            const margin = 10;
            const pageWidth = doc.internal.pageSize.getWidth();

            // Obtener la posición de inicio del contenido después del membrete
            agregarMembretePDF(doc, pageWidth, margin).then(function(startY) {
                // Capturar el contenido HTML y agregarlo al PDF
                html2canvas(document.getElementById('$elementoHTML'), {
                    scale: 2,
                    useCORS: true,
                    logging: false
                }).then(function(canvas) {
                    const imgData = canvas.toDataURL('image/jpeg', 1.0);
                    const imgWidth = pageWidth - (margin * 2);
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;

                    // Agregar contenido al PDF
                    doc.addImage(imgData, 'JPEG', margin, startY, imgWidth, imgHeight);

                    // Guardar el PDF
                    doc.save('$nombreArchivo');
                });
            }).catch(function(err) {
                console.error('Error al generar membrete:', err);
            });
        })();
    </script>";
}




//CARGA DE NOTAS ***********************************************************************


// FUNCIÓN PARA OBTENER NOTAS DEFINITIVAS (SOLO LECTURA - SIN AUDITORÍA)
function obtenerNotasDefinitivas($estudiante_id, $materia_id) {
    global $db;
    $query = "SELECT * FROM notas_definitivas 
              WHERE id_usuario = ? AND id_materia = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $estudiante_id, $materia_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

// FUNCIÓN PARA OBTENER DATOS COMPLETOS DE notas_pendientes (SOLO LECTURA - SIN AUDITORÍA)
function obtenerNotasPendientesEstudiante($id_estudiante, $id_materia, $id_periodo, $id_docente) {
    global $db;
    
    $query = "SELECT * FROM notas_pendientes 
              WHERE id_usuario = ? 
              AND id_materia = ? 
              AND id_periodo = ? 
              AND id_docente = ? 
              LIMIT 1";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iiii", $id_estudiante, $id_materia, $id_periodo, $id_docente);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

// FUNCIÓN MEJORADA PARA OBTENER ESTADO (CORREGIDA) (SOLO LECTURA - SIN AUDITORÍA)
function obtenerEstadoCorregido($id_estudiante, $id_materia, $id_periodo, $id_docente) {
    global $db;
    
    // PRIMERO: Verificar si está en notas_definitivas (APROBADA)
    $query_definitivas = "SELECT id FROM notas_definitivas 
                         WHERE id_usuario = ? AND id_materia = ?";
    $stmt = $db->prepare($query_definitivas);
    $stmt->bind_param("ii", $id_estudiante, $id_materia);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return 'aprobada'; // Está en notas_definitivas = APROBADA
    }
    
    // SEGUNDO: Verificar si existe en notas_pendientes
    $query_pendientes = "SELECT id, estado FROM notas_pendientes 
                        WHERE id_usuario = ? AND id_materia = ? AND id_periodo = ? AND id_docente = ?";
    $stmt = $db->prepare($query_pendientes);
    $stmt->bind_param("iiii", $id_estudiante, $id_materia, $id_periodo, $id_docente);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Si tiene campo estado, usarlo; si no, asumir "en_revision"
        if (isset($row['estado']) && $row['estado'] === 'rechazada') {
            return 'rechazada';
        }
        return 'en_revision'; // SIEMPRE muestra como "En Revisión" si está en pendientes
    }
    
    // No existe en ninguna tabla
    return 'pendiente';
}

/**
 * Obtiene información completa de una materia incluyendo trayecto (SOLO LECTURA - SIN AUDITORÍA)
 */
function obtenerInfoMateria($materia_id) {
    global $db;
    $query = "SELECT m.*, t.numero_trayecto 
              FROM materias m 
              LEFT JOIN trayectos t ON m.trayecto = t.id_trayecto 
              WHERE m.id_materia = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $materia_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    // Si no encuentra el trayecto, intentar obtener solo la información de la materia
    $query = "SELECT * FROM materias WHERE id_materia = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $materia_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $materia = $result->fetch_assoc();
        
        // Si el trayecto es 0, establecer manualmente el número de trayecto
        if ($materia['trayecto'] == 0) {
            $materia['numero_trayecto'] = 0;
        }
        
        return $materia;
    }
    
    return null;
}

/**
 * Obtiene todos los estudiantes de una sección específica (SOLO LECTURA - SIN AUDITORÍA)
 */
function obtenerEstudiantesPorSeccion($seccion_id) {
    global $db;
    $query = "SELECT u.id, u.nombre, u.idusuario 
              FROM users u
              INNER JOIN estudiante_seccion es ON u.id = es.id_usuario
              WHERE es.id_seccion = ? AND u.estudiante = 1
              ORDER BY u.nombre";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $seccion_id);
    $stmt->execute();
    return $stmt->get_result();
}

/**
 * Obtiene las notas de un estudiante en una materia específica (SOLO LECTURA - SIN AUDITORÍA)
 */
function obtenerNotasEstudiante($estudiante_id, $materia_id) {
    global $db;
    $query = "SELECT * FROM notas_pendientes 
              WHERE id_usuario = ? AND id_materia = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $estudiante_id, $materia_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

/**
 * Obtiene el período académico de una sección (SOLO LECTURA - SIN AUDITORÍA)
 */
function obtenerPeriodoSeccion($seccion_id) {
    global $db;
    $query = "SELECT id_periodo FROM secciones WHERE id_seccion = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $seccion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['id_periodo'];
}

/**
 * Obtiene información del trayecto de una sección (SOLO LECTURA - SIN AUDITORÍA)
 */
function obtenerTrayectoSeccion($seccion_id) {
    global $db;
    $query = "SELECT t.id_trayecto, t.numero_trayecto 
              FROM secciones s 
              INNER JOIN trayectos t ON s.id_trayecto = t.id_trayecto 
              WHERE s.id_seccion = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $seccion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

/**
 * Determina el trayecto a mostrar basado en el ID de trayecto de la sección (SOLO LÓGICA - SIN AUDITORÍA)
 */
function determinarTrayectoAMostrar($id_trayecto_seccion) {
    switch ($id_trayecto_seccion) {
        case 1: return 0; // Trayecto Inicial
        case 2: return 1; // Trayecto 1
        case 3: return 2; // Trayecto 2
        case 4: return 3; // Trayecto 3
        case 5: return 4; // Trayecto 4
        default: return 0;
    }
}

// Helper para convertir UTF-8 a ISO-8859-1 para FPDF
if (!function_exists('to_iso')) {
    function to_iso($s) {
        if ($s === null) return '';
        // Convertir a mayúsculas en UTF-8 y luego convertir a ISO-8859-1 para FPDF
        $upper = function_exists('mb_strtoupper') ? mb_strtoupper($s, 'UTF-8') : strtoupper($s);
        return @iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $upper);
    }
}

/**
 * Insertar espacios en palabras largas para evitar que FPDF no las parta.
 * Divide secuencias largas sin espacios cada $limit caracteres.
 */
function insertarEspaciosEnPalabrasLargas($s, $limit = 30) {
    if ($s === null) return '';
    if (!function_exists('mb_strlen')) return $s;
    $parts = preg_split('/(\s+)/u', $s, -1, PREG_SPLIT_DELIM_CAPTURE);
    foreach ($parts as &$p) {
        // solo procesar partes que no sean espacios
        if (trim($p) === '') continue;
        $len = mb_strlen($p, 'UTF-8');
        if ($len > $limit) {
            // insertar espacios cada $limit chars
            $new = '';
            for ($i = 0; $i < $len; $i += $limit) {
                $chunk = mb_substr($p, $i, $limit, 'UTF-8');
                $new .= $chunk;
                if ($i + $limit < $len) $new .= ' ';
            }
            $p = $new;
        }
    }
    return implode('', $parts);
}

/**
 * Calcular cuántas líneas ocupará un texto en FPDF para un ancho dado.
 */
function calcularLineasFPDF($pdf, $txt, $w) {
    if (!$pdf) return 1;
    // manejar saltos de línea explícitos
    $lines = 0;
    $parts = explode("\n", (string)$txt);
    foreach ($parts as $part) {
        $part = trim($part);
        if ($part === '') { $lines++; continue; }
        $words = preg_split('/(\s+)/u', $part, -1, PREG_SPLIT_NO_EMPTY);
        $cur = '';
        foreach ($words as $word) {
            $test = ($cur === '') ? $word : $cur . ' ' . $word;
            $wtest = $pdf->GetStringWidth($test);
            if ($wtest <= $w - 2*$pdf->cMargin) {
                $cur = $test;
            } else {
                // word itself may be longer than width -> split
                if ($pdf->GetStringWidth($word) > $w - 2*$pdf->cMargin) {
                    // split by characters
                    $len = mb_strlen($word, 'UTF-8');
                    $tmp = '';
                    for ($i=0;$i<$len;$i++) {
                        $ch = mb_substr($word,$i,1,'UTF-8');
                        if ($pdf->GetStringWidth($tmp.$ch) <= $w - 2*$pdf->cMargin) {
                            $tmp .= $ch;
                        } else {
                            $lines++; $tmp = $ch;
                        }
                    }
                    if ($tmp !== '') {
                        $cur = $tmp;
                    } else {
                        $cur = '';
                    }
                } else {
                    // move current to next line
                    $lines++;
                    $cur = $word;
                }
            }
        }
        if ($cur !== '') $lines++;
    }
    return max(1, $lines);
}

// Función global para agregar membrete a un objeto FPDF
if (!function_exists('agregarMembreteFPDF')) {
    function agregarMembreteFPDF($pdf, $margin = 10) {
        $pageWidth = $pdf->GetPageWidth();

        // Logo (si existe)
        $logoPath = __DIR__ . '/../images/uptpc.png';
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, $margin, 8, 20, 20);
        }

        // Texto del membrete
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetY(15);
        $pdf->Cell(0, 5, to_iso('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 5, to_iso('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
        $pdf->Cell(0, 5, to_iso('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'C');

        // Fecha (a la derecha)
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY($pageWidth - $margin - 30, 10);
        $pdf->Cell(30, 5, date('d/m/Y'), 0, 0, 'R');

        // Línea separadora
        $pdf->SetY(35);
        $pdf->Cell(0, 0, '', 'T');
        $pdf->Ln(5);
    }
}

// Obtener información del grupo (SOLO LECTURA - SIN AUDITORÍA)
function obtenerInfoGrupo($docente_id, $materia_id, $periodo_id) {
    global $db;
    
    $query = "SELECT ud.nombre as nombre_docente, m.nombre_materia, m.cod_materia,
                     pa.nombre_periodo, s.codigo_seccion, c.nombre_carrera, 
                     t.nombre_trayecto, t.id_trayecto, t.numero_trayecto
              FROM notas_pendientes np
              INNER JOIN users ud ON np.id_docente = ud.id
              INNER JOIN materias m ON np.id_materia = m.id_materia
              INNER JOIN periodos_academicos pa ON np.id_periodo = pa.id_periodo
              INNER JOIN docente_seccion ds ON np.id_docente = ds.id_usuario 
                                           AND np.id_materia = ds.id_materia
              INNER JOIN secciones s ON ds.id_seccion = s.id_seccion
              INNER JOIN carreras c ON s.id_carrera = c.id_carrera
              INNER JOIN trayectos t ON s.id_trayecto = t.id_trayecto
              WHERE np.id_docente = ? 
              AND np.id_materia = ? 
              AND np.id_periodo = ?
              LIMIT 1";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Obtener estudiantes del grupo (MODIFICADA para excluir rechazados) (SOLO LECTURA - SIN AUDITORÍA)
function obtenerEstudiantesGrupo($docente_id, $materia_id, $periodo_id) {
    global $db;
    
    $query = "SELECT np.*, u.nombre as nombre_estudiante, u.idusuario as cedula
              FROM notas_pendientes np
              INNER JOIN users u ON np.id_usuario = u.id
              WHERE np.id_docente = ? 
              AND np.id_materia = ? 
              AND np.id_periodo = ?
              AND np.estado = 'pendiente'  -- SOLO notas pendientes, no rechazadas
              ORDER BY u.nombre";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Obtener información de soporte del grupo (SOLO LECTURA - SIN AUDITORÍA)
function obtenerSoporteGrupo($docente_id, $materia_id, $periodo_id) {
    global $db;
    
    $query = "SELECT DISTINCT soporte, tipo_archivo, fecha_subida
              FROM notas_pendientes 
              WHERE id_docente = ? 
              AND id_materia = ? 
              AND id_periodo = ?
              AND soporte IS NOT NULL
              AND estado = 'pendiente'
              LIMIT 1";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

// Calcular promedio según el id_trayecto de la sección (SOLO CÁLCULO - SIN AUDITORÍA)
function calcularPromedioPorTrayecto($nota, $id_trayecto) {
    $suma = 0;
    $count = 0;
    
    // Determinar qué trayectos promediar según el id_trayecto de la sección
    switch ($id_trayecto) {
        case 1: // Trayecto Inicial - Solo trayecto_0
            if ($nota['trayecto_0'] !== null) {
                $suma = $nota['trayecto_0'];
                $count = 1;
            }
            break;
            
        case 2: // Trayecto 1 - Solo trayecto_1
            if ($nota['trayecto_1'] !== null) {
                $suma = $nota['trayecto_1'];
                $count = 1;
            }
            break;
            
        case 3: // Trayecto 2 - Solo trayecto_2
            if ($nota['trayecto_2'] !== null) {
                $suma = $nota['trayecto_2'];
                $count = 1;
            }
            break;
            
        case 4: // Trayecto 3 - Solo trayecto_3
            if ($nota['trayecto_3'] !== null) {
                $suma = $nota['trayecto_3'];
                $count = 1;
            }
            break;
            
        case 5: // Trayecto 4 - Solo trayecto_4
            if ($nota['trayecto_4'] !== null) {
                $suma = $nota['trayecto_4'];
                $count = 1;
            }
            break;
            
        default:
            // Por defecto, calcular todos los trayectos (no debería pasar)
            for ($i = 0; $i <= 4; $i++) {
                if ($nota['trayecto_' . $i] !== null) {
                    $suma += $nota['trayecto_' . $i];
                    $count++;
                }
            }
    }
    
    return $count > 0 ? round($suma / $count, 1) : 0;
}

// Obtener estadísticas del grupo según el id_trayecto (MODIFICADA para excluir rechazados) (SOLO LECTURA - SIN AUDITORÍA)
function obtenerEstadisticasGrupo($docente_id, $materia_id, $periodo_id, $id_trayecto) {
    global $db;
    
    $query = "SELECT np.trayecto_0, np.trayecto_1, np.trayecto_2, np.trayecto_3, np.trayecto_4
              FROM notas_pendientes np
              WHERE np.id_docente = ? 
              AND np.id_materia = ? 
              AND np.id_periodo = ?
              AND np.estado = 'pendiente'";  // SOLO notas pendientes, no rechazadas
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $total_estudiantes = 0;
    $suma_total = 0;
    $aprobados = 0;
    $reprobados = 0;
    
    while ($nota = $result->fetch_assoc()) {
        $total_estudiantes++;
        
        // Calcular promedio según el id_trayecto de la sección
        $promedio_estudiante = calcularPromedioPorTrayecto($nota, $id_trayecto);
        $suma_total += $promedio_estudiante;
        
        // Aprobados desde 12 puntos
        if ($promedio_estudiante >= 12) {
            $aprobados++;
        } else {
            $reprobados++;
        }
    }
    
    $promedio_general = $total_estudiantes > 0 ? round($suma_total / $total_estudiantes, 1) : 0;
    
    return [
        'total_estudiantes' => $total_estudiantes,
        'promedio_general' => $promedio_general,
        'aprobados' => $aprobados,
        'reprobados' => $reprobados,
        'id_trayecto' => $id_trayecto
    ];
}

// FUNCIÓN AUXILIAR PARA OBTENER MATERIA POR ID (CON AUDITORÍA EN CASO DE ERROR)
if (!function_exists('obtenerMateriaPorId')) {
function obtenerMateriaPorId($materia_id) {
    global $db;
    
    try {
        $query = "SELECT id_materia, nombre_materia, cod_materia, trayecto 
                  FROM materias 
                  WHERE id_materia = ?";
        
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("i", $materia_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $materia = $result->fetch_assoc();
        $stmt->close();
        
        return $materia;
        
    } catch (Exception $e) {
        error_log("Error en obtenerMateriaPorId: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL OBTENER MATERIA
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "materias", 
                    $materia_id, 
                    null, 
                    [
                        'id_materia' => $materia_id,
                        'error' => $e->getMessage()
                    ], 
                    "Gestión de Notas", 
                    "Error al obtener información de materia"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error obtenerMateriaPorId: " . $auditError->getMessage());
            }
        }
        
        return null;
    }
}
}

// FUNCIÓN AUXILIAR PARA OBTENER ESTUDIANTE POR ID (CON AUDITORÍA EN CASO DE ERROR)
if (!function_exists('obtenerEstudiantePorId')) {
function obtenerEstudiantePorId($estudiante_id) {
    global $db;
    
    try {
        $query = "SELECT id, nombre, idusuario, email 
                  FROM users 
                  WHERE id = ? AND estudiante = 1";
        
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("i", $estudiante_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $estudiante = $result->fetch_assoc();
        $stmt->close();
        
        return $estudiante;
        
    } catch (Exception $e) {
        error_log("Error en obtenerEstudiantePorId: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL OBTENER ESTUDIANTE
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "users", 
                    $estudiante_id, 
                    null, 
                    [
                        'id_estudiante' => $estudiante_id,
                        'error' => $e->getMessage()
                    ], 
                    "Gestión de Notas", 
                    "Error al obtener información de estudiante"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error obtenerEstudiantePorId: " . $auditError->getMessage());
            }
        }
        
        return null;
    }
}
}

// FUNCIÓN AUXILIAR PARA OBTENER DOCENTE POR ID (CON AUDITORÍA EN CASO DE ERROR)
if (!function_exists('obtenerDocentePorId')) {
function obtenerDocentePorId($docente_id) {
    global $db;
    
    try {
        $query = "SELECT id, nombre, username, email 
                  FROM users 
                  WHERE id = ? AND docente = 1";
        
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("i", $docente_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $docente = $result->fetch_assoc();
        $stmt->close();
        
        return $docente;
        
    } catch (Exception $e) {
        error_log("Error en obtenerDocentePorId: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL OBTENER DOCENTE
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "users", 
                    $docente_id, 
                    null, 
                    [
                        'id_docente' => $docente_id,
                        'error' => $e->getMessage()
                    ], 
                    "Gestión de Notas", 
                    "Error al obtener información de docente"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error obtenerDocentePorId: " . $auditError->getMessage());
            }
        }
        
        return null;
    }
}
}











//MENSAJERIA ***********************************************************************

// Obtener lista de usuarios para enviar mensajes con filtros
function obtenerUsuariosMensajeria($filtro_tipo = '', $busqueda_cedula = '') {
    global $db;
    
    if (!isset($_SESSION['user']['id'])) {
        return false;
    }
    
    $current_user_id = $_SESSION['user']['id'];
    
    $query = "SELECT id, nombre, usuario, estudiante, docente, admin, idusuario 
              FROM users 
              WHERE id != ? AND status = 1";
    
    $params = array($current_user_id);
    $types = "i";
    
    // Aplicar filtro por tipo de usuario
    if (!empty($filtro_tipo)) {
        if ($filtro_tipo === 'estudiante') {
            $query .= " AND estudiante = 1";
        } elseif ($filtro_tipo === 'docente') {
            $query .= " AND docente = 1";
        } elseif ($filtro_tipo === 'admin') {
            $query .= " AND admin = 1";
        }
    }
    
    // Aplicar búsqueda por cédula
    if (!empty($busqueda_cedula)) {
        $query .= " AND idusuario LIKE ?";
        $params[] = "%$busqueda_cedula%";
        $types .= "s";
    }
    
    $query .= " ORDER BY nombre";
    
    $stmt = $db->prepare($query);
    if (!$stmt) {
        error_log("Error en preparar consulta obtenerUsuariosMensajeria: " . $db->error);
        return false;
    }
    
    // Bind parameters dinámicamente
    if (count($params) > 1) {
        $stmt->bind_param($types, ...$params);
    } else {
        $stmt->bind_param($types, $params[0]);
    }
    
    if (!$stmt->execute()) {
        error_log("Error en ejecutar obtenerUsuariosMensajeria: " . $stmt->error);
        return false;
    }
    
    // Auditoría solo si la función existe
    if (function_exists('registrarAuditoria')) {
        try {
            registrarAuditoria(
                "SELECT", 
                "users", 
                null, 
                null, 
                [
                    'filtro_tipo' => $filtro_tipo,
                    'busqueda_cedula' => $busqueda_cedula,
                    'usuario_consulta' => $current_user_id
                ], 
                "Mensajería", 
                "Consulta de usuarios para mensajería"
            );
        } catch (Exception $e) {
            error_log("Error en auditoría obtenerUsuariosMensajeria: " . $e->getMessage());
        }
    }
    
    return $stmt->get_result();
}

// Función para obtener el tipo de usuario basado en los campos booleanos
function obtenerTipoUsuario($usuario) {
    if ($usuario['estudiante'] == 1) return 'Estudiante';
    if ($usuario['docente'] == 1) return 'Docente';
    if ($usuario['admin'] == 1) return 'Administrador';
    if ($usuario['super_user'] == 1) return 'Super Usuario';
    return 'Usuario';
}

// Obtener mensajes recibidos
function obtenerMensajesRecibidos($user_id) {
    global $db;
    
    $query = "SELECT m.*, u.nombre as remitente_nombre, u.usuario as remitente_usuario,
                     u.estudiante, u.docente, u.admin, u.idusuario as remitente_cedula
              FROM mensajeria m
              INNER JOIN users u ON m.id_usuario_remitente = u.id
              WHERE m.id_usuario_destinatario = ? 
              AND m.eliminado_destinatario = FALSE
              ORDER BY m.fecha_envio DESC";
    
    $stmt = $db->prepare($query);
    if (!$stmt) {
        error_log("Error en preparar consulta obtenerMensajesRecibidos: " . $db->error);
        return false;
    }
    
    $stmt->bind_param("i", $user_id);
    
    if (!$stmt->execute()) {
        error_log("Error en ejecutar obtenerMensajesRecibidos: " . $stmt->error);
        return false;
    }
    
    // Auditoría solo si la función existe
    if (function_exists('registrarAuditoria')) {
        try {
            registrarAuditoria(
                "SELECT", 
                "mensajeria", 
                null, 
                null, 
                [
                    'usuario_id' => $user_id,
                    'tipo_consulta' => 'mensajes_recibidos'
                ], 
                "Mensajería", 
                "Consulta de mensajes recibidos"
            );
        } catch (Exception $e) {
            error_log("Error en auditoría obtenerMensajesRecibidos: " . $e->getMessage());
        }
    }
    
    return $stmt->get_result();
}

// Obtener mensajes enviados
function obtenerMensajesEnviados($user_id) {
    global $db;
    
    $query = "SELECT m.*, u.nombre as destinatario_nombre, u.usuario as destinatario_usuario,
                     u.estudiante, u.docente, u.admin, u.idusuario as destinatario_cedula
              FROM mensajeria m
              INNER JOIN users u ON m.id_usuario_destinatario = u.id
              WHERE m.id_usuario_remitente = ? 
              AND m.eliminado_remitente = FALSE
              ORDER BY m.fecha_envio DESC";
    
    $stmt = $db->prepare($query);
    if (!$stmt) {
        error_log("Error en preparar consulta obtenerMensajesEnviados: " . $db->error);
        return false;
    }
    
    $stmt->bind_param("i", $user_id);
    
    if (!$stmt->execute()) {
        error_log("Error en ejecutar obtenerMensajesEnviados: " . $stmt->error);
        return false;
    }
    
    // Auditoría solo si la función existe
    if (function_exists('registrarAuditoria')) {
        try {
            registrarAuditoria(
                "SELECT", 
                "mensajeria", 
                null, 
                null, 
                [
                    'usuario_id' => $user_id,
                    'tipo_consulta' => 'mensajes_enviados'
                ], 
                "Mensajería", 
                "Consulta de mensajes enviados"
            );
        } catch (Exception $e) {
            error_log("Error en auditoría obtenerMensajesEnviados: " . $e->getMessage());
        }
    }
    
    return $stmt->get_result();
}

// Función para obtener un mensaje específico
function obtenerMensaje($mensaje_id, $user_id, $tipo = 'recibidos') {
    global $db;
    
    if ($tipo === 'recibidos') {
        $query = "SELECT m.*, u.nombre as remitente_nombre, u.usuario as remitente_usuario,
                         u.email as remitente_email, u.estudiante, u.docente, u.admin,
                         u.idusuario as remitente_cedula
                  FROM mensajeria m
                  INNER JOIN users u ON m.id_usuario_remitente = u.id
                  WHERE m.id = ? AND m.id_usuario_destinatario = ? 
                  AND m.eliminado_destinatario = FALSE";
    } else {
        $query = "SELECT m.*, u.nombre as destinatario_nombre, u.usuario as destinatario_usuario,
                         u.email as destinatario_email, u.estudiante, u.docente, u.admin,
                         u.idusuario as destinatario_cedula
                  FROM mensajeria m
                  INNER JOIN users u ON m.id_usuario_destinatario = u.id
                  WHERE m.id = ? AND m.id_usuario_remitente = ? 
                  AND m.eliminado_remitente = FALSE";
    }
    
    $stmt = $db->prepare($query);
    if (!$stmt) {
        error_log("Error en preparar consulta obtenerMensaje: " . $db->error);
        return false;
    }
    
    $stmt->bind_param("ii", $mensaje_id, $user_id);
    
    if (!$stmt->execute()) {
        error_log("Error en ejecutar obtenerMensaje: " . $stmt->error);
        return false;
    }
    
    $result = $stmt->get_result();
    $mensaje = $result->fetch_assoc();
    
    // Auditoría solo si la función existe y se encontró el mensaje
    if ($mensaje && function_exists('registrarAuditoria')) {
        try {
            registrarAuditoria(
                "SELECT", 
                "mensajeria", 
                $mensaje_id, 
                null, 
                [
                    'usuario_id' => $user_id,
                    'tipo_mensaje' => $tipo
                ], 
                "Mensajería", 
                "Consulta de mensaje específico"
            );
        } catch (Exception $e) {
            error_log("Error en auditoría obtenerMensaje: " . $e->getMessage());
        }
    }
    
    return $mensaje;
}

// Marcar mensaje como leído
function marcarMensajeLeido($mensaje_id, $user_id) {
    global $db;
    
    try {
        $query = "UPDATE mensajeria SET leido = TRUE 
                  WHERE id = ? AND id_usuario_destinatario = ?";
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparar consulta: " . $db->error);
        }
        
        $stmt->bind_param("ii", $mensaje_id, $user_id);
        $result = $stmt->execute();
        
        if ($result && function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "UPDATE", 
                    "mensajeria", 
                    $mensaje_id, 
                    ['leido' => '0'], 
                    [
                        'leido' => '1',
                        'usuario_id' => $user_id
                    ], 
                    "Mensajería", 
                    "Mensaje marcado como leído"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría marcarMensajeLeido: " . $e->getMessage());
            }
        }
        
        return $result;
        
    } catch (Exception $e) {
        error_log("Error en marcarMensajeLeido: " . $e->getMessage());
        
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "mensajeria", 
                    $mensaje_id, 
                    null, 
                    [
                        'usuario_id' => $user_id,
                        'error' => $e->getMessage()
                    ], 
                    "Mensajería", 
                    "Error al marcar mensaje como leído"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error marcarMensajeLeido: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

// Enviar mensaje
function enviarMensaje($remitente_id, $destinatario_id, $titulo, $mensaje) {
    global $db;
    
    try {
        // Iniciar transacción
        $db->begin_transaction();

        $query = "INSERT INTO mensajeria (id_usuario_remitente, id_usuario_destinatario, titulo, mensaje)
                  VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparar consulta: " . $db->error);
        }
        
        $stmt->bind_param("iiss", $remitente_id, $destinatario_id, $titulo, $mensaje);
        
        $result = $stmt->execute();
        
        if ($result) {
            $mensaje_id = $stmt->insert_id;
            
            // Auditoría solo si la función existe
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "INSERT", 
                        "mensajeria", 
                        $mensaje_id, 
                        null, 
                        [
                            'id_usuario_remitente' => $remitente_id,
                            'id_usuario_destinatario' => $destinatario_id,
                            'titulo' => $titulo,
                            'mensaje_length' => strlen($mensaje)
                        ], 
                        "Mensajería", 
                        "Nuevo mensaje enviado"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría enviarMensaje: " . $e->getMessage());
                }
            }

            // Confirmar transacción
            $db->commit();
            
            return [
                'success' => true,
                'message' => 'Mensaje enviado exitosamente!',
                'id' => $mensaje_id
            ];
        } else {
            throw new Exception("Error al ejecutar: " . $stmt->error);
        }
        
    } catch(Exception $e) {
        // Revertir transacción en caso de error
        $db->rollback();
        
        // Auditoría de error solo si la función existe
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "mensajeria", 
                    null, 
                    null, 
                    [
                        'remitente_id' => $remitente_id,
                        'destinatario_id' => $destinatario_id,
                        'titulo' => $titulo,
                        'error' => $e->getMessage()
                    ], 
                    "Mensajería", 
                    "Error al enviar mensaje"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error enviarMensaje: " . $auditError->getMessage());
            }
        }
        
        error_log("Error en enviarMensaje: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Error al enviar mensaje: ' . $e->getMessage()
        ];
    }
}



//MI HORARIO ESTUDIANTE ***********************************************************************


function obtenerSeccionEstudiante($db, $estudiante_id) {
    // Consulta SQL para obtener información completa de la sección del estudiante
    $sql = "SELECT s.id_seccion, s.codigo_seccion, s.id_carrera, c.nombre_carrera, 
                   t.numero_trayecto, p.nombre_periodo, s.capacidad_maxima, s.inicia,
                   s.estatus, COUNT(es.id_usuario) as inscritos, p.activo as periodo_activo
            FROM estudiante_seccion es
            INNER JOIN secciones s ON es.id_seccion = s.id_seccion
            INNER JOIN carreras c ON s.id_carrera = c.id_carrera
            INNER JOIN trayectos t ON s.id_trayecto = t.id_trayecto
            INNER JOIN periodos_academicos p ON s.id_periodo = p.id_periodo  -- Usa periodos_academicos
            WHERE es.id_usuario = ? AND es.estatus = 'activo'
            GROUP BY s.id_seccion";
    
    // Preparar la sentencia SQL para prevenir inyecciones
    $stmt = $db->prepare($sql);
    
    // Vincular el parámetro: 'i' indica que es un integer
    $stmt->bind_param("i", $estudiante_id);
    
    // Ejecutar la consulta
    $stmt->execute();
    
    // Obtener el resultado de la consulta
    $result = $stmt->get_result();
    
    // Retornar la primera fila como array asociativo
    // Si no hay resultados, retorna null
    return $result->fetch_assoc();
}

// HORARIO DOCENTE ***********************************************************************



function obtenerHorariosDocente($db, $docente_id) {
    $sql = "SELECT 
                h.id_horario,
                h.dia,
                TIME_FORMAT(h.hora_inicio, '%H:%i') as hora_inicio,
                TIME_FORMAT(h.hora_fin, '%H:%i') as hora_fin,
                h.aula,
                m.nombre_materia,
                s.codigo_seccion,
                c.nombre_carrera,
                t.numero_trayecto,
                pa.nombre_periodo
            FROM horarios h
            INNER JOIN docente_seccion ds ON h.id_docente_seccion = ds.id_docente_seccion
            INNER JOIN materias m ON ds.id_materia = m.id_materia
            INNER JOIN secciones s ON ds.id_seccion = s.id_seccion
            INNER JOIN carreras c ON s.id_carrera = c.id_carrera
            INNER JOIN trayectos t ON s.id_trayecto = t.id_trayecto
            INNER JOIN periodos_academicos pa ON s.id_periodo = pa.id_periodo
            WHERE ds.id_usuario = ? 
            AND ds.estatus = 1
            ORDER BY h.dia, h.hora_inicio";
    
    // Preparar la consulta
    if ($stmt = $db->prepare($sql)) {
        // Vincular parámetros
        $stmt->bind_param("i", $docente_id);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Obtener resultados
            $result = $stmt->get_result();
            $horarios = [];
            
            // Recorrer resultados
            while ($row = $result->fetch_assoc()) {
                $horarios[] = $row;
            }
            
            // Cerrar statement
            $stmt->close();
            return $horarios;
        } else {
            // Manejar error de ejecución
            error_log("Error ejecutando consulta: " . $stmt->error);
            $stmt->close();
            return [];
        }
    } else {
        // Manejar error de preparación
        error_log("Error preparando consulta: " . $db->error);
        return [];
    }
}








//SEMESTRE O TRIMESTRE POR CARRERA ***********************************************************************


function obtenerTipoPeriodoPorCarrera($id_carrera) {
    global $db;
    
    // Consultar el nombre de la carrera desde la base de datos
    $query = "SELECT nombre_carrera FROM carreras WHERE id_carrera = ?";
    $stmt = $db->prepare($query);
    
    if (!$stmt) {
        error_log("Error en preparación de consulta: " . $db->error);
        return 'semestre'; // Valor por defecto en caso de error
    }
    
    $stmt->bind_param("i", $id_carrera);
    
    if (!$stmt->execute()) {
        error_log("Error ejecutando consulta: " . $stmt->error);
        return 'semestre'; // Valor por defecto en caso de error
    }
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $carrera = $result->fetch_assoc();
        $nombre_carrera = strtolower(trim($carrera['nombre_carrera']));
        
        error_log("Carrera consultada: " . $nombre_carrera); // Para debugging
        
        // Carreras que usan trimestre
        $carreras_trimestre = [
            'informatica',
            'materiales industriales',
            'mantenimiento',
            'mecanica'
        ];
        
        foreach ($carreras_trimestre as $carrera_trim) {
            if (strpos($nombre_carrera, $carrera_trim) !== false) {
                error_log("Carrera identificada como TRIMESTRE: " . $nombre_carrera);
                return 'trimestre';
            }
        }
        
        // Carreras que usan semestre
        $carreras_semestre = [
            'turismo',
            'logistica y distribucion',
            'mecanica termica',
            'mecanica automotriz'
        ];
        
        foreach ($carreras_semestre as $carrera_sem) {
            if (strpos($nombre_carrera, $carrera_sem) !== false) {
                error_log("Carrera identificada como SEMESTRE: " . $nombre_carrera);
                return 'semestre';
            }
        }
    }
    
    error_log("Carrera no encontrada en listas, usando valor por defecto: semestre");
    return 'semestre'; // Valor por defecto si no se encuentra coincidencia
}





//TIPOS DE HORARIO ***********************************************************************

// Función para obtener todos los tipos de horario - SOLO LECTURA, SIN AUDITORÍA
function obtenerTiposHorario($db) {
    $query = "SELECT * FROM tipos_horario ORDER BY nombre";
    $result = $db->query($query);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

/**
 * Obtener un tipo de horario por ID - SOLO LECTURA, SIN AUDITORÍA
 */
function obtenerTipoHorarioPorId($db, $id) {
    $query = "SELECT * FROM tipos_horario WHERE id = $id";
    $result = $db->query($query);
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

/**
 * Agregar un nuevo tipo de horario - CON AUDITORÍA
 */
function agregarTipoHorario($db, $nombre, $horas_academicas, $horas_atendiendo) {
    try {
        $nombre_original = $nombre;
        $horas_academicas_original = (int)$horas_academicas;
        $horas_atendiendo_original = (int)$horas_atendiendo;
        
        $nombre = $db->real_escape_string($nombre);
        $horas_academicas = (int)$horas_academicas;
        $horas_atendiendo = (int)$horas_atendiendo;
        
        $query = "INSERT INTO tipos_horario (nombre, horas_academicas, horas_atendiendo) 
                  VALUES ('$nombre', $horas_academicas, $horas_atendiendo)";
        
        $result = $db->query($query);
        
        if ($result) {
            $id_insertado = $db->insert_id;
            
            // REGISTRAR EN AUDITORÍA - TIPO DE HORARIO CREADO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "INSERT", 
                        "tipos_horario", 
                        $id_insertado, 
                        null, 
                        [
                            'nombre' => $nombre_original,
                            'horas_academicas' => $horas_academicas_original,
                            'horas_atendiendo' => $horas_atendiendo_original,
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido',
                            'usuario_id' => $_SESSION['user']['id'] ?? 0,
                            'fecha_creacion' => date('Y-m-d H:i:s')
                        ], 
                        "Tipos de Horario", 
                        "Tipo de horario creado: " . $nombre_original
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría agregarTipoHorario: " . $e->getMessage());
                }
            }
            
            return true;
        } else {
            // REGISTRAR EN AUDITORÍA - ERROR AL CREAR TIPO DE HORARIO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "ERROR", 
                        "tipos_horario", 
                        null, 
                        null, 
                        [
                            'nombre' => $nombre_original,
                            'horas_academicas' => $horas_academicas_original,
                            'horas_atendiendo' => $horas_atendiendo_original,
                            'error' => $db->error,
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                        ], 
                        "Tipos de Horario", 
                        "Error al crear tipo de horario: " . $nombre_original
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría de error agregarTipoHorario: " . $e->getMessage());
                }
            }
            
            return false;
        }
        
    } catch (Exception $e) {
        error_log("Error en agregarTipoHorario: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - EXCEPCIÓN AL CREAR TIPO DE HORARIO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "tipos_horario", 
                    null, 
                    null, 
                    [
                        'nombre' => $nombre_original ?? '',
                        'horas_academicas' => $horas_academicas_original ?? 0,
                        'horas_atendiendo' => $horas_atendiendo_original ?? 0,
                        'error' => $e->getMessage(),
                        'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                    ], 
                    "Tipos de Horario", 
                    "Excepción al crear tipo de horario"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de excepción agregarTipoHorario: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

/**
 * Actualizar un tipo de horario existente - CON AUDITORÍA
 */
function actualizarTipoHorario($db, $id, $nombre, $horas_academicas, $horas_atendiendo) {
    try {
        // Obtener datos actuales para auditoría
        $query_actual = "SELECT nombre, horas_academicas, horas_atendiendo FROM tipos_horario WHERE id = $id";
        $result_actual = $db->query($query_actual);
        
        if ($result_actual->num_rows === 0) {
            return false;
        }
        
        $tipo_horario_actual = $result_actual->fetch_assoc();
        
        $nombre_original = $nombre;
        $horas_academicas_original = (int)$horas_academicas;
        $horas_atendiendo_original = (int)$horas_atendiendo;
        
        $nombre = $db->real_escape_string($nombre);
        $horas_academicas = (int)$horas_academicas;
        $horas_atendiendo = (int)$horas_atendiendo;
        
        $query = "UPDATE tipos_horario SET 
                  nombre = '$nombre', 
                  horas_academicas = $horas_academicas, 
                  horas_atendiendo = $horas_atendiendo 
                  WHERE id = $id";
        
        $result = $db->query($query);
        
        if ($result) {
            // REGISTRAR EN AUDITORÍA - TIPO DE HORARIO ACTUALIZADO
            if (function_exists('registrarAuditoria')) {
                try {
                    $cambios = [];
                    if ($tipo_horario_actual['nombre'] != $nombre_original) {
                        $cambios[] = "nombre: " . $tipo_horario_actual['nombre'] . " → " . $nombre_original;
                    }
                    if ($tipo_horario_actual['horas_academicas'] != $horas_academicas_original) {
                        $cambios[] = "horas_academicas: " . $tipo_horario_actual['horas_academicas'] . " → " . $horas_academicas_original;
                    }
                    if ($tipo_horario_actual['horas_atendiendo'] != $horas_atendiendo_original) {
                        $cambios[] = "horas_atendiendo: " . $tipo_horario_actual['horas_atendiendo'] . " → " . $horas_atendiendo_original;
                    }
                    
                    registrarAuditoria(
                        "UPDATE", 
                        "tipos_horario", 
                        $id, 
                        [
                            'nombre_anterior' => $tipo_horario_actual['nombre'],
                            'horas_academicas_anterior' => $tipo_horario_actual['horas_academicas'],
                            'horas_atendiendo_anterior' => $tipo_horario_actual['horas_atendiendo']
                        ], 
                        [
                            'nombre_nuevo' => $nombre_original,
                            'horas_academicas_nuevo' => $horas_academicas_original,
                            'horas_atendiendo_nuevo' => $horas_atendiendo_original,
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido',
                            'usuario_id' => $_SESSION['user']['id'] ?? 0,
                            'fecha_actualizacion' => date('Y-m-d H:i:s'),
                            'cambios' => implode('; ', $cambios)
                        ], 
                        "Tipos de Horario", 
                        "Tipo de horario actualizado: " . $tipo_horario_actual['nombre'] . " → " . $nombre_original
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría actualizarTipoHorario: " . $e->getMessage());
                }
            }
            
            return true;
        } else {
            // REGISTRAR EN AUDITORÍA - ERROR AL ACTUALIZAR TIPO DE HORARIO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "ERROR", 
                        "tipos_horario", 
                        $id, 
                        null, 
                        [
                            'id_tipo_horario' => $id,
                            'nombre_nuevo' => $nombre_original,
                            'horas_academicas_nuevo' => $horas_academicas_original,
                            'horas_atendiendo_nuevo' => $horas_atendiendo_original,
                            'error' => $db->error,
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                        ], 
                        "Tipos de Horario", 
                        "Error al actualizar tipo de horario ID: " . $id
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría de error actualizarTipoHorario: " . $e->getMessage());
                }
            }
            
            return false;
        }
        
    } catch (Exception $e) {
        error_log("Error en actualizarTipoHorario: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - EXCEPCIÓN AL ACTUALIZAR TIPO DE HORARIO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "tipos_horario", 
                    $id, 
                    null, 
                    [
                        'id_tipo_horario' => $id,
                        'nombre_nuevo' => $nombre_original ?? '',
                        'horas_academicas_nuevo' => $horas_academicas_original ?? 0,
                        'horas_atendiendo_nuevo' => $horas_atendiendo_original ?? 0,
                        'error' => $e->getMessage(),
                        'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                    ], 
                    "Tipos de Horario", 
                    "Excepción al actualizar tipo de horario"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de excepción actualizarTipoHorario: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

/**
 * Eliminar un tipo de horario - CON AUDITORÍA
 */
function eliminarTipoHorario($db, $id) {
    try {
        // Obtener datos del tipo de horario para auditoría
        $query_actual = "SELECT nombre, horas_academicas, horas_atendiendo FROM tipos_horario WHERE id = $id";
        $result_actual = $db->query($query_actual);
        
        if ($result_actual->num_rows === 0) {
            return false;
        }
        
        $tipo_horario_eliminado = $result_actual->fetch_assoc();
        
        $query = "DELETE FROM tipos_horario WHERE id = $id";
        $result = $db->query($query);
        
        if ($result) {
            // REGISTRAR EN AUDITORÍA - TIPO DE HORARIO ELIMINADO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "DELETE", 
                        "tipos_horario", 
                        $id, 
                        [
                            'nombre_eliminado' => $tipo_horario_eliminado['nombre'],
                            'horas_academicas_eliminado' => $tipo_horario_eliminado['horas_academicas'],
                            'horas_atendiendo_eliminado' => $tipo_horario_eliminado['horas_atendiendo']
                        ], 
                        [
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido',
                            'usuario_id' => $_SESSION['user']['id'] ?? 0,
                            'fecha_eliminacion' => date('Y-m-d H:i:s')
                        ], 
                        "Tipos de Horario", 
                        "Tipo de horario eliminado: " . $tipo_horario_eliminado['nombre'] . " (ID: " . $id . ")"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría eliminarTipoHorario: " . $e->getMessage());
                }
            }
            
            return true;
        } else {
            // REGISTRAR EN AUDITORÍA - ERROR AL ELIMINAR TIPO DE HORARIO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "ERROR", 
                        "tipos_horario", 
                        $id, 
                        null, 
                        [
                            'id_tipo_horario' => $id,
                            'nombre' => $tipo_horario_eliminado['nombre'],
                            'error' => $db->error,
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                        ], 
                        "Tipos de Horario", 
                        "Error al eliminar tipo de horario: " . $tipo_horario_eliminado['nombre']
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría de error eliminarTipoHorario: " . $e->getMessage());
                }
            }
            
            return false;
        }
        
    } catch (Exception $e) {
        error_log("Error en eliminarTipoHorario: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - EXCEPCIÓN AL ELIMINAR TIPO DE HORARIO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "tipos_horario", 
                    $id, 
                    null, 
                    [
                        'id_tipo_horario' => $id,
                        'error' => $e->getMessage(),
                        'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                    ], 
                    "Tipos de Horario", 
                    "Excepción al eliminar tipo de horario"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de excepción eliminarTipoHorario: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

if (!function_exists('validarTipoHorario')) {
    function validarTipoHorario($nombre, $horas_academicas, $horas_atendiendo) {
        $errores = [];
        
        if (empty(trim($nombre))) {
            $errores[] = "El nombre del horario es requerido";
        } elseif (strlen(trim($nombre)) < 2) {
            $errores[] = "El nombre debe tener al menos 2 caracteres";
        } elseif (strlen(trim($nombre)) > 100) {
            $errores[] = "El nombre no puede exceder los 100 caracteres";
        }
        
        if (!is_numeric($horas_academicas) || $horas_academicas < 0) {
            $errores[] = "Las horas académicas deben ser un número positivo";
        }
        
        if (!is_numeric($horas_atendiendo) || $horas_atendiendo < 0) {
            $errores[] = "Las horas atendiendo deben ser un número positivo";
        }
        
        return $errores;
    }
}


if (!function_exists('existeTipoHorario')) {
    function existeTipoHorario($db, $nombre, $excluir_id = null) {
        if ($excluir_id) {
            $stmt = $db->prepare("SELECT id FROM tipos_horario WHERE nombre = ? AND id != ?");
            $stmt->bind_param("si", $nombre, $excluir_id);
        } else {
            $stmt = $db->prepare("SELECT id FROM tipos_horario WHERE nombre = ?");
            $stmt->bind_param("s", $nombre);
        }
        
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
}








//ASIGNACION TIPO HORARIO AL PERSONAL ***********************************************************************

/**
 * Asignar tipo de horario a un usuario - CON AUDITORÍA
 */
function asignarTipoHorarioUsuario($db, $id_usuario, $id_tipo_horario) {
    try {
        // Obtener información del usuario y tipo de horario para auditoría
        $query_usuario = "SELECT nombre, username FROM users WHERE id = $id_usuario";
        $query_horario = "SELECT nombre FROM tipos_horario WHERE id = $id_tipo_horario";
        
        $result_usuario = $db->query($query_usuario);
        $result_horario = $db->query($query_horario);
        
        if ($result_usuario->num_rows === 0 || $result_horario->num_rows === 0) {
            return false;
        }
        
        $usuario_info = $result_usuario->fetch_assoc();
        $horario_info = $result_horario->fetch_assoc();
        
        // Verificar si ya existe la relación
        $query_check = "SELECT id FROM tipo_horario_personal 
                        WHERE id_usuario = $id_usuario AND id_tipo_horario = $id_tipo_horario";
        $result = $db->query($query_check);
        
        if ($result->num_rows > 0) {
            return false; // Ya existe esta relación
        }
        
        // Insertar nueva relación
        $query = "INSERT INTO tipo_horario_personal (id_usuario, id_tipo_horario) 
                  VALUES ($id_usuario, $id_tipo_horario)";
        $result = $db->query($query);
        
        if ($result) {
            $id_insertado = $db->insert_id;
            
            // REGISTRAR EN AUDITORÍA - ASIGNACIÓN DE HORARIO CREADA
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "INSERT", 
                        "tipo_horario_personal", 
                        $id_insertado, 
                        null, 
                        [
                            'id_usuario' => $id_usuario,
                            'usuario_nombre' => $usuario_info['nombre'],
                            'usuario_username' => $usuario_info['username'],
                            'id_tipo_horario' => $id_tipo_horario,
                            'horario_nombre' => $horario_info['nombre'],
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido',
                            'usuario_id' => $_SESSION['user']['id'] ?? 0,
                            'fecha_asignacion' => date('Y-m-d H:i:s')
                        ], 
                        "Horario Personal", 
                        "Horario asignado a usuario: " . $horario_info['nombre'] . " → " . $usuario_info['nombre']
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría asignarTipoHorarioUsuario: " . $e->getMessage());
                }
            }
            
            return true;
        } else {
            // REGISTRAR EN AUDITORÍA - ERROR AL ASIGNAR HORARIO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "ERROR", 
                        "tipo_horario_personal", 
                        null, 
                        null, 
                        [
                            'id_usuario' => $id_usuario,
                            'usuario_nombre' => $usuario_info['nombre'],
                            'id_tipo_horario' => $id_tipo_horario,
                            'horario_nombre' => $horario_info['nombre'],
                            'error' => $db->error,
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                        ], 
                        "Horario Personal", 
                        "Error al asignar horario a usuario"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría de error asignarTipoHorarioUsuario: " . $e->getMessage());
                }
            }
            
            return false;
        }
        
    } catch (Exception $e) {
        error_log("Error en asignarTipoHorarioUsuario: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - EXCEPCIÓN AL ASIGNAR HORARIO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "tipo_horario_personal", 
                    null, 
                    null, 
                    [
                        'id_usuario' => $id_usuario,
                        'id_tipo_horario' => $id_tipo_horario,
                        'error' => $e->getMessage(),
                        'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                    ], 
                    "Horario Personal", 
                    "Excepción al asignar horario a usuario"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de excepción asignarTipoHorarioUsuario: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

/**
 * Obtener tipos de horario de un usuario - SOLO LECTURA, SIN AUDITORÍA
 */
function obtenerTiposHorarioUsuario($db, $id_usuario) {
    $query = "SELECT th.* 
              FROM tipo_horario_personal thp
              JOIN tipos_horario th ON thp.id_tipo_horario = th.id
              WHERE thp.id_usuario = $id_usuario
              ORDER BY th.nombre";
    $result = $db->query($query);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

/**
 * Eliminar asignación de horario de usuario - CON AUDITORÍA
 */
function eliminarTipoHorarioUsuario($db, $id_usuario, $id_tipo_horario) {
    try {
        // Obtener información para auditoría
        $query_info = "SELECT u.nombre as usuario_nombre, u.username, th.nombre as horario_nombre
                       FROM tipo_horario_personal thp
                       JOIN users u ON thp.id_usuario = u.id
                       JOIN tipos_horario th ON thp.id_tipo_horario = th.id
                       WHERE thp.id_usuario = $id_usuario AND thp.id_tipo_horario = $id_tipo_horario";
        
        $result_info = $db->query($query_info);
        
        if ($result_info->num_rows === 0) {
            return false;
        }
        
        $info = $result_info->fetch_assoc();
        
        $query = "DELETE FROM tipo_horario_personal 
                  WHERE id_usuario = $id_usuario AND id_tipo_horario = $id_tipo_horario";
        $result = $db->query($query);
        
        if ($result) {
            // REGISTRAR EN AUDITORÍA - ASIGNACIÓN DE HORARIO ELIMINADA
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "DELETE", 
                        "tipo_horario_personal", 
                        null, 
                        [
                            'id_usuario' => $id_usuario,
                            'usuario_nombre' => $info['usuario_nombre'],
                            'usuario_username' => $info['username'],
                            'id_tipo_horario' => $id_tipo_horario,
                            'horario_nombre' => $info['horario_nombre']
                        ], 
                        [
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido',
                            'usuario_id' => $_SESSION['user']['id'] ?? 0,
                            'fecha_eliminacion' => date('Y-m-d H:i:s')
                        ], 
                        "Horario Personal", 
                        "Horario eliminado de usuario: " . $info['horario_nombre'] . " ← " . $info['usuario_nombre']
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría eliminarTipoHorarioUsuario: " . $e->getMessage());
                }
            }
            
            return true;
        } else {
            // REGISTRAR EN AUDITORÍA - ERROR AL ELIMINAR ASIGNACIÓN
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "ERROR", 
                        "tipo_horario_personal", 
                        null, 
                        null, 
                        [
                            'id_usuario' => $id_usuario,
                            'usuario_nombre' => $info['usuario_nombre'],
                            'id_tipo_horario' => $id_tipo_horario,
                            'horario_nombre' => $info['horario_nombre'],
                            'error' => $db->error,
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                        ], 
                        "Horario Personal", 
                        "Error al eliminar horario de usuario"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría de error eliminarTipoHorarioUsuario: " . $e->getMessage());
                }
            }
            
            return false;
        }
        
    } catch (Exception $e) {
        error_log("Error en eliminarTipoHorarioUsuario: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - EXCEPCIÓN AL ELIMINAR ASIGNACIÓN
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "tipo_horario_personal", 
                    null, 
                    null, 
                    [
                        'id_usuario' => $id_usuario,
                        'id_tipo_horario' => $id_tipo_horario,
                        'error' => $e->getMessage(),
                        'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                    ], 
                    "Horario Personal", 
                    "Excepción al eliminar horario de usuario"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de excepción eliminarTipoHorarioUsuario: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

/**
 * Obtener usuarios por tipo de horario - SOLO LECTURA, SIN AUDITORÍA
 */
function obtenerUsuariosPorTipoHorario($db, $id_tipo_horario) {
    $query = "SELECT u.* 
              FROM tipo_horario_personal thp
              JOIN users u ON thp.id_usuario = u.id
              WHERE thp.id_tipo_horario = $id_tipo_horario
              ORDER BY u.nombre";
    $result = $db->query($query);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

/**
 * Obtener texto del tipo de usuario (estético) - SOLO LÓGICA, SIN AUDITORÍA
 */
function obtenerTipoUsuarioTexto($usuario) {
    $tipo_usuario = [];
    if ($usuario['docente'] == 1) $tipo_usuario[] = 'Docente';
    if ($usuario['admin'] == 1) $tipo_usuario[] = 'Admin';
    if ($usuario['super_user'] == 1) $tipo_usuario[] = 'Super User';
    if ($usuario['usuario'] == 1) $tipo_usuario[] = 'Director de Carrera';
    
    return implode(', ', $tipo_usuario);
}

/**
 * Obtener todas las relaciones horario-personal - SOLO LECTURA, SIN AUDITORÍA
 */
function obtenerTodasRelacionesHorarioPersonal($db) {
    $query = "SELECT thp.id, thp.id_usuario, thp.id_tipo_horario, 
                     u.idusuario, u.nombre as usuario_nombre, u.username, 
                     u.docente, u.admin, u.super_user, u.usuario,
                     th.nombre as horario_nombre, th.horas_academicas, th.horas_atendiendo
              FROM tipo_horario_personal thp
              JOIN users u ON thp.id_usuario = u.id
              JOIN tipos_horario th ON thp.id_tipo_horario = th.id
              ORDER BY u.nombre, th.nombre";
    $result = $db->query($query);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

/**
 * Actualizar tipo de horario de usuario - CON AUDITORÍA
 */
function actualizarTipoHorarioUsuario($db, $id_relacion, $id_tipo_horario) {
    try {
        // Obtener información actual para auditoría
        $query_actual = "SELECT thp.id_usuario, thp.id_tipo_horario as id_tipo_horario_anterior, 
                                u.nombre as usuario_nombre, u.username,
                                th_anterior.nombre as horario_anterior_nombre,
                                th_nuevo.nombre as horario_nuevo_nombre
                         FROM tipo_horario_personal thp
                         JOIN users u ON thp.id_usuario = u.id
                         JOIN tipos_horario th_anterior ON thp.id_tipo_horario = th_anterior.id
                         JOIN tipos_horario th_nuevo ON $id_tipo_horario = th_nuevo.id
                         WHERE thp.id = $id_relacion";
        
        $result_actual = $db->query($query_actual);
        
        if ($result_actual->num_rows === 0) {
            return false;
        }
        
        $info_actual = $result_actual->fetch_assoc();
        
        $query = "UPDATE tipo_horario_personal SET id_tipo_horario = $id_tipo_horario WHERE id = $id_relacion";
        $result = $db->query($query);
        
        if ($result) {
            // REGISTRAR EN AUDITORÍA - ASIGNACIÓN DE HORARIO ACTUALIZADA
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "UPDATE", 
                        "tipo_horario_personal", 
                        $id_relacion, 
                        [
                            'id_tipo_horario_anterior' => $info_actual['id_tipo_horario_anterior'],
                            'horario_anterior_nombre' => $info_actual['horario_anterior_nombre']
                        ], 
                        [
                            'id_usuario' => $info_actual['id_usuario'],
                            'usuario_nombre' => $info_actual['usuario_nombre'],
                            'usuario_username' => $info_actual['username'],
                            'id_tipo_horario_nuevo' => $id_tipo_horario,
                            'horario_nuevo_nombre' => $info_actual['horario_nuevo_nombre'],
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido',
                            'usuario_id' => $_SESSION['user']['id'] ?? 0,
                            'fecha_actualizacion' => date('Y-m-d H:i:s')
                        ], 
                        "Horario Personal", 
                        "Horario actualizado para usuario: " . $info_actual['horario_anterior_nombre'] . " → " . $info_actual['horario_nuevo_nombre'] . " (" . $info_actual['usuario_nombre'] . ")"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría actualizarTipoHorarioUsuario: " . $e->getMessage());
                }
            }
            
            return true;
        } else {
            // REGISTRAR EN AUDITORÍA - ERROR AL ACTUALIZAR ASIGNACIÓN
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "ERROR", 
                        "tipo_horario_personal", 
                        $id_relacion, 
                        null, 
                        [
                            'id_relacion' => $id_relacion,
                            'id_tipo_horario_nuevo' => $id_tipo_horario,
                            'usuario_nombre' => $info_actual['usuario_nombre'],
                            'error' => $db->error,
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                        ], 
                        "Horario Personal", 
                        "Error al actualizar horario de usuario"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría de error actualizarTipoHorarioUsuario: " . $e->getMessage());
                }
            }
            
            return false;
        }
        
    } catch (Exception $e) {
        error_log("Error en actualizarTipoHorarioUsuario: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - EXCEPCIÓN AL ACTUALIZAR ASIGNACIÓN
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "tipo_horario_personal", 
                    $id_relacion, 
                    null, 
                    [
                        'id_relacion' => $id_relacion,
                        'id_tipo_horario_nuevo' => $id_tipo_horario,
                        'error' => $e->getMessage(),
                        'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                    ], 
                    "Horario Personal", 
                    "Excepción al actualizar horario de usuario"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de excepción actualizarTipoHorarioUsuario: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

/**
 * Eliminar relación por ID - CON AUDITORÍA
 */
function eliminarTipoHorarioUsuarioPorId($db, $id_relacion) {
    try {
        // Obtener información para auditoría
        $query_info = "SELECT thp.id_usuario, u.nombre as usuario_nombre, u.username, 
                              thp.id_tipo_horario, th.nombre as horario_nombre
                       FROM tipo_horario_personal thp
                       JOIN users u ON thp.id_usuario = u.id
                       JOIN tipos_horario th ON thp.id_tipo_horario = th.id
                       WHERE thp.id = $id_relacion";
        
        $result_info = $db->query($query_info);
        
        if ($result_info->num_rows === 0) {
            return false;
        }
        
        $info = $result_info->fetch_assoc();
        
        $query = "DELETE FROM tipo_horario_personal WHERE id = $id_relacion";
        $result = $db->query($query);
        
        if ($result) {
            // REGISTRAR EN AUDITORÍA - RELACIÓN ELIMINADA POR ID
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "DELETE", 
                        "tipo_horario_personal", 
                        $id_relacion, 
                        [
                            'id_usuario' => $info['id_usuario'],
                            'usuario_nombre' => $info['usuario_nombre'],
                            'usuario_username' => $info['username'],
                            'id_tipo_horario' => $info['id_tipo_horario'],
                            'horario_nombre' => $info['horario_nombre']
                        ], 
                        [
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido',
                            'usuario_id' => $_SESSION['user']['id'] ?? 0,
                            'fecha_eliminacion' => date('Y-m-d H:i:s')
                        ], 
                        "Horario Personal", 
                        "Relación horario-usuario eliminada: " . $info['horario_nombre'] . " ← " . $info['usuario_nombre']
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría eliminarTipoHorarioUsuarioPorId: " . $e->getMessage());
                }
            }
            
            return true;
        } else {
            // REGISTRAR EN AUDITORÍA - ERROR AL ELIMINAR RELACIÓN
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "ERROR", 
                        "tipo_horario_personal", 
                        $id_relacion, 
                        null, 
                        [
                            'id_relacion' => $id_relacion,
                            'usuario_nombre' => $info['usuario_nombre'],
                            'horario_nombre' => $info['horario_nombre'],
                            'error' => $db->error,
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                        ], 
                        "Horario Personal", 
                        "Error al eliminar relación horario-usuario"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría de error eliminarTipoHorarioUsuarioPorId: " . $e->getMessage());
                }
            }
            
            return false;
        }
        
    } catch (Exception $e) {
        error_log("Error en eliminarTipoHorarioUsuarioPorId: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - EXCEPCIÓN AL ELIMINAR RELACIÓN
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "tipo_horario_personal", 
                    $id_relacion, 
                    null, 
                    [
                        'id_relacion' => $id_relacion,
                        'error' => $e->getMessage(),
                        'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                    ], 
                    "Horario Personal", 
                    "Excepción al eliminar relación horario-usuario"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de excepción eliminarTipoHorarioUsuarioPorId: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}



//ACCESOS ***************************************************************

/**
 * Verificar permisos de acceso a una página específica
 * @param string $pagina Nombre del permiso (debe coincidir con el campo en la tabla users)
 * @return void Redirige a home.php si no tiene permisos
 */
function verificarPermiso($pagina) {
    // Si no hay sesión de usuario, redirigir al login
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
        header('location: ../login.php');
        exit();
    }
    
    // Lista de permisos válidos en la base de datos (actualizada con los nuevos)
    $permisosValidos = [
        'usuario', 'estudiante', 'docente', 'admin', 'super_user', 
        'editar_user', 'editar_nota', 'editar_acceso', 'editar_valores', 
        'editar_estudiante', 'agregar_estudiante', 'agregar_docente', 
        'editar_docente', 'agregar_carrera', 'agregar_materia', 'editar_materia',
        'pagos', 'auditoria', 'secciones', 'rela_materia_carrera', 
        'periodos_academicos', 'asig_secciones', 'asig_cursos', 'horarios', 
        'gestion_director_carrera', 'notas_cargadas', 'consultar_notas', 
        'consultar_notas_pasadas', 'tipos_pago', 'tipos_horario', 
        'horario_personal', 'respaldo_bd',
        'gestionar_carrera', 'gestion_periodo_academico', 'gestion_asig_cursos', 
        'gestion_horario', 'titulos_re_materia', 'grado', 'gestion_grado', 'visita'
    ];
    
    // Verificar que el permiso solicitado sea válido
    if (!in_array($pagina, $permisosValidos)) {
        error_log("Permiso no válido: " . $pagina);
        
        // REGISTRAR EN AUDITORÍA - PERMISO NO VÁLIDO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "users", 
                    $_SESSION['user']['id'] ?? null, 
                    null, 
                    [
                        'permiso_solicitado' => $pagina,
                        'usuario' => $_SESSION['user']['username'] ?? 'Desconocido',
                        'error' => 'Permiso no válido'
                    ], 
                    "Control de Acceso", 
                    "Intento de acceso con permiso no válido"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría verificarPermiso (permiso inválido): " . $e->getMessage());
            }
        }
        
        $_SESSION['error'] = "Error de permisos: permiso no válido.";
        header('location: ../login.php');
        exit();
    }
    
    // Si es super_user, tiene acceso a todo - RETORNAR EXPLÍCITAMENTE
    if (isset($_SESSION['user']['super_user']) && $_SESSION['user']['super_user'] == 1) {
        return;
    }
    
    // Verificar si el permiso existe en la sesión y es igual a 1
    if (!isset($_SESSION['user'][$pagina]) || $_SESSION['user'][$pagina] != 1) {
        // Registrar intento de acceso no autorizado
        error_log("Acceso denegado a " . $pagina . " para el usuario: " . $_SESSION['user']['username']);
        
        // REGISTRAR EN AUDITORÍA - ACCESO DENEGADO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "DENEGADO", 
                    "users", 
                    $_SESSION['user']['id'] ?? null, 
                    null, 
                    [
                        'permiso_solicitado' => $pagina,
                        'usuario' => $_SESSION['user']['username'] ?? 'Desconocido',
                        'usuario_id' => $_SESSION['user']['id'] ?? 0,
                        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'Desconocida',
                        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido'
                    ], 
                    "Control de Acceso", 
                    "Acceso denegado a: " . $pagina
                );
            } catch (Exception $e) {
                error_log("Error en auditoría verificarPermiso (acceso denegado): " . $e->getMessage());
            }
        }
        
        // Redirigir a home con mensaje de error
        $_SESSION['error'] = "No tienes permisos para acceder a la página de " . $pagina . ".";
        header('location: ../login.php');
        exit();
    }
}

/**
 * Función para verificar permisos sin redirección (útil para mostrar/ocultar elementos)
 * @param string $pagina Nombre del permiso
 * @return bool True si tiene permiso, False si no
 */
function tienePermiso($pagina) {
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
        return false;
    }
    
    // Si es super_user, tiene acceso a todo
    if (isset($_SESSION['user']['super_user']) && $_SESSION['user']['super_user'] == 1) {
        return true;
    }
    
    // Verificar permiso específico - debe existir y ser igual a 1
    return isset($_SESSION['user'][$pagina]) && $_SESSION['user'][$pagina] == 1;
}

/**
 * Función para cargar/actualizar los permisos del usuario en la sesión
 * Esto asegura que siempre tengamos los permisos actualizados
 */
function cargarPermisosUsuario() {
    global $db;
    
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
        return false;
    }
    
    $user_id = $_SESSION['user']['id'];
    
    $query = "SELECT 
        usuario, estudiante, docente, admin, super_user, 
        editar_user, editar_nota, editar_acceso, editar_valores, 
        editar_estudiante, agregar_estudiante, agregar_docente, 
        editar_docente, agregar_carrera, agregar_materia, editar_materia,
        pagos, auditoria, secciones, rela_materia_carrera, 
        periodos_academicos, asig_secciones, asig_cursos, horarios, 
        gestion_director_carrera, notas_cargadas, consultar_notas, 
        consultar_notas_pasadas, tipos_pago, tipos_horario, 
        horario_personal, respaldo_bd,
        gestionar_carrera, gestion_periodo_academico, gestion_asig_cursos, 
        gestion_horario, titulos_re_materia, grado, gestion_grado, visita
        FROM users WHERE id = ?";
    
    $stmt = $db->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $permisos = $result->fetch_assoc();
            
            // Actualizar los permisos en la sesión
            foreach ($permisos as $key => $value) {
                $_SESSION['user'][$key] = $value;
            }
            
            $stmt->close();
            return true;
        }
        $stmt->close();
    }
    
    return false;
}

// Función para actualizar permisos de usuario - CON AUDITORÍA SOLO PARA CAMBIOS REALES
function actualizarPermisosUsuario($user_id, $permisos) {
    global $db;
    
    try {
        if (!is_numeric($user_id)) {
            throw new Exception("ID de usuario no válido");
        }
        
        // Obtener información del usuario y permisos actuales para auditoría
        $query_actual = "SELECT username, 
                        usuario, estudiante, docente, admin, super_user, editar_user, editar_nota, editar_acceso, 
                        editar_valores, editar_estudiante, agregar_estudiante, agregar_docente, editar_docente, 
                        agregar_carrera, agregar_materia, editar_materia,
                        pagos, auditoria, secciones, rela_materia_carrera, periodos_academicos, asig_secciones, 
                        asig_cursos, horarios, gestion_director_carrera, notas_cargadas, consultar_notas, 
                        consultar_notas_pasadas, tipos_pago, tipos_horario, horario_personal, respaldo_bd,
                        gestionar_carrera, gestion_periodo_academico, gestion_asig_cursos, gestion_horario, titulos_re_materia,
                        grado, gestion_grado, visita
                        FROM users WHERE id = ?";
        
        $stmt_actual = $db->prepare($query_actual);
        $stmt_actual->bind_param("i", $user_id);
        $stmt_actual->execute();
        $result_actual = $stmt_actual->get_result();
        
        if ($result_actual->num_rows === 0) {
            throw new Exception("Usuario no encontrado");
        }
        
        $usuario_actual = $result_actual->fetch_assoc();
        $stmt_actual->close();
        
        // Preparar datos para la actualización
        $permisos_nuevos = [
            'usuario' => isset($permisos['usuario']) ? 1 : 0,
            'estudiante' => isset($permisos['estudiante']) ? 1 : 0,
            'docente' => isset($permisos['docente']) ? 1 : 0,
            'admin' => isset($permisos['admin']) ? 1 : 0,
            'super_user' => isset($permisos['super_user']) ? 1 : 0,
            'editar_user' => isset($permisos['editar_user']) ? 1 : 0,
            'editar_nota' => isset($permisos['editar_nota']) ? 1 : 0,
            'editar_acceso' => isset($permisos['editar_acceso']) ? 1 : 0,
            'editar_valores' => isset($permisos['editar_valores']) ? 1 : 0,
            'editar_estudiante' => isset($permisos['editar_estudiante']) ? 1 : 0,
            'agregar_estudiante' => isset($permisos['agregar_estudiante']) ? 1 : 0,
            'agregar_docente' => isset($permisos['agregar_docente']) ? 1 : 0,
            'editar_docente' => isset($permisos['editar_docente']) ? 1 : 0,
            'agregar_carrera' => isset($permisos['agregar_carrera']) ? 1 : 0,
            'agregar_materia' => isset($permisos['agregar_materia']) ? 1 : 0,
            'editar_materia' => isset($permisos['editar_materia']) ? 1 : 0,
            'pagos' => isset($permisos['pagos']) ? 1 : 0,
            'auditoria' => isset($permisos['auditoria']) ? 1 : 0,
            'secciones' => isset($permisos['secciones']) ? 1 : 0,
            'rela_materia_carrera' => isset($permisos['rela_materia_carrera']) ? 1 : 0,
            'periodos_academicos' => isset($permisos['periodos_academicos']) ? 1 : 0,
            'asig_secciones' => isset($permisos['asig_secciones']) ? 1 : 0,
            'asig_cursos' => isset($permisos['asig_cursos']) ? 1 : 0,
            'horarios' => isset($permisos['horarios']) ? 1 : 0,
            'gestion_director_carrera' => isset($permisos['gestion_director_carrera']) ? 1 : 0,
            'notas_cargadas' => isset($permisos['notas_cargadas']) ? 1 : 0,
            'consultar_notas' => isset($permisos['consultar_notas']) ? 1 : 0,
            'consultar_notas_pasadas' => isset($permisos['consultar_notas_pasadas']) ? 1 : 0,
            'tipos_pago' => isset($permisos['tipos_pago']) ? 1 : 0,
            'tipos_horario' => isset($permisos['tipos_horario']) ? 1 : 0,
            'horario_personal' => isset($permisos['horario_personal']) ? 1 : 0,
            'respaldo_bd' => isset($permisos['respaldo_bd']) ? 1 : 0,
            'gestionar_carrera' => isset($permisos['gestionar_carrera']) ? 1 : 0,
            'gestion_periodo_academico' => isset($permisos['gestion_periodo_academico']) ? 1 : 0,
            'gestion_asig_cursos' => isset($permisos['gestion_asig_cursos']) ? 1 : 0,
            'gestion_horario' => isset($permisos['gestion_horario']) ? 1 : 0,
            'titulos_re_materia' => isset($permisos['titulos_re_materia']) ? 1 : 0,
            'grado' => isset($permisos['grado']) ? 1 : 0,
            'gestion_grado' => isset($permisos['gestion_grado']) ? 1 : 0,
            'visita' => isset($permisos['visita']) ? 1 : 0,
        ];
        
        // VERIFICAR SI HAY CAMBIOS REALES
        $accesos_otorgados = [];
        $accesos_quitados = [];
        
        foreach ($permisos_nuevos as $permiso => $nuevo_valor) {
            $valor_anterior = $usuario_actual[$permiso] ?? 0;
            
            if ($valor_anterior != $nuevo_valor) {
                if ($nuevo_valor == 1) {
                    // Acceso otorgado
                    $accesos_otorgados[] = $permiso;
                } else {
                    // Acceso quitado
                    $accesos_quitados[] = $permiso;
                }
            }
        }
        
        // SI NO HAY CAMBIOS, RETORNAR SIN HACER NADA
        if (empty($accesos_otorgados) && empty($accesos_quitados)) {
            return true; // No hay cambios, retornar sin auditoría
        }
        
        // SOLO ACTUALIZAR SI HAY CAMBIOS REALES
        $query = "UPDATE users SET 
                 usuario = ?,
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
                 respaldo_bd = ?,
                 gestionar_carrera = ?,
                 gestion_periodo_academico = ?,
                 gestion_asig_cursos = ?,
                 gestion_horario = ?,
                 titulos_re_materia = ?,
                 grado = ?,
                 gestion_grado = ?,
                 visita = ?
                 WHERE id = ?";
        
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error al preparar query: " . $db->error);
        }
        
        $stmt->bind_param("iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii", 
            $permisos_nuevos['usuario'], $permisos_nuevos['estudiante'], $permisos_nuevos['docente'], 
            $permisos_nuevos['admin'], $permisos_nuevos['super_user'], $permisos_nuevos['editar_user'], 
            $permisos_nuevos['editar_nota'], $permisos_nuevos['editar_acceso'], $permisos_nuevos['editar_valores'],
            $permisos_nuevos['editar_estudiante'], $permisos_nuevos['agregar_estudiante'], $permisos_nuevos['agregar_docente'],
            $permisos_nuevos['editar_docente'], $permisos_nuevos['agregar_carrera'], $permisos_nuevos['agregar_materia'],
            $permisos_nuevos['editar_materia'], $permisos_nuevos['pagos'], $permisos_nuevos['auditoria'],
            $permisos_nuevos['secciones'], $permisos_nuevos['rela_materia_carrera'], $permisos_nuevos['periodos_academicos'],
            $permisos_nuevos['asig_secciones'], $permisos_nuevos['asig_cursos'], $permisos_nuevos['horarios'],
            $permisos_nuevos['gestion_director_carrera'], $permisos_nuevos['notas_cargadas'], $permisos_nuevos['consultar_notas'],
            $permisos_nuevos['consultar_notas_pasadas'], $permisos_nuevos['tipos_pago'], $permisos_nuevos['tipos_horario'],
            $permisos_nuevos['horario_personal'], $permisos_nuevos['respaldo_bd'], $permisos_nuevos['gestionar_carrera'],
            $permisos_nuevos['gestion_periodo_academico'], $permisos_nuevos['gestion_asig_cursos'], $permisos_nuevos['gestion_horario'],
            $permisos_nuevos['titulos_re_materia'], $permisos_nuevos['grado'], $permisos_nuevos['gestion_grado'],
            $permisos_nuevos['visita'],
            $user_id
        );
        
        $result = $stmt->execute();
        $stmt->close();
        
        if ($result) {
            // REGISTRAR EN AUDITORÍA - SOLO SI HUBO CAMBIOS REALES
            if (function_exists('registrarAuditoria')) {
                try {
                    // Preparar mensaje descriptivo para la auditoría
                    $mensaje_auditoria = "Permisos actualizados para usuario: " . $usuario_actual['username'];
                    
                    if (!empty($accesos_otorgados)) {
                        $mensaje_auditoria .= " - Accesos OTORGADOS: " . implode(', ', $accesos_otorgados);
                    }
                    
                    if (!empty($accesos_quitados)) {
                        if (!empty($accesos_otorgados)) {
                            $mensaje_auditoria .= " | ";
                        }
                        $mensaje_auditoria .= "Accesos QUITADOS: " . implode(', ', $accesos_quitados);
                    }
                    
                    registrarAuditoria(
                        "UPDATE", 
                        "users", 
                        $user_id, 
                        $usuario_actual, 
                        [
                            'usuario_afectado' => $usuario_actual['username'],
                            'usuario_afectado_id' => $user_id,
                            'usuario_editor' => $_SESSION['user']['username'] ?? 'Desconocido',
                            'usuario_editor_id' => $_SESSION['user']['id'] ?? 0,
                            'accesos_otorgados' => implode(', ', $accesos_otorgados),
                            'accesos_quitados' => implode(', ', $accesos_quitados),
                            'total_otorgados' => count($accesos_otorgados),
                            'total_quitados' => count($accesos_quitados),
                            'super_user_anterior' => $usuario_actual['super_user'],
                            'super_user_nuevo' => $permisos_nuevos['super_user']
                        ], 
                        "Gestión de Permisos", 
                        $mensaje_auditoria
                    );
                    
                } catch (Exception $e) {
                    error_log("Error en auditoría actualizarPermisosUsuario: " . $e->getMessage());
                }
            }
            
            return true;
        } else {
            throw new Exception("Error al ejecutar la actualización");
        }
        
    } catch (Exception $e) {
        error_log("Error en actualizarPermisosUsuario: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL ACTUALIZAR PERMISOS
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "users", 
                    $user_id, 
                    null, 
                    [
                        'usuario_afectado' => $user_id,
                        'usuario_editor' => $_SESSION['user']['username'] ?? 'Desconocido',
                        'error' => $e->getMessage(),
                        'permisos_solicitados' => json_encode($permisos)
                    ], 
                    "Gestión de Permisos", 
                    "Error al actualizar permisos del usuario ID: " . $user_id
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error actualizarPermisosUsuario: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

// Función para obtener todos los usuarios con permisos - SOLO LECTURA, SIN AUDITORÍA
function obtenerUsuariosConPermisos() {
    global $db;
    
    $query = "SELECT id, username, 
             usuario, estudiante, docente, admin, super_user, editar_user, editar_nota, editar_acceso, 
             editar_valores, editar_estudiante, agregar_estudiante, agregar_docente, editar_docente, 
             agregar_carrera, agregar_materia, editar_materia,
             pagos, auditoria, secciones, rela_materia_carrera, periodos_academicos, asig_secciones, 
             asig_cursos, horarios, gestion_director_carrera, notas_cargadas, consultar_notas, 
             consultar_notas_pasadas, tipos_pago, tipos_horario, horario_personal, respaldo_bd,
             gestionar_carrera, gestion_periodo_academico, gestion_asig_cursos, gestion_horario, titulos_re_materia,
             grado, gestion_grado, visita
             FROM users ORDER BY username";
    
    return $db->query($query);
}





//GRADUACION ***********************************************************************



/**
 * Obtener la cantidad de registros por página
 */
function obtener_registros_por_pagina() {
    if (isset($_GET['registros_por_pagina']) && in_array($_GET['registros_por_pagina'], [10, 20, 50, 100])) {
        return (int)$_GET['registros_por_pagina'];
    }
    return 20; // Valor por defecto
}

/**
 * Obtener ID del usuario admin logueado
 */
function obtener_id_admin() {
    // Probar diferentes variables de sesión comunes
    $posibles_variables = ['user_id', 'id', 'usuario_id', 'admin_id', 'userid', 'userId', 'idusuario'];
    
    foreach ($posibles_variables as $variable) {
        if (isset($_SESSION[$variable]) && !empty($_SESSION[$variable])) {
            return $_SESSION[$variable];
        }
    }
    
    // Si no se encuentra, usar un valor por defecto
    return 1;
}

/**
 * Obtener estudiantes con paginación para graduación - ACTUALIZADA
 */
function obtener_estudiantes_graduacion_paginados($filtros = [], $pagina = 1, $registros_por_pagina = 20) {
    global $db;
    
    try {
        // Primero obtener todos los estudiantes según los filtros
        $estudiantes_data = obtener_estudiantes_graduacion($filtros);
        $todos_estudiantes = [];
        
        // Convertir a array para poder manipularlo
        if (is_array($estudiantes_data)) {
            $todos_estudiantes = $estudiantes_data;
        } elseif ($estudiantes_data) {
            while ($estudiante = mysqli_fetch_assoc($estudiantes_data)) {
                $todos_estudiantes[] = $estudiante;
            }
        }
        
        // Si no hay filtro de estado, determinar el estado real de cada estudiante
        if (!isset($filtros['estado']) || empty($filtros['estado'])) {
            $estudiantes_con_estado_real = [];
            foreach ($todos_estudiantes as $estudiante) {
                // Si el estudiante no tiene estado definido en la tabla graduados, determinar su estado real
                if (empty($estudiante['estado']) || $estudiante['estado'] === null) {
                    $cumple_requisitos = cumple_requisitos_graduacion($estudiante['id']);
                    $estudiante['estado'] = $cumple_requisitos ? 'cumple_requisitos' : 'pendiente';
                }
                $estudiantes_con_estado_real[] = $estudiante;
            }
            $todos_estudiantes = $estudiantes_con_estado_real;
        }
        
        $total_registros = count($todos_estudiantes);
        $total_paginas = ceil($total_registros / $registros_por_pagina);
        
        // Aplicar paginación
        $inicio = ($pagina - 1) * $registros_por_pagina;
        $estudiantes_paginados = array_slice($todos_estudiantes, $inicio, $registros_por_pagina);
        
        // REGISTRAR EN AUDITORÍA - CONSULTA DE ESTUDIANTES PARA GRADUACIÓN
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "SELECT", 
                    "users", 
                    null, 
                    null, 
                    [
                        'filtros_aplicados' => $filtros,
                        'pagina' => $pagina,
                        'registros_por_pagina' => $registros_por_pagina,
                        'total_registros' => $total_registros
                    ], 
                    "Graduación", 
                    "Consulta de estudiantes para graduación"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría obtener_estudiantes_graduacion_paginados: " . $e->getMessage());
            }
        }
        
        return [
            'resultados' => $estudiantes_paginados,
            'total_registros' => $total_registros,
            'total_paginas' => $total_paginas,
            'pagina_actual' => $pagina
        ];
        
    } catch (Exception $e) {
        error_log("Error en obtener_estudiantes_graduacion_paginados: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR EN CONSULTA
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "users", 
                    null, 
                    null, 
                    [
                        'filtros' => $filtros,
                        'error' => $e->getMessage()
                    ], 
                    "Graduación", 
                    "Error al consultar estudiantes para graduación"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error obtener_estudiantes_graduacion_paginados: " . $auditError->getMessage());
            }
        }
        
        return [
            'resultados' => [],
            'total_registros' => 0,
            'total_paginas' => 0,
            'pagina_actual' => $pagina
        ];
    }
}

/**
 * Generar URL para paginación manteniendo los filtros
 */
function generar_url_paginacion($pagina) {
    $params = $_GET;
    $params['pagina'] = $pagina;
    
    // Mantener el parámetro de registros por página si existe
    if (isset($_GET['registros_por_pagina'])) {
        $params['registros_por_pagina'] = $_GET['registros_por_pagina'];
    }
    
    return 'grado.php?' . http_build_query($params);
}

/**
 * Obtener estudiantes con filtros para graduación - ACTUALIZADA para mostrar nombre carrera
 */
function obtener_estudiantes_graduacion($filtros = []) {
    global $db;
    
    try {
        $where = "WHERE u.estudiante = 1 AND u.status = 1";
        
        if (isset($filtros['buscar']) && !empty($filtros['buscar'])) {
            $buscar = mysqli_real_escape_string($db, $filtros['buscar']);
            $where .= " AND (u.nombre LIKE '%$buscar%' OR u.idusuario LIKE '%$buscar%')";
        }
        
        if (isset($filtros['carrera']) && !empty($filtros['carrera'])) {
            $carrera = mysqli_real_escape_string($db, $filtros['carrera']);
            $where .= " AND c.nombre_carrera = '$carrera'";
        }
        
        // Si se filtra por estado específico de graduación
        if (isset($filtros['estado']) && !empty($filtros['estado'])) {
            $estado = mysqli_real_escape_string($db, $filtros['estado']);
            
            if ($estado == 'cumple_requisitos') {
                // Obtener todos los estudiantes no graduados y determinar su estado real
                $query = "SELECT u.id, u.idusuario, u.nombre, u.carrera,
                                 c.nombre_carrera,
                                 g.id as id_graduado, g.estado, g.fecha_graduacion, 
                                 g.titulo_entregado, g.fecha_entrega_titulo
                          FROM users u 
                          LEFT JOIN carreras c ON u.carrera = c.id_carrera
                          LEFT JOIN graduados g ON u.id = g.id_usuario 
                          WHERE u.estudiante = 1 AND u.status = 1 
                          AND (g.id_usuario IS NULL OR g.estado = 'cumple_requisitos')
                          ORDER BY u.nombre";
            } else {
                // Estudiantes con estado específico en graduados
                $query = "SELECT u.id, u.idusuario, u.nombre, u.carrera,
                                 c.nombre_carrera,
                                 g.id as id_graduado, g.estado, g.fecha_graduacion, 
                                 g.titulo_entregado, g.fecha_entrega_titulo 
                          FROM users u 
                          INNER JOIN carreras c ON u.carrera = c.id_carrera
                          INNER JOIN graduados g ON u.id = g.id_usuario 
                          WHERE u.estudiante = 1 AND u.status = 1 
                          AND g.estado = '$estado'
                          ORDER BY u.nombre";
            }
        } else {
            // Mostrar todos los estudiantes con su estado de graduación
            $query = "SELECT u.id, u.idusuario, u.nombre, u.carrera,
                             c.nombre_carrera,
                             g.id as id_graduado, g.estado, g.fecha_graduacion, 
                             g.titulo_entregado, g.fecha_entrega_titulo 
                      FROM users u 
                      LEFT JOIN carreras c ON u.carrera = c.id_carrera
                      LEFT JOIN graduados g ON u.id = g.id_usuario 
                      $where 
                      ORDER BY u.nombre";
        }
        
        $result = mysqli_query($db, $query);
        
        if (!$result) {
            throw new Exception("Error en consulta: " . mysqli_error($db));
        }
        
        // Si estamos filtrando por "cumple_requisitos", determinar el estado real de cada estudiante
        if (isset($filtros['estado']) && $filtros['estado'] == 'cumple_requisitos') {
            $estudiantes_filtrados = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($estudiante = mysqli_fetch_assoc($result)) {
                    // Verificar si realmente cumple requisitos
                    if (cumple_requisitos_graduacion($estudiante['id'])) {
                        // Si cumple requisitos, actualizar el estado
                        $estudiante['estado'] = 'cumple_requisitos';
                        $estudiantes_filtrados[] = $estudiante;
                    }
                    // Si no cumple requisitos, NO lo incluimos en los resultados
                }
            }
            return $estudiantes_filtrados;
        }
        
        // Para otros casos, procesar los estados correctamente
        $estudiantes_procesados = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($estudiante = mysqli_fetch_assoc($result)) {
                // Si el estudiante no tiene registro en graduados, determinar su estado real
                if (empty($estudiante['id_graduado']) || $estudiante['estado'] === null) {
                    $cumple_requisitos = cumple_requisitos_graduacion($estudiante['id']);
                    $estudiante['estado'] = $cumple_requisitos ? 'cumple_requisitos' : 'pendiente';
                }
                $estudiantes_procesados[] = $estudiante;
            }
            return $estudiantes_procesados;
        }
        
        return $result;
        
    } catch (Exception $e) {
        error_log("Error en obtener_estudiantes_graduacion: " . $e->getMessage());
        return [];
    }
}

/**
 * Marcar estudiante como graduado
 */
function marcar_como_graduado($id_usuario) {
    global $db;
    
    try {
        $id_usuario = mysqli_real_escape_string($db, $id_usuario);
        
        // Obtener información del estudiante antes de la operación
        $estudiante_info = null;
        $query_info = "SELECT u.id, u.nombre, u.idusuario, c.nombre_carrera 
                      FROM users u 
                      LEFT JOIN carreras c ON u.carrera = c.id_carrera 
                      WHERE u.id = '$id_usuario'";
        $result_info = mysqli_query($db, $query_info);
        if ($result_info && mysqli_num_rows($result_info) > 0) {
            $estudiante_info = mysqli_fetch_assoc($result_info);
        }
        
        // Obtener el ID del admin desde la sesión
        $id_admin = obtener_id_admin();
        
        $observaciones = isset($_POST['observaciones']) ? mysqli_real_escape_string($db, $_POST['observaciones']) : '';
        
        // Verificar si ya existe registro
        $check = mysqli_query($db, "SELECT id, estado FROM graduados WHERE id_usuario = '$id_usuario'");
        $registro_existente = null;
        if ($check && mysqli_num_rows($check) > 0) {
            $registro_existente = mysqli_fetch_assoc($check);
        }
        
        if ($registro_existente) {
            // Actualizar registro existente
            $query = "UPDATE graduados SET 
                     estado = 'graduado', 
                     fecha_graduacion = NOW(), 
                     id_admin_graduacion = '$id_admin', 
                     observaciones = '$observaciones',
                     fecha_actualizacion = NOW() 
                     WHERE id_usuario = '$id_usuario'";
        } else {
            // Insertar nuevo registro
            $query = "INSERT INTO graduados 
                     (id_usuario, estado, fecha_graduacion, id_admin_graduacion, observaciones) 
                     VALUES 
                     ('$id_usuario', 'graduado', NOW(), '$id_admin', '$observaciones')";
        }
        
        if (mysqli_query($db, $query)) {
            $id_graduado = $registro_existente ? $registro_existente['id'] : mysqli_insert_id($db);
            
            // REGISTRAR EN AUDITORÍA - MARCADO COMO GRADUADO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        $registro_existente ? "UPDATE" : "INSERT", 
                        "graduados", 
                        $id_graduado, 
                        $registro_existente ? ['estado' => $registro_existente['estado']] : null, 
                        [
                            'estado' => 'graduado',
                            'id_usuario' => $id_usuario,
                            'estudiante_nombre' => $estudiante_info['nombre'] ?? 'Desconocido',
                            'estudiante_cedula' => $estudiante_info['idusuario'] ?? '',
                            'carrera' => $estudiante_info['nombre_carrera'] ?? '',
                            'id_admin_graduacion' => $id_admin,
                            'observaciones' => $observaciones
                        ], 
                        "Graduación", 
                        "Estudiante marcado como graduado"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría marcar_como_graduado: " . $e->getMessage());
                }
            }
            
            $_SESSION['mensaje'] = "Estudiante marcado como graduado exitosamente";
            $_SESSION['tipo_mensaje'] = "success";
            return true;
        } else {
            throw new Exception("Error en consulta: " . mysqli_error($db));
        }
        
    } catch (Exception $e) {
        error_log("Error en marcar_como_graduado: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL MARCAR COMO GRADUADO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "graduados", 
                    null, 
                    null, 
                    [
                        'id_usuario' => $id_usuario,
                        'error' => $e->getMessage()
                    ], 
                    "Graduación", 
                    "Error al marcar estudiante como graduado"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error marcar_como_graduado: " . $auditError->getMessage());
            }
        }
        
        $_SESSION['mensaje'] = "Error al marcar como graduado: " . $e->getMessage();
        $_SESSION['tipo_mensaje'] = "error";
        return false;
    }
}

/**
 * Marcar título como entregado
 */
function marcar_titulo_entregado($id_graduado) {
    global $db;
    
    try {
        $id_graduado = mysqli_real_escape_string($db, $id_graduado);
        
        // Obtener información del graduado antes de la operación
        $graduado_info = null;
        $query_info = "SELECT g.*, u.nombre, u.idusuario, c.nombre_carrera 
                      FROM graduados g 
                      INNER JOIN users u ON g.id_usuario = u.id 
                      LEFT JOIN carreras c ON u.carrera = c.id_carrera 
                      WHERE g.id = '$id_graduado'";
        $result_info = mysqli_query($db, $query_info);
        if ($result_info && mysqli_num_rows($result_info) > 0) {
            $graduado_info = mysqli_fetch_assoc($result_info);
        }
        
        // Obtener el ID del admin desde la sesión
        $id_admin = obtener_id_admin();
        
        $query = "UPDATE graduados SET 
                 titulo_entregado = 1, 
                 fecha_entrega_titulo = NOW(), 
                 id_admin_entrega_titulo = '$id_admin', 
                 estado = 'titulo_entregado',
                 fecha_actualizacion = NOW() 
                 WHERE id = '$id_graduado'";
        
        if (mysqli_query($db, $query)) {
            // REGISTRAR EN AUDITORÍA - TÍTULO MARCADO COMO ENTREGADO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "UPDATE", 
                        "graduados", 
                        $id_graduado, 
                        [
                            'titulo_entregado' => $graduado_info['titulo_entregado'] ?? 0,
                            'estado' => $graduado_info['estado'] ?? ''
                        ], 
                        [
                            'titulo_entregado' => 1,
                            'estado' => 'titulo_entregado',
                            'id_usuario' => $graduado_info['id_usuario'] ?? '',
                            'estudiante_nombre' => $graduado_info['nombre'] ?? 'Desconocido',
                            'estudiante_cedula' => $graduado_info['idusuario'] ?? '',
                            'carrera' => $graduado_info['nombre_carrera'] ?? '',
                            'id_admin_entrega_titulo' => $id_admin
                        ], 
                        "Graduación", 
                        "Título marcado como entregado"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría marcar_titulo_entregado: " . $e->getMessage());
                }
            }
            
            $_SESSION['mensaje'] = "Título marcado como entregado exitosamente";
            $_SESSION['tipo_mensaje'] = "success";
            return true;
        } else {
            throw new Exception("Error en consulta: " . mysqli_error($db));
        }
        
    } catch (Exception $e) {
        error_log("Error en marcar_titulo_entregado: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL MARCAR TÍTULO COMO ENTREGADO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "graduados", 
                    $id_graduado, 
                    null, 
                    [
                        'id_graduado' => $id_graduado,
                        'error' => $e->getMessage()
                    ], 
                    "Graduación", 
                    "Error al marcar título como entregado"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error marcar_titulo_entregado: " . $auditError->getMessage());
            }
        }
        
        $_SESSION['mensaje'] = "Error al marcar título como entregado: " . $e->getMessage();
        $_SESSION['tipo_mensaje'] = "error";
        return false;
    }
}

/**
 * Obtener badge de estado para mostrar - ACTUALIZADA
 */
function obtener_badge_estado($estado) {
    if (empty($estado) || $estado == 'pendiente') {
        return '<span class="badge badge-secondary">Pendiente</span>';
    }
    
    switch ($estado) {
        case 'cumple_requisitos':
            return '<span class="badge badge-warning">Cumple Requisitos</span>';
        case 'graduado':
            return '<span class="badge badge-success">Graduado</span>';
        case 'titulo_entregado':
            return '<span class="badge badge-info">Título Entregado</span>';
        default:
            return '<span class="badge badge-secondary">Pendiente</span>';
    }
}

/**
 * Generar botones de acción según el estado
 */
function generar_botones_accion($estudiante) {
    $botones = '';
    
    $id_usuario = $estudiante['id'];
    $estado = $estudiante['estado'];
    $id_graduado = isset($estudiante['id_graduado']) ? $estudiante['id_graduado'] : null;
    
    if (empty($estado) || $estado == 'cumple_requisitos') {
        // Botón principal - Marcar Graduado
        $botones .= '<div class="mb-2">';
        $botones .= '<button class="btn btn-success btn-sm" onclick="confirmarGraduacion('.$id_usuario.')">
                        <i class="fas fa-graduation-cap"></i> Marcar Graduado
                     </button>';
        $botones .= '</div>';
        
        // Botones de documentos en un grupo organizado
        $botones .= '<div class="btn-group-vertical d-flex flex-column gap-1" style="min-width: 200px;">';
        
        // Fila 1: Documentos principales
        $botones .= '<div class="d-flex gap-1">';
        $botones .= '<a target="_blank" class="btn btn-outline-primary btn-sm flex-fill" href="constancias/pdf_notas_certificadas.php?id='.$id_usuario.'">
                        <i class="fas fa-file-alt"></i> Notas Certificadas
                    </a>';
        $botones .= '<a target="_blank" class="btn btn-outline-secondary btn-sm flex-fill" href="constancias/pdf_servicio_comunitario.php?id='.$id_usuario.'">
                        <i class="fas fa-hands-helping"></i> Servicio Com.
                    </a>';
        $botones .= '</div>';
        
        // Fila 2: Nuevos documentos
        $botones .= '<div class="d-flex gap-1">';
        $botones .= '<a target="_blank" class="btn btn-outline-success btn-sm flex-fill" href="constancias/pdf_carta_culminacion.php?id='.$id_usuario.'">
                        <i class="fas fa-file-contract"></i> Carta Culminación
                    </a>';
        $botones .= '<a target="_blank" class="btn btn-outline-info btn-sm flex-fill" href="constancias/pdf_constancia.php?id='.$id_usuario.'">
                        <i class="fas fa-file-certificate"></i> Constancia
                    </a>';
        $botones .= '</div>';
        
        $botones .= '</div>'; // Cierre del grupo
        
    } elseif ($estado == 'graduado' && empty($estudiante['titulo_entregado'])) {
        // Si está graduado pero no se le ha entregado título
        $botones .= '<form method="POST" style="display:inline;">
                        <input type="hidden" name="id_graduado" value="'.$id_graduado.'">
                        <button type="submit" name="marcar_titulo_entregado" class="btn btn-info btn-sm">
                            <i class="fas fa-file-certificate"></i> Título Entregado
                        </button>
                    </form>';
    } elseif ($estado == 'titulo_entregado') {
        $botones .= '<span class="text-success"><i class="fas fa-check-circle"></i> Completado</span>';
    } else {
        $botones .= '<span class="text-muted">No aplica</span>';
    }
    
    return $botones;
}

/**
 * Obtener lista de carreras - ACTUALIZADA para mostrar nombres
 */
function obtener_carreras() {
    global $db;
    
    try {
        $query = "SELECT c.id_carrera, c.nombre_carrera 
                  FROM carreras c
                  INNER JOIN users u ON c.id_carrera = u.carrera
                  WHERE u.estudiante = 1 
                  AND c.nombre_carrera IS NOT NULL 
                  AND c.nombre_carrera != '' 
                  GROUP BY c.id_carrera, c.nombre_carrera
                  ORDER BY c.nombre_carrera";
        return mysqli_query($db, $query);
    } catch (Exception $e) {
        error_log("Error en obtener_carreras: " . $e->getMessage());
        return false;
    }
}

// =============================================
// FUNCIONES PARA EVALUACIÓN DE GRADOS (TSU Y GRADO COMPLETO)
// =============================================

/**
 * Determinar si un estudiante es apto para el primer título (TSU) o grado completo - ACTUALIZADA
 */
function es_apto_para_grado($estudiante_id) {
    global $db;
    
    try {
        $estudiante_id = mysqli_real_escape_string($db, $estudiante_id);
        
        // 1. Obtener información del estudiante y su carrera - ACTUALIZADA para nombre carrera
        $query_estudiante = "SELECT u.id, u.carrera, c.nombre_carrera 
                            FROM users u 
                            LEFT JOIN carreras c ON u.carrera = c.id_carrera 
                            WHERE u.id = '$estudiante_id'";
        $result_estudiante = mysqli_query($db, $query_estudiante);
        
        if (!$result_estudiante || mysqli_num_rows($result_estudiante) === 0) {
            return [
                'apto_tsu' => false,
                'apto_grado_completo' => false,
                'materias_aprobadas_tsu' => 0,
                'total_materias_tsu' => 0,
                'porcentaje_tsu' => 0,
                'creditos_aprobados_tsu' => 0,
                'materias_aprobadas_completo' => 0,
                'total_materias_carrera' => 0,
                'porcentaje_completo' => 0,
                'creditos_aprobados_completo' => 0,
                'requisitos_adicionales' => false,
                'carrera' => 'No especificada'
            ];
        }
        
        $estudiante = mysqli_fetch_assoc($result_estudiante);
        $carrera_id = $estudiante['carrera'];
        $nombre_carrera = $estudiante['nombre_carrera'] ?: 'Carrera ' . $carrera_id;
        
        // 2. Obtener todas las materias de la carrera (para evaluación completa)
        $query_materias_completo = "SELECT m.id_materia, m.trayecto, m.creditos
                                   FROM carrera_materia cm
                                   INNER JOIN materias m ON cm.id_materia = m.id_materia
                                   WHERE cm.id_carrera = '$carrera_id' 
                                   AND m.activa = 1
                                   ORDER BY m.trayecto";
        
        $result_materias_completo = mysqli_query($db, $query_materias_completo);
        $total_materias_carrera = mysqli_num_rows($result_materias_completo);
        
        if ($total_materias_carrera === 0) {
            return [
                'apto_tsu' => false,
                'apto_grado_completo' => false,
                'materias_aprobadas_tsu' => 0,
                'total_materias_tsu' => 0,
                'porcentaje_tsu' => 0,
                'creditos_aprobados_tsu' => 0,
                'materias_aprobadas_completo' => 0,
                'total_materias_carrera' => 0,
                'porcentaje_completo' => 0,
                'creditos_aprobados_completo' => 0,
                'requisitos_adicionales' => false,
                'carrera' => $nombre_carrera
            ];
        }
        
        // 3. Obtener solo materias de TSU (trayectos 0, 1, 2)
        $query_materias_tsu = "SELECT m.id_materia, m.trayecto, m.creditos
                              FROM carrera_materia cm
                              INNER JOIN materias m ON cm.id_materia = m.id_materia
                              WHERE cm.id_carrera = '$carrera_id' 
                              AND m.trayecto IN (0, 1, 2)
                              AND m.activa = 1";
        
        $result_materias_tsu = mysqli_query($db, $query_materias_tsu);
        $total_materias_tsu = mysqli_num_rows($result_materias_tsu);
        
        // 4. Contar materias aprobadas para TSU
        $materias_aprobadas_tsu = 0;
        $creditos_aprobados_tsu = 0;
        
        if ($result_materias_tsu) {
            mysqli_data_seek($result_materias_tsu, 0);
            while ($materia = mysqli_fetch_assoc($result_materias_tsu)) {
                $materia_id = $materia['id_materia'];
                $trayecto = $materia['trayecto'];
                $creditos = $materia['creditos'] ?: 3;
                
                if (tiene_materia_aprobada($estudiante_id, $materia_id, $trayecto)) {
                    $materias_aprobadas_tsu++;
                    $creditos_aprobados_tsu += $creditos;
                }
            }
        }
        
        // 5. Contar materias aprobadas para carrera completa
        $materias_aprobadas_completo = 0;
        $creditos_aprobados_completo = 0;
        
        mysqli_data_seek($result_materias_completo, 0);
        while ($materia = mysqli_fetch_assoc($result_materias_completo)) {
            $materia_id = $materia['id_materia'];
            $trayecto = $materia['trayecto'];
            $creditos = $materia['creditos'] ?: 3;
            
            if (tiene_materia_aprobada($estudiante_id, $materia_id, $trayecto)) {
                $materias_aprobadas_completo++;
                $creditos_aprobados_completo += $creditos;
            }
        }
        
        // 6. Verificar requisitos adicionales
        $requisitos_adicionales_cumplidos = verificar_requisitos_adicionales($estudiante_id);
        
        // 7. Determinar estados
        $porcentaje_tsu = $total_materias_tsu > 0 ? ($materias_aprobadas_tsu / $total_materias_tsu) * 100 : 0;
        $porcentaje_completo = $total_materias_carrera > 0 ? ($materias_aprobadas_completo / $total_materias_carrera) * 100 : 0;
        
        // Para TSU: 90% de materias aprobadas + requisitos adicionales
        $apto_tsu = ($porcentaje_tsu >= 90) && $requisitos_adicionales_cumplidos;
        
        // Para Grado Completo: 100% de materias aprobadas + requisitos adicionales
        $apto_grado_completo = ($porcentaje_completo >= 100) && $requisitos_adicionales_cumplidos;
        
        return [
            'apto_tsu' => $apto_tsu,
            'apto_grado_completo' => $apto_grado_completo,
            
            // Estadísticas TSU
            'materias_aprobadas_tsu' => $materias_aprobadas_tsu,
            'total_materias_tsu' => $total_materias_tsu,
            'porcentaje_tsu' => round($porcentaje_tsu, 1),
            'creditos_aprobados_tsu' => $creditos_aprobados_tsu,
            
            // Estadísticas carrera completa
            'materias_aprobadas_completo' => $materias_aprobadas_completo,
            'total_materias_carrera' => $total_materias_carrera,
            'porcentaje_completo' => round($porcentaje_completo, 1),
            'creditos_aprobados_completo' => $creditos_aprobados_completo,
            
            // Información general
            'requisitos_adicionales' => $requisitos_adicionales_cumplidos,
            'carrera' => $nombre_carrera
        ];
        
    } catch (Exception $e) {
        error_log("Error en es_apto_para_grado: " . $e->getMessage());
        return [
            'apto_tsu' => false,
            'apto_grado_completo' => false,
            'materias_aprobadas_tsu' => 0,
            'total_materias_tsu' => 0,
            'porcentaje_tsu' => 0,
            'creditos_aprobados_tsu' => 0,
            'materias_aprobadas_completo' => 0,
            'total_materias_carrera' => 0,
            'porcentaje_completo' => 0,
            'creditos_aprobados_completo' => 0,
            'requisitos_adicionales' => false,
            'carrera' => 'Error en consulta'
        ];
    }
}

/**
 * Verificar si un estudiante tiene una materia aprobada
 */
function tiene_materia_aprobada($estudiante_id, $materia_id, $trayecto) {
    global $db;
    
    try {
        $estudiante_id = mysqli_real_escape_string($db, $estudiante_id);
        $materia_id = mysqli_real_escape_string($db, $materia_id);
        $trayecto = mysqli_real_escape_string($db, $trayecto);
        
        // Consulta para verificar nota aprobada
        $campo_trayecto = 'trayecto_' . $trayecto;
        $query_nota = "SELECT $campo_trayecto as nota 
                      FROM notas_definitivas 
                      WHERE id_usuario = '$estudiante_id' 
                      AND id_materia = '$materia_id' 
                      AND $campo_trayecto >= 12  -- Nota mínima para aprobar
                      AND $campo_trayecto IS NOT NULL
                      LIMIT 1";
        
        $result_nota = mysqli_query($db, $query_nota);
        
        if ($result_nota && mysqli_num_rows($result_nota) > 0) {
            $nota_data = mysqli_fetch_assoc($result_nota);
            // Verificar que la nota sea realmente un número y esté aprobada
            return is_numeric($nota_data['nota']) && $nota_data['nota'] >= 10;
        }
        
        return false;
        
    } catch (Exception $e) {
        error_log("Error en tiene_materia_aprobada: " . $e->getMessage());
        return false;
    }
}

/**
 * Verificar requisitos adicionales para el grado
 */
function verificar_requisitos_adicionales($estudiante_id) {
    global $db;
    
    try {
        $estudiante_id = mysqli_real_escape_string($db, $estudiante_id);
        
        // Por ahora, asumimos que todos los requisitos adicionales están cumplidos
        // Debes implementar estas verificaciones según las reglas de tu universidad
        return true;
        
    } catch (Exception $e) {
        error_log("Error en verificar_requisitos_adicionales: " . $e->getMessage());
        return false;
    }
}

/**
 * Función para verificar requisitos de graduación
 */
function cumple_requisitos_graduacion($id_usuario) {
    try {
        $info_aptitud = es_apto_para_grado($id_usuario);
        
        // Para la página de graduación, consideramos aptos tanto TSU como grado completo
        return ($info_aptitud['apto_tsu'] || $info_aptitud['apto_grado_completo']);
        
    } catch (Exception $e) {
        error_log("Error en cumple_requisitos_graduacion: " . $e->getMessage());
        return false;
    }
}



//IMAGENES SOPORTE *********************************************************************


/**
 * Función para subir imagen o PDF de soporte
 * Se ajusta la ruta para que sea absoluta y no rompa el JSON
 */
function subirSoporte($archivo) {
    // Definimos la ruta absoluta basada en la ubicación de este archivo (functions.php)
    // Asumiendo que functions.php está en /funciones/ y la carpeta soportes en la raíz /soportes/
    $directorio = dirname(__DIR__) . '/soportes/';
    
    // Verificar y crear directorio si no existe (silenciamos con @ para evitar salida de texto)
    if (!file_exists($directorio)) {
        if (!@mkdir($directorio, 0755, true)) {
            return ['success' => false, 'error' => 'El servidor no tiene permisos para crear la carpeta de soportes en: ' . $directorio];
        }
    }
    
    // Validar que se haya enviado un archivo
    if (!isset($archivo['name']) || empty($archivo['name'])) {
        return ['success' => false, 'error' => 'No se ha seleccionado ningún archivo'];
    }
    
    // Validar errores internos de PHP al subir
    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        $errores = [
            UPLOAD_ERR_INI_SIZE   => 'El archivo excede el límite (upload_max_filesize)',
            UPLOAD_ERR_FORM_SIZE  => 'El archivo excede el límite del formulario',
            UPLOAD_ERR_PARTIAL    => 'Subida incompleta',
            UPLOAD_ERR_NO_FILE    => 'No se subió archivo',
            UPLOAD_ERR_NO_TMP_DIR => 'Falta carpeta temporal en el servidor',
            UPLOAD_ERR_CANT_WRITE => 'Error al escribir en disco',
            UPLOAD_ERR_EXTENSION  => 'Una extensión de PHP bloqueó la subida'
        ];
        return ['success' => false, 'error' => $errores[$archivo['error']] ?? 'Error desconocido en el servidor'];
    }
    
    // Validar extensión
    $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    
    if (!in_array($extension, $tiposPermitidos)) {
        return ['success' => false, 'error' => 'Formato no permitido. Use: ' . implode(', ', $tiposPermitidos)];
    }
    
    // Validar tamaño (5MB)
    $max_size = 5 * 1024 * 1024;
    if ($archivo['size'] > $max_size) {
        return ['success' => false, 'error' => 'Archivo demasiado pesado. Máximo 5MB'];
    }
    
    // Generar nombre único para evitar sobreescribir otros soportes
    $nombreUnico = uniqid('soporte_', true) . '_' . time() . '.' . $extension;
    $rutaFinal = $directorio . $nombreUnico;
    
    // Intentar mover el archivo
    if (@move_uploaded_file($archivo['tmp_name'], $rutaFinal)) {
        return [
            'success' => true,
            'ruta'    => $nombreUnico,
            'tipo'    => $extension,
            'tamaño'  => $archivo['size']
        ];
    } else {
        return ['success' => false, 'error' => 'No se pudo guardar el archivo. Verifique permisos de escritura en /soportes/'];
    }
}

/**
 * Función para eliminar un archivo de soporte anterior
 */
function eliminarSoporteAnterior($nombreArchivo) {
    if (empty($nombreArchivo)) return false;
    
    $rutaCompleta = dirname(__DIR__) . '/soportes/' . $nombreArchivo;
    
    if (file_exists($rutaCompleta)) {
        return @unlink($rutaCompleta);
    }
    return false;
}

/**
 * Función para obtener los datos de soporte actuales de la BD
 */
function obtenerSoporteActual($id_estudiante, $id_materia, $id_periodo) {
    global $db;
    
    $query = "SELECT soporte, tipo_archivo FROM notas_pendientes 
              WHERE id_usuario = ? 
              AND id_materia = ? 
              AND id_periodo = ? 
              LIMIT 1";
    
    // Usamos sentencias preparadas para mayor seguridad
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param("iii", $id_estudiante, $id_materia, $id_periodo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
    }
    
    return null;
}



// PLANILLA VACIA DE ESTUDIANTES PARA LLENADO MANUAL



/**
 * Clase para generar PDF de planilla de notas
 */
class PDF_PlanillaNotas {
    private $pdf;
    private $info;
    private $estudiantes;
    
    function __construct() {
        // Asegúrate de que la ruta a FPDF sea la correcta en tu proyecto
        require_once('../fpdf/fpdf.php');
        $this->pdf = new FPDF('P', 'mm', 'A4'); // Orientación Vertical, milímetros, A4
    }
    
    function generarPlanilla($info, $estudiantes) {
        $this->info = $info;
        $this->estudiantes = $estudiantes;
        
        $this->pdf->AddPage();
        $this->Cuerpo();
        $this->pdf->Output('D', $this->getNombreArchivo());
        exit;
    }
    
    function Membrete() {
        // Configurar márgenes
        $margin = 10;
        $pageWidth = $this->pdf->GetPageWidth();
        
        // Logo (si existe)
        $logoPath = '../images/uptpc.png';
        if (file_exists($logoPath)) {
            $this->pdf->Image($logoPath, $margin, 8, 15, 15);
        }
        
        // Texto del membrete
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetY(10);
        $this->pdf->Cell(0, 4, $this->codificarTexto('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(0, 4, $this->codificarTexto('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->pdf->Cell(0, 4, $this->codificarTexto('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'C');
        
        // Fecha
        $this->pdf->SetFont('Arial', '', 7);
        $this->pdf->SetXY($pageWidth - $margin - 25, 8);
        $this->pdf->Cell(25, 4, date('d/m/Y'), 0, 0, 'R');
        
        // Línea separadora
        $this->pdf->SetY(28);
        $this->pdf->Cell(0, 0, '', 'T');
        $this->pdf->Ln(5);
    }
    
    function Header() {
        $this->Membrete();
        
        // Título principal
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->Cell(0, 6, $this->codificarTexto('PLANILLA DE REGISTRO DE NOTAS'), 0, 1, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->Cell(0, 4, $this->codificarTexto('SISTEMA DE CONTROL DE NOTAS'), 0, 1, 'C');
        $this->pdf->Ln(1);
        
        // Información en 2 columnas
        $this->pdf->SetFont('Arial', '', 8);
        
        // Columna izquierda
        $x = $this->pdf->GetX();
        $y = $this->pdf->GetY();
        
        $this->pdf->Cell(20, 4, $this->codificarTexto('Sección:'), 0, 0);
        $this->pdf->Cell(40, 4, $this->codificarTexto($this->info['codigo_seccion']), 0, 1);
        
        $this->pdf->SetX($x);
        $this->pdf->Cell(20, 4, $this->codificarTexto('Carrera:'), 0, 0);
        $this->pdf->Cell(40, 4, $this->codificarTexto($this->info['nombre_carrera']), 0, 1);
        
        $this->pdf->SetX($x);
        $this->pdf->Cell(20, 4, $this->codificarTexto('Trayecto:'), 0, 0);
        $this->pdf->Cell(40, 4, $this->codificarTexto($this->info['nombre_trayecto']), 0, 1);
        
        // Columna derecha
        $this->pdf->SetXY($x + 80, $y);
        
        $this->pdf->Cell(25, 4, $this->codificarTexto('Materia:'), 0, 0);
        $this->pdf->Cell(50, 4, $this->codificarTexto($this->info['nombre_materia']), 0, 1);
        
        $this->pdf->SetX($x + 80);
        $this->pdf->Cell(25, 4, $this->codificarTexto('Cod materia:'), 0, 0);
        $this->pdf->Cell(50, 4, $this->codificarTexto($this->info['cod_materia']), 0, 1);
        
        $this->pdf->SetX($x + 80);
        $this->pdf->Cell(25, 4, $this->codificarTexto('Periodo:'), 0, 0);
        $this->pdf->Cell(50, 4, $this->codificarTexto($this->info['nombre_periodo']), 0, 1);
        
        $this->pdf->SetX($x);
        $this->pdf->Ln(5); // Un poco más de espacio antes de la tabla
    }
    
    function Firma() {
        // FIRMA MÁS ARRIBA - justo después de la tabla
        $this->pdf->SetFont('Arial', '', 9);
        
        // Línea para firma
        $this->pdf->Cell(0, 4, '_________________________________', 0, 1, 'C');
        
        // Datos del profesor
        $this->pdf->Cell(0, 4, $this->codificarTexto('Firma del Docente'), 0, 1, 'C');
        
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(0, 3, $this->codificarTexto('Nombre: _________________________'), 0, 1, 'C');
        $this->pdf->Cell(0, 3, $this->codificarTexto('Cédula: _________________________'), 0, 1, 'C');
        $this->pdf->Cell(0, 3, $this->codificarTexto('Materia: ') . $this->codificarTexto($this->info['nombre_materia']), 0, 1, 'C');
    }
    
    function Cuerpo() {
        // Llamar Header
        $this->Header();
        
        // --- CONFIGURACIÓN DE ANCHOS PARA 12 COLUMNAS ---
        // Ajustamos los anchos para que quepan en una hoja A4 (aprox 190mm ancho útil)
        $w_num = 8;
        $w_ced = 20;
        $w_nom = 60;  // Espacio suficiente para nombre
        $w_nota = 7;  // Ancho de cada casillero de nota (7mm x 12 = 84mm)
        $w_fin = 10;  // Nota final
        $cant_notas = 12;

        // Calcular el ancho total de la tabla
        $ancho_total_tabla = $w_num + $w_ced + $w_nom + ($cant_notas * $w_nota) + $w_fin;
        
        // Calcular la posición X para centrar la tabla
        $margen_izquierdo = ($this->pdf->GetPageWidth() - $ancho_total_tabla) / 2;
        
        // Establecer la posición X para centrar
        $this->pdf->SetX($margen_izquierdo);
        
        // Encabezado de la tabla centrado con los parámetros de ancho
        $this->agregarEncabezadoTabla($margen_izquierdo, $w_num, $w_ced, $w_nom, $w_nota, $w_fin, $cant_notas);
        
        // Estudiantes - tabla centrada
        $contador = 0;
        foreach ($this->estudiantes as $estudiante) {
            $contador++;
            
            // Establecer posición X centrada para cada fila
            $this->pdf->SetX($margen_izquierdo);
            
            $this->pdf->SetFont('Arial', '', 7);
            
            // Altura de la fila
            $h_fila = 5;
            
            $this->pdf->Cell($w_num, $h_fila, $contador, 1, 0, 'C');
            $this->pdf->Cell($w_ced, $h_fila, $estudiante['cedula'], 1, 0, 'C');
            
            $nombreCompleto = $this->codificarTexto($estudiante['nombre']);
            if (strlen($nombreCompleto) > 30) {
                $nombreCompleto = substr($nombreCompleto, 0, 30) . '...';
            }
            // Alineación L (Left) para el nombre se ve mejor
            $this->pdf->Cell($w_nom, $h_fila, ' ' . $nombreCompleto, 1, 0, 'L');
            
            // 12 casillas para notas
            for ($i = 1; $i <= $cant_notas; $i++) {
                $this->pdf->Cell($w_nota, $h_fila, '', 1, 0, 'C');
            }
            
            // Casilla para nota final
            $this->pdf->Cell($w_fin, $h_fila, '', 1, 1, 'C');
        }
        
        // AGREGAR FIRMA DESPUÉS DE LA TABLA
        $this->pdf->Ln(15); // Espacio antes de la firma
        $this->Firma();
    }
    
    function agregarEncabezadoTabla($margen_izquierdo, $w_num, $w_ced, $w_nom, $w_nota, $w_fin, $cant_notas) {
        // Establecer posición X para el encabezado
        $this->pdf->SetX($margen_izquierdo);
        
        // Configuración de fuente y color para que se parezca a la imagen
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->SetFillColor(230, 230, 230); // Gris claro tipo formulario
        $this->pdf->SetLineWidth(0.2); // Líneas finas y nítidas
        
        $h_header = 8; // Altura del encabezado
        
        $this->pdf->Cell($w_num, $h_header, 'N', 1, 0, 'C', true);
        $this->pdf->Cell($w_ced, $h_header, $this->codificarTexto('Cédula'), 1, 0, 'C', true);
        $this->pdf->Cell($w_nom, $h_header, $this->codificarTexto('Apellidos y Nombres'), 1, 0, 'C', true);
        
        // 12 casillas para notas numeradas
        for ($i = 1; $i <= $cant_notas; $i++) {
            // Aquí encuadramos bien el número
            $this->pdf->Cell($w_nota, $h_header, $i, 1, 0, 'C', true);
        }
        
        $this->pdf->Cell($w_fin, $h_header, 'DEF', 1, 1, 'C', true);
        
        // Resetear colores
        $this->pdf->SetFillColor(255, 255, 255);
        $this->pdf->SetFont('Arial', '', 7);
    }
    
    /**
     * Función para codificar texto correctamente
     */
    function codificarTexto($texto) {
        if (mb_detect_encoding($texto, 'UTF-8', true)) {
            return mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8');
        }
        return $texto;
    }
    
    function getNombreArchivo() {
        $seccion = preg_replace('/[^a-zA-Z0-9]/', '_', $this->info['codigo_seccion']);
        $materia = preg_replace('/[^a-zA-Z0-9]/', '_', $this->info['cod_materia']);
        return "Planilla_Notas_{$seccion}_{$materia}.pdf";
    }
}

// --------------------------------------------------------------------------
// FUNCIONES AUXILIARES (INCLUIDAS TAL CUAL SE SOLICITÓ)
// --------------------------------------------------------------------------

/**
 * Generar planilla PDF para lista de estudiantes con casillas de notas
 */
function generarPlanillaNotasPDF($seccion_id, $materia_id, $docente_id) {
    global $db;
    
    // Verificar que el docente tiene acceso a esta sección y materia
    if (!verificarAccesoDocente($docente_id, $seccion_id, $materia_id)) {
        return false;
    }
    
    // Obtener información de la sección y materia
    $info = obtenerInfoSeccionMateria($seccion_id, $materia_id);
    if (!$info) {
        return false;
    }
    
    // Obtener estudiantes de la sección
    $estudiantes = obtenerEstudiantesSeccion($seccion_id);
    
    if (empty($estudiantes)) {
        return false;
    }
    
    // Crear PDF
    $pdf = new PDF_PlanillaNotas();
    $pdf->generarPlanilla($info, $estudiantes);
    
    return true;
}

/**
 * Verificar acceso del docente a la sección y materia
 */
function verificarAccesoDocente($docente_id, $seccion_id, $materia_id) {
    global $db;
    
    $query = "SELECT 1 FROM docente_seccion 
              WHERE id_usuario = ? AND id_seccion = ? AND id_materia = ?
              AND (estatus = 'activo' OR estatus = 1)";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $seccion_id, $materia_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0;
}

/**
 * Obtener información de sección y materia
 */
function obtenerInfoSeccionMateria($seccion_id, $materia_id) {
    global $db;
    
    $query = "SELECT s.codigo_seccion, c.nombre_carrera, 
                     t.nombre_trayecto, t.numero_trayecto,
                     pa.nombre_periodo, m.nombre_materia, m.cod_materia
              FROM secciones s
              INNER JOIN carreras c ON s.id_carrera = c.id_carrera
              INNER JOIN trayectos t ON s.id_trayecto = t.id_trayecto
              INNER JOIN periodos_academicos pa ON s.id_periodo = pa.id_periodo
              INNER JOIN materias m ON m.id_materia = ?
              WHERE s.id_seccion = ?";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $materia_id, $seccion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

/**
 * Obtener estudiantes de una sección
 */
function obtenerEstudiantesSeccion($seccion_id) {
    global $db;
    
    $query = "SELECT u.idusuario as cedula, u.nombre
              FROM users u
              INNER JOIN estudiante_seccion es ON u.id = es.id_usuario
              WHERE es.id_seccion = ? AND es.estatus = 'activo'
              ORDER BY u.nombre";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $seccion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $estudiantes = [];
    while ($row = $result->fetch_assoc()) {
        $estudiantes[] = $row;
    }
    
    return $estudiantes;
}


/**
 * Verifica si un usuario tiene marcado el rol de vocero
 */
function esVoceroUsuario($user_id) {
    global $db;
    $query = "SELECT vocero FROM users WHERE id = ? LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        return intval($res->fetch_assoc()['vocero']) === 1;
    }
    return false;
}

/**
 * Obtener lista de estudiantes de una sección junto con sus notas definitivas
 * devuelve un array donde cada elemento tiene keys: id, cedula, nombre, notas (array)
 */
function obtenerEstudiantesConNotasSeccion($seccion_id) {
    global $db;
    
    $query = "SELECT u.id, u.idusuario AS cedula, u.nombre
              FROM users u
              INNER JOIN estudiante_seccion es ON u.id = es.id_usuario
              WHERE es.id_seccion = ? AND es.estatus = 'activo'
              ORDER BY u.nombre";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $seccion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $estudiantes = [];
    while ($row = $result->fetch_assoc()) {
        // agregar notas definitivas para cada estudiante
        $row['notas'] = obtenerNotasEstudianteConsulta($row['id']);
        $estudiantes[] = $row;
    }
    
    return $estudiantes;
}





// REPORTE DE NOTAS DEFINITIVAS ***********************************************************************

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../fpdf/fpdf.php';


// 1. OBTENER EL NOMBRE DEL USUARIO ACTUAL (Basado en tu lógica de BD)
$nombre_usuario_reporte = "ADMINISTRADOR"; 

// Intentar resolver el nombre real sólo si las variables necesarias existen
if (isset($pool) && isset($usua)) {
    try {
        $db_user = $pool->getConnection();
        $query_user = "SELECT nombre FROM users WHERE username = ? LIMIT 1";
        $stmt_u = $db_user->prepare($query_user);
        $stmt_u->bind_param("s", $usua);
        $stmt_u->execute();
        $res_u = $stmt_u->get_result();
        if ($res_u && $res_u->num_rows > 0) {
            $user_data = $res_u->fetch_assoc();
            $nombre_usuario_reporte = $user_data['nombre'];
        }
        $stmt_u->close();
        $pool->releaseConnection($db_user);
    } catch (Exception $e) {
        // Mantener valor por defecto si falla
    }
}

class PDF_ActaCarga extends FPDF {
    private $datos;
    private $usuario;

    function generarReporte($datos, $usuario) {
        $this->datos = $datos;
        $this->usuario = $usuario;
        $this->SetMargins(10, 10, 10);
        $this->AliasNbPages();
        $this->AddPage();
        $this->Cuerpo();
        $this->Output('I', "Reporte_Carga_" . $this->datos['info_general']['codigo_seccion'] . ".pdf");
        exit;
    }

    function Header() {
        // Membrete Institucional
        if (function_exists('agregarMembreteFPDF')) {
            agregarMembreteFPDF($this);
            $this->SetY(40); 
        }

        $info = $this->datos['info_general'];
        // Usamos el nombre del periodo tal cual viene de la BD (ej. 2025-2)
        $periodo_texto = $info['nombre_periodo'];

        // Título: LISTADO DE CARGA DE NOTAS con el periodo dinámico
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 8, $this->utf8('LISTADO DE CARGA DE NOTAS - PERIODO ' . $periodo_texto), 1, 1, 'C');
        $this->Ln(4);

        $this->SetFont('Arial', '', 8);
        
        // Fila 1: Asignatura y Sección
        $this->Cell(20, 5, 'ASIGNATURA:', 0, 0);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(120, 5, $this->utf8($info['nombre_materia']), 'B', 0);
        $this->SetFont('Arial', '', 8);
        $this->Cell(15, 5, $this->utf8('SECCIÓN:'), 0, 0);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(0, 5, $info['codigo_seccion'], 'B', 1);

        // Fila 2: Docente, Cédula y Carrera (Dpt)
        $this->SetFont('Arial', '', 8);
        $this->Cell(15, 5, 'DOCENTE:', 0, 0);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(80, 5, $this->utf8($info['nombre_docente']), 'B', 0);
        $this->SetFont('Arial', '', 8);
        $this->Cell(15, 5, $this->utf8('CÉDULA:'), 0, 0);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, $info['cedula_docente'], 'B', 0);
        $this->SetFont('Arial', '', 8);
        $this->Cell(12, 5, 'DEPT.:', 0, 0);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(0, 5, $this->utf8($info['nombre_carrera']), 'B', 1);
        $this->Ln(5);
    }

    function Cuerpo() {
        // Encabezado de la tabla de alumnos
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(245, 245, 245);
        $this->Cell(10, 6, 'NUM.', 1, 0, 'C', true);
        $this->Cell(25, 6, $this->utf8('CÉDULA'), 1, 0, 'C', true);
        $this->Cell(75, 6, 'NOMBRE DEL ALUMNO', 1, 0, 'C', true);
        $this->Cell(15, 6, 'ACUM.', 1, 0, 'C', true);
        $this->Cell(15, 6, 'NOTA', 1, 0, 'C', true);
        $this->Cell(25, 6, 'LETRA', 1, 0, 'C', true);
        $this->Cell(25, 6, 'OBSERVACION', 1, 1, 'C', true);

        $this->SetFont('Arial', '', 7);
        $i = 1;
        foreach ($this->datos['notas'] as $n) {
            $this->Cell(10, 5, $i++, 1, 0, 'C');
            $this->Cell(25, 5, $n['cedula_estudiante'], 1, 0, 'C');
            $this->Cell(75, 5, $this->utf8($n['nombre_estudiante']), 1, 0, 'L');
            $this->Cell(15, 5, '---', 1, 0, 'C'); // Espacio para acumulado si aplica
            $this->Cell(15, 5, str_pad($n['nota_final'], 2, '0', STR_PAD_LEFT), 1, 0, 'C');
            $this->Cell(25, 5, $this->utf8($this->numLetras($n['nota_final'])), 1, 0, 'C');
            $this->Cell(25, 5, ($n['nota_final'] >= 10 ? 'Aprobado' : 'Reprobado'), 1, 1, 'C');

            if($this->GetY() > 250) $this->AddPage();
        }

        $this->Ln(2);
        $this->Cell(0, 5, $this->utf8('--- FIN DEL REPORTE DE CARGA ---'), 0, 1, 'C');
        $this->DibujarPie();
    }

    function DibujarPie() {
        if ($this->GetY() > 220) $this->AddPage();
        
        $y_base = $this->GetY() + 5;
        $info = $this->datos['info_general'];
        $st = $this->datos['estadisticas'];

        // Bloque de Firmas
        $this->SetXY(10, $y_base);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(55, 5, $this->utf8('Revisión Docente'), 1, 1, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(55, 6, $this->utf8('Firma Profesor: ________________'), 'LR', 1);
        $this->Cell(55, 6, $this->utf8('C.I.: __________________________'), 'LR', 1);
        $this->Cell(55, 6, $this->utf8('Sello Departamento: ____________'), 'LRB', 1);

        // Bloque de Observaciones
        $this->SetXY(65, $y_base);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(90, 5, 'Detalles del Reporte', 1, 1, 'L');
        $this->SetXY(65, $y_base + 5);
        $this->SetFont('Arial', '', 7);
        $this->MultiCell(90, 6, $this->utf8("CARRERA: " . $info['nombre_carrera'] . "\nPERIODO ACADÉMICO: " . $info['nombre_periodo'] . "\nSECCION: " . $info['codigo_seccion']), 1, 'L');

        // Bloque de Estadísticas
        $this->SetXY(155, $y_base);
        $this->SetFont('Arial', '', 7);
        $this->Cell(25, 5, 'Fecha Impresion:', 1, 0); $this->Cell(20, 5, date('d-m-Y'), 1, 1);
        $this->SetX(155);
        $this->Cell(25, 5, 'Inasistentes:', 1, 0); $this->Cell(20, 5, '0', 1, 1);
        $this->SetX(155);
        $this->Cell(25, 5, 'Aprobados:', 1, 0); $this->Cell(20, 5, $st['aprobados'], 1, 1);
        $this->SetX(155);
        $this->Cell(25, 5, 'Reprobados:', 1, 0); $this->Cell(20, 5, $st['reprobados'], 1, 1);

        // Nombre del Usuario Administrativo que genera el reporte
        $this->Ln(6);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(0, 5, 'GENERADO POR: ' . strtoupper($this->utf8($this->usuario)), 0, 1, 'R');
    }

    function numLetras($n) {
        $l = [0=>'CERO', 1=>'UNO', 2=>'DOS', 3=>'TRES', 4=>'CUATRO', 5=>'CINCO', 6=>'SEIS', 7=>'SIETE', 8=>'OCHO', 9=>'NUEVE', 10=>'DIEZ', 11=>'ONCE', 12=>'DOCE', 13=>'TRECE', 14=>'CATORCE', 15=>'QUINCE', 16=>'DIECISEIS', 17=>'DIECISIETE', 18=>'DIECIOCHO', 19=>'DIECINUEVE', 20=>'VEINTE'];
        return ($n < 10) ? "CERO " . $l[$n] : $l[$n];
    }

    function utf8($t) { return mb_convert_encoding($t, "ISO-8859-1", "UTF-8"); }
}

// --- PROCESAMIENTO ---
// Ejecutar únicamente si se proporcionan todos los parámetros esperados
if (isset($_GET['materia_id']) && isset($_GET['docente_id']) && isset($_GET['periodo_id'])) {
    $docente_id_get = $_GET['docente_id'];
    $materia_id_get = $_GET['materia_id'];
    $periodo_id_get = $_GET['periodo_id'];

    $info = obtenerInfoNotasDefinitivas($docente_id_get, $materia_id_get, $periodo_id_get);
    $lista_notas = obtenerNotasDefinitivasGrupo($docente_id_get, $materia_id_get, $periodo_id_get);
    $stats = calcularEstadisticasNotas($lista_notas);

    $datos_pdf = [
        'info_general' => $info,
        'notas' => $lista_notas,
        'estadisticas' => $stats
    ];

    $pdf = new PDF_ActaCarga();
    $pdf->generarReporte($datos_pdf, $nombre_usuario_reporte);
}

/**
 * LOGICA DE PROCESAMIENTO
 */

function generarPDFNotasDefinitivas($docente_id, $materia_id, $periodo_id) {
    global $db;
    
    $info_general = obtenerInfoNotasDefinitivas($docente_id, $materia_id, $periodo_id);
    if (!$info_general) return false;
    
    $notas = obtenerNotasDefinitivasGrupo($docente_id, $materia_id, $periodo_id);
    if (empty($notas)) return false;
    
    $estadisticas = calcularEstadisticasNotas($notas);
    
    $datos = [
        'info_general' => $info_general,
        'notas' => $notas,
        'estadisticas' => $estadisticas
    ];
    
    // Usar la clase existente `PDF_ActaCarga` para generar el reporte
    $pdf = new PDF_ActaCarga();
    // Obtener nombre de usuario para el reporte si está disponible
    $usuario = isset($nombre_usuario_reporte) ? $nombre_usuario_reporte : '';
    $pdf->generarReporte($datos, $usuario);
    return true;
}

function obtenerInfoNotasDefinitivas($docente_id, $materia_id, $periodo_id) {
    global $db;
    $query = "SELECT ud.nombre as nombre_docente, ud.idusuario as cedula_docente,
                     m.nombre_materia, pa.nombre_periodo, 
                     s.codigo_seccion, c.nombre_carrera, t.numero_trayecto
              FROM docente_seccion ds
              INNER JOIN users ud ON ds.id_usuario = ud.id
              INNER JOIN materias m ON ds.id_materia = m.id_materia
              INNER JOIN periodos_academicos pa ON pa.id_periodo = $periodo_id
              INNER JOIN secciones s ON ds.id_seccion = s.id_seccion
              INNER JOIN carreras c ON s.id_carrera = c.id_carrera
              INNER JOIN trayectos t ON s.id_trayecto = t.id_trayecto
              WHERE ds.id_usuario = $docente_id AND ds.id_materia = $materia_id LIMIT 1";
    $result = $db->query($query);
    return ($result) ? $result->fetch_assoc() : false;
}

function obtenerNotasDefinitivasGrupo($docente_id, $materia_id, $periodo_id) {
    global $db;
    $query = "SELECT nd.*, ue.idusuario as cedula_estudiante, ue.nombre as nombre_estudiante
              FROM notas_definitivas nd
              INNER JOIN users ue ON nd.id_usuario = ue.id
              WHERE nd.id_docente = $docente_id AND nd.id_materia = $materia_id AND nd.id_periodo = $periodo_id
              ORDER BY ue.nombre ASC";
    $result = $db->query($query);
    $notas = [];
    if($result){
        while ($row = $result->fetch_assoc()) {
            $row['nota_final'] = calcularNotaFinal($row);
            $notas[] = $row;
        }
    }
    return $notas;
}

function calcularNotaFinal($datos_nota) {
    $suma = 0; $cont = 0;
    for ($i = 0; $i <= 4; $i++) {
        $campo = "trayecto_$i";
        if (isset($datos_nota[$campo]) && $datos_nota[$campo] !== '' && $datos_nota[$campo] !== null) {
            $suma += (int)$datos_nota[$campo];
            $cont++;
        }
    }
    return ($cont > 0) ? round($suma / $cont) : 0;
}

function calcularEstadisticasNotas($notas) {
    $total = count($notas);
    $aprobados = 0;
    foreach ($notas as $n) { 
        if ($n['nota_final'] >= 10) $aprobados++; 
    }
    return [
        'total' => $total,
        'aprobados' => $aprobados,
        'reprobados' => $total - $aprobados
    ];
}



//ASIGNAR DIRECTORES DE CARRERA************************************************************************


/**
 * Función para asignar carrera a director
 */
function asignarCarreraDirector($id_usuario, $id_carrera) {
    global $db;
    
    try {
        // Obtener información para auditoría
        $usuario_info = obtenerUsuarioPorId($id_usuario);
        $carrera_info = obtenerCarreraPorId($id_carrera);
        
        if (!$usuario_info) {
            return [
                'success' => false,
                'message' => 'Usuario no encontrado'
            ];
        }
        
        if (!$carrera_info) {
            return [
                'success' => false,
                'message' => 'Carrera no encontrada'
            ];
        }

        // Verificar si el usuario es tipo "usuario = 1" (posiblemente administrador/director)
        if ($usuario_info['usuario'] != 1) {
            return [
                'success' => false,
                'message' => 'El usuario no tiene permisos para ser director de carrera'
            ];
        }

        // Verificar si ya tiene una carrera asignada
        if (!empty($usuario_info['carrera_di']) && $usuario_info['carrera_di'] != 0) {
            return [
                'success' => false,
                'message' => 'El usuario ya tiene una carrera asignada como director'
            ];
        }

        $stmt = $db->prepare("UPDATE users SET carrera_di = ? WHERE id = ? AND usuario = 1");
        
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("ii", $id_carrera, $id_usuario);
        
        if ($stmt->execute()) {
            $affected_rows = $stmt->affected_rows;
            $stmt->close();
            
            if ($affected_rows > 0) {
                // REGISTRAR EN AUDITORÍA - ASIGNACIÓN DE CARRERA A DIRECTOR
                if (function_exists('registrarAuditoria')) {
                    try {
                        registrarAuditoria(
                            "UPDATE", 
                            "users", 
                            $id_usuario, 
                            [
                                'carrera_di' => $usuario_info['carrera_di'] ?? null,
                                'estado_anterior' => 'Sin carrera asignada'
                            ], 
                            [
                                'carrera_di' => $id_carrera,
                                'carrera_nombre' => $carrera_info['nombre_carrera'],
                                'carrera_codigo' => $carrera_info['cod_carrera'],
                                'usuario_nombre' => $usuario_info['nombre'],
                                'usuario_username' => $usuario_info['username'],
                                'estado_nuevo' => 'Director asignado'
                            ], 
                            "Directores de Carrera", 
                            "Asignación de director de carrera"
                        );
                    } catch (Exception $e) {
                        error_log("Error en auditoría asignarCarreraDirector: " . $e->getMessage());
                    }
                }
                
                return [
                    'success' => true,
                    'message' => 'Director asignado a la carrera exitosamente',
                    'affected_rows' => $affected_rows
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No se realizaron cambios en la asignación'
                ];
            }
        } else {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
    } catch (Exception $e) {
        error_log("Error en asignarCarreraDirector: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL ASIGNAR CARRERA
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "users", 
                    $id_usuario, 
                    null, 
                    [
                        'id_usuario' => $id_usuario,
                        'id_carrera' => $id_carrera,
                        'error' => $e->getMessage()
                    ], 
                    "Directores de Carrera", 
                    "Error al asignar director de carrera"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error asignarCarreraDirector: " . $auditError->getMessage());
            }
        }
        
        return [
            'success' => false,
            'message' => 'Error al asignar director: ' . $e->getMessage()
        ];
    }
}

/**
 * Función para eliminar asignación de carrera
 */
function eliminarAsignacionCarrera($id_usuario) {
    global $db;
    
    try {
        // Obtener información para auditoría
        $usuario_info = obtenerUsuarioPorId($id_usuario);
        
        if (!$usuario_info) {
            return [
                'success' => false,
                'message' => 'Usuario no encontrado'
            ];
        }

        // Verificar si tiene una carrera asignada
        if (empty($usuario_info['carrera_di']) || $usuario_info['carrera_di'] == 0) {
            return [
                'success' => false,
                'message' => 'El usuario no tiene una carrera asignada como director'
            ];
        }

        // Obtener información de la carrera asignada
        $carrera_asignada = null;
        if (!empty($usuario_info['carrera_di'])) {
            $carrera_asignada = obtenerCarreraPorId($usuario_info['carrera_di']);
        }

        $stmt = $db->prepare("UPDATE users SET carrera_di = NULL WHERE id = ? AND usuario = 1");
        
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("i", $id_usuario);
        
        if ($stmt->execute()) {
            $affected_rows = $stmt->affected_rows;
            $stmt->close();
            
            if ($affected_rows > 0) {
                // REGISTRAR EN AUDITORÍA - ELIMINACIÓN DE ASIGNACIÓN DE CARRERA
                if (function_exists('registrarAuditoria')) {
                    try {
                        registrarAuditoria(
                            "UPDATE", 
                            "users", 
                            $id_usuario, 
                            [
                                'carrera_di' => $usuario_info['carrera_di'],
                                'carrera_nombre' => $carrera_asignada['nombre_carrera'] ?? 'Desconocida',
                                'carrera_codigo' => $carrera_asignada['cod_carrera'] ?? '',
                                'estado_anterior' => 'Director asignado'
                            ], 
                            [
                                'carrera_di' => null,
                                'usuario_nombre' => $usuario_info['nombre'],
                                'usuario_username' => $usuario_info['username'],
                                'estado_nuevo' => 'Sin carrera asignada'
                            ], 
                            "Directores de Carrera", 
                            "Eliminación de asignación de director de carrera"
                        );
                    } catch (Exception $e) {
                        error_log("Error en auditoría eliminarAsignacionCarrera: " . $e->getMessage());
                    }
                }
                
                return [
                    'success' => true,
                    'message' => 'Asignación de director eliminada exitosamente',
                    'affected_rows' => $affected_rows
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No se realizaron cambios en la asignación'
                ];
            }
        } else {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }
        
    } catch (Exception $e) {
        error_log("Error en eliminarAsignacionCarrera: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL ELIMINAR ASIGNACIÓN
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "users", 
                    $id_usuario, 
                    null, 
                    [
                        'id_usuario' => $id_usuario,
                        'error' => $e->getMessage()
                    ], 
                    "Directores de Carrera", 
                    "Error al eliminar asignación de director de carrera"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error eliminarAsignacionCarrera: " . $auditError->getMessage());
            }
        }
        
        return [
            'success' => false,
            'message' => 'Error al eliminar asignación: ' . $e->getMessage()
        ];
    }
}

/**
 * Función para obtener directores de carrera (solo usuario = 1)
 */
function obtenerDirectoresDeCarrera() {
    global $db;
    
    try {
        $directores = [];
        $query = "SELECT u.id, u.nombre, u.username, u.email, u.carrera_di, c.nombre_carrera, c.cod_carrera
                  FROM users u 
                  LEFT JOIN carreras c ON u.carrera_di = c.id_carrera 
                  WHERE u.usuario = 1 
                  ORDER BY u.nombre ASC";
        
        if ($stmt = $db->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $directores[] = $row;
            }
            
            $stmt->close();
            return $directores;
        } else {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
    } catch (Exception $e) {
        error_log("Error en obtenerDirectoresDeCarrera: " . $e->getMessage());
        return [];
    }
}

/**
 * Función para obtener usuarios que pueden ser directores (solo usuario = 1 sin carrera asignada)
 */
function obtenerUsuariosParaDirectores() {
    global $db;
    
    try {
        $usuarios = [];
        $query = "SELECT id, nombre, username, email 
                  FROM users 
                  WHERE usuario = 1 
                  AND (carrera_di IS NULL OR carrera_di = '' OR carrera_di = 0)
                  ORDER BY nombre ASC";
        
        if ($stmt = $db->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
            
            $stmt->close();
            return $usuarios;
        } else {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
    } catch (Exception $e) {
        error_log("Error en obtenerUsuariosParaDirectores: " . $e->getMessage());
        return [];
    }
}

/**
 * Función auxiliar para obtener usuario por ID
 */
function obtenerUsuarioPorId($id_usuario) {
    global $db;
    
    try {
        $query = "SELECT id, nombre, username, email, usuario, carrera_di 
                  FROM users 
                  WHERE id = ?";
        
        $stmt = $db->prepare($query);
        if (!$stmt) {
            return null;
        }
        
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();
        
        return $usuario;
        
    } catch (Exception $e) {
        error_log("Error en obtenerUsuarioPorId: " . $e->getMessage());
        return null;
    }
}

/**
 * Función para obtener carreras sin director asignado
 */
function obtenerCarrerasSinDirector() {
    global $db;
    
    try {
        $carreras = [];
        $query = "SELECT c.id_carrera, c.nombre_carrera, c.cod_carrera
                  FROM carreras c
                  WHERE c.activa = 1 
                  AND c.id_carrera NOT IN (
                      SELECT DISTINCT carrera_di 
                      FROM users 
                      WHERE usuario = 1 
                      AND carrera_di IS NOT NULL 
                      AND carrera_di != 0
                  )
                  ORDER BY c.nombre_carrera ASC";
        
        if ($stmt = $db->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $carreras[] = $row;
            }
            
            $stmt->close();
            return $carreras;
        } else {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
    } catch (Exception $e) {
        error_log("Error en obtenerCarrerasSinDirector: " . $e->getMessage());
        return [];
    }
}

/**
 * Función para verificar si una carrera ya tiene director
 */
function carreraTieneDirector($id_carrera) {
    global $db;
    
    try {
        $query = "SELECT COUNT(*) as total 
                  FROM users 
                  WHERE usuario = 1 
                  AND carrera_di = ?";
        
        $stmt = $db->prepare($query);
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param("i", $id_carrera);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_assoc()['total'];
        $stmt->close();
        
        return $count > 0;
        
    } catch (Exception $e) {
        error_log("Error en carreraTieneDirector: " . $e->getMessage());
        return false;
    }
}









// CONSULTA DE NOTAS********************************************************





// Función para buscar estudiante por cédula (CON AUDITORÍA DE BÚSQUEDA)
function buscarEstudiantePorCedulaConsulta($cedula) {
    global $db;
    
    try {
        $query = "SELECT u.id, u.nombre, u.idusuario, u.carrera 
                  FROM users u 
                  WHERE u.idusuario = ? AND u.estudiante = 1";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $cedula);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $estudiante = $result->num_rows > 0 ? $result->fetch_assoc() : null;
        
        // REGISTRAR EN AUDITORÍA - BÚSQUEDA DE ESTUDIANTE
        if (function_exists('registrarAuditoria')) {
            try {
                $resultado_busqueda = $estudiante ? 'ENCONTRADO' : 'NO_ENCONTRADO';
                $detalles_estudiante = $estudiante ? [
                    'id_estudiante' => $estudiante['id'],
                    'nombre_estudiante' => $estudiante['nombre'],
                    'cedula' => $estudiante['idusuario'],
                    'id_carrera' => $estudiante['carrera']
                ] : [
                    'cedula_buscada' => $cedula,
                    'resultado' => 'Estudiante no encontrado'
                ];
                
                registrarAuditoria(
                    "CONSULTA", 
                    "users", 
                    $estudiante ? $estudiante['id'] : null, 
                    null, 
                    array_merge([
                        'cedula_buscada' => $cedula,
                        'resultado_busqueda' => $resultado_busqueda,
                        'tipo_consulta' => 'busqueda_estudiante'
                    ], $detalles_estudiante), 
                    "Consulta de Estudiantes", 
                    "Búsqueda de estudiante por cédula - " . $resultado_busqueda
                );
            } catch (Exception $e) {
                error_log("Error en auditoría buscarEstudiantePorCedulaConsulta: " . $e->getMessage());
            }
        }
        
        return $estudiante;
        
    } catch (Exception $e) {
        error_log("Error en buscarEstudiantePorCedulaConsulta: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR EN BÚSQUEDA
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "users", 
                    null, 
                    null, 
                    [
                        'cedula_buscada' => $cedula,
                        'error' => $e->getMessage(),
                        'tipo_consulta' => 'busqueda_estudiante'
                    ], 
                    "Consulta de Estudiantes", 
                    "Error en búsqueda de estudiante por cédula"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error buscarEstudiantePorCedulaConsulta: " . $auditError->getMessage());
            }
        }
        
        return null;
    }
}


// Función para obtener la carrera del estudiante - SOLO LECTURA, SIN AUDITORÍA
function obtenerCarreraEstudiante($estudiante_id) {
    global $db;
    
    $query = "SELECT c.id_carrera, c.nombre_carrera, c.cod_carrera, c.tipo_formacion 
              FROM users u
              INNER JOIN carreras c ON u.carrera = c.id_carrera
              WHERE u.id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $estudiante_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}


// Función para obtener todas las materias de la carrera - SOLO LECTURA, SIN AUDITORÍA
function obtenerMateriasCarrera($carrera_id) {
    global $db;
    
    $query = "SELECT m.id_materia, m.nombre_materia, m.cod_materia, m.trayecto, m.creditos
              FROM carrera_materia cm
              INNER JOIN materias m ON cm.id_materia = m.id_materia
              WHERE cm.id_carrera = ?
              ORDER BY m.trayecto, m.nombre_materia";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $carrera_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Función para obtener información del trayecto desde la tabla trayectos - SOLO LECTURA, SIN AUDITORÍA
function obtenerInfoTrayecto($numero_trayecto) {
    global $db;
    
    $query = "SELECT id_trayecto, numero_trayecto, nombre_trayecto 
              FROM trayectos 
              WHERE numero_trayecto = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $numero_trayecto);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    // Si no encuentra el trayecto, crear uno basado en el número
    $nombres_trayectos = [
        0 => 'Trayecto Inicial',
        1 => 'Trayecto 1',
        2 => 'Trayecto 2', 
        3 => 'Trayecto 3',
        4 => 'Trayecto 4'
    ];
    
    return [
        'id_trayecto' => $numero_trayecto + 1,
        'numero_trayecto' => $numero_trayecto,
        'nombre_trayecto' => isset($nombres_trayectos[$numero_trayecto]) ? $nombres_trayectos[$numero_trayecto] : 'Trayecto ' . $numero_trayecto
    ];
}

/**
 * Obtiene el trayecto actual estimado de un estudiante consultando
 * las materias en las que tiene notas definitivas o pendientes.
 * Retorna un entero (0 = Trayecto Inicial si no hay registros).
 */
function obtenerTrayectoActualEstudiante($estudiante_id) {
    global $db;

    // Buscamos el máximo trayecto entre las materias asociadas a las notas
    $query = "SELECT COALESCE(MAX(m.trayecto), 0) as max_trayecto
              FROM (
                SELECT id_materia FROM notas_definitivas WHERE id_usuario = ?
                UNION
                SELECT id_materia FROM notas_pendientes WHERE id_usuario = ?
              ) as t
              INNER JOIN materias m ON t.id_materia = m.id_materia";

    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param("ii", $estudiante_id, $estudiante_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return (int)$row['max_trayecto'];
        }
        $stmt->close();
    } else {
        error_log("Error al preparar obtenerTrayectoActualEstudiante: " . $db->error);
    }

    return 0;
}

// Función para obtener las notas definitivas del estudiante - SOLO LECTURA, SIN AUDITORÍA
function obtenerNotasEstudianteConsulta($estudiante_id) {
    global $db;
    
    $query = "SELECT nd.*, 
                     m.id_materia, m.nombre_materia, m.cod_materia, m.trayecto,
                     pa.nombre_periodo,
                     ud.nombre as nombre_docente,
                     ua.nombre as nombre_admin
              FROM notas_definitivas nd
              INNER JOIN materias m ON nd.id_materia = m.id_materia
              INNER JOIN periodos_academicos pa ON nd.id_periodo = pa.id_periodo
              LEFT JOIN users ud ON nd.id_docente = ud.id
              LEFT JOIN users ua ON nd.id_admin_aprobador = ua.id
              WHERE nd.id_usuario = ?
              ORDER BY pa.nombre_periodo, m.nombre_materia";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $estudiante_id);
    $stmt->execute();
    
    // Convertir to array asociativo con id_materia como clave
    $result = $stmt->get_result();
    $notas = [];
    while ($row = $result->fetch_assoc()) {
        $notas[$row['id_materia']] = $row;
    }
    
    return $notas;
}

// Función para determinar si el estudiante es apto para grado (CON AUDITORÍA PARA CASOS ESPECIALES)
function esAptoParaGradoConsulta($estudiante_id, $carrera_id) {
    global $db;
    
    try {
        // Obtener información del estudiante para auditoría
        $estudiante_info = obtenerEstudiantePorId($estudiante_id);
        $carrera_info = obtenerCarreraPorId($carrera_id);
        
        if (!$estudiante_info) {
            // REGISTRAR EN AUDITORÍA - ESTUDIANTE NO ENCONTRADO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "ERROR", 
                        "users", 
                        $estudiante_id, 
                        null, 
                        [
                            'id_estudiante' => $estudiante_id,
                            'id_carrera' => $carrera_id,
                            'error' => 'Estudiante no encontrado'
                        ], 
                        "Consulta de Grado", 
                        "Error en consulta de aptitud para grado - Estudiante no existe"
                    );
                } catch (Exception $auditError) {
                    error_log("Error en auditoría esAptoParaGradoConsulta: " . $auditError->getMessage());
                }
            }
            
            return [
                'apto_tsu' => false,
                'apto_grado_completo' => false,
                'materias_aprobadas_tsu' => 0,
                'total_materias_tsu' => 0,
                'porcentaje_tsu' => 0,
                'materias_aprobadas_completo' => 0,
                'total_materias_carrera' => 0,
                'porcentaje_completo' => 0,
                'error' => 'Estudiante no encontrado'
            ];
        }

        // Obtener todas las materias de la carrera
        $materias_carrera = obtenerMateriasCarrera($carrera_id);
        $total_materias_carrera = $materias_carrera->num_rows;
        
        // Obtener notas del estudiante
        $notas_estudiante = obtenerNotasEstudianteConsulta($estudiante_id);
        
        // Contadores para TSU (trayectos 0, 1, 2)
        $materias_aprobadas_tsu = 0;
        $total_materias_tsu = 0;
        
        // Contadores para carrera completa
        $materias_aprobadas_completo = 0;
        
        // Recorrer todas las materias de la carrera
        while ($materia = $materias_carrera->fetch_assoc()) {
            $trayecto = (int)$materia['trayecto'];
            $materia_id = $materia['id_materia'];
            
            // Verificar si es materia de TSU (trayectos 0, 1, 2)
            if ($trayecto <= 2) {
                $total_materias_tsu++;
            }
            
            // Verificar si el estudiante aprobó esta materia
            if (isset($notas_estudiante[$materia_id])) {
                $nota = $notas_estudiante[$materia_id];
                $campo_trayecto = 'trayecto_' . $trayecto;
                
                if (isset($nota[$campo_trayecto]) && $nota[$campo_trayecto] !== null) {
                    $nota_valor = (float)$nota[$campo_trayecto];
                    if ($nota_valor >= 12) {
                        $materias_aprobadas_completo++;
                        
                        // Si es materia de TSU, contar para TSU también
                        if ($trayecto <= 2) {
                            $materias_aprobadas_tsu++;
                        }
                    }
                }
            }
        }
        
        // Calcular porcentajes
        $porcentaje_tsu = $total_materias_tsu > 0 ? round(($materias_aprobadas_tsu / $total_materias_tsu) * 100, 1) : 0;
        $porcentaje_completo = $total_materias_carrera > 0 ? round(($materias_aprobadas_completo / $total_materias_carrera) * 100, 1) : 0;
        
        // Determinar si es apto
        $apto_tsu = ($porcentaje_tsu >= 90); // 90% o más para TSU
        $apto_grado_completo = ($porcentaje_completo >= 100); // 100% para grado completo
        
        $resultado = [
            'apto_tsu' => $apto_tsu,
            'apto_grado_completo' => $apto_grado_completo,
            'materias_aprobadas_tsu' => $materias_aprobadas_tsu,
            'total_materias_tsu' => $total_materias_tsu,
            'porcentaje_tsu' => $porcentaje_tsu,
            'materias_aprobadas_completo' => $materias_aprobadas_completo,
            'total_materias_carrera' => $total_materias_carrera,
            'porcentaje_completo' => $porcentaje_completo
        ];
        
        // REGISTRAR EN AUDITORÍA - CONSULTA DE APTITUD PARA GRADO (SOLO SI ES APTO)
        if (function_exists('registrarAuditoria') && ($apto_tsu || $apto_grado_completo)) {
            try {
                $tipo_aptitud = $apto_grado_completo ? 'GRADO_COMPLETO' : ($apto_tsu ? 'TSU' : 'NO_APTO');
                
                registrarAuditoria(
                    "CONSULTA", 
                    "notas_definitivas", 
                    $estudiante_id, 
                    null, 
                    [
                        'id_estudiante' => $estudiante_id,
                        'cedula_estudiante' => $estudiante_info['idusuario'] ?? '',
                        'nombre_estudiante' => $estudiante_info['nombre'] ?? '',
                        'id_carrera' => $carrera_id,
                        'carrera_nombre' => $carrera_info['nombre_carrera'] ?? '',
                        'apto_tsu' => $apto_tsu,
                        'apto_grado_completo' => $apto_grado_completo,
                        'porcentaje_tsu' => $porcentaje_tsu,
                        'porcentaje_completo' => $porcentaje_completo,
                        'tipo_aptitud' => $tipo_aptitud
                    ], 
                    "Consulta de Grado", 
                    "Consulta de aptitud para grado - " . $tipo_aptitud
                );
            } catch (Exception $e) {
                error_log("Error en auditoría esAptoParaGradoConsulta: " . $e->getMessage());
            }
        }
        
        return $resultado;
        
    } catch (Exception $e) {
        error_log("Error en esAptoParaGradoConsulta: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR EN CONSULTA DE GRADO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "notas_definitivas", 
                    $estudiante_id, 
                    null, 
                    [
                        'id_estudiante' => $estudiante_id,
                        'id_carrera' => $carrera_id,
                        'error' => $e->getMessage()
                    ], 
                    "Consulta de Grado", 
                    "Error en consulta de aptitud para grado"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error esAptoParaGradoConsulta: " . $auditError->getMessage());
            }
        }
        
        return [
            'apto_tsu' => false,
            'apto_grado_completo' => false,
            'materias_aprobadas_tsu' => 0,
            'total_materias_tsu' => 0,
            'porcentaje_tsu' => 0,
            'materias_aprobadas_completo' => 0,
            'total_materias_carrera' => 0,
            'porcentaje_completo' => 0,
            'error' => 'Error en la consulta: ' . $e->getMessage()
        ];
    }
}

// Función para obtener el badge de estado - SOLO LÓGICA DE PRESENTACIÓN, SIN AUDITORÍA
function obtenerBadgeEstadoConsulta($info_apto) {
    if ($info_apto['apto_grado_completo']) {
        return '<span class="badge badge-success">APTO - GRADO COMPLETO</span>';
    } elseif ($info_apto['apto_tsu']) {
        return '<span class="badge badge-warning">APTO - TSU</span>';
    } else {
        return '<span class="badge badge-secondary">NO APTO</span>';
    }
}

// FUNCIÓN AUXILIAR PARA OBTENER CARRERA POR ID (CON AUDITORÍA EN CASO DE ERROR)
if (!function_exists('obtenerCarreraPorId')) {
function obtenerCarreraPorId($carrera_id) {
    global $db;
    
    try {
        $query = "SELECT id_carrera, nombre_carrera, cod_carrera, activa 
                  FROM carreras 
                  WHERE id_carrera = ?";
        
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }
        
        $stmt->bind_param("i", $carrera_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $carrera = $result->fetch_assoc();
        $stmt->close();
        
        return $carrera;
        
    } catch (Exception $e) {
        error_log("Error en obtenerCarreraPorId: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL OBTENER CARRERA
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "carreras", 
                    $carrera_id, 
                    null, 
                    [
                        'id_carrera' => $carrera_id,
                        'error' => $e->getMessage()
                    ], 
                    "Consulta de Grado", 
                    "Error al obtener información de carrera"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error obtenerCarreraPorId: " . $auditError->getMessage());
            }
        }
        
        return null;
    }
}
}

//NOTAS PASADAS***************************************************************




// Obtener lista de profesores para el filtro (docente = 1) - SOLO LECTURA, SIN AUDITORÍA
function obtenerProfesores() {
    global $db;
    $query = "SELECT id, idusuario, nombre 
              FROM users 
              WHERE docente = 1 
              ORDER BY nombre";
    $result = $db->query($query);
    return $result;
}

// Nueva función para buscar profesores por término - CON AUDITORÍA
function buscarProfesores($termino) {
    global $db;
    
    try {
        $query = "SELECT id, idusuario, nombre 
                  FROM users 
                  WHERE docente = 1 
                  AND (nombre LIKE ? OR idusuario LIKE ?)
                  ORDER BY nombre
                  LIMIT 10";
        $stmt = $db->prepare($query);
        $termino_like = "%$termino%";
        $stmt->bind_param("ss", $termino_like, $termino_like);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // REGISTRAR EN AUDITORÍA - BÚSQUEDA DE PROFESORES
        if (function_exists('registrarAuditoria')) {
            try {
                $cantidad_resultados = $result->num_rows;
                $resultado_busqueda = $cantidad_resultados > 0 ? 'ENCONTRADOS' : 'NO_ENCONTRADOS';
                
                registrarAuditoria(
                    "CONSULTA", 
                    "users", 
                    null, 
                    null, 
                    [
                        'termino_busqueda' => $termino,
                        'cantidad_resultados' => $cantidad_resultados,
                        'resultado_busqueda' => $resultado_busqueda,
                        'tipo_consulta' => 'busqueda_profesores'
                    ], 
                    "Gestión de Docentes", 
                    "Búsqueda de profesores - " . $resultado_busqueda
                );
            } catch (Exception $e) {
                error_log("Error en auditoría buscarProfesores: " . $e->getMessage());
            }
        }
        
        return $result;
        
    } catch (Exception $e) {
        error_log("Error en buscarProfesores: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR EN BÚSQUEDA
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "users", 
                    null, 
                    null, 
                    [
                        'termino_busqueda' => $termino,
                        'error' => $e->getMessage(),
                        'tipo_consulta' => 'busqueda_profesores'
                    ], 
                    "Gestión de Docentes", 
                    "Error en búsqueda de profesores"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error buscarProfesores: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

// Obtener información de un profesor específico - CON AUDITORÍA
function obtenerProfesorPorId($id) {
    global $db;
    
    try {
        $query = "SELECT id, idusuario, nombre 
                  FROM users 
                  WHERE id = ? AND docente = 1";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $profesor = $result->fetch_assoc();
        
        // REGISTRAR EN AUDITORÍA - CONSULTA DE PROFESOR ESPECÍFICO
        if (function_exists('registrarAuditoria') && $profesor) {
            try {
                registrarAuditoria(
                    "CONSULTA", 
                    "users", 
                    $id, 
                    null, 
                    [
                        'id_profesor' => $profesor['id'],
                        'cedula_profesor' => $profesor['idusuario'],
                        'nombre_profesor' => $profesor['nombre'],
                        'tipo_consulta' => 'obtener_profesor'
                    ], 
                    "Gestión de Docentes", 
                    "Consulta de información de profesor"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría obtenerProfesorPorId: " . $e->getMessage());
            }
        }
        
        return $profesor;
        
    } catch (Exception $e) {
        error_log("Error en obtenerProfesorPorId: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR EN CONSULTA
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "users", 
                    $id, 
                    null, 
                    [
                        'id_profesor' => $id,
                        'error' => $e->getMessage(),
                        'tipo_consulta' => 'obtener_profesor'
                    ], 
                    "Gestión de Docentes", 
                    "Error al obtener información de profesor"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error obtenerProfesorPorId: " . $auditError->getMessage());
            }
        }
        
        return null;
    }
}

// Obtener grupos de notas definitivas agrupados por docente/materia/periodo con filtros - CON AUDITORÍA
function obtenerGruposNotasDefinitivas($filtro_profesor = '', $filtro_fecha_desde = '', $filtro_fecha_hasta = '') {
    global $db;
    
    try {
        $query = "SELECT nd.id_docente, nd.id_materia, nd.id_periodo,
                         ud.nombre as nombre_docente, ud.idusuario as cedula_docente,
                         m.nombre_materia, 
                         pa.nombre_periodo, s.codigo_seccion, c.nombre_carrera,
                         COUNT(nd.id) as total_notas, MAX(nd.fecha_registro) as ultima_fecha
                  FROM notas_definitivas nd
                  INNER JOIN users ud ON nd.id_docente = ud.id
                  INNER JOIN materias m ON nd.id_materia = m.id_materia
                  INNER JOIN periodos_academicos pa ON nd.id_periodo = pa.id_periodo
                  INNER JOIN docente_seccion ds ON nd.id_docente = ds.id_usuario 
                                               AND nd.id_materia = ds.id_materia
                  INNER JOIN secciones s ON ds.id_seccion = s.id_seccion
                  INNER JOIN carreras c ON s.id_carrera = c.id_carrera
                  WHERE 1=1";
        
        $params = array();
        $types = '';
        
        // Aplicar filtro por profesor
        if (!empty($filtro_profesor)) {
            $query .= " AND nd.id_docente = ?";
            $params[] = $filtro_profesor;
            $types .= "i";
        }
        
        // Aplicar filtro por fecha desde
        if (!empty($filtro_fecha_desde)) {
            $query .= " AND DATE(nd.fecha_registro) >= ?";
            $params[] = $filtro_fecha_desde;
            $types .= "s";
        }
        
        // Aplicar filtro por fecha hasta
        if (!empty($filtro_fecha_hasta)) {
            $query .= " AND DATE(nd.fecha_registro) <= ?";
            $params[] = $filtro_fecha_hasta;
            $types .= "s";
        }
        
        $query .= " GROUP BY nd.id_docente, nd.id_materia, nd.id_periodo, s.codigo_seccion, c.nombre_carrera
                    ORDER BY ultima_fecha DESC";
        
        if (!empty($params)) {
            $stmt = $db->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $db->query($query);
        }
        
        // REGISTRAR EN AUDITORÍA - CONSULTA DE GRUPOS DE NOTAS DEFINITIVAS
        if (function_exists('registrarAuditoria')) {
            try {
                $filtros_aplicados = [];
                if (!empty($filtro_profesor)) $filtros_aplicados[] = 'profesor';
                if (!empty($filtro_fecha_desde)) $filtros_aplicados[] = 'fecha_desde';
                if (!empty($filtro_fecha_hasta)) $filtros_aplicados[] = 'fecha_hasta';
                
                registrarAuditoria(
                    "CONSULTA", 
                    "notas_definitivas", 
                    null, 
                    null, 
                    [
                        'cantidad_grupos' => $result->num_rows,
                        'filtros_aplicados' => !empty($filtros_aplicados) ? implode(', ', $filtros_aplicados) : 'ninguno',
                        'filtro_profesor' => $filtro_profesor ?: 'todos',
                        'filtro_fecha_desde' => $filtro_fecha_desde ?: 'sin_filtro',
                        'filtro_fecha_hasta' => $filtro_fecha_hasta ?: 'sin_filtro',
                        'tipo_consulta' => 'grupos_notas_definitivas'
                    ], 
                    "Notas Definitivas", 
                    "Consulta de grupos de notas definitivas"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría obtenerGruposNotasDefinitivas: " . $e->getMessage());
            }
        }
        
        return $result;
        
    } catch (Exception $e) {
        error_log("Error en obtenerGruposNotasDefinitivas: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR EN CONSULTA
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "notas_definitivas", 
                    null, 
                    null, 
                    [
                        'filtro_profesor' => $filtro_profesor,
                        'filtro_fecha_desde' => $filtro_fecha_desde,
                        'filtro_fecha_hasta' => $filtro_fecha_hasta,
                        'error' => $e->getMessage(),
                        'tipo_consulta' => 'grupos_notas_definitivas'
                    ], 
                    "Notas Definitivas", 
                    "Error en consulta de grupos de notas definitivas"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error obtenerGruposNotasDefinitivas: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

// Obtener información del grupo para notas definitivas - SOLO LECTURA, SIN AUDITORÍA
function obtenerInfoGrupoDefinitivas($docente_id, $materia_id, $periodo_id) {
    global $db;
    
    $query = "SELECT ud.nombre as nombre_docente, ud.idusuario as cedula_docente, 
                     m.nombre_materia, m.cod_materia,
                     pa.nombre_periodo, s.codigo_seccion, c.nombre_carrera, 
                     t.nombre_trayecto, t.id_trayecto, t.numero_trayecto,
                     a.nombre as nombre_admin
              FROM notas_definitivas nd
              INNER JOIN users ud ON nd.id_docente = ud.id
              INNER JOIN materias m ON nd.id_materia = m.id_materia
              INNER JOIN periodos_academicos pa ON nd.id_periodo = pa.id_periodo
              INNER JOIN docente_seccion ds ON nd.id_docente = ds.id_usuario 
                                           AND nd.id_materia = ds.id_materia
              INNER JOIN secciones s ON ds.id_seccion = s.id_seccion
              INNER JOIN carreras c ON s.id_carrera = c.id_carrera
              INNER JOIN trayectos t ON s.id_trayecto = t.id_trayecto
              LEFT JOIN users a ON nd.id_admin_aprobador = a.id
              WHERE nd.id_docente = ? 
              AND nd.id_materia = ? 
              AND nd.id_periodo = ?
              LIMIT 1";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Obtener estudiantes del grupo para notas definitivas - SOLO LECTURA, SIN AUDITORÍA
function obtenerEstudiantesGrupoDefinitivas($docente_id, $materia_id, $periodo_id) {
    global $db;
    
    $query = "SELECT nd.*, u.nombre as nombre_estudiante, u.idusuario as cedula,
                     nd.fecha_registro, nd.soporte, nd.tipo_archivo
              FROM notas_definitivas nd
              INNER JOIN users u ON nd.id_usuario = u.id
              WHERE nd.id_docente = ? 
              AND nd.id_materia = ? 
              AND nd.id_periodo = ?
              ORDER BY u.nombre";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Obtener información de soporte del grupo para notas definitivas - SOLO LECTURA, SIN AUDITORÍA
function obtenerSoporteGrupoDefinitivas($docente_id, $materia_id, $periodo_id) {
    global $db;
    
    $query = "SELECT DISTINCT soporte, tipo_archivo, fecha_registro
              FROM notas_definitivas 
              WHERE id_docente = ? 
              AND id_materia = ? 
              AND id_periodo = ?
              AND soporte IS NOT NULL
              LIMIT 1";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

// Obtener estadísticas del grupo según el id_trayecto para notas definitivas - SOLO LECTURA, SIN AUDITORÍA
function obtenerEstadisticasGrupoDefinitivas($docente_id, $materia_id, $periodo_id, $id_trayecto) {
    global $db;
    
    $query = "SELECT nd.trayecto_0, nd.trayecto_1, nd.trayecto_2, nd.trayecto_3, nd.trayecto_4
              FROM notas_definitivas nd
              WHERE nd.id_docente = ? 
              AND nd.id_materia = ? 
              AND nd.id_periodo = ?";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $total_estudiantes = 0;
    $suma_total = 0;
    $aprobados = 0;
    $reprobados = 0;
    
    while ($nota = $result->fetch_assoc()) {
        $total_estudiantes++;
        
        // Calcular promedio según el id_trayecto de la sección
        $promedio_estudiante = calcularPromedioPorTrayecto($nota, $id_trayecto);
        $suma_total += $promedio_estudiante;
        
        // Aprobados desde 12 puntos
        if ($promedio_estudiante >= 12) {
            $aprobados++;
        } else {
            $reprobados++;
        }
    }
    
    $promedio_general = $total_estudiantes > 0 ? round($suma_total / $total_estudiantes, 1) : 0;
    
    return [
        'total_estudiantes' => $total_estudiantes,
        'promedio_general' => $promedio_general,
        'aprobados' => $aprobados,
        'reprobados' => $reprobados,
        'id_trayecto' => $id_trayecto
    ];
}


// RESPALDO BD ***********************************************************************



// Función para realizar respaldo de base de datos - CON AUDITORÍA COMPLETA
function realizarRespaldo() {
    global $db;
    
    try {
        // Obtener información del usuario
        $usuario = $_SESSION['user']['nombre'];
        $usuario_id = $_SESSION['user']['id'];
        $fecha = date('Y-m-d_H-i-s');
        
        // Nombre del archivo de respaldo con formato: respaldo_(usuario)_(fecha)
        $backup_file = 'respaldo_' . limpiarNombreArchivo($usuario) . '_' . $fecha . '.sql';
        
        // Registrar la descarga en la base de datos
        $registro_id = registrarDescargaRespaldo($usuario, $backup_file);
        
        // REGISTRAR EN AUDITORÍA - INICIO DE RESPALDO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "BACKUP", 
                    "database", 
                    $registro_id, 
                    null, 
                    [
                        'archivo' => $backup_file, 
                        'usuario' => $usuario,
                        'usuario_id' => $usuario_id,
                        'fecha_generacion' => date('Y-m-d H:i:s'),
                        'estado' => 'iniciado'
                    ], 
                    "Sistema", 
                    "Inicio de respaldo de base de datos"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría realizarRespaldo (inicio): " . $e->getMessage());
            }
        }
        
        // Obtener todas las tablas
        $tables = array();
        $result = $db->query('SHOW TABLES');
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }
        
        // Generar el SQL del respaldo
        $output = "-- Respaldo de Base de Datos\n";
        $output .= "-- Generado: " . date('Y-m-d H:i:s') . "\n";
        $output .= "-- Generado por: " . $usuario . "\n";
        $output .= "-- MySQL Server: " . $db->server_info . "\n\n";
        
        // Recorrer todas las tablas
        foreach ($tables as $table) {
            // Estructura de la tabla
            $output .= "--\n-- Estructura de tabla para la tabla `$table`\n--\n";
            $output .= "DROP TABLE IF EXISTS `$table`;\n";
            
            $create_table = $db->query("SHOW CREATE TABLE `$table`");
            $row = $create_table->fetch_row();
            $output .= $row[1] . ";\n\n";
            
            // Datos de la tabla
            $output .= "--\n-- Volcado de datos para la tabla `$table`\n--\n";
            
            $result = $db->query("SELECT * FROM `$table`");
            if ($result->num_rows > 0) {
                $output .= "INSERT IGNORE INTO `$table` VALUES\n";
                
                $rows = array();
                while ($row = $result->fetch_assoc()) {
                    $values = array();
                    foreach ($row as $value) {
                        if ($value === null) {
                            $values[] = 'NULL';
                        } else {
                            $values[] = "'" . $db->real_escape_string($value) . "'";
                        }
                    }
                    $rows[] = "(" . implode(', ', $values) . ")";
                }
                
                $output .= implode(",\n", $rows) . ";\n\n";
            } else {
                $output .= "-- La tabla `$table` está vacía\n\n";
            }
        }
        
        // REGISTRAR EN AUDITORÍA - RESPALDO COMPLETADO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "BACKUP", 
                    "database", 
                    $registro_id, 
                    null, 
                    [
                        'archivo' => $backup_file, 
                        'usuario' => $usuario,
                        'usuario_id' => $usuario_id,
                        'fecha_generacion' => date('Y-m-d H:i:s'),
                        'total_tablas' => count($tables),
                        'estado' => 'completado',
                        'tamano_aproximado' => strlen($output) . ' bytes'
                    ], 
                    "Sistema", 
                    "Respaldo de base de datos completado exitosamente"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría realizarRespaldo (completado): " . $e->getMessage());
            }
        }
        
        // Cabecera para forzar descarga
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $backup_file . '"');
        
        // Escribir el output y finalizar
        echo $output;
        exit();
        
    } catch (Exception $e) {
        error_log("Error en realizarRespaldo: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR EN RESPALDO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "database", 
                    $registro_id ?? null, 
                    null, 
                    [
                        'archivo' => $backup_file ?? 'desconocido',
                        'usuario' => $usuario ?? 'desconocido',
                        'error' => $e->getMessage(),
                        'estado' => 'fallido'
                    ], 
                    "Sistema", 
                    "Error en respaldo de base de datos"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error realizarRespaldo: " . $auditError->getMessage());
            }
        }
        
        // Mostrar error al usuario
        if (!headers_sent()) {
            header('Content-Type: text/html; charset=utf-8');
            echo "<script>alert('Error al generar el respaldo: " . addslashes($e->getMessage()) . "'); history.back();</script>";
        }
        exit();
    }
}

// Función para limpiar nombre de archivo - SIN AUDITORÍA (función auxiliar)
function limpiarNombreArchivo($nombre) {
    // Eliminar caracteres no permitidos en nombres de archivo
    $nombre = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nombre);
    // Limitar la longitud
    $nombre = substr($nombre, 0, 50);
    return $nombre;
}

// Función para registrar descarga de respaldo - CON AUDITORÍA
function registrarDescargaRespaldo($usuario, $nombre_archivo) {
    global $db;
    
    try {
        // Crear tabla de respaldos si no existe
        $crear_tabla = "
        CREATE TABLE IF NOT EXISTS respaldos_descargas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            usuario VARCHAR(100) NOT NULL,
            nombre_archivo VARCHAR(255) NOT NULL,
            fecha_descarga DATETIME DEFAULT CURRENT_TIMESTAMP,
            ip_address VARCHAR(45),
            user_agent TEXT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        
        $db->query($crear_tabla);
        
        // Insertar registro de la descarga
        $ip = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        
        $stmt = $db->prepare("INSERT INTO respaldos_descargas (usuario, nombre_archivo, ip_address, user_agent) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $usuario, $nombre_archivo, $ip, $user_agent);
        $stmt->execute();
        $id_registro = $stmt->insert_id;
        $stmt->close();
        
        // REGISTRAR EN AUDITORÍA - REGISTRO DE DESCARGA
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "INSERT", 
                    "respaldos_descargas", 
                    $id_registro, 
                    null, 
                    [
                        'usuario' => $usuario,
                        'nombre_archivo' => $nombre_archivo,
                        'ip_address' => $ip,
                        'fecha_descarga' => date('Y-m-d H:i:s')
                    ], 
                    "Sistema", 
                    "Registro de descarga de respaldo creado"
                );
            } catch (Exception $e) {
                error_log("Error en auditoría registrarDescargaRespaldo: " . $e->getMessage());
            }
        }
        
        return $id_registro;
        
    } catch (Exception $e) {
        error_log("Error en registrarDescargaRespaldo: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR EN REGISTRO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "respaldos_descargas", 
                    null, 
                    null, 
                    [
                        'usuario' => $usuario,
                        'nombre_archivo' => $nombre_archivo,
                        'error' => $e->getMessage()
                    ], 
                    "Sistema", 
                    "Error al registrar descarga de respaldo"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error registrarDescargaRespaldo: " . $auditError->getMessage());
            }
        }
        
        return 0; // Retornar 0 en caso de error
    }
}

// Función para obtener historial de respaldos - SOLO LECTURA, SIN AUDITORÍA
function obtenerHistorialRespaldos() {
    global $db;
    
    // Verificar si la tabla existe
    $result = $db->query("SHOW TABLES LIKE 'respaldos_descargas'");
    if ($result->num_rows == 0) {
        return array();
    }
    
    // Obtener el historial de descargas
    $historial = array();
    $result = $db->query("SELECT * FROM respaldos_descargas ORDER BY fecha_descarga DESC LIMIT 10");
    
    while ($row = $result->fetch_assoc()) {
        $historial[] = $row;
    }
    
    return $historial;
}

// Función para verificar si se puede eliminar un respaldo - SOLO LÓGICA, SIN AUDITORÍA
function puedeEliminarRespaldo($fecha_descarga) {
    // Calcular si han pasado 90 días desde la fecha de descarga
    $fecha_descarga_obj = new DateTime($fecha_descarga);
    $fecha_actual = new DateTime();
    $diferencia = $fecha_actual->diff($fecha_descarga_obj);
    
    // Verificar si han pasado al menos 90 días
    return $diferencia->days >= 90;
}

// Función para calcular días restantes para poder eliminar - SOLO LÓGICA, SIN AUDITORÍA
function diasParaPoderEliminar($fecha_descarga) {
    // Calcular cuántos días faltan para poder eliminar el respaldo
    $fecha_descarga_obj = new DateTime($fecha_descarga);
    $fecha_actual = new DateTime();
    $diferencia = $fecha_actual->diff($fecha_descarga_obj);
    
    $dias_transcurridos = $diferencia->days;
    $dias_restantes = 90 - $dias_transcurridos;
    
    return max(0, $dias_restantes);
}

// Función para eliminar respaldo - CON AUDITORÍA COMPLETA
function eliminarRespaldo($id_respaldo) {
    global $db;
    
    try {
        // Primero verificar si el respaldo existe y obtener sus datos para auditoría
        $stmt = $db->prepare("SELECT * FROM respaldos_descargas WHERE id = ?");
        $stmt->bind_param("i", $id_respaldo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $_SESSION['error'] = "El respaldo no existe.";
            
            // REGISTRAR EN AUDITORÍA - INTENTO DE ELIMINAR RESPALDO INEXISTENTE
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "ERROR", 
                        "respaldos_descargas", 
                        $id_respaldo, 
                        null, 
                        [
                            'id_respaldo' => $id_respaldo,
                            'error' => 'Respaldo no encontrado'
                        ], 
                        "Sistema", 
                        "Intento de eliminar respaldo inexistente"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría eliminarRespaldo (no existe): " . $e->getMessage());
                }
            }
            
            return false;
        }
        
        $respaldo = $result->fetch_assoc();
        
        // Verificar si han pasado 90 días desde la descarga
        if (!puedeEliminarRespaldo($respaldo['fecha_descarga'])) {
            $dias_restantes = diasParaPoderEliminar($respaldo['fecha_descarga']);
            $_SESSION['error'] = "No se puede eliminar el respaldo. Deben pasar 90 días desde su descarga. Faltan " . $dias_restantes . " días.";
            
            // REGISTRAR EN AUDITORÍA - INTENTO DE ELIMINACIÓN PREMATURA
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "ERROR", 
                        "respaldos_descargas", 
                        $id_respaldo, 
                        null, 
                        [
                            'id_respaldo' => $id_respaldo,
                            'nombre_archivo' => $respaldo['nombre_archivo'],
                            'fecha_descarga' => $respaldo['fecha_descarga'],
                            'dias_restantes' => $dias_restantes,
                            'error' => 'Eliminación prematura'
                        ], 
                        "Sistema", 
                        "Intento de eliminar respaldo antes de 90 días"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría eliminarRespaldo (prematuro): " . $e->getMessage());
                }
            }
            
            return false;
        }
        
        // REGISTRAR EN AUDITORÍA - ANTES DE ELIMINAR
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "DELETE", 
                    "respaldos_descargas", 
                    $id_respaldo, 
                    $respaldo, 
                    [
                        'usuario_eliminacion' => $_SESSION['user']['nombre'] ?? 'Desconocido',
                        'usuario_id_eliminacion' => $_SESSION['user']['id'] ?? 0,
                        'fecha_eliminacion' => date('Y-m-d H:i:s'),
                        'dias_transcurridos' => (new DateTime())->diff(new DateTime($respaldo['fecha_descarga']))->days
                    ], 
                    "Sistema", 
                    "Eliminación de registro de respaldo: " . $respaldo['nombre_archivo']
                );
            } catch (Exception $e) {
                error_log("Error en auditoría eliminarRespaldo (antes): " . $e->getMessage());
            }
        }
        
        // Eliminar el registro de la base de datos
        $stmt = $db->prepare("DELETE FROM respaldos_descargas WHERE id = ?");
        $stmt->bind_param("i", $id_respaldo);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Respaldo eliminado correctamente.";
            
            // REGISTRAR EN AUDITORÍA - ELIMINACIÓN EXITOSA
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "DELETE_SUCCESS", 
                        "respaldos_descargas", 
                        $id_respaldo, 
                        null, 
                        [
                            'id_respaldo' => $id_respaldo,
                            'nombre_archivo' => $respaldo['nombre_archivo'],
                            'usuario_original' => $respaldo['usuario'],
                            'fecha_descarga_original' => $respaldo['fecha_descarga']
                        ], 
                        "Sistema", 
                        "Respaldo eliminado exitosamente"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría eliminarRespaldo (éxito): " . $e->getMessage());
                }
            }
            
            return true;
        } else {
            $_SESSION['error'] = "Error al eliminar el respaldo: " . $db->error;
            
            // REGISTRAR EN AUDITORÍA - ERROR EN ELIMINACIÓN
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "ERROR", 
                        "respaldos_descargas", 
                        $id_respaldo, 
                        null, 
                        [
                            'id_respaldo' => $id_respaldo,
                            'nombre_archivo' => $respaldo['nombre_archivo'],
                            'error' => $db->error
                        ], 
                        "Sistema", 
                        "Error al eliminar respaldo"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría eliminarRespaldo (error db): " . $e->getMessage());
                }
            }
            
            return false;
        }
        
    } catch (Exception $e) {
        error_log("Error en eliminarRespaldo: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR GENERAL
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "respaldos_descargas", 
                    $id_respaldo, 
                    null, 
                    [
                        'id_respaldo' => $id_respaldo,
                        'error' => $e->getMessage()
                    ], 
                    "Sistema", 
                    "Error general al eliminar respaldo"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error eliminarRespaldo: " . $auditError->getMessage());
            }
        }
        
        $_SESSION['error'] = "Error al eliminar el respaldo: " . $e->getMessage();
        return false;
    }
}



// RELACIONAR TITULOS CON MATERIAS ***********************************************************************



// Función para buscar títulos - SOLO LECTURA, SIN AUDITORÍA
function buscarTitulos($search = '') {
    global $db;
    
    $search = $db->real_escape_string($search);
    $query = "SELECT * FROM titulos WHERE nombre LIKE '%$search%' OR descripcion LIKE '%$search%' ORDER BY nombre";
    $result = $db->query($query);
    
    return $result;
}

// Función para buscar relaciones título-materia - SOLO LECTURA, SIN AUDITORÍA
function buscarRelacionesTituloMateria($search = '') {
    global $db;
    
    $search = $db->real_escape_string($search);
    $query = "SELECT tm.*, t.nombre AS titulo, m.nombre_materia, m.cod_materia 
              FROM titulo_materia tm
              JOIN titulos t ON tm.id_titulo = t.id
              JOIN materias m ON tm.id_materia = m.id_materia
              WHERE t.nombre LIKE '%$search%' OR m.nombre_materia LIKE '%$search%' OR m.cod_materia LIKE '%$search%'
              ORDER BY t.nombre";
    $result = $db->query($query);
    
    return $result;
}

// Función para agregar título - CON AUDITORÍA
function agregarTitulo($nombre, $descripcion) {
    global $db;
    
    try {
        $nombre_original = $nombre;
        $descripcion_original = $descripcion;
        
        $nombre = $db->real_escape_string($nombre);
        $descripcion = $db->real_escape_string($descripcion);
        
        $query = "INSERT INTO titulos (nombre, descripcion) VALUES ('$nombre', '$descripcion')";
        $result = $db->query($query);
        
        if ($result) {
            $id_titulo = $db->insert_id;
            
            // REGISTRAR EN AUDITORÍA - TÍTULO AGREGADO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "INSERT", 
                        "titulos", 
                        $id_titulo, 
                        null, 
                        [
                            'nombre' => $nombre_original,
                            'descripcion' => $descripcion_original,
                            'usuario' => $_SESSION['user']['nombre'] ?? 'Desconocido',
                            'usuario_id' => $_SESSION['user']['id'] ?? 0,
                            'fecha_creacion' => date('Y-m-d H:i:s')
                        ], 
                        "Gestión de Títulos", 
                        "Título agregado: " . $nombre_original
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría agregarTitulo: " . $e->getMessage());
                }
            }
            
            return $id_titulo;
        } else {
            throw new Exception("Error en la consulta: " . $db->error);
        }
        
    } catch (Exception $e) {
        error_log("Error en agregarTitulo: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL AGREGAR TÍTULO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "titulos", 
                    null, 
                    null, 
                    [
                        'nombre' => $nombre_original ?? '',
                        'descripcion' => $descripcion_original ?? '',
                        'error' => $e->getMessage(),
                        'usuario' => $_SESSION['user']['nombre'] ?? 'Desconocido'
                    ], 
                    "Gestión de Títulos", 
                    "Error al agregar título"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error agregarTitulo: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

// Función para editar título - CON AUDITORÍA
function editarTitulo($id, $nombre, $descripcion) {
    global $db;
    
    try {
        // Obtener datos actuales para auditoría
        $query_actual = "SELECT nombre, descripcion FROM titulos WHERE id = '$id'";
        $result_actual = $db->query($query_actual);
        
        if ($result_actual->num_rows === 0) {
            throw new Exception("Título no encontrado");
        }
        
        $titulo_actual = $result_actual->fetch_assoc();
        
        $nombre_original = $nombre;
        $descripcion_original = $descripcion;
        
        $id = $db->real_escape_string($id);
        $nombre = $db->real_escape_string($nombre);
        $descripcion = $db->real_escape_string($descripcion);
        
        $query = "UPDATE titulos SET nombre = '$nombre', descripcion = '$descripcion' WHERE id = '$id'";
        $result = $db->query($query);
        
        if ($result) {
            // REGISTRAR EN AUDITORÍA - TÍTULO EDITADO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "UPDATE", 
                        "titulos", 
                        $id, 
                        [
                            'nombre_anterior' => $titulo_actual['nombre'],
                            'descripcion_anterior' => $titulo_actual['descripcion']
                        ], 
                        [
                            'nombre_nuevo' => $nombre_original,
                            'descripcion_nuevo' => $descripcion_original,
                            'usuario' => $_SESSION['user']['nombre'] ?? 'Desconocido',
                            'usuario_id' => $_SESSION['user']['id'] ?? 0,
                            'fecha_actualizacion' => date('Y-m-d H:i:s')
                        ], 
                        "Gestión de Títulos", 
                        "Título actualizado: " . $titulo_actual['nombre'] . " → " . $nombre_original
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría editarTitulo: " . $e->getMessage());
                }
            }
            
            return true;
        } else {
            throw new Exception("Error en la consulta: " . $db->error);
        }
        
    } catch (Exception $e) {
        error_log("Error en editarTitulo: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL EDITAR TÍTULO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "titulos", 
                    $id, 
                    null, 
                    [
                        'id_titulo' => $id,
                        'nombre_nuevo' => $nombre_original ?? '',
                        'descripcion_nuevo' => $descripcion_original ?? '',
                        'error' => $e->getMessage(),
                        'usuario' => $_SESSION['user']['nombre'] ?? 'Desconocido'
                    ], 
                    "Gestión de Títulos", 
                    "Error al editar título"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error editarTitulo: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

// Función para eliminar título - CON AUDITORÍA
function eliminarTitulo($id) {
    global $db;
    
    try {
        // Obtener datos del título para auditoría
        $query_actual = "SELECT nombre, descripcion FROM titulos WHERE id = '$id'";
        $result_actual = $db->query($query_actual);
        
        if ($result_actual->num_rows === 0) {
            throw new Exception("Título no encontrado");
        }
        
        $titulo_actual = $result_actual->fetch_assoc();
        
        // Verificar si hay relaciones existentes
        $query_relaciones = "SELECT COUNT(*) as total FROM titulo_materia WHERE id_titulo = '$id'";
        $result_relaciones = $db->query($query_relaciones);
        $total_relaciones = $result_relaciones->fetch_assoc()['total'];
        
        $id = $db->real_escape_string($id);
        $query = "DELETE FROM titulos WHERE id = '$id'";
        $result = $db->query($query);
        
        if ($result) {
            // REGISTRAR EN AUDITORÍA - TÍTULO ELIMINADO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "DELETE", 
                        "titulos", 
                        $id, 
                        [
                            'nombre' => $titulo_actual['nombre'],
                            'descripcion' => $titulo_actual['descripcion'],
                            'relaciones_eliminadas' => $total_relaciones
                        ], 
                        [
                            'usuario' => $_SESSION['user']['nombre'] ?? 'Desconocido',
                            'usuario_id' => $_SESSION['user']['id'] ?? 0,
                            'fecha_eliminacion' => date('Y-m-d H:i:s')
                        ], 
                        "Gestión de Títulos", 
                        "Título eliminado: " . $titulo_actual['nombre']
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría eliminarTitulo: " . $e->getMessage());
                }
            }
            
            return true;
        } else {
            throw new Exception("Error en la consulta: " . $db->error);
        }
        
    } catch (Exception $e) {
        error_log("Error en eliminarTitulo: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL ELIMINAR TÍTULO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "titulos", 
                    $id, 
                    null, 
                    [
                        'id_titulo' => $id,
                        'error' => $e->getMessage(),
                        'usuario' => $_SESSION['user']['nombre'] ?? 'Desconocido'
                    ], 
                    "Gestión de Títulos", 
                    "Error al eliminar título"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error eliminarTitulo: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

// Función para agregar relación título-materia - CON AUDITORÍA
function agregarRelacionTituloMateria($id_titulo, $id_materia, $prioridad) {
    global $db;
    
    try {
        // Obtener información del título y materia para auditoría
        $query_titulo = "SELECT nombre FROM titulos WHERE id = '$id_titulo'";
        $query_materia = "SELECT nombre_materia, cod_materia FROM materias WHERE id_materia = '$id_materia'";
        
        $titulo_info = $db->query($query_titulo)->fetch_assoc();
        $materia_info = $db->query($query_materia)->fetch_assoc();
        
        if (!$titulo_info || !$materia_info) {
            throw new Exception("Título o materia no encontrados");
        }
        
        $id_titulo = $db->real_escape_string($id_titulo);
        $id_materia = $db->real_escape_string($id_materia);
        $prioridad = $db->real_escape_string($prioridad);
        
        $query = "INSERT INTO titulo_materia (id_titulo, id_materia, prioridad) VALUES ('$id_titulo', '$id_materia', '$prioridad')";
        $result = $db->query($query);
        
        if ($result) {
            $id_relacion = $db->insert_id;
            
            // REGISTRAR EN AUDITORÍA - RELACIÓN AGREGADA
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "INSERT", 
                        "titulo_materia", 
                        $id_relacion, 
                        null, 
                        [
                            'id_titulo' => $id_titulo,
                            'titulo_nombre' => $titulo_info['nombre'],
                            'id_materia' => $id_materia,
                            'materia_nombre' => $materia_info['nombre_materia'],
                            'materia_codigo' => $materia_info['cod_materia'],
                            'prioridad' => $prioridad,
                            'usuario' => $_SESSION['user']['nombre'] ?? 'Desconocido',
                            'usuario_id' => $_SESSION['user']['id'] ?? 0,
                            'fecha_creacion' => date('Y-m-d H:i:s')
                        ], 
                        "Gestión de Títulos", 
                        "Relación título-materia agregada: " . $titulo_info['nombre'] . " - " . $materia_info['nombre_materia']
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría agregarRelacionTituloMateria: " . $e->getMessage());
                }
            }
            
            return $id_relacion;
        } else {
            throw new Exception("Error en la consulta: " . $db->error);
        }
        
    } catch (Exception $e) {
        error_log("Error en agregarRelacionTituloMateria: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL AGREGAR RELACIÓN
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "titulo_materia", 
                    null, 
                    null, 
                    [
                        'id_titulo' => $id_titulo,
                        'id_materia' => $id_materia,
                        'prioridad' => $prioridad,
                        'error' => $e->getMessage(),
                        'usuario' => $_SESSION['user']['nombre'] ?? 'Desconocido'
                    ], 
                    "Gestión de Títulos", 
                    "Error al agregar relación título-materia"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error agregarRelacionTituloMateria: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

// Función para eliminar relación título-materia - CON AUDITORÍA
function eliminarRelacionTituloMateria($id_relacion) {
    global $db;
    
    try {
        // Obtener información de la relación para auditoría
        $query_relacion = "SELECT tm.*, t.nombre as titulo_nombre, m.nombre_materia, m.cod_materia 
                          FROM titulo_materia tm
                          JOIN titulos t ON tm.id_titulo = t.id
                          JOIN materias m ON tm.id_materia = m.id_materia
                          WHERE tm.id_relacion = '$id_relacion'";
        
        $result_relacion = $db->query($query_relacion);
        
        if ($result_relacion->num_rows === 0) {
            throw new Exception("Relación no encontrada");
        }
        
        $relacion_info = $result_relacion->fetch_assoc();
        
        $id_relacion = $db->real_escape_string($id_relacion);
        $query = "DELETE FROM titulo_materia WHERE id_relacion = '$id_relacion'";
        $result = $db->query($query);
        
        if ($result) {
            // REGISTRAR EN AUDITORÍA - RELACIÓN ELIMINADA
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "DELETE", 
                        "titulo_materia", 
                        $id_relacion, 
                        [
                            'id_titulo' => $relacion_info['id_titulo'],
                            'titulo_nombre' => $relacion_info['titulo_nombre'],
                            'id_materia' => $relacion_info['id_materia'],
                            'materia_nombre' => $relacion_info['nombre_materia'],
                            'materia_codigo' => $relacion_info['cod_materia'],
                            'prioridad' => $relacion_info['prioridad']
                        ], 
                        [
                            'usuario' => $_SESSION['user']['nombre'] ?? 'Desconocido',
                            'usuario_id' => $_SESSION['user']['id'] ?? 0,
                            'fecha_eliminacion' => date('Y-m-d H:i:s')
                        ], 
                        "Gestión de Títulos", 
                        "Relación título-materia eliminada: " . $relacion_info['titulo_nombre'] . " - " . $relacion_info['nombre_materia']
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría eliminarRelacionTituloMateria: " . $e->getMessage());
                }
            }
            
            return true;
        } else {
            throw new Exception("Error en la consulta: " . $db->error);
        }
        
    } catch (Exception $e) {
        error_log("Error en eliminarRelacionTituloMateria: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - ERROR AL ELIMINAR RELACIÓN
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "titulo_materia", 
                    $id_relacion, 
                    null, 
                    [
                        'id_relacion' => $id_relacion,
                        'error' => $e->getMessage(),
                        'usuario' => $_SESSION['user']['nombre'] ?? 'Desconocido'
                    ], 
                    "Gestión de Títulos", 
                    "Error al eliminar relación título-materia"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de error eliminarRelacionTituloMateria: " . $auditError->getMessage());
            }
        }
        
        return false;
    }
}

// Función para obtener todos los títulos - SOLO LECTURA, SIN AUDITORÍA
function obtenerTodosTitulos() {
    global $db;
    
    $query = "SELECT * FROM titulos ORDER BY nombre";
    return $db->query($query);
}

// Función para obtener todas las materias - SOLO LECTURA, SIN AUDITORÍA
function obtenerTodasMaterias() {
    global $db;
    
    $query = "SELECT * FROM materias ORDER BY nombre_materia";
    return $db->query($query);
}


//TIPOS PAGO ***********************************************************************



// Función para obtener todos los tipos de pago - SOLO LECTURA, SIN AUDITORÍA
function obtenerTiposPago() {
    global $db;
    
    $query = "SELECT id, tipopago FROM tipo_pago ORDER BY tipopago";
    $result = $db->query($query);
    
    if ($result) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    return [];
}

// Función para crear un nuevo tipo de pago - CON AUDITORÍA
function crearTipoPago($tipopago) {
    global $db;
    
    try {
        $tipopago_original = $tipopago;
        $stmt = $db->prepare("INSERT INTO tipo_pago (tipopago) VALUES (?)");
        $stmt->bind_param("s", $tipopago);
        
        if ($stmt->execute()) {
            $id_insertado = $db->insert_id;
            $stmt->close();
            
            // REGISTRAR EN AUDITORÍA - TIPO DE PAGO CREADO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "INSERT", 
                        "tipo_pago", 
                        $id_insertado, 
                        null, 
                        [
                            'tipo_pago' => $tipopago_original,
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido',
                            'usuario_id' => $_SESSION['user']['id'] ?? 0,
                            'fecha_creacion' => date('Y-m-d H:i:s')
                        ], 
                        "Tipos de Pago", 
                        "Tipo de pago creado: " . $tipopago_original
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría crearTipoPago: " . $e->getMessage());
                }
            }
            
            return ['success' => true, 'message' => 'Tipo de pago creado exitosamente'];
        } else {
            $error = $stmt->error;
            $stmt->close();
            
            // REGISTRAR EN AUDITORÍA - ERROR AL CREAR TIPO DE PAGO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "ERROR", 
                        "tipo_pago", 
                        null, 
                        null, 
                        [
                            'tipo_pago' => $tipopago_original,
                            'error' => $error,
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                        ], 
                        "Tipos de Pago", 
                        "Error al crear tipo de pago: " . $tipopago_original
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría de error crearTipoPago: " . $e->getMessage());
                }
            }
            
            return ['success' => false, 'message' => 'Error al crear: ' . $error];
        }
        
    } catch (Exception $e) {
        error_log("Error en crearTipoPago: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - EXCEPCIÓN AL CREAR TIPO DE PAGO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "tipo_pago", 
                    null, 
                    null, 
                    [
                        'tipo_pago' => $tipopago_original ?? '',
                        'error' => $e->getMessage(),
                        'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                    ], 
                    "Tipos de Pago", 
                    "Excepción al crear tipo de pago"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de excepción crearTipoPago: " . $auditError->getMessage());
            }
        }
        
        return ['success' => false, 'message' => 'Error al crear: ' . $e->getMessage()];
    }
}

// Función para actualizar un tipo de pago - CON AUDITORÍA
function actualizarTipoPago($id, $tipopago) {
    global $db;
    
    try {
        // Obtener datos actuales para auditoría
        $stmt_actual = $db->prepare("SELECT tipopago FROM tipo_pago WHERE id = ?");
        $stmt_actual->bind_param("i", $id);
        $stmt_actual->execute();
        $result_actual = $stmt_actual->get_result();
        
        if ($result_actual->num_rows === 0) {
            return ['success' => false, 'message' => 'Tipo de pago no encontrado'];
        }
        
        $tipo_pago_actual = $result_actual->fetch_assoc();
        $stmt_actual->close();
        
        $tipopago_original = $tipopago;
        $stmt = $db->prepare("UPDATE tipo_pago SET tipopago = ? WHERE id = ?");
        $stmt->bind_param("si", $tipopago, $id);
        
        if ($stmt->execute()) {
            $stmt->close();
            
            // REGISTRAR EN AUDITORÍA - TIPO DE PAGO ACTUALIZADO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "UPDATE", 
                        "tipo_pago", 
                        $id, 
                        [
                            'tipo_pago_anterior' => $tipo_pago_actual['tipopago']
                        ], 
                        [
                            'tipo_pago_nuevo' => $tipopago_original,
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido',
                            'usuario_id' => $_SESSION['user']['id'] ?? 0,
                            'fecha_actualizacion' => date('Y-m-d H:i:s')
                        ], 
                        "Tipos de Pago", 
                        "Tipo de pago actualizado: " . $tipo_pago_actual['tipopago'] . " → " . $tipopago_original
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría actualizarTipoPago: " . $e->getMessage());
                }
            }
            
            return ['success' => true, 'message' => 'Tipo de pago actualizado exitosamente'];
        } else {
            $error = $stmt->error;
            $stmt->close();
            
            // REGISTRAR EN AUDITORÍA - ERROR AL ACTUALIZAR TIPO DE PAGO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "ERROR", 
                        "tipo_pago", 
                        $id, 
                        null, 
                        [
                            'id_tipo_pago' => $id,
                            'tipo_pago_nuevo' => $tipopago_original,
                            'error' => $error,
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                        ], 
                        "Tipos de Pago", 
                        "Error al actualizar tipo de pago ID: " . $id
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría de error actualizarTipoPago: " . $e->getMessage());
                }
            }
            
            return ['success' => false, 'message' => 'Error al actualizar: ' . $error];
        }
        
    } catch (Exception $e) {
        error_log("Error en actualizarTipoPago: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - EXCEPCIÓN AL ACTUALIZAR TIPO DE PAGO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "tipo_pago", 
                    $id, 
                    null, 
                    [
                        'id_tipo_pago' => $id,
                        'tipo_pago_nuevo' => $tipopago_original ?? '',
                        'error' => $e->getMessage(),
                        'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                    ], 
                    "Tipos de Pago", 
                    "Excepción al actualizar tipo de pago"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de excepción actualizarTipoPago: " . $auditError->getMessage());
            }
        }
        
        return ['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()];
    }
}

// Función para eliminar un tipo de pago - CON AUDITORÍA
function eliminarTipoPago($id) {
    global $db;
    
    try {
        // Obtener datos del tipo de pago para auditoría
        $stmt_actual = $db->prepare("SELECT tipopago FROM tipo_pago WHERE id = ?");
        $stmt_actual->bind_param("i", $id);
        $stmt_actual->execute();
        $result_actual = $stmt_actual->get_result();
        
        if ($result_actual->num_rows === 0) {
            return ['success' => false, 'message' => 'Tipo de pago no encontrado'];
        }
        
        $tipo_pago_eliminado = $result_actual->fetch_assoc();
        $stmt_actual->close();
        
        $stmt = $db->prepare("DELETE FROM tipo_pago WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $stmt->close();
            
            // REGISTRAR EN AUDITORÍA - TIPO DE PAGO ELIMINADO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "DELETE", 
                        "tipo_pago", 
                        $id, 
                        [
                            'tipo_pago_eliminado' => $tipo_pago_eliminado['tipopago']
                        ], 
                        [
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido',
                            'usuario_id' => $_SESSION['user']['id'] ?? 0,
                            'fecha_eliminacion' => date('Y-m-d H:i:s')
                        ], 
                        "Tipos de Pago", 
                        "Tipo de pago eliminado: " . $tipo_pago_eliminado['tipopago'] . " (ID: " . $id . ")"
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría eliminarTipoPago: " . $e->getMessage());
                }
            }
            
            return ['success' => true, 'message' => 'Tipo de pago eliminado exitosamente'];
        } else {
            $error = $stmt->error;
            $stmt->close();
            
            // REGISTRAR EN AUDITORÍA - ERROR AL ELIMINAR TIPO DE PAGO
            if (function_exists('registrarAuditoria')) {
                try {
                    registrarAuditoria(
                        "ERROR", 
                        "tipo_pago", 
                        $id, 
                        null, 
                        [
                            'id_tipo_pago' => $id,
                            'tipo_pago' => $tipo_pago_eliminado['tipopago'],
                            'error' => $error,
                            'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                        ], 
                        "Tipos de Pago", 
                        "Error al eliminar tipo de pago: " . $tipo_pago_eliminado['tipopago']
                    );
                } catch (Exception $e) {
                    error_log("Error en auditoría de error eliminarTipoPago: " . $e->getMessage());
                }
            }
            
            return ['success' => false, 'message' => 'Error al eliminar: ' . $error];
        }
        
    } catch (Exception $e) {
        error_log("Error en eliminarTipoPago: " . $e->getMessage());
        
        // REGISTRAR EN AUDITORÍA - EXCEPCIÓN AL ELIMINAR TIPO DE PAGO
        if (function_exists('registrarAuditoria')) {
            try {
                registrarAuditoria(
                    "ERROR", 
                    "tipo_pago", 
                    $id, 
                    null, 
                    [
                        'id_tipo_pago' => $id,
                        'error' => $e->getMessage(),
                        'usuario' => $_SESSION['user']['username'] ?? 'Desconocido'
                    ], 
                    "Tipos de Pago", 
                    "Excepción al eliminar tipo de pago"
                );
            } catch (Exception $auditError) {
                error_log("Error en auditoría de excepción eliminarTipoPago: " . $auditError->getMessage());
            }
        }
        
        return ['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()];
    }
}

// Función para validar tipo de pago - SOLO VALIDACIÓN, SIN AUDITORÍA
function validarTipoPago($tipopago) {
    $tipopago = trim($tipopago);
    
    if (empty($tipopago)) {
        return ['success' => false, 'message' => 'El tipo de pago es requerido'];
    }
    
    if (strlen($tipopago) < 2) {
        return ['success' => false, 'message' => 'El tipo de pago debe tener al menos 2 caracteres'];
    }
    
    if (strlen($tipopago) > 100) {
        return ['success' => false, 'message' => 'El tipo de pago no puede exceder los 100 caracteres'];
    }
    
    return ['success' => true, 'message' => ''];
}




// CARGA DE NOTAS DOCENTE************************************************************



/**
 * Obtener ID del usuario desde la sesión
 */
function obtenerIdUsuario() {
    if (isset($_SESSION['user']['id'])) {
        return (int)$_SESSION['user']['id'];
    } elseif (isset($_SESSION['id'])) {
        return (int)$_SESSION['id'];
    } elseif (isset($_SESSION['user_id'])) {
        return (int)$_SESSION['user_id'];
    }
    return false;
}

/**
 * Obtener secciones del docente
 */
function obtenerSeccionesDocente($docente_id) {
    global $db;
    
    $query = "SELECT s.id_seccion, s.codigo_seccion, c.nombre_carrera, 
                     t.nombre_trayecto, pa.nombre_periodo,
                     m.id_materia, m.nombre_materia, m.cod_materia, t.numero_trayecto
              FROM secciones s
              INNER JOIN docente_seccion ds ON s.id_seccion = ds.id_seccion
              INNER JOIN carreras c ON s.id_carrera = c.id_carrera
              INNER JOIN trayectos t ON s.id_trayecto = t.id_trayecto
              INNER JOIN periodos_academicos pa ON s.id_periodo = pa.id_periodo
              INNER JOIN materias m ON ds.id_materia = m.id_materia
              WHERE ds.id_usuario = ? 
              AND (ds.estatus = 'activo' OR ds.estatus = 1)
              ORDER BY pa.fecha_inicio DESC, c.nombre_carrera";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $docente_id);
    $stmt->execute();
    
    return $stmt->get_result();
}


/**
 * Procesar notas de estudiantes
 */
function procesarNotasEstudiantes() {
    global $db;
    
    // Obtener datos del formulario
    $docente_id = (int)$_POST['docente_id'];
    $materia_id = (int)$_POST['materia_id'];
    $periodo_id = (int)$_POST['periodo_id'];
    $trayecto_actual = (int)$_POST['trayecto_actual'];
    $campo_trayecto = 'trayecto_' . $trayecto_actual;
    $notas = $_POST['notas'];
    
    // Procesar soporte si se subió
    $soporte_nombre = null;
    $tipo_archivo = null;
    
    if (isset($_FILES['soporte_grupo']) && $_FILES['soporte_grupo']['error'] === UPLOAD_ERR_OK) {
        $soporte = $_FILES['soporte_grupo'];
        $extension = strtolower(pathinfo($soporte['name'], PATHINFO_EXTENSION));
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
        
        if (in_array($extension, $extensiones_permitidas)) {
            $soporte_nombre = uniqid() . '_' . time() . '.' . $extension;
            $tipo_archivo = $extension;
            $ruta_destino = '../soportes/' . $soporte_nombre;
            
            if (!move_uploaded_file($soporte['tmp_name'], $ruta_destino)) {
                echo "<script>alert('Error al subir el archivo de soporte');</script>";
            }
        }
    }
    
    // Procesar cada nota
    $errores = [];
    $exitos = 0;
    
    foreach ($notas as $id_estudiante => $nota_data) {
        $id_estudiante = (int)$id_estudiante;
        $valor_nota = (int)$nota_data[$campo_trayecto];
        
        // Validar que la nota esté entre 1 y 20
        if ($valor_nota < 1 || $valor_nota > 20) {
            $errores[] = "Nota inválida para el estudiante ID $id_estudiante: $valor_nota";
            continue;
        }
        
        // Verificar si ya existe en notas_pendientes
        $check_query = "SELECT id FROM notas_pendientes 
                       WHERE id_usuario = ? 
                       AND id_materia = ? 
                       AND id_periodo = ? 
                       AND id_docente = ?";
        $stmt = $db->prepare($check_query);
        $stmt->bind_param("iiii", $id_estudiante, $materia_id, $periodo_id, $docente_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Actualizar registro existente
            $update_query = "UPDATE notas_pendientes 
                            SET $campo_trayecto = ?, 
                                soporte = ?, 
                                tipo_archivo = ?, 
                                fecha_subida = NOW(),
                                estado = 'en_revision' 
                            WHERE id_usuario = ? 
                            AND id_materia = ? 
                            AND id_periodo = ? 
                            AND id_docente = ?";
            
            $stmt = $db->prepare($update_query);
            $stmt->bind_param("issiiii", $valor_nota, $soporte_nombre, $tipo_archivo, 
                             $id_estudiante, $materia_id, $periodo_id, $docente_id);
        } else {
            // Insertar nuevo registro - el estado por defecto es 'en revision'
            $insert_query = "INSERT INTO notas_pendientes 
                            (id_usuario, id_materia, id_periodo, id_docente, 
                             $campo_trayecto, soporte, tipo_archivo, estado, fecha_envio, fecha_subida) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, 'en_revision', NOW(), NOW())";
            
            $stmt = $db->prepare($insert_query);
            $stmt->bind_param("iiiiiss", $id_estudiante, $materia_id, $periodo_id, $docente_id,
                             $valor_nota, $soporte_nombre, $tipo_archivo);
        }
        
        if ($stmt->execute()) {
            $exitos++;
        } else {
            $errores[] = "Error al guardar nota para estudiante ID $id_estudiante: " . $stmt->error;
        }
    }
    
    // Mostrar resultados
    if (empty($errores)) {
        $_SESSION['success'] = "✅ Todas las notas se guardaron correctamente ($exitos registros)";
    } else {
        $mensaje_error = "Error al guardar algunas notas:\\n";
        $mensaje_error .= "• " . implode("\\n• ", array_slice($errores, 0, 5));
        if (count($errores) > 5) {
            $mensaje_error .= "\\n• ... y " . (count($errores) - 5) . " errores más";
        }
        $_SESSION['error'] = $mensaje_error;
    }
    
    // Redirigir de vuelta al formulario
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

/**
 * Verificar estados de notas de estudiantes
 */
function verificarEstadosNotas($estudiantes, $materia_id, $periodo_id, $docente_id, $trayecto_a_mostrar) {
    $notas_aprobadas = false;
    $notas_rechazadas = false;
    $notas_en_revision = false;
    $notas_pendientes = false;

    $estudiantes_con_notas_aprobadas = [];
    $estudiantes_con_notas_rechazadas = [];
    $estudiantes_con_notas_en_revision = [];
    $estudiantes_con_notas_pendientes = [];

    $estudiantes_info = [];
    
    while ($estudiante = $estudiantes->fetch_assoc()) {
        $notas_definitivas = obtenerNotasDefinitivas($estudiante['id'], $materia_id);
        $notas_pendientes_data = obtenerNotasPendientes($estudiante['id'], $materia_id, $periodo_id, $docente_id);
        
        // JERARQUÍA CORRECTA DE ESTADOS:
        // 1. Aprobada (máxima prioridad) - tabla de notas_definitivas
        // 2. Rechazada - si está en notas_pendientes con estado 'rechazada'
        // 3. En Revisión - si está en tabla notas_pendientes con cualquier otro estado
        // 4. Pendiente - no está en ninguna tabla
        
        $estado = 'pendiente'; // Por defecto
        $valor_nota = 1; // Valor por defecto
        $campo_trayecto = 'trayecto_' . $trayecto_a_mostrar;
        
        // PRIMERO: Verificar si existe en la tabla de notas_definitivas (APROBADA - MÁXIMA PRIORIDAD)
        if ($notas_definitivas) {
            $estado = 'aprobada';
            $notas_aprobadas = true;
            $estudiantes_con_notas_aprobadas[] = $estudiante['nombre'];
            // Obtener la nota de la tabla definitiva
            if (isset($notas_definitivas[$campo_trayecto]) && $notas_definitivas[$campo_trayecto] !== null) {
                $valor_nota = (int)$notas_definitivas[$campo_trayecto];
            }
        } 
        // SEGUNDO: Si no está aprobada, verificar si está en notas_pendientes y su estado
        elseif ($notas_pendientes_data) {
            // Verificar el estado en notas_pendientes
            $estado_pendiente = isset($notas_pendientes_data['estado_pendiente']) ? $notas_pendientes_data['estado_pendiente'] : 'en_revision';
            
            if ($estado_pendiente === 'rechazada') {
                $estado = 'rechazada';
                $notas_rechazadas = true;
                $estudiantes_con_notas_rechazadas[] = $estudiante['nombre'];
            } else {
                $estado = 'en_revision';
                $notas_en_revision = true;
                $estudiantes_con_notas_en_revision[] = $estudiante['nombre'];
            }
            
            // Obtener la nota de la tabla notas_pendientes
            if (isset($notas_pendientes_data[$campo_trayecto]) && $notas_pendientes_data[$campo_trayecto] !== null) {
                $valor_nota = (int)$notas_pendientes_data[$campo_trayecto];
            }
        } 
        // TERCERO: Si no está en ninguna tabla, es pendiente
        else {
            $estado = 'pendiente';
            $notas_pendientes = true;
            $estudiantes_con_notas_pendientes[] = $estudiante['nombre'];
            // Mantener el valor por defecto de 1
        }
        
        $estudiantes_info[] = [
            'datos' => $estudiante,
            'estado' => $estado,
            'valor_nota' => $valor_nota
        ];
    }
    
    return [
        'estudiantes_info' => $estudiantes_info,
        'notas_aprobadas' => $notas_aprobadas,
        'notas_rechazadas' => $notas_rechazadas,
        'notas_en_revision' => $notas_en_revision,
        'notas_pendientes' => $notas_pendientes,
        'estudiantes_con_notas_aprobadas' => $estudiantes_con_notas_aprobadas,
        'estudiantes_con_notas_rechazadas' => $estudiantes_con_notas_rechazadas,
        'estudiantes_con_notas_en_revision' => $estudiantes_con_notas_en_revision,
        'estudiantes_con_notas_pendientes' => $estudiantes_con_notas_pendientes
    ];
}

/**
 * Obtener datos completos de notas_pendientes
 */
function obtenerNotasPendientes($id_estudiante, $id_materia, $id_periodo, $id_docente) {
    global $db;
    
    $query = "SELECT *, estado as estado_pendiente FROM notas_pendientes 
              WHERE id_usuario = ? 
              AND id_materia = ? 
              AND id_periodo = ? 
              AND id_docente = ? 
              LIMIT 1";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iiii", $id_estudiante, $id_materia, $id_periodo, $id_docente);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}




// CORRECCION DE NOTAS**********************************************************************




if (!function_exists('buscarEstudiantePorCedula')) {
/**
 * Buscar estudiante por cédula
 */
function buscarEstudiantePorCedula($cedula) {
    global $db;
    
    try {
        $query = "SELECT id, idusuario, nombre, carrera 
                  FROM users 
                  WHERE idusuario = ? 
                  AND estudiante = 1 
                  AND status = 1";
        
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }

        $stmt->bind_param("s", $cedula);
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $estudiantes = [];
        while ($row = $result->fetch_assoc()) {
            $estudiantes[] = $row;
        }

        $stmt->close();
        
        // Si hay múltiples resultados, devolver el array completo
        if (count($estudiantes) > 1) {
            return $estudiantes;
        }
        // Si hay un solo resultado, devolverlo directamente
        elseif (count($estudiantes) == 1) {
            return $estudiantes[0];
        }
        // Si no hay resultados
        else {
            return false;
        }
        
    } catch (Exception $e) {
        error_log("Error al buscar estudiante: " . $e->getMessage());
        return false;
    }
}
}

/**
 * Función auxiliar para buscar carreras por nombre
 */
function obtenerCarrerasPorNombre($nombre_carrera) {
    global $db;
    
    try {
        $query = "SELECT id_carrera, nombre_carrera 
                  FROM carreras 
                  WHERE nombre_carrera LIKE ? 
                  AND activa = 1";
        
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }

        $nombre_like = "%" . $nombre_carrera . "%";
        $stmt->bind_param("s", $nombre_like);
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $carreras = [];
        while ($row = $result->fetch_assoc()) {
            $carreras[] = $row;
        }

        $stmt->close();
        return $carreras;
        
    } catch (Exception $e) {
        error_log("Error al obtener carreras por nombre: " . $e->getMessage());
        return [];
    }
}

/**
 * Obtener estudiante por ID
 */
if (!function_exists('obtenerEstudiantePorId')) {
function obtenerEstudiantePorId($id) {
    global $db;
    
    try {
        $query = "SELECT id, idusuario, nombre, carrera 
                  FROM users 
                  WHERE id = ? 
                  AND estudiante = 1 
                  LIMIT 1";
        
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }

        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $estudiante = $result->fetch_assoc();
        $stmt->close();
        
        return $estudiante;
        
    } catch (Exception $e) {
        error_log("Error al obtener estudiante: " . $e->getMessage());
        return false;
    }
}
}

/**
 * Obtener carreras del estudiante - VERSIÓN CORREGIDA CON RELACIÓN
 */
function obtenerCarrerasEstudiante($id_estudiante) {
    global $db;
    
    try {
        // Primero obtenemos el ID de la carrera del estudiante
        $query_user = "SELECT carrera FROM users WHERE id = ? LIMIT 1";
        $stmt_user = $db->prepare($query_user);
        if (!$stmt_user) {
            throw new Exception("Error en preparación user: " . $db->error);
        }

        $stmt_user->bind_param("i", $id_estudiante);
        if (!$stmt_user->execute()) {
            throw new Exception("Error en ejecución user: " . $stmt_user->error);
        }

        $result_user = $stmt_user->get_result();
        $user = $result_user->fetch_assoc();
        $stmt_user->close();

        if (!$user || empty($user['carrera'])) {
            return [];
        }

        $carrera_id = $user['carrera'];
        
        // Buscamos la carrera por ID
        $query_carreras = "SELECT id_carrera, nombre_carrera 
                          FROM carreras 
                          WHERE id_carrera = ? 
                          AND activa = 1";
        
        $stmt_carreras = $db->prepare($query_carreras);
        if (!$stmt_carreras) {
            throw new Exception("Error en preparación carreras: " . $db->error);
        }

        $stmt_carreras->bind_param("i", $carrera_id);
        if (!$stmt_carreras->execute()) {
            throw new Exception("Error en ejecución carreras: " . $stmt_carreras->error);
        }

        $result_carreras = $stmt_carreras->get_result();
        $carreras = [];
        while ($row = $result_carreras->fetch_assoc()) {
            $carreras[] = $row;
        }

        $stmt_carreras->close();
        
        return $carreras;
        
    } catch (Exception $e) {
        error_log("Error al obtener carreras: " . $e->getMessage());
        return [];
    }
}

/**
 * Obtener materias por carrera - VERSIÓN CON TABLA DE RELACIÓN
 */
function obtenerMateriasPorCarrera($id_carrera) {
    global $db;
    
    try {
        // Usamos la tabla carrera_materia para obtener las materias relacionadas
        $query = "SELECT m.id_materia, m.cod_materia, m.nombre_materia, m.trayecto, cm.semestre
                  FROM materias m
                  INNER JOIN carrera_materia cm ON m.id_materia = cm.id_materia
                  WHERE cm.id_carrera = ? 
                  AND m.activa = 1 
                  ORDER BY cm.semestre, m.trayecto, m.nombre_materia";
        
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en preparación materias: " . $db->error);
        }

        $stmt->bind_param("i", $id_carrera);
        
        if (!$stmt->execute()) {
            throw new Exception("Error en ejecución materias: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $materias = [];
        while ($row = $result->fetch_assoc()) {
            $materias[] = $row;
        }

        $stmt->close();
        return $materias;
        
    } catch (Exception $e) {
        error_log("Error al obtener materias: " . $e->getMessage());
        return [];
    }
}

// NUEVA FUNCIÓN: Obtener materias con notas del estudiante
function obtenerMateriasConNotas($id_estudiante, $id_carrera) {
    global $db;
    
    $sql = "SELECT DISTINCT m.*, cm.semestre
            FROM materias m
            INNER JOIN carrera_materia cm ON m.id_materia = cm.id_materia
            INNER JOIN notas_definitivas nd ON m.id_materia = nd.id_materia
            WHERE cm.id_carrera = ? 
            AND nd.id_usuario = ?
            AND (
                nd.trayecto_0 IS NOT NULL OR 
                nd.trayecto_1 IS NOT NULL OR 
                nd.trayecto_2 IS NOT NULL OR 
                nd.trayecto_3 IS NOT NULL OR 
                nd.trayecto_4 IS NOT NULL
            )
            ORDER BY m.trayecto, cm.semestre, m.nombre_materia";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $id_carrera, $id_estudiante);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $materias = [];
    while ($row = $result->fetch_assoc()) {
        $materias[] = $row;
    }
    
    $stmt->close();
    return $materias;
}

// FUNCIÓN: Obtener notas del estudiante para una materia específica
function obtenerNotasEstudianteMateria($id_estudiante, $id_materia) {
    global $db;
    
    $sql = "SELECT nd.*, pa.nombre_periodo 
            FROM notas_definitivas nd
            LEFT JOIN periodos_academicos pa ON nd.id_periodo = pa.id_periodo
            WHERE nd.id_usuario = ? AND nd.id_materia = ?
            ORDER BY pa.nombre_periodo DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $id_estudiante, $id_materia);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $notas = [];
    while ($row = $result->fetch_assoc()) {
        $notas[] = $row;
    }
    
    $stmt->close();
    return $notas;
}

// FUNCIÓN: Obtener historial de cambios de una nota - CORREGIDA
function obtenerHistorialCambiosNota($id_nota) {
    global $db;
    
    $sql = "SELECT h.*, u.nombre as admin_nombre 
            FROM historial_cambios_notas h 
            LEFT JOIN users u ON h.id_admin = u.id 
            WHERE h.id_nota = ? 
            ORDER BY h.fecha_cambio DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id_nota);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $historial = [];
    while ($row = $result->fetch_assoc()) {
        $historial[] = $row;
    }
    
    $stmt->close();
    return $historial;
}

// FUNCIÓN: Procesar edición de nota
function procesarEdicionNota() {
    global $db;
    
    if (!isset($_POST['id_nota']) || !isset($_POST['trayecto']) || !isset($_POST['nueva_nota'])) {
        return ['success' => false, 'message' => 'Datos incompletos'];
    }
    
    $id_nota = intval($_POST['id_nota']);
    $trayecto = $_POST['trayecto'];
    $nueva_nota = $_POST['nueva_nota'];
    $justificacion = trim($_POST['justificacion'] ?? '');
    
    // Obtener el ID del administrador de la sesión - CORREGIDO según la estructura del index
    if (isset($_SESSION['user']['id'])) {
        $id_admin = $_SESSION['user']['id'];
    } elseif (isset($_SESSION['id'])) {
        $id_admin = $_SESSION['id'];
    } elseif (isset($_SESSION['user_id'])) {
        $id_admin = $_SESSION['user_id'];
    } else {
        // Debug para ver qué hay en la sesión
        error_log("Session data: " . print_r($_SESSION, true));
        return ['success' => false, 'message' => 'No se pudo identificar al administrador. Sesión: ' . print_r($_SESSION, true)];
    }
    
    // Validar que la justificación no esté vacía
    if (empty($justificacion)) {
        return ['success' => false, 'message' => 'La justificación es obligatoria'];
    }
    
    // Validar que la nota sea numérica y esté entre 0 y 20
    if (!is_numeric($nueva_nota) || $nueva_nota < 0 || $nueva_nota > 20) {
        return ['success' => false, 'message' => 'La nota debe ser un número entre 0 y 20'];
    }
    
    // Obtener la nota actual
    $sql_actual = "SELECT trayecto_0, trayecto_1, trayecto_2, trayecto_3, trayecto_4 
                   FROM notas_definitivas WHERE id = ?";
    $stmt = $db->prepare($sql_actual);
    $stmt->bind_param("i", $id_nota);
    $stmt->execute();
    $result = $stmt->get_result();
    $nota_actual = $result->fetch_assoc();
    $stmt->close();
    
    if (!$nota_actual) {
        return ['success' => false, 'message' => 'No se encontró la nota a editar'];
    }
    
    // Determinar la nota anterior según el trayecto
    $nota_anterior = $nota_actual[$trayecto];
    
    // Iniciar transacción
    $db->begin_transaction();
    
    try {
        // 1. Actualizar la nota en notas_definitivas
        $sql_update = "UPDATE notas_definitivas SET {$trayecto} = ? WHERE id = ?";
        $stmt = $db->prepare($sql_update);
        $stmt->bind_param("di", $nueva_nota, $id_nota);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar la nota");
        }
        $stmt->close();
        
        // 2. Registrar el cambio en el historial
        $sql_historial = "INSERT INTO historial_cambios_notas 
                         (id_nota, trayecto, nota_anterior, nota_nueva, justificacion, id_admin, fecha_cambio) 
                         VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $db->prepare($sql_historial);
        $trayecto_numero = str_replace('trayecto_', '', $trayecto);
        $stmt->bind_param("isddsi", $id_nota, $trayecto_numero, $nota_anterior, $nueva_nota, $justificacion, $id_admin);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al registrar en el historial");
        }
        $stmt->close();
        
        // 3. REGISTRAR EN AUDITORÍA - EDICIÓN EXITOSA DE NOTA
        registrarAuditoria(
            "UPDATE", 
            "notas_definitivas", 
            $id_nota, 
            [
                $trayecto => $nota_anterior
            ], 
            [
                $trayecto => $nueva_nota,
                'justificacion' => $justificacion,
                'id_admin' => $id_admin,
                'trayecto_afectado' => $trayecto
            ], 
            "Notas", 
            "Edición exitosa de nota"
        );
        
        // Confirmar transacción
        $db->commit();
        
        return ['success' => true, 'message' => 'Nota actualizada correctamente'];
        
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $db->rollback();
        
        // REGISTRAR EN AUDITORÍA - ERROR EN EDICIÓN
        registrarAuditoria(
            "ERROR", 
            "notas_definitivas", 
            $id_nota, 
            null, 
            [
                'trayecto' => $trayecto,
                'nota_anterior' => $nota_anterior,
                'nueva_nota' => $nueva_nota,
                'justificacion' => $justificacion,
                'error' => $e->getMessage()
            ], 
            "Notas", 
            "Error en edición de nota"
        );
        
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }
}




//INSCRIPCION DE MATERIAS ***********************************************************************



/**
 * Obtiene las materias de un trayecto específico de una carrera
 * @param int $id_carrera ID de la carrera
 * @param int $trayecto Número del trayecto
 * @return array Materias del trayecto
 */
function obtenerMateriasTrayecto($id_carrera, $trayecto) {
    global $db;
    
    $sql = "SELECT m.* 
            FROM materias m
            INNER JOIN carrera_materia cm ON m.id_materia = cm.id_materia
            WHERE cm.id_carrera = ? AND m.trayecto = ? AND m.activa = 1
            ORDER BY cm.semestre ASC";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $id_carrera, $trayecto);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $materias = [];
    while ($row = $result->fetch_assoc()) {
        $materias[] = $row;
    }
    
    return $materias;
}

/**
 * Obtiene las materias aprobadas de un estudiante
 * @param int $id_usuario ID del usuario (id de users, no idusuario)
 * @param int $trayecto Número del trayecto (opcional)
 * @return array Materias aprobadas
 */
function obtenerMateriasAprobadas($id_usuario, $trayecto = null) {
    global $db;
    
    $sql = "SELECT nd.id_materia, m.nombre_materia, m.trayecto, 
                   CASE 
                     WHEN m.es_proyecto_socio = 1
                     THEN MAX(CASE 
                              WHEN nd.trayecto_0 >= 16 OR nd.trayecto_1 >= 16 OR nd.trayecto_2 >= 16 OR nd.trayecto_3 >= 16 OR nd.trayecto_4 >= 16 
                              THEN 1 ELSE 0 END)
                     ELSE MAX(CASE 
                              WHEN nd.trayecto_0 >= 12 OR nd.trayecto_1 >= 12 OR nd.trayecto_2 >= 12 OR nd.trayecto_3 >= 12 OR nd.trayecto_4 >= 12 
                              THEN 1 ELSE 0 END)
                   END as aprobada
            FROM notas_definitivas nd
            INNER JOIN materias m ON nd.id_materia = m.id_materia
            WHERE nd.id_usuario = ?";
    
    if ($trayecto !== null) {
        $sql .= " AND m.trayecto = ?";
    }
    
    $sql .= " GROUP BY nd.id_materia, m.nombre_materia, m.trayecto
              HAVING aprobada = 1";
    
    $stmt = $db->prepare($sql);
    
    if ($trayecto !== null) {
        $stmt->bind_param("ii", $id_usuario, $trayecto);
    } else {
        $stmt->bind_param("i", $id_usuario);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $aprobadas = [];
    while ($row = $result->fetch_assoc()) {
        $aprobadas[$row['id_materia']] = $row;
    }
    
    return $aprobadas;
}

/**
 * Obtiene las materias reprobadas de un estudiante
 * @param int $id_usuario ID del usuario (id de users, no idusuario)
 * @param int $trayecto Número del trayecto
 * @param int $id_carrera ID de la carrera
 * @return array Materias reprobadas
 */
function obtenerMateriasReprobadas($id_usuario, $trayecto, $id_carrera) {
    $materias_trayecto = obtenerMateriasTrayecto($id_carrera, $trayecto);
    $materias_aprobadas = obtenerMateriasAprobadas($id_usuario, $trayecto);
    
    $reprobadas = [];
    foreach ($materias_trayecto as $materia) {
        if (!isset($materias_aprobadas[$materia['id_materia']])) {
            $reprobadas[] = $materia;
        }
    }
    
    return $reprobadas;
}

/**
 * Verifica si el estudiante puede avanzar al siguiente trayecto
 * @param int $id_usuario ID del usuario (id de users, no idusuario)
 * @param int $trayecto_actual Trayecto actual
 * @param int $id_carrera ID de la carrera
 * @return array Resultado con estado y detalles
 */
function puedeAvanzarTrayecto($id_usuario, $trayecto_actual, $id_carrera) {
    // Verificar si ya existe un registro de control
    $control = obtenerControlAvance($id_usuario, $id_carrera, $trayecto_actual);
    
    if ($control && $control['puede_avanzar'] == 1) {
        // Ya fue aprobado para avanzar
        $info_aprobador = obtenerInfoUsuarioPorId($control['aprobado_por']);
        
        return [
            'puede_avanzar' => true,
            'aprobado_manualmente' => true,
            'cumple_requisitos' => true,
            'aprobado_por' => $control['aprobado_por'],
            'nombre_aprobador' => $info_aprobador ? $info_aprobador['nombre'] : 'Administrador',
            'fecha_aprobacion' => $control['fecha_aprobacion'],
            'motivo' => $control['motivo'],
            'detalles' => 'Aprobación manual previa',
            'control_id' => $control['id']
        ];
    }
    
    // Obtener todas las materias del trayecto actual
    $materias_trayecto = obtenerMateriasTrayecto($id_carrera, $trayecto_actual);
    $materias_aprobadas = obtenerMateriasAprobadas($id_usuario, $trayecto_actual);
    
    $total_materias = count($materias_trayecto);
    $total_aprobadas = count($materias_aprobadas);
    
    $resultado = [
        'puede_avanzar' => false,
        'aprobado_manualmente' => false,
        'cumple_requisitos' => false,
        'detalles' => '',
        'total_materias' => $total_materias,
        'total_aprobadas' => $total_aprobadas,
        'control_id' => null
    ];
    
    // Para trayecto 0: necesita 50% aprobado
    if ($trayecto_actual == 0) {
        $cumple_requisitos = ($total_aprobadas >= ceil($total_materias * 0.5));
        $resultado['puede_avanzar'] = $cumple_requisitos;
        $resultado['cumple_requisitos'] = $cumple_requisitos;
        $resultado['detalles'] = "Aprobado {$total_aprobadas} de {$total_materias} materias (" . ceil($total_materias * 0.5) . " mínimas)";
        $resultado['porcentaje_aprobado'] = $total_materias > 0 ? ($total_aprobadas / $total_materias) * 100 : 0;
        $resultado['minimo_requerido'] = ceil($total_materias * 0.5);
    }
    
    // Para trayecto 1 a 2 y 3 a 4: necesita aprobar proyecto socio integrador
    elseif ($trayecto_actual == 1 || $trayecto_actual == 3) {
        $aprobado_proyecto = haAprobadoProyectoSocio($id_usuario, $trayecto_actual);
        $resultado['puede_avanzar'] = $aprobado_proyecto;
        $resultado['cumple_requisitos'] = $aprobado_proyecto;
        $resultado['detalles'] = $aprobado_proyecto ? 
            "Proyecto Socio Integrador aprobado (nota ≥ 16)" : 
            "Proyecto Socio Integrador no aprobado (nota < 16)";
        $resultado['proyecto_aprobado'] = $aprobado_proyecto;
    }
    
    // Para trayecto 2 a 3: necesita aprobar todas las materias y obtener título
    elseif ($trayecto_actual == 2) {
        $todas_aprobadas = ($total_aprobadas == $total_materias);
        $tiene_titulo = tienePrimerTitulo($id_usuario);
        $cumple_requisitos = ($todas_aprobadas && $tiene_titulo);
        
        $resultado['puede_avanzar'] = $cumple_requisitos;
        $resultado['cumple_requisitos'] = $cumple_requisitos;
        $resultado['detalles'] = $todas_aprobadas ? 
            "Todas las materias aprobadas" . ($tiene_titulo ? " y título obtenido" : " pero falta título") :
            "Faltan materias por aprobar ({$total_aprobadas}/{$total_materias})";
        $resultado['todas_aprobadas'] = $todas_aprobadas;
        $resultado['tiene_titulo'] = $tiene_titulo;
    }
    
    return $resultado;
}

/**
 * Obtiene el control de avance de trayecto
 * @param int $id_usuario ID del usuario
 * @param int $id_carrera ID de la carrera
 * @param int $trayecto Trayecto actual
 * @return array|false Información del control o false si no existe
 */
function obtenerControlAvance($id_usuario, $id_carrera, $trayecto) {
    global $db;
    
    $sql = "SELECT * FROM control_avance_trayecto 
            WHERE id_usuario = ? 
            AND id_carrera = ? 
            AND trayecto_actual = ?
            ORDER BY created_at DESC LIMIT 1";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("iii", $id_usuario, $id_carrera, $trayecto);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}



/**
 * Rechaza o elimina la aprobación de avance
 * @param int $id_usuario ID del usuario
 * @param int $id_carrera ID de la carrera
 * @param int $trayecto_actual Trayecto actual
 * @return bool True si éxito, False si error
 */
function rechazarAvanceTrayecto($id_usuario, $id_carrera, $trayecto_actual) {
    global $db;
    
    $sql = "DELETE FROM control_avance_trayecto 
            WHERE id_usuario = ? 
            AND id_carrera = ? 
            AND trayecto_actual = ?";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("iii", $id_usuario, $id_carrera, $trayecto_actual);
    
    return $stmt->execute();
}

/**
 * Obtiene información de usuario por ID
 * @param int $id_usuario ID del usuario
 * @return array Información del usuario
 */
function obtenerInfoUsuarioPorId($id_usuario) {
    global $db;
    
    if ($id_usuario <= 0) {
        return null;
    }
    
    $sql = "SELECT id, nombre FROM users WHERE id = ?";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

/**
 * Verifica si el estudiante aprobó el proyecto socio integrador
 * @param int $id_usuario ID del usuario (id de users, no idusuario)
 * @param int $trayecto Trayecto del proyecto
 * @return bool True si aprobó, False si no
 */
function haAprobadoProyectoSocio($id_usuario, $trayecto) {
    global $db;
    
    $sql = "SELECT nd.* 
            FROM notas_definitivas nd
            INNER JOIN materias m ON nd.id_materia = m.id_materia
            WHERE nd.id_usuario = ? 
            AND m.trayecto = ?
            AND m.es_proyecto_socio = 1
            AND (
                (m.trayecto = 1 AND nd.trayecto_1 >= 16) OR
                (m.trayecto = 3 AND nd.trayecto_3 >= 16)
            )";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $trayecto);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0;
}

/**
 * Verifica si el estudiante tiene el primer título de la carrera
 * @param int $id_usuario ID del usuario (id de users, no idusuario)
 * @return bool True si tiene título, False si no
 */
function tienePrimerTitulo($id_usuario) {
    global $db;
    
    $sql = "SELECT titulos FROM users WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return !empty(trim($row['titulos']));
    }
    
    return false;
}

/**
 * Obtiene el trayecto actual REAL del estudiante basado en sus notas
 * @param int $id_usuario ID del usuario (id de users, no idusuario)
 * @param int $id_carrera ID de la carrera
 * @return int Trayecto actual (0, 1, 2, 3, 4)
 */
function obtenerTrayectoActualReal($id_usuario, $id_carrera) {
    global $db;
    
    // Primero, verificar si el estudiante tiene notas registradas
    $sql = "SELECT MAX(m.trayecto) as max_trayecto_con_notas
            FROM notas_definitivas nd
            INNER JOIN materias m ON nd.id_materia = m.id_materia
            WHERE nd.id_usuario = ? 
            AND m.id_materia IN (
                SELECT cm.id_materia 
                FROM carrera_materia cm 
                WHERE cm.id_carrera = ?
            )";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $id_carrera);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $row = $result->fetch_assoc();
    $max_trayecto_con_notas = $row['max_trayecto_con_notas'] ?? -1;
    
    // Si no tiene notas, empezar desde trayecto 0
    if ($max_trayecto_con_notas === -1 || $max_trayecto_con_notas === null) {
        return 0;
    }
    
    // Ahora determinar en qué trayecto está realmente
    for ($trayecto = $max_trayecto_con_notas; $trayecto >= 0; $trayecto--) {
        // Verificar si puede avanzar desde este trayecto
        $resultado = puedeAvanzarTrayecto($id_usuario, $trayecto, $id_carrera);
        if ($resultado['puede_avanzar']) {
            // Si puede avanzar, está en el siguiente trayecto
            return $trayecto + 1;
        }
    }
    
    // Si no puede avanzar desde ningún trayecto, está en el último con notas
    return $max_trayecto_con_notas;
}

/**
 * Obtiene las materias en las que el estudiante está actualmente inscrito
 * VERSIÓN PARA TU ESTRUCTURA DE TABLAS
 * @param int $id_usuario ID del usuario
 * @return array Materias inscritas
 */
function obtenerMateriasInscritas($id_usuario) {
    global $db;
    
    // Primero, obtener el período activo
    $periodo_activo = obtenerPeriodoActivo();
    if (!$periodo_activo) {
        return [];
    }
    
    // Consulta para obtener materias inscritas en el período activo
    $sql = "SELECT DISTINCT m.*, s.codigo_seccion, nd.id_periodo,
                   CASE 
                     WHEN nd.trayecto_0 IS NOT NULL THEN nd.trayecto_0
                     WHEN nd.trayecto_1 IS NOT NULL THEN nd.trayecto_1
                     WHEN nd.trayecto_2 IS NOT NULL THEN nd.trayecto_2
                     WHEN nd.trayecto_3 IS NOT NULL THEN nd.trayecto_3
                     WHEN nd.trayecto_4 IS NOT NULL THEN nd.trayecto_4
                     ELSE NULL
                   END as nota_actual
            FROM notas_definitivas nd
            INNER JOIN materias m ON nd.id_materia = m.id_materia
            LEFT JOIN carrera_materia cm ON m.id_materia = cm.id_materia
            LEFT JOIN secciones s ON cm.id_carrera = s.id_carrera AND s.id_periodo = nd.id_periodo
            LEFT JOIN estudiante_seccion es ON nd.id_usuario = es.id_usuario AND s.id_seccion = es.id_seccion
            WHERE nd.id_usuario = ?
            AND nd.id_periodo = ?
            AND es.estatus = 'Activo'
            ORDER BY m.trayecto, m.nombre_materia";
    
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        error_log("Error preparando consulta: " . $db->error);
        return [];
    }
    
    $stmt->bind_param("ii", $id_usuario, $periodo_activo['id_periodo']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $materias_inscritas = [];
    while ($row = $result->fetch_assoc()) {
        $materias_inscritas[] = $row;
    }
    
    return $materias_inscritas;
}

/**
 * Obtiene el historial de secciones del estudiante
 * @param int $id_usuario ID del usuario
 * @return array Historial de secciones
 */
function obtenerHistorialSecciones($id_usuario) {
    global $db;
    
    $sql = "SELECT es.*, s.codigo_seccion, s.id_trayecto, s.id_periodo, p.nombre_periodo
            FROM estudiante_seccion es
            INNER JOIN secciones s ON es.id_seccion = s.id_seccion
            INNER JOIN periodos_academicos p ON s.id_periodo = p.id_periodo
            WHERE es.id_usuario = ?
            ORDER BY p.fecha_inicio DESC, es.fecha_inscripcion DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $historial = [];
    while ($row = $result->fetch_assoc()) {
        $historial[] = $row;
    }
    
    return $historial;
}

/**
 * Verifica si el estudiante ya está inscrito en el período actual
 * @param int $id_usuario ID del usuario
 * @return bool True si ya está inscrito
 */
function estaInscritoEnPeriodo($id_usuario) {
    global $db;
    
    $periodo_activo = obtenerPeriodoActivo();
    if (!$periodo_activo) {
        return false;
    }
    
    $sql = "SELECT COUNT(*) as total
            FROM estudiante_seccion es
            INNER JOIN secciones s ON es.id_seccion = s.id_seccion
            WHERE es.id_usuario = ? AND s.id_periodo = ?";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $periodo_activo['id_periodo']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['total'] > 0;
}

/**
 * Obtiene el último período en el que el estudiante estuvo inscrito
 * @param int $id_usuario ID del usuario
 * @return array Información del último período
 */
function obtenerUltimoPeriodoInscrito($id_usuario) {
    global $db;
    
    $sql = "SELECT p.*
            FROM periodos_academicos p
            INNER JOIN secciones s ON p.id_periodo = s.id_periodo
            INNER JOIN estudiante_seccion es ON s.id_seccion = es.id_seccion
            WHERE es.id_usuario = ?
            ORDER BY p.fecha_inicio DESC
            LIMIT 1";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

/**
 * Determina si el estudiante es nuevo (sin historial académico)
 * @param int $id_usuario ID del usuario
 * @return bool True si es nuevo
 */
function esEstudianteNuevo($id_usuario) {
    global $db;
    
    // Verificar si tiene notas registradas
    $sql_notas = "SELECT COUNT(*) as total FROM notas_definitivas WHERE id_usuario = ?";
    $stmt_notas = $db->prepare($sql_notas);
    $stmt_notas->bind_param("i", $id_usuario);
    $stmt_notas->execute();
    $result_notas = $stmt_notas->get_result();
    $row_notas = $result_notas->fetch_assoc();
    
    // Verificar si tiene inscripciones en secciones
    $sql_inscripciones = "SELECT COUNT(*) as total FROM estudiante_seccion WHERE id_usuario = ?";
    $stmt_inscripciones = $db->prepare($sql_inscripciones);
    $stmt_inscripciones->bind_param("i", $id_usuario);
    $stmt_inscripciones->execute();
    $result_inscripciones = $stmt_inscripciones->get_result();
    $row_inscripciones = $result_inscripciones->fetch_assoc();
    
    return ($row_notas['total'] == 0 && $row_inscripciones['total'] == 0);
}

/**
 * Inscribe al estudiante en materias (sección opcional)
 * @param int $id_usuario ID del usuario (id de users, no idusuario)
 * @param int $id_seccion ID de la sección (opcional, 0 si no hay)
 * @param array $materias_ids IDs de las materias a inscribir
 * @return bool True si éxito, False si error
 */
function inscribirMateriasEstudiante($id_usuario, $id_seccion, $materias_ids) {
    global $db;
    
    if (empty($materias_ids)) {
        error_log("Error: No se proporcionaron materias para inscribir.");
        return false;
    }
    
    // Obtener el período activo para verificar
    $periodo_activo = obtenerPeriodoActivo();
    if (!$periodo_activo) {
        error_log("Error: No hay período académico activo.");
        return false;
    }
    
    // Obtener información del estudiante para saber su carrera y trayecto
    $info_estudiante = obtenerInfoEstudiantePorId($id_usuario);
    $id_carrera = $info_estudiante['carrera'] ?? $info_estudiante['id_carrera'] ?? 0;
    $trayecto_actual = obtenerTrayectoActual($id_usuario, $id_carrera);
    
    if ($id_carrera <= 0) {
        error_log("Error: El estudiante no tiene carrera asignada.");
        return false;
    }
    
    // Iniciar transacción
    $db->begin_transaction();
    
    try {
        // 1. Si se proporcionó una sección VÁLIDA (mayor a 0), inscribir al estudiante en ella
        if ($id_seccion > 0) {
            // Obtener información de la sección
            $sql_seccion = "SELECT * FROM secciones WHERE id_seccion = ?";
            $stmt_seccion = $db->prepare($sql_seccion);
            $stmt_seccion->bind_param("i", $id_seccion);
            $stmt_seccion->execute();
            $result_seccion = $stmt_seccion->get_result();
            $seccion = $result_seccion->fetch_assoc();
            
            if (!$seccion) {
                throw new Exception("Sección no encontrada - ID: $id_seccion");
            }
            
            // Verificar que la sección pertenezca al período activo
            if ($seccion['id_periodo'] != $periodo_activo['id_periodo']) {
                throw new Exception("La sección no pertenece al período activo.");
            }
            
            // Verificar capacidad de la sección
            if ($seccion['capacidad_maxima'] > 0) {
                $sql_capacidad = "SELECT COUNT(*) as total_inscritos 
                                 FROM estudiante_seccion 
                                 WHERE id_seccion = ? AND estatus = 'Activo'";
                $stmt_capacidad = $db->prepare($sql_capacidad);
                $stmt_capacidad->bind_param("i", $id_seccion);
                $stmt_capacidad->execute();
                $result_capacidad = $stmt_capacidad->get_result();
                $row_capacidad = $result_capacidad->fetch_assoc();
                
                if ($row_capacidad['total_inscritos'] >= $seccion['capacidad_maxima']) {
                    throw new Exception("La sección ha alcanzado su capacidad máxima.");
                }
            }
            
            // Verificar si el estudiante ya está inscrito en esta sección
            $sql_check_seccion = "SELECT * FROM estudiante_seccion WHERE id_usuario = ? AND id_seccion = ?";
            $stmt_check_seccion = $db->prepare($sql_check_seccion);
            $stmt_check_seccion->bind_param("ii", $id_usuario, $id_seccion);
            $stmt_check_seccion->execute();
            $result_check_seccion = $stmt_check_seccion->get_result();
            
            if ($result_check_seccion->num_rows == 0) {
                // Inscribir al estudiante en la sección
                $sql_inscripcion_seccion = "INSERT INTO estudiante_seccion (id_usuario, id_seccion, fecha_inscripcion, estatus) 
                                           VALUES (?, ?, NOW(), 'Activo')";
                $stmt_inscripcion_seccion = $db->prepare($sql_inscripcion_seccion);
                $stmt_inscripcion_seccion->bind_param("ii", $id_usuario, $id_seccion);
                
                if (!$stmt_inscripcion_seccion->execute()) {
                    throw new Exception("Error al inscribir estudiante en sección: " . $stmt_inscripcion_seccion->error);
                }
            }
            
            $trayecto_seccion = $seccion['id_trayecto'];
        } else {
            // No hay sección seleccionada, usar el trayecto actual del estudiante
            $trayecto_seccion = $trayecto_actual;
        }
        
        // 2. Verificar que las materias pertenezcan al trayecto correcto
        $materias_validadas = [];
        foreach ($materias_ids as $id_materia) {
            $sql_materia = "SELECT m.* 
                           FROM materias m
                           INNER JOIN carrera_materia cm ON m.id_materia = cm.id_materia
                           WHERE m.id_materia = ? 
                           AND cm.id_carrera = ? 
                           AND m.trayecto = ?
                           AND m.activa = 1";
            
            $stmt_materia = $db->prepare($sql_materia);
            $stmt_materia->bind_param("iii", $id_materia, $id_carrera, $trayecto_seccion);
            $stmt_materia->execute();
            $result_materia = $stmt_materia->get_result();
            
            if ($result_materia->num_rows > 0) {
                $materia_data = $result_materia->fetch_assoc();
                $materias_validadas[$id_materia] = $materia_data;
            } else {
                // Intentar buscar sin validar trayecto (por si hay inconsistencia)
                $sql_materia2 = "SELECT m.* 
                               FROM materias m
                               INNER JOIN carrera_materia cm ON m.id_materia = cm.id_materia
                               WHERE m.id_materia = ? 
                               AND cm.id_carrera = ? 
                               AND m.activa = 1";
                
                $stmt_materia2 = $db->prepare($sql_materia2);
                $stmt_materia2->bind_param("ii", $id_materia, $id_carrera);
                $stmt_materia2->execute();
                $result_materia2 = $stmt_materia2->get_result();
                
                if ($result_materia2->num_rows > 0) {
                    $materia_data = $result_materia2->fetch_assoc();
                    // Verificar que la materia sea del trayecto correcto
                    if ($materia_data['trayecto'] == $trayecto_seccion) {
                        $materias_validadas[$id_materia] = $materia_data;
                    } else {
                        error_log("Advertencia: Materia ID $id_materia es del trayecto {$materia_data['trayecto']} pero se esperaba $trayecto_seccion.");
                    }
                } else {
                    error_log("Advertencia: Materia ID $id_materia no pertenece a la carrera $id_carrera o no está activa.");
                }
            }
        }
        
        if (empty($materias_validadas)) {
            throw new Exception("No hay materias válidas para inscribir.");
        }
        
        // 3. Inscribir cada materia en notas_definitivas
        $inscripciones_exitosas = 0;
        
        foreach ($materias_validadas as $id_materia => $materia_data) {
            // Verificar si ya está inscrito en esta materia en el mismo período
            $sql_check_materia = "SELECT * FROM notas_definitivas 
                                 WHERE id_usuario = ? 
                                 AND id_materia = ? 
                                 AND id_periodo = ?";
            $stmt_check_materia = $db->prepare($sql_check_materia);
            $stmt_check_materia->bind_param("iii", $id_usuario, $id_materia, $periodo_activo['id_periodo']);
            $stmt_check_materia->execute();
            $result_check_materia = $stmt_check_materia->get_result();
            
            if ($result_check_materia->num_rows == 0) {
                // Crear registro en notas_definitivas
                $columna_trayecto = "trayecto_" . $materia_data['trayecto'];
                
                $sql_notas = "INSERT INTO notas_definitivas 
                             (id_usuario, id_materia, id_periodo, fecha_registro, $columna_trayecto) 
                             VALUES (?, ?, ?, NOW(), NULL)";
                $stmt_notas = $db->prepare($sql_notas);
                $stmt_notas->bind_param("iii", $id_usuario, $id_materia, $periodo_activo['id_periodo']);
                
                if (!$stmt_notas->execute()) {
                    throw new Exception("Error al crear registro de notas para materia ID $id_materia: " . $stmt_notas->error);
                }
                
                $inscripciones_exitosas++;
                
                error_log("✅ Estudiante ID $id_usuario inscrito en materia ID $id_materia (Trayecto {$materia_data['trayecto']}) para período ID " . $periodo_activo['id_periodo']);
            } else {
                error_log("ℹ️ Advertencia: Estudiante ID $id_usuario ya está inscrito en materia ID $id_materia para este período.");
            }
        }
        
        // 4. Confirmar transacción
        $db->commit();
        
        error_log("✅ Inscripción exitosa: Estudiante ID $id_usuario inscrito con $inscripciones_exitosas materias." . ($id_seccion > 0 ? " Sección: $id_seccion" : " Sin sección asignada"));
        
        return true;
        
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $db->rollback();
        error_log("❌ Error en inscripción: " . $e->getMessage());
        return false;
    }
}

/**
 * Obtiene información completa del estudiante por ID
 * @param int $id_usuario ID del usuario (id de users, no idusuario)
 * @return array Información del estudiante
 */
function obtenerInfoEstudiantePorId($id_usuario) {
    global $db;
    
    $sql = "SELECT u.*, c.nombre_carrera, c.id_carrera
            FROM users u
            LEFT JOIN carreras c ON u.carrera = c.id_carrera
            WHERE u.id = ? AND u.estudiante = 1";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

/**
 * Obtiene las secciones disponibles para un trayecto
 * @param int $id_carrera ID de la carrera
 * @param int $trayecto Trayecto
 * @param int $id_periodo ID del período académico
 * @return array Secciones disponibles
 */
function obtenerSeccionesTrayecto($id_carrera, $trayecto, $id_periodo) {
    global $db;
    
    $sql = "SELECT s.* 
            FROM secciones s
            WHERE s.id_carrera = ? 
            AND s.id_trayecto = ? 
            AND s.id_periodo = ?
            AND s.estatus = 'Activa'
            AND (s.capacidad_maxima IS NULL OR 
                s.capacidad_maxima > (SELECT COUNT(*) FROM estudiante_seccion es WHERE es.id_seccion = s.id_seccion))";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("iii", $id_carrera, $trayecto, $id_periodo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $secciones = [];
    while ($row = $result->fetch_assoc()) {
        $secciones[] = $row;
    }
    
    return $secciones;
}

/**
 * Obtiene el período académico activo
 * @return array Información del período activo
 */
function obtenerPeriodoActivo() {
    global $db;
    
    $sql = "SELECT * FROM periodos_academicos WHERE activo = 1 ORDER BY fecha_inicio DESC LIMIT 1";
    $result = $db->query($sql);
    
    return $result->fetch_assoc();
}

/**
 * Obtiene si una materia es proyecto socio integrador
 * @param int $id_materia ID de la materia
 * @return bool True si es proyecto socio, False si no
 */
function esProyectoSocio($id_materia) {
    global $db;
    
    $sql = "SELECT es_proyecto_socio FROM materias WHERE id_materia = ?";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id_materia);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return $row['es_proyecto_socio'] == 1;
    }
    
    return false;
}

/**
 * Obtiene la nota mínima requerida para una materia
 * @param int $id_materia ID de la materia
 * @return int Nota mínima requerida
 */
function obtenerNotaMinimaMateria($id_materia) {
    if (esProyectoSocio($id_materia)) {
        return 16;
    }
    return 12;
}

/**
 * Script para marcar materias como Proyecto Socio Integrador
 * Ejecutar una sola vez después de agregar el campo
 */
function marcarProyectosSocio() {
    global $db;
    
    $palabras_clave = [
        'proyecto socio integrador',
        'proyecto sociointegrador',
        'proyecto integrador',
        'socio integrador',
        'proyecto socio',
        'sociointegrador',
        'trabajo especial de grado',
        'proyecto final',
        'trabajo de grado'
    ];
    
    $total_actualizadas = 0;
    
    foreach ($palabras_clave as $palabra) {
        $sql = "UPDATE materias 
                SET es_proyecto_socio = 1 
                WHERE LOWER(nombre_materia) LIKE ? 
                AND es_proyecto_socio = 0";
        
        $busqueda = "%" . $palabra . "%";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $busqueda);
        $stmt->execute();
        
        $total_actualizadas += $stmt->affected_rows;
    }
    
    $codigos_proyecto = ['PSI', 'PROYSOC', 'TEG', 'PROYECTO'];
    
    foreach ($codigos_proyecto as $codigo) {
        $sql = "UPDATE materias 
                SET es_proyecto_socio = 1 
                WHERE UPPER(cod_materia) LIKE ? 
                AND es_proyecto_socio = 0";
        
        $busqueda = "%" . $codigo . "%";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $busqueda);
        $stmt->execute();
        
        $total_actualizadas += $stmt->affected_rows;
    }
    
    return $total_actualizadas;
}


/**
 * Obtiene el trayecto actual del estudiante
 * @param int $id_usuario ID del usuario (id de users, no idusuario)
 * @param int $id_carrera ID de la carrera
 * @return int Trayecto actual (0, 1, 2, 3, 4)
 */
function obtenerTrayectoActual($id_usuario, $id_carrera) {
    global $db;
    
    // Verificar si existe un registro en control_avance_trayecto
    $sql = "SELECT MAX(trayecto_actual) as max_trayecto_aprobado
            FROM control_avance_trayecto 
            WHERE id_usuario = ? 
            AND id_carrera = ?
            AND puede_avanzar = 1";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $id_carrera);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Si tiene un trayecto aprobado, está en el siguiente
    if ($row && $row['max_trayecto_aprobado'] !== null) {
        return $row['max_trayecto_aprobado'] + 1;
    }
    
    // Si no tiene aprobaciones, está en trayecto 0
    return 0;
}

/**
 * Verifica si el estudiante cumple requisitos para avanzar de trayecto
 * @param int $id_usuario ID del usuario (id de users, no idusuario)
 * @param int $trayecto_actual Trayecto actual
 * @param int $id_carrera ID de la carrera
 * @return array Resultado con estado y detalles
 */
function verificarRequisitosTrayecto($id_usuario, $trayecto_actual, $id_carrera) {
    // Obtener todas las materias del trayecto actual
    $materias_trayecto = obtenerMateriasTrayecto($id_carrera, $trayecto_actual);
    $materias_aprobadas = obtenerMateriasAprobadas($id_usuario, $trayecto_actual);
    
    $total_materias = count($materias_trayecto);
    $total_aprobadas = count($materias_aprobadas);
    
    $resultado = [
        'cumple_requisitos' => false,
        'detalles' => '',
        'total_materias' => $total_materias,
        'total_aprobadas' => $total_aprobadas,
        'materias_aprobadas' => $materias_aprobadas,
        'materias_trayecto' => $materias_trayecto
    ];
    
    // Para trayecto 0: necesita 50% aprobado
    if ($trayecto_actual == 0) {
        $minimo_requerido = ceil($total_materias * 0.5);
        $cumple_requisitos = ($total_aprobadas >= $minimo_requerido);
        
        $resultado['cumple_requisitos'] = $cumple_requisitos;
        $resultado['minimo_requerido'] = $minimo_requerido;
        $resultado['detalles'] = "Aprobado {$total_aprobadas} de {$total_materias} materias (mínimo requerido: {$minimo_requerido})";
        $resultado['porcentaje_aprobado'] = $total_materias > 0 ? ($total_aprobadas / $total_materias) * 100 : 0;
    }
    
    // Para trayecto 1 y 3: necesita aprobar proyecto socio integrador
    elseif ($trayecto_actual == 1 || $trayecto_actual == 3) {
        $aprobado_proyecto = haAprobadoProyectoSocio($id_usuario, $trayecto_actual);
        
        $resultado['cumple_requisitos'] = $aprobado_proyecto;
        $resultado['detalles'] = $aprobado_proyecto ? 
            "Proyecto Socio Integrador aprobado (nota ≥ 16)" : 
            "Proyecto Socio Integrador no aprobado (nota < 16)";
        $resultado['proyecto_aprobado'] = $aprobado_proyecto;
    }
    
    // Para trayecto 2: necesita aprobar todas las materias y obtener título
    elseif ($trayecto_actual == 2) {
        $todas_aprobadas = ($total_aprobadas == $total_materias);
        $tiene_titulo = tienePrimerTitulo($id_usuario);
        $cumple_requisitos = ($todas_aprobadas && $tiene_titulo);
        
        $resultado['cumple_requisitos'] = $cumple_requisitos;
        $resultado['detalles'] = $todas_aprobadas ? 
            "Todas las materias aprobadas" . ($tiene_titulo ? " y título obtenido" : " pero falta título") :
            "Faltan materias por aprobar ({$total_aprobadas}/{$total_materias})";
        $resultado['todas_aprobadas'] = $todas_aprobadas;
        $resultado['tiene_titulo'] = $tiene_titulo;
    }
    
    // Para trayecto 4: es el último, no puede avanzar
    elseif ($trayecto_actual == 4) {
        $resultado['cumple_requisitos'] = false;
        $resultado['detalles'] = "Último trayecto, no puede avanzar más";
    }
    
    return $resultado;
}

/**
 * Verifica si ya existe una aprobación para avanzar de trayecto
 * @param int $id_usuario ID del usuario
 * @param int $id_carrera ID de la carrera
 * @param int $trayecto_actual Trayecto actual
 * @return array|false Información de la aprobación o false si no existe
 */
function verificarAprobacionExistente($id_usuario, $id_carrera, $trayecto_actual) {
    global $db;
    
    $sql = "SELECT * FROM control_avance_trayecto 
            WHERE id_usuario = ? 
            AND id_carrera = ? 
            AND trayecto_actual = ?
            AND puede_avanzar = 1
            ORDER BY created_at DESC LIMIT 1";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("iii", $id_usuario, $id_carrera, $trayecto_actual);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $aprobacion = $result->fetch_assoc();
    
    if ($aprobacion) {
        // Obtener información del aprobador
        $info_aprobador = obtenerInfoUsuarioPorId($aprobacion['aprobado_por']);
        $aprobacion['nombre_aprobador'] = $info_aprobador ? $info_aprobador['nombre'] : 'Administrador';
        return $aprobacion;
    }
    
    return false;
}

/**
 * Obtiene el historial de aprobaciones de trayecto
 * @param int $id_usuario ID del usuario
 * @param int $id_carrera ID de la carrera
 * @return array Historial de aprobaciones
 */
function obtenerHistorialAprobaciones($id_usuario, $id_carrera) {
    global $db;
    
    $sql = "SELECT cat.*, u.nombre as nombre_aprobador
            FROM control_avance_trayecto cat
            LEFT JOIN users u ON cat.aprobado_por = u.id
            WHERE cat.id_usuario = ? 
            AND cat.id_carrera = ?
            AND cat.puede_avanzar = 1
            ORDER BY cat.trayecto_actual ASC, cat.fecha_aprobacion ASC";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $id_carrera);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $historial = [];
    while ($row = $result->fetch_assoc()) {
        $historial[] = $row;
    }
    
    return $historial;
}

/**
 * Aprueba el avance de trayecto manualmente
 * @param int $id_usuario ID del usuario
 * @param int $id_carrera ID de la carrera
 * @param int $trayecto_actual Trayecto actual
 * @param string $motivo Motivo de la aprobación
 * @return array Resultado de la operación
 */
function aprobarAvanceTrayecto($id_usuario, $id_carrera, $trayecto_actual, $motivo = '') {
    global $db;
    
    // Verificar que el trayecto no sea el último (4)
    if ($trayecto_actual >= 4) {
        return [
            'success' => false,
            'message' => 'No se puede aprobar avance desde el último trayecto (4)'
        ];
    }
    
    // Obtener ID del administrador actual desde la sesión
    // Primero, asegurar que la sesión esté iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $aprobado_por = 0;
    
    // Buscar el ID del usuario actual de diferentes maneras
    if (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] > 0) {
        $aprobado_por = $_SESSION['user']['id'];
    } elseif (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        $username = $_SESSION['username'];
        
        $sql_usuario = "SELECT id FROM users WHERE username = ? LIMIT 1";
        $stmt_usuario = $db->prepare($sql_usuario);
        $stmt_usuario->bind_param("s", $username);
        $stmt_usuario->execute();
        $result_usuario = $stmt_usuario->get_result();
        
        if ($row_usuario = $result_usuario->fetch_assoc()) {
            $aprobado_por = $row_usuario['id'];
        }
    } elseif (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
        $email = $_SESSION['email'];
        
        $sql_email = "SELECT id FROM users WHERE email = ? LIMIT 1";
        $stmt_email = $db->prepare($sql_email);
        $stmt_email->bind_param("s", $email);
        $stmt_email->execute();
        $result_email = $stmt_email->get_result();
        
        if ($row_email = $result_email->fetch_assoc()) {
            $aprobado_por = $row_email['id'];
        }
    } elseif (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
        $aprobado_por = $_SESSION['user_id'];
    } elseif (isset($_SESSION['id']) && $_SESSION['id'] > 0) {
        $aprobado_por = $_SESSION['id'];
    } elseif (isset($_SESSION['idusuario']) && $_SESSION['idusuario'] > 0) {
        $aprobado_por = $_SESSION['idusuario'];
    }
    
    // Si no encontramos el ID, usar un valor por defecto
    if ($aprobado_por <= 0) {
        // Puedes usar un ID de administrador por defecto o registrar como "sistema"
        $aprobado_por = 1; // Cambia esto por el ID de un administrador por defecto si es necesario
    }
    
    // CORRECCIÓN: Ajustar la consulta SQL para tener los placeholders correctos
    $sql = "INSERT INTO control_avance_trayecto 
            (id_usuario, id_carrera, trayecto_actual, puede_avanzar, aprobado_por, fecha_aprobacion, motivo, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW(), ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE 
            puede_avanzar = VALUES(puede_avanzar),
            aprobado_por = VALUES(aprobado_por),
            fecha_aprobacion = VALUES(fecha_aprobacion),
            motivo = VALUES(motivo),
            updated_at = NOW()";
    
    $stmt = $db->prepare($sql);
    
    // CORRECCIÓN: Agregar el valor para 'puede_avanzar' (siempre 1 para aprobar)
    $puede_avanzar = 1;
    
    // Ahora tenemos 6 variables: i, i, i, i, i, s
    $stmt->bind_param("iiiiis", $id_usuario, $id_carrera, $trayecto_actual, $puede_avanzar, $aprobado_por, $motivo);
    
    if ($stmt->execute()) {
        return [
            'success' => true,
            'message' => 'Avance de trayecto aprobado exitosamente'
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Error al aprobar avance: ' . $stmt->error
        ];
    }
}

/**
 * Obtiene el ID del usuario actual de sesión
 * @return int ID del usuario o 0 si no está definido
 */
function obtenerUsuarioActualId() {
    global $db;
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Si tenemos un username en sesión, buscar el ID en la base de datos
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        $username = $_SESSION['username'];
        
        $sql = "SELECT id FROM users WHERE username = ? LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row['id'];
        }
    }
    
    // Si tenemos email en sesión, buscar el ID
    if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
        $email = $_SESSION['email'];
        
        $sql = "SELECT id FROM users WHERE email = ? LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row['id'];
        }
    }
    
    // Intentar obtener directamente de variables de sesión
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
        return $_SESSION['user_id'];
    } elseif (isset($_SESSION['id']) && $_SESSION['id'] > 0) {
        return $_SESSION['id'];
    } elseif (isset($_SESSION['idusuario']) && $_SESSION['idusuario'] > 0) {
        return $_SESSION['idusuario'];
    }
    
    return 0;
}




/**
 * Aprueba el avance de trayecto manualmente con ID de aprobador
 * @param int $id_usuario ID del usuario
 * @param int $id_carrera ID de la carrera
 * @param int $trayecto_actual Trayecto actual
 * @param int $aprobado_por ID del usuario que aprueba
 * @param string $motivo Motivo de la aprobación
 * @return array Resultado de la operación
 */
function aprobarAvanceTrayectoConAprobador($id_usuario, $id_carrera, $trayecto_actual, $aprobado_por, $motivo = '') {
    global $db;
    
    // Verificar que el trayecto no sea el último (4)
    if ($trayecto_actual >= 4) {
        return [
            'success' => false,
            'message' => 'No se puede aprobar avance desde el último trayecto (4)'
        ];
    }
    
    // CORRECCIÓN: Ajustar la consulta SQL
    $sql = "INSERT INTO control_avance_trayecto 
            (id_usuario, id_carrera, trayecto_actual, puede_avanzar, aprobado_por, fecha_aprobacion, motivo, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW(), ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE 
            puede_avanzar = VALUES(puede_avanzar),
            aprobado_por = VALUES(aprobado_por),
            fecha_aprobacion = VALUES(fecha_aprobacion),
            motivo = VALUES(motivo),
            updated_at = NOW()";
    
    $stmt = $db->prepare($sql);
    
    // CORRECCIÓN: Agregar el valor para 'puede_avanzar'
    $puede_avanzar = 1;
    
    // 6 variables: i, i, i, i, i, s
    $stmt->bind_param("iiiiis", $id_usuario, $id_carrera, $trayecto_actual, $puede_avanzar, $aprobado_por, $motivo);
    
    if ($stmt->execute()) {
        return [
            'success' => true,
            'message' => 'Avance de trayecto aprobado exitosamente'
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Error al aprobar avance: ' . $stmt->error
        ];
    }
}




/**
 * Verifica si una materia ya está inscrita para el estudiante en el período actual
 * @param int $id_usuario ID del usuario
 * @param int $id_materia ID de la materia
 * @return bool True si ya está inscrita en el período actual
 */
function materiaYaInscrita($id_usuario, $id_materia) {
    global $db;
    
    $periodo_activo = obtenerPeriodoActivo();
    if (!$periodo_activo) {
        return false;
    }
    
    $sql = "SELECT COUNT(*) as total 
            FROM notas_definitivas 
            WHERE id_usuario = ? 
            AND id_materia = ? 
            AND id_periodo = ?";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("iii", $id_usuario, $id_materia, $periodo_activo['id_periodo']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['total'] > 0;
}



/**
 * Obtiene las materias en las que el estudiante está INSCRITO ACTUALMENTE en el período activo
 * @param int $id_usuario ID del usuario
 * @return array Materias inscritas en el período actual
 */
function obtenerMateriasInscritasActuales($id_usuario) {
    global $db;
    
    // Primero, obtener el período activo
    $periodo_activo = obtenerPeriodoActivo();
    if (!$periodo_activo) {
        return [];
    }
    
    // Buscar materias que tienen registro en notas_definitivas para el período actual
    // Esto significa que están inscritas en este período
    $sql = "SELECT DISTINCT m.*, nd.id_periodo
            FROM notas_definitivas nd
            INNER JOIN materias m ON nd.id_materia = m.id_materia
            WHERE nd.id_usuario = ?
            AND nd.id_periodo = ?
            ORDER BY m.trayecto, m.nombre_materia";
    
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        error_log("Error preparando consulta: " . $db->error);
        return [];
    }
    
    $stmt->bind_param("ii", $id_usuario, $periodo_activo['id_periodo']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $materias_inscritas = [];
    while ($row = $result->fetch_assoc()) {
        $materias_inscritas[] = $row;
    }
    
    return $materias_inscritas;
}




/**
 * Obtiene las materias disponibles para inscripción (que NO están inscritas actualmente)
 * @param int $id_usuario ID del usuario
 * @param int $trayecto Trayecto actual
 * @param int $id_carrera ID de la carrera
 * @param bool $es_estudiante_nuevo Si es estudiante nuevo
 * @return array Materias disponibles para inscripción
 */
function obtenerMateriasParaInscripcion($id_usuario, $trayecto, $id_carrera, $es_estudiante_nuevo = false) {
    // 1. Obtener todas las materias del trayecto
    $todas_materias = obtenerMateriasTrayecto($id_carrera, $trayecto);
    
    // 2. Obtener materias YA INSCRITAS en el período actual
    $materias_inscritas_actuales = obtenerMateriasInscritasActuales($id_usuario);
    
    // Crear array de IDs de materias ya inscritas
    $ids_materias_inscritas = [];
    foreach ($materias_inscritas_actuales as $materia_inscrita) {
        $ids_materias_inscritas[] = $materia_inscrita['id_materia'];
    }
    
    // 3. Filtrar: solo materias NO inscritas actualmente
    $materias_disponibles = [];
    foreach ($todas_materias as $materia) {
        if (!in_array($materia['id_materia'], $ids_materias_inscritas)) {
            $materias_disponibles[] = $materia;
        }
    }
    
    return $materias_disponibles;
}


/**
 * Obtiene la nota actual de una materia para un estudiante
 * @param int $id_usuario ID del usuario
 * @param int $id_materia ID de la materia
 * @return float|null Nota actual o null si no tiene
 */
function obtenerNotaMateriaActual($id_usuario, $id_materia) {
    global $db;
    
    $sql = "SELECT nd.*, m.trayecto 
            FROM notas_definitivas nd
            INNER JOIN materias m ON nd.id_materia = m.id_materia
            WHERE nd.id_usuario = ? 
            AND nd.id_materia = ?
            ORDER BY nd.id_periodo DESC
            LIMIT 1";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $id_materia);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        switch ($row['trayecto']) {
            case 0: return $row['trayecto_0'];
            case 1: return $row['trayecto_1'];
            case 2: return $row['trayecto_2'];
            case 3: return $row['trayecto_3'];
            case 4: return $row['trayecto_4'];
            default: return null;
        }
    }
    
    return null;
}



































//UBICACION************************************************************************************************



/**
 * Obtener todos los estados
 */
function obtenerEstados() {
    global $db;
    $estados = [];
    $sql = "SELECT id_estado, estado FROM estados ORDER BY estado";
    $result = $db->query($sql);
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $estados[] = $row;
        }
    }
    return $estados;
}

/**
 * Obtener municipios por estado
 */
function obtenerMunicipiosPorEstado($id_estado) {
    global $db;
    $municipios = [];
    if (empty($id_estado) || !is_numeric($id_estado)) {
        return $municipios;
    }
    
    $sql = "SELECT id_municipio, municipio FROM municipios 
            WHERE id_estado = ? ORDER BY municipio";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        return $municipios;
    }
    
    $stmt->bind_param("i", $id_estado);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $municipios[] = $row;
    }
    $stmt->close();
    return $municipios;
}

/**
 * Obtener parroquias por municipio
 */
function obtenerParroquiasPorMunicipio($id_municipio) {
    global $db;
    $parroquias = [];
    if (empty($id_municipio) || !is_numeric($id_municipio)) {
        return $parroquias;
    }
    
    $sql = "SELECT id_parroquia, parroquia FROM parroquias 
            WHERE id_municipio = ? ORDER BY parroquia";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        return $parroquias;
    }
    
    $stmt->bind_param("i", $id_municipio);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $parroquias[] = $row;
    }
    $stmt->close();
    return $parroquias;
}

/**
 * Obtener nombres de ubicación por IDs
 */
function obtenerNombresUbicacion($id_estado, $id_municipio, $id_parroquia) {
    global $db;
    $ubicacion = [
        'estado_nombre' => '',
        'municipio_nombre' => '',
        'parroquia_nombre' => ''
    ];
    
    // Obtener nombre del estado
    if ($id_estado && is_numeric($id_estado)) {
        $sql = "SELECT estado FROM estados WHERE id_estado = ?";
        $stmt = $db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id_estado);
            $stmt->execute();
            $stmt->bind_result($ubicacion['estado_nombre']);
            $stmt->fetch();
            $stmt->close();
        }
    } elseif (!empty($id_estado)) {
        $ubicacion['estado_nombre'] = $id_estado;
    }
    
    // Obtener nombre del municipio
    if ($id_municipio && is_numeric($id_municipio)) {
        $sql = "SELECT municipio FROM municipios WHERE id_municipio = ?";
        $stmt = $db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id_municipio);
            $stmt->execute();
            $stmt->bind_result($ubicacion['municipio_nombre']);
            $stmt->fetch();
            $stmt->close();
        }
    } elseif (!empty($id_municipio)) {
        $ubicacion['municipio_nombre'] = $id_municipio;
    }
    
    // Obtener nombre de la parroquia
    if ($id_parroquia && is_numeric($id_parroquia)) {
        $sql = "SELECT parroquia FROM parroquias WHERE id_parroquia = ?";
        $stmt = $db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id_parroquia);
            $stmt->execute();
            $stmt->bind_result($ubicacion['parroquia_nombre']);
            $stmt->fetch();
            $stmt->close();
        }
    } elseif (!empty($id_parroquia)) {
        $ubicacion['parroquia_nombre'] = $id_parroquia;
    }
    
    return $ubicacion;
}



// Función directa en detalle_estudiante.php (solo si no tienes la función en functions.php)
function obtenerNombresUbicacionDirecto($id_estado, $id_municipio, $id_parroquia = null) {
    global $db;
    $ubicacion = [
        'estado_nombre' => 'No especificado',
        'municipio_nombre' => 'No especificado',
        'parroquia_nombre' => 'No especificado'
    ];
    
    if ($id_estado && is_numeric($id_estado)) {
        $sql = "SELECT estado FROM estados WHERE id_estado = ?";
        $stmt = $db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id_estado);
            $stmt->execute();
            $stmt->bind_result($nombre);
            if ($stmt->fetch()) {
                $ubicacion['estado_nombre'] = $nombre;
            }
            $stmt->close();
        }
    }
    
    if ($id_municipio && is_numeric($id_municipio)) {
        $sql = "SELECT municipio FROM municipios WHERE id_municipio = ?";
        $stmt = $db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id_municipio);
            $stmt->execute();
            $stmt->bind_result($nombre);
            if ($stmt->fetch()) {
                $ubicacion['municipio_nombre'] = $nombre;
            }
            $stmt->close();
        }
    }
    
    if ($id_parroquia && is_numeric($id_parroquia)) {
        $sql = "SELECT parroquia FROM parroquias WHERE id_parroquia = ?";
        $stmt = $db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id_parroquia);
            $stmt->execute();
            $stmt->bind_result($nombre);
            if ($stmt->fetch()) {
                $ubicacion['parroquia_nombre'] = $nombre;
            }
            $stmt->close();
        }
    }
    
    return $ubicacion;
}

// Luego en tu código principal, usa:
$nombresUbicacion = obtenerNombresUbicacionDirecto(
    $estudiante['estado'] ?? null,
    $estudiante['municipio'] ?? null,
    $estudiante['parroquia'] ?? null
);







//PNF PTF *********************************************************************



// Función para formatear el nombre de la carrera según su tipo de formación
function formatearNombreCarrera($nombre_carrera, $tipo_formacion = '') {
    if (empty($tipo_formacion)) {
        return $nombre_carrera;
    }
    
    $tipo = strtoupper(trim($tipo_formacion));
    $nombre = trim($nombre_carrera);
    
    // Quitar prefijos si existen
    $nombre = preg_replace('/^PNF\s+/i', '', $nombre);
    $nombre = preg_replace('/^PTF\s+/i', '', $nombre);
    $nombre = preg_replace('/^PROGRAMA\s+NACIONAL\s+DE\s+FORMACIÓN\s+/i', '', $nombre);
    $nombre = preg_replace('/^PROGRAMA\s+TÉCNICO\s+DE\s+FORMACIÓN\s+/i', '', $nombre);
    
    if ($tipo == 'PNF' || $tipo == 'PROGRAMA NACIONAL DE FORMACION' || $tipo == 'PROGRAMA NACIONAL DE FORMACIÓN') {
        return "PNF " . ucwords(strtolower($nombre));
    } elseif ($tipo == 'PTF' || $tipo == 'PROGRAMA TECNICO DE FORMACION' || $tipo == 'PROGRAMA TÉCNICO DE FORMACIÓN') {
        return "PTF " . ucwords(strtolower($nombre));
    }
    
    return $nombre_carrera;
}

/**
 * Función auxiliar para obtener el tipo de formación de la base de datos
 */
if (!function_exists('obtenerTipoFormacionCarrera')) {
function obtenerTipoFormacionCarrera($id_carrera) {
    global $db;
    
    try {
        $query = "SELECT tipo_formacion FROM carreras WHERE id_carrera = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id_carrera);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['tipo_formacion'] ?? '';
        }
        
        return '';
        
    } catch (Exception $e) {
        error_log("Error al obtener tipo de formación: " . $e->getMessage());
        return '';
    }
}
}

/**
 * Función mejorada para obtener carrera del estudiante incluyendo tipo de formación
 */
if (!function_exists('obtenerCarreraEstudianteCompleta')) {
function obtenerCarreraEstudianteCompleta($estudiante_id) {
    global $db;
    
    $query = "SELECT c.id_carrera, c.nombre_carrera, c.cod_carrera, c.tipo_formacion 
              FROM users u
              INNER JOIN carreras c ON u.carrera = c.id_carrera
              WHERE u.id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $estudiante_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}
}


//HISTORIAL DESGLOZADO ***********************************************************************



/**
 * Función para obtener historial desglozado de notas
 */
function obtenerHistorialNotasDesglozado($estudiante_id) {
    global $db;
    
    $query = "SELECT 
                nd.id,
                nd.id_materia,
                m.nombre_materia,
                m.cod_materia,
                m.trayecto,
                m.creditos,
                pa.nombre_periodo,
                nd.fecha_registro,
                nd.trayecto_0,
                nd.trayecto_1,
                nd.trayecto_2,
                nd.trayecto_3,
                nd.trayecto_4,
                ua.nombre as nombre_admin,
                nd.id_admin_aprobador
              FROM notas_definitivas nd
              INNER JOIN materias m ON nd.id_materia = m.id_materia
              INNER JOIN periodos_academicos pa ON nd.id_periodo = pa.id_periodo
              LEFT JOIN users ua ON nd.id_admin_aprobador = ua.id
              WHERE nd.id_usuario = ?
              ORDER BY m.trayecto, m.nombre_materia, nd.fecha_registro DESC";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $estudiante_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $historial = [];
    while ($row = $result->fetch_assoc()) {
        $historial[] = $row;
    }
    
    return $historial;
}
























// FUNCIONES QUE NO SE VAN A USAR ***********************************************************************




// GENERAR PAGO DE MENSUALIDAD
function generar_pago_mensualidad(){
  global $db, $mes_de_pago_actual, $monto_favor;

  // Datos recibidos del Formulario
  $monto_mensualidad	 		= e($_REQUEST['monto_mensualidad']);
  $monto = explode('_', $monto_mensualidad);
  $afiliacion = $monto[1];
  $monto_mensualidad = $monto[0];
  $banco_emisor	 	= e($_REQUEST['banco_emisor']);
  $banco_destino	 	= e($_REQUEST['banco_destino']);
  $nro_transf 		= e($_REQUEST['nro_transf']);
  $ci_nro_cuenta		= e($_REQUEST['ci_nro_cuenta']);
  $fecha_transf	 	= e($_REQUEST['fecha_transf']);
  $usua	 	= e($_REQUEST['user']);

  a_favor();
  $monto_favor = $GLOBALS['monto_a_favor'];

  if (empty($monto_favor)) {
    $monto_favor	 	= 0;
  } else {
    $monto_favor	 	= $GLOBALS['monto_a_favor'];

  }

  $status_pago ="PENDIENTE";
  $concepto = "MENS_MOVILNET";
  $numerocorto = substr($nro_transf, -6);
  $verf = "SELECT nro_transf FROM pagos WHERE  (nro_transf LIKE '%$numerocorto') AND STR_TO_DATE(fecha_transf,'%Y-%m-%d %T')
  BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND NOW()";
  $result = mysqli_query($db, $verf);
  $rows =  mysqli_num_rows($result);

  $verf2 = "SELECT nro_transf FROM pedidos WHERE  (nro_transf LIKE '%$numerocorto') AND STR_TO_DATE(fecha_transf,'%Y-%m-%d %T')
  BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND NOW()";
  $result2 = mysqli_query($db, $verf2);
  $rows2 =  mysqli_num_rows($result2);

  $sumarows = $rows + $rows2;

  if ($sumarows>0){
    $_SESSION['pago_mensualidad']  = '<i class="fa fa-exclamation-triangle fa-fw"></i> Lo sentimos, el numero de transferencia que intenta utilizar ya fue utilizado, recuerde que no debe utilizar un numero de transferencia usado en alguna otra operacion de declaracion de mensualidades u otros pagos de pedidos, evite ser suspendido/a.<br>';
    mysqli_close($db);
  } else {

    if ($monto_favor>0) {
      $sql1 = "UPDATE users SET
      disp_a_favor = 0,
      act_monto = NOW()
      WHERE
      idusuario = '$usua'";

      if (mysqli_query($db, $sql1)) {
        $_SESSION['pago_mensualidad']  = "Se ha utilizado el dinero a su favor en esta operacion..!!<br>";

      } else {
        $_SESSION['pago_mensualidad']  = "Algo ha ocurrido. Error: ".mysqli_error($db)."<br>";
      }

    } else {
      $_SESSION['pago_mensualidad']  = "No posee monto a favor.<br>";
    }

    $query = "INSERT INTO pagos (id, user, monto, a_favor, concepto, mes_de_pago, afiliacion, banco_origen, banco_destino, nro_transf, ci_nro_cuenta, fecha_transf, status_pago) VALUES (null, '$usua', '$monto_mensualidad', '$monto_favor', '$concepto', '$mes_de_pago_actual', '$afiliacion', '$banco_emisor', '$banco_destino', '$nro_transf', '$ci_nro_cuenta', '$fecha_transf', '$status_pago')";

    if (mysqli_query($db, $query)){

      $id_pago = mysqli_insert_id($db);

      if ($monto_favor>0) {
        $sql2 = "INSERT INTO uso_a_favor (id, usua, id_motivo, monto, motivo, fecha) VALUES (null, '$usua','$id_pago','$monto_favor','$concepto',NOW())";
        if (mysqli_query($db, $sql2)) {
          $_SESSION['pago_mensualidad']  .= "Se ha generado un registro de actualizacion de dinero en su cuenta.<br>";
        } else {
          $_SESSION['pago_mensualidad']  .= 'Algo ha ocurrido, Error: '.mysqli_error($db);
        }

      }

      $_SESSION['pago_mensualidad']  .= "Se ha registrado su pago de manera Exitosa.<br>";

      $monto_mensualidad = number_format($monto_mensualidad, 2, ',', '.');
      $email = $_SESSION['user']['email'];
      $nombre = $_SESSION['user']['nombre'];
      $asunto = "Pago Mensualidad";
      $cuerpo = "Hola $nombre: <br><br>Usted ha registrado un pago de manera exitosa por concepto de mensualidad del mes de $mes_de_pago_actual para uso de la PLATAFORMA <br> Su transferencia fue por un monto de $monto_mensualidad Bs.<br>Esta Transfefencia usted la efectuo: <br> Desde el Banco $banco_emisor <br> Hacia nuestra cuenta en el $banco_destino <br><br>Bajo el Numero de Operacion o Transferencia Bancaria: $nro_transf <br><br>Usted indico que efectuo dicha transferencia en fecha $fecha_transf<br>";
      enviarEmail($email, $nombre, $asunto, $cuerpo);

      $_SESSION['pago_mensualidad']  .='<i class="fa fa-envelope"></i> Hemos enviado Un correo con el resumen de su pago';
    } else {
      $_SESSION['msn_pedidos']  = '<i class="fa fa-exclamation-triangle"></i>Algo ha ocurrido, intente efectuar su declaracion nuevamente. Error: ' . mysqli_error($db);
    }
  }
}




// VERIFICAR QUE NO EXISTA PEDIDOS EN ESPERA
// STATATUS = PENDIENTE   RECHAZADO   APROBADO
function verificar_status(){
    global $db, $usua, $ci_nro_cuenta, $monto, $nro_transf, $banco_emisor, $banco_destino, $fecha_transf,$mes_de_pago_actual, $debe_pagar, $operador, $modal_usuario_bloqueado, $monto_favor,
$mens_monto_favor, $cuentas_bancarias;

    if (isActive()){

      $query = "SELECT * FROM pedidos  WHERE usuario = '$usua' AND status_pedido IN('ESPERANDO','APROBADO')";
		$result = mysqli_query($db, $query);
		$rows =  mysqli_num_rows($result);
if ($rows > 0){

	echo '<div class="alert alert-danger" role="alert" >
				<h3>
		LO SENTIMOS, USTED POSEE UN PEDIDO DE TARJETAS UN1CA EN ESPERA, DEBE ESPERAR SEA DESPACHADO SU PEDIDO PARA PODER EFECTUAR UN NUEVO PEDIDO.
				</h3>
			</div>';


} else {

	$queryvpm = "SELECT * FROM pagos WHERE user = '$usua' AND mes_de_pago = '$mes_de_pago_actual' AND concepto = 'MENS_MOVILNET' AND status_pago = 'APROBADO' ORDER by id DESC LIMIT 1";
	$resultvpm = mysqli_query($db, $queryvpm);
	$rowsvpm =  mysqli_num_rows($resultvpm);
    $rowdato = mysqli_fetch_assoc($resultvpm);
    $motivo = $rowdato['motivo_rechazo'];

	if ($rowdato['status_pago'] == "PENDIENTE") {
		echo '<div class="alert alert-danger" role="alert" >
				<h3>
		LO SENTIMOS, SU PAGO DE LA MENSUALIDAD <b>'.strtoupper ($mes_de_pago_actual) .'</b> AUN NO HA SIDO CONFORMADO
				</h3>
			</div>';
    }

    else if ($rowdato['status_pago'] == "RECHAZADO") {
		echo '<div class="alert alert-danger" role="alert" >
				<h3>
		LO SENTIMOS, USTED NO PUEDE EFECTUAR PEDIDOS YA QUE SU PAGO DE LA MENSUALIDAD <b>'.strtoupper ($mes_de_pago_actual) .'</b> FUE RECHAZADO POR EL SIGUIENTE MOTIVO: <b>'.strtoupper ($motivo) .'</b><br> LE INVITAMOS A A EFECTUAR SU PAGO DE MENSUALIDAD Y DECLARARLO <a href="mensualidad_movilnet.php">AQUI</a>
				</h3>
			</div>';
	}

	else if ($rowdato['status_pago'] == "APROBADO"){

    a_favor();
    echo $mens_monto_favor;
    $monto_favor = $GLOBALS['monto_a_favor'];

        echo $cuentas_bancarias;
contenido('bancario');

echo ' <form autocomplete="off" class="was-validated" method="post" action= "pedidos_movilnet.php">';

echo '<input type="hidden" name="operador" value="'.$operador.'">';

echo '<div class="form-group">
<label for="monto">Seleccione Monto de su Pedido</label>
<select class="custom-select" id="monto" name="monto" value="';
echo $monto;
echo '" required >
<option value="">Seleccione:</option>';
monto();
echo '</select> <div class="invalid-feedback">Debe Seleccionar el monto de su transferencia.</div>
</div>

<div class="form-group">
<label for="banco_emisor">Desde Que banco Transfirio</label>
<select class="custom-select" id="banco_emisor" name="banco_emisor" value="';
echo $banco_emisor;
echo '" required >
<option value="">Seleccione:</option>';
banco_emisor();
echo '</select> <div class="invalid-feedback">Debe Seleccionar desde que banco efectuo su transferencia.</div>
</div>

<div class="form-group">
<label for="banco_destino">A que Banco Transfirio</label>
<select class="custom-select" id="banco_destino" name="banco_destino" value="';
echo $banco_destino;
echo '" required >
<option value="">Seleccione:</option>';
banco_destino();
echo '</select>
<div class="invalid-feedback">Debe Seleccionar a que banco usted efectuo su transferencia.</div>
</div>

<div class="form-group">
<label for="nroTransf">Numero de Transferencia</label>
<input  pattern="[0-9]{8,15}" title = "Debe utilizar solo Numeros, Minimo 8 digitos y Maximo 15 digitos. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234"  type="text" class="form-control" id="nro_transf" aria-describedby="nro_transf" placeholder="Numero de Operacion Bancaria" name="nro_transf" value="';
echo $nro_transf;
echo '" required>
<div class="invalid-feedback">Debe indicar el numero de operacion bancaria indicada por su Banco. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234</div>
</div>

<div class="form-group">
<label for="ci_nro_cuenta">Cedula del Titular de la Cuenta Origen</label>
<input  pattern="[0-9]{7,10}" title = "Debe utilizar solo Numeros, Minimo 7 digitos y Maximo 10 digitos"  type="text" class="form-control" id="ci_nro_cuenta" aria-describedby="ci_nro_cuenta" placeholder="Numero de Cedula Titular de la Cuenta Origen" name="ci_nro_cuenta" value="';
echo $ci_nro_cuenta;
echo '" required>
<div class="invalid-feedback">Debe indicar el numero de cedula del titular de la cuenta desde donde usted efectuo su transferencia.</div>
</div>

<div class="form-group">
<label for="fechaTransf">Fecha de su Transferencia</label>
<input pattern="(?: 30)) | (? :(? : 0 [13578] | 1 [02]) - 31)) / (? :(?: 0 [1-9] | 1 [0-2]) - (?: 0 [1-9] | 1 [0 -9] | 2 [0-9]) | (? :( ?! 02) (?: 0 [1-9] | 1 [0-2]) / (?: 19 | 20) [0-9] {2}" title = "Debe utilizar el formato DD/MM/YYYY" type="date" class="form-control" id="fecha_transf" aria-describedby="fecha_transf" placeholder="Numero de Operacion Bancaria" name ="fecha_transf" value="';
echo $fecha_transf;
echo '" required>
<div class="invalid-feedback">Debe Seleccione la fecha en que usted efectuo su transferencia.</div>
</div>

<input type="hidden" name="user" value="'.$usua.'">

<input type="hidden" name="sin_plan" value="0">

<button type="submit" class="btn btn-primary" name="pedido_btn">Enviar</button>

</form>';
	}  else {
	echo $debe_pagar;
}
}

    } else {

      echo $modal_usuario_bloqueado;

    }
}



// VERIFICAR QUE NO EXISTA PEDIDOS EN ESPERA OPERADORES
// STATATUS = PENDIENTE   RECHAZADO   APROBADO
function verificar_status2(){
  global $db, $usua, $ci_nro_cuenta, $monto, $nro_transf, $banco_emisor, $banco_destino, $fecha_transf,$mes_de_pago_actual, $debe_pagar, $debe_pagar_operador, $concepto, $operador, $num_min, $text_num_min, $ph, $modal_usuario_bloqueado, $monto_favor, $mens_monto_favor, $cuentas_bancarias, $movilnet_msn, $rowsvpm;

  selector_operador();

  if (isActive()){

  //INICIO SI ES MOVILNET
  if ($operador == "Movilnet"){



    $query = "SELECT * FROM pedidos  WHERE usuario = '$usua' AND operador = '$operador' AND status_pedido IN('ESPERANDO','APROBADO')";
  $result = mysqli_query($db, $query);
  $rows =  mysqli_num_rows($result);
if ($rows > 0){

echo '<div class="alert alert-danger" role="alert" >
      <h3>
  LO SENTIMOS, USTED POSEE UN PEDIDO EN ESPERA
      </h3>
    </div>';


} else {

$queryvpm = "SELECT * FROM pagos WHERE user = '$usua' AND concepto = '$concepto' AND mes_de_pago = '$mes_de_pago_actual' ORDER by id DESC LIMIT 1";
$resultvpm = mysqli_query($db, $queryvpm);
$rowsvpm =  mysqli_num_rows($resultvpm);
  $rowdato = mysqli_fetch_assoc($resultvpm);
  $motivo = $rowdato['motivo_rechazo'];

if ($rowdato['status_pago'] == "PENDIENTE") {
  echo '<div class="alert alert-danger" role="alert" >
      <h3>
  LO SENTIMOS, SU PAGO DE LA MENSUALIDAD <b>'.strtoupper ($mes_de_pago_actual) .'</b> AUN NO HA SIDO CONFORMADO
      </h3>
    </div>';
  }

  else if ($rowdato['status_pago'] == "RECHAZADO") {
  echo '<div class="alert alert-danger" role="alert" >
      <h3>
  LO SENTIMOS, USTED NO PUEDE EFECTUAR PEDIDOS YA QUE SU PAGO DE LA MENSUALIDAD <b>'.strtoupper ($mes_de_pago_actual) .'</b> FUE RECHAZADO POR EL SIGUIENTE MOTIVO: <b>'.strtoupper ($motivo) .'</b><br> LE INVITAMOS A A EFECTUAR SU PAGO DE MENSUALIDAD Y DECLARARLO <a href="mensualidad_movilnet.php">AQUI</a>
      </h3>
    </div>';
}

else if ($rowdato['status_pago'] == "APROBADO"){

  a_favor();
  echo $mens_monto_favor;
  $monto_favor = $GLOBALS['monto_a_favor'];

      echo $cuentas_bancarias;
contenido('bancario');

echo ' <form autocomplete="off" class="was-validated" method="post" action= "pedidos_movilnet.php">';

echo '<div class="form-group">
<label for="monto">Seleccione Monto de su Pedido</label>
<select class="custom-select" id="monto" name="monto" value="';
echo $monto;
echo '" required >
<option value="">Seleccione:</option>';
monto();
echo '</select> <div class="invalid-feedback">Debe Seleccionar el monto de su transferencia.</div>
</div>

<div class="form-group">
<label for="banco_emisor">Desde Que banco Transfirio</label>
<select class="custom-select" id="banco_emisor" name="banco_emisor" value="';
echo $banco_emisor;
echo '" required >
<option value="">Seleccione:</option>';
banco_emisor();
echo '</select> <div class="invalid-feedback">Debe Seleccionar desde que banco efectuo su transferencia.</div>
</div>

<div class="form-group">
<label for="banco_destino">A que Banco Transfirio</label>
<select class="custom-select" id="banco_destino" name="banco_destino" value="';
echo $banco_destino;
echo '" required >
<option value="">Seleccione:</option>';
banco_destino();
echo '</select>
<div class="invalid-feedback">Debe Seleccionar a que banco usted efectuo su transferencia.</div>
</div>

<div class="form-group">
<label for="nroTransf">Numero de Transferencia</label>
<input  pattern="[0-9]{8,15}" title = "Debe utilizar solo Numeros, Minimo 8 digitos y Maximo 15 digitos. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234"  type="text" class="form-control" id="nro_transf" aria-describedby="nro_transf" placeholder="Numero de Operacion Bancaria" name="nro_transf" value="';
echo $nro_transf;
echo '" required>
<div class="invalid-feedback">Debe indicar el numero de operacion bancaria indicada por su Banco. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234</div>
</div>

<div class="form-group">
<label for="ci_nro_cuenta">Cedula del Titular de la Cuenta Origen</label>
<input  pattern="[0-9]{7,10}" title = "Debe utilizar solo Numeros, Minimo 7 digitos y Maximo 10 digitos"  type="text" class="form-control" id="ci_nro_cuenta" aria-describedby="ci_nro_cuenta" placeholder="Numero de Cedula Titular de la Cuenta Origen" name="ci_nro_cuenta" value="';
echo $ci_nro_cuenta;
echo '" required>
<div class="invalid-feedback">Debe indicar el numero de cedula del titular de la cuenta desde donde usted efectuo su transferencia.</div>
</div>

<div class="form-group">
<label for="fechaTransf">Fecha de su Transferencia</label>
<input pattern="(?: 30)) | (? :(? : 0 [13578] | 1 [02]) - 31)) / (? :(?: 0 [1-9] | 1 [0-2]) - (?: 0 [1-9] | 1 [0 -9] | 2 [0-9]) | (? :( ?! 02) (?: 0 [1-9] | 1 [0-2]) / (?: 19 | 20) [0-9] {2}" title = "Debe utilizar el formato DD/MM/YYYY" type="date" class="form-control" id="fecha_transf" aria-describedby="fecha_transf" placeholder="Numero de Operacion Bancaria" name ="fecha_transf" value="';
echo $fecha_transf;
echo '" required>
<div class="invalid-feedback">Debe Seleccione la fecha en que usted efectuo su transferencia.</div>
</div>

<input type="hidden" name="user" value="'.$usua.'">
<input type="hidden" name="monto_favor" value="'.$monto_favor.'">
<input type="hidden" name="sin_plan" value="0">

<button type="submit" class="btn btn-primary" name="pedido_btn">Enviar</button>

</form>';
}  else {
echo $debe_pagar;

}
}

}
//INICIO SI ES OPERADOR DIFERENTE A MOVILNET
else if ($operador == $operador){
  echo '<h1>'.$operador.'</h1>';

  $query = "SELECT * FROM pedidos  WHERE usuario = '$usua' AND operador = '$operador' AND status_pedido IN('ESPERANDO','APROBADO') AND sin_plan = '0' ORDER BY id DESC LIMIT 1";
  $result = mysqli_query($db, $query);
  $rows =  mysqli_num_rows($result);
  // ANALIZAR QUE NO TENGA PEDIDOS EN ESPERA
if ($rows > 5){

echo '<div class="alert alert-danger" role="alert" >
      <h3>
  LO SENTIMOS, USTED POSEE UN REGISTRO DE RECARGAS QUE AUN NO HA SIDO ATENDIDO.
      </h3>
    </div>';


} else {

$queryvpm = "SELECT *, DATEDIFF(fin, NOW()) as DiasRestantes FROM pagos WHERE user = '$usua' AND concepto = '$concepto' AND DATEDIFF(fin, inicio)>'0' ORDER by id DESC LIMIT 1";
  $resultvpm = mysqli_query($db, $queryvpm);
  $rowsvpm =  mysqli_num_rows($resultvpm);
  $rowdato = mysqli_fetch_assoc($resultvpm);
  $motivo = $rowdato['motivo_rechazo'];

if ($rowdato['DiasRestantes'] > 0)
{

if ($rowdato['status_pago'] == "PENDIENTE") {
  echo '<div class="alert alert-danger" role="alert" >
      <h3>
  LO SENTIMOS, SU PAGO DE LA MENSUALIDAD PARA EL USO DE LA PLATAFORMA <b>'.strtoupper ($operador) .'</b> AUN NO HA SIDO CONFORMADO.
      </h3>
    </div>';
  }

  else if ($rowdato['status_pago'] == "RECHAZADO") {
  echo '<div class="alert alert-danger" role="alert" >
      <h3>
  LO SENTIMOS, USTED NO PUEDE EFECTUAR SOLICITUDES DE RECARGA YA QUE SU PAGO DE LA MENSUALIDAD PARA EL USO DE LA PLATAFORMA <b>'.strtoupper ($operador) .'</b> FUE RECHAZADO POR EL SIGUIENTE MOTIVO: <b>'.strtoupper ($motivo) .'</b><br> LE INVITAMOS A A EFECTUAR SU PAGO DE MENSUALIDAD Y DECLARARLO NUEVAMENTE <a href="mensualidad_'.strtolower ($operador) .'.php">AQUI</a>
      </h3>
    </div>';
}

else if ($rowdato['status_pago'] == "APROBADO"){

  if ($operador == 'Movilnet') {
    echo $movilnet_msn;
  }
      echo ' VERIFIQUE MUY BIEN LOS DATOS QUE VA A INGRESAR AL SISTEMA';

echo ' <form autocomplete="off" class="was-validated" method="post" action= "">';

echo '<div class="form-group">
<label for="monto">Seleccione Monto a recargar</label>
<select class="custom-select form-control-lg" id="monto" name="monto" value="';
//echo $monto;
echo '" required >
<option value="">Seleccione:</option>';
monto_recarga();
echo '</select> <div class="invalid-feedback">Debe Seleccionar el monto a recargar.</div>
</div>

<div class="form-group">
<label for="nro">Numero A Recargar</label>
<input title = "'.$text_num_min.'"  type="num"  pattern="'.$num_min.'" minlenght="8" maxlenght="11" class="form-control form-control-lg" id="nro" aria-describedby="nro" placeholder="'.$ph.'" name="nro" value="';
//echo $nro;
echo '" required>
<div class="invalid-feedback">'.$text_num_min.'</div>
</div>
<input type="hidden" name="accion" value="insert">
<input type="hidden" name="user" value="'.$usua.'">
<input type="hidden" name="operador" value="'.$operador.'">
<input type="hidden" name="sin_plan" value="0">

<button type="submit" class="btn btn-primary" name="registrar_recarga_btn"><i class="fa fa-save"></i> Registrar</button>

</form>';
}
}  else {
echo $debe_pagar_operador;

}
} // CIERRE VERIFICAR QUE NO TENGA PEDIDOS EN ESPERA




} // CIERRE PARA MOVISTAR


} // TODO ANTES DE ESTO PASA SI EL USUARIO ESTA ACTIVO
else {

    echo $modal_usuario_bloqueado;

  }

}



function verificar_status3(){
  global $db, $username, $usua, $ci_nro_cuenta, $monto, $nro_transf, $banco_emisor, $banco_destino, $fecha_transf, $status_pedido, $fecha_pedido, $status_pago, $fecha_aprobacion,$mes_de_pago_actual, $debe_pagar, $debe_pagar_operador, $concepto, $operador, $link, $t, $num_min, $text_num_min, $ph, $fecha_sistema, $modal_usuario_bloqueado, $monto_favor, $mens_monto_favor, $movilnet_msn;

  selector_operador();

  if (isActive()){

  if ($operador == $operador){
  echo '<h1>'.$operador.'</h1>';


  $query = "SELECT * FROM pedidos  WHERE usuario = '$usua' AND operador = '$operador' AND status_pedido IN('ESPERANDO','APROBADO') AND sin_plan = '0' ORDER BY id DESC LIMIT 1";
  $result = mysqli_query($db, $query);
  $rows =  mysqli_num_rows($result);
  // ANALIZAR QUE NO TENGA PEDIDOS EN ESPERA
if ($rows > 5){

echo '<div class="alert alert-danger" role="alert" >
      <h3>
  LO SENTIMOS, USTED POSEE 5 REGISTROS DE RECARGAS QUE AUN NO HA SIDO ATENDIDO.
      </h3>
    </div>';


} else {

$queryvpm = "SELECT *, DATEDIFF(fin, NOW()) as DiasRestantes FROM pagos WHERE user = '$usua' AND concepto = '$concepto' AND DATEDIFF(fin, inicio)>'0' ORDER by id DESC LIMIT 1";
  $resultvpm = mysqli_query($db, $queryvpm);
  $rowsvpm =  mysqli_num_rows($resultvpm);
  $rowdato = mysqli_fetch_assoc($resultvpm);
  $motivo = $rowdato['motivo_rechazo'];

if ($rowdato['DiasRestantes'] > 0)
{

if ($rowdato['status_pago'] == "PENDIENTE") {
  echo '<div class="alert alert-danger" role="alert" >
      <h3>
  LO SENTIMOS, SU PAGO DE LA MENSUALIDAD PARA EL USO DE LA PLATAFORMA <b>'.strtoupper ($operador) .'</b> AUN NO HA SIDO CONFORMADO.
      </h3>
    </div>';
  }

  else if ($rowdato['status_pago'] == "RECHAZADO") {
  echo '<div class="alert alert-danger" role="alert" >
      <h3>
  LO SENTIMOS, USTED NO PUEDE EFECTUAR SOLICITUDES DE RECARGA YA QUE SU PAGO DE LA MENSUALIDAD PARA EL USO DE LA PLATAFORMA <b>'.strtoupper ($operador) .'</b> FUE RECHAZADO POR EL SIGUIENTE MOTIVO: <b>'.strtoupper ($motivo) .'</b><br> LE INVITAMOS A A EFECTUAR SU PAGO DE MENSUALIDAD Y DECLARARLO NUEVAMENTE <a href="mensualidad_'.strtolower ($operador) .'.php">AQUI</a>
      </h3>
    </div>';
}

else if ($rowdato['status_pago'] == "APROBADO"){

  if ($operador == 'Movilnet') {
    echo $movilnet_msn;
  }
      echo ' VERIFIQUE MUY BIEN LOS DATOS QUE VA A INGRESAR AL SISTEMA';

echo ' <form autocomplete="off" class="was-validated" method="post" action= "">';

echo '<div class="form-group">
<label for="monto">Seleccione Monto a recargar</label>
<select class="custom-select form-control-lg" id="monto" name="monto" value="';
//echo $monto;
echo '" required >
<option value="">Seleccione:</option>';
monto_recarga();
echo '</select> <div class="invalid-feedback">Debe Seleccionar el monto a recargar.</div>
</div>

<div class="form-group">
<label for="nro">Numero A Recargar</label>
<input title = "'.$text_num_min.'"  type="num"  pattern="'.$num_min.'" minlenght="8" maxlenght="11" class="form-control form-control-lg" id="nro" aria-describedby="nro" placeholder="'.$ph.'" name="nro" value="';
//echo $nro;
echo '" required>
<div class="invalid-feedback">'.$text_num_min.'</div>
</div>
<input type="hidden" name="accion" value="insert">
<input type="hidden" name="user" value="'.$usua.'">
<input type="hidden" name="operador" value="'.$operador.'">
<input type="hidden" name="sin_plan" value="0">

<button type="submit" class="btn btn-primary" name="registrar_recarga_btn"><i class="fa fa-save"></i> Registrar</button>

</form>';
}
}  else {
echo $debe_pagar_operador;

}
} // CIERRE VERIFICAR QUE NO TENGA PEDIDOS EN ESPERA




} // CIERRE PARA MOVISTAR


} //TODO ANTES DE ESTO PASA SI EL USUARIO ESTA ACTIVO
else {

    echo $modal_usuario_bloqueado;

  }

}

function contar_en_espera(){
      global $db, $username, $usua, $contar_pedido,
      $pendiente_pedido, $mes_de_pago_actual, $ganancia_bantecom, $esperando;

      $query = "SELECT SUM(CASE WHEN confirmacion = 'Esperando_Operador' THEN 1 ELSE 0 END) AS 'esperando' FROM `recargar`";
      $result = mysqli_query($db, $query);
      $rows =  mysqli_fetch_assoc($result);
      $esperando = $rows['esperando'];

      if ($esperando>0) {
        $esperando = $esperando;
      } else {
      $esperando = "";
      }

      $esperando = $esperando ;

    }


	



//BOTONERA EDITAR NUMERO DE SOLICITUD DE RECARGA
// $a = id de recarga
function botonera_recarga($a){
  global $db, $usua, $accion, $concepto, $operador, $link, $multiplo, $num_min, $text_num_min, $ph, $nro, $op, $opciones, $monto_minimo, $monto_maximo, $titulopag, $porcentaje;

  $query = "SELECT * FROM recargar WHERE id = '$a'";
  $result = mysqli_query($db, $query);
    $rows =  mysqli_num_rows($result);
    $row = mysqli_fetch_array($result);

    if ($rows<1){
      $_SESSION['recarga']  = "Lo sentimos,la accion que intenta efectuar no se puede llevar a cabo motivado q que intenta editar un id que no existe<br>";
      //mysqli_close($db);
    } else {

            $nro = $row['nro'];
            $monto = $row['monto'];
            $operador = $row['operador'];
            $tipo = $row['tipo'];
            selector_operador();

$boton_editar = '<div data-html="true" href="#" data-toggle="popover" title="EDITAR NUMERO A RECARGAR" data-content="Editar Numero <br> <b>'.$nro.'</b>.">
<i class="fa fa-edit"></i>
</div>';

$boton_editar2 ='<!-- Button trigger modal -->
<button type="button" class="mx-auto btn btn-sm btn-outline-info" data-toggle="modal" data-target="#editar'.$a.'" title="EDITAR NUMERO '.$nro.'">
'.$boton_editar.'
</button>';

$boton_eliminar = '<div data-html="true" href="#" data-toggle="popover" title="ELIMINAR NUMERO A RECARGAR" data-content="Eliminar Numero <br> <b>'.$nro.'</b>.">
<i class="fa fa-trash-alt"></i>
</div>';

$boton_eliminar2 ='<!-- Button trigger modal -->
<button type="button" class="mx-auto btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#eliminar'.$a.'" title="ELIMINAR NUMERO '.$nro.'">
'.$boton_eliminar.'
</button>';

$boton_eliminar2 .= '<!-- Modal -->
<div class="modal fade" id="eliminar'.$a.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Eliminar Numero</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      Confirme que desea eliminar la solicitud de recarga al numero : <b>' .$nro .'</b> por un monto de <b>' .$monto .' Bs.</b><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">cerrar</button>
        <form autocomplete="off" class="was-validated" method="post" action= "">
        <input type="hidden" name="id" value="'.$a.'">
        <input type="hidden" name="accion" value="eliminar">
        <button type="submit" class="btn btn-danger" name="registrar_recarga_btn">Eliminar</button>
        </form>
      </div>
    </div>
  </div>
</div>';

$boton_editar2 .= '<!-- Modal -->
          <div class="modal fade" id="editar'.$a.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Editar Recarga</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">';


    $editar_recarga = ' <form autocomplete="off" class="was-validated" method="post" action= "">';

    //$editar_recarga .= 'Identificador: ' .$a .'<br>';
    $editar_recarga .= 'Editar Numero: ' .$nro .'<br>';
    $editar_recarga .= 'Monto: ' .number_format($monto,2,',','.') .' Bs.<br>';
    $editar_recarga .= 'Tipo: ' .$tipo .'<br>';
    $editar_recarga .= '<div class="dropdown-divider"></div>';


    $editar_recarga .= '<div class="form-group">
    <label for="monto">Seleccione Monto a recargar</label>
    <select class="custom-select form-control-lg" id="monto" name="monto" value="';
    $editar_recarga .= $monto;
    $monto_f=number_format($monto,2,',','.');
    $editar_recarga .= 'Bs." required> <option value="'.$monto.'">'.$monto_f.' Bs.</option>';
    selector_operador();


	$query2 = "SELECT * FROM `monto_recarga` WHERE mod (monto, '$multiplo') = 0 AND monto >= $monto_minimo AND monto <= $monto_maximo ORDER BY monto ASC";
	$results2 = mysqli_query($db, $query2);

  //$foo = 'Hola mundo';

if (strpos($titulopag, 'Sin Plan')) {
  while ($valores = mysqli_fetch_array($results2)) {

    $monto = $valores['monto'];
    $monto_f = number_format($monto,2,',','.');
    $calculo = $monto * $porcentaje / 100;
    $total = $monto + $calculo;
    $total_f = number_format($total,2,',','.');

  $editar_recarga .= '<option value="'.$monto.'"> Para Recargar '.$monto_f.' Bs Deberá Pagar '.$total_f.' Bs.</option>';
    }
} else {


	while ($valores = mysqli_fetch_array($results2)) {
    $monto_f = number_format($valores['monto'],2,',','.');
    $editar_recarga .= '<option value="'.$valores['monto'].'">'.$monto_f.' Bs.</option>';

  }
  }
    $editar_recarga .= '</select> <div class="invalid-feedback">Debe Seleccionar el monto a recargar.</div>
    </div>



    <div class="form-group">
<label for="nro">Numero A Recargar</label>
<input  pattern="'.$num_min.'" minlenght="8" maxlenght="11" title = "'.$text_num_min.'"  type="text" class="form-control form-control-lg" id="nro" aria-describedby="nro" placeholder="'.$ph.'" name="nro" value="';
    $editar_recarga .= $nro;
    $editar_recarga .= '" required>
    <div class="invalid-feedback">'.$text_num_min.'</div>
    </div>
    <input type="hidden" name="accion" value="update">
    <input type="hidden" name="id" value="'.$a.'">
    <input type="hidden" name="user" value="'.$usua.'">
    <input type="hidden" name="operador" value="'.$operador.'">
    <button type="submit" class="btn btn-primary" name="registrar_recarga_btn"><i class="fa fa-save"></i> Registrar</button>

    </form>

                    </div>
                  </div>
                </div>
              </div>
              ';
  $boton_editar2 .=  $editar_recarga;
  $accion = '<div class="btn-group-horizontal" >' . $boton_editar2.$boton_eliminar2.'</div>';
}
}


// BOTONERA USUARIO
//$a = Id
//$b = Nombre de usuario
//$c = Username de usuario
// Se debe utilizar global $accion y la salida es $accion
function botonera_usuario($b,$c){
    global $db, $usua, $accion, $mes_de_pago_actual;


    $query = "SELECT * FROM pedidos  WHERE usuario = '$c'";
		$resultA = mysqli_query($db, $query);
    $rows =  mysqli_num_rows($resultA);

    $query2 = "SELECT * FROM pagos  WHERE user = '$c'";
    $resultB = mysqli_query($db, $query2);
    $rowsB =  mysqli_num_rows($resultB);

    $query3 = "SELECT id FROM users WHERE idusuario = '$c'";
    $resultC = mysqli_query($db, $query3);
    while ($rowC = mysqli_fetch_assoc($resultC))
   {
$a = $rowC['id'];
}
    //$rowsC =  mysqli_num_rows($resultC);


      $cant_pedido = $rows;
      $cant_meses = $rowsB;

$boton_editar = '<div data-html="true" href="#" data-toggle="popover" title="EDITAR USUARIO" data-content="Editar Usuario <br> <b>'.$b.'</b>.">Editar <i class="fa fa-envelope"></i></div>';

//$boton_editar = '';
//<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">
$boton_editar2 ='<!-- Button trigger modal -->
 <button type="button" class="mx-auto btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#editar'.$a.'" title="EDITAR USUARIO '.$b.'">
'.$boton_editar.'
</button><br>';




echo '<!-- Modal -->
<div id="editar'.$a.'" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Editar al Usuario</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  Editar al Usuario '.$b;




$query = "SELECT * FROM users WHERE id = '$a'";
$result = mysqli_query($db, $query);
    $rows =  mysqli_num_rows($result);
    $row = mysqli_fetch_array($result);
if ($rows<1){
  $_SESSION['usuarios']  = "Lo sentimos, el usuario que intenta editar no existe id $a.<br>";
  //mysqli_close($db);
} else {
        $idusuario = $row['idusuario'];
        $nombre = $row['nombre'];
        $email = $row['email'];
        $telefono_usuario = $row['tlf'];
        $celular_usuario = $row['cel'];
        $direccion_usuario = $row['direccion'];
        $ciudad_usuario = $row['ciudad'];
        $estado_usuario = $row['estado'];
        $municipio_usuario = $row['municipio'];
        $parroquia_usuario = $row['parroquia'];
        //$password_usuario = $row['password'];
        $status_usuario = $row['status'];
        $option = "";
        if ($status_usuario ==1){
            $option = '<option value= "'.$status_usuario.'">ACTIVO</option>
            <option value = "0">SUSPENDER</option>';
        }else if ($status_usuario ==0){
            $option = '<option value= "'.$status_usuario.'">SUSPENDIDO</option>
            <option value = "1">ACTIVAR</option>';
        }

$editar_usuario = ' <form autocomplete="off" class="was-validated" method="post" action= "editar_usuarios.php?id='.$a.'">';


//$editar_usuario .= 'Web de Origen: ' . $web = basename($_SERVER['REQUEST_URI']).'<br>';
$web = basename($_SERVER['REQUEST_URI']);
$editar_usuario .= '<input type="hidden" name="web" value="'.$web.'">';


$editar_usuario .= 'Identificador: ' .$a .'<br>';
$editar_usuario .= 'Usuario: ' .$idusuario .'<br>';
$editar_usuario .= 'Nombre: ' .$nombre .'<br>';
$editar_usuario .= 'Email: ' .$email .'<br>';
$editar_usuario .= '<div class="dropdown-divider"></div>';


$editar_usuario .= '<div class="form-group">
<label for="nombre">Numero de Cliente</label>
<input type="text" pattern="[V,J,G,E]{1}[-][0-9]{7,9}" class="form-control" id="idusuario" aria-describedby="idusuario" placeholder="Ingrese Id de Usuario" name="idusuario" value="';
$editar_usuario .= $idusuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el idusuario en formato V-12345678.</div>
</div>



<div class="form-group">
<label for="nombre">Nombre</label>
<input type="text" class="form-control" id="nombre" aria-describedby="nombre" placeholder="Ingrese nombre" name="nombre" value="';
$editar_usuario .= $nombre;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el nombre.</div>
</div>


<div class="form-group">
<label for="email">Email</label>
<input type="email" pattern="[a-zA-Z0-9]{0,}([.]?[_.a-zA-Z0-9]{1,})[@](gmail.com|hotmail.com|yahoo.com|yahoo.es|outlook.es|outlook.com|hotmail.es|cantv.net|cantv.com)" title="Debe utilizar solo correos gmail, yahoo, hotmail o cantv" class="form-control" id="email" aria-describedby="email" placeholder="Ingrese Email" name="email" value="';
$editar_usuario .= $email;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Email, solo usar gmail, yahoo, hotmail o cantv.</div>
</div>



<div class="form-group">
<label for="telefono_usuario">Numero de Telefono Local</label>
<input type="tel" pattern="[0]{1}[2]{1}[1-9]{1}[0-9]{8}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de area, Ejemplo: 02431234567"  class="form-control" id="telefono_usuario" aria-describedby="telefono_usuario" placeholder="Ingrese su numero de Telefono local" name="telefono_usuario" value="';
$editar_usuario .= $telefono_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el numero de Telefono local, Debe usar minimo 11 digitos debe incluir el codigo de area, Ejemplo: 02431234567.</div>
</div>

<div class="form-group">
<label for="celular_usuario">Numero de Celular</label>
<input type="tel" pattern="[0]{1}[4]{1}[1,2]{1}[2,4,6]{1}[0-9]{7}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567"  class="form-control" id="celular_usuario" aria-describedby="celular_usuario" placeholder="Ingrese su numero de Celular" name="celular_usuario" value="';
$editar_usuario .= $celular_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar su numero de telefono Celular, debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567.</div>
</div>

<div class="form-group">
<label for="direccion_usuario">Su Direccion Completa</label>
<input type="textarea" class="form-control" id="direccion_usuario" aria-describedby="direccion_usuario" placeholder="Ingrese su Direccion" name="direccion_usuario" value="';
$editar_usuario .= $direccion_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar su Direccion completa.</div>
</div>

<div class="form-group">
<label for="estado_usuario">Estado donde Vive</label>
<input type="text" class="form-control" id="estado_usuario" aria-describedby="estado_usuario" placeholder="Ingrese el Estado" name="estado_usuario" value="';
$editar_usuario .= $estado_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Estado donde vive.</div>
</div>

<div class="form-group">
<label for="ciudad_usuario">Ciudad donde vive</label>
<input type="text" class="form-control" id="ciudad_usuario" aria-describedby="ciudad_usuario" placeholder="Ingrese la Ciudad" name="ciudad_usuario" value="';
$editar_usuario .= $ciudad_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Ciudad donde vive.</div>
</div>

<div class="form-group">
<label for="municipio_usuario">Municipio donde vive</label>
<input type="text" class="form-control" id="municipio_usuario" aria-describedby="municipio_usuario" placeholder="Ingrese el Municipio" name="municipio_usuario" value="';
$editar_usuario .= $municipio_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Municipio de ubicacion.</div>
</div>

<div class="form-group">
<label for="parroquia_usuario">Parroquia donde vive</label>
<input type="text" class="form-control" id="parroquia_usuario" aria-describedby="parroquia_usuario" placeholder="Ingrese el Parroquia" name="parroquia_usuario" value="';
$editar_usuario .= $parroquia_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Parroquia de ubicacion.</div>
</div>';

$editar_usuario .= '<div class="form-group">
<label for="exampleFormControlSelect1">Status de Usuario </label>
<select class="form-control" name = "status_usuario" id="status_usuario" value="'.$status_usuario.'">
'.$option.'
</select>
</div>';



//$editar_usuario .= '<button type="submit" class="btn btn-primary" name="editar_desde_admin_btn">Enviar</button>';
echo  $editar_usuario;




    echo  '</div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                  <button type="submit" class="btn btn-primary" name="editar_desde_admin_btn">Enviar</button>



</form>

                </div>
              </div>
            </div>
          </div>';



        }

$boton_pedidos = '<a data-html="true" class="btn btn-outline-success btn-sm" href="ver_pedidos_del_usuarios.php?id='.$a.'&usuario='.$c.'&nombre_usuario='.$b.'" data-toggle="popover" title="VER PEDIDOS" data-content="<b> '.$b.'</b> <br> Ha efectuado '.$cant_pedido.' pedidos en total.">
Pedidos ('.$cant_pedido.')
</a><br>';

$boton_meses = '<a data-html="true" class="btn btn-outline-dark btn-sm" href="ver_mensualidades_del_usuario.php?id='.$a.'&usuario='.$c.'&nombre_usuario='.$b.'" data-toggle="popover" title="VER MENSUALIDADES" data-content="<b> '.$b.'</b>.<br>Ha realizado el pago de '.$cant_meses.' Mensualidades">Mensualidades ('.$cant_meses.')
</a><br>';
//$boton_enviar_mensaje = '<a data-html="true" class="btn btn-outline-info btn-sm" href="enviar_correo_a_usuario.php?id='.$a.'&usuario='.$c.'&nombre_usuario='.$b.'" data-toggle="popover" title="Enviar Mensaje" data-content="Enviarle un correo a: <b> '.$b.'</b>.">Enviar Correo <i class="fa fa-envelope"></i></a>';


$boton_enviar = '<div data-html="true" href="#" data-toggle="popover" title="ENVIAR CORREO" data-content="Enviar Correo a Usuario <br> <b>'.$b.'</b>.">
Email <i class="fa fa-envelope"></i>
</div>';

$boton_enviar_mensaje = '<!-- Large modal -->
<button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target=".bd-example-modal-lg'.$a.'">'.$boton_enviar.'</button>';


$modal_enviar_mensaje = '
<div class="modal fade bd-example-modal-lg'.$a.'" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">

    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Enviar Correo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">


      Enviar correo a: '.$b;


  $editar_contenido = ' <form autocomplete="off" class="was-validated" method="post" action= "#">';





  $editar_contenido .= '<input type="hidden" name="nombre" value="'.$b.'">';

  $editar_contenido .= '<input type="hidden" name="email" value="'.$email.'">';

  $editar_contenido .= '<input type="hidden" name="id" value="'.$a.'">';

  $editar_contenido .= '<input type="hidden" name="usua" value="'.$usua.'">';

  $editar_contenido .= '<input type="hidden" name="destinatario" value="'.$c.'">';

  $editar_contenido .= '<div class="form-group">
  <label for="asunto">Asunto</label>
  <input type="text" class="form-control" id="asunto" aria-describedby="asunto" placeholder="Ingrese el asunto del MSN" name="asunto" required>
  <div class="invalid-feedback">Debe indicar el asunto del MSN.</div>
  </div>';

  $editar_contenido .= '<label for="mensaje">Mensaje</label>
<textarea width = "100%" type="text" class="form-control summernote" id="mensaje" aria-describedby="mensaje" placeholder="Ingrese el mensaje" name="mensaje" ></textarea>
';


$modal_enviar_mensaje .=  $editar_contenido;

$modal_enviar_mensaje .= '<div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                  <button type="submit" class="btn btn-primary" name="enviar_msn_btn">Enviar</button>



</form>

</div> </div>
    </div>
  </div>
</div>';

echo $modal_enviar_mensaje;



$accion = '<div class="btn-group-vertical" >' . $boton_editar2 . $boton_pedidos . $boton_meses . $boton_enviar_mensaje .'</div>';


}

// BORRAR USUARIO DEL SISTEMA
function borrar_usuario(){
  global $db;

  $idusuario          =  e($_REQUEST['id']);

$_SESSION['usuarios']  = "Se borrara al usuario $idusuario y esta Funcionando";
//header('location: usuarios.php');
}



$resultado_estadistica ="";







function editar_mensajeria(){
  global $db;

  $rowid = e($_REQUEST['id']);

  $query = "SELECT mensajes.*, users.nombre, users.email, users.username 
            FROM mensajes 
            INNER JOIN 
            users ON mensajes.destinatario = users.username 
            WHERE mensajes.id = ?";
  
  $stmt = $db->prepare($query);
  $stmt->bind_param('i', $rowid);
  $stmt->execute();
  $resultado = $stmt->get_result();
  $rows = $resultado->num_rows;
  $row = $resultado->fetch_assoc();

  if ($rows < 1){
    $_SESSION['editar_mensajeria'] = "Lo sentimos, algo ha ocurrido.<br>";
  } else {
    $id = $row['id'];
    $asunto = $row['asunto'];
    $contenido = $row['contenido'];
    $nombre = $row['nombre'];
    $email = $row['email'];
    $destinatario = $row['destinatario'];

    $editar_contenido = '<form autocomplete="off" class="was-validated" method="post" action="editar_mensajeria.php?id='.$id.'">';
    $editar_contenido .= '<div class="form-group">
                            <label for="asunto">Asunto</label>
                            <input type="text" class="form-control" id="asunto" aria-describedby="asunto" placeholder="Ingrese el asunto" name="asunto" value="'.$asunto.'">
                            <label for="contenido">Contenido</label>
                            <textarea type="text" class="form-control" id="contenido" aria-describedby="contenido" placeholder="Ingrese el contenido" name="contenido">'.$contenido.'</textarea>
                            <div class="invalid-feedback">Debe indicar el contenido.</div>
                            <input type="hidden" name="nombre" value="'.$nombre.'">
                            <input type="hidden" name="email" value="'.$email.'">
                            <input type="hidden" name="destinatario" value="'.$destinatario.'">
                          </div>';
    $editar_contenido .= '<button type="submit" class="btn btn-primary" name="editar_mensajeria_btn">Enviar</button>';
    echo $editar_contenido;
  }

  $stmt->close();
}



function modal_edicion_usuario(){
    global $rowid, $idusuario, $nombre_usuario, $email_usuario, $telefono_usuario, $celular_usuario, $direccion_usuario, $ciudad_usuario, $estado_usuario, $status_usuario;

    $acciones_usuario = '
    <!-- Button trigger modal -->
    <input type="hidden" name="id" value="'.$rowid.'">
    <a class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#exampleModal" href="#">
      Editar
    </a>
    ';

$acciones_usuario .= ' <!-- Modal DEL Boton Editar -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editar Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">';



      $acciones_usuario .= ' <form autocomplete="off" class="was-validated" method="post" action= "usuarios.php">';

      $acciones_usuario .= 'Identificador: ' .$rowid;

      $acciones_usuario .= '<div class="form-group">
      <label for="idusuario">Id del Usuario</label>
      <input type="text" class="form-control" id="idusuario" aria-describedby="idusuario" placeholder="Ingrese el idusuario" name="idusuario" value="';
      $acciones_usuario .= $idusuario;
      $acciones_usuario .= '" required>
      <div class="invalid-feedback">Debe indicar el numero de ID del Usuario.</div>
      </div>

      <div class="form-group">
      <label for="nombre_usuario">Nombre del Usuario</label>
      <input type="text" class="form-control" id="nombre_usuario" aria-describedby="nombre_usuario" placeholder="Ingrese el Nombre del Usuario" name="nombre_usuario" value="';
      $acciones_usuario .= $nombre_usuario;
      $acciones_usuario .= '" required>
      <div class="invalid-feedback">Debe indicar el numero de ID del Usuario.</div>
      </div>

      <div class="form-group">
      <label for="email_usuario">Email del Usuario</label>
      <input type="text" class="form-control" id="email_usuario" aria-describedby="email_usuario" placeholder="Ingrese el Email del Usuario" name="email_usuario" value="';
      $acciones_usuario .= $email_usuario;
      $acciones_usuario .= '" required>
      <div class="invalid-feedback">Debe indicar el Email del Usuario.</div>
      </div>

      <div class="form-group">
      <label for="telefono_usuario">Telefono del Usuario</label>
      <input type="text" class="form-control" id="telefono_usuario" aria-describedby="telefono_usuario" placeholder="Ingrese el Telefono del Usuario" name="telefono_usuario" value="';
      $acciones_usuario .= $telefono_usuario;
      $acciones_usuario .= '" required>
      <div class="invalid-feedback">Debe indicar el Telefono del Usuario.</div>
      </div>

      <div class="form-group">
      <label for="celular_usuario">Celular del Usuario</label>
      <input type="text" class="form-control" id="celular_usuario" aria-describedby="celular_usuario" placeholder="Ingrese el Celular del Usuario" name="celular_usuario" value="';
      $acciones_usuario .= $celular_usuario;
      $acciones_usuario .= '" required>
      <div class="invalid-feedback">Debe indicar el Celular del Usuario.</div>
      </div>

      <div class="form-group">
      <label for="direccion_usuario">Direccion del Usuario</label>
      <input type="text" class="form-control" id="direccion_usuario" aria-describedby="direccion_usuario" placeholder="Ingrese la Direccion" name="direccion_usuario" value="';
      $acciones_usuario .= $direccion_usuario;
      $acciones_usuario .= '" required>
      <div class="invalid-feedback">Debe indicar el Direccion del Usuario.</div>
      </div>

      <div class="form-group">
      <label for="ciudad_usuario">Ciudad del Usuario</label>
      <input type="text" class="form-control" id="ciudad_usuario" aria-describedby="ciudad_usuario" placeholder="Ingrese la Ciudad" name="ciudad_usuario" value="';
      $acciones_usuario .= $ciudad_usuario;
      $acciones_usuario .= '" required>
      <div class="invalid-feedback">Debe indicar la Ciudad del Usuario.</div>
      </div>

      <div class="form-group">
      <label for="estado_usuario">Estado del Usuario</label>
      <input type="text" class="form-control" id="estado_usuario" aria-describedby="estado_usuario" placeholder="Ingrese el Estado" name="estado_usuario" value="';
      $acciones_usuario .= $estado_usuario;
      $acciones_usuario .= '" required>
      <div class="invalid-feedback">Debe indicar el Estado de ubicacion del Usuario.</div>
      </div>

      <div class="form-group">
      <label for="estado_usuario">Status del Usuario</label>
      <input type="text" class="form-control" id="status_usuario" aria-describedby="status_usuario" placeholder="Ingrese el Status" name="status_usuario" value="';
      $acciones_usuario .= $status_usuario;
      $acciones_usuario .= '" required>

      <div class="invalid-feedback">Debe indicar el Status del Usuario 1 Para activarlo y 0 para Desactivarlo.</div>
      </div>


      <button type="submit" class="btn btn-primary" name="editar_usuario_btn">Enviar</button>

      </form>';


      $acciones_usuario .= '</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Guardar Cambios</button>
      </div>
    </div>
  </div>
</div>';


}

function activar_desactivar() {
  global $db, $logo, $footer_correo;
  $id    = e($_REQUEST['id']);

  $query  = "SELECT * FROM users WHERE idusuario = '$id'";
  $resultado = mysqli_query($db, $query) or mysqli_error($db);
    while ($row = mysqli_fetch_assoc($resultado))
     {
       $nombre = $row['nombre'];
       $rowUser = $row['idusuario'];

     }

     $a = "Bloquear Usuario";

     $salida = '<b>'. strtoupper($a).'</b><br>'.strtoupper($a) .'<br> Usuario: '. $nombre . '<br> Identificador: '. $rowUser . '<br>';

     $editar_contenido = ' <form autocomplete="off" class="was-validated" method="post" action= "activar_desactivar.php?id='.$id.'">';

  $editar_contenido .= '<label for="motivo">Motivo del Bloqueo</label>
<textarea width = "100%" type="text" class="form-control" id="motivo" aria-describedby="motivo" placeholder="Ingrese el motivo" name="motivo" ></textarea>
';

$editar_contenido .= '<button type="submit" class="btn btn-primary" name="procesar_bloqueo_btn">Enviar</button>';


  echo '<div class="row">';
echo '<div class="col-xs-12 col-md-4">';
echo $salida;
  echo '</div>';

  echo '<div class="col-xs-12 col-md-8 form-group">';
  echo $editar_contenido;
  echo '</div>';

  echo '</div>';

}

function procesar_bloqueo(){
  global $db, $logo, $footer_correo;
  $id = e($_GET['id']);
  $motivo  = e($_REQUEST['motivo']);

  $query  = "SELECT * FROM users WHERE idusuario = '$id'";
  $resultado = mysqli_query($db, $query) or mysqli_error($db);
    while ($row = mysqli_fetch_assoc($resultado))
     {
       $nombre = $row['nombre'];
       $rowUser = $row['idusuario'];
       $email = $row['email'];
     }

     $sql = "UPDATE users SET
     status = 0,
     motivo_bloqueo = '$motivo'
     WHERE
     idusuario = '$id'";
     $mensaje = "Se ha BLOQUEADO al usuario de manera correcta..!!<br>";

     if (mysqli_query($db, $sql)) {
      $_SESSION['activar_desactivar']  = $mensaje;


      $link_mensualidades = '<a href="https://virtual.jesuministrosymas.com.ve/u/usuario/mensualidades.php" target="_blank"><b> ACTIVAR ALGUN PLAN DISPONIBLE </b></a>';

      $link_contactanos = '<a href="https://virtual.jesuministrosymas.com.ve/u/usuario/mensajeria.php" target="_blank"><b> CONTACTANOS AQUI </b></a>';

      $link_cancelar_ggroups = '<a href="mailto:gestionderecargas+unsubscribe@googlegroups.com">gestionderecargas+unsubscribe@googlegroups.com</a>';


	$asunto = "Su Usuario ha Sido Bloqueado";

	$cuerpo = "Hola $nombre <br><br><p>Le informamos que su usuario ha sido bloqueado por el siguiente motivo:</p><p> $motivo. </p><p> Con esta accion su usuario se bloqueará y lamentablemente ya no podrás utilizar el sitio..!</p><p>Si considera que es un error en cualquier momento puede favor comuniquese con nosotros para reconsiderar el bloqueo de su usuario.</p><p>Si considera que es un error, puede comunicarse respondiendo este correo o ingresando al modulo de Mensajerias de la plataforma $link_contactanos </p><p>No te preocupes, ahora es posible reactivar tu usuario de manera automatica solo debes efectuar el pago de algunas de las mensualidades disponibles hoy mismo, puedes hacerlo ingresando a: $link_mensualidades </p><p>Si desea dejar de recibir mensajeria instantanea de la plataforma puedes hacerlo en cualquier momento: <p>Para cancelar la suscripción al grupo de distribucion masiva de informacion es sencillo, envía un correo electrónico con cualquier contenido al correo $link_cancelar_ggroups y listo de manera automatica dejara de recibibir correos automatizados del sistema</p>";

  enviarEmail($email, $nombre, $asunto, $cuerpo);

    $_SESSION['activar_desactivar']  .= '<i class="fa fa-envelope"></i> Le hemos enviado un Email a ' .$nombre.' avisandole que ha sido suspendido..!!';


   } else {
    $_SESSION['activar_desactivar']  = '<i class="fa fa-exclamation-triangle fa-fw"></i>Algo ha ocurrido al intentar bloquear a: '.$nombre.' Error updating record: '. mysqli_error($db);
      mysqli_close($db);
   }

    // $_SESSION['activar_desactivar']  = '<i class="fa fa-exclamation-triangle fa-fw"></i> Actualizacion aplicable a '.$nombre.'<br>Con el motivo '.$motivo.'.<br>';

}


function enviar_msn(){
  global $db, $logo, $footer_correo, $usua;
  $id    = e($_REQUEST['id']);
  $nombre  = e($_REQUEST['nombre']);
  $email  = e($_REQUEST['email']);
  $asunto  = e($_REQUEST['asunto']);
  $mensaje  = e($_REQUEST['mensaje']);
  $origen  = $usua;
  $destinatario  = e($_REQUEST['destinatario']);

  $query = "INSERT INTO mensajes (id, asunto, contenido, origen, destinatario) VALUES (null, '$asunto', '$mensaje',' $origen', '$destinatario')";

   if (mysqli_query($db, $query)) {

  $_SESSION['msn']  = "Se ha guardado en la Base de datos el Mensaje para $nombre destinatario $destinatario y origen: $origen Y se enviara un correo al correo $email notificando de esta accion, el asunto es $asunto y el contendio es: $mensaje";

  $asunto2 = "$asunto";
  $cuerpo = "Hola $nombre <br><br>Le informamos que tiene un nuevo mensaje.<br><br><b>$asunto</b><br><br>$mensaje";

  enviarEmail($email, $nombre, $asunto2, $cuerpo);

   } else {
    $_SESSION['msn']  .= '<i class="fa fa-exclamation-triangle"></i> Algo ha.<br>'. mysqli_error($db);

   }

}



function rechazar_pagos(){
  global $db, $logo, $footer_correo;

  $id         = e($_REQUEST['id']);
  $rowUser    = e($_REQUEST['user']);
  $a          = e($_REQUEST['asunto']);




  if ($a == 'mensualidad') {
    $query = "SELECT pagos.*, users.nombre, users.email, users.username FROM pagos INNER JOIN users  ON pagos.user=users.idusuario WHERE pagos.id = '$id' ";
    $resultado = mysqli_query($db, $query) or mysqli_error($db);
    while ($row = mysqli_fetch_assoc($resultado))
     {

        $monto          = $row['monto'];
        $banco_emisor   = $row['banco_origen'];
        $banco_destino  = $row['banco_destino'];
        $nro_transf     = $row['nro_transf'];
        $ci_nro_cuenta  = $row['ci_nro_cuenta'];
        $fecha_transf   = $row['fecha_transf'];
        $plan           = $row['afiliacion'];
        $concepto       = $row['concepto'];
        $nombre         = $row['nombre'];
        $email         = $row['email'];

    $date = date_create($fecha_transf);
    $fecha = date_format($date, 'd-m-Y');
    $fecha_de_transf = $fecha;
    $monto = number_format($monto, 2, ',', '.');

    $resumen = 'Por un Monto de: '.$monto . ' Bs. <br>
    Desde el Banco: '. $banco_emisor . ' <br>
    A nuestra Cuenta del: '. $banco_destino . ' <br>
    Numero de Transferencia: '. $nro_transf . '<br>
    Numero de Cedula del titular de la cuenta origen: '. $ci_nro_cuenta . '<br>
    Efectuado en fecha: '. $fecha_de_transf . '<br> ';

  }


  } else if ($a == 'pedido') {

    $query = "SELECT pedidos.*, users.id AS 'id_usuario', users.nombre, users.email, users.username FROM pedidos INNER JOIN users  ON pedidos.usuario=users.idusuario WHERE pedidos.id = '$id' ";
    $resultado = mysqli_query($db, $query) or mysqli_error($db);
    while ($row = mysqli_fetch_assoc($resultado))
     {

        $id_usuario     = $row['id_usuario'];
        $montoA         = $row['monto'];
        $banco_emisor   = $row['banco_emisor'];
        $banco_destino  = $row['banco_destino'];
        $nro_transf     = $row['nro_transf'];
        $ci_nro_cuenta  = $row['ci_nro_cuenta'];
        $fecha_transf   = $row['fecha_transf'];
        $nombre         = $row['nombre'];
        $email          = $row['email'];
        $operador       = $row['operador'];

    $date = date_create($fecha_transf);
    $fecha = date_format($date, 'd-m-Y');
    $fecha_pedido = $fecha;

    $monto = number_format($montoA, 2, ',', '.');

    $resumen = '
    Por un Monto de: '.$monto . ' Bs. <br>
    Desde el Banco: '. $banco_emisor . ' <br>
    A nuestra Cuenta del: '. $banco_destino . ' <br>
    Numero de Transferencia: '. $nro_transf . '<br>
    Numero de Cedula del titular de la cuenta origen: '. $ci_nro_cuenta . '<br>
    Efectuado en fecha: '. $fecha_pedido . '<br> ';

    }

  }

  $salida = '<b>'. strtoupper($a).'</b><br>'
  .strtoupper($a) .
  ' Identificador '. $id .
  '<br> Id Usuario: ' . $id_usuario .
  '<br> Nombre: '. $nombre .
  '<br> Identificador: '. $rowUser .
  '<br>'. $resumen;

  $salida_codificada = '<b>'. strtoupper($a).'</b><br>'.strtoupper($a) .' Identificador '. base64_encode($id) . '<br> Del Usuario: '. $nombre . '<br> Identificador: '. $rowUser . '<br>'. $resumen;
  // base64_decode PARA DECODIFICAR

  $editar_contenido = '<form autocomplete="off" class="was-validated" method="post" action= "rechazar.php">';

  $editar_contenido .= '
  <input type="hidden" name="id_usuario" value="'.$id_usuario.'">
  <input type="hidden" name="id" value="'.$id.'">
  <input type="hidden" name="user" value="'.$rowUser.'">
  <input type="hidden" name="asunto" value="'.$a.'">
  <input type="hidden" name="contenido" value="'.$salida_codificada.'">
  <input type="hidden" name="nro_transf" value="'.$nro_transf.'">
  <input type="hidden" name="nombre" value="'.$nombre.'">
  <input type="hidden" name="email" value="'.$email.'">
  <input type="hidden" name="concepto" value="'.@$concepto.'">
  <input type="hidden" name="operador" value="'.@$operador.'">';

  $editar_contenido .= '<label for="motivo">Motivo del Rechazo</label>
<textarea width = "100%" type="text" class="form-control" id="motivo" aria-describedby="motivo" placeholder="Ingrese el motivo" name="motivo" ></textarea>

<hr><p>Favor verifique con su plataforma bancaria e intente efectuar nuevamente su declaracion de pago.</p><p>Si efectuo un pago inferior al monto declarado su pago sera rechazado y el monto sera automaticamente agregado a su billetera virtual con la finalidad de que lo pueda utilizar de alli.</p> <br><br><p><b>RECOMENDACIONES</b></p><ul><li>Procure hacer sus transferencias del mismo Banco, es decir si usted posee cuenta en el Banco Banesco, efectúe su transferencia al mismo Banco Banesco, evite hacer transferencias por ejemplo desde el Banco de Venezuela al Banco Banesco.</li><li>Le recordamos que el sistema no acepta el mismo numero de transferencia para el pago de planes, pedidos o recarga de Billetera.</li><li>Si desea efectuar adelantos de pagos, puede hacerlo desde su billetera <a href="https://virtual.jesuministrosymas.com.ve/u/usuario/billetera.php">Ir a Billetera Virtual</a>.</li></ul>

  <div class="form-group form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="billetera" value="'.$montoA.'">
    <label class="form-check-label" for="exampleCheck1">Devolver dinero a Billetera</label>
  </div>

  ';
$editar_contenido .= '<button type="submit" class="btn btn-primary" name="procesar_rechazo_de_pagos_btn">Rechazar</button></form>';


    echo '<div class="row">';
    echo '<div class="col-xs-12 col-md-4">';
    echo $salida;
    echo '</div>';

    echo '<div class="col-xs-12 col-md-8 form-group">';
    echo $editar_contenido;
    echo '</div>';

    echo '</div>';



}

function procesar_rechazo_de_pagos(){
    global $db, $fecha_act, $logo, $footer_correo;

    $status = "RECHAZADO";

   $id_usuario = e($_REQUEST['id_usuario']);
   $id = e($_REQUEST['id']);
   $user = e($_REQUEST['user']);
   $nombre = e($_REQUEST['nombre']);
   $email = e($_REQUEST['email']);
   $a = e($_REQUEST['asunto']);
   $contenido = e($_REQUEST['contenido']);
   $motivo = e($_REQUEST['motivo']);
   @$monto = e($_REQUEST['billetera']);

   $nro_transf  = $status .' ' . e($_REQUEST['nro_transf']) . ' ' . $status;

if ($a == 'mensualidad'){

  $query = "UPDATE pagos SET
  status_pago = '$status',
  motivo_rechazo = '$motivo',
  fecha_rechazo = '$fecha_act',
  nro_transf = '$nro_transf'
  WHERE id = '$id'";
  if (mysqli_query($db, $query)) {
      $_SESSION['rechazar']  = "Se ha Actualizado el STATUS del pago de Mensualidad a RECHAZADO..!!<br>";
      } else {
      echo "Error updating record: " . mysqli_error($db);
      //mysqli_close($db);
      }

} else if ($a == 'pedido'){

    $operador = e($_REQUEST['operador']);

  $query = "UPDATE pedidos SET
  status_pedido = '$status',
  motivo_rechazo = '$motivo',
  fecha_rechazo = '$fecha_act',
  nro_transf = '$nro_transf'
  WHERE id = '$id'";
  if (mysqli_query($db, $query)) {
      $_SESSION['rechazar']  = "Se ha Actualizado el STATUS del Pedido a RECHAZADO..!!<br>";

      // if ($operador == $operador) {
        $sql = "UPDATE recargar SET
        status = 1,
        relacion = '$id'
        WHERE
        user = '$user' AND operador = '$operador' AND status = 2";
            if (mysqli_query($db, $sql)){
              $_SESSION['rechazar']  .= "Se ha Actualizado el status de la solicitud de recargas.<br>";
              }
              else {
              $_SESSION['rechazar']  = '<i class="fa fa-exclamation-triangle"></i>Algo ha ocurrido, intente efectuar el rechazo nuevamente. ' . mysqli_error($db);
              }
      // }


      } else {
      echo "No Se podido Actualizar el STATUS del Pedido a RECHAZADO el codigo error del sistema es el siguiente: <br>" . mysqli_error($db);
      //mysqli_close($db);
      }

}

// Actualizar billetera al recharzar pago
    if (isset($_REQUEST['billetera'])){

    //echo $_REQUEST['billetera']; // Muestra el CheckBox marcado.
    //Se devuelve monto positivo a billetera de cliente
    $descripcion = 'DEVOLUCION';
    $sql2 = "INSERT INTO billetera (id, id_usuario, monto, descripcion, id_descripcion, fecha, status) VALUES (null, '$id_usuario','$monto','$descripcion','$id',NOW(),1)";

    if (mysqli_query($db, $sql2)) {
    $_SESSION['rechazar']  .= "Se ha generado un registro de actualizacion de dinero en su Billetera.<br>";
    } else {
    $_SESSION['rechazar']  .= 'Algo ha ocurrido Actualizando su billetera, Error: ' . mysqli_error($db);
    }
} else {
  //Solo aplica para rechazo de ingresos a billetera
  //Update de tabla billetera
  // Status 2 Rechazado

  $sql_billetera = "UPDATE billetera SET
  descripcion = '$motivo',
  status = 2
  WHERE
  id_descripcion = '$id' ORDER BY id DESC LIMIT 1";

      if (mysqli_query($db, $sql_billetera)){
        $_SESSION['rechazar']  .= "Se ha Actualizado el status en la Billetera.<br>";
        }
        else {
        $_SESSION['rechazar']  = '<i class="fa fa-exclamation-triangle"></i>Algo ha ocurrido, intente efectuar el rechazo nuevamente. ' . mysqli_error($db);
        }
}

$asunto = "Se ha Rechazado su Pago";
$cuerpo = "Hola Usuario $nombre <br><br><b>Estimado Usuario. <br><br>Lamentamos informale que su pago con las siguientes caracteristicas:</b><br><p>$contenido.</p><br><b>HA SIDO RECHAZADO POR EL SIGUIENTE MOTIVO:</b><br><p>$motivo</p><br> <p>Favor verifique con su plataforma bancaria e intente efectuar nuevamente su declaracion de pago.</p><p>Si efectuo un pago inferior al monto declarado su pago sera rechazado y el monto sera automaticamente agregado a su billetera virtual con la finalidad de que lo pueda utilizar de alli.</p> <br><br><p><b>RECOMENDACIONES</b></p><ul><li>Procure hacer sus transferencias del mismo Banco, es decir si usted posee cuenta en el Banco Banesco, efectúe su transferencia al mismo Banco Banesco, evite hacer transferencias por ejemplo desde el Banco de Venezuela al Banco Banesco.</li><li>Le recordamos que el sistema no acepta el mismo numero de transferencia para el pago de planes, pedidos o recarga de Billetera.</li><li>Si desea efectuar adelantos de pagos, puede hacerlo desde su billetera <a href='https://virtual.jesuministrosymas.com.ve/u/usuario/billetera.php'>Ir a Billetera Virtual</a>.</li></ul>";

enviarEmail($email, $nombre, $asunto, $cuerpo);

 $_SESSION['rechazar']  .= '<i class="fa fa-envelope"></i> Se ha enviado un correo electronico notificando sobre este rechazo de pago..!!<br>';

}


// ANALISIS DE PEDIDOS POR CLIENTE

function analisis_pedidos_por_cliente($a) {
  global $db, $res;
  $query="SELECT SUM(CASE WHEN status_pedido = 'ENTREGADO' THEN 1 ELSE 0 END) AS 'entregado',
                 SUM(CASE WHEN status_pedido = 'RECHAZADO' THEN 1 ELSE 0 END) AS 'rechazado'
                 FROM pedidos
                 WHERE usuario = '$a'";
  $result = mysqli_query($db, $query);

  while ($row = mysqli_fetch_assoc($result))
  {
    $e = $row['entregado'];
    $r = $row['rechazado'];
  }

  if ($e<1){
$res = 'Primera Vez';
  } else if ($e==1) {
$res = 'Segunda vez';
  } else {
$res = 'Ha recibido: '.$e;
  }

  if ($r<1){
$res .= '';
  } else if ($r==1) {
$res .= '<br> Rechazado: 1';
  } else {
$res .= '<br>Rechazados: '.$r;
  }

}


//RESUMEN SUMA DE MENSUALIDAD
function suma_mensualidad(){
  global $db, $usua, $pendiente_mensualidad, $suma_mensualidad,$mes_de_pago_actual, $titulopag, $pmes, $fecha_sistema;

  if (isAdmin()) {
    // SI ES ADMIN
    //$sql="SELECT sum(monto) as total FROM pagos ";
    $sql="SELECT SUM(monto) AS 'total',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' THEN monto ELSE 0 END) AS 'mes',
    SUM(CASE WHEN status_pago = 'PENDIENTE' AND mes_de_pago ='$mes_de_pago_actual' THEN 1 ELSE 0 END) AS 'pendiente',
    SUM(CASE WHEN status_pago = 'APROBADO' AND mes_de_pago ='$mes_de_pago_actual' THEN 1 ELSE 0 END) AS 'aprobado',
    SUM(CASE WHEN status_pago = 'APROBADO' THEN 1 ELSE 0 END) AS 'aprobado_general',
    SUM(CASE WHEN status_pago = 'APROBADO' THEN monto ELSE 0 END) AS 'monto_aprobado_general',
    SUM(CASE WHEN  mes_de_pago ='$mes_de_pago_actual' AND status_pago = 'APROBADO' AND afiliacion = 'BASICO' THEN 1 ELSE 0 END) AS 'cantidad_basico',
    SUM(CASE WHEN  mes_de_pago ='$mes_de_pago_actual' AND status_pago = 'APROBADO' AND afiliacion = 'BASICO' THEN monto ELSE 0 END) AS 'monto_basico',
    SUM(CASE WHEN  mes_de_pago ='$mes_de_pago_actual' AND status_pago = 'APROBADO' AND afiliacion = 'AVANZADO' THEN monto ELSE 0 END) AS 'monto_avanzado',
    SUM(CASE WHEN  mes_de_pago ='$mes_de_pago_actual' AND status_pago = 'APROBADO' AND afiliacion = 'AVANZADO' THEN 1 ELSE 0 END) AS 'cantidad_avanzado',
    SUM(CASE WHEN  mes_de_pago ='$mes_de_pago_actual' AND status_pago = 'APROBADO' AND afiliacion = 'VIP' THEN monto ELSE 0 END) AS 'monto_vip',
    SUM(CASE WHEN  mes_de_pago ='$mes_de_pago_actual' AND status_pago = 'APROBADO' AND afiliacion = 'VIP' THEN 1 ELSE 0 END) AS 'cantidad_vip',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_MOVILNET' THEN monto ELSE 0 END) AS 'monto_movilnet',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_MOVILNET' THEN 1 ELSE 0 END) AS 'cantidad_movilnet',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_MOVISTAR' THEN monto ELSE 0 END) AS 'monto_movistar',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_MOVISTAR' THEN 1 ELSE 0 END) AS 'cantidad_movistar',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_DIGITEL' THEN monto ELSE 0 END) AS 'monto_digitel',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_DIGITEL' THEN 1 ELSE 0 END) AS 'cantidad_digitel',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_DIRECTV' THEN monto ELSE 0 END) AS 'monto_directv',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_DIRECTV' THEN 1 ELSE 0 END) AS 'cantidad_directv',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_INTER' THEN monto ELSE 0 END) AS 'monto_inter',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_INTER' THEN 1 ELSE 0 END) AS 'cantidad_inter'
    FROM pagos";
    $result = mysqli_query($db, $sql);

    while ($row = mysqli_fetch_assoc($result))
  {
    if ($row['total']>0){


    $suma_mensualidad = "Total General ".number_format($row['monto_aprobado_general'], 2, ',', '.')." Bs.<br>";
    $suma_mensualidad .= "<b>En el Mes " . $mes_de_pago_actual ."<br> </b>" ;

    $pmes=$row['mes'];
    $suma_mensualidad .=  "Total " . number_format($row['mes'], 2, ',', '.')."<br>";
    $suma_mensualidad .= "Aprobados " .$row['aprobado']."<br>";
    $suma_mensualidad .= "Pendientes " .$row['pendiente']."<br>";
    $suma_mensualidad .= "Basico " .$row['cantidad_basico']. " = ".number_format($row['monto_basico'], 2, ',', '.')." Bs.<br>";
    $suma_mensualidad .= "Avanzado " .$row['cantidad_avanzado']. " = ".number_format($row['monto_avanzado'], 2, ',', '.')." Bs.<br>";
    $suma_mensualidad .= "VIP " .$row['cantidad_vip']. " = ".number_format($row['monto_vip'], 2, ',', '.')." Bs.<br>";
    $suma_mensualidad .= "Movilnet " .$row['cantidad_movilnet']. " = ".number_format($row['monto_movilnet'], 2, ',', '.')." Bs.<br>";
    $suma_mensualidad .= "Movistar " .$row['cantidad_movistar']. " = ".number_format($row['monto_movistar'], 2, ',', '.')." Bs.<br>";
    $suma_mensualidad .= "Digitel " .$row['cantidad_digitel']. " = ".number_format($row['monto_digitel'], 2, ',', '.')." Bs.<br>";
    $suma_mensualidad .= "Directv " .$row['cantidad_directv']. " = ".number_format($row['monto_directv'], 2, ',', '.')." Bs.<br>";
    $suma_mensualidad .= "Inter " .$row['cantidad_inter']. " = ".number_format($row['monto_inter'], 2, ',', '.')." Bs.<br>";

    // $pendiente_mensualidad = $row['pendiente'];

        } else  if ($row['total']==0) {


      $suma_mensualidad = "No hay datos";
     // $pendiente_mensualidad = "";

  }

  if ($row['pendiente']==0){
    $pendiente_mensualidad = "";
  } else
  {
    $pendiente_mensualidad = $row['pendiente'];

  }



  }




} else {
  // SI ES USUARIO
  $sql="SELECT sum(monto) as total,
  SUM(CASE WHEN (status_pago = 'PENDIENTE' OR status_pago = 'APROBADO' OR status_pago = 'RECHAZADO' ) THEN 1 ELSE 0 END) AS 'todo',
  SUM(CASE WHEN status_pago = 'PENDIENTE' THEN 1 ELSE 0 END) AS 'pendiente',
  SUM(CASE WHEN status_pago = 'RECHAZADO' THEN 1 ELSE 0 END) AS 'rechazado',
  SUM(CASE WHEN status_pago = 'APROBADO' THEN 1 ELSE 0 END) AS 'aprobado',
  SUM(CASE WHEN status_pago = 'APROBADO' AND afiliacion = 'BASICO' THEN 1 ELSE 0 END) AS 'basico',
  SUM(CASE WHEN status_pago = 'APROBADO' AND afiliacion = 'AVANZADO' THEN 1 ELSE 0 END) AS 'avanzado',
  SUM(CASE WHEN status_pago = 'APROBADO' AND afiliacion = 'VIP' THEN 1 ELSE 0 END) AS 'vip',
  SUM(CASE WHEN status_pago = 'APROBADO' AND afiliacion = 'MENS_MOVISTAR' THEN 1 ELSE 0 END) AS 'movistar',
  SUM(CASE WHEN status_pago = 'APROBADO' AND afiliacion = 'MENS_DIGITEL' THEN 1 ELSE 0 END) AS 'digitel',
  SUM(CASE WHEN status_pago = 'APROBADO' AND afiliacion = 'MENS_DIRECTV' THEN 1 ELSE 0 END) AS 'directv',
  SUM(CASE WHEN status_pago = 'APROBADO' AND afiliacion = 'MENS_INTER' THEN 1 ELSE 0 END) AS 'inter'
  FROM pagos
  WHERE user = '$usua' ";
  $result = mysqli_query($db, $sql);

  while ($row = mysqli_fetch_assoc($result))
  {
    if ($row['todo']<1){
      echo "En este momento no hay datos que permitan mostrar estadisticas.";
        } else {

          if ($titulopag == 'Mensualidades') {

            echo '<b class="card-title text-uppercase">Resumen</b><br>';
            echo "Cantidad de Pagos de Mensualidades Aprobadas = " .$row['aprobado']."<br>";
            echo "Cantidad de Pagos de Mensualidades Pendientes = ".$row['pendiente']."<br>";
            echo "Cantidad de Pagos de Mensualidades Rechazados = ".$row['rechazado']."<br>";

            echo '<b class="card-title text-uppercase">Operadora Publica Movilnet</b><br>';
            echo "Cantidad de Plan Basico activados = ".$row['basico']."<br>";
            echo "Cantidad de Plan Avanzado activados = ".$row['avanzado']."<br>";
            echo "Cantidad de Plan Vip activados = ".$row['vip']."<br>";

            echo '<b class="card-title text-uppercase">Operadoras Privadas</b><br>';
            echo "Cantidad de Mensualidades Movistar activados = ".$row['movistar']."<br>";
            echo "Cantidad de Mensualidades Digitel activados = ".$row['digitel']."<br>";
            echo "Cantidad de Mensualidades Directv activados = ".$row['directv']."<br>";
            echo "Cantidad de Mensualidades Inter activados = ".$row['inter']."<br>";




          } else {


     echo '<b class="card-title text-uppercase">Resumen</b><br>';
     echo "Aprobados " .$row['aprobado']."<br>";
     echo "Pendientes ".$row['pendiente']."<br>";
     echo "Rechazados ".$row['rechazado']."<br>";

     echo '<b class="card-title text-uppercase">Movilnet</b><br>';
     echo "Plan Basico ".$row['basico']."<br>";
     echo "Plan Avanzado ".$row['avanzado']."<br>";
     echo "Plan Vip ".$row['vip']."<br>";

     echo '<b class="card-title text-uppercase">Privadas</b><br>';
     echo "Movistar ".$row['movistar']."<br>";
     echo "Digitel ".$row['digitel']."<br>";
     echo "Directv ".$row['directv']."<br>";
     echo "Inter ".$row['inter']."<br>";
     }

  }
}
  //mysqli_close($db);}

}}


//DETALLADO SUMA MENSUALIDAD
function detallado_suma_mensualidad(){
  global $db, $usua;
  if (isAdmin()) {
    //$sql="SELECT sum(monto) as total FROM pagos ";
    $sql="SELECT sum(monto) AS 'total',
    SUM(CASE WHEN status_pago = 'PENDIENTE' THEN 1 ELSE 0 END) AS 'pendiente',
      SUM(CASE WHEN status_pago = 'APROBADO' THEN 1 ELSE 0 END) AS 'aprobado'
    FROM pagos ";
    $result = mysqli_query($db, $sql);

    while ($row = mysqli_fetch_assoc($result))
  {
    if ($row['total']<1){
      echo "No hay datos";
        } else {
     echo "Cantidad en Bs. Pagados a la fecha ".number_format($row['total'],2,',','.') ." Bs<br>";
     echo "Cantidad de Pagos Aprobados " .$row['aprobado']."<br>";
     echo "Cantidad de Pagos Pendientes ".$row['pendiente']."<br>";

  }

  }} else {
  $sql="SELECT sum(monto) as total,
  SUM(CASE WHEN status_pago = 'PENDIENTE' THEN 1 ELSE 0 END) AS 'pendiente',
  SUM(CASE WHEN status_pago = 'APROBADO' THEN 1 ELSE 0 END) AS 'aprobado'
  FROM pagos
  WHERE user = '$usua' ";
  $result = mysqli_query($db, $sql);

  while ($row = mysqli_fetch_assoc($result))
  {
    if ($row['total']<1){
      echo "No hay Pagos Aprobados";
        } else {
     //echo "Cantidad en Bs. Pagados a la fecha ".$row['total']." Bs.<br>";
     echo "<h2>Resumen</h2><br>";
     echo "Cantidad de Pagos Aprobados " .$row['aprobado']."<br>";
     echo "Cantidad de Pagos Pendientes ".$row['pendiente']."<br>";
  }
}
//  mysqli_close($db);
}

}


// PAGO DE MENSUALIDAD
function verificar_pago_mes() {
	global $db, $username, $usua, $mes_de_pago_actual;

	$queryvpm = "SELECT * FROM pagos WHERE user = '$usua' AND mes_de_pago = '$mes_de_pago_actual'";
	$resultvpm = mysqli_query($db, $queryvpm);
	$rowsvpm =  mysqli_num_rows($resultvpm);

    if ($rowsvpm > 0){
	echo '<div class="alert alert-info" role="alert" >
	<h3>'
.$mes_de_pago_actual .' Pagado		</h3>
</div>';
    } else {
		echo '<div class="alert alert-danger" role="alert" >
        <h3>
Lo sentimos usted no ha efectuado el pago correspondiente a ' .$mes_de_pago_actual .'
        </h3>
	</div>';
	exit;
    }}

//PARA EL MODAL DE PAGO DE MENSUALIDAD
function pago_mensualidad(){
    global $db, $username, $usua, $ci_nro_cuenta, $monto_mensualidad, $nro_transf, $banco_emisor, $banco_destino, $fecha_transf, $status_pedido, $fecha_pedido, $status_pago, $fecha_aprobacion,$mes_de_pago_actual, $debe_pagar, $operador, $concepto, $link, $accion, $mens_monto_favor, $monto_favor, $cuentas_bancarias;

    selector_operador();

    $queryvpm = "SELECT * FROM pagos WHERE user = '$usua' AND mes_de_pago = '$mes_de_pago_actual' AND concepto = '$concepto' AND (status_pago = 'APROBADO' OR status_pago = 'PENDIENTE') ";
	$resultvpm = mysqli_query($db, $queryvpm);
	$rowsvpm =  mysqli_num_rows($resultvpm);
    $rowsvpma =  mysqli_fetch_assoc($resultvpm);


    // if (isActive()){

        //if ($rowsvpm > 0){
            if ($rowsvpma['status_pago'] == 'PENDIENTE'){
                echo '<div class="alert alert-danger" role="alert" >
            <h3>YA USTED EFECTUO EL PAGO DEL MES DE <b>'
        .strtoupper($mes_de_pago_actual) .'</b> Y EL STATUS DE DICHO PAGO ES <b>'.$rowsvpma['status_pago'].'</b> DEBE ESPERAR QUE SU PAGO SEA APROBADO PARA QUE PUEDA ACCEDER AL MODULO DE PEDIDOS <a class = "link" href="pedidos_movilnet.php">AQUI</a></h3>
        </div>';
            }
            else if ($rowsvpma['status_pago'] == 'APROBADO'){
                echo '<div class="alert alert-info" role="alert" >
            <h3>YA USTED EFECTUO EL PAGO DEL MES DE <b>'
        .strtoupper($mes_de_pago_actual) .'</b> Y EL STATUS DE DICHO PAGO ES <b>'.$rowsvpma['status_pago'].'</b> YA PUEDE ACCEDER AL MODULO DE PEDIDOS <a class = "link" href="pedidos_movilnet.php">AQUI</a></h3><p>SI HA EFECTUADO UN PAGO DE MEJORA DE SU PLAN DEBE ESPERAR QUE EL MISMO SEA CONFORMADO PARA QUE PUEDA DISFRUTAR DE LOS BENEFICIOS DE DICHO PLAN</p>
        </div>';
            }

            //}
             else {

               a_favor();
               echo $mens_monto_favor;
               $monto_favor = $GLOBALS['monto_a_favor'];


                echo $cuentas_bancarias;
contenido('bancario');

                echo '<hr>';

      $inicio = new DateTime();
      $fin = new DateTime();
      $fin = $fin->modify('last day of this month');

      $hoy_a = date('d/m/Y');
      $fin_a = $fin->format('d/m/Y');

      $interval = $inicio->diff($fin);
      $interval = $interval->days .' Dias';

                echo '<div class="alert alert-warning" role="alert"><h5>Vigencia de su Plan '.$operador.'</h5>Por ejemplo:<br>Aprobandose su pago hoy: <b>'. $hoy_a .'</b><br>Su renta venceria el <b>'. $fin_a .'</b><br>Pudiendo Disfrutar su plan por los proximos: '. $interval .'

                </div>';
                echo '<hr>';


    echo ' <form autocomplete="off" class="was-validated" method="post" action= "mensualidad_movilnet.php">';
    //echo $status_usuario;

    echo '<div class="form-group">
    <label for="monto_mensualidad">Seleccione Monto de su Mensualidad</label>
    <select class="custom-select" id="monto_mensualidad" name="monto_mensualidad" value="';
    echo $monto_mensualidad;
    echo '" required >
    <option value="">Seleccione:</option>';
    monto_mensualidad_movilnet();
    echo '</select> <div class="invalid-feedback">Debe Seleccionar el monto de su transferencia.</div>
    </div>

    <div class="form-group">
    <label for="banco_emisor">Desde Que banco Transfirio</label>
    <select class="custom-select" id="banco_emisor" name="banco_emisor" value="';
    echo $banco_emisor;
    echo '" required >
    <option value="">Seleccione:</option>';
    banco_emisor();
    echo '</select> <div class="invalid-feedback">Debe Seleccionar desde que banco efectuo su transferencia.</div>
    </div>

    <div class="form-group">
    <label for="banco_destino">A que Banco Transfirio</label>
    <select class="custom-select" id="banco_destino" name="banco_destino" value="';
    echo $banco_destino;
    echo '" required >
    <option value="">Seleccione:</option>';
    banco_destino();
    echo '</select>
    <div class="invalid-feedback">Debe Seleccionar a que banco usted efectuo su transferencia.</div>
    </div>

    <div class="form-group">
    <label for="nroTransf">Numero de Transferencia</label>
    <input pattern="[0-9]{8,15}" title = "Debe utilizar solo Numeros, Minimo 8 digitos y Maximo 15 digitos. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234"  type="text" class="form-control" id="nro_transf" aria-describedby="nro_transf" placeholder="Numero de Operacion Bancaria" name="nro_transf" value="';
    echo $nro_transf;
    echo '" required>
    <div class="invalid-feedback">Debe indicar el numero de operacion bancaria indicada por su Banco. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234</div>
    </div>

    <div class="form-group">
    <label for="ci_nro_cuenta">Cedula del Titular de la Cuenta Origen</label>
    <input  pattern="[0-9]{7,10}" title = "Debe utilizar solo Numeros, Minimo 7 digitos y Maximo 10 digitos"   type="text" class="form-control" id="ci_nro_cuenta" aria-describedby="ci_nro_cuenta" placeholder="Numero de Cedula Titular de la Cuenta Origen" name="ci_nro_cuenta" value="';
    echo $ci_nro_cuenta;
    echo '" required>
    <div class="invalid-feedback">Debe indicar el numero de cedula del titular de la cuenta desde donde usted efectuo su transferencia.</div>
    </div>

    <div class="form-group">
    <label for="fechaTransf">Fecha de su Transferencia</label>
    <input pattern="(?: 30)) | (? :(? : 0 [13578] | 1 [02]) - 31)) / (? :(?: 0 [1-9] | 1 [0-2]) - (?: 0 [1-9] | 1 [0 -9] | 2 [0-9]) | (? :( ?! 02) (?: 0 [1-9] | 1 [0-2]) / (?: 19 | 20) [0-9] {2}" title = "Debe utilizar el formato DD/MM/YYYY" type="date" class="form-control" id="fecha_transf" aria-describedby="fecha_transf" placeholder="Numero de Operacion Bancaria" name ="fecha_transf" value="';
    echo $fecha_transf;
    echo '" required>
    <div class="invalid-feedback">Debe Seleccione la fecha en que usted efectuo su transferencia.</div>
    </div>

    <input type="hidden" name="user" value="'.$usua.'">


    <button type="submit" class="btn btn-primary" name="pago_mensualidad_btn">Enviar</button>

    </form>';
    }
    // } else {
    //
    //     echo '<div class="alert alert-warning" role="alert" >
    //     <h3>SU USUARIO ESTA BLOQUEADO</h3>
    //     <p>Si considera que es un error, favor ingrese al area de <a target="_BLANK" href= "http://www.jesuministrosymas.com.ve/contactenos" ><b>CONTACTENOS</b></a> para mas informacion.</p>
    // </div>';
    // }
  }


  function verificar_pago_mensualidad(){
  	global $db, $usua, $mmo, $concepto, $operador, $link, $m_dias_r, $fecha_sistema, $como_pagar, $pago_mensualidad;

  	analisis_dias_restantes();
  	if ($pago_mensualidad == 0) {
  		// SI NO HAY MENSUALIDAD PAGA
  	$pago_mensualidad = '';
  	}
  	else {
  		// SI SE DETECTA PAGO DE MENSUALIDAD
  		$pago_mensualidad = '<hr>';
  		//$pago_mensualidad .= $img_recarga_sin_necesidad;
  		$pago_mensualidad .= '<div class="alert alert-warning" role="alert">USTED HA PAGADO SU MENSUALIDAD PARA USAR LA PLATAFORMA '.strtoupper ($operador) .' Y TIENE ACTIVO TODOS LOS MODULOS </div>';
  		$pago_mensualidad .= '';
  		$pago_mensualidad .= '';
  		$pago_mensualidad .= '<hr>';
  	}
  	echo $pago_mensualidad;
  }



  $m_dias_r ="";

  function analisis_dias_restantes(){
    global $db, $usua, $mmo, $concepto, $operador, $link, $m_dias_r, $fecha_sistema, $como_pagar, $pago_mensualidad, $link_recargas;

    selector_operador();

    $sql = "SELECT DATEDIFF(fin, NOW()) as DiasRestantes FROM pagos WHERE concepto = '$concepto' AND user = '$usua' AND (status_pago = 'APROBADO' OR status_pago = 'PENDIENTE') ORDER BY id DESC LIMIT 1";
$result = mysqli_query($db, $sql);

if ($result){
$row = mysqli_fetch_assoc($result);

//return $user;
if ($row['DiasRestantes']>0){


$como_pagar = "";
$m_dias_r = ' De la plataforma <b>'.$operador.'</b> le quedan <b>'.$row['DiasRestantes'].' Dias </b> Restantes para disfrutar de su plan de uso.<hr>';
$pago_mensualidad = 1;
 }



else {
  $como_pagar = $como_pagar;
  $m_dias_r = ' No se ha detectado pago de mensualidad para el uso del servicio de recargas <b>'.$operador.'</b><hr>';
  $pago_mensualidad = 0;
}
  } else {
    $como_pagar = $como_pagar;
    $m_dias_r = ' No se ha detectado pago de mensualidad para el uso del servicio de recargas <b>'.$operador.'</b><hr>';
    $pago_mensualidad = 0;
  }



}



 //ACTIVAR O SUSPENDER USUARIO
 function activar_bloquear_usuario() {
    global $db, $logo;

   $idusuario = e($_REQUEST['id']);
   $status_usuario = e($_REQUEST['status']);
   $nombre = e($_REQUEST['nombre']);
   $email = e($_REQUEST['email']);

if ($status_usuario==0){
  $sql = "UPDATE users SET
  status = 1,
  motivo_bloqueo = NULL
  WHERE id = '$idusuario'";
  $mensaje = "Se ha ACTIVADO al usuario de manera correcta..!!<br>";
  $asunto = "Su usuario ha sido Desbloqueado";
  $cuerpo = "Hola $nombre <br><br>  Le informamos que su usuario ha sido desbloqueado de manera exitosa y puede ingresar nuevamente a la plataforma con su usuario y clave. <br>";

  enviarEmail($email, $nombre, $asunto, $cuerpo);

  $mensaje .= '<i class="fa fa-envelope"></i> Hemos enviado Un correo a '.$nombre.' indicando que el usuario fue desbloqueado';

} else {

  $motivo = 'No se ha definido un motivo en particular, normalmente este tipo de bloqueo responde al hecho de que nunca ha utilizado la plataforma y el sistema le ha bloqueado como parte de un proceso de depuración de nuestro sistema, tambien el bloqueo puede responder al hecho de que nos hemos tratado de comunicar con usted via telefonica a los numeros de telefonos suministrados y los mismos son incorrectos o estan desconectados, por ello es importante que suministre informacion real y actualizada. En cualquier momento usted puede comunicarse via telefonica, Whatsapp o por Telegram para que podamos analizar su caso.';

  $sql = "UPDATE users SET
  status = 0,
  motivo_bloqueo = '$motivo'
  WHERE id = '$idusuario'";

  $link_mensualidades = '<a href="https://virtual.jesuministrosymas.com.ve/u/usuario/mensualidades.php" target="_blank"><b> ACTIVAR ALGUN PLAN DISPONIBLE </b></a>';

  $link_contactanos = '<a href="https://virtual.jesuministrosymas.com.ve/u/usuario/mensajeria.php" target="_blank"><b> CONTACTANOS AQUI </b></a>';

  $link_cancelar_ggroups = '<a href="mailto:gestionderecargas+unsubscribe@googlegroups.com">gestionderecargas+unsubscribe@googlegroups.com</a>';

$asunto = "Su Usuario ha Sido Bloqueado";

$cuerpo = "Hola $nombre <br><br><p>Le informamos que su usuario ha sido bloqueado por el siguiente motivo:</p><p> $motivo. </p><p> Con esta accion su usuario se bloqueará y lamentablemente ya no podrás utilizar el sitio..!</p><p>Si considera que es un error en cualquier momento puede favor comuniquese con nosotros para reconsiderar el bloqueo de su usuario.</p><p>Si considera que es un error, puede comunicarse respondiendo este correo o ingresando al modulo de Mensajerias de la plataforma $link_contactanos </p><p>No te preocupes, ahora es posible reactivar tu usuario de manera automatica solo debes efectuar el pago de algunas de las mensualidades disponibles hoy mismo, puedes hacerlo ingresando a: $link_mensualidades </p><p>Si desea dejar de recibir mensajeria instantanea de la plataforma puedes hacerlo en cualquier momento: <p>Para cancelar la suscripcion al grupo de distribucion masiva de informacion es sencillo, envía un correo electronico con cualquier contenido al correo $link_cancelar_ggroups y listo de manera automatica dejara de rcibibir correos automatizados del sistema</p>";

enviarEmail($email, $nombre, $asunto, $cuerpo);


  $mensaje = "Se ha BLOQUEADO al usuario de manera correcta..!!<br>";
  $mensaje .= "Se ha enviado una notificacion por correo electronico al usuario..!<br>";
}

if (mysqli_query($db, $sql)) {
   $_SESSION['usuarios']  = $mensaje;
   //header('location: usuarios.php');

} else {
   echo "Error updating record: " . mysqli_error($db);
   mysqli_close($db);
}
}



 //ACTIVAR O DESACTIVAR COMENTARIO
 function activar_desactivar_comentario() {
  global $db;

 $id = e($_REQUEST['id']);
 $visible = e($_REQUEST['visible']);
// $user = ($_REQUEST['user']);
 //$nombre = ($_REQUEST['nombre']);
 //$email = ($_REQUEST['email']);

if ($visible==0){
$sql = "UPDATE comentario SET
visible = 1
WHERE id = '$id'";
$mensaje =  'Se ha ACTIVADO este comentario al usuario de manera correcta..!!<br>';

} else {
$sql = "UPDATE comentario SET
visible = 0
WHERE id = '$id'";
$mensaje = "Se ha BLOQUEADO el comentario de manera correcta..!!<br>";
}

if (mysqli_query($db, $sql)) {
 $_SESSION['comentario']  = $mensaje;
 //header('location: usuarios.php');

} else {
 echo "Error updating record: " . mysqli_error($db);
 mysqli_close($db);
}
}





 //APROBAR PAGOS MENSUALIDAD
 function aprobar_pago_mes() {
     global $db, $logo, $fecha_act, $mes_de_pago_actual;
    $id = e($_REQUEST['id']);
    $usua = e($_REQUEST['user']);
    //echo $idusuario;
    // if (isset($_GET['id']))
    // $idusuario=$_GET['id'];

    $sqlA = "UPDATE pagos SET
   status_pago = 'APROBADO',
   fecha_aprobacion = NOW()
   WHERE id = '$id'";

if (mysqli_query($db, $sqlA)) {


    $sql2 = "SELECT pagos.a_favor AS 'a_favor', pagos.concepto AS 'concepto', pagos.mes_de_pago AS 'mes', pagos.afiliacion AS 'afiliacion', users.id AS 'id_usuario', users.nombre AS 'nombre', users.email AS 'email' FROM pagos INNER JOIN users ON pagos.user=users.idusuario WHERE pagos.id = '$id' ";

  	$result = mysqli_query($db, $sql2);
    $row = mysqli_fetch_assoc($result);


    $id_usuario = $row['id_usuario'];
    $email = $row['email'];
    $nombre = $row['nombre'];
    $mes = $row['mes'];
    $afiliacion = $row['afiliacion'];
    $concepto = $row['concepto'];

    $operadora = str_replace("MENS_", "", $concepto);


/*
$sql2 = "INSERT INTO billetera (id, id_usuario, monto, descripcion, id_descripcion, fecha, status) VALUES (null, '$id_usua','-$monto','$descripcion','$id_pago',NOW(),1)";

if (mysqli_query($db, $sql2))
*/


    $sqlB = "UPDATE `users` SET `monto_a_favor` = 0, `status` = (CASE WHEN status = 0 THEN 1 ELSE status END)
    WHERE `users`.`id` = $id_usuario";

    if (mysqli_query($db, $sqlB)){
      $_SESSION['pago_mensualidad'] = "Este usuario ya puede utilizar los modulos de recargas.<br>";
// PROCESAR ACTIVACION DE MENSUALIDADES

		activar_automatica_mes(strtolower($mes_de_pago_actual),'MOVILNET',$id_usuario);
    activar_automatica_mes(strtolower($mes_de_pago_actual),'MOVISTAR',$id_usuario);
    activar_automatica_mes(strtolower($mes_de_pago_actual),'DIGITEL',$id_usuario);
    activar_automatica_mes(strtolower($mes_de_pago_actual),'DIRECTV',$id_usuario);

    } else {
      $_SESSION['pago_mensualidad'] = "Algo ha ocurrido " . mysqli_error($db). "<br>";
    }



$az = '';

$_SESSION['pago_mensualidad']  .= "Se ha Actualizado status de Pago de $nombre de manera correcta..!!<br>";

      $pr = 'Recargas ';
      $az = 'https://virtual.jesuministrosymas.com.ve/u/usuario/recargas_'.strtolower($operadora).'.php';

	$asunto = "Aprobado su Pago del Periodo $mes de la Operadora $operadora";
	$cuerpo = "Hola $nombre <br><br>Le informamos que su pago del periodo $mes ha sido aprobado de manera satisfactoria <br>Desde ya puede ingresar y generar solicitudes de recarga adaptados a su plan $afiliacion de la Operadora $operadora <br>";

  $cuerpo .= '<br><span style="background-color: #baedec; color: #fff; display: inline-block; padding: 10px 20px; font-weight: bold; border-radius: 10px;"><strong><a href="'.$az.'" target="_BLANK">'.$pr . $operadora.'</a></strong></span><br>';

		enviarEmail($email, $nombre, $asunto, $cuerpo);

        $_SESSION['pago_mensualidad']  .= '<i class="fa fa-envelope"></i> Le hemos enviado un Email a ' .$nombre.' informando sobre la aprobacion de su pago y la invitacion a que ingrese a hacer recargas..!!';

 } else {
    $_SESSION['pago_mensualidad'] = "Error al actualizar este dato, algo ha ocurrido: " . mysqli_error($db);
    mysqli_close($db);
 } }




//LISTA PAGO OPERADORES
function lista_pagos_operador(){
    global $db, $usua, $mes, $limit_end, $accion, $concepto;

    selector_operador();

  $url = basename($_SERVER ["PHP_SELF"]);

  if (isset($_REQUEST['busqueda'])) {
    $busqueda = strtolower(e($_REQUEST['busqueda']));
  } else {
    $busqueda = "";
  }


	if (isset($_GET['p']))
		$ini=$_GET['p'];
	else
		$ini=1;
    $init = ($ini-1) * $limit_end;


        if (isAdmin()) {
            //SI ES ADMIN

          if (empty($busqueda)) {
            $busqueda = "";

            $countmes="SELECT COUNT(*) FROM pagos
            INNER JOIN users
            ON pagos.user=users.idusuario
            WHERE status_pago = 'PENDIENTE'";

            $querymes = "SELECT pagos.*, users.nombre, users.email, users.username FROM pagos
             INNER JOIN users
                        ON pagos.user=users.idusuario
                        WHERE status_pago = 'PENDIENTE' ORDER BY fecha_pago ASC LIMIT $init, $limit_end";

	        $resultmes = mysqli_query($db, $querymes);
            $rowmes =  mysqli_num_rows($resultmes);

            $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No hay Mensualidades Pendientes.';
          } else {

            $countmes="SELECT COUNT(*) FROM pagos
            INNER JOIN users
            ON pagos.user=users.idusuario
            WHERE status_pago = 'PENDIENTE' AND (user LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR email LIKE '%$busqueda%' OR status_pago LIKE '%$busqueda%' OR mes_de_pago LIKE '%$busqueda%' OR afiliacion LIKE '%$busqueda%'  OR banco_origen LIKE '%$busqueda%' OR banco_destino LIKE '%$busqueda%' OR nro_transf LIKE '%$busqueda%'  OR ci_nro_cuenta LIKE '%$busqueda%' )";

            $querymes = "SELECT pagos.*, users.nombre, users.email, users.username
            FROM pagos
            INNER JOIN users
            ON pagos.user=users.idusuario
            WHERE status_pago = 'PENDIENTE'
            AND (user LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR email LIKE '%$busqueda%' OR status_pago LIKE '%$busqueda%' OR mes_de_pago LIKE '%$busqueda%' OR afiliacion LIKE '%$busqueda%'  OR banco_origen LIKE '%$busqueda%' OR banco_destino LIKE '%$busqueda%' OR nro_transf LIKE '%$busqueda%'  OR ci_nro_cuenta LIKE '%$busqueda%' )
            ORDER BY fecha_pago ASC
            LIMIT $init, $limit_end";

	          $resultmes = mysqli_query($db, $querymes);
            $rowmes =  mysqli_num_rows($resultmes);

            $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No resultados con su criterio de busqueda.';

          }

        } else {
// SI ES USUARIO
            $countmes="SELECT COUNT(*) FROM pagos WHERE user = '$usua' AND concepto = '$concepto'";
            $querymes = "SELECT * FROM pagos  WHERE user = '$usua' AND concepto = '$concepto' ORDER BY id DESC LIMIT $init, $limit_end";
            $resultmes = mysqli_query($db, $querymes);
            $rowmes =  mysqli_num_rows($resultmes);

            $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No hay Mensualidades que Mostrar del usuario ' .ucwords(strtolower($_SESSION['user']['nombre']));


        }

	if (!$rowmes){

	echo '<div class="alert alert-danger" role="alert" >';
	echo '<h3>';
	echo $mensaje;
	echo '</h3>';
	echo '</div>';

	} else {
		$num = $db->query($countmes);
		$x = $num->fetch_array();
    $total = ceil($x[0]/$limit_end);
    echo '<div class="d-none d-sm-none d-md-block">';
        pag($ini, $limit_end, $total);
    echo "</div>";
    echo '<div class="d-block d-sm-block d-md-none">';
    pag_test($ini, $limit_end, $total);
    echo "</div>";
        if (isAdmin()){
    // SI ES ADMIN

	echo '<div class="table-responsive">';
    echo '<table id="tabla1" class="table table-bordered table-hover ">
    <thead>
     <tr>
     <th>ID</th>
     <th>Usuario</th>
     <th>Nombre</th>
      <th>Fecha de Transf </th>
      <th>Monto / Mes Pagado</th>
      <th>Nro Transf / CI</th>
      <th>Desde / Hasta</th>
      <th>Accion</th>
     </tr>
     </thead>
     <tbody>';

     $c = $db->query($querymes);
     while($rowmes = $c->fetch_array(MYSQLI_ASSOC))
      {
      $date = date_create($rowmes['fecha_transf']);
      $fecha = date_format($date, 'd-m-Y');
      $fecha_pago = $fecha;
      $rowUser = $rowmes['user'];
      $rowid = $rowmes['id'];

      $rowNombre = $rowmes['nombre'];


      // MENSUALIDADES

        $aprobar = '<form autocomplete="off" class="was-validated" method="post" action= "">

        <input type="hidden" name="id" value="'.$rowid.'">
        <input type="hidden" name="user" value="'.$rowUser.'">

        <button type="submit" class="btn btn-success btn-block" name="aprobar_pago_btn" data-html="true" data-toggle="popover" title="Aprobar Pago" data-content="Aca podra aprobar el pago de esta mensualidad y notificar a <b>'.$rowNombre.'</b> con un correo electronico.">Aprobar <i class="fa fa-check-circle"></i></button> ';


       $rechazar = '<a href= "rechazar.php?id='.$rowid.'&user='.$rowUser.'&asunto=mensualidad" type="submit" class="btn btn-danger btn-block" data-html="true" data-toggle="popover" title="Rechazar Pago" data-content="Aca podra rechazar el pago de esta mensualidad y notificar a <b>'.$rowNombre.'</b> con un correo electronico.">Rechazar  <i class="fa fa-times-circle"></i></a></form>';

       botonera_usuario($rowNombre, $rowUser);

        $link = '<div class="btn-group-vertical" role="group" >'. $aprobar .$rechazar . $accion . '</div>';


echo '<tr>';
echo '<td>'.$rowid.'</td>
       <td>'.$rowUser.'</td>
       <td>'.$rowNombre.'</td>
       <td>'.$fecha_pago .'</td>
       <td>'.$rowmes['monto'].' Bs. / '.$rowmes['mes_de_pago']. '</td>
       <td>'.$rowmes['nro_transf'] . ' / '.$rowmes['ci_nro_cuenta'].'</td>
       <td>'.$rowmes['banco_origen'].' / '.$rowmes['banco_destino'] .'</td>
       <td>'.$link .'</td>
      </tr>';
      }
      echo '</tbody></table>';


        }
        else
        // SI ES USUARIO
        {

	echo '<div class="table-responsive">';
    echo '<table id="tabla1" class="table table-bordered table-hover ">
    <thead>
     <tr>
      <th>Fecha de Pago</th>
      <th>Monto</th>
      <th>Mes</th>
      <th>Desde / Hasta</th>
      <th>Status de Pago</th>

     </tr>
     </thead>
     <tbody>';

     $c = $db->query($querymes);
     while($rowmes = $c->fetch_array(MYSQLI_ASSOC)) {

     $statuspago = $rowmes['status_pago'];
     $mes = $rowmes['mes_de_pago'];
     $motivo = strip_tags($rowmes['motivo_rechazo']);

     if ($statuspago == "PENDIENTE") {
       $statuspago = '<div class="text-center w-70 mx-auto alert alert-warning" role="alert" data-toggle="popover" title="PENDIENTE" data-content="Su pago aun no ha sido conformado.">
       PENDIENTE  <i class="fa fa-clock"></i>
     </div>';
     } else if ($statuspago == "APROBADO") {

      $statuspago = '<div class="text-center w-70 mx-auto alert alert-success" role="alert" data-toggle="popover" title="APROBADO" data-content="Su pago ya fue aprobado, ya puede generar pedidos en el periodo '.$mes.' .">
       APROBADO  <i class="fa fa-thumbs.-up"></i>
     </div>';

     }
     else if ($statuspago == "RECHAZADO") {

        $statuspago = '<div class="text-center w-70 mx-auto alert alert-danger" role="alert" data-toggle="popover" title="RECHAZADO" data-content="Su pago fue rechazado, por el siguiente motivo: '.$motivo.'.">
         RECHAZADO  <i class="fa fa-exclamation-triangle"></i>
       </div>';

       }


      $date = date_create($rowmes['fecha_pago']);
      $fecha = date_format($date, 'd-m-Y');
      $fecha_pago = $fecha;
echo '<tr>';
echo '<td>'.$fecha_pago .'</td>
       <td>'.$rowmes['monto'].' Bs. Plan '.$rowmes['afiliacion'].'</td>
       <td>'.$rowmes['mes_de_pago'] .'</td>
       <td>'.$rowmes['inicio'] . ' / '.$rowmes['fin'] .'</td>
       <td>'.$statuspago .'</td>
      </tr>';
      }
      echo '</tbody></table>';

        }



        echo '<div class="d-none d-sm-none d-md-block">';
            pag($ini, $limit_end, $total);
        echo "</div>";
        echo '<div class="d-block d-sm-block d-md-none">';
        pag_test($ini, $limit_end, $total);
        echo "</div>";
}


}

$dest ="";

function selector_bancario($a){
  global $dest;
  if ($a == 'Banco Banesco a Nombre de JE SUMINISTROS Y MAS CA'){
    $dest = 'Banesco JE';
    }
    if ($a == 'Banco Banesco a Nombre de ELENA NUÑEZ'){
    $dest = 'Banesco Elena';
    }
    if ($a == 'Banco Venezuela a Nombre de JOSE HERRERA'){
    $dest = 'BDV';
    }
    if ($a == 'Banco Occidental de Descuento BOD a Nombre de GLADYS ARRAYAGO'){
    $dest = 'BOD';
    }
    if ($a == 'Banco Bicentenario a Nombre de JOSE HERRERA'){
    $dest = 'Bicentenario';
    }
    if ($a == 'Banco del Caribe a Nombre de JOSE HERRERA'){
    $dest = 'Bancaribe';
    }
    if ($a == 'PAYPAL (SOLO MENSUALIDADES)'){
    $dest = 'PAYPAL';
    }
    if ($a == 'GIFT CARD (SOLO MENSUALIDADES)'){
    $dest = 'GIFT CARD';
    }
    if ($a == 'SKRILL (SOLO MENSUALIDADES)'){
    $dest = 'SKRILL';
    }
    if ($a == 'NETELLER (SOLO MENSUALIDADES)'){
    $dest = 'NETELLER';
    }
    if ($a == 'Interno'){
    $dest = 'Interno';
    }
    return $dest;
}

function img_ope($a){
  global $img_ope, $logo_movilnet, $logo_movistar, $logo_digitel, $logo_directv, $logo_inter, $logo_netflix, $logo_billetera;

  if ($a == 'MENS_MOVILNET' || $a == 'Movilnet'){
    $img_ope = $logo_movilnet;
  }
  if ($a == 'MENS_MOVISTAR' || $a == 'Movistar'){
    $img_ope = $logo_movistar;
  }
  if ($a == 'MENS_DIGITEL' || $a == 'Digitel'){
    $img_ope = $logo_digitel;
  }
  if ($a == 'MENS_DIRECTV' || $a == 'Directv'){
    $img_ope = $logo_directv;
  }
  if ($a == 'MENS_INTER' || $a == 'Inter'){
    $img_ope = $logo_inter;
  }
  if ($a == 'MENS_NETFLIX' || $a == 'Netflix'){
    $img_ope = $logo_netflix;
  }
  if ($a == 'BILLETERA' || $a == 'Billetera'){
    $img_ope = $logo_billetera;
  }

  return $img_ope;

}

// LISTAR PAGOS MENSUALES LISTA MESES
function lista_pagos_mes(){
	global $db, $usua, $mes, $limit_end, $accion, $concepto, $dest, $img_ope;

  $url = basename($_SERVER ["PHP_SELF"]);

  if (isset($_REQUEST['busqueda'])) {
    $busqueda = strtolower(e($_REQUEST['busqueda']));
  } else {
    $busqueda = "";
  }


	if (isset($_GET['p']))
		$ini=$_GET['p'];
	else
		$ini=1;
    $init = ($ini-1) * $limit_end;


        if (isAdmin()) {
            //SI ES ADMIN

          if (empty($busqueda)) {
            $busqueda = "";

            $countmes="SELECT COUNT(*) FROM pagos
            INNER JOIN users
            ON pagos.user=users.idusuario
            WHERE status_pago = 'PENDIENTE'";

            $querymes = "SELECT pagos.*, users.cel, users.tlf, users.nombre, users.email, users.username FROM pagos
             INNER JOIN users
                        ON pagos.user=users.idusuario
                        WHERE status_pago = 'PENDIENTE' ORDER BY fecha_pago ASC LIMIT $init, $limit_end";

	        $resultmes = mysqli_query($db, $querymes);
            $rowmes =  mysqli_num_rows($resultmes);

            $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No hay Mensualidades Pendientes.';
          } else {

            $countmes="SELECT COUNT(*) FROM pagos
            INNER JOIN users
            ON pagos.user=users.idusuario
            WHERE status_pago = 'PENDIENTE' AND (user LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR email LIKE '%$busqueda%' OR status_pago LIKE '%$busqueda%' OR mes_de_pago LIKE '%$busqueda%' OR afiliacion LIKE '%$busqueda%'  OR banco_origen LIKE '%$busqueda%' OR banco_destino LIKE '%$busqueda%' OR nro_transf LIKE '%$busqueda%'  OR ci_nro_cuenta LIKE '%$busqueda%' )";

            $querymes = "SELECT pagos.*, users.cel, users.tlf, users.nombre, users.email, users.username FROM pagos
            INNER JOIN users
            ON pagos.user=users.idusuario
             WHERE status_pago = 'PENDIENTE'  AND (user LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR email LIKE '%$busqueda%' OR status_pago LIKE '%$busqueda%' OR mes_de_pago LIKE '%$busqueda%' OR afiliacion LIKE '%$busqueda%'  OR banco_origen LIKE '%$busqueda%' OR banco_destino LIKE '%$busqueda%' OR nro_transf LIKE '%$busqueda%'  OR ci_nro_cuenta LIKE '%$busqueda%' ) ORDER BY fecha_pago ASC LIMIT $init, $limit_end";

	        $resultmes = mysqli_query($db, $querymes);
            $rowmes =  mysqli_num_rows($resultmes);

            $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No resultados con su criterio de busqueda.';

          }

        } else {

// SI ES USUARIO
selector_operador();

            $countmes="SELECT COUNT(*) FROM pagos WHERE user = '$usua' AND concepto = '$concepto'";
            $querymes = "SELECT * FROM pagos  WHERE user = '$usua' AND concepto = '$concepto' ORDER BY id DESC LIMIT $init, $limit_end";
            $resultmes = mysqli_query($db, $querymes);
            $rowmes =  mysqli_num_rows($resultmes);

            $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No hay Mensualidades que Mostrar del usuario ' .ucwords(strtolower($_SESSION['user']['nombre']));


        }

	if (!$rowmes){

	echo '<div class="alert alert-danger" role="alert" >';
	echo '<h3>';
	echo $mensaje;
	echo '</h3>';
	echo '</div>';

	} else {
		$num = $db->query($countmes);
		$x = $num->fetch_array();
    $total = ceil($x[0]/$limit_end);
    echo '<div class="d-none d-sm-none d-md-block">';
        pag($ini, $limit_end, $total);
    echo "</div>";
    echo '<div class="d-block d-sm-block d-md-none">';
    pag_test($ini, $limit_end, $total);
    echo "</div>";
        if (isAdmin()){



// SI ES ADMIN

	echo '<div class="table-responsive">';
    echo '<table id="tabla1" class="table table-bordered table-hover ">
    <thead>
     <tr>
     <th>ID</th>
     <th>Usuario / Nombre / Tlf</th>
      <th>Fecha de Transf </th>
      <th>Monto / Mes Pagado / Concepto / Nro Transf / CI</th>

      <th>Desde / Hasta</th>
      <th>Accion</th>
     </tr>
     </thead>
     <tbody>';

     $c = $db->query($querymes);
     while($rowmes = $c->fetch_array(MYSQLI_ASSOC))
      {
      //$date = date_create($rowmes['fecha_transf']);
      //$fecha = date_format($date, 'd-m-Y');
      //$fecha_pago = $fecha;
      $rowUser = $rowmes['user'];
      $rowid = $rowmes['id'];
      $cel = $rowmes['cel'];
      $tlf = $rowmes['tlf'];
      $rowNombre = $rowmes['nombre'];
      $concep = $rowmes['concepto'];


      $destino = $rowmes['banco_destino'];


      // MENSUALIDADES

        $aprobar = '<form autocomplete="off" class="was-validated" method="post" action= "">

        <input type="hidden" name="id" value="'.$rowid.'">
        <input type="hidden" name="user" value="'.$rowUser.'">

        <button type="submit" class="btn btn-success btn-block" name="aprobar_pago_btn" data-html="true" data-toggle="popover" title="Aprobar Pago" data-content="Aca podra aprobar el pago de esta mensualidad y notificar a <b>'.$rowNombre.'</b> con un correo electronico.">Aprobar <i class="fa fa-check-circle"></i></button> ';


       $rechazar = '<a href= "rechazar.php?id='.$rowid.'&user='.$rowUser.'&asunto=mensualidad" type="submit" class="btn btn-danger btn-block" data-html="true" data-toggle="popover" title="Rechazar Pago" data-content="Aca podra rechazar el pago de esta mensualidad y notificar a <b>'.$rowNombre.'</b> con un correo electronico.">Rechazar  <i class="fa fa-times-circle"></i></a></form>';

       botonera_usuario($rowNombre, $rowUser);

        $link = '<div class="btn-group-vertical" role="group" >'. $aprobar .$rechazar . $accion . '</div>';

selector_bancario($destino);
img_ope($concep);

//var_dump($img_ope);

echo '<tr>';
echo '<td>'.$rowid.'</td>
       <td>'.$rowUser.'<br>'.$rowNombre.'<br>'.$tlf.'<br>'.$cel.'</td>
       <td>'.$rowmes['fecha_transf'] .'</td>
       <td>Transf: '.$rowmes['monto'].' Bs. <br>A Favor:  '.$rowmes['a_favor'].' Bs. <br> '.$rowmes['mes_de_pago']. '<br>'.$concep. '<br>Transf: '. $rowmes['nro_transf'] .'<br>C.I.: '.$rowmes['ci_nro_cuenta'] .'<br></td>
       <td>'.$rowmes['banco_origen'].' / '.$dest.'<br>'.$img_ope.'<br></td>
       <td>'.$link .'</td>
      </tr>';
      }
      echo '</tbody></table>';


        }
        else
        // SI ES USUARIO
        {

	echo '<div class="table-responsive">';
    echo '<table id="tabla1" class="table table-bordered table-hover ">
    <thead>
     <tr>
      <th>Fecha de Pago</th>
      <th>Monto</th>
      <th>Mes</th>
      <th>Status de Pago</th>
     </tr>
     </thead>
     <tbody>';

     $c = $db->query($querymes);
     while($rowmes = $c->fetch_array(MYSQLI_ASSOC)) {

     $statuspago = $rowmes['status_pago'];
     $mes = $rowmes['mes_de_pago'];
     $motivo = strip_tags($rowmes['motivo_rechazo']);

     if ($statuspago == "PENDIENTE") {
       $statuspago = '<div class="text-center w-70 mx-auto alert alert-warning" role="alert" data-toggle="popover" title="PENDIENTE" data-content="Su pago aun no ha sido conformado.">
       PENDIENTE  <i class="fa fa-clock"></i>
     </div>';
     } else if ($statuspago == "APROBADO") {

      $statuspago = '<div class="text-center w-70 mx-auto alert alert-success" role="alert" data-toggle="popover" title="APROBADO" data-content="Su pago ya fue aprobado, ya puede generar pedidos en el periodo '.$mes.' .">
       APROBADO  <i class="fa fa-thumbs.-up"></i>
     </div>';

     }
     else if ($statuspago == "RECHAZADO") {

        $statuspago = '<div class="text-center w-70 mx-auto alert alert-danger" role="alert" data-toggle="popover" title="RECHAZADO" data-content="Su pago fue rechazado, por el siguiente motivo: '.$motivo.'.">
         RECHAZADO  <i class="fa fa-exclamation-triangle"></i>
       </div>';

       }


      $date = date_create($rowmes['fecha_pago']);
      $fecha = date_format($date, 'd-m-Y');
      $fecha_pago = $fecha;
echo '<tr>';
echo '<td>'.$fecha_pago .'</td>
       <td>'.$rowmes['monto'].' Bs. Plan '.$rowmes['afiliacion'].'</td>
       <td>'.$rowmes['mes_de_pago'] .'</td>
       <td>'.$statuspago .'</td>
      </tr>';
      }
      echo '</tbody></table>';

        }



        echo '<div class="d-none d-sm-none d-md-block">';
            pag($ini, $limit_end, $total);
        echo "</div>";
        echo '<div class="d-block d-sm-block d-md-none">';
        pag_test($ini, $limit_end, $total);
        echo "</div>";
}


}



//PARA EL MODAL DE AGREGAR USUARIO
function modal_agregar_usuario(){
	global $nombre_usuario, $email_usuario,  $telefono_usuario,
    $celular_usuario, $user_type;


echo ' <form autocomplete="off" class="was-validated" method="post" action= "usuarios.php">

<div class="form-group">
<label for="idusuario">Id del Usuario</label>
<input type="text" pattern="[V,J,G,E]{1}[-][0-9]{7,9}" class="form-control" id="idusuario" aria-describedby="idusuario" placeholder="Ingrese el idusuario" name="idusuario" value="';
//echo $idusuario;
echo '" required>
<div class="invalid-feedback">Debe indicar el numero de ID del Usuario.</div>
</div>

<div class="form-group">
<label for="nombre_usuario">Nombre del Usuario</label>
<input type="text" class="form-control" id="nombre_usuario" aria-describedby="nombre_usuario" placeholder="Ingrese el Nombre del Nuevo Usuario" name="nombre_usuario" value="';
echo $nombre_usuario;
echo '" required>
<div class="invalid-feedback">Debe indicar el numero de ID del Usuario.</div>
</div>

<div class="form-group">
<label for="email_usuario">Email del Usuario</label>
<input type="email" pattern="[a-zA-Z0-9]{0,}([.]?[_.a-zA-Z0-9]{1,})[@](gmail.com|hotmail.com|yahoo.com|yahoo.es|outlook.es|outlook.com|hotmail.es|cantv.net|cantv.com)" title="Debe utilizar solo correos gmail, yahoo, hotmail o cantv" class="form-control" id="email_usuario" aria-describedby="email_usuario" placeholder="Ingrese el Email del Nuevo Usuario Debe utilizar solo correos gmail, yahoo, hotmail o cantv" name="email_usuario" value="';
echo $email_usuario;
echo '" required>
<div class="invalid-feedback">Debe indicar el Email del Usuario.</div>
</div>

<div class="form-group">
<label for="telefono_usuario">Telefono del Usuario</label>
<input pattern="[0-9]{11}" title = "Debe ingresar un telefono valido con 11 digitos, no se requiere el codigo de discado internacional" type="tel" class="form-control" id="telefono_usuario" aria-describedby="telefono_usuario" placeholder="Ingrese el Telefono del Nuevo Usuario" name="telefono_usuario" value="';
echo $telefono_usuario;
echo '" required>
<div class="invalid-feedback">Debe indicar el Telefono del Usuario.</div>
</div>

<div class="form-group">
<label for="celular_usuario">Celular del Usuario</label>
<input pattern="[0]{1}[4]{1}[1,2]{1}[2,4,6]{1}[0-9]{7}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567" type="tel" class="form-control" id="celular_usuario" aria-describedby="celular_usuario" placeholder="Ingrese el Celular del Nuevo Usuario" name="celular_usuario" value="';
echo $celular_usuario;
echo '" required>
<div class="invalid-feedback">Debe indicar el Celular del Usuario.</div>
</div>

<div class="form-group">
<label for="user_type">Tipo de Usuario</label>
<select class="custom-select" id="user_type" name="user_type" value="';
echo $user_type;
echo '" required >';
//echo '<option value="">Seleccione:</option>';
user_type();
echo '</select>
<div class="invalid-feedback">Debe Seleccionar El tipo de Usuario.</div>
</div>



<button type="submit" class="btn btn-primary" name="agregar_usuario_btn">Enviar</button>

</form>';
}

//PARA EL MODAL DE EDITAR USUARIO
function modal_editar_desde_usuario(){
	global $db, $idusuario,
    $nombre_usuario, $email_usuario,  $telefono_usuario,
    $celular_usuario, $rowid, $status_usuario, $nombre_comercio, $direccion_comercio, $logo_comercio;

    $usua = ($_SESSION['user']['username']);

    $query = "SELECT * FROM users WHERE username = '$usua'";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_array($result);

    $rowid = $row['username'];
   // $rowid = $row['id'];
          $idusuario = $row['idusuario'];
          $nombre_usuario = $row['nombre'];
          $email_usuario = $row['email'];
          $telefono_usuario = $row['tlf'];
          $celular_usuario = $row['cel'];
          $direccion_usuario = $row['direccion'];
          $ciudad_usuario = $row['ciudad'];
          $estado_usuario = $row['estado'];
          $municipio_usuario = $row['municipio'];
          $parroquia_usuario = $row['parroquia'];
          //$password_usuario = $row['password'];
          $status_usuario = $row['status'];

          $nombre_comercio = $row['nombre_comercio'];
          $direccion_comercio = $row['direccion_comercio'];
          $logo_comercio = $row['logo_comercio'];

$modal_editar_usuario = ' <form autocomplete="off" class="was-validated" method="post" action= "perfil.php">';

$modal_editar_usuario .= 'Identificador: ' .$rowid .'<br>';
$modal_editar_usuario .= 'Nombre: ' .$nombre_usuario .'<br>';
$modal_editar_usuario .= 'Email: ' .$email_usuario .'<br>';
$modal_editar_usuario .= '<div class="dropdown-divider"></div>';






$modal_editar_usuario .= '<div class="form-group">
<label for="telefono_usuario">Numero de Telefono Local</label>
<input type="tel" pattern="[0]{1}[2]{1}[1-9]{1}[0-9]{8}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de area, Ejemplo: 02431234567" class="form-control" id="telefono_usuario" aria-describedby="telefono_usuario" placeholder="Ingrese su numero de Telefono local" name="telefono_usuario" value="';
$modal_editar_usuario .= $telefono_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el numero de Telefono local, Debe usar minimo 11 digitos debe incluir el codigo de area, Ejemplo: 02431234567.</div>
</div>

<div class="form-group">
<label for="celular_usuario">Numero de Celular</label>
<input type="tel" pattern="[0]{1}[4]{1}[1,2]{1}[2,4,6]{1}[0-9]{7}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567" class="form-control" class="form-control" id="celular_usuario" aria-describedby="celular_usuario" placeholder="Ingrese su numero de Celular" name="celular_usuario" value="';
$modal_editar_usuario .= $celular_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar su numero de telefono Celular, debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567.</div>
</div>

<div class="form-group">
<label for="direccion_usuario">Su Direccion Completa</label>
<input type="textarea" class="form-control" id="direccion_usuario" aria-describedby="direccion_usuario" placeholder="Ingrese su Direccion" name="direccion_usuario" value="';
$modal_editar_usuario .= $direccion_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar su Direccion completa.</div>
</div>

<div class="form-group">
<label for="estado_usuario">Estado donde Vive</label>
<input type="text" class="form-control" id="estado_usuario" aria-describedby="estado_usuario" placeholder="Ingrese el Estado" name="estado_usuario" value="';
$modal_editar_usuario .= $estado_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Estado donde vive.</div>
</div>

<div class="form-group">
<label for="ciudad_usuario">Ciudad donde vive</label>
<input type="text" class="form-control" id="ciudad_usuario" aria-describedby="ciudad_usuario" placeholder="Ingrese la Ciudad" name="ciudad_usuario" value="';
$modal_editar_usuario .= $ciudad_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Ciudad donde vive.</div>
</div>

<div class="form-group">
<label for="municipio_usuario">Municipio donde vive</label>
<input type="text" class="form-control" id="municipio_usuario" aria-describedby="municipio_usuario" placeholder="Ingrese el Municipio" name="municipio_usuario" value="';
$modal_editar_usuario .= $municipio_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Municipio de ubicacion.</div>
</div>

<div class="form-group">
<label for="parroquia_usuario">Parroquia donde vive</label>
<input type="text" class="form-control" id="parroquia_usuario" aria-describedby="parroquia_usuario" placeholder="Ingrese el Parroquia" name="parroquia_usuario" value="';
$modal_editar_usuario .= $parroquia_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Parroquia de ubicacion.</div>
</div>


<div class="form-group">
<label for="nombre_comercio">Nombre del Comercio</label>
<input type="text" class="form-control" id="nombre_comercio" aria-describedby="nombre_comercio" placeholder="Ingrese el Parroquia" name="nombre_comercio" value="';
$modal_editar_usuario .= $nombre_comercio;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Parroquia de ubicacion.</div>
</div>

<div class="form-group">
<label for="direccion_comercio">Direccion del Comercio</label>
<input type="text" class="form-control" id="direccion_comercio" aria-describedby="direccion_comercio" placeholder="Ingrese el Parroquia" name="direccion_comercio" value="';
$modal_editar_usuario .= $direccion_comercio;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Parroquia de ubicacion.</div>
</div>

<div class="form-group">
    <label for="logo_comercio">Logo</label>';

// Si hay un logo almacenado, muestra la imagen
if (!empty($logo_comercio)) {
    $modal_editar_usuario .= '<img src="' . $logo_comercio . '" alt="Logo del comercio" class="img-fluid">'; // Mostrar imagen
} else {
    // De lo contrario, muestra un texto o un placeholder
    $modal_editar_usuario .= 'No se ha subido un logo.';
}

$modal_editar_usuario .= '<input type="file" class="form-control-file" id="logo_comercio" name="logo_comercio" accept="image/*"> 
  <div class="invalid-feedback">Debe seleccionar una imagen.</div>
</div>
</div>';



echo $modal_editar_usuario;
}

function modal_editar_desde_usuario2(){
	global $db, $idusuario,
    $nombre_usuario, $email_usuario,  $telefono_usuario,
    $celular_usuario, $password_usuario, $user_type, $rowid;

    $usua = ($_SESSION['user']['username']);

    $query = "SELECT * FROM users WHERE username = '$usua'";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_array($result);

    $rowid = $row['username'];
   // $rowid = $row['id'];
          $idusuario = $row['idusuario'];
          $nombre_usuario = $row['nombre'];
          $email_usuario = $row['email'];
          $telefono_usuario = $row['tlf'];
          $celular_usuario = $row['cel'];
          $direccion_usuario = $row['direccion'];
          $ciudad_usuario = $row['ciudad'];
          $estado_usuario = $row['estado'];
          $municipio_usuario = $row['municipio'];
          $parroquia_usuario = $row['parroquia'];
          //$password_usuario = $row['password'];
          $status_usuario = $row['status'];

$modal_editar_usuario = ' <form autocomplete="off" class="was-validated" method="post" action= "perfil.php">';

$modal_editar_usuario .= 'Identificador: ' .$rowid .'<br>';
$modal_editar_usuario .= 'Nombre: ' .$nombre_usuario .'<br>';
$modal_editar_usuario .= 'Email: ' .$email_usuario .'<br>';
$modal_editar_usuario .= '<div class="dropdown-divider"></div>';






$modal_editar_usuario .= '<div class="form-group">
<label for="telefono_usuario">Numero de Telefono Local</label>
<input type="tel" pattern="[0]{1}[2]{1}[1-9]{1}[0-9]{8}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de area, Ejemplo: 02431234567" class="form-control" id="telefono_usuario" aria-describedby="telefono_usuario" placeholder="Ingrese su numero de Telefono local" name="telefono_usuario" value="';
$modal_editar_usuario .= $telefono_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el numero de Telefono local, Debe usar minimo 11 digitos debe incluir el codigo de area, Ejemplo: 02431234567.</div>
</div>

<div class="form-group">
<label for="celular_usuario">Numero de Celular</label>
<input type="tel" pattern="[0]{1}[4]{1}[1,2]{1}[2,4,6]{1}[0-9]{7}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567" class="form-control" class="form-control" id="celular_usuario" aria-describedby="celular_usuario" placeholder="Ingrese su numero de Celular" name="celular_usuario" value="';
$modal_editar_usuario .= $celular_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar su numero de telefono Celular, debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567.</div>
</div>

<div class="form-group">
<label for="direccion_usuario">Su Direccion Completa</label>
<input type="textarea" class="form-control" id="direccion_usuario" aria-describedby="direccion_usuario" placeholder="Ingrese su Direccion" name="direccion_usuario" value="';
$modal_editar_usuario .= $direccion_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar su Direccion completa.</div>
</div>



';

$sq = "SELECT * FROM estados ORDER BY id_estado";
$results = mysqli_query($db, $sq);
$modal_editar_usuario .= '<div class="form-group">
<label for="banco_emisor">Seleccione su Estado</label>
<select class="custom-select" id="estado_id" name="estado_id" value="" required >
<option value="">Seleccione:</option>';
while ($a = mysqli_fetch_array($results)) {
  $modal_editar_usuario .= '<option value="'.$a['id_estado'].'">'.$a['estado'].'</option>';
}
$modal_editar_usuario .= '</select> <div class="invalid-feedback">Debe Seleccionar su estado.</div>
</div>';


$modal_editar_usuario .= '<div class="form-group">
   <label for="name1">Ciudad</label>
   <select id="ciudad_id" class="form-control" name="ciudad_id" required>
     <option value="">-- SELECCIONE --</option>
  </select> <div class="invalid-feedback">Debe Seleccionar su estado.</div>
 </div>';


$modal_editar_usuario .= '


<div class="form-group">
<label for="estado_usuario">Estado donde Vive</label>
<input type="text" class="form-control" id="estado_usuario" aria-describedby="estado_usuario" placeholder="Ingrese el Estado" name="estado_usuario" value="';
$modal_editar_usuario .= $estado_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Estado donde vive.</div>
</div>

<div class="form-group">
<label for="ciudad_usuario">Ciudad donde vive</label>
<input type="text" class="form-control" id="ciudad_usuario" aria-describedby="ciudad_usuario" placeholder="Ingrese la Ciudad" name="ciudad_usuario" value="';
$modal_editar_usuario .= $ciudad_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Ciudad donde vive.</div>
</div>

<div class="form-group">
<label for="municipio_usuario">Municipio donde vive</label>
<input type="text" class="form-control" id="municipio_usuario" aria-describedby="municipio_usuario" placeholder="Ingrese el Municipio" name="municipio_usuario" value="';
$modal_editar_usuario .= $municipio_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Municipio de ubicacion.</div>
</div>

<div class="form-group">
<label for="parroquia_usuario">Parroquia donde vive</label>
<input type="text" class="form-control" id="parroquia_usuario" aria-describedby="parroquia_usuario" placeholder="Ingrese el Parroquia" name="parroquia_usuario" value="';
$modal_editar_usuario .= $parroquia_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Parroquia de ubicacion.</div>
</div>';

echo $modal_editar_usuario;
}

//PARA EL MODAL DE EDITAR USUARIO
function modal_editar_password_desde_usuario(){
	global $db, $idusuario,
    $nombre_usuario, $email_usuario,  $telefono_usuario,
    $celular_usuario, $password_usuario, $user_type, $rowid, $usua;



    $query = "SELECT * FROM users WHERE username = '$usua'";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_array($result);

    $rowid = $row['username'];
   // $rowid = $row['id'];
          $idusuario = $row['idusuario'];
          $nombre_usuario = $row['nombre'];
          $email_usuario = $row['email'];
          $telefono_usuario = $row['tlf'];
          $celular_usuario = $row['cel'];
          $direccion_usuario = $row['direccion'];
          $ciudad_usuario = $row['ciudad'];
          $estado_usuario = $row['estado'];
          $municipio_usuario = $row['municipio'];
          $parroquia_usuario = $row['parroquia'];
          //$password_usuario = $row['password'];
          $status_usuario = $row['status'];

$modal_editar_usuario = ' <form autocomplete="off" class="was-validated" method="post" action= "crear_password.php">';

$modal_editar_usuario .= 'Identificador: ' .$rowid .'<br>';
$modal_editar_usuario .= 'Nombre: ' .$nombre_usuario .'<br>';
$modal_editar_usuario .= 'Email: ' .$email_usuario .'<br>';
$modal_editar_usuario .= '<div class="dropdown-divider"></div>';






$modal_editar_usuario .= '<div class="form-group">
<label for="password_1">Password o Contraseña</label>
<input pattern="[a-zA-Z0-9.+_-]{6,10}" title="Debe utilizar combiaciones de Letras, Numeros y Puede utilizar los caracteres especiales: . + _ - Puede usar un minimo de 6 caracteres y un maximo de 10"
type="password" class="form-control" id="password_1" placeholder="Password" name="password_1" required>
<div class="invalid-feedback">Ingrese su Password o Contraseña. Por su seguridad Recomendamos que Utilice una contraseña conformada por combiaciones de Letras Pueden ser Mayusculas o Minusculas y Numeros. Su contraseña debe tener minimo 6 caracteres y un maximo de 10 caracteres. Puede utilizar los caracteres especiales: . + _ - </div>
</div>

<div class="form-group">
    <label for="password_2">Repita su Password o Contraseña</label>
    <input pattern="[a-zA-Z0-9.+_-]{6,10}" title="Debe utilizar combiaciones de Letras, Numeros y Puede utilizar los caracteres especiales: . + _ - Puede usar un minimo de 6 caracteres y un maximo de 10"
 type="password" class="form-control" id="password_2" placeholder="Password" name="password_2" required>
    <div class="invalid-feedback">Ingrese su Password o Contraseña. Por su seguridad Recomendamos que Utilice una contraseña conformada por combiaciones de Letras Pueden ser Mayusculas o Minusculas y Numeros. Su contraseña debe tener minimo 6 caracteres y un maximo de 10 caracteres. Puede utilizar los caracteres especiales: . + _ - </div>
  </div';

echo $modal_editar_usuario;
}

//PARA EL MODAL DE EDITAR USUARIO
function modal_editar_usuario(){
	global $db, $usua, $idusuario,
    $nombre_usuario, $email_usuario,  $telefono_usuario,
    $celular_usuario, $password_usuario, $user_type, $rowid;


$modal_editar_usuario = ' <form autocomplete="off" class="was-validated" method="post" action= "usuarios.php">';

$modal_editar_usuario .= 'Identificador: ' .$rowid;

$modal_editar_usuario .= '<div class="form-group">
<label for="idusuario">Id del Usuario</label>
<input type="text" class="form-control" id="idusuario" aria-describedby="idusuario" placeholder="Ingrese el idusuario" name="idusuario" value="';
$modal_editar_usuario .= $idusuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el numero de ID del Usuario.</div>
</div>

<div class="form-group">
<label for="nombre_usuario">Nombre del Usuario</label>
<input type="text" class="form-control" id="nombre_usuario" aria-describedby="nombre_usuario" placeholder="Ingrese el Nombre del Nuevo Usuario" name="nombre_usuario" value="';
$modal_editar_usuario .= $nombre_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el numero de ID del Usuario.</div>
</div>

<div class="form-group">
<label for="email_usuario">Email del Usuario</label>
<input type="text" class="form-control" id="email_usuario" aria-describedby="email_usuario" placeholder="Ingrese el Email del Nuevo Usuario" name="email_usuario" value="';
$modal_editar_usuario .= $email_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Email del Usuario.</div>
</div>

<div class="form-group">
<label for="telefono_usuario">Telefono del Usuario</label>
<input type="text" class="form-control" id="telefono_usuario" aria-describedby="telefono_usuario" placeholder="Ingrese el Telefono del Nuevo Usuario" name="telefono_usuario" value="';
$modal_editar_usuario .= $telefono_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Telefono del Usuario.</div>
</div>

<div class="form-group">
<label for="celular_usuario">Celular del Usuario</label>
<input type="text" class="form-control" id="celular_usuario" aria-describedby="celular_usuario" placeholder="Ingrese el Celular del Nuevo Usuario" name="celular_usuario" value="';
$modal_editar_usuario .= $celular_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Celular del Usuario.</div>
</div>

<div class="form-group">
<label for="password_usuario">Password del Usuario</label>
<input type="text" class="form-control" id="password_usuario" aria-describedby="password_usuario" placeholder="Ingrese el Password del Nuevo Usuario" name="password_usuario" value="';
$modal_editar_usuario .= $password_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Password del Usuario.</div>
</div>

<button type="submit" class="btn btn-primary" name="agregar_usuario_btn">Enviar</button>

</form>';
return $modal_editar_usuario;
}

function agregar_usuario(){
    global $db, $error;
    $alea = "";
// RECIBE LOS DATOS DEL FORM
$idusuario          =  strtoupper(e($_POST['idusuario']));
$nombre_usuario     =  strtoupper(e($_POST['nombre_usuario']));
$email_usuario      =  strtolower(e($_POST['email_usuario']));
$telefono_usuario   =  (e($_POST['telefono_usuario']));
$celular_usuario    =  (e($_POST['celular_usuario']));
$direccion_usuario  =  "Debe Completar";
$ciudad_usuario     =  "Debe Completar";
$estado_usuario     =  "Debe Completar";
$municipio_usuario  =  "Debe Completar";
$parroquia_usuario  =  "Debe Completar";
$user_type          =  "user";
$alea = generateRandomString(10);
// $a dato a verificar y $b el regex
//validar_dato($a, $b);
$vid = "[V,J,G,E]{1}[-][0-9]{7,9}";
$vnu = "[A-Z ]{7,50}";
$veu = "[a-zA-Z0-9]{0,}([.]?[_.a-zA-Z0-9]{1,})[@](gmail.com|hotmail.com|yahoo.com|yahoo.es|outlook.es|outlook.com|hotmail.es|cantv.net|cantv.com)";
$vtu = "[0]{1}[2]{1}[1-9]{1}[0-9]{8}";
$vcu = "[0]{1}[4]{1}[1,2]{1}[2,4,6]{1}[0-9]{7}";
validar_dato($idusuario, $vid);
validar_dato($nombre_usuario, $vnu);
validar_dato($email_usuario, $veu);
validar_dato($telefono_usuario, $vtu);
validar_dato($celular_usuario, $vcu);
//$password_usuario   =  ($_POST['password_usuario']);

//$password = md5($password_usuario);//encrypt the password before saving in the database

$verf = "SELECT email FROM users WHERE username = '$idusuario' OR email = '$email_usuario'";
		$result = mysqli_query($db, $verf);
		$rows =  mysqli_num_rows($result);
		if ($rows>0){
			$_SESSION['usuarios']  = 'Lo sentimos, el usuario que intenta registrar ya existe, si no recuerda sus credenciales de acceso favor ingrese a <a href="recuperar_password.php">RECUPERAR CONTRASEÑA</a>.<br>';
      $_SESSION['msg'] = $_SESSION['usuarios'];
      //header('location: usuarios.php');
      //mysqli_close($db);
		} else {

$query = "INSERT INTO users (
id,
idusuario,
nombre,
username,
email,
tlf,
cel,
direccion,
ciudad,
estado,
municipio,
parroquia,
user_type, control)
VALUES(null, '$idusuario', '$nombre_usuario', '$idusuario', '$email_usuario', '$telefono_usuario', '$celular_usuario', '$direccion_usuario', '$ciudad_usuario','$estado_usuario','$municipio_usuario', '$parroquia_usuario','$user_type', '$alea')";
  //mysqli_query($db, $query);
  if (mysqli_query($db, $query)) {

    $_SESSION['usuarios']  = "Se ha registrado nuevo usuario de manera Exitosa.<br>";
    $_SESSION['msg'] = $_SESSION['usuarios'];

    $sql = "SELECT id FROM users
    WHERE username='$idusuario' OR username='$idusuario'";
    $results_sql = mysqli_query($db, $sql);
    $rows_sql =  mysqli_fetch_assoc($results_sql);

    $rowid = $rows_sql['id'];

$email = $email_usuario;
$nombre = $nombre_usuario;
$asunto = "Registro Exitoso Sistema Gestion de Recargas";
$cuerpo = 'Hola '.$nombre.' <br><br>Usted ha sido registrado de manera exitosa en la Plataforma Digital de J.E Suministros y Mas, C.A. Ventana digital que le permitira adquirir Recargar Movilnet, Recargas Movistar, Recargas Digitel.<p style="text-align: justify;"><strong>SUS CREDENCIALES DE ACCESO:</strong></p><p style="text-align: center;"><br> <span style= "background-color: #70FF70; color: #000000; display: inline-block; padding: 3px 10px; font-weight: bold; border-radius: 5px;">Correo Registrado: <strong>'.$email_usuario.'</strong><br>Su Usuario es: <strong>'.$idusuario.'</strong></span></p><p>&nbsp;</p></hr>CREA TU CONTRASEÑA DE ACCESO AQUI</strong></span></p><br><br>Ahora debes crear tu contraseña ingresando <p style="text-align: center;"><br> <span style="background-color: #FFFD01; color: #fff; display: inline-block; padding: 10px 20px; font-weight: bold; border-radius: 10px;"><strong><a href=';
$cuerpo .= '"';
$cuerpo .= "https://virtual.jesuministrosymas.com.ve/u/crear_password.php?id=";
$cuerpo .=$rowid;
$cuerpo .="&control=";
$cuerpo .=$alea;
$cuerpo .= '"';
$cuerpo .=">CREAR CONTRASEÑA AQUI</a></strong></span></p><br><br>";
$cuerpo .= "Ya en breve podras acceder al sistema y empezar a utilizarlo.";
enviarEmail($email, $nombre, $asunto, $cuerpo);

$_SESSION['usuarios']  .= '<i class="fa fa-envelope"></i> Hemos enviado un Correo con Instrucciones para que cree su contraseña.<br>';
$_SESSION['msg'] .= '<i class="fa fa-envelope"></i> En breve este sistema enviará un Correo Electronico a la direccion '.$email.' suministrada con instrucciones para que usted cree su contraseña, si no encuentra el correo en el buzon de correo normal favor revise el buzon de correos no deseados o buzon de correos SPAM.<br>Si por algun error el correo '.$email.' no existe entonces usted debe comunicarse con nosotros via Whatsapp, o Telegram para que podamos efectuar la correccion del correo.';

} else {

      $_SESSION['usuarios']  .= '<i class="fa fa-exclamation-triangle"></i> Algo ha ocurrido, favor intente este proceso mas tarde.<br>'. mysqli_error($db);
      $_SESSION['msg'] .= '<i class="fa fa-exclamation-triangle"></i> Algo ha ocurrido, favor intente este proceso mas tarde.<br>'. mysqli_error($db);
    }

}
}


function editar_desde_usuario(){
    global $db, $error;
// RECIBE LOS DATOS DEL FORM
$telefono_usuario     =  e($_POST['telefono_usuario']);
$celular_usuario      =  e($_POST['celular_usuario']);
$direccion_usuario    =  e($_POST['direccion_usuario']);
$ciudad_usuario       =  e($_POST['ciudad_usuario']);
$estado_usuario       =  e($_POST['estado_usuario']);
$municipio_usuario    =  e($_POST['municipio_usuario']);
$parroquia_usuario    =  e($_POST['parroquia_usuario']);

$usua = e($_SESSION['user']['username']);


//$password = md5($password_usuario);//encrypt the password before saving in the database

$sql = "UPDATE users SET
   tlf = '$telefono_usuario',
   cel = '$celular_usuario',
   direccion = '$direccion_usuario',
   ciudad = '$ciudad_usuario',
   estado = '$estado_usuario',
   municipio = '$municipio_usuario',
   parroquia = '$parroquia_usuario'
   WHERE username = '$usua'";

if (mysqli_query($db, $sql)) {
    $_SESSION['msn_perfil']  = "Se ha Actualizado su usuario de manera correcta..!!";

 } else {
    echo "Error updating record: " . mysqli_error($db);
    mysqli_close($db);
 }



}

function guardar_editar_usuario(){
    global $db, $error, $usua, $logo, $footer_correo;
    $id = ($_GET['id']);
// RECIBE LOS DATOS DEL FORM
$idusuario = e($_POST['idusuario']);
$nombre = strtoupper(e($_POST['nombre']));
$email = strtolower(e($_POST['email']));
$telefono_usuario     =  e($_POST['telefono_usuario']);
$celular_usuario      =  e($_POST['celular_usuario']);
$direccion_usuario    =  e($_POST['direccion_usuario']);
$ciudad_usuario       =  e($_POST['ciudad_usuario']);
$estado_usuario       =  e($_POST['estado_usuario']);
$municipio_usuario    =  e($_POST['municipio_usuario']);
$parroquia_usuario    =  e($_POST['parroquia_usuario']);
$parroquia_usuario    =  e($_POST['parroquia_usuario']);
$status_usuario    =  e($_POST['status_usuario']);
$web    =  e($_REQUEST['web']);


//$password = md5($password_usuario);//encrypt the password before saving in the database

$sql = "UPDATE users SET
   nombre       = '$nombre',
   email        = '$email',
   idusuario    = '$idusuario',
   username     = '$idusuario',
   tlf          = '$telefono_usuario',
   cel          = '$celular_usuario',
   direccion    = '$direccion_usuario',
   ciudad       = '$ciudad_usuario',
   estado       = '$estado_usuario',
   municipio    = '$municipio_usuario',
   parroquia    = '$parroquia_usuario',
   status       = '$status_usuario'
   WHERE id     = '$id'";

if (mysqli_query($db, $sql)) {
    $_SESSION['usuarios']  = '<i class="fa fa-thumbs.-up"></i> Se ha Actualizado este usuario de manera correcta..!!<br>';
    //sleep(10);

  $asunto = "Actualizacion de Usuario";
  $cuerpo = '<p>Hola '.$nombre.' <br><br> Por alguna razon hemos tenido que modificar tu perfil dentro de la plataforma, normalmente se debe a que al momento de ingresar tus datos en el formulario de solicitud de afiliacion algunos datos como tu correo lo escribistes con errores, o colocastes datos incompletos y los mismos ya fueron corregidos, te invitamos a utilizar tus credenciales:</p><p style="text-align: justify;"><strong>CREDENCIALES DE ACCESO:</strong></p><p style="text-align: center;"><br> <span style="background-color: #70FF70; color: #000000; display: inline-block; padding: 3px 10px; font-weight: bold; border-radius: 5px;">Correo Registrado: <strong>'.$email.'</strong><br>Su Usuario es: <strong>'.$idusuario.'</strong></span></p><p>&nbsp;</p><hr /><p>Ahora puedes acceder y crear tu contrase&ntilde;a desde el modulo <a href="https://virtual.jesuministrosymas.com.ve/u/recuperar_password.php" target="_blank"> OLVIDO CONTRASE&Ntilde;A:</a></p><p style="text-align: center;"><br> <span style="background-color: #DE0000; color: #fff; display: inline-block; padding: 3px 10px; font-weight: bold; border-radius: 5px;"><strong><a href="https://virtual.jesuministrosymas.com.ve/u/recuperar_password.php" target="_blank">RECUPERA TU CLAVE DE ACCESO AQUI</a></strong></span></p>';

    enviarEmail($email, $nombre, $asunto, $cuerpo);

    $_SESSION['usuarios']  .='<i class="fa fa-envelope"></i> Le Hemos enviado un Correo notificandole sobre esta accion..<br>';

    header('location:'.$web);


 } else {
    echo "Error updating record: " . mysqli_error($db);
    mysqli_close($db);
 }



}

function editar_usuario(){
    global $db, $error;
// RECIBE LOS DATOS DEL FORM

$id = ($_GET['id']);

if (isAdmin()){



$query = "SELECT * FROM users WHERE id = '$id'";
		$result = mysqli_query($db, $query);
        $rows =  mysqli_num_rows($result);
        $row = mysqli_fetch_array($result);
		if ($rows<1){
			$_SESSION['editar_usuarios']  = "Lo sentimos, el usuario que intenta editar no existe id $id.<br>";
			//mysqli_close($db);
		} else {
            $idusuario = $row['idusuario'];
            $nombre = $row['nombre'];
            $email = $row['email'];
            $telefono_usuario = $row['tlf'];
            $celular_usuario = $row['cel'];
            $direccion_usuario = $row['direccion'];
            $ciudad_usuario = $row['ciudad'];
            $estado_usuario = $row['estado'];
            $municipio_usuario = $row['municipio'];
            $parroquia_usuario = $row['parroquia'];
            //$password_usuario = $row['password'];
            $status_usuario = $row['status'];

            $option = "";
            if ($status_usuario ==1){
                $option = '<option value= "'.$status_usuario.'">ACTIVO</option>
                <option value = "0">SUSPENDER</option>';
            }else if ($status_usuario ==0){
                $option = '<option value= "'.$status_usuario.'">SUSPENDIDO</option>
                <option value = "1">ACTIVAR</option>';
            }

            $editar_usuario = ' <form autocomplete="off" class="was-validated" method="post" action= "editar_usuarios.php?id='.$id.'">';
$editar_usuario .= 'Web de Origen: ' . $web = basename($_SERVER['REQUEST_URI']).'<br>';
$editar_usuario .= 'Identificador: ' .$id .'<br>';
$editar_usuario .= 'Usuario: ' .$idusuario .'<br>';
$editar_usuario .= 'Nombre: ' .$nombre .'<br>';
$editar_usuario .= 'Email: ' .$email .'<br>';
$editar_usuario .= '<div class="dropdown-divider"></div>';

$editar_usuario .= '<div class="form-group">
<label for="nombre">Numero de Cliente</label>
<input type="tel" class="form-control" id="idusuario" aria-describedby="idusuario" placeholder="Ingrese Id de Usuario" name="idusuario" value="';
$editar_usuario .= $idusuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el idusuario.</div>
</div>



<div class="form-group">
<label for="nombre">Nombre</label>
<input type="tel" class="form-control" id="nombre" aria-describedby="nombre" placeholder="Ingrese nombre" name="nombre" value="';
$editar_usuario .= $nombre;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el nombre.</div>
</div>


<div class="form-group">
<label for="email">Email</label>
<input type="tel" class="form-control" id="email" aria-describedby="email" placeholder="Ingrese Email" name="email" value="';
$editar_usuario .= $email;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Email.</div>
</div>



<div class="form-group">
<label for="telefono_usuario">Numero de Telefono Local</label>
<input type="tel" class="form-control" id="telefono_usuario" aria-describedby="telefono_usuario" placeholder="Ingrese su numero de Telefono local" name="telefono_usuario" value="';
$editar_usuario .= $telefono_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el numero de Telefono local.</div>
</div>

<div class="form-group">
<label for="celular_usuario">Numero de Celular</label>
<input type="tel" class="form-control" id="celular_usuario" aria-describedby="celular_usuario" placeholder="Ingrese su numero de Celular" name="celular_usuario" value="';
$editar_usuario .= $celular_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar su numero de telefono Celular.</div>
</div>

<div class="form-group">
<label for="direccion_usuario">Su Direccion Completa</label>
<input type="textarea" class="form-control" id="direccion_usuario" aria-describedby="direccion_usuario" placeholder="Ingrese su Direccion" name="direccion_usuario" value="';
$editar_usuario .= $direccion_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar su Direccion completa.</div>
</div>

<div class="form-group">
<label for="estado_usuario">Estado donde Vive</label>
<input type="text" class="form-control" id="estado_usuario" aria-describedby="estado_usuario" placeholder="Ingrese el Estado" name="estado_usuario" value="';
$editar_usuario .= $estado_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Estado donde vive.</div>
</div>

<div class="form-group">
<label for="ciudad_usuario">Ciudad donde vive</label>
<input type="text" class="form-control" id="ciudad_usuario" aria-describedby="ciudad_usuario" placeholder="Ingrese la Ciudad" name="ciudad_usuario" value="';
$editar_usuario .= $ciudad_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Ciudad donde vive.</div>
</div>

<div class="form-group">
<label for="municipio_usuario">Municipio donde vive</label>
<input type="text" class="form-control" id="municipio_usuario" aria-describedby="municipio_usuario" placeholder="Ingrese el Municipio" name="municipio_usuario" value="';
$editar_usuario .= $municipio_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Municipio de ubicacion.</div>
</div>

<div class="form-group">
<label for="parroquia_usuario">Parroquia donde vive</label>
<input type="text" class="form-control" id="parroquia_usuario" aria-describedby="parroquia_usuario" placeholder="Ingrese el Parroquia" name="parroquia_usuario" value="';
$editar_usuario .= $parroquia_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Parroquia de ubicacion.</div>
</div>';

$editar_usuario .= '<div class="form-group">
<label for="exampleFormControlSelect1">Status de Usuario </label>
<select class="form-control" name = "status_usuario" id="status_usuario" value="'.$status_usuario.'">
'.$option.'
</select>
</div>';



$editar_usuario .= '<button type="submit" class="btn btn-primary" name="editar_desde_admin_btn">Enviar</button>

';
echo $editar_usuario;
        }
      } else {
        echo 'Sin autorizacion';
      }

}

//MOSTRAR PERFIL

function mostrar_perfil(){
    global $db, $usua;
    $query = "SELECT * FROM users WHERE username = '$usua'";
    $result = mysqli_query($db, $query);
    $rows = mysqli_fetch_array($result);

    $id = $rows['id'];
    $control = $rows['control'];

    echo '<h3>Los datos de su Usuario</h3>';

    echo '<div class="card">';
    echo '<ul class="list-group list-group-flush">';

    echo '<li class="list-group-item">';
    echo '<b>Usuario: </b>';
    echo $rows['username'];
    echo '<br><b>Nombre: </b>';
    echo $rows['nombre'];
    echo '<br><b>Email: </b>';
    echo $rows['email'];
    echo  '</li>';

    echo '<li class="list-group-item">';
    echo '<b>Telefono: </b>';
    echo $rows['tlf'];
    echo '<br><b>Celular: </b>';
    echo $rows['cel'];
    echo '<br><b>Direccion: </b>';
    echo $rows['direccion'];
    echo '<br><b>Estado: </b>';
    echo $rows['estado'];
    echo '<br><b>Ciudad: </b>';
    echo $rows['ciudad'];
    echo '<br><b>Municipio: </b>';
    echo $rows['municipio'];
    echo '<br><b>Parroquia: </b>';
    echo $rows['parroquia'];
    echo '</li>';


    echo '<li class="list-group-item">';
    echo '<b>Nombre de Comercio: </b>';
    echo $rows['nombre_comercio'];
    echo '<br><b>Direccion Comercio: </b>';
    echo $rows['direccion_comercio'];
    echo '<br><b>Logo: </b>';
    echo $rows['logo_comercio'];
    echo '</li>';


    echo '</ul>';

    echo '</div>';

    echo '<div class="text-right">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    <i class="fa fa-user-edit"></i> Editar</button>

    <a  class="btn btn-danger" type="button" href="../crear_password.php?id='.$id.'&control='.$control.'"><i class="fa fa-key"></i> Cambiar Contraseña</a>
    </div>';


    echo '<!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar Usuario</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">';
          modal_editar_desde_usuario();
          echo '
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button name="editar_desde_usuario_btn" type="submit" class="btn btn-primary">Guardar Cambios</button> </form>
          </div>
        </div>
      </div>
    </div>';




    echo '<!-- Modal -->
    <div class="modal fade" id="cambiarpassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Cambiar Password o Contraseña de Acceso</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">';
          modal_editar_password_desde_usuario();
          echo '
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button name="editar_password_desde_usuario_btn" type="submit" class="btn btn-primary">Guardar Cambios</button> </form>
          </div>
        </div>
      </div>
    </div>';

}

function preparar_entrega_pedido() {
global $db, $errors, $nombre;
   $id_pedido = ($_GET['id']);
   $user = ($_GET['user']);

   $lote_pedido="";

   $sql="SELECT sum(monto) AS 'total'
   FROM tarjetas
   WHERE usuario = '$user' AND id_pedido = '$id_pedido'";
   $result = mysqli_query($db, $sql);

   $query = "SELECT pedidos.*, users.id AS uid, users.nombre, users.email, users.username FROM pedidos INNER JOIN users ON pedidos.usuario=users.idusuario WHERE pedidos.id= '$id_pedido' ";
   $resultado = mysqli_query($db, $query) or mysqli_error($db);
   while ($row = mysqli_fetch_assoc($resultado)){
    $monto	 	    	= $row['monto'];
    $nombre	 	    	= $row['nombre'];

   }
   echo '<div class="container">';
   echo "Entrega para el Usuario <b>";
   echo $nombre;
   echo "</b><br>";
   echo "Identificador <b>";
   echo $user;
   echo "</b><br>";
   while ($row = mysqli_fetch_assoc($result))
   {
     if ($row['total']<1){
       echo "No se Han Asignado Tarjetas Aun <br>";
       echo "Se deben asignar <b> $monto Bs.</b><br>";
         } else {
          echo "A Este Pedido ya se le han asignado <b>".$row['total']. " Bs. </b><br>";
   }
 }

echo ' <form autocomplete="off" class="was-validated" method="post" action= "preparar_entrega_pedido.php?id='.$id_pedido.'&user='.$user.'">

<div class="form-group">
<label for="lote">Monto, Codigo y Serial</label>
<input minlength="31" required  type="text" class="form-control" id="lote" aria-describedby="lote" placeholder="Ingrese el lote" name="lote"';
echo 'value="';
echo $lote_pedido;
echo '">';
echo '
<div class="invalid-feedback">Se debe utilizar el siguiente formato: 3 1234 1234 1234 1234 123456789 sin puntos ni coma, solo separado con espacios</div>
</div>
<input class="input-group-text" id="finalcount" value="0" disabled />

<button type="submit" class="btn btn-primary" name="entregar_pedido_btn">Enviar</button>

</form>
</div>';


}
function confirmaciones(){
    global $db, $fecha_act;

    $msg = "";
    $id_pedido = e($_REQUEST['id_pedido']);
    $confirmacion = e($_REQUEST['confirmacion']);
    $status = '';

if ($confirmacion == 'DEVOLUCION') {
  # code...
  $status = 4;
} else if ($confirmacion == 'Esperando_Operador') {
  $status = 4;
} else {
  $status = 3;
}


    $lote_confirmacion = str_replace("	", " ", $confirmacion);

    $allValues = explode(' ', $lote_confirmacion);

    $allIDs=[];

    $query2 = "SELECT * FROM recargar WHERE relacion = '$id_pedido' ORDER BY id ASC";
    $result2 = mysqli_query($db, $query2);
    $row2 =  mysqli_num_rows($result2);

    while ($row2 = mysqli_fetch_assoc($result2))
    {
        $id = $row2['id'] ;
        $allIDs[]=$id;
    }

$allParams=array_combine($allIDs,$allValues);

if($allParams){
    $db->autocommit(FALSE);
    $sql="UPDATE recargar SET confirmacion = ?, status = '$status' WHERE id = ?";
    $stmt=$db->prepare($sql);
    $stmt->bind_param('si', $value,$id);
    $status=TRUE;
    foreach ($allParams as $id=>$value) {
        $stmt->execute() ? null : $msg =$stmt->error;
    }

    if(!$msg){
        $db->commit();
        // ACTUALIZAR TABLA PEDIDOS A ENTREGADO
$query = "UPDATE pedidos
SET status_pedido = 'ENTREGADO',
 fecha_entrega = '$fecha_act'
WHERE id = '$id_pedido'";

if (mysqli_query($db, $query)) {

    $query3 = "SELECT recargar.*, users.nombre, users.email FROM recargar INNER JOIN users ON recargar.user=users.idusuario WHERE relacion = '$id_pedido' ORDER BY id ASC";
    $result3 = mysqli_query($db, $query3);
    $row3 =  mysqli_num_rows($result3);



    $recarga = '<div class="table-responsive"><table class="table table-bordered table-hover ">';
    $recarga .= '<thead><tr>';
    $recarga .= '<th height="17" width ="20%" align="center">';
    $recarga .= 'NUMERO';
    $recarga .= '</th>';
    $recarga .= '<th height="17" width ="20%" align="center">';
    $recarga .= 'TIPO';
    $recarga .= '</th>';
    $recarga .= '<th height="17" width ="20%" align="center">';
    $recarga .= 'MONTO';
    $recarga .= '</th>';
    $recarga .= '<th height="17" width ="30%" align="center">';
    $recarga .= 'CONFIRMACION';
    $recarga .= '</th>';
    $recarga .= '</tr></thead>';

    while ($row3 = mysqli_fetch_assoc($result3))
    {
        $operador =$row3['operador'];
        $nombre =  $row3['nombre'];
        $email =   $row3['email'];
        $nro =   $row3['nro'];
        $tipo =   $row3['tipo'];
        $monto =   $row3['monto'];
        $confirmacion =   $row3['confirmacion'];


        $recarga .= '<tr>';

  $recarga .= '<td align="center">';
  $recarga .= $nro;
  $recarga .= '</td>';
  $recarga .= '<td align="center">';
  $recarga .= $tipo;
  $recarga .= '</td>';
  $recarga .= '<td align="center">';
  $recarga .= $monto;
  $recarga .= ' Bs.</td>';
  $recarga .= '<td align="center"> Nro: ';
  $recarga .= $confirmacion;
  $recarga .= '</td>';
  $recarga .= '</tr>';

}
$recarga .=  '</table></div>';


$confirmaciones = $recarga;

if ($operador=='Movilnet') {
  // code...
  $mensaje_movilnet = "<br><br>Es posible que los codigos de confirmacion recibidos esten marcados como <b>Esperando_Operador</b> esto es indicativo de que esa solicitud en particular de recarga aun no ha sido procesada y nuestro sistema ha incluido dicho requerimiento en un bucle que se estara repitiendo hasta recargar el numero solicitado o hasta que se efectue el reverso de dicha solicitud.";
} else {
  // code...
  $mensaje_movilnet = '';
}

	$asunto = "Recargas Procesadas";
	$cuerpo = "Hola $nombre <br><br>Le informamos que las Recargas $operador solicitadas han sido procesadas de manera exitosa y puede ingresar a su plataforma para verificar los numeros de confirmacion respectivos. $mensaje_movilnet ";
  $cuerpo .= "<h2>Recargas Solicitadas</h2>";
  $cuerpo .= $confirmaciones;

  enviarEmail($email, $nombre, $asunto, $cuerpo);


   } else {
    $_SESSION['msn_pedidos']  = '<i class="fa fa-exclamation-triangle fa-fw"></i>Algo ha ocurrido'. mysqli_error($db);
   }

    $_SESSION['msn_pedidos']  = 'Todo fue actualizado sin problemas<br><i class="fa fa-envelope"></i> Se ha enviado un correo electronico a '.$nombre.' notificando sobre estas asignaciones de recarga..!!<br>';
    }else{
        $db->rollback();
    }
    $db->autocommit(TRUE);
} else {
    $_SESSION['msn_pedidos']  = '<i class="fa fa-exclamation-triangle"></i>Error, no se pueden combinar los valores, por favor revísalos.';
}

}



function entregar_pedido(){
  global $db, $fecha_act;

  $id_pedido = e($_REQUEST['id']);
  $user = e($_REQUEST['user']);


  $lote = e($_REQUEST['lote']);
  $lote_pedido = str_replace("	", " ", $lote);
  $datos = $lote_pedido;
// divides por espacios y cada 6 elementos, los elementos de cada fila
$temp = array_chunk(explode(' ', $datos), 6);
$ar = array();



foreach($temp as $key => $v) {
  // optienes el 1º elemento monto
  $ar[$key]['monto'] = array_shift($v);
  // optienes el ultimo elemento, serial
  $ar[$key]['serial'] = array_pop($v);
  // lo que queda es el codigo, lo unes con espacios
  $ar[$key]['codigo'] = implode(' ', $v);

  $monto =   $ar[$key]['monto'];
  $codigo =  $ar[$key]['codigo'];
  $serial =  $ar[$key]['serial'];
  try {
  $sql = "INSERT INTO tarjetas (id, monto, codigo, serial, usuario, id_pedido)
      VALUES(null, '$monto', ' $codigo', '$serial', '$user', '$id_pedido')";
     $resultado_ingreso = mysqli_query($db, $sql) or $error= (mysqli_error($db));
    } catch (Exception $e) {
        // Aqui puedes desplegar el error si quieres
        $_SESSION['msn_pedidos']  = "Algo ha Ocurrido<br>No se ejecutara ninguna accion, este fue el error:<br>" . $error;
        continue;
    }

}

if (!$resultado_ingreso){
$_SESSION['msn_pedidos']  = "Algo ha Ocurrido<br>" . $error;
} else {

$status = 'ENTREGADO';
$admin = $_SESSION['user']['username'];
$concepto = "ASIGNACION DE TARJETAS";
$sqlUPDATE = "UPDATE pedidos SET
status_pedido = '$status', fecha_entrega = '$fecha_act'
WHERE id = '$id_pedido'";

if (mysqli_query($db, $sqlUPDATE)) {
$_SESSION['msn_pedidos']  = "Se ha Actualizado el STATUS del pedido..!!<br>";
} else {
echo "Error updating record: " . mysqli_error($db);
//mysqli_close($db);
}


$query = "INSERT INTO bitacora (
id,
id_pedido,
status,
admin,
concepto)
VALUES(null, '$id_pedido', '$status', '$admin', '$concepto')";
  //mysqli_query($db, $query);
    $resultado_ingreso = mysqli_query($db, $query) or mysqli_error($db);


if (count($ar)<2){
$t= "Tarjeta";
} else {
$t= "Tarjetas";
}
$_SESSION['msn_pedidos']  .= "Se ha entregado el Pedido con Exito.<br>";
$_SESSION['msn_pedidos']  .= "En esta Transaccion fueron asignadas " .count($ar)." ".$t." <br>";

$sql1="SELECT sum(monto) AS 'total'
  FROM tarjetas
  WHERE usuario = '$user' AND id_pedido = '$id_pedido'";
  $result1 = mysqli_query($db, $sql1);

  while ($row1 = mysqli_fetch_assoc($result1))
{
  if ($row1['total']<1){
    echo "No se Ha encontrado Registros";
      } else {
        $_SESSION['msn_pedidos']  .= "Total de Bs. Entregado ".$row1['total']." Bs.<br>";
}
}


$sql2 = "SELECT tarjetas.*, users.nombre, users.email, users.username FROM tarjetas INNER JOIN users  ON tarjetas.usuario=users.idusuario WHERE usuario = '$user' AND id_pedido = '$id_pedido' ";
$result2 = mysqli_query($db, $sql2);
//if (mysqli_query($db, $query)){
$row2count =  mysqli_num_rows($result2);
//$row2 =  mysqli_fetch_assoc($result2);

  $tarjetas = '<div class="table-responsive"><table class="table table-bordered table-hover ">';
  $tarjetas .= '<thead><tr>';
  $tarjetas .= '<th height="17" width ="20%" align="center">';
  $tarjetas .= 'MONTO';
  $tarjetas .= '</th>';
  $tarjetas .= '<th height="17" width ="20%" align="center">';
  $tarjetas .= 'CODIGO';
  $tarjetas .= '</th>';
  $tarjetas .= '<th height="17" width ="20%" align="center">';
  $tarjetas .= 'SERIAL';
  $tarjetas .= '</th>';
  $tarjetas .= '</tr></thead>';

while ($row2 = mysqli_fetch_assoc($result2)) {
  $monto = $row2['monto'];
  $codigo = $row2['codigo'];
  $serial = $row2['serial'];
  $email_usuario = $row2['email'];
  $nombre_usuario = $row2['nombre'];

  $tarjetas .= '<tr>';

  $tarjetas .= '<td align="center">';
  $tarjetas .= $monto;
  $tarjetas .= '</td>';
  $tarjetas .= '<td align="center">';
  $tarjetas .= $codigo;
  $tarjetas .= '</td>';
  $tarjetas .= '<td align="center">';
  $tarjetas .= $serial;
  $tarjetas .= '</td>';
  $tarjetas .= '</tr>';
}
  $tarjetas .=  '</table></div>';

  $tarjetas_asignadas = $tarjetas;


$_SESSION['msn_pedidos']  .= "En total se le han asignado ".$row2count." tarjetas al usuario " .$user."<br>";

$email = $email_usuario;
$nombre = $nombre_usuario;
$asunto = "Entrega de Tarjetas UN1CA";
$cuerpo = "Hola $nombre <br><br> <h1>FAVOR LEER</h1>Por medio de la presente le informamos que la operadora Movilnet ha asignado tarjetas UN1CAS a su Pedido y desde ya puede acceder y ver su Pedido de Tarjetas On-Line en: ";
$cuerpo .= '<a href= "https://virtual.jesuministrosymas.com.ve/u/usuario/pedidos_movilnet.php"><b>VER PEDIDO COMPLETO AQUI</b></a>.<br><br>';
$cuerpo .= "<b>PARA ACCEDER A SU PEDIDO COMPLETO DEBE HACERLO INGRESANDO DIRECTAMENTE A SU PLATAFORMA DIGITAL</b>";
$cuerpo .= '<a href= "https://virtual.jesuministrosymas.com.ve/u/usuario/pedidos_movilnet.php"><b>VER PEDIDO COMPLETO AQUI</b></a>.<br><br>';
$cuerpo .= "<h2>Tarjetas Asignadas</h2>";
$cuerpo .= $tarjetas_asignadas;
$cuerpo .= "<h2>Consideraciones</h2>";
$cuerpo .= "Motivado a los ultimos eventos acontecidos en el pais, tanto el personal como la infraestructura interna de CANTV se ha visto en riesgo de ataque terrorista y por ello hay lentitud y retrasos en las entregas.";

enviarEmail($email, $nombre, $asunto, $cuerpo);

$_SESSION['msn_pedidos']  .= '<i class="fa fa-envelope"></i> Se ha enviado un correo electronico notificando sobre esta asignacion de pedido..!!<br>';

}
}
//}



  




  function analizar_mensualidad(){
    global $db, $usua, $mes_de_pago_actual, $limite_basico,
    $limite_avanzado, $limite_vip, $titulopag, $planes, $operador, $como_pagar, $registrar_mensualidad;

    $montos_permitidos ="";
    $limite_base = 0;


      $inicio = new DateTime();
      $fin = new DateTime();
      $fin = $fin->modify('last day of this month');

      $hoy_a = date('d/m/Y');
      $fin_a = $fin->format('d/m/Y');

      $interval = $inicio->diff($fin);
      $interval = $interval->days .' Dias';


  $query = "SELECT * FROM pagos WHERE user='$usua' AND mes_de_pago = '$mes_de_pago_actual' AND concepto = 'MENS_MOVILNET' AND status_pago ='APROBADO' ORDER BY `id` DESC LIMIT 1";
  $resultado = mysqli_query($db, $query);
  $row = mysqli_fetch_assoc($resultado);

  if ($row['afiliacion']=='BASICO'){
   $l = $limite_basico;
  } else if ($row['afiliacion']=='AVANZADO') {
   $l = $limite_avanzado;
  } else {
    $l = $limite_vip;
   }

   $sql = "SELECT * FROM monto LIMIT $limite_base, $l ";
  $resultadosql = mysqli_query($db, $sql);
  $num_datos = mysqli_num_rows($resultadosql);
  while ($rowsql=mysqli_fetch_row($resultadosql)){
      if ($num_datos ==1)
      {
        $montos_permitidos = $montos_permitidos .  $rowsql[1] . " Bs.";
      }
      else if ($num_datos ==2)
      {
        $montos_permitidos = $montos_permitidos .  $rowsql[1] . " Bs y ";
      }
      else if ($num_datos > 2)
      {
        $montos_permitidos = $montos_permitidos .  $rowsql[1] . " Bs., ";
      }
      $num_datos--;
    }
  $logo_movilnet = '<img src="../images/operadoras/movilnet.png" class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}" alt="">';

  if (mysqli_num_rows($resultado)){
      echo $logo_movilnet;
    echo ' De la platafoma <b>'. $operador .'</b> le quedan <b>'.$interval.'</b> Restantes para disfrutar de su plan. <b>'.$row['afiliacion'].'
    </b>';

    $actual = $row['mes_de_pago'];


  echo '<hr><div class="alert alert-success" role="alert">
   <h3>SOBRE SU PLAN MOVILNET CONTRATADO</h3>En el periodo correspondiente al mes de <b> '.strtoupper($actual).'</b> su tipo de Plan es Afiliacion <b>'.$row['afiliacion'].'
  </b> y Vence el dia <b>'. $fin_a .'</b> Usted puede efectuar pedidos de los siguientes Montos: <b>'.$montos_permitidos.'</b> por cada una de sus solicitudes, la cantidad de pedidos diarios, semanales o mensuales son ilimitados. Cada vez que la operadora le asigne un pedido usted podra generar un nuevo pedido, evite hacer transferencias adelantadas, efectue solo la transferencia del pedido que va a declarar. </div><hr>';
  } else {

    if ($titulopag == "Movilnet")
  {
    $link = $registrar_mensualidad;
  } else {
    $link = '<a class="btn btn-danger" href="mensualidad_movilnet.php"><span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> <b>PAGUE SU MENSUALIDAD</b> <span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></a>';
  }

    echo $como_pagar;
    echo $logo_movilnet;
    echo ' No se ha detectado pago de mensualidad para el uso del servicio <b>'.$titulopag.'</b>.';
    echo '<hr><div class="alert alert-warning" role="alert">
    Lo sentimos aun no se ha registrado o aprobado pagos de Mensualidad correspondientes de la operadora '.strtoupper($operador).' del mes de: <b>'.strtoupper($mes_de_pago_actual).'</b> si no ha efectuado pago le invitamos a hacerlo ingresando a:<br> '.$link.'<br>Si por el contrario ya efectuo su pago debe esperar a que el mismo sea conformado.<br><br>
    <h5>Vigencia de su Plan '.$operador.'</h5>Por ejemplo:<br>Aprobandose su pago hoy: <b>'. $hoy_a .'</b> Su renta venceria el <b>'. $fin_a .'</b><br>Pudiendo disfrutar de  <b>'.$interval . '</b> de su Plan.<br> <br>Si no desea pagar mensualidades por uso de la plataforma ahora tendra la posibilidad de efectuar pedidos para consumo personal<br><br> <a href="pedidos_movilnet_sin_plan.php" class="btn btn-info" ><b><span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> <i class="fas fa-hand-point-right"></i>  HACER PEDIDOS SIN PAGAR MENSUALIDADES  <span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></b></a> </div><hr>';

  planes_movilnet();

  }



  }




function m_o($a){
  global $db, $montos;

  $sql ="SELECT * FROM `monto_recarga` WHERE mod (monto, $a) = 0";
 $resultadosql = mysqli_query($db, $sql);
 $num_datos = mysqli_num_rows($resultadosql);
 while ($rowsql=mysqli_fetch_row($resultadosql)){

  if($num_datos ==1)
  {
     $montos = $montos . "y " . $rowsql[1] . " Bs.";
  }
  else
  {

     $montos = $montos . $rowsql[1] . " Bs., ";
  }

     $num_datos--;
 }


}






function analizar_mensualidad2(){
    global $db, $usua, $mes_de_pago_actual, $limite_basico,
    $limite_avanzado, $limite_vip, $operador, $planes, $fecha_actual_sistema, $concepto, $m_dias_r, $como_pagar, $me, $pago_mensualidad, $cabecera_privada;

    $montos_permitidos ="";
    $limite_base = 0;

    selector_operador();


  analisis_dias_restantes();
  $cabecera_privada = $como_pagar;
  $cabecera_privada .= '<img width="20%" src="../images/operadoras/'.strtolower ($operador) .'.png" class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}" alt="">';
  $cabecera_privada .= $m_dias_r;
  }


function ejecutar_editar_contenido(){
  global $db;

$rowid      = e($_REQUEST['id']);
$contenido  = e($_POST['contenido']);


  $sql = "UPDATE contenido SET
  contenido = '$contenido'
  WHERE id = '$rowid'";
  if (mysqli_query($db, $sql)) {
    $_SESSION['editar_contenido']  = "Se ha Actualizado el contenido de manera correcta..!!<br>";
    		//$email = "jose@jesuministrosymas.com.ve";
		//$nombre = "Jose";
		//$asunto = "Prueba de Contenido";
		//$cuerpo = $contenido;
		//enviarEmail($email, $nombre, $asunto, $cuerpo);
		//$_SESSION['editar_contenido']  .= '<i class="fa fa-envelope"></i> Hemos enviado Un correo a jose@jesuministrosymas.com.ve<br>';

 } else {
  $_SESSION['editar_contenido']  = "NO SE PUEDE ACTUALIZAR..!!";
    echo "Un Error ha ocurrido: " . mysqli_error($db);
    //mysqli_close($db);
 }
}

function ejecutar_editar_mensajeria(){
  global $db;

$rowid      = e($_REQUEST['id']);
$contenido  = e($_REQUEST['contenido']);
$asunto  = e($_REQUEST['asunto']);
$email = e($_REQUEST['email']);
$nombre = e($_REQUEST['nombre']);
$destinatario = e($_REQUEST['destinatario']);
$control = '';

if ($destinatario == 'JESUMINISTROSYMAS'){
  $control = '1';

} else {
  $control = '0';
}


  $sql = "UPDATE mensajes SET
  contenido = '$contenido', asunto = '$asunto', fecha_mensaje = NOW(), control = '$control'
  WHERE id = '$rowid'";
  if (mysqli_query($db, $sql)) {
    $_SESSION['editar_mensajeria']  = "Se ha Actualizado el contenido de manera correcta..!!<br>";

    $asunto2 = "Su consulta ha recibido respuesta";
    $cuerpo = "Hola $nombre <br><br>Tu requerimiento $asunto ha recibido la siguiente respuesta:<br>$contenido";

    enviarEmail($email, $nombre, $asunto2, $cuerpo);

 } else {
  $_SESSION['editar_mensajeria']  = "NO SE PUEDE ACTUALIZAR..!!";
    echo "Un Error ha ocurrido: " . mysqli_error($db);
    //mysqli_close($db);
 }
}



function breadcrumbs($sep = ' » ', $home = 'Inicio') {
    $bc     =   '<ul class="breadcrumb">';
    //Get the server http address
    $site   =   'https://'.$_SERVER['HTTP_HOST'];
    //Get all vars en skip the empty ones
    $crumbs =   array_filter( explode("/",$_SERVER["REQUEST_URI"]) );
    //Create the homepage breadcrumb
    $bc    .=   '<li><a href="'.$site.'">'.$home.'</a>'.$sep.'</li>';
    //Count all not empty breadcrumbs
    $nm     =   count($crumbs);
    $i      =   1;
    //Loop through the crumbs
    foreach($crumbs as $crumb){
    //grab the last crumb
    $last_piece = end($crumbs);

        //Make the link look nice
        $link    =  ucfirst( str_replace( array(".php","-","_"), array(""," "," ") ,$crumb) );

        //Loose the last seperator
        $sep     =  $i==$nm?'':$sep;
        //Add crumbs to the root
        $site   .=  '/'.$crumb;
        //Check if last crumb
        if ($last_piece!==$crumb){
        //Make the next crumb
        $bc     .= '<li><a href="'.$site.'">'.$link.'</a>'.$sep.'</li>';
        } else {
        //Last crumb, do not make it a link
        $bc     .= '<li class="active">'.ucfirst( str_replace( array(".php","-","_"), array(""," "," ") ,$last_piece)).'</li>';
        }
        $i++;
    }
    $bc .=  '</ul>';
    //Return the result
    return $bc;
    }


    



function enviar_comentario(){
    global $usua, $modal_usuario_bloqueado;


    $modal = '
    <!-- Button trigger modal -->
<button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#exampleModal">
  <b data-toggle="popover" title="Dejanos tu Comentario" data-content="Ingresa aqui y dejanos tu comentario."> <i class="fa fa-comments fa-fw"></i> Dejanos tu Opinion</b>
</button>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Dejanos tu Comentario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">';

    if (isActive())
{
  $modal .= '<h4>Su comentario es importante para nosotros</h4>
        <p>En pro de que usted pueda expresarse de una manera publica, le informamos que el comentario que usted efectue en este sitio sera visible por los otros asociados a esta plataforma, recomendamos no suministrar claves de acceso ni informacion personal como: numeros de identificacion, direccion de ubicacion ni numeros de telefono o correos de contacto.</p>
        <p>Asi mismo le indicamos que este no es un espacio para reclamos, si usted posee un reclamo el mismo debe ser canalizado desde el buzon de reclamos o buzones de sugerencia.</p>
        <p>Los comentarios que contengan contenido ofensivo o sensible podra ser baneado.</p>
        <p>Si usted tiene alguna duda, o si usted necesita hacer un reclamo, si desea hacernos llegar una sugerencia, o desea hacer un aporte que usted condidere puede hacer mejorar el o los servicios ofrecidos en la plataforma puede contactarse con nosotros <a href="mensajeria.php"><b>AQUI</b></a></p>
          <form autocomplete="off" class="was-validated" method="post" action ="#">
          <label for="comentario">Su Comentario</label>
  <input required  pattern="[A-Za-z0-9 ]{20,250}"
  title="Puede utilizar Letras y números. Tamaño mínimo de su comentario debe ser de: 20 caracteres. Tamaño máximo: 250 caracteres" type="text" class="form-control" id="comentario" aria-describedby="comentario" placeholder="Ingrese el comentario" name="comentario">
  <p>Deja aca tus impresiones sobre nuestro servicio y sobre la atencion que usted ha recibido de nuestra parte.</p>
  <input type="hidden" name="usua" value="'.$usua.'">';

  $modal .= '</div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      <button type="submit" name="enviar_comentario_btn" class="btn btn-success"><i class="fa fa-comments fa-fw"></i> Enviar Comentario</button>
      </form>
    </div>
  </div>
</div>
</div>';

} else {
  $modal .= $modal_usuario_bloqueado;

$modal .= '</div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    <button type="submit" name="enviar_comentario_btn" class="btn btn-success disabled"><i class="fa fa-comments fa-fw"></i> Enviar Comentario</button>
    </form>
  </div>
</div>
</div>
</div>';
}

echo $modal;
}



function procesar_enviar_comentario(){
    global $db, $logo, $footer_correo;
    $user= e($_REQUEST['usua']);
    $comentario= e($_REQUEST['comentario']);

    //$user= mysqli_real_escape_string($db,$_REQUEST['usua']);
    //$comentario= mysqli_real_escape_string($db,$_REQUEST['comentario']);

    //echo 'Se ha agregado el siguiente comentario: '. $comentario;

    $query = "INSERT INTO comentario (id, user, comentario)
VALUES(null, '$user', '$comentario')";
	//mysqli_query($db, $query);
    //$resultado_ingreso = mysqli_query($db, $query) or mysqli_error($db);
    if (mysqli_query($db, $query)){
    $_SESSION['comentario']  = "Se ha registrado su comentario con el siguiente contenido:  $comentario y en breve figurara en el carrusel de comentarios y sera visible por todos.<br>";
  } else {
    echo mysqli_error($db);
}
}

function mostrar_alert($a) {
if (isset($_SESSION[$a])) {
			echo '<div class="alert alert-danger" role="alert" ><h3>';
			echo $_SESSION[$a];
			unset($_SESSION[$a]);
			echo '</h3></div>';
}
}



function pag_test($ini, $limit_end, $total){

  $url = basename($_SERVER ["PHP_SELF"]);

  if (isset($_REQUEST['busqueda'])) {
      $busqueda = strtolower(e($_REQUEST['busqueda']));

      if (empty($busqueda)) {
      $busq = "";
    } else {
      $busq = '&busqueda='.$busqueda;
    }


    } else {
      $busq = "";
      //unset($_REQUEST['busqueda']);
    }
//echo '<div class="container">';
echo '<nav aria-label="Page navigation example">';
echo '<ul class="pagination pagination-sm flex-sm-wrap">';
/****************************************/
if(($ini - 1) == 0)
{
echo "<li class='page-item disabled'><a title='Principio' class='page-link' href='$url?p=".(1).$busq."'><b><i class='fa fa-angle-double-left'></i>  </b></a></li>";
echo "<li class='page-item disabled'><a title='Anterior' class='page-link' href='#'><i class='fa fa-angle-left'></i>  </a></li>";
}
else
{
echo "<li class='page-item'><a title='Principio' class='page-link' href='$url?p=".(1).$busq."'><b><i class='fa fa-angle-double-left'></i>  </b></a></li>";
echo "<li class='page-item'><a title='Anterior' class='page-link' href='$url?p=".($ini-1).$busq."'><b><i class='fa fa-angle-left'></i>  </b></a></li>";
}
/****************************************/

  for($k=max(1, min($ini-5,$total-10));
  $k < max(min(11,$total+1), min($ini+5,$total+1));
  $k++)
  {
if($ini == $k){
    echo "<li class='page-item active'><a class='page-link' href='$url?p=$k$busq'>".$k."</a></li>";
}
else{
    echo "<li class='page-item'><a class='page-link' href='$url?p=$k$busq'>".$k."</a></li>";
}
}



/****************************************/
if($ini == $total)
{
echo "<li class='page-item disabled'><a title='Siguiente' class='page-link' href='#'> <i class='fa fa-angle-right'></i> </a></li>";
echo "<li class='page-item disabled'><a title='Ultimo' class='page-link' href='$url?p=".($total).$busq."'><b> <i class='fa fa-angle-double-right'></i></b></a></li>";
}
else
{
echo "<li class='page-item'><a title='Siguiente' class='page-link' href='$url?p=".($ini+1).$busq."'><b> <i class='fa fa-angle-right'></i></b></a></li>";
echo "<li class='page-item'><a title='Ultimo' class='page-link' href='$url?p=".($total).$busq."'><b> <i class='fa fa-angle-double-right'></i></b></a></li>";
}
/*******************END*******************/
echo "</ul>";
// echo "</div>";
echo '</nav>';
//echo '</div>';
}


function admin_comentarios(){
  global $db, $limit_end;
  //$limit_end = 1;
  //$init = "";

if (isset($_GET['p']))
$ini=$_GET['p'];
else
$ini=1;

$init = ($ini-1) * $limit_end;

  $count_query="SELECT COUNT(*) FROM comentario";

  $query = "SELECT *, comentario.id AS idrow, users.nombre AS 'nombre'
  FROM comentario
  INNER JOIN users ON (comentario.user=users.idusuario)
  ORDER BY fecha DESC
  LIMIT $init, $limit_end";

  //$result = mysqli_query($db,$query);
$result_count = mysqli_query($db, $count_query);



  if (isAdmin()){
    $num = $db->query($count_query);
    $x = $num->fetch_array();
    $total = ceil($x[0]/$limit_end);

    echo '<div class="d-none d-sm-none d-md-block">';
        pag($ini, $limit_end, $total);
    echo "</div>";
    echo '<div class="d-block d-sm-block d-md-none">';
    pag_test($ini, $limit_end, $total);
    echo "</div>";

    $admin_comentarios = '<div class="table-responsive">';
    $admin_comentarios .= '<table id="tabla1" class="table table-bordered table-hover stacktable">
      <thead>
       <tr>
       <th>ID</th>
       <th>Fecha del Comentario</th>
       <th>Nombre</th>
        <th>Comentario </th>
        <th>Accion</th>
       </tr>
       </thead>
       <tbody>';

       $c = $db->query($query);
       while($row = $c->fetch_array(MYSQLI_ASSOC))
        {
        $date = date_create($row['fecha']);
        $fecha = date_format($date, 'd-m-Y');
        $fecha_comentario = $fecha;
        $comentario = $row['comentario'];
        $id = $row['idrow'];
        $nombre = $row['nombre'];
        $user = $row['user'];

        $visible = $row['visible'];

    if ($visible==1)
    {
      $stdp = '<button type="submit" class="btn btn-success btn-sm" name="activar_desactivar_comentario_btn" data-toggle="popover" title="ACTIVO" data-content="Comentario ACTIVO haga click para desactivarlo y que no se muestre.">ACT <i class="fa fa-thumbs.-up"></i></button>';

} else {
      $stdp = '<button type="submit" class="btn btn-danger btn-sm" name="activar_desactivar_comentario_btn" data-toggle="popover" title="BLOQUEADO" data-content="Comentario BLOQUEADO, haga click para activarlo.">BLOQUEADO <i class="fa fa-thumbs.-down"></i></button>';

    }

    $link = '<form autocomplete="off" class="was-validated" method="post" action= "">
       <input type="hidden" name="id" value="'.$id.'">
    <input type="hidden" name="visible" value="'.$visible.'">
    '.$stdp.' </form>';



    $visible = $link;


    $admin_comentarios .= '<tr>';
    $admin_comentarios .= '<td>'.$id.'</td>
                          <td>'.$fecha_comentario.'</td>
                          <td>'.$nombre.'</td>
                          <td>'.$comentario .'</td>
                          <td>'.$visible.'</td>
                          </tr>';
        }
        $admin_comentarios .= '</tbody></table>';


          }

echo $admin_comentarios;
echo '<div class="d-none d-sm-none d-md-block">';
    pag($ini, $limit_end, $total);
echo "</div>";
echo '<div class="d-block d-sm-block d-md-none">';
pag_test($ini, $limit_end, $total);
echo "</div>";
}

function error_fatal($a){
    global $db, $nombrepag, $usua;
//echo $usua;
$query = "SELECT * FROM users WHERE username='$usua' LIMIT 1";
$rows =  mysqli_fetch_array(mysqli_query($db, $query));

$id_usuario = $rows['id'];


$ip = get_client_ip();

$query_error_fatal = "INSERT INTO error_fatal (id, id_usuario, ip, web, area) VALUES(null, '$id_usuario', '$ip', '$nombrepag', '$a')";

if (mysqli_query($db, $query_error_fatal)) {
  echo '<div class="alert alert-warning" role="alert">
  <h1>Se ha registrado esta accion y se ha generado un alerta, evite continuar con esta practica no permitida.</h1>
</div>';
} else {
  echo 'error '. mysqli_error($db);
 }
}


function isLoggedIn()
{
    return isset($_SESSION['user']);
}

function isAdmin()
{
    return isset($_SESSION['user']) && $_SESSION['user']['admin'] == 1;
}

function isSuperUser()
{
    return isset($_SESSION['user']) && $_SESSION['user']['super_user'] == 1;
}

function isDocente()
{
    return isset($_SESSION['user']) && $_SESSION['user']['docente'] == 1;
}

function isEstudiante()
{
    return isset($_SESSION['user']) && $_SESSION['user']['estudiante'] == 1;
}

function isUser()
{
    return isset($_SESSION['user']) && $_SESSION['user']['usuario'] == 1;
}




// Función genérica para verificar múltiples roles
function hasRole($roles) {
    if (!isset($_SESSION['user'])) {
        return false;
    }
    
    $user = $_SESSION['user'];
    $userRoles = [
        'usuario' => $user['usuario'] ?? 0,
        'estudiante' => $user['estudiante'] ?? 0,
        'docente' => $user['docente'] ?? 0,
        'admin' => $user['admin'] ?? 0,
        'super_user' => $user['super_user'] ?? 0
    ];
    
    if (is_array($roles)) {
        foreach ($roles as $role) {
            if (isset($userRoles[$role]) && $userRoles[$role] == 1) {
                return true;
            }
        }
        return false;
    } else {
        return isset($userRoles[$roles]) && $userRoles[$roles] == 1;
    }
}

function getAvailableProfiles() {
    if (!isset($_SESSION['user'])) {
        return [];
    }
    
    $user = $_SESSION['user'];
    $profiles = [];
    
    if ($user['usuario'] == 1) $profiles[] = 'director_de_carrera';
    if ($user['estudiante'] == 1) $profiles[] = 'estudiante';
    if ($user['docente'] == 1) $profiles[] = 'docente';
    if ($user['admin'] == 1) $profiles[] = 'admin';
    if ($user['super_user'] == 1) $profiles[] = 'super_user';
    
    return $profiles;
}




function requireAdmin() {
  if (!isLoggedIn()) {
      $_SESSION['msg'] = "Debe iniciar sesión primero";
      header('location: ../login.php');
      exit;
  }
  
  if (!isset($_SESSION['current_profile'])) {
      header('location: ../profile_selector.php');
      exit;
  }
  
  if ($_SESSION['current_profile'] !== 'admin' || !isAdmin()) {
      $_SESSION['error'] = "Acceso denegado: Se requieren privilegios de administrador";
      header('location: ../' . $_SESSION['current_profile'] . '/home.php');
      exit;
  }
}



function requireSuperUser() {
  if (!isLoggedIn()) {
      $_SESSION['msg'] = "Debe iniciar sesión primero";
      header('location: ../login.php');
      exit;
  }
  
  if (!isset($_SESSION['current_profile'])) {
      header('location: ../profile_selector.php');
      exit;
  }
  
  if (($_SESSION['current_profile'] !== 'super_user' && $_SESSION['current_profile'] !== 'admin') || !isSuperUser()) {
      $_SESSION['error'] = "Acceso denegado: Se requieren privilegios de superusuario";
      // Redirige al home correspondiente o al dashboard principal
      $redirect = isset($_SESSION['current_profile']) ? '../' . $_SESSION['current_profile'] . '/home.php' : '../index.php';
      header('location: ' . $redirect);
      exit;
  }
}

// Función para verificar acceso de docente
function requireDocente() {
  if (!isLoggedIn()) {
      $_SESSION['msg'] = "Debe iniciar sesión primero";
      header('location: ../login.php');
      exit;
  }

  if (!isset($_SESSION['current_profile'])) {
      header('location: ../profile_selector.php');
      exit;
  }

  if ($_SESSION['current_profile'] !== 'docente' || !isDocente()) {
      $_SESSION['error'] = "Acceso denegado: Se requieren privilegios de docente";
      header('location: ../' . $_SESSION['current_profile'] . '/home.php');
      exit;
  }
}

// Función para verificar acceso de estudiante
function requireEstudiante() {
  if (!isLoggedIn()) {
      $_SESSION['msg'] = "Debe iniciar sesión primero";
      header('location: ../login.php');
      exit;
  }

  if (!isset($_SESSION['current_profile'])) {
      header('location: ../profile_selector.php');
      exit;
  }

  if ($_SESSION['current_profile'] !== 'estudiante' || !isEstudiante()) {
      $_SESSION['error'] = "Acceso denegado: Se requieren privilegios de estudiante";
      header('location: ../' . $_SESSION['current_profile'] . '/home.php');
      exit;
  }
}



function verifyProfileAccess() {
  if (!isLoggedIn()) {
      header('location: ../login.php');
      exit;
  }

  $current_folder = basename(dirname($_SERVER['SCRIPT_FILENAME']));
  $available_profiles = $_SESSION['user']['available_profiles'] ?? [];

  if (!isset($_SESSION['current_profile'])) {
      if (count($available_profiles) === 1) {
          $_SESSION['current_profile'] = $available_profiles[0];
      } else {
          header('location: ../profile_selector.php');
          exit;
      }
  }

  if ($_SESSION['current_profile'] !== $current_folder) {
      header('location: ../' . $_SESSION['current_profile'] . '/home.php');
      exit;
  }

  // Verificación con funciones específicas
  $profile_function = 'is' . ucfirst($_SESSION['current_profile']);
  if (function_exists($profile_function) && !$profile_function()) {
      $_SESSION['error'] = "Privilegios insuficientes";
      header('location: ../profile_selector.php');
      exit;
  }
}

















function isActive(){
    global $db, $usua;

    $query = "SELECT * FROM users WHERE username = '$usua'";
	  $result = mysqli_query($db, $query);
    $rows =  mysqli_fetch_assoc($result);

	if ($rows['status']==1) {
		return true;
	}else{
		return false;
	}
}

// escape string
function e($val){
	global $db;
	return mysqli_real_escape_string($db, trim($val));
}

function display_error() {
	global $errors;

	if (count($errors) > 0){
		echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
			foreach ($errors as $error){
				echo $error;
				echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			  </button>';
			}
		echo '</div>';
	}
}



function analizar_mensualidades(){
  global $db, $usua, $mes_de_pago_actual, $limite_basico,
  $limite_avanzado, $limite_vip, $titulopag, $planes, $operador, $como_pagar, $fecha_actual_sistema, $concepto, $m_dias_r, $como_pagar, $me, $img_ope, $fecha_sistema;

  $montos_permitidos ="";
  $limite_base = 0;


    $inicio = new DateTime();
    $fin = new DateTime();
    $fin = $fin->modify('last day of this month');

    $hoy_a = date('d/m/Y');
    $fin_a = $fin->format('d/m/Y');

    $interval = $inicio->diff($fin);
    $interval = $interval->days .' Dias';

    $query = "SELECT * FROM pagos WHERE user='$usua' AND status_pago ='APROBADO' AND DATEDIFF(fin, NOW()) > 0";
    $resultado = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($resultado);

    if (mysqli_num_rows($resultado)>0) {

if (mysqli_num_rows($resultado)==1){
$tituloA = '<h3>Usted actualmente posee activo un solo plan:</h3>';
} else {
$tituloA = '<h3>Usted actualmente posee activo los siguientes planes:</h3>';
}
echo $tituloA;


} // Cierre de if (mysqli_num_rows($resultado)>0)
else {
  echo '<hr>';
  echo '<div class="alert alert-warning" role="alert">En el Periodo <b>'.strtoupper($mes_de_pago_actual).'</b> No posee Ningun Plan Activado</div>';
  echo '<hr>';
}

$query2 = "SELECT *, DATEDIFF(fin, NOW()) as DiasRestantes FROM pagos WHERE user = '$usua' AND status_pago = 'APROBADO' ORDER BY id DESC LIMIT 4";

$resul = mysqli_query($db, $query2);

while ($a = mysqli_fetch_assoc($resul)) {

  if ($a['DiasRestantes']>0) {
    $concepto = $a['concepto'];
    img_ope($concepto);
    $operador = ucwords(strtolower(str_replace("MENS_", "", $concepto)));
    echo '<hr>';
    echo '<div class="container">
  <div class="row">';
    echo '<div class="col-2">';
    echo $img_ope;
    echo '</div>';
    if ($a['DiasRestantes']==1) {
      $t_dias = "Dia restante";
    }
    else {
      $t_dias = "Dias restantes";
    }
    echo '<div class="col-10">';
    echo ' De la platafoma <b>'. $operador .'</b> le quedan <b>'.$a['DiasRestantes'].'</b> '.$t_dias.' para disfrutar de su plan de Recargas. <b><a href="recargas_'.strtolower($operador).'.php">'.strtoupper($operador).'
    </a></b>';
    echo '</div>';
    echo '</div></div>';
    echo '<hr>';

$date = date_create($a['fin']);
$fin_a = date_format($date, 'd/m/Y');
$actual = $a['mes_de_pago'];

    echo '<div class="alert alert-success" role="alert">
   <h3>SOBRE SU PLAN '.strtoupper($operador).' CONTRATADO</h3>En el periodo correspondiente al mes de <b>'.strtoupper($actual).' </b> su Plan Vence el dia <b>'. $fin_a .'</b>. y puede acceder cuando guste y hacer uso del servicio de recargas <a data-toggle="popover" data-content="Aca podrá ingresar directamente al area de recargas '.strtoupper($operador).'." href="recargas_'.strtolower($operador).'.php"><b>AQUI</b></a> </div><hr>';

    //echo $concepto;
  }
}

}


function a_favor(){
  global $db, $monto_favor, $mens_monto_favor;

$user_id = $_SESSION['user']['id'];
//echo $user_id ;
//$sql = "SELECT monto_a_favor FROM `users` WHERE id = $user_id AND disp_a_favor = 1";

$sql = "SELECT SUM(monto) AS 'monto_a_favor' FROM billetera WHERE id_usuario = '$user_id' AND  status = '1'";

$row = mysqli_fetch_assoc(mysqli_query($db, $sql));
$montoafavor = $row['monto_a_favor'];

if ($montoafavor>0) {
  $monto_favor = $GLOBALS['monto_a_favor'] = $montoafavor;
  $mens_monto_favor = '<div class="alert alert-danger" role="alert">Usted posee un saldo a favor de <b>' .
      number_format($monto_favor, 2, ',', '.') . '</b>
       Bs.</div><p>Este saldo sera utilizado de forma automatica para recalcular el monto que usted debe pagar en esta operacion.</p>';
}
else if ($montoafavor<0) {
  $monto_favor = $GLOBALS['monto_a_favor'] = $montoafavor;
  $mens_monto_favor = '<div class="alert alert-danger" role="alert">Usted posee una deuda de <b>'.number_format(abs($monto_favor), 2, ',', '.').' Bs.</b></div>';
} else {
  $monto_favor = $GLOBALS['monto_a_favor'] = $montoafavor;
  $mens_monto_favor = "<h4>Su saldo es de 0,00 Bs.</h4>";
  //$mens_monto_favor = '';
}


}




function conteo(){
global $db, $fecha_act_lectura, $fads, $titulo;

$verf = "SELECT id FROM users";
$result = mysqli_query($db, $verf);
$rows =  mysqli_num_rows($result);

$variable_interno = 0;
$suma=$rows+$variable_interno;

$boton = '';

if ($titulo == "Registro en el Sistema") {
  $boton = '';
} else {
$boton = '  <span class="d-inline-block" data-toggle="popover" data-content="Si aun no posee credenciales de acceso puede solicitarlas aqui.">
  <a id="afiliarse" class="btn btn-success" href="registro.php">
   <i class="fas fa-key"></i> Afiliarse de forma gratuita al Servicio Aqui</a>
  </span>';
}

//$fecha = date_format($fecha_act, 'd-m-Y');
echo '<div class="p-3 mb-2 bg-danger text-white text-center">';
echo '<i class="fas fa-users fa-10x"></i>';
echo '<h3>Hoy es: ' . $fads . '</h3><br>';
echo '<h1>Y hay registrados: ' . $suma . ' Usuarios.</h1>';

echo $boton;
echo '</div><hr>';

}


$variable_informacion_cuenta = 0;


if ($variable_informacion_cuenta == 1) {
  contenido('bancario');
  $informacion_cuentas = $contenido;

}
else {
$informacion_cuentas = '';
}







// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************


function contenido($s){
  global $db, $contenido;
  $sql = "SELECT * FROM contenido WHERE seccion = '$s' " ;
  $resultado = mysqli_query($db, $sql) or mysqli_error($db);
  $row = mysqli_fetch_assoc($resultado);
  $rows = mysqli_num_rows($resultado);
  if (!$rows || strlen($row['contenido'])=='11'){
    $contenido = '';
    $contenido2 = '';
   }
    else {
  
  $id_contenido = $row['id'];
  $contenido = $row['contenido'];
  $contenido2 = '<a class="btn btn-secondary" title="Editar" target="_blank" href="https://virtual.jesuministrosymas.com.ve/u/admin/editar_contenido.php?id='.$id_contenido.'">Editar</a>';
    //echo $contenido;
  
   }
  
  if (IsAdmin()) {
    echo $contenido . $contenido2;
  }
  else {
    echo $contenido;
  }
  
  
  }

function activar_automatica_mes($a,$b,$c){
  //$a mes_de_pago_actual
  //$b Operador en Mayuscula
  //$c ID de Usuario para activarle MENS_

  global $db, $mes_de_pago_actual, $id_usua;

//echo $mes_de_pago_actual;
//echo "<br>";
$usuaci = $c; //$_SESSION['user']['idusuario'];
$concepto = $afiliacion = "MENS_".$b;

$verif1= "SELECT * FROM `pagos` WHERE user = '$usuaci' AND mes_de_pago = '$a' AND concepto = '$concepto' ORDER by id DESC LIMIT 1";
$result = mysqli_query($db, $verif1);

if($result){
   if(mysqli_num_rows($result) > 0) {
     echo '<div class="alert alert-info alert-dismissible fade show" role="primary">
      SE HA ACTUALIZADO SU PERFIL DE FORMA CORRECTA, Ahora ya puedes usar el sistema '.strtoupper($b).'
      <br>Durante todo el periodo  <b>'.strtoupper($mes_de_pago_actual).'
      </b> Ahora podras ir a cualquier seccion de este sitio. <br> Para agregar saldo a su billetera puede acceder al area de <a href="billetera.php" class="badge badge-success" data-toggle="popover" title="Recargar Billetera" data-content="Aca podra recargar su Billetera." ><i class="fas fa-wallet"></i> BILLETERA</a><button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button></div>';
   }
   else {
     if ($mes_de_pago_actual == ($a)) {

   $usua = $_SESSION['user']['idusuario'];
   $monto_mensualidad = $monto_favor = 0;
   $concepto = $afiliacion = "MENS_".$b;
   $mes_de_pago_actual = $mes_de_pago_actual;
   $banco_emisor = $banco_destino = $ci_nro_cuenta = 'Interno';
   $nro_transf = 'ACT_'.generar_cadena(40);
   $fecha_transf = $fecha_pago = $fecha_aprobacion = $fecha_inicio = date("Y-m-d H:i:s");
   $fecha_fin = date("Y-m-d H:i:s",strtotime($fecha_inicio."+ 1 month"));
   $status_pago ="APROBADO";

 $query = "INSERT INTO
 pagos (id, user, monto, a_favor, concepto, mes_de_pago, afiliacion, banco_origen, banco_destino, nro_transf, ci_nro_cuenta, fecha_transf, fecha_pago, status_pago, fecha_aprobacion, inicio, fin)
 VALUES (null, '$usua', '$monto_mensualidad', '$monto_favor', '$concepto', '$mes_de_pago_actual', '$afiliacion', '$banco_emisor', '$banco_destino', '$nro_transf', '$ci_nro_cuenta', '$fecha_transf', '$fecha_pago', '$status_pago', '$fecha_aprobacion', '$fecha_inicio', '$fecha_fin')";



     if (mysqli_query($db, $query)){


       echo '<div class="alert alert-warning alert-dismissible fade show" role="primary">
       Genial ya puedes usar el sistema '.strtoupper($b).'
       <br>Durante todo el periodo  <b>'.strtoupper($mes_de_pago_actual).'
       </b> Ahora podras ir a cualquier seccion del sitio. <br> Para agregar saldo a su billetera puede acceder al area de <a href="billetera.php" class="badge badge-success" data-toggle="popover" title="Recargar Billetera" data-content="Aca podra recargar su Billetera." ><i class="fas fa-wallet"></i> BILLETERA</a><button type="button" class="close" data-dismiss="alert" aria-label="Close">
         <span aria-hidden="true">&times;</span>
       </button></div>';

 } else {

     echo '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i>Algo ha ocurrido, intente actualizar esta web nuevamente. Si el error persiste comuniquese de manera inmediatamente con el administrador y reporte el siguiente Error: ' . mysqli_error($db).'</div>';
         }
   } else {
     echo '<div class="alert alert-warning" role="alert">
<i class="fas fa-newspaper"></i> En <b>'.strtoupper($a).'</b> de forma automatica podras usar el sistema en este periodo de prueba!
</div>';
   }
}
}
}





// GENERAR PAGO DE BILLETERA
function generar_pago_billetera(){
  global $db, $mes_de_pago_actual, $logo, $monto_favor;

  // Datos recibidos del Formulario
  $monto           = e($_REQUEST['monto']);
  $concepto        = e($_REQUEST['concepto']);
  $afiliacion      = $concepto;
  $banco_emisor    = e($_REQUEST['banco_emisor']);
  $banco_destino   = e($_REQUEST['banco_destino']);
  $nro_transf      = e($_REQUEST['nro_transf']);
  $ci_nro_cuenta   = e($_REQUEST['ci_nro_cuenta']);
  $fecha_transf    = e($_REQUEST['fecha_transf']);
  $operador        = e($_REQUEST['titulopag']);
  $usua            = e($_REQUEST['user']);
  $user_id            = e($_REQUEST['user_id']);

  $hoy = date('d/m/Y');

  a_favor();
  $monto_favor = $GLOBALS['monto_a_favor'];
  $status_pedido ="ESPERANDO";

  $numerocorto = substr($nro_transf, -6);
  $verf = "SELECT nro_transf FROM pagos WHERE  (nro_transf LIKE '%$numerocorto') AND STR_TO_DATE(fecha_transf,'%Y-%m-%d %T')
  BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND NOW()";
  $result = mysqli_query($db, $verf);
  $rows =  mysqli_num_rows($result);

  $verf2 = "SELECT nro_transf FROM pedidos WHERE  (nro_transf LIKE '%$numerocorto') AND STR_TO_DATE(fecha_transf,'%Y-%m-%d %T')
  BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND NOW()";
  $result2 = mysqli_query($db, $verf2);
  $rows2 =  mysqli_num_rows($result2);

  $sumarows = $rows + $rows2;

  if ($sumarows>0){
    $_SESSION['billetera_virtual'] = '<i class="fa fa-exclamation-triangle fa-fw"></i> Lo sentimos, el numero de transferencia que intenta utilizar ya fue utilizado, recuerde que no debe utilizar un numero de transferencia usado en alguna otra operacion de declaracion de mensualidades u otros pagos de pedidos, evite ser suspendido/a.<br>';
  } else {

    $query = "INSERT INTO
    pedidos (
      id,
      usuario,
      operador,
      monto,
      nro_transf,
      banco_emisor,
      banco_destino,
      fecha_transf,
      ci_nro_cuenta,
      status_pedido,
      fecha_pedido,
      sin_plan)
      VALUES (
        null,
        '$usua',
        '$operador',
        '$monto',
        '$nro_transf',
        '$banco_emisor',
        '$banco_destino',
        '$fecha_transf',
        '$ci_nro_cuenta',
        '$status_pedido',
        STR_TO_DATE('$hoy', '%d/%m/%Y'),
        '2')";

  if (mysqli_query($db, $query)) {
    $_SESSION['billetera_virtual']  = "Se ha Registrado su Pago de Manera exitosa.<br>";
    $id_pedido = mysqli_insert_id($db);
    $descripcion = 'INGRESO';
    $sql2 = "INSERT INTO billetera (id, id_usuario, monto, descripcion, id_descripcion, fecha, status) VALUES (null, '$user_id','$monto','$descripcion','$id_pedido',NOW(),0)";

    if (mysqli_query($db, $sql2)) {
      $_SESSION['billetera_virtual']  .= "Se ha generado un registro de actualizacion de dinero en su Billetera.<br>";
    } else {
      $_SESSION['billetera_virtual']  = 'Algo ha ocurrido Actualizando su billetera, Error: ' . mysqli_error($db);
    }
    $_SESSION['billetera_virtual'] .= "Se ha registrado su pago para recarga de billetera manera Exitosa.<br>";
    $monto = number_format($monto, 2, ',', '.');
    $email = $_SESSION['user']['email'];
    $nombre = $_SESSION['user']['nombre'];
    $asunto = "Dinero a Billetera";
    $cuerpo = "Hola $nombre: <br><br>Usted ha registrado un pago de manera exitosa por concepto de Recarga de Billetera Virtual<br> por un monto de $monto Bs. <br> desde el Banco $banco_emisor <br> Hacia nuestra cuenta en el $banco_destino <br>Numero Transferencia Bancaria: $nro_transf <br>De fecha $fecha_transf <br>";
    enviarEmail($email, $nombre, $asunto, $cuerpo);
    $_SESSION['billetera_virtual'] .='<i class="fa fa-envelope"></i> Hemos enviado Un correo con el resumen de su pago';
  } else {
    $_SESSION['billetera_virtual']  = 'Algo ha ocurrido registrando el pedido de actualizacion de su billetera, Error: ' . mysqli_error($db);
  }
  }
}



// return user array from their id
function getUserById($id){
  global $db;
  $query = "SELECT * FROM users WHERE id=" . $id;
  $result = mysqli_query($db, $query);
  $user = mysqli_fetch_assoc($result);
  return $user;
}




// LISTAR BANCO EMISOR
function banco_emisor(){
  global $db;
  $query = "SELECT * FROM banco_emisor ORDER BY banco_emisor";
  $results = mysqli_query($db, $query);
  while ($valores = mysqli_fetch_array($results)) {
    echo '<option value="'.$valores['banco_emisor'].'">'.$valores['banco_emisor'].'</option>';
  }
}

// LISTAR SECCIONES
function seccion(){
  global $db;
  $query = "SELECT * FROM seccion ORDER BY seccion";
  $results = mysqli_query($db, $query);
  while ($valores = mysqli_fetch_array($results)) {
    echo '<option value="'.$valores['seccion'].'">'.$valores['seccion'].'</option>';
  }
}


// LISTAR BANCO DESTINO
function banco_destino(){
  global $db;
  $query = "SELECT * FROM banco_destino";
  $results = mysqli_query($db, $query);
  while ($valores = mysqli_fetch_array($results)) {
    echo '<option value="'.$valores['banco_destino'].'">'.$valores['banco_destino'].'</option>';
  }
}

// LISTAR TIPO DE USUARIO
function user_type(){
  global $db;
  $query = "SELECT * FROM user_types";
  $results = mysqli_query($db, $query);
  while ($valores = mysqli_fetch_array($results)) {
    echo '<option value="'.$valores['user_type'].'">'.$valores['descripcion'].'</option>';
  }
}

// GENERA NUMERO ALEATORIO
function generateRandomString($A) {
  return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $A);
}

// GENERA CADENA ALFANUMERICA
function generar_cadena($A) {
  $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $input_length = strlen($permitted_chars);
  $random_string = '';
  for($i = 0; $i < $A; $i++) {
    $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
    $random_string .= $random_character;
  }

  return $random_string;
}



// CREAR PASSWORD
function crear_password(){
  global $db, $error;

  $password_1     = e($_POST['password_1']);
  $password_2     = e($_POST['password_2']);
  $idusuario      = e($_POST['idusuario']);
  $email          = e($_POST['email']);
  $control        = e($_POST['control']);
  $nombre        = e($_POST['nombre']);
  $username        = e($_POST['username']);

  if ($password_1 != $password_2) {
    array_push($error, "Las dos contraseñas no coinciden");
  } else {
    if (count($error) == 0) {
      $alea = generateRandomString(10);
      $password = md5($password_1);

      $sql = "UPDATE users SET
      password = '$password', control = '$alea'
      WHERE id = '$idusuario' AND email = '$email'";
    }


    if (mysqli_query($db, $sql)) {
      $_SESSION['msg']  = "Se ha creado Su contraseña de acceso de manera correcta, ahora puedes iniciar sesion..!!<br>";

      $email = $email;
      $nombre = $nombre;
      //CORREO CREACION DE CLAVE
      $asunto = "Creacion de Clave Exitoso Sistema Gestion de Recargas";
      $cuerpo = 'Hola Usuario '.$nombre.' <br><br>Usted ha creado su contraseña de manera exitosa.<br><p style="text-align: justify;"><br>Podra ingresar utilizando como usuario sus credenciales de acceso, puede utilizar su correo electronico o su numero de usuario</p> <p style="text-align: justify;"><strong>CREDENCIALES DE ACCESO:</strong></p><p style="text-align: center;"><br>  <span style="background-color: #70FF70; color: #000000; display: inline-block; padding: 3px 10px; font-weight: bold; border-radius: 5px;">Correo Registrado: <b>'.$email.'</b><br>Su Usuario es: <b>'.$username.'<b><br>Su clave de acceso es: <b>'.$password_1.'</b></span></p><br><br> Recomendamos que no borre este correo y copie sus datos de acceso en un lugar seguro.<br> <br> <br><b>PREGUNTAS FRECUENTES</b><p></p><p><b>¿Cuales son los montos de inversión?</b></p><p></p><ul><li>Primero usted debe pagar la mensualidad por uso de la plataforma segun la plataforma que usted desee utilizar. <a href="https://virtual.jesuministrosymas.com.ve/u/usuario/mensualidades.php"> <b>MENSUALIDADES</b></a></li><li>Luego generar sus respectivas solicitudes de recargas segun la operadora previamente seleccionada.</li></ul><PREGUNTAS FRECUENTES</P> <p><b>¿A que cuenta debo efectuar mi pago?</b></p><p>Usted debe hacer su pago a cualquiera de nuestras cuentas indicadas en <b><a href="http://www.jesuministrosymas.com.ve/pagos#TOC-PAGOS-BANCARIOS-EN-VENEZUELA"> FORMAS DE PAGO AQUI</a>.</b></p>';

      enviarEmail($email, $nombre, $asunto, $cuerpo);
      $_SESSION['msg']  .='<i class="fa fa-envelope"></i> Le Hemos enviado un Correo notificandole sobre esta accion..<br>';
      header('location: login.php');

    } else {
      echo "Error updating record: " . mysqli_error($db);
      mysqli_close($db);
    }
  }

}

// LOGIN USER
function login(){
    global $db, $username, $errors;
    $username = e($_POST['username']);
    $password = e($_POST['password']);
    
    if (empty($username)) {
        array_push($errors, "Su Numero de Usuario o Correo Electronico es Requerido<br>");
    }
    if (empty($password)) {
        array_push($errors, "Su Contraseña de Acceso es Requerida<br>");
    }
    
    if (count($errors) == 0) {
        // Buscar usuario sin aplicar hash a la contraseña aún
        $query = "SELECT * FROM users WHERE (username='$username' OR email='$username') LIMIT 1";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) { // user found
            $logged_in_user = mysqli_fetch_assoc($results);
            
            // Verificar contraseña usando password_verify()
            if (password_verify($password, $logged_in_user['password'])) {
                // Contraseña correcta - iniciar sesión
                $_SESSION['user'] = $logged_in_user;
                $_SESSION['success'] = "Bienvenido/a " . $logged_in_user['username'];
                
                // **CARGAR TODOS LOS PERMISOS ACTUALIZADOS**
                cargarPermisosUsuario();
                
                // REGISTRAR EN AUDITORÍA - LOGIN EXITOSO
                registrarAuditoria(
                    "LOGIN", 
                    "users", 
                    $logged_in_user['id'], 
                    null, 
                    ['username' => $username], 
                    "Autenticación", 
                    "Inicio de sesión exitoso"
                );
                
                // Determinar los perfiles disponibles
                $available_profiles = [];
                
                // Verificar cada perfil usando tus funciones existentes
                if (isAdmin()) $available_profiles[] = 'admin';
                if (isDocente()) $available_profiles[] = 'docente';
                if (isEstudiante()) $available_profiles[] = 'estudiante';
                if (isUser()) $available_profiles[] = 'user';
                
                // Guardar perfiles disponibles en sesión
                $_SESSION['user']['available_profiles'] = $available_profiles;
                
                // Si solo tiene un perfil, redirigir directamente
                if (count($available_profiles) == 1) {
                    $_SESSION['current_profile'] = $available_profiles[0];
                    $where = $_SESSION['here'] ?? $available_profiles[0] . '/home.php';
                    header("Location: $where");
                } else {
                    // Mostrar selector de perfiles
                    header('Location: profile_selector.php');
                }
                
                exit();
            } else {
                // Contraseña incorrecta
                // REGISTRAR EN AUDITORÍA - LOGIN FALLIDO
                registrarAuditoria(
                    "LOGIN", 
                    "users", 
                    null, 
                    null, 
                    ['username' => $username], 
                    "Autenticación", 
                    "Intento de inicio de sesión fallido - Contraseña incorrecta"
                );
                
                array_push($errors, "Usuario/Correo o contraseña incorrectos");
            }
        } else {
            // Usuario no encontrado
            // REGISTRAR EN AUDITORÍA - LOGIN FALLIDO
            registrarAuditoria(
                "LOGIN", 
                "users", 
                null, 
                null, 
                ['username' => $username], 
                "Autenticación", 
                "Intento de inicio de sesión fallido - Usuario no encontrado"
            );
            
            array_push($errors, "Usuario/Correo o contraseña incorrectos");
        }
    }
}

function visita() {
  global $pool, $nombrepag, $usua, $stmt_visita;
  try {
      // Obtener una conexión del pool
      $db = $pool->getConnection();
      // Preparar la consulta para seleccionar el usuario
      $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
      $stmt = $db->prepare($query);
      $stmt->bind_param("s", $usua);
      $stmt->execute();
      $results = $stmt->get_result();
      if ($results !== null && $results->num_rows > 0) {
          $logged_in_user = $results->fetch_assoc();
          $id_usuario = $logged_in_user['id'];
          $ip = get_client_ip();
          // Preparar la consulta para insertar la visita
          $query_visita = "INSERT INTO visitas (id, id_usuario, ip, fecha_visita, web) VALUES (null, ?, ?, NOW(), ?)";
          $stmt_visita = $db->prepare($query_visita);
          $stmt_visita->bind_param("iss", $id_usuario, $ip, $nombrepag);
          $stmt_visita->execute();
          if ($stmt_visita->error) {
              // Manejo del error
              echo 'Error al insertar la visita: ' . $stmt_visita->error;
          }
      } else {
          // Manejo del error
          echo 'Error: no se encontró ningún usuario registrado con el nombre de usuario actual.';
      }
      // Cerrar los statement y liberar la conexión
      if ($stmt !== null) {
          $stmt->close();
      }
      if ($stmt_visita !== null) {
          $stmt_visita->close();
      }
      $pool->releaseConnection($db);
  } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
  }
}




function mostrar_mensajes(){
  global $db, $limit_end, $usua;

  $url = basename($_SERVER ["PHP_SELF"]);

  if (isset($_GET['p']))
    $ini=$_GET['p'];
  else
    $ini=1;
  $init = ($ini-1) * $limit_end;

  // SI ES ADMIN
  if (isAdmin()) {
    $count_mensajeria="SELECT COUNT(*) FROM mensajes";
    $query_mensajeria = "SELECT * FROM mensajes ORDER BY id DESC LIMIT $init, $limit_end";
    $result_mensajeria = mysqli_query($db, $query_mensajeria);
    $row_mensajeria =  mysqli_num_rows($result_mensajeria);
    $mensaje  = 'No hay mensajes que Mostrar';

  } else {
    //Si es Usuario
    $count_mensajeria="SELECT COUNT(*) FROM mensajes WHERE destinatario IN ('GENERAL','$usua') OR origen = '$usua'";
    $query_mensajeria = "SELECT * FROM mensajes WHERE destinatario IN ('GENERAL','$usua') OR origen = '$usua' ORDER BY id DESC LIMIT $init, $limit_end";
    $result_mensajeria = mysqli_query($db, $query_mensajeria);
    $row_mensajeria =  mysqli_num_rows($result_mensajeria);

    $mensaje  = '<i class="fa fa-exclamation-triangle"></i> ESTAMOS MEJORANDO ESTE MODULO';
  }

  /* querys */

  if (!$row_mensajeria){
    echo '<div class="alert alert-danger" role="alert" >';
    echo '<h3>';
    echo $mensaje;
    echo '</h3>';
    echo '</div>';
  } else {
    $num = $db->query($count_mensajeria);
    $x = $num->fetch_array();
    $total = ceil($x[0]/$limit_end);
    pag_test($ini, $limit_end, $total);
    if (isAdmin()){
      echo '<div class="table-responsive"><table id="tabla1" class="table table-bordered table-hover">
      <thead>
      <tr>
      <th>ID / Fecha de Mensaje / Asunto / Para</th>
      <th>Contenido</th>
      <th>Accion</th>
      </tr>
      </thead>
      <tbody>';

      $c = $db->query($query_mensajeria);
      while($row_mensajeria = $c->fetch_array(MYSQLI_ASSOC))
      {
        $date = date_create($row_mensajeria['fecha_mensaje']);
        $fecha = date_format($date, 'd-m-Y');
        $fecha_mensaje = $fecha;
        $asunto = $row_mensajeria['asunto'];
        $rowid = $row_mensajeria['id'];
        $contenido = $row_mensajeria['contenido'];
        $rowid = $row_mensajeria['id'];
        $origen = $row_mensajeria['origen'];
        $destinatario = $row_mensajeria['destinatario'];

        $boton_editar = '<a class="btn btn-outline-dark btn-sm" href="editar_mensajeria.php?id='.$rowid.'" data-toggle="popover" title="EDITAR CONTENIDO" data-content="Editar este contenido.">
        Editar
        </a>';

      $accion = '<div class="btn-group" >'. $boton_editar. '</div>';

      $consultar_nombre = "SELECT nombre FROM users WHERE id = '$origen'";
      $resultado_consultar_nombre=mysqli_query($db,$consultar_nombre);
      $rcn = mysqli_fetch_assoc($resultado_consultar_nombre);


      echo '<tr>';
      echo '<td><b>'.$rowid.'</b><br>'.$fecha_mensaje.'<br>'.$asunto.'<br><b>'.$destinatario.'</b></td>
      <td>'.$contenido .'</td>
      <td>'.$accion.'</td>
      </tr>';
      }
      echo '</tbody></table></div>';


    }
    else
      // SI ES USER NO ES ADMIN
    {
      echo '<div class="accordion" id="accordionExample">';

      $c = $db->query($query_mensajeria);
      while($row_mensajeria = $c->fetch_array(MYSQLI_ASSOC))
      {
        $date = date_create($row_mensajeria['fecha_mensaje']);
        $fecha = date_format($date, 'd-m-Y');
        $fecha_mensaje = $fecha;
        $asunto = $row_mensajeria['asunto'];
        $contenido = $row_mensajeria['contenido'];
        $rowid = $row_mensajeria['id'];
        $origen = $row_mensajeria['origen'];
        $destinatario = $row_mensajeria['destinatario'];
        $control = $row_mensajeria['control'];

        if ($destinatario == 'GENERAL') {
          $destino = '<span class="justify-content-end badge badge-pill badge-info">Mensaje General</span></div>';
        } else if ($destinatario == 'JESUMINISTROSYMAS' && $control == '0') {
          $destino = '<span class="justify-content-end badge badge-pill badge-danger">Solicitud de Soporte</span></div>';
        } else if ($destinatario == 'JESUMINISTROSYMAS' && $control == '1') {
          $destino = '<span class="justify-content-end badge badge-pill badge-success">Soporte Atendido</span></div>';
        } else {
          $destino = '<span class="justify-content-end badge badge-pill badge-warning">Mensaje Para Usted</span></div>';
        }

        $a = '
        <div class="card">
        <div class="card-header" id="headingOne'.$rowid.'">
        <h5 class="row mb-0">
        <button  title="Ver detalles de '.$asunto.'" class="btn btn-link collapsed col-12" type="button" data-toggle="collapse" data-target="#collapseOne'.$rowid.'" aria-expanded="true" aria-controls="collapseOne'.$rowid.'">

        <div class="row no-gutters">
        <div class="d-flex justify-content-start col-sm-8">'.$asunto.'</div>
        <div class="d-flex justify-content-end col-sm-4">'.$destino.'
        </div>

        </button>
        </h5>
        </div>

        <div id="collapseOne'.$rowid.'" class="collapse" aria-labelledby="headingOne'.$rowid.'" data-parent="#accordionExample">
        <div class="card-body">
        Publicado en Fecha: '.$fecha_mensaje.'<br><h2>'.$asunto.'</h2>'.$contenido.'
        </div>
        </div>
        </div>

        ';
echo $a;
      }
      echo '</div>';

    }

    pag_test($ini, $limit_end, $total);
  }

}

// CONTAR MENSAJES
function contar_mensajes(){
  global $db, $usua, $contador_msn, $contador_msn_badge;

  $id_usuario = $_SESSION['user']['id'];
  $web = "mensajeria.php";

  $cont_visita = "SELECT * FROM visitas
  WHERE fecha_visita =  (
    SELECT MAX(fecha_visita)
    FROM visitas WHERE web = '$web' AND id_usuario = '$id_usuario')  ";
  $result_visita = mysqli_query($db, $cont_visita);
  $row_visita =  mysqli_fetch_assoc($result_visita);

  $fecha_visita = $row_visita['fecha_visita'];
  $cont_msn = "SELECT * FROM mensajes
  WHERE (destinatario IN ('GENERAL','$usua') OR origen = '$usua')
  AND fecha_mensaje > '$fecha_visita'";

  $resultcont = mysqli_query($db, $cont_msn);
  $rowcont =  mysqli_num_rows($resultcont);


  if ($rowcont >= 1) {
    $contador_msn_badge = $rowcont;
    $contador_msn = 'Tiene '.$rowcont .' mensaje por leer!';
  }
  else {
    $contador_msn_badge = "";
    $contador_msn = "No hay Mensajes Nuevos";
  }

  $contador_msn .= '<br>Ahora puedes enviarnos mensajes que seran atendidos a la brevedad.';

}

// MOSTRAR ERROR
function display_error2() {
  global $error;

  if (count($error) > 0){
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
    foreach ($error as $error){
      echo $error;
      echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>';
    }
    echo '</div>';
  }
}

// VERIFICAR STATUS DE USUARIO
function status_usuario(){
  global $db, $usua, $operador, $nombrepag;
  $sql ="SELECT * FROM users WHERE username = '$usua' ";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_assoc($result);

  $motivo= $row['motivo_bloqueo'];
  $status = $row['status'];

  if (!$motivo){
    $motivo = 'No se ha especificado un motivo en particular, si considera que es un error usted puede comunicarse con el <a href="http://www.jesuministrosymas.com.ve/contactenos" target="_blank"> Area de Soporte J.E Suministros y Mas, C.A.</a>.';
  } else {
    $motivo= $row['motivo_bloqueo'];
  }

  if ( $status == 0){

    $ndp = 'mensualidad_'.strtolower($operador).'.php';

    if ($operador == "Mensualidades" || $nombrepag == $ndp ) {
      $complemento ='<hr>Active cualquier plan disponible para que pueda desbloquear su usuario de forma automatizada. <i class="far fa-arrow-alt-circle-down fa-2x"></i>
      ';
    } else {

      $complemento = '<hr>Tambien es posible desbloquear su usuario efectuando el pago de su mensualidad hoy mismo, puedes hacerlo ingresando a: <a href="mensualidades.php"><b> ACTIVAR ALGUN PLAN DISPONIBLE</b></a>';
    }

    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">

    <h2 class="text-center"><i class="fa fa-exclamation-triangle fa-fw fa-bars "></i>Usuario Bloqueado<i class="fa fa-exclamation-triangle fa-fw fa-bars "></i></h2> <h3>Motivo:</h3>' . $motivo . '<hr>Si considera que es un error o desea que sea reconsiderada su suspension puede comunicarse a los canales de comunicacion explicando su caso <a target="_BLANK" href="mensajeria.php"><b> COMUNIQUESE CON NOSOTROS AQUI</b></a>'.$complemento.'</div>';
  }

}

// VERIFICAR TRANSFERENCIAS
function verificar_transferencias($a){
  global $db, $mensaje_verificacion;

  $verf = "SELECT nro_transf FROM pedidos WHERE (nro_transf LIKE '%$a') OR (nro_transf LIKE '%$a') AND STR_TO_DATE(fecha_transf,'%Y-%m-%d %T')
  BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND NOW()";

  $result = mysqli_query($db, $verf);
  $rows =  mysqli_num_rows($result);

  $verf2 = "SELECT nro_transf FROM pagos WHERE (nro_transf LIKE '%$a') OR (nro_transf LIKE '%$a') AND STR_TO_DATE(fecha_transf,'%Y-%m-%d %T')
  BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND NOW()";

  $result2 = mysqli_query($db, $verf2);
  $rows2 =  mysqli_num_rows($result2);

  $sumarows = $rows + $rows2;

  if ($sumarows>0){
    $mensaje_verificacion  = '<i class="fa fa-exclamation-triangle fa-fw"></i> Lo sentimos, el numero de transferencia que intenta utilizar ya fue utilizado, recuerde que no debe utilizar un numero de transferencia usado en alguna otra operacion de declaracion de mensualidades u otros pagos de pedidos, evite ser suspendido/a.<br>';
    mysqli_close($db);
  }
}

  
// $ini=1; VALOR SUMINISTRADO POR LA FUNCTION
// $limit_end FIN DE UN CICLO EN LA PAG
// $total CANTIDAD TOTAL DE REGISTROS
function pag($ini, $limit_end, $total)
{
  $url = basename($_SERVER["PHP_SELF"]);
  if (isset($_REQUEST['busqueda'])) {
    $busqueda = strtolower(e($_REQUEST['busqueda']));
    if (empty($busqueda)) {
      $busq = "";
    } else {
      $busq = '&busqueda=' . $busqueda;
    }
  } else {
    $busq = "";
    //unset($_REQUEST['busqueda']);
  }
  if (isset($_REQUEST['filtro'])) {
    $filtro = strtolower(e($_REQUEST['filtro']));

    if (empty($filtro)) {
      $filt = "";
    } else {
      $filt = '&filtro=' . $filtro;
    }
  } else {
    $filt = "";
    //unset($_REQUEST['busqueda']);
  }
  echo '<nav aria-label="Page navigation example">';
  echo '<ul class="pagination pagination-sm flex-sm-wrap">';
  /****************************************/
  if (($ini - 1) == 0) {
    echo "<li class='page-item disabled'><a class='page-link' href='$url?p=" . (1) . $busq . $filt . "'><b><i class='fa fa-angle-double-left'></i>  Principio</b></a></li>";
    echo "<li class='page-item disabled'><a class='page-link' href='#'><i class='fa fa-angle-double-left'></i>  Anterior</a></li>";
  } else {
    echo "<li class='page-item'><a class='page-link' href='$url?p=" . (1) . $busq . $filt . "'><b><i class='fa fa-angle-double-left'></i>  Principio</b></a></li>";
    echo "<li class='page-item'><a class='page-link' href='$url?p=" . ($ini - 1) . $busq . $filt . "'><b><i class='fa fa-angle-double-left'></i>  Anterior</b></a></li>";
  }
  /****************************************/
  for (
    $k = max(1, min($ini - 5, $total - 10));
    $k < max(min(11, $total + 1), min($ini + 5, $total + 1));
    $k++
  ) {
    if ($ini == $k) {
      echo "<li class='page-item active'><a class='page-link' href='$url?p=$k$busq$filt'>" . $k . "</a></li>";
    } else {
      echo "<li class='page-item'><a class='page-link' href='$url?p=$k$busq$filt'>" . $k . "</a></li>";
    }
  }
  /****************************************/
  if ($ini == $total) {
    echo "<li class='page-item disabled'><a class='page-link' href='#'>Siguiente <i class='fa fa-angle-double-right'></i> </a></li>";
    echo "<li class='page-item disabled'><a class='page-link' href='$url?p=" . ($total) . $busq . $filt . "'><b>Ultima <i class='fa fa-angle-double-right'></i></b></a></li>";
  } else {
    echo "<li class='page-item'><a class='page-link' href='$url?p=" . ($ini + 1) . $busq . $filt . "'><b>Siguiente <i class='fa fa-angle-double-right'></i></b></a></li>";
    echo "<li class='page-item'><a class='page-link' href='$url?p=" . ($total) . $busq . $filt . "'><b>Ultima <i class='fa fa-angle-double-right'></i></b></a></li>";
  }
  /*******************END*******************/
  echo "</ul>";
  // echo "</div>";
  echo '</nav>';
}


function formatearCantidad($cantidad, $tipo) {
  $cantidad_final = $cantidad;
  $unidad = "";

  if ($cantidad == 0) {
    return "Sin Existencia"; // O el mensaje que desees mostrar si no hay cantidad
  }

  if ($tipo == 'liq') {
    if ($cantidad >= 1000) {
      $cantidad_final = $cantidad / 1000; 
      $unidad = ($cantidad_final > 1) ? "Litros" : "Litro"; 
    } else {
      $unidad =  "mililitro" . ($cantidad > 1 ? "s" : "");
    }
  } else if ($tipo == 'sol') {
    if ($cantidad >= 1000) {
      $cantidad_final = $cantidad / 1000; 
      $unidad = ($cantidad_final > 1) ? "Kilos" : "Kilo";
    } else {
      $unidad =  "gramo" . ($cantidad > 1 ? "s" : ""); 
    }
  } else { //  Para otros tipos,  puedes manejarlo como desees
    $unidad = "";
  }

  $cantidad_final = round($cantidad_final, 2);

  return $cantidad_final . " " . $unidad;
}


// Función para obtener la cantidad total de materia prima
function totalMateriaPrima() {
  global $db, $id_usua;
  $sql = "SELECT 
          SUM(CASE WHEN descripcion = 1 THEN cantidad END) AS total 
          FROM inventario_componente 
          WHERE id_usuario = $id_usua";
  $result = $db->query($sql);
  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['total'];
  } else {
      return 0;
  }
}

// Función para obtener la cantidad de materia prima ingresada en un mes específico
function ingresoMateriaPrimaPorMes($mes, $año) {
  global $db, $id_usua;
  $sql = "SELECT 
  SUM(CASE WHEN descripcion = 1 THEN cantidad END) AS total 
          FROM inventario_componente 
          WHERE MONTH(fecha) = '$mes' AND YEAR(fecha) = '$año' AND id_usuario = '$id_usua'";
  $result = $db->query($sql);
  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['total'];
  } else {
      return 0;
  }
}

// Función para obtener la cantidad total de producto terminado
function totalProductoTerminado() {
  global $db, $id_usua;
  $sql = "SELECT 
    SUM(CASE WHEN descripcion = 1 THEN cantidad END) AS total  
FROM 
    inventario_producto_terminado 
WHERE 
    id_usuario = $id_usua";
  $result = $db->query($sql);
  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['total'];
  } else {
      return 0;
  }
}

// Función para obtener la cantidad de producto terminado producido en un mes específico
function productoTerminadoPorMes($mes, $año) {
  global $db, $id_usua;
  $sql = "SELECT 
 SUM(CASE WHEN descripcion = 1 THEN cantidad END) AS total 
          FROM inventario_producto_terminado 
          WHERE MONTH(fecha) = '$mes' AND YEAR(fecha) = '$año' AND id_usuario = '$id_usua'";
  $result = $db->query($sql);
  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['total'];
  } else {
      return 0;
  }
}

function kilo($a){
  if ($a>1000){
      $a = $a/1000;
      $a = number_format($a, 2, ',', '.') . ' Kilos';
  }
  else if ($a == 0){
      $a = "No hay Registros";
  }
  else {
      $a = number_format($a, 2, ',', '.') . ' Gramos';
  }
  return $a;
}

$formatter = IntlDateFormatter::create(
  'es_ES',
  IntlDateFormatter::NONE,
  IntlDateFormatter::NONE,
  'America/Santiago', // Ajusta la zona horaria si es necesario
  IntlDateFormatter::GREGORIAN,
  'MMMM' // Formato personalizado para mostrar solo el nombre completo del mes
);


function respaldarDatosUsuario($id_usua) {
  global $db, $pag_web;

  // 1. Crear la carpeta de respaldos si no existe:
  $carpetaRespaldos = 'respaldos/' . $id_usua . '/';
  if (!file_exists($carpetaRespaldos)) {
      mkdir($carpetaRespaldos, 0777, true); 
  }

  // 2. Definir el nombre del archivo del respaldo 
  $nombreArchivo = 'respaldo_' . date('Ymd_His') . '.sql';

  try {
      // Iniciar la transacción para un respaldo consistente:
      $db->begin_transaction();

      // 3. Obtener los datos de las tablas:
      $tablas = [
          'clientes' => "SELECT * FROM clientes WHERE idusuario = '$id_usua'",
          'ventas' => "SELECT * FROM ventas WHERE id_usuario = '$id_usua'",
          'inventario_componente' => "SELECT * FROM inventario_componente WHERE id_usuario = '$id_usua'",
          'inventario_producto_terminado' => "SELECT * FROM inventario_producto_terminado WHERE id_usuario = '$id_usua'"
      ];


      $sql = ''; // Acumular las consultas SQL

      foreach ($tablas as $tabla => $consulta) {
          $sql .= "-- Respaldo de datos de la tabla '$tabla'\n";
          $sql .= "DROP TABLE IF EXISTS `$tabla`;\n";
          $resultadoTabla = $db->query("SHOW CREATE TABLE `$tabla`");
          $filaTabla = $resultadoTabla->fetch_assoc();
          $sql .= $filaTabla['Create Table'] . ";\n\n";

          $resultadoDatos = $db->query($consulta);
          while ($filaDatos = $resultadoDatos->fetch_assoc()) {
              $campos = implode("`, `", array_keys($filaDatos));
              $valores = "'" . implode("', '", array_values($filaDatos)) . "'";
              $sql .= "INSERT INTO `$tabla` (`$campos`) VALUES ($valores);\n";
          }
          $sql .= "\n\n";
      }

      // 4. Escribir el SQL en el archivo
      $rutaArchivo = $carpetaRespaldos . $nombreArchivo; 
      file_put_contents($rutaArchivo, $sql);

      // Cerrar la transacción si todo salió bien
      $db->commit();

      // Mostrar mensaje de éxito 
      echo '<div class="alert alert-success">Respaldo generado exitosamente en: <a href="'. $pag_web . '/usuario/'. $rutaArchivo .'" target="_BLANK">' . $rutaArchivo . '</a></div>';
  } catch (Exception $e) {
      // Si ocurre un error durante la transacción, hacer rollback 
      $db->rollback();
      echo '<div class="alert alert-danger">Error al generar el respaldo: ' . $e->getMessage() . '</div>';
  }
}


function optimizarTablasUsuario($id_usua) {
global $db;
// Implementar la lógica de optimización, por ejemplo:
  $tablas = ['clientes', 'ventas', 'inventario_materia_prima', 'inventario_producto_terminado'];
  
  foreach ($tablas as $tabla) {
    $sql = "OPTIMIZE TABLE $tabla"; 
    $db->query($sql); 
  }
  
  echo '<div class="alert alert-success">Tablas optimizadas correctamente.</div>'; 
} 


function respaldarDatosAdmin() {
  global $db; // Asegúrate de que $db está definida globalmente o pásala como parámetro a la función.

  // Carpeta para almacenar los respaldos (crea la carpeta si no existe)
  $carpetaRespaldos = 'respaldos/admin/';
  if (!file_exists($carpetaRespaldos)) {
      mkdir($carpetaRespaldos, 0777, true);
  }

  // Nombre del archivo de respaldo
  $nombreArchivo = 'respaldo_completo_' . date('Ymd_His') . '.sql'; 
  $rutaArchivo = $carpetaRespaldos . $nombreArchivo;

  try {
      $db->begin_transaction(); 

      // Obtener todas las tablas de la base de datos
      $resultadoTablas = $db->query("SHOW TABLES");
      $tablas = []; 
      while ($fila = $resultadoTablas->fetch_row()) {
          $tablas[] = $fila[0];
      }

      // Generar el SQL para el respaldo 
      $sql = ''; 
      foreach ($tablas as $tabla) {
          $sql .= "-- Respaldo de datos de la tabla '$tabla'\n";
          $sql .= "DROP TABLE IF EXISTS `$tabla`;\n";

          $resultadoTabla = $db->query("SHOW CREATE TABLE `$tabla`");
          $filaTabla = $resultadoTabla->fetch_assoc();
          $sql .= $filaTabla['Create Table'] . ";\n\n";

          $resultadoDatos = $db->query("SELECT * FROM `$tabla`"); 
          while ($filaDatos = $resultadoDatos->fetch_assoc()) {
              $campos = implode("`, `", array_keys($filaDatos));
              $valores = "'" . implode("', '", array_values($filaDatos)) . "'"; 
              $sql .= "INSERT INTO `$tabla` (`$campos`) VALUES ($valores);\n";
          }

          $sql .= "\n\n"; 
      }

      // Escribir el SQL al archivo
      file_put_contents($rutaArchivo, $sql);

      // Cerrar la transacción
      $db->commit();

      echo '<div class="alert alert-success">Respaldo completo generado exitosamente: <a href="'. $rutaArchivo .'" target="_BLANK">' . $rutaArchivo . '</a></div>';
  } catch (Exception $e) {
      $db->rollback(); // Revertir la transacción si hay errores. 
      echo '<div class="alert alert-danger">Error al generar el respaldo: ' . $e->getMessage() . '</div>';
  }
}

function optimizarTablasAdmin() {
  global $db; // Asegúrate de que $db está definida globalmente o pásala como parámetro.
  
  try {
      // Iniciar transacción (opcional pero recomendado para garantizar la consistencia)
      $db->begin_transaction();

      // Obtener todas las tablas de la base de datos 
      $resultadoTablas = $db->query('SHOW TABLES');
      while ($fila = $resultadoTablas->fetch_row()) {
          $tabla = $fila[0]; 
          $db->query("OPTIMIZE TABLE `$tabla`"); 
      }

      // Cerrar transacción
      $db->commit();
      
      echo '<div class="alert alert-success">Todas las tablas de la base de datos han sido optimizadas correctamente.</div>';
  } catch (Exception $e) {
      $db->rollback(); // Revertir si hay errores. 
      echo '<div class="alert alert-danger">Error al optimizar las tablas: ' . $e->getMessage() . '</div>';
  }
}



function verificar_precios(){
  global $db, $id_usua;
  
  $query = "SELECT p.id, p.nombre 
FROM productos p
LEFT JOIN precios pre ON p.id = pre.id_producto AND pre.id_usuario = '$id_usua' 
WHERE pre.id_producto IS NULL AND p.id IN (
    SELECT ipt.id_producto
    FROM inventario_producto_terminado ipt
    WHERE ipt.id_usuario = '$id_usua')";
  
  $result = $db->query($query);
  
  if ($result->num_rows > 0) {

    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Advertencia!</strong> Se ha detectado la existencia en Stock de Productos pero que aun no se le han asignado Precio. Se sugiere ir a la seccion de <a href="precios.php">Precios</a> y crear el precio correspondiente para que el mismo pueda aparecer en la lista de Venta. <ul>';
    while($row = $result->fetch_assoc()){
        echo "<li><a href='#' class='crear-precio' data-id='" . $row['id'] . "' data-nombre='" . $row['nombre'] . "'>" . $row['nombre'] . "</a></li>"; 
    }
    echo ' </ul><button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">×</span>
    </button>
    </div> ';
    
    // Mostrar el modal con el producto
    echo '<div class="modal fade" id="modalCrearPrecio" tabindex="-1" role="dialog" aria-labelledby="modalCrearPrecioLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCrearPrecioLabel">Crear Precio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCrearPrecio">
                    <input type="hidden" id="producto" name="producto">
                    <div class="form-group">
                        <label for="productoNombre">Producto:</label>
                        <input type="text" class="form-control" id="productoNombre" name="productoNombre" readonly>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio:</label>
                        <input type="text" class="form-control" id="precio" name="precio" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-tag"></i> Crear Precio</button>
                </form>
            </div>
        </div>
    </div>
    </div>';

    // Agregar JavaScript para manejar el evento click en los links
    echo '<script>
    $(document).ready(function() {
        // Evento click en los links para abrir el modal
        $(".crear-precio").click(function(event) {
            event.preventDefault();
            var producto = $(this).data("id");
            var productoNombre = $(this).data("nombre");
            $("#producto").val(producto);
            $("#productoNombre").val(productoNombre);
            $("#modalCrearPrecio").modal("show");
        });
    
        // Submit del formulario del modal
        $("#formCrearPrecio").submit(function(e) {
            e.preventDefault();
            var producto = $("#producto").val();
            var precio = $("#precio").val();
            
            // Realizar la solicitud AJAX para guardar el precio
            $.ajax({
                url: "funciones/guardar_precios.php",
                type: "POST",
                body: formCrearPrecio,
                data: {
                    producto: producto,
                    precio: precio
                },
                success: function(response) {
                  $("#modalCrearPrecio").modal("hide");
                  alert("Precio creado correctamente.");

                  //  Recargar la página actual
                  location.reload(); 
                },
                error: function(xhr, status, error) {
                    console.error("Error al crear el precio: " + error);
                    alert("Error al crear el precio. Por favor, inténtalo de nuevo.");
                }
            });
        });
    });
    </script>';

} else {
    //echo "<h1>No se encontraron productos sin precio</h1>"; 
}
}

/**
 * Obtiene todos los códigos de secciones
 * @return array Lista de códigos de secciones
 */
function obtenerCodigosSecciones() {
    global $db;
    
    $sql = "SELECT cs.*, c.nombre_carrera 
            FROM codigos_secciones cs 
            INNER JOIN carreras c ON cs.id_carrera = c.id_carrera 
            ORDER BY c.nombre_carrera, cs.codigo_inicio";
    
    $result = $db->query($sql);
    $codigos = [];
    
    while ($row = $result->fetch_assoc()) {
        $codigos[] = $row;
    }
    
    return $codigos;
}

/**
 * Inserta un nuevo código de sección
 * @param int $id_carrera ID de la carrera
 * @param int $codigo_inicio Código inicial del rango
 * @param int $codigo_fin Código final del rango
 * @param string $descripcion Descripción opcional
 * @return array Resultado con success y message
 */
function insertarCodigoSeccion($id_carrera, $codigo_inicio, $codigo_fin, $descripcion) {
    global $db;
    
    // Validar que no se solape con rangos existentes para la misma carrera
    $sql_check = "SELECT COUNT(*) as total FROM codigos_secciones 
                  WHERE id_carrera = ? AND 
                  ((? BETWEEN codigo_inicio AND codigo_fin) OR 
                   (? BETWEEN codigo_inicio AND codigo_fin) OR 
                   (codigo_inicio BETWEEN ? AND ?) OR 
                   (codigo_fin BETWEEN ? AND ?))";
    
    $stmt_check = $db->prepare($sql_check);
    $stmt_check->bind_param("iiiiiii", $id_carrera, $codigo_inicio, $codigo_fin, $codigo_inicio, $codigo_fin, $codigo_inicio, $codigo_fin);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $row_check = $result_check->fetch_assoc();
    
    if ($row_check['total'] > 0) {
        return ['success' => false, 'message' => 'El rango de códigos se solapa con uno existente para esta carrera.'];
    }
    
    $sql = "INSERT INTO codigos_secciones (id_carrera, codigo_inicio, codigo_fin, descripcion) 
            VALUES (?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("iiis", $id_carrera, $codigo_inicio, $codigo_fin, $descripcion);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Código de sección creado correctamente.'];
    } else {
        return ['success' => false, 'message' => 'Error al crear el código de sección: ' . $stmt->error];
    }
}

/**
 * Actualiza un código de sección
 * @param int $id ID del registro
 * @param int $id_carrera ID de la carrera
 * @param int $codigo_inicio Código inicial
 * @param int $codigo_fin Código final
 * @param string $descripcion Descripción
 * @return array Resultado
 */
function actualizarCodigoSeccion($id, $id_carrera, $codigo_inicio, $codigo_fin, $descripcion) {
    global $db;
    
    // Validar que no se solape con otros rangos (excluyendo el actual)
    $sql_check = "SELECT COUNT(*) as total FROM codigos_secciones 
                  WHERE id != ? AND id_carrera = ? AND 
                  ((? BETWEEN codigo_inicio AND codigo_fin) OR 
                   (? BETWEEN codigo_inicio AND codigo_fin) OR 
                   (codigo_inicio BETWEEN ? AND ?) OR 
                   (codigo_fin BETWEEN ? AND ?))";
    
    $stmt_check = $db->prepare($sql_check);
    $stmt_check->bind_param("iiiiiiii", $id, $id_carrera, $codigo_inicio, $codigo_fin, $codigo_inicio, $codigo_fin, $codigo_inicio, $codigo_fin);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $row_check = $result_check->fetch_assoc();
    
    if ($row_check['total'] > 0) {
        return ['success' => false, 'message' => 'El rango de códigos se solapa con uno existente para esta carrera.'];
    }
    
    $sql = "UPDATE codigos_secciones SET id_carrera = ?, codigo_inicio = ?, codigo_fin = ?, descripcion = ? 
            WHERE id = ?";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("iiisi", $id_carrera, $codigo_inicio, $codigo_fin, $descripcion, $id);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Código de sección actualizado correctamente.'];
    } else {
        return ['success' => false, 'message' => 'Error al actualizar el código de sección: ' . $stmt->error];
    }
}

/**
 * Elimina un código de sección
 * @param int $id ID del registro
 * @return array Resultado
 */
function eliminarCodigoSeccion($id) {
    global $db;
    
    $sql = "DELETE FROM codigos_secciones WHERE id = ?";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Código de sección eliminado correctamente.'];
    } else {
        return ['success' => false, 'message' => 'Error al eliminar el código de sección: ' . $stmt->error];
    }
}

/**
 * Genera un código de sección automáticamente basado en los rangos definidos
 * @param int $id_carrera ID de la carrera
 * @return string Código generado o null si no hay rango disponible
 */
function generarCodigoSeccion($id_carrera, $turno) {
    global $db;
    
    // Obtener el rango para la carrera
    $sql = "SELECT codigo_inicio, codigo_fin FROM codigos_secciones 
            WHERE id_carrera = ? 
            ORDER BY codigo_inicio";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id_carrera);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        return null; // No hay rango definido
    }
    
    $rangos = [];
    while ($row = $result->fetch_assoc()) {
        $rangos[] = $row;
    }
    
    // Obtener códigos ya usados en secciones para esta carrera y turno
    $sql_usados = "SELECT DISTINCT CAST(codigo_seccion AS UNSIGNED) as codigo_num 
                   FROM secciones 
                   WHERE id_carrera = ? AND turno = ? AND codigo_seccion REGEXP '^[0-9]+$'";
    
    $stmt_usados = $db->prepare($sql_usados);
    $stmt_usados->bind_param("is", $id_carrera, $turno);
    $stmt_usados->execute();
    $result_usados = $stmt_usados->get_result();
    
    $usados = [];
    while ($row = $result_usados->fetch_assoc()) {
        $usados[] = (int)$row['codigo_num'];
    }
    
    // Encontrar el primer código disponible en los rangos
    foreach ($rangos as $rango) {
        for ($codigo = $rango['codigo_inicio']; $codigo <= $rango['codigo_fin']; $codigo++) {
            if (!in_array($codigo, $usados)) {
                return (string)$codigo;
            }
        }
    }
    
    return null; // No hay códigos disponibles
}

?>