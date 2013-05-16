<?php
require_once 'inicializacion.php';
require_once 'classes/Fabricantes.php';
require_once 'classes/Ofertas.php'; 

$prods = new Productos($controlador);
$galeria = new Imagenes($controlador);

$galeria->getItemBD(array('principal' => TRUE));

// T�tulo por defecto de la p�gina
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
					<?php
					$prods = new Productos($controlador);
					$ofertas = new Ofertas($controlador);
					
					// Cargamos todos las productos en oferta
					$prods->getItemBD(array('ofertas'=>-1));
					// Cargamos todas las ofertas (habria que mejorarlo 
					// para hacerlo todo con una sola consulta)
					$ofertas->getItemBD();

					// Recorremos los productos y comprobamos si la oferta
					// tiene un slideshow activo.
					
					$a=$prods->getItemById();

					foreach ($a as $producto){
						
						$oferta=$ofertas->getItemByProducto($producto->getPropiedad('id'));

						if ($oferta->getPropiedad('activa') && $oferta->getPropiedad('slideshow_activo')){
							echo "<li>";
							echo "<a href=\"detalleProducto.php?id={$producto->getPropiedad('id')}\" title=\"{$producto->getPropiedad('nombre')}\">";
							echo "<img src=\"images/slideshow/{$oferta->getPropiedad('slideshow_url')}.jpeg\" />";
							echo "</a>";
							echo "<p>{$producto->getPropiedad('precio_venta')}&euro;</p>";
							echo "<p>antes: {$oferta->getPropiedad('precio_anterior')}&euro;</p>";
							echo "<div class=\"fondo_precio\"></div>";
							echo "<p class=\"fondo_texto\">{$producto->getPropiedad('nombre')}</>";
							echo "</li>";
						}
					}
					?>
				</ul>
			</div>



			<h2>Ofertas destacadas</h2>

			<?php

			$prods->getItemBD(array('ofertas' => MAX_OFERTAS));
			// $ofertas->getItemBD(); // Ya las hemos cargado en el Slideshow

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



			<p class="derecha"><a href="verProductos.php?ofertas=-1">Ver m�s ofertas &gt;&gt;</a></p>			<h2 class="separacion">Novedades</h2>

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
			<p class="derecha"><a href="verProductos.php?novedades=-1">Ver m�s novedades &gt;&gt;</a></p>
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
			
			<p class="separacion derecha"><a href="verProductos.php?outlet=-1">Ver m�s outlets &gt;&gt;</a></p>
			
		</div>
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
	

<?php
include 'pie.php';
?>

</body>
</html>

</body>
</html>