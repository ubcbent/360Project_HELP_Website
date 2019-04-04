<?php
//include_once "account.php";
include_once "include_files/session.php";
include_once "include_files/accountProcesses.php";
?>

<header>
  <nav>
    <ul id="left-nav-mobile">
      <!--<a id="navbar-logo" href="index.html"><img src="assets/images/logo.png" alt=""/></a>-->
      <li id="left-nav-mobile-hamburger"><img src="assets/images/menu.png" alt="Menu"/></li>
    </ul>
    <ul id="left-nav">
      <li><a id="navbar-logo" href="admin-home.php"><img src="assets/images/logo.png" alt=""/></a></li>
      <li><a href="admin-new-product.php">Add Product</a></li>
      <li><a href="admin-products.php">View/Edit Products</a></li>
      <?php
      if(explode("?", basename($_SERVER['REQUEST_URI']))[0] == "admin-products-details.php")
      {
        echo "<li class='nav-nonlink'>Product Details</li>";
      }
      ?>
      <li><a href="admin-customers.php">Customers</a></li>
      <li><a href="admin-orders.php">Sales</a></li>
      <li><a href="admin-comments.php">Reviews</a></li>
      <li><a href="logout.php">Sign Out</a></li>
    </ul>
    <ul id="right-nav">
      <li><a id="nav-user">
        <?php
        startSession();
        if(isset($_SESSION['email']))
        {
          //TODO: get user profile photo and display here
          echo "<li><a id='nav-user'>";

          if(isset($_SESSION['pic_id']))
          {
            //Now we get the user image:
            $file = getPhoto($_SESSION['pic_id']);
            if(!is_array($file)) { exit($file); }

            //echo $file['contentType'];
            if(isset($file["contentType"]) && isset($file["image"])) { echo '<img id="nav-user-profile-picture" src="data:image/'. $file["contentType"] . ';base64,' . base64_encode($file["image"]) . '"/>'; }
            else { echo "<img id='nav-user-profile-picture' src='assets/images/unknown-profile.png' alt='Profile Picture'/>"; }
          }
          else { echo "<img id='nav-user-profile-picture' src='assets/images/unknown-profile.png' alt='Profile Picture'/>"; }

          echo "</a></li>";
          //echo "<li><a id='nav-shopping-cart'><img src='assets/images/shopping-cart.PNG'/></a></li>"
        }
        ?>
        <!--<img id="nav-user-profile-picture" src="assets/images/unknown-profile.png" alt="Profile Picture"/>-->
      </a></li>
    </ul>
    <p id="nav-user-greeting">Hello: admin!</p>
  </nav>
</header>
<aside>
<?php if(isset($_SESSION['error'])): ?>
  <div id="error-dialog-box">
    <div>
      <a id="error-dialog-close">close</a>
      <?php echo $_SESSION['error']; clearErrorMessage(); ?>
    </div>
  </div>
<?php endif; ?>
</aside>
