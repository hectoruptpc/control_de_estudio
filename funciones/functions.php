
<?php

session_start();

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
function desactivarPeriodosVencidos($db) {
    $query = "UPDATE periodos_academicos SET activo = 0 
              WHERE fecha_fin < CURDATE() AND activo = 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $db->affected_rows; // Retorna cuántos periodos desactivó
}

// Ejecutar la función cada vez que alguien entre al sistema
desactivarPeriodosVencidos($db);








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

/**
* Valida y sanitiza los datos de un estudiante
*/
function validarDatosEstudiante($data) {
  $errors = [];
  $validados = [];
  
  // Validación de cédula
  if (empty($data['idusuario'])) {
      $errors['idusuario'] = "La cédula es requerida";
  } else {
      $validados['idusuario'] = htmlspecialchars(trim($data['idusuario']));
  }
  
  // Validación de nombre
  if (empty($data['nombre'])) {
      $errors['nombre'] = "El nombre es requerido";
  } else {
      $validados['nombre'] = htmlspecialchars(trim($data['nombre']));
  }
  
  // Validación de correo
  if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = "Correo electrónico no válido";
  } else {
      $validados['email'] = !empty($data['email']) ? htmlspecialchars(trim($data['email'])) : null;
  }
  
  // Validación de teléfono
  if (!empty($data['tlf']) && !preg_match('/^[0-9\-\+]{10,15}$/', $data['tlf'])) {
      $errors['tlf'] = "Formato de teléfono no válido";
  } else {
      $validados['tlf'] = !empty($data['tlf']) ? htmlspecialchars(trim($data['tlf'])) : null;
  }
  
  // Otros campos
  $validados['carrera'] = !empty($data['carrera']) ? htmlspecialchars(trim($data['carrera'])) : null;
  $validados['genero'] = !empty($data['genero']) ? htmlspecialchars(trim($data['genero'])) : null;
  $validados['status'] = !empty($data['status']) ? htmlspecialchars(trim($data['status'])) : 'Activo';
  $validados['user_type'] = 'estudiante';
  
  return [
      'data' => $validados,
      'errors' => $errors
  ];
}

function insertarEstudiante($datos) {
    global $db;
    
    try {
        // Iniciar transacción
        $db->begin_transaction();

        // 1. Preparar datos del usuario
        $username = strtolower(str_replace(' ', '.', $datos['nombre']));
        $cedulaLimpia = substr($datos['idusuario'], 2);
        $password = md5($cedulaLimpia);
        $fecha_act = date('Y-m-d H:i:s');
        $api_key = '';

        // 2. Configurar roles y valores por defecto
        $roles = [
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
            'editar_materia' => 0
        ];

        $defaults = [
            'cel' => '',
            'ciudad' => $datos['municipio'] ?? '',
            'num_telf_opc' => '',
            'etnia' => '',
            'casaapto' => 'No especificado',
            'punto_referencia' => '',
            'grupo_familiar' => 0,
            'acargo_usted' => 0,
            'fuente_ingresos' => '',
            'tipo_vivienda' => '',
            'tenencia_vivienda' => '',
            'enfermedad' => '',
            'discapacidad' => '',
            'titulos' => '',
            'institutos' => '',
            'api_key' => $api_key
        ];

        // 3. Combinar todos los valores
        $valores = array_merge(
            [
                'idusuario' => $datos['idusuario'],
                'nombre' => $datos['nombre'],
                'username' => $username,
                'email' => $datos['email'] ?? null,
                'tlf' => $datos['tlf'] ?? null,
                'direccion' => $datos['direccion'] ?? null,
                'estado' => $datos['estado'] ?? null,
                'municipio' => $datos['municipio'] ?? null,
                'parroquia' => $datos['parroquia'] ?? null,
                'fecha_ingreso' => $datos['fecha_ingreso'] ?? null,
                'status' => $datos['status'] ?? 'Activo',
                'user_type' => 'estudiante',
                'password' => $password,
                'carrera' => $datos['carrera'] ?? null,
                'genero' => $datos['genero'] ?? null,
                'edo_civil' => $datos['edo_civil'] ?? null,
                'fecha_nac' => $datos['fecha_nac'] ?? null,
                'fecha_act' => $fecha_act
            ],
            $defaults,
            $roles
        );

        // 4. Insertar en la tabla users
        $columnas = implode(', ', array_keys($valores));
        $placeholders = implode(', ', array_fill(0, count($valores), '?'));
        $sql = "INSERT INTO users ($columnas) VALUES ($placeholders)";

        $stmt = $db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en preparación: " . $db->error);
        }

        // 5. Vincular parámetros
        $tipos = '';
        $valoresBind = [];
        foreach ($valores as $key => $value) {
            if (in_array($key, ['grupo_familiar', 'acargo_usted', 'usuario', 'estudiante', 'docente', 'admin', 'super_user', 'editar_user', 'editar_nota', 'editar_acceso'])) {
                $tipos .= 'i'; // Entero
                $valoresBind[] = (int)$value;
            } else {
                $tipos .= 's'; // String
                $valoresBind[] = $value;
            }
        }

        $stmt->bind_param($tipos, ...$valoresBind);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar: " . $stmt->error);
        }
        
        $userId = $stmt->insert_id;
        $stmt->close();

        // 6. Insertar títulos obtenidos si existen
        if (!empty($datos['titulos']) && !empty($datos['institutos'])) {
            $titulos = is_array($datos['titulos']) ? $datos['titulos'] : [$datos['titulos']];
            $institutos = is_array($datos['institutos']) ? $datos['institutos'] : [$datos['institutos']];
            
            $count = min(count($titulos), count($institutos));
            
            $sqlTitulos = "INSERT INTO titulos_obtenidos (id_usuario, nombre, titulo_obtenido, instituto) VALUES (?, ?, ?, ?)";
            $stmtTitulos = $db->prepare($sqlTitulos);
            
            if (!$stmtTitulos) {
                throw new Exception("Error al preparar consulta de títulos: " . $db->error);
            }
            
            for ($i = 0; $i < $count; $i++) {
                $stmtTitulos->bind_param(
                    "isss", 
                    $userId,
                    $datos['nombre'],
                    $titulos[$i],
                    $institutos[$i]
                );
                if (!$stmtTitulos->execute()) {
                    throw new Exception("Error al insertar título: " . $stmtTitulos->error);
                }
            }
            
            $stmtTitulos->close();
        }

        // 7. REGISTRAR EN AUDITORÍA - NUEVO ESTUDIANTE
        $valores_nuevos = [
            'idusuario' => $datos['idusuario'],
            'nombre' => $datos['nombre'],
            'email' => $datos['email'] ?? '',
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
            "Registro de nuevo estudiante"
        );

        // Confirmar transacción
        $db->commit();

        return [
            'success' => true,
            'message' => 'Estudiante registrado exitosamente!',
            'id' => $userId
        ];

    } catch(Exception $e) {
        // Revertir transacción en caso de error
        $db->rollback();
        
        // REGISTRAR EN AUDITORÍA - ERROR AL REGISTRAR ESTUDIANTE
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
        
        error_log("Error en insertarEstudiante: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Error al registrar estudiante: ' . $e->getMessage()
        ];
    }
}

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
    
    // Validación especial para carrera cuando se selecciona "OTRA"
    if (isset($datos['carrera']) && $datos['carrera'] === 'OTRA' && empty($datos['otra_carrera'])) {
        $errores[] = "Debe especificar el nombre de la carrera cuando selecciona 'OTRA'";
    }
    
    // Validar email
    if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Por favor ingrese un correo electrónico válido';
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

