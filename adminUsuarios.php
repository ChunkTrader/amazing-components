<?php
require_once 'inicializacion.php';
require_once 'classes/Roles.php';
require_once 'classes/Privilegios.php';


$usuarios = new Usuarios($controlador);
$roles = new Roles($controlador);
$privs = new Privilegios($controlador);


if (!$regMem->getValor('ver') && (!$privilegios['verAdminUsuarios'])) {
	$regSistema->setValor('acceso_denegado', 'administrar');
	header('Location: error.php');
	exit;
} 

// Titulo por defecto de la p�gina
$regMem->setValor('titulo', 'A�adir usuarios');


// Cargamos las listas
$usuarios->getItemBD();
$roles->getItemBD();
$privs->getItemBD();

//print_r($regMem->getValor()); echo '<br>';

switch ($regMem->getValor('ver')) {

	case 'usuarios':
	if ($privilegios['verAdminUsuarios']){

		switch ($regMem->getValor('accion')){
			case 'A�adir':
			$correcto = TRUE;
						// Comenzamos a validar los datos
			if (strlen($regMem->getValor('nombre'))<5) {
				$regError->setError('nombre', 'El nombre debe tener al menos 5 caracteres.');
				$correcto=FALSE;
			} else if ($usuarios->getItemByNombre($regMem->getValor('nombre'))){
				$regError->setError('nombre', 'Ya existe un usuario con ese nombre');
				$correcto=FALSE;
			} else if (strlen($regMem->getValor('nombre'))>40) {
				$regError->setError('nombre', 'El nombre no puede tener m�s de 40 caracteres.');
				$correcto=FALSE;
			}

			if ($regMem->getValor('password2')!=$regMem->getValor('password1')){
				$regError->setError('password', 'Las contrase�as no coinciden.');
				$correcto=FALSE;
			} else if (strlen($regMem->getValor('password1'))<5){
				$regError->setError('password', 'La contrase�a debe tener al menos 5 caracteres.');
				$correcto=FALSE;
			} else if (strlen($regMem->getValor('password1'))>40) {
				$regError->setError('password', 'La contrase�a debe ser como m�ximo de 40 caracteres.');
				$correcto=FALSE;
			}

			if (!filter_var($regMem->getValor('email'), FILTER_VALIDATE_EMAIL)) {
				$regError->setError('email', 'La direcci�n de correo electr�nico no es v�lida.');
				$correcto=FALSE;
			}


			if ($correcto) {
				$valores = array (
					'nombre' => $regMem->getValor('nombre'),
					'password' => SHA1($regMem->getValor('password1')),
					'email' => $regMem->getValor('email')
					);
				$usuario = new Usuario($valores);
				$usuarios->addItemBD($usuario);

				// Recuperamos el usuario de la base de datos para obtener la id
				$usuario = $usuarios->getUsuarioByNombreBD($usuario->getPropiedad('nombre'))->getItemByNombre($usuario->getPropiedad('nombre'));

				// Todos los usuarios creados tienen asignado el rol usuario autom�ticamente
				$usuario->setRol('Usuario');
				$usuarios->setRolesBD($usuario, $roles);

				$regFeedback->addFeedback ('Usuario creado con �xito <b>' . $regMem->getValor('nombre') . '</b>');
			} else {
				$regError->setError('general', 'No se ha creado el usuario.');
			}
			break;

			case 'Editar':
			$regMem->setValor('titulo', 'Editar usuarios');

			// Comprobamos si existe el usuario
			$usuario = $usuarios->getItemBD(array('id'=>$regMem->getValor('id')))->getItemById($regMem->getValor('id'));


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

					// Comprobamos si se ha cambiado el pass, y si es as� si es correcto:
					if (!($regMem->getValor('password1') && $regMem->getValor('password2'))){
								// No se ha enviado hay pass, no hacemos nada.
					} else if ($regMem->getValor('password2')!=$regMem->getValor('password1')){
						$regError->setError('password', 'Las contrase�as no coinciden.');
					} else if (strlen($regMem->getValor('password1'))<5){
						$regError->setError('password', 'La contrase�a debe tener al menos 5 caracteres.');
					} else if (strlen($regMem->getValor('password1'))>40) {
						$regError->setError('password', 'La contrase�a debe ser como m�ximo de 40 caracteres.');
					} else {
						// Todo es correcto, actualizamos el password y el usuario
						$usuario->setPropiedad('password', SHA1($regMem->getValor('password1')));
						$regFeedback->addFeedback('Se ha actualizado el password.');
					}

					//var_dump(filter_var($regMem->getValor('email'), FILTER_VALIDATE_EMAIL));
					if (!filter_var($regMem->getValor('email'), FILTER_VALIDATE_EMAIL)) {
						$regError->setError('email', 'La direcci�n de correo electr�nico no es v�lida.');
					} else {
						$usuario->setPropiedad('email', $regMem->getValor('email'));
					}

					// Actualizamos el estado activo/inactivo
					if ($regMem->getValor('activo')) {
						$usuario->setPropiedad('activo', 1);
					} else {
						$usuario->setPropiedad('activo', 0);
					}
					// Guardamos los cambios en la base de datos
					// Ojo, no hemos comprobado si ha habido realmente alg�n cambio
					$usuarios->setItemBD($usuario);
				}
				// Recuperamos los roles actualizados (no necesitamos los privilegios en este caso)
				$usuarios->getRolesBD($usuario);
			} else {
				$regError->setError('general', 'No existe el usuario.');
			}
			break;
		}
	} else {
		$regSistema->setValor('acceso_denegado', 'administrar');
		
	}
	break;

	case 'roles':
	if ($privilegios['verAdminRoles']){
		$regMem->setValor('titulo', 'A�adir roles');

		switch ($regMem->getValor('accion')){
			case 'A�adir':
			$correcto = TRUE;
			if (strlen($regMem->getValor('nombre'))<5) {
				$regError->setError('nombre', 'El nombre debe tener al menos 5 caracteres.');
				$correcto=FALSE;
			} else if ($roles->getItemByNombre($regMem->getValor('nombre'))){
				$regError->setError('nombre', 'Ya existe un rol con ese nombre');
				$correcto=FALSE;
			} else if (strlen($regMem->getValor('nombre'))>40) {
				$regError->setError('nombre', 'El nombre no puede tener m�s de 40 caracteres.');
				$correcto=FALSE;
			}

			// A�adimos el nuevo rol a la base de datos
			if ($correcto) {
				$valores = array (
					'nombre' => $regMem->getValor('nombre')
					);
				$rol = new Rol($valores);
				$roles->addItemBD($rol);
				$regFeedback->addFeedback ('El rol <b>' . $regMem->getValor('nombre') . '</b> ha sido creado con �xito');
			} else {
				$regError->setError('general', 'No se ha creado el rol.');
			}

			break;

			case 'Editar':
			$regMem->setValor('titulo', 'Editar roles');			

							// Comprobamos si existe el rol
			$rol = $roles->getItemBD(array('id'=>$regMem->getValor('id')))->getItemById($regMem->getValor('id'));

			if ($rol) {
				// pasamos los privilegios enviados en el formulario
				if ($regMem->getValor('privilegio')) {
					$checked = $regMem->getValor('privilegio');
					foreach ($checked as $privilegio) {
						$rol->setPrivilegio($privilegio);							
					}
				}

				if ($regMem->getValor('metodo')=='POST') {
					// Hemos enviado el formulario, actualizamos los privilegios
					$roles->setPrivilegiosBD($privs, $rol);
					$regFeedback->addFeedback('Privilegios actualizados.');	

					// Actualizamos el estado activo/inactivo
					if ($regMem->getValor('activo')) {
						$rol->setPropiedad('activo', 1);
					} else {
						$rol->setPropiedad('activo', 0);
					}
					// Guardamos los cambios en la base de datos
					// Ojo, no hemos comprobado si ha habido realmente alg�n cambio
					$roles->setItemBD($rol);
				}
				// Recuperamos los roles actualizados (no necesitamos los privilegios en este caso)
				$roles->getPrivilegiosBD($rol);


			} else {
				$regError->setError('general', 'No existe el rol.');
			}


			break;
		}
	} else {
		$regSistema->setValor('acceso_denegado', 'administrar');
		
	}
	break;

	case 'privilegios':
	if ($privilegios['verAdminPrivilegios']){
		$regMem->setValor('titulo', 'A�adir privilegios');

		switch ($regMem->getValor('accion')){
			case 'A�adir':
			$correcto = TRUE;
			if (strlen($regMem->getValor('nombre'))<5) {
				$regError->setError('nombre', 'El nombre debe tener al menos 5 caracteres.');
				$correcto=FALSE;
			} else if ($roles->getItemByNombre($regMem->getValor('nombre'))){
				$regError->setError('nombre', 'Ya existe un privilegio con ese nombre');
				$correcto=FALSE;
			} else if (strlen($regMem->getValor('nombre'))>40) {
				$regError->setError('nombre', 'El nombre no puede tener m�s de 40 caracteres.');
				$correcto=FALSE;
			}

			// A�adimos el nuevo rol a la base de datos
			if ($correcto) {
				$valores = array (
					'nombre' => $regMem->getValor('nombre')
					);
				$privilegio = new Privilegio($valores);
				$privs->addItemBD($privilegio);
				$regFeedback->addFeedback ('El privilegio <b>' . $regMem->getValor('nombre') . '</b> ha sido creado con �xito');
			} else {
				$regError->setError('general', 'No se ha creado el privilegio.');
			}

			break;

			case 'Editar':
				$regError->setError('general', 'Actualmente no es posible editar los privilegios.');
		}
	} else {
		$regSistema->setValor('acceso_denegado', 'administrar');
		
	}
	break;

	default:
		// Hay un valor en ver, pero no es v�lido.
		if ($regMem->getValor('ver'))
		$regSistema->setValor('acceso_denegado', 'administrar');
		
}

