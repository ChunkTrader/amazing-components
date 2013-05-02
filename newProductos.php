<?php
require_once 'conectar_bd.php';
require_once 'Categorias.php';
require_once 'Productos.php';
require_once 'Fabricantes.php';
require_once 'Imagenes.php';

define ("MAX_FILE_SIZE", "307200");


$PDO = new PDOConfig ();

$cats = new ListasCategorias($PDO);
$prods = new ListasProductos($PDO);
$galeria = new Galeria($PDO);

$get_accion = empty($_GET['accion']) ? null : $_GET['accion'];
$post_accion = empty($_POST['accion']) ? null : $_POST['accion'];

$producto_id = empty($_REQUEST['id']) ? null : $_REQUEST['id'];


if ($post_accion == 'Cancelar') {
	header("Location: newProductos.php");
	exit;
}


if (! empty ( $_POST ['nombre'] ) && $post_accion=='Añadir') {
	$nombre = $_POST ['nombre'];
	$categoria_id = $_POST ['categoria_id'];
	$precio_venta = $_POST ['precio_venta'];
	$descripcion = $_POST ['descripcion'];
	$fabricante_id = $_POST['fabricante_id'];
	$existencias = $_POST['existencias'];


	if ($categoria_id) {
		$nuevo_producto = new Producto ($nombre, null, $categoria_id, $precio_venta, $descripcion);
		$nuevo_producto->setFabricanteId($fabricante_id);
		$nuevo_producto->setExistencias($existencias);
		$prods->addProductoBD ( $nuevo_producto);
	} else {
		echo "ERROR. Tienes que seleccionar una categoría.";
	}
}

// Cargamos todos los productos
$prods->getProductoBD ( );

// Comprobamos si existe el producto
$producto = $prods->getProductoById($producto_id);

if ($post_accion=='Editar') {
	// Actualizamos nombre, parent_cat y descripción		
	$nombre = $_POST['nombre'];
	$categoria_id = $_POST['categoria_id'];
	$precio_venta = $_POST['precio_venta'];
	$descripcion = $_POST['descripcion'];
	$fabricante_id = $_POST['fabricante_id'];
	$existencias = $_POST['existencias'];
	$activa = isset($_POST['activa']);
	
	$disponibilidad = $_POST['disponibilidad'];
	$fecha = $producto->getFecha();

	$nuevo_producto = new Producto($nombre, $producto_id, $categoria_id, $precio_venta, $descripcion);
	$nuevo_producto->setFabricanteId($fabricante_id);		
	$nuevo_producto->setExistencias($existencias);
	$nuevo_producto->setDisponibilidad($disponibilidad);
	$nuevo_producto->setActiva($activa);

	$prods->setProductoBD ($nuevo_producto);

	// recargamos el producto
	$producto = $prods->getProductoById($producto_id);

	// recuperamos la disponibilidad
	$disponibilidad = $producto->getDisponibilidad();

} else if (($get_accion=='Editar' || $post_accion=='Guardar Imagen') && $producto) {
	$nombre = $producto->getNombre();
	$categoria_id = $producto->getCategoriaId();
	$precio_venta = $producto->getPrecioVenta();
	$descripcion = $producto->getDescripcion();
	$fabricante_id = $producto->getFabricanteId();
	$existencias = $producto->getExistencias();
	$disponibilidad = $producto->getDisponibilidad();
	$fecha = $producto->getFecha();
	$activa = $producto->getActiva();

}

if ($producto && $post_accion=='Eliminar') {
		
		$prods->delProductoBD ( $producto_id);

}


