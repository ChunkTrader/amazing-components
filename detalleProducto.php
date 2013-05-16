<?php
require_once 'inicializacion.php';
require_once 'classes/Fabricantes.php';
require_once 'classes/Ofertas.php'; 

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

if ($producto) {
	switch ($regMem->getValor('accion')){
		case 'Comprar':
			// Comprobamos si ya existe un producto igual en la sesion
			// Cada linea de pedido es un array: id, cantidad, preciounitario

			// El carrito es un array de lineas de pedidos
			$a = $regSistema->getValor('carrito');

			$nuevo = TRUE; // Damos por supuesto que es un producto nuevo
			// $a contiene un array de lineas o está vacio, si esta vacio lo convertimos en un array vacio

			if (!$a) {
				$a=array();
			}

			// Si tiene líneas las recorremos, si encontramos alguna coincidencia 
			// aumentamos en 1 la cantidad de esa linea
			foreach ($a as $key => $linea) {
				if ($linea['id']==$producto->getPropiedad('id')) {
					
					$nuevo=FALSE; // Encontrado, no es nuevo
					// Comprobamos si hay existencias
					if ($producto->getPropiedad('existencias')<$a[$key]['cantidad']+1) {
						$regError->setError('General', "En estos momentos no disponemos de suficientes existencias. Solo puede comprar {$producto->getPropiedad('existencias')} unidades.");
					} else {
						$a[$key]['cantidad']+=1;
						$regFeedback->addFeedback("Añadido otro {$producto->getPropiedad('nombre')} al carrito ({$a[$key]['cantidad']})");
						
					}
				}
			}

			if ($nuevo) {
					// Comprobamos si hay existencias
					if ($producto->getPropiedad('existencias')<1) {
						// No deberia aparecer el link para comprar, pero podria darse el caso que se agotase
						// Mientras tienes la página abierta.
						$regError->setError('General', 'El producto está agotado. Disculpe las molestias.');
					} else {

					// Añadimos una nueva línea de pedido
					$a[] = array(
							'id'=>$producto->getPropiedad('id'),
							'cantidad'=> 1,
							'precio'=>$producto->getPropiedad('precio_venta')
						);
					$regFeedback->addFeedback("Añadido {$producto->getPropiedad('nombre')} al carrito");
				}
			}

			// Guardamos el carrito en la sesion
			$regSistema->setValor('carrito', $a);

			// TEST Mostramos todo el carrito
			//foreach ($a as $linea) {
			//	echo "{$linea['id']} - cantidad: {$linea['cantidad']} precio: {$linea['precio']}<br>";
			//}

	}
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
if ($producto) {
?>

		<h3><?=$producto->getPropiedad('nombre')?></h3>

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
				echo "<p class=\"comprar\"><a href=\"{$_SERVER['SCRIPT_NAME']}?id={$producto->getPropiedad('id')}&amp;accion=Comprar\">comprar</a></p>";
			}
		?>
		
		
		<p class="separacion">Descripción</b>:</p>
		<p class="descripcion"><b><?=$a?></b></p>
	</div>
	<?php
	}
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
<?php
include 'pie.php';
?>

</body>
</html>
