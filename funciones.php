<?php

function ReadChannels ($servidor, $usuario, $contrasena, $basedatos) {
	$sentenciaSQL = "SELECT * FROM `canales`";
	return ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
}

function ReadItems ($servidor, $usuario, $contrasena, $basedatos) {
	$sentenciaSQL = "SELECT * FROM `items`";
	return ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
}

function ReadCategories ($servidor, $usuario, $contrasena, $basedatos) {
	$sentenciaSQL = "SELECT * FROM `categorias`";
	return ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
}

function ReadCategorizacion ($servidor, $usuario, $contrasena, $basedatos) {
	$sentenciaSQL = "SELECT * FROM `categorizacion`";
	return ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
}

function GetChannelImage ($idCanalNoticia,$servidor, $usuario, $contrasena, $basedatos) {
	$sentenciaSQL = "SELECT SiteImg FROM canales WHERE IdCanal=$idCanalNoticia";
	return ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL)[0]["SiteImg"];
}

function LeerArchivoSELECT($seleccion) {
	
	$lineas = file("areas.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	foreach ($lineas as $linea){
		if (strtolower ($linea) == strtolower ($seleccion)){
			echo "<option value=\"{$linea}\" selected>{$linea}</option>\n";
		}else{
			echo "<option value=\"{$linea}\">{$linea}</option>\n";
		}
	}
		
}

function EjecutarSQL ($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL) {

	$conexion = mysqli_connect($servidor, $usuario, $contrasena, $basedatos);
	if (!$conexion) {
    	die("Fallo: " . mysqli_connect_error());
	}

	$resultado = mysqli_query($conexion, $sentenciaSQL);
	mysqli_close($conexion);

}

function ConsultarSQL ($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL) {

	$conexion = mysqli_connect($servidor, $usuario, $contrasena, $basedatos);
	if (!$conexion) {
    	die("Fallo: " . mysqli_connect_error());
	}

	$resultado = mysqli_query($conexion, $sentenciaSQL);
	
	for ($registros = array (); $fila = mysqli_fetch_assoc($resultado); $registros[] = $fila);	
	
	mysqli_close($conexion);
	
	return $registros;

}

function loadXML($RSSUrl) {
    if (@simplexml_load_file($RSSUrl)) {
        $feeds = simplexml_load_file($RSSUrl);
    } else {
        $feeds = null;
        echo "Invalid RSS URL";
    }
    return $feeds;
}

function generateAllItems ($arrayItems) {
	$allItems = "";
	foreach ($arrayItems as $item) {
		$allItems .= generateItem($item);
	}
	return $allItems;
}

function generateItem($item) {
    $categoryList = "";
    foreach ($item["Categories"] as $category) {
        $categoryList .= $category . "<hr>";
    }

    $itemHTML = 
	'<div class="col-lg-3 col-md-6 mb-4">' . 
	'<div class="card h-100">' . 
	'<img class="card-img-top" src="' . 
	$item["Image"] . 
	'" alt="">' . 
	'<div class="card-body">' . 
	'<h4 class="card-title">' . 
	$item["Title"] . 
	'</h4>' . 
	'<p class="card-text">' . 
	$item["Description"] . 
	'</p>' . 
	'</div>' . 
	'<div class="card-text">' . 
	$item["Date"] . 
	'</div>' . 
	'<div class="card-text">' . 
	'<a href="">' . 
	$item["Link"] . 
	'</a>' . 
	'</div>' . 
	'<div class="card-footer">' . 
	'Categorías <h6><hr/>' . 
	$categoryList . 
	'</h6>' . 
	'</div>' . 
	'<div class="card-footer">' . 
	'<a href="#" class="btn btn-primary">Ver actualizaciones</a>' . 
	'</div>' . 
	'</div>' . 
	'</div>';

    return $itemHTML;
}

/*function generateItem($siteImg, $itemTitle, $itemLink, $itemDescription, $itemCategories, $itemDate) {
    $categoryList = "";
    foreach ($itemCategories as $category) {
        $categoryList .= $category . "<hr>";
    }

    $itemHTML = 
	'<div class="col-lg-3 col-md-6 mb-4">' . 
	'<div class="card h-100">' . 
	'<img class="card-img-top" src="' . 
	$siteImg . 
	'" alt="">' . 
	'<div class="card-body">' . 
	'<h4 class="card-title">' . 
	$itemTitle . 
	'</h4>' . 
	'<p class="card-text">' . 
	$itemDescription . 
	'</p>' . 
	'</div>' . 
	'<div class="card-text">' . 
	$itemDate . 
	'</div>' . 
	'<div class="card-text">' . 
	'<a href="">' . 
	$itemLink . 
	'</a>' . 
	'</div>' . 
	'<div class="card-footer">' . 
	'Categorías <h6><hr/>' . 
	$categoryList . 
	'</h6>' . 
	'</div>' . 
	'<div class="card-footer">' . 
	'<a href="#" class="btn btn-primary">Ver actualizaciones</a>' . 
	'</div>' . 
	'</div>' . 
	'</div>';

    return $itemHTML;
}*/

	// Consultas para ordenamiento

	function orderItemsByDate ($servidor, $usuario, $contrasena, $basedatos) {
		$sentenciaSQL = "SELECT * FROM `items` ORDER BY Fecha DESC";
		return ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
	}

	function orderItemsByTitle ($servidor, $usuario, $contrasena, $basedatos) {
		$sentenciaSQL = "SELECT * FROM `items` ORDER BY Titulo";
		return ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
	}

	function orderItemsByUrl ($servidor, $usuario, $contrasena, $basedatos) {
		$sentenciaSQL = "SELECT * FROM `items` ORDER BY itemLink";
		return ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
	}

	function orderItemsByDescription ($servidor, $usuario, $contrasena, $basedatos) {
		$sentenciaSQL = "SELECT * FROM `items` ORDER BY Descripcion";
		return ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
	}

?>