if ($post_accion == 'Guardar Imagen' && !empty($_FILES['imagen']['name'])) {
	$correcto = TRUE; // Mientras no encontremos ningún problema.

	$uploaddir = 'images/products/';

	// Comprobamos el tamaño
	if ($_FILES['imagen']['size']>MAX_FILE_SIZE) {
		$correcto = FALSE;
	}

	// Comprobamos el tipo
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
			$correcto = FALSE;
	}

	// Comprobamos que se ha reprocesado correctamente
	if (!$nueva_imagen) {
		echo "La imagen no es válida.";
		$correcto = FALSE;
	} 

	// Si la imagen es correcta la guardamos como jpeg
	if ($correcto){

		// la url es el nombre del producto + uniqid()

		
		do {
			$nombre_imagen = $producto->getNombre() . '_' . uniqid(); // a la imagen para que sea única, comprobamos si existe por si acaso
			// Sustituimos todos los carácteres no alfanuméricos
			$nombre_imagen = quitarEspacios($nombre_imagen);
		} while (file_exists($uploaddir . $nombre_imagen . '.jpeg'));

		// Guardamos la imagen
		imagejpeg($nueva_imagen, $uploaddir . $nombre_imagen . '.jpeg');

		// Si hay otras imagenes ya subida, no hace falta ponerla como principal.

		$principal = empty($_POST['imagenes']);

		$imagen = new Imagen($producto->getId(), null, $nombre_imagen, $principal);
		$galeria->addImagenBD($imagen);


	} 
}

function quitarEspacios($string) {
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		return preg_replace($old_pattern, $new_pattern , $string);
}



$galeria->getImagenBD ($producto_id);

$cats->getCategoriaBD (  );

if (!empty($_GET['Principal'])) {
		$principal_id = $_GET['Principal'];
		// Recorremos todas las imagenes del producto y ponemos principal a FALSE excepto la 
		// que hemos cambiado
		$a = $galeria->getImagenById();
		foreach ($a as $key=>$imagen){
			
			if ($imagen->getId() == $principal_id){
				$imagen->setPrincipal(TRUE);
			} else {
				$imagen->setPrincipal(FALSE);
			}
			$galeria->setImagenBD($imagen);
		}

}

if (!empty($_GET['EliminarImagen'])) {
		$imagen_id = $_GET['EliminarImagen'];

		$imagen = $galeria->getImagenById($imagen_id);
		// Comprobamos si la imagen existe:
		if ($imagen) {
			// Comprobamos si era la principal
			if ($imagen->getPrincipal()) {
				//echo "Es la imagen principal! Saliendo...";
				//exit;
				$imagen =$galeria->getImagenByProductoId($producto_id);
				if ($imagen){
					// Si existen otras imagenes, ponemos la primera como principal
					$imagen->setPrincipal(TRUE);
					$galeria->setImagenBD($imagen);
				}
			}

			$galeria->delImagenBD($imagen_id);
		} else {
			// la imagen no existe
		}
	}

?>
<head>
<link href="css/main.css" rel="stylesheet" type="text/css" title="main" />
<link href="css/admin-site.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery-2.0.0.min.js"></script>
<script type="text/javascript" src="js/jquery.fancybox.pack.js"></script>


</head>
<body>


