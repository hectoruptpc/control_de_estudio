<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

	include('funciones/functions.php');

	if (!isLoggedIn()) {
        $_SESSION['here'] = $_SERVER['PHP_SELF'];
		$_SESSION['msg'] = "Debes iniciar sesión primero";
        header('location: login.php');
        die();
	} else {
        if (isAdmin()) {
            header('location: admin/index.php');
        } else {
            header('location: usuario/index.php');
        }
    }
?>
