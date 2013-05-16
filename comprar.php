<?php
require_once 'inicializacion.php';
require_once 'classes/Usuarios.php';

$usuarios = new Usuarios($controlador);

if (!$regMem->getValor('ver') && (!isset($regSistema->getValor('privilegios')['comprar']))) {
	$regSistema->setValor('acceso_denegado', 'autenticar');
	$regSistema->setValor('forward', $_SERVER['SCRIPT_NAME']);
	header('Location: error.php');
	exit;
} 


// Titulo por defecto de la página
$regMem->setValor('titulo', 'Comprar');


$carrito = $regSistema->getValor('carrito');
$prods = new Productos($controlador);

switch ($regMem->getValor('accion')) {
	case 'Eliminar':
		unset($carrito[$regMem->getValor('id')]);
		$regFeedback->addFeedback("Se ha eliminado el producto eliminado del carrito.");		
		$regSistema->setValor('carrito',$carrito);
		break;
	
	case 'Guardar cambios':
		// Recorremos las lineas de pedido y modificamos las cantidades
		foreach ($carrito as $clave => $linea){

			if ($regMem->getValor('cantidad')[$clave]==0) {
				unset($carrito[$clave]);
				$regFeedback->addFeedback("Se ha eliminado el producto eliminado del carrito.");		

			} else if (!filter_var($regMem->getValor('cantidad')[$clave], FILTER_VALIDATE_INT))  {
				$regError->setError('general', 'La cantidad introducida no es válida.');
			
			} else if ($regMem->getValor('cantidad')[$clave]<0) {
				// OJO, hay que comprobar si hay existencias
				$regError->setError('general', 'La cantidad introducida no puede ser menor de 0.');
			
			} else {
				// Se ha introducido una cantidad, comprobamos existencias
				$producto = $prods->getItemBD(array('id'=>$linea['id']))->getItemById($linea['id']);

				if ($producto->getPropiedad('existencias')<$regMem->getValor('cantidad')[$clave]) {
					$regError->setError('General', "En estos momentos no disponemos de suficientes existencias. Solo disponemos de {$producto->getPropiedad('existencias')} unidades de {$producto->getPropiedad('nombre')}.");
				} else {
					// Si hay suficientes ajustamos la cantidad
						if ($carrito[$clave]['cantidad']!=$regMem->getValor('cantidad')[$clave]) {
							$regFeedback->addFeedback('Se ha modificado la cantidad');
						}
						$carrito[$clave]['cantidad']=$regMem->getValor('cantidad')[$clave];
				}
			}
		}

		// Actualizamos el carrito
		$regSistema->setValor('carrito', $carrito);
		break;
}





if (!$carrito){
	$regError->setError('general', 'El carrito está vacio.');
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

	?>
	<div class="separacion">
	<?php	
		//Paso 1
		if ($regMem->getValor('paso')==1 OR !$regMem->getValor('paso')) {

			echo '<h3>Paso 1: Comprobar el pedido</h3>';
			?>
			<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
				<table>
					<tr>
						<th>Producto</th>
						<th>Unidades</th>
						<th>Precio</th>
						<th>Subtotal</th>
						<th></th>
					</tr>
				<?php



				// Comprobamos si existe el carrito
				if ($carrito) {
					// Calculamos la cantidad de productos, y el precio total
					$total_cantidad = 0;
					$total_precio = 0;
					foreach ($carrito as $clave => $linea) {
						echo "<tr>";
						
						echo "<td>";
						$producto = $prods->getItemBD(array('id' => $linea['id']))->getItemById($linea['id']);
						echo $producto->getPropiedad('nombre');
						echo "</td>";

						echo "<td>";
						echo "<input type=\"text\" size=\"3\" name=\"cantidad[$clave]\" value=\"{$linea['cantidad']}\"/></td>";
						echo "</td>";

						echo "<td>" . number_format($linea['precio']) . "&euro;</td>";
						echo "<td>" . number_format($linea['precio']*$linea['cantidad']) . "&euro;</td>";
						echo "<td>";

						echo "<a href=\"{$_SERVER['SCRIPT_NAME']}?accion=Eliminar&id={$clave}\" title=\"Eliminar del carrito\"><img src=\"images/icon_delete.gif\"/></a>";
						echo "</td>";
						echo "</tr>";
					}
				} else {

				}

				?>
				</table>
				<p class="centrado">
					<input type="submit" name="accion" value="Guardar cambios" />
					<p class="siguiente"><a href="<?=$_SERVER['SCRIPT_NAME']?>?paso=2">Continuar</a></p>
				<p>
			</form>










	<?php
		}
	?>







	</div>

</div>

<?php
include 'pie.php';
?>

</body>
</html>
