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
	mysqli_close($con);
	die();
}
//check for proper id
$id = null;
$val = null;
if(isset($_GET['id']) && isset($_GET['val'])){
	$id = $_GET['id'];
	$val = $_GET['val'];
}else{
	mysqli_close($con);
	die();
}
//validate id
if(!is_numeric($id) || $id<0){
	mysqli_close($con);
	die();
}

//check for valid change
if($val <= 0 || !is_numeric($val)){
	$val = 1;
}

//find price of the item being changed
$sql = "SELECT unitPrice FROM product WHERE pid=?";
if($stmt = mysqli_prepare($con,$sql)){
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$stmt->bind_result($unitPrice);
	$stmt->store_result();
	if($stmt->num_rows===0){
		//item wasn't found
		mysqli_close($con);
		die();
	}
}else{
	mysqli_close($con);
	die();
}
$stmt->fetch();
$newprice = $val*$unitPrice;
//update in quantity in the db
$sql2 = "UPDATE cart SET quantity=?,price=? WHERE email=? and pid=?";
if($stmt2 = mysqli_prepare($con,$sql2)){
	$stmt2->bind_param('idsi', $val,$newprice,$email,$id);
	$stmt2->execute();
}else{
	mysqli_close($con);
	die();
}
//find new total price
$sql = "SELECT sum(price) FROM cart WHERE email=?";
if($stmt=mysqli_prepare($con,$sql)){
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$stmt->bind_result($totalPrice);
	$stmt->store_result();
	$stmt->fetch();
}
$output['total']=$totalPrice;
$output['qty']=$val;
$output['subtotal']=$newprice;
echo(json_encode($output));

mysqli_close($con);

?>
