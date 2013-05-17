<?php
require_once 'inicializacion.php';
require_once 'classes/Usuarios.php';
require_once 'classes/DatosUsuarios.php';
require_once 'classes/Pedidos.php';
require_once 'classes/LineasPedido.php';


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
					$pedido->setPropiedad('estado', 'Cancelado');
					$pedidos->setItemBD($pedido);
					$regMem->setValor('titulo', 'Pedido cancelado');
					$regFeedback->addFeedback('Se ha cancelado el pedido');
					$regMem->setValor('ver', 'lista');
				} else {
					$pedido = $pedidos->getItemBD(array ('id' =>$regMem->getValor('id')))->getItemById($regMem->getValor('id'));
					if ($pedido) {
						$regMem->setValor('titulo', 'Eliminar pedido');
					} else {
						$regError->setError('general', 'No existe el pedido.');
						$regMem->setValor('ver', 'lista');
					}
			
				}
				break;
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

	<div>
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
					echo "<td>{$pedido->getPropiedad('ref')}</td>";
					
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
	if ($regMem->getValor('usuario') || $regMem->getValor('accion')) {
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
	} else {

	}
	?>
	</div>
</div>

<?php
include 'pie.php';
?>

</body>
</html>
