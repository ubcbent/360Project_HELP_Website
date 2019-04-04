<?php
include_once "include_files/session.php";

startSession();

if(!isset($_SESSION['admin']))
{
  if(!$_SESSION['admin'] == "admin") redirect("index.php");
}

if (isset($_GET['id'])){
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
    $sql1 = "SELECT * FROM customer WHERE email = \"".$_GET['id']."\";"; //password, fullName, gender, pic_id, billingType, forgotPasswordHash
    $results1 = mysqli_query($connection, $sql1);
    $row = mysqli_fetch_assoc($results1);

    if ($row['billingType']=="visa"){
      $sql4 = "SELECT bill_id FROM visa WHERE email = \"".$_GET['id']."\";";
    } else {
      $sql4 = "SELECT bill_id FROM mastercard WHERE email = \"".$_GET['id']."\";";
    }
    $results4 = mysqli_query($connection, $sql4);
    $row4 = mysqli_fetch_assoc($results4);

    $sql2 = "INSERT INTO disabledCustomer (email, password, fullName, gender, pic_id, billingType, bill_id) VALUES (\"".$_GET['id']."\", \"".$row['password']."\", \"".$row['fullName']."\", \"".$row['gender']."\", \"".$row['pic_id']."\", \"".$row['billingType']."\", \"".$row4['bill_id']."\");";
    mysqli_query($connection, $sql2);

    $sql3 = "DELETE FROM customer WHERE email = \"".$_GET['id']."\";";
    mysqli_query($connection, $sql3);
  }

    mysqli_close($connection);
    redirect("admin-customers.php");
  }
?>
