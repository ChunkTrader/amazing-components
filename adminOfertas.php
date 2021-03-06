<?php
require_once 'inicializacion.php';
require_once 'classes/Fabricantes.php';
require_once 'classes/Ofertas.php'; 


// Comprobamos si tiene privilegio de acceso a la p�gina
if (!$privilegios['verAdminOfertas']){
	$regSistema->setValor('acceso_denegado', 'administrar');
	header('Location: error.php');
	exit;
}

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

// Titulo por defecto de la p�gina
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
			// getItemById devuelve un array pero solo nos interesa el primer elemento (el �nico que debe haber)
			if (isset($producto[0])) {
				$producto = $producto[0];
			} 
			if (!$producto) {
				$regError->setError('general', ' No existe ning�n producto con esa <b>id</b>.');
				$correcto=FALSE;
			} else {
				// Comprobamos que no exista ya una oferta para ese producto
				$ofertas->getItemBD(array('producto_id'=>$regMem->getValor('producto_id')));
				if ($ofertas->getItemByProducto($regMem->getValor('producto_id'))) {
					$regError->setError('general', ' Ya existe una oferta para ese producto.');
					$correcto=FALSE;
				}
			}
		} else {
			$regError->setError('general', ' Tienes que elegir un producto para crear una oferta.');
			$correcto=FALSE;
		}

		if ($correcto) {
			
			//A�adimos la oferta en blanco y cargamos el formulario para editar
			$valores = array (
				'producto_id' => $producto->getPropiedad('id'),
				'precio_anterior' => $producto->getPropiedad('precio_venta'),
				'precio_oferta'=>  $producto->getPropiedad('precio_venta'),
				'activa' => 0
				);

			$oferta = new Oferta ($valores);
			$ofertas->addItemBD ($oferta);

			$regFeedback->addFeedback('Se ha creado la oferta con �xito');
			$regFeedback->addFeedback('Las ofertas recien creadas estan desactivadas por defecto');
			$regMem->setValor('accion','Editar');
			$regMem->setValor('titulo', 'Editar Oferta');
		} else {
			//Cargamos el formulario para a�adir
			$regMem->setValor('accion',NULL);
			$regMem->setValor('metodo',NULL);
		}
		break;

	case 'Editar':
		$regMem->setValor('titulo', 'Editar Oferta');
		break;

	case 'Guardar cambios':
		// Si todo es correcto actualizamos el precio actual de la oferta

		$correcto = TRUE;
		// Validamos la oferta
		$valores_oferta = (array_intersect_key($regMem->getValor(), Oferta::getListaPropiedades()));

		if (!$regMem->getValor('activa')) {
			$valores_oferta['activa']=FALSE;
		} else {
			$valores_oferta['activa']=TRUE;
		}


		$args_oferta = array_intersect_key(Oferta::getListaPropiedades(), $valores_oferta);
		$validar_oferta = filter_var_array($valores_oferta, $args_oferta);

		// Validamos el producto
		$valores_producto = array(
				'id'=>$regMem->getValor('producto_id'),
				'precio_venta'=>$regMem->getValor('precio_oferta')
			);

		$args_producto =array_intersect_key(Producto::getListaPropiedades(), $valores_producto);
		$validar_producto = filter_var_array($valores_producto, $args_producto);

		// Recorremos los arrays, si alg�n valor false es que ha habido alg�n error:
		// nos saltamos la $key activa porque es boolean y no hemos establecido opciones para el filtro

		foreach ($validar_oferta as $key=>$a) {
			if (!$a && $key!='activa') {
				$regError->setError($key,"Error en el campo <b>$key</b>");
				$correcto=FALSE;
			}
		}

		foreach ($validar_producto as $key=>$a) {
			if (!$a && $key!='activo') {
				$regError->setError($key,"Error en el campo <b>$key</b>");
				$correcto=FALSE;
			}
		}
		// Comprobamos si el producto existe.
		if ($regMem->getValor('producto_id')) {
			$producto=$prods->getItemBD(array ('id'=>$regMem->getValor('producto_id')))->getItemById($regMem->getValor('producto_id'));
			if (!$producto) {
				$regError->setError('general', ' No existe ning�n producto con esa <b>id</b>.');
				$correcto=FALSE;
			}
		}

		if ($correcto) {
			// Guardamos los cambios en la oferta
			$oferta = new Oferta($validar_oferta);
			$ofertas->setItemBD($oferta);

			// Si la oferta est� activa, el precio actual = al precio oferta
			if ($validar_oferta['activa']) {
				$producto->setPropiedad('precio_venta', $validar_oferta['precio_oferta']);
				$regFeedback->addFeedback("El producto muestra ahora el precio de oferta <b>{$validar_oferta['precio_oferta']}</b>&euro;");
			} else {
			// Si la oferta est� inactiva, el precio actual = al precio anterior
				$producto->setPropiedad('precio_venta', $validar_oferta['precio_anterior']);
				$regFeedback->addFeedback("El producto muestra ahora su precio normal <b>{$validar_oferta['precio_anterior']}</b>&euro;");
			}

			// Guardamos los cambios en el producto
			$producto->setPropiedad('id', $validar_producto['id']);

			$producto->setPropiedad('nombre', null);

			$prods->setItemBD($producto);

			$regFeedback->addFeedback('Se han guardado los cambios con �xito');
		} else {
			$regError->setError('general', 'No se han podido guardar los cambios');
		}

		$regMem->setValor('accion','Editar');
		$regMem->setValor('titulo', 'Editar Oferta');

		break;
	case 'Eliminar':
		$correcto=TRUE;

		// Comprobamos si el producto existe.
		if ($regMem->getValor('producto_id')) {
			$producto=$prods->getItemBD(array ('id'=>$regMem->getValor('producto_id')))->getItemById($regMem->getValor('producto_id'));
			if (!$producto) {
				$regError->setError('general', 'No existe ning�n producto con esa <b>id</b>.');
				$correcto=FALSE;		
			} else {
				// Comprobamos que la oferta corresponde a ese producto producto
				$oferta=$ofertas->getItemByProducto($regMem->getValor('producto_id'));
				if ($oferta->getPropiedad('producto_id')!=$producto->getPropiedad('id')) {
					$regError->setError('general', 'Error. La oferta no se corresponde con el producto.');
					$correcto=FALSE;
				}
			}
		}

		if ($correcto && $regMem->getValor('metodo')=='GET') {
			$regMem->setValor('titulo', 'Eliminar Oferta');
		} else {
			// Borramos la oferta
			$ofertas->delItemBD($oferta->getPropiedad('id'));
			$producto->setPropiedad('precio_venta', $oferta->getPropiedad('precio_anterior'));
			
			$a = $producto->getPropiedad('nombre');
			
			$producto->setPropiedad('nombre', null);
			$prods->setItemBD($producto);

			// Actualizamos el precio del producto

			$regFeedback->addFeedback('Se ha eliminado la oferta de <b>'. $a .'</b>');

			$regMem->setValor('accion',NULL);
			$regMem->setValor('metodo',NULL);

		}
		break;

	case 'Guardar Imagen':
		$correcto = TRUE;

		if (!$oferta) {			
			$correcto = FALSE;
		} else if ($regMem->getValor('slideshow_activo')!=$oferta->getPropiedad('slideshow_activo')) {
			// El valor es distinto, guardamos el nuevo estado independientemente de si hay o no imagen
			// Metodo cutre, habria que hacer un solo acceso a la base de datos.
			$oferta->setPropiedad('slideshow_activo', $regMem->getValor('slideshow_activo')? 1:0);
			$ofertas->setItemBD($oferta);
			$cambio_estado=TRUE;			

			if ($regMem->getValor('slideshow_activo') || $regMem->getValor('slideshow_activo')) {
				$regFeedback->addFeedback('Se ha activado el Slideshow.');
			} else {
				$regFeedback->addFeedback('Se ha desactivado el Slideshow.');
			}

		}

		if ($regMem->getValor('producto_id')) {
			$producto=$prods->getItemBD(array ('id'=>$regMem->getValor('producto_id')))->getItemById($regMem->getValor('producto_id'));
			if (!$producto) {
				$regError->setError('general', ' No existe ning�n producto con esa <b>id</b>.');
				$correcto=FALSE;
			}
		}


		if (!($_FILES['imagen']['name'])) {
			$regError->setError('archivo', 'El archivo no se ha subido correctamente.');
			$correcto = FALSE;
		}

		if ($_FILES['imagen']['size']>MAX_FILE_SIZE) {
			$regError->setError('archivo', 'El tama�o m�ximo para el archivo es '. MAX_FILE_SIZE . ' bytes.');
			$correcto = FALSE;
		}

		switch ($_FILES['imagen']['type']) {
			case 'image/gif':
			$nueva_imagen = @imagecreatefromgif($_FILES['imagen']['tmp_name']);
			break;
			case 'image/jpeg':
			$nueva_imagen = @imagecreatefromjpeg($_FILES['imagen']['tmp_name']);
			break;
			case 'image/png':
			$nueva_imagen = @imagecreatefrompng($_FILES['imagen']['tmp_name']);
			break;
			default:
			$regError->setError('archivo', 'El archivo debe ser de tipo jpeg, png o gif.');
			$correcto = FALSE;
		}

		// Comprobamos que se ha reprocesado correctamente
		if (empty($nueva_imagen)){
			$regError->setError('archivo', 'La imagen no es v�lida.');
			$correcto = FALSE;
		} else {
			// La recortamos a 703x300.
			$width = 703;
			$height = 300;
			$copia = imagecreatetruecolor(703, 300);
			$white = imagecolorallocate($copia, 255, 255, 255);
			imagefilledrectangle($copia, 0, 0, 703, 300, $white);
			
			
			imagecopyresampled($copia,$nueva_imagen,0,0,0,0,$width,$height,$width,$height);
			$nueva_imagen = $copia;
		}



		if ($correcto) {
			// Generamos un nombre basado en el producto, si se sube otra imagen para el mismo
			// producto la reescribimos.
			$nombre_imagen = $producto->getPropiedad('nombre');
			$nombre_imagen = Imagen::encode($nombre_imagen);

			// Guardamos la imagen
			imagejpeg($nueva_imagen, SHLOADDIR . $nombre_imagen . '.jpeg');

			
			// Activamos el slideshow tras subir la imagen con exito.
			$oferta->setPropiedad('slideshow_url', $nombre_imagen);
			$oferta->setPropiedad('slideshow_activo', TRUE);
			
			$ofertas->setItemBD($oferta);

			$regFeedback->addFeedback('Se ha actualizado la imagen con �xito.');
			

		}

		// Si ha sido un cambio de estado ignoramos el mensaje de error del archivo.
		if (!empty($cambio_estad)) {
			$regError->unsetError('archivo');
		}
		$regMem->setValor('accion','Editar');
		$regMem->setValor('titulo', 'Editar Oferta');

		break;


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
			
			/*	SIN ACCI�N, MOSTRAMOS EL FORMULARIO CREAR */
			if (!$regMem->getValor('metodo'))  {
			?>
				<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="post">
				<label>Producto</label>
				<select class="long" name="producto_id">
					<option>&lt;Selecciona un producto&gt;</option>
				<?php
				// 1-Recorremos las categorias que tienen 1 o m�s productos (las categorias 
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
						// 2-A�adimos la categor�a como opt-group
						echo "<optgroup label=\"{$cat->getPropiedad('nombre')}\">";
						// 3-A�adimos los productos que pertenecen a la categor�a
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
				<input type="text" name="precio_oferta" value="<?=$oferta->getPropiedad('precio_oferta')?>"/>

				<!-- Este campo deber�a estar disabled para ver el precio anterior, y para las ofertas
					reemplazar el precio_venta por un precio_oferta, para simplificar lo dejamos as�-->
				<label>Anterior</label>
				<input disabled type="text" value="<?=$oferta->getPropiedad('precio_anterior')?>"/>
				<input type="hidden" name="precio_anterior" value="<?=$oferta->getPropiedad('precio_anterior')?>"/>
				<!-- Este campo ser�a para mostrar el % calculado con JavaScript dinamicamente-->
				<label>Descuento</label>
					<?php
					$descuento = round(100-(1/$oferta->getPropiedad('precio_anterior')*$oferta->getPropiedad('precio_oferta'))*100,2);
					?>
				<input type="text" id="descuento" name="descuento" value="<?=$descuento?>"
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

				<input type="hidden" name="producto_id" value="<?=$producto->getPropiedad('id')?>">
				<input type="hidden" name="id" value="<?=$oferta->getPropiedad('id')?>">
				<p class="centrado">
					<input type="submit" name="accion" value="Guardar cambios"/>
					<input type="submit" value="Cancelar" name="accion" />
				</p>

			</form>
			
			<!-- Formulario Slideshow -->
			<h2 class="separacion">Imagen Slideshow</h2>
			
			<img class="<?=($oferta->getPropiedad('slideshow_activo'))? 'slideshow' : 'slideshow_disabled'?>" src="images/slideshow/<?=$oferta->getPropiedad('slideshow_url')?>.jpeg" alt="">
			
			<p class="separaci�n">La imagen para el Slideshow debe ser de 703x300 pixels o no se mostrar� correctamente.</p>
			<p>El peso m�ximo de la imagen es de <?=(MAX_FILE_SIZE/1024)?>kbs.</p>
			<p>La imagen solo se mostrar� en el Slideshow si la oferta est� activa.</p>
			<form class="separacion" action="<?=$_SERVER['SCRIPT_NAME'] ?>" method="post" enctype="multipart/form-data">
				<label>A�adir:</label>
				<input type="hidden" name="MAX_FILE_SIZE" value="<?=MAX_FILE_SIZE?>" />
				<input type="file" name="imagen"/>
				<input type="hidden" value="<?=$oferta->getPropiedad('id')?>" name="id" />
				<input type="hidden" name="producto_id" value="<?=$producto->getPropiedad('id')?>" />
				<label>Activo</label>
				<input type="checkbox" name="slideshow_activo"
					<?php
					if ($oferta->getPropiedad('slideshow_activo')){
						echo ' checked';
					}
					if ($oferta->getPropiedad('slideshow_url')==NULL) {
						echo ' disabled';
					}
					?>
				/>

				<p class="centrado">
					<input type="submit" value="Guardar Imagen" name="accion"/>
				</p>
			</form>

			<?php
			} else {
			?>

		<!-- FORMULARIO PARA CONFIRMAR ELIMINACI�N  -->
		<form action="<?=$_SERVER['SCRIPT_NAME'] ?>" method="post">

			<p class="separacion centrado">�Estas seguro que deseas eliminar la oferta del producto <b><?=$producto->getPropiedad('nombre')?></b>?</p>
			<p class="separacion centrado">El precio del producto volver� a su <b>precio anterior</b></p>

			<input type="hidden" name="id" value="<?=$oferta->getPropiedad('id')?>" />
			<input type="hidden" name="producto_id" value="<?=$producto->getPropiedad('id')?>" />
			<p class="separacion centrado">
				<input type="submit" value="Eliminar" name="accion"/>
				<input type="submit" value="Cancelar" name="accion" />
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
		// con los t�tulos de la cabecera. Recorresmo el bucle en sentido contrario porque 
		// nos interesa mostrar primero las activas.

		$d=array('Ofertas inactivas', 'Ofertas activas');
		
		for ($estado=1;$estado>=0;$estado--){
		
		?>
			<h2 class="separacion"><?=$d[$estado]?></h2>
			<table>
				<tr>
					<th>Producto</th>
					<th>Categor�a</th>
					<th>Precio Oferta</th>
					<th>Precio Normal</th>
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
							echo "<td>" . number_format($oferta->getPropiedad('precio_oferta'),2)."&euro;</td>";
							echo "<td>" . number_format($oferta->getPropiedad('precio_anterior'),2) . "&euro;</td>";

							$descuento = round(100-(1/$oferta->getPropiedad('precio_anterior')*$oferta->getPropiedad('precio_oferta'))*100,2);
							if ($descuento<=0) {
								echo '<td class="error">';
							} else {
								echo '<td class="correcto">';
							}
							echo "{$descuento}%</td>";

							echo "<td>" . ($oferta->getPropiedad('activa')?'S�':'No') ."</td>";

							echo "<td>";
							// Si hay una imagen de slideshow mostramos el icono
							if ($oferta->getPropiedad('slideshow_url')) {
								echo "<img src=\"images/" . ($oferta->getPropiedad('slideshow_activo')? 'slide-on.png':'slide-off.png') . "\" alt=\"\" />";
							}

							// A�adimos el icono de eliminar
							echo "<a href=\"{$_SERVER['SCRIPT_NAME']}?id={$oferta->getPropiedad('id')}&accion=Eliminar&producto_id={$b->getPropiedad('id')}\" title=\"Eliminar Oferta: {$b->getPropiedad('nombre')}\"><img src=\"images/icon_delete.gif\"/></a>";
							echo "</td>";
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

	<?php
	include 'pie.php';
	?>
</body>
</html>
