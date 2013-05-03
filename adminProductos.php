<?php
require_once 'configuracion.php';
require_once 'conectar_bd.php';
require_once 'classes/Controlador.php';
require_once 'classes/Registro.php';
require_once 'classes/Categorias.php';
require_once 'classes/Productos.php';
require_once 'classes/Fabricantes.php';
require_once 'classes/Imagenes.php'; // old

$PDO = new PDOConfig ();

define ("MAX_FILE_SIZE", "307200");

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

// Intentamos recuperar el producto
if ($regMem->getValor('id')) {
	$producto=$prods->getItemBD(array ('id'=>$regMem->getValor('id')))->getItemById($regMem->getValor('id'));
	if (!$producto) {
		$regError->setError('general', ' No existe ningún producto con esa <b>id</b>.');
	}

}

// Titulo por defecto de la página
$regMem->setValor('titulo', 'Añadir Producto');



switch ($regMem->getValor('accion')){
	case 'Cancelar':
		header("Location: {$_SERVER['SCRIPT_NAME']}");
		exit;
		break;

	case 'Añadir':
		$correcto = true;


		if (!$regMem->getValor('nombre')) {
			$regError->setError('nombre', 'El <b>nombre</b> del producto es obligatorio.');
			$correcto = false;
		}

		if (!$regMem->getValor('categoria_id')) {
			$regError->setError('categoria', 'La <b>categoria</b> del producto es obligatoria.');
			$correcto = false;
		}

		if ($correcto) {
			$valores = (array_intersect_key($regMem->getValor(), Producto::getListaPropiedades()));
			$producto = new Producto ($valores);
			$prods->addItemBD ($producto);
			$regFeedback->addFeedback("Se ha añadido el producto <b>{$regMem->getValor('nombre')}</b> con éxito.");

			// Eliminamos los valores de memoria para dejar limpio el formulario
			foreach ($valores as $clave=>$valor){
				$regMem->setValor($clave, null);
			}

		}
		break;

	case 'Editar':
		$regMem->setValor('titulo', 'Editar Producto');

		if ($regMem->getValor('metodo')=='POST' && $producto) {
			$valores = (array_intersect_key($regMem->getValor(), Producto::getListaPropiedades()));
			$producto = new Producto ($valores);			
			$prods->setItemBD($producto);
			$regFeedback->addFeedback("Se ha modificado el producto <b>{$regMem->getValor('nombre')}</b> con éxito.");

		}
		break;

	case 'Eliminar':
		$regMem->setValor('titulo', 'Eliminar Producto');
		
		if ($regMem->getValor('metodo')=='POST' && $producto){

			$prods->delItemBD($regMem->getValor('id'));			
			$regFeedback->addFeedback("Se ha eliminado el producto <b>{$producto->getPropiedad('nombre')}</b> con éxito.");
		}
		
		break;

	case 'Guardar Imagen':
		break;

}


// Cargamos todos los productos y las categorias
$prods->getItemBD ();
$cats->getItemBD();

/*
} else if (($get_accion=='Editar' || $post_accion=='Guardar Imagen') && $producto) {
	$nombre = $producto->getPropiedad('nombre');
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
*/

/* IMAGENES  IMAGENES  IMAGENES  IMAGENES  IMAGENES  IMAGENES  IMAGENES  IMAGENES  IMAGENES 
/* IMAGENES  IMAGENES  IMAGENES  IMAGENES  IMAGENES  IMAGENES  IMAGENES  IMAGENES  IMAGENES 
/* IMAGENES  IMAGENES  IMAGENES  IMAGENES  IMAGENES  IMAGENES  IMAGENES  IMAGENES  IMAGENES 
if ($post_accion == 'Guardar Imagen' && 

	$correcto = TRUE; // Mientras no encontremos ningún problema.

	if (!empty($_FILES['imagen']['name'])) {
		echo 'El archivo no se ha subido correctamente.';
		$correcto= FALSE;
	}

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
			$nombre_imagen = $producto->getPropiedad('nombre') . '_' . uniqid(); // a la imagen para que sea única, comprobamos si existe por si acaso
			// Sustituimos todos los carácteres no alfanuméricos
			$nombre_imagen = quitarEspacios($nombre_imagen);
		} while (file_exists($uploaddir . $nombre_imagen . '.jpeg'));

		// Guardamos la imagen
		imagejpeg($nueva_imagen, $uploaddir . $nombre_imagen . '.jpeg');

		// Si hay otras imagenes ya subida, no hace falta ponerla como principal.

		$principal = empty($_POST['imagenes']);

		$imagen = new Imagen($producto->getPropiedad('id'), null, $nombre_imagen, $principal);
		$galeria->addImagenBD($imagen);


	} 
}

function quitarEspacios($string) {
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		return preg_replace($old_pattern, $new_pattern , $string);
}



$galeria->getImagenBD ($producto_id);
*/


