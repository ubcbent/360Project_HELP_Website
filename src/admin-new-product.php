<?php
include_once "include_files/session.php";

startSession();

if(!isset($_SESSION['admin']))
{
  if(!$_SESSION['admin'] == "admin") redirect("index.php");
}
?>

<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title>H.E.L.P. Admin - Add New Product</title>
  <meta name="description" content="">
  <meta name="viewport" id="myViewPort" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/nav.css">
  <link rel="stylesheet" href="css/footer.css">
  <link rel="stylesheet" href="css/admin-new-product.css">

  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script type="text/Javascript">
    window.jQuerry || document.write("<script src=\"script/jquery-3.3.1.js\"><\/script>");
  </script>
  <script type="text/JavaScript" src="script/validation.js"></script>
  <script type="text/JavaScript" src="script/admin-nav.js"></script>
  <script type="text/JavaScript" src="script/admin-new-product.js"></script>
</head>

<body>
  <!-- Top Menu Bar -->
  <?php include_once "include_files/admin-nav.php"; ?>

<div id="main">
    <br><br><br><br><br>
    <h2>Add New Product</h2>
    <form method="post" action="processNewProduct.php" id="updateForm" enctype="multipart/form-data">
    <table>
      <tr><td>Product Name: </td><td><textarea rows="1" cols="50" name="drugName" form="updateForm" required pattern='[^"]+' required></textarea></td></tr>
      <tr><td>Short Description: </td><td><textarea rows="2" cols="50" name="descShort" form="updateForm" required pattern='[^"]+' required></textarea></td></tr>
      <tr><td>Long Description: </td><td><textarea rows="6" cols="50" name="descLong" form="updateForm" required pattern='[^"]+' required></textarea></td></tr>
      <tr><td>Unit Price: </td><td>$<input type="number" name="unitPrice" required min="0" step="0.01"/></td></tr>
      <tr><td>Stock: </td><td><input type="number" name="stock" required min="0"/></td></tr>
      <tr><td>Category: </td><td><input type="checkbox" name="Accessories" id="firstCategory">Accessories<br>
                                  <input type="checkbox" name="Edible">Edible<br>
                                  <input type="checkbox" name="Inhalant">Inhalant<br>
                                  <input type="checkbox" name="Injectable">Injectable<br>
                                  <input type="checkbox" name="Smokeable">Smokeable<br>
                                  <input type="checkbox" name="Snortable">Snortable<br>
                                  <input type="checkbox" name="Used">Used<br></td></tr>

      <tr><td>Product Image: </td><td><input type="file" name="productImage" required id="productImage"></td></tr>
      <tr><td colspan="2"><input type="submit" value="Add Product"/></td></tr>
  	</table>
  </form>
</div>

<!--Footer-->
<footer>
  <?php include_once "include_files/footer.html" ?>
</footer>

</body>
</html>
