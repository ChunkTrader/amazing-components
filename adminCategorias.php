<?php
require_once 'conectar_bd.php';
require_once 'Categorias2.php'; // Nueva
require_once 'Categorias.php'; // Borrando
require_once 'Registro.php';
require_once 'Colecciones.php';
require_once 'Controlador.php';

$PDO = new PDOConfig ();



// Incializamos los registros
$regMem = RegistroMemoria::instancia();
$regError = RegistroErrores::instancia();
$regFeedback = RegistroFeedback::instancia();

// El controlador de registros almacena un array con acceso a los registros que le a�adamos, este
// controlador se pasa a las colecciones al crearlo para que puedan mandar mensajes a la aplicaci�n
$controlador = new Controlador;
$controlador -> setRegistro ('feedback', $regFeedback);
$controlador -> setRegistro ('errores', $regError);
$controlador -> setPDO($PDO);

$cats = new Categorias($controlador);


//$cats = new ListasCategorias ($PDO);






// Intentamos recuperar la categoria
if ($regMem->getValor('id')){
	$categoria=$cats->getItemBD($regMem->getValor('id'))->getItemById($regMem->getValor('id'));
}

// Titulo por defecto de la p�gina
$regMem->setValor('titulo', 'A�adir Categor�a');


switch ($regMem->getValor('accion')){

	case 'Cancelar':
		header("Location: {$_SERVER['SCRIPT_NAME']}");
		exit;
		break;

	case 'A�adir':
		$regMem->setValor('titulo', 'A�adir Categor�a');

		if ($regMem->getValor('nombre')) {
			$valores = array (
				'nombre'=>$regMem->getValor('nombre'), 
				'parent_id' => $regMem->getValor('parent_id'), 
				'descripcion' => $regMem->getValor('descripcion'));
			$cats->addItemBD ( new Categoria ( $valores));
			$regFeedback->addFeedback("Se ha a�adido la categor�a <b>{$regMem->getValor('nombre')}</b> con �xito.");
		} else {
			$regError->setError('general', 'El nombre no puede estar vac�o');
		}
		break;

	case 'Eliminar':		
		$regMem->setValor('titulo', 'Eliminar Categor�a');

		// Si el m�todo es POST es que hemos enviado la confiramaci�n
		if ($regMem->getValor('metodo')=='POST' && $categoria) {
			$cats->delItemBD($regMem->getValor('id'));
			$regFeedback->addFeedback("Se ha eliminado la categoria <b>{$categoria->getPropiedad('nombre')}</b> con �xito.");
		} else if ($regMem->getValor('metodo')=='GET' && $categoria) {
			$regMem->setValor('nombre', $categoria->getPropiedad('nombre'));
		} else {
			$regError->setError('general', 'No existe ninguna categor�a con esa <b>id</b>.');
		}
		
		break;

	case 'Editar':
		$regMem->setValor('titulo', 'Editar Categor�a');

		if ($regMem->getValor('metodo')=='POST' && $categoria) {

			$valores = array (
				'id' =>$regMem->getValor('id'),
				'nombre'=>$regMem->getValor('nombre'),
				'parent_id' => $regMem->getValor('parent_id'),
				'descripcion' => $regMem->getValor('descripcion'),
				'activa' => (boolean)($regMem->getValor('activa'))
			);
			$cats->setItemBD ( new Categoria ( $valores));
			$regFeedback->addFeedback("Se ha modificado la categor�a <b>{$regMem->getValor('nombre')}</b> con �xito.");
		} else if (!$categoria) {
			$regError->setError('general', 'No existe ninguna categor�a con esa <b>id</b>.');
		}
		break;
	}

// Cargamos la lista de categorias
$cats->getItemBD();



