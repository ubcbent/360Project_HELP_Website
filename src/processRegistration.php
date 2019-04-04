<?php
include_once "include_files/accountProcesses.php";
include_once "include_files/session.php";

startSession();

if(isset($_SESSION['email']))
{
  redirect("index.php");
}

if(isset($_SESSION['admin']))
{
  if($_SESSION['admin'] == "admin") redirect("admin-home.php");
}

if(!isset($_SERVER['REQUEST_METHOD']) || !isset($_SERVER['HTTP_REFERER']))
{
  redirect("index.php");
}

$prevURL = basename($_SERVER['HTTP_REFERER']);


if($_SERVER["REQUEST_METHOD"] == "POST")
{
  if(isset($_POST["gender"]) && isset($_POST["cname"]) && isset($_POST["email"])
    && isset($_POST["password"]) && isset($_POST["password-check"]) && isset($_POST["country"])
    && isset($_POST["prov-state"]) && isset($_POST["city"]) && isset($_POST["street"]) && isset($_POST["postcode"])
    && isset($_POST["payment-method"]) && isset($_POST["cardNum"]) && isset($_POST["cvv"]) && isset($_POST["month"])
    && isset($_POST["year"]) && isset($_POST["terms"]))
    //&& isset($_FILES["profilePhoto"]))
  {
    //echo $_FILES["photo"]["name"];

    $array = array("gender" => $_POST["gender"], "cname" => $_POST["cname"], "email" => $_POST["email"],
      "password" => $_POST["password"], "password-check" => $_POST["password-check"], "country" => $_POST["country"],
      "prov-state" => $_POST["prov-state"], "city" => $_POST["city"], "street" => $_POST["street"], "postcode" => $_POST["postcode"],
      "payment-method" => $_POST["payment-method"], "cardNum" => $_POST["cardNum"], "cvv" => $_POST["cvv"],
      "month" => $_POST["month"], "year" => $_POST["year"], "terms" => $_POST["terms"]);
    $validNewUser = newUser($array);
    if(!is_bool($validNewUser) && is_string($validNewUser)) { sendErrorMessage($validNewUser); }

    redirect($prevURL);
  }
  else
  {
    sendErrorMessage("<p>It seems that not all fields were filled in, please go back to the form and try again.</p>");
    redirect($prevURL);
  }
}
else
{
  sendErrorMessage("<p>Sorry there was a problem with the data sent to the server, please go back to the form and try again.</p>");
  redirect($prevURL);
}
?>
