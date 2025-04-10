<?php
// REGISTER USER
function register(){
	global $db, $errors;

	// receive all input values from the form
	$username    =  e($_POST['username']);
	$idusuario   =  e($_POST['idusuario']);
	$nombre      =  e($_POST['nombre']);
	$email       =  e($_POST['email']);
	$password_1  =  e($_POST['password_1']);
	$password_2  =  e($_POST['password_2']);

	// form validation: ensure that the form is correctly filled
	if (empty($username)) {
		array_push($errors, "Usuario es Requerido");
	}
	if (empty($email)) {
		array_push($errors, "Email es Requerido");
	}
	if (empty($password_1)) {
		array_push($errors, "Contraseña es requerida");
	}
	if ($password_1 != $password_2) {
		array_push($errors, "Las dos contraseñas no coinciden");
	}

// register user if there are no errors in the form
if (count($errors) == 0) {
	$password = md5($password_1);//encrypt the password before saving in the database

if (isset($_POST['user_type'])) {
	$user_type = e($_POST['user_type']);
	$query = "INSERT INTO users (idusuario, nombre, username, email, user_type, password)
			  VALUES('$idusuario', '$nombre', '$username', '$email', '$user_type', '$password')";
	mysqli_query($db, $query);
	$_SESSION['success']  = "Se ha creado un nuevo usuario de manera satisfactoria..!!";
	header('location: home.php');
}else{
	$query = "INSERT INTO users (username, email, user_type, password)
			  VALUES('$username', '$email', 'user', '$password')";
	mysqli_query($db, $query);

	// get id of the created user
	$logged_in_user_id = mysqli_insert_id($db);

	$_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
	$_SESSION['success']  = "Usted esta conectado";
	header('location: index.php');
}
}

}


?>
