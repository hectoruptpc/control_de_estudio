// EN ESTA PAGINA SE RECUPERARA LA CONTRASEÑA DE FORMA PROVICIONAL



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