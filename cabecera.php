<!DOCTYPE html>
<html>
<head>
	<meta charset="ISO-8859-1">
	<title><?=EMPRESA . ' - ' . $regMem->getValor('titulo')?></title>
	<link rel="shortcut icon" type="image/x-icon"
	href="http://localhost/practicas/amazing-components/favicon.ico" />

	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.fancybox.pack.js"></script>
	<script type="text/javascript" src="js/jquery.flexslider-min.js"></script>

	<link rel="stylesheet" href="css/main.css" type="text/css" title="main" />
	<link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" media="screen" />
	<link href="css/flexslider.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div id="wrapper">
		<div id="header">
			<div>
				<a href="index.php"><img src="images/logo1.png" alt="Logo amazing-components.com" /></a>
			</div>
			<div>
				<a href="<?=URL_BANNER?>"><img src="images/banners/<?=IMG_BANNER?>" title="<?=MSG_BANNER?>" alt="<?=MSG_BANNER?>" /></a>
			</div>
		</div>