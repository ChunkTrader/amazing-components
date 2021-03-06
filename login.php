<?php
require_once 'inicializacion.php';


$usuarios = new Usuarios($controlador);

// Titulo por defecto de la p�gina
$regMem->setValor('titulo', 'Conectar');

switch ($regMem->getValor('accion')){
	case 'Conectar':
	$conectado = FALSE;

	if ($regMem->getValor('nombre') && $regMem->getValor('password')) {
		$valores = array (
			'nombre' => $regMem->getValor('nombre'),
			'password' => SHA1($regMem->getValor('password'))
			);

		$usuario = new Usuario($valores);
		$usuario_id = $usuarios->matchUsuario($usuario);

			//echo "usuario_id :$usuario_id";
		if ($usuario_id) {
				// Recuperamos los datos del usuario y compramos si esta activado
			$usuario=$usuarios->getItemBD(array('id' =>$usuario_id))->getItemById($usuario_id);

			if ($usuario->getPropiedad('activo')) {

				// Si el usuraio existe y la contrase�a es correcta regeneramos la sesion y
				// creamos un nuevo token.
				session_regenerate_id();

				$regFeedback->addFeedback ('Has conectado con �xito como <b>'.$usuario->getPropiedad('nombre').'</b>');
				$usuario->setPropiedad('id', $usuario_id);
				$usuario->setToken();

				// Almacenamos el token en la base de datos, de momento guardamos todo el usuario entero
				// Esto habria que optimizarlo.				
				$usuarios->setItemBD($usuario);

				// Aqui hay que poner el c�digo para almacenar los datos en la session y en las cookies
				$regSistema->setValor('autenticado', TRUE);
				$regSistema->setValor('nombre', $usuario->getPropiedad('nombre'));
				$regSistema->setValor('id', $usuario->getPropiedad('id'));

				// Recuperamos los roles y los privilegios
				$usuarios->getRolesBD($usuario);

				// Obtenemos la lista de privilegios del usuario
				$usuarios->getPrivilegiosUsuarioBD($usuario);

				// Guardamos los privilegios en la sesion
				$regSistema->setValor('privilegios', $usuario->getPrivilegios());

				// Guardamos los datos en la cookie
				// id del usuario, token

				setcookie('login', serialize(array(
						'id'=> $usuario->getPropiedad('id'),
						'token' => $usuario->getPropiedad('token')
					)),time()+3600*24*14); // Expira en 2 semanas

				
				

			} else {
				$regError->setError('usuario', 'Este usuario est� desactivado, pongase en contacto con un administrador.');

			}

		} else {
			$regError->setError('general', 'Nombre de usuario o contrase�a incorrectos');
		}

	} else {
		$regError->setError('general', 'Debes introducir el nombre de usuario y la contrase�a.');
	}
	break;

	case 'Desconectar':
		// Cerramos la sesi�n:
		$regSistema->limpiar();
		$usuario=null;
		$regFeedback->addFeedback('Ahora estas desconectado.');


}



include 'cabecera.php';
?>

<div id="main">

	<?php
	include 'top-menu.php';
	include 'main-menu.php';
	include 'sidebar-categorias.php';
	?>



	<div id="main-content">
		<h2><?=$regMem->getValor('titulo')?></h2>
		<div class="separacion">
			<?php
			if ($regError->getError()) {
				$a = $regError->getError();
				foreach ($a as $error) {
					echo "<p class=\"error centrado\">{$error}</p>";
				}
			}
			if ($regFeedback->getFeedback()) {
				$a = $regFeedback->getFeedback();
				foreach ($a as $feed) {
					echo "<p class=\"centrado\">{$feed}</p>";
				}
			}
			?>
		</div>

		<?php
	//			SIN CONECTAR
		if (!$regSistema->getValor('autenticado')) {
			?>
			<div class="separacion">
				<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
					<label>Nombre: </label>
					<input type="text" name="nombre"/>

					<label>Password: </label>
					<input type="password" name="password"/>

					<p class="centrado separacion">
						<input type="submit" name="accion" value="Conectar"/>
					</p>
				</form>

				<p class="centrado separacion">�No tienes cuenta?  <a href="nuevoUsuario.php">Registrate</a></p>

				<?php
	// 			CONECTADO CON �XITO
			} else {
				echo "<p class=\"separacion centrado\"><a href=\"index.php\">P�gina principal</a></p>";
				if ($regSistema->getValor('forward')) {
					echo "<p class=\"separacion centrado\"><a href=\"{$regSistema->getValor('forward')}\">Volver</a></p>";
					// Despues de mostrarlo lo eliminamos
					$regSistema->setValor('forward', NULL);
				}
			}
			?>
		</div>

	</div>

	<?php
	include 'pie.php';
	?>

</body>
</html>
