<?php
		//Cerramos la conexi�n con la base de datos
		if ($PDO) {
			$PDO = null;
		}
?>
<div id="footer">
	<p>Esto es una p�gina de prueba, todos los contenidos son ficticios. Usala bajo tu propia responsabilidad.</p>
	<p>Javier Garc�a Rodr�guez.</p>
	<p>�ltima actualizaci�n 10/05/2013</p>
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