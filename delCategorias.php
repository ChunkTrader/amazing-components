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

if ($categoria && $accion=='Eliminar') {
		$cats->delCategoriaBD (  $categoria_id);
}
?>

<head>
<link href="css/main.css" rel="stylesheet" type="text/css" title="main" />
<link href="css/admin-site.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="main-content">
	<h2>Eliminar Categorias</h2>

	<div class="separacion" >

		<?php
		if ($categoria && empty($accion)) {
			?>

		<form action="<?=$_SERVER['SCRIPT_NAME'] ?>" method=post>

			<p class="centrado">¿Estas seguro de que deseas eliminar la categoría <b><?=$categoria->getNombre()?></b>?</p>

			<input type="hidden" name="id" value="<?=$categoria_id?>" />
			<p class="separacion centrado">
				<input type="submit" value="Eliminar" name="accion"/>
				<input type="submit" value="Cancelar" name="accion" />
			</p>
		</form>
		<?php
		} else if ($categoria && $accion=='Eliminar') {
		?>
			<p class="centrado"> La categoria <?=$categoria->getNombre()?> ha sido eliminada. </p>
		<?php
		} else {
		?>
			<p class="centrado"> La categoria no existe. </p>
		<?php
		}		
		?>
			<p class="separacion centrado"><a href="newCategorias.php">Añadir categoria</a></p>

	</div>
	<div id="categorias">
		<?php

		include 'listaCategorias.php';

		?>
	</div>
</div>
</body>