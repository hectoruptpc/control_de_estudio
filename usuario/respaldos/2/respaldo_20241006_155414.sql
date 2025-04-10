-- Respaldo de datos de la tabla 'clientes'
DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cedula` varchar(20) NOT NULL,
  `nombre` varchar(300) NOT NULL,
  `telefono1` varchar(20) NOT NULL,
  `telefono2` varchar(20) DEFAULT NULL,
  `direccion` varchar(300) NOT NULL,
  `ciudad` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cedula` (`cedula`),
  KEY `idusuario` (`idusuario`),
  KEY `ciudad` (`ciudad`),
  KEY `estado` (`estado`),
  CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`idusuario`) REFERENCES `users` (`id`),
  CONSTRAINT `clientes_ibfk_2` FOREIGN KEY (`ciudad`) REFERENCES `ciudades` (`id_ciudad`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('1', 'V-12345678', 'JOSE HERRERA', '04121111111', '0414000001', 'LAS LLAVES', '123', '7', '2', '2023-12-14 11:39:40', '2023-12-18 14:01:55');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('2', 'V-98765432', 'MEDARDO VARGAS', '04141447777', '', 'CUMBOTO 2', '124', '7', '2', '2023-12-11 13:00:02', '');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('3', 'V-123', 'PRUEBA', '04141444444', '04125555555', 'EL CUALQUIER SITIO', '59', '4', '2', '2023-12-17 12:32:06', '');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('47', 'V-1234', 'UN NOMBRE DE PRUEBA', '041214522254', '11214554775', 'UN LUGAR DE LA MANCHA', '4', '2', '2', '2023-12-17 14:11:24', '');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('49', 'V-12345', 'LUISA MARIN', '04141222222', '0412111111', 'OTRO SITIO', '123', '7', '2', '2023-12-10 14:19:38', '2024-02-24 19:41:18');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('50', 'V-11111', 'MARIA A GARCIA', '0414000001', '0412000002', 'CALLE 1 CASA 2 PISO 0', '106', '7', '2', '2024-02-09 14:20:17', '2024-02-25 13:00:58');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('51', 'V-1111188', 'ANA PEREZ', '04141111111', '04121111111', 'URB LAS TABLAS CALLE 4 VEREDA 4 CASA 22', '123', '7', '2', '2024-02-25 09:35:56', '');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('52', 'V-12310', 'PEDRO PEREZ', '4545454545', '45454545454', 'JKHKHKJHK HKLJLKJLJ LJKLJLKJLJ', '106', '7', '2', '2024-02-25 14:36:28', '');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('53', 'V-12320', 'PAULA MILLAN', '45454545454', '4545454545', 'JKJJKHJKHKJ LKJLKJL JKLJLKJL KJLJLK', '148', '9', '2', '2024-02-08 20:26:17', '');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('54', 'V-12330', 'BARBARA HERRERA', '45454545', '45454545', 'KLJK JLKJLKJ LKJLJLK JSSS', '106', '7', '2', '2024-02-25 14:48:16', '');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('55', 'V-12340', 'PAUL ZAZ', '454545454545', '45454545454', 'JKJKJKJk', '182', '11', '2', '2024-02-25 14:50:41', '');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('56', 'V-12350', 'LUZ MENDEZ', '4545454545', '4545454545', 'KJKJKJKJ JKJKJK JKJKJ KJKJKJK', '54', '4', '2', '2024-02-25 15:00:21', '');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('57', 'V-12360', 'ANDRES ZAPATA', '4545555555544', '54556565656', 'JKKJKJK JKJK JKJKJK JKJKJK', '233', '13', '2', '2024-02-25 15:05:36', '');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('58', 'V-12370', 'MARGARET PEREZ', '87878787878', '78787878787', 'KJKJKJK JKJKJ KJKJK JKJKJK', '180', '10', '2', '2024-02-25 15:40:56', '2024-05-31 10:26:42');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('59', 'V-12380', 'ANDREA BURGOS', '1111111', '2222222', 'URB AMARANTO', '106', '7', '2', '2024-01-25 15:45:20', '2024-03-16 09:42:49');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('60', 'V-12390', 'ROSA MARIN', '30303030', '32020202', 'KNKNNM NMNMNM', '108', '7', '2', '2024-01-17 15:49:12', '2024-03-16 13:55:11');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('61', 'V-123000', 'JOSE HERRERA', '04141448515', '04141448515', 'LAS LLAVES', '123', '7', '2', '2024-03-16 10:12:23', '2024-03-16 13:54:07');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('62', 'V-30179788', 'RONALD MARTINEZ', '04143416785', '', 'CALLE 1 VEREDA 7 CASA 8', '123', '7', '2', '2024-06-28 11:31:54', '2024-06-28 11:32:59');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('63', 'V-121412', 'PRUEBA DESDE VENTAS ACT', '04121452222', '04141448515', 'CASA 1 NUMERO 8', '210', '12', '2', '2024-07-05 10:54:55', '2024-07-05 22:22:59');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('64', 'V-12333', 'PRUEBA DOS DESDE VENTAS ', '04141448515', '04121555454', 'CUALQUIER DIRECCION', '108', '7', '2', '2024-07-06 11:57:04', '');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('65', 'V-141414', 'ANA MARIA', '0414141414', '041215144444', 'CUALQUIER DIRECCION', '76', '5', '2', '2024-07-08 21:34:51', '');
INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `telefono1`, `telefono2`, `direccion`, `ciudad`, `estado`, `idusuario`, `fecha_registro`, `fecha_update`) VALUES ('66', 'V-12366', 'LUIS MUJICA', '0414123456', '04121254125', 'URB LAS LLAVES VEREDA K CASA 38', '123', '7', '2', '2024-07-09 19:22:50', '');


