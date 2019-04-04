<?php
include_once "include_files/session.php";
include_once "include_files/orderProcesses.php";

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
   <title>H.E.L.P. Admin - Home</title>
   <meta name="description" content="">
   <meta name="viewport" id="myViewPort" content="width=device-width, initial-scale=1">

   <link rel="stylesheet" href="css/admin-home.css" >
   <link rel="stylesheet" href="css/reset.css">
   <link rel="stylesheet" href="css/nav.css">
   <link rel="stylesheet" href="css/footer.css">

   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
   <script type="text/Javascript">
     window.jQuerry || document.write("<script src=\"script/jquery-3.3.1.js\"><\/script>");
   </script>
   <script type="text/JavaScript" src="script/validation.js"></script>
   <script type="text/JavaScript" src="script/admin-nav.js"></script>
   <script type="text/JavaScript" src="script/admin-home.js"></script>
</head>

<body>
  <!-- Top Menu Bar -->
  <?php include_once "include_files/admin-nav.php"; ?>

  <div id="sidebar">
    <?php
    $today = date("Y-m-d",time()-(9*60*60));
    echo "<span id='side-bar-date'>" . str_replace("-", "/",$today) . "</span>";
    $orders = getAllOrdersBetween($today, $today.' 23:59:59');

    $totalEarningsToday = 0;
    $totalItemsSold = 0;
    if(is_array($orders)) {
      foreach($orders as $order) {
        $totalEarningsToday += $order[1];

        //TODO: get all inorders for order (then add all quantities)
        $inorders = getAllInOrderForOrder($order[0]);
        if(is_array($inorders)) {
          foreach($inorders as $inorder) {
            $totalItemsSold += $inorder[2];
          }
        }
      }
    }
    ?>
    <h2>Quick Stats</h2>
    <ul>
      <li id="total-orders-em"><em>
        <?php
        if(is_array($orders)) { echo sizeof($orders); }
        else { echo $orders; }
        ?>
      </em> orders today </li>
      <li id="total-revenue-em"><em>$<?php echo number_format($totalEarningsToday, 2, '.', ', '); ?></em> total revenue today </li>
      <li id="total-items-sold-em"><em><?php echo $totalItemsSold; ?></em> items sold today </li>
    </ul>
  </div>

  <div id="charts">
    <figure class="chart">
      <div id="chart1_div" class="google-chart"></div>
    </figure>

    <figure class="chart">
      <div id="chart2_div" class="google-chart"></div>
    </figure>

    <figure class="chart">
      <div id="piechart" class = "google-piechart"></div>
       <!--<img src="assets/images/sample-pie-chart.JPG" alt="Weekly Sales by Product Types" />-->
       <!--<figcaption>Sales by Product Types (Sep 30 to Oct 6)</figcaption>-->
    </figure>
  </div>

  <!--Footer-->
  <footer>
    <?php include_once "include_files/footer.html"; ?>
  </footer>

</body>
</html>
