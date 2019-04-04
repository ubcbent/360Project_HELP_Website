<?php

$output = array();

include_once "include_files/session.php";
include_once "include_files/db_info.php";
$con=mysqli_connect(HOST, USER, PASSWORD, DATABASE);
$error = mysqli_connect_error();
if($error != null){
	echo("<p>Unable to connect to database!</p>");
	die();
}

startSession();

//check that user is logged in
$email = null;
if(isset($_SESSION['email'])){
	$email = $_SESSION['email'];
}else{
	$output['a']="<p class=\"redText\">Please log in before adding items to your cart.</p>";
	echo(json_encode($output));
	mysqli_close($con);
	die();
}

//find current total price
$sql = "SELECT sum(price) FROM cart WHERE email=?";
if($stmt=mysqli_prepare($con,$sql)){
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$stmt->bind_result($totalPrice);
	$stmt->store_result();
	$stmt->fetch();
}
$output['total']=$totalPrice;

//check for proper id
$id = null;
if(isset($_GET['id'])){
	$id = $_GET['id'];
}else{
	$output['a']="<p class=\"redText\">The item id was not correctly sent to the server. Try again.</p>";
	echo(json_encode($output));
	mysqli_close($con);
	die();
}
//validate id
if(!is_numeric($id) || $id<0){
	$output['a']="<p class=\"redText\">There was an issue with the item id you sent to the server. Try again.</p>";
	echo(json_encode($output));
	mysqli_close($con);
	die();
}

//check if item is in stock, and store relevant variables
$sql = "SELECT stock,unitPrice,pic_id,drugName FROM product WHERE pid=?";
if($stmt = mysqli_prepare($con,$sql)){
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$stmt->bind_result($stock,$unitPrice,$pic_id,$drugName);
	$stmt->store_result();
	if($stmt->num_rows===0){
		$output['a']="<p class=\"redText\">The item requested could not be found. Try refreshing the page and try again.</p>";
		echo(json_encode($output));
		mysqli_close($con);
		die();
	}else{
		$stmt->fetch();
		if($stock===0){
			$output['a']="<p class=\"redText\">Sorry, that item is out of stock. Check back later.</p>";
			echo(json_encode($output));
			mysqli_close($con);
			die();
		}
	}
}else{
	$output['a']="<p class=\"redText\">An error was encountered while searching for the item requested. Try again.</p>";
	echo(json_encode($output));
	mysqli_close($con);
	die();
}

//check for amount already in cart
$sql = "SELECT quantity FROM cart WHERE email=? and pid=?";
if($stmt = mysqli_prepare($con,$sql)){
	$stmt->bind_param('si', $email,$id);
	$stmt->execute();
	$stmt->bind_result($quantity);
	$stmt->store_result();
	if($stmt->num_rows===0){
		//this user has none in their cart
		$quantity = 1;
		$sql2 = "INSERT INTO cart VALUES (?,?,?,?)";
		if($stmt2 = mysqli_prepare($con,$sql2)){
			$output['total']+=$unitPrice;
			$stmt2->bind_param('sidi',$email,$id,$unitPrice,$quantity);
			$stmt2->execute();
		}else{
			$output['a']="<p class=\"redText\">An error was encountered while adding this item to your cart. Try again</p>";
			echo(json_encode($output));
			mysqli_close($con);
			die();
		}

	}else{
		//this item is in their cart, they should go to the cart to change quantity
		$output['a']="<p class=\"redText\">This item is already in your cart, to change the quantity go there.</p>";
		echo(json_encode($output));
		mysqli_close($con);
		die();
	}
}else{
	$output['a']="<p class=\"redText\">An error was encountered while checking your cart for this item. Try again.</p>";
	echo(json_encode($output));
	mysqli_close($con);
	die();
}
$output['a']="<p class=\"greenText\">Item successfully added!</p>";
//write into cart in real time
//find the number of items in the users cart
$sql = "SELECT count(pid) FROM cart WHERE email=?";
$cartItems=null;
if($stmt=mysqli_prepare($con,$sql)){
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$stmt->bind_result($cartItems);
	$stmt->store_result();
	$stmt->fetch();
}else{
	$output['a']="<p class=\"redText\">An error was encountered reading your cart. Try again.</p>";
	echo(json_encode($output));
	mysqli_close($con);
	die();
}
//beginning of item
$output['b']="<li id='".$id."' class='shopping-cart-element'>
                  <figure><a href='index.php?id=$id'>";

//get photo for item
$sql = "SELECT image,contentType FROM picture WHERE pic_id=?";
$imageExists = False;
if($stmt=mysqli_prepare($con,$sql)){
	$stmt->bind_param('i', $pic_id);
	$stmt->execute();
	$stmt->bind_result($img,$content);
	$stmt->store_result();
	$stmt->fetch();
	$imageExists = True;
}
if($stmt->num_rows===0){
	$imageExists=False;
}

if($imageExists){
	$output['b'].= '<img src="data:image/'. $content . ';base64,' . base64_encode($img) . '"/>';
}
else {
	$output['b'].= "<img src='assets/images/unknown-item.png' />";
}

//output the rest
$output['b'].="  </a></figure><div class=\"shopping-cart-element-rightside\">
                    <h4><a href=\"item.php?id=".$id."\">".$drugName."</a></h4>
                    <label>Quantity:</label>
                    <input type=\"number\" name=\"quantity".($cartItems)."\" min=\"0\" step=\"1\" value=\"1\"/>
                    <p id=\"shopping-cart-item-price\">$" . number_format($unitPrice,2) . "</p>
                    <span>x</span>
                  </div>
                </li>";


echo(json_encode($output));
mysqli_close($con);

?>
