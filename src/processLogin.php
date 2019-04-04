<?php
include_once "include_files/accountProcesses.php";
include_once "include_files/session.php";

session_start();

if(isset($_SESSION['email']))
{
  redirect("index.php");
}

if(!isset($_SERVER['REQUEST_METHOD']) || !isset($_SERVER['HTTP_REFERER']))
{
  redirect("index.php");
}

if(isset($_SESSION['admin']))
{
  if($_SESSION['admin'] == "admin") redirect("admin-home.php");
}

$prevURL = basename($_SERVER['HTTP_REFERER']);

if($_SERVER["REQUEST_METHOD"] == "POST") // && basename($_SERVER['HTTP_REFERER']) == "login.php")
{
  if(isset($_POST["email"]) && isset($_POST["password"]))
  {
    $loggedIn = login($_POST["email"], $_POST["password"], "customer");
    if(is_bool($loggedIn) && $loggedIn == 1)
    {
      //User has a valid account

      //create new session superglobal for Username
      //this is done in the login() method
      redirect($prevURL);
    }
    else
    {
      //User did not have a customer account so check to see if they have an admin account
      $loggedInAdmin = login($_POST["email"], $_POST["password"], "administrator");
      if(is_bool($loggedInAdmin) && $loggedInAdmin == 1)
      {
        $_SESSION['admin'] = "admin";
        redirect("admin-home.php");
      }
      else
      {
        //User did not have a valid account or some error occured for bot cases
        sendErrorMessage($loggedIn);
        redirect($prevURL);
      }
    }
  }
  else
  {
    sendErrorMessage("<p>Please enter both username and password and try again.</p>");
    redirect($prevURL);
  }
}
else
{
  sendErrorMessage("<p>Sorry there was a problem with the data sent to the server, please go back to the form and try again.</p>");
  redirect($prevURL);
}


?>
