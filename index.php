<!DOCTYPE html>
<html lang="en">

<?php
include ("variables.php");

include ("funciones.php");

global $servidor, $usuario, $contrasena, $basedatos;

// Obtener las tablas de la base de datos

$sentenciaSQL = "SELECT * FROM `canales`";
$registroCanales = ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
$contadorCanales = count($registroCanales);

$sentenciaSQL = "SELECT * FROM `items`";
$registroItems = ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
$contadorItems = count($registroItems);

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

$sentenciaSQL = "SELECT * FROM `categorias`";
$registroCategorias = ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
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

function loadXML($RSSUrl)
{
    if (@simplexml_load_file($RSSUrl)) {
        $feeds = simplexml_load_file($RSSUrl);
    } else {
        $feeds = null;
        echo "Invalid RSS URL";
    }
    return $feeds;
}

function generateItem($siteImg, $itemTitle, $itemLink, $itemDescription, $itemCategories, $itemDate)
{
    $categoryList = "";
    foreach ($itemCategories as $category) {
        $categoryList .= $category . "<hr>";
    }

    $itemHTML = '<div class="col-lg-3 col-md-6 mb-4">' . '<div class="card h-100">' . '<img class="card-img-top" src="' . $siteImg . '" alt="">' . '<div class="card-body">' . '<h4 class="card-title">' . $itemTitle . '</h4>' . '<p class="card-text">' . $itemDescription . '</p>' . '</div>' . '<div class="card-text">' . $itemDate . '</div>' . '<div class="card-text">' . '<a href="">' . $itemLink . '</a>' . '</div>' . '<div class="card-footer">' . 'Categorías <h6><hr/>' . $categoryList . '</h6>' . '</div>' . '<div class="card-footer">' . '<a href="#" class="btn btn-primary">Ver actualizaciones</a>' . '</div>' . '</div>' . '</div>';

    echo $itemHTML;
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
					<input type="text" name="RSSUrl" class="form-control"
						placeholder="http://feeds.bbci.co.uk/news/world/rss.xml"
						aria-label="Recipient's username" aria-describedby="button-addon2">
					<input type="submit" name="submit" value="Ingresar"
						class="btn btn-outline-primary">
				</div>
			</form>
		</div>


		<!-- Page Features -->
		<div class="row text-center" id="ItemsContainer">

      <?php

    // Se cargan nuevamente los canales por si se agrego uno nuevo

    $sentenciaSQL = "SELECT * FROM `canales`";
    $registroCanales = ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
    $contadorCanales = count($registroCanales);

    // Fin de recarga

    $insercionFallida = false;
    if (isset($_POST['submit'])) {
        $counter = 0;
        foreach ($feeds->channel->item as $item) {
            $itemTitle = $item->title;
            $itemLink = $item->link;
            $itemDescription = filter_var ( $item->description, FILTER_SANITIZE_STRING);
            $itemCategories = $item->category;
            $itemDate = date("Y-m-d", strtotime($item->pubDate));

            // Insertar noticia a la base de datos

            $noticiaRepetida = false;
            $categoriaRepetida = false;

            for ($j = 0; $j < $contadorItems; $j ++) {
                if ($registroItems[$j]["Titulo"] == $itemTitle) {
                    $noticiaRepetida = true;
                    break;
                }
            }

            if (!$noticiaRepetida) {
                $idCanal = 0;
                for ($j = 0; $j < $contadorCanales; $j ++) {
                    if ($registroCanales[$j]["NombreCanal"] == $siteTitle) {
                        $idCanal = $registroCanales[$j]["IdCanal"];
                        break;
                    }
                }

                $conexion = mysqli_connect($servidor, $usuario, $contrasena, $basedatos);
                if (! $conexion) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                $sentenciaSQL = "INSERT INTO `items` (`IdNoticia`, `IdCanal`, `Titulo`, `itemLink`, `Descripcion`, `Fecha`)
     VALUES (NULL, '" . $idCanal . "', '" . $itemTitle . "', '" . $itemLink . "' , '" . $itemDescription . "' , '" . $itemDate . "')";
                if (mysqli_query($conexion, $sentenciaSQL)) {} else {
                    $insercionFallida = true;
                    // echo "Error: " . $sentenciaSQL . "<br>" . mysqli_error($conexion); Si sale algun error aleatorio en la insercion, se deja esta bandera para el manejo de error
                }

                mysqli_close($conexion);
                
                // Lo de categorias
                
                foreach ($itemCategories as $category) {
                    for ($j = 0; $j < $contadorCategorias; $j ++) {
                        if ($registroCategorias[$j]["NombreCategoria"] == $category) {
                            $categoriaRepetida = true;
                            break;
                        }
                    }
                    if(!$categoriaRepetida){
                        // Insertar categoria a la base de datos
                        
                        $conexion = mysqli_connect($servidor, $usuario, $contrasena, $basedatos);
                        if (! $conexion) {
                            die("Connection failed: " . mysqli_connect_error());
                        }
                        $sentenciaSQL = "INSERT INTO `categorias` (`IdCategoria`, `NombreCategoria`)
                                          VALUES (NULL, '" . $category . "')";
                        if (mysqli_query($conexion, $sentenciaSQL)) {} else {
                            // echo "Error: " . $sentenciaSQL . "<br>" . mysqli_error($conexion); Si sale algun error aleatorio en la insercion, se deja esta bandera para el manejo de error
                        }
                        
                        mysqli_close($conexion);
                        
                        // Fin de insertar categoria
                    }
                    $categoriaRepetida = false;
                }
                
                // Relacionar categorias con noticias
                
                // Se cargan nuevamente las noticias y categorias por si se agrego una nueva
                
                $sentenciaSQL = "SELECT * FROM `items`";
                $registroItems = ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
                $contadorItems = count($registroItems);
                
                $sentenciaSQL = "SELECT * FROM `categorias`";
                $registroCategorias = ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
                $contadorCategorias = count($registroCategorias);
                
                // Fin de recarga
                $idNoticia = 0;
                for ($j = 0; $j < $contadorItems; $j ++) {
                    if ($registroItems[$j]["Titulo"] == $itemTitle) {
                        $idNoticia = $registroItems[$j]["IdNoticia"];
                        break;
                    }
                }
                
                foreach ($itemCategories as $category) {
                    for ($j = 0; $j < $contadorCategorias; $j ++) {
                        if ($registroCategorias[$j]["NombreCategoria"] == $category) {
                            $idCategoria = $registroCategorias[$j]["IdCategoria"];
                            // Insertar relacion a la base de datos
                            
                            $conexion = mysqli_connect($servidor, $usuario, $contrasena, $basedatos);
                            if (! $conexion) {
                                die("Connection failed: " . mysqli_connect_error());
                            }
                            $sentenciaSQL = "INSERT INTO `categorizacion` (`IdRelacion`,`IdNoticia`, `IdCategoria`)
                                          VALUES (NULL,'" . $idNoticia . "', '" . $idCategoria . "')";
                            if (mysqli_query($conexion, $sentenciaSQL)) {} else {
                                // echo "Error: " . $sentenciaSQL . "<br>" . mysqli_error($conexion); Si sale algun error aleatorio en la insercion, se deja esta bandera para el manejo de error
                            }
                            
                            mysqli_close($conexion);
                            
                            // Fin de insertar relacion
                            break;
                        }
                    }
                }
                
                // Fin de relacionar
                
                // Fin de lo de categorias
            }

            // Fin de insertar noticia en la base de datos

            if ($counter >= 3) {
                break;
            }
            $counter ++;
        }

        if (! $insercionFallida) {
            
            // Se cargan nuevamente las noticias y categorizacion por si se agrego una nueva
            
            $sentenciaSQL = "SELECT * FROM `items` ORDER BY Fecha DESC";
            $registroItems = ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
            $contadorItems = count($registroItems);
            
            $sentenciaSQL = "SELECT * FROM `categorizacion`";
            $registroCategorizacion = ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
            $contadorCategorizacion = count($registroCategorizacion);
            
            $sentenciaSQL = "SELECT * FROM `categorias`";
            $registroCategorias = ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
            $contadorCategorias = count($registroCategorias);
            
            // Fin de recarga
            
            // Crear los obejtos de notcias
            $imagenItem = "";
            for ($j = 0; $j < $contadorItems; $j ++) {
              $arrayCategories = array("");
              for ($i = 0; $i < $contadorCanales; $i ++) {
                if ($registroCanales[$i]["IdCanal"] == $registroItems[$j]["IdCanal"]) {
                  
                  $imagenItem = $registroCanales[$i]["SiteImg"];
                  break;
                }
              }
                
              for ($i = 0; $i < $contadorCategorizacion; $i ++) {
                  if ($registroCategorizacion[$i]["IdNoticia"] == $registroItems[$j]["IdNoticia"]) {
                      for ($f = 0; $f < $contadorCategorias; $f ++) {
                          if ($registroCategorias[$f]["IdCategoria"] == $registroCategorizacion[$i]["IdCategoria"]) {
                              $categoriaActual = $registroCategorias[$f]["NombreCategoria"];
                              array_push($arrayCategories, $categoriaActual);
                              break;
                          }
                      }
                  }
              }
              generateItem($imagenItem, $registroItems[$j]["Titulo"], $registroItems[$j]["itemLink"], $registroItems[$j]["Descripcion"], $arrayCategories, $registroItems[$j]["Fecha"]);
          }
        } else {
            print "Fuente de noticias no soportada.";
        }
    }

    // Fin de generar los objetos de noticias

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
