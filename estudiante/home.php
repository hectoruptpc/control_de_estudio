<?php 
    include('../funciones/functions.php');

    if (!isEstudiante()) {
        $where = $_SESSION['here'];
        if (!empty($where)) {
            header("Location: $where");
        } else {
            $_SESSION['msg'] = "Debe iniciar sesión primero";
            header('location: ../login.php');
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Panel del Estudiante</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <style>
        .header {
            background: #003366; /* Azul oscuro para diferenciar del docente */
        }
        button[name=register_btn] {
            background: #003366;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Estudiante</h2>
    </div>
    <div class="content">
        <!-- notification message -->
        <?php if (isset($_SESSION['success'])) : ?>
            <div class="error success">
                <h3>
                    <?php 
                        echo $_SESSION['success']; 
                        unset($_SESSION['success']);
                    ?>
                </h3>
            </div>
        <?php endif ?>

        <!-- logged in user information -->
        <div class="profile_info">
            <img src="../images/estudiante.png"> <!-- Cambiada la imagen por una de estudiante -->

            <div>
                <?php if (isset($_SESSION['user'])) : ?>
                    <strong><?php echo $_SESSION['user']['username']; ?></strong>

                    <small>
                        <i style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i> 
                        <br>
                        <a href="home.php?logout='1'" style="color: red;">Salir</a>
                        &nbsp; <a href="mis_clases.php">Mis Clases</a> <!-- Enlace específico para estudiantes -->
                        &nbsp; <a href="mis_notas.php">Mis Notas</a> <!-- Enlace específico para estudiantes -->
                    </small>

                    <?php 
                    echo "<script language='JavaScript'>"; 
                    echo "location = 'index.php'"; 
                    echo "</script>";
                    ?>
                <?php endif ?>
            </div>
        </div>
    </div>
</body>
</html>