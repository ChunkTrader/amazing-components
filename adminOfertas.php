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
$controlador = new Controlador();
$controlador -> setRegistro ('feedback', $regFeedback);
$controlador -> setRegistro ('errores', $regError);
$controlador -> setPDO($PDO);

$cats = new Categorias($controlador);
$prods = new Productos($controlador);
$galeria = new Imagenes($controlador);
$ofertas = new Ofertas($controlador);

// Intentamos recuperar la oferta
if ($regMem->getValor('id')) {
	$oferta =$ofertas->getItemBD(array ('id'=>$regMem->getValor('id')))->getItemById($regMem->getValor('id'));
	if (!$oferta) {
		$regError->setError('general', ' No existe ninguna oferta con esa <b>id</b>.');
	}
}

// Titulo por defecto de la página
$regMem->setValor('titulo', 'Administrar Ofertas');

switch ($regMem->getValor('accion')){
	case 'Cancelar':
	header("Location: {$_SERVER['SCRIPT_NAME']}");
	exit;
	break;

}

// Cargamos todos los productos, las categorias, las ofertas
$prods->getItemBD();
$cats->getItemBD();
$ofertas->getItemBD();

include 'cabecera.php';
?>

<div id="main">

	<?php
	include 'top-menu.php';
	include 'main-menu.php';
	include 'sidebar-administrar.php';
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

		<h2>Ofertas activas</h2>
		<?php
		$a=$ofertas->getItemById();
		if (!$ofertas->getTotal()) {
			echo '<h3 class="separacion">No se ha creado ninguna oferta</h3>';
		} else {
		?>
			
			<table>
				<tr>
					<th>Producto</th>
					<th>Categoría</th>
					<th>Precio actual</th>
					<th>Precio anterior</th>
					<th>Activa</th>
					<th> </th>
				</tr>
				<?php
					foreach ($a as $oferta) {
						$b = $prods->getItemById($oferta->getPropiedad('producto_id'));
						$c = $cats->getItemById($b->getPropiedad('categoria_id'));
						echo "<tr>";
						echo "<td>";
						echo "<a href=\"{$_SERVER['SCRIPT_NAME']}?id=".$oferta->getPropiedad('id') ."\">";
						echo "{$b->getPropiedad('nombre')}</a></td>";
						echo "<td>{$c->getPropiedad('nombre')}</td>";
						echo "<td>{$b->getPropiedad('precio_venta')}&euro;</td>";
						echo "<td>{$oferta->getPropiedad('precio_anterior')}&euro;</td>";
						echo "<td>" . ($oferta->getPropiedad('activa')?'Sí':'No') ."</td>";
						echo "<td></td>";
						echo "</tr>";
					}
				}
			?>
			</table>


		</div>


	</div>
</div>
	<script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox(
		{
			'loop' : false,
		}
		);

	});
	</script>
	<?php
	include 'pie.php';
	?>
</body>
</html>
