<?php
require_once 'inicializacion.php';
require_once 'classes/Usuarios.php';
require_once 'classes/DatosUsuarios.php';
require_once 'classes/Pedidos.php';
require_once 'classes/LineasPedido.php';


$prods= new Productos($controlador);
$datos_usuarios = new DatosUsuarios($controlador);
$pedidos = new Pedidos($controlador);
$lineas = new LineasPedido($controlador);



if (!$regMem->getValor('ver') && (!$regSistema->getValor('privilegios')['verAdminPedidos'])) {
	$regSistema->setValor('acceso_denegado', 'administrar');
	header('Location: error.php');
	exit;
} 

// Titulo por defecto de la página
$regMem->setValor('titulo', 'Ver pedidos');

if (!$regMem->getValor('ver')) {
	$regMem->setValor('ver', 'lista');
}

switch ($regMem->getValor('ver')) {

	case 'lista':
		if ($regMem->getValor('usuario')) {
			// Si hay un usuario recuperamos los pedidos de ese usuario
			$pedidos->getItemBD(array ('usuario_id'=>($regMem->getValor('usuario'))));
		} else {
			// Si no, recuperamos todos los pedidos
			$pedidos->getItemBD();

		}

		break; // ver = lista

	case 'detalle':
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

		break; // ver = detalle

	case 'cancelar':
		switch ($regMem->getValor('accion')) {
			case 'Cancelar':
				header("Location: {$_SERVER['SCRIPT_NAME']}");
				exit;
				break;

			case 'Cancelar Pedido':
				if (!$regMem->getValor('id')) {
					$regError->setError('general', 'No existe ningún pedido con esa <b>referencia</b>');
					$regMem->setValor('titulo', 'Error. No existe el pedido.');
					$regMem->setValor('ver', 'lista');
				} else if ($regMem->getValor('metodo')=='POST') {
					// Se ha confirmado la cancelación del pedido
					$pedido = $pedidos->getItemBD(array ('id' =>$regMem->getValor('id')))->getItemById($regMem->getValor('id'));
					
					// Comprobamos que el pedido no esté ya cancelado o recibido.
					if (!$pedido) {
						// Error, el pedido no existe
						$regError->setError('general', 'No existe ningún pedido con esa <b>referencia</b>');
						$regMem->setValor('titulo', 'Error. No existe el pedido.');
						$regMem->setValor('ver', 'lista');

					} else if ($pedido->getPropiedad('estado')!='Cancelado' && $pedido->getPropiedad('estado')!='Recibido') {
						$pedido->setPropiedad('estado', 'Cancelado');
						$pedidos->setItemBD($pedido);

						// Recuperamos las lineas de pedido
						$lineas->getItemBD(array('pedido_id'=>$pedido->getPropiedad('id')));
						$a=$lineas->getItemById();


						foreach ($a as $linea) {
							$producto = $prods->getItemBD(array('id'=>$linea->getPropiedad('producto_id')))->getItemById($linea->getPropiedad('producto_id'));
							$producto->setPropiedad('existencias', $producto->getPropiedad('existencias')+$linea->getPropiedad('cantidad'));
							
							// Restauramos las existencias de los productos
							$prods->setItemBD($producto);
							$regFeedback->addFeedback('Actualizadas las existencias de ' . $producto->getPropiedad('nombre') . ' en ' . $linea->getPropiedad('cantidad') . ' unidades.');
						}

						$regMem->setValor('titulo', 'Pedido cancelado');
						$regFeedback->addFeedback('Se ha cancelado el pedido');
						$regMem->setValor('ver', 'lista');
					} else {
						$regError->setError('general', 'No es posible cancelar el pedido porque ya ha sido <b>' . $pedido->getPropiedad('estado') . '</b>.');
						$regMem->setValor('ver', 'lista');

					}
				} else {
					$pedido = $pedidos->getItemBD(array ('id' =>$regMem->getValor('id')))->getItemById($regMem->getValor('id'));
					if ($pedido) {
						$regMem->setValor('titulo', 'Eliminar pedido');
					} else {
						$regError->setError('general', 'No existe el pedido.');
						$regMem->setValor('ver', 'lista');
					}
			
				}
				break; // Confirmación cancelar

			case 'Confirmar Pago':
				// Deberiamos cambiar el nombre del valor ver porque es confunso, reutilizado por ahora

				echo "Confirmando el pago!";
				exit;

				break; // Confirmar pago
		} // Fin switch acciones cancelar
		

		break; // ver = cancelar

	default:
		// Hay un valor en ver, pero no es válido.
		if ($regMem->getValor('ver'))
		$regSistema->setValor('acceso_denegado', 'administrar');
		break; // Default ver
}


