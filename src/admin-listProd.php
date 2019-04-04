<?php
include_once "include_files/session.php";

startSession();

/*if(!isset($_SESSION['email']))
{
  redirect("index.php");
}*/

/*if(isset($_SESSION['admin']))
{
  if($_SESSION['admin'] == "admin") redirect("admin-home.php");
}*/

		/*$host = HOST; //"localhost";
		$database = DATABASE; //"project_data";
		$user = USER; //"webuser";
		$password = PASSWORD; //"P@ssw0rd";*/
		//mysqli_report(MYSQLI_REPORT_ALL);
		include_once "include_files/db_info.php";
		$con=mysqli_connect(HOST, USER, PASSWORD, DATABASE);
		$error = mysqli_connect_error();
		if($error != null){
		echo("<p>Unable to connect to database!</p>");
		die();
		}
		//set sql string and params that will change based on what is available
		$sql = "SELECT product.pid,drugName,unitPrice,descShort,pic_id, COALESCE((SELECT avg(rating) as itemrating FROM review WHERE product.pid=review.pid),-1) as itemrating FROM product WHERE TRUE";
		$params = array();
		$chars = '';

		if(isset($_GET['search']) && !($_GET['search']==="undefined")){
			//search term found
			$searchterm = mysqli_real_escape_string($con,$_GET['search']);
			$searchparam = "%{$searchterm}%";

			$params[]=$searchparam;
			$chars.='s';
			$sql .= " AND drugName LIKE ?";
		}

		if(isset($_GET['category']) && !($_GET['category']==="[]")){
			//filtered categories were found
			$allowedCategories = array("Edible","Smokeable","Injectable","Inhalant","Snortable","Accessories","Used");
			$categories = json_decode($_GET['category']);
			if(!is_array($categories)){
				echo("<h3>The categories were not sent correctly. <a href=\"admin-products.php\">View new listings</a></h3>");
				mysqli_close($con);
				die();
			}
			foreach($categories as $c){ //validate categories
				if(!in_array($c,$allowedCategories)){
					echo("<h3>An invalid category '$c' was found in the filter. <a href=\"admin-products.php\">View new listings</a></h3>");
					mysqli_close($con);
					die();
				}
			}
			$sqlc = "SELECT pid FROM categoryincludes WHERE cateName IN (";
			$paramsc = array();
			foreach($categories as $cat){
				$sqlc.="?,";
				$paramsc[]=$cat;
			}
			$sqlc.="-1)";

			//we need to do a query to find all products that are in the selected categories
			$count=0;
			if($stmt = mysqli_prepare($con,$sqlc)){
				$charsc = str_repeat("s", count($paramsc));
				$args = array_merge(array($charsc), $paramsc);
				call_user_func_array(array($stmt, 'bind_param'), ref($args));
				$stmt->execute();
				//$results = $stmt->get_result();
				$stmt->bind_result($pid);
				$stmt->store_result();
				while($stmt->fetch()){
					$params[]=$pid;
					$count++;
				}
			}
			$sql.=" AND product.pid IN (".str_repeat("?,",$count)."0)";
			$chars.=str_repeat('i',$count);

		}

		if(isset($_GET['minprice']) && $_GET['minprice']!=null){
			//minimum price found
			$minprice = $_GET['minprice'];
			if($minprice<0){
				$minprice = 0;
			}else if($minprice>9999999.99){
				$minprice = 9999999.99;
			}
			$chars.='i';
			$sql.=" AND unitPrice>=?";
			$params[]=(float)$minprice;
		}

		if(isset($_GET['maxprice']) && $_GET['maxprice']!=null){
			//maximum price found
			$maxprice = $_GET['maxprice'];
			if($maxprice<0){
				$maxprice = 0;
			}else if($maxprice>9999999.99){
				$maxprice = 9999999.99;
			}
			$chars.='i';
			$sql.=" AND unitPrice<=?";
			$params[]=(float)$maxprice;
		}

		if(isset($_GET['ratings'])&&$_GET['ratings']!=null){
			//maximum price found
			$ratings = $_GET['ratings'];
			if($ratings<0){
				$ratings = 0;
			}else if($ratings>10){
				$ratings = 10;
			}
			$chars.='i';
			$sql.=" AND COALESCE((SELECT avg(rating) as itemrating FROM review WHERE product.pid=review.pid),-1)>?";
			$params[]=(int)$ratings;
		}

		//set the results offset based on what page is displayed
		if(isset($_GET['page'])&&$_GET['page']>1){
			$offset = ($_GET['page']-1)*10;
		}
		else{$offset = 0;}
		$params[]=$offset;
		$chars .= 'i';

		//Finish query and execute
		$sql.= " ORDER BY pid DESC LIMIT ?,10";
		//echo($sql."\n\n");
		//foreach($params as $param){echo($param."\n");}	//some debug stuff
		//echo($chars);
		//echo("\n\n\n");

		if($stmt = mysqli_prepare($con,$sql)){
			$args = array_merge(array($chars), $params);
			call_user_func_array(array($stmt, 'bind_param'), ref($args));
			$stmt->execute();
			//$results = $stmt->get_result();
			$stmt->bind_result($pid,$drugName,$unitPrice,$descShort,$pic_id,$avgrating);
			$stmt->store_result();
			if($stmt->num_rows===0){
				echo("<h3>Your search parameters returned no results. <a href=\"admin-products.php\">View new listings</a></h3>");
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
					  <h3><a href=\"admin-products-details.php?id=".$pid."\">".$drugName."</a></h3>
					  <div class=\"item-pic\">
						<a href=\"admin-products-details.php?id=".$pid."\"><img src=\"data:image/".$picrow['contentType'].";base64,".base64_encode($picrow['image'])."\"/></a>
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
