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
// (Sin usar por el momento)

$controlador = new Controlador();
$controlador -> setRegistro ('feedback', $regFeedback);
$controlador -> setRegistro ('errores', $regError);
$controlador -> setPDO($PDO);

$cats = new Categorias($controlador);

function quitarEspacios($string){
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		return preg_replace($old_pattern, $new_pattern , $string);
}

// Esta plantilla solo se usa para la página de bienvenida.
$regMem->setValor('titulo', 'Bienvenido');

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title><?=EMPRESA . ' - ' . $regMem->getValor('titulo')?></title>
<link rel="shortcut icon" type="image/x-icon"
	href="http://localhost/practicas/amazing-components/favicon.ico" />

<script type="text/javascript" src="js/jquery-2.0.0.min.js"></script>
<script type="text/javascript" src="js/jquery.flexslider-min.js"></script>

<link href="css/main.css" rel="stylesheet" type="text/css" title="main" />
<link href="css/flexslider.css" rel="stylesheet" type="text/css" />

</head>
<body>

	<div id="header">
		<div>
			<a href="#"><img src="images/logo1.png"
				alt="Logo amazing-components.com" /></a>
		</div>
		<div>
			<a href="#"><img src="images/banners/banner.jpg"
				title="Banner principal" alt="banner principal" /></a>
		</div>

	</div>
	<div id="main">
		<div id="top-menu">
			<ul>
				<li><a href="#">Home</a></li>
				<li><a href="#">Categoria 1</a></li>
				<li><a href="#">Categoria 2</a></li>
				<li><a href="#">Categoria 3</a></li>
				<li><a href="#">Categoria 4</a></li>
				<li><a href="#">Categoria 5</a></li>
				<li><a href="#">Categoria 6</a></li>
			</ul>
		</div>

		<div id="main-menu">

			<form>
				<input type="text" /> <input type="submit" value="Buscar" />
			</form>

			<ul>
				<li>Conectar</li>
				<li>12 productos (10000 &euro;)</li>
			</ul>

			<div id="conectar">
				<form>
					<label>Correo electronico</label> <input type="text" /> <label>Contraseña</label>
					<input type="password"> <input type="submit" value="Conectar">
				</form>
				<p>
					<a href="#">Recordar contaseña</a>
				</p>
				<p>
					<a href="#">Registrarme</a>
				</p>
			</div>

		</div>


		<?php
		include 'sidebar-categorias.php';
		?>


		<div id="main-content">
			<div class="flexslider">
				<ul class="slides">
					<li><a href="#" title="nombre del producto 3"><img
							src="images/slideshow/slide1.jpg" /></a>
						<p>520&euro;</p>
						<p>antes: 649&euro;</p>
						<div></div></li>
					<li><a href="#" title="nombre del producto 3"><img
							src="images/slideshow/slide2.jpg" /></a>
						<p>231&euro;</p>
						<p>antes: 328&euro;</p>
						<div></div></li>
					<li><a href="#" title="nombre del producto 3"><img
							src="images/slideshow/slide3.jpg" /></a>
						<p>7187&euro;</p>
						<p>antes: 9227&euro;</p>
						<div></div></li>

				</ul>
			</div>

			<h2>Ofertas destacadas</h2>



			<h2 class="separacion">Novedades</h2>

			<?php
			$prods = new ListasProductos($PDO);
			$prods->getProductoBD(null, 6);
			$a = $prods->getProductoById();
			$galeria = new Galeria($PDO);
			$b = $galeria->getImagenBD(null, TRUE);
			
			foreach ($a as $producto) {
			
			$imagen= $galeria->getImagenByProductoId($producto->getId());

			if ($imagen) {
				$b = $imagen->getImagen();
			} else {
				$b= 'default';
			}
			
			$size = 227;

			$url = "getthumb.php?path=images/products/{$b}.jpeg&size={$size}";

			?>
				<div class="box">
					<a href="#"><img
						src="<?=$url?>"
						title="<?=$producto->getNombre()?>" alt="<?=$producto->getNombre()?>" /></a>
					<p><?=$producto->getPrecioVenta()?>&euro;</p>
					<p>
						<a href="#"><?=$producto->getNombre()?></a>
					</p>
					<div></div>
					<p class="<?=quitarEspacios($producto->getDisponibilidad())?>"><?=$producto->getDisponibilidad()?></p>
				</div>				
			<?php
			}

			?>

			<h2 class="separacion">Outlet</h2>
			<?php
			$prods = new ListasProductos($PDO);
			$prods->getProductoBD(null, null, 6);
			$a = $prods->getProductoById();
			$galeria = new Galeria($PDO);
			$b = $galeria->getImagenBD(null, TRUE);
			
			foreach ($a as $producto) {
			
			$imagen= $galeria->getImagenByProductoId($producto->getId());

			if ($imagen) {
				$b = $imagen->getImagen();
			} else {
				$b= 'default';
			}
			
			$size = 227;

			$url = "getthumb.php?path=images/products/{$b}.jpeg&size={$size}";

			?>
				<div class="box">
					<a href="#"><img
						src="<?=$url?>"
						title="<?=$producto->getNombre()?>" alt="<?=$producto->getNombre()?>" /></a>
					<p><?=$producto->getPrecioVenta()?>&euro;</p>
					<p>
						<a href="#"><?=$producto->getNombre()?></a>
					</p>
					<div></div>
					<p class="<?=quitarEspacios($producto->getDisponibilidad())?>"><?=$producto->getDisponibilidad()?></p>
				</div>				
			<?php
			}

			?>

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