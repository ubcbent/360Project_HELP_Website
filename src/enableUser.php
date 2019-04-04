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
    $sql1 = "SELECT * FROM disabledCustomer WHERE email = \"".$_GET['id']."\";";
    $results1 = mysqli_query($connection, $sql1);
    $row = mysqli_fetch_assoc($results1);

    $sql2 = "INSERT INTO customer (email, password, fullName, gender, pic_id, billingType) VALUES (\"".$_GET['id']."\", \"".$row['password']."\", \"".$row['fullName']."\", \"".$row['gender']."\", \"".$row['pic_id']."\", \"".$row['billingType']."\");";
    mysqli_query($connection, $sql2);

    if ($row['billingType']=="visa"){
      $sql4 = "UPDATE visa SET email = \"".$_GET['id']."\" WHERE bill_id = ".$row['bill_id'].";";
    } else {
      $sql4 = "UPDATE mastercard SET email = \"".$_GET['id']."\" WHERE bill_id = ".$row['bill_id'].";";
    }
    mysqli_query($connection, $sql4);

    $sql3 = "DELETE FROM disabledCustomer WHERE email = \"".$_GET['id']."\";";
    mysqli_query($connection, $sql3);
  }

    mysqli_close($connection);
    redirect("admin-customers.php");
  }
?>