?>
<head>
<link href="css/main.css" rel="stylesheet" type="text/css" title="main" />
<link href="css/admin-site.css" rel="stylesheet" type="text/css" />
<title><?=$regMem->getValor('titulo')?></title>
</head>
<body>
<div id="main-content">
		<h2><?=$regMem->getValor('titulo')?></h2>
		<div class="separacion">
		<?php
		if ($regError->getError()) {
			$a = $regError->getError();
			foreach ($a as $error) {
				echo "<p class=\"error centrado\">{$regError->getError('general')}</p>";
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
		// Si no hemos recibido ninguna acci�n o la acci�n es a�adir
		// mostramos el formulario para a�adir categorias
		if (!$regMem->getValor('accion') || $regMem->getValor('accion')=='A�adir') {
		?>
		
		<!-- FORMULARIO PARA A�ADIR CATEGORIAS -->

		<form action="<?=$_SERVER['SCRIPT_NAME'] ?>" method=post>
			<label>Nombre: </label>
			<input type="text" name="nombre" />
			<label>Parent Cat: </label>
			<select name="parent_id">
				<option value="" selected="selected">&lt;General&gt;</option>
				<?php
				$a = $cats->getChildItemsById(0);
				print_r($a);
				foreach ($a as $cat ) {
					echo "<option value=\"{$cat->getPropiedad('id')}\">{$cat->getPropiedad('nombre')}</option>";
				}
				?>
			</select> 
			<label>Descripcion:</label>
			<input type="text" class="long" maxlength="80" name="descripcion" />
			<p class="centrado">
				<input type="submit" value="A�adir" name="accion"/>
			</p>
		</form>
	
	<?php
	} else if (!$categoria) {

		// Si no hemos recibido la categor�a es que hay un error y no continuamos con las dem�s
		// posibilidades.

	} else if ($regMem->getValor('accion') == 'Eliminar' && $regMem->getValor('metodo') == 'GET') {
		?>
	
		<!-- FORMULARIO PARA CONFIRMAR ELIMINACI�N  -->
		<form action="<?=$_SERVER['SCRIPT_NAME'] ?>" method=post>
			<p class="centrado">�Estas seguro de que deseas eliminar la categor�a <b><?=$regMem->getValor('nombre')?></b>?</p>
			<input type="hidden" name="id" value="<?=$regMem->getValor('id')?>" />
			<p class="separacion centrado">
				<input type="submit" value="Eliminar" name="accion"/>
				<input type="submit" value="Cancelar" name="accion" />
			</p>
		</form>
		<?php		
	} else if ($regMem->getValor('accion') == 'Editar') {
		if ($regMem->getValor('metodo')=='GET') {
			?>
			<!-- FORMULARIO PARA EDITAR CATEGORIAS -->
			<form action="<?=$_SERVER['SCRIPT_NAME'] ?>" method=post>
				<label>Nombre: </label>
				<input type="text" name="nombre" value="<?=$categoria->getPropiedad('nombre')?>"/>
				<label>Parent Cat: </label>
				<select name="parent_id">
					<option value="">&lt;General&gt;</option>
					<?php
					$a = $cats->getChildItemsById(0);
					
					foreach ($a as $cat ) {
						
						if ($cat->getPropiedad('id')==$categoria->getPropiedad('id')) {
							continue;
						}

						echo "<option value=\"{$cat->getPropiedad('id')}\"";
						if ($cat->getPropiedad('id') == $categoria->getPropiedad('parent_id')) {
							echo " selected ";
						}
						echo ">{$cat->getPropiedad('nombre')}</option>";
					}
					?>
				</select>
				<label>Activa:</label>
				<input type="checkbox" name="activa"
				<?php
					if ($categoria->getPropiedad('activa')) {
						echo " CHECKED ";
					}
				?>
				/>
				<label>Descripcion:</label>
				<input type="text" class="long" maxlength="80" name="descripcion" value="<?=$categoria->getPropiedad('descripcion')?>" />
				<input type="hidden" value="<?=$categoria->getPropiedad('id')?>" name="id"/>
				<p class="separacion centrado">
					<input type="submit" value="Editar" name="accion"/>
					<input type="submit" value="Cancelar" name="accion" />
				</p>
			</form>

			<?php
		} else {		
			?>
			<!-- MOSTRAMOS ENLACE VOLVER -->
			<p class="separacion centrado">
				<a href="<?=$_SERVER['SCRIPT_NAME']?>?accion=Editar&id=<?=$regMem->getValor('id')?>">Volver</a>
			</p>
			
			<?php
		}
	}

	if ($regMem->getValor('accion')!='A�adir' && ($regError->getError() || $regMem->getValor('metodo')=='POST')) {
	//if (($regError->getError() && $regMem->getValor('accion')!='A�adir') || ($regMem->getValor('accion')!='A�adir' && $regMem->getValor('metodo')=='POST')) {
		?>
		<!-- MOSTRAMOS ENLACE A A�ADIR -->
		<p class="separacion centrado"><a href="<?=$_SERVER['SCRIPT_NAME']?>">A�adir categoria</a></p>

		<?php
	}

	?>
	
	</div>
	<!-- VISTA DE CATEGORIAS -->
	<div id="categorias">
	<?php
	include 'listaCategorias.php';
	?>
	</div>
</div>
</body>