<?php
include_once "validation.php";
include_once "db_info.php";
include_once "email.php";

function login($email, $password, $table)
{
  global $connection;
  global $results;

  if(isset($email) && isset($password) && isset($table))
  {
    if (!connect())
    {
      return "<p>Unable to connect to database!</p>";
    }
    if($table != "customer" && $table != "administrator")
    {
      return "<p>The type of user trying to be accessed from the database didn't exist and an internal server error occured. If this happens again please contact support.</p>";
    }

    //good connection, so do your thing
    //$username = mysqli_real_escape_string($connection, $username);
    $password = mysqli_real_escape_string($connection, $password);

    $row = getUser($email, $table);
    if(!is_array($row)) { close(); return $row; } //return error when searching for username

    $passwordValidation = validatePassword($password);
    if(!is_bool($passwordValidation) || $passwordValidation != 1) { close(); return $passwordValidation; }

    //All entered information is valid so we can insert into database

    //Hash the password
    $password = MD5($password);

    if (!isset($row['email']) || !isset($row['password']))
    {
      return "<p>We could not find that email in our database, please try again.</p> (2)";
    }

    if($row['password'] != $password || $row['email'] != $email)
    {
      $output = "<p>If the error continues to occur please try to recover your email and password imediately.</p>";
      return "<p>Incorrect password, please try again.</p>" . $output;
    }

    session_start();

    $_SESSION['email'] = $email;
    $_SESSION['pic_id'] = $row['pic_id'];

    if($table == "customer") { $_SESSION['fullName'] = $row['fullName']; }

    return true;
  }
  else
  {
    return "<p>Please enter both email and password and try again.</p>";
  }
}

function changePassword($email, $password, $newPassword, $newPassword2)
{
  global $connection;
  global $results;

  if(isset($email) && isset($password) && isset($newPassword) && isset($newPassword2))
  {
    if(!isset($_SESSION)) { session_start(); }

    if(!isset($_SESSION['email']))
    {
      $loggedIn = login($email, $password);
      if($loggedIn != 1) { return $loggedIn; }
    }

    if(!connect())
    {
      return "<p>Unable to connect to database!</p>";
    }

    $newPassword = mysqli_real_escape_string($connection, $newPassword);
    $newPassword2 = mysqli_real_escape_string($connection, $newPassword2);

    //validate new passwords:
    $validateNewPassword = validatePassword($newPassword);
    if(!is_bool($validateNewPassword) || $validateNewPassword != 1) { close(); return $validateNewPassword; }

    $validateNewPassword2 = validatePassword($newPassword2);
    if(!is_bool($validateNewPassword2) || $validateNewPassword2 != 1) { close(); return $validateNewPassword2; }

    if($newPassword != $newPassword2)
    {
      close();
      return "<p>New passwords do not match, please try entering them again.</p><p><a href='lab9-3.html'>Return to user entry</a></p>";
    }

    //All entered information is valid so we can insert into database

    if($password == $newPassword)
    {
      close();
      return "<p>The new password you have entered is the same as the old password, please enter a new password and try again.</p>";
    }

    $newPassword = MD5($newPassword);

    //the username and password are valid
    //log the user in
    $sql = "UPDATE customer SET password='$newPassword' WHERE email='$email';";

    if (mysqli_query($connection, $sql))
    {
      close();
      if(!isset($_SESSION)) { session_start(); }

      //$firstName = getUser($username)["firstName"];
      //if (!isset($firstName)) { $firstName = "user"; }
      return "<p>Password for " . $_SESSION['fullName'] . " has been changed successfully.</p>";
    }
    else
    {
      close();
      return "<p>A problem occured with the server while trying to update the password.</p>";
    }
  }
  else
  {
    return "<p>Please fill in the username and password fields and try again.</p>";
  }
}


