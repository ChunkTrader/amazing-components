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


include 'cabecera.php';
?>

<div id="main">

	<?php
		include 'top-menu.php';
		include 'main-menu.php';
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

			<?php
			$prods = new Productos($controlador);
			$ofertas = new Ofertas($controlador);

			$prods->getItemBD(array('ofertas' => MAX_OFERTAS));
			$ofertas->getItemBD();

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
						<a href="detalleProducto.php?id=<?=$producto->getPropiedad('id')?>">
							<img src="<?=$url?>" title="<?=$producto->getPropiedad('nombre')?>" alt="<?=$producto->getPropiedad('nombre')?>" />
						</a>
						<p><?=$producto->getPropiedad('precio_venta')?>&euro;</p>
						<p>
							<a href="detalleProducto.php?id=<?=$producto->getPropiedad('id')?>"><?=$producto->getPropiedad('nombre')?></a>
						</p>
						<div></div>
						<p class="<?=quitarEspacios($producto->getPropiedad('disponibilidad'))?>"><?=$producto->getPropiedad('disponibilidad')?></p>
						<p class="descuento">
						<?php 
						$oferta = $ofertas->getItemByProducto($producto->getPropiedad('id'));
						$descuento = round(100-(1/$oferta->getPropiedad('precio_anterior')*$oferta->getPropiedad('precio_oferta'))*100);
						echo "-$descuento%";
						?>
						</p>
					</div>
			<?php
			}

			?>



			<p class="derecha"><a href="verProductos.php?ofertas=-1">Ver más ofertas &gt;&gt;</a></p>			<h2 class="separacion">Novedades</h2>

			<?php
			
			$prods->getItemBD(array('novedades' => MAX_NOVEDADES));
				
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
						<a href="detalleProducto.php?id=<?=$producto->getPropiedad('id')?>">
							<img src="<?=$url?>" title="<?=$producto->getPropiedad('nombre')?>" alt="<?=$producto->getPropiedad('nombre')?>" />
						</a>
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

			?>
			<p class="derecha"><a href="verProductos.php?novedades=-1">Ver más novedades &gt;&gt;</a></p>
			<h2 class="separacion">Outlet</h2>
			<?php			
			$prods->getItemBD(array('outlet' => MAX_OUTLET));
				
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
						<a href="detalleProducto.php?id=<?=$producto->getPropiedad('id')?>">
							<img src="<?=$url?>" title="<?=$producto->getPropiedad('nombre')?>" alt="<?=$producto->getPropiedad('nombre')?>" />
						</a>
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

			?>
			
			<p class="separacion derecha"><a href="verProductos.php?outlet=-1">Ver más outlets &gt;&gt;</a></p>
			
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