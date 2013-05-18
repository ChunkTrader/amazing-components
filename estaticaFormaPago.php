<?php
require_once 'inicializacion.php';

// Comprobamos si tiene privilegio de acceso a la p�gina si es necesario

/* 
if (!$regSistema->getValor('privilegios')[' PRIVILEGIO DE ACCESO ']){
	$regSistema->setValor('acceso_denegado', 'principal');
	header('Location: error.php');
	exit;
}
*/

// Titulo por defecto de la p�gina
$regMem->setValor('titulo', '�Quienes somos?');


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
			<h3 class="error">�ATENCI�N ESTO ES UNA P�GINA DE PRUEBA Y ESTA INFORMACI�N ES SOLO RELLENO</h3>	

			<p class="separacion"> Los precios y condiciones de venta tienen un car�cter meramente informativo y  pueden ser modificados en atenci�n a las fluctuaciones del mercado. No obstante,  la realizaci�n del pedido  mediante la cumplimentaci�n del  formulario de compra,  implica la conformidad con el precio ofertado y con las condiciones generales de venta vigentes en este concreto momento. Una vez formalizado el pedido se entender� perfeccionada la compra de pleno derecho,  con todas las garant�as legales que amparan al consumidor adquirente, y desde ese instante los precios y condiciones tendr�n car�cter contractual, y no podr�n ser modificados sin el  expreso acuerdo de ambos contratantes.</p>

			<h3 class="separacion">Envios</h3>

			<ul class="texto">
				<li>Todos los env�os se realizan de manera urgente 24 horas (pen�nsula) y 48 horas (Baleares). No podemos garantizar estos plazos de entrega, si bien intentamos que la empresa de transportes los cumpla siempre que sea posible.
				<li>Los plazos de entrega depender�n de la disponibilidad de cada producto, la cual se encuentra indicada en todos y cada uno de los productos ofertados. En los pedidos que incluyan varios art�culos se har� un �nico env�o y el plazo de entrega se corresponder� con el art�culo cuyo plazo de entrega sea mayor.</li>
				<li>El cliente dispondr� de 24 horas para comprobar la integridad de todos los componentes del pedido y para comprobar que se incluye todo lo que debe en los productos incluidos. Pasadas estas 24 horas se dar� por aceptado el env�o y no se aceptar�n reclamaciones por desperfectos o fallos con el env�o.</li>
				<li>Se considerar� entregado un pedido cuando sea firmado el recibo de entrega por parte del cliente. Es en las pr�ximas 24 horas cuando el cliente debe verificar los productos a la recepci�n de los mismos y exponer todas las objeciones que pudiesen existir.</li>
				<li>En caso de recibir un producto da�ado por el transporte es recomendable contactarnos dentro de las primeras 24h para poder reclamar la incidencia a la empresa de transporte. De la misma forma es conveniente dejar constancia a la empresa de transporte.</li>
				<li>Posteriormente debe de indicarnos su caso mediante nuestro formulario de contacto haciendo click aqu�, indicando el n�mero de pedido y el problema que presenta. Una vez recibida la incidencia le ser� tramitado un nuevo env�o si as� lo requiere su caso.</li>
			</ul>

			<h3 class="separacion">Devoluciones</h3>
			<p>Amazing-Components.com ofrece la posibilidad de devolver productos dentro de los primeros 7 d�as, por el motivo que sea, a contar desde la fecha de recepci�n de la mercanc�a por el cliente, y siempre que se cumplan las condiciones expuestas en esta p�gina.</p>


		</div>

	</div>

	<?php
	include 'pie.php';
	?>

</body>
</html>