/* IMAGENES 
if (!empty($_GET['Principal'])) {
		$principal_id = $_GET['Principal'];
		// Recorremos todas las imagenes del producto y ponemos principal a FALSE excepto la 
		// que hemos cambiado
		$a = $galeria->getImagenById();
		foreach ($a as $key=>$imagen){
			
			if ($imagen->getPropiedad('id') == $principal_id){
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
*/
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
	// No hay ninguna acción seleccionada o la última acción ha sido añadir, mostramos formulario añadir.
	
	if (!$regMem->getValor('metodo') || $regMem->getValor('accion')=='Añadir') {
	?>
	<!-- FORMULARIO PARA AÑADIR PRODUCTOS -->

	<form action="<?=$_SERVER['SCRIPT_NAME'] ?>" method=post>
		<label>Nombre: </label>
		<input type="text" name="nombre" value="<?=$regMem->getValor('nombre')?>" />
		<label>Parent Cat: </label>
		<select name="categoria_id">
			<option value=""
			<?php
			 if (!$regMem->getValor('categoria_id')) {
			 	echo " selected ";
			 }
			 ?>
			 >&lt;Selecciona una categoría&gt;</option>
			?>
			<?php
			$a = $cats->getChildItemsById(0);
			
			foreach ($a as $cat) {
				echo "<optgroup label=\"{$cat->getPropiedad('nombre')}\">";
				$b = $cats->getChildItemsById($cat->getPropiedad('id'));
				foreach ($b as $subCat) {
					echo "<option value=\"{$subCat->getPropiedad('id')}\"";
					if ($subCat->getPropiedad('id')==$regMem->getValor('categoria_id')){
						echo " selected ";
					}
					echo ">{$subCat->getPropiedad('nombre')}</option>";
				}
				echo "</optgroup>";
			}
			?>
		</select>
		<label>Precio: </label>
		<input type="text" name="precio_venta" value="<?=$regMem->getValor('precio_venta')?>" />

		<label>Fabricante: </label>
		<select name="fabricante_id">

			<?php
			$fabs = new Fabricantes($controlador);
			$fabs->getItemBD();
			$a = $fabs->getItemById();
			
			echo "<option value=\"0\"";
			if (!$regMem->getValor('fabricante_id')) {
				echo " selected ";
			}
			echo ">OEM</option>";

			foreach ($a as $fab) {
				echo "<option value=\"{$fab->getPropiedad('id')}\"";
				if ($fab->getPropiedad('id') == $regMem->getValor('fabricante_id'))  {
					echo " selected ";
				}
				echo ">{$fab->getPropiedad('nombre')}</option>";
			}

			?>
		</select>
				
		<label>Existencias: </label>
		<input type="text" name="existencias" value="<?=$regMem->getValor('existencias')?>"/>

		<input type="hidden" name="activo" value="TRUE" />


		<label>Descripcion:</label>
		<textarea name="descripcion" rows="5"><?=$regMem->getValor('descripcion')?></textarea>
		<p class="centrado">
			<input type="submit" value="Añadir" name="accion"/>
		</p>
	</form>
</div>

	<?php 
	} else if ($regMem->getValor('accion')=='Editar' || $regMem->getValor('accion')== 'Guardar Imagen' || $regMem->getValor('accion')=='Principal') {


	?>
	<!-- FORMULARIO PARA EDITAR PRODUCTOS -->
	<div class="separacion" >
		<form action="<?=$_SERVER['SCRIPT_NAME'] ?>" method="post">
			<label>Nombre:</label>
			<input type="text" name="nombre" value="<?=$producto->getPropiedad('nombre')?>"/>
			<label>Categoria:</label>
			<select name="categoria_id">
				<?php
				$a = $cats->getChildItemsById(0);
					
				foreach ($a as $cat ) {
					echo "<optgroup label=\"{$cat->getPropiedad('nombre')}\">";
					$b = $cats->getChildItemsById($cat->getPropiedad('id'));
					foreach ($b as $catb) {
						echo "<option value=\"{$catb->getPropiedad('id')}\"";

						if ($catb->getPropiedad('id') == $producto->getPropiedad('id')) {
							echo " selected ";
						}

						echo ">{$catb->getPropiedad('nombre')}</option>";
					}
							
					echo "</optgroup>";
				}
				?>
				</select>
				<label>Precio:</label>
				<input type="text" name="precio_venta" value="<?=$producto->getPropiedad('precio_venta')?>"/>

				<label>Fabricante:</label>
				<select name="fabricante_id">
					<?php
					$fabs = new Fabricantes($controlador);
					$fabs->getItemBD();
					$a = $fabs->getItemById();

					echo "<option value=\"0\"";
					if ($regMem->getValor('fabricante_id')==0) {
						echo " selected ";
					}
					echo ">OEM</option>";


					foreach ($a as $fab) {
						echo "<option value=\"{$fab->getPropiedad('id')}\"";
						if ($fab->getPropiedad('id') == $producto->getPropiedad('fabricante_id')) {
							echo " selected ";							
						}
						echo ">{$fab->getPropiedad('nombre')}</option>";
					}

					?>
				</select>

				<label>Existencias:</label>
				<input type="text" name="existencias" value="<?=$producto->getPropiedad('existencias')?>"/>
				
				<label>Disponibilidad:</label>
				<input type="text" disabled value="<?=$producto->getPropiedad('disponibilidad')?>"/>
				<input type="hidden" value="<?=$producto->getPropiedad('disponibilidad')?>" name="disponibilidad"/>
				<input type="hidden" value="<?=$producto->getPropiedad('fecha')?>" name="fecha"/>
				<label>Fecha:</label>
				<input type="text" disabled value="<?=$producto->getPropiedad('fecha')?>"/>
				
				<label>Activo:</label>
				<input type="checkbox" name="activo"
				<?php
					if ($producto->getPropiedad('activo')) {
						echo " CHECKED ";
					}
				?>
				/>
				
				<label>Descripcion:</label>
				<textarea name="descripcion" rows="5"><?=$producto->getPropiedad('descripcion')?></textarea>
				<input type="hidden" value="<?=$producto->getPropiedad('id')?>" name="id"/>

				<p class="centrado">
					<input type="submit" value="Editar" name="accion"/>
					<input type="submit" value="Cancelar" name="accion" />
				</p>
			</form>

<?php /*
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
						echo "title=\"{$producto->getPropiedad('nombre')} - {$a_actual} de {$a_total}\" >";						
						echo "<img class=\"thumb\" src=\"getthumb.php?path=images/products/{$imagen->getImagen()}.jpeg&size={$size}\" alt=\"{$producto->getPropiedad('nombre')} - {$a_actual} de {$a_total}\"/></a>";
						echo "<a  class=\"borrar\" href=\"{$_SERVER['SCRIPT_NAME']}?id={$producto->getPropiedad('id')}&accion=Editar&EliminarImagen={$imagen->getPropiedad('id')}\" title=\"Eliminar Imagen: {$a_actual} de {$a_total}\"><img src=\"images/icon_delete.gif\"/></a>";
						if (!($imagen->getPrincipal())) {
							echo "<a  class=\"importante\" href=\"{$_SERVER['SCRIPT_NAME']}?id={$producto->getPropiedad('id')}&accion=Editar&Principal={$imagen->getPropiedad('id')}\" title=\"Establecer como imagen principal del producto\"><img src=\"images/important.gif\"/></a>";
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
*/?>

	<?php
	}  else if ($regMem->getValor('accion')=='Eliminar') {
		if ($regMem->getValor('metodo')=='POST') {
		?>
			<p class="separacion centrado"><a href="<?=$_SERVER['SCRIPT_NAME']?>">Añadir producto</a></p>

		<?php		
		} else if ($producto) {
		?>
		<!-- FORMULARIO PARA CONFIRMAR ELIMINACIÓN  -->
		<form action="<?=$_SERVER['SCRIPT_NAME'] ?>" method=post>

			<p class="separacion centrado">¿Estas seguro de que deseas eliminar el producto <b><?=$producto->getPropiedad('nombre')?></b>?</p>

			<input type="hidden" name="id" value="<?=$producto->getPropiedad('id')?>" />
			<p class="separacion centrado">
				<input type="submit" value="Eliminar" name="accion"/>
				<input type="submit" value="Cancelar" name="accion" />
			</p>
		</form>
		<?php
		}
	}
	?>

	<div id="categorias" >
		<h2 class="separacion">Lista de productos por categorias</h2>
		<?php
		// Dibujar arbol
		
		$a = $cats->getChildItemsById(0);
		
		$columnas = 3;
		$contador = 0;
		$contador_max = ceil(($cats->getTotal()+$prods->getTotal())/$columnas);

		foreach ( $a as $cat ) {			

		// Principal: categoria null:
			if ($cat->getPropiedad('parent_id') == null) {
				if ($contador==0) {
					echo "<div class=\"categoria\">";
				}
				$contador+=1;

				echo "<ul><li>{$cat->getPropiedad('nombre')}";
				echo "<ul>";


				// 	Buscamos sus hijos
				$children = $cats->getChildItemsById($cat->getPropiedad('id'));

				foreach ($children as $child) {
					if ($contador==0) {
						echo "<div class=\"categoria\"><ul><li><ul>";
					}
					$contador+=1;
					echo "<li>{$child->getPropiedad('nombre')}";
					// Añadimos los productos

					echo "<ul>";
					$productos = $prods->getItemByCategoria($child->getPropiedad('id'));
					foreach ($productos as $producto) {
						if ($contador==0) {
							echo "<div class=\"categoria\"><ul><li><ul><li><ul>";
						}
						$contador+=1;
												
						echo "<li><a href=\"{$_SERVER['SCRIPT_NAME']}?id={$producto->getPropiedad('id')}&accion=Editar\" title=\"Editar: {$producto->getPropiedad('nombre')}\">{$producto->getPropiedad('nombre')}</a>";
						// Añadimos el icono de eliminar
						echo "<a href=\"{$_SERVER['SCRIPT_NAME']}?id={$producto->getPropiedad('id')}&accion=Eliminar\" title=\"Eliminar: {$producto->getPropiedad('nombre')}\"><img src=\"images/icon_delete.gif\"/></a>";
						echo "</li>";

					if ($contador>=$contador_max) {
						echo "</ul></li></ul></li></ul></div>";
						$contador=0;
					}

					}
					echo "</ul>";

					echo "</li>";

					if ($contador>=$contador_max) {
						echo "</ul></li></ul></div>";
						$contador=0;
					}

				}
				echo "</ul></li></ul>";

				if ($contador>=$contador_max) {
					echo "</div>";
					$contador=0;
				}
				
			}
		}


