<?php

$validMonths = array("january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december");

function nonStringError($field)
{
    return "<p>The data that was entered in the $field field was not of the correct format and may have had incorrect characters, please re-enter the data and try again.</p>";
}


function emptyDataError($field)
{
  return "<p>You must fill in the $field field, please enter all fields and try again.</p>";
}

function test_input($data)
{
   $data2 = trim($data);
   $data2 = stripslashes($data2);
   $data2 = htmlspecialchars($data2);
   return $data == $data2;
}

function validateGender($gender)
{
  if(!isset($gender)) { return emptyDataError("gender"); }
  if(!is_string($gender)) { return nonStringError("gender"); }
  if(!preg_match("/^[a-zA-Z]*$/", $gender) || !test_input($gender) || strlen($gender) > 1)
  {
    return "<p>The gender you have entered did not match the required format or was too long.</p>";
  }
  if(strtolower($gender) != "m" && strtolower($gender) != "f" && strtolower($gender) != "o") //(male, female, or other)
  {
    return "<p>We know it's " . date("Y") . ", and here at H.E.L.P. we don't descriminate, but we only have limitted web resources and we apologize for the inconvenience but that gender you entered wasn't in the options.</p>";
  }

  return true;
}

function validateName($name)
{
  if(!isset($name)) { return emptyDataError("name"); }
  if(!is_string($name)) { return nonStringError("name"); }
  if (!preg_match("/^([a-zA-Z' ]+)$/", $name) || !test_input($name) || strlen($name) > 50) //"/^[a-zA-Z ]*$/"
  {
    return "<p>A name you have entered did not match the required format or was too long.</p>";
  }

  return true;
}

function validateEmail($email)
{
  if(!isset($email)) { return emptyDataError("email"); }
  if(!is_string($email)) { return nonStringError("email"); }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !test_input($email) || strlen($email) > 50)
  {
    return "<p>The email you have entered did not match the required format or was too long.</p>";
  }

  return true;
}

