<?php
include_once "validation.php";
include_once "session.php";

//define("SITE_URL", "http://cosc360.ok.ubc.ca/41271157/the-project-thedrugcartel/src/index.php");
$siteURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$file_info = pathinfo($siteURL);
$siteURL = isset($file_info['extension']) ? str_replace($file_info['filename'] . "." . $file_info['extension'], "", $siteURL) : $siteURL;

//mail(to,subject,message,headers,parameters);

function emailResetPassword($email, $resetLink)
{
  if(!isset($email)) { return "<p>Email for sending was empty</p>"; }
  $validateEmail = validateEmail($email);
  if (!is_bool($validateEmail) || $validateEmail != 1) { return $validateEmail; }

  global $siteURL;

  //We have a valid email to send a first time hello to
  $subject = "Password reset for your H.E.L.P. account";

  $body =  "To confirm and reset your password, choose one of the following options:

                1)     Click the link below.
                2)     Or copy and paste the URL into your browser's address bar.

                $resetLink

            The link above is valid for the next hour, after that it will expire.

            If you did not request this please contact the H.E.L.P. support immediately! (Do not reply to this email)

            Thank you for shopping at H.E.L.P.!

            Sincerely,
            Customer Service at " . $siteURL;

  $from = "help@help.com";
  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  mail($email, $subject, $body, "From:$from");
}

function emailUserOrder($email, $orderid)
{
  //Verify valid email to send their Order Receipt
  if(!isset($email)) { return "<p>Email for sending was empty</p>"; }
  $validateEmail = validateEmail($email);
  if (!is_bool($validateEmail) || $validateEmail != 1) { return $validateEmail; }

  if(!isset($orderid)) { return "<p>No order id was given to the server in sending a receipt to $email.</p>"; }

  //Need to get order
  $order = getOrder($orderid);
  if(!isset($order) || !is_array($order) || !isset($order['orderid'])) { return "<p>Couldn't get order details when trying to create email to send receipt to $email.</p>"; }

  //Need to get each inorder of the order:
  $inorders = getAllInOrderForOrder($order['orderid']);
  if(!isset($inorders) || !is_array($inorders)) { return "<p>Couldn't get ordered products when trying to create email to send receipt to $email.</p>$inorders"; }


  //Now generate email

  global $siteURL;

  //We have a valid email to send a first time hello to
  $subject = "Order Reciept at H.E.L.P. for order {$order['orderid']}";

  $body =  "<html><body><h1>Hello {$_SESSION['fullName']},</h1>

            <p>This email concludes your order with H.E.L.P.</p>
            <p>Below is your receipt:</p>";

  $body .= "<table><thead><th>Item #</th><th>Product Description</th><th>Quantity</th><th>Price Per</th></thead>";
  $i = 1;
  foreach($inorders as $inorder)
  {
    $product = getProduct($inorder[1]);
    if(!isset($product) || !is_array($product)) { return "<p>Couldn't find an item when creating order receipt to send to $email.</p>"; }
    //print_r($product);
    $body .= "<tr><td>Item $i: </td><td>\"{$product['drugName']}\"<td>{$inorder[2]}</td><td>\${$inorder[3]}</td></tr>";
    $i++;
  }

  //Add order total:
  $body .= "<tr></tr><tr><td>Total: </td><td>\$${order['totalPrice']}</td></tr></table>\n\n";

  $body .= "<p>If you did not make this order, please contact the H.E.L.P. support immediately! (Do not reply to this email)</p>

            <p>Thank you for shopping at H.E.L.P.! Your order should be shipped within the next few business days.</p>
            <br>
            <p>Sincerely,</p>
            <p>Customer Service at " . $siteURL ."</p></body></html>";

  $from = "help@help.com";
  $headers = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
  $headers .= "From:$from\r\n";

  ini_set('display_errors', 1);
  mail($email, $subject, $body, $headers);
  //echo $body;
  return true;
}

?>