function getUser($email, $table="customer")
{
  global $connection;
  global $results;
  global $validTables;

  if (isset($email))
  {
    if(!connect())
    {
      close();
      return "<p>Unable to connect to database!</p>";
    }

    $email = mysqli_real_escape_string($connection, $email);
    $table = mysqli_real_escape_string($connection, $table);

    $validateEmail = validateEmail($email);
    if(!is_bool($validateEmail) || $validateEmail != 1) { return $validateEmail; }

    if(!isset($table) || !in_array($table, $validTables))
    {
        return "<p>In searching for a user in our database, the databased seemed to have recieved information that was entered or passed incorrectly. If the error continues to persit please contact H.E.L.P. for help. (1)</p>";
    }

    // valid username, alphanumeric & longer than or equals 5 chars

    $sql = "SELECT * FROM $table WHERE email='$email';";

    $results = mysqli_query($connection, $sql);

    $row = mysqli_fetch_assoc($results);

    if (!isset($row['email']) || !isset($row['password'])) //|| !isset($row['fullName']) || !isset($row['gender']) || !isset($row['billingType'])
    {
      close();
      return "<p>We could not find that user in our database, please try again. (1)</p>";
    }

    //close();
    return $row;
    //return array("username" => $row['username'], "firstName" => $row['firstName'], "lastName" => $row['lastName'], "email" => $row['email']);

  }

  return "<p>Sorry there was a problem with the data sent to the server, please go back to the form and try again.</p>";
}