-- Respaldo de datos de la tabla 'ventas'
DROP TABLE IF EXISTS `ventas`;
CREATE TABLE `ventas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_control` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `status` text NOT NULL,
  `nota` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

INSERT INTO `ventas` (`id`, `id_control`, `id_usuario`, `id_cliente`, `id_producto`, `cantidad`, `monto`, `status`, `nota`, `fecha`) VALUES ('1', '22', '2', '3', '1', '2000.00', '4.00', 'pendiente', '', '2024-07-07 19:21:23');
INSERT INTO `ventas` (`id`, `id_control`, `id_usuario`, `id_cliente`, `id_producto`, `cantidad`, `monto`, `status`, `nota`, `fecha`) VALUES ('2', '22', '2', '3', '38', '2000.00', '60.00', 'pendiente', '', '2024-07-07 19:21:23');
INSERT INTO `ventas` (`id`, `id_control`, `id_usuario`, `id_cliente`, `id_producto`, `cantidad`, `monto`, `status`, `nota`, `fecha`) VALUES ('3', '23', '2', '3', '1', '4000.00', '8.00', 'pendiente', '', '2024-07-07 21:48:56');
INSERT INTO `ventas` (`id`, `id_control`, `id_usuario`, `id_cliente`, `id_producto`, `cantidad`, `monto`, `status`, `nota`, `fecha`) VALUES ('4', '24', '2', '49', '31', '2000.00', '10.00', 'pendiente', '', '2024-07-07 22:05:57');
INSERT INTO `ventas` (`id`, `id_control`, `id_usuario`, `id_cliente`, `id_producto`, `cantidad`, `monto`, `status`, `nota`, `fecha`) VALUES ('5', '24', '2', '49', '38', '3000.00', '90.00', 'pendiente', '', '2024-07-07 22:05:57');
INSERT INTO `ventas` (`id`, `id_control`, `id_usuario`, `id_cliente`, `id_producto`, `cantidad`, `monto`, `status`, `nota`, `fecha`) VALUES ('6', '25', '2', '3', '38', '1000.00', '30.00', 'pendiente', '', '2024-07-07 22:09:01');
INSERT INTO `ventas` (`id`, `id_control`, `id_usuario`, `id_cliente`, `id_producto`, `cantidad`, `monto`, `status`, `nota`, `fecha`) VALUES ('7', '26', '2', '3', '38', '1000.00', '30.00', 'pendiente', '', '2024-07-07 22:20:26');
INSERT INTO `ventas` (`id`, `id_control`, `id_usuario`, `id_cliente`, `id_producto`, `cantidad`, `monto`, `status`, `nota`, `fecha`) VALUES ('8', '27', '2', '3', '38', '1000.00', '30.00', 'pendiente', '', '2024-07-07 22:23:09');
INSERT INTO `ventas` (`id`, `id_control`, `id_usuario`, `id_cliente`, `id_producto`, `cantidad`, `monto`, `status`, `nota`, `fecha`) VALUES ('9', '28', '2', '3', '38', '1000.00', '30.00', 'pendiente', '', '2024-07-07 22:23:45');
INSERT INTO `ventas` (`id`, `id_control`, `id_usuario`, `id_cliente`, `id_producto`, `cantidad`, `monto`, `status`, `nota`, `fecha`) VALUES ('10', '29', '2', '3', '38', '1000.00', '30.00', 'pendiente', '', '2024-07-07 22:27:20');
INSERT INTO `ventas` (`id`, `id_control`, `id_usuario`, `id_cliente`, `id_producto`, `cantidad`, `monto`, `status`, `nota`, `fecha`) VALUES ('11', '30', '2', '3', '38', '1000.00', '30.00', 'pendiente', '', '2024-06-30 22:31:11');
INSERT INTO `ventas` (`id`, `id_control`, `id_usuario`, `id_cliente`, `id_producto`, `cantidad`, `monto`, `status`, `nota`, `fecha`) VALUES ('12', '31', '2', '49', '38', '1000.00', '30.00', 'pendiente', '', '2024-06-30 22:36:56');
INSERT INTO `ventas` (`id`, `id_control`, `id_usuario`, `id_cliente`, `id_producto`, `cantidad`, `monto`, `status`, `nota`, `fecha`) VALUES ('13', '32', '2', '3', '31', '1000.00', '5.00', 'pendiente', '', '2024-09-20 00:33:45');


