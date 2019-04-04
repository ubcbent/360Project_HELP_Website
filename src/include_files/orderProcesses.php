<?php
include_once "validation.php";
include_once "db_info.php";
include_once "email.php";

function getProduct($pid)
{
  global $connection;
  global $results;

  if(!isset($pid))
  {
    return "<p>There wasn't a customer passed to get your items from the database, if the error continues to occur please contact support.</p>";
  }

  if(!connect())
  {
    close();
    return "<p>Unable to connect to database!</p>";
  }

  $pid = mysqli_real_escape_string($connection, $pid);

  $sql = "SELECT * FROM product WHERE pid='$pid';";

  $results = mysqli_query($connection, $sql);

  $row = mysqli_fetch_assoc($results);

  if (!isset($row['pid']))
  {
    close();
    return "<p>We could not find a product in our database, if the error continues to exist please contact support.</p>";
  }

  //close();
  return $row;
}

function verifyEnoughStockForOrder($cartResults)
{
  $nonValidProducts = array();
  if(isset($cartResults) && !is_bool($cartResults))
  {
    while($row = mysqli_fetch_row($cartResults))
    {
      $product = getProduct($row[1]);
      if(is_array($product) && isset($product['stock']) && isset($row[3]))
      {
        if($product['stock'] - $row[3] < 0)
        {
          array_push($nonValidProducts, $row[1]);
        }
      }
      else { }
    }
    //
  }
  else { }

  if(sizeof($nonValidProducts) > 0) return $nonValidProducts;
}

function addNewOrder($orderid, $email)
{
  global $connection;

  if(!isset($orderid)|| !isset($email))
  {
      return "<p>An internal error occurred where data was not passed over correctly (1)</p>";
  }

  if (!connect()) { return "<p>Unable to connect to database!</p>"; }

  $date = date("Y-m-d H:i:s",time()-(9*60*60));

  $sql = "INSERT INTO orders (orderid, email, orderDate) VALUES ($orderid, '$email', '$date');";

  if (!mysqli_query($connection, $sql))
  {
    $error = mysqli_error($connection);
    close();
    return "<p>A problem occured with the server while trying to add your order. (1)</p>";
  }

  //close();
  return true;
}

function updateOrder($orderid, $totalPrice, $email)
{
  global $connection;

  if(!isset($orderid) || !isset($totalPrice) || !isset($email))
  {
      return "<p>An internal error occurred where data was not passed over correctly (2)</p>";
  }

  if (!connect()) { return "<p>Unable to connect to database!</p>"; }

  $sql = "UPDATE orders SET totalPrice=$totalPrice WHERE orderId=$orderid AND email='$email';";

  if (!mysqli_query($connection, $sql))
  {
    close();
    return "<p>A problem occured with the server while trying to add your order. (2)</p>";
  }

  //close();
  return true;
}

function getOrder($orderid)
{
  global $connection;

  if(!isset($orderid))
  {
      return "<p>An internal error occurred where data was not passed over correctly (2)</p>";
  }

  if (!connect()) { return "<p>Unable to connect to database!</p>"; }

  $orderid = mysqli_real_escape_string($connection, $orderid);

  $sql = "SELECT * FROM orders WHERE orderid=$orderid;";

  $results = mysqli_query($connection, $sql);

  $row = mysqli_fetch_assoc($results);

  if (!isset($row['orderid']))
  {
    close();
    return "<p>We could not find a product in our database, if the error continues to exist please contact support.</p>";
  }

  //close();
  return $row;

}

function addInOrder($orderid, $product, $quantity)
{
  global $connection;

  if(!isset($orderid) || !is_array($product) || !isset($quantity))
  {
      return "<p>An internal error occurred where data was not passed over correctly (3)</p>";
  }

  if (!connect()) { return "<p>Unable to connect to database!</p>"; }

  $sql = "INSERT INTO inorder (orderid, pid, quantity, price) VALUES ($orderid, {$product['pid']}, $quantity, {$product['unitPrice']});";

  if (!mysqli_query($connection, $sql))
  {
    close();
    return "<p>A problem occured with the server while trying to add your order. (3)</p>";
  }

  //close();
  return true;
}

function getAllInOrderForOrder($orderid)
{
  global $connection;
  $inorder = array();

  if(!isset($orderid))
  {
      return "<p>An internal error occurred where data was not passed over correctly (3)</p>";
  }

  if (!connect()) { return "<p>Unable to connect to database!</p>"; }

  $sql = "SELECT * FROM inorder WHERE orderid=$orderid;";

  $results = mysqli_query($connection, $sql);

  while($row = mysqli_fetch_row($results))
  {
    array_push($inorder, $row);
  }

  if(sizeof($inorder) > 0) { return $inorder; }
  else { return "<p>No items were found when getting ordered items from the database</p>"; }
}

function decrementStock($pid, $quantity)
{
  global $connection;

  if(!isset($pid) || !isset($quantity))
  {
      return "<p>An internal error occurred where data was not passed over correctly (4)</p>";
  }

  if (!connect()) { return "<p>Unable to connect to database!</p>"; }

  $sql = "UPDATE product SET stock = stock - $quantity WHERE pid=$pid;";

  if (!mysqli_query($connection, $sql))
  {
    close();
    return "<p>A problem occured with the server while trying to add your order. (4)</p>";
  }

  //close();
  return true;
}

function clearCustomerCart($email)
{
  global $connection;

  if(!isset($email))
  {
      return "<p>An internal error occurred where data was not passed over correctly (5)</p>";
  }

  if (!connect()) { return "<p>Unable to connect to database!</p>"; }

  $sql = "DELETE FROM cart WHERE email='$email';";

  if (!mysqli_query($connection, $sql))
  {
    close();
    return "<p>A problem occured with the server while trying to add your order. (5)</p>";
  }

  //close();
  return true;
}

function getAllOrders()
{
  global $connection;
  $orders = array();

  if (!connect()) { return "<p>Unable to connect to database!</p>"; }

  $sql = "SELECT * FROM orders;";

  $results = mysqli_query($connection, $sql);

  while($row = mysqli_fetch_row($results))
  {
    array_push($orders, $row);
  }

  if(sizeof($orders) > 0) { return $orders; }
  else { return "<p>No orders were found when getting all orders from the database (6)</p>"; }
}

function getAllOrdersBetween($firstDate, $secondDate)
{
  global $connection;
  $orders = array();

  if(!isset($firstDate) ||!isset($secondDate)) // || !is_a($firstDate, 'DateTime') || !is_a($secondDate, 'DateTime'))
  {
      return "<p>An internal error occurred where data was not passed over correctly (7)</p>";
  }

  if (!connect()) { return "<p>Unable to connect to database!</p>"; }

  $sql = "SELECT * FROM orders WHERE orderDate BETWEEN '$firstDate' AND '$secondDate';";
  $results = mysqli_query($connection, $sql);

  while($row = mysqli_fetch_row($results))
  {
    array_push($orders, $row);
  }

  return $orders;
}

function getAllInCategoryForProduct($pid)
{
  global $connection;
  $orders = array();

  if(!isset($pid))
  {
    return "<p>There wasn't a customer passed to get your items from the database, if the error continues to occur please contact support.</p>";
  }

  if (!connect()) { return "<p>Unable to connect to database!</p>"; }

  $sql = "SELECT * FROM categoryincludes WHERE pid='$pid';";
  $results = mysqli_query($connection, $sql);

  while($row = mysqli_fetch_row($results))
  {
    array_push($orders, $row);
  }

  return $orders;

}
?>
