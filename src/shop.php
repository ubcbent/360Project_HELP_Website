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
  <meta name="viewport" id="myViewPort" content="width=device-width, initial-scale=1">
  <title>H.E.L.P.</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/nav.css">
  <link rel="stylesheet" href="css/footer.css">
	<link rel="stylesheet" href="css/shop.css">


  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script type="text/Javascript">
    window.jQuerry || document.write("<script src=\"script/jquery-3.3.1.js\"><\/script>");
  </script>
  <script type="text/JavaScript" src="script/validation.js"></script>
  <script type="text/JavaScript" src="script/nav.js"></script>
  <script type="text/JavaScript" src="script/shop.js"></script>
</head>

<body>
  <?php include_once "include_files/nav.php"; ?>


<div id="main">
  <!--Side bar -->
  <div id="filter-bar">
    <fieldset id="filter-fieldset">
      <label>Item Type:</label>
      <ul>
        <li><p><input type="checkbox" name="Edible"> Edible</p></li>
        <li><p><input type="checkbox" name="Smokeable"> Smokeable</p></li>
        <li><p><input type="checkbox" name="Injectable"> Injectable</p></li>
        <li><p><input type="checkbox" name="Inhalant"> Inhalant</p></li>
		<li><p><input type="checkbox" name="Snortable"> Snortable</p></li>
        <li><p><input type="checkbox" name="Accessories"> Accessories</p></li>
        <li><p><input type="checkbox" name="Used"> Used</p></li>
      </ul>
      <label>Price Range:</label>
      <p>$<input type="number" id="minprice" placeholder="Minimum Price"> - <input type="number" id="maxprice" placeholder="Maximum Price"></p>
      <label>User Reviews:</label>
      <input type = "number" id = "reviews" placeholder="Minimum">
    </fieldset>

  </div>

  <!--Item List-->
  <div id="items">
    <!--
    <div class="item">
      <h3><a href="item.php">Premium Tripper's Toolset&copy; 10mL needle</a></h3>
	  <div class="item-pic">
		<a href="item.php"><img src="assets\images\needle.jpg"></a>
	  </div>
	  <div class="details-short">
		<p class="desc-short">Perfect needle for first users, as well as long time enthusiasts! The plastic casing is very resilient, and the needle will be well sterilized before it is shipped out.
		If you're on a budget, a used variant is available at a heavily discounted price!</p>
		<p class="item-review">User Reviews: 9.2/10<span class="item-price">$40.00</span></p>
	  </div>
    </div>-->

	<?php
    include_once "include_files/db_info.php";
		/*$user = "btissera";
		$password = "37615168";
		$database = "db_" . $user;
		$host = "cosc304.ok.ubc.ca";*/

    /*$host = "localhost";
		$database = "project_data";
		$user = "webuser";
		$password = "P@ssw0rd";*/

		$con=mysqli_connect(HOST, USER, PASSWORD, DATABASE);
		$error = mysqli_connect_error();
		if($error != null){
		echo("<p>Unable to connect to database!</p>");
		die();
		}
		//set sql string and params that will change based on what is available
		$sql = "SELECT pid, drugName, unitPrice, descShort, pic_id, COALESCE((SELECT avg(rating) as itemrating FROM review WHERE product.pid=review.pid),-1) as itemrating FROM product WHERE TRUE";
		$params = [];
		$chars = '';

		if(isset($_GET['search']) && !($_GET['search']==="undefined")){
			//search term found
			$searchterm = mysqli_real_escape_string($con,$_GET['search']);
			$searchparam = "%{$searchterm}%";
			
			$params[]=$searchparam;
			$chars.='s';
			$sql .= " AND drugName LIKE ?";
		}

		//set the results offset based on what page is displayed
		if(isset($_GET['page'])&&$_GET['page']>1){
			$offset = ($_GET['page']-1)*10;
		}
		else{$offset = 0;}
		$params[]=$offset;
		$chars .= 'i';

		//finish query and execute
		$sql .= " ORDER BY pid DESC LIMIT ?,10";

		//mysqli_report(MYSQLI_REPORT_ALL);
		if($stmt = mysqli_prepare($con,$sql)){
			$args = array_merge(array($chars), $params);
			call_user_func_array(array($stmt, 'bind_param'), ref($args));
			$stmt->execute();
			//$results = $stmt->get_result();
			$stmt->bind_result($pid,$drugName,$unitPrice,$descShort,$pic_id,$avgrating);
			$stmt->store_result();
			if($stmt->num_rows===0){
				echo("<h3>Your search parameters returned no results. <a href=\"shop.php\">View new listings</a></h3>");
				mysqli_close($con);
				die();
			}
			
			while($stmt->fetch()){
				//find picture
				//mysqli_report(MYSQLI_REPORT_ALL);
				$pic_id = mysqli_real_escape_string($con, $pic_id);
				if(!is_numeric($pic_id)) { echo "<p>There was a problem with the Picture ID that was used when attempting to  retrieve a photo.</p>"; }
				$sql2 = "SELECT contentType, image FROM picture WHERE pic_id=".$pic_id;
				$results2 = mysqli_query($con, $sql2);
				$picrow = mysqli_fetch_assoc($results2);
		
				if(!isset($picrow['contentType']) || !isset($picrow['image']))
				{
				  echo "<p>We could not find a profile image in our database.</p>";
				}

				echo("<div class=\"item\">
					  <h3><a href=\"item.php?id=".$pid."\">".$drugName."</a></h3>
					  <div class=\"item-pic\">
						<a href=\"item.php?id=".$pid."\"><img src=\"data:image/".$picrow['contentType'].";base64,".base64_encode($picrow['image'])."\"/></a>
					  </div>
					  <div class=\"details-short\">
						<p class=\"desc-short\">".$descShort."</p>
						<p class=\"item-review\">".($avgrating==-1?"No Ratings":"User Reviews: ".number_format($avgrating,1)."/10")."<span class=\"item-price\">$".number_format($unitPrice,2)."</span></p>
					  </div>
					</div>");
			}
		}

	mysqli_close($con);

	//helper functions
	function ref($arr) {
    $refs = array();
    foreach ($arr as $key => $val) $refs[$key] = &$arr[$key];
    return $refs;
	}

	?>

    <button id="prev-page">Previous</button>
    <button id="next-page">Next</button>

  </div>
</div>

  <!--Added these b/c the footer was overlapping with buttons so i made a quick fix-->
  <br><br><br><br><br>
  <!--Footer-->
  <footer>
    <?php include_once "include_files/footer.html" ?>
  </footer>


</body>
</html>
