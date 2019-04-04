<?php
include_once "include_files/session.php";
include_once "include_files/db_info.php";
include_once "include_files/orderProcesses.php";
include_once "include_files/accountProcesses.php";

startSession();

if(!isset($_SESSION['email']))
{
  redirect("index.php");
}

if(isset($_SESSION['admin']))
{
  if($_SESSION['admin'] == "admin") redirect("admin-home.php");
}

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

$sql = "SELECT *  FROM orders WHERE email='{$_SESSION['email']}';";
$Results = mysqli_query($connection, $sql);

if(is_bool($Results) && !$Results)
{
  sendErrorMessage("<p>Your cart was empty and there was nothing for you to place an order on.</p>");
  redirect($prevURL);
}

$customerAccount = getUser($_SESSION['email']);
if(!is_array($customerAccount)) { sendErrorMessage($row); redirect($prevURL); }

?>

<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>H.E.L.P.</title>

  <meta name="description" content="">
  <meta name="viewport" id="myViewPort" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="css/reset.css"/>
  <link rel="stylesheet" href="css/nav.css"/>
  <link rel="stylesheet" href="css/customer.css">
  <link rel="stylesheet" href="css/footer.css">

  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script type="text/Javascript">
    window.jQuerry || document.write("<script src=\"script/jquery-3.3.1.js\"><\/script>");
  </script>
  <script type="text/JavaScript" src="script/validation.js"></script>
  <script type="text/JavaScript" src="script/nav.js"></script>
  <script type="text/JavaScript" src="script/customer.js"></script>
  </head>

<body>
  <?php include_once "include_files/nav.php"; ?>

  <div class="card">
    <div id="your-account">
      <?php
      if(isset($_SESSION['pic_id']))
      {
        //Now we get the user image:
        $file = getPhoto($_SESSION['pic_id']);

        if(isset($file["contentType"]) && isset($file["image"])) { echo '<img id="user-profile-picture" src="data:image/'. $file["contentType"] . ';base64,' . base64_encode($file["image"]) . '" alt="Profile Picture" />'; }
        else { echo "<img id='user-profile-picture' src='assets/images/unknown-profile.png' alt='Profile Picture'/>"; }
      }
      else { echo "<img id='user-profile-picture' src='assets/images/unknown-profile.png' alt='Profile Picture'/>"; }
      //<img src="assets/images/unknown-profile.png" alt="your photo" style="width:70%">


      if(isset($_SESSION['fullName'])){
        echo "<h1>".$_SESSION['fullName']."</h1>";
      } ?>
      <hr></hr>
      <?php
      if(isset($customerAccount['gender'])){
        echo "<p class='title'>";
        switch($customerAccount['gender'])
        {
          case 'M':
            echo "Male";
            break;
          case 'F':
            echo "Female";
            break;
          case 'O':
            echo "Other";
            break;
        }
        echo "</p>";
      }
      if(isset($_SESSION['email'])){
        echo "<p class='title'>".$_SESSION['email']."</p>";
      }

      $validBilling = getBillingInfo($_SESSION['email'], $customerAccount['billingType']);
      if(!isset($validBilling) || !is_array($validBilling)) { sendErrorMessage($validBilling); }
      echo "<p class='title'>{$validBilling['street']}, {$validBilling['city']}, {$validBilling['state_province']}, {$validBilling['country']}</p>";
      ?>
      <!--<p class="title">55 acadmic road, BC, Kelowna</p>-->

    </div>
    <button id="orders" class="collapsible">View Your Orders</button>
      <div class="content">
        <table>
          <tr>
            <th>Order ID</th>
            <th>Total Price</th>
          </tr>
          <tr>
            <?php
            while($row = mysqli_fetch_row($Results))
            {
              if(is_array($row))
              {
                echo "<tr><td>{$row[0]}</td><td>{$row[1]}</td></tr>";
              }
            }

            ?>
          </tr>
        </table>
      </div>

      <button id="change-password" class="collapsible">change your password</button>
      <div class="content">
        <form name="frmChange" role="form" class="form-signin" method="POST" action="changepword_script.php">

          <div class="form-group">
            <!-- basic things to correct the form, internet source as refernce -->
            <label for="InputPassword2">New Password</label>
            <input type="password" class="form-control" id="InputPassword2" placeholder="New Password" name="newPassword">
            <label for="InputPassword3">Confirm New Password</label>
            <input type="password" class="form-control" id="InputPassword3" placeholder="Confirm Password" name="confirmPassword">  </div>
            <button class="btn btn-lrg btn-default btn-block" type="submit" value="send">Change it</button>

          </div>
     </form>
      </div>
    <div class="change">
      <a href="logout.php"><button value="logout" id="left">log out</button></a>
      <a href="processLogin.php"><button value="changeaccount" id="right">change account</button>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <?php //include_once "include_files/footer.html"; ?>
  </footer>
</body>
</html>
<?php if($Results != null) { mysqli_free_result($Results); $Results = null; } ?>
