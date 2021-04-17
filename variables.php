<?php
    $servidor = "localhost";
    $usuario = "root";
    $contrasena = "";
    $basedatos = "noticias_opt";
    
    $queryByDate = "SELECT * FROM `items` ORDER BY Fecha DESC";
    $queryByTitle = "SELECT * FROM `items` ORDER BY Titulo";
    $queryByUrl = "SELECT * FROM `items` ORDER BY itemLink";
    $queryByDescription = "SELECT * FROM `items` ORDER BY Descripcion";
?>