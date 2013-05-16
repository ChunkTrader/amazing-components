<?php
require_once 'configuracion.php';
require_once 'conectar_bd.php';

require_once 'classes/Controlador.php';
require_once 'classes/Registro.php';
require_once 'classes/PageNavigator.php';

require_once 'classes/Categorias.php';
require_once 'classes/Productos.php';
require_once 'classes/Fabricantes.php';
require_once 'classes/Imagenes.php'; 
require_once 'classes/Ofertas.php';



/*get query string - name should be same as first parameter name passed to the page navigator class*/
$offset = @$_GET['offset'];

//check variable
if (!isset($offset)){
	$recordoffset = 0;
} else {
	// then calculate offset
	$recordoffset = $offset * PRODUCTOS_PAGINA;
}


$PDO = new PDOConfig ();

// Incializamos los registros
$regMem = RegistroMemoria::instancia();
$regError = RegistroErrores::instancia();
$regFeedback = RegistroFeedback::instancia();
$regSistema = RegistroSistema::instancia();


// El controlador de registros almacena un array con acceso a los registros que le añadamos, este
// controlador se pasa a las colecciones al crearlo para que puedan mandar mensajes a la aplicación
// (Sin usar por el momento)

$controlador = new Controlador();
$controlador -> setRegistro ('feedback', $regFeedback);
$controlador -> setRegistro ('errores', $regError);
$controlador -> setPDO($PDO);

$cats = new Categorias($controlador);
$prods = new Productos($controlador);
$ofertas = new Ofertas($controlador);

$opciones = array('recordoffset' => $recordoffset);

//Obtenemos el parametro


if ($regMem->getValor('novedades')) {
	$parametro="&amp;novedades=-1";
	$regMem->setValor('subtitulo', 'Novedades');
	$opciones += array('novedades'=>-1);

} else if ($regMem->getValor('outlet')){
	$parametro="&amp;outlet=-1";
	$regMem->setValor('subtitulo', 'Outlet');
	$opciones += array('outlet'=>-1);

} else if ($regMem->getValor('ofertas')){
	$parametro="&amp;ofertas=-1";
	$regMem->setValor('subtitulo', 'Ofertas');
	$opciones += array('ofertas'=>-1);

} else if ($regMem->getValor('cat')){
	$parametro="&amp;cat=" . $regMem->getValor('cat');
	$cats->getItemBD(array('id'=>$regMem->getValor('cat')));
	$a = $cats->getItemBD()->getItemById($regMem->getValor('cat'));
	$regMem->setValor('subtitulo', $a->getPropiedad('nombre'));
	$regMem->setValor('cat_parent_id', $a->getPropiedad('parent_id'));
	if ($regMem->getValor('cat_parent_id')==0) {
		// No tiene parent, mostramos todos los productos que pertenecen
		// a sus categorias hijas.
		$opciones +=  array('parent_id'=>$regMem->getValor('cat'));

	} else {
		// Mostramos todos los productos de la subcategoria
		$opciones +=  array('cat_id'=>$regMem->getValor('cat'));

	}
} else {
	$parametro ='';
}


// Cargamos los productos segun la opción: novedades, outlet, ofertas, cat

$prods->getItemBD($opciones);
$totalrecords = $prods->getTotalBD();
$regMem->setValor('titulo', 'Ver Productos -' . $regMem->getValor('subtitulo'));




function quitarEspacios($string){
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		return preg_replace($old_pattern, $new_pattern , $string);
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
			<h2><?=$regMem->getValor('subtitulo')?></h2>

			<?php

			$a = $prods->getItemById();
			$ofertas->getItemBD();

			$galeria = new Imagenes($controlador);
			$galeria->getItemBD(array('principal' => TRUE));
						
			foreach ($a as $producto) {

				$imagen=$galeria->getItemByProductoFirst($producto->getPropiedad('id'));
				if ($imagen) {
					$b = $imagen->getPropiedad('imagen');

				} else {
					$b = 'default';
				}
				
				$size = MAIN_THUMB_SIZE;

				$url = "getthumb.php?path=images/products/{$b}.jpeg&size={$size}";

				?>
					<div class="box">
						<a href="detalleProducto.php?id=<?=$producto->getPropiedad('id')?>"><img
							src="<?=$url?>"
							title="<?=$producto->getPropiedad('nombre')?>" alt="<?=$producto->getPropiedad('nombre')?>" /></a>
						<p><?=$producto->getPropiedad('precio_venta')?>&euro;</p>
						<p>
							<a href="detalleProducto.php?id=<?=$producto->getPropiedad('id')?>"><?=$producto->getPropiedad('nombre')?></a>
						</p>
						<div></div>
						<p class="<?=quitarEspacios($producto->getPropiedad('disponibilidad'))?>"><?=$producto->getPropiedad('disponibilidad')?></p>
					<?php
						// Comprobamos si existe una oferta para este producto
						$c =$ofertas->getItemByProducto($producto->getPropiedad('id')); 
						if ($c && $c->getPropiedad('activa')==1){
							echo '<p class="descuento">';
							$oferta = $ofertas->getItemByProducto($producto->getPropiedad('id'));
							$descuento = round(100-(1/$oferta->getPropiedad('precio_anterior')*$oferta->getPropiedad('precio_oferta'))*100);
							echo "-$descuento%";
							echo '</p>';
						}
					?>


					</div>				
			<?php
			}
			// No hay productos en la pàgina o se ha llegado modificando la URL.
			if (!$a) {
				echo "<h3 class=\"separacion\">No hay productos en esta categoría.</h3>";
			}

			?>

			<div id="navigator" class="separacion">
			<?php
			$pagename = basename($_SERVER['PHP_SELF']);
			// find total number of records

			$numpages = ceil($totalrecords/PRODUCTOS_PAGINA);
			//echo "numpages: $numpages -- totalrecords: $totalrecords -- PRODUCTOS_PAGINA " . PRODUCTOS_PAGINA;
			// Create if needed
			$otherparameters="";
			if ($numpages>1) {
				// create navigator
				$nav = new PageNavigator($pagename, $totalrecords, PRODUCTOS_PAGINA, $recordoffset, 10, $parametro);
				echo $nav->getNavigator();
			}


			?>

			</div>
		</div>
	</div>



<?php
include 'pie.php';
?>

	<script>
		// Can also be used with $(document).ready()
		$(window).load(function() {
			$('.flexslider').flexslider({
				animation: "slide",
				controlNav : false,
			});
		});
	</script>

</body>
</html>