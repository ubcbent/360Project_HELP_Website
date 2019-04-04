<?php
$allowedURLs = array("index.php", "shop.php", "item.php", "checkout.php", "customerpage.php", "addReview.php");
$allowedAdminURLs = array("admin-home.php", "admin-comments.php", "admin-customers-details.php", "admin-customers.php", "admin-orders.php", "admin-products-details.php", "admin-products.php");

function redirect($url)
{
  global $allowedURLs;
  global $allowedAdminURLs;
  startSession();

  if(isset($_SESSION['admin']))
  {
    if($_SESSION['admin'] == "admin" && in_array(explode("?", $url)[0], $allowedAdminURLs))
    {
      ob_start();
      header("Location: $url");
      ob_end_flush();
      die();
    }
  }
  if(!in_array(explode("?", $url)[0], $allowedURLs)) { $url = "index.php"; }
  ob_start();
  header("Location: $url");
  ob_end_flush();
  die();
}

function sendErrorMessage($error)
{
  if (!isset($_SESSION)) { session_start(); }
  $_SESSION['error'] = $error;
}

function clearErrorMessage()
{
  if (!isset($_SESSION)) { session_start(); }
  if( isset($_SESSION['error']) ) { unset($_SESSION['error']); }
}

function startSession()
{
  if(!isset($_SESSION)) { session_start(); }
}
?>