function newUser($userArray)
{
  global $connection;
  global $results;

  if(isset($userArray["gender"]) && isset($userArray["cname"]) && isset($userArray["email"])
    && isset($userArray["password"]) && isset($userArray["password-check"]) && isset($userArray["country"])
    && isset($userArray["prov-state"]) && isset($userArray["city"]) && isset($userArray["street"])
    && isset($userArray["postcode"]) && isset($userArray["payment-method"]) && isset($userArray["cardNum"])
    && isset($userArray["cvv"]) && isset($userArray["month"]) && isset($userArray["year"]) && isset($userArray["terms"]))
    //&& isset($_FILES['profilePhoto']))
  {
    if (!connect()) { return "<p>Unable to connect to database!</p>"; }

    //good connection, so do your thing
    $gender = mysqli_real_escape_string($connection, $userArray["gender"]);
    $cname = mysqli_real_escape_string($connection, $userArray["cname"]);
    $email = mysqli_real_escape_string($connection, $userArray["email"]);
    $password = mysqli_real_escape_string($connection, $userArray["password"]);
    $password2 = mysqli_real_escape_string($connection, $userArray["password-check"]);
    $country = mysqli_real_escape_string($connection, $userArray["country"]);
    $provState = mysqli_real_escape_string($connection, $userArray["prov-state"]);
    $city = mysqli_real_escape_string($connection, $userArray["city"]);
    $street = mysqli_real_escape_string($connection, $userArray["street"]);
    $postCode = mysqli_real_escape_string($connection, $userArray["postcode"]);
    $payment = mysqli_real_escape_string($connection, $userArray["payment-method"]);
    $cardNum = mysqli_real_escape_string($connection, $userArray["cardNum"]);
    $cvv = mysqli_real_escape_string($connection, $userArray["cvv"]);
    $month = mysqli_real_escape_string($connection, $userArray["month"]);
    $year = mysqli_real_escape_string($connection, $userArray["year"]);
    $terms = mysqli_real_escape_string($connection, $userArray["terms"]);

    //Validate Gender:
    $gender = substr($gender, 0, 1);
    $validateGender = validateGender($gender);
    if (!is_bool($validateGender) || $validateGender != 1) { close(); return $validateGender; }

    //Validate Customer Name:
    $validateName = validateName($cname);
    if (!is_bool($validateName) || $validateName != 1) { close(); return $validateName; }

    //Validate Email:
    $validateEmail = validateEmail($email);
    if (!is_bool($validateEmail) || $validateEmail != 1) { close(); return $validateEmail; }

    //Validate Password
    $validatePassword = validatePassword($password);
    if (!is_bool($validatePassword) || $validatePassword != 1) { close(); return $validatePassword; }

    //Validate Password-Check
    $validatePassword2 = validatePassword($password2);
    if (!is_bool($validatePassword2) || $validatePassword2 != 1) { close(); return $validatePassword2; }

    //Validate Shipping/Billing Address:

    //Validate Country
    $validateCountry = validateCountry($country);
    if (!is_bool($validateCountry) || $validateCountry != 1) { close(); return $validateCountry; }

    //Validate Province/State
    $validateProvState = validateProvState($provState);
    if (!is_bool($validateProvState) || $validateProvState != 1) { close(); return $validateProvState; }

    //Validate City
    $validateCity = validateCity($city);
    if (!is_bool($validateCity) || $validateCity != 1) { close(); return $validateCity; }

    //Validate Street
    $validateStreet = validateStreet($street);
    if (!is_bool($validateStreet) || $validateStreet != 1) { close(); return $validateStreet; }

    //Validate Postal Code
    $postCode = str_replace(" ", "", $postCode);
    $validatePostCode = validatePostCode($postCode);
    if (!is_bool($validatePostCode) || $validatePostCode != 1) { close(); return $validatePostCode; }

    //Validate The Card Information:

    //Validate Payment Method
    $validatePayment = validatePayment($payment);
    if (!is_bool($validatePayment) || $validatePayment != 1) { close(); return $validatePayment; }

    //Validate Card Number
    $validateCardNumber = validateCardNumber($cardNum);
    if (!is_bool($validateCardNumber) || $validateCardNumber != 1) { close(); return $validateCardNumber; }

    //Validate CVV
    $validateCVV = validateCVV($cvv);
    if (!is_bool($validateCVV) || $validateCVV != 1) { close(); return $validateCVV; }

    //Validate Month
    $validateMonth = validateMonth($month);
    if (!is_bool($validateMonth) || $validateMonth != 1) { close(); return $validateMonth; }

    //Validate Year
    $validateYear = validateYear($year);
    if (!is_bool($validateYear) || $validateYear != 1) { close(); return $validateYear; }

    //Check that the date of the card is ok
    $date = $year . " " . $month;
    $date = date("Y/m", strtotime($date));
    if ($date < date("Y/m"))
    {
      close();
      return "<p>The expiry date you have entered is expired, you may need to get a new credit card or use a different one.<p>";
    }

    //Create a date string using month and year
    $date = date("m", strtotime($month)) . "/" . substr($year, 2, 2);

    //Validate Terms CheckBox
    $validateTerms = validateTerms($terms);
    if (!is_bool($validateTerms) || $validateTerms != 1) { close(); return $validateTerms; }


    $sql = "SELECT * FROM customer WHERE email='$email';";
    $results = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($results);

    if (isset($row['email']) )
    {
      close();
      return "<p>User already exists with this username and/or email</p><p><a href='newuser.html'>Return to user entry</a></p>";
    }

    if($password != $password2)
    {
      close();
      return "<p>Passwords do not match, please try entering them again.</p><p><a href='lab9-1.html'>Return to user entry</a></p>";
    }

    //All entered information is valid so we can insert into database

    //Hash the password
    $password = MD5($password);

    //Verify and upload profile photo:
    //Insert Profile Photo:
    $pic_id = null;
    $sql = "";
    if(isset($_FILES['profilePhoto']))
    {
      $pic_id = getNextID("picture", "pic_id");
      if (!is_numeric($pic_id)) { close(); return $pic_id; }

      $uploadPhoto = uploadPhoto($pic_id);
      if (!is_bool($uploadPhoto) || $uploadPhoto != 1) { return $uploadPhoto; }
    }

    //Insert Customer Info:
    if(isset($pic_id))
    {
      $sql = "INSERT INTO customer (email, password, fullName, gender, pic_id, billingType) VALUES ('$email', '$password', '$cname', '$gender', '$pic_id', '$payment');";
    }
    else
    {
        $sql = "INSERT INTO customer (email, password, fullName, gender, billingType) VALUES ('$email', '$password', '$cname', '$gender', '$payment');";
    }

    if (!mysqli_query($connection, $sql))
    {
      close();
      return "<p>A problem occured with the server while trying to create a new user.</p>";
    }

    //Insert Billing Info:
    $bill_id = getNextID($payment, "bill_id");
    if (!is_numeric($bill_id)) { close(); return $bill_id; }

    $sql = "INSERT INTO $payment (bill_id, country, state_province, city, street, zip_postalcode, cardNumber, expiryDate, cvv, email) VALUES ('$bill_id', '$country', '$provState', '$city', '$street', '$postCode', '$cardNum', '$date', '$cvv', '$email');";

    if (!mysqli_query($connection, $sql))
    {
      close();
      return "<p>A problem occured with the server while trying to create a new user.</p>";
    }

    close();

    $_SESSION['email'] = $email;
    $_SESSION['fullName'] = $cname;
    $_SESSION['pic_id'] = $pic_id;
    return "<p>An account for the user $cname has been created.</p><p>Please verify the email sent to you.";

  }
  else
  {
    return "<p>It seems that not all fields were filled in, please go back to the form and try again.</p>";
  }
}

