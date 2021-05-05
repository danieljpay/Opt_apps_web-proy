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
		'<a class="btn btn-primary" href=' . $item["Link"] . ' target="_blank">Leer noticia</a>' . 
		'</div>' . 
		'</div>' . 
		'</div>';

		return $itemHTML;
	}

	//Función que genera todas las tarjetas de las noticias en Index.php
	function loadItemsFromDB($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL) {
        return generateAllItems(getAllNewsFromDataBase($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL));
	}

	function searchItemsByTitle($servidor, $usuario, $contrasena, $basedatos, $registroItems, $keyword) {
        $sentenciaSQL = "SELECT * FROM items WHERE Titulo LIKE '%$keyword%' ORDER BY Titulo ASC";
        $itemsFound = ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
        $itemsFoundGenerated = array();
        $counterCategoria = 0;
        foreach ($itemsFound as $item) {
            $idNoticiaBusqueda = $registroItems[$counterCategoria]["IdNoticia"];
            $idCanalBusqueda = $registroItems[$counterCategoria]["IdCanal"];
            $NewSiteImg = GetChannelImage($idCanalBusqueda,$servidor, $usuario, $contrasena, $basedatos);

            $sentenciaSQL = "SELECT NombreCategoria FROM categorizacion INNER JOIN categorias ON categorizacion.IdCategoria=categorias.IdCategoria WHERE categorizacion.IdNoticia=$idNoticiaBusqueda";
            $busquedaCategorias = ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
            
            $categoriasActuales = array();
            foreach ($busquedaCategorias as $categoria) {
                array_push($categoriasActuales,$categoria["NombreCategoria"]);
            }

            $currentItem = array (
                "Image" => $NewSiteImg,
                "Title" => $item["Titulo"],
                "Link" => $item["itemLink"],
                "Description" => $item["Descripcion"],
                "Date" => $item["Fecha"],
                "Categories" => $categoriasActuales
            );
            array_push($itemsFoundGenerated, $currentItem);
            $counterCategoria++;
        }

        return generateAllItems($itemsFoundGenerated);
	}

	//Función que carga las noticias de la base de datos y devuelve un array de Items(Noticias)
	function getAllNewsFromDataBase ($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL) {
		$registroItems = ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
        $contadorItems = count($registroItems);

        $registroCategorizacion = ReadCategorizacion ($servidor, $usuario, $contrasena, $basedatos);
        $contadorCategorizacion = count($registroCategorizacion);
        // Fin de recarga
        
        // Crear los obejtos de notcias
        $imagenItem = "";
        $arrayItems = array();
        $itemsFoundGenerated = array();

        for ($j = 0; $j < $contadorItems; $j ++) {
			$arrayCategories = array("");

			$NewSiteImg = GetChannelImage($registroItems[$j]["IdCanal"], $servidor, $usuario, $contrasena, $basedatos);
            
			$idNoticiaBusqueda = $registroItems[$j]["IdNoticia"];
			$sentenciaSQL = "SELECT NombreCategoria FROM categorizacion INNER JOIN categorias ON categorizacion.IdCategoria=categorias.IdCategoria WHERE categorizacion.IdNoticia=$idNoticiaBusqueda";
            $categories = ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);

			$categoriasActuales = array();
            foreach ($categories as $categoria) {
                array_push($categoriasActuales, $categoria["NombreCategoria"]);
            }
          
			$currentItem = array (
				"Image" => $NewSiteImg,
				"Title" => $registroItems[$j]["Titulo"],
				"Link" => $registroItems[$j]["itemLink"],
				"Description" => $registroItems[$j]["Descripcion"],
				"Date" => $registroItems[$j]["Fecha"],
				"Categories" => $categoriasActuales,
				"ChannelID" => $registroItems[$j]["IdCanal"]
			);

          	array_push($arrayItems, $currentItem);
        }
		return $arrayItems;
	}

	function getMatrixNewsByChannel ($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL) {
		$allNews = getAllNewsFromDataBase($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);

		//newsMatrixByChannel guarda todas las noticias ordenadamente
		//Se accede con la estructura $newsMatrixByChannel[Canal][Noticia] 
		$newsMatrixByChannel = array();
		foreach ($allNews as $new) {
			$newsMatrix[$new["ChannelID"]][] = $new;
		}

		return $newsMatrix;
	}
	
?>