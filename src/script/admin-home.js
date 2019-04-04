google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

$(document).ready( function(e)
{
  setInterval(function(){
    drawChart();
  }, 60000);
});

function drawChart()
{
  var results = $.getJSON("admin-home-ajax.php");

  results.done( function(data) {
    var chart1 = new Array(data.length);
    var chart2 = new Array(data.length);

    for(var i = 0; i < data.length; i++) {
        chart1[i] = [data[i][0], data[i][1], data[i][3]];
        chart2[i] = [data[i][0], data[i][2]];
    }

    drawChart1(chart1);
    drawChart2(chart2);

    //var date = new Date();
    //$("#side-bar-date").html(date.getFullYear() + "/" + date.getMonth() + "/" + date.getDay());
    $("#total-orders-em").html("<em>" + data[data.length-1][1] + "</em> orders today");
    $("#total-revenue-em").html("<em>$" + data[data.length-1][2] + "</em> total revenue today");
    $("#total-items-sold-em").html("<em>" + data[data.length-1][3] + "</em> items sold today");
  });

  results.fail( function(jqXHR) {
    console.log("there was an error in updating chart 1 and 2");
  });


  var results2 = $.getJSON("admin-home-ajax-2.php");

  results2.done( function(data) {
    var chart3 = new Array(8);
    chart3[0] = [data[0][0], data[1][0]];

    for(var i = 1; i < data[0].length; i++) {
      chart3[i] = [data[0][i], data[1][i]];
    }
    drawChart4(chart3);
  });

  results2.fail( function(jqXHR) {
    console.log("there was an error in updating chart 3");
  });
}

function drawChart1(data)
{
  var data = google.visualization.arrayToDataTable(data);

  var options = {
    title: 'Sales by Orders and Items Sold',
    hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
    vAxis: {minValue: 0}
  };

  var chart = new google.visualization.AreaChart(document.getElementById('chart1_div'));
  chart.draw(data, options);
}

function drawChart2(data)
{
  var data = google.visualization.arrayToDataTable(data);

  var options = {
    title: 'Sales by Revenue',
    hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
    vAxis: {minValue: 0}
  };

  //Draw Chart 2
  var chart = new google.visualization.AreaChart(document.getElementById('chart2_div'));
  chart.draw(data, options);
}

function drawChart4(data)
{
  var data = google.visualization.arrayToDataTable(data);

  var options = {
    title: 'Total Sales by Product Types',
    is3D: true,
  };

  var chart = new google.visualization.PieChart(document.getElementById('piechart'));

  chart.draw(data, options);
}
