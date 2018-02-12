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
<div class="jumbotron" ><h1><center>CPU Utilization Dashboard</center></h1></div>
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
<h3>CPU-Utilization</h3>
<canvas id="line-chart" width="50" height="20"></canvas>
<h3>DiskReadOps</h3>
<canvas id="line-chart2" width="50" height="20"></canvas>
<h3>DiskWriteOps</h3>
<canvas id="line-chart3" width="50" height="20"></canvas>
<h3>DiskReadBytes</h3>
<canvas id="line-chart4" width="50" height="20"></canvas>
<h3>DiskWriteBytes</h3>
<canvas id="line-chart5" width="50" height="20"></canvas>
<h3>NetworkIn</h3>
<canvas id="line-chart6" width="50" height="20"></canvas>
<h3>NetworkOut</h3>
<canvas id="line-chart7" width="50" height="20"></canvas>
<script>
var line_chart = document.getElementById("line-chart");
var line_chart_2 = document.getElementById("line-chart2");
var line_chart_3 = document.getElementById("line-chart3");
var line_chart_4 = document.getElementById("line-chart4");
var line_chart_5 = document.getElementById("line-chart5");
var line_chart_6 = document.getElementById("line-chart6");
var line_chart_7 = document.getElementById("line-chart7");
$(document).ready(function(){
  var urlLink = '<?php echo $link?>'
  var instance = [];
  var timestamp = [];
  var maximum =[];
  var instance2 = [];
  var timestamp2 = [];
  var maximum2 =[];
  var instance3 = [];
  var timestamp3 = [];
  var maximum3 =[];
  var instance4 = [];
  var timestamp4 = [];
  var maximum4 =[];
  var instance5 = [];
  var timestamp5 = [];
  var maximum5 =[];
  var instance6 = [];
  var timestamp6 = [];
  var maximum6 =[];
  var instance7 = [];
  var timestamp7 = [];
  var maximum7 =[];
  $.ajax({
    url: urlLink+"/CPUUtilization1.php",
    method: "GET",
    success: function(tempData) {
        var data = JSON.parse(tempData);
        for(var i= 0; i < data.length ; i++){
            instance.push(data[i].InstanceId);
            timestamp = data[i].timestamp;
            maximum.push(data[i].maximum);
    }
        var line_data = {
            labels: timestamp,
            datasets : [
                {
                    label: 'Instance 1',
                    data: maximum[0]
                },
                {
                      label: 'Instance 2',
                      data: maximum[1]
                  },
                  {
                      label: 'Instance 3',
                      data: maximum[2]
                  }
            ]
        };
        var linechart = new Chart(line_chart, {
            type: 'line',
            data: line_data
        });
    },
    error: function(data) {
        console.log(data);
    }
    });
    $.ajax({
      url: urlLink+"/CPUUtilization2.php",
      method: "GET",
      success: function(tempData2) {
          var data = JSON.parse(tempData2);
          for(var i= 0; i < data.length ; i++){
            instance2.push(data[i].InstanceId);
            timestamp2 = data[i].timestamp;
            maximum2.push(data[i].maximum);
    }
          var line_data = {
              labels: timestamp,
              datasets : [
                {
                    label: 'Instance 1',
                    data: maximum2[0]
                },
                  {
                      label: 'Instance 2',
                      data: maximum2[1]
                  },
                  {
                      label: 'Instance 3',
                      data: maximum2[2]
                  }
              ]
          };
          var linechart = new Chart(line_chart_2, {
              type: 'line',
              data: line_data
          });
      },
      error: function(data) {
          console.log(data);
      }
      });
      $.ajax({
      url: urlLink+"/CPUUtilization3.php",
      method: "GET",
      success: function(tempData3) {
          var data = JSON.parse(tempData3);
          for(var i= 0; i < data.length ; i++){
            instance3.push(data[i].InstanceId);
            timestamp3 = data[i].timestamp;
            maximum3.push(data[i].maximum);
    }
          var line_data = {
              labels: timestamp,
              datasets : [
                {
                    label: 'Instance 1',
                    data: maximum3[0]
                },
                  {
                      label: 'Instance 2',
                      data: maximum3[1]
                  },
                  {
                      label: 'Instance 3',
                      data: maximum3[2]
                  }
              ]
          };
          var linechart = new Chart(line_chart_3, {
              type: 'line',
              data: line_data
          });
      },
      error: function(data) {
          console.log(data);
      }
      });
      $.ajax({
      url: urlLink+"/CPUUtilization4.php",
      method: "GET",
      success: function(tempData4) {
          var data = JSON.parse(tempData4);
          for(var i= 0; i < data.length ; i++){
            instance4.push(data[i].InstanceId);
            timestamp4 = data[i].timestamp;
            maximum4.push(data[i].maximum);
    }
          var line_data = {
              labels: timestamp,
              datasets : [
                {
                    label: 'Instance 1',
                    data: maximum4[0]
                },
                  {
                      label: 'Instance 2',
                      data: maximum4[1]
                  },
                  {
                      label: 'Instance 3',
                      data: maximum4[2]
                  }
              ]
          };
          var linechart = new Chart(line_chart_4, {
              type: 'line',
              data: line_data
          });
      },
      error: function(data) {
          console.log(data);
      }
      });
      $.ajax({
      url: urlLink+"/CPUUtilization5.php",
      method: "GET",
      success: function(tempData5) {
          var data = JSON.parse(tempData5);
          for(var i= 0; i < data.length ; i++){
            instance5.push(data[i].InstanceId);
            timestamp5 = data[i].timestamp;
            maximum5.push(data[i].maximum);
    }
          var line_data = {
              labels: timestamp,
              datasets : [
                {
                    label: 'Instance 1',
                    data: maximum5[0]
                },
                  {
                      label: 'Instance 2',
                      data: maximum5[1]
                  },
                  {
                      label: 'Instance 3',
                      data: maximum5[2]
                  }
              ]
          };
          var linechart = new Chart(line_chart_5, {
              type: 'line',
              data: line_data
          });
      },
      error: function(data) {
          console.log(data);
      }
      });
      $.ajax({
      url: urlLink+"/CPUUtilization6.php",
      method: "GET",
      success: function(tempData6) {
          var data = JSON.parse(tempData6);
          for(var i= 0; i < data.length ; i++){
            instance6.push(data[i].InstanceId);
            timestamp6 = data[i].timestamp;
            maximum6.push(data[i].maximum);
    }
          var line_data = {
              labels: timestamp,
              datasets : [
                {
                    label: 'Instance 1',
                    data: maximum6[0]
                },
                  {
                      label: 'Instance 2',
                      data: maximum6[1]
                  },
                  {
                      label: 'Instance 3',
                      data: maximum6[2]
                  }
              ]
          };
          var linechart = new Chart(line_chart_6, {
              type: 'line',
              data: line_data
          });
      },
      error: function(data) {
          console.log(data);
      }
      });
          $.ajax({
      url: urlLink+"/CPUUtilization7.php",
      method: "GET",
      success: function(tempData7) {
          var data = JSON.parse(tempData7);
          for(var i= 0; i < data.length ; i++){
            instance7.push(data[i].InstanceId);
            timestamp7 = data[i].timestamp;
            maximum7.push(data[i].maximum);
    }
          var line_data = {
              labels: timestamp,
              datasets : [
                {
                    label: 'Instance 1',
                    data: maximum7[0]
                },
                  {
                      label: 'Instance 2',
                      data: maximum7[1]
                  },
                  {
                      label: 'Instance 3',
                      data: maximum7[2]
                  }
              ]
          };
          var linechart = new Chart(line_chart_7, {
              type: 'line',
              data: line_data
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
