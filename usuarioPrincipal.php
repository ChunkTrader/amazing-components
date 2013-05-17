<?php
require_once 'inicializacion.php';
require_once 'classes/Usuarios.php';

require_once 'classes/DatosUsuarios.php';
require_once 'classes/Pedidos.php';
require_once 'classes/LineasPedido.php';


$usuarios = new Usuarios($controlador);
$prods= new Productos($controlador);
$datos_usuarios = new DatosUsuarios($controlador);
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

if ($regMem->getValor('ver')=='detalle') {
	if (!$regMem->getValor('id')) {
		$regError->setError('general', 'No existe ningún pedido con esa <b>referencia</b>');
		$regMem->setValor('titulo', 'Error. No existe el pedido.');
		$regMem->setValor('ver', 'lista');
	} else {
		$pedido = $pedidos->getItemBD(array ('id' =>$regMem->getValor('id')))->getItemById($regMem->getValor('id'));
		if (!$pedido) {
			// Error, el pedido no existe
			$regError->setError('general', 'No existe ningún pedido con esa <b>referencia</b>');
			$regMem->setValor('titulo', 'Error. No existe el pedido.');
			$regMem->setValor('ver', 'lista');
		} else {
			$regMem->setValor('titulo', 'Ver detalle del pedido');
		}
	}
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
	<?php
	if ($regMem->getValor('ver')=='detalle') {
	?>
	<h2 class="separacion">Detalle del pedido <?=$pedido->getPropiedad('ref')?></h2>

	<?php
		// Mostramos la cabecera del pedido (el pedido lo recuperamos en el switch del principio)
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



	<?php
	} // Fin if ver = detalle

?>

		<h2 class="separacion">Mis pedidos</h2>
	<table>
		<tr>
			<th>Ref</th>
			<th>Fecha</th>
			<th>Estado</th>
			<th></th>
		</tr>

		<?php
			$pedidos->getItemBD(array('usuario_id'=>$regSistema->getValor('id')));
			$a = $pedidos->getItemById();
			foreach ($a as $pedido) {
				echo "<tr>";
					echo "<td><a href=\"{$_SERVER['SCRIPT_NAME']}?ver=detalle&amp;id={$pedido->getPropiedad('id')}\">";
					echo "{$pedido->getPropiedad('ref')}";
					echo "</a></td>";
					
					// Recuperamos el nombre y apellido del usuario
					$usuario = $datos_usuarios->getItemBD(array('id'=>$pedido->getPropiedad('usuario_id')))->getItemById($pedido->getPropiedad('usuario_id'));

					echo "<td>{$pedido->getPropiedad('fecha')}</td>";

					echo "<td class=\"" . quitarEspacios($pedido->getpropiedad('estado')) . "\">{$pedido->getpropiedad('estado')}</td>";

					// Cancelar pedido
					if ($pedido->getPropiedad('estado')!='Cancelado' && $pedido->getPropiedad('estado')!='Recibido') {
						echo "<td>";
						echo "<a href=\"{$_SERVER['SCRIPT_NAME']}?ver=cancelar&amp;accion=Cancelar+Pedido&amp;id={$pedido->getPropiedad('id')}\" title=\"Cancelar pedido\"><img src=\"images/icon_delete.gif\"/></a>";
						echo "</td>";	
					} else {
						echo "<td></td>";
					}
					
				echo "</tr>";
			}

		?>


	</table>




	</div>
</div>

<?php
include 'pie.php';
?>

</body>
</html>
