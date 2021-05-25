<!doctypehtml><html lang=en><?php 
    include("variables.php");
    include("funciones.php");
?><meta charset=UTF-8><meta name=viewport content="width=device-width,initial-scale=1,shrink-to-fit=no"><meta name=description><meta name=author><title>Explorador de noticias</title><link href=./public/assets/favicon.png rel=icon type=image/png><link href=https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css rel=stylesheet crossorigin=anonymous integrity=sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6><link href=css/heroic-features.css rel=stylesheet><body style=background-image:url(public/assets/background.png);background-attachment:fixed><?php 
        include("componentsMinimized/Header.min.html");
        generateNewsCardAccordion($servidor, $usuario, $contrasena, $basedatos, $queryByDate);
        include("componentsMinimized/Footer.min.html");
    ?><script crossorigin=anonymous defer integrity=sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG src=https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js></script><script crossorigin=anonymous defer integrity=sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc src=https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js></script>