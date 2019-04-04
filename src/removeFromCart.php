<?php

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
if(isset($_GET['id'])){
	$id = $_GET['id'];
}else{
	mysqli_close($con);
	die();
}
//validate id
if(!is_numeric($id) || $id<0){
	mysqli_close($con);
	die();
}

//remove the item
$sql = "DELETE FROM cart WHERE email=? and pid=?";
if($stmt = mysqli_prepare($con,$sql)){
	$stmt->bind_param('si', $email,$id);
	$stmt->execute();
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
if($totalPrice==null){$totalPrice=0.00;}
echo $totalPrice;
mysqli_close($con);

?>