<div id="main-content">
	<?php
	// No hay ninguna acción seleccionada o la última acción ha sido añadir, mostramos formulario añadir.
	if ((empty($get_accion) && empty($post_accion)) || $post_accion == 'Añadir') {
	
	?>

	<h2>Añadir Productos</h2>

		<div class="separacion" >
			<form action="<?=$_SERVER['SCRIPT_NAME'] ?>" method=post>
				<label>Nombre: </label>
				<input type="text" name="nombre" />
				<label>Parent Cat: </label>
				<select name="categoria_id">
					<option value="" selected="selected">&lt;Selecciona una categoría&gt;</option>
					<?php
					$a = $cats->getChildCategoriasById(0);
					//$a = $cats->getCategoriaById ();
					
					foreach ($a as $cat ) {
						echo "<optgroup label=\"{$cat->getNombre()}\">";
						$b = $cats->getChildCategoriasById($cat->getId());
						foreach ($b as $catb) {
							echo "<option value=\"{$catb->getId()}\">{$catb->getNombre()}</option>";
						}
							
						echo "</optgroup>";
					}
					?>
				</select>
				<label>Precio: </label>
				<input type="text" name="precio_venta" />

				<label>Fabricante: </label>
				<select name="fabricante_id">
					<?php
					$fabs = new ListaFabricantes($PDO);
					$fabs->getFabricanteBD();
					$a = $fabs->getFabricanteById();
					foreach ($a as $fab) {
						echo "<option value=\"{$fab->getId()}\"";
						if ($fab->getId() == 0) {
							echo " selected ";							
						}
						echo ">{$fab->getNombre()}</option>";
					}

					?>
				</select>
				
				<label>Existencias: </label>
				<input type="text" name="existencias" />

				<label>Descripcion:</label>
				<textarea name="descripcion" rows="5"></textarea>
				<p class="centrado">
					<input type="submit" value="Añadir" name="accion"/>
				</p>
			</form>
		</div>
	<?php 
	} else if ($get_accion == 'Editar' || $post_accion == 'Guardar Imagen' || $get_accion=='Principal') {
		
	// La acción es por GET, hemos clicado un elemento para editarlo, mostramos el formulario.
	// OJO, no estamos controlando si se ha pasado una id de producto válida.

	?>

	<h2>Editar Productos</h2>
	
		<div class="separacion" >
			<form action="<?=$_SERVER['SCRIPT_NAME'] ?>" method="post">
				<label>Nombre:</label>
				<input type="text" name="nombre" value="<?=$nombre?>"/>
				<label>Categoria:</label>
				<select name="categoria_id">
					<?php
					$a = $cats->getChildCategoriasById(0);
					//$a = $cats->getCategoriaById ();
					
					foreach ($a as $cat ) {
						echo "<optgroup label=\"{$cat->getNombre()}\">";
						$b = $cats->getChildCategoriasById($cat->getId());
						foreach ($b as $catb) {
							echo "<option value=\"{$catb->getId()}\"";

							if ($catb->getId() == $categoria_id) {
								echo " selected ";
							}

							echo ">{$catb->getNombre()}</option>";
						}
							
						echo "</optgroup>";
					}
					?>
				</select>
				<label>Precio:</label>
				<input type="text" name="precio_venta" value="<?=$precio_venta?>"/>

				<label>Fabricante:</label>
				<select name="fabricante_id">
					<?php
					$fabs = new ListaFabricantes($PDO);
					$fabs->getFabricanteBD();
					$a = $fabs->getFabricanteById();
					foreach ($a as $fab) {
						echo "<option value=\"{$fab->getId()}\"";
						if ($fab->getId() == $producto->getFabricanteId()) {
							echo " selected ";							
						}
						echo ">{$fab->getNombre()}</option>";
					}

					?>
				</select>

				<label>Existencias:</label>
				<input type="text" name="existencias" value="<?=$existencias?>"/>
				
				<label>Disponibilidad:</label>
				<input type="text" disabled value="<?=$disponibilidad?>"/>
				<input type="hidden" value="<?=$disponibilidad?>" name="disponibilidad"/>
				
				<label>Fecha:</label>
				<input type="text" disabled value="<?=$fecha?>"/>
				
				<label>Activo:</label>
				<input type="checkbox" name="activa"
				<?php
					if ($activa) {
						echo " CHECKED ";
					}
				?>
				
				/>

				
				<label>Descripcion:</label>
				<!--<input type="text" class="long" name="descripcion" value="<?=$descripcion?>"/> -->
				<textarea name="descripcion" rows="5"><?=$descripcion?></textarea>
				<input type="hidden" value="<?=$producto_id?>" name="id"/>

				<p class="centrado">
					<input type="submit" value="Editar" name="accion"/>
					<input type="submit" value="Cancelar" name="accion" />
				</p>
			</form>

				<h2 class="separacion">Galeria de imagenes</h2>
				
				<div id="Galeria">
					<?php

					$a = $galeria->getImagenById();
					$a_actual=0;
					$a_total = count($a);
					$size = 161; // Tamanño del Thumnail 161 : 4 por fila

					foreach ($a as $key=>$imagen){
						$a_actual += 1;
						echo "<div class=\"edit_image";
						if ($imagen->getPrincipal()) {
							echo " principal";
						}
						echo "\">";
						echo "<a class=\"fancybox\" rel=\"gallery1\" href=\"images/products/{$imagen->getImagen()}.jpeg\" ";
						echo "title=\"{$producto->getNombre()} - {$a_actual} de {$a_total}\" >";						
						echo "<img class=\"thumb\" src=\"getthumb.php?path=images/products/{$imagen->getImagen()}.jpeg&size={$size}\" alt=\"{$producto->getNombre()} - {$a_actual} de {$a_total}\"/></a>";
						echo "<a  class=\"borrar\" href=\"newProductos.php?id={$producto->getId()}&accion=Editar&EliminarImagen={$imagen->getId()}\" title=\"Eliminar Imagen: {$a_actual} de {$a_total}\"><img src=\"images/icon_delete.gif\"/></a>";
						if (!($imagen->getPrincipal())) {
							echo "<a  class=\"importante\" href=\"newProductos.php?id={$producto->getId()}&accion=Editar&Principal={$imagen->getId()}\" title=\"Establecer como imagen principal del producto\"><img src=\"images/important.gif\"/></a>";
						}
						echo "</div>";
					}

					?>

				<form class="separacion" action="<?=$_SERVER['SCRIPT_NAME'] ?>" method="post" enctype="multipart/form-data">
					<label>Añadir:</label>
					<input type="hidden" name="MAX_FILE_SIZE" value="<?=MAX_FILE_SIZE?>" />
					<input type="hidden" name="imagenes" value="<?=$a_total?>">
					<input type="file" name="imagen"/>
					<input type="hidden" value="<?=$producto_id?>" name="id" />
				

				<p class="centrado">
					<input type="submit" value="Guardar Imagen" name="accion"/>
				</p>
				
				</form>
		</div>
	<?php
	} else if ($post_accion=='Editar' && $producto) {
		$volver = $_SERVER['SCRIPT_NAME'] . "?id=" . $producto->getId() . "&accion=Editar";
	?>
		<h2>Editar Productos</h2>
		<p class="separacion centrado"> El producto <b><?=$producto->getNombre()?></b> ha sido editado. </p>
		<p class="separacion centrado"><a href="<?=$volver?>">Volver</a></p>
		<p class="centrado"><a href="newProductos.php">Añadir producto</a></p>
	<?php
	} else if ($post_accion=='Eliminar' && $producto) {
	?>
		<h2>Eliminar Productos</h2>
		<p class="separacion centrado"> El producto <b><?=$producto->getNombre()?></b> ha sido eliminado. </p>
		<p class="separacion centrado"><a href="newProductos.php">Añadir producto</a></p>

	<?php
	} else if ($get_accion=='Eliminar' && $producto) {
	?>
		<h2>Eliminar Productos</h2>

		<form action="<?=$_SERVER['SCRIPT_NAME'] ?>" method=post>

			<p class="separacion centrado">¿Estas seguro de que deseas eliminar el producto <b><?=$producto->getNombre()?></b>?</p>

			<input type="hidden" name="id" value="<?=$producto_id?>" />
			<p class="separacion centrado">
				<input type="submit" value="Eliminar" name="accion"/>
				<input type="submit" value="Cancelar" name="accion" />
			</p>
		</form>
	<?php
	}
	?>

	<div id="categorias" >
		<h2 class="separacion">Lista de productos por categorias</h2>
		<?php
		include 'listaProductos.php';
		?>
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

</body>