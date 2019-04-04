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

  <title>H.E.L.P. Admin - Manage Product</title>
  <meta name="description" content="">
  <meta name="viewport" id="myViewPort" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/nav.css">
  <link rel="stylesheet" href="css/footer.css">
  <link rel="stylesheet" href="css/admin-products-details.css">

  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script type="text/Javascript">
    window.jQuerry || document.write("<script src=\"script/jquery-3.3.1.js\"><\/script>");
  </script>
  <script type="text/JavaScript" src="script/validation.js"></script>
  <script type="text/JavaScript" src="script/admin-nav.js"></script>
  <script type="text/JavaScript" src="script/admin-products-details.js"></script>
</head>

<body>
  <!-- Top Menu Bar -->
  <?php include_once "include_files/admin-nav.php"; ?>

<div id="main">
  <?php
    include_once "include_files/db_info.php";

  	$con=mysqli_connect(HOST, USER, PASSWORD, DATABASE);
  	$error = mysqli_connect_error();
  	if($error != null){
  		echo("<p>Unable to connect to database!</p>");
  		die();
  	}
  	$id=null;
  	if(isset($_SERVER['REQUEST_METHOD']) && isset($_GET['id']) && $_SERVER['REQUEST_METHOD']==='GET'){
  		$id = $_GET['id'];
      $_SESSION['pid'] = $id;
  	}
  	else{
  		echo("<h3>There was an error with the data sent to this page. Please <a href=\"".$_SERVER['HTTP_REFERER']."\">try again</a></h3>");
  		mysqli_close($con);
  		die();
  	}

  	//query for the selected item's name, stock, price, description, and picture
  	$sql = "SELECT drugName, stock, unitPrice, descShort, descLong, pic_id FROM product WHERE pid=?;";

  	if($stmt = mysqli_prepare($con,$sql)){
  		$stmt->bind_param('i', $id);
  		$stmt->execute();
		//$results = $stmt->get_result();
		$stmt->bind_result($drugName, $stock, $unitPrice, $descShort, $descLong, $pic_id);
		$stmt->store_result();
		if($stmt->num_rows===0){
			echo("<h3>This item could not be found.</h3>");
			mysqli_close($con);
			die();
		}
		$stmt->fetch();
	}

  	//find picture
  	//mysqli_report(MYSQLI_REPORT_ALL);
  	$pic_id = mysqli_real_escape_string($con, $pic_id);
  	if(!is_numeric($pic_id)) { echo "<p>There was a problem with the Picture ID that was used when attempting to retrieve a photo.</p>"; }
  	$sql2 = "SELECT contentType, image FROM picture WHERE pic_id=".$pic_id;
  	$results2 = mysqli_query($con, $sql2);
  	$picrow = mysqli_fetch_assoc($results2);
  	if(!isset($picrow['contentType']) || !isset($picrow['image'])){
  	  echo "<p>We could not find a profile image in our database.</p>";
  	}

    //Select the categories
    $sql5 = "SELECT * FROM categoryincludes WHERE pid = $id;";
    $results5 = mysqli_query($con, $sql5);

    $sql6 = "SELECT * FROM category WHERE cateName NOT IN (SELECT cateName FROM categoryincludes WHERE pid = $id);";
    $results6 = mysqli_query($con, $sql6);

  	echo("
    <br><br><br><br><br>
    <div id=\"image\">
      <img alt=\"item image\" src=\"data:image/".$picrow['contentType'].";base64,".base64_encode($picrow['image'])."\"/>
      </div>
      <table>
      <form method=\"post\" action=\"updateProducts.php\" id=\"updateForm\" enctype=\"multipart/form-data\">
        <tr><td>Product ID: </td><td>".$id."</td></tr>
        <tr><td>Product Name: </td><td><textarea rows=\"1\" cols=\"50\" name=\"drugName\" form=\"updateForm\" required pattern=\"^[^".'"'. "]*$\">".$drugName."</textarea></td></tr>
        <tr><td>Short Description: </td><td><textarea rows=\"2\" cols=\"50\" name=\"descShort\" form=\"updateForm\" required pattern=\"^[^".'"'. "]*$\">".$descShort."</textarea></td></tr>
        <tr><td>Long Description: </td><td><textarea rows=\"6\" cols=\"50\" name=\"descLong\" form=\"updateForm\" required pattern=\"^[^".'"'. "]*$\">".$descLong."</textarea></td></tr>
        <tr><td>Unit Price: </td><td>$<input type=\"text\" name=\"unitPrice\" value=\"".$unitPrice."\"/></td></tr>
        <tr><td>Stock: </td><td><input type=\"text\" name=\"stock\" value=\"".$stock."\"/></td></tr>
        <tr><td>Category: </td><td>");
        $i = 0;
        while ($row5 = mysqli_fetch_assoc($results5)){
          $cateName = $row5['cateName'];
          if($i == 0) { echo("<input type=\"checkbox\" name=\"$cateName\" id='firstCategory' checked>$cateName<br>"); }
          else { echo("<input type=\"checkbox\" name=\"$cateName\" checked>$cateName<br>"); }
          $i++;
        }
        while ($row6 = mysqli_fetch_assoc($results6)){
          $cateName = $row6['cateName'];
          echo("<input type=\"checkbox\" name=\"$cateName\">$cateName<br>");
        }
        echo("
          </td></tr><tr><td>Upload New Image: </td><td><input type=\"file\" name=\"productImage\" id=\"productImage\"></td></tr>
          <tr><td colspan=\"2\"><input type=\"submit\" value=\"Update Product Information\"/></td></tr>
      	</form></table>");

  	echo("</div>");

  ?>
</div>
</body>
</html>
