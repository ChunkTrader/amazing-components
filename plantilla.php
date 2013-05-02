<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Amazing Components - Bienvenido</title>
<link rel="shortcut icon" type="image/x-icon"
	href="http://localhost/practicas/amazing-components/favicon.ico" />

<script type="text/javascript" src="js/jquery-2.0.0.min.js"></script>
<script type="text/javascript" src="js/jquery.flexslider-min.js"></script>

<link href="css/main.css" rel="stylesheet" type="text/css" title="main" />
<link href="css/flexslider.css" rel="stylesheet" type="text/css" />

</head>
<body>

	<div id="header">
		<div>
			<a href="#"><img src="images/logo1.png"
				alt="Logo amazing-components.com" /></a>
		</div>
		<div>
			<a href="#"><img src="images/banners/banner.jpg"
				title="Banner principal" alt="banner principal" /></a>
		</div>

	</div>
	<div id="main">
		<div id="top-menu">
			<ul>
				<li><a href="#">Home</a></li>
				<li><a href="#">Categoria 1</a></li>
				<li><a href="#">Categoria 2</a></li>
				<li><a href="#">Categoria 3</a></li>
				<li><a href="#">Categoria 4</a></li>
				<li><a href="#">Categoria 5</a></li>
				<li><a href="#">Categoria 6</a></li>
			</ul>
		</div>

		<div id="main-menu">

			<form>
				<input type="text" /> <input type="submit" value="Buscar" />
			</form>

			<ul>
				<li>Conectar</li>
				<li>12 productos (10000 &euro;)</li>
			</ul>

			<div id="conectar">
				<form>
					<label>Correo electronico</label> <input type="text" /> <label>Contraseña</label>
					<input type="password"> <input type="submit" value="Conectar">
				</form>
				<p>
					<a href="#">Recordar contaseña</a>
				</p>
				<p>
					<a href="#">Registrarme</a>
				</p>
			</div>

		</div>


		<div id="sidebar">
			<ul>
				<li><a href="#">Categoria 1</a>
					<ul>
						<li><a href="#">Subcategoria 1.1</a></li>
						<li><a href="#">Subcategoria 1.2</a></li>
						<li><a href="#">Subcategoria 1.3</a></li>
					</ul></li>
				<li><a href="#">Categoria 2</a>
					<ul>
						<li><a href="#">Subcategoria 2.1</a></li>
						<li><a href="#">Subcategoria 2.2</a></li>
					</ul></li>
			</ul>

		</div>

		<div id="main-content">
			<div class="flexslider">
				<ul class="slides">
					<li><a href="#" title="nombre del producto 3"><img
							src="images/slideshow/slide1.jpg" /></a>
						<p>520&euro;</p>
						<p>antes: 649&euro;</p>
						<div></div></li>
					<li><a href="#" title="nombre del producto 3"><img
							src="images/slideshow/slide2.jpg" /></a>
						<p>231&euro;</p>
						<p>antes: 328&euro;</p>
						<div></div></li>
					<li><a href="#" title="nombre del producto 3"><img
							src="images/slideshow/slide3.jpg" /></a>
						<p>7187&euro;</p>
						<p>antes: 9227&euro;</p>
						<div></div></li>

				</ul>
			</div>

			<h2>Ofertas destacadas</h2>

			<div class="box">
				<a href="#"><img
					src="images/products/lg_42ls3400_42__led_290_290.jpg"
					title="Producto de prueba" alt="Producto de prueba" /></a>
				<p>2231&euro;</p>
				<p>
					<a href="#">LG 42LS3400 42" LED - Televisión</a>
				</p>
				<div></div>
				<p>En stock</p>

			</div>

			<div class="box">
				<a href="#"><img
					src="images/products/msi_ge60_259es_i5_3210_8gb_750gb_gtx660m_15_6__290_290.jpg"
					title="Producto de prueba" alt="Producto de prueba" /></a>
				<p>89.95&euro;</p>
				<p>
					<a href="#">LG 42LS3400 42" LED - Televisión</a>
				</p>
				<div></div>
				<p>En stock</p>
			</div>

			<div class="box">
				<a href="#"><img src="images/products/notebook_cooler_ergostand.jpg"
					title="Producto de prueba" alt="Producto de prueba" /></a>
				<p>44&euro;</p>
				<p>
					<a href="#">LG 42LS3400 42" LED - Televisión</a>
				</p>
				<div></div>
				<p>En stock</p>
			</div>

			<h2>Novedades</h2>

			<div class="box">
				<a href="#"><img src="images/products/salicru_sps_one_700va_sai.jpg"
					title="Producto de prueba" alt="Producto de prueba" /></a>
				<p>9111&euro;</p>
				<p>
					<a href="#">LG 42LS3400 42" LED - Televisión</a>
				</p>
				<div></div>
				<p>En stock</p>
			</div>

			<div class="box">
				<a href="#"><img
					src="images/products/sony_xperia_t_black_libre_290_290.jpg"
					title="Producto de prueba" alt="Producto de prueba" /></a>
				<p>0.35&euro;</p>
				<p>
					<a href="#">LG 42LS3400 42" LED - Televisión</a>
				</p>
				<div></div>
				<p>En stock</p>
			</div>
			<div class="box">
				<a href="#"><img
					src="images/products/hp_officejet_pro_8600_multifuncion_fax_wifi_290_290.jpg"
					title="Producto de prueba" alt="Producto de prueba" /></a>
				<p>9&euro;</p>
				<p>
					<a href="#">LG 42LS3400 42" LED - Televisión</a>
				</p>
				<div></div>
				<p>En stock</p>
			</div>



		</div>
	</div>


	<div id="footer">
		<p>Esto es una página de prueba, todos los contenidos son ficticios.
			Usala bajo tu propia responsabilidad.</p>
		<p>Javier García Rodríguez.</p>
		<p>Última actualización 25/04/2013</p>
	</div>


	<script>
		// Can also be used with $(document).ready()
		$(window).load(function() {
			$('.flexslider').flexslider({
				animation: "slide",
				controlNav : false,
			});
		});
	</script>

</body>
</html>