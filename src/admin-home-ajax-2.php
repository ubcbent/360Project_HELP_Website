<?php
include_once "include_files/session.php";
include_once "include_files/orderProcesses.php";

startSession();

if(!isset($_SESSION['admin']))
{
  if(!$_SESSION['admin'] == "admin") redirect("index.php");
}

$resultsArray = array(2);
$resultsArray[0] = array("Category", "Edible", "Smokeable", "Injectable", "Inhalant", "Snortable", "Accessories", "Used");
$categoryTotals = ["Items Sold", 0, 0, 0, 0, 0, 0, 0];

$orders = getAllOrders();
if(is_array($orders)) {

  foreach($orders as $order) {
    $inorders = getAllInOrderForOrder($order[0]);
    if(is_array($inorders)) {

      foreach($inorders as $inorder) {
          $categoryIncludes = getAllInCategoryForProduct($inorder[1]);
          if(is_array($categoryIncludes)){

            foreach($categoryIncludes as $category)
              switch($category[0])
              {
                case "Edible":
                  $categoryTotals[1] += $inorder[2];
                  break;
                case "Smokeable":
                  $categoryTotals[2] += $inorder[2];
                  break;
                case "Injectable":
                  $categoryTotals[3] += $inorder[2];
                  break;
                case "Inhalent":
                  $categoryTotals[4] += $inorder[2];
                  break;
                case "Snortable":
                  $categoryTotals[5] += $inorder[2];
                  break;
                case "Accessories":
                  $categoryTotals[6] += $inorder[2];
                  break;
                case "Used":
                  $categoryTotals[7] += $inorder[2];
                  break;
              }
          }
      }
    }
  }
}

$resultsArray[1] = $categoryTotals;

/*
[['Category', 'Items Sold'],
['07',              15],
['08',              17],
['09',              36],
['10',              21]]

//echo json_encode() on array
*/

echo json_encode($resultsArray);

?>
