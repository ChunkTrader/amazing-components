<?php
require_once 'inicializacion.php';

// Vaciamos el carrito
$carrito = null;
$regSistema->setValor('carrito', $carrito);
setcookie('carrito', '', time()-3600);

// Volvemos a la p�gina principal del sitio para simplificar.

// Habr�a que a�adir la �ltima p�gina visitada en una variable de sesi�n junto con 
// todas las variables POST/GET para poder redirigir de nuevo al mismo sitio.
header ('location: index.php');
?>
