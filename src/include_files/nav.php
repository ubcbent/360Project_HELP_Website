<?php
include_once "include_files/session.php";
include_once "include_files/accountProcesses.php";

$prevURL = basename($_SERVER['HTTP_REFERER']);
?>

<header>
  <nav>
    <ul id="left-nav-mobile">
      <!--<a id="navbar-logo" href="index.html"><img src="assets/images/logo.png" alt=""/></a>-->
      <li id="left-nav-mobile-hamburger"><img src="assets/images/menu.png" alt="Menu"/></li>
    </ul>
    <ul id="left-nav">
      <li><a id="navbar-logo" href="index.php"><img src="assets/images/logo.png" alt="Logo"/></a></li>
      <li><a href="#about-us">ABOUT US</a></li>
      <?php
      if(explode("?", basename($_SERVER['REQUEST_URI']))[0] == "customerpage.php")
      {
        echo "<li class='nav-nonlink'>ACCOUNT</li>";
      }
      ?>
      <li><a href="shop.php">SHOP</a></li>
      <?php
      startSession();
      if(explode("?", basename($_SERVER['REQUEST_URI']))[0] == "item.php")
      {
        echo "<li class='nav-nonlink'>ITEM</li>";
      }
      if(explode("?", basename($_SERVER['REQUEST_URI']))[0] == "checkout.php" && explode("?", basename($prevURL))[0] == "item.php")
      {
        echo "<li><a href='$prevURL'>ITEM</a></li>";
      }
      if(isset($_SESSION['email']))
      {
          echo "<li><a href='checkout.php'>CHECKOUT</a></li>";
      }
      else
      {
        $str = "<li>
                  <a id='nav-register'>REGISTER</a>
                  <a id='nav-register-noscript' href='#'>REGISTER</a> <!-- Will be set to hidden if JavaScript runs -->
                </li>
                <li>
                  <a id='nav-signin'>SIGN IN</a>
                  <a id='nav-signin-noscript' href='#'>SIGN IN</a> <!-- Will be set to hidden if JavaScript runs -->
                </li>";
        echo $str;
      }
      ?>
    </ul>
    <ul id="right-nav">
      <li class="nav-search-container">
        <form method="get" action="shop.php">
          <input class="nav-search-box" type="text" placeholder="Search.." name="search"/>
          <button type="submit"><img src="assets/images/Search.PNG" /></button> <!--&#128269; <i class="fa fa-search"></i>-->
        </form>
      </li>
      <?php
      startSession();
      if(isset($_SESSION['email']))
      {
        //get user profile photo and display here
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
      <li><a id='nav-shopping-cart'><img src='assets/images/shopping-cart.PNG'/></a></li>

    </ul>
    <?php
    startSession();
    if(isset($_SESSION['fullName']))
    {
      echo "<p id='nav-user-greeting'>Hello: {$_SESSION['fullName']}!</p>";
    }
    ?>
  </nav>
</header>

<aside>
  <div id="nav-search-container-mobile">
    <form method="get" action="shop.php">
      <input class="nav-search-box" type="text" placeholder="Search.." name="search"/>
    </form>
  </div>
  <?php startSession(); if (isset($_SESSION['email'])): ?>
    <a href="customerpage.php"><div id="nav-user-account-mouseout"></div></a>
    <div id="nav-shopping-cart-mouseout"></div>
    <div id="body-mouseout"></div>
    <div id="nav-user-selection-container" class="">
      <p><a href="customerpage.php">Your Account</a></p>
      <p><a href="customerpage.php#orders">Your Orders</a><p>
      <p><a href="customerpage.php#change-password">Change Your Password</a></p>
      <p><a href="logout.php">Sign Out</a></p>
    </div>
  <?php else: ?>
    <div id="modal-close-background">
      <span id="modal-close" title="Close Page">&times;</span>
    </div>
  <?php endif; ?>
  <div id="modal">
  </div>
  <?php if(isset($_SESSION['error'])): ?>
    <div id="error-dialog-box">
      <div>
        <a id="error-dialog-close">close</a>
        <?php echo $_SESSION['error']; clearErrorMessage(); ?>
      </div>
    </div>
  <?php endif; ?>
</aside>


<?php startSession(); if(!isset($_SESSION['email'])): ?>

  <!-- Registration Form -->
  <form id="registration-form" method="post" action="processRegistration.php" enctype="multipart/form-data"> <!--http://www.randyconnolly.com/tests/process.php-->
    <div class="form-top-container">
      <h3>Register A New Account</h3>
      <p>Please fill in this form to create a new account</p>
    </div>

    <!--Profile Information-->
    <section>
      <h4>Profile:</h4>
      <div id="registration-profile-circle">
        <img id="registration-profile-photo" src="assets/images/unknown-profile.png"/>
      </div>
      <div id="registration-profile-upload-container">
        <img id="registration-profile-camera-icon" src="assets/images/camera-icon.png" />
        <input type="file" id="registration-photo-uploader" name="profilePhoto" accept="image/*"/>
      </div>

      <div class="registration-select-box" id="registration-select-box-gender">
        <!--<span class="error" id="gender_error"></span>-->
        <label for="registration-gender">Select your gender</label>
        <select id="registration-gender" name="gender">
          <option selected disabled>Select your gender</option>
          <option>Male</option>
          <option>Female</option>
          <option>Other</option>
        </select>
      </div>
    </section>

    <!--Account Info -->
    <section>
      <h4>Account Information:</h4>
      <!--<span class="error" id="cname_error"></span>-->
      <input type="text" placeholder="Full Name" name="cname" required pattern="[a-zA-Z]+[ ][a-zA-Z]+" title="Name Last-Name" maxlength="50">
      <!--<span class="error" id="email_error"></span>-->
      <input type="email" placeholder="Email Address" name="email" maxlength="50" value="" required>
      <!--<span class="error" id="password_error"></span>-->
      <input type="password" placeholder="Password" name="password" required pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" title="Password (UpperCase, LowerCase and Number)" maxlength="24">
      <input type="password" placeholder="Re-Type Password" name="password-check" required pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" title="Password (UpperCase, LowerCase and Number)" maxlength="24">
    </section>

    <!-- Address Info -->
    <section>
    	<h4>Address Information</h4>
      <div class="registration-select-box" id="registration-select-box-country">
        <label for"registrtion-country">Country</label>
        <select id="registration-country" name="country">
          <option selected disabled>Country</option>
          <option>Canada</option>
          <option>USA</option>
        </select>
      </div>
    	<input type="text" placeholder="Province/State" name="prov-state" required pattern="[a-zA-Z]{2}" title="Province: Only Letters" maxlength="2">
    	<input type="text" placeholder="City" required pattern="[a-zA-Z0-9 .]+" title = "City: Letters, Spaces and Periods Only" name="city"  maxlength="24">
    	<input type="text" placeholder="Street" name="street" required pattern="[a-zA-Z0-9 .]+" title = "Street: Letters, Spaces and Periods Only" maxlength="36">
    	<input type="text" placeholder="Postal Code" name="postcode" required pattern="[A-Za-z][0-9][A-Za-z] [0-9][A-Za-z][0-9]" title="Postal Code of Format: A0A 0A0" maxlength="7" style="text-transform:uppercase">
    </section>

    <!-- Payment Details -->
    <section>
      <h4>Payment Details</h4>
      <p>You will not be charged for signing up. We only require credit cards to assure our products are being sold to users of age 19 years or older.</p>
      <div class="registration-cc-selector">
        <input type="radio" name="payment-method" value="visa" id="payment-method-card" checked="true"/>
        <label class="registration-drinkcard-cc" for="payment-method-card"></label>
        <!--<input type="radio" name="payment-method" value="paypal" id="payment-method-paypal"/>
        <label class="registration-drinkcard-cc" for="payment-method-paypal"></label>-->
        <input type="radio" name="payment-method" value="mastercard" id="payment-method-mastercard"/>
        <label class="registration-drinkcard-cc" for="payment-method-mastercard"></label>
      </div>

      <input type="text" placeholder="Card Number" name="cardNum" required pattern= "[0-9]{16}" title="Please Enter Your Card Number With No Spaces" maxlength="16" />
      <input type="text" placeholder="Card CVV" name="cvv" required pattern= "[0-9]{3}" title="Please Enter Your Card's CVC Code (3-digit code)" maxlength="3"/>

      <!-- <input type="text" placeholder="day" onkeypress="return isNumberKey(event)" maxlength="2">-->
      <!--<input class="date" placeholder="" type="date" name="date"/>-->
      <div class="registration-select-box" id="registration-select-box-month">
        <label for="registrtion-month">Card Expiry: Month</label>
        <select id="registration-month" name="month">
          <option selected disabled>Card Expiry: Month</option>
          <option>January</option>
          <option>February</option>
          <option>March</option>
          <option>April</option>
          <option>May</option>
          <option>June</option>
          <option>July</option>
          <option>August</option>
          <option>September</option>
          <option>October</option>
          <option>November</option>
          <option>December</option>
        </select>
      </div>

      <!--TODO: Need to set the dates that are set based on the year of today (php)-->
      <div class="registration-select-box" id="registration-select-box-year">
        <label for="registrtion-year">Card Expiry: Year</label>
        <select id="registration-year" name="year">
          <option selected disabled>Card Expiry: Year</option>
          <?php
          for($year = date("Y"); $year < date("Y") + 6; $year++)
          {
              echo "<option>$year</option>";
          }
          ?>
        </select>
      </div>
    </section>

    <section>
    	<h4>Terms and Conditions</h4>
      <input type="checkbox" id="terms" name="terms"/>
      <label for="terms">I accept the terms and conditions for signing up to this service, and hereby confirm I have read the <a href="assets/agreements/privacy-policy.pdf" target="_blank">Privacy Policy</a></label>
    </section>

    <div class="form-button-container">
      <input type="reset" text="Reset" />
      <input type="submit" value="Submit" text="Login" />
    </div>
  </form>

  <!-- Sign In Container -->
  <div id="signin-form">
    <!-- Sign In Form -->
    <form id="signin-form-form" method="post" action="processLogin.php">
      <div class="form-top-container">
        <h3>Sign In To Your Account</h3>
        <p>Please enter your email and password</p>
      </div>
      <input type="email" placeholder="Email Address" name="email" maxlength="50" value="" required>
      <input type="password" placeholder="Password" name="password" required pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" title="Password (UpperCase, LowerCase and Number)" maxlength="24">

      <!--<input type="checkbox" id="verification" name="verification"/>-->
      <!--<label id="verification-label" for="verification">I am not a robot!</a></label>-->

      <label id="forgot-password">Forgot Password</label>

      <div class="form-button-container">
        <input type="reset" text="Reset" />
        <input type="submit" value="Submit" text="Login" />
      </div>
    </form>

    <form id="forgot-password-form" method=post action="processForgotPassword.php">
      <div class="form-top-container">
        <h3>Forgot Password</h3>
      </div>
      <p>
        We know it's pretty easy forgetting your password when you're always using our products so we make resetting really secure and easy!
        So don't worry, we can help you! We'll get you back up and hitting those midnight tokes in no time!
        Enter your email address and we will send you a recovery link to reset your password.
        Just don't go forgetting it this time!
      </p>
      <input id="reset-email" type="email" placeholder="Email Address" name="email" maxlength="50" value="" required>
      <div class="form-button-container">
        <input id="reset-password-button" type="submit" value="Reset Password" text="Reset Password"></input><!--<img src="assets/images/unlock.png"/>-->
      </div>
    </form>
  </div>

<?php endif; ?>

<!-- Shopping Cart -->
<aside>
  <div id="shopping-cart-container">
    <?php
    startSession();
    if(isset($_SESSION['email'])):
        include_once "shoppingcart.php";
    else:
    ?>
      <div id="shopping-cart-form">
        <div class="shopping-cart-header" id="shopping-cart-header">
          <span>&rang;</span>
          <h3>CART</h3>
        </div>
        <ul id="shopping-cart-element-container">
          <li class="shopping-cart-element">
            <div class="shopping-cart-element-rightside">
              <h4>Must be logged in!</h4>
            </div>
          </li>
        </ul>
      </div>
    <?php endif; ?>
  </div>
</aside>
