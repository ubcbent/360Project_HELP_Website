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
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>H.E.L.P.</title>
  <meta name="description" content="">
  <meta name="viewport" id="myViewPort" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/nav.css">
  <link rel="stylesheet" href="css/footer.css">
  <link rel="stylesheet" href="css/item.css">

  <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script type="text/javascript" src="script/item.js"></script>
  <script type="text/JavaScript" src="script/validation.js"></script>
  <script type="text/JavaScript" src="script/nav.js"></script>
</head>

<body>
  <?php include_once "include_files/nav.php"; ?>

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
	}
	else{
		echo("<h3>There was an error with the data sent to this page. Please <a href=\"".$_SERVER['HTTP_REFERER']."\">try again</a></h3>");
		mysqli_close($con);
		die();
	}

	//query for the selected item's name, stock, price, description, and picture
	$sql = "SELECT drugName, stock, unitPrice, descLong, pic_id, COALESCE((SELECT avg(rating) as itemrating FROM review WHERE product.pid=review.pid),-1) as itemrating FROM product WHERE pid=?;";

	if($stmt = mysqli_prepare($con,$sql)){
		$stmt->bind_param('i', $id);
		$stmt->execute();
		//$results = $stmt->get_result();
		$stmt->bind_result($drugName, $stock, $unitPrice, $descLong, $pic_id, $itemrating);
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
	if(!is_numeric($pic_id)) { echo "<p>There was a problem with the Picture ID that was used when attempting to  retrieve a photo.</p>"; }
	$sql2 = "SELECT contentType, image FROM picture WHERE pic_id=".$pic_id;
	$results2 = mysqli_query($con, $sql2);
	$picrow = mysqli_fetch_assoc($results2);
	if(!isset($picrow['contentType']) || !isset($picrow['image'])){
	  echo "<p>We could not find a profile image in our database.</p>";
	}

	echo("<h2>".$drugName."</h2>
	<div id=\"right-image\">
    <img alt=\"item image\" src=\"data:image/".$picrow['contentType'].";base64,".base64_encode($picrow['image'])."\"/>
	</div>

	<div id=\"left-text\">
    <p id=\"item-desc\">".$descLong."</p>
    <div id=\"item-details\">
      <p id=\"review-score\">".($itemrating==-1?"No Ratings":"User Reviews: ".number_format($itemrating,1)."/10")."</p>
      <p id=\"price\">$".number_format($unitPrice,2)."</p>
      <p id=\"stock\">Stock: ".$stock."</p>
    </div>
    <button type=\"button\" id=\"add-to-cart\"><img src=\"assets/images/shopping-cart.PNG\"> Add to Cart</button>
	<span id=\"cart-feedback\"></span>
	</div>");
	echo("<a id=\"continue-shopping\" href=\"shop.php\">Continue Shopping</a>");

	echo("</div>");

?>
  <div id="user-reviews">
	<form method="POST" action="addReview.php">
		<h2>User Reviews</h2>
		<label for="Rating">Rating: </label>
		<select name="Rating">
		  <option>1</option>
		  <option>2</option>
		  <option>3</option>
		  <option>4</option>
		  <option>5</option>
		  <option>6</option>
		  <option>7</option>
		  <option>8</option>
		  <option>9</option>
		  <option>10</option>
		</select>

		<textarea rows="6" name="ReviewText" placeholder="User Review"></textarea>
		<?php
		//to sneak in the id of the product
		echo("<input type=\"hidden\" name=\"id\" value=\"".$id."\">");
		?>
		<input type="submit" id="review-submit" Value="Place Review">
	</form>
    <div id="prev-reviews">
	<?php
	//get reviews for this item
	$sql = "SELECT timeposted,revContent,rating,email FROM review WHERE pid=? ORDER BY timeposted DESC";

	if($stmt = mysqli_prepare($con,$sql)){
		$stmt->bind_param('i', $id);
		$stmt->execute();
		//$results = $stmt->get_result();
		$stmt->bind_result($timeposted,$revContent,$rating,$email);
		$stmt->store_result();
		if($stmt->num_rows===0){
			echo("<h3>There are not any reviews for this item.</h3>");
			mysqli_close($con);
			die();
		}

		while($stmt->fetch()){

			//nested query to find the name of the reviewer
			$sql2 = "SELECT fullName FROM customer WHERE email=?";
			$name=null;
			if($stmt2 = mysqli_prepare($con,$sql2)){
				$stmt2->bind_param('s', $email);
				$stmt2->execute();
				//$results = $stmt->get_result();
				$stmt2->bind_result($fullName);
				$stmt2->store_result();
				if($stmt2->num_rows===0){
					$name = "Administrator";
				}else{
					$stmt2->fetch();
					$name = $fullName;
				}
			}else{
				$name = "ERROR";
			}

			//write out the review
			if(isset($_SESSION['email'])){
				echo("<div class=\"review\"><h3>Posted by ".$name." on ".date("F jS, g:i a",strtotime($timeposted)).($_SESSION['email']===$email?"     <a href=\"removeReview.php?time=".$timeposted."&email=".$email."&id=".$id."\">Remove</a>":"")."</h3><p>".str_replace("\\", "", $revContent)."</p><p>Rating: ".$rating."</p></div>");
			}else{
				echo("<div class=\"review\"><h3>Posted by ".$name." on ".date("F jS, g:i a",strtotime($timeposted))."</h3><p>".str_replace("\\", "", $revContent)."</p><p>Rating: ".$rating."</p></div>");
			}
		}

	}else{
		echo("There was an error finding the reviews for this item");
		mysqli_close($con);
		die();
	}

	mysqli_close($con);
	?>
    </div>
  </div>

  <!--Footer-->
  <footer>
    <?php include_once "include_files/footer.html" ?>
  </footer>

</body>
</html>