function uploadPhoto($pic_id)
{
  global $connection;

  if (isset($_FILES["profilePhoto"]) && isset($pic_id))
  {
    if (!connect())
    {
      return "<p>Unable to connect to database!</p>";
    }

    $uid = mysqli_real_escape_string($connection, $pic_id);
    if(!is_numeric($pic_id)) { return "<p>There was a problem with the picture id that was used when attempting to upload a photo.</p>"; }

    //Verify Profile Picture:

    $photo = $_FILES["profilePhoto"];
    $imageName = basename($photo["name"]);
    $imageType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    $imageSize = $photo["size"];

    if($imageType != "jpg" && $imageType != "jpeg" && $imageType != "png" && $imageType != "gif")
    {
      close();
      return "<p>The image file type you have selected was not an allowed image type, please select an image file that is one of: JPG, JPEG, PNG, or GIF.</p>";
    }

    if ($imageSize > 500000)
    {
      close();
      return "<p>Sorry but the image file you tried to upload was too big.</p>";
    }

    //Correct image type, now upload to the database:

    $imagedata = file_get_contents($_FILES['profilePhoto']['tmp_name']); //store the contents of the files in memory in preparation for upload

    $sql = "INSERT INTO picture (pic_id, contentType, image) VALUES(?,?,?);";
    $stmt = mysqli_stmt_init($connection);
    mysqli_stmt_prepare($stmt, $sql); // register the query

    $null = NULL;
    mysqli_stmt_bind_param($stmt, "isb", $uid, $imageType, $null);
    mysqli_stmt_send_long_data($stmt, 2, $imagedata); // This sends the binary data to the third variable location in the prepared statement

    $result = mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt)); //run the statement

    mysqli_stmt_close($stmt);

    //close();
    return true;
  }
  else
  {
    return "<p>It seems that not all fields were filled in to enter your profile photo into our database, please go back to the form and try again. If the error occurs again, please contact support.</p>";
  }
}

function getPhoto($pic_id)
{
  global $connection;

  if (isset($pic_id))
  {
    if (!connect())
    {
      return "<p>Unable to connect to database!</p>";
    }

    $pic_id = mysqli_real_escape_string($connection, $pic_id);
    if(!is_numeric($pic_id)) { return "<p>There was a problem with the Pictrue ID that was used when attempting to  retrieve a photo.</p>"; }

    $sql = "SELECT contentType, image FROM picture where pic_id=$pic_id;";
    $results = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($results);

    if(!isset($row['contentType']) || !isset($row['image']))
    {
      close();
      return "<p>We could not find a profile image in our database.</p>";
    }

    close();
    return $row;
  }
  else
  {
    return "<p>It seems that not all fields were filled in, please go back to the form and try again.</p>";
  }
}

