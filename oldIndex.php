<!DOCTYPE html>
<html lang="en">

<?php
	include("variables.php");
	include("funciones.php");

	global $servidor, $usuario, $contrasena, $basedatos;
	// Obtener las tablas de la base de datos
	$registroCanales = ReadChannels($servidor, $usuario, $contrasena, $basedatos);
	$contadorCanales = count($registroCanales);
	$registroItems = ReadItems ($servidor, $usuario, $contrasena, $basedatos);
	$contadorItems = count($registroItems);
	$registroCategorias = ReadCategories ($servidor, $usuario, $contrasena, $basedatos);
	$contadorCategorias = count($registroCategorias);
	// Fin de obtener las tablas de la base de datos

	if (isset($_POST['submit']) && $_POST['RSSUrl'] != '') {
		$RSSUrl = $_POST['RSSUrl'];
		$feeds = loadXML($RSSUrl);
		if ($feeds != null && ! empty($feeds)) {
			$siteTitle = $feeds->channel->title;
			$siteLink = $feeds->channel->link;
			$siteImg = $feeds->channel->image->url;
		}
		// Insertar canal a la base de datos
		$canalRepetido = false;
		for ($j = 0; $j < $contadorCanales; $j ++) {
			if ($registroCanales[$j]["NombreCanal"] == $siteTitle) {
				$canalRepetido = true;
				break;
			}
		}
		if (! $canalRepetido) {
			$conexion = mysqli_connect($servidor, $usuario, $contrasena, $basedatos);
			if (! $conexion) {
				die("Connection failed: " . mysqli_connect_error());
			}
			$sentenciaSQL = "INSERT INTO `canales` (`IdCanal`, `URL`, `NombreCanal`, `SiteImg`, Feed)
		VALUES (NULL, '" . $siteLink . "', '" . $siteTitle . "', '" . $siteImg . "', '" . $RSSUrl . "')";
			if (mysqli_query($conexion, $sentenciaSQL)) {} else {
				echo "Error: " . $sentenciaSQL . "<br>" . mysqli_error($conexion);
			}
			mysqli_close($conexion);
		}	// Fin de insertar canal en la base de datos
	}
?>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description">
	<meta name="author">
	<title>Lector RSS</title>
	<link href="./public/assets/favicon.png" rel="icon" type="image/png">
	<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="css/heroic-features.css" rel="stylesheet">
</head>
<body 
	style="background-image: url('./public/assets/background.png');
		background-attachment: fixed;"
>
	<?php include("components/Header.html") ?>
	<div class="container">
		<?php 
			include("components/Jumbotron.html");
			include("components/UrlsInput.html"); 
			echo '<hr/>';
			include("components/Search.html");
		?>
		<div class="row text-center" id="ItemsContainer">
			<?php include("CargaBD.php");?>
    	</div>
	</div>
	<?php include("components/Footer.html") ?>
	<script defer src="vendor/jquery/jquery.min.js"></script>
	<script defer src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
