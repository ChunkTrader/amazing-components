<?php
		//Cerramos la conexi�n con la base de datos
		if ($PDO) {
			$PDO = null;
		}
?>
<div id="footer">
	<ul class="menu-pie">
		<li><a href="estaticaCondicionesCompra.php">Condiciones de compra</a></li>
		<li><a href="estaticaFormaPago.php">Formas de pago</a></li>
		<li><a href="estaticaQuienesSomos.php">�Quienes somos?</a></li>
		<li><a href="estaticaGarantia.php">Garant�a</a></li>
	
	</ul>
	<p class="separacion">Esto es una p�gina de prueba desarrollada para la asignatura <b>Desarrollo de aplicaciones web en entorno servidor</b> para el CFGS Desarrollo de aplicaciones WEB.</p>
	<p class="separacion"><em>Javier Garc�a. �ltima actualizaci�n 10/05/2013</em></p>

</p>
</div>

<script>
	$('#carrito').hover(
	  function () {
	    $('#carrito_detalle').show();
	  }, 
	  function () {
	    $('#carrito_detalle').hide();
	  }
	);
	
	$('#carrito').click(function(){
  		window.location = 'comprar.php';
	}); 

	$(document).ready(function() {
		$('#carrito_detalle').hide();
	});
</script>