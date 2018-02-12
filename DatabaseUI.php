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
<div class="jumbotron" ><h1><center>Database Dashboard</center></h1></div>
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
<h3 id="total"></h3>
<canvas id="pie-chart" width="50" height="10"></canvas>
<script>
var pie_chart = document.getElementById("pie-chart");
$(document).ready(function(){
  var urlLink = '<?php echo $link?>'
  $.ajax({
    url: urlLink+"/JobStatus.php",
    method: "GET",
    success: function(tempData) {
       var data = JSON.parse(tempData);
        var jobsCompleted = 0;
        var jobsIncomplete = 0;
        for ( var i=0 ; i<data.length ; i++) {
            var data_status = data[i];
            if(data_status.status == '1' ){
                jobsCompleted = jobsCompleted+1;
            }
            else {
                jobsIncomplete = jobsIncomplete +1 ;
            }
        }
        var totaljobs= jobsCompleted+jobsIncomplete;
        document.getElementById("total").innerHTML="The total number of jobs: "+totaljobs;
        var pie_data = {
            labels: ["Completed-Jobs", "Incomplete-Jobs"],
            datasets : [
                {
                    label: 'Job Status',
                    data: [jobsCompleted, jobsIncomplete]
                }
            ]
        };
        var piechart = new Chart(pie_chart, {
            type: 'pie',
            data: pie_data
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
