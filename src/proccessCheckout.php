<?php
include_once "include_files/session.php";
include_once "include_files/db_info.php";
include_once "include_files/orderProcesses.php";
include_once "include_files/email.php";

startSession();

if(!isset($_SESSION['email']))
{
  redirect("index.php");
}

if(isset($_SESSION['admin']))
{
  if($_SESSION['admin'] == "admin") redirect("admin-home.php");
}

$prevURL = basename($_SERVER['HTTP_REFERER']);

if($prevURL != "checkout.php")
{
  redirect("index.php");
}

if(!connect())
{
  close();
  sendErrorMessage("<p>Unable to connect to database!</p>");
  redirect($prevURL);
}

//Get all items in the cart for the user
$sql = "SELECT * FROM cart WHERE email='{$_SESSION['email']}';";

$orderResults = mysqli_query($connection, $sql);

if((is_bool($orderResults) && !$orderResults) || !isset($orderResults))
{
  close();
  sendErrorMessage("<p>Your cart was empty and there was nothing for you to place an order on.</p>");
  redirect($prevURL);
}

//TODO:Check for empty cart:
$row = mysqli_fetch_assoc($orderResults);
if (!isset($row['pid'])) { mysqli_free_result($orderResults); close(); sendErrorMessage("<p>Sorry but in trying to place an order, you had an empty cart.</p>"); redirect("index.php"); }
$orderResults = mysqli_query($connection, $sql);

//TODO: Chceck enough stock for all items, if not enough we can't place an order and should redirect of this page with an error message
$validStockForPurchase = verifyEnoughStockForOrder($orderResults);
if(is_array($validStockForPurchase))
{
  $error = "<p>There were products in your cart that do not have enough stock to supply your order. The following list of id's do not have enough stock: ";
  $i = 0;
  foreach($validStockForPurchase as $id)
  {
    $error .= "<a href='item.php?id=$id'>$id</a>";
    if($i < sizeof($validStockForPurchase) - 2) { $error .= ", "; }
  }
  mysqli_free_result($orderResults);
  close();
  //exit($error . "</p>");
  sendErrorMessage($error . "</p>");
  redirect($prevURL);
}

$orderResults = mysqli_query($connection, $sql);

//TODO: Get next order id
$orderid = getNextID("orders", "orderid");
if (!is_numeric($orderid)) { mysqli_free_result($orderResults); close(); sendErrorMessage($orderid); redirect($prevURL); }

//Insert new Order into database
$validOrder = addNewOrder($orderid, $_SESSION['email']);
if(!is_bool($validOrder) || $validOrder != true) { mysqli_free_result($orderResults); close(); sendErrorMessage($validOrder); redirect($prevURL); }

$totalPrice = 0.00;

while($row = mysqli_fetch_row($orderResults))
{
  $price = $row[2]; //number_format((float)$row[2], 2, '.', ',');
  $totalPrice += $price;
  $count = 0;
  $product = getProduct($row[1]);

  //Display the item
  if(is_array($product))
  {
    //TODO: Insert into inOrder
    $validAddInOrder = addInOrder($orderid, $product, $row[3]);
    if(!is_bool($validAddInOrder) || $validAddInOrder != true) { mysqli_free_result($orderResults); close(); sendErrorMessage($validAddInOrder); redirect($prevURL); }

    //TODO: Decrement stock
    $validDecrementStock = decrementStock($product['pid'], $row[3]);
    if(!is_bool($validDecrementStock) || $validDecrementStock != true) { mysqli_free_result($orderResults); close(); sendErrorMessage($validDecrementStock); redirect($prevURL); }

    $count++;
  }
}

//TODO: Update orders with total price
$validUpdateOrder = updateOrder($orderid, $totalPrice, $_SESSION['email']);
if(!is_bool($validUpdateOrder) || $validUpdateOrder != true) { mysqli_free_result($orderResults); close(); sendErrorMessage($validUpdateOrder); redirect($prevURL); }

//TODO: Clear Cart for user
$validClearCart = clearCustomerCart($_SESSION['email']);
if(!is_bool($validClearCart) || $validClearCart != true) { mysqli_free_result($orderResults); close(); sendErrorMessage($validClearCart); redirect($prevURL); }

//TODO: Send customer an email with their receipt
$validEmail = emailUserOrder($_SESSION['email'], $orderid);
if(!is_bool($validEmail) || $validEmail != true) { mysqli_free_result($orderResults); close(); sendErrorMessage("<p>Your order has been placed! However the following issue occured while trying to email you your receipt:</p>$validEmail"); redirect("index.php"); }

//Clear the results
close();
if($orderResults != null) { mysqli_free_result($orderResults); $orderResults = null; }
sendErrorMessage("<p>Your order has been placed! Please check your email for a receipt and confirmation of this purchase. Thank you for shopping at H.E.L.P.</p>");
redirect("index.php");
?>
