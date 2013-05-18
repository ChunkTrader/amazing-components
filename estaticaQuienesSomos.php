<?php
require_once 'inicializacion.php';

// Comprobamos si tiene privilegio de acceso a la página si es necesario

/* 
if (!$regSistema->getValor('privilegios')[' PRIVILEGIO DE ACCESO ']){
	$regSistema->setValor('acceso_denegado', 'principal');
	header('Location: error.php');
	exit;
}
*/

// Titulo por defecto de la página
$regMem->setValor('titulo', 'Formas de pago');


include 'cabecera.php';
?>

<div id="main">

	<?php
	include 'top-menu.php';
	include 'main-menu.php';

	/// Cargamos la sidebar que nos interese
	include 'sidebar-categorias.php';
	//include 'sidebar-usuarios.php';
	//include 'sidebar-administrar.php';

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
			<h3 class="error">¡ATENCIÓN ESTO ES UNA PÁGINA DE PRUEBA Y ESTA INFORMACIÓN ES SOLO RELLENO</h3>	

			<h3 class="separacion">Quienes somos</h3>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras mattis consequat mattis. Sed ac enim nisl. Nunc auctor, elit eget faucibus faucibus, dolor ligula vehicula nulla, sed eleifend metus libero sit amet dui. Fusce at erat nulla, in euismod velit. Duis iaculis tincidunt tempor. Nam mattis sollicitudin aliquet. Aenean venenatis quam bibendum augue volutpat viverra. Maecenas ante nunc, sagittis at varius sit amet, fringilla rutrum justo. Pellentesque egestas cursus convallis. Morbi at enim ac neque feugiat tincidunt. Integer rhoncus nunc vitae magna ullamcorper vestibulum. Etiam quis ligula ut neque ultricies ultricies. Nam tincidunt metus quis nisi dictum interdum. Vivamus id gravida lacus.</p>

				<p>Nulla facilisi. Nulla lobortis massa elit. Fusce pellentesque lorem et felis sagittis convallis. Vivamus vitae lectus vel quam semper elementum id a quam. Donec eu dapibus metus. Maecenas porttitor, quam sed eleifend convallis, est nibh volutpat lacus, eu congue libero est accumsan nisi. Duis hendrerit augue id nisl dictum luctus. Cras molestie ante et dolor lobortis dictum. Praesent imperdiet elementum odio, ultrices pellentesque augue suscipit vestibulum. Donec id elit vel lectus mattis vulputate sit amet ut purus.</p>
			

			<h3 class="separacion">Nuestras instalaciones</h3>
				<p>Mauris eget mattis quam. Etiam in ipsum quis libero rutrum fringilla. Proin adipiscing libero consequat odio facilisis vestibulum. Curabitur lobortis nibh at erat aliquet eget commodo justo bibendum. Vestibulum tincidunt, neque nec placerat pretium, velit purus semper mi, et feugiat nulla elit at sem. Proin nec erat nisi, vel sagittis metus. Integer porttitor felis urna. Sed dapibus neque ut purus laoreet tincidunt. Quisque ullamcorper auctor eros, ut tristique nibh sollicitudin in. Donec metus erat, porttitor sed pulvinar lacinia, mattis a ipsum. Aenean egestas ornare ante et bibendum.</p>

				<p>Sed elit mauris, dictum eget tempus eget, tincidunt non felis. Aenean eros nunc, tincidunt a consectetur a, consequat nec nisl. Pellentesque facilisis condimentum ullamcorper. Sed erat justo, scelerisque sed vehicula non, fermentum at sapien. Curabitur in libero vel eros pellentesque consequat. Mauris eros sapien, congue ac interdum sit amet, porta non magna. Quisque imperdiet, mauris vitae ultricies feugiat, velit mauris dignissim tortor, sit amet rutrum purus nisl sed eros. Donec quis turpis lectus, a ultrices tortor. Aliquam posuere metus vel erat gravida faucibus.</p>

			
			<h3 class="separacion">Servicio técnico</h3>
				<p>Maecenas auctor malesuada lacus in viverra. Nam non elit id eros pellentesque dapibus et id risus. Sed eu nulla sapien, ac tincidunt quam. Aenean ipsum orci, viverra ac tempus non, posuere vel felis. Proin posuere, elit in interdum adipiscing, ante metus tristique nunc, eget tempus urna libero aliquam odio. Vivamus vel orci nulla, fermentum vehicula erat. Proin auctor mi eu nisi fermentum ut lobortis massa consectetur. Aliquam vulputate feugiat ullamcorper. Donec sagittis interdum nisl, euismod elementum tellus ornare et. Aliquam placerat, neque vehicula bibendum lacinia, nibh lorem gravida mauris, quis pharetra enim nibh at nisl. Duis pulvinar laoreet pretium. Curabitur a mi in diam interdum pellentesque in eu risus. Duis dignissim dictum metus, ut aliquet mauris venenatis at. Vivamus suscipit accumsan nisl, et condimentum risus ullamcorper non. Donec ornare sapien eu est aliquet id laoreet nulla pretium.</p>


		</div>

	</div>

	<?php
	include 'pie.php';
	?>

</body>
</html>
