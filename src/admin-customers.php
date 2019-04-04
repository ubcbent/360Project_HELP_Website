<?php
include_once "include_files/session.php";

startSession();

if(!isset($_SESSION['admin']))
{
  if(!$_SESSION['admin'] == "admin") redirect("index.php");
}
?>

<!DOCTYPE html>
<html>

<head lang="en">
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
   <meta name="viewport" id="myViewPort" content="width=device-width, initial-scale=1">
   <title>H.E.L.P. Admin - Browse Customers</title>
   <link rel="stylesheet" href="css/admin-customers.css" />
   <link rel="stylesheet" href="css/reset.css">
   <link rel="stylesheet" href="css/nav.css">
   <link rel="stylesheet" href="css/footer.css">

   <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
   <script type="text/Javascript">
     window.jQuerry || document.write("<script src=\"script/jquery-3.3.1.js\"><\/script>");
   </script>
   <script type="text/JavaScript" src="script/validation.js"></script>
   <script type="text/JavaScript" src="script/admin-nav.js"></script>
</head>

<body>
  <!-- Top Menu Bar -->
  <?php include_once "include_files/admin-nav.php"; ?>

  <!--Item Details-->
  <div id="main">
    <form method="get" action="admin-customers.php">
      <input type="text" placeholder="Search Customer.." name="search">
      <button type="submit">Submit</button>
    </form>
    <h1>Browse Customers</h1>
    <table>
    <tr>
      <th>Email</th>
      <th>Full Name</th>
      <th>Gender</th>
      <th>Picture ID</th>
      <th>Billing Type</th>
      <th>Status</th>
      <th colspan="2">Actions</th>
    </tr>
      <?php
        include_once "include_files/db_info.php";
        $connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
        $error = mysqli_connect_error();
        if($error != null)
        {
          $output = "<p>Unable to connect to database!</p>";
          exit($output);
        }
        else
        {
          $sql = "SELECT email, fullName, gender, pic_id, billingType FROM customer;";
          if(isset($_GET['search'])){
            $sql = "SELECT email, fullName, gender, pic_id, billingType FROM customer WHERE fullName LIKE '%".$_GET["search"]."%' OR email LIKE '%".$_GET["search"]."%';";
          }

          $results = mysqli_query($connection, $sql);
          if (mysqli_num_rows($results) == 0) {
            echo "<tr><td colspan=\"5\">No active users found with given criteria.</td></tr>";
          }
          while ($row = mysqli_fetch_assoc($results))
          {
            $userEmail = $row['email'];
            $userFullName = $row['fullName'];
            $userGender = $row['gender'];
            $userPicId = $row['pic_id'];
            $userBillingType = $row['billingType'];
            echo <<<EOD
              <tr>
                <td>$userEmail</td>
                <td>$userFullName</td>
                <td>$userGender</td>
                <td>$userPicId</td>
                <td>$userBillingType</td>
                <td>Active</td>
                <td><a href="admin-comments.php?search=$userEmail">View Comments</a></td>
                <td><a href="admin-orders.php?search=$userEmail">View Orders</a></td>
                <td><a href="disableUser.php?id=$userEmail">Disable User</a></td>
              </tr>
EOD;
            }

          $sql2 = "SELECT email, fullName, gender, pic_id, billingType FROM disabledCustomer;";
          if(isset($_GET['search'])){
            $sql2 = "SELECT email, fullName, gender, pic_id, billingType FROM disabledCustomer WHERE fullName LIKE '%".$_GET["search"]."%' OR email LIKE '%".$_GET["search"]."%';";
          }

          $results2 = mysqli_query($connection, $sql2);
          if (mysqli_num_rows($results2) == 0) {
            echo "<tr><td colspan=\"5\">No disabled users found with given criteria.</td></tr>";
          }
          while ($row = mysqli_fetch_assoc($results2))
          {
            $userEmail = $row['email'];
            $userFullName = $row['fullName'];
            $userGender = $row['gender'];
            $userPicId = $row['pic_id'];
            $userBillingType = $row['billingType'];
            echo <<<EOD
              <tr>
                <td>$userEmail</td>
                <td>$userFullName</td>
                <td>$userGender</td>
                <td>$userPicId</td>
                <td>$userBillingType</td>
                <td>Disabled</td>
                <td colspan="3"><a href="enableUser.php?id=$userEmail">Enable User</a></td>
              </tr>
EOD;
            }
          }

          mysqli_free_result($results);
          mysqli_close($connection);
      ?>
    </table>
  </div>

<!--Footer-->
<footer>
  <?php include_once "include_files/footer.html" ?>
</footer>

</body>
</html>
