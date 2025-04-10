<?php
	include('../funciones/functions.php');

	if (!isLoggedIn()) {
		$_SESSION['msg'] = "Debes iniciar sesión primero";
		header('location: ../login.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>SISTEMA DE GESTION</title>


    <?php echo $bootstrap_head; ?>

</head>
<body>

<hr>


<div id="main" class="container-fluid">
  <div class="row">
    <div class="d-none d-sm-block col-md-6">

    <img src="../images/responsive.png" class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}" alt="">
    </div>
    <div class="col-sm-12 col-md-6">
    <h3 class="text-center text-uppercase"> <?php echo ucwords(strtolower($_SESSION['user']['nombre'])); ?></h3>

	<div class="content">
		<!-- notification message -->
		<?php if (isset($_SESSION['success'])) : ?>
			<div class="error success" >
				<h3>
					<?php
						echo $_SESSION['success'];
						unset($_SESSION['success']);
					?>
				</h3>
			</div>
		<?php endif ?>

		<!-- logged in user information -->
<div class="text-center">
			<img src="../images/user_profile.png" align="center" width="60%" class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}" >

			<div class="d-flex justify-content-center">
  <div class="spinner-border text-primary" role="status">
    <span class="sr-only">Loading...</span>
  </div>
</div>

</div>
		<p class="text-center">
				<?php  if (isset($_SESSION['user'])) : ?>
					<b><?php echo $_SESSION['user']['username']; ?></b>

					<small>
						<i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i>
					</small>

				<?php
				echo "<script language='JavaScript'>";
				echo "location = 'index.php'";
				echo "</script>";
				endif ?>
				</p>
			</div>
		</div>
	</div>

    </div>
  </div>




</body>
</html>