function validatePassword($password)
{
  if(!isset($password)) { return emptyDataError("password"); }
  if(!is_string($password)) { return nonStringError("password"); }
  if(!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/", $password) || !test_input($password) || strlen($password) > 50) //"/^S*(?=S{3,})(?=S*[a-z])(?=S*[A-Z])(?=S*[d])(?=S*[W])S*$/"
  {
    return "<p>The password you have entered did not match the required format or was too long.</p>";
  }

  return true;
}

function validateCountry($country)
{
  if(!isset($country)) { return emptyDataError("country"); }
  if(!is_string($country)) { return nonStringError("country"); }
  if(!preg_match("/^([a-zA-Z' ]+)$/", $country) || !test_input($country) || strlen($country) > 20)
  {
    return "<p>The country you have entered did not match the required format or was too long.</p>";
  }

  return true;
}

function validateProvState($stateProv)
{
  if(!isset($stateProv)) { return emptyDataError("State/Province"); }
  if(!is_string($stateProv)) { return nonStringError("State/Province"); }
  if(!preg_match("/^[a-zA-Z][a-zA-Z]$/", $stateProv) || !ctype_alpha($stateProv) || !test_input($stateProv) || strlen($stateProv) > 2)
  {
    return "<p>The country you have entered did not match the required format or was too long.</p>";
  }

  return true;
}

function validateCity($city)
{
  if(!isset($city)) { return emptyDataError("city"); }
  if(!is_string($city)) { return nonStringError("city"); }
  if (!preg_match("/^([a-zA-Z' .]+)$/", $city) || !test_input($city) || strlen($city) > 20)
  {
    return "<p>The city name you have entered did not match the required format or was too long.</p>";
  }

  return true;
}

function validateStreet($street)
{
  if(!isset($street)) { return emptyDataError("street"); }
  if(!is_string($street)) { return nonStringError("street"); }
  if (!preg_match("/^([a-zA-Z'0-9 .]+)$/", $street) || !test_input($street))
  {
    return "<p>The street name you have entered just didn't make the cut, you're options are to either get off the streets or get a new street name.</p>";
  }
  if(strlen($street) > 20)
  {
    return "<p>The street name you have entered just didn't make the cut (it was longer than what patience I have left waiting on you to fill out this form), you're options are to either get off the streets or get a new street name.</p>";
  }

  return true;
}

function validatePostCode($postCode)
{
  if(!isset($postCode)) { return emptyDataError("postal-code"); }
  if(!is_string($postCode)) { return nonStringError("postal-code"); }
  $postCode = str_replace(" ", "", $postCode);
  if(!preg_match("/^[A-Za-z][0-9][A-Za-z][0-9][A-Za-z][0-9]$/", $postCode) || !test_input($postCode) || strlen($postCode) != 6)
  {
      return "<p>The postal code you have entered did not match the required format (X0X 0X0) or was too long.</p>";
  }

  return true;
}

function validatePayment($payment)
{
  if(!isset($payment)) { return emptyDataError("payment method"); }
  if(!is_string($payment)) { return nonStringError("payment method"); }
  if(!preg_match("/^[a-zA-Z]*$/", $payment) || !ctype_alpha($payment) || !test_input($payment) || strlen($payment) > 10)
  {
    return "<p>The payment method you have entered did not match the required format or was too long.</p>";
  }
  if(strtolower($payment) != "visa" && strtolower($payment) != "mastercard")
  {
    return "<p>The payment method that you have entered was not one in the selection. Please try filling the form out again. If you're trying to hack our system, you're kind is not wanted here.</p>";
  }

  return true;
}

function validateCardNumber($cardNum)
{
  if(!isset($cardNum)) { return emptyDataError("card number"); }
  if(!is_numeric($cardNum)) { return nonStringError("card number"); } //Integer Error in this case but not a big deal
  if(!preg_match("/^[0-9]{16}$/", $cardNum) || !test_input($cardNum) || strlen($cardNum) != 16)
  {
    return "<p>The card number you have entered did not match the required format or was not a 16 character code.</p>";
  }

  return true;
}

function validateCVV($cvv)
{
  if(!isset($cvv)) { return emptyDataError("cvv code"); }
  if(!is_numeric($cvv)) { return nonStringError("cvv code"); } //Integer Error in this case but not a big deal
  if(!preg_match("/^[0-9]{3}$/", $cvv) || !test_input($cvv) || strlen($cvv) != 3)
  {
    return "<p>The CCV Code you have entered did not match the required format or was not a 3 character code.</p>";
  }

  return true;
}

function validateMonth($month)
{
  global $validMonths;
  if(!isset($month)) { return emptyDataError("expiry-month"); }
  if(!is_string($month)) { return nonStringError("expiry-month"); }
  if(!preg_match("/^[a-zA-Z]*$/", $month) || !test_input($month) || strlen($month) > 9 || strlen($month) < 3)
  {
    return "<p>The month you have entered did not match the required format or was too long.</p>";
  }
  if(!in_array(strtolower($month), $validMonths))
  {
    return "<p>The month you have entered was not a real month.</p>";
  }

  return true;
}

function validateYear($year)
{
  if(!isset($year)) { return emptyDataError("expiry-year"); }
  if(!is_numeric($year)) { return nonStringError("expiry-year"); } //Integer Error in this case but not a big deal
  if(!preg_match("/^[0-9]{4}$/", $year) || !test_input($year) || strlen($year) != 4)
  {
    return "<p>The CCV Code you have entered did not match the required format or was not a 3 character code.</p>";
  }
  if($year < date("Y")) { return "<p>The expiry-year that you have entered is before " . date("Y") . ", you may have entered the wrong year or your card may be expired.</p>"; }
  if($year > date("Y") + 6) { return "<p>Thats a pretty advanced banking system you must have to be buying a bank card with an expiry-year that far into the future.</p>"; }

  return true;
}

function validateTerms($terms)
{
  if(!isset($terms)) { return emptyDataError("expiry-month"); }
  if(!is_string($terms)) { return nonStringError("expiry-month"); }
  if(!preg_match("/^[a-zA-Z]{2}$/", $terms) || !test_input($terms) || strlen($terms) != 2 || strtolower($terms) != "on")
  {
    return "<p>You forgot to accept our terms and conditions to signup, please go back to the registration form and make sure you read the <a href=''>Privace Policy and accept the agreament.</p>";
  }

  return true;
}

function validateInteger($int) //Can also use the native function is_integer() instead
{
  if (filter_var($int, FILTER_VALIDATE_INT)) { return true; }
  return false;
}

function validateKeyCode($keyCode)
{
  if(!isset($keyCode)) { return emptyDataError("key"); }
  if(!is_string($keyCode)) { return nonStringError("key"); }
  if (!preg_match("/^([a-zA-Z0-9]+)$/", $keyCode) || !test_input($keyCode) || strlen($keyCode) > 150)
  {
    return "<p>The key code that was sent to the server did not match the required format for being a valid key code.</p>";
  }

  return true;
}
?>