// Función para actualizar estudiante
function actualizarEstudiante(array $datos): array {
    global $db;
    
    try {
        // Primero obtener los valores antiguos para auditoría
        $query_antiguo = "SELECT * FROM users WHERE id = ?";
        $stmt_antiguo = $db->prepare($query_antiguo);
        $stmt_antiguo->bind_param("i", $datos['id']);
        $stmt_antiguo->execute();
        $result_antiguo = $stmt_antiguo->get_result();
        $valores_antiguos = $result_antiguo->fetch_assoc();
        $stmt_antiguo->close();

        // Consulta SQL con los campos correctos que estás usando
        $sql = "UPDATE users SET 
                nombre = ?,
                username = ?,
                email = ?,
                tlf = ?,
                num_telf_opc = ?,
                carrera = ?,
                genero = ?,
                fecha_nac = ?,
                fecha_ingreso = ?,
                status = ?,
                fecha_act = NOW()
                WHERE id = ?";

        // Preparar la sentencia
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación: " . $db->error);
        }

        // Vincular parámetros
        $stmt->bind_param(
            "sssssssssii", // Tipos de parámetros
            $datos['nombre'],
            $datos['username'],
            $datos['email'],
            $datos['tlf'],
            $datos['num_telf_opc'],
            $datos['carrera'],
            $datos['genero'],
            $datos['fecha_nac'],
            $datos['fecha_ingreso'],
            $datos['status'],
            $datos['id']
        );

        // Ejecutar la actualización
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar: " . $stmt->error);
        }

        // Verificar si se realizaron cambios
        $cambios = $stmt->affected_rows > 0;
        
        // Registrar auditoría solo si hubo cambios
        if ($cambios) {
            $valores_nuevos = [
                'nombre' => $datos['nombre'],
                'username' => $datos['username'],
                'email' => $datos['email'],
                'tlf' => $datos['tlf'],
                'num_telf_opc' => $datos['num_telf_opc'],
                'carrera' => $datos['carrera'],
                'genero' => $datos['genero'],
                'fecha_nac' => $datos['fecha_nac'],
                'fecha_ingreso' => $datos['fecha_ingreso'],
                'status' => $datos['status']
            ];
            
            registrarAuditoria(
                "UPDATE", 
                "users", 
                $datos['id'], 
                $valores_antiguos, 
                $valores_nuevos, 
                "Estudiantes", 
                "Actualización de datos de estudiante"
            );
        }
        
        return [
            'success' => $cambios,
            'message' => $cambios 
                ? 'Estudiante actualizado correctamente' 
                : 'No se realizaron cambios (posiblemente los datos son iguales)',
            'affected_rows' => $stmt->affected_rows
        ];

    } catch(Exception $e) {
        error_log("Error en actualizarEstudiante: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Error al actualizar estudiante: ' . $e->getMessage()
        ];
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
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
    
    $query = "UPDATE carreras SET activa = ? WHERE id_carrera = ?";
    $stmt = $db->prepare($query);
    
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $conn->error);
    }
    
    $stmt->bind_param("ii", $estado, $id_carrera);
    $resultado = $stmt->execute();
    
    // Verificar si realmente hubo cambios
    if ($resultado && $stmt->affected_rows > 0) {
        return true;
    }
    
    return false;
}




// Función para agregar nuevas carreras
// Función para agregar nuevas carreras
function registrarNuevaCarrera(
    string $nombre, 
    string $codigo, 
    string $tipo_formacion, 
    int $duracion_anios,
    string $titulo_principal,
    string $titulo_opcional = ''
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
        
        // 2. Insertar nueva carrera
        $insertStmt = $db->prepare("INSERT INTO carreras 
            (nombre_carrera, cod_carrera, tipo_formacion, duracion_semestres, titulo_otorga, otro_titulo, descripcion, activa) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
        
        if (!$insertStmt) {
            throw new Exception("Error al preparar inserción: " . $db->error);
        }
        
        // Construir descripción con los títulos
        $descripcion = "Título principal: $titulo_principal";
        if (!empty($titulo_opcional)) {
            $descripcion .= "\nTítulo opcional: $titulo_opcional";
        }
        
        $insertStmt->bind_param(
            "sssisss", 
            $nombre, 
            $codigo, 
            $tipo_formacion,
            $duracion_semestres,
            $titulo_principal,  // Solo el título principal
            $titulo_opcional,   // Título opcional por separado
            $descripcion
        );
        
        if (!$insertStmt->execute()) {
            throw new Exception("Error al insertar carrera: " . $insertStmt->error);
        }
        
        $insertId = $db->insert_id;
        $insertStmt->close();
        
        $db->commit();
        
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
        
        $updateStmt->close();
        $db->commit();
        
        return [
            'success' => true,
            'message' => 'Programa académico actualizado exitosamente'
        ];
        
    } catch (Exception $e) {
        if (isset($db) && method_exists($db, 'rollback')) {
            $db->rollback();
        }
        error_log("Error en actualizarCarrera: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Error al actualizar programa: ' . $e->getMessage()
        ];
    }
}


