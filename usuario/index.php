<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Acceso denegado";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $titulopag; ?></title>
    <!-- Bootstrap core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="../css/simple-sidebar.css" rel="stylesheet">
</head>

<body>
    <!-- Page Content -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center mt-5">
                <div class="alert alert-danger" role="alert">
                    <h2 class="alert-heading">Acceso denegado</h2>
                    <hr>
                    <p class="mb-0">No tiene permisos para acceder a esta página. Si cree que esto es un error, por favor notifique a la Universidad.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>