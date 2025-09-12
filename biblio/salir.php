<?php
/*==============================================================*/
/*======== CODIGO PARA QUE EL USUARIO SALGO DEL SISTEMA ========*/
/*==============================================================*/

session_start();

session_destroy();

header("location: index.php");



?>