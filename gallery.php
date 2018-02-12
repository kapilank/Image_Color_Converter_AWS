<?php
session_start();

require 'vendor/autoload.php';

use Aws\Rds\RdsClient;

$client = RdsClient::factory(array(
'version' => 'latest',
'region'  => 'us-west-2'
));


$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'itmo-544-kkumanan-dbreplica',
));


$endpoint = "";
$url="";

foreach ($result['DBInstances'] as $ep)
{

    foreach($ep['Endpoint'] as $endpointurl)
        {
        $url=$endpointurl;
                break;
        }
}


$link = mysqli_connect($url,"controller","controllerpass","itmo544mp","3306") or die("Error " . mysqli_error($link));


$sqlselect = "SELECT s3_raw_url,s3_finished_url FROM records where status=1";
$resultforselect = $link->query($sqlselect);


?>

<html>
<head>
<title>Uploaded Image</title>
<style>
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
<div class="jumbotron" ><h1><center>Gallery</center></h1></div>
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

</center>
</div>
<div style="margin-left:25%;padding:1px 16px;height:1000px;">
<br>
<br>
<br>
<?php
if ($resultforselect->num_rows > 0) {
    while($row = $resultforselect->fetch_assoc()) {
		$value=$row["s3_raw_url"];
        echo "<a href='$value' class=\"lightbox_trigger\">";

        echo "<img src='$value' height=\"200\" width=\"200\" style=\"margin:0px 20px\" />";

        $valuefinish=$row["s3_finished_url"];
        echo "<a href='$valuefinish' class=\"lightbox_trigger\">";

        echo "<img src='$valuefinish' height=\"200\" width=\"200\"/>";
        echo"<br>";
    }
} else {
    echo "<center><h4>Oops! No Images Found!</h4></center>";
}
$link->close();
?>
</div>
</div>
</body>
</html>
