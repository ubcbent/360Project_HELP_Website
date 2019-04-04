<?php

include_once "include_files/db_info.php";
include_once "include_files/session.php";

$con=mysqli_connect(HOST, USER, PASSWORD, DATABASE);
$error = mysqli_connect_error();
if($error != null){
	echo("<p>Unable to connect to database!</p>");
	die();
}
session_start();
//check if user is logged in and isn't trying to remove someone elses review
$email = null;
if(isset($_SESSION['email']) && isset($_GET['email']) && $_SESSION['email']===$_GET['email']){
	$email = $_SESSION['email'];
}else{
	echo("You can only remove your own reviews. Please log in.");
	mysqli_close($con);
	die();
}

//check if the time and id were sent
$time = null;
$id = null;
if(isset($_GET['time']) && isset($_GET['id'])){
	$time = $_GET['time'];
	$id = $_GET['id'];
}else{
	echo("There was an error retrieving the details of the review you are trying to remove. Go back and try again.");
	mysqli_close($con);
	die();
}


//mysqli_report(MYSQLI_REPORT_ALL);
//Delete the review from the database
$sql = "DELETE FROM review WHERE timeposted=? and email=? and pid=?";
if($stmt = mysqli_prepare($con,$sql)){
	$stmt->bind_param('ssi', $time,$email,$id);
	$stmt->execute();
}else{
	echo("There was an error removing the review. Check to make sure it still exists.");
	mysqli_close($con);
	die();
}
if($con->affected_rows===0){
	echo("There was an error removing the review. Check to make sure it still exists.");
	mysqli_close($con);
	die();
}

mysqli_close($con);
redirect("item.php?id=".$id);

?>