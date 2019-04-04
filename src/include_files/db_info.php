<?php
/*define("HOST", "localhost");
define("DATABASE", "project_drugcartel");
define("USER", "webuser");
define("PASSWORD", "P@ssw0rd");*/

define("HOST", "cosc304.ok.ubc.ca");
define("DATABASE", "db_btissera");
define("USER", "btissera");
define("PASSWORD", "37615168");

/*$user = "btissera";
$password = "37615168";
$database = "db_" . $user;
$host = "cosc304.ok.ubc.ca";*/

$connection;
$results;

$validTables = array("picture", "administrator", "customer", "mastercard", "visa", "product", "category", "review", "cart", "orders", "inorder", "categoryincludes");
$validKeyFields = array("pic_id", "email", "bill_id", "pid", "cateName", "orderid");

function connect()
{
  global $connection;
  if(isset($connection)) { return true; }

  $connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

  $error = mysqli_connect_error();
  if($error != null) { return false; }
  else { return true; }
}

function close()
{
  global $connection;
  global $results;
  if($connection != null) { mysqli_close($connection); $connection = null; }
  if($results != null) { mysqli_free_result($results); $results = null; }
}

function getNextID($table, $keyField)
{
  global $connection;
  global $results;
  global $validTables;
  global $validKeyFields;

  if(!connect())
  {
    close();
    return "<p>Unable to connect to database!</p>";
  }

  $table = mysqli_real_escape_string($connection, $table);
  $keyField = mysqli_real_escape_string($connection, $keyField);

  if(!isset($table) || !in_array($table, $validTables))
  {
      return "<p>In searching for a new ID for you, the databased seemed to have recieved information that was entered or passed incorrectly. If the error continues to persit please contact H.E.L.P. for help. (1)</p>";
  }
  if(!isset($keyField) || !in_array($keyField, $validKeyFields))
  {
      return "<p>In searching for a new ID for you, the databased seemed to have recieved information that was entered or passed incorrectly. If the error continues to persit please contact H.E.L.P. for help. (2)</p>";
  }

  $sql = "SELECT MAX($keyField) FROM $table;";
  $results = mysqli_query($connection, $sql);
  $row = mysqli_fetch_assoc($results);

  if (isset($row["MAX($keyField)"]))
  {
      return $row["MAX($keyField)"] + 1;
  }
  mysqli_free_result($results);
  $results = null;
  return 0;
}
?>
