<?php

include_once "include_files/db_info.php";
include_once "include_files/session.php";

$prevURL = basename($_SERVER['HTTP_REFERER']);

$con=mysqli_connect(HOST, USER, PASSWORD, DATABASE);
$error = mysqli_connect_error();
if($error != null){
	sendErrorMessage("<p>Unable to connect to database!</p>");
	redirect("shop.php");
	die();
}
session_start();
//check if user is logged in
$email = null;
if(isset($_SESSION['email'])){
	$email = $_SESSION['email'];
}else{
	sendErrorMessage("You must log in to post a review.");
	mysqli_close($con);
	redirect($prevURL);
	die();
}
$id=null;
$revtext = null;
$rating = null;
if(isset($_SERVER['REQUEST_METHOD']) && isset($_POST['id'])&& isset($_POST['ReviewText'])&& isset($_POST['Rating']) && $_SERVER['REQUEST_METHOD']==='POST'){
	$id = $_POST['id'];
	$revtext = mysqli_real_escape_string($con,$_POST['ReviewText']);
	$rating = $_POST['Rating'];
}else{
	sendErrorMessage("There was an issue with the data sent through the server. Your review was not posted.");
	mysqli_close($con);
	redirect($prevURL);
	die();
}
//validate rating
if((!preg_match("/^[0-9]{1}$/", $rating) && $rating!=10) || !is_numeric($rating)){
	sendErrorMessage("The rating you set did not match our parameters. Your review was not posted");
	mysqli_close($con);
	redirect($prevURL);
	die();
}

//mysqli_report(MYSQLI_REPORT_ALL);
//Insert the review into the database
$sql = "INSERT INTO review(timeposted,revContent,rating,email,pid) VALUES (?,?,?,?,?)";
$date = date("Y-m-d H:i:s",time()-(9*60*60));
if($stmt = mysqli_prepare($con,$sql)){
	$stmt->bind_param('ssisi', $date,$revtext,$rating,$email,$id);
	$stmt->execute();
}else{
	sendErrorMessage("There was a problem placing your review in the server.");
	redirect("item.php?id=".$id);
}

mysqli_close($con);
redirect("item.php?id=".$id);

?>
