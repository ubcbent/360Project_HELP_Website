<?php
include_once "include_files/session.php";

session_start();

if(isset($_SESSION['email']))
{
  session_unset();
  session_destroy();
}

redirect("index.php");
?>
