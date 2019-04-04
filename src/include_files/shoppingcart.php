<head>
<script type="text/JavaScript" src="script/removeFromCart.js"></script>
<script type="text/JavaScript" src="script/changeInCart.js"></script>
</head>

<?php
include_once "include_files/orderProcesses.php";

if(!connect())
{
  close();
  return "<p>Unable to connect to database!</p>";
}
$prevURL = null;
if(isset($_SERVER['HTTP_REFERER'])){
	$prevURL = basename($_SERVER['HTTP_REFERER']);
}

$sql = "SELECT * FROM cart WHERE email='{$_SESSION['email']}';";

$cartResults = mysqli_query($connection, $sql);
?>

<form id="shopping-cart-form" method="post" action="checkout.php">
  <div class="shopping-cart-header" id="shopping-cart-header">
    <span>&rang;</span>
    <h3>CART</h3>
  </div>

  <!-- The List of items in the cart -->
  <ul id="shopping-cart-element-container">
    <?php
    if(is_bool($results) && !$results)
    {
      echo "<li><p>Your cart is empty</p>";
    }
    else
    {
	//find total price
    $totalPrice = null;
	$sql = "SELECT sum(price) FROM cart WHERE email=?";
	if($stmt=mysqli_prepare($connection,$sql)){
		$stmt->bind_param('s', $_SESSION['email']);
		$stmt->execute();
		$stmt->bind_result($totalPrice);
		$stmt->store_result();
		$stmt->fetch();
	}
	  
	  $i=1;

      while($row = mysqli_fetch_row($cartResults))
      {
        $price = number_format((float)$row[2] * $row[3], 2, '.', ',');
        $product = getProduct($row[1]);

        //Display the item
        if(is_array($product))
        {
          echo "<li id='{$product['pid']}' class='shopping-cart-element'>
                  <figure><a href='item.php?id={$product['pid']}'>";

          //Get product image
          if(isset($product['pic_id']))
          {
            $file = getPhoto($product['pic_id']);

            if(isset($file["contentType"]) && isset($file["image"])) { echo '<img src="data:image/'. $file["contentType"] . ';base64,' . base64_encode($file["image"]) . '"/>'; }
            else { echo "<img src='assets/images/unknown-item.png' />"; }
          }
          else { echo "<img src='assets/images/unknown-item.png' />"; }

          echo "  </a></figure>
                  <div class='shopping-cart-element-rightside'>
                    <h4><a href='item.php?id={$product['pid']}'>{$product['drugName']}</a></h4>
                    <label>Quantity:</label>
                    <input type='number' name='quantity{$i}' min='0' step='1' value='{$row[3]}'/>
                    <p id='shopping-cart-item-price'>$" . $row[2] . "</p>
                    <span>x</span>
                  </div>
                </li>";
          $i++;
        }
      }
    }
    ?>
  </ul>

  <!-- The Checkout Area -->
  <div class="shopping-cart-footer">
    <h5 id="shopping-cart-total">Total: $<?php echo $totalPrice; ?></h5>
    <button>Checkout</button>
  </div>
</form>

<?php if($cartResults != null) { mysqli_free_result($cartResults); $cartResults = null; } ?>
