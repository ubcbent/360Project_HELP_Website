<?php
include_once "include_files/session.php";
include_once "include_files/orderProcesses.php";

startSession();

if(!isset($_SESSION['admin']))
{
  if(!$_SESSION['admin'] == "admin") redirect("index.php");
}

//TODO: Loop through each day for the week and  Get {totalOrders, totalRevenue, totalItemsSold}
$totals = array(array('Day', 'Orders', 'Revenue', 'Items Sold'));

$todayTime = time() - (9*60*60);
$today = date("Y-m-d", $todayTime);

for($i = 7; $i >= 0; $i--) {

  $dayTime = $todayTime - $i*(60*60*24);
  $day = date("Y-m-d", $dayTime);
  $orders = getAllOrdersBetween($day, $day.' 23:59:59');

  $totalEarningsToday = 0;
  $totalItemsSoldToday = 0;
  if(is_array($orders)) {

    foreach($orders as $order) {
      $totalEarningsToday += $order[1];

      //TODO: get all inorders for order (then add all quantities)
      $inorders = getAllInOrderForOrder($order[0]);
      if(is_array($inorders)) {

        foreach($inorders as $inorder) {
          $totalItemsSoldToday += $inorder[2];
        }
      }
    }
  }

  array_push($totals, array(date("d", $dayTime), sizeof($orders), $totalEarningsToday, $totalItemsSoldToday));
  //echo "{day: $i}"
}
/*
[['Day', 'Orders', 'Profit', 'Items Sold'],
['07',    1000,      400,          15],
['08',    1170,      460,          17],
['09',     660,     1120,          36],
['10',    1030,      540,          21]]

[['Day', 'Orders', 'Profit', 'Items Sold'], ['07',    1000,      400,          15], ['08',    1170,      460,          17], ['09',     660,     1120,          36], ['10',    1030,      540,          21]]

//echo json_encode() on array
*/

echo json_encode($totals);

/*foreach($totals as $total) {
    echo json_encode($total);
}*/
?>
