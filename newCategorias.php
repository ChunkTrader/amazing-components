<?php
require_once 'conectar_bd.php';
require_once 'Categorias.php';


$PDO = new PDOConfig ();

$cats = new ListasCategorias ($PDO);

if (! empty ( $_POST ['nombre'] )) {
	if ($_POST ['parent_id']==0) {
		$_POST ['parent_id']=null;
	}
	$cats->addCategoriaBD ( new Categoria ( $_POST ['nombre'], null, $_POST ['parent_id'], $_POST ['descripcion'] ) );
}

$cats->getCategoriaBD (  );


?>
<head>
<link href="css/main.css" rel="stylesheet" type="text/css" title="main" />
<link href="css/admin-site.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="main-content">
		<h2>Añadir Categorias</h2>
		<?php

		?>
	<div class="separacion" >
		<form action="<?=$_SERVER['SCRIPT_NAME'] ?>" method=post>
			<label>Nombre: </label>
			<input type="text" name="nombre" />
			<label>Parent Cat: </label>
			<select name="parent_id">
				<option value="" selected="selected">&lt;General&gt;</option>
				<?php
				$a = $cats->getChildCategoriasById(0);
				//$a = $cats->getCategoriaById ();
				
				foreach ($a as $cat ) {
					echo "<option value=\"{$cat->getId()}\">{$cat->getNombre()}</option>";
				}
				?>
			</select> 
			<label>Descripcion:</label>
			<input type="text" class="long" maxlength="80" name="descripcion" />
			<p class="centrado">
				<input type="submit" value="Añadir" />
			</p>
		</form>
	</div>
		<div id="categorias">
		<?php
		include 'listaCategorias.php';
		?>
	</div>
</div>
</body>