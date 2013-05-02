<?php
require_once 'conectar_bd.php';
require_once 'Categorias.php';


$PDO = new PDOConfig ();

$cats = new ListasCategorias ($PDO);

$categoria_id = empty($_REQUEST['id']) ? null : $_REQUEST['id'];

$accion = empty($_POST['accion']) ? null : $_POST['accion'];;

$cats->getCategoriaBD (  );

if ($accion == 'Cancelar') {
		header("Location: newCategorias.php");
		exit;

}


// Comprobamos si existe la categoría
$categoria =$cats->getCategoriaById($categoria_id);

if ($categoria) {
	if ($accion=='Editar') {
		// Actualizamos nombre, parent_cat y descripción		
		$nombre = $_POST['nombre'];
		$parent_id = $_POST['parent_id'];
		$descripcion = $_POST['descripcion'];
		$activa = isset($_POST['activa']);

		if ($parent_id==0) {
			$parent_id=null;
		}

		$cat = new Categoria($nombre, $categoria_id, $parent_id, $descripcion);
		$cat->setActiva($activa);
		$cats->setCategoriaBD ( $cat);

	} else {
		$nombre = $categoria->getNombre();
		$parent_id = $categoria->getParentId();
		$descripcion = $categoria->getDescripcion();
		$activa = $categoria->getActiva();
	}
}


?>
<head>
<link href="css/main.css" rel="stylesheet" type="text/css" title="main" />
<link href="css/admin-site.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="main-content">
	<h2>Editar Categoria</h2>

	<div class="separacion" >

		<?php
		if ($categoria && $accion!='Editar') {
		?>


		<form action="<?=$_SERVER['SCRIPT_NAME'] ?>" method=post>
			<label>Nombre: </label>
			<input type="text" name="nombre" value="<?=$nombre?>"/>
			<label>Parent Cat: </label>
			<select name="parent_id">
				<option value="">&lt;General&gt;</option>
				<?php
				$a = $cats->getChildCategoriasById(0);
				
				foreach ($a as $cat ) {
					
					echo "<option value=\"{$cat->getId()}\"";
					if ($cat->getId() == $parent_id) {
						echo " selected ";
					}
					echo ">{$cat->getNombre()}</option>";
				}
				?>
			</select>
			<label>Activa:</label>
			<input type="checkbox" name="activa"
			<?php
				if ($activa) {
					echo " CHECKED ";
				}
			?>
			/>
			<label>Descripcion:</label>
			<input type="text" class="long" maxlength="80" name="descripcion" value="<?=$descripcion?>" />
			<input type="hidden" value="<?=$categoria_id?>" name="id"/>
			<p class="separacion centrado">
				<input type="submit" value="Editar" name="accion"/>
				<input type="submit" value="Cancelar" name="accion" />
			</p>
		</form>
		<?php
		} else if ($categoria && $accion=='Editar') {
			$volver = $_SERVER['SCRIPT_NAME'] . "?id=" . $categoria->getId() . "&accion=Editar";
		?>
			<p class="centrado"> La categoria <b><?=$categoria->getNombre()?></b> ha sido editada. </p>
			<p class="separacion centrado"><a href="<?=$volver?>">Volver</a></p>
			<p class="centrado"><a href="newCategorias.php">Añadir categoria</a></p>

		<?php
		} else {
		?>
			<p class="centrado"> La categoria no existe. </p>
			<p class="separacion centrado"><a href="newCategorias.php">Añadir categoria</a></p>
		<?php
		}		
		?>
		
	</div>
		<div id="categorias">
		<?php
		include 'listaCategorias.php';
		?>
	</div>
</div>
</body>