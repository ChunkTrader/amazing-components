<?php
require_once 'configuracion.php';
require_once 'conectar_bd.php';

require_once 'classes/Controlador.php';
require_once 'classes/Registro.php';

require_once 'classes/Categorias.php';
require_once 'classes/Productos.php';
require_once 'classes/Fabricantes.php';
require_once 'classes/Imagenes.php'; 
require_once 'classes/Ofertas.php'; 

$PDO = new PDOConfig ();

// Incializamos los registros
$regMem = RegistroMemoria::instancia();
$regError = RegistroErrores::instancia();
$regFeedback = RegistroFeedback::instancia();

// El controlador de registros almacena un array con acceso a los registros que le añadamos, este
// controlador se pasa a las colecciones al crearlo para que puedan mandar mensajes a la aplicación
$controlador = new Controlador();
$controlador -> setRegistro ('feedback', $regFeedback);
$controlador -> setRegistro ('errores', $regError);
$controlador -> setPDO($PDO);

$cats = new Categorias($controlador);
$prods = new Productos($controlador);
$galeria = new Imagenes($controlador);
$ofertas = new Ofertas($controlador);

// Intentamos recuperar la oferta
if ($regMem->getValor('id')) {
	$oferta =$ofertas->getItemBD(array ('id'=>$regMem->getValor('id')))->getItemById($regMem->getValor('id'));
	if (!$oferta) {
		$regError->setError('general', ' No existe ninguna oferta con esa <b>id</b>.');
	}
}

// Titulo por defecto de la página
$regMem->setValor('titulo', 'Crear Oferta');

switch ($regMem->getValor('accion')){
	case 'Cancelar':
		header("Location: {$_SERVER['SCRIPT_NAME']}");
		exit;
		break;

	case 'Crear oferta':
		$correcto=TRUE;
		// Comprobamos si el producto existe
		if ($regMem->getValor('producto_id')) {
			$producto=$prods->getItemBD(array ('id'=>$regMem->getValor('producto_id')))->getItemById($regMem->getValor('id'));
			// getItemById devuelve un array pero solo nos interesa el primer elemento (el único que debe haber)
			$producto = $producto[0];
			if (!$producto) {
				$regError->setError('general', ' No existe ningún producto con esa <b>id</b>.');
				$correcto=FALSE;
			} else {
				// Comprobamos que no exista ya una oferta para ese producto
				$ofertas->getItemBD(array('producto_id'=>$regMem->getValor('producto_id')));
				if ($ofertas->getItemById($regMem->getValor('producto_id'))) {
					$regError->setError('general', ' Ya existe una oferta para ese producto.');
					print_r($ofertas);
					$correcto=FALSE;
				}
			}
		} else {
			$regError->setError('general', ' Tienes que elegir un producto para crear una oferta.');
			$correcto=FALSE;
		}

		if ($correcto) {
			
			//Añadimos la oferta en blanco y cargamos el formulario para editar
			$valores = array (
				'producto_id' => $producto->getPropiedad('id'),
				'precio_anterior' => $producto->getPropiedad('precio_venta'),
				'activa' => 0
				);

			$oferta = new Oferta ($valores);
			$ofertas->addItemBD ($oferta);

			$regFeedback->addFeedback('Se ha creado la oferta con éxito');
			$regFeedback->addFeedback('Las ofertas recien creadas estan desactivadas por defecto');
			$regMem->setValor('accion','Editar');
			$regMem->setValor('titulo', 'Editar Oferta');
		} else {
			//Cargamos el formulario para añadir
			$regMem->setValor('accion',NULL);
			$regMem->setValor('metodo',NULL);
		}
		break;

		case 'Editar':
			$regMem->setValor('titulo', 'Editar Oferta');
}

