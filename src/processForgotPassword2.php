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

$prevURL = explode("?", basename($_SERVER['HTTP_REFERER']))[0];

if($prevURL != "forgotPassword.php")
{
  redirect("index.php");
}



if($_SERVER["REQUEST_METHOD"] == "POST")
{
  if(isset($_POST["email"]) && isset($_POST["key"]) && isset($_POST["password"]) && isset($_POST["password-check"]))
  {
    //Use AccountProcesses.php
    //call the resetChancePassword function etc.
    $passwordReset = resetPassword($_POST["email"], $_POST["key"], $_POST["password"], $_POST["password-check"]);
    if(!is_bool($passwordReset) || $passwordReset != 1)
    {
      sendErrorMessage($passwordReset);
      redirect("index.php");
    }
    else
    {
      sendErrorMessage("<p>The password has been changed successfully!</p>");
    }
  }
  else
  {
    sendErrorMessage("<p>Sorry but it seems that not all the data fields were passed to the server. Please check that you entered all the fields and try again. If the error continues to occur please contact our support.</p>");
  }
}
else
{
  sendErrorMessage("<p>It seems that bad data was passed to the server. Are you sure you're filling out our form and not some mean hacker?</p>");
}

redirect("index.php");

?>
