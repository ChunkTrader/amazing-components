<?php
require_once 'configuracion.php';
require_once 'conectar_bd.php';

require_once 'classes/Controlador.php';
require_once 'classes/Registro.php';

require_once 'classes/Coleccion.php';
require_once 'classes/Usuarios.php';
require_once 'classes/Roles.php';
require_once 'classes/Privilegios.php';



$PDO = new PDOConfig ();

// Incializamos los registros
$regMem = RegistroMemoria::instancia();
$regError = RegistroErrores::instancia();
$regFeedback = RegistroFeedback::instancia();
$regSistema = RegistroSistema::instancia();


// El controlador de registros almacena un array con acceso a los registros que le añadamos, este
// controlador se pasa a las colecciones al crearlo para que puedan mandar mensajes a la aplicación
$controlador = new Controlador();
$controlador -> setRegistro ('feedback', $regFeedback);
$controlador -> setRegistro ('errores', $regError);
$controlador -> setPDO($PDO);

// Cargamos la comprobación despues de cargar las demás clases e inicializar los registros

$usuarios = new Usuarios($controlador);
$roles = new Roles($controlador);
$privilegios = new Privilegios($controlador);

require_once 'comprobarUsuario.php';

// Titulo por defecto de la página
$regMem->setValor('titulo', 'Añadir usuarios');


// Cargamos las listas
$usuarios->getItemBD();
$roles->getItemBD();
$privilegios->getItemBD();

//print_r($regMem->getValor()); echo '<br>';


switch ($regMem->getValor('ver')) {

	case 'usuarios':

		switch ($regMem->getValor('accion')){
			case 'Añadir':
			$correcto = TRUE;
				// Comenzamos a validar los datos
			if (strlen($regMem->getValor('nombre'))<5) {
				$regError->setError('nombre', 'El nombre debe tener al menos 5 caracteres.');
				$correcto=FALSE;
			} else if ($usuarios->getItemByNombre($regMem->getValor('nombre'))){
				$regError->setError('nombre', 'Ya existe un usuario con ese nombre');
				$correcto=FALSE;
			} else if (strlen($regMem->getValor('nombre'))>40) {
				$regError->setError('nombre', 'El nombre no puede tener más de 40 caracteres.');
				$correcto=FALSE;
			}

			if ($regMem->getValor('password2')!=$regMem->getValor('password1')){
				$regError->setError('password', 'Las contraseñas no coinciden.');
				$correcto=FALSE;
			} else if (strlen($regMem->getValor('password1'))<5){
				$regError->setError('password', 'La contraseña debe tener al menos 5 caracteres.');
				$correcto=FALSE;
			} else if (strlen($regMem->getValor('password1'))>40) {
				$regError->setError('password', 'La contraseña debe ser como máximo de 40 caracteres.');
				$correcto=FALSE;
			}

			if ($correcto) {
				$valores = array (
					'nombre' => $regMem->getValor('nombre'),
					'password' => SHA1($regMem->getValor('password1'))
					);
				$usuario = new Usuario($valores);
				$usuarios->addItemBD($usuario);

				$regFeedback->addFeedback ('Usuario creado con éxito <b>' . $regMem->getValor('nombre') . ':' . $regMem->getValor('password1') . '</b>');
			} else {
				$regError->setError('general', 'No se ha creado el usuario.');
			}
		break;

		case 'Editar':
			$regMem->setValor('titulo', 'Editar usuarios');


			// Comprobamos si existe el usuario
			$usuario = $usuarios->getItemBD(array('id'=>$regMem->getValor('id')))->getItemById($regMem->getValor('id'));
			//print_r($usuario);
			if ($usuario) {
				if ($regMem->getValor('rol')) {
					$checked = $regMem->getValor('rol');
					foreach ($checked as $rol) {
						$usuario->setRol($rol);
					}
				}

				if ($regMem->getValor('metodo')=='POST') {
					// Hemos enviado el formulario, actualizamos los roles
					$usuarios->setRolesBD($usuario, $roles);
					$regFeedback->addFeedback('Roles actualizados.');

					// Comprobamos si se ha cambiado el pass, y si es así si es correcto:
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

					// Actualizamos el estado activo/inactivo
					if ($regMem->getValor('activo')) {
						$usuario->setPropiedad('activo', 1);
					} else {
						$usuario->setPropiedad('activo', 0);
					}

					// Guardamos los cambios en la base de datos
					// Ojo, no hemos comprobado si ha habido realmente algún cambio
					$usuarios->setItemBD($usuario);

				}
				
				// Recuperamos los roles actualizados (no necesitamos los privilegios en este caso)
			$usuarios->getRolesBD($usuario);


			} else {
				$regError->setError('general', 'No existe el usuario.');	
			}

			break;
		}

	case 'roles':
		$regMem->setValor('titulo', 'Añadir roles');

		switch ($regMem->getValor('accion')){
			case 'Añadir':
				$correcto = TRUE;
				if (strlen($regMem->getValor('nombre'))<5) {
					$regError->setError('nombre', 'El nombre debe tener al menos 5 caracteres.');
					$correcto=FALSE;
				} else if ($roles->getItemByNombre($regMem->getValor('nombre'))){
					$regError->setError('nombre', 'Ya existe un rol con ese nombre');
					$correcto=FALSE;
				} else if (strlen($regMem->getValor('nombre'))>40) {
					$regError->setError('nombre', 'El nombre no puede tener más de 40 caracteres.');
					$correcto=FALSE;
				}
		
				// Añadimos el nuevo rol a la base de datos
				if ($correcto) {
					$valores = array (
						'nombre' => $regMem->getValor('nombre')
					);
					$rol = new Rol($valores);
					$roles->addItemBD($rol);
					$regFeedback->addFeedback ('El rol <b>' . $regMem->getValor('nombre') . '</b> ha sido creado con éxito');
				} else {
					$regError->setError('general', 'No se ha creado el rol.');
				}
				
				break;

		}
		break;

	case 'privilegios':
		$regMem->setValor('titulo', 'Añadir privilegios');

		switch ($regMem->getValor('accion')){
			case 'Añadir':
				$correcto = TRUE;
				if (strlen($regMem->getValor('nombre'))<5) {
					$regError->setError('nombre', 'El nombre debe tener al menos 5 caracteres.');
					$correcto=FALSE;
				} else if ($roles->getItemByNombre($regMem->getValor('nombre'))){
					$regError->setError('nombre', 'Ya existe un privilegio con ese nombre');
					$correcto=FALSE;
				} else if (strlen($regMem->getValor('nombre'))>40) {
					$regError->setError('nombre', 'El nombre no puede tener más de 40 caracteres.');
					$correcto=FALSE;
				}
		
				// Añadimos el nuevo rol a la base de datos
				if ($correcto) {
					$valores = array (
						'nombre' => $regMem->getValor('nombre')
					);
					$privilegio = new Privilegio($valores);
					$privilegios->addItemBD($privilegio);
					$regFeedback->addFeedback ('El privilegio <b>' . $regMem->getValor('nombre') . '</b> ha sido creado con éxito');
				} else {
					$regError->setError('general', 'No se ha creado el privilegio.');
				}
				
				break;
		}

		break;
}