// Reargamos las listas para asegurarnos que todos los valores estan actualizados.
$usuarios->getItemBD();
$roles->getItemBD();
$privs->getItemBD();


include 'cabecera.php';
?>

<div id="main">

<?php
include 'top-menu.php';
include 'main-menu.php';
include 'sidebar-administrar.php';


// Si hay un error redirigimos la p�gina
if ($regSistema->getValor('acceso_denegado')) {
	header('Location: error.php');
	exit;
}

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
	
	if ($regMem->getValor('ver')=='usuarios' || !$regMem->getValor('ver')) {
		$regMem->setValor('ver','usuarios');
	
		if (!$regMem->getValor('accion') || $regMem->getValor('accion')=='A�adir') {

	?>
		<div class="separacion">
		<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
			<label>Nombre: </label>
			<input type="text" name="nombre"/>
			
			<label>e-mail: </label>
			<input type="text" name="email"/>

			<label>Password: </label>
			<input type="password" name="password1"/>
			
			<label>Repite pass: </label>
			<input type="password" name="password2"/>
			
			<input type="hidden" name="ver" value="<?=$regMem->getValor('ver')?>"/>
			
			<p class="centrado">
				<input type="submit" name="accion" value="A�adir"/>
			</p>
		</form>
		</div>
	<?php
	/*		EDICION DE USUARIOS 		*/
	} else if ($regMem->getValor('accion')=="Editar" && $usuario) {
	?>
	<div class="separacion">
		<h3><?=$usuario->getPropiedad('nombre')?></h3>

		<form class="separacion" action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">

			<label>Password:</label>
			<input type="password" name="password1"/>
				
			<label>Repite pass:</label>
			<input type="password" name="password2"/>

			<label>e-mail:</label>
			<input type="text" name="email" value="<?=$usuario->getPropiedad('email')?>"/>

			<label>Activo:</label>
				<input type="checkbox" name="activo"
				<?php
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
		<h3><?=$usuario->getPropiedad('nombre')?></h3>

	<form class="separacion" action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
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
		// Obtener roles del usuario
		echo "<td>";
		$usuarios->getRolesBD($usuario);
		$b =$usuario->getRoles();
		$c = array();
		foreach ($b as $clave=>$rol) {
			$c[] = $clave;
		}
		echo implode(', ', $c);

		echo "</td>";
		
		$activo = ($usuario->getPropiedad('activo')?"S�":"No");
		
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
	
		if ($regMem->getValor('accion')=='A�adir' || !$regMem->getValor('accion')) {
		?>
		<div class="separacion">
		<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
			<label>Nombre: </label>
			<input type="text" name="nombre"/>
			
			<input type="hidden" name="ver" value="<?=$regMem->getValor('ver')?>"/>
			
			<p class="centrado">
				<input type="submit" name="accion" value="A�adir"/>
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
			$activo = ($rol->getPropiedad('activo')?"S�":"No");
			echo "<td>{$activo}</td>";
			echo "<td></td>";
			echo "</tr>";
		}
		?>	
		</table>
		</div>
	<?php 
	} else if ($regMem->getValor('accion')=='Editar') {


	/* EDITAR ROLES 		*/
	?>
	<h3><?=$rol->getPropiedad('nombre')?></h3>

	<form class="separacion" action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">

		<label>Activo:</label>
				<input type="checkbox" name="activo"
				<?php
				if ($rol->getPropiedad('activo')) {
					echo " CHECKED ";
				}
				?>
			/>

	<div class="separacion">
		<table>
			<tr>
				<th>Privilegio</th>
				<th>Activo</th>
				<th> </th>
			</tr>
		<?php

		
		$a = $privs->getItemById();


		foreach ($a as $privilegio) {
						
			echo "<tr>";
			echo "<td>" . $privilegio->getPropiedad('nombre') . "</td>";
			echo "<td>" . "<input type=\"checkbox\" ";
			
			if (array_key_exists($privilegio->getPropiedad('nombre'), $rol->getPrivilegios())) {
				echo ' checked ';
			}
			echo " name=\"privilegio[]\" value=\"{$privilegio->getPropiedad('nombre')}\"/>";
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
			$activo = ($rol->getPropiedad('activo')?"S�":"No");
			echo "<td>{$activo}</td>";
			echo "<td></td>";
			echo "</tr>";
		}
		?>	
		</table>
		</div>






<?php

	}
	?>


	<?php
	/* 		VER PRIVILEGIOS 		*/
	} else if ($regMem->getValor('ver')=='privilegios') {
	?>
	<div class="separacion">

	<p class="centrado">�Cuidado! Los privilegios son usados internamente por la aplicaci�n y solo deben ser usados por los desarrolladores.</p>
	<form class="separacion" action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
		<label>Nombre: </label>
		<input type="text" name="nombre"/>
		
		<input type="hidden" name="ver" value="<?=$regMem->getValor('ver')?>"/>
		
		<p class="centrado">
			<input type="submit" name="accion" value="A�adir"/>
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
	$a = $privs->getItemById();
	
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
