<?php
require_once 'inicializacion.php';
require_once 'classes/Usuarios.php';

require_once 'classes/DatosUsuarios.php';
require_once 'classes/Pedidos.php';
require_once 'classes/LineasPedido.php';


$usuarios = new Usuarios($controlador);
$prods= new Productos($controlador);
$direcciones = new DatosUsuarios($controlador);
$pedidos = new Pedidos($controlador);
$lineas = new LineasPedido($controlador);

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


switch ($regMem->getValor('accion')) {
	case 'Cancelar':
		header("Location: {$_SERVER['SCRIPT_NAME']}");
		exit;
		break;

	case 'Editar':
		// Si hemos enviado el formulario intentamos aplicar los cambios
		if ($regMem->getValor('metodo')=='POST') {

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
		break; // Fin Editar

	case 'Guardar Cambios':
		$correcto = TRUE;

		// Validamos los datos
		$regexp = "/[\^<,\"@\/\{\}\(\)\*\$%\?=>:\|;#]+/i";
		if (preg_match($regexp, $regMem->getValor('nombre'))) {
		    // error
		    $regError->setError('nombre', 'El nombre contiene carácteres no válidos');
		    $correcto = FALSE;
		} else if (strlen($regMem->getValor('nombre'))<4) {
			$regError->setError('nombre', 'El nombre debe tener al menos 4 caracteres(tiene'. strlen($regMem->getValor('nombre')) . ')');
		    $correcto = FALSE;
		}

		if (preg_match($regexp, $regMem->getValor('apellido'))) {
		$regError->setError('apellido', 'El apellido contiene carácteres no válidos');
		    $correcto = FALSE;
		} else if (strlen($regMem->getValor('apellido'))<4) {
			$regError->setError('apellido', 'El apellido debe tener al menos 4 caracteres (tiene '. strlen($regMem->getValor('apellido')) . ')');
		    $correcto = FALSE;
		}

		if (preg_match($regexp, $regMem->getValor('poblacion'))) {
			$regError->setError('poblacion', 'La población contiene carácteres no válidos');
		    $correcto = FALSE;
		} else if (!$regMem->getValor('poblacion')) {
			$regError->setError('poblacion', 'La población no puede estar en blanco.');
		    $correcto = FALSE;
		}

		$regexp = "/^\d{5}$/";
		if (!preg_match($regexp, $regMem->getValor('cp'))) {
			$regError->setError('cp', 'El código postal debe estar compuesto por 5 dígitos');
			$correcto = FALSE;
		}

		// La dirección no la validamos.
		if (!$regMem->getValor('direccion')) {
			$regError->setError('direccion', 'La direccion no puede estar en blanco.');
		    $correcto = FALSE;
		}

		// Si todo es correcto preparamos el pedido y pasamos al siguiente paso
		if ($correcto) {
			$a = array(
				'nombre' => $regMem->getValor('nombre'),
				'apellido' => $regMem->getValor('apellido'),
				'poblacion' => $regMem->getValor('poblacion'),
				'direccion' => $regMem->getValor('direccion'),
				'cp' => $regMem->getValor('cp'),
				);
			$regSistema->setValor('datos_envio', $a);
			
			// Si no existe dirección de envio para el usuario añadimos una nueva
			// Si existe la actualizamos
			// OJO, no comprobamos si los datos han cambiado.

			$direccion = $direcciones->getItemBD(array ('id'=>$regSistema->getValor('id')))->getItemById($regSistema->getValor('id'));
			if ($direccion) {
				// Si ya existe una dirección, la sustituimos
				$a += array ('id' => $regSistema->getValor('id'));
				$direccion = new DatosUsuario($a);
				$direcciones->setItemBD($direccion);

			} else {
				$a += array ('id' => $regSistema->getValor('id'));
				$direccion = new DatosUsuario($a);
				$direcciones->addItemBD($direccion);
			}

			$regFeedback->addFeedback('Cambios guardados.');
		}
		break;


} // Fin swtich accion

if ($regMem->getValor('ver')=='detalle') {
	if (!$regMem->getValor('id')) {
		$regError->setError('general', 'No existe ningún pedido con esa <b>referencia</b>');
		$regMem->setValor('titulo', 'Error. No existe el pedido.');
		$regMem->setValor('ver', 'Mis pedidos');
	} else {
		$pedido = $pedidos->getItemBD(array ('id' =>$regMem->getValor('id')))->getItemById($regMem->getValor('id'));
		if (!$pedido) {
			// Error, el pedido no existe
			$regError->setError('general', 'No existe ningún pedido con esa <b>referencia</b>');
			$regMem->setValor('titulo', 'Error. No existe el pedido.');
			$regMem->setValor('ver', 'Mis pedidos');
		} else {
			// Existe el pedido
			$regMem->setValor('titulo', 'Detalle del pedido ref. ' . $pedido->getPropiedad('ref'));
		}
	}
}

if ($regMem->getValor('ver')=='Mi perfil') {
	$regMem->setValor('titulo', 'Mi perfil');

} else if ($regMem->getValor('ver')=='Mis pedidos'){
	$regMem->setValor('titulo', 'Mis pedidos');
	$pedidos->getItemBD(array('usuario_id'=>$regSistema->getValor('id')));
	if (!$pedidos->getTotal()) {
		$regError->setError('general', 'No se ha encontrado ningún pedido.');
	}


} else if ($regMem->getValor('ver')=='Mis datos'){
	$regMem->setValor('titulo', 'Mis datos');

	$direccion = $direcciones->getItemBD(array ('id'=>$regSistema->getValor('id')))->getItemById($regSistema->getValor('id'));

	if (!$direccion){
		$valores = array(
				'nombre' => '',
				'apellido' => '',
				'poblacion' => '',
				'direccion' => '',
				'cp' => '',
			);
		$direccion = new DatosUsuario ($valores);
	}

}


include 'cabecera.php';
?>

<div id="main">

<?php
include 'top-menu.php';
include 'main-menu.php';
include 'sidebar-usuarios.php';

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
	if ($regMem->getValor('ver')=='Mi perfil') {
	
	?>
	<div>
		<form class="separacion" action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">

			<label>Password:</label>
			<input type="password" name="password1"/>
				
			<label>Repite pass:</label>
			<input type="password" name="password2"/>

			<label>e-mail:</label>
			<input type="text" name="email" value="<?=$usuario->getPropiedad('email')?>"/>

			<p class="centrado">
				<input type="submit" name="accion" value="Cancelar"/>
				<input type="submit" name="accion" value="Editar"/>
			</p>
		
		</form>
	</div>
	<?php
	} else if ($regMem->getValor('ver')=='detalle') {
	?>
	<?php
		// Mostramos la cabecera del pedido (el pedido lo recuperamos en el switch del principio)
		echo "<div class=\"columnas\">";
		echo "<p>Referencia: <b>" . $pedido->getPropiedad('ref') . "</b></p>";
		echo "<p>Fecha: <b>" . $pedido->getPropiedad('fecha') . "</b></p>";
		echo "<p>Estado: <b class=" . quitarEspacios($pedido->getPropiedad('estado')) . ">" . $pedido->getPropiedad('estado') . "</b></p>";
		echo "</div>";

		// Recuperamos las lineas de pedido
		$lineas->getItemBD(array('pedido_id'=>$pedido->getPropiedad('id')));
		$a=$lineas->getItemById();
		?>
		<table class="separacion">
			<tr>
				<th>Producto</th>
				<th>Unidades</th>
				<th>Precio</th>
				<th>Subtotal</th>
			</tr>
		<?php

		$total_precio = 0;
		foreach ($a as $linea) {
			echo "<tr>";
			
			echo "<td>";
			$producto = $prods->getItemBD(array('id'=>$linea->getPropiedad('producto_id')))->getItemById($linea->getPropiedad('producto_id'));
			echo $producto->getPropiedad('nombre');
			echo "</td>";

			echo "<td>" . $linea->getPropiedad('cantidad') . "</td>";

			echo "<td>" . number_format($linea->getPropiedad('precio')/(1+IVA),2) . "&euro;</td>";
			echo "<td>" . number_format($linea->getPropiedad('precio')*$linea->getPropiedad('cantidad')/(1+IVA),2) . "&euro;</td>";
			echo "</tr>";
			$total_precio += $linea->getPropiedad('precio')*$linea->getPropiedad('cantidad');
		}

		?>
			<tr>
				<th>IVA <?=(IVA*100)?>%</th>
				<th></th>
				<th></th>
				<th><?=number_format($total_precio*IVA,2)?>&euro;</th>
			</tr>
			<tr>
				<th>TOTAL</th>
				<th></th>
				<th></th>
				<th><?=number_format($total_precio,2)?>&euro;</th>
			</tr>

		</table>

		<p class="centrado separacion">
			<a href="<?=$_SERVER['SCRIPT_NAME']?>?ver=Mis+pedidos">Volver a Mis pedidos</a>
		</p>

	<?php
	} else if ($regMem->getValor('ver') == 'Mis pedidos') {
		if ($pedidos->getTotal()) {

	?>
		<table>
			<tr>
				<th>Ref</th>
				<th>Fecha</th>
				<th>Estado</th>
				<th></th>
			</tr>

			<?php
	
				$a = $pedidos->getItemById();
				foreach ($a as $pedido) {
					echo "<tr>";
					echo "<td><a href=\"{$_SERVER['SCRIPT_NAME']}?ver=detalle&amp;id={$pedido->getPropiedad('id')}\">";
					echo "{$pedido->getPropiedad('ref')}";
					echo "</a></td>";
						
					// Recuperamos el nombre y apellido del usuario
					$usuario = $direcciones->getItemBD(array('id'=>$pedido->getPropiedad('usuario_id')))->getItemById($pedido->getPropiedad('usuario_id'));

					echo "<td>{$pedido->getPropiedad('fecha')}</td>";

					echo "<td class=\"" . quitarEspacios($pedido->getpropiedad('estado')) . "\">{$pedido->getpropiedad('estado')}</td>";
					
					echo "<td></td>";	
					echo "</tr>";

				}

			?>


		</table>
		<?php
		} else {
		?>
			<p class="separacion centrado"><a href="<?=$_SERVER['SCRIPT_NAME']?>">Volver</a></p>
		<?php
		}
		?>
	</div>
	<?php



	} else if ($regMem->getValor('ver')=='Mis datos') {
	?>

	<form class="separacion" action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
			<label>Nombre:</label>
			<input type="text" name="nombre" value="<?=$direccion->getPropiedad('nombre')?>"/>

			<label>Apellido:</label>
			<input type="text" name="apellido" value="<?=$direccion->getPropiedad('apellido')?>"/>

			<label>Dirección:</label>
			<input type="text" name="direccion" value="<?=$direccion->getPropiedad('direccion')?>"/>

			<label>Población:</label>
			<input type="text" name="poblacion" value="<?=$direccion->getPropiedad('poblacion')?>"/>

			<label>CP:</label>
			<input type="text" name="cp" value="<?=$direccion->getPropiedad('cp')?>"/>

			<input type="hidden" name="ver" value="Mis datos"/>

			<p class="separacion centrado">
				<input type="submit" name="accion" value="Cancelar" />
				<input type="submit" name="accion" value="Guardar Cambios" />
			</p>

		</form>

	<?php
	} else { // Si no hay ninguna vista adecuada mostramos esto
	?>

	
	<p class="separacion centrado">Esta es tu página principal. Desde aquí puedes modificar tus datos y ver el estado de tus pedidos.</p>

	<?php
	}// Fin if ver = detalle
	?>
</div>

<?php
include 'pie.php';
?>

</body>
</html>
