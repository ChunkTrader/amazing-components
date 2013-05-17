<?php


// comentada para testeo de cookies
// if ($regSistema->getValor('autenticado')) {


if (1!=1) {
	// Estamos autenticados

} else {
	// 1. Comprobar si hay cookie
	// 2. Intentar recuperar los datos a partir de la cookie, contrastando el token
	// 3. Recuperar el usuario si es posible.
	
	
	// Recuperamos los datos
	if (!empty($_COOKIE['login'])) {
		$recuperar = unserialize($_COOKIE['login']);

		
		//echo "user id: {$recuperar['id']}<br>";
		//echo "token: {$recuperar['token']}<br>";

		// Comprobamos que existan un token y una id almacenados en la cookie
		if ($recuperar['id'] && $recuperar['token']) {
			

			// Comprobamos que el token corresponda con la id
			$usuario = $usuarios->checkToken($recuperar['id'], $recuperar['token']);
			

			if ($usuario) {
				// El usuario y el token son correctos

				// Regeneramos la sesion
				session_regenerate_id();

				// Aqui hay que poner el código para almacenar los datos en la session y en las cookies
				$regSistema->setValor('autenticado', TRUE);
				$regSistema->setValor('nombre', $usuario->getPropiedad('nombre'));
				$regSistema->setValor('id', $usuario->getPropiedad('id'));

				// Creamos un nuevo token y actualizamos
				$usuario->setToken();
				$usuarios->setItemBD($usuario);

				// Recuperamos los roles y los privilegios
				$usuarios->getRolesBD($usuario);

				// Obtenemos la lista de privilegios del usuario
				$usuarios->getPrivilegiosUsuarioBD($usuario);

				// Guardamos los privilegios en la sesion
				$regSistema->setValor('privilegios', $usuario->getPrivilegios());

				// Guardamos la cookie con el nuevo token
				setcookie('login', serialize(array(
					'id'=> $usuario->getPropiedad('id'),
					'token' => $usuario->getPropiedad('token')
				)),time()+3600*24*14); // Expira en 2 semanas
			}
		}
	}

}
	
?>