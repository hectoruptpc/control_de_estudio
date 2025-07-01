<?php
include('../funciones/functions.php');

// Verificación de acceso simplificada con la nueva función
verifyProfileAccess();

// Verificar redirección guardada
if (isset($_SESSION['here']) && !empty($_SESSION['here'])) {
    header("Location: " . $_SESSION['here']);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Administración del Sistema - Super Usuario</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header {
            background: #343a40; /* Gris oscuro para super_user */
            color: white;
            position: relative;
        }
        button[name=register_btn] {
            background: #343a40;
            color: white;
        }
        .profile-switcher {
            position: absolute;
            right: 20px;
            top: 15px;
        }
        .profile-switcher a {
            background: #495057;
            color: white;
            border: none;
        }
        .profile_info {
            background: #e9ecef;
            border: 1px solid #dee2e6;
        }
        .content {
            background: white;
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .error.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Super Usuario</h2>
        <?php if (count($_SESSION['user']['available_profiles'] ?? []) > 1): ?>
        <div class="profile-switcher">
            <a href="../profile_selector.php" class="btn btn-dark btn-sm">Cambiar perfil</a>
        </div>
        <?php endif; ?>
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
            <img src="../images/admin_profile.png">

            <div>
                <?php if (isset($_SESSION['user'])) : ?>
                    <strong><?php echo $_SESSION['user']['username']; ?></strong>

                    <small>
                        <i style="color: #6c757d;">(<?php echo ucfirst($_SESSION['current_profile']); ?>)</i> 
                        <br>
                    </small>

                    <script language='JavaScript'>
                        // Redirección a index.php después de mostrar el mensaje
                        setTimeout(function() {
                            window.location = 'index.php';
                        }, 1000);
                    </script>
                <?php endif ?>
            </div>
        </div>
    </div>
</body>
</html>