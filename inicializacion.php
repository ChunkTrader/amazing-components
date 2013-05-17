<?php
require_once 'configuracion.php';
require_once 'conectar_bd.php';

require_once 'classes/Controlador.php';
require_once 'classes/Registro.php';
require_once 'classes/PageNavigator.php';

require_once 'classes/Categorias.php';
require_once 'classes/Productos.php';
require_once 'classes/Imagenes.php';
require_once 'classes/Usuarios.php';



// Iniciamos la session, nos hace falta en todas las páginas
session_start();

$PDO = new PDOConfig ();

// Incializamos los registros
$regMem = RegistroMemoria::instancia();
$regError = RegistroErrores::instancia();
$regFeedback = RegistroFeedback::instancia();
$regSistema = RegistroSistema::instancia();

// El controlador de registros almacena un array con acceso a los registros que le añadamos, este
// controlador se pasa a las colecciones al crearlo para que puedan mandar mensajes a la aplicación
$controlador = new Controlador();
$controlador -> setRegistro ('feedback', $regFeedback);
$controlador -> setRegistro ('errores', $regError);
$controlador -> setPDO($PDO);

$cats = new Categorias($controlador);
$usuarios = new Usuarios($controlador);

// Cargamos la comprobación despues de cargar las demás clases e inicializar los registros
require_once 'comprobarUsuario.php';

// Funciones que no se donde poner
function quitarEspacios($string){
	$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
	$new_pattern = array("_", "_", "");
	return preg_replace($old_pattern, $new_pattern , $string);
}

?>