/*
		foreach ( $a as $cat ) {
			// Principal: categoria null:
			if ($cat->getParentId() == null) {

				echo "<div class=\"categoria\"><ul><li>{$cat->getPropiedad('nombre')}";
				echo "<ul>";


				// 		Buscamos sus hijos
				$children = $cats->getChildCategoriasById($cat->getPropiedad('id'));

				foreach ($children as $child) {
					echo "<li>{$child->getPropiedad('nombre')}";
					// Añadimos los productos
					echo "<ul>";

					$productos = $prods->getItemByCategoria($child->getPropiedad('id'));
					foreach ($productos as $producto) {
						//$nombre = htmlentities($producto->getPropiedad('nombre'), ENT_QUOTES, 'ISO-8859-1');
						
						echo "<li><a href=\"{$_SERVER['SCRIPT_NAME']}?id={$producto->getPropiedad('id')}&accion=Editar\" title=\"Editar: {$producto->getPropiedad('nombre')}\">{$producto->getPropiedad('nombre')}</a>";
						// Añadimos el icono de eliminar
						echo "<a href=\"{$_SERVER['SCRIPT_NAME']}?id={$producto->getPropiedad('id')}&accion=Eliminar\" title=\"Eliminar: {$producto->getPropiedad('nombre')}\"><img src=\"images/icon_delete.gif\"/></a>";
						echo "</li>";
					}
					echo "</ul>";

					echo "</li>";
				}
				echo "</ul></li></ul></div>";
			}
		}
*/
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