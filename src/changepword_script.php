<?php
include_once "include_files/db_info.php";
include_once "include_files/accountProcesses.php";
include_once "include_files/session.php";
startSession();

if(!(isset($_SESSION['email']) && $_SESSION['email']!=' '))
{
  redirect("index.php");
  //header("location:processLogin.php");
}

if (!connect())
{
  return "<p>Unable to connect to database!</p>";
}

$row = getUser($_SESSION['email']);
if(!is_array($row)) { close(); return $row; }

$passwordChange = changePassword($_SESSION['email'], $row['password'], $_POST['newPassword'], $_POST['confirmPassword']);
close();

if(isset($passwordChange)) { sendErrorMessage($passwordChange); }
redirect("customerpage.php");


/*$dbcon=mysqli_connect(HOST, USER, "37615168", "db_btissera");
$password1=mysqli_real_escape_string($dbcon,$_POST['newPassword']);
$password2=mysqli_real_escape_string($dbcon,$_POST['confirmPassword']);
$email=mysqli_real_escape_string($_SESSION['email']);
if($password1<>$password2){
  echo("doesnt match");
}
else if(mysqli_query($dbcon,"UPDATE administrator SET password='$password1' WHERE email='$email' ")){
  echo "successfull";
}
else{
  mysql_error($dbcon);
}
mysqli_close($dbcon);*/

?>
