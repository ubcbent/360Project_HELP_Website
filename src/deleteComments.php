<?php
include_once "include_files/session.php";

startSession();

if(!isset($_SESSION['admin']))
{
  if(!$_SESSION['admin'] == "admin") redirect("index.php");
}

if (isset($_GET['time'])&&isset($_GET['email'])&&isset($_GET['pid'])){
  include_once "include_files/db_info.php";
  $connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
  $error = mysqli_connect_error();
  if($error != null)
  {
    $output = "<p>Unable to connect to database!</p>";
    exit($output);
  }
  else
  {
    $sql = "DELETE FROM review WHERE timeposted = '".$_GET['time']."' AND email = '".$_GET['email']."' AND pid = '".$_GET['pid']."';";
    mysqli_query($connection, $sql);
    }
    
    mysqli_close($connection);
    redirect("admin-comments.php");
  }
?>
