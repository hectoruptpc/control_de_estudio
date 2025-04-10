<?php


// call the register() function if register_btn is clicked
if (isset($_POST['register_btn'])) {
	register();
}

if (isset($_POST['pedido_btn'])) {
	pedido();
}

if (isset($_POST['registrar_recarga_btn'])) {
	registrar_recarga();
}

if (isset($_POST['editar_desde_usuario_btn'])) {
	editar_desde_usuario();
}

if (isset($_POST['borrar_usuario_btn'])) {
	borrar_usuario();
}

if (isset($_POST['agregar_usuario_btn'])) {
	agregar_usuario();
}

if (isset($_POST['pago_mensualidad_btn'])) {
	generar_pago_mensualidad();
}

if (isset($_POST['pago_mensualidad_operadoras_btn'])) {
	generar_pago_mensualidad_operadora();
}

if (isset($_POST['pago_billetera_btn'])) {
	generar_pago_billetera();
}

if (isset($_POST['editar_desde_admin_btn'])) {
	guardar_editar_usuario();
}

if (isset($_REQUEST['aprobar_pago_btn'])) {
	aprobar_pago_mes();
}

if (isset($_POST['rechazar_pago_btn'])) {
	rechazar_pagos();
}

if (isset($_REQUEST['procesar_rechazo_de_pagos_btn'])) {
	procesar_rechazo_de_pagos();
}

if (isset($_REQUEST['activar_desactivar_comentario_btn'])) {
	activar_desactivar_comentario();
}

if (isset($_POST['nuevo_contenido_btn'])) {
	nuevo_contenido();
}

if (isset($_POST['editar_contenido_btn'])) {
	ejecutar_editar_contenido();
}

if (isset($_POST['editar_mensajeria_btn'])) {
	ejecutar_editar_mensajeria();
}

if (isset($_POST['enviar_msn_btn'])) {
	enviar_msn();
}

if (isset($_POST['aprobar_pago_pedido_btn'])) {
	aprobar_pago_pedido();
}

if (isset($_POST['nuevo_mensaje_btn'])) {
	nuevo_mensaje();
}

if (isset($_POST['activar_bloquear_btn'])) {
	activar_bloquear_usuario();
}

if (isset($_POST['procesar_bloqueo_btn'])) {
	procesar_bloqueo();
}

 if (isset($_POST['confirmaciones_btn'])) {
    confirmaciones();
 }

 if (isset($_POST['entregar_pedido_btn'])) {
    entregar_pedido();
}

 if (isset($_POST['crear_password_btn'])) {
    crear_password();
}



// call the login() function if register_btn is clicked
if (isset($_POST['login_btn'])) {
	login();
}

if (isset($_POST['enviar_comentario_btn'])) {
    procesar_enviar_comentario();
}
 ?>
