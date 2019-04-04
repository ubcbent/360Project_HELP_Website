<?php
include_once "include_files/session.php";

startSession();

if(!isset($_SESSION['admin']))
{
  if(!$_SESSION['admin'] == "admin") redirect("index.php");
}
?>

<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" id="myViewPort" content="width=device-width, initial-scale=1">
  <title>H.E.L.P. Admin - Item Sales</title>

  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/nav.css">
  <link rel="stylesheet" href="css/footer.css">
  <link rel="stylesheet" href="css/admin-orders.css">

  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script type="text/Javascript">
    window.jQuerry || document.write("<script src=\"script/jquery-3.3.1.js\"><\/script>");
  </script>
  <script type="text/JavaScript" src="script/validation.js"></script>
  <script type="text/JavaScript" src="script/admin-nav.js"></script>
</head>

<body>
  <!-- Top Menu Bar -->
  <?php include_once "include_files/admin-nav.php"; ?>

  <div id="main">
    <form method="get" action="admin-orders.php">
      <input type="text" placeholder="Search Order.." name="search">
      <button type="submit">Submit</button>
    </form>
    <h1>Item Sales</h1>
    <table>
      <tr>
        <th>Order ID</th>
        <th>Order Date</th>
        <th>User Email</th>
        <th>Product Ordered</th>
        <th>Quantity</th>
        <th>Unit Price</th>
      </tr>
      <?php
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
          $sql = "SELECT * FROM orders;";
          if(isset($_GET['search'])){
            $sql = "SELECT * FROM orders WHERE email LIKE '%".$_GET["search"]."%' OR orderDate LIKE '%".$_GET["search"]."%';";
          }

          $results = mysqli_query($connection, $sql);
          if (mysqli_num_rows($results) == 0) {
            echo "<tr><td colspan=\"5\">No orders found with given criteria.</td></tr>";
          }
          while ($row = mysqli_fetch_assoc($results))
          {
            $orderid = $row['orderid'];
            $totalPrice = $row['totalPrice'];
            $email = $row['email'];
            $orderDate = $row['orderDate'];
            echo <<<EOD
              <tr>
                <td>$orderid</td>
                <td>$orderDate</td>
                <td><a href="admin-customers.php?search=$email">$email</a></td>
                <td colspan="3" class="info">ORDER TOTAL PRICE: $$totalPrice</td>
              </tr>
EOD;

            $sql2 = "SELECT drugName, quantity, inorder.price FROM inorder, product WHERE inorder.pid = product.pid AND orderid = $orderid;";
          //  echo $sql2;
            $results2 = mysqli_query($connection, $sql2);
            while ($row2 = mysqli_fetch_assoc($results2))
            {
              $drugName = $row2['drugName'];
              $quantity = $row2['quantity'];
              $price = $row2['price'];
              echo <<<EOD
                <tr>
                  <td colspan="3"></td>
                  <td>$drugName</td>
                  <td>$quantity</td>
                  <td>$$price</td>
                </tr>
EOD;
              }
            }
          }

          mysqli_free_result($results);
          mysqli_close($connection);
      ?>
    </table>
  </div>


  <!--Footer-->
  <footer>
    <?php include_once "include_files/footer.html" ?>
  </footer>

  </body>
  </html>
