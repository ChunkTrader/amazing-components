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

-- --------------------------------------------------------