-- Respaldo de datos de la tabla 'inventario_componente'
DROP TABLE IF EXISTS `inventario_componente`;
CREATE TABLE `inventario_componente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_control` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_componente` varchar(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `descripcion` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_producto` (`id_producto`),
  KEY `id_control` (`id_control`),
  CONSTRAINT `inventario_componente_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`),
  CONSTRAINT `inventario_componente_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('1', '1', '38', '2', '17', '1000', '1', '2024-05-25 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('2', '1', '38', '2', '138', '10', '1', '2024-05-25 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('3', '1', '38', '2', '204', '2990', '0', '2024-05-25 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('4', '1', '38', '2', '209', '2990', '0', '2024-05-25 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('5', '1', '38', '2', '210', '2990', '0', '2024-05-25 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('6', '1', '38', '2', '211', '500', '0', '2024-05-25 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('7', '2', '1', '2', '204', '2900', '0', '2024-05-25 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('8', '2', '1', '2', '57', '2720', '0', '2024-05-25 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('9', '2', '1', '2', '58', '712', '0', '2024-05-25 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('10', '2', '1', '2', '59', '1500', '0', '2024-05-25 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('11', '1010', '1', '2', '80', '1', '1', '2024-05-25 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('12', '1515', '1', '2', '05', '1', '1', '2024-05-25 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('13', '2020', '1', '2', '15', '1', '1', '2024-05-25 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('30', '1515', '', '2', '20', '30', '1', '2024-07-03 01:16:57');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('31', '1616', '', '2', '80', '20', '1', '2024-07-03 01:20:49');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('32', '1717', '', '2', '80', '100', '1', '2024-07-03 01:24:47');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('33', '1010', '', '2', '80', '100', '1', '2024-07-03 01:42:47');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('34', '10', '', '2', '01', '5000', '1', '2024-07-03 01:49:07');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('35', '1010', '', '2', '01', '250', '1', '2024-07-03 01:49:53');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('36', '2020', '', '2', '110', '3000', '1', '2024-07-03 03:52:14');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('37', '20212', '', '2', '204', '2000', '1', '2024-07-04 13:21:02');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('38', '202020', '', '2', '76', '20', '1', '2024-07-04 13:33:48');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('39', '202020', '', '2', '46', '20', '1', '2024-07-04 13:33:48');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('40', '101010', '', '2', '526', '20', '1', '2024-07-04 13:37:31');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('41', '101010', '', '2', '206', '20', '1', '2024-07-04 13:37:31');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('42', '2020', '', '2', '00', '5000', '1', '2024-07-04 13:42:08');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('43', '2020', '', '2', '526', '10', '1', '2024-07-04 14:20:11');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('44', '101010', '', '2', '130', '30', '1', '2024-07-04 14:23:16');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('45', '2020', '', '2', '210', '5000', '1', '2024-07-09 11:46:10');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('46', '2020', '', '2', '209', '5000', '1', '2024-07-09 11:46:31');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('47', '2020', '', '2', '204', '5000', '1', '2024-07-09 11:47:27');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('48', '2020', '', '2', '138', '5000', '1', '2024-07-09 11:47:27');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('49', '2020', '', '2', '17', '5000', '1', '2024-07-09 11:47:27');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('50', '2020', '', '2', '08', '50000', '1', '2024-07-09 11:48:38');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('51', '2020', '', '2', '00', '5000000', '1', '2024-07-09 12:16:32');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('52', '2020', '', '2', '00', '50000', '1', '2024-07-09 12:17:27');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('53', '5050', '', '2', '03', '2000', '1', '2024-07-09 12:24:03');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('54', '5050', '', '2', '03', '2000', '1', '2024-07-09 12:25:18');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('55', '5050', '', '2', '03', '2000', '1', '2024-07-09 12:25:39');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('56', '8080', '', '2', '00', '80000', '1', '2024-07-09 12:27:59');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('57', '8080', '', '2', '04', '2000', '1', '2024-07-09 12:27:59');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('58', '50505050', '', '2', '00', '150', '1', '2024-09-20 00:31:47');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('59', '50505050', '', '2', '30', '2000', '1', '2024-09-20 00:31:47');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('60', '50505050', '', '2', '518', '1500', '1', '2024-09-20 00:31:47');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('61', '50505050', '', '2', '73', '5000', '1', '2024-09-20 00:31:47');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('62', '50505050', '', '2', '23', '8000', '1', '2024-09-20 00:31:47');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('63', '50505050', '', '2', '34', '2000', '1', '2024-09-20 00:31:47');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('64', '8085555', '', '2', '211', '2000', '1', '2024-09-20 00:32:28');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('65', '9090', '', '2', '204', '3000', '1', '2024-09-20 01:19:27');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('66', '9090', '', '2', '209', '5000', '1', '2024-09-20 01:19:27');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('67', '9090', '', '2', '210', '9000', '1', '2024-09-20 01:19:27');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('68', '33', '38', '2', '17', '100', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('69', '33', '38', '2', '138', '1', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('70', '33', '38', '2', '204', '299', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('71', '33', '38', '2', '209', '299', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('72', '33', '38', '2', '210', '299', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('73', '33', '38', '2', '211', '1', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('74', '34', '38', '2', '17', '100', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('75', '34', '38', '2', '138', '1', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('76', '34', '38', '2', '204', '299', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('77', '34', '38', '2', '209', '299', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('78', '34', '38', '2', '210', '299', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('79', '34', '38', '2', '211', '1', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('80', '889898900', '', '2', '31', '8000', '1', '2024-09-20 11:09:56');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('81', '889898900', '', '2', '32', '8000', '1', '2024-09-20 11:09:56');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('82', '35', '3', '2', '30', '400', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('83', '35', '3', '2', '31', '5', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('84', '35', '3', '2', '32', '5', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('85', '35', '3', '2', '00', '1600', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('86', '35', '3', '2', '08', '6', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('87', '36', '3', '2', '30', '200', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('88', '36', '3', '2', '31', '3', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('89', '36', '3', '2', '32', '3', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('90', '36', '3', '2', '00', '800', '0', '2024-09-20 00:00:00');
INSERT INTO `inventario_componente` (`id`, `id_control`, `id_producto`, `id_usuario`, `id_componente`, `cantidad`, `descripcion`, `fecha`) VALUES ('91', '36', '3', '2', '08', '3', '0', '2024-09-20 00:00:00');


-- Respaldo de datos de la tabla 'inventario_producto_terminado'
DROP TABLE IF EXISTS `inventario_producto_terminado`;
CREATE TABLE `inventario_producto_terminado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_control` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `descripcion` int(11) NOT NULL,
  `fecha` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_producto` (`id_producto`),
  KEY `id_control` (`id_control`),
  CONSTRAINT `inventario_producto_terminado_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inventario_producto_terminado_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('1', '1', '2', '38', '10000', '1', '2024-05-25 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('2', '2', '2', '1', '6000', '1', '2024-05-25 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('3', '3', '2', '38', '50000', '1', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('4', '4', '2', '38', '50000', '1', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('5', '5', '2', '38', '10000', '1', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('6', '6', '2', '38', '10000', '1', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('7', '7', '2', '38', '80000', '1', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('8', '8', '2', '38', '50000', '1', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('9', '9', '2', '38', '50000', '1', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('10', '10', '2', '38', '50000', '1', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('11', '11', '2', '38', '50000', '1', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('12', '12', '2', '38', '80000', '1', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('13', '13', '2', '38', '80000', '1', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('14', '14', '2', '31', '5000', '1', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('15', '15', '2', '38', '80000', '1', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('16', '16', '2', '38', '80000', '1', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('17', '17', '2', '38', '80000', '1', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('18', '18', '2', '38', '80000', '1', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('19', '19', '2', '38', '80000', '0', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('20', '20', '2', '38', '80000', '0', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('21', '21', '2', '38', '80000', '0', '2024-05-26 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('22', '22', '2', '1', '2000', '0', '2024-07-07 19:21:23');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('23', '22', '2', '38', '2000', '0', '2024-07-07 19:21:23');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('24', '23', '2', '1', '4000', '0', '2024-07-07 21:48:56');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('25', '24', '2', '31', '2000', '0', '2024-07-07 22:05:57');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('26', '24', '2', '38', '3000', '0', '2024-07-07 22:05:57');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('27', '25', '2', '38', '1000', '0', '2024-07-07 22:09:01');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('28', '26', '2', '38', '1000', '0', '2024-07-07 22:20:26');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('29', '27', '2', '38', '1000', '0', '2024-07-07 22:23:09');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('30', '28', '2', '38', '1000', '0', '2024-07-07 22:23:45');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('31', '29', '2', '38', '1000', '0', '2024-07-07 22:27:20');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('32', '30', '2', '38', '1000', '0', '2024-07-07 22:31:11');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('33', '31', '2', '38', '1000', '0', '2024-07-07 22:36:56');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('34', '32', '2', '31', '1000', '0', '2024-09-20 00:33:45');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('35', '33', '2', '38', '1000', '1', '2024-09-20 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('36', '34', '2', '38', '1000', '1', '2024-09-20 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('37', '35', '2', '3', '2000', '1', '2024-09-20 00:00:00');
INSERT INTO `inventario_producto_terminado` (`id`, `id_control`, `id_usuario`, `id_producto`, `cantidad`, `descripcion`, `fecha`) VALUES ('38', '36', '2', '3', '1000', '1', '2024-09-20 00:00:00');


