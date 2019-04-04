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
  <title>H.E.L.P. Admin - View Comments</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/nav.css">
  <link rel="stylesheet" href="css/footer.css">
  <link rel="stylesheet" href="css/admin-comments.css">

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

  <div id="main">
    <form method="get" action="admin-comments.php">
      <input type="text" placeholder="Search Comments.." name="search">
      <button type="submit">Submit</button>
    </form>
    <h1>View Comments</h1>
    <table>
      <tr>
        <th>Time Posted</th>
        <th>Review Content</th>
        <th>Rating</th>
        <th>User Email</th>
        <th>Product ID</th>
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
          $sql = "SELECT timeposted, revContent, rating, email, pid FROM review;";
          if(isset($_GET['search'])){
            $sql = "SELECT timeposted, revContent, rating, email, pid FROM review WHERE revContent LIKE '%".$_GET["search"]."%' OR email LIKE '%".$_GET["search"]."%' OR timeposted LIKE '%".$_GET["search"]."%';";
          }

          $results = mysqli_query($connection, $sql);
          if (mysqli_num_rows($results) == 0) {
            echo "<tr><td colspan=\"5\">No reviews found with given criteria.</td></tr>";
          }
          while ($row = mysqli_fetch_assoc($results))
          {
            $reviewTimePosted = $row['timeposted'];
            $reviewContent = $row['revContent'];
            $reviewRating = $row['rating'];
            $reviewEmail = $row['email'];
            $reviewPid = $row['pid'];
            echo <<<EOD
              <tr>
                <td>$reviewTimePosted</td>
                <td>$reviewContent</td>
                <td>$reviewRating</td>
                <td><a href="admin-customers.php?search=$reviewEmail">$reviewEmail</a></td>
                <td>$reviewPid</td>
                <td><a href="deleteComments.php?time=$reviewTimePosted&email=$reviewEmail&pid=$reviewPid">Delete</a></td>
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
