<?php
// Se cargan nuevamente los canales por si se agrego uno nuevo

$registroCanales = ReadChannels($servidor, $usuario, $contrasena, $basedatos);
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

            $registroItems = ReadItems ($servidor, $usuario, $contrasena, $basedatos);
            $contadorItems = count($registroItems);

            $registroCategorias = ReadCategories ($servidor, $usuario, $contrasena, $basedatos);
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
        
        $sentenciaSQL = "SELECT * FROM items ORDER BY Fecha DESC";
        $registroItems = ConsultarSQL($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL);
        $contadorItems = count($registroItems);

        $registroCategorizacion = ReadCategorizacion ($servidor, $usuario, $contrasena, $basedatos);
        $contadorCategorizacion = count($registroCategorizacion);

        $registroCategorias = ReadCategories ($servidor, $usuario, $contrasena, $basedatos);
        $contadorCategorias = count($registroCategorias);
        
        // Fin de recarga
        
        // Crear los obejtos de notcias
        $imagenItem = "";
        $arrayItems = array();
        $itemsFoundGenerated = array();

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
          
          $currentItem = array (
            "Image" => $imagenItem,
            "Title" => $registroItems[$j]["Titulo"],
            "Link" => $registroItems[$j]["itemLink"],
            "Description" => $registroItems[$j]["Descripcion"],
            "Date" => $registroItems[$j]["Fecha"],
            "Categories" => $arrayCategories
          );

          array_push($arrayItems,$currentItem);
        }
        

        //TODO ESTO ES DE LA BUSQUEDA ---------------------------------------

        $keyword = "A";
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
            array_push($itemsFoundGenerated,$currentItem);
            $counterCategoria++;
        }
        // TERMINA SECCION DE LA BUSQUEDA -------------------------------------------------------------------------

        //ESTE ECHO ES PARA IMPRIMIR, PUEDES ELEGIR ENTRE arrayItems (Todos) y itemsFoundGenerated (Busqueda)
        echo generateAllItems($arrayItems);
    } else {
        print "Fuente de noticias no soportada.";
    }
}
// Fin de generar los objetos de noticias
?>