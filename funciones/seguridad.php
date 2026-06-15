<?php
// funciones/seguridad.php - Módulo de ciberseguridad (CORREGIDO)
// NO iniciar sesión aquí, ya se inicia en el archivo que lo llama

class Seguridad {
    private $db;
    private $ip;
    private $user_agent;
    
    public function __construct($db_connection) {
        $this->db = $db_connection;
        $this->ip = $this->getRealIP();
        $this->user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'desconocido';
    }
    
    // Obtener IP real (incluso detrás de proxies)
    private function getRealIP() {
        $headers = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                return trim($ips[0]);
            }
        }
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    // ==============================================
    // 1. CONTROL DE ACCESO AL SISTEMA (cerrar/abrir)
    // ==============================================
    public function sistemaEstaActivo() {
        return $this->obtenerConfiguracion('sistema_activo', '1') == '1';
    }
    
    public function sistemaEnMantenimiento() {
        return $this->obtenerConfiguracion('modo_mantenimiento', '0') == '1';
    }
    
    // Verificar si el usuario ADMIN puede acceder al panel
    public function verificarAccesoAdmin() {
        if (!isset($_SESSION['user']['super_user']) || $_SESSION['user']['super_user'] != 1) {
            header('Location: login.php');
            exit;
        }
    }
    
    // ==============================================
    // 2. VALIDACIÓN DE TOKEN (detección de alteración)
    // ==============================================
    public function validarToken($token) {
        // Verificar que el token tenga formato hexadecimal (64 caracteres)
        if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
            $this->registrarTokenInvalido($token);
            return false;
        }
        
        // Buscar token en BD
        $sql = "SELECT * FROM password_resets WHERE token = ? AND usado = 0 AND expira > NOW()";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) == 0) {
            $this->registrarTokenInvalido($token);
            return false;
        }
        
        return mysqli_fetch_assoc($result);
    }
    
    private function registrarTokenInvalido($token) {
        $sql = "INSERT INTO seguridad_tokens_invalidos (token_recibido, ip, user_agent) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $token, $this->ip, $this->user_agent);
        mysqli_stmt_execute($stmt);
    }
    
    // ==============================================
    // 3. CONTROL DE INTENTOS (bloqueos progresivos)
    // ==============================================
    public function verificarIntentos($email = null, $tipo = 'recuperar') {
        // Verificar bloqueo por IP
        $bloqueo_ip = $this->estaBloqueado($this->ip, null, $tipo);
        if ($bloqueo_ip) {
            return ['permitido' => false, 'mensaje' => "IP bloqueada hasta: " . $bloqueo_ip, 'bloqueado_hasta' => $bloqueo_ip];
        }
        
        // Verificar bloqueo por email (si se proporcionó)
        if ($email) {
            $bloqueo_email = $this->estaBloqueado(null, $email, $tipo);
            if ($bloqueo_email) {
                return ['permitido' => false, 'mensaje' => "Esta cuenta está bloqueada temporalmente", 'bloqueado_hasta' => $bloqueo_email];
            }
        }
        
        return ['permitido' => true];
    }
    
    private function estaBloqueado($ip = null, $email = null, $tipo = null) {
        $sql = "SELECT desbloqueo_en FROM seguridad_bloqueos WHERE activo = 1 AND desbloqueo_en > NOW()";
        if ($ip) {
            $sql .= " AND ip = '$ip'";
        }
        if ($email) {
            $sql .= " AND email = '$email'";
        }
        if ($tipo) {
            $sql .= " AND motivo LIKE '%$tipo%'";
        }
        $sql .= " ORDER BY desbloqueo_en DESC LIMIT 1";
        
        $result = mysqli_query($this->db, $sql);
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['desbloqueo_en'];
        }
        return false;
    }
    
    public function registrarIntentoFallido($email = null, $tipo = 'recuperar') {
        // Registrar intento
        $sql = "INSERT INTO seguridad_intentos (ip, email, tipo, user_agent) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $this->ip, $email, $tipo, $this->user_agent);
        mysqli_stmt_execute($stmt);
        
        // Contar intentos en la última hora
        $limite = $this->obtenerConfiguracion('limite_recuperar_por_hora', 3);
        $intentos = $this->contarIntentosRecientes($email, $tipo);
        
        // Determinar tiempo de bloqueo
        $tiempo_bloqueo = $this->calcularTiempoBloqueo($email, $intentos);
        
        if ($intentos >= $limite && $tiempo_bloqueo) {
            $this->crearBloqueo($email, $tipo, $tiempo_bloqueo, $intentos);
            return $tiempo_bloqueo;
        }
        
        return false;
    }
    
    private function contarIntentosRecientes($email = null, $tipo = 'recuperar') {
        $sql = "SELECT COUNT(*) as total FROM seguridad_intentos 
                WHERE tipo = ? AND fecha > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        $params = [$tipo];
        $types = "s";
        
        if ($email) {
            $sql .= " AND email = ?";
            $params[] = $email;
            $types .= "s";
        } else {
            $sql .= " AND ip = ?";
            $params[] = $this->ip;
            $types .= "s";
        }
        
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        return $row['total'];
    }
    
    private function calcularTiempoBloqueo($email, $intentos) {
        $primera_hora = $this->obtenerConfiguracion('limite_bloqueo_horas', 1);
        $segunda_hora = $this->obtenerConfiguracion('limite_bloqueo_incremento', 24);
        
        // Si es la primera vez que excede (3-5 intentos) -> 1 hora
        if ($intentos <= 5) {
            return $primera_hora;
        }
        // Si sigue insistiendo (6+ intentos) -> 24 horas
        else {
            return $segunda_hora;
        }
    }
    
    private function crearBloqueo($email, $tipo, $horas, $intentos) {
        $desbloqueo = date('Y-m-d H:i:s', strtotime("+$horas hours"));
        $motivo = $tipo . '_fallido';
        
        // Desactivar bloqueos anteriores
        $sql = "UPDATE seguridad_bloqueos SET activo = 0 WHERE email = ? AND motivo = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $email, $motivo);
        mysqli_stmt_execute($stmt);
        
        // Crear nuevo bloqueo
        $sql = "INSERT INTO seguridad_bloqueos (ip, email, motivo, desbloqueo_en, intentos) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $this->ip, $email, $motivo, $desbloqueo, $intentos);
        mysqli_stmt_execute($stmt);
    }
    
    // ==============================================
    // 4. CONTROL RPS (Requests Per Second)
    // ==============================================
    public function verificarRPS($endpoint) {
        // Limpiar registros antiguos
        $sql = "DELETE FROM seguridad_rps WHERE fecha < DATE_SUB(NOW(), INTERVAL 10 SECOND)";
        mysqli_query($this->db, $sql);
        
        // Contar peticiones de esta IP en los últimos 10 segundos
        $sql = "SELECT COUNT(*) as total FROM seguridad_rps WHERE ip = ? AND endpoint = ? AND fecha > DATE_SUB(NOW(), INTERVAL 10 SECOND)";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $this->ip, $endpoint);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        $limite_individual = $this->obtenerConfiguracion('limite_rps_10seg', 10);
        
        if ($row['total'] >= $limite_individual) {
            return ['permitido' => false, 'mensaje' => "Demasiadas peticiones. Espera unos segundos."];
        }
        
        // ==============================================
        // VERIFICACIÓN GLOBAL (10% de la tabla users)
        // ==============================================
        $total_usuarios = $this->obtenerConfiguracion('total_usuarios', 100);
        $porcentaje = $this->obtenerConfiguracion('limite_rps_global_porcentaje', 10);
        $limite_global = ceil($total_usuarios * ($porcentaje / 100));
        
        // Contar peticiones GLOBALES en los últimos 10 segundos
        $sql = "SELECT COUNT(*) as total FROM seguridad_rps WHERE endpoint = ? AND fecha > DATE_SUB(NOW(), INTERVAL 10 SECOND)";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $endpoint);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row_global = mysqli_fetch_assoc($result);
        
        if ($row_global['total'] >= $limite_global) {
            return ['permitido' => false, 'mensaje' => "El sistema está congestionado. Intenta más tarde."];
        }
        
        // Registrar esta petición
        $sql = "INSERT INTO seguridad_rps (ip, endpoint) VALUES (?, ?)";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $this->ip, $endpoint);
        mysqli_stmt_execute($stmt);
        
        return ['permitido' => true];
    }
    
    // ==============================================
    // 5. FUNCIONES DE CONFIGURACIÓN (PÚBLICAS)
    // ==============================================
    public function obtenerConfiguracion($clave, $default = null) {
        $sql = "SELECT valor FROM seguridad_sistema WHERE clave = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $clave);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['valor'];
        }
        return $default;
    }
    
    public function actualizarConfiguracion($clave, $valor) {
        $sql = "UPDATE seguridad_sistema SET valor = ? WHERE clave = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $valor, $clave);
        return mysqli_stmt_execute($stmt);
    }
    
    // ==============================================
    // 6. ESTADÍSTICAS PARA EL PANEL
    // ==============================================
    public function obtenerEstadisticas() {
        $stats = [];
        
        // Intentos hoy
        $sql = "SELECT COUNT(*) as total FROM seguridad_intentos WHERE DATE(fecha) = CURDATE()";
        $result = mysqli_query($this->db, $sql);
        $stats['intentos_hoy'] = mysqli_fetch_assoc($result)['total'];
        
        // Bloqueos activos
        $sql = "SELECT COUNT(*) as total FROM seguridad_bloqueos WHERE activo = 1 AND desbloqueo_en > NOW()";
        $result = mysqli_query($this->db, $sql);
        $stats['bloqueos_activos'] = mysqli_fetch_assoc($result)['total'];
        
        // Tokens inválidos (posibles ataques)
        $sql = "SELECT COUNT(*) as total FROM seguridad_tokens_invalidos WHERE DATE(fecha) = CURDATE()";
        $result = mysqli_query($this->db, $sql);
        $stats['tokens_invalidos_hoy'] = mysqli_fetch_assoc($result)['total'];
        
        // RPS actual
        $sql = "SELECT COUNT(*) as total FROM seguridad_rps WHERE fecha > DATE_SUB(NOW(), INTERVAL 10 SECOND)";
        $result = mysqli_query($this->db, $sql);
        $stats['rps_actual'] = mysqli_fetch_assoc($result)['total'];
        
        // Intentos por IP (top 5)
        $sql = "SELECT ip, COUNT(*) as total FROM seguridad_intentos WHERE fecha > DATE_SUB(NOW(), INTERVAL 1 HOUR) GROUP BY ip ORDER BY total DESC LIMIT 5";
        $stats['top_ips'] = mysqli_query($this->db, $sql);
        
        return $stats;
    }


    // ==============================================
    // 7. CONTROL DE SISTEMA COMPLETO (Cierre total)
    // ==============================================
    public function sistemaCompletoActivo() {
        return $this->obtenerConfiguracion('sistema_completo_activo', '1') == '1';
    }
    
    public function cerrarSistemaCompleto($razon = '', $usuario = '') {
        $this->actualizarConfiguracion('sistema_completo_activo', '0');
        $this->actualizarConfiguracion('razon_cierre', $razon);
        $this->actualizarConfiguracion('ultimo_cierre_por', $usuario);
        $this->actualizarConfiguracion('fecha_cierre', date('Y-m-d H:i:s'));
    }
    
    public function abrirSistemaCompleto() {
        $this->actualizarConfiguracion('sistema_completo_activo', '1');
        $this->actualizarConfiguracion('razon_cierre', '');
    }
    
    public function verificarSistemaAbierto() {
        if (!$this->sistemaCompletoActivo()) {
            $razon = $this->obtenerConfiguracion('razon_cierre', 'Mantenimiento del sistema');
            $mensaje = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <title>Sistema Temporalmente Cerrado</title>
                <style>
                    body {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                        padding: 20px;
                    }
                    .closed-container {
                        background: white;
                        border-radius: 20px;
                        padding: 40px;
                        max-width: 500px;
                        text-align: center;
                        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                    }
                    .closed-container h1 {
                        color: #dc3545;
                        font-size: 60px;
                        margin-bottom: 20px;
                    }
                    .closed-container h2 {
                        color: #333;
                        margin-bottom: 20px;
                    }
                    .closed-container p {
                        color: #666;
                        margin-bottom: 20px;
                        line-height: 1.6;
                    }
                    .closed-container .reason {
                        background: #f8f9fa;
                        padding: 15px;
                        border-radius: 10px;
                        margin: 20px 0;
                        border-left: 4px solid #dc3545;
                        text-align: left;
                    }
                    .btn {
                        display: inline-block;
                        padding: 12px 30px;
                        background: #007bff;
                        color: white;
                        text-decoration: none;
                        border-radius: 8px;
                        transition: all 0.3s;
                    }
                    .btn:hover {
                        background: #0056b3;
                        transform: translateY(-2px);
                    }
                </style>
            </head>
            <body>
                <div class='closed-container'>
                    <h1>🔒</h1>
                    <h2>Sistema Temporalmente Cerrado</h2>
                    <p>El sistema de Control de Estudios se encuentra actualmente cerrado por mantenimiento o razones administrativas.</p>
                    <div class='reason'>
                        <strong>📌 Motivo:</strong><br>
                        " . htmlspecialchars($razon) . "
                    </div>
                    <p><small>Por favor, intenta más tarde. Disculpa las molestias.</small></p>
                    <a href='javascript:history.back()' class='btn'>← Intentar más tarde</a>
                </div>
            </body>
            </html>";
            die($mensaje);
        }
        return true;
    }





}







// NO instanciar aquí, se instancia en el archivo que lo necesita
// La instancia se crea en recuperar_password.php y en otros archivos
?>