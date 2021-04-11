<!DOCTYPE html>
<html lang="en">

<?php 
  if (isset($_POST['submit']) && $_POST['RSSUrl'] != '') {
    $RSSUrl = $_POST['RSSUrl'];
    $feeds = loadXML($RSSUrl);

    if ($feeds != null && !empty($feeds)) {
      $siteTitle = $feeds->channel->title;
      $siteLink = $feeds->channel->link;
      $siteImg = $feeds->channel->image->url;
    }
  }

  function loadXML ($RSSUrl){
    if (@simplexml_load_file($RSSUrl)) {
      $feeds = simplexml_load_file($RSSUrl);
    }else {
      $feeds = null;
      echo "Invalid RSS URL";
    }
    return $feeds;
  }

  function generateItem ($siteImg,$itemTitle,$itemLink,$itemDescription,$itemCategories,$itemDate) {
    $categoryList = "";
    foreach ($itemCategories as $category) {
      $categoryList .= $category . "<hr>";
    }
    
    $itemHTML = '<div class="col-lg-3 col-md-6 mb-4">'.
      '<div class="card h-100">'.
        '<img class="card-img-top" src="'. $siteImg . '" alt="">'.
        '<div class="card-body">'.
          '<h4 class="card-title">' . $itemTitle . '</h4>'.
          '<p class="card-text">' . $itemDescription . '</p>'.
        '</div>'.
        '<div class="card-text">'.
          $itemDate .
        '</div>'.
        '<div class="card-text">'.
          '<a href="">' . $itemLink . '</a>'.
        '</div>'.
        '<div class="card-footer">'.
          'Categorías <h6><hr/>' . $categoryList . '</h6>'.
        '</div>'.
        '<div class="card-footer">'.
          '<a href="#" class="btn btn-primary">Ver actualizaciones</a>'.
        '</div>'.
      '</div>'.
    '</div>';

    echo $itemHTML;
  }

?>

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container">

    <!-- Jumbotron Header -->
    <header class="jumbotron my-4">
      <h1 class="display-3">¡Bienvenido a nuestro feed RSS!</h1>
      <p class="lead">En este sitio podrás agregar las urls de las páginas, blogs, videos de los que quieras estar pendientes de sus actualizaciones.</p>
      <a href="#" class="btn btn-primary btn-lg">Actualizar</a>
    </header>

    <!-- Urls Input -->
    <div class="container">
      <p class="text-center"><strong>Ingresa la Url de donde desees recibir sus actualizaciones:</strong></p>
        <form method='post' action='' class="input-group-append">
          <div class="input-group mb-3">
            <input type="text" name="RSSUrl" class="form-control" placeholder="http://feeds.bbci.co.uk/news/world/rss.xml" aria-label="Recipient's username" aria-describedby="button-addon2">
            <input type="submit" name="submit"  value="Ingresar" class="btn btn-outline-primary">
          </div>
        </form>
    </div>
    

    <!-- Page Features -->
    <div class="row text-center" id="ItemsContainer">

      <?php
        if (isset($_POST['submit'])){
          $counter = 0;
          foreach ($feeds->channel->item as $item) {
            $itemTitle = $item->title;
            $itemLink = $item->link;
            $itemDescription = $item->description;
            $itemCategories = $item->category;
            $itemDate = date('D, d M Y' ,strtotime($item->pubDate));
            generateItem($siteImg,$itemTitle,$itemLink,$itemDescription,$itemCategories,$itemDate);
            if ($counter >= 3) {
              break;
            }
            $counter ++;
          }
        }
        
      ?>

    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->

  <!-- Footer -->
  <footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">by Jorge A. Chi, Jimmy N. Ojeda y Daniel J. Pérez &copy; </p>
    </div>
    <!-- /.container -->
  </footer>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
