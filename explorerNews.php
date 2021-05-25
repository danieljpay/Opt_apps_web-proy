<!DOCTYPE html>
<html lang="en">

<?php 
    include("variables.php");
    include("funciones.php");
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
    <title>Explorador de noticias</title>
    <link href="./public/assets/favicon.png" rel="icon" type="image/png">

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="css/heroic-features.css" rel="stylesheet">
</head>
<body 
    style="background-image: url('./public/assets/background.png');
        background-attachment: fixed;"
>

    <?php 
        include("components/Header.html");

        generateNewsCardAccordion($servidor, $usuario, $contrasena, $basedatos, $queryByDate);

        include("components/Footer.html");
    ?>

    <!-- JavaScript Bundle with Popper -->
    <script defer src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
</body>
</html>