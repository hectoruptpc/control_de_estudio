<?php
if (isset($limpio)) {

} else {
    if (!isLoggedIn()) {
        $_SESSION['here'] = $_SERVER['REQUEST_URI'];
        $_SESSION['msg'] = $msn_iniciar_sesion;
        header('location: ../login.php');
        die();
    }

    if (isAdmin()) {
        header('location: ../admin/home.php');
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $titulopag; ?></title>
    
    <!-- Bootstrap 4.0 CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    
    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>

<?php if (isLoggedIn()) : ?>

<!-- Navigation simplificada -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <?php echo $logopertenencia; ?>
        </a>
        
        <div class="ml-auto">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php?logout='1'">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <p class="font-weight-bold"><?php echo strtoupper(strtolower($_SESSION['user']['nombre'])); ?></p>
            <hr>
            <?php status_usuario(); ?>
        </div>
    </div>
</div>

<?php else : ?>

<!-- Navigation para usuarios no logueados -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="../index.php">
            <?php echo $logo_web; ?>
        </a>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <p class="font-weight-bold">Bienvenido/a</p>
            <hr>
        </div>
    </div>
</div>

<?php endif; ?>