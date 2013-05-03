<?php
require_once 'configuracion.php';
require_once 'conectar_bd.php';

require_once 'classes/Controlador.php';
require_once 'classes/Registro.php';

require_once 'classes/Categorias.php';
require_once 'classes/Productos.php';
require_once 'classes/Fabricantes.php';
require_once 'classes/Imagenes.php'; 

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
		$correcto = TRUE;

		if (!$regMem->getValor('nombre')) {
			$regError->setError('nombre', 'El <b>nombre</b> del producto es obligatorio.');
			$correcto = FALSE;
		}

		if (!$regMem->getValor('categoria_id')) {
			$regError->setError('categoria', 'La <b>categoria</b> del producto es obligatoria.');
			$correcto = FALSE;
		}

		if ($correcto) {
			$valores = (array_intersect_key($regMem->getValor(), Producto::getListaPropiedades()));
			$producto = new Producto ($valores);
			$prods->addItemBD ($producto);
			$regFeedback->addFeedback("Se ha añadido el producto <b>{$regMem->getValor('nombre')}</b> con éxito.");

			// Eliminamos los valores de memoria para dejar limpio el formulario
			foreach ($valores as $clave=>$valor){
				$regMem->setValor($clave, NULL);
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

		if ($regMem->getValor('principal')) {
			if ($producto) {
				$galeria->getItemBD( array ('producto_id' => $producto->getPropiedad('id')));

				$a = $galeria->getItemByProducto($producto->getPropiedad('id'));
				foreach ($a as $key=>$item) {
					if ($item->getPropiedad('id') == $regMem->getValor('principal')){
						$item->setPropiedad('principal', TRUE);
						$regFeedback->addFeedback('Imagen establecida como principal.');
					} else {
						$item->setPropiedad('principal', FALSE);
					}
					$galeria->setItemBD($item);
				}			
			}
		}

		if ($regMem->getValor('eliminar_imagen')) {
			$imagen = $galeria->getItemBD(array('id'=>$regMem->getValor('eliminar_imagen')))->getItemById($regMem->getValor('eliminar_imagen'));

			if ($imagen) {
				$galeria->delItemBD($regMem->getValor('eliminar_imagen'));				
				$regFeedback->addFeedback('Se ha eliminado la imagen.');
				
				if ($imagen->getPropiedad('principal')) {
					$a=$galeria->getItemBD( array ('producto_id' => $producto->getPropiedad('id')))->getItemById();
					if ($a) {
						$a[0]->setPropiedad('principal', TRUE);
						$galeria->setItemBD($a[0]);
						$regFeedback->addFeedback('Se ha cambiado la imagen principal del producto.');
					}		
				}
			} else {
				$regError->setError('imagen', 'No existe ninguna imagen con esa <b>id</b>.');
			}
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
		$correcto = TRUE;

		if (!$producto) {
			$correcto = FALSE;
		}

		if (!($_FILES['imagen']['name'])) {
			$regError->setError('archivo', 'El archivo no se ha subido correctamente.');
			$correcto = FALSE;
		}

		if ($_FILES['imagen']['size']>MAX_FILE_SIZE) {
			$regError->setError('archivo', 'El tamaño máximo para el archivo es '. MAX_FILE_SIZE . ' bytes.');
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
		if (empty($nueva_imagen)) {
			$regError->setError('archivo', 'La imagen no es válida.');
			$correcto = FALSE;
		} 

		if ($correcto) {
			// Generamos un nombre único para la imagen y la guardamos como jpeg.
			do {
				$nombre_imagen = $producto->getPropiedad('nombre') . '_' . uniqid();
				$nombre_imagen = Imagen::encode($nombre_imagen);
			
			} while (file_exists(UPLOADDIR . $nombre_imagen . '.jpeg'));

			// Guardamos la imagen
			imagejpeg($nueva_imagen, UPLOADDIR . $nombre_imagen . '.jpeg');

			$a=$galeria->getItemBD( array (
				'producto_id' => $producto->getPropiedad('id'),
				'principal' => TRUE
			))->getItemById();

	
			$valores = array (
				'imagen' => $nombre_imagen,
				'producto_id' => $producto->getPropiedad('id')
			);

			if (!$a) {
				$valores['principal'] = TRUE;
				$regFeedback->addFeedback('Imagen establecida como principal.');
			}

			$imagen = new Imagen ($valores);
			$galeria->addItemBD($imagen);
			$regFeedback->addFeedback('Se ha añadido la imagen con éxito.');

		}
	break;

	}

// Cargamos todos los productos, las categorias
$prods->getItemBD();
$cats->getItemBD();

if (isset($producto)) {
	$galeria->getItemBD( array ('producto_id' => $producto->getPropiedad('id')));
}

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
	} else if ($regMem->getValor('accion')=='Editar' || $regMem->getValor('accion')== 'Guardar Imagen' || $regMem->getValor('accion')=='principal') {

	?>
	<div class="separacion" >

	<!-- FORMULARIO PARA EDITAR PRODUCTOS -->
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
	</div>

	<h2 class="separacion">Galeria de imagenes</h2>

	<div id="Galeria">
		<?php
		$a = $galeria->getItemById();
		$a_actual=0;
		$a_total = count($a);
		$size = 161; // Tamanño del Thumnail 161 : 4 por fila

		foreach ($a as $key=>$imagen){
			$a_actual += 1;
				echo "<div class=\"edit_image";
			if ($imagen->getPropiedad('principal')) {
				echo " principal";
			}
			echo "\">";
			echo "<a class=\"fancybox\" rel=\"gallery1\" href=\"images/products/{$imagen->getPropiedad('imagen')}.jpeg\" ";
			echo "title=\"{$producto->getPropiedad('nombre')} - {$a_actual} de {$a_total}\" >";						
			echo "<img class=\"thumb\" src=\"getthumb.php?path=images/products/{$imagen->getPropiedad('imagen')}.jpeg&size={$size}\" alt=\"{$producto->getPropiedad('nombre')} - {$a_actual} de {$a_total}\"/></a>";
			echo "<a  class=\"borrar\" href=\"{$_SERVER['SCRIPT_NAME']}?id={$producto->getPropiedad('id')}&accion=Editar&eliminar_imagen={$imagen->getPropiedad('id')}\" title=\"Eliminar Imagen: {$a_actual} de {$a_total}\"><img src=\"images/icon_delete.gif\"/></a>";
			if (!$imagen->getPropiedad('principal')) {
				echo "<a  class=\"importante\" href=\"{$_SERVER['SCRIPT_NAME']}?id={$producto->getPropiedad('id')}&accion=Editar&principal={$imagen->getPropiedad('id')}\" title=\"Establecer como imagen principal del producto\"><img src=\"images/important.gif\"/></a>";
			}
			echo "</div>";
		}
		?>

		<form class="separacion" action="<?=$_SERVER['SCRIPT_NAME'] ?>" method="post" enctype="multipart/form-data">
			<label>Añadir:</label>
			<input type="hidden" name="MAX_FILE_SIZE" value="<?=MAX_FILE_SIZE?>" />
			<input type="file" name="imagen"/>
			<input type="hidden" value="<?=$producto->getPropiedad('id')?>" name="id" />
			<p class="centrado">
				<input type="submit" value="Guardar Imagen" name="accion"/>
			</p>
		</form>
	</div>

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
		<h2>Lista de productos por categorias</h2>
		<?php
		
		$a = $cats->getChildItemsById(0);		
		$columnas = 3;
		$contador = 0;
		$contador_max = ceil(($cats->getTotal()+$prods->getTotal())/$columnas);

		foreach ( $a as $cat ) {			

		// principal: categoria NULL:
			if ($cat->getPropiedad('parent_id') == NULL) {
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
		?>
		</div>
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
