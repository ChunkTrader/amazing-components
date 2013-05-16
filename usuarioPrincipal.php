<?php
require_once 'inicializacion.php';
require_once 'classes/Usuarios.php';

$usuarios = new Usuarios($controlador);

// Comprobamos si tiene privilegio de acceso a la página
if (!$regSistema->getValor('privilegios')['verHome']){
	$regSistema->setValor('acceso_denegado', 'principal');
	header('Location: error.php');
	exit;
}

// Titulo por defecto de la página
$regMem->setValor('titulo', 'Mi cuenta');

// Comprobamos que exista un usuario con ese nombre
$usuario = $usuarios->getUsuarioByNombreBD($regSistema->getValor('nombre'))->getItemByNombre($regSistema->getValor('nombre'));

// Si hemos enviado el formulario intentamos aplicar los cambios
if ($regMem->getValor('accion')=='Editar' && $regMem->getValor('metodo')=='POST') {

	if (!($regMem->getValor('password1') && $regMem->getValor('password2'))){
		// No se ha enviado hay pass, no hacemos nada.
	} else if ($regMem->getValor('password2')!=$regMem->getValor('password1')){
		$regError->setError('password', 'Las contraseñas no coinciden.');
	} else if (strlen($regMem->getValor('password1'))<5){
		$regError->setError('password', 'La contraseña debe tener al menos 5 caracteres.');
	} else if (strlen($regMem->getValor('password1'))>40) {
		$regError->setError('password', 'La contraseña debe ser como máximo de 40 caracteres.');
	} else {
		// Todo es correcto, actualizamos el password y el usuario
		$usuario->setPropiedad('password', SHA1($regMem->getValor('password1')));
		$regFeedback->addFeedback('Se ha actualizado el password.');
	}

	// Comprobamos si se ha cambiado el correo, y si es así si es válido
	// OJO, la comprobación de cambio no se ha añadido al crear las cuentas por administrador,
	// (pero si la validación.)
	if ($regMem->getValor('email')!=$usuario->getPropiedad('email')) {
		if (!filter_var($regMem->getValor('email'), FILTER_VALIDATE_EMAIL)) {
			$regError->setError('email', 'La dirección de correo electrónico no es válida.');
		} else {
			$usuario->setPropiedad('email', $regMem->getValor('email'));
			$regFeedback->addFeedback('Se ha actualizado el email.');		
		}
	}

	// Guardamos los cambios en la base de datos
	// Ojo, no hemos comprobado si ha habido realmente algún cambio
	$usuarios->setItemBD($usuario);
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

		<p class="separacion centrado">Esta es tu página principal. Desde aquí puedes modificar tus datos y ver el estado de tus pedidos.</p>

	<div class="separacion">
		<h2>Mi perfil</h2>

		<form class="separacion" action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">

			<label>Password:</label>
			<input type="password" name="password1"/>
				
			<label>Repite pass:</label>
			<input type="password" name="password2"/>

			<label>e-mail:</label>
			<input type="text" name="email" value="<?=$usuario->getPropiedad('email')?>"/>

			<p class="centrado">
				<input type="submit" name="accion" value="Editar"/>
			</p>
		
		</form>





		<h2 class="separacion">Mis pedidos</h2>


	</div>
</div>

<?php
include 'pie.php';
?>

</body>
</html>
