<?php
include_once "include_files/session.php";

startSession();

if(isset($_SESSION['admin']))
{
  if($_SESSION['admin'] == "admin") redirect("admin-home.php");
}
?>

<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>H.E.L.P.</title>
  <meta name="description" content="">
  <meta name="viewport" id="myViewPort" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/footer.css">
	<link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/nav.css">

  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script type="text/Javascript">
    window.jQuerry || document.write("<script src=\"script/jquery-3.3.1.js\"><\/script>");
  </script>
  <script type="text/JavaScript" src="script/validation.js"></script>
  <script type="text/JavaScript" src="script/nav.js"></script>
</head>

<body>
  <!-- Top Menu Bar -->
  <?php include_once "include_files/nav.php" ?>

  <!-- Main Web Content -->
  <div id="background-image-container">
    <section id="index-description">
      <p class="body-heading-midsize">The only known website where you can find</p>
      <div id="large-heading-container">
        <p class="body-heading-large"><span>Premium</span> Quality</p>
        <p class="body-heading-large">Drugs For The Best</p>
        <p class="body-heading-large"><span>Price</span></p>
      </div>
      <p class="body-heading-midsize">To Your Door</p>
    </section>
    <a id="index-shop-button" href="shop.php"><img src="assets/images/shop-button.png" alt="Shop Button"/></a> <!--<button type="button" class="btn btn-default btn-home">-->
  </div>

  <!--Footer-->
  <footer>
    <?php include_once "include_files/footer.html" ?>
  </footer>

</body>
</html>
