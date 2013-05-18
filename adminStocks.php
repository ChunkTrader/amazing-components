<?php
require_once 'inicializacion.php';
require_once 'classes/Fabricantes.php';

$prods = new Productos($controlador);
$fabs = new Fabricantes($controlador);


if (!$regMem->getValor('ver') && (!$regSistema->getValor('privilegios')['verAdminStocks'])) {
	$regSistema->setValor('acceso_denegado', 'administrar');
	header('Location: error.php');
	exit;
} 

// Titulo por defecto de la página
$regMem->setValor('titulo', 'Control de stocks');

if (!$regMem->getValor('ver')) {
	$regMem->setValor('ver', 'todo');
}

switch ($regMem->getValor('ver')) {
	case 'todo':
		$regMem->setValor('titulo', 'Control de stocks: Todos los productos activos');
		$prods->getItemBD(array(
				'activo'=>1
			));
		break; // ver = todo

	case 'descatalogados':
		$regMem->setValor('titulo', 'Control de stocks: Productos descatalogados y Outlets');
		$prods->getItemBD(array(
				'activo'=>0
			));
		break; // ver = todo	

	case 'agotados':
		// Esto descarta los descatalogados
		$regMem->setValor('titulo', 'Control de stocks: Productos agotados');
		$prods->getItemBD(array(
				'disponibilidad'=>'Agotado'
			));
		break; // ver = agotados

	case 'minimos':
		// Se recuperan todos los pedidos con existencias menor a STOCK_MINIMO.
		// Este debería ser un valor por defecto y cada producto tener sus mínimos en la tabla productos.
		$regMem->setValor('titulo', 'Control de stocks: Productos bajo mínimos');
		$prods->getItemBD (array(
				'existencias' => STOCK_MINIMO
			));
		break; // ver = minimos

	default:
		// Si un valor en ver pero no es válido es un error
		if ($regMem->getValor('ver')) {
			$regSistema->setValor('acceso_denegado', 'administrar');
		}
		break; // Default ver
}



if (!$prods->getTotal()) {
	$regFeedback->addFeedback('No se ha encontrado ningún producto.');
}


include 'cabecera.php';
?>

<div id="main">

<?php
include 'top-menu.php';
include 'main-menu.php';
include 'sidebar-administrar.php';


// Si hay un error redirigimos la página
if ($regSistema->getValor('acceso_denegado')) {
	header('Location: error.php');
	exit;
}

?>


<div id="main-content">
	<h2><?=$regMem->getValor('titulo')?></h2>
	<div class="separacion">
		<?php
		if ($regError->getError()) {
			$a = $regError->getError();
			foreach ($a as $error) {
				echo "<p class=\"error centrado\">{$error}</p>";
			}
		}
		if ($regFeedback->getFeedback()) {
			$a = $regFeedback->getFeedback();
			foreach ($a as $feed) {
				echo "<p class=\"centrado\">{$feed}</p>";
			}
		}
		?>
	</div>

	<div class="separacion">
	<?php 
	/*		VER LISTA PRODUCTOS 		*/
	
	if ($prods->getTotal()) {
	?>

	<table>
		<tr>
			<th>Nombre</th>
			<th>Fabricante</th>
			<th>Categoria</th>
			<th>Existencias</th>
			<th>Disponibilidad</th>
			<th></th>
		</tr>

		<?php
		// Cargamos la lista completa de fabricantes y categorias.
		$fabs->getItemBD();
		$cats->getItemBD();

		$a = $prods->getItemById();

		foreach ($a as $producto) {
			$fabricante = $fabs->getItemById($producto->getPropiedad('fabricante_id'));
			$categoria = $cats->getItemById($producto->getPropiedad('categoria_id'));

			echo '<tr>';
			echo "<td>{$producto->getPropiedad('nombre')}</td>";
			echo "<td>{$fabricante->getPropiedad('nombre')}</td>";
			echo "<td>{$categoria->getPropiedad('nombre')}</td>";
			echo '<td>' . ($producto->getPropiedad('existencias') ? $producto->getPropiedad('existencias') : '0') . '</td>';
			echo "<td class=\"". quitarEspacios($producto->getPropiedad('disponibilidad')). "\">{$producto->getPropiedad('disponibilidad')}</td>";
			echo '<td></td>';
			echo '</tr>';		

		}

		?>
	</table>




	<?php
	} else {
		// Si no se ha encontrado ningún producto en la lista podemos mostrar algún mensaje especial.

	?>
		
	<?php
	} // Else final de las vistas


	?>

		<p class="separacion centrado"><a href="<?=$_SERVER['SCRIPT_NAME']?>">Volver a la lista completa</a></p>		

	</div>
</div>

<?php
include 'pie.php';
?>

</body>
</html>
