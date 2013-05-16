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


// Titulo por defecto de la p�gina
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
				$regError->setError('general', 'La cantidad introducida no es v�lida.');
			
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
		break; // Guardar cambios

	case 'Continuar':
		$correcto = TRUE;
		


		// Validamos los datos del paso 2
		$regexp = "/[\^<,\"@\/\{\}\(\)\*\$%\?=>:\|;#]+/i";
		if (preg_match($regexp, $regMem->getValor('nombre'))) {
		    // error
		    $regError->setError('nombre', 'El nombre contiene car�cteres no v�lidos');
		    $correcto = FALSE;
		} else if (strlen($regMem->getValor('nombre'))<4) {
			$regError->setError('nombre', 'El nombre debe tener al menos 4 caracteres(tiene'. strlen($regMem->getValor('nombre')) . ')');
		    $correcto = FALSE;
		}

		if (preg_match($regexp, $regMem->getValor('apellido'))) {
		$regError->setError('apellido', 'El apellido contiene car�cteres no v�lidos');
		    $correcto = FALSE;
		} else if (strlen($regMem->getValor('apellido'))<4) {
			$regError->setError('apellido', 'El apellido debe tener al menos 4 caracteres (tiene '. strlen($regMem->getValor('apellido')) . ')');
		    $correcto = FALSE;
		}

		if (preg_match($regexp, $regMem->getValor('poblacion'))) {
			$regError->setError('poblacion', 'La poblaci�n contiene car�cteres no v�lidos');
		    $correcto = FALSE;
		} else if (!$regMem->getValor('poblacion')) {
			$regError->setError('poblacion', 'La poblaci�n no puede estar en blanco.');
		    $correcto = FALSE;
		}

		$regexp = "/^\d{5}$/";
		if (!preg_match($regexp, $regMem->getValor('cp'))) {
			$regError->setError('cp', 'El c�digo postal debe estar compuesto por 5 d�gitos');
			$correcto = FALSE;
		}

		// La direcci�n no la validamos.
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
			
			// Si no existe direcci�n de envio para el usuario a�adimos una nueva
			// Si existe la actualizamos
			// OJO, no comprobamos si los datos han cambiado.



			$regMem->setValor('paso', 3);
			$regFeedback->addFeedback('Datos de usuario correctos.');
		}
		

		break; // Continuar



} // Fin switch accion





if (!$carrito){
	$regError->setError('general', 'El carrito est� vacio.');
}

// Asignamos el subtitulo y acciones especiales seg�n el paso
switch ($regMem->getValor('paso')){
	case 1:
		$regMem->setValor('subtitulo', 'Paso 1: Comprobar el pedido');
		break;
	
	case 2:
		$regMem->setValor('subtitulo', 'Paso 2: Datos del usuario');
		// Comprobamos si ya existe una direcci�n de envio para el usuario.




		break;

	case 3:
		$regMem->setValor('subtitulo', 'Datos bancarios y confirmaci�n');
		break;


	default:
		$regMem->setValor('subtitulo', 'Paso 1: Comprobar el pedido');
		break;
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
	<h3><?=$regMem->getValor('subtitulo')?></h3>
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
					// En esta tabla mostramos los precios sin IVA y el total desglosado al final

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

						echo "<td>" . number_format($linea['precio']/(1+IVA),2) . "&euro;</td>";
						echo "<td>" . number_format($linea['precio']*$linea['cantidad']/(1+IVA),2) . "&euro;</td>";
						echo "<td>";

						echo "<a href=\"{$_SERVER['SCRIPT_NAME']}?accion=Eliminar&id={$clave}\" title=\"Eliminar del carrito\"><img src=\"images/icon_delete.gif\"/></a>";
						echo "</td>";
						echo "</tr>";
						$total_precio += $linea['precio']*$linea['cantidad'];
					}

				} else {

				}

				?>
				<tr>
					<th>IVA <?=(IVA*100)?>%</th>
					<th></th>
					<th></th>
					<th><?=number_format($total_precio*IVA,2)?>&euro;</th>
					<th></th>
				</tr>
				<tr>
					<th>TOTAL</th>
					<th></th>
					<th></th>
					<th><?=number_format($total_precio,2)?>&euro;</th>
					<th></th>
				</tr>

				</table>
				<p class="centrado">
					<input type="submit" name="accion" value="Guardar cambios" />
					<p class="siguiente"><a href="<?=$_SERVER['SCRIPT_NAME']?>?paso=2">Continuar</a></p>
				<p>
			</form>










	<?php
		/* 	PASO 2		 */
		} else if ($regMem->getValor('paso')==2) {
		?>

		
		<p class="centrado">Son necesarios los datos de usuario para poder tramitar el pedido.</p>
		<form class="separacion" action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
			<label>Nombre:</label>
			<input type="text" name="nombre" value="<?=$regMem->getValor('nombre')?>"/>

			<label>Apellido:</label>
			<input type="apellido" name="apellido" value="<?=$regMem->getValor('apellido')?>"/>

			<label>Direcci�n:</label>
			<input type="text" name="direccion" value="<?=$regMem->getValor('direccion')?>"/>

			<label>Poblaci�n:</label>
			<input type="text" name="poblacion" value="<?=$regMem->getValor('poblacion')?>"/>

			<label>CP:</label>
			<input type="text" name="cp" value="<?=$regMem->getValor('cp')?>"/>

			<input type="hidden" name="paso" value="2" />


			<p class="separacion"></p>
			<input class="siguiente" type="submit" name="accion" value="Continuar" />
			<p class="volver"><a href="<?=$_SERVER['SCRIPT_NAME']?>?paso=1">Volver</a></p>


		</form>




	<?php
		/* PASO 3  		*/
		} 
	
	?>







	</div>

</div>

<?php
include 'pie.php';
?>

</body>
</html>
