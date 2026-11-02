<?php
// Archivo de super_user para gestión de evaluaciones especiales
require_once __DIR__ . '/../funciones/functions.php';

cargarPermisosUsuario();
verificarPermiso('super_user');

require_once __DIR__ . '/../admin/gestion_evaluaciones_especiales.php';
