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


//max per page
define ('PERPAGE', 5);
//name of first parameter in query string

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

// El controlador de registros almacena un array con acceso a los registros que le añadamos, este
// controlador se pasa a las colecciones al crearlo para que puedan mandar mensajes a la aplicación
// (Sin usar por el momento)

$controlador = new Controlador();
$controlador -> setRegistro ('feedback', $regFeedback);
$controlador -> setRegistro ('errores', $regError);
$controlador -> setPDO($PDO);

$cats = new Categorias($controlador);



//Obtenemos el parametro
if ($regMem->getValor('novedades')) {
	$parametro="&amp;novedades";
	$regMem->setValor('subtitulo', 'Novedades');
} else if ($regMem->getValor('outlet')){	
	$parametro="&amp;outlet";
	$regMem->setValor('subtitulo', 'Outlet');
} else if ($regMem->getValor('ofertas')){
	$parametro="&amp;ofertas";
	$regMem->setValor('subtitulo', 'Ofertas');
} else if ($regMem->getValor('cat')){
	$parametro="&amp;cat=" . $regMem->getValor('cat');
	$cats->getItemBD(array('id'=>$regMem->getValor('cat')));
	//$a = $cats->getItemBD(array('id'=>$regMem->getValor('cat')))->getItemById($regMem->getValor('cat'))->getPropiedad('nombre');
	$a = $cats->getItemBD()->getItemById($regMem->getValor('cat'));
	$regMem->setValor('subtitulo', $a->getPropiedad('nombre'));
	$regMem->setValor('cat_parent_id', $a->getPropiedad('parent_id'));

} else {
	$parametro ='';
}


$regMem->setValor('titulo', 'Ver Productos -' . $regMem->getValor('subtitulo'));


function quitarEspacios($string){
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		return preg_replace($old_pattern, $new_pattern , $string);
}

// Esta plantilla solo se usa para la página de bienvenida.






include 'cabecera.php';
?>

<div id="main">

	<?php
		include 'top-menu.php';
		include 'main-menu.php';
		include 'sidebar-categorias.php';
		?>


		<div id="main-content">




			<!-- NOVEDADES -->
			<h2 class="separacion"><?=$regMem->getValor('subtitulo')?></h2>

			<?php
			$prods = new Productos($controlador);

			//$prods->getItemBD(array('novedades' => MAX_NOVEDADES));
			$prods->getItemBD(array('recordoffset' => $recordoffset));
				
			$a = $prods->getItemById();
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
						<a href="#"><img
							src="<?=$url?>"
							title="<?=$producto->getPropiedad('nombre')?>" alt="<?=$producto->getPropiedad('nombre')?>" /></a>
						<p><?=$producto->getPropiedad('precio_venta')?>&euro;</p>
						<p>
							<a href="#"><?=$producto->getPropiedad('nombre')?></a>
						</p>
						<div></div>
						<p class="<?=quitarEspacios($producto->getPropiedad('disponibilidad'))?>"><?=$producto->getPropiedad('disponibilidad')?></p>
					</div>				
			<?php
			}

			?>

			<div id="navigator" class="separacion">
			<?php
			$pagename = basename($_SERVER['PHP_SELF']);
			// find total number of records
			$totalrecords = $prods->getItemBD()->getTotal();

			$numpages = ceil($totalrecords/PRODUCTOS_PAGINA);
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
		<p>Esto es una página de prueba, todos los contenidos son ficticios.
			Usala bajo tu propia responsabilidad.</p>
		<p>Javier García Rodríguez.</p>
		<p>Última actualización 25/04/2013</p>
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