function sendPasswordReset($email)
{
  global $connection;
  global $results;

  if (!connect())
  {
    return "<p>Unable to connect to database!</p>";
  }

  $row = getUser($email, "customer");
  if(!is_array($row)) { close(); return $row; } //return error when searching for username

  //Generate a randomized Key Code to use:
  $length = rand(24, 47);
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $randomString = "";
  for($i = 0; $i < $length; $i++)
  {
    $randomString .= $characters[rand(0, strlen($characters) - 1)];
  }

  $date = date('Y/m/d-H:i:s');
  $dateHash = MD5($date);
  $splitAt = rand(0, strlen($randomString));

  $key = substr($randomString, 0, $splitAt) . substr($dateHash, rand(0, strlen($dateHash) - 19), rand(21, strlen($dateHash))) . substr($randomString, $splitAt + 1, strlen($randomString));

  //Get the rest of the link to send the user
  $baseURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  $file_info = pathinfo($baseURL);
  $baseURL = isset($file_info['extension']) ? str_replace($file_info['filename'] . "." . $file_info['extension'], "", $baseURL) : $baseURL;
  $baseURL .= "forgotPassword.php";

  $fullURL = "$baseURL?key=$key&email=" . MD5($email);

  //Now we need to send the user an email with the key
  emailResetPassword($email, $fullURL);

  //Now set the forgotPasswordHash in customer
  $forgotPasswordHash = $date . "&" . $key;

  $sql = "UPDATE customer SET forgotPasswordHash='$forgotPasswordHash' WHERE email='$email';";

  if (mysqli_query($connection, $sql))
  {
    close();
    return "<p>Email to " . $email . " has been sent with link to reset password.</p>";
  }
  else
  {
    close();
    return "<p>A problem occured with the server while trying to update user's account with a password reset.</p>";
  }
}

function verifyResetPassword($emailHash, $key)
{
  global $connection;
  global $results;

  if (!connect())
  {
    return "<p>Unable to connect to database!</p>";
  }

  $emailHash = mysqli_real_escape_string($connection, $emailHash);
  $key = mysqli_real_escape_string($connection, $key);

  $validateEmailHash = validateKeyCode($emailHash);
  if (!is_bool($validateEmailHash) || $validateEmailHash != 1) { close(); return $validateEmailHash; }

  $validateKey = validateKeyCode($key);
  if (!is_bool($validateKey) || $validateKey != 1) { close(); return $validateKey; }

  //Now need to get all the users and check if emailHash matches their hashed email (since we can't unhash the hashed password)
  $sql = "SELECT email, forgotPasswordHash FROM customer WHERE forgotPasswordHash IS NOT NULL;";
  $results = mysqli_query($connection, $sql);

  while($row = mysqli_fetch_row($results))
  {
    if(isset($row[0]) && isset($row[1]))
    {
      $resetPasswordHashSplit = explode('&', $row[1]);
      if(MD5($row[0]) == $emailHash && sizeof($resetPasswordHashSplit) == 2)
      {
        if($resetPasswordHashSplit[1] == $key)
        {
          //Valid info entered but need to check and make sure that the user asked for it to be reset recently and not say a year ago
          //Going with that it's been an hour since the user asked to reset their password
          $dateRequested = $resetPasswordHashSplit[0];
          $dateRequestedSplit = explode("-", $dateRequested);
          $timeRequested = strtotime($dateRequestedSplit[0] . " " . $dateRequestedSplit[1]);
          $timeExpired = strtotime('+1 hour', $timeRequested);

          $currentTime = strtotime(date('Y/m/d H:i:s'));

          //close(); return getUser($row[0]);
          if($currentTime > $timeRequested && $currentTime < $timeExpired) {  close(); return getUser($row[0]); }
          else
          {
            close();
            return "<p>The key that you have requested to use has expired.</p>";
          }
        }
      }
    }
  }


  close();
  return false;
}

