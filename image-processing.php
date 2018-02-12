<?php
session_start();
require 'vendor/autoload.php';
use Aws\Sqs\SqsClient;
use Aws\Rds\RdsClient;
$sqsclient = SqsClient::factory(array(
       'version' => 'latest',
      'region'  => 'us-west-2'
));
$client = RdsClient::factory(array(
        'version' => 'latest',
        'region' => 'us-west-2'
));
$s3 = new Aws\S3\S3Client(['version' => 'latest', 'region' => 'us-west-2']);
$result = $client->describeDBInstances(array(
        'DBInstanceIdentifier' => 'itmo544-kkumanan-mysqldb',
));
$endpoint = "";
$url = "";
foreach($result['DBInstances'] as $ep)
        {
        foreach($ep['Endpoint'] as $endpointurl)
                {
                $url = $endpointurl;
                break;
                }
        }
$conn = mysqli_connect($url, "controller", "controllerpass", "itmo544mp", "3306") or die("Error " . mysqli_error($link));
$total = count($_FILES["fileToUpload"]["name"]);
for($a =0 ; $a < $total ; $a++)
{
$name[] = $_FILES["fileToUpload"]["name"][$a];
$tmp[] = $_FILES['fileToUpload']['tmp_name'][$a];
}
$totalNumberOfFiles = count($tmp);
for($b = 0 ; $b < $totalNumberOfFiles ; $b++){
$sourceimageput[] = $s3->putObject(array(
        'Bucket' => 'kkumanan-bucket-1',
        'Key' => $name[$b],
        'SourceFile' => $tmp[$b],
        'region' => 'us-west-2',
        'ACL' => 'public-read'
));
}
$resultImages = count($sourceimageput);
for($c = 0; $c < $resultImages ; $c++){
$imageurl[] = $sourceimageput[$c]['ObjectURL'];
}
$totalURLs = count($imageurl);
for($d = 0; $d<$totalURLs; $d++){
$_SESSION[]['s3-raw'] = $imageurl[$d];
$rawurl[] = $imageurl[$d];
}
for($e=0; $e<$totalURLs ;$e++){
if (!($stmt2 = $conn->prepare("INSERT INTO records (id,email,phone,s3_raw_url,s3_finished_url,status,receipt) VALUES (NULL,?, ?, ?, ?, ?, ?)")))
        {
        }

$stmt = $conn->prepare("INSERT INTO records (email,phone,s3_raw_url,s3_finished_url,status,receipt) VALUES (?, ?, ?, ?, ?, ?)");
$statusnumber = 0;
$stmt->bind_param("ssssss", $email, $phone, $s3_source, $s3_destination, $status, $receipt);
$email = "kapilankumanan@gmail.com";
$phone = "3123425335";
$s3_source = $imageurl[$e];
$s3_destination = 'Yet To Process';
$status = $statusnumber;
$receipt = $imageurl[$e];
$stmt->execute();

$queueUrl = $sqsclient->listQueues(array(
'QueueNamePrefix' => 'kkumanan',
));
$sqsclient->sendMessage(array(
    'QueueUrl'    => $queueUrl['QueueUrls'][0],
    'MessageBody' => $s3_source,
));
}
$stmt->close();
$conn->close();
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
<div class="jumbotron" ><h1><center>Success !</center></h1></div>
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
<div>

<form action="" method='post' enctype="multipart/form-data">
<br />
<br />
<center><h3>Please go to Gallery to view the B&W image</h3>
<br />
<br />
<br />
</center>
</form>
</div>
</div>
</body>
</html>