include 'cabecera.php';
?>

<div id="main">

<?php
include 'top-menu.php';
include 'main-menu.php';
include 'sidebar-administrar.php';


// Si hay un error redirigimos la página
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

	<div class="separacion">
	<?php 
	/*		VER LISTA PEDIDOS 		*/
	if ($regMem->getValor('ver')=='lista') {
	?>
	<table>
		<tr>
			<th>Ref</th>
			<th>Nombre</th>
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
					$usuario = $datos_usuarios->getItemBD(array('id'=>$pedido->getPropiedad('usuario_id')))->getItemById($pedido->getPropiedad('usuario_id'));
					echo "<td><a href={$_SERVER['SCRIPT_NAME']}?ver=lista&amp;usuario={$pedido->getPropiedad('usuario_id')}>";
					echo "{$usuario->getPropiedad('nombre')} {$usuario->getPropiedad('apellido')}";
					echo "</a></td>";

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

	<?php
	// Si hay algún filtro, mostramos el enlace para volver a la lista completa
	if ($regMem->getValor('usuario') || $regMem->getValor('accion') || $regError->getError()) {
	?>
		<p class="centrado separacion">
			<a href="<?=$_SERVER['SCRIPT_NAME']?>">Volver a la lista de pedidos</a>
		</p>
	<?php
	}



	// Fin ver lista
	} else if ($regMem->getValor('ver')=='cancelar') {
	// Confirmación eliminar pedido
?>
	<p class="centrado">¿Estas seguro de que deseas eliminar el pedido con referencia <b><?=$pedido->getPropiedad('ref')?></b>?</p>

	<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
		<input type="hidden" name="id" value="<?=$pedido->getPropiedad('id')?>"/>
		<input type="hidden" name="ver" value="cancelar"/>
		<p class="separacion centrado">
			<input type="submit" value="Cancelar" name="accion" />
			<input type="submit" value="Cancelar Pedido" name="accion"/>
		</p>


	</form>

<?php
	} else if ($regMem->getValor('ver'=='detalle')) {

		// Mostramos los datos del usuario
		$usuario = $datos_usuarios->getItemBD(array('id'=>$pedido->getPropiedad('usuario_id')))->getItemById($pedido->getPropiedad('usuario_id'));
		echo "<div class= \"columnas\">";
		echo "<p>Nombre: <b>" . $usuario->getPropiedad('nombre') . " " . $usuario->getPropiedad('apellido') . '</b></p>';
		echo "<p>Dirección: <b>" . $usuario->getPropiedad('direccion') . "</b></p>";
		echo "<p>Población: <b>" . $usuario->getPropiedad('poblacion') . " (" . $usuario->getPropiedad('cp') . ")</b></p>";

		// Mostramos la cabecera del pedido (el pedido lo recuperamos en el switch del principio)
		echo "<p>Referencia: <b>" . $pedido->getPropiedad('ref') . "</b></p>";
		echo "<p>Fecha: <b>" . $pedido->getPropiedad('fecha') . "</b></p>";
		echo "<p>Estado: <b class=" . quitarEspacios($pedido->getPropiedad('estado')) . ">" . $pedido->getPropiedad('estado') . "</b></p>";
		echo "</div>";

		echo "<div class=\"separacion\">";
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
	</div>
		<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
			<input type="hidden" name="id" value="<?=$pedido->getPropiedad('id')?>"/>
			<input type="hidden" name="ver" value="cancelar"/>
			<p class="separacion centrado">
				<input type="submit" value="Cancelar" name="accion" />
				
				<input type="submit" value="Cancelar Pedido" name="accion"
				<?php
				if ($pedido->getPropiedad('estado')=='Recibido' || $pedido->getPropiedad('estado')=='Cancelado') {
					echo ' disabled ';
				}
				?>
				/>
				

				<input type="submit" value="Confirmar Pago" name="accion"
				<?php
				if ($pedido->getPropiedad('estado')!='Confirmado') {
					echo ' disabled ';
				}
				?>
				/>
			</p>

		</form>

	<?php
	} // Elfse final de las vistas
	
	?>
	</div>
</div>

<?php
include 'pie.php';
?>

</body>
</html>