function resetPassword($emailHash, $key, $newPassword, $newPassword2)
{
  global $connection;
  global $results;

  if(isset($emailHash) && isset($key) && isset($newPassword) && isset($newPassword2))
  {
    startSession();
    $validResetPassword;

    if(!isset($_SESSION['email']))
    {
      $validResetPassword = verifyResetPassword($emailHash, $key);
      if (!is_array($validResetPassword) || !isset($validResetPassword['email'])) { close(); return $validResetPassword;}
    }
    else
    {
      removeResetPasswordHash();
      close();
      return "<p>You are already logged in, if you would like to just change your password please navagate to the account settings and change your password from there.</p>";
    }

    if(!connect())
    {
      return "<p>Unable to connect to database!</p>";
    }

    $email = $validResetPassword['email'];
    $password = $validResetPassword['password'];

    $newPassword = mysqli_real_escape_string($connection, $newPassword);
    $newPassword2 = mysqli_real_escape_string($connection, $newPassword2);

    //validate new passwords:
    $validateNewPassword = validatePassword($newPassword);
    if(!is_bool($validateNewPassword) || $validateNewPassword != 1) { close(); return $validateNewPassword; }

    $validateNewPassword2 = validatePassword($newPassword2);
    if(!is_bool($validateNewPassword2) || $validateNewPassword2 != 1) { close(); return $validateNewPassword2; }

    if($newPassword != $newPassword2)
    {
      close();
      return "<p>New passwords do not match, please try entering them again.</p><p><a href='lab9-3.html'>Return to user entry</a></p>";
    }

    //All entered information is valid so we can insert into database

    if($password == $newPassword)
    {
      close();
      return "<p>The new password you have entered is the same as the old password, please enter a new password and try again.</p>";
    }

    $newPassword = MD5($newPassword);

    //the username and password are valid
    //log the user in
    $sql = "UPDATE customer SET password='$newPassword' WHERE email='$email';";

    if (mysqli_query($connection, $sql))
    {
      removeResetPasswordHash($email);
      close();
      return true;
    }
    else
    {
      close();
      return "<p>A problem occured with the server while trying to update the password.</p>";
    }
  }
  else
  {
    return "<p>Please fill in the username and password fields and try again.</p>";
  }
}

function removeResetPasswordHash($email)
{
  global $connection;

  if (!connect())
  {
    return "<p>Unable to connect to database!</p>";
  }

  $validateEmail = validateEmail($email);
  if (!is_bool($validateEmail) || $validateEmail != 1) { close(); return $validateEmail; }

  $sql = "UPDATE customer SET forgotPasswordHash=NULL WHERE email='$email';";

  if (mysqli_query($connection, $sql))
  {
    close();
    return true;
  }
  else
  {
    close();
    return "<p>A problem occured with the server while trying to update user's account.</p>";
  }
}

function getBillingInfo($email, $payment)
{
  global $connection;

  if(!isset($email) || !isset($payment))
  {
    return "<p>There wasn't a customer passed to get your items from the database, if the error continues to occur please contact support.</p>";
  }

  if (!connect()) { return "<p>Unable to connect to database!</p>"; }

  $email = mysqli_real_escape_string($connection, $email);
  $payment = mysqli_real_escape_string($connection, $payment);

  $validateEmail = validateEmail($email);
  if (!is_bool($validateEmail) || $validateEmail != 1) { close(); return $validateEmail; }

  $validatePayment = validatePayment($payment);
  if (!is_bool($validatePayment) || $validatePayment != 1) { close(); return $validatePayment; }

  $sql = "SELECT * FROM $payment WHERE email='$email';";
  $results = mysqli_query($connection, $sql);

  $row = mysqli_fetch_assoc($results);

  if (!isset($row['bill_id']))
  {
    close();
    return "<p>We could not find a product in our database, if the error continues to exist please contact support.</p>";
  }

  close();
  return $row;
}
?>
