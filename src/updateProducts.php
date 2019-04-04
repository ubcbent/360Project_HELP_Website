<?php
include_once "include_files/session.php";

startSession();

if(!isset($_SESSION['admin']))
{
  if(!$_SESSION['admin'] == "admin") redirect("index.php");
}

$prevURL = basename($_SERVER['HTTP_REFERER']);

if (isset($_SESSION['pid'])){
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
//mysqli_report(MYSQLI_REPORT_ALL);
    $sql = "UPDATE product SET drugName = \"".$_POST['drugName']."\" , descShort = \"".$_POST['descShort']."\" , descLong = \"".$_POST['descLong']."\" , unitPrice = \"".$_POST['unitPrice']."\" , stock = \"".$_POST['stock']."\" WHERE pid = ".$_SESSION['pid'].";";
    mysqli_query($connection, $sql);

    //Select the categories
    $pid = $_SESSION['pid'];
    $sql5 = "SELECT * FROM categoryincludes WHERE pid = $pid;";
    $results5 = mysqli_query($connection, $sql5);

    $sql6 = "SELECT * FROM category WHERE cateName NOT IN (SELECT cateName FROM categoryincludes WHERE pid = $pid);";
    $results6 = mysqli_query($connection, $sql6);

    while ($row5 = mysqli_fetch_assoc($results5)){ //Originally selected category
      $cateName = $row5['cateName'];
      if(!isset($_POST["$cateName"])){
        $sql7 = "DELETE FROM categoryincludes WHERE pid = $pid AND cateName = \"$cateName\";";
        mysqli_query($connection, $sql7);
      }
    }
    while ($row6 = mysqli_fetch_assoc($results6)){ //Originall not selected category
      $cateName = $row6['cateName'];
      if(isset($_POST["$cateName"])){
        $sql7 = "INSERT INTO categoryincludes (cateName, pid) VALUES (\"$cateName\", $pid);";
        mysqli_query($connection, $sql7);
      }
    }
    $filename = basename($_FILES["productImage"]['name']);
    //echo "<p>Test:" . ("" == $filename)  . "Test</p>";

    if($filename == ""){
      redirect("admin-products.php");
    }
    else if (isset($_FILES["productImage"])){
      $photo = $_FILES["productImage"];
      $imageName = basename($photo["name"]);
      $imageType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
      $imageSize = $photo["size"];
      $uploadOk = true;

      if($imageType != "jpg" && $imageType != "jpeg" && $imageType != "png" && $imageType != "gif")
      {
        $uploadOk = false;
        exit("<p>The image file type you have selected was not an allowed image type, please select an image file that is one of: JPG, JPEG, PNG, or GIF.</p>");
      }

      if ($imageSize > 500000)
      {
        $uploadOk = false;
        exit("<p>Sorry but the image file you tried to upload was too big.</p>");
      }

      //Correct image type, now upload to the database:
      if ($uploadOk) {
        $imagedata = file_get_contents($_FILES['productImage']['tmp_name']); //store the contents of the files in memory in preparation for upload
        $sql = "UPDATE picture SET contentType = ?, image = ? WHERE pic_id = (SELECT pic_id FROM product WHERE pid = ?);";
        $stmt = mysqli_stmt_init($connection);
        mysqli_stmt_prepare($stmt, $sql); // register the query

        $null = NULL;
        mysqli_stmt_bind_param($stmt, "sbi", $imageType, $null, $pid);
        mysqli_stmt_send_long_data($stmt, 1, $imagedata); // This sends the binary data to the third variable location in the prepared statement

        $result = mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt)); //run the statement
        mysqli_stmt_close($stmt);

      }
    }
  }
  mysqli_free_result($results);
  mysqli_close($connection);
  if(isset($_FILES["productImage"])) { unlink($_FILES["productImage"]); }
}
redirect("admin-products.php");
?>