// Reargamos las listas para asegurarnos que todos los valores estan actualizados.
$usuarios->getItemBD();
$roles->getItemBD();
$privilegios->getItemBD();


include 'cabecera.php';
?>

<div id="main">

<?php
include 'top-menu.php';
include 'main-menu.php';
include 'sidebar-administrar.php';
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
	/*		VER USUARIOS 		*/
	if ($regMem->getValor('ver')=='usuarios' || !$regMem->getValor('ver')){
		$regMem->setValor('ver','usuarios');
	
		if (!$regMem->getValor('accion')) {

	?>
		<div class="separacion">
		<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
			<label>Nombre: </label>
			<input type="text" name="nombre"/>
			
			<label>e-mail: </label>
			<input type="text" name="email" disabled/>

			<label>Password: </label>
			<input type="password" name="password1"/>
			
			<label>Repite pass: </label>
			<input type="password" name="password2"/>
			
			<input type="hidden" name="ver" value="<?=$regMem->getValor('ver')?>"/>
			
			<p class="centrado">
				<input type="submit" name="accion" value="Añadir"/>
			</p>
		</form>
		</div>
	<?php
	/*		EDICION DE USUARIOS 		*/
	} else if ($regMem->getValor('accion')=="Editar" && $usuario) {
	?>
	<div class="separacion">
		<h3><?=$usuario->getPropiedad('nombre')?></h3>

		<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">

			<label>Password:</label>
			<input type="password" name="password1"/>
				
			<label>Repite pass:</label>
			<input type="password" name="password2"/>

			<label>e-mail:</label>
			<input type="text" name="email" disabled/>

			<label>Activo:</label>
				<input type="checkbox" name="activo"
				<?php
				var_dump($usuario->getPropiedad('activo'));
				if ($usuario->getPropiedad('activo')) {
					echo " CHECKED ";
				}
				?>
			/>

			<table class="separacion">
				<tr>
					<th>Rol</th>
					<th>Activo</th>
					<th></th>
			<?php
			// Lista de roles
			$a = $roles->getItemById();
			foreach ($a as $rol) {
				echo "<tr>";
				echo "<td>" . $rol->getPropiedad('nombre') . "</td>";
				echo "<td>" . "<input type=\"checkbox\" ";
				if (!$usuario->getRol($rol->getPropiedad('nombre'))) {
				} else {
					echo ' checked ';
				}
				echo " name=\"rol[]\" value=\"{$rol->getPropiedad('nombre')}\"/>";

				echo "</td>";
				echo "<td></td>";
				echo "</tr>";
			}

			?>
			</table>
			<input type="hidden" name="ver" value="<?=$regMem->getValor('ver')?>"/>
			<input type="hidden" name="id" value="<?=$regMem->getValor('id')?>"/>
			<p class="centrado">
				<input type="submit" name="accion" value="Editar"/>
			</p>
		
		</form>

		<?php
	} else if ($regMem->getValor('accion')=="Editar") {
	?>
	<div class="separacion">
		<h3><?=$usuario_conectado->getPropiedad('nombre')?></h3>

	<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
		<table>
			<tr>
				<th>Rol</th>
				<th>Activo</th>
				<th></th>
		<?php
		// Lista de roles
		$a = $roles->getItemById();
		foreach ($a as $rol) {
			echo "<tr>";
			echo "<td>" . $rol->getPropiedad('nombre') . "</td>";
			echo "<td>" . "<input type=\"checkbox\" ";
			if (!$usuario_conectado->getRol($rol->getPropiedad('nombre'))) {
			} else {
				echo ' checked ';
			}
			echo " name=\"rol[]\" value=\"{$rol->getPropiedad('nombre')}\"/>";
			echo "</td>";
			echo "<td></td>";
			echo "</tr>";
		}

		?>

		<input type="hidden" name="ver" value="<?=$regMem->getValor('ver')?>"/>
		<p class="centrado">
			<input type="submit" name="accion" value="Editar"/>
		</p>
	</table>
	</form>


	</div>
	<?php
	}

	?>
	<h2>Lista de usuarios</h2>
	<div class="separacion">
		<table>
			<tr>
				<th>Usuario</th>
				<th>Roles</th>
				<th>Activo</th>
				<th> </th>
			</tr>
	<?php
	$a = $usuarios->getItemById();
	
	foreach ($a as $usuario) {
		echo "<tr>";
		echo "<td><a href=\"{$_SERVER['SCRIPT_NAME']}?id=".$usuario->getPropiedad('id') . "&amp;accion=Editar&amp;ver=usuarios\">";
		echo $usuario->getPropiedad('nombre');
		echo "</a></td>";
		echo "<td></td>";
		
		$activo = ($usuario->getPropiedad('activo')?"Sí":"No");
		
		echo "<td>{$activo}</td>";
		echo "<td></td>";
		echo "</tr>";
	}
	?>	
	</table>
	</div>
	<?php
	/*		VER ROLES 		*/
	} else if ($regMem->getValor('ver')=='roles') {
	?>
	<div class="separacion">
	<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
		<label>Nombre: </label>
		<input type="text" name="nombre"/>
		
		<input type="hidden" name="ver" value="<?=$regMem->getValor('ver')?>"/>
		
		<p class="centrado">
			<input type="submit" name="accion" value="Añadir"/>
		</p>
	</form>
	</div>

	<h2>Lista de roles</h2>
	<div class="separacion">
		<table>
			<tr>
				<th>Rol</th>
				<th>Activo</th>
				<th> </th>
			</tr>
	<?php
	$a = $roles->getItemById();
	
	foreach ($a as $rol) {
		echo "<tr>";
		echo "<td><a href=\"{$_SERVER['SCRIPT_NAME']}?id=".$rol->getPropiedad('id') . "&amp;accion=Editar&amp;ver=roles\">";
		echo $rol->getPropiedad('nombre');
		echo "</a></td>";
		$activo = ($rol->getPropiedad('activo')?"Sí":"No");
		echo "<td>{$activo}</td>";
		echo "<td></td>";
		echo "</tr>";
	}
	?>	
	</table>
	</div>

	<?php

	} else if ($regMem->getValor('ver')=='privilegios') {
	?>
	<div class="separacion">
	<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
		<label>Nombre: </label>
		<input type="text" name="nombre"/>
		
		<input type="hidden" name="ver" value="<?=$regMem->getValor('ver')?>"/>
		
		<p class="centrado">
			<input type="submit" name="accion" value="Añadir"/>
		</p>
	</form>
	</div>

	<h2>Lista de privilegios</h2>
	<div class="separacion">
		<table>
			<tr>
				<th>Privilegio</th>
				<th> </th>
			</tr>
	<?php
	$a = $privilegios->getItemById();
	
	foreach ($a as $privilegio) {
		echo "<tr>";
		echo "<td><a href=\"{$_SERVER['SCRIPT_NAME']}?id=".$privilegio->getPropiedad('id') . "&amp;accion=Editar&amp;ver=privilegios\">";
		echo $privilegio->getPropiedad('nombre');
		echo "</a></td>";
		echo "<td></td>";
		echo "</tr>";
	}
	?>	
	</table>
	</div>

	<?php

	}	
	?>

</div>

<?php
include 'pie.php';
?>

</body>
</html>
