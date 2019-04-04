<?php
include_once "include_files/session.php";
include_once "include_files/db_info.php";
include_once "include_files/orderProcesses.php";

startSession();

if(!isset($_SESSION['email']))
{
  redirect("index.php");
}

if(isset($_SESSION['admin']))
{
  if($_SESSION['admin'] == "admin") redirect("admin-home.php");
}

if(!connect())
{
  close();
  return "<p>Unable to connect to database!</p>";
}

$prevURL = basename($_SERVER['HTTP_REFERER']);

$sql = "SELECT * FROM cart WHERE email='{$_SESSION['email']}';";

$checkoutResults = mysqli_query($connection, $sql);

if(is_bool($checkoutResults) && !$checkoutResults)
{
  sendErrorMessage("<p>Your cart was empty and there was nothing for you to place an order on.</p>");
  redirect($prevURL);
}

//TODO:Check for empty cart:
$row = mysqli_fetch_assoc($checkoutResults);
if (!isset($row['pid'])) { mysqli_free_result($checkoutResults); close(); sendErrorMessage("<p>Sorry but in trying to go to checkout, you had an empty cart.</p>"); redirect($prevURL); }
$checkoutResults = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>H.E.L.P.</title>
  <meta name="description" content="">
  <meta name="viewport" id="myViewPort" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="css/reset.css"/>
  <link rel="stylesheet" href="css/footer.css"/>
  <link rel="stylesheet" href="css/nav.css"/>
  <link rel="stylesheet" href="css/checkout.css"/>

  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script type="text/Javascript">
    window.jQuerry || document.write("<script src=\"script/jquery-3.3.1.js\"><\/script>");
  </script>
  <script type="text/JavaScript" src="script/validation.js"></script>
  <script type="text/JavaScript" src="script/nav.js"></script>
  <script type="text/JavaScript" src="script/checkout.js"></script>
</head>

<body>
  <?php include_once "include_files/nav.php"; ?>

  <h1>Confirm Purchase</h1>

  <div id="main">
    <?php
    $totalPrice = 0.00;

    while($row = mysqli_fetch_row($checkoutResults))
    {
      $price = $row[2];
      $totalPrice += $price;
      $count = 0;
      $product = getProduct($row[1]);

      //Display the item
      if(is_array($product))
      {
        echo "<div class='checkout-item'>";

        //Get product image
        if(isset($product['pic_id']))
        {
          $file = getPhoto($product['pic_id']);

          if(isset($file["contentType"]) && isset($file["image"])) { echo '<img src="data:image/'. $file["contentType"] . ';base64,' . base64_encode($file["image"]) . '"/>'; }
          else { echo "<img src='assets/images/unknown-item.png' />"; }
        }
        else { echo "<img src='assets/images/unknown-item.png' />"; }

        echo "<p class='right name'>{$product['drugName']}</p>
              <p class='right qty'>Quantity: {$row[3]}</p>
              <p class='right cost'>$" . $row[2] . "</p>
            </div>";
        $count++;
      }
    }
    ?>

    <p id="total">TOTAL: <span class="cost">$<?php echo number_format((float)$totalPrice, 2, '.', ',');; ?></span></p>

    <div id="final-confirm">
      <form id="checkout-form" method="post" action="proccessCheckout.php">
        <p id="billing">Are you sure you wish to purchase these potentially illegal and immoral items? <input type="checkbox" name="billing-confirm"> Yes</p>
        <p class="red invis">Please confirm your purchase!</p>
        <input id="button-confirm" type="submit" name="submit" value="Confirm Purchase" />
      </form>
    </div>
  </div>

  <!--Footer-->
  <footer>
    <?php include_once "include_files/footer.html" ?>
  </footer>

</body>
</html>

<?php if($checkoutResults != null) { mysqli_free_result($checkoutResults); $checkoutResults = null; } ?>
