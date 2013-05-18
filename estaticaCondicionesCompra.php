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
$regMem->setValor('titulo', 'Condiciones de compra');


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
		<h3 class="separacion">	Condiciones de compra</h3>

		<p class="separacion">Le facilitamos diferentes formas para realizar los pagos de sus pedidos:</p>
		<ul class="texto">
			<li>Transferencia o ingreso bancario. Con esta forma de pago usted realiza el pago por adelantado, bien transfiriendo el importe del pedido desde su entidad bancaria o bien haciendo el ingreso en ventanilla en una de nuestras entidades. Con esta forma de pago no se paga ninguna cantidad como gastos de gestión y puede beneficiarse de promociones puntuales. Recuerde que los pedidos por transferencia se guardarán 4 días hábiles en espera del pago, sin contar fines de semana, comenzando al día siguiente de realizar el pedido, pasado este tiempo se eliminarán automáticamente. Forma de pago recomendada para reducir comisiones. </li>

			<li>Contrareembolso. Usted paga el pedido cuando se lo entrega la empresa de transporte. Esta forma de pago conlleva un 5% de gastos de gestión de cobro sobre el total del pedido con un mínimo de 2.50 &euro; que cobra la empresa transportista. Esta forma de pago está limitada a un máximo total de 500&euro;. Forma de pago válida para España peninsular.</li>
			<li>Tarjeta de crédito. Esta forma de pago es inmediata, totalmente segura y verificada por VISA. Todos los pagos efectuados mediante esta forma de pago serán validados por su entidad bancaria y en caso de ser aceptados nos será remitido de forma automática e instantanea. Requiere de una clave de seguridad para poder realizar este tipo de pagos online. Consulte a su entidad bancaria si desconoce su clave de seguridad. Se aceptan tarjetas VISA/ VISA Electron y MasterCard/Maestro. Esta forma de pago conlleva un 1% de gastos de gestión de cobro sobre el total del pedido con un mínimo de 1.95 &euro; . </li>
			<li>Paypal. Solución de pago segura que permite a compradores y empresas enviar y recibir dinero por Internet. No necesita introducir datos bancarios, tan solo se requiere que tenga una cuenta Paypal creada y podrá hacer pagos introduciendo solamente su email y contraseña. Esta forma de pago conlleva un 3% de gastos de gestión de cobro sobre el total del pedido con un mínimo de 1.95 &euro; . </li>
			<li>Financiación bancaria. Esta forma de pago queda supeditada a aceptación previa por Banco Cetelem y generalmente tarda 3/4 días en ser aceptada. Banco Cetelem le contactará para solicitarle sus datos y una vez aceptado el pago nos será comunicado de inmediato. Esta forma de pago conlleva un 1% de gastos de gestión de cobro sobre el total del pedido con un mínimo de 1.95 &euro; . Para más información siga este enlace. Además, mientras esté disponible el Plan Avanza del gobierno, podrá beneficiarse de intereses muy bajos. </li>
			<li>Pago en metálico. Esta forma de pago solamente es posible siempre y cuando el pedido sea recogido en nuestras instalaciones.</li>
		</ul>
		</div>

	</div>

	<?php
	include 'pie.php';
	?>

</body>
</html>
