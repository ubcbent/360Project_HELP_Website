<?php
include_once "include_files/accountProcesses.php";
include_once "include_files/session.php";

startSession();

if(isset($_SESSION['email']))
{
  redirect("index.php");
}

if(!isset($_SERVER['REQUEST_METHOD']) || !isset($_SERVER['HTTP_REFERER']))
{
  redirect("index.php");
}

$prevURL = basename($_SERVER['HTTP_REFERER']);
$message = '';

if($_SERVER["REQUEST_METHOD"] == "POST")
{
  if(isset($_POST["email"]))
  {
    //Use AccountProcesses.php
    //Call function resetPassword($_POST["email"]);

    $message = sendPasswordReset($_POST['email']);

    //when creating forgotPasswordHash use: YY/MM/DD-HH/MM/SS&hascode
    //EX:                                   18/11/22-01/22/30&5273909C02B8CE7AE42A4E21542B3326 (50 characters)
  }
}

if($message != '') { sendErrorMessage($message); }
redirect($prevURL);
?>
