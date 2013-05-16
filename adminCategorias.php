<?php
require_once 'inicializacion.php';

// Comprobamos si tiene privilegio de acceso a la página
if (!$regSistema->getValor('privilegios')['verAdminCategorias']){
	$regSistema->setValor('acceso_denegado', 'administrar');
	header('Location: error.php');
	exit;
}

// Intentamos recuperar la categoria
if ($regMem->getValor('id')){
	$categoria=$cats->getItemBD(array('id'=>$regMem->getValor('id')))->getItemById($regMem->getValor('id'));
}

// Titulo por defecto de la página
$regMem->setValor('titulo', 'Añadir Categoría');


switch ($regMem->getValor('accion')){

	case 'Cancelar':
	header("Location: {$_SERVER['SCRIPT_NAME']}");
	exit;
	break;

	case 'Añadir':
	if ($regMem->getValor('nombre')) {
		$valores = array (
			'nombre'=>$regMem->getValor('nombre'), 
			'parent_id' => $regMem->getValor('parent_id'), 
			'descripcion' => $regMem->getValor('descripcion'));
		$cats->addItemBD ( new Categoria ( $valores));
		$regFeedback->addFeedback("Se ha añadido la categoría <b>{$regMem->getValor('nombre')}</b> con éxito.");
	} else {
		$regError->setError('general', 'El nombre no puede estar vacío');
	}
	break;

	case 'Eliminar':		
	$regMem->setValor('titulo', 'Eliminar Categoría');

	// Si el método es POST es que hemos enviado la confiramación
	if ($regMem->getValor('metodo')=='POST' && $categoria) {				
		$cats->getItemBD();
		if ($cats->getChildItemsById($categoria->getPropiedad('id'))) {
			$regError->setError('general' , "No puede eliminarse <b>{$categoria->getPropiedad('nombre')}</b> porque tiene categorias descendientes.");
		} else {
			$cats->delItemBD($regMem->getValor('id'));
			$regFeedback->addFeedback("Se ha eliminado la categoria <b>{$categoria->getPropiedad('nombre')}</b> con éxito.");
		}
	} else if ($regMem->getValor('metodo')=='GET' && $categoria) {
		$regMem->setValor('nombre', $categoria->getPropiedad('nombre'));
	} else {
		$regError->setError('general', 'No existe ninguna categoría con esa <b>id</b>.');
	}

	break;

	case 'Editar':
	$regMem->setValor('titulo', 'Editar Categoría');

	if ($regMem->getValor('metodo')=='POST' && $categoria) {

		$valores = array (
			'id' =>$regMem->getValor('id'),
			'nombre'=>$regMem->getValor('nombre'),
			'parent_id' => $regMem->getValor('parent_id'),
			'descripcion' => $regMem->getValor('descripcion'),
			'activa' => (boolean)($regMem->getValor('activa'))
			);
		$cats->setItemBD ( new Categoria ( $valores));
		$regFeedback->addFeedback("Se ha modificado la categoría <b>{$regMem->getValor('nombre')}</b> con éxito.");
	} else if (!$categoria) {
		$regError->setError('general', 'No existe ninguna categoría con esa <b>id</b>.');
	}
	break;
}

// Cargamos la lista de categorias
$cats->getItemBD();


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
		<?php 
		// Si no hemos recibido ninguna acción o la acción es añadir
		// mostramos el formulario para añadir categorias
		if (!$regMem->getValor('accion') || $regMem->getValor('accion')=='Añadir') {
		?>
		
		<!-- FORMULARIO PARA AÑADIR CATEGORIAS -->

		<form action="<?=$_SERVER['SCRIPT_NAME'] ?>" method="post">
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
				<input type="submit" value="Añadir" name="accion"/>
			</p>
		</form>
	
	<?php
		} else if (!$categoria) {

			// Si no hemos recibido la categoría es que hay un error y no continuamos con las demás
			// posibilidades.

		} else if ($regMem->getValor('accion') == 'Eliminar' && $regMem->getValor('metodo') == 'GET') {
			?>
		
			<!-- FORMULARIO PARA CONFIRMAR ELIMINACIÓN  -->
			<form action="<?=$_SERVER['SCRIPT_NAME'] ?>" method="post">
				<p class="centrado">¿Estas seguro de que deseas eliminar la categoría <b><?=$regMem->getValor('nombre')?></b>?</p>
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
				<form action="<?=$_SERVER['SCRIPT_NAME'] ?>" method="post">
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

	if ($regMem->getValor('accion')!='Añadir' && ($regError->getError() || $regMem->getValor('metodo')=='POST')) {
	//if (($regError->getError() && $regMem->getValor('accion')!='Añadir') || ($regMem->getValor('accion')!='Añadir' && $regMem->getValor('metodo')=='POST')) {
		?>
		<!-- MOSTRAMOS ENLACE A AÑADIR -->
		<p class="separacion centrado"><a href="<?=$_SERVER['SCRIPT_NAME']?>">Añadir categoria</a></p>

		<?php
	}

	?>
	
	</div>
	<!-- VISTA DE CATEGORIAS -->
	<div id="categorias">
		<h2>Lista de categorias</h2>

		<?php
		$a = $cats->getChildItemsById(0);
		$columnas = 3;
		$contador = 0;
		$contador_max = ceil($cats->getTotal()/$columnas);

		foreach ( $a as $cat ) {			

		// Principal: categoria null:
			if ($cat->getPropiedad('parent_id') == null) {
				if ($contador==0) {
					echo "<div class=\"categoria\">";
				}
				$contador+=1;
				echo "<ul><li><a href=\"{$_SERVER['SCRIPT_NAME']}?accion=Editar&id={$cat->getPropiedad('id')}\" title=\"Editar: {$cat->getPropiedad('nombre')}\">{$cat->getPropiedad('nombre')}</a>";
				// añadimos el icono de eliminar
				echo "<a href=\"{$_SERVER['SCRIPT_NAME']}?accion=Eliminar&id={$cat->getPropiedad('id')}\" title=\"Eliminar: {$cat->getPropiedad('nombre')}\"><img src=\"images/icon_delete.gif\"/></a>";
				echo "<ul>";


				// 	Buscamos sus hijos
					$children = $cats->getChildItemsById($cat->getPropiedad('id'));
				
				foreach ($children as $child) {
					if ($contador==0) {
						echo "<div class=\"categoria\"><ul><li><ul>";
					}
					$contador+=1;
					echo "<li><a href=\"{$_SERVER['SCRIPT_NAME']}?accion=Editar&id={$child->getPropiedad('id')}\" title=\"Editar: {$child->getPropiedad('nombre')}\">{$child->getPropiedad('nombre')}</a>";
					// añadimos el icono de eliminar
					echo "<a href=\"{$_SERVER['SCRIPT_NAME']}?accion=Eliminar&id={$child->getPropiedad('id')}\" title=\"Eliminar: {$child->getPropiedad('nombre')}\"><img src=\"images/icon_delete.gif\"/></a>";
					echo "</li>";
					if ($contador>=$contador_max) {
						echo "</ul></li></ul></div>";
						$contador=0;
					}

				}
				echo "</ul></li></ul>";

				if ($contador>=$contador_max) {
					echo "</div>";
					$contador=0;
				}
				
			}

		}
		?>
		</div>
	</div>

</div>

<?php
include 'pie.php';
?>

</body>
</html>
