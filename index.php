<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include('funciones/functions.php');

if (!isLoggedIn()) {
    $_SESSION['here'] = $_SERVER['PHP_SELF'];
    $_SESSION['msg'] = "Debes iniciar sesión primero";
    header('location: login.php');
    exit();
} else {
    if (isSuperUser()) {
        header('location: super_user/index.php');
        exit();
    } elseif (isAdmin()) {
        header('location: admin/index.php');
        exit();
    } elseif (isDocente()) {
        header('location: docente/index.php');
        exit();
    } elseif (isEstudiante()) {
        header('location: estudiante/index.php');
        exit();
    } elseif (isUser()) {
        header('location: director_de_carrera/index.php');
        exit();
    } else {
        header('location: login.php');
        exit();
    }
}
?>
