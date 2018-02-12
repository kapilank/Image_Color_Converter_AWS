<?php
session_start();
?>

<html>
<head>
<title>Welcome</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<style>
</style>
</head>
<body>
<div class="container">
<div class="jumbotron" ><h1><center>SQS Dashboard</center></h1></div>
<div>
  <h1><center> Please select an option</center></h1>
</div>
<div class><center>
<h2><span class="glyphicon glyphicon-home"></span> <a class="active" href="/index.php">Home</a></h2>
<h2><span class="glyphicon glyphicon-camera"></span> <a href="/gallery.php">Gallery</a></h2>
<h2><span class="glyphicon glyphicon-cloud-upload"></span> <a href="/upload.php">Upload</a></h2>
<h2><span class="glyphicons glyphicons-dashboard"></span><a href="/DatabaseUI.php">Database Dashboard</a></h2>
<h2><span class="glyphicons glyphicons-dashboard"><a href="/SQSDashboardUI.php">SQS Dashboard</a></h2>
<h2><span class="glyphicons glyphicons-dashboard"><a href="/CPUUtilizationUI.php">CPU Utilization Dashboard</a></h2>

<?php
 function getLink() {
    $checkProto = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
    return $checkProto.'://'.$_SERVER['HTTP_HOST'];
}
$link =  getLink();
?>
<h3>Number of Messages Sent </h3>
<canvas id="bar-chart" width="50" height="20"></canvas>
<h3>Number of Jobs Visible </h3>
<canvas id="bar-chart2" width="50" height="20"></canvas>
<script>
var bar_chart = document.getElementById("bar-chart");
var bar_chart_2 = document.getElementById("bar-chart2");
$(document).ready(function(){
  var urlLink = '<?php echo $link?>'
  $.ajax({
    url: urlLink+"/SQSQueueElements.php",
    method: "GET",
    success: function(tempData) {
        var data = JSON.parse(tempData);
        var noOfSqsJobs = data.sum;
        var time = data.timestamp;
        var bar_data = {
            labels: time,
            datasets : [
                {
                    label: 'Number of Messages',
                    data: noOfSqsJobs
                }
            ]
        };
        var barchart = new Chart(bar_chart, {
            type: 'bar',
            data: bar_data
        });
    },
    error: function(data) {
        console.log(data);
    }
    });
    $.ajax({
      url: urlLink+"/SQSQueueVisibleJobs.php",
      method: "GET",
      success: function(tempData) {
          var data = JSON.parse(tempData);
          var noOfSqsJobs = data.maximum;
          var time = data.timestamp;
          var bar_data = {
              labels: time,
              datasets : [
                  {
                      label: 'Number of Visible Messages',
                      data: noOfSqsJobs
                  }
              ]
          };
          var barchart = new Chart(bar_chart_2, {
              type: 'bar',
              data: bar_data
          });
      },
      error: function(data) {
          console.log(data);
      }
      });
});
</script>
</center>
</div>
</div>
</body>
</html>
