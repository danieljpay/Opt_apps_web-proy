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

	//echo $registroItems[1]["Titulo"];
	/*
	* Ejemplo de busqueda
		$nomBusqueda = (string)$_GET["buscar"];
		$keyword = trim ($nomBusqueda);
		$sentenciaSQL = "SELECT campos que requieran aqui FROM tabla de la bd WHERE columna de busqueda LIKE '%$keyword%' ORDER BY columna para ordenar ASC o DESC";
		$sentenciaSQL = "SELECT *  FROM `items` WHERE `Titulo` LIKE '%$keyword%' ORDER BY `Titulo` ASC";
		$registros = ConsultarSQL ($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
		$contador = count($registros);
	*/



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

<!-- Bootstrap core CSS -->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="css/heroic-features.css" rel="stylesheet">

</head>

<body>

	<!-- Navigation -->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
		<div class="container">
			<a class="navbar-brand" href="#">Feed RSS</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse"
				data-target="#navbarResponsive" aria-controls="navbarResponsive"
				aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarResponsive">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item active"><a class="nav-link" href="#">Home <span
							class="sr-only">(current)</span>
					</a></li>
					<li class="nav-item"><a class="nav-link" href="#">About</a></li>
					<li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<!-- Page Content -->
	<div class="container">

		<!-- Jumbotron Header -->
		<header class="jumbotron my-4">
			<h1 class="display-3">¡Bienvenido a nuestro feed RSS!</h1>
			<p class="lead">En este sitio podrás agregar las urls de las
				páginas, blogs, videos de los que quieras estar pendientes de sus
				actualizaciones.</p>
			<a href="#" class="btn btn-primary btn-lg">Actualizar</a>
		</header>

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
						<strong>Buscar noticias:</strong>
					</label>
					<input 
						type="text" 
						class="form-control" 
						placeholder="downtown"
					>
					<input 
						class="btn btn-outline-primary" 
						name="search"
						type="submit" 
						value="Buscar"
					>
				</div>
			</form>
			<div class="container mb-3">
			<form action="" method="post">
				<label class="col-sm-auto control-label" for=""><strong>Ordenar por:</strong></label>
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
	<footer class="py-5 bg-dark">
		<div class="container">
			<p class="m-0 text-center text-white">by Jorge A. Chi, Jimmy N. Ojeda
				y Daniel J. Pérez &copy;</p>
		</div>
		<!-- /.container -->
	</footer>

	<!-- Bootstrap core JavaScript -->
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
