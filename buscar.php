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

// El controlador de registros almacena un array con acceso a los registros que le a�adamos, este
// controlador se pasa a las colecciones al crearlo para que puedan mandar mensajes a la aplicaci�n
// (Sin usar por el momento)

$controlador = new Controlador();
$controlador -> setRegistro ('feedback', $regFeedback);
$controlador -> setRegistro ('errores', $regError);
$controlador -> setPDO($PDO);

$cats = new Categorias($controlador);
$prods = new Productos($controlador);
$ofertas = new Ofertas($controlador);

$opciones = array('recordoffset' => $recordoffset);


// Obtenemos los parametros de consulta

$opciones += array('buscar'=>$regMem->getValor('buscar'));
$parametro ='&amp;buscar=' . $regMem->getValor('buscar');


if (!$regMem->getValor('buscar')) {
	$regError->setError('general', 'Debes introducir un valor de b�squeda.');
	$totalrecords =0;
} else {
	// Realizamos la busqueda
	$prods->getItemBD($opciones);
	$totalrecords = $prods->getTotalBD();
	$regMem->setValor('titulo', 'Buscar - Error');
}

$regMem->setValor('titulo', 'Buscar -' . $regMem->getValor('buscar'));




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
					<div class="box2">
						<p>
							<a href="detalleProducto.php?id=<?=$producto->getPropiedad('id')?>"><?=$producto->getPropiedad('nombre')?></a>
						</p>
						<a href="detalleProducto.php?id=<?=$producto->getPropiedad('id')?>"><img
							src="<?=$url?>"
							title="<?=$producto->getPropiedad('nombre')?>" alt="<?=$producto->getPropiedad('nombre')?>" /></a>


						<p class="separacion descripcion"><b>Descripcion: </b><?=substr($producto->getPropiedad('descripcion'),0,DESCRIPCION_CORTA)?>...</p>
				
						<p class="separacion precio"><?=$producto->getPropiedad('precio_venta')?>&euro;</p>

						<p class="separacion <?=quitarEspacios($producto->getPropiedad('disponibilidad'))?>"><?=$producto->getPropiedad('disponibilidad')?></p>
		
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
			// No hay productos en la p�gina o se ha llegado modificando la URL.
			if (!$a) {
				echo "<h3 class=\"separacion\">No se ha encontrado ning�n producto que coincida con la cadena de b�squeda.</h3>";
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


	<div id="footer">
		<p>Esto es una p�gina de prueba, todos los contenidos son ficticios.
			Usala bajo tu propia responsabilidad.</p>
		<p>Javier Garc�a Rodr�guez.</p>
		<p>�ltima actualizaci�n 25/04/2013</p>
	</div>


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