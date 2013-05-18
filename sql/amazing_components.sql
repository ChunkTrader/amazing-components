-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-05-2013 a las 17:54:49
-- Versión del servidor: 5.5.27
-- Versión de PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `amazing_components`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `descripcion` char(80) DEFAULT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=67 ;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `parent_id`, `descripcion`, `activa`) VALUES
(1, 'Componentes', NULL, '', 1),
(2, 'Periféricos', NULL, '', 1),
(3, 'Redes', NULL, '', 1),
(4, 'Cables', NULL, '', 1),
(5, 'Smartphones', NULL, '', 1),
(6, 'Portatiles', NULL, '', 1),
(7, 'Placas Base', 1, '', 0),
(8, 'Monitores', 2, '', 1),
(9, 'Teclados', 2, '', 1),
(10, 'Impresoras', 2, '', 1),
(11, 'Procesadores', 1, '', 1),
(12, 'Audio/Foto/Vídeo', NULL, '', 1),
(13, 'Tablets/E-books', NULL, '', 1),
(14, 'Consolas', NULL, '', 1),
(15, 'Consumibles', NULL, '', 1),
(16, 'Discos Duros', 1, '', 1),
(17, 'Tarjetas Gráficas', 1, NULL, 0),
(18, 'Memoria RAM', 1, '', 1),
(19, 'Grabadoras DVD/Blu Ray', 1, '', 1),
(20, 'Disqueteras', 1, '', 1),
(21, 'Multilectores', 1, '', 1),
(22, 'Tarjetas de Sonido', 1, '', 1),
(23, 'Torres/Cajas/Carcasas', 1, NULL, 1),
(24, 'Ventilación', 1, '', 1),
(25, 'Fuentes de Alimentación', 1, '', 1),
(26, 'Frontales/Multiconectores', 1, '', 1),
(27, 'Modding', 1, '', 1),
(28, 'Edición/Captura Vídeo', 1, '', 1),
(29, 'Cables Internos PC', 1, NULL, 1),
(30, 'Conectividad', 1, '', 1),
(31, 'Carcasas Disco Externas', 1, '', 1),
(32, 'Carcasas Multimedia', 1, NULL, 1),
(33, 'Multifunciones', 2, '', 1),
(34, 'Altavoces', 2, '', 1),
(35, 'Ratones', 2, '', 1),
(36, 'Webcam/Cámaras Web', 2, '', 1),
(37, 'Gamepads/Joysticks', 2, '', 1),
(38, 'Auriculares', 2, '', 1),
(39, 'Alfombrillas', 2, '', 1),
(40, 'Tabletas Digitales', 2, '', 1),
(41, 'Sais', 2, '', 1),
(42, 'Software', 2, '', 1),
(43, 'Escanners', 2, '', 1),
(44, 'TPV/Punto de Venta', 2, '', 1),
(45, 'Gadgets USB', 2, '', 1),
(46, 'Hub USB', 2, '', 1),
(47, 'Micrófonos', 2, '', 1),
(48, 'Antenas', 3, '', 1),
(49, 'Routers', 3, '', 1),
(50, 'Adaptadores', 4, '', 1),
(51, 'Cables de Red', 4, '', 1),
(52, 'Portatiles', 6, '', 1),
(53, 'Netbooks', 6, '', 1),
(54, 'Ultrabooks', 6, '', 1),
(55, 'Reproductores MP3/MP4', 12, NULL, 1),
(57, 'TabletPC', 13, '', 1),
(58, 'E-books', 13, '', 1),
(59, 'Smartphones', 5, '', 1),
(60, 'iPhone', 5, '', 1),
(61, 'Sony PS3', 14, '', 1),
(62, 'XBOX 360', 14, '', 1),
(63, 'CDs/DVDs', 15, '', 1),
(64, 'Tinta HP', 15, '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_usuarios`
--

CREATE TABLE IF NOT EXISTS `datos_usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(60) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `poblacion` varchar(30) NOT NULL,
  `cp` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `datos_usuarios`
--

INSERT INTO `datos_usuarios` (`id`, `nombre`, `apellido`, `direccion`, `poblacion`, `cp`) VALUES
(1, 'Johan', 'Esburgo Town', 'C/ Mesita, 18', 'Igualada', 80811),
(4, 'Julian', 'Martinez', 'C/ Girasoles, 23', 'Martorell', 12345),
(21, 'Juan', 'Ruiz', 'Plaza de los tomates, 27', 'Rubí', 8001),
(23, 'Luis', 'Pujol', 'Avd. Constitución 213', 'Sant Joan', 90122);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fabricantes`
--

CREATE TABLE IF NOT EXISTS `fabricantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` char(40) NOT NULL,
  `descripcion` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Volcado de datos para la tabla `fabricantes`
--

INSERT INTO `fabricantes` (`id`, `nombre`, `descripcion`) VALUES
(1, 'OEM', 'Genérico'),
(2, 'Philips', NULL),
(3, 'Samsung', NULL),
(4, 'Nikon', NULL),
(5, 'Dell', NULL),
(6, 'Sony', NULL),
(7, 'HP', NULL),
(8, 'Flypad', NULL),
(9, 'Leotec', NULL),
(10, 'Archos', NULL),
(11, 'Acer', NULL),
(12, 'Microsoft', NULL),
(13, 'Unotec', NULL),
(14, 'LG', NULL),
(15, 'Genius', NULL),
(16, 'Salicru', NULL),
(17, 'Blusens', NULL),
(18, 'Logitech', NULL),
(19, 'Sandisk', NULL),
(20, 'B-Move', NULL),
(21, 'Nintendo', NULL),
(22, 'Epson', NULL),
(23, 'Brother', NULL),
(24, 'Canon', NULL),
(25, 'Panasonic', NULL),
(26, 'AMD', NULL),
(27, 'Intel', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `galeria_productos`
--

CREATE TABLE IF NOT EXISTS `galeria_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `imagen` char(60) NOT NULL,
  `principal` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `producto_id` (`producto_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=128 ;

--
-- Volcado de datos para la tabla `galeria_productos`
--

INSERT INTO `galeria_productos` (`id`, `producto_id`, `imagen`, `principal`) VALUES
(1, 1, 'Logitech_Wireless_Keyboard_K340_Teclad_51912391ce915', 1),
(2, 1, 'Logitech_Wireless_Keyboard_K340_Teclad_51912398c3f0b', 0),
(3, 1, 'Logitech_Wireless_Keyboard_K340_Teclad_519123a0868d4', 0),
(4, 2, 'Samsung_UE40F6500_40_LED_3D_Televisi_oacute_51912520ba45b', 1),
(5, 2, 'Samsung_UE40F6500_40_LED_3D_Televisi_oacute_5191252746bf0', 0),
(6, 2, 'Samsung_UE40F6500_40_LED_3D_Televisi_oacute_5191252d9bd60', 0),
(7, 2, 'Samsung_UE40F6500_40_LED_3D_Televisi_oacute_519125356c649', 0),
(8, 3, 'Brigmton_BTPC_1011_DC_10_1_Negro_5191290b877d7', 1),
(9, 4, 'Samsung_Galaxy_S4_I9505_Negro_Libre_519129af14507', 1),
(10, 4, 'Samsung_Galaxy_S4_I9505_Negro_Libre_519129b78369f', 0),
(11, 4, 'Samsung_Galaxy_S4_I9505_Negro_Libre_519129bdc073d', 0),
(12, 4, 'Samsung_Galaxy_S4_I9505_Negro_Libre_519129c450462', 0),
(14, 4, 'Samsung_Galaxy_S4_I9505_Negro_Libre_519129d549929', 0),
(15, 4, 'Samsung_Galaxy_S4_I9505_Negro_Libre_519129dc0bc57', 0),
(16, 4, 'Samsung_Galaxy_S4_I9505_Negro_Libre_519129e3c161d', 0),
(17, 5, 'Lenovo_Essential_B590_i5_3210_4GB_500GB_15_6_51912a6a9a870', 1),
(18, 5, 'Lenovo_Essential_B590_i5_3210_4GB_500GB_15_6_51912a7091254', 0),
(19, 5, 'Lenovo_Essential_B590_i5_3210_4GB_500GB_15_6_51912a7bf2dd7', 0),
(20, 5, 'Lenovo_Essential_B590_i5_3210_4GB_500GB_15_6_51912a8278815', 0),
(24, 6, '520G_15_6_51912dc733d02', 0),
(25, 6, '520G_15_6_51912dcdb9dc5', 0),
(26, 6, '520G_15_6_51912dd3e0c57', 1),
(27, 6, '520G_15_6_51912dda4051b', 0),
(28, 7, '_51912e63bea53', 1),
(29, 7, '_51912e69dbbbd', 0),
(30, 7, '_51912e7070abd', 0),
(31, 8, '_5191303978d37', 1),
(32, 8, '_51913040b453b', 0),
(33, 9, '_519130af571f7', 1),
(34, 9, '_519130b7adfcf', 0),
(35, 9, '_519130bdde2c5', 0),
(36, 10, '_5191312730c21', 1),
(37, 10, '_5191312d3b3bc', 0),
(38, 10, '_5191313455c34', 0),
(39, 11, '_519132350a1d7', 1),
(40, 11, '_5191323b01fd6', 0),
(41, 11, '_51913240bb442', 0),
(42, 11, '_519132477704f', 0),
(43, 13, 'ndows_Negr_519133a1c9c40', 0),
(44, 13, 'ndows_Negr_519133a7d21cf', 1),
(45, 14, '_519134015cf25', 1),
(46, 14, '_51913406cf2ef', 0),
(47, 14, '_5191340cc230f', 0),
(48, 14, '_519134125687d', 0),
(49, 16, '_51913484d1407', 0),
(50, 16, '_5191348b44ad1', 1),
(51, 16, '_51913493464e7', 0),
(52, 17, '_51913509e960a', 1),
(53, 17, '_5191350f7321c', 0),
(54, 17, '_51913515857bf', 0),
(55, 17, '_5191351f9fc83', 0),
(56, 18, '_5191357f4dacc', 1),
(57, 19, '_519135d7028b3', 1),
(58, 19, '_519135dc6ce60', 0),
(59, 19, '_519135e1a448d', 0),
(60, 20, '_51913644d029b', 1),
(61, 20, '_5191364a98e01', 0),
(62, 20, '_5191365222929', 0),
(63, 21, '_519136c34f9c5', 1),
(64, 21, '_519136c98f921', 0),
(65, 22, '_519137841c186', 1),
(66, 22, '_51913789778f2', 0),
(67, 22, '_5191378f330d9', 0),
(68, 23, '_5191463cb12d6', 1),
(69, 23, '_5191464ef25f1', 0),
(70, 25, '_5191479a0177c', 1),
(71, 25, '_519147a2033c5', 0),
(72, 26, '_519147ee32288', 1),
(73, 26, '_519147f6268f8', 0),
(74, 26, '_519147ff6c3f9', 0),
(75, 27, '_519148572c250', 1),
(76, 27, '_5191485cd2028', 0),
(77, 27, '_51914862e8387', 0),
(78, 29, '_51914943eb75a', 1),
(79, 29, '_5191494c3d924', 0),
(80, 28, '_519149640dd57', 1),
(81, 30, '_51914a2716723', 1),
(82, 31, '_51914abb0d7d9', 1),
(83, 31, '_51914ad6826e7', 0),
(84, 31, '_51914adc950de', 0),
(85, 32, '_51914b837326c', 1),
(86, 32, '_51914b8bc580e', 0),
(87, 32, '_51914b924da8e', 0),
(88, 32, '_51914b99d8cc4', 0),
(89, 33, '_XBOX_PS3_PC_51914c741a76f', 1),
(90, 34, '_51914cb8dcadc', 1),
(91, 35, '_51914d6198608', 1),
(92, 36, '_51914db22784b', 1),
(93, 37, '_51914e07b05b7', 1),
(94, 38, '_51914e490070f', 1),
(95, 38, '_51914e4f5f3ef', 0),
(96, 40, '_51914f217882a', 1),
(97, 42, '_51914f8cbf40f', 1),
(98, 41, '_51914fa28841b', 1),
(99, 43, '_5191501b3f465', 1),
(100, 44, '_519150584c3ce', 1),
(101, 44, '_5191505fd3c8e', 0),
(102, 44, '_51915066d1e34', 0),
(103, 45, '_519150b265690', 1),
(104, 46, 'DR5_519151c2c741b', 1),
(105, 46, 'DR5_519151cab3b05', 0),
(106, 46, 'DR5_519151d1ec99d', 0),
(107, 47, '_5191545c04984', 1),
(108, 47, '_51915476d4c58', 0),
(109, 48, 'GB_CL10_519154ca2c363', 1),
(110, 49, 'GB_CL9_5191550f5f2e9', 1),
(111, 50, '_5191556e69564', 1),
(112, 50, '_519155769bcd0', 0),
(113, 50, '_5191557d154b6', 0),
(114, 51, '_519155ea32cf1', 1),
(115, 51, '_519155f448363', 0),
(116, 51, '_519155fb4bb9b', 0),
(117, 52, '5_51915680355bd', 1),
(118, 53, '_GTX_680_17_3_5191573f391d7', 1),
(119, 53, '_GTX_680_17_3_5191574bcc948', 0),
(120, 53, '_GTX_680_17_3_51915753ec4cb', 0),
(121, 53, '_GTX_680_17_3_5191575c9e990', 0),
(122, 53, '_GTX_680_17_3_519157670a9d0', 0),
(123, 55, '_519159c587fec', 1),
(124, 55, '_519159cc105b6', 0),
(125, 54, '_519159ed8fb81', 1),
(126, 56, '_51915bc97bb16', 1),
(127, 57, '_51915c2a4e5a4', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `linea_pedido`
--

CREATE TABLE IF NOT EXISTS `linea_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `producto_id` (`producto_id`),
  KEY `pedido_id` (`pedido_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ofertas`
--

CREATE TABLE IF NOT EXISTS `ofertas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `precio_anterior` double NOT NULL,
  `activa` tinyint(1) DEFAULT '0',
  `precio_oferta` double DEFAULT NULL,
  `slideshow_url` varchar(60) DEFAULT NULL,
  `slideshow_activo` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `producto_id` (`producto_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Volcado de datos para la tabla `ofertas`
--

INSERT INTO `ofertas` (`id`, `producto_id`, `precio_anterior`, `activa`, `precio_oferta`, `slideshow_url`, `slideshow_activo`) VALUES
(1, 7, 221, 1, 199, 'Samsung_Galaxy_S3_Mini_Azul_Libre', 1),
(2, 32, 56, 1, 41, 'Razer_Nostromo_Gaming_Keyboard', 1),
(3, 46, 430, 1, 380, 'Gigabyte_Radeon_HD_7970_OC_GHz_Edition_3GB_GDDR5', 0),
(4, 11, 75, 0, 75, '', 0),
(5, 43, 86, 0, 78, NULL, 0),
(6, 20, 59.95, 0, 59.95, NULL, 0),
(7, 48, 238, 0, 258, NULL, 0),
(8, 53, 2436, 1, 2145, 'MSI_GT70_1053ES_Dragon_i7_3630_16GB_750GB_SSD_GTX_680_17_3', 1),
(9, 35, 17.25, 1, 15.25, NULL, 0),
(10, 19, 1445, 0, 1113, NULL, 0),
(11, 26, 89.95, 1, 79.95, NULL, 0),
(12, 49, 154, 1, 145, NULL, 0),
(13, 8, 99.95, 1, 89.95, NULL, 0),
(14, 55, 555, 0, 499, 'Apple_iPhone_5_16GB_Blanco', 1),
(15, 9, 149, 0, 149, NULL, 0),
(16, 44, 142, 1, 128, NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('Confirmado','Pagado','Preparado','Enviado','Recibido','Cancelado') NOT NULL DEFAULT 'Confirmado',
  `ref` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `privilegios`
--

CREATE TABLE IF NOT EXISTS `privilegios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Volcado de datos para la tabla `privilegios`
--

INSERT INTO `privilegios` (`id`, `nombre`) VALUES
(11, 'comprar'),
(2, 'noConectar'),
(1, 'noRegistrar'),
(4, 'verAdminCategorias'),
(5, 'verAdminOfertas'),
(12, 'verAdminPedidos'),
(9, 'verAdminPrincipal'),
(8, 'verAdminPrivilegios'),
(3, 'verAdminProductos'),
(7, 'verAdminRoles'),
(13, 'verAdminStocks'),
(6, 'verAdminUsuarios'),
(10, 'verHome');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `privilegios_rol`
--

CREATE TABLE IF NOT EXISTS `privilegios_rol` (
  `privilegio_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL,
  PRIMARY KEY (`privilegio_id`,`rol_id`),
  KEY `rol_id` (`rol_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `privilegios_rol`
--

INSERT INTO `privilegios_rol` (`privilegio_id`, `rol_id`) VALUES
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(9, 1),
(12, 1),
(13, 1),
(3, 2),
(4, 2),
(9, 2),
(13, 2),
(1, 4),
(2, 4),
(10, 4),
(11, 4),
(1, 5),
(2, 5),
(3, 5),
(4, 5),
(5, 5),
(6, 5),
(7, 5),
(8, 5),
(9, 5),
(10, 5),
(11, 5),
(12, 5),
(13, 5),
(5, 6),
(9, 6),
(12, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE IF NOT EXISTS `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) DEFAULT NULL,
  `descripcion` text,
  `categoria_id` int(11) NOT NULL,
  `fabricante_id` int(11) DEFAULT '1',
  `precio_venta` double NOT NULL,
  `disponibilidad` enum('En stock','Próxima reposición','Agotado','Descatalogado','Outlet') NOT NULL DEFAULT 'Agotado',
  `existencias` int(11) DEFAULT '0',
  `activo` tinyint(1) DEFAULT '1',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  KEY `fabricante_id` (`fabricante_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=67 ;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `categoria_id`, `fabricante_id`, `precio_venta`, `disponibilidad`, `existencias`, `activo`, `fecha`) VALUES
(1, 'Logitech Wireless Keyboard K340', 'NAVARRABA0\r\n\r\nDiseñado para adaptarse a su espacio y a su estilo de vida.\r\n\r\nMás espacio \r\nEl diseño compacto con teclas de tamaño normal y teclado numérico libera espacio en el escritorio.\r\nCargado y a punto \r\nSin preocupaciones con las pilas de teclado de 3 años de duración\r\nEste teclado inalámbrico (con un diseño compacto y tres años de duración de las pilas) se adapta a su espacio y a su estilo de vida.\r\n\r\nDiseño compacto \r\nPuede escribir en el escritorio o llevarse el teclado con usted, sin que ello vaya en detrimento de la comodidad. Las teclas de tamaño normal y el teclado numérico ofrecen una combinación que le permitirá escribir cómodamente. \r\nTres años de duración de las pilas \r\nCon los tres años de duración de pilas del teclado, podrá olvidarse de cambiar las pilas.* ¿Le preocupa la duración de las pilas? Lo entendemos perfectamente. \r\nTecnología inalámbrica avanzada de 2,4 GHz \r\nPara trabajar o jugar desde más lugares como si estuviese en el sofá: la conexión inalámbrica de gran alcance elimina casi por completo retrasos, interrupciones e interferencias. Infórmese sobre las tecnologías inalámbricas de Logitech. \r\nTeclas planas \r\nLas manos y muñecas se mantienen en una posición más neutra que contribuye a una mayor comodidad de uso, con teclas agradables al tacto que casi no se oyen. \r\nTeclas programables \r\nControl total con teclas F** programables que se pueden personalizar fácilmente para abrir las aplicaciones, carpetas o páginas Web favoritas. ¿Métodos abreviados? ¿Botones de acceso rápido? ¿Botones programables? Obtenga más información sobre el software SetPointTM.', 9, 18, 13, 'En stock', 5, 1, '2013-05-13 15:31:05'),
(2, 'Samsung UE40F6500 40" LED 3D', 'Disfruta de una gran variedad de contenidos\r\n\r\nS Recommendation\r\nClear Motion Rate\r\nFull HD 1080P\r\nConexión WiFi incorporada \r\nUna Smart TV que te comprende \r\nCon el tiempo, S Recommendation redefinirá su búsqueda en función de lo que te gusta a ti, y no por popularidad. Cada sugerencia de tu Smart TV será original, entretenida y relacionada con tus gustos, de ese modo no obtendrás siempre los mismos resultados. Una función intuitiva, sencilla y muy útil.\r\n\r\nLa puerta a un concepto revolucionario de entretenimiento \r\nSamsung está cambiando, aún más, la forma de llegar a los contenidos en los televisores. Con cinco sencillos paneles y una intuitiva interfaz Smart Hub, podrás buscar y abrir tus canales fácil y rápidamente. Gracias al acceso inmediato a las recomendaciones de tu televisor, verás todo lo que te gusta sin tener que investigar las programaciones de los canales. Disfruta de una gran variedad de programas y películas a la carta, accede a aplicaciones disponibles en tu Smart TV y comparte contenido en Facebook y Twitter con tus amigos. Además, reproduce tus fotos, vídeo y música con tus dispositivos compatibles.\r\n\r\nDescubre tus mejores momentos con la mejor calidad de imagen \r\nDisfruta de lo último en imagen con el nuevo Smart TV de Samsung. Y para que disfrutes de una mejor experiencia visual, características como Micro Dimming y Clear Motion Rate actúan para que las reproducciones se perciban más reales. Consigue una imagen nítida con Wide Colour Enhacer Plus y Full HD 1080P y disfruta de vídeos en streaming con la mejor calidad de la mano de Web Clear View.\r\n\r\nUna obra de arte con la mejor calidad de imagen \r\nMejora tu experiencia audiovisual, a la vez que realzas el estilo de tu casa. Los televisores de la Serie 6 han sido diseñados con una línea premium y un marco estrecho, de este modo logran que te sumerjas en lo que estás viendo. Un televisor con el que nunca te aburrirás y que, gracias a su diseño One Desing, no solo conquistará a todo el mundo, sino que conseguirá que solo estés pendiente de lo que sale en su pantalla.\r\n\r\nSaca el máximo provecho a sus opciones de conectividad \r\n\r\nLa Serie 6 de Samsung te permite acceder de manera inalámbrica y reproducir en streaming contenido desde cualquier dispositivo, como un tablet. Incluso podrás conectar tu memoria USB o disco duro a tu televisor para disfrutar de tus películas, fotos y canciones preferidas. Además, con la conexión WiFi incorporada, sacarás el máximo provecho a la conectividad de tu televisor sin necesidad de molestos cables o dispositivos externos.', 8, 3, 649, 'En stock', 18, 1, '2013-05-13 15:36:45'),
(3, 'Brigmton BTPC-1011-DC 10.1" Negro', 'Especificaciones:\r\n\r\nPantalla\r\nTablet 10.1"\r\nPantalla 1024x600\r\nPantalla multitáctil capacitiva\r\nAndroid 4.1 Jelly Bean\r\nProcesador Dual Cortex A9 RockChip RK3066 1.6 Ghz\r\nGraficos:\r\nAcelerador 3D\r\nGPU MALI\r\nMemoria:\r\nRAM: 1GB DDR3\r\nMemoria interna: 8GB\r\nEntrada MicroSD hasta 32GB\r\nBatería litio recargable: 3.7V, 5000mAh\r\nHasta 300 horas en espera\r\nConexiones:\r\nEntrada USB OTG\r\nSalida HDMI 1080P\r\nEntrada de auriculares jack 3.5 mm.\r\nAltavoces incorporados\r\nMicrófono incorporado\r\nMultimedia\r\nCámara frontal 3Mp\r\nCamara trasera 2Mp\r\nGrabación de vídeo\r\nG-Sensor\r\nConexión Wifi: IEEE 802.11b/g/n\r\nBluetooth 2.0 Integrado\r\nAlimentación: 5V, 2A\r\nSoporta dongle 3G\r\nFunción Ebook\r\nSoporta hasta video HD 2160P\r\nFormatos de vídeo: AVI, MPG, MKV, RM, RMVB, MP4, MOV, VOB, DAT, FLV, TS, 3GP, WEBM (VP8), 1080P, etc.\r\nFormatos de audio: MP1, MP2, MP3, WMA, WAV, OGG, OGA, APE, FLAC, ACC, M4A, 3GPP, etc.\r\nFormatos imagen: JPG, JPEG, GIF, BMP, PNG, JFIF, etc.\r\nBuscador Google\r\nSoporta: Email, Youtube, Market, Java, Flash, PDF, etc.\r\nDimensiones\r\nMedidas: 266x169x13.5 mm.\r\nPeso: 610 gr.\r\nContenido\r\n\r\nCable USB\r\nAdaptador de corriente.\r\nGuía de inicio rápido.', 57, 1, 139, 'En stock', 2, 1, '2013-05-13 15:54:15'),
(4, 'Samsung Galaxy S4 I9505 Negro Libre', 'Como un compañero en la vida real, el nuevo Galaxy S4 te ayuda a estar más cerca de los tuyos y a capturar esos momentos irrepetibles que compartes con ellos. Cada característica ha sido diseñada para hacerte la vida más fácil e, incluso, para velar por tu salud y bienestar. Galaxy S4 es el dispositivo pensado para ti.\r\n\r\nGalaxy S4 captura tus momentos más importantes donde quiera que te encuentres. Galaxy S4 te permite no solo mirar tus fotos, sino también escucharlas y revivir tus recuerdos de la forma más real. \r\nHaz múltiples fotos de la misma escena y edítalas para añadirles un efecto dramático especial. Después, comparte instantáneamente los álbumes con tus amigos y tu familia.\r\n\r\nEl último dispositivo móvil de Samsung, Galaxy S4, esta diseñado para aquellos a quienes les gusta estar siempre conectados mientras hacen varias cosas a la vez. \r\nLlama a un amigo, responde una llamada, previsualiza contenidos o muévete entre emails y videos sin tocar el teléfono. Cuando conectas Galaxy S4 a tu Smart TV, incluso este puede sugerirte programas de televisión basándose en tus preferencias. \r\nAccede a tu sistema HomeSync desde tus dispositivos y comparte de forma remota con tu familia y amigos.\r\n\r\nGalaxy S4 sabe lo importante que es la salud. Por eso, quiere ayudarte a conseguir tus metas de vida saludable monitorizando tus niveles de actividad durante los \r\nejercicios diarios. Además, el dispositivo se preocupa de nuestro bienestar con sensores que, automáticamente, ajustan la pantalla y el volumen dependiendo de cómo estés usando el teléfono; de este modo, Galaxy S4 asegura una experiencia de uso siempre óptima.', 59, 3, 629, 'En stock', 11, 1, '2013-05-13 15:56:47'),
(5, 'Lenovo Essential B590 i5-3210/4GB/500GB/15.6"', 'Los sistemas Essential de Lenovo integran numerosas características y funciones que no suelen asociarse a los equipos de precio asequible. La variedad de opciones de procesadores Intel® y AMD ayuda a acelerar el rendimiento, mientras que nuestras herramientas de ahorro de energía aumentan todavía más su productividad. Sincronice y comparta grandes archivos de vídeo rápidamente con DirectShare. Gestione fácilmente las conexiones de Internet con nuestra utilidad ReadyComm. En determinados modelos, puede disfrutar de chats de vídeo sin fatiga visual gracias a la cámara Web de alta sensibilidad de Lenovo y a los ajustes automáticos del brillo de la pantalla.\r\n\r\nTanto si los utiliza en una pequeña empresa o en una oficina doméstica, como para sus actividades cotidianas, mostrará un estilo dinámico con los acabados de metal cepillado y los ángulos de visión amplios de nuestros elegantes laptops. Determinados modelos también ofrecen un teclado AccuType ergonómico y transpirable con almohadilla táctil MultiTouch. Además, nuestros ventiladores proporcionan la refrigeración necesaria y son más silenciosos cuando el equipo está encendido.\r\n\r\nDisfrutará de la máxima tranquilidad desde el primer momento al adquirir un PC Essential. Los nuevos usuarios comprobarán que nuestra excelente calidad promete un uso duradero. Nuestros lectores de huellas dactilares permiten iniciar sesión de forma segura, mientras que el bloqueador de puertos USB impide el acceso no autorizado a los datos. OneKey® Rescue System ofrece soporte en el caso de que necesite restaurar el sistema o recuperar sus datos importantes. Cuando la jornada laboral deja paso a unas horas de ocio, el software Lenovo Energy Management ayuda a prolongar al máximo la autonomía de la batería.', 52, 1, 459, 'Agotado', 0, 1, '2013-05-13 16:00:29'),
(6, 'HP Pavilion G6-2263SS A6-4400M/6GB/500GB/HD 7520G/15.6"', 'Flexibilidad de desempeño y movilidad. En sus desplazamientos o en casa, la PC HP Paviliong6 cubre todas sus necesidades. La velocidad, gráficos y opciones de almacenamiento más adecuados para su estilo, junto con las exclusivas innovaciones de HP, buscan allanar un poco más su camino. \r\nUna combinación perfecta. \r\nHasta 7,25 horas de duración de batería. La potencia que necesita para todo su día. \r\nInnovaciones exclusivas de HP \r\nGracias a HP ProtectSmart, puede mantener los datos de su portátil a salvo de sacudidas y golpes accidentales. Detecta el movimiento y los planes por delante, detiene el disco duro y protege la vida digital completa.\r\nEl cliente recibe el paquete de Universal Music. \r\nEl software incorporado permite que los consumidores tengan la oportunidad de ganar las experiencias "lo que el dinero no puede comprar" y entradas para conciertos VIP, además de contar con acceso a transmisión ilimitada del catálogo Universal Music - todo de forma gratuita[ \r\nCon los productos de HP, usted puede sentir un verdadero apoyo. \r\nHP Support Assistant le proporciona una solución interactiva que ayuda a mantener el rendimiento de su PC portátil al máximo. Para que su PC portátil tenga un óptimo comienzo, también le brindamos 90 días de asistencia sobre el software preinstalado.\r\n\r\nEspecificaciones:\r\n\r\nProcesador AMD Dual-Core A6-4400M APU 2.7Ghz\r\nMemoria RAM 6GB DDR3 SODIMM (Máximo 8GB)\r\nDisco duro 500 GB (5400 rpm S-ATA)\r\nAlmacenamiento óptico Super Multi Dual Layer (S-ATA)\r\nDisplay Pantalla HD BrightView con retroiluminación LED de 39,6 cm (15,6") en diagonal, (1.366 x 768)\r\nControlador gráfico AMD Radeon HD 7520G compartida\r\nConectividad\r\n10/100 BASE-T Ethernet LAN integrada\r\n802.11 b/g/n\r\nBluetooth\r\nCámara de portátil Sí\r\nMicrófono Sí\r\nBatería 6 celdas Ion de litio\r\n1 x VGA\r\n1 x HDMI\r\n1 x salida de auriculares\r\n1 x entrada de micrófono\r\n1 x USB 2.0 (Chargable USB included)\r\n2 x USB 3.0\r\n1 RJ45\r\nLector de Tarjetas multimedia\r\nSistema operativo Microsoft Windows 8 64bits\r\nDimensiones (Ancho x Profundidad x Altura)  376 x 244 x 36.3 cm\r\nSoftware:Symantec Norton Internet Security 2013 (actualización gratuita durante 60 días) Dolby Advanced Audio HP Connected Music desarrollado porUniversal Music Group HP Connected Photo HP CoolSense HP ProtectSmart HP Connected ePrint Cyberlink Power DVD Windows Live Essentials Amazon Kindle\r\nPeso  2.48 kg \r\nColor Negro', 52, 7, 395, 'Descatalogado', 0, 0, '2013-05-13 16:03:07'),
(7, 'Samsung Galaxy S3 Mini Azul Libre', 'Galaxy S III mini mantiene el rendimiento y el diseño inspirado en la naturaleza del Galaxy S III, pero en un tamaño más compacto, con una pantalla de 4". La mejor elección para aquellos que buscan un smartphone más práctico.\r\n\r\nDiseño Galaxy S III mini toma el diseño minimalista y orgánico del Galaxy S III, inspirado en la naturaleza. Disfruta de una experiencia de uso mejorada gracias a su forma ergonómica. Sus suaves curvas te ofrecen, no solo un diseño más natural, sino un agarre mucho más cómodo.\r\nPantalla AMOLED de 4" Ahora sí podrás decir que ves la realidad. Su pantalla Super AMOLED de 4 pulgadas hará que disfrutes de la mejor experiencia visual. Los contenidos multimedia, las aplicaciones o las páginaseb tendrán los colores más vivos y una mayor nitidez.\r\nSistema operativo Jelly Bean Samsung Galaxy S III mini incorpora el último sistema operativo de Android, Jelly Bean (AndroidTM 4.1). Disfruta de unos gráficos más rápidos y fluidos, del nuevo buscador de Google y de la característica Google NowTM, un servicio capaz de ofrecerte toda la información que necesitas incluso antes de pedirla.\r\nPop up play Gracias a la función Pop up Play podrás ver un vídeo en una ventana al mismo tiempo que escribes un mensaje de texto o envías un email. El tamaño de las ventanas es un poco más reducido, pero te permite trabajar cómodamente con dos aplicaciones a la vez, para que no te pierdas nada\r\nDirect Call Samsung Galaxy S III mini sabe cuándo quieres hablar. Si estás escribiendo un mensaje y de pronto decides llamar, tan sólo acerca el smartphone a tu oído y la función Direct call marcará el número. Olvídate de buscar en los registros de llamadas y agenda de contactos, tu Galaxy S III mini lo hará por ti.\r\nS Voice El avanzado software de reconocimiento natural de lenguaje te permitirá utiliza tu voz para desbloquear tu móvil gracias a sencillos comandos de voz, así como reproducir tus canciones preferidas, subir o bajar el volumen, organizar tu calendario o ejecutar la cámara para fotografiar.\r\nSmart alert \r\nCada vez que coges el teléfono, ¿no sería genial que te avisara de todo lo que ha ocurrido durante el tiempo en el que no lo has mirado o no lo has tenido cerca? Samsung Galaxy S III mini sabe cuándo has vuelto, avisándote con una breve vibración de las llamadas perdidas y los mensajes nuevos. A eso sí que se le llama pensar en ti.', 59, 3, 199, 'En stock', 34, 1, '2013-05-13 16:17:39'),
(8, 'Bq Maxwell 7" IPS 8G', 'Caracter&amp;iacute;sticas:\r\n\r\nTecnolog&amp;iacute;a IPS: visi&amp;oacute;n perfecta desde cualquier &amp;aacute;ngulo \r\nbq Maxwell viene equipado con 7 pulgadas de pantalla multit&amp;aacute;ctil capacitiva IPS con cinco puntos de detecci&amp;oacute;n simult&amp;aacute;neos. Gracias a la tecnolog&amp;iacute;a IPS, su pantalla ofrece una visi&amp;oacute;n completa desde cualquier posici&amp;oacute;n, vertical u horizontal, hasta los 178 grados. Y con una resoluci&amp;oacute;n de 1024 x 600 p&amp;iacute;xeles, bq Maxell brinda una gran nitidez y detalle, respetando la intensidad de los colores.\r\nConexi&amp;oacute;n sin l&amp;iacute;mites \r\nComparte y almacena archivos, y env&amp;iacute;alos de forma r&amp;aacute;pida y gratuita gracias a la tecnolog&amp;iacute;a Bluetooth V.4.0 (HS) incorporada. Olv&amp;iacute;date de los cables.\r\nAumenta las posibilidades \r\nDisfruta de una conectividad total a trav&amp;eacute;s de los puertos USB OTG y HDMI. Aprovecha todo el potencial de bq Maxwell conect&amp;aacute;ndolo a un teclado, rat&amp;oacute;n o disco duro a trav&amp;eacute;s de la entrada USB. Y con el cable HDMI, podr&amp;aacute;s conectar tu tablet a un televisor o pantalla de ordenador para disfrutar de pel&amp;iacute;culas o v&amp;iacute;deos en alta definici&amp;oacute;n.\r\nUna versatilidad cautivadora \r\nSi quieres una tablet ligera y port&amp;aacute;til, con gran calidad y a un precio asumible, bq Maxwell es tu dispositivo. Por solo 119 euros, te presentamos un dispositivo de 360 gramos de peso que podr&amp;aacute;s manejar f&amp;aacute;cilmente y llevar a cualquier lugar.\r\nCon Android 4.0 ICS \r\nEl sistema operativo 4.0 Ice Cream Sandwich mejora sustancialmente la experiencia de usuario al estar desarrollado espec&amp;iacute;ficamente para tablets. Descubre su nueva interfaz renovada, la nueva tipograf&amp;iacute;a oficial y su nuevo teclado virtual, entre otras muchas mejoras.', 57, 1, 89.95, 'Agotado', 0, 1, '2013-05-13 16:25:22'),
(9, 'Samsung T22B300 TV 22" LED', 'Los monitores LED de la serie 3 con sintonizador de TDT HD integrado, aportan un diseño exclusivo y una tecnología avanzada, que permite al usuario el disfrute de la máxima calidad de imagen respetando el medio ambiente.\r\n\r\nA través del puerto USB el monitor es capaz de reproducir una amplia variedad de archivos multimedia sin necesidad de conectarlo a un PC.\r\n\r\nLa función PIP permite que se muestren otros contenidos en el monitor al mismo tiempo que se disfruta de la televisión. \r\n\r\nPantalla\r\nTamaño de pantalla en diagonal 551.2 mm (21.7 ")\r\nResolución de la pantalla 1920 x 1080 Pixeles Full HD\r\nProporción latitud-altura 16:9\r\nFormatos gráficos soportados 1920 x 1080 (HD 1080)\r\nFormato de vídeo soportado 1080p\r\nRelación de contraste (dinámico) Mega contraste\r\nIndice de contraste típico 1000:1\r\nBrillo de pantalla 250 cd/m²\r\nTiempo de respuesta 5 ms\r\nÁngulo de visión, horizontal 170 °\r\nÁngulo de visión, vertical 160 °\r\nColor Negro\r\nSintonizador de la TV\r\nFormato de sistema de señal análoga* No específicado\r\nFormato de señal digital* DVB-C, DVB-T\r\nAudio\r\nAltavoces incorporados*\r\nNúmero de altavoces* 2 piezas\r\nPotencia estimada RMS 10 W\r\nConectividad\r\nCandidad de puertos HDMI: 1\r\nCantidad de puertos USB 2.0: 1\r\nVideo componente (YPbPr/YCbCr) in 1\r\nPC in (D-Sub)\r\nCantidad de puertos SCART 1\r\nCandidas de puertos RF 1\r\nInterfaz común*\r\nCommon interface Plus (CI+)*\r\nRed eléctrida\r\nCaracterísticas de manejo\r\nGuía electrónica de programación\r\nExhibición en pantalla (OSD)\r\nNúmero de lenguajes OSD 29\r\nImagen en imagen\r\nFunciones de teletexto\r\nMontaje en pared: compatible VESA 75 x 75 mm\r\nContros de energía\r\nConsumo energético 35 W\r\nConsumo de energía (inactivo) 1 W\r\nVoltaje de entrada 220 - 240 V\r\nFrecuencia de entrada 50/60 Hz\r\nPeso y dimensiones\r\nDimensiones: Ancho 509.7 mm x Profundidad 47 mm x Altura 318.2 mm\r\nDimensiones con soporte: Ancho 509.7 mm x Profundidad 195 mm x Altura 396.6 mm \r\nDimensiones del embalaje (alto x alto x peso) 589 x 397 x 141 mm\r\nPeso sin soporte 3700 g \r\nPeso con soporte 3950 g\r\nPeso Paquete, peso 5450 g\r\nContenido del embalaje\r\n\r\nMando a distancia\r\nCables incluidos AC, VGA\r\nSoporte de sobremesa\r\nGuía de configuración rápida\r\nManual de usuario\r\nTarjeta de garantía', 8, 3, 149, 'Agotado', 0, 1, '2013-05-13 16:27:14'),
(10, 'Dell Vostro 2520 i3-2328/4GB/500GB/15.6"', 'Diseñado para la empresa. \r\nEl resistente VostroTM 2520 de 15,6" está disponible en un imponente Gris pizarra London y ofrece movilidad esencial, eficiencia y valor a largo plazo. \r\nDisfrute de viva claridad y audio claro y cristalino. \r\nDescubra una espectacular experiencia multimedia con tarjetas gráficas Intel® HD integradas y audio mejorado.\r\n\r\nEspecificaciones:\r\n\r\nProcesador Procesador Intel Core i3 (2328M)\r\nMemoria RAM 4GB DDR3 SODIMM\r\nDisco duro 500 GB (5400 rpm S-ATA)\r\nAlmacenamiento óptico Super Multi Dual Layer (S-ATA)\r\nDisplay 15.6" LED HD (1366 x 768) Antireflectante 720p\r\nControlador gráfico\r\nIntel HD Graphics 3000\r\nVGA\r\nHDMI\r\nConectividad\r\nLAN 10/100 /1000\r\n802.11 b/g/n\r\nBluetooth V4.0\r\nCámara de portátil Sí\r\nMicrófono Sí\r\nBatería 6 celdas Ion de litio\r\nConexiones\r\n1 x VGA\r\n1 x HDMI\r\n1 x salida de auriculares\r\n1 x entrada de micrófono\r\n3 x USB 2.0 \r\n1 RJ45\r\nLector de Tarjetas MMC, SD, SDHC, SDXC\r\nSistema operativo Microsoft Windows 8 64bits\r\nDimensiones (Ancho x Profundidad x Altura)  376 x 260 x 31.5~34.5 mm\r\nPeso  2.36 Kg\r\nColor Negro\r\nNota informativa: Todos nuestros productos son distribuídos por canal oficial español, por lo que todos los teclados incluyen Ñ y poseen garantía oficial española.', 52, 5, 395, 'En stock', 10, 1, '2013-05-13 16:29:22'),
(11, 'Samsung 840 SSD Series 120GB SATA3 Basic Kit', 'nterface: SATA 6GB/s\r\nDesign 2.5" 7mm (Ultraslim) Form Factor\r\nSeries 840\r\nStorage\r\nCapacity 120GB\r\nFeatures\r\nSequential Read Speed Up to 530MB/s\r\nSequential Write Speed Up to 130MB/s\r\nRandom Read Speed Up to 85K IOPS\r\nRandom Write Speed Up to 32K IOPS\r\nPower\r\nPower Consumption (W) .15W\r\nVoltage 5V ± 5% \r\nContents\r\nSamsung SmartMigration Software, Samsung Software & Manual CD, Quick User Manual', 16, 3, 75, 'En stock', 8, 1, '2013-05-13 16:31:38'),
(12, 'Unotec Flypad Teclado + Ratón Motion Controller', 'Disfruta del control inalámbrico de tu dispositivo Android TV con este teclado QWERTY de reducidas dimensiones con función de ratón integrado.\r\n\r\nContrla tu Android TV de la forma más sencilla. Este teclado inalámbrico contiene 75 teclas, incluyendo teclas de acceso directo como Play, Pause y Mute. Pero la función más sorprendente de este teclado es su función de ratón, pues funciona como un mando remoto de Wii, moviendo el teclado, podrá mover el cursor del por la pantalla de tu dispositivo Android TV. Tan solo necesita pulsar el botón "Android" del teclado y girarlo para poner en funcionamiento esta increíble característica. Para dejar de usar el ratón y usar el teclado de nuevo, tan solo necesita pulsar de nuevo el botón "Android".\r\n\r\nIdeal para presentaciones, juegos, multimedia y todo tipo de aplicaciones de entretenimiento, este teclado cambiará tu forma de interactuar con tu TV.\r\n\r\nAdemás, puedes usar este increíble teclado y su función de ratón con sistemas Windows y Mac, sin necesidad de drivers, tan solo conectar el receptor inalámbrico y listo!', 9, 1, 23, 'En stock', 2, 1, '2013-05-13 16:38:26'),
(13, 'Microsoft Xbox 360 Wireless Controller for Windows Negr', 'Genial gamepad compatible con XBOX 360 y PC. Es el mismo gamepad original de XBOX 360 pero que además incluye el adaptador inalámbrico para ser usado con el PC.\r\n\r\nDescubra la mejor precisión, control y comfort. El control Wireless Xbox 360 para Windows le proporciona una experiencia de juego consistente y universal a través de los dos sistema de juegos Microsoft. Experimente la última experiencia con Windows y Xbox 360. \r\n\r\nInalámbrico\r\nEl micro transceptor USB inalámbrico de 2,4 GHz se puede conectar de inmediato sin prácticamente ninguna interferencia y tiene un alcance inalámbrico de hasta 9 metros, admitiendo hasta 4 mandos con un solo receptor.\r\nPara PC y Xbox 360\r\nTrabaja con todas las plataformas de juego de Microsoft y con todos los PC con Windows XP / Vista o Windows 7 y Xbox 360, ofrece una experiencia de juego universal y consitente.   \r\nVibración\r\nSiéntete bien jugando. La vibración asegura una sensación auténtica en cada juego.   \r\nErgonómico\r\nJuega con total confort. Ganador de premios, proporciona una experiencia de juego más confortable. \r\nMejora el juego en tu PC\r\nMandos para los pulgares de precisión, con dos puntos de presión, y 8 pad direccionales \r\nXbox Live®\r\nPreparadado para integrar auriculares para jugadores de PC y Xbox Live®\r\nIncluye adaptador inalámbrico.', 62, 12, 31.95, 'En stock', 10, 1, '2013-05-13 16:39:46'),
(14, 'Hannspree Basketball LCD 19" HDMI', 'Con los conceptos de diseño centrado en la forma de una pelota de baloncesto, HANNS basketball amplía su imaginativa visión de la tecnología con un genuino revestimiento de cuero de pelota de baloncesto. La pantalla con forma de pelota puede girar 90 grados, como una pelota rodante; realmente es una combinación ingeniosa de funcionalidad y diseño que ofrece de forma divertida los modos de pantalla vertical y horizontal. Los modelos de pantalla LCD con diseños temáticos de la serie Xm-S de Hannspree, aportan humor y entretenimiento a su vida diaria, un requisito imprescindible para los aficionados al baloncesto\r\n\r\nEl diseño integral del cuerpo principal acompañado por una superficie de cuero hecha a mano muestra la exquisita textura y hendiduras de una pelota de baloncesto.\r\nDiseño de base exclusivo que ofrece varios ángulos de giro e inclinación, una ingeniosa combinación de funcionalidad y aspecto que proporciona modos de pantalla horizontal y vertical así como una increíble comodidad de visualización desde cualquier ángulo.\r\nLos puertos de ampliación para altavoces simulan una pelota de baloncesto que rebota.', 8, 1, 69.95, 'Outlet', 3, 0, '2013-05-13 16:41:37'),
(16, 'Asus VH168D 15.6" LED', 'Especificaciones:\r\n\r\nPantalla\r\nTamaño: Wide Screen 15.6"(39.6cm) 16:9 \r\nResolución : 1366x768 \r\nTamaño pixel : 0.252mm\r\nBrillo(Max) : 220 cd/&#13217;\r\nASUS Smart Contrast Ratio (ASCR) : 50000000:1 \r\nAngulo de vision (CR&#8807;10) : 90°(H)/65°(V) \r\nTiempo de respuesta : 11ms\r\nFunciones\r\nSPLENDID Video Intelligence Technology\r\nSPLENDID Video Preset Modes : 6 Modes\r\nSkin-Tone Selection : 3 Modes\r\nColor Temperature Selection : 4 Modes\r\nPrácticos botones\r\nSPLENDID Video Preset Mode Selection\r\nAuto. Adjustment\r\nBrightness Adjustment\r\nContrast Adjustment\r\nI/O Ports\r\nEntrada: D-Sub\r\nFrecuencia de señal\r\nAnalog Signal Frequency : 30~85 KHz(H)/ 55~63 Hz(V)\r\nConsumo:\r\nPower ON : < 10W\r\nPower Saving Mode : < 0.5W\r\nPower Off Mode : < 0.5W\r\nDiseño:\r\nChassis Colors : Negro\r\nTilt : +20°~-5°\r\nVESA Wall Mounting : 75x75mm\r\nSeguridad\r\nKensington lock\r\nDimensiones\r\nPhys. Dimension with Stand(WxHxD) : 377x327x180 mm\r\nBox Dimension (WxHxD) : 450x322x132 mm\r\nPeso\r\nNet Weight (Esti.) : 2.0kg\r\nGross Weight (Esti.) : 3.5kg\r\nAccesorios\r\nVGA cable\r\nPower cord\r\nQuick start guide\r\nWarranty Card', 8, 1, 74.95, 'En stock', 32, 1, '2013-05-13 16:43:42'),
(17, 'Samsung S27A850D 27" LED USB 3.0', 'Mejora tu productividad con lo último en monitores \r\nCalidad de imagen - Gracias a la tecnología LED, obtendrás mejor una calidad de imagen y color (Confirmado); Conectividad - Con la conexión USB 3.0 y la opción de pantalla dual, mejorarás tu productividad (Confimado) \r\nLos monitores LED de la Serie 850 con su diseño profesional y avanzadas prestaciones proporcionan el máximo rendimiento y confort al usuario, aumentando así la productividad en el trabajo.\r\n\r\nCaracterísticas:\r\n\r\nConsiderable ahorro de energía \r\nLos monitores de Samsung están fabricados con un amplio sentido de responsabilidad con el medio ambiente, consiguiendo reducir el consumo hasta en un 40% respecto a monitores convencionales. La Función Magic Eco permite regular el consumo de energía del monitor en función del brillo de la pantalla posibilitando un ahorro considerable. Ofrece cuatro opciones de consumo: 100%, 75%, 50% y apagado. La función Magic Bright estará disponible cuando la función Magic Eco se encuentre apagada. El monitor en modo standby consume únicamente hasta 0,3 W.\r\nVe más contenido gracias a su increíble resolución \r\nEl monitor LED de la serie SA850 cuenta con una increíble pantalla WQHD con una resolución de 2.560 x 1.440 píxeles, cuatro veces la resolución HD (720p). Resulta ideal para aquellos negocios y empresas que necesitan el máximo detalle. Su amplia pantalla, que cuenta con doble puerto DVI y Display Port, te permite obtener el máximo detalle, perfecto para hospitales, clínicas y estudios. Su mejorada resolución de pantalla te proporciona la mejor calidad y eficiencia de imagen, convirtiéndolo en una herramienta imprescindible para tu trabajo.\r\nImágenes reales con la máxima calidad \r\nYa sea un vídeo HD de tu videocámara o fotos de tu cámara digital, la característica sRGB te asegurará siempre colores naturales. Poco importa con qué dispositivo capturas la imagen, pues lo verás idéntico al original en tu monitor Samsung. Prepárate para visualizar, editar y guardar imágenes en su formato original sin pérdida de calidad.\r\nObserva todo desde el mejor ángulo posible \r\nOfrece a tus empleados la mejor experiencia visual con el monitor profesional de Samsung. Mientras que los monitores convencionales ofrecen un ángulo de visión de 170º en horizontal, el nuevo monitor de Samsung ofrece un ángulo de visión extra de 178º. Los 8 grados adicionales son realmente útiles cuando quieres compartir en pantalla información con más compañeros o clientes. Además, el monitor de Samsung ha mejorado su ángulo de visión vertical en 18º, convirtiéndose en el monitor perfecto para realizar presentaciones o mostrar contenidos como vídeos.\r\nLos mejores colores desde cualquier ángulo \r\nMejora tu experiencia visual con la tecnología PLS (Plane to Line Switching). Las pantallas convencionales suelen sufrir degradaciones en el color, perdiendo calidad de imagen y color cuando se observa desde un ángulo muy agudo. El monitor de la serie SA850, que cubre un increíble ángulo de visión horizontal y vertical de 178º, ofrece una calidad de imagen nítida al mantener los colores naturales, por lo que observarás siempre la mejor imagen desde cualquier ángulo.', 8, 3, 647, 'En stock', 3, 1, '2013-05-13 16:46:19'),
(18, 'HP Deskjet 1000', 'Impresionante oferta, llévese una impresora a menor precio que el precio de los cartuchos que lleva incluídos!\r\n\r\nEsté seguro de una fiable impresión diaria con esta impresora HP sencilla, asequible y fácil de usar. Obtenga la instalación y la impresión inmediatamente y ahorre dinero y energía con una serie de características de ahorro de recursos.\r\n\r\nDisfrute de una instalación fácil y de un rendimiento fiable de una impresora en la que puede confiar.\r\n\r\n Empiece inmediatamente: fácil de usar e instalar nada más sacarla de la caja, en apenas unos minutos.\r\n Sin esperas - esta impresora de respuesta rápida se enciende en segundos; ahorre también tiempo con un apagado instantáneo.\r\n Relájese con la garantía de un año y con el servicio técnico HP Total Care.\r\n Esté seguro del rendimiento fiable ofrecido por la marca de impresoras de mayor venta en el mundo[1].\r\nImprima documentos diarios con la impresora más sencilla y asequible de HP.\r\n\r\n Produzca documentos diariamente con texto en calidad láser y gráficos de colores\r\n Obtenga una gran calidad de impresión con los asequibles cartuchos de tinta de HP.\r\n Confíe en HP Deskjet - celebrando más de 20 años como la marca de impresoras de mayor venta en el mundo.[1]\r\n Obtenga hasta 2,5 veces más páginas imprimidas en negro y 2 veces más páginas imprimidas en color con los cartuchos de tinta opcionales de gran capacidad.[2]', 10, 7, 41, 'Outlet', 2, 0, '2013-05-13 16:48:07'),
(19, '3D UP! Plus Impresora 3D', 'Impresora 3D compacta de sobremesa\r\n\r\nLa impresora 3D UP! Plus es una impresora 3D compacta y de sobremesa a un precio asequible. Se suministra con su propio software, f&aacute;cil de utilizar para usuarios de cualquier nivel, y con una bobina de material ABS. As&iacute; tiene todo el que necesita para convertir sus dise&ntilde;os en 3D en modelos de trabajo duraderos.\r\n\r\nConsumibles econ&oacute;micos\r\nEl filamento ABS UP!, ahora disponible en azul, negro, blnaco, amarillo, verde y rojo. (Paquetes de 2 bobinas de 700 gr., se venden por separado)\r\n\r\n&iexcl;No requiere montaje! \r\nLa impresora UP! Plus viene totalmente montada de f&aacute;brica, garantizando una &oacute;ptima calidad de impresi&oacute;n y una puesta en macha sin problemas.\r\n\r\nDesktop Friendly\r\nLa UP! Plus es silenciosa y limpia. Su medida reducida hace que quepa en cualquer espacio de trabajo. Para una m&aacute;quina tan compacta, la UP! Plus tiene una medida de trabajo de 140 x 140 x \r\n135 mm, que permite la fabricaci&oacute;n de una amplia gama de modelos.', 10, 1, 1445, 'En stock', 1, 1, '2013-05-13 16:49:35'),
(20, 'HP Officejet 6100 WiFi', 'mprima color de calidad profesional de gran impacto por menos con esta impresora con conexi&oacute;n web. Imprima mediante su red cableada o inal&aacute;mbrica o permanezca productivo en movimiento, con la impresi&oacute;n m&oacute;vil, incluyendo HP ePrint y AirPrintTM.La HP Officejet 6100 ePrinter est&aacute; dise&ntilde;ada para usuarios de negocios peque&ntilde;os y muy peque&ntilde;os que desean impresi&oacute;n en color de calidad profesional asequible y la capacidad de imprimir desde una red o directamente desde dispositivos m&oacute;viles sobre la marcha.\r\n\r\n\r\nCaracter&iacute;sticas \r\n\r\nImprima en color con calidad profesional a un bajo coste por p&aacute;gina.\r\n\r\nObtenga una impresi&oacute;n de calidad profesional por menos, cree documentos en color de alto impacto a un bajo coste por p&aacute;gina.\r\nImprima utilizando tintas individuales asequibles; elija los cartuchos de tinta de alta capacidad para una impresi&oacute;n frecuente de gran valor.\r\nImprima documentos que resisten a la decoloraci&oacute;n, el agua y las manchas de rotuladores con las tintas HP Officejet.\r\nDisfrute del rendimiento fiable de una impresora con un ciclo de trabajo mensual de 12.000 p&aacute;ginas; ideal para la impresi&oacute;n a color empresarial.\r\n\r\nImprima de forma inal&aacute;mbrica o en movimiento, con una amplia gama de opciones de impresi&oacute;n m&oacute;vil.\r\n\r\nPermanezca conectado y productivo sobre la marcha, gracias a la impresi&oacute;n m&oacute;vil con esta impresora conectada a la web.\r\nDescubra la libertad de HP ePrint, ahora puede imprimir desde casi cualquier lugar.\r\nImprima mensajes de correo electr&oacute;nico, documentos, p&aacute;ginas web y m&aacute;s directamente desde su iPad&reg;, iPhone&reg; o iPod touch&reg; con AirPrintTM.\r\nImprima de forma inal&aacute;mbrica directamente desde un smartphone y otros dispositivos m&oacute;viles con las aplicaciones de impresi&oacute;n m&oacute;vil de HP', 10, 7, 59.95, 'En stock', 8, 1, '2013-05-13 16:51:20'),
(21, 'Acer G196HQLB 18.5" LED', 'El monitor LED de 18.5" ultraplano G226HQLBb de Acer te ofrece la mejor calidad de imagen en un monitor liviano (menos de 3 Kg), con un pie elegante y un cuerpo negro brillante muy atractivo.\r\n\r\nEspecificaciones:\r\n\r\nIMAGEN\r\nTamaño: 18.5"\r\nTecnología: LED (TN+Film)\r\nLuminosidad: 200 cd/m2\r\nContraste: 100.000.000:1\r\nRespuesta: 5 ms\r\nÁngulo de visión (H/V): 90º/65º\r\nRelación de Aspecto: 16:9\r\nResolución: 1366x768\r\nFrecuencia: 60Hz\r\nAJUSTES DE POSICIÓN\r\nInclinación: SÍ (-5º a 15º)\r\nDIMENSIONES\r\nTamaño (Anchura x Prof. x Altura): 446,4x327,5x169,2 mm\r\nPeso: 2,50 Kg\r\nCONEXIONES\r\nVGA: 1\r\nOTRAS CARACTERÍSTICAS\r\nConsumo: 19W\r\nSoporte VESA: No\r\nRanura bloqueo de seguridad: SÍ\r\nColor: Negro', 8, 11, 81.95, 'En stock', 2, 1, '2013-05-13 16:53:12'),
(22, 'Asus VW199DR 19" LED', 'Características:\r\n\r\nTrue-to-Life Pictures Powered by LED\r\n50,000,000:1 ASUS Smart Contrast Ratio \r\nDinámicamente mejora el contraste de la pantalla ajustando la luminosidad de la luz de fondo para lograr el negro más oscuro y más brillante blanco - la entrega de imágenes reales.\r\nExcelente rendimiento visual\r\nDeleite sus sentidos visuales con resolución de 1440x900 y el rápido tiempo de respuesta de 5 ms para el trabajo y el entretenimiento.\r\nEl mejor compañero de doble pantalla con su portátil de pantalla ancha aumenta el espacio visual de la eficiencia multitarea.\r\nLa función de control de aspecto permite a los usuarios seleccionar un modo de visualización preferido entre DNS y 4:03 para los juegos reales como la vida o películas sin pérdida de datos o la distorsión de la imagen.\r\nSplendid TM Video Intelligence Tecnología \r\nLa exclusiva tecnología Splendid Video Intelligence optimiza el rendimiento de video y fidelidad de imagen en color mejora, el brillo, el contraste y la nitidez. 6 modos de preselección de video (Paisaje, Teatro, Juegos, Visión Nocturna, sRGB y Estándar) se pueden seleccionar a través de una tecla de acceso directo.\r\nDiseño de uso fácil\r\nControles clave convenientes para funcional configuración en el OSD en varios idiomas.\r\nGestor de cable se deshace de enreden los cables y alambres.\r\nVESA de montaje en pared estándar (100 x 100 mm).\r\n25 ° (Adelante 5 °, atrás 20 °) Ángulo de inclinación.\r\nSeguridad Kensington Lock.\r\nCompletar Servicio Post-Venta\r\n3 años de garantía del sistema\r\nGarantía del panel por 3 años\r\nServicio de recogida (en determinados países)\r\nVerde en la mente\r\nGreener Materiales\r\nCon retroiluminación LED y panel de mercurio \r\nembalajes de cartón corrugado con al menos un 80% de contenido reciclado\r\nSlim-embalaje plano permite que más cartones para montarse por carga, lo que reduce los costos de transporte y las emisiones de CO &#8322;\r\nEnergía y Ahorro Planet\r\n37.2k Wh ahorro de electricidad\r\nReducción 23.6kg las emisiones de CO &#8322;, equivalentes a:\r\n1,9 árboles plantados\r\n2 años de oxígeno para una familia de cuatro', 8, 1, 83.95, 'En stock', 2, 1, '2013-05-13 16:55:05'),
(23, 'LG 19EN33S-B 18.5" LED', 'Aumenta el rendimiento de tu trabajo\r\n\r\nMonitor LED, Full HD, 18.5´´, formato 16:9, resolución 1366x768, inclinable.\r\n\r\nEspecificaciones:\r\n\r\nTamaño (pulgadas) 18.5\r\nPantalla LCD TN\r\nFormato 16:9\r\nResolución 1360 x 768\r\nBrillo (cd/m2) 200\r\nTiempo Respuesta (ms) 5\r\nNúmero Colores (Millones) 16.7\r\nTratamiento de la superficie Hard Coating(3H), Anti-glare\r\nConexiones\r\nD-Sub (VGA)\r\nPeso(Kg)\r\nPeso con peana(Kg) 2.1\r\nPeso sin peana (Kg) 1.9\r\nCaracterísticas especiales\r\nPicture Mode\r\nDual Smart Solution\r\nDDC / CI\r\nSuper Energy Saving\r\nIntelligent Auto\r\nPlug & Play\r\nConsumo(W)\r\nNormal 24\r\nModo Ahorro 0.3\r\nDC Off 0.3\r\nDimensiones (largo x ancho x alto, mm)\r\nAparato (sin soporte) 441 x 35 x 275\r\nAparato (con soporte) 441 x 168 x 349\r\nVESA 75 x 75\r\nAccesorios\r\nCable de corriente\r\nD-Sub', 8, 14, 85.95, 'En stock', 32, 1, '2013-05-13 17:59:32'),
(25, 'Asus VS197N 19" LED', 'Calidad de imagen y un diseño elegante.\r\n\r\nCaracterísticas:\r\n\r\nCalidad de imagen y un diseño elegante \r\nSon los pequeños detalles los que separan a un buen monitor de uno realmente excepcional. El VS197N incorpora un ratio de contraste de 50 000 000:1 optimizado para ofrecer la mejor calidad de color, un perfil ultra fino y una peana muy estable y resistente.\r\nASUS Smart Contrast Ratio 50 000 000:1 \r\nMejora el contraste de la pantalla ajustando la retroiluminación del panel y asegurando los negros más puros y los blancos más brillantes.\r\nUn rendimiento visual excepcional\r\nDeleita tus sentidos con la resolución 1366x768 y los 5 ms de tiempo de respuesta.\r\nIdeal para ampliar el espacio de trabajo junto a un equipo portátil.\r\nPuertos D-sub y DVI con soporte HDCP.\r\nLa función control de aspecto permite que los usuarios escojan el modo de reproducción entre pleno y 4:3 sin pérdida de imagen ni distorsiones.\r\nSmart View Technology \r\nEsta tecnología permite disfrutar de la máxima calidad imagen sin que importe tu ángulo de visión.\r\nTecnología SplendidTM Video Intelligence \r\nLa tecnología SplendidTM Video Intelligence emplea un potente motor de coloreado que analiza y determina la tarea y ajusta los parámetros de visualización de la pantalla optimizando el color y el realismo de la imagen. Las 6 configuraciones de fábrica (paisajes, cine, gaming, escenas nocturnas, sRGB y estándar) ofrecen una optimización del color para diferentes escenarios de entretenimiento.', 8, 1, 89.95, 'En stock', 7, 1, '2013-05-13 18:05:17'),
(26, 'BenQ GL2023A 19.5" LED', 'Disfruta Cada D&iacute;a Tanto en casa Como en el Trabajo \r\nYa mar p&aacute;rrafo ponerse al dia en Internet o visualizaci&oacute;n de Documentos El Monitor LED BenQ GL2023A sueros de la Elecci&oacute;n perfecta! Con Una Contraste Din&aacute;mico de 12M: 1, Tiempo de Respuesta de 5ms, Tecnolog&iacute;a Senseye &reg; Exclusiva de BenQ, 19.5 "W y 16:09 LED.\r\n\r\nCaracter&iacute;stica:\r\n\r\nCalidad de imagen brillante \r\nLED Lidera el Camino \r\nRetroiluminaci&oacute;n LED ofrece ventajas significativas sobre la tecnolog&iacute;a CCFL usada en monitores antiguos LED. Estas ventajas abarcan no s&oacute;lo las m&eacute;tricas de rendimiento, tales como un mayor contraste din&aacute;mico, sin fugas de luz y sin parpadeos, sino tambi&eacute;n factores ambientales, como un proceso de fabricaci&oacute;n y eliminaci&oacute;n que produce menos contaminantes.\r\n12M: 1 Ultra Alto Contraste Din&aacute;mico para Profundidad y Definici&oacute;n \r\nEl GL2023A viene con un ratio de contraste ultra alta din&aacute;mica del 12M: 1 para a&ntilde;adir profundidad de color y definici&oacute;n a las pel&iacute;culas oscuras y complejas, de modo que todo lo que ves, desde el blanco m&aacute;s brillante al negro m&aacute;s oscuro, es perfectamente prestados para entregar la m&aacute;s clara , m&aacute;s suave imagen rendimiento durante las pel&iacute;culas y los videojuegos. \r\nBenQ Monitores LED Monitores LED convencionales\r\nTiempo de Respuesta Superior \r\nDisfruta de videos de alta din&aacute;micos sin im&aacute;genes fantasma u otros artefactos con tiempo de respuesta de 5ms.\r\nSenseye &reg; Tecnolog&iacute;a \r\nExperimenta los verdaderos colores del mundo con la tecnolog&iacute;a Senseye de BenQ &reg; Technology Vision Humana. Con la ayuda de sus seis t&eacute;cnicas de calibraci&oacute;n de propiedad, Senseye &reg; 3 ofrece s&oacute;lo la mejor calidad de visi&oacute;n en cada uno de sus seis pre-set-VStandard modos de visualizaci&oacute;n, Pel&iacute;cula, Juego, Foto, sRGB, y Eco-V con el Modo Eco especialmente dise&ntilde;ado para ahorrar energ&iacute;a y dinero.\r\nAhorra energ&iacute;a para ahorrar \r\nEficiencia energ&eacute;tica panel LED proporciona mayor ahorro de energ&iacute;a en un 14,3% * y con la ayuda del modo Eco, puede ahorrar hasta un 39,3% *. \r\nWindows &reg; 8 y Windows &reg; 7 compatible \r\nEl GL2023A ha pasado de Windows 8 y Windows 7 de certificaci&oacute;n y son totalmente compatibles con ambos sistemas. Enchufe el GL2023A a su ordenador, y Windows lo reconocer&aacute; al instante, haciendo que la configuraci&oacute;n y la conexi&oacute;n sin esfuerzo.\r\necofactos Label \r\necofactos puedo decirte lo ecol&oacute;gico son los productos de BenQ! \r\nDesarrollo de productos ecol&oacute;gicos, BenQ no s&oacute;lo quiere cumplir con las regulaciones verdes pasivamente, sino tambi&eacute;n para desarrollar activamente productos ecol&oacute;gicos! \r\necofactos declara BenQ mayores esfuerzos en la sustituci&oacute;n de sustancias peligrosas, selecci&oacute;n de materiales, dise&ntilde;o de packaging, dise&ntilde;o de ahorro de energ&iacute;a y otros aspectos de los productos.\r\nEnergy Star Calificado \r\nEl GL2023A cumple con los requisitos de ENERGY STAR &reg; Programa para monitores de ordenador, la versi&oacute;n 6.0. Ahora puedes disfrutar de ahorrar energ&iacute;a y dinero, as&iacute; como el mantenimiento de la tierra buscando genial! \r\n* Las especificaciones personalizadas pueden variar seg&uacute;n el modelo. Por favor refi&eacute;rase a la tabla de especificaciones para revisar cada modelo en detalle.', 8, 1, 79.95, 'En stock', 5, 1, '2013-05-13 18:06:37'),
(27, 'Benq GL2055 20" LED DVI', 'Disfrute de su casa todos los días y la vida laboral \r\nYa sea que usted está poniendo al día con el último espectáculo en el Internet o la visualización de documentos, la BenQ GL2055 LED monitor es la opción perfecta para usted con estilo! Con un ratio dinámico de 12M: 1 y un tiempo de respuesta de 5 ms, este 20 "W 16:09 monitor LED que proporciona el disfrute visual.\r\n\r\nCaracterísticas:\r\n\r\nCalidad de imagen brillante \r\nRetroiluminación LED ofrece ventajas significativas sobre la tecnología CCFL usada en monitores LCD de más edad. Estas ventajas abarcan no sólo las métricas de rendimiento, tales como un mayor contraste dinámico, sin fugas de luz y sin parpadeos, sino también factores ambientales, como un proceso de fabricación y eliminación que produce menos contaminantes.\r\n12M: 1 Ultra Alto Contraste Dinámico para Profundidad y Definición \r\nLa GL2055 viene con un ratio de contraste ultra alta dinámica del 12M: 1 para añadir profundidad de color y definición a las películas oscuras y complejas, de modo que todo lo que ves, desde el blanco más brillante al negro más oscuro, es perfectamente prestados para entregar la más clara , más suave imagen rendimiento durante las películas y los videojuegos.\r\nTiempo de Respuesta Superior \r\nDisfruta de videos de alta dinámicos sin imágenes fantasma u otros artefactos con tiempo de respuesta de 5ms.\r\nSenseye ® 3 Solución visual a sus necesidades de visualización Todos los días \r\nExperimenta los verdaderos colores del mundo con la tecnología Senseye de BenQ ° Tecnología de Visión Humana. Con la ayuda de sus seis técnicas de calibración de propiedad, Senseye 3 ° ofrece sólo la mejor calidad de visión en cada uno de sus seis visualización sRGB modos predefinidos-Estándar, Película, Juego, Foto, y Eco - con el modo Eco especialmente diseñado para ahorrar energía y dinero.\r\nAhorra energía para ahorrar \r\nEficiencia energética panel LED proporciona mayor ahorro de energía en un 46,7% *\r\n* En comparación con G2225HD\r\necofactos Label \r\necofactos puedo decirte lo ecológico son los productos de BenQ! \r\nDesarrollo de productos ecológicos, BenQ no sólo quiere cumplir con las regulaciones verdes pasivamente, sino también para desarrollar activamente productos ecológicos! \r\necofactos declara BenQ mayores esfuerzos en la sustitución de sustancias peligrosas, selección de materiales, diseño de packaging, diseño de ahorro de energía y otros aspectos de los productos.\r\nSin mercurio retroiluminada por LED\r\n> 85% papel reciclado en cada embalaje\r\nEco-friendly tinta de impresión en la caja de embalaje\r\nBFR / PVC sin revestimiento de plástico\r\nEnergy Star Calificado \r\nLa GL2055 cumple con los requisitos de ENERGY STAR ® Programa de Monitores, Versión 5.1. Ahora puedes disfrutar de ahorrar energía y dinero, así como el mantenimiento de la tierra buscando genial!', 8, 1, 99, 'Outlet', 9, 0, '2013-05-13 18:08:18'),
(28, 'LG 24MN43D-PZ Monitor/TV 23.6" LED', 'Tecnología LED\r\nRESOLUCIÓN FULL HD: MAGNÍFICA CALIDAD DE IMAGEN\r\nPIP: Visionado de varias pantallas al mismo tiempo\r\nUSB HD PLAYER: REPRODUCCIÓN DE CONTENIDO POR USB \r\nEl Monitor TV de LG te ofrece un gran surtido de entretenimiento multimedia, como emisión en HD, DVDs, Internet, juegos y fotos. El placer que experimentas es aún mayor con la calidad de imagen HD.\r\n\r\nUSB Quick View (Video, Musica, Imágenes) \r\nReproduce de manera instantánea los video clips guardados dentro de los dispositivos de memoria USB, simplemente conectando el cable USB al Monitor TV de LG. \r\nAltavoz Estéreo \r\nDisfruta de tus películas o juegos con sonido estéreo más real. Con el altavoz estéreo, no hay necesidad de altavoces adicionales alrededor del Monitor TV de LG. \r\nConexión HDMI \r\nEl sistema HDMI recibe señales claras para una mayor resolución de contenidos HD, sin distorsión o compresión.', 8, 14, 224, 'Outlet', 2, 0, '2013-05-13 18:11:25'),
(29, 'LG 23ET83V 23" IPS Táctil', 'Monitor 23" Premium IPS Táctil.\r\n\r\nControl táctil de 10 puntos\r\nTecnología IPS: Imágenes con mayor nitidez y claridad\r\nMayor ángulo de visión\r\nSuaves Cambio de color\r\nMientras que los modelos convencionales sólo ofrecen control con dos dedos para arrastrar, navegar por los menús o hacer zoom, este equipo permite al usuario usar sus 10 dedos de manera simultánea. Esto implica una mayor rapidez y comodidad a la hora de escribir con el teclado de pantalla. Así, a los usuarios que estén habituados a utilizar sus smartphones, les resultará familiar y cómodo.\r\n\r\nColores más reales \r\nComo el monitor IPS de LG presenta consistencia y menos cambios en la temperatura de color, te ofrece una impresión de color idéntica al de la imagen original. \r\nMayor ángulo de visión \r\nEl monitor IPS de LG te permite disfrutar de una calidad de imagen realista sin ningún cambio de color ni calidad de imagen independientemente del lugar o el ángulo de visión que tengas en ese momento \r\nImágenes más nítidas y claras \r\nEl monitor IPS de LG siempre proporciona imágenes claras con cambios suaves de color liso. Te permite disfrutar de cualquier contenido, como películas de acción, navegación web o juegos durante mucho tiempo con comodidad \r\nFULL HD \r\nLa resolución Full HD te trae imágenes de magnífica calidad a través de la precisión excepcional del color, los contrastes visuales y la nitidez. \r\nMega Ratio de Contraste \r\nLa tecnología Mega Ratio de contraste de LG ofrece una relación de contraste excepcional que mejora el brillo y la claridad de la imagen.', 8, 1, 427, 'En stock', 2, 1, '2013-05-13 18:12:05');
INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `categoria_id`, `fabricante_id`, `precio_venta`, `disponibilidad`, `existencias`, `activo`, `fecha`) VALUES
(30, 'B-Move 2.0 B-Blast 200 Negros', 'Los nuevos altavoces 2.0 de B-Move proporcionan un sonido de calidad con gran nitidez para llenar tus grandes momentos con tu música preferida. Perfectos para cualquier equipo y ambiente.\r\n\r\nEspecificaciones\r\n\r\nPrestaciones\r\nSistema de sonido estéreo 2.0\r\nAcabado brillante tipo "gloss"\r\nPara música, películas, videojuegos\r\nEntrada de audio y micro\r\nDetalle de acabado tipo cepillado de aluminio\r\nTamaño compacto, perfecto para viajar\r\nControl de volumen y botón ON/OFF\r\nMini conector estéreo de 3.5 mm\r\nAltavoces 2.25´´ 4 Ohm\r\nPotencia total 5W (2.5W x 2)\r\nFrecuencia 20 - 20 KHz\r\nS/N >65 dB\r\nMaterial Plástico ABS\r\nLongitud cable 1 m\r\nAlimentación USB 5V\r\nDimensiones 75 x 150 x 80 mm\r\nPeso 320 g \r\nGarantía: 2 años.', 34, 20, 8.95, 'En stock', 1, 1, '2013-05-13 18:16:20'),
(31, 'Razer Ouroboros Gaming 4G 8200 DPI', 'El Razer Ouroboros ofrece una ergonomía ambidiestra personalizable con una parte posterior y reposamanos ajustables, junto con dos paneles laterales intercambiables. Este ratón para juegos se adapta perfectamente a cualquier curvatura de mano y estilo de agarre, lo que garantiza una comodidad óptima y una fatiga mínima durante las sesiones de juego prolongadas de cualquier jugador y, en especial, las tuyas.\r\n\r\nSistema de sensor dual 4G \r\nExperimenta el nuevo estándar de juego profesional de precisión con el sistema de sensor dual 4Gde 8200 ppp, que incorpora un láser y un sensor óptico para calibrar el ratón con precisión sobre cualquier superficie y lograr un seguimiento excepcional. El Razer Ouroboros no solo está a la altura de la velocidad de tus golpes (independientemente de tu estilo de juego), sino que también ofrece una distancia de seguimiento de despegue ajustable que te proporciona más control que nunca.\r\nTecnología inalámbrica con calidad para juegos de 1 ms \r\nLa incomparable tecnología inalámbrica con calidad para juegos del Razer Ouroboros hace que funcione tan bien con cable como sin cable, lo que te proporciona una libertad de movimientos total para dominar las competiciones. Con un tiempo de respuesta de 1 ms, tu comando se ejecutará en primer lugar incluso si tu rival y tú reaccionáis al mismo tiempo. Cuando tengas que cargar el ratón, utiliza su elegante base o enchufa el cable para seguir jugando. ', 35, 1, 139, 'En stock', 3, 1, '2013-05-13 18:18:33'),
(32, 'Razer Nostromo Gaming Keyboard', 'As&iacute; tu pasi&oacute;n sean los juegos de shooter en primera persona (FPS), los juegos de rol multijugador masivo en l&iacute;nea (MMORPG) o en los juegos de estrategia en tiempo real (RTS), el teclado para juegos Razer Nostromo&reg; est&aacute; ideado para darte la ventaja en el juego con el dise&ntilde;o ergon&oacute;mico m&aacute;s avanzado para control de juego intuitivo, mientras juegas c&oacute;modamente. Gracias a su arsenal de controles de nivel profesional entre los que se incluyen 16 teclas Hyperesponse totalmente programables, 8 mapas de teclado que se pueden cambiar sobre la marcha y apoyo para pulgar de ocho v&iacute;as (todo esto integrado en un teclado espec&iacute;fico para juegos), estar&aacute;s preparado para enfrentarte a todos los retadores.\r\n\r\nDise&ntilde;o ergon&oacute;mico para precisi&oacute;n y comodidad m&aacute;ximas \r\nLa disposici&oacute;n de los 16 botones y el dise&ntilde;o ergon&oacute;mico del teclado Razer Nostromo est&aacute;n optimizados para un acceso sencillo e intuitivo a los comandos de juego m&aacute;s importantes, lo que mejora la precisi&oacute;n al activar las teclas, a la vez que permite jugar con comodidad. Y para esas jornadas marat&oacute;nicas eliminando enemigos, un descansa-mu&ntilde;ecas con una suave superficie de goma se adapta a la palma de la mano para reducir notablemente la fatiga y la tensi&oacute;n en la mu&ntilde;eca, para que puedas avasallar a tus oponentes sin pausa ni compasi&oacute;n.\r\n16 teclas totalmente programables con cambio del mapa de teclas sobre la marcha \r\nLos 16 botones de juego Hyperesponse totalmente programables y el apoyo para pulgar de ocho v&iacute;as del Razer Nostromo est&aacute;n dise&ntilde;ados para un desempe&ntilde;o r&aacute;pido y una respuesta precisa de las teclas. El apoyo para pulgar de ocho v&iacute;as, que se puede usar tanto para realizar movimientos como con las teclas del modificador perfecciona tus armas de destrucci&oacute;n masiva. Adem&aacute;s, puedes cambiar de forma instant&aacute;nea entre 8 mapas de teclas asignando los diferentes cambios de mapa de teclas a cualquier bot&oacute;n del teclado.\r\nPersonaliza tu juego con el software configurador de Razer \r\nEl nuevo software configurador de Razer Nostromo es tan f&aacute;cil de usar como letal, ya que te permite personalizar todos los botones con cualquier comando de juego o uniones de teclas para poner un gigantesco repertorio de impresionantes combinaciones al alcance de tus dedos.', 9, 1, 41, 'En stock', 4, 1, '2013-05-13 18:20:26'),
(33, 'Creative SB Recon 3D Omega Wireless G-Headset XBOX/PS3/PC', 'Los definitivos auriculares inalámbricos para juegos \r\nCon la tecnología del revolucionario chip de audio y voz de cuatro núcleos Sound Core3DTM , el sistema de audio Sound Blaster Recon3D Omega Wireless es el pionero en esta nueva era de sonido para juegos, ya que incluye en un sistema de sonido inalámbrico para Xbox 360, Playstation 3, PC o Mac, la asombrosa capacidad de procesamiento de sonido que antes solo estaba disponible en tarjetas internas de audio para PC. Las tecnologías aceleradas por hardware THX Surround, THX Crystalizer y otras tecnologías THX mejoran enormemente el sonido normal de los juegos, dejando atrás los sistemas de audio estándar 5.1 y 7.1. Una vez hayas sentido el poder del sonido para juegos acelerado por hardware Sound Core3D, no te podrás conformar con menos. Únete a los 400 millones de usuarios de Sound Blaster y prepárate para la legendaria calidad de sonido para juegos.\r\n\r\nCon la tecnología del revolucionario procesador de audio de cuatro núcleos Sound Core3DTM: el innovador chip de audio y voz Sound Core3DTM permite, por primera vez en la historia, disfrutar de las ventajas de una tarjeta de sonido interna en un dispositivo USB externo. Sea cual sea la plataforma en la que juegues, ya tienes disponible el audio para juegos de Sound Blaster.\r\nTecnologías aceleradas por hardware THX® TruStudio Pro: las tecnologías de sonido THX® TruStudio Pro crean niveles de realismo sin precedentes en el audio e incluyen efectos de sonido envolvente extraordinarios que superan con creces el audio estándar 5.1 y 7.1. Así se genera la sensación de estar rodeado por cientos de altavoces a la vez.\r\nJuegos multiplataforma con decodificación por hardware Dolby Digital: convierte cualquier fuente estéreo o Dolby Digital 5.1 en un sonido envolvente de alta fidelidad THX para tu Xbox 360, Playstation 3, PC o Mac.\r\nJuegos con auriculares Tactic LinkTM Wireless: la tecnología inalámbrica sin comprimir TacticLinkTM proporciona un audio para juegos dinámico y sin retraso en Xbox 360, PlayStation 3, PC o Mac.', 38, 1, 199, 'En stock', 2, 1, '2013-05-13 18:26:05'),
(34, 'Canon CanoScan LiDE 210', 'Canon CanoScan LiDE 210 \r\nCanoScan Escaner Planos \r\n\r\nEste escáner LiDE ofrece resolución a 4800 ppp y un soporte para escaneo vertical y ahorro de espacio. Es aún más fácil de usar gracias al Auto Scan Mode y la reducción de polvo y arañazos.\r\n\r\nCaracterísticas\r\n\r\nResolución 4800 x 4800 ppp con color a 48 bits\r\nEscaneo de un A4 a 300 ppp en 10 seg\r\n5 botones EZ de acceso rápido para Escaneo Automático, copia, correo elec. y 2 para PDF\r\nEscaneo vertical\r\nReducción intuitiva de polvo y arañazos\r\nAuto Scan Mode: escanea y guarda fácilmente\r\nEscáner LiDE versátil y de alta resolución para uso habitual\r\n\r\nEscaneo productivo y de calidad \r\nEscanea fácilmente todas tus fotografías y documentos con una resolución de 4800 x 4800 ppp. Gracias a su profundidad de color interna de 48 bits, las fotografías poseen un nivel excepcional de detalle. El sensor de imagen y la superficie de escaneo tienen el mismo ancho, lo que garantiza una reproducción precisa y de calidad en todas las fotos. \r\nPuede realizar escaneos rápidos de documentos de tamaño A4 a 300 ppp en sólo 10 segundos.\r\nCompacto y elegante \r\nEl escáner cuenta con un diseño elegante y ultra compacto que hace que resulte el complemento ideal en cualquier hogar. El soporte vertical garantiza que ocupe un espacio mínimo en el escritorio. A su vez, la tapa integrada permite escanear todo tipo de documentos (como libros), sin comprometer su elegante apariencia.\r\nFácil de usar \r\nEste escáner hace que todo resulte sencillo gracias a los 5 botones de una sola pulsación. El botón de Escaneo Automático reconoce automáticamente el tipo de original y posibilita el escaneo de fotos y documentos con un solo clic. Los botones de copia, correo electrónico y los dos de creación de PDF consiguen que todo sea igualmente sencillo.\r\nMejoras de la imagen para ahorrar tiempo \r\nEs posible eliminar el polvo y los arañazos en sus fotos, para mejorar las imágenes. Esta función reduce el polvo y los arañazos automáticamente, y ayuda a devolver el color original a las fotografías antiguas. \r\nAuto Document Fix es una tecnología que mejora por separado los textos, gráficos e imágenes de los documentos escaneados. \r\nEntre otras soluciones para ahorrar tiempo se encuentran Auto Photo Fix II y Solution Menu EX.\r\nAlimentación por USB \r\nLa alimentación y la conexión del escáner se realizan mediante un único cable USB, lo que reduce el número de cables y el desorden.', 43, 24, 84, 'En stock', 3, 1, '2013-05-13 18:27:17'),
(35, 'Genius MetalStrike 3D - Joystick', 'Joystick USB para PC \r\nDistinto a todos los joysticks est&amp;aacute;ndares del mercado, el nuevo joystick de Genius MetalStrike 3D es ideal para los juegos de simulaci&amp;oacute;n de vuelo de dise&amp;ntilde;o a cuatro ejes y dispone de controles de aler&amp;oacute;n, elevaci&amp;oacute;n, aceleraci&amp;oacute;n y potencia. \r\nLa funci&amp;oacute;n turbo de autorepetici&amp;oacute;n puede utilizarse tanto en juegos de acci&amp;oacute;n de vuelo o juegos de combates a&amp;eacute;reos adem&amp;aacute;s de para disparar potentes armas en formas de misil.Adem&amp;aacute;s, MetalStrike 3D cuenta con 13 botones programables, y un hat switch de 8 direcciones para que pueda volar su nave como un avi&amp;oacute;n de verdad. \r\nDeje el teclado y el rat&amp;oacute;n a un lado mientras vuela; simplemente sostenga el MetalStrike 3D como a un joystick de verdad, ponga los botones de disparo en la posici&amp;oacute;n turbo &amp;iexcl;y disfrute del combate a&amp;eacute;reo!\r\n\r\nCaracter&amp;iacute;sticas:\r\n\r\n4 ejes: X, Y, Z, y tim&amp;oacute;n para control del aler&amp;oacute;n, elevador, aceleraci&amp;oacute;n y potencia ideal para juegos de simulaci&amp;oacute;n de vuelo.\r\nFunci&amp;oacute;n turbo de autorepetici&amp;oacute;n - &amp;oacute;ptimo para disparar en juegos de vuelo.\r\n13 botones programables incluyendo gatillo de disparo, cuatro botones de disparo y ocho botones de base.\r\nHat switch de 8 direcciones para cambiar los puntos de vista mientras vuela.', 37, 15, 15.25, 'En stock', 4, 1, '2013-05-13 18:30:01'),
(36, 'Natec Giraffe Micrófono de sobremesa', 'El micrófono omnidireccional con una gama de frecuencias de 100 a 16.000 Hz. Tiene incorporado un interruptor on / off y un cable largo con trenzas textiles (2m) y para ajustar la posición del micrófono.\r\n\r\nEspecificaciones:\r\n\r\nMicrófono estéreo no\r\nTipo de micrófono Omnidireccional\r\nTernura 58\r\nMin Respuesta de frecuencia [Hz] 100\r\nRango de frecuencia [Hz] 16 000 \r\nImpedancia: 2,2 kOhm\r\nColor Negro\r\nConstrucción Ajuste de la posición\r\nLa fijación Base\r\nDe encendido / apagado', 47, 1, 4.95, 'Descatalogado', 0, 0, '2013-05-13 18:31:21'),
(37, 'Ovislink Cobalt 780VA', 'El SAI Cobalt 780 E, con una potencia de 780 VA / 390W, se ha diseñado especialmente para la protección de ordenadores tanto en entornos profesionales como domésticos. \r\nPor su reducido tamaño, se puede instalar fácilmente al lado de tu ordenador y otros dispositivos. \r\nIncluye 2 enchufes de red eléctrica (estándar europeo), que garantizan la seguridad del suministro para tus equipos informáticos.\r\n\r\nEspecificaciones:\r\n\r\nControl de energía\r\nCapacidad de potencia de salida (VA)* 780 VA\r\nPotencia de salida* 390 W\r\nVoltaje de entrada de operación (max)* 230 V\r\nVoltaje de operación de salida (max)* 230 V\r\nFuente de alimentación, frecuencia de entrada* 50/60 Hz\r\nCorriente de entrada THD 25 %\r\nSalida de voltaje THD 10 %\r\nConectividad\r\nCantidad de salidas AC* 2\r\nTipo de salida AC* Type F (Schuko)\r\nColor del producto * Negro, Azul\r\nPeso y dimensiones\r\nPeso 5.3 kg\r\nAncho 95 mm\r\nProfundidad 340 mm\r\nAltura 165 mm\r\nCondiciones ambientales\r\nIntervalo de temperatura operativa 0 - 40 °C\r\nIntervalo de humedad relativa para funcionamiento 20 - 90 %\r\nOtras características\r\nVoltaje nominal de salida 230 V\r\nVoltaje nominal de entrada 230 V', 41, 1, 47, 'En stock', 2, 1, '2013-05-13 18:32:54'),
(38, 'APC Smart-UPS 1500VA LCD 230V', 'Protección eléctrica inteligente y eficiente de la red desde el nivel básico hasta el tiempo de autonomía escalable. Ideal para servidores, puntos de venta, routers, conmutadores, concentradores y otros componentes de red.\r\n\r\nEl premiado Smart-UPS® de APC es el SAI más popular del mundo para servidores, unidades de almacenamiento y redes. Encargado de proteger equipos y datos críticos contra problemas eléctricos garantizando un suministro eléctrico limpio y fiable adecuado para redes. Además de su legendaria fiabilidad y manejabilidad, los Smart-UPS presentan una elevadísima eficiencia a niveles de carga bajos, medios y altos, lo que les hace ideales para los actuales servidores multifilares o virtualizados de consumo variable. Disponibles en diversos factores de forma (torre, rack, torre/rack convertible), hay un modelo para todas las aplicaciones y presupuestos. Los Smart-UPS de montaje en rack son ideales para el suministro eléctrico de servidores de tarjetas blade o de densidad optimizada en un entorno de protección eléctrica distribuida como protección primaria o redundante. Los modelos Smart-UPS® se consideran desde hace tiempo la referencia para todos los SAI de redes y servidores. Los Smart-UPS presentan un indicador LED de 16 segmentos, regulación automática de tensión (AVR), y salida de onda sinusoidal pura en la batería. Opciones de gestión adicionales disponibles a través de la interfaz Smart-Slot, que es una ranura interna para la instalación de tarjetas accesorias opcionales. El modelo Smart-UPS XL de autonomía extendida puede agregar baterías externas para escalar el tiempo de autonomía de minutos a horas, como suele ser necesario para redes convergentes de voz y datos. El sistema Smart-UPS SC de nivel básico es una opción económica para pequeñas y medianas empresas que quieran proteger pequeños conmutadores, componentes de red y terminales punto de venta (TPV).', 41, 1, 555, 'En stock', 2, 1, '2013-05-13 18:33:52'),
(39, 'HP Officejet 6600 Multifunción WiFi+Fax', 'Obtenga una calidad en color profesional a un bajo coste por página con esta eficiente multifuncional con conexión web. Utilice la pantalla táctil para acceder a las aplicaciones y a las herramientas de escaneo, copia y fax. Imprima sobre la marcha con HP ePrint y comparta a través de su red inalámbrica. \r\nLa impresora multifuncional con conexión web HP Officejet 6600 está diseñada para usuarios de empresas pequeñas y muy pequeñas que buscan una multifuncional con conexión web que realice una impresión en color profesional y a bajo coste por página, a través de una red inalámbrica o directamente desde dispositivos móviles.', 33, 1, 79.95, 'Outlet', 5, 0, '2013-05-13 18:36:42'),
(40, 'AMD Sempron 145 2.8Ghz AM3 Box', 'AMD Sempron 145 (SDX145HBGMBOX)\r\n\r\nSpecifications:\r\n\r\nType CPU / Microprocessor \r\nFamily AMD Sempron\r\nModel number  145 \r\nFrequency (MHz)  2800\r\nBus speed (MHz)  One 2000 MHz 16-bit HyperTransport link \r\nSockets\r\nSocket AM2+\r\nSocket AM3\r\nWeight 1.4oz / 39.5g \r\nData width 64 bit\r\nNumber of cores 1\r\nFloating Point Unit Integrated\r\nLevel 1 cache size \r\n64 KB code cache\r\n64 KB data cache\r\nLevel 2 cache size 1 MB\r\nCache latency\r\n3 (L1 cache)\r\n15 (L2 cache) \r\nThermal Design Power (W)  45', 11, 26, 33, 'En stock', 8, 1, '2013-05-13 18:37:39'),
(41, 'AMD APU A4-3300 2.5Ghz Socket FM1', 'Especificaciones:\r\n\r\nModelo: APU AMD A4-3300 APU with AMD RadeonTM HD 6410D\r\nSocket : FM1\r\nCache: 1MB\r\nCore: 2\r\nCPU Clock: 2500 MHz \r\nCMOS: 32nm SOI\r\nFusion Control Hubs: D2/D3 FCH\r\nNota: Compruebe que su placa base y version de BIOS son compatibles con este procesador, puede hacerlo a traves de la pagina web del fabricante de su placa base. Tambien prodra actualizar desde la misma pagina web su version de BIOS en caso de no estar actualizada.', 11, 26, 41, 'En stock', 2, 1, '2013-05-13 18:38:42'),
(42, 'AMD Phenom II X4 965 Quad-Core Black Edition', 'AMD Phenom II 965 X4\r\n\r\nSpecifications:\r\n\r\nType CPU / Microprocessor\r\nFamily AMD Phenom II X4\r\nModel number 965\r\nFrequency (MHz)  3400\r\nBus speed (MHz)\r\n667 MHz Memory controller\r\nOne 1800 MHz 16-bit HyperTransport link\r\nPackage 938-pin organic micro-PGA\r\nSockets\r\nSocket AM2+\r\nSocket AM3\r\nData width 64 bit\r\nNumber of cores 4\r\nFloating Point Unit Integrated\r\nLevel 1 cache size ? 4 x 64 KB 2-way associative instruction caches\r\n4 x 64 KB 2-way associative data caches\r\nLevel 2 cache size ? 4 x 512 KB 16-way associative caches\r\nLevel 3 cache size 6 MB shared 48-way associative cache \r\nMaximum operating temperature (°C)  62\r\nThermal Design Power (W)  125W \r\n*INCLUYE VENTILADOR ORIGINAL AMD', 11, 26, 79, 'En stock', 12, 1, '2013-05-13 18:39:16'),
(43, 'Intel Dual Core G2130 3.2Ghz Box Socket 1155', 'General information \r\n\r\nFrequency (MHz) 3200\r\nSocket Socket 1155 (Socket H2)\r\nArchitecture / Microarchitecture \r\n\r\nMicroarchitecture Ivy Bridge\r\nProcessor core Ivy Bridge\r\nManufacturing process 0.022 micron\r\nData width 64 bit\r\nNumber of cores 2\r\nFloating Point Unit Integrated\r\nLevel 1 cache size \r\n2 x 32 KB instruction caches\r\n2 x 32 KB data caches\r\nLevel 2 cache size  2 x 256 KB\r\nLevel 3 cache size 3 MB\r\nMultiprocessing Not supported\r\nFeatures\r\nMMX instructions\r\nSSE / Streaming SIMD Extensions\r\nSSE2 / Streaming SIMD Extensions 2\r\nSSE3 / Streaming SIMD Extensions 3\r\nSSSE3 / Supplemental Streaming SIMD Extensions 3\r\nSSE4 / SSE4.1 + SSE4.2 / Streaming SIMD Extensions 4\r\nEM64T / Extended Memory 64 technology / Intel 64\r\nNX / XD / Execute disable bit\r\nVT-x / Virtualization technology\r\nOn-chip peripherals\r\nIntegrated graphics\r\nGPU Type: HD (Ivy Bridge)\r\nBase frequency: 650 MHz\r\nMaximum frequency: 1050 MHz\r\nMemory controller\r\nThe number of controllers: 1\r\nMemory channels: 2\r\nSupported memory: DDR3-1333, DDR3-1600\r\nOther peripherals\r\nDirect Media Interface\r\nPCI Express 2.0 interface', 11, 27, 86, 'En stock', 11, 1, '2013-05-13 18:41:07'),
(44, 'ASRock Fatal1ty Z77 Professional-M', 'Caracter&iacute;sticas:\r\n\r\nCapacitadores Premium Gold\r\nDigi Power Design\r\nSoporta DDR3 2800+(OC) Dual Channel\r\n2 x ranuras PCIe 3.0 x16, Soporta AMD Quad CrossFireXTM, 3-Way CrossFireX, CrossFireXTM y NVIDIA&reg; Quad SLITM, SLITM\r\nPCIE Gigabit LAN\r\nSoporta Intel&reg; HD Graphics Built-in Visuals\r\nM&uacute;ltiples opciones de salida VGA : DVI-D, D-Sub, HDMI\r\nOpci&oacute;n C.C.O (Combo Cooler Option)\r\nAudio 7.1 CH HD con protecci&oacute;n de contenido (Realtek ALC898 Audio Codec), Soporta THX TruStudioTM\r\nSoporta ASRock XFast RAM, XFast LAN, XFast USB, OMG, Internet Flash, Deshumidificador, Sistema Navegador UEFI\r\nSoporta Intel&reg; Smart Connect, Intel&reg; Rapid Start, Lucid Virtu Universal MVP\r\nBundle Gratis : Prueba CyberLink MediaEspresso 6.5, Suite Multimedia ASRock MAGIX', 7, 1, 128, 'En stock', 8, 1, '2013-05-13 18:42:34'),
(45, 'WD Caviar Green 2TB SATA3 64MB Recertified', 'Caviar Green ... Fresco, silencioso y ecológico!!\r\n\r\nA medida que aumentan las capacidades de los discos duros, también aumenta la energía necesaria para hacerlos funcionar. Los discos WD Caviar Green hacen posible que nuestros clientes, conscientes de la importancia del ahorro de energía, construyan sistemas con mayor capacidad y consigan el perfecto equilibrio entre el rendimiento del sistema, la fiabilidad garantizada y el ahorro de energía.\r\n\r\nMenor consumo de energía \r\nWD ha reducido el consumo de energía hasta el 40 por ciento en comparación con los discos de escritorio estándar por la combinación de las tecnologías IntelliSeekTM, NoTouchTM e IntelliPower de WD.\r\nAyuda a que los ordenadores se vuelvan más respetuosos con el medio ambiente \r\nAl utilizar ordenadores ecológicos con nuestros discos WD Caviar Green, las grandes organizaciones que cuentan con muchos ordenadores de sobremesa pueden minimizar sus emisiones de carbono y ahorrar gran cantidad de dinero en costes de electricidad.\r\nFrío y silencioso \r\nWD GreenPower TechnologyTM produce temperaturas operativas más bajas para una mayor fiabilidad y un nivel de ruido muy reducido para ordenadores y discos externos ultrasilenciosos.\r\nPerfecto para discos externos \r\nLos fabricantes de discos externos pueden eliminar la necesidad de un ventilador en un producto de alta capacidad al usar un disco WD Caviar Green, el más frío y silencioso de su clase.\r\nIntelliPower \r\nUn equilibrio adecuado entre velocidad de rotación, velocidad de transferencia y algoritmos de caché diseñado para contribuir al ahorro de energía y proporcionar un rendimiento uniforme. Adicionalmente, los discos WD Caviar Green consumen menos energía durante el arranque, lo que permite que las cargas de los sistemas sean menores en el momento de arrancar.\r\nIntelliSeek \r\nCalcula las velocidades de búsqueda óptimas para reducir el consumo de energía, el ruido y las vibraciones\r\nTecnología NoTouch con rampa para carga del cabezal \r\nEl cabezal de grabación nunca entra en contacto con el medio del disco, lo que asegura un desgaste muy inferior del cabezal de grabación y del medio, así como una mejor protección del disco durante el traslado.\r\nStableTracTM \r\nAsegura ambos extremos del eje del motor para reducir la vibración inducida por el sistema, y estabiliza los platos para obtener un seguimiento preciso durante las operaciones de lectura y escritura.\r\nTecnología energética avanzada \r\nLos discos WD Caviar Green consumen menos energía durante el arranque, lo que permite que haya unas cargas máximas más bajas. Los componentes electrónicos ofrecen el consumo de energía más bajo de su clase para reducir los requisitos de energía y aumentar la fiabilidad.', 16, 1, 79, 'Outlet', 8, 0, '2013-05-13 18:44:04'),
(46, 'Gigabyte Radeon HD 7970 OC GHz Edition 3GB GDDR5', 'Caracter&iacute;sticas:\r\n\r\nGIGABYTE Ultra Durable VGA Series\r\nPowered by AMD Radeon HD 7970 GHz Edition GPU\r\nCore clock OC to Base 1050 / Boost 1100 MHz\r\nIntegrated with the first 3GB GDDR5 memory and 384-bit memory interface\r\nSupport AMD Eyefinity Technology and AvivoTMHD\r\nFeatures Dual-link DVI-I / HDMI / mini DisplayPort * 2\r\nSystem power supply requirement: 600W', 17, 1, 380, 'En stock', 8, 1, '2013-05-13 18:45:41'),
(47, 'NOX Zen', 'Semitorre ATX, Micro ATX\r\nBahías:\r\nExternas: 1 x 5.25", 1 x 3.5"\r\nInternas: 6 x 3.5", 1 x 5.25"\r\n Sistema de ventilación:\r\nTrasero: 1 x 80 mm (Incluido)\r\nLateral: 1 x 120 mm (Opcional)\r\n7 slots de expansión\r\nMaterial: Chasis acero SGCC y frontal de plástico\r\nPuertos: 2 x USB 2.0 + HD Audio\r\nDimensiones: 390 x 175 x 415 mm\r\nPeso: 4.0 Kg\r\nInteriores en color negro\r\nCompatible con gráficas de gran tamaño (290 mm)\r\nBotón power LED azul\r\nPanel frontal en color negro brillante', 23, 1, 22, 'En stock', 3, 1, '2013-05-13 18:51:37'),
(48, 'G.Skill TridentX DDR3 2600 PC3-20800 16GB 4x4GB CL10', 'XMP Ready 1.3 para Intel Ivy Bridge y la plataforma del procesador Z77', 18, 1, 238, 'En stock', 12, 1, '2013-05-13 19:01:25'),
(49, 'G.Skill RipjawsZ DDR3 2133 PC3-17000 16GB 4x4GB CL9', 'Excelente kit de memorias de 16GB compuesto por 4 m&oacute;dulos de 4GB exactamente iguales, con latencias bajas para un rendimiento &oacute;ptimo. Adem&aacute;s incluyen el disipador RipjawsZ de Gskill que provee de una gran eficiencia para evitar calentamientos en largas e intensas sesiones con el PC \r\n\r\nQuad Channel Memory Designed For Intel X79 platform Intel XMP 1.3 Ready\r\n\r\nSystem Desktop\r\nSystem Type DDR3\r\nM/B Chipset Intel X79\r\nCAS Latency 9-11-10-28-2N\r\nCapacity 16GB (4GB x4)\r\nSpeed DDR3-2133 (PC3 17000)\r\nTest Voltage 1.65 Volts\r\nHeight 40 mm / 1.58 inch\r\nRegistered/Unbuffered Unbuffered\r\nError Checking Non-ECC\r\nType 240-pin DIMM\r\nWarranty Lifetime\r\nFeatures Intel XMP (Extreme Memory Profile) Certified', 18, 1, 145, 'En stock', 2, 1, '2013-05-13 19:02:42'),
(50, 'Aerocool Xpredator X3 Devil Red Edition', 'Características:\r\n\r\nChasis interior sólidamente construido (0.7 mm SECC)\r\nCapacidad para refrigerador de CPU de hasta 186 mm de altura como máximo (sin ventilado-res laterales)\r\nCapacidad para tarjetas VGA largas de hasta 310 mm.\r\nIncluye 1 x 12 cm ventilador trasero y 1 x 12 cm ventilador LED en la parte frontal\r\nLas tapas superiores pueden abrirse o cerrarse para un control del &#64258;ujo del aire más e&#64257;ciente\r\nFiltro de polvo para PSU.\r\nIncluye 1 puerto externo 2.5" HDD en la parte superior para su fácil acceso.\r\nIncluye dos reguladores que controlan cada uno de los ventiladores hasta los 25W.\r\nSe pueden instalar hasta 9 ventiladores.\r\nIncluye almohadillas anti vibración para HDDs y PSU.\r\nSistema de gestión de cables mediante agujeros con arandelas de goma de gama alta para prote-ger los cables.\r\nAgujeros pre-taladrados en CPU que facilitan el montaje y extracción del refrigerador.\r\nCapacidad para sistemas de refrigeración líquida\r\n2xUSB 3.0 y puertos Audio/Mic fácilmente accesibles desde el panel superior.\r\nUSB 3.0 > USB 2.0 adaptador incluido', 23, 1, 89.95, 'Outlet', 2, 0, '2013-05-13 19:04:17'),
(51, 'Cooler Master HAF-X - Caja/Torre', 'La esperada HAF X llega como el buque insignia de la popular serie HAF (de high air flow). Diseñado para los sistemas de alto rendimiento, este chasis es capaz de albergar los componentes más extremos: placas base, tarjetas gráficas, etc. Además es incluso compatible con dispositivos USB 3.0. Diseñado para dar un flujo interior de aire masivo, cuenta con 4 ventiladores enormes de baja sonoridad para que sus componentes no sufran lo más mínimo las altas temperaturas. \r\n\r\nCaracterísticas\r\n\r\nDock SATA para una fácil instalación sin necesidad de quitar el panel lateral\r\nConexión USB 3.0 en la parte frontal\r\nConductos de aire para enfriar la zona de las tarjetas gráficas\r\nVentilador de 230 mm ventilador LED rojo y 3 ventiladores de 200 mm\r\n9 slots para soportar hasta 3 VGA\r\nSoporte ajustable para tarjetas gráficas para fijar las más pesadas tarjetas gráficas\r\nCompartimento para ocultar los cables de la fuente de alimentación\r\nGestión avanzada de cable con arandelas de goma\r\nInterior amplio para dar cabida a los componentes más grandes\r\nRobusto revestimiento interior negro', 23, 1, 140, 'Agotado', 0, 1, '2013-05-13 19:06:24'),
(52, 'Asus GeForce GTX 680 DirectCU II TOP 2GB GDDR5', 'La gráfica ASUS GeForce® GTX 680 DirectCU II TOP ofrece un rendimiento gráfico tope de gama para entusiastas. ASUS ha acelerado de fábrica la GPU 28nm NVIDIA® GeForce® GTX 680 a 1201MHz (143MHz más que el diseño de referencia), lo que permite alcanzar más imágenes por segundo durante las partidas. También ha incorporado al diseño el diseño térmico DirectCU II, que rebaja la temperatura un 20% y 14dB de ruido respecto al diseño de referencia. Por si fuera poco, ASUS también ha añadido el control de alimentación digital DIGI+ VRM por 10 fases, que reduce el ruido un 30% y los componentes Super Alloy Power, con una vida útil 2.5 veces más larga. Los usuarios podrán realizar ajustes de rendimiento a nivel hardware vía VGA Hotwire y software mediante la utilidad GPU Tweak. \r\n\r\nASUS también ha presentado la ASUS GeForce® GTX 680 DirectCU II OC, con un núcleo a 1019MHz capaz de acelerar su reloj a 1084MHz. Este modelo también incluye la tecnología de refrigeración DirectCU II y la misma PCB que la versión TOP.', 17, 1, 509, 'En stock', 1, 1, '2013-05-13 19:08:26'),
(53, 'MSI GT70-1053ES Dragon i7-3630/16GB/750GB+SSD/GTX 680/17.3"', 'Incluye Pack de regalo con una Mochila Gaming + Auriculares + el cup&oacute;n de juego Fire Fall + Alfombrilla, para que no te falte de nada cuando hagas tus salidas gaming!!! \r\n\r\nLos notebook gaming MSI son el est&aacute;ndar en port&aacute;tiles gaming. M&aacute;xima potencia con las &uacute;ltimas tarjetas gr&aacute;ficas NVIDIA y la tercera generaci&oacute;n de procesadores Intel mobile, asgur&aacute;ndole al Gamer el m&aacute;ximo de FPS, una imagen espectacular y una gran experiencia gaming. En los port&aacute;tiles gaming MSI siempre ha suficiente almacenamiento para tus juegos y una gran conectividad para perfif&eacute;ricos gaming como auriculares y ratones. Junto con un teclado SteelSeries y un sistema de sonido espectacular dise&ntilde;ado por Dynaudio.\r\n\r\nEspecificaciones:\r\n\r\nProcesador Intel&reg; CoreTM i7 - 3630QM\r\nMemoria RAM 16 GB (DDR III 8GB*2)\r\nDisco duro Super Raid Dual 64GB SSD (mSATA3)+750GB (SATA) 7200rpm\r\nAlmacenamiento &oacute;ptico Blu-Ray Writer\r\nDisplay 17.3" Full-HD (Mate, 1920x1080)\r\nControlador gr&aacute;fico\r\nNVIDIA&reg; GeForce&reg; GTX680M con 4GB de memoria\r\nVGA\r\nHDMI\r\nConectividad\r\nKillerTM DoubleShot configuration (KillerTM Wireless-N 1202 paired with KillerTM E2200)\r\n802.11 b/g/n\r\nBluetooth 4.0\r\nC&aacute;mara de port&aacute;til S&iacute;\r\nMicr&oacute;fono S&iacute;\r\nBater&iacute;a 9 celdas Ion de litio\r\nConexiones\r\n1 x VGA\r\n1 x HDMI\r\n1 x salida de auriculares\r\n1 x entrada de micr&oacute;fono\r\n2 x USB 2.0\r\n3 x USB 3.0\r\n1 RJ45\r\nLector de Tarjetas 4-en-1\r\nSistema operativo Microsoft Windows 8\r\nDimensiones (Ancho x Profundidad x Altura)  428 x 288 x 55mm\r\nPeso 3.9 kg\r\nColor Negro/Rojo', 52, 1, 2145, 'En stock', 2, 1, '2013-05-13 19:10:47'),
(54, 'Sony PS3 Playstation 3 Slim 500GB', 'La revolución llega a PlayStation 3. La nueva Ps3 de 500Gb es más ligera y mucho más fina que su predecesora, lo que la hace mucho más manejable. Ahora con WiFi 802.11n.\r\n\r\nEl nuevo diseño, tamaño, precio y capacidad hace que se convierta en uno de los sistemas de entretenimiento más irresistibles del mercado. \r\nCon Ps3, el juego es sólo el principio ya que te permite disfrutar de la última generación de entretenimiento interactivo que va desde juegos y películas en alta definición gracias al formato Blu-ray Disc hasta las cada vez más numerosas comunidades sociales, pasando por el sencillo sistema de almacenamiento de música, vídeos y fotos.\r\n\r\nVideos: Gracias al reproductor de Blu-ray que incorpora podrás ver todas tus películas en alta definición.\r\nMúsica: ¡Tu sistema PS3 te permite reproducir y almacenar tu música preferida!\r\nFotos: Esta opción no sólo te permite visualizar las imágenes almacenadas en el disco duro de 500Gb de tu sistema PS3, sino que también puedes utilizar las diferentes opciones de presentación disponibles.\r\nPlayStation Network: Es un entorno interactivo donde puedes disfrutar de juegos online, charlar con tus amigos y navegar por la red... Sólo tienes que inscribirte en PlayStation Network de forma gratuita para comenzar a disfrutar de este fantástico universo online.\r\nVidzone: Es un nuevo servicio gratuito de videoclips que te permite ver, pausar y rebobinar miles de videos de tus artistas preferidos, así como crear listas de reproducción ilimitadas para todas las ocasiones. Cuando te descargues esta aplicación gratuita, aparecerá un icono de VidZone debajo de la sección Música del menú principal de tu PS3. A partir de este momento tendrás acceso a la biblioteca de vídeos musicales de Vidzone que se actualiza periódicamente.\r\nNota: Según disponibilidad, el mando Sony incluido puede ser de otro color diferente al habitual.', 61, 6, 219, 'Agotado', 0, 1, '2013-05-13 19:19:15'),
(55, 'Apple iPhone 5 16GB Blanco', 'Lo m&aacute;s grande que le ha pasado al iPhone desde el iPhone. \r\nEl iPhone 5 incluye una pantalla Retina de 4 pulgadas, el potente chip A6, una c&aacute;mara iSight de 8 megap&iacute;xeles con funci&oacute;n panor&aacute;mica, conexi&oacute;n inal&aacute;mbrica ultrarr&aacute;pida, iOS 6 y iCloud. Y, con todo eso, es el iPhone m&aacute;s fino y ligero hasta ahora.\r\n\r\nCaracter&iacute;sticas:\r\n\r\nNuevo dise&ntilde;o. \r\nCon solo 0,76 cm de grosor y 112 gramos de peso,2 el iPhone 5 es incre&iacute;blemente fino y ligero. Su carcasa es de aluminio anodizado y sus bordes biselados se han tallado con diamante.\r\nEspectacular pantalla Retina de 4 pulgadas. \r\nAhora podr&aacute;s ver los colores m&aacute;s vivos y las im&aacute;genes m&aacute;s realistas en una pantalla m&aacute;s grande, pero igual de ancha que la del iPhone 4S para que puedas usarlo con una mano.\r\nChip A6. \r\nEl rendimiento gr&aacute;fico y el de la CPU son hasta el doble que con el chip A5, y aun as&iacute; la autonom&iacute;a sigue siendo igual de incre&iacute;ble.\r\nConexi&oacute;n inal&aacute;mbrica ultrarr&aacute;pida. \r\nEl iPhone 5 es compatible con las tecnolog&iacute;as inal&aacute;mbricas m&aacute;s modernas, as&iacute; que podr&aacute;s conectarte a m&aacute;s tipos de redes en todo el mundo,4 y la conexi&oacute;n Wi-Fi tambi&eacute;n es m&aacute;s r&aacute;pida.', 59, 1, 555, 'En stock', 17, 1, '2013-05-13 19:22:44'),
(56, 'HP CD973AE Nº920XL Magenta', 'Los cartuchos de tinta magenta HP 920XL imprimen documentos profesionales a color con un coste inferior al láser, con tintas HP Officejet. Las tintas HP Officejet originales han sido formuladas para producir documentos de secado rápido, especialmente en papeles con el logotipo ColorLok.\r\n\r\nImprima documentos profesionales en color hasta un 40% menos por página que en láser, con tintas HP Officejet. Los cartuchos de tinta individuales le ayudan a imprimir de forma económica.', 64, 7, 12.75, 'En stock', 124, 1, '2013-05-13 19:31:33'),
(57, 'Sony CD-R 50x 700MB Tarrina 50 Unds', 'Bobina de 50 CD-R de 700 MB (80 min.). Se suministran en una bobina para facilitar el almacenamiento masivo. Estos discos de alta calidad son ideales para grabar un gran número de discos\r\n\r\nSoporte de alta calidad\r\nHasta 700 MB de capacidad por disco\r\nMuy fiable\r\nCompatible con las últimas velocidades de grabación\r\nEspecificaciones\r\n\r\nVelocidad lineal de disco (m/s) 1,2 ~ 2\r\nDiámetro exterior (mm) 120.0\r\nCapacidad de grabación (MB) 700\r\nCapa de grabación sublimación\r\nTiempo de grabación (min.) 80.0\r\nSustrato policarbonato\r\nGrosor (mm) 1,2', 63, 6, 13.75, 'En stock', 129, 1, '2013-05-13 19:32:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `activo`) VALUES
(1, 'Administrador', 1),
(2, 'Empleado', 1),
(4, 'Usuario', 1),
(5, 'Desarrollador', 1),
(6, 'Comercial', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  `password` varchar(40) NOT NULL,
  `token` varchar(40) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `email` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `password`, `token`, `activo`, `email`) VALUES
(1, 'admin', 'f865b53623b121fd34ee5426c792e5c33af8c227', 'f6df3f081e15d92eebbfba43cc01d2e29932b5c7', 1, 'admin@amazing-components.com'),
(3, 'empleado', 'e5a83a10ba9cbdc8107acf0cdafc6369b385e4c2', '2a0a25be528ff551c91a2b20ef542c6c3c718590', 1, 'empleado@amazing-components.com'),
(4, 'developer', 'a37eed78239fffc27af57d196b76c8e66774c16e', 'eb758745c3527ccce41697b3a4b9adb05dc88759', 1, 'developer@amazing-components.com'),
(5, 'comercial', '303a50263c2c74133c7a6f8c11f96fdc0912faeb', '5ea09216763231b17625681ebb62ba128f2edbc6', 1, 'comercial@amazing-components.com'),
(21, 'juanito', '5663456829a78b810035996c39d29441b1b072e2', '3897e5fb920d56ef53603bf5382d1cacb17b6078', 1, 'juanito@amazing-components.com'),
(22, 'paquito', '8bdd551f05ea94f06f203fbc9c91becf323dbe18', '', 0, 'paquito@amazing-components.com'),
(23, 'luisito', 'dafafc1c6a4387902edd4f82f5a73699663bfb30', '2e94a5bbab9362189ee63b70ebab1e714d976964', 1, 'luisito@amazing-components.es');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_roles`
--

CREATE TABLE IF NOT EXISTS `usuarios_roles` (
  `usuario_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL,
  PRIMARY KEY (`usuario_id`,`rol_id`),
  KEY `rol_id` (`rol_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios_roles`
--

INSERT INTO `usuarios_roles` (`usuario_id`, `rol_id`) VALUES
(1, 1),
(3, 2),
(1, 4),
(3, 4),
(4, 4),
(5, 4),
(21, 4),
(22, 4),
(23, 4),
(4, 5),
(5, 6);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `categorias_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `datos_usuarios`
--
ALTER TABLE `datos_usuarios`
  ADD CONSTRAINT `datos_usuarios_ibfk_1` FOREIGN KEY (`id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `galeria_productos`
--
ALTER TABLE `galeria_productos`
  ADD CONSTRAINT `galeria_productos_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `linea_pedido`
--
ALTER TABLE `linea_pedido`
  ADD CONSTRAINT `linea_pedido_ibfk_2` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `linea_pedido_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `ofertas`
--
ALTER TABLE `ofertas`
  ADD CONSTRAINT `ofertas_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `privilegios_rol`
--
ALTER TABLE `privilegios_rol`
  ADD CONSTRAINT `privilegios_rol_ibfk_2` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `privilegios_rol_ibfk_1` FOREIGN KEY (`privilegio_id`) REFERENCES `privilegios` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`fabricante_id`) REFERENCES `fabricantes` (`id`);

--
-- Filtros para la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD CONSTRAINT `usuarios_roles_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `usuarios_roles_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