// Cargamos todos los productos, las categorias, las ofertas
$prods->getItemBD();
$cats->getItemBD();
$ofertas->getItemBD();

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
			
			/*	SIN ACCIÓN, MOSTRAMOS EL FORMULARIO CREAR */
			if (!$regMem->getValor('metodo'))  {
			?>
				<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="post">
				<label>Producto</label>
				<select class="long" name="producto_id">
					<option>&lt;Selecciona un producto&gt;</option>
				<?php
				// 1-Recorremos las categorias que tienen 1 o más productos (las categorias 
				// principales no tienen productos directos).
				$a = $cats->getItemById();

				foreach ($a as $cat) {
					$b = $prods->getItemByCategoria($cat->getPropiedad('id'));
					// 1.1 Eliminamos los productos que ya esten en la lista de ofertas
					foreach ($b as $key=>$producto) {
						if ($ofertas->getItemByProducto($producto->getPropiedad('id'))){
							unset($b[$key]);
						}
					}

					if ($b) {
						// 2-Añadimos la categoría como opt-group
						echo "<optgroup label=\"{$cat->getPropiedad('nombre')}\">";
						// 3-Añadimos los productos que pertenecen a la categoría
						foreach ($b as $producto) {
							echo "<option value=\"{$producto->getPropiedad('id')}\">";
							echo "{$producto->getPropiedad('nombre')}</option>";
						}
						echo "</optgroup>";
					}
				}

				?>

				</select>
				<p class="centrado">
					<input  type="submit" name="accion" value="Crear oferta"/>
				</p>


			</form>


			<?php
			} else if ($regMem->getValor('accion')=='Editar'){
				$producto=$prods->getItemById($regMem->getValor('producto_id'));
				$oferta=$ofertas->getItemByProducto($regMem->getValor('producto_id'));
			?>
			<!-- FORMULARIO PARA EDITAR -->
			
			<h3 class="separacion"><?=$producto->getPropiedad('nombre')?></h3>
			<form action = <?=$_SERVER['SCRIPT_NAME']?> method="post" class="separacion">

				<label>Precio oferta</label>
				<input type="text" name="precio_venta" value="<?=$producto->getPropiedad('precio_venta')?>"/>

				<label>Anterior</label>
				<input type="text" name="precio_venta" value="<?=$oferta->getPropiedad('precio_anterior')?>" disabled/>

				<!-- Este campo es para mostrar el % calculado con JavaScript dinamicamente-->
				<label>Descuento</label>
					<?php
					$descuento = round(100-(1/$oferta->getPropiedad('precio_anterior')*$producto->getPropiedad('precio_venta'))*100,2);
					?>
				<input type="text" id="descuento" value="<?=$descuento?>"
				<?php
					if ($descuento<=0) {
						echo ' class="error" ';
					} else {
						echo ' class="correcto" ';
					}
				?> 
				/>

				<label>Activa</label>
				<input type="checkbox" name="activa" 
					<?php
					if ($oferta->getPropiedad('activa')){
						echo ' checked';
					}
					?>
				/>

				<input type="hidden" name="producto_id">
				<input type="hidden" name="id">
				<p class="centrado">
					<input type="submit" name="accion" value="Guardar cambios"/>
				</p>

			</form>





			<?php

			}

			
			?>


		</div>

		<div>

		<?php
		$a=$ofertas->getItemById();


		if (!$ofertas->getTotal()) {
			echo '<h2 class="separacion">No hay ofertas creadas</h2>';

			echo '<h3 class="separacion">No se ha creado ninguna oferta</h3>';
		} else {

		// Como el codigo es el mismo para las dos tablas, usamos un bucle y creamos un array
		// con los títulos de la cabecera. Recorresmo el bucle en sentido contrario porque 
		// nos interesa mostrar primero las activas.

		$d=array('Ofertas inactivas', 'Ofertas activas');
		
		for ($estado=1;$estado>=0;$estado--){
		
		?>
			<h2 class="separacion"><?=$d[$estado]?></h2>
			<table>
				<tr>
					<th>Producto</th>
					<th>Categoría</th>
					<th>Precio actual</th>
					<th>Precio anterior</th>
					<th>Descuento</th>
					<th>Activa</th>
					<th> </th>
				</tr>
				<?php
					foreach ($a as $oferta) {
						$b = $prods->getItemById($oferta->getPropiedad('producto_id'));
						$c = $cats->getItemById($b->getPropiedad('categoria_id'));

						if ($oferta->getPropiedad('activa')==$estado) {
							echo "<tr>";
							echo "<td>";
							echo "<a href=\"{$_SERVER['SCRIPT_NAME']}?id=".$oferta->getPropiedad('id') . "&amp;producto_id=" . $oferta->getPropiedad('producto_id') . "&amp;accion=Editar\">";
							echo "{$b->getPropiedad('nombre')}</a></td>";
							echo "<td>{$c->getPropiedad('nombre')}</td>";
							echo "<td>{$b->getPropiedad('precio_venta')}&euro;</td>";
							echo "<td>{$oferta->getPropiedad('precio_anterior')}&euro;</td>";

							$descuento = round(100-(1/$oferta->getPropiedad('precio_anterior')*$b->getPropiedad('precio_venta'))*100,2);
							if ($descuento<=0) {
								echo '<td class="error">';
							} else {
								echo '<td class="correcto">';
							}
							echo "{$descuento}%</td>";

							echo "<td>" . ($oferta->getPropiedad('activa')?'Sí':'No') ."</td>";
							echo "<td></td>";
							echo "</tr>";	
						}
						
					}
				
			?>
			</table>
		<?php
		}
	}
	?>

		</div>


	</div>
</div>
	<script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox(
		{
			'loop' : false,
		}
		);

	});
	</script>
	<?php
	include 'pie.php';
	?>
</body>
</html>
