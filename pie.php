<?php
		//Cerramos la conexión con la base de datos
		if ($PDO) {
			$PDO = null;
		}
?>
<div id="footer">
	<p>Esto es una página de prueba, todos los contenidos son ficticios. Usala bajo tu propia responsabilidad.</p>
	<p>Javier García Rodríguez.</p>
	<p>Última actualización 10/05/2013</p>
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