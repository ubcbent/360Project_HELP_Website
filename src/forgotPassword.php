<?php
include_once "include_files/session.php";
include_once "include_files/accountProcesses.php";

session_start();

if(isset($_SESSION['email']))
{
  redirect("index.php");
}

/*if(!isset($_SERVER['REQUEST_METHOD']) || !isset($_SERVER['HTTP_REFERER']))
{
  redirect("index.php");
}*/

if(!isset($_GET['email']) && !isset($_GET['key']))
{
  redirect("index.php");
}

if(isset($_GET['email']) && isset($_GET['key']))
{
  $validResetPassword = verifyResetPassword($_GET['email'], $_GET['key']);

  if (!is_array($validResetPassword) || !isset($validResetPassword['email']))
  {
     close();
     sendErrorMessage($validResetPassword);
     //echo "<br><br><br><br><br>-----------$validResetPassword";
     redirect("index.php");
  }

}
?>

<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>H.E.L.P.</title>
  <meta name="description" content="">
  <meta name="viewport" id="myViewPort" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/footer.css">
	<link rel="stylesheet" href="css/forgotPassword.css">
  <link rel="stylesheet" href="css/nav.css">

  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script type="text/Javascript">
    window.jQuerry || document.write("<script src=\"script/jquery-3.3.1.js\"><\/script>");
  </script>
  <script type="text/JavaScript" src="script/validation.js"></script>
  <script type="text/JavaScript" src="script/nav.js"></script>
  <script type="text/JavaScript" src="script/forgotPassword.js"></script>

</head>

<body>
  <!-- Top Menu Bar -->
  <?php include_once "include_files/nav.php" ?>

  <div id="main">
    <form id="forgot-password-form2" method=post action="processForgotPassword2.php">
      <div class="form-top-container">
        <h3>Password Reset</h3>
      </div>
      <h4>You're half way there!</h4>
      <p>Enter a new Password for your account below:</p>
      <div>
        <input type="hidden" name="key" value="<?php echo $_GET["key"]; ?>" required pattern="^([a-zA-Z0-9]+)$" />
        <input type="hidden" name="email" value="<?php echo $_GET["email"]; ?>" required pattern="^([a-zA-Z0-9]+)$" />
      </div>
      <input type="password" placeholder="Password" name="password" required pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" title="Password (UpperCase, LowerCase and Number)" maxlength="24">
      <input type="password" placeholder="Password Check" name="password-check" required pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" title="Password (UpperCase, LowerCase and Number)" maxlength="24">

      <div class="form-button-container">
        <input type="submit" value="Reset Password" text="Reset Password"></input><!--<img src="assets/images/unlock.png"/>-->
      </div>
    </form>
  </div>

  <!--Footer-->
  <footer>
    <?php include_once "include_files/footer.html" ?>
  </footer>

</body>
</html>
