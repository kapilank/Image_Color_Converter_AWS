<?php
require 'vendor/autoload.php';
use Aws\Sqs\SqsClient;
use Aws\Sns\SnsClient;
use Aws\Rds\RdsClient;
$sqsClient = SqsClient::factory(array(
       'version' => 'latest',
      'region'  => 'us-west-2'
));
$rdsClient = RdsClient::factory(array(
'version' => 'latest',
'region'  => 'us-west-2'
));
$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);
$snsClient = new Aws\Sns\SnsClient([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);
$queueUrl = $sqsClient->listQueues(array(
 'QueueNamePrefix' => 'kkumanan',
));
$rdsInstance = $rdsClient->describeDBInstances(array(
    'DBInstanceIdentifier' => 'itmo544-kkumanan-mysqldb',
));
$sqsInstance = $sqsClient->receiveMessage(array(
    'QueueUrl' => $queueUrl['QueueUrls'][0],
    'VisibilityTimeout' => 100,
  'MaxNumberOfMessages' => 1,
));
$sqsBody=$sqsInstance['Messages'][0]['Body'];
$queueElementToDelete=$sqsInstance['Messages'][0]['ReceiptHandle'];
$arnTopicName = $snsClient->createTopic(array(
    'Name' => 'kkumanan--sns-topic',
));
if (!empty($sqsBody)){
$RDSUrl="";
foreach ($rdsInstance['DBInstances'] as $endPoint1)
{
    foreach($endPoint1['Endpoint'] as $endPoint2)
        {
        $RDSUrl=$endPoint2;
                break;
        }
}
$conn = mysqli_connect($RDSUrl,"controller","controllerpass","itmo544mp","3306") or die("Error " . mysqli_error($link));
$sqlSelectStatement = "SELECT s3_raw_url,s3_finished_url FROM records where s3_raw_url='$sqsBody'";
$sqlSelectResult = $conn->query($sqlSelectStatement);
$s3RawUrl="";
while($eachRow = $sqlSelectResult->fetch_assoc()){
                $s3RawUrl=$eachRow["s3_raw_url"];
}
$conn->close();
$imageIdentifier = '';
$imageFormatChecker = substr($s3RawUrl, -3);
if ($imageFormatChecker == 'png' || $imageFormatChecker == 'PNG')
        {
        $imageIdentifier = imagecreatefrompng($s3RawUrl);
        }
  else
        {
        $imageIdentifier = imagecreatefromjpeg($s3RawUrl);
        }
$nameDelimiter = strripos($s3RawUrl, "/") + 1;
$imageNameIdentifier = substr($s3RawUrl, $nameDelimiter, strlen($s3RawUrl));
ImageFilter($imageIdentifier, IMG_FILTER_GRAYSCALE);
$tmp = "/tmp/$imageNameIdentifier";
imagepng($imageIdentifier, $tmp);
imagedestroy($imageIdentifier);
$resulTempVariable = $s3->putObject(array(
             'Bucket'=>'kkumanan-bucket-2',
             'Key' =>  $imageNameIdentifier,
             'SourceFile' => $tmp,
             'region' => 'us-west-2',
              'ACL'    => 'public-read'
));
$s3FinishedUrl=$resulTempVariable['ObjectURL'];
$RDSfinsihedUrlUpdate="";
foreach ($rdsInstance['DBInstances'] as $endPoint1)
{
    foreach($endPoint1['Endpoint'] as $endPoint2)
        {
        $RDSfinsihedUrlUpdate=$endPoint2;
                break;
        }
}
$connForUpdate = mysqli_connect($RDSfinsihedUrlUpdate,"controller","controllerpass","itmo544mp","3306") or die("Error " . mysqli_error($link));
$sqlSelectStatement = "UPDATE records SET s3_finished_url='$s3FinishedUrl',status=1 WHERE s3_raw_url='$sqsBody'";
$sqlSelectResult = $connForUpdate->query($sqlSelectStatement);
$connForUpdate->close();
$pushNotification = $snsClient->publish(array(
    'TopicArn' => $arnTopicName['TopicArn'],
    'Message' => 'Image has been processed. Check your Gallery.',
    'Subject' => 'Image Processed',
));
$deleteQueueElement = $sqsClient->deleteMessage(array(
    'QueueUrl' => $queueUrl['QueueUrls'][0],
    'ReceiptHandle' => $queueElementToDelete,
));
}
?>
