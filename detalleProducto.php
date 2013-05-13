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

// Intentamos recuperar el producto
if ($regMem->getValor('id')) {
	$producto=$prods->getItemBD(array ('id'=>$regMem->getValor('id')))->getItemById($regMem->getValor('id'));
	if (!$producto) {
		$regError->setError('general', ' No existe ningún producto con esa <b>id</b>.');
		$regMem->setValor('titulo', 'Error: El producto no existe');
	} else {
		// Titulo por defecto de la página nombre del producto
		$regMem->setValor('titulo', $producto->getPropiedad('nombre'));
		// Cargamos la lista de categorias para el sidebar, seleccionamos la categoria padre
		$cats->getItemBD();
		$a = $cats->getItemById($producto->getPropiedad('categoria_id'));
		//$regMem->setValor('cat',$a->getPropiedad('parent_id'));
		$regMem->setValor('cat',$a->getPropiedad('id'));
		$regMem->setValor('cat_parent_id',$a->getPropiedad('parent_id'));
	}
}



switch ($regMem->getValor('accion')){

	case 'Editar':
	
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
	include 'sidebar-categorias.php';
	?>

	<div id="main-content">
		<h2>Detalle del producto</h2>
		<h3><?=$producto->getPropiedad('nombre')?></h3>
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
		</div>			<!-- FORMULARIO PARA EDITAR PRODUCTOS -->

		<div id="imagenes">

			<?php
			$a = $galeria->getItemById();
			$a_actual=0;
			$a_total = count($a);

				// Imagen Principal
			$galeria = new Imagenes($controlador);
			$imagen = $galeria->getItemBD(array('principal' => TRUE, 'producto_id' => $producto->getPropiedad('id')))->getItemByProductoFirst($producto->getPropiedad('id'));

			if ($imagen) {
				$b= $imagen->getPropiedad('imagen');
			} else {
				$b= 'default';
			}

			$size=294;

			echo "<a class=\"fancybox\" rel=\"gallery1\" href=\"images/products/{$b}.jpeg\" ";
			echo "title=\"{$producto->getPropiedad('nombre')} - {$a_actual} de {$a_total}\" >";
			echo "<img class=\"thumb\" src=\"getthumb.php?path=images/products/{$b}.jpeg&size={$size}\" alt=\"{$producto->getPropiedad('nombre')} - {$a_actual} de {$a_total}\"/></a>";



		$size = 94; // Tamaño del Thumbnail 

		foreach ($a as $key=>$imagen){
			$a_actual += 1;
			if ($imagen->getPropiedad('principal')) {
				continue;
			}

			echo "<a class=\"fancybox\" rel=\"gallery1\" href=\"images/products/{$imagen->getPropiedad('imagen')}.jpeg\" ";
			echo "title=\"{$producto->getPropiedad('nombre')} - {$a_actual} de {$a_total}\" >";						
			echo "<img class=\"thumb\" src=\"getthumb.php?path=images/products/{$imagen->getPropiedad('imagen')}.jpeg&size={$size}\" alt=\"{$producto->getPropiedad('nombre')} - {$a_actual} de {$a_total}\"/></a>";
			
		}
		?>
	</div>
	<div id="detalle">
		<?php
		// Comprobamos si existe una oferta para este producto
		?>
		<ul>
			<?php
			$ofertas->getItemBD(array('producto_id' =>$producto->getPropiedad('id')));
			$c=$ofertas->getItemByProducto($producto->getPropiedad('id')); 
			if ($c && $c->getPropiedad('activa')==1){
				echo '<li>¡Este producto está en oferta! </li>';
				echo '<li class="descuento">';
				$oferta = $ofertas->getItemByProducto($producto->getPropiedad('id'));
				$descuento = round(100-(1/$oferta->getPropiedad('precio_anterior')*$oferta->getPropiedad('precio_oferta'))*100);
				echo "-$descuento%";
				echo '</li>';
			}

			$fabs = new Fabricantes($controlador);
			$a = $fabs->getItemBD(array('id'=>$producto->getPropiedad('fabricante_id')));
			
			// Algunas veces no se guarda el valor del fabricante al crear el producto.
			// para evitar errores si el valor es falso lo ponemos como OEM
			if (!$producto->getPropiedad('fabricante_id')) {
				$a = $a->getItemById(1);
			} else {
				$a = $a->getItemById($producto->getPropiedad('fabricante_id'));
			}
			?>

			<li>Fabricante: <b><?=$a->getPropiedad('nombre')?></b></li>
			<li>Disponibilidad: <b><?=$producto->getPropiedad('disponibilidad')?></b></li>
		</ul>
		<?php
		// Reemplazamos los saltos de línea de la descripción por <br>
		$a = $producto->getPropiedad('descripcion');
		$a = preg_replace("/[\n]/i", '<br>',$a)
		?>
		<p class="precio"><?=$producto->getPropiedad('precio_venta')?>&euro;</p>
		<?php
			if ($producto->getPropiedad('existencias')<1) {
				echo "<p class=\"comprar_disabled\">comprar</p>";
			} else {
				echo "<p class=\"comprar\"><a href=\"#\">comprar</a></p>";
			}
		?>
		
		
		<p class="separacion">Descripción</b>:</p>
		<p class="descripcion"><b><?=$a?></b></p>
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
