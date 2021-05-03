<!DOCTYPE html>
<html lang="en">

<?php
	include ("variables.php");

	include ("funciones.php");

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
		}

		// Fin de insertar canal en la base de datos
	}
	
?>

<head>

<meta charset="utf-8">
<meta name="viewport"
	content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>Lector RSS</title>
<link href="./public/assets/favicon.png" rel="icon" type="image/png">

<!-- Bootstrap core CSS -->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="css/heroic-features.css" rel="stylesheet">

</head>

<body style=" background-image: url('./public/assets/background.jpg'); background-attachment: fixed; ">

	<?php include("components/header.php") ?>

	<!-- Page Content -->
	<div class="container">

		<!-- Jumbotron Header -->
		<?php include("components/jumbotron.php") ?>

		<!-- Urls Input -->
		<div class="container">
			<p class="text-center">
				<strong>Ingresa la Url de donde desees recibir sus actualizaciones:</strong>
			</p>
			<form method='post' action='' class="input-group-append">
				<div class="input-group mb-3">
					<input 
						type="text" 
						name="RSSUrl" 
						class="form-control"
						placeholder="http://feeds.bbci.co.uk/news/world/rss.xml"
					>
					<input 
						type="submit" 
						name="submit" 
						value="Ingresar"
						class="btn btn-primary"
					>
				</div>
			</form>
		</div>

		<hr/>

		<!-- Search Input Words -->
		<div class="container">
			<form action="" method="post" class="input-group-append">
				<div class="input-group mb-3">
					<label 
						class="col-sm-auto control-label" 
						for=""
					>
						<strong><img src="./public/assets/search-icon.png"/> - Buscar noticias:</strong>
					</label>
					<input 
						class="form-control" 
						name="search"
						placeholder="downtown"
						type="text" 
					>
					<input 
						class="btn btn-outline-primary" 
						type="submit" 
						value="Buscar"
					>
				</div>
			</form>
			<div class="container mb-3">
				<form action="" method="post">
					<label 
						class="col-sm-auto control-label" 
						for=""
					>
						<img src="./public/assets/sort-icon.png"/>
						<strong> - Ordenar por:</strong>
					</label>
					<button class="btn btn-secondary" type="submit" name="byDate">Fecha</button>
					<button class="btn btn-secondary" type="submit" name="byTitle">Título</button>
					<button class="btn btn-secondary" type="submit" name="byUrl">URL</button>
					<button class="btn btn-secondary" type="submit" name="byDescription">Descripción</button>
				</form>
			</div>
		</div>

		<!-- Page Features -->
		<div class="row text-center" id="ItemsContainer">

			<?php
				include("CargaBD.php");
			?>

    	</div>
		<!-- /.row -->
	</div>
	<!-- /.container -->

	<!-- Footer -->
	<?php include("components/footer.php") ?>

	<!-- Bootstrap core JavaScript -->
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
