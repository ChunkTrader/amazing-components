<?php
require_once 'inicializacion.php';

// Vaciamos el carrito
$carrito = null;
$regSistema->setValor('carrito', $carrito);
setcookie('carrito', '', time()-3600);

// Volvemos a la página principal del sitio para simplificar.

// Habría que añadir la última página visitada en una variable de sesión junto con 
// todas las variables POST/GET para poder redirigir de nuevo al mismo sitio.
header ('location: index.php');
?>
