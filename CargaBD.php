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
            
            echo loadItemsFromBD($servidor, $usuario, $contrasena, $basedatos, 
                                $registroCanales, $contadorCanales, $queryByDate);
            
        } else {
            print "Fuente de noticias no soportada.";
        }
    }
    // Fin de generar los objetos de noticias

    // **************************************************************** ordenamiento ****************************************************************

    if(isset($_POST['byDate'])) {
        echo loadItemsFromBD($servidor, $usuario, $contrasena, $basedatos, 
                            $registroCanales, $contadorCanales, $queryByDate);
    }

    if(isset($_POST['byTitle'])) {
        echo loadItemsFromBD($servidor, $usuario, $contrasena, $basedatos, 
                            $registroCanales, $contadorCanales, $queryByTitle);
    }

    if(isset($_POST['byUrl'])) {
        echo loadItemsFromBD($servidor, $usuario, $contrasena, $basedatos, 
                            $registroCanales, $contadorCanales, $queryByUrl);
    }

    if(isset($_POST['byDescription'])) {
        echo loadItemsFromBD($servidor, $usuario, $contrasena, $basedatos, 
                            $registroCanales, $contadorCanales, $queryByDescription);
    }

    // ***************************************************************** busqueda  ****************************************************************
    if(isset($_POST['search'])) {
        echo searchItemsByTitle($servidor, $usuario, $contrasena, $basedatos,
                                $registroItems, $_POST['search']);
    }

    //************************************************************* actualizar la pagina *****************************************
    if(isset($_POST['refresh'])){
        foreach ($registroCanales as $canal) {
            $feedsCanal = loadXML($canal["Feed"]);
            $counter = 0;
            $siteTitle = $feedsCanal->channel->title;
            foreach ($feedsCanal->channel->item as $item) {
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
                    $idCanal = $canal["IdCanal"];
                    
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
        }
        echo loadItemsFromBD($servidor, $usuario, $contrasena, $basedatos,
                            $registroCanales, $contadorCanales, $queryByDate);
    }
?>