//HACER COSAS CON LOS DOCENTES

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
        $password = password_hash($datos['documento'], PASSWORD_DEFAULT);
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

        // Confirmar transacción
        $db->commit();

        return [
            'success' => true,
            'message' => 'Docente registrado exitosamente',
            'id' => $idInsertado,
            'username' => $username,
            'password_temp' => $datos['documento']
        ];

    } catch(Exception $e) {
        if (isset($db) && method_exists($db, 'rollback')) {
            $db->rollback();
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
      
      // Vincula los parámetros a la sentencia preparada
      // Los tipos de datos se especifican con una cadena:
      // s = string, i = integer, d = double, b = blob
      // En este caso: 16 strings (s) y 1 integer (i) al final (el ID)
      $stmt->bind_param(
          "ssssssssssssssssi",
          $datos['nombre'],
          $datos['email'],
          $datos['tlf'],
          $datos['cel'],
          $datos['direccion'],
          $datos['estado'],
          $datos['municipio'],
          $datos['parroquia'],
          $datos['status'],
          $datos['carrera'],
          $datos['genero'],
          $datos['edo_civil'],
          $datos['fecha_nac'],
          $datos['num_telf_opc'],
          $datos['titulos'],
          $datos['institutos'],
          $datos['fecha_ingreso'],
          $datos['id']
      );
      
      // Ejecuta la sentencia preparada
      $stmt->execute();
      
      // Retorna un array con el resultado de la operación
      return [
          'success' => $stmt->affected_rows > 0,  // True si se actualizó alguna fila
          'message' => $stmt->affected_rows > 0 
              ? 'Docente actualizado correctamente' 
              : 'No se realizaron cambios'  // Puede ocurrir si los datos son iguales
      ];
      
  } catch(Exception $e) {
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
  
  $docentes = [];
  
  // Preparamos la consulta
  $stmt = $db->prepare("SELECT id, idusuario, nombre, email, tlf, status 
                       FROM users 
                       WHERE docente = 1 
                       ORDER BY nombre ASC");
  
  if ($stmt === false) {
      die('Error en la preparación de la consulta: ' . $db->error);
  }
  
  // Ejecutamos la consulta
  if (!$stmt->execute()) {
      die('Error al ejecutar la consulta: ' . $stmt->error);
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
  $query = "SELECT id_carrera, nombre_carrera, cod_carrera 
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
      return [
          'success' => false,
          'message' => 'Error al verificar asignación: ' . $db->error
      ];
  }

  // 2. Insertar nueva asignación (con prepared statement)
  $insert_query = "INSERT INTO carrera_materia 
                  (id_carrera, id_materia, semestre) 
                  VALUES (?, ?, ?)";
  
  if ($insert_stmt = $db->prepare($insert_query)) {
      $insert_stmt->bind_param("iii", $id_carrera, $id_materia, $semestre);
      $execute_result = $insert_stmt->execute();
      $insert_stmt->close();
      
      if ($execute_result) {
          return [
              'success' => true,
              'message' => 'Materia asignada correctamente',
              'insert_id' => $db->insert_id
          ];
      } else {
          return [
              'success' => false,
              'message' => 'Error al asignar: ' . $db->error
          ];
      }
  } else {
      return [
          'success' => false,
          'message' => 'Error preparando consulta: ' . $db->error
      ];
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

    // 1. Verificar si existe la relación
    $check_query = "SELECT COUNT(*) AS total FROM carrera_materia WHERE id_relacion = ?";
    
    if ($check_stmt = $db->prepare($check_query)) {
        $check_stmt->bind_param("i", $id_relacion);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $existe_relacion = $check_result->fetch_assoc()['total'] > 0;
        $check_stmt->close();
        
        if (!$existe_relacion) {
            return [
                'success' => false, 
                'message' => 'No se encontró la relación especificada'
            ];
        }
    } else {
        return [
            'success' => false,
            'message' => 'Error al verificar la relación: ' . $db->error
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
            return [
                'success' => true,
                'message' => 'Asignación eliminada correctamente',
                'affected_rows' => $affected_rows
            ];
        } else {
            return [
                'success' => false,
                'message' => $affected_rows === 0 
                    ? 'No se encontró la asignación con el ID proporcionado' 
                    : 'Error al eliminar: ' . $db->error
            ];
        }
    } else {
        return [
            'success' => false,
            'message' => 'Error preparando consulta de eliminación: ' . $db->error
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
              created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

  // Preparamos la sentencia
  $stmt = $db->prepare($query);
  
  if (!$stmt) {
      error_log("Error en preparación de query: " . $db->error);
      return false;
  }

  // Validación y asignación de valores
  $cod_materia = $data['cod_materia'] ?? '';
  $nombre_materia = $data['nombre_materia'] ?? '';
  $pnf_ptf = $data['pnf_ptf'] ?? '';
  $duracion_periodo = isset($data['duracion_periodo']) ? (int)$data['duracion_periodo'] : 0;
  $creditos = isset($data['creditos']) ? (int)$data['creditos'] : 0;
  $horas_teoricas = isset($data['horas_teoricas']) ? (int)$data['horas_teoricas'] : 0;
  $horas_practicas = isset($data['horas_practicas']) ? (int)$data['horas_practicas'] : 0;

  // Vinculamos parámetros (tipos: s=string, i=integer)
  $stmt->bind_param("sssiiiiii", 
      $cod_materia,
      $nombre_materia,
      $pnf_ptf,
      $duracion_periodo,
      $trayecto,
      $creditos,
      $activa,
      $horas_teoricas,
      $horas_practicas
  );
  
  // Ejecutamos la sentencia
  $result = $stmt->execute();
  
  if (!$result) {
      error_log("Error al ejecutar query: " . $stmt->error);
  }
  
  // Cerramos la sentencia
  $stmt->close();
  
  return $result;
}






function actualizarMateria($db, $id, $data) {
  // Validar que el ID sea numérico
  if (!is_numeric($id)) {
      error_log("Error: ID de materia no válido");
      return false;
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
            trayecto = ?
            WHERE id_materia = ?";
  
  // Preparamos la sentencia
  $stmt = $db->prepare($query);
  
  if (!$stmt) {
      error_log("Error en preparación de query: " . $db->error);
      return false;
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
  $trayecto = isset($data['trayecto']) ? (int)$data['trayecto'] : 0; // Añadido

  // Vinculamos parámetros (tipos: s=string, i=integer)
  $stmt->bind_param("sssiiiiiii", // Ahora son 10 'i's
      $cod_materia,
      $nombre_materia,
      $pnf_ptf,
      $duracion_periodo,
      $creditos,
      $activa,
      $horas_teoricas,
      $horas_practicas,
      $trayecto, // Añadido
      $id
  );
  
  // Ejecutamos la sentencia
  $result = $stmt->execute();
  
  if (!$result) {
      error_log("Error al actualizar materia: " . $stmt->error);
  }
  
  // Cerramos la sentencia
  $stmt->close();
  
  return $result;
}

/**
 * Alterna el estado activo/inactivo de una materia
 * 
 * @param mysqli $db Conexión a la base de datos MySQLi
 * @param int $id ID de la materia a modificar
 * @return array|false Retorna el nuevo estado y datos básicos, o false en caso de error
 */
function toggleMateria($db, $id) {
    // Validación básica del ID
    if (!is_numeric($id)) {
        return false;
    }

    // Consulta directa para alternar el estado
    $query = "UPDATE materias SET activa = NOT activa WHERE id_materia = ?";
    $stmt = $db->prepare($query);
    
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
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
                       horas_teoricas, horas_practicas, activa 
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
                       horas_teoricas, horas_practicas, activa 
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
      
      // Validación del ID de entrada
      if (!is_numeric($id) || $id <= 0) {
          error_log("Error en toggleMateriaStatus: ID inválido ($id)");
          return false;
      }
      
      // Preparamos la consulta para cambiar el estado
      $query = "UPDATE materias SET activa = NOT activa WHERE id = ?";
      $stmt = $db->prepare($query);
      
      // Verificamos si la preparación fue exitosa
      if (!$stmt) {
          error_log("Error al preparar la consulta en toggleMateriaStatus: ".$db->error);
          return false;
      }
      
      // Vinculamos el parámetro (i = integer)
      if (!$stmt->bind_param("i", $id)) {
          error_log("Error al vincular parámetro en toggleMateriaStatus: ".$stmt->error);
          $stmt->close();
          return false;
      }
      
      // Ejecutamos la consulta
      if (!$stmt->execute()) {
          error_log("Error al ejecutar consulta en toggleMateriaStatus: ".$stmt->error);
          $stmt->close();
          return false;
      }
      
      // Obtenemos el número de filas afectadas
      $affectedRows = $stmt->affected_rows;
      
      // Cerramos la sentencia
      $stmt->close();
      
      // Verificamos si realmente se actualizó algún registro
      if ($affectedRows === 0) {
          error_log("Advertencia en toggleMateriaStatus: Ningún registro actualizado (ID: $id)");
          return [
              'success' => false,
              'message' => 'No se encontró la materia con el ID proporcionado',
              'affected_rows' => 0
          ];
      }
      
      // Retornamos información sobre la operación exitosa
      return [
          'success' => true,
          'message' => 'Estado de la materia actualizado correctamente',
          'affected_rows' => $affectedRows
      ];
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
      
      // Validación de datos requeridos
      $camposRequeridos = ['cod_materia', 'nombre_materia', 'creditos', 
                          'horas_teoricas', 'horas_practicas', 'activa'];
      
      foreach ($camposRequeridos as $campo) {
          if (!isset($datos[$campo])) {
              error_log("Error en guardarMateria: Falta el campo requerido '$campo'");
              return [
                  'success' => false,
                  'message' => "Falta el campo requerido '$campo'"
              ];
          }
      }
      
      // Determinar si es inserción o actualización
      $esNueva = empty($datos['id']);
      
      try {
          if ($esNueva) {
              // INSERT de nueva materia
              $query = "INSERT INTO materias 
                       (cod_materia, nombre_materia, creditos, horas_teoricas, horas_practicas, activa) 
                       VALUES (?, ?, ?, ?, ?, ?)";
              $stmt = $db->prepare($query);
              
              if (!$stmt) {
                  error_log("Error preparando INSERT: ".$db->error);
                  return false;
              }
              
              $stmt->bind_param("ssiiii", 
                  $datos['cod_materia'],
                  $datos['nombre_materia'],
                  $datos['creditos'],
                  $datos['horas_teoricas'],
                  $datos['horas_practicas'],
                  $datos['activa']);
          } else {
              // UPDATE de materia existente
              $query = "UPDATE materias SET 
                       cod_materia = ?, 
                       nombre_materia = ?, 
                       creditos = ?, 
                       horas_teoricas = ?, 
                       horas_practicas = ?, 
                       activa = ? 
                       WHERE id = ?";
              $stmt = $db->prepare($query);
              
              if (!$stmt) {
                  error_log("Error preparando UPDATE: ".$db->error);
                  return false;
              }
              
              $stmt->bind_param("ssiiiii", 
                  $datos['cod_materia'],
                  $datos['nombre_materia'],
                  $datos['creditos'],
                  $datos['horas_teoricas'],
                  $datos['horas_practicas'],
                  $datos['activa'],
                  $datos['id']);
          }
          
          // Ejecutar la consulta
          if (!$stmt->execute()) {
              error_log("Error ejecutando consulta: ".$stmt->error);
              $stmt->close();
              return [
                  'success' => false,
                  'message' => "Error al guardar en la base de datos"
              ];
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
          
          // Retornar resultado exitoso
          return [
              'success' => true,
              'message' => $esNueva ? "Materia creada exitosamente" : "Materia actualizada exitosamente",
              'affected_rows' => $affectedRows,
              'id' => $esNueva ? $nuevoId : $datos['id']
          ];
          
      } catch (Exception $e) {
          error_log("Excepción en guardarMateria: ".$e->getMessage());
          if (isset($stmt) && $stmt instanceof mysqli_stmt) {
              $stmt->close();
          }
          return false;
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
                'cedula' => $row['cedula'],
                'nombre' => $row['nombre'],
                'carrera' => $row['carrera'] ?? 'No especificado',
                'contacto' => $row['cel'] ?: ($row['tlf'] ?: 'Sin teléfono'),
                'email' => $row['email'] ?? 'Sin email'
            ];
        }
        
        $stmt->close();
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


// OBTENER LOS DATOS SIMPLES****************************************************************************************


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
        // Registrar el error
        error_log($e->getMessage());
        
        // Opcional: puedes devolver un array vacío o false según tu necesidad
        return [];
    }
}



function obtenerEstadosCiviless($db) {
    // Validar conexión
    if (!($db instanceof mysqli)) {
        throw new InvalidArgumentException("Se esperaba una conexión MySQLi válida");
    }

    $estados = [];
    $query = "SELECT id, estado_civil FROM estado_civil ORDER BY estado_civil ASC";
    
    try {
        // Preparar sentencia
        if (!$stmt = $db->prepare($query)) {
            throw new Exception("Error al preparar la consulta: " . $db->error);
        }
        
        // Ejecutar
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        
        // Obtener resultados
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $estados[$row['id']] = $row['estado_civil']; // CORRECCIÓN AQUÍ (faltaba ])
        }
        
        return $estados;
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        return []; // Devuelve array vacío en caso de error
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}


function obtenerTiposVivienda($db) {
    // Validar conexión
    if (!($db instanceof mysqli)) {
        throw new InvalidArgumentException("Se esperaba una conexión MySQLi válida");
    }

    $viviendas = [];
    $query = "SELECT id, vivienda FROM tipo_vivienda ORDER BY vivienda ASC";
    
    try {
        // Preparar sentencia
        if (!$stmt = $db->prepare($query)) {
            throw new Exception("Error al preparar la consulta: " . $db->error);
        }
        
        // Ejecutar
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        
        // Obtener resultados
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $viviendas[$row['id']] = $row['vivienda'];
        }
        
        return $viviendas;
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        return []; // Devuelve array vacío en caso de error
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}


function obtenerTenenciaViviendas($db) {
    // Validar conexión
    if (!($db instanceof mysqli)) {
        throw new InvalidArgumentException("Se esperaba una conexión MySQLi válida");
    }

    $tenencias = [];
    $query = "SELECT id, tenencia FROM tenencia_vivienda ORDER BY tenencia ASC";
    
    try {
        // Preparar sentencia
        if (!$stmt = $db->prepare($query)) {
            throw new Exception("Error al preparar la consulta: " . $db->error);
        }
        
        // Ejecutar
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        
        // Obtener resultados
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $tenencias[$row['id']] = $row['tenencia'];
        }
        
        return $tenencias;
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        return []; // Devuelve array vacío en caso de error
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}



function obtenerOpcionesStatus($db) {
    // Validar conexión
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

// Agrega esta función junto con las demás funciones de obtención de datos
function obtenerIngresos($db) {
    $ingresos = [];
    $query = "SELECT id, ingreso FROM ingresos ORDER BY id";
    $result = $db->query($query);
    
    while ($row = $result->fetch_assoc()) {
        $ingresos[$row['id']] = $row['ingreso'];
    }
    
    return $ingresos;
}

//FUNCIONES PARA LAS SECCIONES ***********************************************************************



// Constante para el mínimo de estudiantes requeridos
define('MINIMO_ESTUDIANTES', 10);

/**
 * Crea una nueva sección en la base de datos
 * @param mysqli $db Conexión a la base de datos
 * @param array $datos Datos de la sección (codigo_seccion, id_carrera, id_trayecto, id_periodo, capacidad_maxima)
 * @return array Resultado de la operación (éxito, mensaje)
 */
function crearSeccion($db, $datos) {
    try {
        $stmt = $db->prepare("INSERT INTO secciones (codigo_seccion, id_carrera, id_trayecto, id_periodo, capacidad_maxima, inicia, estatus) 
                            VALUES (?, ?, ?, ?, ?, ?, 'inactiva')");
        $stmt->bind_param("siiiis", 
            $datos['codigo_seccion'], 
            $datos['id_carrera'], 
            $datos['id_trayecto'], 
            $datos['id_periodo'], 
            $datos['capacidad_maxima'],
            $datos['inicia']);
        $stmt->execute();
        $stmt->close();
        
        return [
            'success' => true,
            'message' => "Sección creada exitosamente! La sección estará inactiva hasta tener al menos ".MINIMO_ESTUDIANTES." estudiantes."
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => "Error al crear sección: " . $e->getMessage()
        ];
    }
}

function editarSeccion($db, $datos) {
    try {
        // Verificar si el período está activo
        $stmt = $db->prepare("SELECT p.activo FROM periodos_academicos p
                             JOIN secciones s ON s.id_periodo = p.id_periodo
                             WHERE s.id_seccion = ?");
        $stmt->bind_param("i", $datos['id_seccion']);
        $stmt->execute();
        $result = $stmt->get_result();
        $periodo = $result->fetch_assoc();
        $stmt->close();
        
        if ($periodo['activo'] == 0) {
            throw new Exception("No se puede editar una sección con período inactivo.");
        }
        
        $stmt = $db->prepare("UPDATE secciones 
                            SET codigo_seccion = ?, 
                                id_carrera = ?, 
                                id_trayecto = ?, 
                                id_periodo = ?, 
                                capacidad_maxima = ?,
                                inicia = ?
                            WHERE id_seccion = ?");
        $stmt->bind_param("siiiisi", 
            $datos['codigo_seccion'], 
            $datos['id_carrera'], 
            $datos['id_trayecto'], 
            $datos['id_periodo'], 
            $datos['capacidad_maxima'],
            $datos['inicia'],
            $datos['id_seccion']);
        $stmt->execute();
        $stmt->close();
        
        return [
            'success' => true,
            'message' => "Sección actualizada exitosamente!"
        ];
    } catch (Exception $e) {
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
        
        $result = [
            'success' => true,
            'message' => "Asignación de estudiantes actualizada!"
        ];
        
        if ($count >= MINIMO_ESTUDIANTES) {
            $result['message'] .= " La sección ha sido activada al alcanzar el mínimo requerido.";
        } else {
            $result['warning'] = "La sección permanecerá inactiva hasta tener al menos ".MINIMO_ESTUDIANTES." estudiantes (actualmente tiene $count).";
        }
        
        return $result;
    } catch (Exception $e) {
        $db->rollback();
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
        
        // Desactivar al estudiante en la sección
        $stmt = $db->prepare("UPDATE estudiante_seccion 
                             SET estatus = 'retirado'
                             WHERE id_seccion = ? AND id_usuario = ?");
        $stmt->bind_param("ii", $seccion_id, $usuario_id);
        $stmt->execute();
        $stmt->close();
        
        // Verificar si se debe cambiar el estado de la sección
        $count = contarEstudiantesActivos($db, $seccion_id);
        actualizarEstadoSeccion($db, $seccion_id, $count);
        
        $db->commit();
        
        return [
            'success' => true,
            'message' => "Estudiante retirado exitosamente de la sección."
        ];
    } catch (Exception $e) {
        $db->rollback();
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
    $stmt->bind_param("i", $seccion_id);
    $stmt->execute();
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
    $stmt->bind_param("i", $seccion_id);
    $stmt->execute();
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
    $placeholders = implode(',', array_fill(0, count($estudiantes), '?'));
    $types = str_repeat('i', count($estudiantes));
    
    $stmt = $db->prepare("UPDATE estudiante_seccion 
                        SET estatus = 'retirado'
                        WHERE id_seccion = ? 
                        AND id_usuario IN ($placeholders)");
    
    $params = array_merge([$seccion_id], $estudiantes);
    $stmt->bind_param(str_repeat('i', count($params)), ...$params);
    $stmt->execute();
    $stmt->close();
}

/**
 * Activa estudiantes en una sección
 * @param mysqli $db Conexión a la base de datos
 * @param int $seccion_id ID de la sección
 * @param array $estudiantes IDs de estudiantes a activar
 */
function activarEstudiantes($db, $seccion_id, $estudiantes) {
    $placeholders = implode(',', array_fill(0, count($estudiantes), '(?,?,CURDATE(),\'activo\')'));
    
    $stmt = $db->prepare("INSERT INTO estudiante_seccion (id_usuario, id_seccion, fecha_inscripcion, estatus)
                        VALUES $placeholders
                        ON DUPLICATE KEY UPDATE estatus = 'activo'");
    
    $params = [];
    foreach ($estudiantes as $est_id) {
        $params[] = $est_id;
        $params[] = $seccion_id;
    }
    
    $stmt->bind_param(str_repeat('i', count($params)), ...$params);
    $stmt->execute();
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
    $stmt->bind_param("i", $seccion_id);
    $stmt->execute();
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
    $stmt->bind_param("i", $seccion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $periodo = $result->fetch_assoc();
    $stmt->close();
    
    // Determinar el nuevo estado
    if ($periodo['activo'] == 0) {
        $nuevo_estatus = 'inactiva'; // Siempre inactiva si el período está inactivo
    } else {
        $nuevo_estatus = ($count >= MINIMO_ESTUDIANTES) ? 'activa' : 'inactiva';
    }
    
    // Actualizar el estado de la sección
    $stmt = $db->prepare("UPDATE secciones SET estatus = ? WHERE id_seccion = ?");
    $stmt->bind_param("si", $nuevo_estatus, $seccion_id);
    $stmt->execute();
    $stmt->close();
}



/**
 * Desactiva todas las secciones de un período académico cuando este se desactiva
 * @param mysqli $db Conexión a la base de datos
 * @param int $periodo_id ID del período académico
 */
function desactivarSeccionesDePeriodo($db, $periodo_id) {
    $stmt = $db->prepare("UPDATE secciones SET estatus = 'inactiva' WHERE id_periodo = ?");
    $stmt->bind_param("i", $periodo_id);
    $stmt->execute();
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
                            CASE WHEN p.activo = 0 THEN 'inactiva' ELSE s.estatus END as estatus,
                            COUNT(es.id_usuario) as inscritos
                          FROM secciones s
                          JOIN carreras c ON s.id_carrera = c.id_carrera
                          JOIN trayectos t ON s.id_trayecto = t.id_trayecto
                          JOIN periodos_academicos p ON s.id_periodo = p.id_periodo
                          LEFT JOIN estudiante_seccion es ON s.id_seccion = es.id_seccion AND es.estatus = 'activo'
                          GROUP BY s.id_seccion
                          ORDER BY p.nombre_periodo DESC, s.codigo_seccion");
    $stmt->execute();
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
    $stmt->bind_param("i", $seccion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $seccion = $result->fetch_assoc();
    $stmt->close();
    return $seccion;
}

/**
 * Obtiene los datos para los selects del formulario de sección
 * @param mysqli $db Conexión a la base de datos
 * @return array Datos para los selects (carreras, trayectos, periodos)
 */
function obtenerDatosSelects($db) {
    // Carreras - Excluyendo la que tiene id_carrera = 0 (No especificado)
    $stmt = $db->prepare("SELECT id_carrera, nombre_carrera FROM carreras WHERE activa = 1 AND id_carrera != 0");
    $stmt->execute();
    $result = $stmt->get_result();
    $carreras = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    // Trayectos
    $stmt = $db->prepare("SELECT id_trayecto, numero_trayecto FROM trayectos ORDER BY numero_trayecto");
    $stmt->execute();
    $result = $stmt->get_result();
    $trayectos = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    // Periodos
    $stmt = $db->prepare("SELECT id_periodo, nombre_periodo FROM periodos_academicos WHERE activo = 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $periodos = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
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
    $stmt->bind_param("i", $seccion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $seccion = $result->fetch_assoc();
    $stmt->close();
    
    if (!isset($seccion['inscritos'])) {
        $seccion['inscritos'] = 0;
    }
    
    return $seccion;
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
    $stmt->bind_param("i", $seccion_id);
    $stmt->execute();
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
    $stmt->bind_param("ii", $seccion_id, $carrera_id);
    $stmt->execute();
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


// Función para calcular cuántas filas debe ocupar una clase
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








// FUNCIONES DE PERIODOS ACADEMICOS***********************************************************************



/**
 * Obtiene todos los periodos académicos
 * @param mysqli $db Conexión a la base de datos
 * @return array Lista de periodos académicos
 */
function obtenerPeriodosAcademicos($db) {
    // Primero desactivamos cualquier periodo vencido (por si acaso)
    desactivarPeriodosVencidos($db);
    
    $query = "SELECT * FROM periodos_academicos ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Crea un nuevo periodo académico
 * @param mysqli $db Conexión a la base de datos
 * @param string $nombre Nombre del periodo
 * @param string $fecha_inicio Fecha de inicio (YYYY-MM-DD)
 * @param string $fecha_fin Fecha de fin (YYYY-MM-DD)
 * @return bool True si se creó correctamente
 */
function crearPeriodoAcademico($db, $nombre, $fecha_inicio, $fecha_fin) {
    $query = "INSERT INTO periodos_academicos (nombre_periodo, fecha_inicio, fecha_fin, activo, created_at) 
              VALUES (?, ?, ?, 1, NOW())";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sss", $nombre, $fecha_inicio, $fecha_fin);
    return $stmt->execute();
}

/**
 * Actualiza un periodo académico existente
 * @param mysqli $db Conexión a la base de datos
 * @param int $id ID del periodo
 * @param string $nombre Nuevo nombre del periodo
 * @param string $fecha_inicio Nueva fecha de inicio
 * @param string $fecha_fin Nueva fecha de fin
 * @return bool True si se actualizó correctamente
 */
function actualizarPeriodoAcademico($db, $id, $nombre, $fecha_inicio, $fecha_fin) {
    $query = "UPDATE periodos_academicos SET 
              nombre_periodo = ?,
              fecha_inicio = ?,
              fecha_fin = ?
              WHERE id_periodo = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sssi", $nombre, $fecha_inicio, $fecha_fin, $id);
    return $stmt->execute();
}

/**
 * Cambia el estado de un período académico
 * @param mysqli $db Conexión a la base de datos
 * @param int $periodo_id ID del período
 * @param int $nuevo_estado Nuevo estado (1 para activo, 0 para inactivo)
 * @return bool True si la operación fue exitosa
 */
function cambiarEstadoPeriodo($db, $periodo_id, $nuevo_estado) {
    try {
        $stmt = $db->prepare("UPDATE periodos_academicos SET activo = ? WHERE id_periodo = ?");
        $stmt->bind_param("ii", $nuevo_estado, $periodo_id);
        $stmt->execute();
        $stmt->close();
        return true;
    } catch (Exception $e) {
        error_log("Error al cambiar estado del período: " . $e->getMessage());
        return false;
    }
}


/**
 * Actualiza el estado de todas las secciones de un período cuando este se activa
 * @param mysqli $db Conexión a la base de datos
 * @param int $periodo_id ID del período académico
 */
function actualizarEstadoSeccionesDePeriodo($db, $periodo_id) {
    // Obtener todas las secciones del período
    $stmt = $db->prepare("SELECT id_seccion FROM secciones WHERE id_periodo = ?");
    $stmt->bind_param("i", $periodo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $secciones = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    // Actualizar el estado de cada sección
    foreach ($secciones as $seccion) {
        $count = contarEstudiantesActivos($db, $seccion['id_seccion']);
        actualizarEstadoSeccion($db, $seccion['id_seccion'], $count);
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


// ==============================================
// ARCHIVO: funciones/functions.php
// Sistema de Auditoría - Funciones adicionales
// ==============================================

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
function obtenerRegistrosAuditoria($limite = 100, $fecha_inicio = null, $fecha_fin = null, $usuario_id = null, $accion = null, $modulo = null) {
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
    
    if ($usuario_id) {
        $query .= " AND a.usuario_id = ?";
        $params[] = $usuario_id;
        $types .= "i";
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


// Función para generar membrete en PDF
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
                
                // Agregar texto del membrete
                doc.setFontSize(12);
                doc.setFont(undefined, 'bold');
                doc.text('REPÚBLICA BOLIVARIANA DE VENEZUELA', pageWidth / 2, 15, { align: 'center' });
                doc.text('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA', pageWidth / 2, 20, { align: 'center' });
                doc.text('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO', pageWidth / 2, 25, { align: 'center' });
                
                // Agregar fecha
                doc.setFont(undefined, 'normal');
                doc.text('$fecha', pageWidth - margin, 15, { align: 'right' });
                
                resolve(35); // Retornar posición Y después del membrete
            };
            
            logoImg.onerror = function() {
                // Fallback sin imagen
                doc.setFontSize(12);
                doc.setFont(undefined, 'bold');
                doc.text('República Bolivariana de Venezuela', pageWidth / 2, 15, { align: 'center' });
                doc.text('Ministerio del Poder Popular para la Educación Universitaria', pageWidth / 2, 20, { align: 'center' });
                doc.text('Universidad Politécnica Territorial de Puerto Cabello', pageWidth / 2, 25, { align: 'center' });
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
    echo "<script>
        // Configuración de jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        const margin = 10;
        const pageWidth = doc.internal.pageSize.getWidth();
        
        // Agregar membrete
        const startY = " . generarMembretePDF('doc', 'pageWidth') . ";
        
        // Capturar el contenido HTML y agregarlo al PDF
        html2canvas(document.getElementById('$elementoHTML'), {
            scale: 2,
            useCORS: true,
            logging: false
        }).then(canvas => {
            const imgData = canvas.toDataURL('image/jpeg', 1.0);
            const imgWidth = pageWidth - (margin * 2);
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            
            // Agregar contenido al PDF
            doc.addImage(imgData, 'JPEG', margin, startY, imgWidth, imgHeight);
            
            // Guardar el PDF
            doc.save('$nombreArchivo');
        });
    </script>";
}




//CARGA DE NOTAS ***********************************************************************

/**
 * Obtiene información completa de una materia incluyendo trayecto
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
 * Obtiene todos los estudiantes de una sección específica
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
 * Obtiene las notas de un estudiante en una materia específica
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
 * Obtiene el período académico de una sección
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
 * Obtiene información del trayecto de una sección
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
 * Determina el trayecto a mostrar basado en el ID de trayecto de la sección
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




//MENSAJERIA ***********************************************************************

// Obtener lista de usuarios para enviar mensajes con filtros
function obtenerUsuariosMensajeria($filtro_tipo = '', $busqueda_cedula = '') {
    global $db;
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
    
    // Bind parameters dinámicamente
    if (count($params) > 1) {
        $stmt->bind_param($types, ...$params);
    } else {
        $stmt->bind_param($types, $params[0]);
    }
    
    $stmt->execute();
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
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
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
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
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
    $stmt->bind_param("ii", $mensaje_id, $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Marcar mensaje como leído
function marcarMensajeLeido($mensaje_id, $user_id) {
    global $db;
    
    $query = "UPDATE mensajeria SET leido = TRUE 
              WHERE id = ? AND id_usuario_destinatario = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $mensaje_id, $user_id);
    return $stmt->execute();
}

// Enviar mensaje
function enviarMensaje($remitente_id, $destinatario_id, $titulo, $mensaje) {
    global $db;
    
    $query = "INSERT INTO mensajeria (id_usuario_remitente, id_usuario_destinatario, titulo, mensaje)
              VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("iiss", $remitente_id, $destinatario_id, $titulo, $mensaje);
    
    return $stmt->execute();
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

function obtenerTiposHorario($db) {
    $query = "SELECT * FROM tipos_horario ORDER BY nombre";
    $result = $db->query($query);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

/**
 * Obtener un tipo de horario por ID
 */
function obtenerTipoHorarioPorId($db, $id) {
    $query = "SELECT * FROM tipos_horario WHERE id = $id";
    $result = $db->query($query);
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

/**
 * Agregar un nuevo tipo de horario
 */
function agregarTipoHorario($db, $nombre, $horas_academicas, $horas_atendiendo) {
    $nombre = $db->real_escape_string($nombre);
    $horas_academicas = (int)$horas_academicas;
    $horas_atendiendo = (int)$horas_atendiendo;
    
    $query = "INSERT INTO tipos_horario (nombre, horas_academicas, horas_atendiendo) 
              VALUES ('$nombre', $horas_academicas, $horas_atendiendo)";
    return $db->query($query);
}

/**
 * Actualizar un tipo de horario existente
 */
function actualizarTipoHorario($db, $id, $nombre, $horas_academicas, $horas_atendiendo) {
    $nombre = $db->real_escape_string($nombre);
    $horas_academicas = (int)$horas_academicas;
    $horas_atendiendo = (int)$horas_atendiendo;
    
    $query = "UPDATE tipos_horario SET 
              nombre = '$nombre', 
              horas_academicas = $horas_academicas, 
              horas_atendiendo = $horas_atendiendo 
              WHERE id = $id";
    return $db->query($query);
}

/**
 * Eliminar un tipo de horario
 */
function eliminarTipoHorario($db, $id) {
    $query = "DELETE FROM tipos_horario WHERE id = $id";
    return $db->query($query);
}




//ASIGNACION TIPO HORARIO AL PERSONAL ***********************************************************************

/**
 * Asignar tipo de horario a un usuario
 */
function asignarTipoHorarioUsuario($db, $id_usuario, $id_tipo_horario) {
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
    return $db->query($query);
}

/**
 * Obtener tipos de horario de un usuario
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
 * Eliminar asignación de horario de usuario
 */
function eliminarTipoHorarioUsuario($db, $id_usuario, $id_tipo_horario) {
    $query = "DELETE FROM tipo_horario_personal 
              WHERE id_usuario = $id_usuario AND id_tipo_horario = $id_tipo_horario";
    return $db->query($query);
}

/**
 * Obtener usuarios por tipo de horario
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
 * Obtener texto del tipo de usuario (estético)
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
 * Obtener todas las relaciones horario-personal
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
 * Actualizar tipo de horario de usuario
 */
function actualizarTipoHorarioUsuario($db, $id_relacion, $id_tipo_horario) {
    $query = "UPDATE tipo_horario_personal SET id_tipo_horario = $id_tipo_horario WHERE id = $id_relacion";
    return $db->query($query);
}

/**
 * Eliminar relación por ID
 */
function eliminarTipoHorarioUsuarioPorId($db, $id_relacion) {
    $query = "DELETE FROM tipo_horario_personal WHERE id = $id_relacion";
    return $db->query($query);
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
        'gestion_horario', 'titulos_re_materia'
    ];
    
    // Verificar que el permiso solicitado sea válido
    if (!in_array($pagina, $permisosValidos)) {
        error_log("Permiso no válido: " . $pagina);
        $_SESSION['error'] = "Error de permisos: permiso no válido.";
        header('location: ../usuario/home.php');
        exit();
    }
    
    // Si es super_user, tiene acceso a todo - RETORNAR EXPLÍCITAMENTE
    if (isset($_SESSION['user']['super_user']) && $_SESSION['user']['super_user'] == 1) {
        return; // Cambiado de return true a return;
    }
    
    // Verificar si el permiso existe en la sesión y es igual a 1
    if (!isset($_SESSION['user'][$pagina]) || $_SESSION['user'][$pagina] != 1) {
        // Registrar intento de acceso no autorizado
        error_log("Acceso denegado a " . $pagina . " para el usuario: " . $_SESSION['user']['username']);
        
        // Redirigir a home con mensaje de error
        $_SESSION['error'] = "No tienes permisos para acceder a la página de " . $pagina . ".";
        header('location: ../usuario/home.php');
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
        gestion_horario, titulos_re_materia
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




//GRADUACION ***********************************************************************



// =============================================
// FUNCIONES PARA GRADUACIÓN - SISTEMA COMPLETO
// =============================================

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
    
    return [
        'resultados' => $estudiantes_paginados,
        'total_registros' => $total_registros,
        'total_paginas' => $total_paginas,
        'pagina_actual' => $pagina
    ];
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
}

/**
 * Marcar estudiante como graduado
 */
function marcar_como_graduado($id_usuario) {
    global $db;
    
    $id_usuario = mysqli_real_escape_string($db, $id_usuario);
    
    // Obtener el ID del admin desde la sesión
    $id_admin = obtener_id_admin();
    
    $observaciones = isset($_POST['observaciones']) ? mysqli_real_escape_string($db, $_POST['observaciones']) : '';
    
    // Verificar si ya existe registro
    $check = mysqli_query($db, "SELECT id FROM graduados WHERE id_usuario = '$id_usuario'");
    
    if (mysqli_num_rows($check) > 0) {
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
        $_SESSION['mensaje'] = "Estudiante marcado como graduado exitosamente";
        $_SESSION['tipo_mensaje'] = "success";
        return true;
    } else {
        $_SESSION['mensaje'] = "Error al marcar como graduado: " . mysqli_error($db);
        $_SESSION['tipo_mensaje'] = "error";
        return false;
    }
}

/**
 * Marcar título como entregado
 */
function marcar_titulo_entregado($id_graduado) {
    global $db;
    
    $id_graduado = mysqli_real_escape_string($db, $id_graduado);
    
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
        $_SESSION['mensaje'] = "Título marcado como entregado exitosamente";
        $_SESSION['tipo_mensaje'] = "success";
        return true;
    } else {
        $_SESSION['mensaje'] = "Error al marcar título como entregado: " . mysqli_error($db);
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
        // Si cumple requisitos pero no está graduado
        $botones .= '<button class="btn btn-success btn-sm" onclick="confirmarGraduacion('.$id_usuario.')">
                        <i class="fas fa-graduation-cap"></i> Marcar Graduado
                     </button>';
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
    $query = "SELECT c.id_carrera, c.nombre_carrera 
              FROM carreras c
              INNER JOIN users u ON c.id_carrera = u.carrera
              WHERE u.estudiante = 1 
              AND c.nombre_carrera IS NOT NULL 
              AND c.nombre_carrera != '' 
              GROUP BY c.id_carrera, c.nombre_carrera
              ORDER BY c.nombre_carrera";
    return mysqli_query($db, $query);
}

// =============================================
// FUNCIONES PARA EVALUACIÓN DE GRADOS (TSU Y GRADO COMPLETO)
// =============================================

/**
 * Determinar si un estudiante es apto para el primer título (TSU) o grado completo - ACTUALIZADA
 */
function es_apto_para_grado($estudiante_id) {
    global $db;
    
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
    
    // El resto de la función se mantiene igual...
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
}

/**
 * Verificar si un estudiante tiene una materia aprobada
 */
function tiene_materia_aprobada($estudiante_id, $materia_id, $trayecto) {
    global $db;
    
    $estudiante_id = mysqli_real_escape_string($db, $estudiante_id);
    $materia_id = mysqli_real_escape_string($db, $materia_id);
    $trayecto = mysqli_real_escape_string($db, $trayecto);
    
    // Consulta para verificar nota aprobada
    $campo_trayecto = 'trayecto_' . $trayecto;
    $query_nota = "SELECT $campo_trayecto as nota 
                  FROM notas_definitivas 
                  WHERE id_usuario = '$estudiante_id' 
                  AND id_materia = '$materia_id' 
                  AND $campo_trayecto >= 10  -- Nota mínima para aprobar
                  AND $campo_trayecto IS NOT NULL
                  LIMIT 1";
    
    $result_nota = mysqli_query($db, $query_nota);
    
    if ($result_nota && mysqli_num_rows($result_nota) > 0) {
        $nota_data = mysqli_fetch_assoc($result_nota);
        // Verificar que la nota sea realmente un número y esté aprobada
        return is_numeric($nota_data['nota']) && $nota_data['nota'] >= 10;
    }
    
    return false;
}

/**
 * Verificar requisitos adicionales para el grado
 */
function verificar_requisitos_adicionales($estudiante_id) {
    global $db;
    
    $estudiante_id = mysqli_real_escape_string($db, $estudiante_id);
    
    // Por ahora, asumimos que todos los requisitos adicionales están cumplidos
    // Debes implementar estas verificaciones según las reglas de tu universidad
    return true;
}

/**
 * Función para verificar requisitos de graduación
 */
function cumple_requisitos_graduacion($id_usuario) {
    $info_aptitud = es_apto_para_grado($id_usuario);
    
    // Para la página de graduación, consideramos aptos tanto TSU como grado completo
    return ($info_aptitud['apto_tsu'] || $info_aptitud['apto_grado_completo']);
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


	function contar_pedidos(){
        global $db, $username, $usua, $contar_pedido,
        $pendiente_pedido, $mes_de_pago_actual, $ganancia_bantecom, $id_usua;
        if (minisAd())
        {



if ($porentregar>0) {
  $pendiente_pedido = $porentregar;
} else {
$pendiente_pedido = 0;
}

$pendiente_pedido = $pendiente_pedido;

	// } else {
  //   $contar_pedido .= $entregado .' Ya Entregados <br>';
  //   $pendiente_pedido = "";
	// }
        } else {
//echo 'USUARIO';

$sql = "SELECT
SUM(monto) AS total_ventas,
SUM(IF(MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE()), monto, 0)) AS total_ventas_mes,
COUNT(DISTINCT id) AS cantidad_transacciones,
SUM(IF(MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE()), 1, 0)) AS cantidad_transacciones_mes,
COUNT(DISTINCT id_cliente) AS cantidad_clientes
FROM ventas
WHERE id_usuario = '$id_usua'";

$resultsql = mysqli_query($db, $sql);
$rowsql = mysqli_fetch_assoc($resultsql);

echo "Total ventas: $" . $rowsql['total_ventas'] . "<br>";
echo "Total ventas en el mes: $" . $rowsql['total_ventas_mes'] . "<br>";
echo "Transacciones en el mes: " . $rowsql['cantidad_transacciones_mes'] . "<br>";
echo "Total transacciones: " . $rowsql['cantidad_transacciones'] . "<br>";
echo "Total clientes: " . $rowsql['cantidad_clientes'] . "<br>";
}

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
        $password = md5($password);

        $query = "SELECT * FROM users WHERE (username='$username' OR email='$username') AND password='$password' LIMIT 1";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) { // user found
            $logged_in_user = mysqli_fetch_assoc($results);
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
            // REGISTRAR EN AUDITORÍA - LOGIN FALLIDO
            registrarAuditoria(
                "LOGIN", 
                "users", 
                null, 
                null, 
                ['username' => $username], 
                "Autenticación", 
                "Intento de inicio de sesión fallido"
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

	?>