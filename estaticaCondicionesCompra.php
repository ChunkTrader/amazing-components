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
		<h3 class="error">�ATENCI�N ESTO ES UNA P�GINA DE PRUEBA Y ESTA INFORMACI�N ES SOLO RELLENO</h3>	
		<h3 class="separacion">	Condiciones de compra</h3>

		<p class="separacion">Le facilitamos diferentes formas para realizar los pagos de sus pedidos:</p>
		<ul class="texto">
			<li>Transferencia o ingreso bancario. Con esta forma de pago usted realiza el pago por adelantado, bien transfiriendo el importe del pedido desde su entidad bancaria o bien haciendo el ingreso en ventanilla en una de nuestras entidades. Con esta forma de pago no se paga ninguna cantidad como gastos de gesti�n y puede beneficiarse de promociones puntuales. Recuerde que los pedidos por transferencia se guardar�n 4 d�as h�biles en espera del pago, sin contar fines de semana, comenzando al d�a siguiente de realizar el pedido, pasado este tiempo se eliminar�n autom�ticamente. Forma de pago recomendada para reducir comisiones. </li>

			<li>Contrareembolso. Usted paga el pedido cuando se lo entrega la empresa de transporte. Esta forma de pago conlleva un 5% de gastos de gesti�n de cobro sobre el total del pedido con un m�nimo de 2.50 &euro; que cobra la empresa transportista. Esta forma de pago est� limitada a un m�ximo total de 500&euro;. Forma de pago v�lida para Espa�a peninsular.</li>
			<li>Tarjeta de cr�dito. Esta forma de pago es inmediata, totalmente segura y verificada por VISA. Todos los pagos efectuados mediante esta forma de pago ser�n validados por su entidad bancaria y en caso de ser aceptados nos ser� remitido de forma autom�tica e instantanea. Requiere de una clave de seguridad para poder realizar este tipo de pagos online. Consulte a su entidad bancaria si desconoce su clave de seguridad. Se aceptan tarjetas VISA/ VISA Electron y MasterCard/Maestro. Esta forma de pago conlleva un 1% de gastos de gesti�n de cobro sobre el total del pedido con un m�nimo de 1.95 &euro; . </li>
			<li>Paypal. Soluci�n de pago segura que permite a compradores y empresas enviar y recibir dinero por Internet. No necesita introducir datos bancarios, tan solo se requiere que tenga una cuenta Paypal creada y podr� hacer pagos introduciendo solamente su email y contrase�a. Esta forma de pago conlleva un 3% de gastos de gesti�n de cobro sobre el total del pedido con un m�nimo de 1.95 &euro; . </li>
			<li>Financiaci�n bancaria. Esta forma de pago queda supeditada a aceptaci�n previa por Banco Cetelem y generalmente tarda 3/4 d�as en ser aceptada. Banco Cetelem le contactar� para solicitarle sus datos y una vez aceptado el pago nos ser� comunicado de inmediato. Esta forma de pago conlleva un 1% de gastos de gesti�n de cobro sobre el total del pedido con un m�nimo de 1.95 &euro; . Para m�s informaci�n siga este enlace. Adem�s, mientras est� disponible el Plan Avanza del gobierno, podr� beneficiarse de intereses muy bajos. </li>
			<li>Pago en met�lico. Esta forma de pago solamente es posible siempre y cuando el pedido sea recogido en nuestras instalaciones.</li>
		</ul>
		</div>

	</div>

	<?php
	include 'pie.php';
	?>

</body>
</html>
