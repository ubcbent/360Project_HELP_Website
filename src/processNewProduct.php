<?php
include_once "include_files/session.php";

startSession();

if(!isset($_SESSION['admin']))
{
  if(!$_SESSION['admin'] == "admin") redirect("index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  exit("Request method must be post!");
}

include_once "include_files/db_info.php";

$connection=mysqli_connect(HOST, USER, PASSWORD, DATABASE);
$error = mysqli_connect_error();
if($error != null){
  echo("<p>Unable to connect to database!</p>");
  die();
}
else
{
    $sql = "SELECT pid FROM product;"; //Get all pid currently in the database
    $results = mysqli_query($connection, $sql);
    /*$pidArray1 = mysqli_fetch_all($results, MYSQLI_NUM);
    $pidArray2 = array(); //Create a new array to store all the pids being used in database
    for ($x = 0; $x < count($pidArray1); $x++){
      array_push($pidArray2, $pidArray1[$x][0]);
    }*/
	$pidArray2 = array();
	while(($row = mysqli_fetch_row($results))!=null){
		array_push($pidArray2,$row[0]);
	}

    $nextPid = 1;
    $pidFound = false;
    while($pidFound==false){ //Find the pid for the new product
      if (in_array($nextPid, $pidArray2)){
        //echo "This pid $nextPid is already used. ";
        $nextPid++;
      } else {
        //echo "This is the pid being used, $nextPid";
        $pidFound = true;
      }
    }

    //Register the user
    $sql2 = "INSERT INTO product (pid, drugName, unitPrice, descShort, descLong, stock) VALUES (\"" . $nextPid . "\", \"" . $_POST['drugName'] . "\", \"" . $_POST['unitPrice'] . "\", \"" . $_POST['descShort'] . "\", \"" . $_POST['descLong'] . "\", \"" . $_POST['stock'] . "\");";
    mysqli_query($connection, $sql2);
    if (mysqli_error($connection) == "") {
      echo "New product: " . $_POST['drugName'] . " has been added. ";
    } else {
      echo mysqli_error($connection);
    }

    //Add the categories
    if (isset($_POST['Accessories'])){
      $sql5 = "INSERT INTO categoryincludes (cateName, pid) VALUES (\"Accessories\", \"" . $nextPid . "\");";
      mysqli_query($connection, $sql5);
    }
    if (isset($_POST['Edible'])){
      $sql5 = "INSERT INTO categoryincludes (cateName, pid) VALUES (\"Edible\", \"" . $nextPid . "\");";
      mysqli_query($connection, $sql5);
    }
    if (isset($_POST['Inhalant'])){
      $sql5 = "INSERT INTO categoryincludes (cateName, pid) VALUES (\"Inhalant\", \"" . $nextPid . "\");";
      mysqli_query($connection, $sql5);
    }
    if (isset($_POST['Injectable'])){
      $sql5 = "INSERT INTO categoryincludes (cateName, pid) VALUES (\"Injectable\", \"" . $nextPid . "\");";
      mysqli_query($connection, $sql5);
    }
    if (isset($_POST['Smokeable'])){
      $sql5 = "INSERT INTO categoryincludes (cateName, pid) VALUES (\"Smokeable\", \"" . $nextPid . "\");";
      mysqli_query($connection, $sql5);
    }
    if (isset($_POST['Snortable'])){
      $sql5 = "INSERT INTO categoryincludes (cateName, pid) VALUES (\"Snortable\", \"" . $nextPid . "\");";
      mysqli_query($connection, $sql5);
    }
    if (isset($_POST['Used'])){
      $sql5 = "INSERT INTO categoryincludes (cateName, pid) VALUES (\"Used\", \"" . $nextPid . "\");";
      mysqli_query($connection, $sql5);
    }

    //Choose a picture id based on availbility
    $sql3 = "SELECT pic_id FROM picture;";
    $results3 = mysqli_query($connection, $sql3);
    /*$pic_idArray1 = mysqli_fetch_all($results3, MYSQLI_NUM);
    $pic_idArray2 = array();
    for ($x = 0; $x < count($pic_idArray1); $x++){
      array_push($pic_idArray2, $pic_idArray1[$x][0]);
    }*/
	$pic_idArray2 = array();
	while(($row = mysqli_fetch_row($results3))!=null){
		array_push($pic_idArray2,$row[0]);
	}
    $nextpic_id = 1;
    $pic_idFound = false;
    while($pic_idFound==false){ //Find the pid for the new product
      if (in_array($nextpic_id, $pic_idArray2)){
        $nextpic_id++;
      } else {
        $pic_idFound = true;
      }
    }

    if(isset($_FILES["productImage"])){
      $photo = $_FILES["productImage"];
      $imageName = basename($photo["name"]);
      $imageType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
      $imageSize = $photo["size"];
      $uploadOk = true;

      if($imageType != "jpg" && $imageType != "jpeg" && $imageType != "png" && $imageType != "gif")
      {
        $uploadOk = false;
        return "<p>The image file type you have selected was not an allowed image type, please select an image file that is one of: JPG, JPEG, PNG, or GIF.</p>";
      }

      if ($imageSize > 500000)
      {
        $uploadOk = false;
        return "<p>Sorry but the image file you tried to upload was too big.</p>";
      }

      //Correct image type, now upload to the database:
      if ($uploadOk) {
        $imagedata = file_get_contents($_FILES['productImage']['tmp_name']); //store the contents of the files in memory in preparation for upload
        $sql = "INSERT INTO picture (pic_id, contentType, image) VALUES(?,?,?);";
        $stmt = mysqli_stmt_init($connection);
        mysqli_stmt_prepare($stmt, $sql); // register the query

        $null = NULL;
        mysqli_stmt_bind_param($stmt, "isb", $nextpic_id, $imageType, $null);
        mysqli_stmt_send_long_data($stmt, 2, $imagedata); // This sends the binary data to the third variable location in the prepared statement

        $result = mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt)); //run the statement
        mysqli_stmt_close($stmt);

        //Add the pic_id to the product table
        $sql4 = "UPDATE product SET pic_id = $nextpic_id WHERE pid = $nextPid;";
        mysqli_query($connection, $sql4);
        if (mysqli_error($connection) == "") {
          echo "The uploaded picture is now associated with the product. ";
        } else {
          echo mysqli_error($connection);
        }
      }
    }

    mysqli_free_result($results);
    mysqli_close($connection);
}
redirect("admin-products.php");
?>
